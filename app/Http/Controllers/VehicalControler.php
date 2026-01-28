<?php

namespace App\Http\Controllers;

use App\Branch;
use App\BranchSetting;
use App\Http\Requests\VehicleAddEditFormRequest;
use App\JobcardDetail;
use App\Notes;
use App\Service;
use App\tbl_fuel_types;
use App\tbl_model_names;
use App\tbl_vehicle_colors;
use App\tbl_vehicle_discription_records;
use App\tbl_vehicle_images;
use App\User;
use App\Vehicle;
use App\Vehiclebrand;
use App\Vehicletype;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use URL;

class VehicalControler extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // vehicle description
    public function decription()
    {
        return view('vehicle.description');
    }

    // vehical list

    public function vehicallist(Request $request)
    {
        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();

        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if ($request->ajax()) {
            $query = DB::table('tbl_vehicles')
                ->leftJoin('tbl_vehicle_types', 'tbl_vehicles.vehicletype_id', '=', 'tbl_vehicle_types.id')
                ->leftJoin('tbl_vehicle_images', 'tbl_vehicles.id', '=', 'tbl_vehicle_images.vehicle_id')
                ->leftJoin('users', 'tbl_vehicles.customer_id', '=', 'users.id')  // Join to get customer name

                ->select(
                    'tbl_vehicles.id',
                    'tbl_vehicle_types.vehicle_type',
                    'tbl_vehicles.number_plate',
                    'tbl_vehicles.modelname',
                    'tbl_vehicles.dom',
                    'tbl_vehicles.branch_id',
                    'tbl_vehicles.engineno',
                    'tbl_vehicle_images.image',
                    'tbl_vehicles.customer_id',
                    'users.name as customer_name',
                    DB::raw('(SELECT MAX(service_date) FROM tbl_services WHERE tbl_services.vehicle_id = tbl_vehicles.id) AS lastServiceDate'),
                    DB::raw('(SELECT MAX(next_date) FROM tbl_jobcard_details WHERE tbl_jobcard_details.vehicle_id = tbl_vehicles.id) AS upcomingServiceDate')
                );

            // Apply role based filter
            if (! isAdmin(Auth::User()->role_id)) {
                $userRole = getUsersRole(Auth::User()->role_id);

                if ($userRole == 'Customer') {
                    $query->where('tbl_vehicles.customer_id', '=', Auth::User()->id)->where('tbl_vehicles.soft_delete', 0);
                } elseif ($userRole == 'Employee') {
                    // Add filters for Employee if necessary
                    $query->where('tbl_vehicles.soft_delete', 0);
                } elseif (in_array($userRole, ['Support Staff', 'Accountant', 'Branch Admin'])) {
                    // For specific roles, filter by branch if applicable
                    if ($adminCurrentBranch) {
                        $query->where('tbl_vehicles.branch_id', '=', $adminCurrentBranch->branch_id)->where('tbl_vehicles.soft_delete', 0);
                    }
                }
            } else {
                // For admin, no additional filtering is applied
                $query->where('tbl_vehicles.soft_delete', 0);
            }
            // Search functionality
            $searchValue = $request->input('search.value');
            if ($searchValue) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('tbl_vehicles.modelname', 'LIKE', "%{$searchValue}%")
                        ->orWhere('tbl_vehicles.number_plate', 'LIKE', "%{$searchValue}%")
                        ->orWhere('tbl_vehicle_types.vehicle_type', 'LIKE', "%{$searchValue}%")
                        ->orWhere('tbl_vehicles.engineno', 'LIKE', "%{$searchValue}%")
                        ->orWhere('users.name', 'LIKE', "%{$searchValue}%")  // Search for customer_name
                        ->orWhereRaw('(SELECT MAX(service_date) FROM tbl_services WHERE tbl_services.vehicle_id = tbl_vehicles.id) LIKE ?', ["%{$searchValue}%"])
                        ->orWhereRaw('(SELECT MAX(next_date) FROM tbl_jobcard_details WHERE tbl_jobcard_details.vehicle_id = tbl_vehicles.id) LIKE ?', ["%{$searchValue}%"]);
                });

            }

            // Pagination and ordering
            $columns = ['id', 'image', 'vehicle_type', 'modelname', 'number_plate', 'customer_name', 'dom', 'lastServiceDate', 'upcomingServiceDate', 'engineno', 'action'];
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
                'recordsTotal' => DB::table('tbl_vehicles')->count(),
                'recordsFiltered' => $query->count(),
                'data' => $data->map(function ($vehicle) {
                    // Dynamically fetch additional data
                    $vehicle->image = getVehicleImage($vehicle->id);
                    $vehicle->customer_name = getCustomerName($vehicle->customer_id);
                    $vehicle->dom = $vehicle->dom ?: trans('message.Not Added');
                    $vehicle->engineno = $vehicle->engineno ?: trans('message.Not Added');

                    // Fetch last service date
                    $lastService = DB::table('tbl_services')
                        ->where('vehicle_id', $vehicle->id)
                        ->orderBy('service_date', 'desc')
                        ->first();
                    $vehicle->lastServiceDate = $lastService ? $lastService->service_date : trans('message.Not Added');

                    // Fetch upcoming service date
                    $upcomingService = DB::table('tbl_jobcard_details')
                        ->where('vehicle_id', $vehicle->id)
                        ->orderBy('next_date', 'desc')
                        ->first();
                    $vehicle->upcomingServiceDate = $upcomingService ? $upcomingService->next_date : trans('message.Not Added');

                    return [
                        'id' => $vehicle->id,
                        'image' => '<a href="'.url('/vehicle/list/view/'.$vehicle->id).'">
									<img src="'.url('public/vehicle/'.$vehicle->image).'" width="52px" height="52px" class="datatable_img">
								</a>',
                        'vehicle_type' => '<a href="'.url('/vehicle/list/view/'.$vehicle->id).'">'.$vehicle->vehicle_type.' </a>',
                        'modelname' => $vehicle->modelname,
                        'number_plate' => $vehicle->number_plate,
                        'customer_name' => $vehicle->customer_name,
                        'dom' => $vehicle->dom,
                        'lastServiceDate' => $vehicle->lastServiceDate,
                        'upcomingServiceDate' => $vehicle->upcomingServiceDate ?: trans('message.Not Added'),
                        'engineno' => $vehicle->engineno,
                        'action' => '<div class="dropdown_toggle">
									<img src="'.asset('public/img/list/dots.png').'" class="btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
									<ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonAction">
										'.(auth()->user()->can('vehicle_view') ? '<li><a class="dropdown-item" href="'.url('/vehicle/list/view/'.$vehicle->id).'"><img src="'.asset('public/img/list/Vector.png').'" class="me-3"> '.trans('message.View').'</a></li>' : '').'
										'.(auth()->user()->can('vehicle_edit') ? '<li><a class="dropdown-item" href="'.url('/vehicle/list/edit/'.$vehicle->id).'"><img src="'.asset('public/img/list/Edit.png').'" class="me-3"> '.trans('message.Edit').'</a></li>' : '').'
										'.(auth()->user()->can('vehicle_delete') ? '<div class="dropdown-divider m-0"></div><li><a class="dropdown-item deletedatas" url="'.url('/vehicle/list/delete/'.$vehicle->id).'" style="color:#FD726A"><img src="'.asset('public/img/list/Delete.png').'" class="me-3"> '.trans('message.Delete').'</a></li>' : '').'
									</ul>
								</div>',
                    ];
                }),
            ];

            return response()->json($response);
        }
        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        // $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

        if (! isAdmin(Auth::User()->role_id)) {
            if (getUsersRole(Auth::User()->role_id) == 'Customer') {
                $vehicals = Vehicle::where([['soft_delete', '=', 0], ['customer_id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->get();
            } elseif (getUsersRole(Auth::User()->role_id) == 'Employee') {
                $vehicals = Vehicle::where([['soft_delete', 0]])->orderBy('id', 'DESC')->get();
            } elseif (getUsersRole(Auth::user()->role_id) == 'Support Staff' || getUsersRole(Auth::user()->role_id) == 'Accountant' || getUsersRole(Auth::user()->role_id) == 'Branch Admin') {

                $vehicals = Vehicle::where([['soft_delete', 0]])->orderBy('id', 'DESC')->get();
            }
        } else {
            $vehicals = Vehicle::where([['soft_delete', 0]])->orderBy('id', 'DESC')->get();
        }

        foreach ($vehicals as $vehical) {
            $lastService = Service::where([['vehicle_id', $vehical->id], ['sales_id', '=', null]])->orderBy('service_date', 'desc')->first();
            $upcomingService = JobcardDetail::where([['vehicle_id', $vehical->id]])->orderBy('next_date', 'desc')->first();
            $vehical->lastServiceDate = $lastService ? Carbon::parse($lastService->service_date) : null;
            $vehical->upcomingServiceDate = $upcomingService ? Carbon::parse($upcomingService->next_date) : null;
        }

        return view('vehicle.list', compact('vehicals'));
    }

    // Vehicle add form
    public function index()
    {
        $vehical_type = DB::table('tbl_vehicle_types')->where('soft_delete', '=', 0)->get()->toArray();
        // dd($vehical_type);
        $vehical_brand = DB::table('tbl_vehicle_brands')->where('soft_delete', '=', 0)->get()->toArray();
        $fuel_type = DB::table('tbl_fuel_types')->where('soft_delete', '=', 0)->get()->toArray();
        $color = DB::table('tbl_colors')->where('soft_delete', '=', 0)->get()->toArray();
        $model_name = DB::table('tbl_model_names')->where('soft_delete', '=', 0)->get()->toArray();
        // dd($vehical_brand);

        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'vehicle'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if (isAdmin(Auth::User()->role_id)) {
            $branchDatas = Branch::get();
            // } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            // 	$branchDatas = Branch::get();
        } else {
            $branchDatas = Branch::where('id', $currentUser->branch_id)->get();
        }

        $customer = DB::table('users')->where([['role', 'Customer'], ['soft_delete', 0]])->get()->toArray();

        return view('vehicle.add', compact('customer', 'vehical_type', 'vehical_brand', 'fuel_type', 'color', 'model_name', 'tbl_custom_fields', 'branchDatas'));
    }

    // Add vehical type
    public function vehicaltypeadd(Request $request)
    {
        $vehical_type = $request->vehical_type;
        $count = DB::table('tbl_vehicle_types')->where('vehicle_type', '=', $vehical_type)->count();

        if ($count == 0) {
            $vehicaltype = new Vehicletype;
            $vehicaltype->vehicle_type = $vehical_type;
            $vehicaltype->save();
            echo $vehicaltype->id;
        } else {
            $vehicleTypeRecord = DB::table('tbl_vehicle_types')->where([['soft_delete', '!=', 1], ['vehicle_type', '=', $vehical_type]])->first();
            if (! empty($vehicleTypeRecord)) {
                return '01';
            } else {
                $vehicaltype = new Vehicletype;
                $vehicaltype->vehicle_type = $vehical_type;
                $vehicaltype->save();
                echo $vehicaltype->id;
            }
        }
    }

    // Add vehical brand
    public function vehicalbrandadd(Request $request)
    {
        $vehical_id = $request->vehical_id;
        $vehical_brand1 = $request->vehical_brand;

        $count = DB::table('tbl_vehicle_brands')->where([['vehicle_type_id', '=', $vehical_id], ['vehicle_brand', '=', $vehical_brand1]])->count();

        if ($count == 0) {
            $vehical_brand = new Vehiclebrand;
            $vehical_brand->vehicle_type_id = $vehical_id;
            $vehical_brand->vehicle_brand = $vehical_brand1;
            $vehical_brand->save();
            echo $vehical_brand->id;
        } else {
            $vehicleBrandRecord = DB::table('tbl_vehicle_brands')->where([['soft_delete', '!=', 1], ['vehicle_brand', '=', $vehical_brand1], ['vehicle_type_id', '=', $vehical_id]])->first();
            if (! empty($vehicleBrandRecord)) {
                return '01';
            } else {
                $vehical_brand = new Vehiclebrand;
                $vehical_brand->vehicle_type_id = $vehical_id;
                $vehical_brand->vehicle_brand = $vehical_brand1;
                $vehical_brand->save();
                echo $vehical_brand->id;
            }
        }
    }

    // Add fuel type
    public function fueladd(Request $request)
    {
        $fuel_type1 = $request->fuel_type;

        $count = DB::table('tbl_fuel_types')->where('fuel_type', '=', $fuel_type1)->count();
        if ($count == 0) {
            $fueltype = new tbl_fuel_types;
            $fueltype->fuel_type = $fuel_type1;
            $fueltype->save();

            return $fueltype->id;
        } else {

            $vehicleFuelRecord = DB::table('tbl_fuel_types')->where([['soft_delete', '!=', 1], ['fuel_type', '=', $fuel_type1]])->first();
            if (! empty($vehicleFuelRecord)) {
                return '01';
            } else {
                $fueltype = new tbl_fuel_types;
                $fueltype->fuel_type = $fuel_type1;
                $fueltype->save();
                echo $fueltype->id;
            }
        }
    }

    // Add Vehicle Model
    public function add_vehicle_model(Request $request)
    {
        $model_name = $request->model_name;
        $brand_id = $request->brand_id;

        $count = DB::table('tbl_model_names')->where('model_name', '=', $model_name)->count();
        // dd($model_name);

        if ($count == 0) {
            $tbl_model_names = new tbl_model_names;
            $tbl_model_names->model_name = $model_name;
            $tbl_model_names->brand_id = $brand_id;
            $tbl_model_names->save();

            return $tbl_model_names->id;
        } else {
            $vehicleModelNameRecord = DB::table('tbl_model_names')->where([['soft_delete', '!=', 1], ['model_name', '=', $model_name]])->first();
            if (! empty($vehicleModelNameRecord)) {
                return '01';
            } else {
                $tbl_model_names = new tbl_model_names;
                $tbl_model_names->model_name = $model_name;
                $tbl_model_names->brand_id = $brand_id;
                $tbl_model_names->save();
                echo $tbl_model_names->id;
            }
        }
    }

    // Vehical type two brand select
    public function vehicaltype(Request $request)
    {
        $id = $request->vehical_id;

        $vehical_brand = DB::table('tbl_vehicle_brands')->where([['vehicle_type_id', '=', $id], ['soft_delete', '=', 0]])->get()->toArray();

        if (! empty($vehical_brand)) {
            foreach ($vehical_brand as $vehical_brands) { ?>
				<option value="<?php echo $vehical_brands->id; ?>" class="brand_of_type"><?php echo $vehical_brands->vehicle_brand; ?></option>
			<?php }
            }
    }

    // Vehical type two brand select
    public function vehicalmodel(Request $request)
    {
        $id = $request->id;

        $vehical_model = DB::table('tbl_model_names')->where([['brand_id', '=', $id], ['soft_delete', '=', 0]])->get()->toArray();
        // dd($vehical_model);
        if (! empty($vehical_model)) {
            foreach ($vehical_model as $vehical_models) { ?>
				<option value="<?php echo $vehical_models->model_name; ?>" class="brand_of_type"><?php echo $vehical_models->model_name; ?></option>
		<?php }
            }
    }

    // Vehical type Delete
    public function deletevehicaltype(Request $request)
    {
        $id = $request->vtypeid;
        // DB::table('tbl_vehicle_types')->where('id','=',$id)->delete();
        // DB::table('tbl_vehicle_brands')->where('vehicle_id','=',$id)->delete();

        DB::table('tbl_vehicle_types')->where('id', '=', $id)->update(['soft_delete' => 1]);
        DB::table('tbl_vehicle_brands')->where('vehicle_type_id', '=', $id)->update(['soft_delete' => 1]);
    }

    // Vehical brand Delete
    public function deletevehicalbrand(Request $request)
    {
        $id = $request->vbrandid;
        // DB::table('tbl_vehicle_brands')->where('id','=',$id)->delete();
        DB::table('tbl_vehicle_brands')->where('id', '=', $id)->update(['soft_delete' => 1]);
    }

    // Fual type Delete
    public function fueltypedelete(Request $request)
    {
        $id = $request->fueltypeid;
        // $fuel=DB::table('tbl_fuel_types')->where('id','=',$id)->delete();
        $fuel = DB::table('tbl_fuel_types')->where('id', '=', $id)->update(['soft_delete' => 1]);
    }

    // Vehical Model Name Delete
    public function delete_vehi_model(Request $request)
    {
        $id = $request->mod_del_id;
        // tbl_model_names::destroy($id);
        DB::table('tbl_model_names')->where('id', '=', $id)->update(['soft_delete' => 1]);
    }

    // Vehical save
    public function vehicalstore(VehicleAddEditFormRequest $request)
    {
        /*$this->validate($request, [
             'price' => 'numeric',
        ]);*/

        $vehical_type = $request->vehical_id;
        $chasicno = $request->chasicno;
        $vehicabrand = $request->vehicabrand;
        $modelyear = $request->modelyear;
        $fueltype = $request->fueltype;
        $modelname = $request->modelname;
        $price = $request->price;
        $odometerreading = $request->odometerreading;
        $gearbox = $request->gearbox;
        $gearboxno = $request->gearboxno;
        $engineno = $request->engineno;
        $enginesize = $request->enginesize;
        $keyno = $request->keyno;
        $engine = $request->engine;
        $nogears = $request->gearno;
        $numberPlate = $request->number_plate;
        $customer = $request->customer;
        $doms = $request->dom;

        if (! empty($doms)) {
            if (getDateFormat() == 'm-d-Y') {
                $dom = date('Y-m-d', strtotime(str_replace('-', '/', $doms)));
            } else {
                $dom = date('Y-m-d', strtotime($doms));
            }
        } else {
            $dom = null;
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
        $vehical->branch_id = $request->branch;
        $vehical->customer_id = $request->customer;

        // custom field save
        // $custom=Input::get('custom');
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
                $vehicleData = $val1;
            }
            $vehical->custom_field = $vehicleData;
        }
        $vehical->save();

        $customer = User::find($vehical->customer_id);
        $toEmail = $customer->email;
        // Save Notes data
        if ($vehical->save()) {
            // if ($request->notes !== null) {
            // 	saveNotes($vehical, $request->notes, $toEmail);
            // }
            if (! empty($request->notes)) {
                // Filter notes to exclude empty ones
                $validNotes = array_filter($request->notes, function ($note) {
                    return ! empty($note['note_text']) || (! empty($note['note_file']) && is_array($note['note_file']));
                });

                // Only call saveNotes if we have valid notes
                if (! empty($validNotes)) {
                    saveNotes($vehical, $request->notes, $toEmail);
                }
            }
        }

        $vehicles = DB::table('tbl_vehicles')->orderBy('id', 'desc')->first();
        $id = $vehicles->id;

        // $descriptionsdata = Input::get('description');
        $descriptionsdata = $request->description;

        foreach ($descriptionsdata as $key => $value) {
            if ($descriptionsdata[$key] !== null) {
                $desc = $descriptionsdata[$key];
                $descriptions = new tbl_vehicle_discription_records;
                $descriptions->vehicle_id = $id;
                $descriptions->vehicle_description = $desc;
                $descriptions->save();
            }
        }
        $vehicals = DB::table('tbl_vehicles')->orderBy('id', 'desc')->first();
        $id = $vehicles->id;

        $image = $request->image;
        if (! empty($image)) {
            $files = $image;

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $file->move(public_path().'/vehicle/', $file->getClientOriginalName());
                $images = new tbl_vehicle_images;
                $images->vehicle_id = $id;
                $images->image = $filename;
                $images->save();
            }
        }
        $vehicles = DB::table('tbl_vehicles')->orderBy('id', 'desc')->first();
        $id = $vehicles->id;

        // $colores = Input::get('color');
        $colores = $request->color;

        foreach ($colores as $key => $value) {
            if ($value === null) {
                continue;
            }
            $colorse = $colores[$key];
            $color1 = new tbl_vehicle_colors;
            $color1->vehicle_id = $id;
            $color1->color = $colorse;
            $color1->save();
        }

        return redirect('/vehicle/list')->with('message', 'Vehicle Added Successfully');
    }

    // Vehical  Delete
    public function destory($id)
    {

        // $tbl_checkout_categories = DB::table('tbl_checkout_categories')->where('vehicle_id','=',$id)->delete();
        $tbl_checkout_categories = DB::table('tbl_checkout_categories')->where('vehicle_id', '=', $id)->update(['soft_delete' => 1]);
        // $tbl_points = DB::table('tbl_points')->where('vehicle_id','=',$id)->delete();
        $tbl_points = DB::table('tbl_points')->where('vehicle_id', '=', $id)->update(['soft_delete' => 1]);
        // $tbl_rto_taxes = DB::table('tbl_rto_taxes')->where('vehicle_id','=',$id)->delete();
        $tbl_rto_taxes = DB::table('tbl_rto_taxes')->where('vehicle_id', '=', $id)->update(['soft_delete' => 1]);

        $color1 = DB::table('tbl_vehicle_colors')->where('vehicle_id', '=', $id)->delete();
        $images = DB::table('tbl_vehicle_images')->where('vehicle_id', '=', $id)->delete();
        $descriptions = DB::table('tbl_vehicle_discription_records')->where('vehicle_id', '=', $id)->delete();

        // $vehical = DB::table('tbl_vehicles')->where('id','=',$id)->delete();
        $vehical = DB::table('tbl_vehicles')->where('id', '=', $id)->update([
            'soft_delete' => 1,
        ]);

        return redirect('vehicle/list')->with('message', 'Vehicle Deleted Successfully');
    }

    public function destoryMultiple(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $this->destory($id);
        }

        return response()->json(['message' => 'Successfully deleted selected purchase records']);
    }

    // Vehical  Edit
    public function editvehical($id)
    {
        $editid = $id;
        $vehicaledit = Vehicle::with('notes')->find($id);
        $vehical_type = DB::table('tbl_vehicle_types')->where('soft_delete', '=', 0)->get()->toArray();
        $vehical_brand = DB::table('tbl_vehicle_brands')->where('id', '=', $vehicaledit->vehiclebrand_id)->where('soft_delete', '=', 0)->get()->toArray();
        $fueltype = DB::table('tbl_fuel_types')->where('soft_delete', '=', 0)->get()->toArray();
        $color = DB::table('tbl_colors')->where('soft_delete', '=', 0)->get()->toArray();

        $colors1 = DB::table('tbl_vehicle_colors')->where('vehicle_id', '=', $id)->get()->toArray();
        $images1 = DB::table('tbl_vehicle_images')->where('vehicle_id', '=', $id)->get()->toArray();
        $vehicaldes = DB::table('tbl_vehicle_discription_records')->where('vehicle_id', '=', $id)->get()->toArray();
        $model_name = DB::table('tbl_model_names')->where('soft_delete', '=', 0)->where('brand_id', '=', $vehicaledit->vehiclebrand_id)->get()->toArray();
        $customer = DB::table('users')->where([['role', 'Customer'], ['soft_delete', 0]])->get()->toArray();

        // Custom Field Data
        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'vehicle'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if (isAdmin(Auth::User()->role_id)) {
            $branchDatas = Branch::get();
            // } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            // 	$branchDatas = Branch::get();
        } else {
            $branchDatas = Branch::where('id', $currentUser->branch_id)->get();
        }

        $vehical_brand_all = DB::table('tbl_vehicle_brands')->where('soft_delete', '=', 0)->get()->toArray();
        $model_name_all = DB::table('tbl_model_names')->where('soft_delete', '=', 0)->get()->toArray();

        return view('vehicle.edit', compact('vehicaledit', 'vehicaldes', 'vehical_type', 'vehical_brand', 'fueltype', 'color', 'editid', 'colors1', 'images1', 'model_name', 'tbl_custom_fields', 'branchDatas', 'customer', 'vehical_brand_all', 'model_name_all'));
    }

    // vehical Update
    public function updatevehical($id, VehicleAddEditFormRequest $request)
    {

        /*$this->validate($request, [
             'price' => 'numeric',
        ]);*/

        $vehical_type = $request->vehical_id;
        $chasicno = $request->chasicno;
        $vehicabrand = $request->vehicabrand;
        $modelyear = $request->modelyear;
        $fueltype = $request->fueltype;
        $modelname = $request->modelname;
        $price = $request->price;
        $odometerreading = $request->odometerreading;
        $gearbox = $request->gearbox;
        $gearboxno = $request->gearboxno;
        $engineno = $request->engineno;
        $enginesize = $request->enginesize;
        $keyno = $request->keyno;
        $engine = $request->engine;
        $nogears = $request->gearno;
        $numberPlate = $request->number_plate;
        $customer = $request->customer;

        $doms = $request->dom;
        $dom = null;
        if (! empty($doms)) {
            if (getDateFormat() == 'm-d-Y') {
                $dom = date('Y-m-d', strtotime(str_replace('-', '/', $doms)));
            } else {
                $dom = date('Y-m-d', strtotime($doms));
            }
        }

        $vehical = Vehicle::find($id);
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
        $vehical->branch_id = $request->branch;
        $vehical->customer_id = $request->customer;

        // Custom Field Data
        // $custom=Input::get('custom');
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
                $vehicleData = $val1;
            }
            $vehical->custom_field = $vehicleData;
        }

        $vehical->save();

        // $delete = Notes::where('entity_id', '=', $id)->delete();
        $customer = User::find($vehical->customer_id);
        $toEmail = $customer->email;
        // Save Notes data
        if ($vehical->save()) {
            // if ($request->notes !== null) {
            // 	saveNotes($vehical, $request->notes, $toEmail);
            // }
            if (! empty($request->notes)) {
                // Filter notes to exclude empty ones
                $validNotes = array_filter($request->notes, function ($note) {
                    return ! empty($note['note_text']) || (! empty($note['note_file']) && is_array($note['note_file']));
                });

                // Only call saveNotes if we have valid notes
                if (! empty($validNotes)) {
                    saveNotes($vehical, $request->notes, $toEmail);
                }
            }
        }

        // $colores = Input::get('color');
        $colores = $request->color;
        $tbl_vehicale_colors = DB::table('tbl_vehicle_colors')->where('vehicle_id', '=', $id)->delete();
        if (! empty($colores)) {
            foreach ($colores as $key => $value) {
                $colorse = $colores[$key];
                $color1 = new tbl_vehicle_colors;
                $color1->vehicle_id = $id;
                $color1->color = $colorse;
                $color1->save();
            }
        }

        // $files = Input::file('image');
        $files = $request->image;
        if (! empty($files)) {
            foreach ($files as $file) {
                // if(Input::hasFile('image'))
                if ($files) {
                    $filename = $file->getClientOriginalName();
                    $file->move(public_path().'/vehicle/', $file->getClientOriginalName());
                    $images = new tbl_vehicle_images;
                    $images->vehicle_id = $id;
                    $images->image = $filename;
                    $images->save();
                }
            }
        } else {
            // Nothing to do (solved by Mukesh Bug list row number 582 and 583)
            /*$images = new tbl_vehicle_images;
            $images->vehicle_id = $id;
            $images->image = "'avtar.png'";
            $images->save();*/
        }

        // $descriptionsdata = Input::get('description');
        $descriptionsdata = $request->description;
        $tbl_vehicle_discription_records = DB::table('tbl_vehicle_discription_records')->where('vehicle_id', '=', $id)->delete();
        if (! empty($descriptionsdata)) {
            foreach ($descriptionsdata as $key => $value) {

                if ($descriptionsdata[$key] !== null) {
                    $desc = $descriptionsdata[$key];
                    $descriptions = new tbl_vehicle_discription_records;
                    $descriptions->vehicle_id = $id;
                    $descriptions->vehicle_description = $desc;
                    $descriptions->save();
                }
            }
        }

        return redirect('/vehicle/list')->with('message', 'Vehicle Updated Successfully');
    }

    // vehicle show
    public function vehicalshow($id)
    {
        $view_id = $id;
        /* $vehical=DB::table('tbl_vehicles')->where('id','=',$id)->first(); */
        $vehical = Vehicle::where('id', '=', $id)->first();

        $image = DB::table('tbl_vehicle_images')->where('vehicle_id', '=', $id)->get()->toArray();
        if (! empty($image)) {
            foreach ($image as $images) {
                $image_name[] = URL::to('/public/vehicle/'.$images->image);
            }
        } else {
            $image_name[] = URL::to('/public/vehicle/avtar.png');
        }
        $available = json_encode($image_name);

        $available1 = json_decode($available, true);
        $col = DB::table('tbl_vehicle_colors')->where('vehicle_id', '=', $id)->get()->toArray();

        // Custom Field Data
        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'vehicle'], ['always_visable', '=', 'yes']])->get()->toArray();
        // $tbl_custom_fields = CustomField::where([['form_name','=','vehicle'],['always_visable','=','yes']])->get();

        return view('/vehicle/view', compact('available1', 'vehical', 'image', 'col', 'available', 'view_id', 'tbl_custom_fields'));
    }

    // get description
    public function getDescription(Request $request)
    {
        // $row_id = Input::get('row_id');
        $row_id = $request->row_id;
        $ids = $row_id + 1;
        $html = view('vehicle.newdescription')->with(compact('row_id', 'ids'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // delete description
    public function deleteDescription(Request $request)
    {
        // $id=Input::get('description');
        $id = $request->description;
        $description = DB::table('tbl_vehicle_discription_records')->where('id', '=', $id)->delete();
    }

    // get color
    public function getcolor(Request $request)
    {
        // $color_id = Input::get('color_id');
        $color_id = $request->color_id;

        $color = DB::table('tbl_colors')->where('soft_delete', 0)->get()->toArray();
        // dd($color);
        $idc = $color_id + 1;

        $html = view('vehicle.newcoloradd')->with(compact('color_id', 'color', 'idc'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // color delete
    public function deletecolor(Request $request)
    {
        // $id=Input::get('color_id');
        $id = $request->color_id;
        $color = DB::table('tbl_vehicle_colors')->where('id', '=', $id)->delete();
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
        $image = DB::table('tbl_vehicle_images')->where('id', '=', $id)->delete();
    }

    public function vehicalDescription($id)
    {
        $view_id = $id;
        $vehical = Vehicle::with('notes')->find($id);

        $desription = DB::table('tbl_vehicle_discription_records')->where('vehicle_id', '=', $id)->get()->toArray();

        return view('/vehicle/vehicleDescription', compact('vehical', 'view_id', 'desription'));
    }

    public function vehicalMaintainance($id)
    {
        $view_id = $id;
        $vehical = Vehicle::where('id', '=', $id)->first();
        $image = DB::table('tbl_vehicle_images')->where('vehicle_id', '=', $id)->get()->toArray();
        if (! empty($image)) {
            foreach ($image as $images) {
                $image_name[] = URL::to('/public/vehicle/'.$images->image);
            }
        } else {
            $image_name[] = URL::to('/public/vehicle/avtar.png');
        }
        $services = Service::orderBy('service_date', 'desc')->where([['job_no', 'like', 'J%'], ['vehicle_id', $id]])->get();
        $available = json_encode($image_name);

        return view('/vehicle/vehicalMaintainance', compact('vehical', 'view_id', 'services', 'available'));
    }

    public function vehicalMOT($id)
    {
        $vehical = Vehicle::where('id', '=', $id)->first();

        $get_services_tbl_data = Service::where([['vehicle_id', '=', $id], ['mot_status', '=', 1]])->orderBy('id', 'desc')->get();

        $servicesData = [];

        foreach ($get_services_tbl_data as $service) {
            $mot_test_status_yes_or_no = $service->mot_status;
            $get_vehicle_mot_test_reports_data = null;
            $get_mot_vehicle_inspection_data = null;
            $answers_question_id_array = null;
            $get_inspection_points_library_data = null;

            if ($mot_test_status_yes_or_no == 1) {
                $get_vehicle_mot_test_reports_data = DB::table('vehicle_mot_test_reports')->where('service_id', '=', $service->id)->latest('date')->first();

                if ($get_mot_vehicle_inspection_data = DB::table('mot_vehicle_inspection')->where('service_id', '=', $service->id)->latest('created_at')->first()) {
                    $json_data = $get_mot_vehicle_inspection_data->answer_question_id;
                    $answers_question_id_array = json_decode($json_data, true);
                    ksort($answers_question_id_array);
                }

                $get_inspection_points_library_data = DB::select('select * from inspection_points_library');
            }

            $servicesData[] = [
                'service' => $service,
                'mot_test_status_yes_or_no' => $mot_test_status_yes_or_no,
                'get_vehicle_mot_test_reports_data' => $get_vehicle_mot_test_reports_data,
                'get_mot_vehicle_inspection_data' => $get_mot_vehicle_inspection_data,
                'answers_question_id_array' => $answers_question_id_array,
                'get_inspection_points_library_data' => $get_inspection_points_library_data,
            ];
        }

        // dd($servicesData);

        return view('/vehicle/vehicleMOT', compact('vehical', 'servicesData'));
    }

    public function checkNumberPlateUnique(Request $request)
    {
        $numberPlate = $request->input('numberPlate');

        // Perform the uniqueness check
        $isUnique = ! Vehicle::where('number_plate', $numberPlate)->exists();

        return response()->json(['unique' => $isUnique]);
    }

    public function vehicalshowNotes($id)
    {
        $view_id = $id;
        $vehical = Vehicle::with('notes')->find($id);

        return view('vehicle.showNotes', compact('view_id', 'vehical'));
    }
}
