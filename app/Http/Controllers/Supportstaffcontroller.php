<?php

namespace App\Http\Controllers;

use App\Branch;
use App\BranchSetting;
use App\CustomField;
use App\EmailLog;
use App\Http\Requests\SupportstaffAddEditFormRequest;
use App\Role;
use App\Role_user;
use App\Sale;
use App\Service;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Mail;
use URL;

class Supportstaffcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // supportstaff list
  
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Build the query for fetching support staff
            $query = User::query()
                ->where('role', 'supportstaff')
                ->where('soft_delete', 0)
                ->select(
                    'users.id',
                    'users.name',
                    'users.lastname',
                    'users.email',
                    'users.mobile_no',
                    'users.image'
                );

            // Search functionality
            $searchValue = $request->input('search.value');
            if ($searchValue) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('users.name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('users.lastname', 'LIKE', "%{$searchValue}%")
                        ->orWhere('users.email', 'LIKE', "%{$searchValue}%")
                        ->orWhere('users.mobile_no', 'LIKE', "%{$searchValue}%");
                });
            }

            // Get the total records after applying the search filter
            $filteredRecords = $query->count();

            // Apply ordering and pagination
            $columns = ['id', 'name', 'lastname', 'email', 'mobile_no', 'image', 'id'];

            $data = $query->orderBy(
                $columns[$request->input('order.0.column', 0)],
                $request->input('order.0.dir', 'asc')
            )
                ->offset($request->input('start', 0))
                ->limit($request->input('length', 10))
                ->get();

            // Return the data in the required format for DataTables
            $response = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => User::where('role', 'supportstaff')->where('soft_delete', 0)->count(),
                'recordsFiltered' => $filteredRecords,
                'data' => $data->map(function ($supportstaff) {
                    return [
                        'id' => $supportstaff->id,
                        'image' => '<img src="'.asset('public/supportstaff/'.$supportstaff->image).'" width="52px" height="52px" class="datatable_img">',
                        'name' => '<a class="dropdown-item" href="'.url('/supportstaff/list/'.$supportstaff->id).'">'.$supportstaff->name.'</a>',
                        'lastname' => '<a class="dropdown-item" href="'.url('/supportstaff/list/'.$supportstaff->id).'">'.$supportstaff->lastname.'</a>',
                        'email' => $supportstaff->email,
                        'mobile_no' => $supportstaff->mobile_no,
                        'action' => '<div class="dropdown_toggle">
					<img src="'.asset('public/img/list/dots.png').'" class="btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
					<ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonaction">
						'.(auth()->user()->can('supportstaff_view') ? '<li><a class="dropdown-item" href="'.url('/supportstaff/list/'.$supportstaff->id).'"><img src="'.asset('public/img/list/Vector.png').'" class="me-3"> '.trans('message.View').'</a></li>' : '').'
						
						'.(auth()->user()->can('supportstaff_edit') ? '<li><a class="dropdown-item" href="'.url('/supportstaff/list/edit/'.$supportstaff->id).'"><img src="'.asset('public/img/list/Edit.png').'" class="me-3"> '.trans('message.Edit').'</a></li>' : '').'
						
						'.(auth()->user()->can('supportstaff_delete') ? '<div class="dropdown-divider m-0"></div><li><a class="dropdown-item deletedatas" url="'.url('/supportstaff/list/delete/'.$supportstaff->id).'" style="color:#FD726A"><img src="'.asset('public/img/list/Delete.png').'" class="me-3"> '.trans('message.Delete').'</a></li>' : '').'
					</ul>
				 </div>',
                    ];
                }),
            ];

            return response()->json($response);
        }
        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

        if (isAdmin(Auth::User()->role_id)) {
            $supportstaff = User::where([['role', '=', 'supportstaff'], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
        } elseif (getUsersRole(Auth::user()->role_id) == 'Support Staff') {
            if (Gate::allows('supportstaff_owndata')) {
                $supportstaff = User::where([['role', '=', 'supportstaff'], ['id', Auth::User()->id], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
            } else {
                $supportstaff = User::where([['role', '=', 'supportstaff'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'DESC')->get();
            }
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            if (Gate::allows('supportstaff_owndata')) {
                $supportstaff = User::where([['role', '=', 'supportstaff'], ['create_by', Auth::User()->id], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
            } else {
                $supportstaff = User::where([['role', '=', 'supportstaff'], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
            }
        } else {
            if (Gate::allows('supportstaff_owndata')) {
                $supportstaff = User::where([['role', '=', 'supportstaff'], ['create_by', Auth::User()->id], ['soft_delete', 0]])->orderBy('id', 'DESC')->get();
            } else {
                $supportstaff = User::where([['role', '=', 'supportstaff'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->orderBy('id', 'DESC')->get();
            }
        }

        return view('supportstaff.list', compact('supportstaff'));
    }

    // supportstaff add form
    public function supportstaffadd()
    {
        $country = DB::table('tbl_countries')->get()->toArray();
        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'supportstaff'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

        if (isAdmin(Auth::User()->role_id)) {
            $branchDatas = Branch::get();
            // } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            // 	$branchDatas = Branch::where('id', $currentUser->branch_id)->get();
        } else {
            $branchDatas = Branch::where('id', $currentUser->branch_id)->get();
        }

        return view('supportstaff.add', compact('country', 'tbl_custom_fields', 'branchDatas'));
    }

    // supportstaff store
    public function store_supportstaff(SupportstaffAddEditFormRequest $request)
    {
        $email = $request->email;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $displayname = $request->displayname;
        $gender = $request->gender;
        $birthdate = $request->dob;
        $password = $request->password;
        $mobile = $request->mobile;
        $landlineno = $request->landlineno;
        $address = $request->address;
        $country = $request->country_id;
        $state = $request->state_id;
        $city = $request->city;

        $dob = null;
        if (! empty($birthdate)) {
            if (getDateFormat() == 'm-d-Y') {
                $dob = date('Y-m-d', strtotime(str_replace('-', '/', $birthdate)));
            } else {
                $dob = date('Y-m-d', strtotime($birthdate));
            }
        }

        // Get user role id from Role table
        $getRoleId = Role::where('role_name', '=', 'Support Staff')->first();

        $supportstaff = new User;
        $supportstaff->name = $firstname;
        $supportstaff->lastname = $lastname;
        $supportstaff->display_name = $displayname;
        $supportstaff->gender = $gender;
        $supportstaff->birth_date = $dob;
        $supportstaff->email = $email;
        $supportstaff->password = bcrypt($password);
        $supportstaff->mobile_no = $mobile;
        $supportstaff->landline_no = $landlineno;
        $supportstaff->address = $address;
        $supportstaff->country_id = $country;
        $supportstaff->state_id = $state;
        $supportstaff->city_id = $city;
        $supportstaff->branch_id = $request->branch;
        $supportstaff->create_by = Auth::User()->id;

        $image = $request->image;
        if (! empty($image)) {
            $file = $image;
            $filename = $file->getClientOriginalName();
            $file->move(public_path().'/supportstaff/', $file->getClientOriginalName());
            $supportstaff->image = $filename;
        } else {
            $supportstaff->image = 'avtar.png';
        }

        $supportstaff->role_id = $getRoleId->id; /* Store Role table User Role Id */

        $supportstaff->role = 'supportstaff';
        $supportstaff->language = 'en';
        $supportstaff->timezone = 'UTC';

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
                $supportstaffData = $val1;
            }
            $supportstaff->custom_field = $supportstaffData;
        }
        $supportstaff->save();

        /* For data store inside Role_user table */
        if ($supportstaff->save()) {
            $currentUserId = $supportstaff->id;

            $role_user_table = new Role_user;
            $role_user_table->user_id = $currentUserId;
            $role_user_table->role_id = $getRoleId->id;
            $role_user_table->save();
        }

        // email format
        try {
            $logo = DB::table('tbl_settings')->first();
            $systemname = $logo->system_name;
            $emailformats = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'User_registration')->first();
            if ($emailformats->is_send == 0) {
                if ($supportstaff->save()) {
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
                    $redirect_url = url('/supportstaff/list');
                    // Render Blade template with all required variables
                    $blade_view = View::make('mail.template', [
                        'notification_label' => $notification_label,
                        'email_content' => $email_content,
                        'redirect_url' => $redirect_url,
                        'system_link' => $systemlink,
                    ])->render();

                    // Send email
                    try {
                        Mail::send([], [], function ($message) use ($email, $mail_sub, $blade_view, $mail_send_from) {
                            $message->to($email)->subject($mail_sub);
                            $message->from($mail_send_from);
                            $message->html($blade_view, 'text/html');
                        });
                    } catch (\Exception $e) {
                        \Log::error('Error sending wash_bay_complete_process email: '.$e->getMessage());
                    }
                  
                    $messageBody = 'Welcome to '.$systemname."Your account is created successfully. Your login details are as follows: \nEmail: ".$email."\n Password: ".$password."\nLogin Url :".$systemlink;
                    $receiverNumber = getMobileNoWithCode($supportstaff->id);
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

        return redirect('/supportstaff/list')->with('message', 'Supportstaff Added Successfully');
    }

    // supportstaff show
    public function supportstaff_show($id)
    {
        $viewid = $id;
        $supportstaff = User::where('id', '=', $id)->first();
        $service = Service::where([['customer_id', '=', $id], ['done_status', '=', '1']])->get();
        $servic = Service::where([['customer_id', '=', $id], ['done_status', '=', '2']])->get();
        $sales = Sale::where('customer_id', '=', $id)->get();
        $taxes = DB::table('tbl_sales_taxes')->where('sales_id', '=', $id)->get()->toArray();
        $tbl_custom_fields = CustomField::where([['form_name', '=', 'supportstaff'], ['always_visable', '=', 'yes']])->get();

        return view('supportstaff.view', compact('supportstaff', 'viewid', 'sales', 'service', 'servic', 'tbl_custom_fields'));
    }

    // supportstaff delete
    public function destory($id)
    {
        $supportstaff = User::where('id', '=', $id)->update(['soft_delete' => 1]);

        return redirect('/supportstaff/list')->with('message', 'Supportstaff Deleted Successfully');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (! empty($ids)) {
            User::whereIn('id', $ids)->update(['soft_delete' => 1]);
        }

        return redirect('/supportstaff/list')->with('message', 'Supportstaff Deleted Successfully');
    }

    // supportstaff edit
    public function edit($id)
    {

        $editid = $id;
        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();

        if (! isAdmin(Auth::User()->role_id)) {
            if (Gate::allows('supportstaff_owndata')) {
                if (Auth::User()->id == $id) {
                    $country = DB::table('tbl_countries')->get()->toArray();
                    $supportstaff = DB::table('users')->where('id', '=', $id)->first();

                    $state = DB::table('tbl_states')->where('country_id', $supportstaff->country_id)->get()->toArray();
                    $city = DB::table('tbl_cities')->where('state_id', $supportstaff->state_id)->get()->toArray();

                    $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'supportstaff'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

                    $branchDatas = Branch::where('id', '=', $currentUser->branch_id)->get();

                    return view('supportstaff.update', compact('country', 'supportstaff', 'state', 'city', 'editid', 'tbl_custom_fields', 'branchDatas'));
                } elseif (Gate::allows('supportstaff_edit')) {
                    $country = DB::table('tbl_countries')->get()->toArray();
                    $supportstaff = DB::table('users')->where('id', '=', $id)->first();

                    $state = DB::table('tbl_states')->where('country_id', $supportstaff->country_id)->get()->toArray();
                    $city = DB::table('tbl_cities')->where('state_id', $supportstaff->state_id)->get()->toArray();

                    $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'supportstaff'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

                    $branchDatas = Branch::where('id', '=', $currentUser->branch_id)->get();

                    return view('supportstaff.update', compact('country', 'supportstaff', 'state', 'city', 'editid', 'tbl_custom_fields', 'branchDatas'));
                } else {
                    return abort('403', 'This action is unauthorized.');
                }
            } elseif (Gate::allows('supportstaff_edit')) {
                $country = DB::table('tbl_countries')->get()->toArray();
                $supportstaff = DB::table('users')->where('id', '=', $id)->first();

                $state = DB::table('tbl_states')->where('country_id', $supportstaff->country_id)->get()->toArray();
                $city = DB::table('tbl_cities')->where('state_id', $supportstaff->state_id)->get()->toArray();

                $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'supportstaff'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

                // if (getUsersRole(Auth::User()->role_id) == "Customer") {
                // 	$branchDatas = Branch::get();
                // } else {
                $branchDatas = Branch::where('id', $currentUser->branch_id)->get();
                // }

                return view('supportstaff.update', compact('country', 'supportstaff', 'state', 'city', 'editid', 'tbl_custom_fields', 'branchDatas'));
            } else {
                return abort('403', 'This action is unauthorized.');
            }
        } else {
            $country = DB::table('tbl_countries')->get()->toArray();
            $supportstaff = DB::table('users')->where('id', '=', $id)->first();
            $state = DB::table('tbl_states')->where('country_id', $supportstaff->country_id)->get()->toArray();
            $city = DB::table('tbl_cities')->where('state_id', $supportstaff->state_id)->get()->toArray();
            $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'supportstaff'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();
            $branchDatas = Branch::get();

            return view('supportstaff.update', compact('country', 'supportstaff', 'state', 'city', 'editid', 'tbl_custom_fields', 'branchDatas'));
        }
    }

    // supportstaff update
    public function update($id, SupportstaffAddEditFormRequest $request)
    {
        // dd($request->all());
        $usimgdtaa = DB::table('users')->where('id', '=', $id)->first();
        $email = $usimgdtaa->email;

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
        $birtDate = $request->dob;

        $dob = null;
        if (! empty($birtDate)) {
            if (getDateFormat() == 'm-d-Y') {
                $dob = date('Y-m-d', strtotime(str_replace('-', '/', $birtDate)));
            } else {
                $dob = date('Y-m-d', strtotime($birtDate));
            }
        }

        $supportstaff = User::find($id);
        $supportstaff->name = $firstname;
        $supportstaff->lastname = $lastname;
        $supportstaff->display_name = $displayname;
        $supportstaff->gender = $gender;
        $supportstaff->birth_date = $dob;
        $supportstaff->email = $email;

        if (! empty($password)) {
            $supportstaff->password = bcrypt($password);
        }

        $supportstaff->mobile_no = $mobile;
        $supportstaff->landline_no = $landlineno;
        $supportstaff->address = $address;
        $supportstaff->country_id = $country;
        $supportstaff->state_id = $state;
        $supportstaff->city_id = $city;
        $supportstaff->branch_id = $request->branch;

        $image = $request->image;
        if (! empty($image)) {
            $file = $image;
            $filename = $file->getClientOriginalName();
            $file->move(public_path().'/supportstaff/', $file->getClientOriginalName());
            $supportstaff->image = $filename;
        }
        $supportstaff->role = 'supportstaff';

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
                $supportstaffData = $val1;
            }
            $supportstaff->custom_field = $supportstaffData;
        }
        $supportstaff->save();

        return redirect('/supportstaff/list')->with('message', 'Supportstaff Updated Successfully');
    }
}
