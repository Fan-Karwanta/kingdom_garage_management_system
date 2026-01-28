<?php

namespace App\Http\Controllers;

use App\CustomField;
use App\Http\Requests\SupplierAddEditFormRequest;
use App\note_attachments;
use App\Notes;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Suppliercontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // supplier list
 
    public function supplierlist(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query()
                ->where('role', 'Supplier')
                ->where('soft_delete', 0);

            if (! isAdmin(Auth::User()->role_id)) {
                if (Gate::allows('supplier_owndata')) {
                    $query->where('create_by', Auth::User()->id);
                }
            }

            $columns = ['id', 'image', 'name', 'lastname', 'company_name', 'email', 'id'];
            $searchValue = $request->input('search.value');

            if ($searchValue) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('lastname', 'LIKE', "%{$searchValue}%")
                        ->orWhere('company_name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('email', 'LIKE', "%{$searchValue}%");
                });
            }

            $filteredRecords = $query->count();

            $data = $query->orderBy(
                $columns[$request->input('order.0.column', 0)],
                $request->input('order.0.dir', 'asc')
            )
                ->offset($request->input('start', 0))
                ->limit($request->input('length', 10))
                ->get();

            $response = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => User::where('role', 'Supplier')->where('soft_delete', 0)->count(),
                'recordsFiltered' => $filteredRecords,
                'data' => $data->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'image' => '<img src="'.asset('public/supplier/'.$user->image).'" width="52px" height="52px" class="datatable_img">',
                        'name' => '<a class="dropdown-item" href="'.url('/supplier/list/'.$user->id).'">'.$user->name.'</a>',
                        'lastname' => '<a class="dropdown-item" href="'.url('/supplier/list/'.$user->id).'">'.$user->lastname.'</a>',
                        'company_name' => $user->company_name,
                        'email' => $user->email,
                        'product_name' => substr(getProductList($user->id), 0, 100),
                        'action' => '<div class="dropdown_toggle">
						<img src="'.asset('public/img/list/dots.png').'" 
							 class="btn dropdown-toggle border-0" 
							 type="button" 
							 data-bs-toggle="dropdown" 
							 aria-expanded="false">
						<ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonaction">
							'.(auth()->user()->can('supplier_view') ? '<li>
								<a class="dropdown-item" href="'.url('/supplier/list/'.$user->id).'">
									<img src="'.asset('public/img/list/Vector.png').'" class="me-3"> '.trans('message.View').'
								</a>
							</li>' : '').'
							'.(auth()->user()->can('supplier_edit') ? '<li>
								<a class="dropdown-item" href="'.url('/supplier/list/edit/'.$user->id).'">
									<img src="'.asset('public/img/list/Edit.png').'" class="me-3"> '.trans('message.Edit').'
								</a>
							</li>' : '').'
							'.(auth()->user()->can('supplier_delete') ? '<div class="dropdown-divider m-0"></div>
							<li>
								<a class="dropdown-item deletedatas" url="'.url('/supplier/list/delete/'.$user->id).'" style="color:#FD726A">
									<img src="'.asset('public/img/list/Delete.png').'" class="me-3"> '.trans('message.Delete').'
								</a>
							</li>' : '').'
						</ul>
					 </div>',
                    ];
                }),
            ];

            return response()->json($response);
        }
        $user = User::where('role', 'Supplier')
            ->where('soft_delete', 0)
            ->get();

        $server = 'http://'.$_SERVER['SERVER_NAME'].'/garrage';

        return view('supplier.list', compact('server', 'user'));
    }

    // supplier add in user_tbl
    public function adddata(Request $request)
    {
        $supllier = new User;
        $supllier->name = $request->name;
        $supllier->save();
    }

    // supplier add form
    public function supplieradd()
    {
        $country = DB::table('tbl_countries')->get()->toArray();

        $tbl_custom_fields = CustomField::where([['form_name', '=', 'suppliers'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get();

        // dd($tbl_custom_fields);
        return view('supplier.add', compact('country', 'tbl_custom_fields'));
    }

    // supplier store
    public function storesupplier(SupplierAddEditFormRequest $request)
    {
        $displayname = $request->displayname;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $gender = $request->gender;
        $email = $request->email;
        $mobile = $request->mobile;
        $landlineno = $request->landlineno;
        $address = $request->address;
        $country_id = $request->country_id;
        $state = $request->state;
        $city = $request->city;
        $image = $request->image;

        $user = new User;
        $user->name = $firstname;
        $user->lastname = $lastname;
        $user->company_name = $displayname;
        $user->gender = $gender;
        $user->email = $email;
        $user->mobile_no = $mobile;
        $user->landline_no = $landlineno;
        $user->address = $address;
        $user->create_by = Auth::User()->id;

        if (! empty($image)) {
            $file = $image;
            $filename = $file->getClientOriginalName();
            $file->move(public_path().'/supplier/', $file->getClientOriginalName());
            $user->image = $filename;
        } else {
            $user->image = 'avtar.png';
        }

        $user->country_id = $country_id;
        $user->state_id = $state;
        $user->city_id = $city;
        $user->role = 'Supplier';
        $user->language = 'en';
        $user->timezone = 'UTC';

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
                $supplierdata = $val1;
            }
            $user->custom_field = $supplierdata;
        }

        $user->save();

        // Save Notes data
        if ($user->save()) {
            // if ($request->notes !== null) {
            // 	saveNotes($user, $request->notes, $user->email);
            // }
            // Ensure notes exist before processing
            if (! empty($request->notes)) {
                // Filter notes to exclude empty ones
                $validNotes = array_filter($request->notes, function ($note) {
                    return ! empty($note['note_text']) || (! empty($note['note_file']) && is_array($note['note_file']));
                });

                // Only call saveNotes if we have valid notes
                if (! empty($validNotes)) {
                    saveNotes($user, $validNotes, $user->email);
                }
            }

        }

        return redirect('/supplier/list')->with('message', 'Supplier Added Successfully');
    }

    // supplier show
    public function showsupplier($id)
    {
        $viewid = $id;
        $user = User::with('notes')->find($id);

        $tbl_custom_fields = CustomField::where([['form_name', '=', 'suppliers'], ['soft_delete', 0]])->get();

        return view('supplier.show', compact('user', 'viewid', 'tbl_custom_fields'));
    }

    // supplier delete
    public function destroy($id)
    {
        $user = User::where('id', '=', $id)->update(['soft_delete' => 1]);

        return redirect('/supplier/list')->with('message', 'Supplier Deleted Successfully');
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (! empty($ids)) {
            User::whereIn('id', $ids)->update(['soft_delete' => 1]);
        }

        return redirect('/supplier/list')->with('message', 'Supplier Deleted Successfully');
    }

    // supplier edit
    public function edit($id)
    {
        $editid = $id;
        $user = User::with('notes')->find($id);

        $country = DB::table('tbl_countries')->get()->toArray();
        $state = [];
        $city = [];

        if (! is_null($user)) {
            if ($user->country_id != null) {
                $state = DB::table('tbl_states')->where('country_id', '=', $user->country_id)->get()->toArray();
            }

            if ($user->state_id != null) {
                $city = DB::table('tbl_cities')->where('state_id', '=', $user->state_id)->get()->toArray();
            }
        }

        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'suppliers'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get();

        return view('supplier.edit', compact('country', 'state', 'city', 'user', 'editid', 'tbl_custom_fields'));
    }

    // supplier update
    public function update(SupplierAddEditFormRequest $request, $id)
    {
        $usimgdtaa = DB::table('users')->where('id', '=', $id)->first();
        $email = $usimgdtaa->email;

        // if ($email != $request->email) {
        // 	$this->validate($request, [
        // 		'email' => 'email|unique:users'
        // 	]);
        // }

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $displayname = $request->displayname;
        $gender = $request->gender;
        $email = $request->email;
        $password = $request->password;
        $mobile = $request->mobile;
        $landlineno = $request->landlineno;
        $address = $request->address;
        $country_id = $request->country_id;
        $state = $request->state;
        $city = $request->city;

        $user = User::find($id);
        $user->name = $firstname;
        $user->lastname = $lastname;
        $user->company_name = $displayname;
        $user->gender = $gender;
        $user->email = $email;
        $user->mobile_no = $mobile;
        $user->landline_no = $landlineno;
        $user->address = $address;

        if (! empty($request->image)) {
            $file = $request->image;
            $filename = $file->getClientOriginalName();
            $file->move(public_path().'/supplier/', $file->getClientOriginalName());
            $user->image = $filename;
        }

        $user->country_id = $country_id;
        $user->state_id = $state;
        $user->city_id = $city;
        $user->role = 'Supplier';
        $user->language = 'en';
        $user->timezone = 'UTC';

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
                $supplierdata = $val1;
            }
            $user->custom_field = $supplierdata;
        }
        $user->save();

        // $delete = Notes::where('entity_id', '=', $id)->delete();
        // Save Notes data
        if ($user->save()) {
           
            if (! empty($request->notes)) {
                // Filter notes to exclude empty ones
                $validNotes = array_filter($request->notes, function ($note) {
                    return ! empty($note['note_text']) || (! empty($note['note_file']) && is_array($note['note_file']));
                });

                // Only call saveNotes if we have valid notes
                if (! empty($validNotes)) {
                    saveNotes($user, $validNotes, $user->email);
                }
            }
        }

        return redirect('/supplier/list')->with('message', 'Supplier Updated Successfully');
    }

    // delete images
    public function deleteAttachment(Request $request)
    {
        $id = $request->delete_image;
        $delete = note_attachments::where('id', '=', $id)->delete();
    }

    public function deleteNote($id)
    {
        $note = Notes::where('id', '=', $id)->delete();
        $attachment = note_attachments::where('note_id', '=', $id)->delete();

        return redirect()->back();
    }

    public function showsupplierNotes($id)
    {
        $viewid = $id;
        $user = User::with('notes')->find($id);

        $tbl_custom_fields = CustomField::where([['form_name', '=', 'suppliers'], ['soft_delete', 0]])->get();

        return view('supplier.showNotes', compact('user', 'viewid', 'tbl_custom_fields'));
    }
}
