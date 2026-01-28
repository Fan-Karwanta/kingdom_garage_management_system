<?php

namespace App\Http\Controllers;

use App\Branch;
use App\BranchSetting;
use App\CustomField;
use App\EmailLog;
use App\Http\Requests\CustomerAddEditFormRequest;
use App\Income;
use App\Invoice;
use App\JobcardDetail;
use App\Kanban\TaskKanban;
use App\Notes;
use App\Role;
use App\Role_user;
use App\Service;
use App\User;
use App\Vehicle;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Mail;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use URL;

class Customercontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // customer addform
    public function customeradd()
    {
        $country = DB::table('tbl_countries')->get()->toArray();
        $onlycustomer = DB::table('users')->where([['role', '=', 'Customer'], ['id', '=', Auth::User()->id]])->first();

        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();
        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if (isAdmin(Auth::User()->role_id)) {
            $branchDatas = Branch::get();
        } else {
            $branchDatas = Branch::where('id', $currentUser->branch_id)->get();
        }

        return view('customer.add', compact('country', 'onlycustomer', 'tbl_custom_fields', 'branchDatas'));
    }

    // customer store
    public function storecustomer(CustomerAddEditFormRequest $request)
    {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $displayname = $request->displayname;
        $password = $request->password;
        $gender = $request->gender;
        $birthdate = $request->dob;
        $email = $request->email;
        $mobile = $request->mobile;
        $landlineno = $request->landlineno;
        $address = $request->address;
        $country_id = $request->country_id;
        $state_id = $request->state_id;
        $city = $request->city;
        $company_name = $request->company_name;
        $branch_id = $request->branch;
        $image = $request->image;
        $tax_id = $request->tax_id;

        $dob = null;
        if (! empty($birthdate)) {
            if (getDateFormat() == 'm-d-Y') {
                $dob = date('Y-m-d', strtotime(str_replace('-', '/', $birthdate)));
            } else {
                $dob = date('Y-m-d', strtotime($birthdate));
            }
        }

        if (! empty($email)) {
            $email = $email;
        } else {
            $email = null;
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
        $customer->country_id = $country_id;
        $customer->state_id = $state_id;
        $customer->city_id = $city;
        $customer->branch_id = $branch_id;
        $customer->company_name = $company_name;
        $customer->tax_id = $tax_id;
        $customer->create_by = Auth::User()->id;

        if (! empty($image)) {
            $file = $image;
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

        // custom field
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
            $customer->custom_field = $customerdata;
        }
        $customer->save();

        // Save Notes data
        if ($customer->save()) {
            // if ($request->notes !== null) {
            // 	saveNotes($customer, $request->notes, $customer->email);
            // }
            if (! empty($request->notes)) {
                // Filter notes to exclude empty ones
                $validNotes = array_filter($request->notes, function ($note) {
                    return ! empty($note['note_text']) || (! empty($note['note_file']) && is_array($note['note_file']));
                });

                // Only call saveNotes if we have valid notes
                if (! empty($validNotes)) {
                    saveNotes($customer, $request->notes, $customer->email);
                }
            }
        }

        /* For data store inside Role_user table */
        if ($customer->save()) {
            $currentUserId = $customer->id;

            $role_user_table = new Role_user;
            $role_user_table->user_id = $currentUserId;
            $role_user_table->role_id = $getRoleId->id;
            $role_user_table->save();
        }

        $enable_customer_login = DB::table('tbl_settings')->where('id', '=', '1')->first()->customer_login ?? null;
        if (! is_null($email) && $enable_customer_login == 1) {
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
                       // Log::info('Before blade view');
                        // Render Blade template with all required variables
                        $blade_view = View::make('mail.template', [
                            'notification_label' => $notification_label,
                            'email_content' => $email_content,
                            'redirect_url' => $redirect_url,
                            'system_link' => $systemlink,
                        ])->render();
                        //Log::info('After blade view');
                        // Send email
                        try {
                           // Log::info('preparing blade view file');
                            Mail::send([], [], function ($message) use ($email, $mail_sub, $blade_view, $mail_send_from) {
                                $message->to($email)->subject($mail_sub);
                                $message->from($mail_send_from);
                                $message->html($blade_view, 'text/html');
                            });
                        } catch (\Exception $e) {
                            Log::error('Error sending email: '.$e->getMessage());
                        }
                       
                        $messageBody = 'Welcome to '.$systemname."Your account is created successfully. Your login details are as follows:\n Email: ".$email."\n Password: ".$password."\nLogin Url :".$systemlink;
                        $receiverNumber = getMobileNoWithCode($customer->id);
                        // Log::info('Receiver number is'.$receiverNumber);
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
                Log::info('email sending failed run main catche');
            }
        }

        return redirect('/customer/list')->with('message', 'Customer Added Successfully');
    }

    // customer list
    // public function index()
    // {
    // if (!isAdmin(Auth::User()->role_id)) {

    // 	if (getUsersRole(Auth::user()->role_id) == 'Customer') {
    // 		if (Gate::allows('customer_owndata')) {
    // 			$customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->get();
    // 		} else {
    // 			$customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
    // 		}
    // 	} else {
    // 		if (Gate::allows('customer_owndata')) {
    // 			$customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0], ['create_by', '=', Auth::User()->id]])->orderBy('id', 'DESC')->get();
    // 		} else {
    // 			$customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
    // 		}
    // 	}
    // } else {
    // 	$customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
    // }

    // 	return view('customer.list', compact('customer'));
    // }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query()
                ->where('role', '=', 'Customer')
                ->where('soft_delete', '=', 0)
                ->select('id', 'name', 'lastname', 'email', 'mobile_no', 'image');

            // Roles based filtering
            if (! isAdmin(Auth::User()->role_id)) {
                $userRole = getUsersRole(Auth::user()->role_id);

                if ($userRole == 'Customer') {
                    if (Gate::allows('customer_owndata')) {
                        $query->where('id', '=', Auth::User()->id);
                    }
                } else {
                    if (Gate::allows('customer_owndata')) {
                        $query->where('create_by', '=', Auth::User()->id);
                    }
                }
            }
            // Search functionality
            $searchValue = $request->input('search.value');
            if ($searchValue) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('lastname', 'LIKE', "%{$searchValue}%")
                        ->orWhere('email', 'LIKE', "%{$searchValue}%")
                        ->orWhere('mobile_no', 'LIKE', "%{$searchValue}%");
                });
            }

            // Get the total filtered records
            $filteredRecords = $query->count();

            // Pagination and ordering
            $columns = ['id', 'name', 'lastname', 'email', 'mobile_no', 'vehicle_list', 'image'];
            $data = $query->orderBy(
                $columns[$request->input('order.0.column', 0)],
                $request->input('order.0.dir', 'asc')
            )
                ->offset($request->input('start', 0))
                ->limit($request->input('length', 10))
                ->get();

            // Prepare response
            $response = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => User::where('role', '=', 'Customer')->where('soft_delete', '=', 0)->count(),
                'recordsFiltered' => $filteredRecords,
                'data' => $data->map(function ($customer) {

                    return [
                        'id' => $customer->id,
                        'name' => '<a href="'.url('/customer/list/'.$customer->id).'" class="serviceview">'.$customer->name.'</a>',
                        'lastname' => '<a href="'.url('/customer/list/'.$customer->id).'" class="serviceview">'.$customer->lastname.'</a>',
                        'image' => '<img src="'.url('public/customer/'.$customer->image).'" width="50px" height="50px" class="datatable_img">',
                        'vehicle_list' => getVehiclesdata($customer->id),
                        'email' => $customer->email,
                        'mobile_no' => $customer->mobile_no,
                        'action' => '<div class="dropdown_toggle">
                                    <img src="'.asset('public/img/list/dots.png').'" class="btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonAction">
                                        '.(auth()->user()->can('customer_view') ? '<li><a class="dropdown-item" href="'.url('/customer/list/'.$customer->id).'"><img src="'.asset('public/img/list/Vector.png').'" class="me-3"> '.trans('message.View').'</a></li>' : '').'
                                        '.(auth()->user()->can('customer_edit') ? '<li><a class="dropdown-item" href="'.url('/customer/list/edit/'.$customer->id).'"><img src="'.asset('public/img/list/Edit.png').'" class="me-3"> '.trans('message.Edit').'</a></li>' : '').'
                                        '.(auth()->user()->can('customer_delete') ? '<div class="dropdown-divider m-0"></div><li><a class="dropdown-item deletecustomers" url="'.url('/customer/list/delete/'.$customer->id).'" style="color:#FD726A"><img src="'.asset('public/img/list/Delete.png').'" class="me-3"> '.trans('message.Delete').'</a></li>' : '').'
                                    </ul>
                                 </div>',
                    ];
                }),
            ];

            return response()->json($response);
        }
        if (! isAdmin(Auth::User()->role_id)) {

            if (getUsersRole(Auth::user()->role_id) == 'Customer') {
                if (Gate::allows('customer_owndata')) {
                    $customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->get();
                } else {
                    $customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
                }
            } else {
                if (Gate::allows('customer_owndata')) {
                    $customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0], ['create_by', '=', Auth::User()->id]])->orderBy('id', 'DESC')->get();
                } else {
                    $customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
                }
            }
        } else {
            $customer = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
        }

        return view('customer.list', compact('customer'));
    }

    // customer show
    public function customershow($id)
    {
        $viewid = $id;
        $userid = Auth::User()->id;
        $tbl_vehicles = Vehicle::where('customer_id', '=', $viewid)->where('soft_delete', '=', 0)->orderBy('created_at', 'desc')->take(3)->get();
        $jobCards = Service::orderBy('id', 'desc')->where([['job_no', 'like', 'J%'], ['customer_id', '=', $viewid]])->whereNotIn('quotation_modify_status', [1])->take(3)->get();
        $quotations = DB::table('tbl_services')->where([['customer_id', '=', $viewid], ['job_no', 'like', 'J%'], ['is_quotation', '=', 1], ['soft_delete', '=', 0]])->orderBy('id', 'DESC')->take(3)->get();
        $invoices = Invoice::where([['customer_id', '=', $viewid], ['soft_delete', '=', 0]])->where('type', '!=', 2)->orderBy('id', 'DESC')->take(3)->get();

        // dd($quotations);
        if (! isAdmin(Auth::User()->role_id)) {
            if (getUsersRole(Auth::user()->role_id) == 'Customer') {
                $customer = User::where('id', '=', $id)->first();

                $tbl_custom_fields = CustomField::where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes']])->get();

                $freeservice = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'free']])
                    ->where('tbl_services.customer_id', '=', $id)
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->select('tbl_services.*')
                    ->get();

                $paidservice = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'paid']])
                    ->where('tbl_services.customer_id', '=', $id)
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->select('tbl_services.*')
                    ->get();

                $repeatjob = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_category', '=', 'repeat job']])
                    ->where('tbl_services.customer_id', '=', $id)
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->select('tbl_services.*')
                    ->get();
            } elseif (getUsersRole(Auth::user()->role_id) == 'Employee') {
                $customer = DB::table('users')->where('id', '=', $id)->first();

                $tbl_custom_fields = CustomField::where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes']])->get();

                $freeservice = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'free']])
                    ->where('tbl_services.assign_to', '=', $userid)
                    ->where('tbl_services.customer_id', '=', $id)
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->select('tbl_services.*')
                    ->get();

                $paidservice = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'paid']])
                    ->where('tbl_services.assign_to', '=', $userid)
                    ->where('tbl_services.customer_id', '=', $id)
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->select('tbl_services.*')
                    ->get();

                $repeatjob = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_category', '=', 'repeat job']])
                    ->where('tbl_services.assign_to', '=', $userid)
                    ->where('tbl_services.customer_id', '=', $id)
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->select('tbl_services.*')
                    ->get();
            } elseif (getUsersRole(Auth::user()->role_id) == 'Support Staff' || getUsersRole(Auth::user()->role_id) == 'Accountant' || getUsersRole(Auth::user()->role_id) == 'Branch Admin') {

                $customer = User::where('id', '=', $id)->first();

                $tbl_custom_fields = CustomField::where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes']])->get();

                $freeservice = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'free']])
                    ->where('tbl_services.customer_id', '=', $id)
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->select('tbl_services.*')
                    ->get();

                $paidservice = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'paid']])
                    ->where('tbl_services.customer_id', '=', $id)
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->select('tbl_services.*')
                    ->get();

                $repeatjob = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_category', '=', 'repeat job']])
                    ->where('tbl_services.customer_id', '=', $id)
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->select('tbl_services.*')
                    ->get();
            }
        } else {
            $customer = User::where('id', '=', $id)->first();

            $tbl_custom_fields = CustomField::where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes']])->get();

            $freeservice = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'free']])
                ->where('tbl_services.customer_id', '=', $id)
                ->orderBy('tbl_services.id', 'desc')->take(5)
                ->select('tbl_services.*')
                ->get();

            $paidservice = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'paid']])
                ->where('tbl_services.customer_id', '=', $id)
                ->orderBy('tbl_services.id', 'desc')->take(5)
                ->select('tbl_services.*')
                ->get();

            $repeatjob = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_category', '=', 'repeat job']])
                ->where('tbl_services.customer_id', '=', $id)
                ->orderBy('tbl_services.id', 'desc')->take(5)
                ->select('tbl_services.*')
                ->get();
        }

        return view('customer.view', compact('invoices', 'jobCards', 'quotations', 'tbl_vehicles', 'customer', 'viewid', 'freeservice', 'paidservice', 'repeatjob', 'tbl_custom_fields'));
    }

    // free service modal
    public function free_open_model(Request $request)
    {
        // $serviceid = Input::get('f_serviceid');
        $serviceid = $request->f_serviceid;

        $tbl_services = DB::table('tbl_services')->where('id', '=', $serviceid)->first();

        $c_id = $tbl_services->customer_id;
        $v_id = $tbl_services->vehicle_id;

        $s_id = $tbl_services->sales_id;
        $sales = DB::table('tbl_sales')->where('id', '=', $s_id)->first();

        $job = DB::table('tbl_jobcard_details')->where('service_id', '=', $serviceid)->first();
        $s_date = DB::table('tbl_sales')->where('vehicle_id', '=', $v_id)->first();

        $vehical = DB::table('tbl_vehicles')->where('id', '=', $v_id)->first();

        $customer = DB::table('users')->where('id', '=', $c_id)->first();
        $service_pro = DB::table('tbl_service_pros')->where('service_id', '=', $serviceid)
            ->where('type', '=', 0)
            ->get()->toArray();

        $service_pro2 = DB::table('tbl_service_pros')->where('service_id', '=', $serviceid)
            ->where('type', '=', 1)->get()->toArray();

        $tbl_service_observation_points = DB::table('tbl_service_observation_points')->where('services_id', '=', $serviceid)->get()->toArray();

        $service_tax = DB::table('tbl_invoices')->where('sales_service_id', '=', $serviceid)->first();
        if (! empty($service_tax->tax_name)) {
            $service_taxes = explode(', ', $service_tax->tax_name);
        } else {
            $service_taxes = '';
        }
        $discounts = 0;
        if (! empty($service_tax->discount)) {
            $discounts = $service_tax->discount;
        }

        $logo = DB::table('tbl_settings')->first();

        $html = view('customer.freeservice')->with(compact('serviceid', 'tbl_services', 'sales', 'logo', 'job', 's_date', 'vehical', 'customer', 'service_pro', 'service_pro2', 'tbl_service_observation_points', 'service_tax', 'discounts', 'service_taxes'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // paid service modal
    public function paid_open_model(Request $request)
    {
        // $serviceid = Input::get('p_serviceid');
        $serviceid = $request->p_serviceid;

        $tbl_services = DB::table('tbl_services')->where('id', '=', $serviceid)->first();

        $c_id = $tbl_services->customer_id;
        $v_id = $tbl_services->vehicle_id;

        $s_id = $tbl_services->sales_id;
        $sales = DB::table('tbl_sales')->where('id', '=', $s_id)->first();

        $job = DB::table('tbl_jobcard_details')->where('service_id', '=', $serviceid)->first();
        $s_date = DB::table('tbl_sales')->where('vehicle_id', '=', $v_id)->first();

        $vehical = DB::table('tbl_vehicles')->where('id', '=', $v_id)->first();

        $customer = DB::table('users')->where('id', '=', $c_id)->first();
        $service_pro = DB::table('tbl_service_pros')->where('service_id', '=', $serviceid)
            ->where('type', '=', 0)
            ->where('chargeable', '=', 1)
            ->get()->toArray();

        $service_pro2 = DB::table('tbl_service_pros')->where('service_id', '=', $serviceid)
            ->where('type', '=', 1)->get()->toArray();

        $tbl_service_observation_points = DB::table('tbl_service_observation_points')->where('services_id', '=', $serviceid)->get()->toArray();

        $service_tax = DB::table('tbl_invoices')->where('sales_service_id', '=', $serviceid)->first();
        if (! empty($service_tax->tax_name)) {
            $service_taxes = explode(', ', $service_tax->tax_name);
        } else {
            $service_taxes = '';
        }
        // dd($service_tax->tax_name)
        if (! empty($service_tax->discount)) {
            $discount = $service_tax->discount;
        } else {
            $discount = null;
        }
        $logo = DB::table('tbl_settings')->first();

        $html = view('customer.paidservice')->with(compact('serviceid', 'tbl_services', 'sales', 'logo', 'job', 's_date', 'vehical', 'customer', 'service_pro', 'service_pro2', 'tbl_service_observation_points', 'service_tax', 'discount', 'service_taxes'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // repeat service modal
    public function repeat_job_model(Request $request)
    {
        // $serviceid = Input::get('r_service');
        $serviceid = $request->r_service;

        $tbl_services = DB::table('tbl_services')->where('id', '=', $serviceid)->first();

        $c_id = $tbl_services->customer_id;
        $v_id = $tbl_services->vehicle_id;

        $s_id = $tbl_services->sales_id;
        $sales = DB::table('tbl_sales')->where('id', '=', $s_id)->first();

        $job = DB::table('tbl_jobcard_details')->where('service_id', '=', $serviceid)->first();
        $s_date = DB::table('tbl_sales')->where('vehicle_id', '=', $v_id)->first();

        $vehical = DB::table('tbl_vehicles')->where('id', '=', $v_id)->first();

        $customer = DB::table('users')->where('id', '=', $c_id)->first();
        $service_pro = DB::table('tbl_service_pros')->where('service_id', '=', $serviceid)
            ->where('type', '=', 0)
            ->get()->toArray();

        $service_pro2 = DB::table('tbl_service_pros')->where('service_id', '=', $serviceid)
            ->where('type', '=', 1)->get()->toArray();

        $tbl_service_observation_points = DB::table('tbl_service_observation_points')->where('services_id', '=', $serviceid)->get()->toArray();

        $service_tax = DB::table('tbl_invoices')->where('sales_service_id', '=', $serviceid)->first();
        if (! empty($service_tax->tax_name)) {
            $service_taxes = explode(', ', $service_tax->tax_name);
        } else {
            $service_taxes = '';
        }
        if ($service_tax !== null) {

            $discount = $service_tax->discount;
        } else {
            $discount = 0;
        }

        $logo = DB::table('tbl_settings')->first();

        $html = view('customer.repeatservice')->with(compact('serviceid', 'tbl_services', 'sales', 'logo', 'job', 's_date', 'vehical', 'customer', 'service_pro', 'service_pro2', 'tbl_service_observation_points', 'service_tax', 'discount', 'service_taxes'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // customer delete
    public function destroy($id)
    {

        // $customer = DB::table('users')->where('id','=',$id)->delete();
        $customer = User::where('id', '=', $id)->update(['soft_delete' => 1]);

        /*$tbl_incomes = DB::table('tbl_incomes')->where('customer_id','=',$id)->delete();
        $tbl_invoices = DB::table('tbl_invoices')->where('customer_id','=',$id)->delete();
        $tbl_jobcard_details = DB::table('tbl_jobcard_details')->where('customer_id','=',$id)->delete();
        $tbl_gatepasses = DB::table('tbl_gatepasses')->where('customer_id','=',$id)->delete();
        $tbl_sales = DB::table('tbl_sales')->where('customer_id','=',$id)->delete();
        $tbl_services = DB::table('tbl_services')->where('customer_id','=',$id)->delete();*/

        return redirect('/customer/list')->with('message', 'Customer Deleted Successfully');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (! empty($ids)) {
            User::whereIn('id', $ids)->update(['soft_delete' => 1]);
        }

        return redirect('/customer/list')->with('message', 'Customer Deleted Successfully');
    }

    // customer edit
    public function customeredit($id)
    {
        $editid = $id;
        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        if (! isAdmin(Auth::User()->role_id)) {
            if (Gate::allows('customer_owndata')) {
                if (Auth::User()->id == $id) {
                    // dd(Gate::allows('customer_owndata'), 1);
                    $customer = User::with('notes')->find($id);

                    $country = DB::table('tbl_countries')->get()->toArray();
                    $state = DB::table('tbl_states')->where('country_id', $customer->country_id)->get()->toArray();
                    $city = DB::table('tbl_cities')->where('state_id', $customer->state_id)->get()->toArray();

                    $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();
                    $branchDatas = Branch::where('id', '=', $currentUser->branch_id)->get();

                    return view('customer.update', compact('country', 'customer', 'state', 'city', 'editid', 'tbl_custom_fields', 'branchDatas'));
                } elseif (Gate::allows('customer_edit')) {
                    // dd(Gate::allows('customer_edit'), 2);
                    $customer = User::with('notes')->find($id);

                    $country = DB::table('tbl_countries')->get()->toArray();
                    $state = DB::table('tbl_states')->where('country_id', $customer->country_id)->get()->toArray();
                    $city = DB::table('tbl_cities')->where('state_id', $customer->state_id)->get()->toArray();

                    $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();
                    $branchDatas = Branch::where('id', '=', $currentUser->branch_id)->get();

                    return view('customer.update', compact('country', 'customer', 'state', 'city', 'editid', 'tbl_custom_fields', 'branchDatas'));
                } else {
                    return abort('403', 'This action is unauthorized.');
                }
            } elseif (Gate::allows('customer_edit')) {
                $customer = User::with('notes')->find($id);

                $country = DB::table('tbl_countries')->get()->toArray();
                $state = DB::table('tbl_states')->where('country_id', $customer->country_id)->get()->toArray();
                $city = DB::table('tbl_cities')->where('state_id', $customer->state_id)->get()->toArray();

                $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();
                $branchDatas = Branch::where('id', '=', $currentUser->branch_id)->get();

                return view('customer.update', compact('country', 'customer', 'state', 'city', 'editid', 'tbl_custom_fields', 'branchDatas'));
            } else {
                return abort('403', 'This action is unauthorized.');
            }
        } else {
            $customer = User::with('notes')->find($id);

            $country = DB::table('tbl_countries')->get()->toArray();
            $state = DB::table('tbl_states')->where('country_id', $customer->country_id)->get()->toArray();
            $city = DB::table('tbl_cities')->where('state_id', $customer->state_id)->get()->toArray();

            $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();
            $branchDatas = Branch::get();

            return view('customer.update', compact('country', 'customer', 'state', 'city', 'editid', 'tbl_custom_fields', 'branchDatas'));
        }
    }

    // customer update
    public function customerupdate($id, CustomerAddEditFormRequest $request)
    {
        // dd($request->all());
        /*  $this->validate($request, [
         'firstname' => 'regex:/^[(a-zA-Z\s)]+$/u',
         'lastname'=>'regex:/^[(a-zA-Z\s)]+$/u',
         'displayname'=>'regex:/^[(a-zA-Z\s)]+$/u',
         //'mobile'=>'required|max:12|min:6|regex:/^[- +()]*[0-9][- +()0-9]*$/',
         'mobile'=>'required|max:12|min:6|regex:/^[0-9]*$/',
         //Solved by Mukesh [Bug list row number: 625]

         //'landlineno'=>'nullable|max:12|min:6|regex:/^[- +()]*[0-9][- +()0-9]*$/',
         'landlineno'=>'nullable|max:12|min:6|regex:/^[0-9]*$/',
         'image' => 'image|mimes:jpg,png,jpeg',
         'password'=>'nullable|min:6',
         'password_confirmation' => 'nullable|same:password',
        'company_name' => 'nullable|regex:/^[a-zA-Z][a-zA-Z\s\.]*$/',
          ],[
            'displayname.regex' => 'Enter valid display name',
            'firstname.regex' => 'Enter valid first name',
            'lastname.regex' => 'Enter valid last name',
            'landlineno.regex' => 'Enter valid landline no',
            'company_name.regex' => 'Enter only alphabets, space and dot',
        ]);*/

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $displayname = $request->displayname;
        $gender = $request->gender;
        $password = $request->password;
        $mobile = $request->mobile;
        $landlineno = $request->landlineno;
        $address = $request->address;
        $country = $request->country_id;
        $state = $request->state_id;
        $city = $request->city;
        $companyName = $request->company_name;
        $branch_id = $request->branch;
        $updated_email = $request->email;
        $updated_dob = $request->dob;
        $tax_id = $request->tax_id;

        $usimgdtaa = DB::table('users')->where('id', '=', $id)->first();
        $email = $usimgdtaa->email;
        if (! empty($email)) {
            if ($email != $updated_email) {
                $this->validate($request, [
                    'email' => 'required|email|custom_email|unique:users',
                ]);
            }
        }

        $dob = null;
        if (! empty($updated_dob)) {
            if (getDateFormat() == 'm-d-Y') {
                $dob = date('Y-m-d', strtotime(str_replace('-', '/', $updated_dob)));
            } else {
                $dob = date('Y-m-d', strtotime($updated_dob));
            }
        }

        $customer = User::find($id);
        $customer->name = $firstname;
        $customer->lastname = $lastname;
        $customer->display_name = $displayname;
        $customer->gender = $gender;
        $customer->birth_date = $dob;
        $customer->company_name = $companyName;
        $customer->email = $updated_email;

        if (! empty($password)) {
            $customer->password = bcrypt($password);
        }

        $customer->mobile_no = $mobile;
        $customer->landline_no = $landlineno;
        $customer->address = $address;
        $customer->country_id = $country;
        $customer->state_id = $state;
        $customer->city_id = $city;
        $customer->branch_id = $branch_id;
        $customer->tax_id = $tax_id;

        $image = $request->image;
        if (! empty($image)) {
            $file = $image;
            $filename = $file->getClientOriginalName();
            $file->move(public_path().'/customer/', $file->getClientOriginalName());
            $customer->image = $filename;
        }

        $customer->role = 'Customer';

        // custom field
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
            $customer->custom_field = $customerdata;
        }

        $customer->save();

        // $delete = Notes::where('entity_id', '=', $id)->delete();
        // Save Notes data
        if ($customer->save()) {
            // if ($request->notes !== null) {
            // 	saveNotes($customer, $request->notes, $customer->email);
            // }
            if (! empty($request->notes)) {
                // Filter notes to exclude empty ones
                $validNotes = array_filter($request->notes, function ($note) {
                    return ! empty($note['note_text']) || (! empty($note['note_file']) && is_array($note['note_file']));
                });

                // Only call saveNotes if we have valid notes
                if (! empty($validNotes)) {
                    saveNotes($customer, $request->notes, $customer->email);
                }
            }
        }

        // if (!empty($updated_email)) {
        // 	//email format
        // 	$logo = DB::table('tbl_settings')->first();
        // 	$systemname = $logo->system_name;
        // 	$emailformats = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'User_registration')->first();
        // 	if ($emailformats->is_send == 0) {
        // 		if ($customer->save()) {
        // 			$emailformat = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'User_registration')->first();
        // 			$mail_format = $emailformat->notification_text;
        // 			$mail_subjects = $emailformat->subject;
        // 			$mail_send_from = $emailformat->send_from;
        // 			$search1 = array('{ system_name }');
        // 			$replace1 = array($systemname);
        // 			$mail_sub = str_replace($search1, $replace1, $mail_subjects);

        // 			$systemlink = URL::to('/');

        // 			$search = array('{ system_name }', '{ user_name }', '{ email }', '{ Password }', '{ system_link }');
        // 			$replace = array($systemname, $firstname, $email, $password, $systemlink);

        // 			$email_content = str_replace($search, $replace, $mail_format);

        // 			$actual_link = $_SERVER['HTTP_HOST'];
        // 			$startip = '0.0.0.0';
        // 			$endip = '255.255.255.255';
        // 			if (($actual_link == 'localhost' || $actual_link == 'localhost:8080') || ($actual_link >= $startip && $actual_link <= $endip)) {
        // 				//local format email
        // 				$data = array(
        // 					'email' => $email,
        // 					'mail_sub1' => $mail_sub,
        // 					'email_content1' => $email_content,
        // 					'emailsend' => $mail_send_from,
        // 				);
        // 				$data1 = Mail::send('customer.customermail', $data, function ($message) use ($data) {

        // 					$message->from($data['emailsend'], 'noreply');
        // 					$message->to($data['email'])->subject($data['mail_sub1']);
        // 				});
        // 				//dd($data1);
        // 			} else {
        // 				//Live format email
        // 				$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // 				$headers .= 'From:' . $mail_send_from . "\r\n";
        // 				$sended = mail($email, $mail_sub, $email_content, $headers);

        // 				//dd($sended);
        // 			}
        // 		}
        // 	}
        // }

        return redirect('/customer/list')->with('message', 'Customer Updated Successfully');
    }

    // new added
    /* 31/07/2023 */
    public function customersvehicle($id)
    {
        $customer = User::where('id', '=', $id)->first();
        $vehicles = Vehicle::where([['soft_delete', '=', 0], ['customer_id', '=', $id]])->orderBy('id', 'DESC')->get();
        foreach ($vehicles as $vehicle) {
            $get_services_tbl_data = Service::where('vehicle_id', $vehicle->id)->orderBy('id', 'desc')->first();
            if ($get_services_tbl_data) {
                $mot_test_status_yes_or_no = $get_services_tbl_data->mot_status;
                if ($mot_test_status_yes_or_no == 1) {
                    $vehicle->mot = true;
                }
            }
            $lastService = Service::where([['vehicle_id', $vehicle->id], ['sales_id', '=', null]])->orderBy('service_date', 'desc')->first();
            $upcomingService = JobcardDetail::where([['vehicle_id', $vehicle->id]])->orderBy('next_date', 'desc')->first();
            $vehicle->lastServiceDate = $lastService ? Carbon::parse($lastService->service_date) : null;
            $vehicle->upcomingServiceDate = $upcomingService ? Carbon::parse($upcomingService->next_date) : null;
        }

        return view('customer.customervehicle', compact('customer', 'vehicles'));
    }

    public function customersjobcard($id)
    {
        // dd($id);
        $customer = User::where('id', '=', $id)->first();
        $vehicles = Vehicle::where([['soft_delete', '=', 0], ['customer_id', '=', $id]])->orderBy('id', 'DESC')->first();
        $services = Service::orderBy('id', 'desc')->where([['soft_delete', '=', 0], ['job_no', 'like', 'J%'], ['customer_id', '=', $id]])->whereNotIn('quotation_modify_status', [1])->get();

        return view('customer.customerjobcards', compact('customer', 'services'));
    }

    public function customersquotation($id)
    {
        $customer = User::where('id', '=', $id)->first();
        $service = DB::table('tbl_services')->where([['job_no', 'like', 'J%'], ['customer_id', '=', $id], ['is_quotation', '=', 1], ['quotation_modify_status', '=', 1], ['soft_delete', '=', 0]])->orderBy('id', 'DESC')->get();

        return view('customer.customerquotation', compact('customer', 'service'));
    }

    public function customersinvoice($id)
    {
        $customer = User::where('id', '=', $id)->first();
        $invoice = Invoice::where([['customer_id', '=', $id], ['soft_delete', '=', 0]])->where('type', '!=', 2)->orderBy('id', 'DESC')->get();

        return view('customer.customerinvoice', compact('customer', 'invoice'));
    }

    public function customerspayment($id)
    {
        $customer = User::where('id', '=', $id)->first();
        $income = Income::join('tbl_income_history_records', 'tbl_incomes.id', '=', 'tbl_income_history_records.tbl_income_id')->where([['tbl_incomes.soft_delete', 0], ['tbl_incomes.customer_id', $id]])->groupBy('tbl_income_history_records.tbl_income_id')->orderBy('tbl_incomes.id', 'DESC')->select('tbl_incomes.*', 'tbl_income_history_records.*')->get();

        return view('customer.customerpayment', compact('customer', 'income'));
    }

    // View MOT
    public function customersmot(Request $request)
    {
        $logo = DB::table('tbl_settings')->first();
        $page_action = $request->page_action;

        $id = $request->servicesid;

        $get_services_tbl_data = Service::where('id', $id)->orderBy('id', 'desc')->first();

        if ($get_services_tbl_data) {
            $mot_test_status_yes_or_no = $get_services_tbl_data->mot_status;
            // dd($mot_test_status_yes_or_no);
            if ($mot_test_status_yes_or_no == 1) {
                /* Get data of 'vehicle_mot_test_reports' of checked for Mot Test */
                $get_vehicle_mot_test_reports_data = DB::table('vehicle_mot_test_reports')->where('service_id', '=', $id)->latest('date')->first();

                /* Get data of 'mot_vehicle_inspection_data' table with questions and answer_id */
                if ($get_mot_vehicle_inspection_data = DB::table('mot_vehicle_inspection')->where('service_id', '=', $id)->latest('created_at')->first()) {

                    $json_data = $get_mot_vehicle_inspection_data->answer_question_id;
                    $answers_question_id_array = json_decode($json_data, true);
                    /* question and answer in array in key sorted */
                    ksort($answers_question_id_array);
                } else {
                    $mot_test_status_yes_or_no = null;
                    $get_vehicle_mot_test_reports_data = null;
                    $get_mot_vehicle_inspection_data = null;
                    $answers_question_id_array = null;
                }

                /* Get inspection_points_library for display MoT questions */
                $get_inspection_points_library_data = DB::select('select * from inspection_points_library');
            } else {
                $mot_test_status_yes_or_no = null;
                $get_vehicle_mot_test_reports_data = null;
                $get_mot_vehicle_inspection_data = null;
                $answers_question_id_array = null;
                $get_inspection_points_library_data = null;
            }
        } else {
            $mot_test_status_yes_or_no = null;
            $get_vehicle_mot_test_reports_data = null;
            $get_mot_vehicle_inspection_data = null;
            $answers_question_id_array = null;
            $get_inspection_points_library_data = null;
        }
        // dd($get_vehicle_mot_test_reports_data);
        $html = view('customer.motdetailmodel')->with(compact('id', 'page_action', 'mot_test_status_yes_or_no', 'get_vehicle_mot_test_reports_data', 'get_mot_vehicle_inspection_data', 'answers_question_id_array', 'get_inspection_points_library_data', 'logo'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // Print MOT
    public function motPrint(Request $request)
    {
        $logo = DB::table('tbl_settings')->first();
        $page_action = $request->page_action;

        $id = $request->servicesid;

        $get_services_tbl_data = Service::where('id', $id)->orderBy('id', 'desc')->first();

        if ($get_services_tbl_data) {
            $mot_test_status_yes_or_no = $get_services_tbl_data->mot_status;
            // dd($mot_test_status_yes_or_no);
            if ($mot_test_status_yes_or_no == 1) {
                /* Get data of 'vehicle_mot_test_reports' of checked for Mot Test */
                $get_vehicle_mot_test_reports_data = DB::table('vehicle_mot_test_reports')->where('service_id', '=', $id)->latest('date')->first();

                /* Get data of 'mot_vehicle_inspection_data' table with questions and answer_id */
                if ($get_mot_vehicle_inspection_data = DB::table('mot_vehicle_inspection')->where('service_id', '=', $id)->latest('created_at')->first()) {

                    $json_data = $get_mot_vehicle_inspection_data->answer_question_id;
                    $answers_question_id_array = json_decode($json_data, true);
                    /* question and answer in array in key sorted */
                    ksort($answers_question_id_array);
                } else {
                    $mot_test_status_yes_or_no = null;
                    $get_vehicle_mot_test_reports_data = null;
                    $get_mot_vehicle_inspection_data = null;
                    $answers_question_id_array = null;
                }

                /* Get inspection_points_library for display MoT questions */
                $get_inspection_points_library_data = DB::select('select * from inspection_points_library');
            } else {
                $mot_test_status_yes_or_no = null;
                $get_vehicle_mot_test_reports_data = null;
                $get_mot_vehicle_inspection_data = null;
                $answers_question_id_array = null;
                $get_inspection_points_library_data = null;
            }
        } else {
            $mot_test_status_yes_or_no = null;
            $get_vehicle_mot_test_reports_data = null;
            $get_mot_vehicle_inspection_data = null;
            $answers_question_id_array = null;
            $get_inspection_points_library_data = null;
        }

        // dd($get_vehicle_mot_test_reports_data);
        return view('customer.motdetailmodel_print')->with(compact('id', 'page_action', 'mot_test_status_yes_or_no', 'get_vehicle_mot_test_reports_data', 'get_mot_vehicle_inspection_data', 'answers_question_id_array', 'get_inspection_points_library_data', 'logo'))->render();
    }

    public function motpdf($id, Request $request)
    {
        $logo = DB::table('tbl_settings')->first();

        $get_services_tbl_data = Service::where('id', $id)->orderBy('id', 'desc')->first();

        if ($get_services_tbl_data) {
            $mot_test_status_yes_or_no = $get_services_tbl_data->mot_status;
            // dd($mot_test_status_yes_or_no);
            if ($mot_test_status_yes_or_no == 1) {
                /* Get data of 'vehicle_mot_test_reports' of checked for Mot Test */
                $get_vehicle_mot_test_reports_data = DB::table('vehicle_mot_test_reports')->where('service_id', '=', $id)->latest('date')->first();

                /* Get data of 'mot_vehicle_inspection_data' table with questions and answer_id */
                if ($get_mot_vehicle_inspection_data = DB::table('mot_vehicle_inspection')->where('service_id', '=', $id)->latest('created_at')->first()) {

                    $json_data = $get_mot_vehicle_inspection_data->answer_question_id;
                    $answers_question_id_array = json_decode($json_data, true);
                    /* question and answer in array in key sorted */
                    ksort($answers_question_id_array);
                } else {
                    $mot_test_status_yes_or_no = null;
                    $get_vehicle_mot_test_reports_data = null;
                    $get_mot_vehicle_inspection_data = null;
                    $answers_question_id_array = null;
                }

                /* Get inspection_points_library for display MoT questions */
                $get_inspection_points_library_data = DB::select('select * from inspection_points_library');
            } else {
                $mot_test_status_yes_or_no = null;
                $get_vehicle_mot_test_reports_data = null;
                $get_mot_vehicle_inspection_data = null;
                $answers_question_id_array = null;
                $get_inspection_points_library_data = null;
            }
        } else {
            $mot_test_status_yes_or_no = null;
            $get_vehicle_mot_test_reports_data = null;
            $get_mot_vehicle_inspection_data = null;
            $answers_question_id_array = null;
            $get_inspection_points_library_data = null;
        }

        $mpdf = new Mpdf;

        // Get the HTML content from the view
        $html = view('customer.motdetailpdf', compact('mot_test_status_yes_or_no', 'get_vehicle_mot_test_reports_data', 'get_mot_vehicle_inspection_data', 'answers_question_id_array', 'get_inspection_points_library_data', 'logo'));

        // Write HTML content to the PDF
        $mpdf->autoLangToFont = true;
        $mpdf->autoScriptToLang = true;
        $mpdf->WriteHTML($html);

        $filename = 'MOT-'.$get_services_tbl_data->job_no.'.pdf';

        $filePath = public_path('pdf/service/').$filename;

        $mpdf->Output($filePath, Destination::FILE);

        // Check if page_action is set to 'mobile_app'
        if ($request->input('page_action') === 'mobile_app') {
            $filePath = 'public/pdf/service/'.$filename;
            $invoice = URL::to($filePath);

            return redirect($invoice);
        } else {
            return response()->download($filePath, $filename);
        }
    }

    public function customershowNotes($id)
    {
        $customer = User::with('notes')->find($id);

        // Initialize as collections
        $allNotes = collect();

        $customer = User::with('notes')->find($id);
        foreach ($customer->notes as $note) {
            // Add label to service note
            $note->plate = "Cust-$customer->id";
            $note->specificID = null;
            $note->vehicle = null;
            $allNotes->push($note);
        }
        // Fetch notes data for services
        $services = Service::where('customer_id', $id)->with('notes')->get();
        foreach ($services as $service) {
            foreach ($service->notes as $note) {
                // Add label to service note
                $note->plate = getVehicleNumberPlate($service->vehicle_id);
                if ($service->is_quotation == 1) {
                    $quotationNum = getQuotationNumber($service->job_no);
                    $note->specificID = "Quot-$quotationNum";
                } else {
                    $note->specificID = "Job-$service->job_no";
                }
                $note->vehicle = getVehicleName($service->vehicle_id);
                $allNotes->push($note);
            }
        }

        // Fetch notes data for invoices
        $invoices = Invoice::where('customer_id', $id)->with('notes')->get();
        foreach ($invoices as $invoice) {
            foreach ($invoice->notes as $note) {
                // Add label to invoice note
                $note->plate = getVehicleNumberPlateFromService($invoice->sales_service_id);
                $note->specificID = "Inv-$invoice->invoice_number";
                $note->vehicle = getVehicleNameFromInvoice($invoice->sales_service_id);
                $allNotes->push($note);
            }
        }

        // Fetch notes data for vehicles
        $vehicles = Vehicle::where('customer_id', $id)->with('notes')->get();
        foreach ($vehicles as $vehicle) {
            foreach ($vehicle->notes as $note) {
                // Add label to vehicle note
                $type = getVehicleType($vehicle->vehicletype_id);
                $note->plate = getVehicleNumberPlate($vehicle->id);
                $note->specificID = "Type-$type";
                $note->vehicle = getVehicleName($vehicle->id);
                $allNotes->push($note);
            }
        }

        return view('customer.showNotes', compact('customer', 'allNotes'));
    }

    public function getKanban(TaskKanban $kanban)
    {
        // dd($kanban);
        return $kanban->render('kanban');
    }
}
