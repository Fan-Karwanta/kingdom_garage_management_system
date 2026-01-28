<?php

namespace App\Http\Controllers;

use App\Branch;
use App\BranchSetting;
use App\CustomField;
use App\Http\Requests\PurchaseAddEditFormRequest;
use App\Product;
use App\Purchase;
use App\Setting;
use App\Stock;
use App\tbl_purchase_history_records;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class Purchasecontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // purchase list
    public function listview(Request $request)
    {
        if ($request->ajax()) {
            $query = Purchase::query()
                ->select(
                    'tbl_purchases.id',
                    'tbl_purchases.branch_id',
                    'tbl_purchases.purchase_no',
                    'tbl_purchases.supplier_id',
                    'tbl_purchases.email',
                    'tbl_purchases.mobile',
                    'tbl_purchases.date',
                    DB::raw("(SELECT GROUP_CONCAT(tbl_products.name SEPARATOR ', ') 
							  FROM tbl_purchase_history_records 
							  JOIN tbl_products 
							  ON tbl_purchase_history_records.product_id = tbl_products.id 
							  WHERE tbl_purchase_history_records.purchase_id = tbl_purchases.id) as products")
                );
            // Apply role-based filtering
            $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
            if (isAdmin(Auth::User()->role_id)) {
                // Admin can see all purchases
                $query->orderBy('tbl_purchases.id', 'DESC');
            } elseif (getUsersRole(Auth::user()->role_id) == 'Accountant') {
                // Accountants can see purchases for their branch
                if (Gate::allows('purchase_owndata')) {
                    $query->where('tbl_purchases.branch_id', '=', $currentUser->branch_id)
                        ->where('tbl_purchases.create_by', '=', Auth::User()->id);
                } else {
                    $query->where('tbl_purchases.branch_id', '=', $currentUser->branch_id);
                }
            } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {

                // $query->where('tbl_purchases.branch_id', '=', $currentUser->branch_id);
                $query->where('tbl_purchases.branch_id', '=', $currentUser->branch_id);

            } else {
                // Default case: Filter by branch
                $query->where('tbl_purchases.branch_id', '=', $currentUser->branch_id);
            }

            // Search functionality
            $searchValue = $request->input('search.value');
            if ($searchValue) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('purchase_no', 'LIKE', "%{$searchValue}%")
                        ->orWhere('email', 'LIKE', "%{$searchValue}%")
                        ->orWhere('mobile', 'LIKE', "%{$searchValue}%");
                });
            }

            // Get the total filtered records
            $filteredRecords = $query->count();

            // Pagination and ordering
            $columns = ['id', 'purchase_no', 'supplier_id', 'email', 'mobile', 'date', 'products'];
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
                'recordsTotal' => Purchase::count(),
                'recordsFiltered' => $filteredRecords,
                'data' => $data->map(function ($purchase) {
                    return [
                        'id' => $purchase->id,
                        // 'purchase_no' => '<a href="#" class="purchasesave" data-id="' . $purchase->id . '">' . $purchase->purchase_no . '</a>',
                        'purchase_no' => '<a href="javascript:void(0)" class="purchasesave" data-bs-toggle="modal" data-bs-target="#purchaseview" purchaseid="'.$purchase->id.'" url="'.url('/purchase/list/modalview').'">'.$purchase->purchase_no.'</a>',
                        'supplier_name' => getCompanyNames($purchase->supplier_id) ?: trans('message.Not Added'),
                        'email' => $purchase->email ?: trans('message.Not Added'),
                        'mobile' => $purchase->mobile ?: trans('message.Not Added'),
                        'date' => date(getDateFormat(), strtotime($purchase->date)),
                        'products' => $purchase->products ?: trans('message.Not Added'),
                        'action' => '<div class="dropdown_toggle">
					<img src="'.asset('public/img/list/dots.png').'" class="btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
					<ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonaction">
						'.(auth()->user()->can('purchase_view') ? '<li><a data-bs-toggle="modal" data-bs-target="#purchaseview" purchaseid="'.$purchase->id.'" url="'.url('/purchase/list/modalview').'" class="purchasesave dropdown-item"><img src="'.asset('public/img/list/Vector.png').'" class="me-3">'.trans('message.View').'</a></li>' : '').'
						
						'.(auth()->user()->can('purchase_edit') ? '<li><a class="dropdown-item" href="'.url('/purchase/list/edit/'.$purchase->id).'"><img src="'.asset('public/img/list/Edit.png').'" class="me-3"> '.trans('message.Edit').'</a></li>' : '').'
						
						'.(auth()->user()->can('purchase_delete') ? '<div class="dropdown-divider m-0"></div><li><a class="dropdown-item deletedatas" url="'.url('/purchase/list/delete/'.$purchase->id).'" style="color:#FD726A"><img src="'.asset('public/img/list/Delete.png').'" class="me-3"> '.trans('message.Delete').'</a></li>' : '').'
					</ul>
				 </div>',
                    ];
                }),
            ];

            return response()->json($response);
        }
        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        // $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

        if (isAdmin(Auth::User()->role_id)) {
            $purchase = Purchase::orderBy('id', 'DESC')->get();
        } elseif (getUsersRole(Auth::user()->role_id) == 'Accountant') {
            if (Gate::allows('purchase_owndata')) {
                $purchase = Purchase::where('branch_id', '=', $currentUser->branch_id)->where('create_by', '=', Auth::User()->id)->orderBy('id', 'DESC')->get();
            } else {
                $purchase = Purchase::where('branch_id', '=', $currentUser->branch_id)->orderBy('id', 'DESC')->get();
            }
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            $purchase = Purchase::orderBy('id', 'DESC')->get();
        } else {
            $purchase = Purchase::where('branch_id', '=', $currentUser->branch_id)->orderBy('id', 'DESC')->get();
        }

        return view('purchase.list', compact('purchase'));
    }

    // purchase list
    public function listview1($id)
    {
        $purchase = Purchase::where('id', '=', $id)->get();

        return view('purchase.list', compact('purchase'));
    }

    // purchase addform
    public function index()
    {
        $characters = '0123456789';
        $code = 'P'.''.substr(str_shuffle($characters), 0, 6);
        $supplier = DB::table('users')->where([['role', '=', 'supplier'], ['soft_delete', '=', 0]])->get()->toArray();

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

        if (isAdmin(Auth::User()->role_id)) {

            $Select_product = DB::table('tbl_product_types')
                ->join('tbl_products', 'tbl_products.product_type_id', '=', 'tbl_product_types.id')
                ->where('tbl_product_types.soft_delete', '=', 0)
                ->groupBy('tbl_products.product_type_id')
                ->get()
                ->toArray();
            $prd_type_id_array = [];
            foreach ($Select_product as $value) {
                $prd_type_id_array[] = $value->id;
            }

            $first_product = DB::table('tbl_products')->where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->first();
            $product = Product::where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id], ['product_type_id', reset($prd_type_id_array)]])->get();

            $branchDatas = Branch::get();
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {

            $Select_product = DB::table('tbl_product_types')
                ->join('tbl_products', 'tbl_products.product_type_id', '=', 'tbl_product_types.id')
                ->where('tbl_product_types.soft_delete', '=', 0)
                ->groupBy('tbl_products.product_type_id')
                ->get()
                ->toArray();

            $prd_type_id_array = [];
            foreach ($Select_product as $value) {
                $prd_type_id_array[] = $value->id;
            }

            $first_product = DB::table('tbl_products')->where('soft_delete', '=', 0)->first();

            $product = Product::where([['soft_delete', '=', 0], ['product_type_id', reset($prd_type_id_array)]])->get();

            $branchDatas = Branch::where('id', $currentUser->branch_id)->get();
        } else {

            $Select_product = DB::table('tbl_product_types')
                ->join('tbl_products', 'tbl_products.product_type_id', '=', 'tbl_product_types.id')
                ->where('tbl_product_types.soft_delete', '=', 0)
                ->groupBy('tbl_products.product_type_id')
                ->get()
                ->toArray();

            $prd_type_id_array = [];
            foreach ($Select_product as $value) {
                if ($currentUser->branch_id == $value->branch_id) {
                    $prd_type_id_array[] = $value->id;
                }
            }

            $product = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id], ['product_type_id', reset($prd_type_id_array)]])->get();

            $first_product = DB::table('tbl_products')->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->first();

            $branchDatas = Branch::where('id', $currentUser->branch_id)->get();
        }
        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'purchase'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

        return view('purchase.add', compact('supplier', 'product', 'code', 'Select_product', 'tbl_custom_fields', 'first_product', 'branchDatas'));
    }

    // get supplier record
    public function getrecord(Request $request)
    {
        $s_id = $request->supplier_id;
        $supplier_record = DB::table('users')->where([['id', '=', $s_id], ['role', '=', 'supplier'], ['soft_delete', '=', 0]])->first();
        $record = json_encode($supplier_record);

        echo $record;
    }

    // get supplier product
    public function getSupplierProduct(Request $request)
    {
        $s_id = $request->supplier_id;
        $Select_product = DB::table('tbl_product_types')
            ->join('tbl_products', 'tbl_products.product_type_id', '=', 'tbl_product_types.id')
            ->where('tbl_product_types.soft_delete', '=', 0)
            ->where('tbl_products.supplier_id', '=', $s_id)
            ->groupBy('tbl_products.product_type_id')
            ->get()
            ->toArray();

        $prd_type_id_array = [];

        foreach ($Select_product as $product) {
            $prd_type_id_array[] = $product->product_type_id;
        }

        $products = Product::where('soft_delete', 0)
            ->whereIn('product_type_id', $prd_type_id_array)
            ->get()
            ->toArray();

        // dd($products);

        $record = json_encode($Select_product);

        echo $record;
    }

    // productitem (purchase product time)
    public function productitem(Request $request)
    {
        $id = $request->m_id;
        $currentUser = User::where([['soft_delete', 0], ['id', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        // if (isAdmin(Auth::User()->role_id) || getUsersRole(Auth::user()->role_id) == 'Branch Admin' || getUsersRole(Auth::user()->role_id) == 'Customer') {
        if (isAdmin(Auth::User()->role_id)) {
            $tbl_products = DB::table('tbl_products')->where([['product_type_id', '=', $id], ['soft_delete', '=', 0]])->get()->toArray();
            if (! empty($tbl_products)) {   ?>
				<!-- <option value="">Select Product</option> -->
				<?php
                foreach ($tbl_products as $tbl_productss) { ?>
					<option value="<?php echo $tbl_productss->id; ?>"><?php echo $tbl_productss->name; ?></option>
				<?php
                }
            } else {
                ?>
				<option value="">--Select Product--</option>
			<?php
            }
        } else {

            $tbl_products = DB::table('tbl_products')->where([['product_type_id', $id], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();

            if (! empty($tbl_products)) {   ?>
				<!-- <option value="">Select Product</option> -->
				<?php
                foreach ($tbl_products as $tbl_productss) { ?>
					<option value="<?php echo $tbl_productss->id; ?>"><?php echo $tbl_productss->name; ?></option>
				<?php
                }
            } else {
                ?>
				<option value="">--Select Product--</option>
			<?php
            }
        }
    }

    // productitems (add salespart time)
    public function productitems(Request $request)
    {
        $id = $request->m_id;
        $currentUser = User::where([['soft_delete', 0], ['id', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        // $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if (isAdmin(Auth::User()->role_id)) {
            $tbl_products = DB::table('tbl_products')->where([['product_type_id', '=', $id], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();

            if (! empty($tbl_products)) {   ?>
				<option value="">--Select Product--</option>
				<?php
                foreach ($tbl_products as $tbl_productss) { ?>
					<option value="<?php echo $tbl_productss->id; ?>"><?php echo $tbl_productss->name; ?></option>
				<?php
                }
            } else {
                ?>
				<option value="">--Select Product--</option>
			<?php
            }
        } else {

            $tbl_products = DB::table('tbl_products')->where([['product_type_id', $id], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();

            if (! empty($tbl_products)) {   ?>
				<option value="">--Select Product--</option>
				<?php
                foreach ($tbl_products as $tbl_productss) { ?>
					<option value="<?php echo $tbl_productss->id; ?>"><?php echo $tbl_productss->name; ?></option>
				<?php
                }
            } else {
                ?>
				<option value="">--Select Product--</option>
			<?php
            }
        }
    }

    // product data
    public function getproduct(Request $request)
    {
        $p_id = $request->p_id;

        if (! empty($p_id)) {
            $t_record = DB::table('tbl_products')->where([['id', '=', $p_id], ['soft_delete', '=', 0], ['branch_id', Auth::User()->branch_id]])->first();
            echo json_encode($t_record);
        } else {
            echo 0;
        }
    }

    // delete product
    // public function deleteproduct(Request $request)
    // {
    //     $productid = $request->procuctid;

    //     $product1 = DB::table('tbl_purchase_history_records')->where('id', '=', $productid)->first();
    //     $pid = $product1->product_id;
    //     $qty = $product1->qty;
    //     $stock = DB::table('tbl_stock_records')->where('product_id', '=', $pid)->first();
    //     $sid = $stock->no_of_stoke;
    //     $total = $sid - $qty;
    //     DB::update("update tbl_stock_records set no_of_stoke='$total' where product_id='$pid'");
    //     $product = DB::table('tbl_purchase_history_records')->where('id', '=', $productid)->delete();
    // }
    // delete product
public function deleteproduct(Request $request)
{
    $productid = $request->procuctid;

    $product1 = DB::table('tbl_purchase_history_records')->where('id', '=', $productid)->first();
    
    if (!empty($product1)) {
        $pid = $product1->product_id;
        $qty = $product1->qty;
        
        $stock = DB::table('tbl_stock_records')->where('product_id', '=', $pid)->first();
        
        if (!empty($stock)) {
            $sid = $stock->no_of_stoke;
            $total = $sid - $qty;
            
            // Update stock
            DB::update("update tbl_stock_records set no_of_stoke='$total' where product_id='$pid'");
        }
        
        // Delete the purchase history record
        DB::table('tbl_purchase_history_records')->where('id', '=', $productid)->delete();
        
        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
    }
    
    return response()->json(['success' => false, 'message' => 'Product not found']);
}

    // product total
    public function getqty(Request $request)
    {
        $qty = $request->qty;
        $price = $request->price;
        $total_price = $qty * $price;
        echo $total_price;
    }

    // product store
    public function store(PurchaseAddEditFormRequest $request)
    {
        $p_date = $request->p_date;
        $p_no = $request->p_no;
        $s_name = $request->s_name;
        $mobile = $request->mobile;
        $email = $request->email;
        $address = $request->address;

        if (getDateFormat() == 'm-d-Y') {
            $dates = date('Y-m-d', strtotime(str_replace('-', '/', $p_date)));
        } else {
            $dates = date('Y-m-d', strtotime($p_date));
        }

        $purchase = new Purchase;
        $purchase->purchase_no = $p_no;
        $purchase->date = $dates;
        $purchase->supplier_id = $s_name;
        $purchase->mobile = $mobile;
        $purchase->email = $email;
        $purchase->address = $address;
        $purchase->branch_id = $request->branch;
        $purchase->create_by = Auth::User()->id;

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
                $purchaseData = $val1;
            }
            $purchase->custom_field = $purchaseData;
        }

        $purchase->save();

        $supplier = User::find($purchase->supplier_id);
        $toEmail = $supplier->email;
        // Save Notes data
        if ($purchase->save()) {
            // if ($request->notes !== null) {
            // 	saveNotes($purchase, $request->notes, $toEmail);
            // }
            if (! empty($request->notes)) {
                // Filter notes to exclude empty ones
                $validNotes = array_filter($request->notes, function ($note) {
                    return ! empty($note['note_text']) || (! empty($note['note_file']) && is_array($note['note_file']));
                });

                // Only call saveNotes if we have valid notes
                if (! empty($validNotes)) {
                    saveNotes($purchase, $request->notes, $toEmail);
                }
            }

        }

        $lat_record = DB::table('tbl_purchases')->orderBy('id', 'desc')->first();
        $purchase_id = $lat_record->id;

        $products = $request->product;

        if (! empty($products)) {
            foreach ($products['product_id'] as $key => $value) {
                $Product_id = $products['product_id'][$key];
                $qty = $products['qty'][$key];
                $price = $products['price'][$key];
                $total_price = $products['total_price'][$key];

                $purchas = new tbl_purchase_history_records;
                $purchas->purchase_id = $purchase_id;
                $purchas->product_id = $Product_id;
                $purchas->qty = $qty;
                $purchas->price = $price;
                $purchas->category = 1; // 1=Part
                $purchas->total_amount = $total_price;
                $purchas->branch_id = $request->branch;
                $purchas->save();

                $stock = DB::table('tbl_stock_records')->where('product_id', '=', $Product_id)->first();
                if (! empty($stock)) {
                    $old_stock = $stock->no_of_stoke;

                    $qty = $products['qty'][$key] + $old_stock;

                    DB::update("update tbl_stock_records set no_of_stoke='$qty' where product_id='$Product_id'");
                } else {
                    $product = new Stock;
                    $product->product_id = $Product_id;
                    $product->supplier_id = $request->s_name;
                    $product->no_of_stoke = $qty;
                    $product->branch_id = $request->branch;
                    $product->save();
                }
            }
        }

        return redirect('purchase/list')->with('message', 'Purchase Added Successfully');
    }

    // product edit
    public function editview($id)
    {
        // $purchase = DB::table('tbl_purchases')->where('id', '=', $id)->first();
        $purchase = Purchase::with('notes')->find($id);

        $supplier = DB::table('users')->where([['role', '=', 'supplier'], ['soft_delete', '=', 0], ['id', '=', $purchase->supplier_id]])->first();
        // dd($supplier);

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        // $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if (isAdmin(Auth::User()->role_id)) {
            $branchDatas = Branch::get();
            $Select_product = DB::table('tbl_product_types')
                ->join('tbl_products', 'tbl_products.product_type_id', '=', 'tbl_product_types.id')
                ->where('tbl_product_types.soft_delete', '=', 0)
                ->where('tbl_products.supplier_id', '=', $purchase->supplier_id)
                ->groupBy('tbl_products.product_type_id')
                ->get()
                ->toArray();
            $prd_type_id_array = [];
            foreach ($Select_product as $value) {
                $prd_type_id_array[] = $value->id;
            }

            $product = Product::where([['soft_delete', 0], ['supplier_id', '=', $purchase->supplier_id]])->get();
           
        } else {
            $branchDatas = Branch::where('id', '=', $currentUser->branch_id)->get();

            $Select_product = DB::table('tbl_product_types')
                ->join('tbl_products', 'tbl_products.product_type_id', '=', 'tbl_product_types.id')
                ->where('tbl_product_types.soft_delete', '=', 0)
                ->where('tbl_products.supplier_id', '=', $purchase->supplier_id)
                ->groupBy('tbl_products.product_type_id')
                ->get()
                ->toArray();

            $prd_type_id_array = [];
            foreach ($Select_product as $value) {
                if ($currentUser->branch_id == $value->branch_id) {
                    $prd_type_id_array[] = $value->id;
                }
            }

            $product = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id], ['supplier_id', '=', $purchase->supplier_id]])->get();
        }

        $stock = DB::table('tbl_purchase_history_records')->where('purchase_id', '=', $id)->get()->toArray();

        // Custom Field Data
        $tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'purchase'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

        // dd($product);
        return view('purchase.edit', compact('supplier', 'product', 'purchase', 'stock', 'Select_product', 'tbl_custom_fields', 'branchDatas'));
    }

    // product delete
    public function destory($id)
    {
        $stock = DB::table('tbl_purchase_history_records')->where('purchase_id', '=', $id)->get()->toArray();
        foreach ($stock as $stock) {
            $product_id = $stock->product_id;

            $getqty = DB::table('tbl_purchase_history_records')->where([['product_id', '=', $product_id], ['purchase_id', '=', $id]])->first();
            $total = $getqty->qty;

            $stock1 = DB::table('tbl_stock_records')->where('product_id', '=', $product_id)->first();

            if (! empty($stock1)) {
                $old_stock = $stock1->no_of_stoke;

                $qty = $old_stock - $total;

                DB::update("update tbl_stock_records set no_of_stoke='$qty' where product_id='$product_id'");
            }
        }

        $purchase = DB::table('tbl_purchases')->where('id', '=', $id)->delete();

        $purchase = DB::table('tbl_purchase_history_records')->where('purchase_id', '=', $id)->delete();

        return redirect('purchase/list')->with('message', 'Purchase Deleted Successfully');
    }

    public function destoryMultiple(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $this->destory($id);
        }

        return response()->json(['message' => 'Purchase Deleted Successfully selected purchase records']);
    }

    // product update
    public function update($id, PurchaseAddEditFormRequest $request)
    {
        $p_date = $request->p_date;
        $p_no = $request->p_no;
        $s_name = $request->s_name;
        $mobile = $request->mobile;
        $email = $request->email;
        $address = $request->address;

        if (getDateFormat() == 'm-d-Y') {
            $dates = date('Y-m-d', strtotime(str_replace('-', '/', $p_date)));
        } else {
            $dates = date('Y-m-d', strtotime($p_date));
        }

        $purchase = Purchase::find($id);
        $purchase->purchase_no = $p_no;
        $purchase->date = $dates;
        $purchase->supplier_id = $s_name;
        $purchase->mobile = $mobile;
        $purchase->email = $email;
        $purchase->address = $address;
        $purchase->branch_id = $request->branch;

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
                $purchaseData = $val1;
            }
            $purchase->custom_field = $purchaseData;
        }

        $purchase->save();

        $supplier = User::find($purchase->supplier_id);
        $toEmail = $supplier->email;
        // Save Notes data
        if ($purchase->save()) {
            // if ($request->notes !== null) {
            // 	saveNotes($purchase, $request->notes, $toEmail);
            // }
            if (! empty($request->notes)) {
                // Filter notes to exclude empty ones
                $validNotes = array_filter($request->notes, function ($note) {
                    return ! empty($note['note_text']) || (! empty($note['note_file']) && is_array($note['note_file']));
                });

                // Only call saveNotes if we have valid notes
                if (! empty($validNotes)) {
                    saveNotes($purchase, $request->notes, $toEmail);
                }
            }
        }

        $products = $request->product;

        $stock_no = DB::table('tbl_purchase_history_records')->where('purchase_id', '=', $id)->get()->toArray();

        if (! empty($stock_no)) {
            foreach ($stock_no as $stock_nos) {
                $productids = $stock_nos->product_id;

                if (! empty($productids)) {
                    $stocknos = DB::table('tbl_purchase_history_records')->where([['purchase_id', '=', $id], ['product_id', '=', $productids]])->first();

                    $pr_id = $stocknos->product_id;
                    $qtyold = $stocknos->qty;
                    $stock = DB::table('tbl_stock_records')->where('product_id', '=', $pr_id)->first();

                    $stock_id = $stock->id;
                    $qtyolds = $stock->no_of_stoke;
                    $newqty = $qtyolds - $qtyold;
                    $stcoksnew = Stock::find($stock_id);
                    $stcoksnew->product_id = $productids;
                    $stcoksnew->no_of_stoke = $newqty;
                    $stcoksnew->branch_id = $request->branch;
                    $stcoksnew->save();
                }
            }
        }

        if (! empty($products)) {
            foreach ($products['product_id'] as $key => $value) {
                $purchase_hiatory_id = $products['tr_id'][$key];
                $Product_id = $products['product_id'][$key];
                $qty = $products['qty'][$key];
                $price = $products['price'][$key];
                $total_price = $products['total_price'][$key];

                $stockno = DB::table('tbl_purchase_history_records')->where('purchase_id', '=', $id)->get()->toArray();

                if ($purchase_hiatory_id != '') {
                    $history = tbl_purchase_history_records::find($purchase_hiatory_id);
                    $history->product_id = $Product_id;
                    $history->qty = $qty;
                    $history->price = $price;
                    $history->total_amount = $total_price;
                    $history->category = 1; // 1=Part
                    $history->branch_id = $request->branch;
                    $history->save();
                } else {
                    $history = new tbl_purchase_history_records;
                    $history->product_id = $Product_id;
                    $history->purchase_id = $id;
                    $history->qty = $qty;
                    $history->price = $price;
                    $history->total_amount = $total_price;
                    $history->category = 1; // 1=Part
                    $history->branch_id = $request->branch;
                    $history->save();
                }

                $stocks = DB::table('tbl_purchase_history_records')->where('product_id', '=', $Product_id)->get()->toArray();

                $qtytotal = 0;
                foreach ($stocks as $stockss) {
                    $pur_stock = $stockss->qty;
                    $qtytotal += $pur_stock;
                }

                $stock = DB::table('tbl_stock_records')->where('product_id', '=', $Product_id)->first();
                // $pid = $stock->product_id;
                if (! empty($stock)) {
                    $sid = $stock->id;
                    $stockes = Stock::find($sid);
                    $stockes->product_id = $Product_id;
                    $stockes->supplier_id = $request->s_name;
                    $stockes->no_of_stoke = $qtytotal;
                    $stockes->branch_id = $request->branch;
                    $stockes->save();
                } else {
                    $stocks = new Stock;
                    $stocks->product_id = $Product_id;
                    $stocks->supplier_id = $request->s_name;
                    $stocks->no_of_stoke = $qty;
                    $stocks->branch_id = $request->branch;
                    $stocks->save();
                }
            }
        }

        return redirect('purchase/list')->with('message', 'Purchase Updated Successfully');
    }

    // modal view for product
    public function purchaseview(Request $request)
    {
        $purchaseid = $request->purchaseid;

        $logo = Setting::first();
        $purchas = Purchase::where('id', '=', $purchaseid)->first();
        $purchasdetails = DB::table('tbl_purchase_history_records')->where('purchase_id', '=', $purchaseid)->get()->toArray();

        $tbl_custom_fields = CustomField::where([['form_name', '=', 'purchase'], ['always_visable', '=', 'yes']])->get();

        $html = view('purchase.modal')->with(compact('purchasdetails', 'purchas', 'logo', 'purchaseid', 'tbl_custom_fields'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // get product name
    public function getproductname(Request $request)
    {
        $ids = $request->row_id;
        $s_id = $request->supplier_id;

        $id = $ids;
        $rowid = 'row_id_'.$ids;

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
        if (isAdmin(Auth::User()->role_id)) {

            $Select_product = DB::table('tbl_product_types')
                ->join('tbl_products', 'tbl_products.product_type_id', '=', 'tbl_product_types.id')
                ->where('tbl_product_types.soft_delete', '=', 0)
                ->where('tbl_products.supplier_id', '=', $s_id)
                ->groupBy('tbl_products.product_type_id')
                ->get()
                ->toArray();

            $prd_type_id_array = [];
            foreach ($Select_product as $value) {
                $prd_type_id_array[] = $value->id;
            }
            $first_product = DB::table('tbl_products')->where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id]])->first();
            $product = Product::where([['soft_delete', 0], ['branch_id', $adminCurrentBranch->branch_id], ['product_type_id', reset($prd_type_id_array)]])->get();
        } else {

            $Select_product = DB::table('tbl_product_types')
                ->join('tbl_products', 'tbl_products.product_type_id', '=', 'tbl_product_types.id')
                ->where('tbl_product_types.soft_delete', '=', 0)
                ->where('tbl_products.supplier_id', '=', $s_id)
                ->groupBy('tbl_products.product_type_id')
                ->get()
                ->toArray();

            $prd_type_id_array = [];
            foreach ($Select_product as $value) {
                if ($currentUser->branch_id == $value->branch_id) {
                    $prd_type_id_array[] = $value->id;
                }
            }

            $product = Product::where([['soft_delete', 0], ['branch_id', $currentUser->branch_id], ['product_type_id', reset($prd_type_id_array)]])->get();

            $first_product = DB::table('tbl_products')->where([['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->first();
        }

        $html = view('purchase.newproduct')->with(compact('id', 'ids', 'rowid', 'product', 'Select_product', 'first_product'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // get category Item
    public function Categoryitem(Request $request)
    {
        $id = $request->m_id;

        $tbl_products = DB::table('tbl_products')->where([['product_type_id', '=', $id], ['soft_delete', '=', 0]])->get()->toArray();

        if (! empty($tbl_products)) {   ?>
			<option value="">--Select Product--</option>
			<?php
            foreach ($tbl_products as $tbl_productss) { ?>
				<option value="<?php echo $tbl_productss->id; ?>"><?php echo $tbl_productss->name; ?></option>
			<?php
            }
        } else {
            ?>
			<option value="">--Select Product--</option>
<?php
        }
    }

    public function getFirstProductData(Request $request)
    {
        $productTypeId = $request->productTypeId;

        if (! empty($productTypeId)) {
            $first_product = DB::table('tbl_products')->where([['product_type_id', '=', $productTypeId], ['soft_delete', '=', 0]])->orderBy('id', 'ASC')->first();

            if (! empty($first_product)) {
                $prices = $first_product->price;
                $productNumber = $first_product->product_no;

                return response()->json(['success' => 'yes', 'data' => $prices, 'product_number' => $productNumber]);
            }
        }
    }
}
