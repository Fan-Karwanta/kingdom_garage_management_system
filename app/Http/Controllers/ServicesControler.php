<?php

namespace App\Http\Controllers;

use App\Branch;
use App\BranchSetting;
use App\EmailLog;
use App\Holiday;
use App\JobcardDetail;
use App\Notes;
use App\RepairCategory;
use App\Role;
use App\Role_user;
use App\Sale;
use App\Service;
use App\Setting;
use App\tbl_service_images;
use App\tbl_service_observation_points;
use App\tbl_service_pros;
use App\User;
use App\Vehicle;
use App\Washbay;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Mail;

class ServicesControler extends Controller
{
    // get tables and compact
    public function __construct()
    {
        $this->middleware('auth');
    }

    // service list

    public function servicelist(Request $request)
    {
        if ($request->ajax()) {
            $query = Service::query()->leftJoin('users as customer', 'tbl_services.customer_id', '=', 'customer.id') // Join for customer
                ->leftJoin('users as assignee', 'tbl_services.assign_to', '=', 'assignee.id')
                ->select(
                    'tbl_services.id',
                    'tbl_services.job_no',
                    'tbl_services.service_date',
                    'tbl_services.assign_to as assign_name',
                    'tbl_services.service_category',
                    'tbl_services.done_status',
                    'tbl_services.charge',
                    'tbl_services.customer_id',
                    'tbl_services.branch_id',
                    'customer.name as customer_name', // Get customer name
                    'assignee.name as assign_to',      // Get assignee name
                    'tbl_services.vehicle_id',
                    DB::raw('(SELECT next_date FROM tbl_jobcard_details WHERE tbl_jobcard_details.service_id = tbl_services.id LIMIT 1) as upcoming_service_date')
                )->where('tbl_services.soft_delete', '=', 0)->orderBy('id', 'DESC')
                ->distinct(); // Ensure distinct rows to avoid duplicates

            // Role-based filtering
            if (! isAdmin(Auth::User()->role_id)) {
                $userRole = getUsersRole(Auth::user()->role_id);

                if ($userRole === 'Customer') {
                    $query->where('customer_id', Auth::User()->id)
                        ->where('job_no', 'like', 'J%')
                        ->where('is_quotation', '=', 0);
                } elseif ($userRole === 'Employee') {
                    $query->where('job_no', 'like', 'J%')
                        ->where('tbl_services.branch_id', Auth::user()->branch_id)
                        ->whereNotIn('quotation_modify_status', [1]);

                    if (Gate::allows('service_owndata')) {
                        $query->where('assign_to', Auth::User()->id);
                    }
                } elseif (in_array($userRole, ['Support Staff', 'Accountant', 'Branch Admin'])) {
                    $query->where('job_no', 'like', 'J%')
                        ->where('tbl_services.branch_id', Auth::user()->branch_id)
                        ->whereNotIn('quotation_modify_status', [1]);

                    if (Gate::allows('service_owndata')) {
                        $query->where('create_by', Auth::User()->id);
                    }
                }
            } else {
                // Admin has full access
                $query->where('job_no', 'like', 'J%')
                    ->whereNotIn('quotation_modify_status', [1]);
            }
            // Search functionality
            $searchValue = $request->input('search.value');
            if ($searchValue) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('job_no', 'LIKE', "%{$searchValue}%")
                        ->orWhere('service_category', 'LIKE', "%{$searchValue}%")
                        ->orWhere('service_date', 'LIKE', "%{$searchValue}%")
                        ->orWhere('customer.name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('assignee.name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('charge', 'LIKE', "%{$searchValue}%");
                });
            }

            // Get the total filtered records
            $filteredRecords = $query->count();

            // Pagination and ordering
            $columns = ['id', 'job_no', 'service_date', 'service_category', 'upcoming_service_date', 'assign_to', 'vehicle_id'];

            $columnIndex = $request->input('order.0.column', 0); // Default to the first column if not provided
            $sortDirection = $request->input('order.0.dir', 'asc'); // Default to ascending order if not provided
            $column = $columns[$columnIndex] ?? 'id'; // Default to 'id' if the column index is invalid
            $data = $query
                ->orderBy($column, $sortDirection) // Primary ordering by the selected column
                ->orderBy('id', 'DESC') // Secondary ordering to always prioritize recent entries
                ->offset($request->input('start', 0))
                ->limit($request->input('length', 10))
                ->get();

            // Prepare response
            $response = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => Service::count(),
                'recordsFiltered' => $filteredRecords,
                'data' => $data->map(function ($service) {
                    // Generate the action dropdown
                    $actionDropdown = '
                <div class="dropdown_toggle">
                    <img src="'.asset('public/img/list/dots.png').'" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButtonaction" data-bs-toggle="dropdown" aria-expanded="false">
                    <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonaction">';

                    // View action
                    if (auth()->user()->can('service_view')) {
                        $actionDropdown .= '
                        <li><a class="dropdown-item" href="'.url('/service/list/view/'.$service->id).'">
                            <img src="'.asset('public/img/list/Vector.png').'" class="me-3">'.trans('message.View').'</a></li>';
                    }

                    // Edit action
                    $jobcard = getJobcardStatus($service->job_no);
                    $view_data = getInvoiceStatus($service->job_no);
                    $edit_service = getEditService();

                    if (auth()->user()->can('service_edit')) {
                        if ($jobcard == 1) {
                            if ($edit_service == 1) {
                                $actionDropdown .= '
                                <li>
                                    <a href="'.url('service/list/edit/'.$service->id).'" class="dropdown-item">
                                        <img src="'.asset('public/img/list/Edit.png').'" class="me-3"> '.trans('message.Edit').'
                                    </a>
                                </li>';
                            }
                        } elseif ($view_data == 'Yes') {
                            if ($edit_service == 1) {
                                $actionDropdown .= '
                                <li>
                                    <a href="'.url('service/list/edit/'.$service->id).'" class="dropdown-item">
                                        <img src="'.asset('public/img/list/Edit.png').'" class="me-3"> '.trans('message.Edit').'
                                    </a>
                                </li>';
                            }
                        } else {
                            $actionDropdown .= '
                            <li>
                                <a href="'.url('service/list/edit/'.$service->id).'" class="dropdown-item">
                                    <img src="'.asset('public/img/list/Edit.png').'" class="me-3"> '.trans('message.Edit').'
                                </a>
                            </li>';
                        }
                    }

                    // Delete action
                    if (auth()->user()->can('service_delete')) {
                        $actionDropdown .= '
                        <div class="dropdown-divider m-0"></div>
                        <li><a class="dropdown-item deletedatas" url="'.url('/service/list/delete/'.$service->id).'" style="color:#FD726A">
                            <img src="'.asset('public/img/list/Delete.png').'" class="me-3">'.trans('message.Delete').'</a></li>';
                    }

                    $actionDropdown .= '</ul></div>';

                    return [
                        'id' => $service->id,
                        'job_no' => '<a href="'.url('/service/list/view/'.$service->id).'" class="serviceview">'.$service->job_no.'</a>',
                        'customer_name' => getCustomerName($service->customer_id) ?: trans('message.Not Added'),
                        'date' => date('Y-m-d', strtotime($service->service_date)) ?: trans('message.Not Added'),
                        'service_category' => $service->service_category ?: trans('message.Not Added'),
                        'upcoming_service_date' => $service->upcoming_service_date ?: trans('message.Not Added'),
                        'assign_to' => getAssignTo($service->assign_name) ?: trans('message.Not Added'),
                        'number_plate' => getVehicleNumberPlate($service->vehicle_id) ?: trans('message.Not Added'),
                        'action' => $actionDropdown,
                    ];
                }),
            ];

            return response()->json($response);
        }

        // Render view with services
        $month = date('m');
        $year = date('Y');
        $available = '';
        $servi_id = '';
        $start_date = "$year/$month/01";
        $end_date = "$year/$month/30";
        $date = [];

        $current_month = Service::whereBetween('service_date', [$start_date, $end_date])->get();
        if (! empty($current_month)) {
            foreach ($current_month as $list) {
                $date[] = $list->service_date;
            }
            $available = json_encode($date);
        }

        $ser_id_jobcard_details = JobcardDetail::get();
        foreach ($ser_id_jobcard_details as $ser_id) {
            $servi_id = $ser_id->service_id;
        }

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

        if (! isAdmin(Auth::User()->role_id)) {
            if (getUsersRole(Auth::user()->role_id) == 'Customer') {
                $services = Service::where([['job_no', 'like', 'J%'], ['customer_id', '=', Auth::User()->id], ['soft_delete', '=', 0], ['is_quotation', '=', 0]])->orderBy('id', 'DESC')->get();
            } elseif (getUsersRole(Auth::user()->role_id) == 'Employee') {
                if (Gate::allows('service_owndata')) {
                    $services = Service::where([['job_no', 'like', 'J%'], ['assign_to', '=', Auth::User()->id], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->whereNotIn('quotation_modify_status', [1])->orderBy('id', 'DESC')->get();
                } else {
                    $services = Service::where([['job_no', 'like', 'J%'], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->whereNotIn('quotation_modify_status', [1])->orderBy('id', 'DESC')->get();
                }
            } elseif (getUsersRole(Auth::user()->role_id) == 'Support Staff' || getUsersRole(Auth::user()->role_id) == 'Accountant' || getUsersRole(Auth::user()->role_id) == 'Branch Admin') {
                if (Gate::allows('service_owndata')) {
                    $services = Service::where([['job_no', 'like', 'J%'], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id], ['create_by', Auth::User()->id]])->whereNotIn('quotation_modify_status', [1])->orderBy('id', 'DESC')->get();
                } else {
                    $services = Service::where([['job_no', 'like', 'J%'], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->whereNotIn('quotation_modify_status', [1])->orderBy('id', 'DESC')->get();
                }
            }
        } else {
            $services = Service::where([['job_no', 'like', 'J%'], ['soft_delete', '=', 0]])->whereNotIn('quotation_modify_status', [1])->orderBy('id', 'DESC')->get();
        }
        foreach ($services as $service) {
            $job = JobcardDetail::where('service_id', '=', $service->id)->first();
            if ($job) {
                $service->next_date = $job->next_date;
            } else {
                // If record doesn't exist, create a new one
                $tbl_jobcard_details = new JobcardDetail;
                $tbl_jobcard_details->customer_id = $service->customer_id;
                $tbl_jobcard_details->vehicle_id = $service->vehicle_id;
                $tbl_jobcard_details->service_id = $service->id;
                $tbl_jobcard_details->jocard_no = $service->job_no;
                $tbl_jobcard_details->in_date = $service->created_at;
                $tbl_jobcard_details->save();

                $service->next_date = $tbl_jobcard_details->next_date;
            }
        }

        return view('/service/list', compact('services'));
    }

    public function index()
    {
        $last_order = DB::table('tbl_services')->latest()->where('sales_id', '=', null)->get()->first();

        if (! empty($last_order)) {

            $last_full_job_number = $last_order->job_no;
            $last_job_number_digit = substr($last_full_job_number, 1);
            $new_number = 'J'.str_pad($last_job_number_digit + 1, 6, 0, STR_PAD_LEFT);
        } else {
            $new_number = 'J000001';
        }

        $code = $new_number;

        // Customer add
        $customer = DB::table('users')->where([['role', 'Customer'], ['soft_delete', 0]])->get()->toArray();
        $country = DB::table('tbl_countries')->get()->toArray();
        $onlycustomer = DB::table('users')->where([['role', '=', 'Customer'], ['id', '=', Auth::User()->id]])->first();

        // vehicle add
        $vehical_type = DB::table('tbl_vehicle_types')->where('soft_delete', '=', 0)->get()->toArray();
        $vehical_brand = DB::table('tbl_vehicle_brands')->where('soft_delete', '=', 0)->get()->toArray();
        $fuel_type = DB::table('tbl_fuel_types')->where('soft_delete', '=', 0)->get()->toArray();
        $color = DB::table('tbl_colors')->where('soft_delete', '=', 0)->get()->toArray();
        $model_name = DB::table('tbl_model_names')->where('soft_delete', '=', 0)->get()->toArray();

        // Custom Field Data
        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'service'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        // $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if (isAdmin(Auth::User()->role_id)) {

            $branchDatas = Branch::where('soft_delete', '=', 0)->get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0]])->get()->toArray();
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            $branchDatas = Branch::where('soft_delete', '=', 0)->get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0]])->get()->toArray();
        } else {
            $branchDatas = Branch::where('id', $currentUser->branch_id)->where('soft_delete', '=', 0)->get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
        }

        $repairCategoryList = DB::table('table_repair_category')->where([['soft_delete', '=', 0]])->get()->toArray();

        return view('service.add', compact('employee', 'customer', 'code', 'country', 'onlycustomer', 'vehical_brand', 'vehical_type', 'fuel_type', 'color', 'model_name', 'tbl_custom_fields', 'branchDatas', 'repairCategoryList'));
    }

    // customer add
    public function customeradd(Request $request)
    {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $displayname = $request->displayname;
        $gender = $request->gender;
        $email = $request->email;
        $password = $request->password;
        $mobile = $request->mobile;
        $landlineno = $request->landlineno;
        $address = $request->address;
        $country = $request->country_id;
        $state = $request->state_id;
        $city = $request->city;
        $company_name = $request->company_name;

        $dobs = $request->dob;
        if (getDateFormat() == 'm-d-Y') {
            $dob = date('Y-m-d', strtotime(str_replace('-', '/', $dobs)));
        } else {
            $dob = date('Y-m-d', strtotime($dobs));
        }

        // Get user role id from Role table
        $getRoleId = Role::where('role_name', '=', 'Customer')->first();

        $customer = new User;
        $customer->name = $firstname;
        $customer->lastname = $lastname;
        $customer->display_name = $displayname;
        $customer->gender = $gender;
        $customer->birth_date = $dob;
        $customer->email = $email;
        $customer->password = bcrypt($password);
        $customer->mobile_no = $mobile;
        $customer->landline_no = $landlineno;
        $customer->address = $address;
        $customer->country_id = $country;
        $customer->state_id = $state;
        $customer->city_id = $city;
        $customer->company_name = $company_name;

        $images = $request->image;
        if (! empty($images)) {
            // $file= Input::file('image');
            $file = $images;
            $filename = $file->getClientOriginalName();
            $file->move(public_path().'/customer/', $file->getClientOriginalName());
            $customer->image = $filename;
        } else {
            $customer->image = 'avtar.png';
        }

        $customer->role = 'Customer';
        $customer->role_id = $getRoleId->id; /* Store Role table User Role Id */
        $customer->language = 'en';
        $customer->timezone = 'UTC';
        $customer->save();

        /* For data store inside Role_user table */
        if ($customer->save()) {
            $currentUserId = $customer->id;

            $role_user_table = new Role_user;
            $role_user_table->user_id = $currentUserId;
            $role_user_table->role_id = $getRoleId->id;
            $role_user_table->save();
        }

        $customer_fullname = $customer->name.' '.$customer->lastname;

        return response()->json(['customerId' => $customer->id, 'customer_fullname' => $customer_fullname]);
    }

    // add vehicle
    public function vehicleadd(Request $request)
    {
        $vehical_type = $request->vehical_id1;
        $chasicno = $request->chasicno1;
        $vehicabrand = $request->vehicabrand1;
        $modelyear = $request->modelyear1;
        $fueltype = $request->fueltype1;
        $modelname = $request->modelname1;
        $price = $request->price1;
        $odometerreading = $request->odometerreading1;
        $domm = $request->dom1;
        $gearbox = $request->gearbox1;
        $gearboxno = $request->gearboxno1;
        $engineno = $request->engineno1;
        $enginesize = $request->enginesize1;
        $keyno = $request->keyno1;
        $engine = $request->engine1;
        $nogears = $request->gearno1;
        $numberPlate = $request->numberPlate;
        $customer_id = $request->customer_id;
        $select_branch_vehicle = $request->branch_id_vehicle;

        $dom = '';
        if (! empty($domm)) {
            if (getDateFormat() == 'm-d-Y') {
                $dom = date('Y-m-d', strtotime(str_replace('-', '/', $domm)));
            } else {
                $dom = date('Y-m-d', strtotime($domm));
            }
        }

        $vehical = new Vehicle;
        $vehical->vehicletype_id = $vehical_type;
        $vehical->chassisno = $chasicno;
        $vehical->vehiclebrand_id = $vehicabrand;
        $vehical->modelyear = $modelyear;
        $vehical->fuel_id = $fueltype;
        $vehical->modelname = $modelname;
        $vehical->price = $price;
        $vehical->odometerreading = $odometerreading;
        $vehical->dom = $dom;
        $vehical->gearbox = $gearbox;
        $vehical->gearboxno = $gearboxno;
        $vehical->engineno = $engineno;
        $vehical->enginesize = $enginesize;
        $vehical->keyno = $keyno;
        $vehical->engine = $engine;
        $vehical->nogears = $nogears;
        $vehical->number_plate = $numberPlate;
        $vehical->added_by_service = 1;
        $vehical->customer_id = $customer_id;
        $vehical->branch_id = $select_branch_vehicle;
        $vehical->save();

        // $vehicles = DB::table('tbl_vehicles')->orderBy('id', 'desc')->first();
        // $vehi_id = $vehicles->id;
        // $model_name = $vehicles->modelname;

        echo $vehical->id;

        // dd($numberPlate);
    }

    // add repair Category
    public function addRepairCategory(Request $request)
    {
        $repairCategoryName = $request->repair_category_name;

        $repairCategories = DB::table('table_repair_category')->where('repair_category_name', '=', $repairCategoryName)->count();

        if ($repairCategories == 0) {
            $repairCategory = new RepairCategory;
            $repairCategory->repair_category_name = $repairCategoryName;
            $repairCategory->slug = $repairCategoryName;
            $repairCategory->added_by_system = 0;
            $repairCategory->soft_delete = 0;
            $repairCategory->save();
            echo $repairCategory->slug;
        } else {
            $repairCategoryRecord = DB::table('table_repair_category')->where([['soft_delete', '!=', 1], ['repair_category_name', '=', $repairCategoryName]])->first();
            if (! empty($repairCategoryRecord)) {
                return '01';
            } else {
                $repairCategory = new RepairCategory;
                $repairCategory->repair_category_name = $repairCategoryName;
                $repairCategory->slug = $repairCategoryName;
                $repairCategory->added_by_system = 0;
                $repairCategory->soft_delete = 0;
                $repairCategory->save();
                echo $repairCategory->slug;
            }
        }
    }

    // Repair Category Delete
    public function deleteRepairCategory(Request $request)
    {
        $id = $request->colorid;
        $color = DB::table('table_repair_category')->where('slug', '=', $id)->update(['soft_delete' => 1]);
    }

    // get regi. number
    public function getregistrationno(Request $request)
    {
        $vehi_id = $request->vehi_id;

        $vehicals = DB::table('tbl_sales')->where('vehicle_id', '=', $vehi_id)->first();
        if (! empty($vehicals)) {
            $reg = $vehicals->registration_no;
        } else {
            $vehicals = DB::table('tbl_vehicles')->where('id', '=', $vehi_id)->first();
            $reg = $vehicals->registration_no;
        }

        return $reg;
    }

    // get vehicle name
    public function get_vehicle_name(Request $request)
    {
        $cus_id = $request->cus_id;
        $selectedVId = $request->input('v_id');

        // $vehicals = DB::table('tbl_services')->where('customer_id', '=', $cus_id)->groupBy('vehicle_id')->get()->toArray();

        $vehicle = getVehicles1($cus_id);

        // If no vehicles are available, remove previous options
        if (empty($vehicle)) {
            return;
        }

        $arr = implode(',', $vehicle);
        $option = explode(',', $arr);

        foreach ($option as $value) {
            $num = "$value";
            $n = explode('/', $num);
            $isSelected = ($n[3] == $selectedVId) ? 'selected' : '';
            ?>
            <option value="<?php echo $n[3]; ?>" class="modelnms" <?php echo $isSelected; ?>>
                <?php echo $value; ?>
            </option>
        <?php
        }
    }

    // add_jobcard store
    public function add_jobcard(Request $request)
    {
        $deletedObsIds = explode(',', $request->input('deleted_obs_ids'));
        $serviceId = $request->service_id;
        // Handle deleted observation points
        if (! empty($deletedObsIds)) {
            DB::table('tbl_service_pros')
                ->whereIn('tbl_service_observation_points_id', $deletedObsIds)
                ->delete();

            DB::table('tbl_service_observation_points')
                ->whereIn('id', $deletedObsIds)
                ->delete();
        }
        $message = $request->message;
        $job_no = $request->job_no;
        $service_id = $request->service_id;
        $cus_id = $request->cust_id;
        $vehi_id = $request->vehi_id;
        $coupan_no = $request->coupan_no;
        $product = $request->product;
        $sub_product = $request->sub_product;
        $comment = $request->comment;
        $obs_auto_id = $request->obs_id;
        $in_date = $request->in_date;
        $out_date = $request->out_date;

        if (getDateFormat() == 'm-d-Y') {
            $in_dat = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $in_date)));
            $out_dat = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $out_date)));
        } else {
            $in_dat = date('Y-m-d H:i:s', strtotime($in_date));
            $out_dat = date('Y-m-d H:i:s', strtotime($out_date));
        }

        // Example: Insert data into the tbl_service_pros table-relationally
        if (! empty($product)) {
            foreach ($product as $key => $value) {
                $category = $product[$key] ?? null;
                $sub = $sub_product[$key] ?? null;
                $comm = $comment[$key] ?? null;
                $obs_au_id = $obs_auto_id[$key] ?? null;

                // Check if a record exists in tbl_service_observation_points
                $obsPointRecord = DB::table('tbl_service_observation_points')
                    ->where('services_id', $serviceId)
                    ->where('observation_points_id', $obs_au_id)
                    ->first();

                if (! $obsPointRecord) {
                    // Insert new record in tbl_service_observation_points if not found
                    $obsPointRecordId = DB::table('tbl_service_observation_points')->insertGetId([
                        'services_id' => $serviceId,
                        'observation_points_id' => $obs_au_id,
                        'review' => 1, // Set review field as needed
                    ]);
                } else {
                    // Use existing record ID
                    $obsPointRecordId = $obsPointRecord->id;

                    // Update existing record to ensure no null values
                    DB::table('tbl_service_observation_points')
                        ->where('id', $obsPointRecordId)
                        ->update(['observation_points_id' => $obs_au_id, 'review' => 1]);
                }

                // Check if a record exists in tbl_service_pros
                $existingRecord = DB::table('tbl_service_pros')
                    ->where('tbl_service_observation_points_id', $obsPointRecordId)
                    ->first();

                if ($existingRecord) {
                    // Update existing record, ensuring comments and other fields are updated correctly
                    DB::table('tbl_service_pros')
                        ->where('tbl_service_observation_points_id', $obsPointRecordId)
                        ->update([
                            'category' => $category,
                            'obs_point' => $sub,
                            'category_comments' => $comm,
                            'chargeable' => 1, // Adjust as needed
                            'type' => 0, // Adjust as needed
                        ]);
                } else {
                    // Insert new record into tbl_service_pros
                    DB::table('tbl_service_pros')->insert([
                        'service_id' => $serviceId,
                        'tbl_service_observation_points_id' => $obsPointRecordId,
                        'category' => $category,
                        'obs_point' => $sub,
                        'category_comments' => $comm,
                        'chargeable' => 1, // Adjust as needed
                        'type' => 0, // Adjust as needed
                    ]);
                }
            }
        }
        // end new added logic for storing data relationally
        $available = JobcardDetail::where('jocard_no', '=', $job_no)->first();

        if ($available) {
            // If record exists, update it
            $available->customer_id = $cus_id;
            $available->vehicle_id = $vehi_id;
            $available->service_id = $service_id;
            $available->jocard_no = $job_no;
            $available->in_date = $in_dat;
            $available->out_date = $out_dat;

            if (! empty($coupan_no)) {
                $available->coupan_no = $coupan_no;
            }

            $available->save();
        } else {
            // If record doesn't exist, create a new one
            $tbl_jobcard_details = new JobcardDetail;
            $tbl_jobcard_details->customer_id = $cus_id;
            $tbl_jobcard_details->vehicle_id = $vehi_id;
            $tbl_jobcard_details->service_id = $service_id;
            $tbl_jobcard_details->jocard_no = $job_no;
            $tbl_jobcard_details->in_date = $in_dat;
            $tbl_jobcard_details->out_date = $out_dat;

            if (! empty($coupan_no)) {
                $tbl_jobcard_details->coupan_no = $coupan_no;
            }

            $tbl_jobcard_details->save();
        }
        $mot_main_module_status_checked = DB::table('tbl_services')->where('id', '=', $service_id)->first();
        if ($mot_main_module_status_checked !== null) {

            $mot_main_module_status = $mot_main_module_status_checked->mot_status;
        } else {
            $mot_main_module_status = null;
        }

        $inspection_data = [];

        if ($mot_main_module_status == 1) {
            $inspection_answers = DB::table('mot_vehicle_inspection')->where('service_id', '=', $service_id)->first();
            if (! empty($inspection_answers)) {
                // dd('record found');
                $inspection_data = $request->inspection;
                $data_for_db = json_encode($inspection_data);
                $fill_mot_vehicle_inspection = ['answer_question_id' => $data_for_db, 'vehicle_id' => $vehi_id, 'service_id' => $service_id, 'jobcard_number' => $job_no];
                $mot_vehicle_inspection_data_store = DB::table('mot_vehicle_inspection')->where('service_id', '=', $service_id)->update($fill_mot_vehicle_inspection);
                $get_vehicle_inspection_id = DB::table('mot_vehicle_inspection')->latest('id')->first();
                $get_vehicle_current_id = $get_vehicle_inspection_id->id;

                if (in_array('x', $inspection_data) || in_array('r', $inspection_data)) {
                    $mot_test_status = 'fail';
                } else {
                    $mot_test_status = 'pass';
                }

                $generateMotTestNumber = rand();
                $todayDate = date('Y-m-d');

                $fill_data_vehicle_mot_test_reports = ['vehicle_id' => $vehi_id, 'service_id' => $service_id, 'mot_vehicle_inspection_id' => $get_vehicle_current_id, 'test_status' => $mot_test_status, 'mot_test_number' => $generateMotTestNumber, 'date' => $todayDate];

                $insert_data_vehicle_mot_test_reports = DB::table('vehicle_mot_test_reports')->where('service_id', '=', $service_id)->update($fill_data_vehicle_mot_test_reports);
            } else {
                // dd('record not found');
                $inspection_data = $request->inspection;
                $data_for_db = json_encode($inspection_data);
                $fill_mot_vehicle_inspection = ['answer_question_id' => $data_for_db, 'vehicle_id' => $vehi_id, 'service_id' => $service_id, 'jobcard_number' => $job_no];
                $mot_vehicle_inspection_data_store = DB::table('mot_vehicle_inspection')->insert($fill_mot_vehicle_inspection);
                $get_vehicle_inspection_id = DB::table('mot_vehicle_inspection')->latest('id')->first();
                $get_vehicle_current_id = $get_vehicle_inspection_id->id;

                if (in_array('x', $inspection_data) || in_array('r', $inspection_data)) {
                    $mot_test_status = 'fail';
                } else {
                    $mot_test_status = 'pass';
                }

                $generateMotTestNumber = rand();
                $todayDate = date('Y-m-d');

                $fill_data_vehicle_mot_test_reports = ['vehicle_id' => $vehi_id, 'service_id' => $service_id, 'mot_vehicle_inspection_id' => $get_vehicle_current_id, 'test_status' => $mot_test_status, 'mot_test_number' => $generateMotTestNumber, 'date' => $todayDate];

                $insert_data_vehicle_mot_test_reports = DB::table('vehicle_mot_test_reports')->insert($fill_data_vehicle_mot_test_reports);
            }
        } else {
            // echo "You have not checked MoT Module";
        }

        // email format
        try {
            $user = DB::table('users')->where('id', '=', $cus_id)->first();
            $email = $user->email;
            $firstname = $user->name;
            $logo = DB::table('tbl_settings')->first();
            $systemname = $logo->system_name;
            $servicedetails = DB::table('tbl_services')->where('job_no', '=', $job_no)->first();
            $details = $servicedetails->detail;
            $car_details = $servicedetails->car_detail;
            $emailformats = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'successful_jobcard')->first();
            if ($emailformats->is_send == 0) {
                if ($tbl_jobcard_details->save()) {
                    $emailformat = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'successful_jobcard')->first();
                    $mail_format = $emailformat->notification_text;
                    $notification_label = $emailformat->notification_label;
                    $mail_subjects = $emailformat->subject;
                    $mail_send_from = $emailformat->send_from;
                    $search1 = ['{ jobcard_number }'];
                    $replace1 = [$job_no];
                    $mail_sub = str_replace($search1, $replace1, $mail_subjects);

                    $search = ['{ system_name }', '{ Customer_name }', '{ jobcard_number }', '{ service_date }', '{ detail }'];
                    $replace = [$systemname, $firstname, $job_no, $in_dat, $details];

                    $email_content = str_replace($search, $replace, $mail_format);
                    $redirect_url = url('/jobcard/list');
                    $systemLink = url('/');
                    // Render Blade template with all required variables
                    $blade_view = View::make('mail.template', [
                        'notification_label' => $notification_label,
                        'email_content' => $email_content,
                        'redirect_url' => $redirect_url,
                        'system_link' => $systemLink,
                    ])->render();

                    // Send email
                    try {
                        Mail::send([], [], function ($message) use ($email, $mail_sub, $blade_view, $mail_send_from) {
                            $message->to($email)->subject($mail_sub);
                            $message->from($mail_send_from);
                            $message->html($blade_view, 'text/html');
                        });
                    } catch (\Exception $e) {
                        \Log::error('Error sending successful_jobcard email: '.$e->getMessage());
                    }

                    $messageBody = 'Thank you for bringing your vehicle to our service center.Your Jobcard Number is'.$servicedetails->job_no.'created on'.$servicedetails->service_date;
                    $receiverNumber = getMobileNoWithCode($user->id);
                    if (\Schema::hasTable('sms_settings') && View::exists('smsaddon::sms_setting')) {
                        \Illuminate\Support\Facades\Http::post(route('smsaddon.send-sms'), ['messageBody' => $messageBody, 'receiver_mobile' => $receiverNumber]);
                    }
                    // Store email log entry
                    $emailLog = new EmailLog;
                    $emailLog->recipient_email = $email;
                    $emailLog->subject = $mail_sub;
                    $emailLog->content = $email_content;
                    $emailLog->save();
                }
            }
        } catch (\Exception $e) {
        }

        // dd($tbl_service_pros);
        return redirect('jobcard/list')->with('message', $message);
    }

    // Service store
    public function store(Request $request)
    {
        $mot_test_status = $request->motTestStatusCheckbox;
        $mot_charge = $request->motTestCharge;
        $job = $request->jobno;
        $vehicalname = $request->vehicalname;
        $Customername = $request->Customername;
        $details = $request->details;
        $car_details = $request->car_details;
        // $markers = json_decode($request->input('markers'), true);
        $markers = $request->input('markers');
        // dd($markers);
        $reg_no = $request->reg_no;
        $title = $request->title;
        $AssigneTo = $request->AssigneTo;
        $service_category = $request->repair_cat;
        $ser_type = $request->service_type;
        $charge = $request->charge;
        $vehicalname = $request->vehicalname;
        $Customername = $request->Customername;
        $date = $request->date;
        $kms = $request->kms;

        $image = $request->image;

        if ($mot_test_status == 'on') {
            $mot_test_status = 1;
        } else {
            $mot_test_status = 0;
        }

        $color = null;
        if (getDateFormat() == 'm-d-Y') {
            $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date)));
        } else {
            $date = date('Y-m-d H:i:s', strtotime($date));
        }

        if ($ser_type == 'free') {
            $charge = '0';
        }

        if ($ser_type == 'paid') {
            $charge = $request->charge;
        }

        $available = Service::where('job_no', '=', $job)->first();

        if ($available) {
            // If record exists, update it
            $available->vehicle_id = $vehicalname;
            $available->service_date = $date;
            $available->title = $title;
            $available->assign_to = $AssigneTo;
            $available->service_category = $service_category;
            $available->charge = $charge;
            $available->customer_id = $Customername;
            $available->detail = $details;
            $available->car_detail = $car_details;
            $available->service_type = $ser_type;
            $available->mot_status = $mot_test_status;
            $available->kms_run = $kms;
            $available->mot_charge = $mot_charge;
            $available->branch_id = $request->branch;
            $available->create_by = Auth::User()->id;

            // Update custom field
            $custom = $request->custom;
            $custom_fileld_value = [];
            $custom_fileld_value_jason_array = [];

            if (! empty($custom)) {
                foreach ($custom as $key => $value) {
                    if (is_array($value)) {
                        $add_one_in = implode(',', $value);
                        $custom_fileld_value[] = ['id' => "$key", 'value' => "$add_one_in"];
                    } else {
                        $custom_fileld_value[] = ['id' => "$key", 'value' => "$value"];
                    }
                }

                $custom_fileld_value_jason_array['custom_fileld_value'] = json_encode($custom_fileld_value);

                foreach ($custom_fileld_value_jason_array as $key1 => $val1) {
                    $customerdata = $val1;
                }
                $available->custom_field = $customerdata;
            }
            $available->save();

            $customer = User::find($available->customer_id);
            $toEmail = $customer->email;
            // Save Notes data
            if ($available->save()) {
                // if ($request->notes !== null) {
                //     saveNotes($available, $request->notes, $toEmail);
                // }
                if (! empty($request->notes)) {
                    // Filter notes to exclude empty ones
                    $validNotes = array_filter($request->notes, function ($note) {
                        return ! empty($note['note_text']) || (! empty($note['note_file']) && is_array($note['note_file']));
                    });

                    // Only call saveNotes if we have valid notes
                    if (! empty($validNotes)) {
                        saveNotes($available, $request->notes, $toEmail);
                    }
                }

            }
        } else {
            // If record doesn't exist, create a new one
            $services = new Service;
            $services->job_no = $job;
            $services->vehicle_id = $vehicalname;
            $services->service_date = $date;
            $services->title = $title;
            $services->assign_to = $AssigneTo;
            $services->service_category = $service_category;
            $services->done_status = 0;
            $services->charge = $charge;
            $services->customer_id = $Customername;
            $services->detail = $details;
            $services->kms_run = $kms;

            $services->car_detail = $car_details;
            $services->service_type = $ser_type;
            $services->mot_status = $mot_test_status;
            $services->mot_charge = $mot_charge;
            $services->branch_id = $request->branch;
            $services->create_by = Auth::User()->id;
        
            $services->markers = $markers;
        
            // Save custom field
            $custom = $request->custom;
            $custom_fileld_value = [];
            $custom_fileld_value_jason_array = [];

            if (! empty($custom)) {
                foreach ($custom as $key => $value) {
                    if (is_array($value)) {
                        $add_one_in = implode(',', $value);
                        $custom_fileld_value[] = ['id' => "$key", 'value' => "$add_one_in"];
                    } else {
                        $custom_fileld_value[] = ['id' => "$key", 'value' => "$value"];
                    }
                }

                $custom_fileld_value_jason_array['custom_fileld_value'] = json_encode($custom_fileld_value);

                foreach ($custom_fileld_value_jason_array as $key1 => $val1) {
                    $customerdata = $val1;
                }
                $services->custom_field = $customerdata;
            }
            $services->save();

            $customer = User::find($services->customer_id);
            $toEmail = $customer->email;
            // Save Notes data
            if ($services->save()) {
              
                if (! empty($request->notes)) {
                    // Filter notes to exclude empty ones
                    $validNotes = array_filter($request->notes, function ($note) {
                        return ! empty($note['note_text']) || (! empty($note['note_file']) && is_array($note['note_file']));
                    });

                    // Only call saveNotes if we have valid notes
                    if (! empty($validNotes)) {
                        saveNotes($available, $request->notes, $toEmail);
                    }
                }
            }
        }

        $get_service_id = DB::table('tbl_services')->where('job_no', '=', $job)->pluck('id')->first();

        $image = $request->image;
        if (! empty($image)) {
            $files = $image;

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $file->move(public_path().'/service/', $file->getClientOriginalName());
                $images = new tbl_service_images;
                $images->service_id = $get_service_id;
                $images->image = $filename;
                $images->save();
            }
        }

        $washbay_status = $request->washbay;

        if ($washbay_status == 'on') {
            $washbay_charge = $request->washBayCharge;

            $washbays = new Washbay;
            $washbays->service_id = $get_service_id;
            $washbays->jobcard_no = $job;
            $washbays->vehicle_id = $vehicalname;
            $washbays->customer_id = $Customername;
            $washbays->price = $washbay_charge;
            $washbays->save();
        }

        $washbay_price = Washbay::where([['jobcard_no', '=', $job], ['customer_id', '=', $Customername]])->first();

        $service_data = DB::table('tbl_services')->orderBy('id', 'DESC')->first();
        $veh_id = $service_data->vehicle_id;
        $ser_id = $service_data->id;
        $cus_id = $service_data->customer_id;
        $counpan_no = [];
        $job_card_data = DB::table('tbl_jobcard_details')->where([['customer_id', '=', $cus_id], ['vehicle_id', '=', $veh_id]])->get()->toArray();
        if (! empty($job_card_data)) {
            foreach ($job_card_data as $job_card_datas) {
                $counpan_no[] = $job_card_datas->coupan_no;
            }

            $free_coupan = DB::table('tbl_services')->where([['customer_id', '=', $Customername], ['service_type', '=', 'free'], ['vehicle_id', '=', $vehicalname], ['job_no', 'like', 'C%']])->get()->toArray();
        } else {
            $free_coupan = DB::table('tbl_services')->where([['customer_id', '=', $Customername], ['service_type', '=', 'free'], ['vehicle_id', '=', $vehicalname], ['job_no', 'like', 'C%']])->whereNotIn('job_no', $counpan_no)->get()->toArray();
        }

        $sale_date = DB::table('tbl_sales')->where('vehicle_id', '=', $veh_id)->first();
        if (! empty($sale_date)) {
            $color_id = $sale_date->color_id;
            $color = DB::table('tbl_colors')->where('id', '=', $color_id)->first();
        }
        $num_plate = $request->number_plate;
        // $vehical = DB::table('tbl_vehicles')->where('number_plate', 'like', "%$num_plate")->first();
        $vehical = DB::table('tbl_vehicles')->where('id', '=', "$veh_id")->first();
        $model_id = getModel_id($vehical->modelname);

        $obs_point = DB::table('tbl_service_observation_points')->where([['services_id', '=', $ser_id], ['review', '=', 1]])->get()->toArray();

        $sale_regi = DB::table('tbl_sales')->where('vehicle_id', '=', $vehicalname)->first();
        if (! empty($sale_regi)) {
            DB::update("update tbl_sales set registration_no = '$reg_no' where vehicle_id = $vehicalname");
        } else {
            DB::update("update tbl_vehicles set registration_no = '$reg_no' where id = $vehicalname");
        }

        $logo = DB::table('tbl_settings')->first();
        $inspection_points_library_data = DB::table('inspection_points_library')->get();

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        $names = null;
        if (isAdmin(Auth::User()->role_id)) {
            $tbl_checkout_categories = DB::table('tbl_checkout_categories')->where([['vehicle_id', '=', $model_id], ['soft_delete', '=', 0]])->orWhere('vehicle_id', '=', 0)->where('branch_id', '=', $adminCurrentBranch->branch_id)->get()->toArray();
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            $tbl_checkout_categories = DB::table('tbl_checkout_categories')->where([['vehicle_id', '=', $model_id], ['soft_delete', '=', 0]])->orWhere('vehicle_id', '=', 0)->get()->toArray();
        } else {
            $tbl_checkout_categories = DB::table('tbl_checkout_categories')->where([['vehicle_id', '=', $model_id], ['soft_delete', '=', 0]])->orWhere('vehicle_id', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->get()->toArray();
        }
        $message = 'Service Added Successfully';

        // dd($inspection_points_library_data);
        // dd($num_plate);
        return view('/service/jobcard_form', compact('service_data', 'vehical', 'tbl_checkout_categories', 'sale_date', 'color', 'obs_point', 'free_coupan', 'logo', 'inspection_points_library_data', 'washbay_price', 'message'));
    }

    // select checkpoints
    public function select_checkpt(Request $request)
    {
        $value = $request->value;
        $id = $request->id;
        $service_id = $request->service_id;

        $datas = DB::table('tbl_service_observation_points')->where([['services_id', '=', $service_id], ['observation_points_id', '=', $id]])->first();

        if (! empty($datas)) {
            $review = $datas->review;
            if ($review == 1) {
                DB::update("update tbl_service_observation_points set review = 0 where services_id='$service_id' and observation_points_id='$id'");
            } else {
                DB::update("update tbl_service_observation_points set review = 1 where services_id='$service_id' and observation_points_id='$id'");
            }
        } else {
            $data = new tbl_service_observation_points;
            $data->services_id = $service_id;
            $data->observation_points_id = $id;
            $data->review = $value;
            $data->save();
        }
    }

    // get obs. points
    public function Get_Observation_Pts(Request $request)
    {
        $s_id = $request->service_id;

        $product = DB::table('tbl_products')->get();
        $data = DB::table('tbl_points')
            ->join('tbl_service_observation_points', 'tbl_service_observation_points.observation_points_id', '=', 'tbl_points.id')
            ->where([['tbl_service_observation_points.services_id', '=', $s_id], ['review', '=', 1]])
            ->select('tbl_points.*', 'tbl_service_observation_points.id')
            ->get()->toArray();

        foreach ($data as &$record) {
            $comment = DB::table('tbl_service_pros')
                ->where('tbl_service_observation_points_id', $record->id)
                ->value('category_comments');

            $record->category_comments = $comment;
        }

        // $data = DB::select("select tbl_service_pros.*, tbl_points.*,tbl_service_observation_points.id from tbl_points join tbl_service_observation_points on tbl_service_observation_points.observation_points_id = tbl_points.id join tbl_service_pros on tbl_service_pros.tbl_service_observation_points_id = tbl_service_observation_points.id where tbl_service_observation_points.services_id = $s_id and tbl_service_observation_points.review = 1 and tbl_service_pros.type = 0");

        $html = view('service.observationpoin')->with(compact('s_id', 'product', 'data'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // service delete
    public function destory($id)
    {
        $service1 = DB::table('tbl_jobcard_details')->where('service_id', '=', $id)->first();
        $tbl_invoices1 = DB::table('tbl_invoices')->where('sales_service_id', '=', $id)->first();

        if (! empty($tbl_invoices1)) {
            $in_id = $tbl_invoices1->id;

            $tbl_invoices = DB::table('tbl_invoices')->where('id', '=', $in_id)->first();
            $invoice_no = $tbl_invoices->invoice_number;
            $incomes_id = DB::table('tbl_incomes')->where('invoice_number', '=', $invoice_no)->first();

            if (! empty($incomes_id)) {
                $incomeid = $incomes_id->id;
            }
        }

        if (! empty($service1)) {
            $jobid = $service1->jocard_no;
            $tbl_gatepasses = DB::table('tbl_gatepasses')->where('jobcard_id', '=', $jobid)->delete();
        }

        $tbl_jobcard_details = DB::table('tbl_jobcard_details')->where('service_id', '=', $id)->update(['soft_delete' => 1]);
        $tbl_service_pros = DB::table('tbl_service_pros')->where('service_id', '=', $id)->update(['soft_delete' => 1]);
        $tbl_services = DB::table('tbl_services')->where('id', '=', $id)->update(['soft_delete' => 1]);

        return redirect('/service/list')->with('message', 'Service Deleted Successfully');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $this->destory($id);
        }

        return response()->json(['message' => 'Successfully deleted selected purchase records']);
    }

    // service edit
    public function serviceedit($id)
    {
        $vehical = DB::table('tbl_vehicles')->where('soft_delete', '=', 0)->get()->toArray();
        $customer = DB::table('users')->where([['role', 'Customer'], ['soft_delete', 0]])->get()->toArray();
        $service = Service::with('notes')->find($id);
        $services = Service::select('markers')->where('id', $id)->first(); // Use first() since you're fetching a single record
        $services_kms = Service::select('kms_run')->where('id', $id)->first();
        // $markers = $services ? $services->markers : null;
        $markers = $services ? json_decode($services->markers, true) : [];
        // dd($markers);
        $cus_id = $service->customer_id;
        $vah_id = $service->vehicle_id;
        $tbl_sales = DB::table('tbl_sales')->where('vehicle_id', $vah_id)->first();
        $images1 = DB::table('tbl_service_images')->where('service_id', '=', $id)->get()->toArray();

        if (! empty($tbl_sales)) {
            $regi = DB::table('tbl_sales')->where('customer_id', $cus_id)->first();
        } else {
            $regi = DB::table('tbl_vehicles')->where('id', $vah_id)->first();
        }

        if (! empty($regi)) {
            $regi_no = $regi->registration_no;
        } else {
            $regi_no = null;
        }

        $washbay_data = Washbay::where('service_id', '=', $id)->first();
        $washbayPrice = null;
        if (! empty($washbay_data)) {
            $washbayPrice = $washbay_data->price;
        }

        // Custom Field Data
        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'service'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        // $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if (isAdmin(Auth::User()->role_id)) {
            $branchDatas = Branch::get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0]])->get()->toArray();
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            $branchDatas = Branch::get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0]])->get()->toArray();
        } else {
            $branchDatas = Branch::where('id', $currentUser->branch_id)->get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
        }

        $repairCategoryList = DB::table('table_repair_category')->where([['soft_delete', '=', 0]])->get()->toArray();
        // new added

        return view('/service/edit', compact('service', 'services', 'markers', 'services_kms', 'vehical', 'employee', 'customer', 'regi_no', 'tbl_custom_fields', 'washbayPrice', 'branchDatas', 'repairCategoryList', 'images1'));
    }

    // service update
    public function serviceupdate(Request $request, $id)
    {
        $mot_test_status = $request->motTestStatusCheckbox;
        $mot_charge = $request->motTestCharge;
        $job = $request->jobno;
        $vehicalname = $request->vehicalname;
        $kms = $request->kms;
        $Customername = $request->Customername;
        $details = $request->details;
        $car_details = $request->car_details;
        $markers = $request->input('markers');
        // dd($markers);
        $reg_no = $request->reg_no;
        $title = $request->title;
        $AssigneTo = $request->AssigneTo;
        $service_category = $request->repair_cat;
        $ser_type = $request->service_type;
        $charge = $request->charge;
        // $vehicalname = $request->vehicalname;
        $Customername = $request->Customername;
        $date = $request->date;
        $image = $request->image;

        if ($mot_test_status == 'on') {
            $mot_test_status = 1;
        } else {
            $mot_test_status = 0;
        }

        $color = null;
        if (getDateFormat() == 'm-d-Y') {
            $date = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $date)));
        } else {
            $date = date('Y-m-d H:i:s', strtotime($date));
        }

        if ($ser_type == 'free') {
            $charge = '0';
        }

        if ($ser_type == 'paid') {
            $charge = $request->charge;
        }

        $available = Service::find($id);
        $available->vehicle_id = $vehicalname;
        $available->service_date = $date;
        $available->title = $title;
        $available->assign_to = $AssigneTo;
        $available->service_category = $service_category;
        $available->charge = $charge;
        $available->customer_id = $Customername;
        $available->detail = $details;
        $available->car_detail = $car_details;
        $available->markers = $markers;
        $available->kms_run = $kms;
        $available->service_type = $ser_type;
        $available->mot_status = $mot_test_status;
        $available->mot_charge = $mot_charge;
        $available->branch_id = $request->branch;
        // Update custom field
        $custom = $request->custom;
        $custom_fileld_value = [];
        $custom_fileld_value_jason_array = [];

        if (! empty($custom)) {
            foreach ($custom as $key => $value) {
                if (is_array($value)) {
                    $add_one_in = implode(',', $value);
                    $custom_fileld_value[] = ['id' => "$key", 'value' => "$add_one_in"];
                } else {
                    $custom_fileld_value[] = ['id' => "$key", 'value' => "$value"];
                }
            }

            $custom_fileld_value_jason_array['custom_fileld_value'] = json_encode($custom_fileld_value);

            foreach ($custom_fileld_value_jason_array as $key1 => $val1) {
                $customerdata = $val1;
            }
            $available->custom_field = $customerdata;
        }
        $available->save();

        // $delete = Notes::where('entity_id', '=', $id)->delete();
        $customer = User::find($available->customer_id);
        $toEmail = $customer->email;
        // Save Notes data
        if ($available->save()) {
            if ($request->notes !== null) {
                saveNotes($available, $request->notes, $toEmail);
            }
        }

        $get_service_id = DB::table('tbl_services')->where('job_no', '=', $job)->pluck('id')->first();

        $image = $request->image;
        if (! empty($image)) {
            $files = $image;

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $file->move(public_path().'/service/', $file->getClientOriginalName());
                $images = new tbl_service_images;
                $images->service_id = $get_service_id;
                $images->image = $filename;
                $images->save();
            }
        }

        $washbay_status = $request->washbay;

        if ($washbay_status == 'on') {
            $washbay_charge = $request->washBayCharge;

            $washbays = new Washbay;
            $washbays->service_id = $get_service_id;
            $washbays->jobcard_no = $job;
            $washbays->vehicle_id = $vehicalname;
            $washbays->customer_id = $Customername;
            $washbays->price = $washbay_charge;
            $washbays->save();
        }
        $washbay_price = Washbay::where([['jobcard_no', '=', $job], ['customer_id', '=', $Customername]])->first();

        $service_data = Service::find($id);
        $veh_id = $service_data->vehicle_id;
        $ser_id = $service_data->id;
        $cus_id = $service_data->customer_id;
        $counpan_no = [];
        $job_card_data = DB::table('tbl_jobcard_details')->where([['customer_id', '=', $cus_id], ['vehicle_id', '=', $veh_id]])->get()->toArray();
        if (! empty($job_card_data)) {
            foreach ($job_card_data as $job_card_datas) {
                $counpan_no[] = $job_card_datas->coupan_no;
            }

            $free_coupan = DB::table('tbl_services')->where([['customer_id', '=', $Customername], ['service_type', '=', 'free'], ['vehicle_id', '=', $vehicalname], ['job_no', 'like', 'C%']])->get()->toArray();
        } else {
            $free_coupan = DB::table('tbl_services')->where([['customer_id', '=', $Customername], ['service_type', '=', 'free'], ['vehicle_id', '=', $vehicalname], ['job_no', 'like', 'C%']])->whereNotIn('job_no', $counpan_no)->get()->toArray();
        }

        $sale_date = DB::table('tbl_sales')->where('vehicle_id', '=', $veh_id)->first();
        if (! empty($sale_date)) {
            $color_id = $sale_date->color_id;
            $color = DB::table('tbl_colors')->where('id', '=', $color_id)->first();
        }
        $num_plate = $request->number_plate;
        // $vehical = DB::table('tbl_vehicles')->where('number_plate', 'like', "%$num_plate")->first();
        $vehical = DB::table('tbl_vehicles')->where('id', '=', "$veh_id")->first();
        $model_id = getModel_id($vehical->modelname);

        $obs_point = DB::table('tbl_service_observation_points')->where([['services_id', '=', $ser_id], ['review', '=', 1]])->get()->toArray();

        $sale_regi = DB::table('tbl_sales')->where('vehicle_id', '=', $vehicalname)->first();
        if (! empty($sale_regi)) {
            DB::update("update tbl_sales set registration_no = '$reg_no' where vehicle_id = $vehicalname");
        } else {
            DB::update("update tbl_vehicles set registration_no = '$reg_no' where id = $vehicalname");
        }
        $logo = DB::table('tbl_settings')->first();
        $inspection_points_library_data = DB::table('inspection_points_library')->get();

        $mot_inspections_data = DB::table('mot_vehicle_inspection')->where('service_id', '=', $id)->get()->toArray();
        $mot_inspections_answers = '';

        if (! empty($mot_inspections_data)) {
            foreach ($mot_inspections_data as $key => $value) {
                $mot_inspections_answers = json_decode($value->answer_question_id, true);
            }
        }

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        $names = null;
        if (isAdmin(Auth::User()->role_id)) {
            $tbl_checkout_categories = DB::table('tbl_checkout_categories')->where([['vehicle_id', '=', $model_id], ['soft_delete', '=', 0]])->orWhere('vehicle_id', '=', 0)->where('branch_id', '=', $adminCurrentBranch->branch_id)->get()->toArray();
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            $tbl_checkout_categories = DB::table('tbl_checkout_categories')->where([['vehicle_id', '=', $model_id], ['soft_delete', '=', 0]])->orWhere('vehicle_id', '=', 0)->get()->toArray();
        } else {
            $tbl_checkout_categories = DB::table('tbl_checkout_categories')->where([['vehicle_id', '=', $model_id], ['soft_delete', '=', 0]])->orWhere('vehicle_id', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->get()->toArray();
        }
        $jobcard_detail = JobcardDetail::where('service_id', '=', $id)->first();
        $message = 'Service Updated Successfully';

        // die(json_encode(compact('service_data', 'vehical', 'tbl_checkout_categories', 'sale_date', 'color', 'obs_point', 'free_coupan', 'logo', 'inspection_points_library_data', 'washbay_price', 'jobcard_detail', 'message', 'mot_inspections_answers')));
        // dd($service_data,$vehical,$currentUser,$tbl_checkout_categories,$sale_date,$color,$obs_point,$free_coupan,$logo,$inspection_points_library_data,$washbay_price,$jobcard_detail,$message,$mot_inspections_answers);
        // return view('/service/tab', compact('message','jobcard_detail','service_data','vehical','tbl_checkout_categories'));
        return view('/service/jobcard_form', compact('service_data', 'vehical', 'tbl_checkout_categories', 'sale_date', 'color', 'obs_point', 'free_coupan', 'logo', 'inspection_points_library_data', 'washbay_price', 'jobcard_detail', 'message', 'mot_inspections_answers'));
    }

    // get used coupon data
    public function Used_Coupon_Data(Request $request)
    {
        $cpn_no = $request->coupon_no;

        $used_cpn_data = DB::table('tbl_jobcard_details')->where('coupan_no', $cpn_no)->first();
        $status = $used_cpn_data->done_status;
        $jb_no = $used_cpn_data->jocard_no;

        $vhi_no = DB::table('tbl_services')->where('job_no', $cpn_no)->first();
        $vehi_name = $vhi_no->vehicle_id;
        $regi = DB::table('tbl_sales')->where('vehicle_id', $vehi_name)->first();
        $ser_tab = DB::table('tbl_services')->where('job_no', $jb_no)->first();
        $logo = DB::table('tbl_settings')->first();

        if (! empty($used_cpn_data)) {
            $service_id = $used_cpn_data->service_id;
            $cus_id = $used_cpn_data->customer_id;
            $custo_info = DB::table('users')->where('id', $cus_id)->first();
            $mob = $custo_info->mobile_no;
            $city = $custo_info->city_id;
            $state = $custo_info->state_id;
            $country = $custo_info->country_id;

            $all_data = DB::table('tbl_service_pros')->where([['service_id', $service_id], ['type', '=', 0]])->get()->toArray();
            $all_data2 = DB::table('tbl_service_pros')->where([['service_id', $service_id], ['type', '=', 1]])->get()->toArray();
        }

        $html = view('service.couponmodel')->with(compact('service_id', 'custo_info', 'logo', 'mob', 'custo_info', 'status', 'vehi_name', 'regi', 'city', 'state', 'country', 'all_data', 'all_data2', 'used_cpn_data', 'vhi_no', 'ser_tab', 'cpn_no'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // service modal view
    public function serviceview(Request $request)
    {
        $ser_id = $request->servicesid;
        $vhi_no = Service::where('id', $ser_id)->first();
        $vehi_name = $vhi_no->vehicle_id;
        $cus_id = $vhi_no->customer_id;

        $tbl_sales = Sale::where('vehicle_id', $vehi_name)->first();
        if (! empty($tbl_sales)) {
            $regi = Sale::where('vehicle_id', $vehi_name)->first();
        } else {
            $regi = Vehicle::where('id', $vehi_name)->first();
        }

        $logo = Setting::first();
        $custo_info = User::where('id', $cus_id)->first();

        $used_cpn_data = JobcardDetail::where('service_id', $ser_id)->first();

        $service_id = $status = $all_data = $all_data2 = null;
        if (! empty($used_cpn_data)) {
            $status = $used_cpn_data->done_status;
            $service_id = $used_cpn_data->service_id;

            $all_data = DB::table('tbl_service_pros')->where([['service_id', $service_id], ['type', '=', 0]])->get()->toArray();
            $all_data2 = DB::table('tbl_service_pros')->where([['service_id', $service_id], ['type', '=', 1]])->get()->toArray();
        }

        // For Custom Field Data
        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'service'], ['always_visable', '=', 'yes']])->get()->toArray();

        $washbay_data = Washbay::where('service_id', '=', $ser_id)->first();

        $html = view('service.servicemodel')->with(compact('service_id', 'custo_info', 'logo', 'custo_info', 'status', 'vehi_name', 'regi', 'all_data', 'all_data2', 'used_cpn_data', 'vhi_no', 'tbl_custom_fields', 'washbay_data'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    /* For get customer name by auto searchable select box */
    public function get_customer_name(Request $request)
    {
        $customer_name = [];

        if ($request->has('q')) {
            $search = $request->q;
            $customer_name = User::select('id', 'name', 'lastname')
                ->where('role', '=', 'Customer')
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }

        return response()->json($customer_name);
    }

    public function free_service(Request $request)
    {
        $vehi_id = $request->vehi_id;
        $cust_id = $request->customer_id;
        $free_coupan = DB::table('tbl_services')->where([['customer_id', '=', $cust_id], ['service_type', '=', 'free'], ['vehicle_id', '=', $vehi_id], ['job_no', 'like', 'C%']])->get()->toArray();
        // dd($free_coupan);

        $coupan = [];

        foreach ($free_coupan as $coupon) {
            $usedData = getUsedCoupon($coupon->customer_id, $coupon->vehicle_id, $coupon->job_no);
            if (! $usedData) {
                // Coupon is unused, store it in the $coupan array
                $coupan[] = $coupon;
            }
        }

        // dd($coupan);
        return response()->json($coupan);
    }

    // get images
    public function getImages(Request $request)
    {
        // $image_id = Input::get('image_id');
        $image_id = $request->image_id;
        $idi = $image_id + 1;
        ?>
        <tr id="image_id_<?php echo $idi; ?>">
            <td>
                <input type="file" id="tax_<?php echo $idi; ?>" name="image[]" class="form-control dropify tax" data-max-file-size="5M">
                <div class="dropify-preview">
                    <span class="dropify-render"></span>
                    <div class="dropify-infos">
                        <div class="dropify-infos-inner">
                            <p class="dropify-filename">
                                <span class="file-icon"></span>
                                <span class="dropify-filename-inner"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <span class="trash_accounts" data-id="<?php echo $idi; ?>"><i class="fa fa-trash"></i> Delete</span>
            </td>
        </tr>
        <script>
            $(document).ready(function() {
                // Basic
                $('.dropify').dropify();

                // Translated
                $('.dropify-fr').dropify({
                    messages: {
                        default: 'Glissez-déposez un fichier ici ou cliquez',
                        replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                        remove: 'Supprimer',
                        error: 'Désolé, le fichier trop volumineux'
                    }
                });

                // Used events
                var drEvent = $('#input-file-events').dropify();

                drEvent.on('dropify.beforeClear', function(event, element) {
                    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                });

                drEvent.on('dropify.afterClear', function(event, element) {
                    alert('File deleted');
                });

                drEvent.on('dropify.errors', function(event, element) {
                    console.log('Has Errors');
                });

                var drDestroy = $('#input-file-to-destroy').dropify();
                drDestroy = drDestroy.data('dropify')
                $('#toggleDropify').on('click', function(e) {
                    e.preventDefault();
                    if (drDestroy.isDropified()) {
                        drDestroy.destroy();
                    } else {
                        drDestroy.init();
                    }
                })
            });
        </script>
<?php
    }

    // delete images
    public function deleteImages(Request $request)
    {
        // $id=Input::get('delete_image');
        $id = $request->delete_image;
        $image = DB::table('tbl_service_images')->where('id', '=', $id)->delete();
    }

    public function view($id)
    {
        $vehical = DB::table('tbl_vehicles')->where('soft_delete', '=', 0)->get()->toArray();
        $service = Service::with('notes')->find($id);

        $job_card_data = JobcardDetail::where('service_id', $id)->first();
        $cus_id = $service->customer_id;
        $customer = DB::table('users')->where('id', $cus_id)->first();
        $vah_id = $service->vehicle_id;
        $tbl_sales = DB::table('tbl_sales')->where('vehicle_id', $vah_id)->first();
        $images1 = DB::table('tbl_service_images')->where('service_id', '=', $id)->get()->toArray();

        if (! empty($tbl_sales)) {
            $regi = DB::table('tbl_sales')->where('customer_id', $cus_id)->first();
        } else {
            $regi = DB::table('tbl_vehicles')->where('id', $vah_id)->first();
        }

        if (! empty($regi)) {
            $regi_no = $regi->registration_no;
        } else {
            $regi_no = null;
        }

        $washbay_data = Washbay::where('service_id', '=', $id)->first();
        $washbayPrice = null;
        if (! empty($washbay_data)) {
            $washbayPrice = $washbay_data->price;
        }

        // Custom Field Data
        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'service'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if (isAdmin(Auth::User()->role_id)) {
            $branchDatas = Branch::get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->get()->toArray();
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            $branchDatas = Branch::get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0]])->get()->toArray();
        } else {
            $branchDatas = Branch::where('id', $currentUser->branch_id)->get();
            $employee = DB::table('users')->where([['role', 'employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
        }

        $repairCategoryList = DB::table('table_repair_category')->where([['soft_delete', '=', 0]])->get()->toArray();

        $image = DB::table('tbl_service_images')->where('service_id', '=', $id)->get()->toArray();
        if (! empty($image)) {
            foreach ($image as $images) {
                $image_name[] = URL::to('/public/service/'.$images->image);
            }
        } else {
            $image_name[] = URL::to('/public/vehicle/avtar.png');
        }
        $available = json_encode($image_name);

        $available1 = json_decode($available, true);
        $washbay_data = Washbay::where('service_id', '=', $id)->first();
        $all_data = DB::table('tbl_service_pros')->where([['service_id', $id], ['type', '=', 0]])->get()->toArray();
        $all_data2 = DB::table('tbl_service_pros')->where([['service_id', $id], ['type', '=', 1]])->get()->toArray();

        $logo = DB::table('tbl_settings')->first();

        return view('/service/view', compact('service', 'vehical', 'employee', 'customer', 'regi_no', 'tbl_custom_fields', 'washbayPrice', 'branchDatas', 'repairCategoryList', 'available1', 'job_card_data', 'washbay_data', 'all_data', 'all_data2', 'logo'));
    }

    public function viewNotes($id)
    {
        $service = Service::with('notes')->find($id);
        $cus_id = $service->customer_id;
        $customer = DB::table('users')->where([['id', '=', $cus_id], ['soft_delete', 0]])->first();

        return view('/service/showNotes', compact('service', 'customer'));
    }

    // frontend service booking form
    public function frontendBook()
    {
        $holiday = Holiday::ORDERBY('date', 'ASC')->get();

        $last_order = DB::table('tbl_services')->latest()->where('sales_id', '=', null)->get()->first();

        if (! empty($last_order)) {

            $last_full_job_number = $last_order->job_no;
            $last_job_number_digit = substr($last_full_job_number, 1);
            $new_number = 'J'.str_pad($last_job_number_digit + 1, 6, 0, STR_PAD_LEFT);
        } else {
            $new_number = 'J000001';
        }

        $code = $new_number;

        $country = DB::table('tbl_countries')->get()->toArray();

        // vehicle add
        $vehical_type = DB::table('tbl_vehicle_types')->where('soft_delete', '=', 0)->get()->toArray();
        $vehical_brand = DB::table('tbl_vehicle_brands')->where('soft_delete', '=', 0)->get()->toArray();
        $fuel_type = DB::table('tbl_fuel_types')->where('soft_delete', '=', 0)->get()->toArray();
        $model_name = DB::table('tbl_model_names')->where('soft_delete', '=', 0)->get()->toArray();
        $repairCategoryList = DB::table('table_repair_category')->where([['soft_delete', '=', 0]])->get()->toArray();

        return view('/service/frontendBook', compact('holiday', 'code', 'country', 'vehical_brand', 'vehical_type', 'fuel_type', 'model_name', 'repairCategoryList'));
    }

    public function forntendAdd(Request $request)
    {
        $logo = DB::table('tbl_settings')->first();

        // customer details
        $firstname = $request->firstname;
        $email = $request->email;
        $password = $logo->default_password;

        // Check if email already exists
        $existingEmail = User::where('email', $request->email)->where('soft_delete', 0)->exists();
        if ($existingEmail) {
            return redirect('/service/frontendBook')->with('message', 'Email you entered is already registered.');
        }

        // Check if number plate already exists
        $existingNumberPlate = Vehicle::where('number_plate', $request->number_plate)->exists();

        if ($existingNumberPlate) {

            return redirect('/service/frontendBook')->with('message', 'Number Plate you entered is already registered.');
        }

        // Get user role id from Role table
        $getRoleId = Role::where('role_name', '=', 'Customer')->first();

        $customer = new User;
        $customer->name = $request->firstname;
        $customer->lastname = $request->lastname;
        $customer->email = $request->email;
        $customer->password = bcrypt($password);
        $customer->mobile_no = $request->mobile;
        $customer->address = $request->address;
        $customer->country_id = $request->country_id;
        $customer->state_id = $request->state_id;
        $customer->city_id = $request->city;
        $customer->role = 'Customer';
        $customer->role_id = $getRoleId->id; /* Store Role table User Role Id */
        $customer->language = 'en';
        $customer->timezone = 'UTC';
        $customer->image = 'avtar.png';
        $customer->save();

        $customer_id = $customer->id;

        /* For data store inside Role_user table */
        if ($customer->save()) {
            $currentUserId = $customer->id;

            $role_user_table = new Role_user;
            $role_user_table->user_id = $currentUserId;
            $role_user_table->role_id = $getRoleId->id;
            $role_user_table->save();
        }

        if (! is_null($email)) {
            // email format
            try {
                $logo = DB::table('tbl_settings')->first();
                $systemname = $logo->system_name;

                $emailformats = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'User_registration')->first();
                if ($emailformats->is_send == 0) {
                    if ($customer->save()) {
                        $emailformat = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'User_registration')->first();
                        $mail_format = $emailformat->notification_text;
                        $notification_label = $emailformat->notification_label;
                        $mail_subjects = $emailformat->subject;
                        $mail_send_from = $emailformat->send_from;
                        $search1 = ['{ system_name }'];
                        $replace1 = [$systemname];
                        $mail_sub = str_replace($search1, $replace1, $mail_subjects);

                        $systemlink = URL::to('/');
                        $search = ['{ system_name }', '{ user_name }', '{ email }', '{ Password }', '{ system_link }'];
                        $replace = [$systemname, $firstname, $email, $password, $systemlink];

                        $email_content = str_replace($search, $replace, $mail_format);
                        $redirect_url = url('/customer/list');
                        $systemLink = URL::to('/');
                        // Render Blade template with all required variables
                        $blade_view = View::make('mail.template', [
                            'notification_label' => $notification_label,
                            'email_content' => $email_content,
                            'redirect_url' => $redirect_url,
                            'system_link' => $systemLink,
                        ])->render();

                        // Send email
                        try {
                            Mail::send([], [], function ($message) use ($email, $mail_sub, $blade_view, $mail_send_from) {
                                $message->to($email)->subject($mail_sub);
                                $message->from($mail_send_from);
                                $message->html($blade_view, 'text/html');
                            });
                        } catch (\Exception $e) {
                            \Log::error('Error sending User_registration email: '.$e->getMessage());
                        }
                      
                        $systemname = $logo->system_name;
                        $systemlink = URL::to('/');
                        $messageBody = 'Welcome to '.$systemname."Your account is created successfully. Your login details are as follows: Email:\n ".$email."\n Password: ".$password."\nLogin Url :".$systemlink;
                        $receiverNumber = getMobileNoWithCode($customer->id);
                        if (\Schema::hasTable('sms_settings') && View::exists('smsaddon::sms_setting')) {
                            \Illuminate\Support\Facades\Http::post(route('smsaddon.send-sms'), ['messageBody' => $messageBody, 'receiver_mobile' => $receiverNumber]);
                        }
                        // Store email log entry
                        $emailLog = new EmailLog;
                        $emailLog->recipient_email = $email;
                        $emailLog->subject = $mail_sub;
                        $emailLog->content = $email_content;
                        $emailLog->save();
                    }
                }
            } catch (\Exception $e) {
            }
        }

        // vehicle details
        $vehical = new Vehicle;
        $vehical->vehicletype_id = $request->vehical_id;
        $vehical->vehiclebrand_id = $request->vehicabrand;
        $vehical->fuel_id = $request->fueltype;
        $vehical->modelname = $request->modelname;
        $vehical->number_plate = $request->number_plate;
        $vehical->customer_id = $customer_id;
        $vehical->branch_id = 1;
        $vehical->save();
        // dd('number_plate');
        $vehical_id = $vehical->id;

        // service details
        $jobno = $request->jobno;
        $s_date = $request->s_date;
        $repair_cat = $request->repair_cat;

        $service = new Service;
        $service->job_no = $jobno;
        $service->vehicle_id = $vehical_id;
        $service->service_date = $s_date;
        $service->assign_to = $logo->default_emp;
        $service->service_category = $repair_cat;
        $service->done_status = 0;
        $service->charge = $logo->default_charge;
        $service->customer_id = $customer_id;
        $service->service_type = 'paid';
        $service->branch_id = 1;
        $service->mot_status = 0;
        $service->create_by = $customer_id;
        $service->save();

        $service_id = $service->id;

        $tbl_jobcard_details = new JobcardDetail;
        $tbl_jobcard_details->customer_id = $customer_id;
        $tbl_jobcard_details->vehicle_id = $vehical_id;
        $tbl_jobcard_details->service_id = $service_id;
        $tbl_jobcard_details->jocard_no = $jobno;
        $tbl_jobcard_details->in_date = $s_date;
        $tbl_jobcard_details->save();

        return redirect('/service/frontendBook')->with('message', 'Service Booked Successfully');
    }
}
