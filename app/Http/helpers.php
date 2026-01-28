<?php

/* New code for Accessrights */

// Get User Role From Id

// use Illuminate\Support\Facades\Auth;

use App\note_attachments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

if (! function_exists('getVersionNumber')) {
    function getVersionNumber()
    {
        $logo = DB::table('tbl_settings')->first();
        if (! empty($logo)) {
            $version = $logo->version;

            return $version;
        } else {
            return null;
        }
    }
}
if (! function_exists('getUsersRole')) {
    function getUsersRole($id)
    {
        $data = DB::table('roles')->where('id', $id)->first();
        if (! empty($data)) {
            $name = $data->role_name;

            return $name;
        } else {
            return null;
        }
    }
}
if (! function_exists('getWashBayCharge')) {
    function getWashBayCharge($id)
    {
        $data = DB::table('washbays')->where('jobcard_no', $id)->first();
        if (! empty($data)) {
            $charge = $data->price;

            return $charge;
        } else {
            return null;
        }
    }
}

// check if role has access of admin or not
if (! function_exists('isAdmin')) {
    function isAdmin($roleId)
    {
        $role = DB::table('roles')->find($roleId);

        if ($role) {
            if ($role->is_admin == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }
}

// check if role has access of branch_admin or not
if (! function_exists('isBranchAdmin')) {
    function isBranchAdmin($roleId)
    {
        $role = DB::table('roles')->find($roleId);

        if ($role) {
            if ($role->role_slug == 'branch_admin') {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }
}
/* --------------End Accessrights code-------------- */

/* -------------Start New Functions-------------------- */
// Get User role from User Table
if (! function_exists('getUserRoleFromUserTable')) {
    function getUserRoleFromUserTable($id)
    {
        $userData = DB::table('users')->find($id);

        if ($userData) {
            return $userData->role;
        } else {
            return null;
        }
    }
}

/* -------------End New Functions-------------------- */

// get categry
if (! function_exists('getCategory')) {
    function getCategory($id)
    {
        switch ($id) {
            case 0:
                return 'Vehicle';
            case 1:
                return 'Part';
            default:
                return 'Invalid';

        }
    }
}

// get part
if (! function_exists('getPart')) {
    function getPart($id)
    {
        return DB::table('tbl_products')->find($id);
    }
}

// Get Vehicle List Name In Cusromer module
if (! function_exists('getVehicles')) {
    function getVehicles($id)
    {
        $tbl_vehicles = DB::table('tbl_vehicles')->where('customer_id', '=', $id)->where('soft_delete', '=', 0)->get()->toArray();
        if (! empty($tbl_vehicles)) {
            $customer_id = [];
            foreach ($tbl_vehicles as $tbl_vehicles) {
                $vehicleBrand = getvehicleBrand($tbl_vehicles->vehiclebrand_id);
                $modelName = $tbl_vehicles->modelname;
                $numberPlate = $tbl_vehicles->number_plate;
                $customer_id[] = "$vehicleBrand/$modelName/$numberPlate";
            }
            $name = implode("\n", $customer_id);
            // dd($name);
            // return $name;
            echo '<pre>'.$name.'</pre>';
        } else {
            return 'Vehicle-not-available';
        }
    }
}
if (! function_exists('getVehiclesdata')) {
    function getVehiclesdata($id)
    {
        $tbl_vehicles = DB::table('tbl_vehicles')->where('customer_id', '=', $id)->where('soft_delete', '=', 0)->get()->toArray();
        if (! empty($tbl_vehicles)) {
            $customer_id = [];
            foreach ($tbl_vehicles as $tbl_vehicle) {
                $vehicleBrand = getvehicleBrand($tbl_vehicle->vehiclebrand_id); // Assuming this function exists and returns vehicle brand
                $modelName = $tbl_vehicle->modelname;
                $numberPlate = $tbl_vehicle->number_plate;
                $customer_id[] = "$vehicleBrand/$modelName/$numberPlate";
            }

            // Return the concatenated string of vehicle data
            return implode("\n", $customer_id);
        } else {
            return 'Vehicle-not-available';
        }
    }
}

if (! function_exists('getVehicles1')) {
    function getVehicles1($id)
    {
        $tbl_vehicles = DB::table('tbl_vehicles')->where('customer_id', '=', $id)->where('soft_delete', '=', 0)->get()->toArray();
        if (! empty($tbl_vehicles)) {
            $customer_id = [];
            foreach ($tbl_vehicles as $tbl_vehicles) {
                $vehicleBrand = getvehicleBrand($tbl_vehicles->vehiclebrand_id);
                $modelName = $tbl_vehicles->modelname;
                $numberPlate = $tbl_vehicles->number_plate;
                $id = $tbl_vehicles->id;
                $customer_id[] = "$vehicleBrand/$modelName/$numberPlate/$id";
            }
            $name = $customer_id;
            $arr = implode(',', $name);
            $option = explode(',', $arr);

            // dd($name);
            return $option;
        }
    }
}

// Get getRegistrationNo
if (! function_exists('getRegistrationNo')) {
    function getRegistrationNo($id)
    {
        $tbl_sales = DB::table('tbl_sales')->where('vehicle_id', '=', $id)->first();

        if (! empty($tbl_sales)) {
            $registration_no = $tbl_sales->registration_no;

            return $registration_no;
        } else {
            $tbl_vehicles = DB::table('tbl_vehicles')->where('id', '=', $id)->first();
            if (! empty($tbl_vehicles)) {
                $regno = $tbl_vehicles->registration_no;

                return $regno;
            } else {
                return '';
            }
        }
    }
}
// Get getProductcode
if (! function_exists('getProductcode')) {
    function getProductcode($id)
    {
        $product = DB::table('tbl_products')->where('id', '=', $id)->first();
        if (! empty($product)) {
            $code = $product->product_no;

            return $code;
        } else {
            return '';
        }
    }
}

// Get mobile number with country code
if (! function_exists('getMobileNoWithCode')) {
    function getMobileNoWithCode($userId)
    {
        // Get user data
        $user = \App\User::find($userId);
        if (! $user || empty($user->mobile_no)) {
            return null; // Return null if user or mobile number not found
        }

        // Get country phone code
        $mo_code = DB::table('tbl_countries')->where('id', $user->country_id)->value('phonecode');
        if (! $mo_code) {
            return $user->mobile_no; // Return mobile number as is if no country code is found
        }

        // Format country code
        $mo_code = '+'.$mo_code;

        // Check if mobile number already contains the country code
        if (strpos($user->mobile_no, $mo_code) === 0) {
            return $user->mobile_no; // Return unchanged if already has country code
        }

        return $mo_code.$user->mobile_no; // Append country code if not present
    }
}
// Get getCellProduct
if (! function_exists('getTotalProduct')) {
    function getTotalProduct($id, $s_date, $e_date)
    {
        if ($s_date == '' && $e_date == '') {
            $totalstock = DB::table('tbl_purchase_history_records')->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
                ->where('product_id', '=', $id)
                ->get()->toArray();
        } else {
            $totalstock = DB::table('tbl_purchase_history_records')->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
                ->whereBetween('date', [$s_date, $e_date])
                ->where('product_id', '=', $id)
                ->get()->toArray();
        }

        $stocktotal = 0;
        if (! empty($totalstock)) {
            foreach ($totalstock as $totalstocks) {
                $total_stock = $totalstocks->qty;
                $stocktotal += $total_stock;
            }

            return $stocktotal;
        } else {
            return 0;
        }
    }
}

// get cell product sale
if (! function_exists('getTotalServiceProduct')) {
    function getTotalServiceProduct($id, $s_date, $e_date)
    {
        if ($s_date == '' && $e_date == '') {
            $serviceStocks = DB::table('tbl_service_pros')
                ->where('product_id', '=', $id)
                ->get()->toArray();
        } else {
            $serviceStocks = DB::table('tbl_service_pros')
                ->whereBetween('created_at', [$s_date, $e_date])
                ->where('product_id', '=', $id)
                ->get()->toArray();
        }

        $serviceTotal = 0;
        if (! empty($serviceStocks)) {
            foreach ($serviceStocks as $serviceStock) {
                $service_stock = $serviceStock->quantity;
                $serviceTotal += $service_stock;
            }

            return $serviceTotal;
        } else {
            return 0;
        }
    }
}

// Get getCellProduct
if (! function_exists('getCellProduct')) {
    function getCellProduct($id, $s_date, $e_date)
    {
        if ($s_date == '' && $e_date == '') {
            $cellstock = DB::table('tbl_service_pros')->JOIN('tbl_services', 'tbl_services.id', '=', 'tbl_service_pros.service_id')
                ->where('product_id', '=', $id)
                ->get()->toArray();
        } else {
            $cellstock = DB::table('tbl_service_pros')->JOIN('tbl_services', 'tbl_services.id', '=', 'tbl_service_pros.service_id')
                ->whereBetween('service_date', [$s_date, $e_date])
                ->where('product_id', '=', $id)
                ->get()->toArray();
        }
        $celltotal = 0;
        if (! empty($cellstock)) {
            foreach ($cellstock as $cellstocks) {
                $cell_stock = $cellstocks->quantity;
                $celltotal += $cell_stock;
            }

            return $celltotal;
        } else {
            return 0;
        }
    }
}

// get cell product sale
if (! function_exists('getCellProductSale')) {
    function getCellProductSale($id, $s_date, $e_date)
    {
        if ($s_date == '' && $e_date == '') {
            $cellstock = DB::table('tbl_sale_parts')
                ->where('product_id', '=', $id)
                ->get()->toArray();
        } else {
            $cellstock = DB::table('tbl_sale_parts')
                ->whereBetween('date', [$s_date, $e_date])
                ->where('product_id', '=', $id)
                ->get()->toArray();
        }
        $celltotal = 0;
        if (! empty($cellstock)) {
            foreach ($cellstock as $cellstocks) {
                $cell_stock = $cellstocks->quantity;
                $celltotal += $cell_stock;
            }

            return $celltotal;
        } else {
            return 0;
        }
    }
}

// Get getStockProduct
if (! function_exists('getStockProduct')) {
    function getStockProduct($id, $s_date, $e_date)
    {
        if ($s_date == '' && $e_date == '') {
            $totalstock = DB::table('tbl_purchase_history_records')->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
                ->where('product_id', '=', $id)
                ->get()->toArray();

            $cellstock = DB::table('tbl_service_pros')->JOIN('tbl_services', 'tbl_services.id', '=', 'tbl_service_pros.service_id')
                ->where('product_id', '=', $id)
                ->get()->toArray();
        } else {
            $totalstock = DB::table('tbl_purchase_history_records')->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
                ->whereBetween('date', [$s_date, $e_date])
                ->where('product_id', '=', $id)
                ->get()->toArray();

            $cellstock = DB::table('tbl_service_pros')->JOIN('tbl_services', 'tbl_services.id', '=', 'tbl_service_pros.service_id')
                ->whereBetween('service_date', [$s_date, $e_date])
                ->where('product_id', '=', $id)
                ->get()->toArray();
        }

        $stocktotal = 0;
        if (! empty($totalstock)) {
            foreach ($totalstock as $totalstocks) {
                $total_stock = $totalstocks->qty;
                $stocktotal += $total_stock;
            }
            $currenttotal = $stocktotal;
        } else {
            $currenttotal = 0;
        }

        $celltotal = 0;
        if (! empty($cellstock)) {
            foreach ($cellstock as $cellstocks) {
                $cell_stock = $cellstocks->quantity;
                $celltotal += $cell_stock;
            }
            $totalcellcurrent = $celltotal;
        } else {
            $totalcellcurrent = 0;
        }

        $finalcurrenttotal = $currenttotal - $totalcellcurrent;

        return $finalcurrenttotal;
    }
}

// Get getStockProduct
if (! function_exists('getTotalStock')) {
    function getTotalStock($id)
    {
        $totalstock = DB::table('tbl_purchase_history_records')->JOIN('tbl_purchases', 'tbl_purchases.id', '=', 'tbl_purchase_history_records.purchase_id')
            ->where('product_id', '=', $id)
            ->get()->toArray();
        $stocktotal = 0;
        if (! empty($totalstock)) {
            foreach ($totalstock as $totalstocks) {
                $total_stock = $totalstocks->qty;
                $stocktotal += $total_stock;
            }
            $total = $stocktotal;
        } else {
            $total = $stocktotal;
        }
        $cellstock = DB::table('tbl_service_pros')->where('product_id', '=', $id)
            ->get()->toArray();
        $celltotal = 0;
        if (! empty($cellstock)) {
            foreach ($cellstock as $cellstocks) {
                $cell_stock = $cellstocks->quantity;
                $celltotal += $cell_stock;
            }
            $totalcellcurrent = $celltotal;
        } else {
            $totalcellcurrent = $celltotal;
        }

        $salepart_stocks = DB::table('tbl_sale_parts')->where('product_id', '=', $id)->get()->toArray();
        $salepart_total = 0;
        foreach ($salepart_stocks as $salepart_stock) {
            $salepart_stock = $salepart_stock->quantity;
            $salepart_total += $salepart_stock;
        }

        $total_sale_service_product = $totalcellcurrent + $salepart_total;

        $totalcurrentstock = $total - $total_sale_service_product;

        return $totalcurrentstock;
    }
}

// Get getEmployeeservice
if (! function_exists('getEmployeeservice')) {
    function getEmployeeservice($id, $salesid, $nowmonthdate, $nowmonthdate1)
    {

        $tbl_services = DB::select("SELECT * FROM tbl_services where (done_status=2) and (assign_to='$id') and (sales_id='$salesid') and(service_date BETWEEN '".$nowmonthdate."' AND  '".$nowmonthdate1."')");

        if (! empty($tbl_services)) {
            foreach ($tbl_services as $tbl_services) {
                $assign_to = $tbl_services->assign_to;
                $admin = DB::table('users')->where('id', '=', $assign_to)->first();
                $dd = $admin->id;

                return $dd;
            }
        } else {
            return '';
        }
    }
}
// Get model name in sales module

if (! function_exists('getModelName')) {
    function getModelName($id)
    {
        $tbl_vehicles = DB::table('tbl_vehicles')->where('id', '=', $id)->first();

        if (! empty($tbl_vehicles)) {
            $modelname = $tbl_vehicles->modelname;

            return $modelname;
        } else {
            return '';
        }
    }
}

// Get Unit  name in jobcardproccess module

if (! function_exists('getUnitName')) {
    function getUnitName($id)
    {
        $tbl_product_units = DB::table('tbl_product_units')->where('id', '=', $id)->first();

        if (! empty($tbl_product_units)) {
            $name = $tbl_product_units->name;

            return $name;
        } else {
            return '';
        }
    }
}
// Get invoice number from tbl_invoices
if (! function_exists('getInvoiceNumber')) {
    function getInvoiceNumber($id)
    {
        $data = DB::table('tbl_invoices')->where([['sales_service_id', $id], ['job_card', 'NOT LIKE', 'J%'], ['type', 1], ['soft_delete', '=', 0]])->first();

        if (! empty($data)) {
            $invoice = $data->invoice_number;

            return $invoice;
        } else {
            return 'No data';
        }
    }
}

// get invoice number from invoice ID
if (! function_exists('getInvoiceNumbers')) {
    function getInvoiceNumbers($id)
    {
        $data = DB::table('tbl_invoices')->where([['sales_service_id', $id], ['type', 2]])->first();

        if (! empty($data)) {
            $invoice = $data->invoice_number;

            return $invoice;
        } else {
            return 'No data';
        }
    }
}

// get invoice number from invoice table for service invoice
if (! function_exists('getInvoiceNumbersForServiceInvoice')) {
    function getInvoiceNumbersForServiceInvoice($id)
    {
        $data = DB::table('tbl_invoices')->where([['job_card', $id], ['type', 0]])->first();

        if (! empty($data)) {
            $invoice = $data->invoice_number;

            return $invoice;
        } else {
            return 'No Data';
        }
    }
}

// get invoice number from invoice table for Sales invoice
if (! function_exists('getInvoiceNumbersForSaleInvoice')) {
    function getInvoiceNumbersForSaleInvoice($id)
    {
        $data = DB::table('tbl_invoices')->where([['sales_service_id', $id], ['type', 1], ['soft_delete', 0]])->first();

        if (! empty($data)) {
            $invoice = $data->invoice_number;

            return $invoice;
        } else {
            return 'No Data';
        }
    }
}

// get invoice number from invoice table for sales part invoice
if (! function_exists('getInvoiceNumbersForSalepartInvoice')) {
    function getInvoiceNumbersForSalepartInvoice($id)
    {
        $data = DB::table('tbl_invoices')->where([['sales_service_id', $id], ['type', 2]])->first();

        if (! empty($data)) {
            $invoice = $data->invoice_number;

            return $invoice;
        } else {
            return 'No Data';
        }
    }
}

// Select Product
if (! function_exists('getSelectedProduct')) {
    function getSelectedProduct($id, $pro_id)
    {

        $data = DB::table('tbl_service_pros')->where([['service_id', '=', $id], ['product_id', '=', $pro_id]])->first();

        if (! empty($data)) {
            $p_id = $data->product_id;

            return $p_id;
        }
    }
}
// Get Sum Of Income
if (! function_exists('getSumOfIncome')) {
    function getSumOfIncome($id)
    {

        $data = DB::table('tbl_income_history_records')->where('tbl_income_id', '=', $id)->SUM('income_amount');

        return $data;
    }
}

// Get Sum Of Expense
if (! function_exists('getSumOfExpense')) {
    function getSumOfExpense($id)
    {
        $data = DB::table('tbl_expenses_history_records')->where('tbl_expenses_id', '=', $id)->SUM('expense_amount');

        return $data;
    }
}
// Get Invoice Status
if (! function_exists('getInvoiceStatus')) {
    function getInvoiceStatus($jobcard)
    {

        $data = DB::table('tbl_invoices')->where('job_card', '=', $jobcard)->first();

        if (! empty($data)) {
            return 'Yes';
        } else {
            return 'No';
        }
    }
}
// Get Invoice Status
if (! function_exists('getInvoicePaymentStatus')) {
    function getInvoicePaymentStatus($jobcard)
    {

        $data = DB::table('tbl_invoices')->where('job_card', '=', $jobcard)->first();

        if (! empty($data)) {
            return $data->payment_status;
        } else {
            return '1';
        }
    }
}
// Get status of processed jobcard for gatepass
if (! function_exists('getJobcardStatus')) {
    function getJobcardStatus($jobcard)
    {
        $data = DB::table('tbl_gatepasses')->where('jobcard_id', '=', $jobcard)->first();
        if (! empty($data)) {
            $jbno = $data->ser_pro_status;

            return $jbno;
        }
    }
}

// Get status for checked observation points
if (! function_exists('getCheckedStatus')) {
    function getCheckedStatus($id, $ids)
    {
        $data = DB::table('tbl_service_observation_points')
            ->join('tbl_service_pros', 'tbl_service_observation_points.id', '=', 'tbl_service_pros.tbl_service_observation_points_id')
            ->where([['tbl_service_observation_points.observation_points_id', '=', $id], ['tbl_service_observation_points.services_id', '=', $ids], ['tbl_service_pros.type', '=', 0]])->first();

        if (! empty($data)) {
            $review = $data->review;

            if ($review == 1) {
                return 'checked';
            } else {
                return '';
            }
        }
    }
}

// Get observation points

if (! function_exists('getObservationPoint')) {
    function getObservationPoint($id)
    {
        $data = DB::table('tbl_points')->where('id', '=', $id)->first();
        if (! empty($data)) {
            $name = $data->checkout_point;

            return $name;
        }
    }
}

// Get subcategory of the main checkpoints
if (! function_exists('getCheckPointSubCategory')) {
    function getCheckPointSubCategory($id, $vid)
    {

        // $data  = DB::table('tbl_points')->where([['checkout_subpoints','=',$id],['vehicle_id','=',$vid]])->get()->toArray();

        $data = DB::table('tbl_points')->where([['checkout_subpoints', '=', $id], ['vehicle_id', '=', $vid], ['soft_delete', '=', 0]])->get()->toArray();

        if (! empty($data)) {
            return $data;
        }
    }
}

// Get subcategory of the main checkpoints
if (! function_exists('getDataFromCheckoutCategorie')) {
    function getDataFromCheckoutCategorie($id, $vid)
    {

        // $data  = DB::table('tbl_points')->where([['checkout_subpoints','=',$id],['vehicle_id','=',$vid]])->get()->toArray();

        $data = DB::table('tbl_points')->where([['checkout_subpoints', '=', $id], ['vehicle_id', '=', $vid], ['soft_delete', '=', 0], ['checkout_point', '!=', '']])->get()->toArray();

        if (! empty($data)) {
            return $data;
        }
    }
}

// Get checkpoints of main category
if (! function_exists('getCheckPoint')) {
    function getCheckPoint($id)
    {
        $categorypoint = [];
        $categorypoint = DB::table('tbl_points')->where('checkout_subpoints', '=', $id)->get()->toArray();
        if (! empty($categorypoint)) {
            return $categorypoint;
        } else {
            return $categorypoint;
        }
    }
}

// Get Selected Observation points
// if (!function_exists('getSelectedObservationPoints')) {
//     /**
//      * Get selected observation points for a given service ID
//      *
//      * @param int $serviceId
//      * @return array
//      */
//     function getSelectedObservationPoints($serviceId)
//     {
//         return DB::table('tbl_service_pros')
//             ->where('service_id', $serviceId)
//             ->get()
//             ->map(function ($item) {
//                 return [
//                     'category' => $item->category,
//                     'obs_point' => $item->obs_point,
//                     'comment' => $item->comment,
//                     'obs_id' => $item->tbl_service_observation_points_id,
//                 ];
//             })
//             ->toArray();
//     }
// }
if (! function_exists('getSelectedObservationPoints')) {
    /**
     * Get selected observation points for a given service ID
     *
     * @param  int  $serviceId
     * @return array
     */
    function getSelectedObservationPoints($serviceId)
    {
        // Fetch observation points with relationships
        $data = DB::table('tbl_points')
            ->join('tbl_service_observation_points', 'tbl_service_observation_points.observation_points_id', '=', 'tbl_points.id')
            ->where([
                ['tbl_service_observation_points.services_id', '=', $serviceId],
                ['tbl_service_observation_points.review', '=', 1],
            ])
            ->select('tbl_points.*', 'tbl_service_observation_points.id as obs_id')
            ->get();

        // Map data to include category comments from tbl_service_pros
        $result = $data->map(function ($item) {
            $comment = DB::table('tbl_service_pros')
                ->where('tbl_service_observation_points_id', $item->obs_id)
                ->value('category_comments');

            return [
                'category' => $item->checkout_subpoints ?? '', // Assuming `category` exists in tbl_points
                'obs_point' => $item->checkout_point ?? '', // Assuming `name` represents observation point in tbl_points
                'comment' => $comment ?? '', // Add comments from tbl_service_pros
                'obs_id' => $item->obs_id, // Observation point ID  checkout_subpoints,checkout_point,category_comments
            ];
        });

        return $result->toArray();
    }
}

// GET value if Gatepass already created

if (! function_exists('getAlreadypasss')) {
    function getAlreadypasss($job_no)
    {
        $jobno = DB::table('tbl_gatepasses')->where('jobcard_id', $job_no)->count();
        if ($jobno > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}

// Get City Name In Customer,Employee,supplier module
if (! function_exists('getCityName')) {
    function getCityName($id)
    {
        $city = DB::table('tbl_cities')->where('id', '=', $id)->first();
        if (! empty($city)) {
            $city_name = $city->name;

            return $city_name;
        }
    }
}

// Get State Name In Customer,Employee,supplier module
if (! function_exists('getStateName')) {
    function getStateName($id)
    {
        $state = DB::table('tbl_states')->where('id', '=', $id)->first();
        if (! empty($state)) {
            $state_name = $state->name;

            return $state_name;
        }
    }
}

// Get Country Name In Customer,Employee,supplier module
if (! function_exists('getCountryName')) {
    function getCountryName($id)
    {
        $country = DB::table('tbl_countries')->where('id', '=', $id)->first();
        if (! empty($country)) {
            $country_name = $country->name;

            return $country_name;
        }
    }
}

// Get Product Name In Producttype module
if (! function_exists('getProductName')) {
    function getProductName($id)
    {
        $product_tpye = DB::table('tbl_product_types')->where('id', '=', $id)->first();
        if (! empty($product_tpye)) {
            $product_name = $product_tpye->type;

            return $product_name;
        }
    }
}

// Get Product Name In getproducttyid module
if (! function_exists('getproducttyid')) {
    function getproducttyid($id)
    {
        $product_tpye = DB::table('tbl_products')->where('id', '=', $id)->first();
        if (! empty($product_tpye)) {
            $product_type_id = $product_tpye->product_type_id;

            return $product_type_id;
        }
    }
}

// Get Product Name In Product module
if (! function_exists('getProduct')) {
    function getProduct($id)
    {
        $product = DB::table('tbl_products')->where('id', '=', $id)->first();
        if (! empty($product)) {
            $productname = $product->name;

            return $productname;
        }
    }
}
// Get Supplier Name In Product module
if (! function_exists('getSupplierName')) {
    function getSupplierName($id)
    {
        $users = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Supplier']])->first();
        if (! empty($users)) {
            $supplier_name = $users->name.' '.$users->lastname;

            return $supplier_name;
        }
    }
}

// Get Supplier FullName In Product module
if (! function_exists('getSupplierFullName')) {
    function getSupplierFullName($id)
    {
        $users = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Supplier']])->first();
        if (! empty($users)) {
            $supplier_name = $users->name.' '.$users->lastname;

            return $supplier_name;
        }
    }
}

// Get Company Name In Product module
if (! function_exists('getCompanyName')) {
    function getCompanyName($id)
    {
        $users = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Supplier']])->first();
        if (! empty($users)) {
            $display_name = $users->display_name;

            return $display_name;
        }
    }
}

// Get Company Name In Product module
if (! function_exists('getCompanyNames')) {
    function getCompanyNames($id)
    {
        $users = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Supplier']])->first();
        if (! empty($users)) {
            $display_name = $users->company_name;

            return $display_name;
        }
    }
}

// Get Product List Name In Supplier module
if (! function_exists('getProductList')) {
    function getProductList($id)
    {
        $tbl_products = DB::table('tbl_products')->where([['soft_delete', '=', 0], ['supplier_id', '=', $id]])->get()->toArray();
        if (! empty($tbl_products)) {
            $supplier_id = [];
            foreach ($tbl_products as $tbl_productss) {
                $supplier_id[] = $tbl_productss->name;
            }
            $name = implode(', ', $supplier_id);

            return $name;
        } else {
            return 'Not Added';
        }
    }
}

// Get Color Name In Product module
if (! function_exists('getColor')) {
    function getColor($id)
    {
        $color = DB::table('tbl_colors')->where('id', '=', $id)->first();
        if (! empty($color)) {
            $color_code = $color->color_code;

            return strtolower(str_replace(' ', '', $color_code));
        }
    }
}

// Get RTl value for all module
if (! function_exists('getValue')) {
    function getValue()
    {
        $id = Auth::user()->id;
        $rtls = DB::table('users')->where('id', $id)->first();
        if (! empty($rtls)) {
            $direction_name = $rtls->gst_no;

            return $direction_name;
        }
    }
}

// Get Vehicle Name value In Rto managament module
if (! function_exists('getVehicleName')) {
    function getVehicleName($id)
    {
        $vehicles = DB::table('tbl_vehicles')->where('id', '=', $id)->first();
        if (! empty($vehicles)) {
            // $vehicle_name = $vehicles->modelname;
            $vehicle_name = getVehicleBrand($vehicles->vehiclebrand_id).' '.$vehicles->modelname.' '.$vehicles->modelyear;

            return $vehicle_name;
        }
    }
}

if (! function_exists('getVehicleNameFromInvoice')) {
    function getVehicleNameFromInvoice($id)
    {

        $tbl_services = DB::table('tbl_services')->where('id', '=', $id)->first();

        if (! empty($tbl_services)) {
            $vehicles = DB::table('tbl_vehicles')->where('id', '=', $tbl_services->vehicle_id)->first();
            if (! empty($vehicles)) {
                $vehicle_name = getVehicleBrand($vehicles->vehiclebrand_id).' '.$vehicles->modelname.' '.$vehicles->modelyear;

                return $vehicle_name;
            }
        }
    }
}

if (! function_exists('Getvehiclecheckpoint')) {
    function Getvehiclecheckpoint($id)
    {

        $currentUser = DB::table('users')->where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = DB::table('branch_setting')->where('id', '=', 1)->first();

        if (isAdmin(Auth::User()->role_id)) {
            $vehicles = DB::table('tbl_checkout_categories')->where([['vehicle_id', '=', $id], ['soft_delete', '=', 0], ['branch_id', '=', $adminCurrentBranch->branch_id]])->get()->toArray();
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            $vehicles = DB::table('tbl_checkout_categories')->where([['vehicle_id', '=', $id], ['soft_delete', '=', 0]])->get()->toArray();
        } else {
            $vehicles = DB::table('tbl_checkout_categories')->where([['vehicle_id', '=', $id], ['soft_delete', '=', 0], ['branch_id', '=', $currentUser->branch_id]])->get()->toArray();
        }
        // $vehicles  = DB::table('tbl_checkout_categories')->where('vehicle_id','=',$id)->get()->toArray();

        if (! empty($vehicles)) {
            return $vehicles;
        } else {
            return [];
        }
    }
}

/*if (!function_exists('getCheckpointsSubCheckPoints')) {
    function getCheckpointsSubCheckPoints($hasValue)
    {
        //$vehicles  = DB::table('tbl_checkout_categories')->where('vehicle_id','=',$id)->get()->toArray();
        $checkPointsData  = DB::table('tbl_points')->where([['checkout_subpoints','=',$hasValue],['soft_delete','=',0]])->first();

        if(!empty($checkPointsData))
        {
            $data = $checkPointsData->checkout_point;
            if ($data != null) {
                return "1";
            }
            else {
                return "";
            }
        }
        else
        {
            return "";
        }
    }
}*/

// Get Vehicle type value In vehicle brand module
if (! function_exists('getVehicleBrand')) {
    function getVehicleBrand($id)
    {
        $vehiclebrand = DB::table('tbl_vehicle_brands')->where('id', '=', $id)->first();
        if (! empty($vehiclebrand)) {
            $vehicle_brand = $vehiclebrand->vehicle_brand;

            return $vehicle_brand;
        }
    }
}

// Get already paid total Amount from Invoice table
if (! function_exists('getPaidAmount')) {
    function getPaidAmount($joncard_number)
    {

        $invoice = DB::table('tbl_invoices')->where('job_card', '=', $joncard_number)->first();

        if (! empty($invoice)) {
            if ($invoice->paid_amount != '') {
                return $invoice->paid_amount;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}

// getVehicleDescription
if (! function_exists('getVehicleDescription')) {
    function getVehicleDescription($id)
    {
        $VehicalDescription = DB::table('tbl_vehicles')->where('id', '=', $id)->first();
        if (! empty($VehicalDescription)) {
            $VehicalDescriptions = $VehicalDescription->modelname;

            return $VehicalDescriptions;
        }
    }
}

// Customer Name in View of Sales module
if (! function_exists('getServiceId')) {
    function getServiceId()
    {
        $data = DB::table('tbl_services')->orderBy('id', 'DESC')->first();
        if (! empty($data)) {
            $id = $data->id;

            return $id;
        }
    }
}

// Get customer full name
if (! function_exists('getCustomerName')) {
    function getCustomerName($id)
    {
        $customer = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Customer']])->first();
        if (! empty($customer)) {
            $customer_name = $customer->name;
            $customer_lname = $customer->lastname;

            return $customer_name.' '.$customer_lname;
        }
    }
    function getCustomerMobile($id)
    {
        $customer = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Customer']])->first();
        if (! empty($customer)) {
            $customer_mo = $customer->mobile_no;

            return $customer_mo;
        }
    }
}

// get Employee full name
if (! function_exists('getAssignedName')) {
    function getAssignedName($id)
    {
        $assigned = DB::table('users')->where([['id', '=', $id], ['role', '=', 'employee']])->first();
        if (! empty($assigned)) {
            $assi_name = $assigned->name;
            $assi_lname = $assigned->lastname;

            return $assi_name.' '.$assi_lname;
        }
    }
}

// Customer Address in View of Sales module
if (! function_exists('getCustomerAddress')) {
    function getCustomerAddress($id)
    {

        $customer = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Customer']])->first();

        if (! empty($customer)) {
            $customer_address = $customer->address;

            return $customer_address;
        }
    }
}

// Customer city in View of Sales module
if (! function_exists('getCustomerCity')) {
    function getCustomerCity($id)
    {

        $customer = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Customer']])->first();

        if (! empty($customer)) {
            $customer_city = getCityName($customer->city_id);

            return $customer_city;
        }
    }
}

// Customer state in View of Sales module
if (! function_exists('getCustomerState')) {
    function getCustomerState($id)
    {

        $customer = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Customer']])->first();

        if (! empty($customer)) {
            $customer_state = getStateName($customer->state_id);

            return $customer_state;
        }
    }
}
// Customer state in View of Sales module
if (! function_exists('getCustomerCountry')) {
    function getCustomerCountry($id)
    {

        $customer = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Customer']])->first();

        if (! empty($customer)) {
            $customer_country = getCountryName($customer->country_id);

            return $customer_country;
        }
    }
}

// Customer Mobile in View of Sales module
if (! function_exists('getCustomerMobile')) {
    function getCustomerMobile($id)
    {

        $customer = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Customer']])->first();

        if (! empty($customer)) {
            $customer_mobile = $customer->mobile_no;

            return $customer_mobile;
        }
    }
}

// Customer Mobile in View of Sales module
if (! function_exists('getCustomerTaxid')) {
    function getCustomerTaxid($id)
    {

        $customer = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Customer']])->first();

        if (! empty($customer)) {
            $customer_taxid = $customer->tax_id;

            return $customer_taxid;
        }
    }
}

// Customer Email in View of Sales module
if (! function_exists('getCustomerEmail')) {
    function getCustomerEmail($id)
    {

        $customer = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Customer']])->first();

        if (! empty($customer)) {
            $customer_email = $customer->email;

            return $customer_email;
        }
    }
}
// Get VehicleType Name In Vehicle module
if (! function_exists('getVehicleType')) {
    function getVehicleType($id)
    {

        $vehical_type = DB::table('tbl_vehicle_types')->where('id', '=', $id)->first();

        if (! empty($vehical_type)) {
            $vehical_type_name = $vehical_type->vehicle_type;

            return $vehical_type_name;
        }
    }
}

// Vehicle Type in View of Sales module
if (! function_exists('getVehicleType')) {
    function getVehicleType($id)
    {

        $vehi_type = DB::table('tbl_vehicle_types')->where('id', '=', $id)->first();

        if (! empty($vehi_type)) {
            $vehi_type_name = $vehi_type->vehicle_type;

            return $vehi_type_name;
        }
    }
}

// Vehicle Color in View of Sales module
if (! function_exists('getVehicleColor')) {
    function getVehicleColor($id)
    {

        $color = DB::table('tbl_colors')->where('id', '=', $id)->first();

        if (! empty($color)) {
            $color_name = $color->color;

            return $color_name;
        }
    }
}

// Total Amount in View of Sales module
if (! function_exists('getTotalAmonut')) {
    function getTotalAmonut($tax, $name, $amount)
    {

        $tax = DB::table('tbl_sales_taxes')->where([['tax_name', '=', $name], ['tax', '=', $tax]])->first();
        $tax_rate = $tax->tax;
        $total_price = ($tax_rate * $amount) / 100;

        return $total_price;
    }
}

// Total Amount of rto  in View of Sales module
if (! function_exists('getTotalRto')) {
    function getTotalRto($id)
    {

        $rto = DB::table('tbl_rto_taxes')->where('vehicle_id', '=', $id)->first();
        $r_tax = $rto->registration_tax;
        $no_plate = $rto->number_plate_charge;
        $road_tax = $rto->muncipal_road_tax;

        $total_rto_charges = $r_tax + $no_plate + $road_tax;

        return $total_rto_charges;
    }
}

// Get Observation Type Name in Observation Point List Module
if (! function_exists('getObservationTypeName')) {
    function getObservationTypeName($id)
    {

        $o_type = DB::table('tbl_observation_types')->where('id', '=', $id)->first();

        if (! empty($o_type)) {
            $type_name = $o_type->type;

            return $type_name;
        }
    }
}

// Fuel type  in View of vehicle  module
if (! function_exists('getFuelType')) {
    function getFuelType($id)
    {

        $fueal_type = DB::table('tbl_fuel_types')->where('id', '=', $id)->first();

        if (! empty($fueal_type)) {
            $fuel_type_name = $fueal_type->fuel_type;

            return $fuel_type_name;
        }
    }
}

// Vehicle Brand  in View of vehicle module
if (! function_exists('getVehicleBrands')) {
    function getVehicleBrands($id)
    {

        $vehi_brand = DB::table('tbl_vehicle_brands')->where('id', '=', $id)->first();

        if (! empty($vehi_brand)) {
            $vehicalbrand = $vehi_brand->vehicle_brand;

            return $vehicalbrand;
        }
    }
}

// Get Color Name in View of vehicle module
if (! function_exists('getColorName')) {
    function getColorName($id)
    {

        $color = DB::table('tbl_colors')->where('id', '=', $id)->first();
        if (! empty($color)) {
            $color_name = $color->color;

            return $color_name;
        }
    }
}

// getcolourcode

if (! function_exists('getColourCode')) {
    function getColourCode($id)
    {
        $colourname = getColorName($id);
        switch ($colourname) {
            case 'red':
                return '#ff0000';
            case 'blue':
                return '#0000FF';
            case 'green':
                return '#008000';
            case 'Black ':
                return '#000000';
            case 'Brown ':
                return '#A52A2A';
            case 'Grey ':
                return '##808080';
            case 'Pink ':
                return '##FFC0CB';
            case 'Purple ':
                return '##800080';
            case 'Yellow ':
                return '###FFFF00';

            default:
                return '#696969';
        }
    }
}

// Get Checked Value In Jobcard Detail
if (! function_exists('getCheckvalue')) {
    function getCheckvalue($services_id, $observation_points_id)
    {

        $getdata = DB::table('tbl_service_observation_points')->where([['services_id', '=', $services_id], ['observation_points_id', '=', $observation_points_id]])->count();
        if ($getdata > 0) {
            return 'checked';
        } else {
            return '';
        }
    }
}

// Get Checked Value In Jobcard Detail
if (! function_exists('getCheckReview')) {
    function getCheckReview($services_id, $observation_points_id)
    {

        $getdata = DB::table('tbl_service_observation_points')->where([['services_id', '=', $services_id], ['observation_points_id', '=', $observation_points_id]])->first();

        if (! empty($getdata)) {
            $review = $getdata->review;

            return $review;
        }
    }
}

// get vehicle first image
if (! function_exists('getVehicleImage')) {
    function getVehicleImage($id)
    {

        $vehicleimage = DB::table('tbl_vehicle_images')->where('vehicle_id', '=', $id)->first();
        if (! empty($vehicleimage)) {
            $vehiclefisrtimage = $vehicleimage->image;

            return $vehiclefisrtimage;
        } else {
            $vehiclefisrtimage = 'avtar.png';

            return $vehiclefisrtimage;
        }
    }
}

// Get AssigineTo  Value In Service(module) List  Detail
if (! function_exists('getAssignTo')) {
    function getAssignTo($id)
    {

        $AssignTo = DB::table('users')->where('id', '=', $id)->first();

        if (! empty($AssignTo)) {
            $AssignTo_name = $AssignTo->name.' '.$AssignTo->lastname;

            return $AssignTo_name;
        }
    }
}

// Set the logo of get pass invoice
if (! function_exists('getLogoInvoice')) {
    function getLogoInvoice()
    {

        $logo = DB::table('tbl_settings')->first();
        $logo_img = $logo->logo_image;

        return $logo_img;
    }
}

// Set the Coupan no in Service List
if (! function_exists('getAllCoupon')) {
    function getAllCoupon($cid, $vid)
    {

        $all_coupan = DB::table('tbl_services')->where([['customer_id', '=', $cid], ['vehicle_id', '=', $vid], ['job_no', 'like', 'C%']])->get()->toArray();

        return $all_coupan;
    }
}

// Set the Used Coupon no in Service List
if (! function_exists('getUsedCoupon')) {
    function getUsedCoupon($cid, $vid, $cupanno)
    {

        $used_coupon = DB::table('tbl_jobcard_details')->where([['customer_id', '=', $cid], ['vehicle_id', '=', $vid], ['coupan_no', '=', $cupanno]])->first();

        if (! empty($used_coupon)) {

            $done_status = $used_coupon->done_status;

            return $done_status;
        }
    }
}

// Get A Access Rights Setting  In User Side PAge for all Module
if (! function_exists('getAccessStatusUser')) {
    function getAccessStatusUser($menu_name, $id)
    {

        $user = DB::table('users')->where('id', '=', $id)->first();

        $userrole = $user->role;

        if ($userrole == 'admin') {
            return 'yes';
        } else {
            if ($userrole == 'Customer') {
                $acess = DB::table('tbl_accessrights')->where('menu_name', '=', $menu_name)->first();

                $customers = $acess->customers;

                if ($customers == 1) {
                    return 'yes';
                } elseif ($customers == 0) {
                    return 'no';
                }
            } elseif ($userrole == 'employee') {
                $acess = DB::table('tbl_accessrights')->where('menu_name', '=', $menu_name)->first();
                $employee = $acess->employee;
                if ($employee == 1) {
                    return 'yes';
                } elseif ($employee == 0) {
                    return 'no';
                }
            } elseif ($userrole == 'supportstaff') {
                $acess = DB::table('tbl_accessrights')->where('menu_name', '=', $menu_name)->first();
                $support_staff = $acess->support_staff;
                if ($support_staff == 1) {
                    return 'yes';
                } elseif ($support_staff == 0) {
                    return 'no';
                }
            } elseif ($userrole == 'accountant') {
                $acess = DB::table('tbl_accessrights')->where('menu_name', '=', $menu_name)->first();
                $accountant = $acess->accountant;
                if ($accountant == 1) {
                    return 'yes';
                } elseif ($accountant == 0) {
                    return 'no';
                }
            } elseif ($userrole == 'branch_admin') {
                $acess = DB::table('tbl_accessrights')->where('menu_name', '=', $menu_name)->first();
                $accountant = $acess->accountant;
                if ($accountant == 1) {
                    return 'yes';
                } elseif ($accountant == 0) {
                    return 'no';
                }
            }
        }
    }
}

// Get active Admin list in data list
if (! function_exists('getActiveAdmin')) {
    function getActiveAdmin($id)
    {
        $data = DB::table('users')->where('id', '=', $id)->first();

        if (! empty($data)) {
            $userrole = $data->role;
            if ($userrole == 'admin') {
                return 'yes';
            } else {
                return 'no';
            }
        }
    }
}

// Get active Customer list in data list
if (! function_exists('getActiveCustomer')) {
    function getActiveCustomer($id)
    {

        $data = DB::table('users')->where('id', '=', $id)->first();

        if (! empty($data)) {
            $userrole = $data->role;
            if ($userrole == 'admin' || $userrole == 'supportstaff' || $userrole == 'accountant') {

                return 'yes';
            } else {
                return 'no';
            }
        }
    }
}

// Get active Employee list in data list
if (! function_exists('getActiveEmployee')) {
    function getActiveEmployee($id)
    {

        $data = DB::table('users')->where('id', '=', $id)->first();

        if (! empty($data)) {
            $userrole = $data->role;
            if ($userrole == 'employee') {

                return 'yes';
            } else {

                return 'no';
            }
        }
    }
}

// Get active Admin list in data list
if (! function_exists('getCustomersactive')) {
    function getCustomersactive($id)
    {

        $data = DB::table('users')->where('id', '=', $id)->first();

        if (! empty($data)) {
            $userrole = $data->role;
            if ($userrole == 'customer') {

                return 'yes';
            } else {
                return 'no';
            }
        }
    }
}

// Get active Admin list in data list
if (! function_exists('getBranchadminsactive')) {
    function getBranchadminsactive($id)
    {
        $data = DB::table('users')->where('id', '=', $id)->first();

        if (! empty($data)) {
            $userrole = $data->role;
            if ($userrole == 'branch_admin') {
                return 'yes';
            } else {
                return 'no';
            }
        }
    }
}

// Get active jobcard list in Customer data list
if (! function_exists('getCustomerJobcard')) {
    function getCustomerJobcard($id)
    {

        $service = DB::table('tbl_services')->where([['customer_id', '=', $id], ['job_no', 'like', 'J%']])->get()->toArray();

        if (! empty($service)) {
            return 'yes';
        } else {

            return 'no';
        }
    }
}

// Get Login Customer in Sales data list

if (! function_exists('getCustomerSales')) {
    function getCustomerSales($id)
    {

        $sales = DB::table('tbl_sales')->where('customer_id', '=', $id)->get()->toArray();

        if (! empty($sales)) {
            return 'yes';
        } else {

            return 'no';
        }
    }
}

// Get active Service list in Customer data list
if (! function_exists('getCustomerService')) {
    function getCustomerService($id)
    {

        $service = DB::table('tbl_services')->where([['customer_id', '=', $id], ['job_no', 'like', 'J%']])->get()->toArray();

        if (! empty($service)) {
            return 'yes';
        } else {

            return 'no';
        }
    }
}

// Get active Customer list in data list
if (! function_exists('getCustomerList')) {
    function getCustomerList($id)
    {

        $data = DB::table('users')->where('id', '=', $id)->first();

        if (! empty($data)) {
            $userrole = $data->role;
            if ($userrole == 'Customer') {
                $service = DB::table('tbl_services')->where([['customer_id', '=', $id], ['job_no', 'like', 'J%'], ['done_status', '=', 1]])->get()->toArray();
                if (! empty($service)) {
                    return 'yes';
                } else {

                    return 'no';
                }
            } else {
                return 'no';
            }
        }
    }
}

// Count Number of service in dashboard
if (! function_exists('getNumberOfService')) {
    function getNumberOfService($id)
    {
        $y = date('Y');
        $m = date('m');

        $d = $id;

        $datess = "$y/$m/$d";

        $data = DB::table('tbl_services')->where('done_status', '!=', 2)->where('soft_delete', '=', 0)->whereDate('service_date', '=', $datess)->count();

        return $data;
    }
}

// Current  stock
if (! function_exists('getCurrentStock')) {
    function getCurrentStock($p_id)
    {
        $stockproduct = DB::table('tbl_service_pros')->where('product_id', '=', $p_id)->get()->toArray();
        $selltotal = 0;
        foreach ($stockproduct as $stockproducts) {
            $qty = $stockproducts->quantity;
            $selltotal += $qty;
        }

        $allstock = DB::table('tbl_purchase_history_records')->where('product_id', '=', $p_id)->get()->toArray();
        $alltotal = 0;
        foreach ($allstock as $allstocks) {
            $qtys = $allstocks->qty;
            $alltotal += $qtys;
        }

        $currentstock = $alltotal - $selltotal;

        return $currentstock;
    }
}

// Get logo system in app blade

if (! function_exists('getLogoSystem')) {
    function getLogoSystem()
    {

        $logo = DB::table('tbl_settings')->first();
        $logo_image = $logo->logo_image;

        return $logo_image;
    }
}

// Get  system name in app blade

if (! function_exists('getNameSystem')) {
    function getNameSystem()
    {

        $system_name = DB::table('tbl_settings')->first();
        $system_name = $system_name->system_name;

        return $system_name;
    }
}
// Get date format in all project
if (! function_exists('getDateFormat')) {
    function getDateFormat()
    {

        $dateformat = DB::table('tbl_settings')->first();

        if (! empty($dateformat)) {
            $dateformate = $dateformat->date_format;

            return $dateformate;
        }
    }
}

// Get date format in datepicker
if (! function_exists('getDatepicker')) {
    function getDatepicker()
    {
        $dateformat = DB::table('tbl_settings')->first();
        $dateformate = $dateformat->date_format;
        if (! empty($dateformate)) {
            if ($dateformate == 'm-d-Y') {
                $dateformats = 'mm-dd-yyyy';

                return $dateformats;
            } elseif ($dateformate == 'Y-m-d') {
                $dateformats = 'yyyy-mm-dd';

                return $dateformats;
            } elseif ($dateformate == 'd-m-Y') {
                $dateformats = 'dd-mm-yyyy';

                return $dateformats;
            } elseif ($dateformate == 'M-d-Y') {
                $dateformats = 'MM-dd-yyyy';

                return $dateformats;
            }
        }
    }
}

// Get date format in Datetimepicker
if (! function_exists('getDatetimepicker')) {
    function getDatetimepicker()
    {

        $dateformate = getDateFormat();

        if (! empty($dateformate)) {
            if ($dateformate == 'm-d-Y') {
                $dateformats = 'mm-dd-yyyy hh:ii:ss';

                return $dateformats;
            } elseif ($dateformate == 'Y-m-d') {
                $dateformats = 'yyyy-mm-dd  hh:ii:ss';

                return $dateformats;
            } elseif ($dateformate == 'd-m-Y') {
                $dateformats = 'dd-mm-yyyy hh:ii:ss';

                return $dateformats;
            } elseif ($dateformate == 'M-d-Y') {
                $dateformats = 'M-dd-yyyy hh:ii:ss';

                return $dateformats;
            }
        }
    }
}
// Get Day Name in View Of general_setting
if (! function_exists('getDayName')) {
    function getDayName($id)
    {

        switch ($id) {
            case '1':
                return 'Monday';
            case '2':
                return 'Tuesday';
            case '3':
                return 'Wednesday';
            case '4':
                return 'Thursday';
            case '5':
                return 'Friday';
            case '6':
                return 'Saturday';
            case '7':
                return 'Sunday';
            default:
                echo 'Sunday';
        }
    }
}

// Get from open hours time in View Of general_setting
if (! function_exists('getOpenHours')) {
    function getOpenHours($id)
    {
        $tbl_hours = DB::table('tbl_business_hours')->where('from', '=', $id)->first();
        $pm = $tbl_hours->from;
        if ($pm >= 12) {
            if ($pm == 12) {
                $pmfinal = $pm;
                $final = $pmfinal.''.':00 '.trans('message.PM');

                return $final;
            } else {
                $pmfinal = $pm - 12;
                $final = $pmfinal.''.':00 '.trans('message.PM');

                return $final;
            }
        } else {
            if ($pm == 0) {
                $pmfinal = $pm + 12;
                $final = $pmfinal.''.':00 '.trans('message.AM');

                return $final;
            } else {
                $pmfinal = $pm;
                $final = $pmfinal.''.':00 '.trans('message.AM');

                return $final;
            }
        }
    }
}

// Get close hours time in View Of general_setting
if (! function_exists('getCloseHours')) {
    function getCloseHours($id)
    {
        $tbl_hours = DB::table('tbl_business_hours')->where('to', '=', $id)->first();
        $am = $tbl_hours->to;
        if ($am >= 12) {
            if ($am == 12) {
                $pmfinal = $am;
                $final = $pmfinal.''.':00 '.trans('message.PM');

                return $final;
            } else {
                $pmfinal = $am - 12;
                $final = $pmfinal.''.':00 '.trans('message.PM');

                return $final;
            }
        } else {
            if ($am == 0) {
                $pmfinal = $am + 12;
                $final = $pmfinal.''.':00 '.trans('message.AM');

                return $final;
            } else {
                $pmfinal = $am;
                $final = $pmfinal.''.':00 '.trans('message.AM');

                return $final;
            }
        }
    }
}

// Get data  value in custom field
if (! function_exists('getCustomData')) {
    function getCustomData($tbl_custom, $userid)
    {
        $userdata = DB::table('users')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field vehicle table
if (! function_exists('getCustomDataVehicle')) {
    function getCustomDataVehicle($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_vehicles')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from Service table
if (! function_exists('getCustomDataService')) {
    function getCustomDataService($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_services')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from Sales table
if (! function_exists('getCustomDataSales')) {
    function getCustomDataSales($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_sales')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from Salepart table
if (! function_exists('getCustomDataSalepart')) {
    function getCustomDataSalepart($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_sale_parts')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from Invoice table
if (! function_exists('getCustomDataInvoice')) {
    function getCustomDataInvoice($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_invoices')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from Product table
if (! function_exists('getCustomDataProduct')) {
    function getCustomDataProduct($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_products')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from Purchase table
if (! function_exists('getCustomDataPurchase')) {
    function getCustomDataPurchase($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_purchases')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from VehicleType table
if (! function_exists('getCustomDataVehicleType')) {
    function getCustomDataVehicleType($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_vehicle_types')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from VehicleBrand table
if (! function_exists('getCustomDataVehicleBrand')) {
    function getCustomDataVehicleBrand($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_vehicle_brands')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from Color table
if (! function_exists('getCustomDataColors')) {
    function getCustomDataColors($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_colors')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from Income table
if (! function_exists('getCustomDataIncome')) {
    function getCustomDataIncome($tbl_custom, $userid)
    {

        $userdata = DB::table('tbl_incomes')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from Expenses table
if (! function_exists('getCustomDataExpenses')) {
    function getCustomDataExpenses($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_expenses')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get data  value in custom field from Rto table
if (! function_exists('getCustomDataRto')) {
    function getCustomDataRto($tbl_custom, $userid)
    {
        $userdata = DB::table('tbl_rto_taxes')->where('id', '=', $userid)->first();

        $jsonn = $userdata->custom_field;

        $jsonns = json_decode($jsonn);
        if (! empty($jsonns)) {
            foreach ($jsonns as $key => $value) {
                $ids = $value->id;
                $value1 = $value->value;

                if ($tbl_custom == $ids) {
                    return $value1;
                }
            }
        }
    }
}

// Get Currency symbols in all module
if (! function_exists('getCurrencySymbols')) {
    function getCurrencySymbols()
    {

        $setting = DB::table('tbl_settings')->first();
        $id = $setting->currancy;

        $currancy = DB::table('currencies')->where('id', '=', $id)->first();

        if (! empty($currancy)) {
            $symbol = currencyList($currancy->code);

            return html_entity_decode($symbol);
        }
    }
}

// Get Currency symbols in all module
if (! function_exists('getCurrencyCode')) {
    function getCurrencyCode()
    {

        $setting = DB::table('tbl_settings')->first();
        $id = $setting->currancy;

        $currancy = DB::table('currencies')->where('id', '=', $id)->first();

        if (! empty($currancy)) {
            // $symbol = currencyList($currancy->code);
            //  return html_entity_decode($symbol);
            return $currancy->code;
        }
    }
}

// Get current stock in stock  module
if (! function_exists('getStockCurrent')) {
    function getStockCurrent($id)
    {

        $product_stock = DB::table('tbl_stock_records')->where('product_id', '=', $id)->first();
        $full_stock = $product_stock->no_of_stoke;

        $product_service_stocks = DB::table('tbl_service_pros')->where('product_id', '=', $id)->get()->toArray();

        $product_service_total = 0;
        foreach ($product_service_stocks as $product_service_stock) {
            $service_stock = $product_service_stock->quantity;
            $product_service_total += $service_stock;
        }

        $product_sale_stocks = DB::table('tbl_sale_parts')->where('product_id', '=', $id)->get()->toArray();

        $product_sale_total = 0;
        foreach ($product_sale_stocks as $product_sale_stock) {
            $sale_stock = $product_sale_stock->quantity;
            $product_sale_total += $sale_stock;
        }

        $total_full = $product_service_total + $product_sale_total;
        if (! empty($product_stock)) {
            $finalstock = $full_stock - $total_full;

            return $finalstock;
        }
    }
}

// Get  languagechange
if (! function_exists('getLanguageChange')) {
    function getLanguageChange()
    {

        $userid = Auth::User()->id;
        $data = DB::table('users')->where('id', '=', $userid)->first();
        $language = $data->language;

        if (! empty($language)) {
            if ($language == 'en') {
                $language = 'English';

                return $language;
            } elseif ($language == 'de') {
                $language = 'Spanish';

                return $language;
            } elseif ($language == 'gr') {
                $language = 'Greek';

                return $language;
            } elseif ($language == 'ar') {
                $language = 'Arabic';

                return $language;
            } elseif ($language == 'ger') {
                $language = 'German';

                return $language;
            } elseif ($language == 'pt') {
                $language = 'Portuguese';

                return $language;
            } elseif ($language == 'fr') {
                $language = 'French';

                return $language;
            } elseif ($language == 'it') {
                $language = 'Italian';

                return $language;
            } elseif ($language == 'sv') {
                $language = 'Swedish';

                return $language;
            } elseif ($language == 'dt') {
                $language = 'Dutch';

                return $language;
            } elseif ($language == 'hi') {
                $language = 'Hindi';

                return $language;
            } elseif ($language == 'zhcn') {
                $language = 'Chinese';

                return $language;
            } elseif ($language == 'id') {
                $language = 'Indonesian';

                return $language;
            } elseif ($language == 'ca') {
                $language = 'Catalan';

                return $language;
            } elseif ($language == 'cs') {
                $language = 'Czech';

                return $language;
            } elseif ($language == 'da') {
                $language = 'Danish';

                return $language;
            } elseif ($language == 'de') {
                $language = 'Spanish';

                return $language;
            } elseif ($language == 'dt') {
                $language = 'Dutch';

                return $language;
            } elseif ($language == 'et') {
                $language = 'Estonian';

                return $language;
            } elseif ($language == 'fa') {
                $language = 'Persian';

                return $language;
            } elseif ($language == 'fi') {
                $language = 'Finnish';

                return $language;
            } elseif ($language == 'he') {
                $language = 'Hebrew';

                return $language;
            } elseif ($language == 'ja') {
                $language = 'Japanese';

                return $language;
            } elseif ($language == 'pl') {
                $language = 'Polish';

                return $language;
            } elseif ($language == 'ro') {
                $language = 'Romanian';

                return $language;
            } elseif ($language == 'ru') {
                $language = 'Russian';

                return $language;
            } elseif ($language == 'th') {
                $language = 'Thai';

                return $language;
            } elseif ($language == 'tr') {
                $language = 'Turkish';

                return $language;
            } elseif ($language == 'vi') {
                $language = 'Vietnamese';

                return $language;
            } elseif ($language == 'hr') {
                $language = 'Croatian';

                return $language;
            } elseif ($language == 'hu') {
                $language = 'Hungarian';

                return $language;
            } elseif ($language == 'lt') {
                $language = 'Lithuanian';

                return $language;
            } elseif ($language == 'no') {
                $language = 'Norwegian';

                return $language;
            } elseif ($language == 'gu') {
                $language = 'Gujarati';

                return $language;
            } elseif ($language == 'ta') {
                $language = 'Tamil';

                return $language;
            } elseif ($language == 'ur') {
                $language = 'Urdu';

                return $language;
            } elseif ($language == 'sl') {
                $langauge = 'Slovenian';

                return $langauge;
            } elseif ($language == 'be') {
                $language = 'Belarusian';

                return $language;
            } elseif ($language == 'bg') {
                $langauge = 'Bulgarian';

                return $langauge;
            } elseif ($language == 'sq') {
                $language = 'Albanian';

                return $language;
            } elseif ($language == 'sr') {
                $language == 'Serbian';

                return $language;
            } elseif ($language == 'sw') {
                $language = 'Swahili';

                return $language;
            } elseif ($language == 'ga') {
                $language = 'Irish';

                return $language;
            } elseif ($language == 'is') {
                $language = 'Icelandic';

                return $language;
            } elseif ($language == 'gl') {
                $language = 'Galician';

                return $language;
            } elseif ($language == 'sk') {
                $language = 'Slovak';

                return $language;
            } elseif ($language == 'lv') {
                $language = 'Latvian';

                return $language;
            } elseif ($language == 'ko') {
                $language = 'Korean';

                return $language;
            } elseif ($language == 'mk') {
                $language = 'Macedonian';

                return $language;
            } elseif ($language == 'ms') {
                $language = 'Malay';

                return $language;
            } elseif ($language == 'af') {
                $language = 'Afrikaans';

                return $language;
            } elseif ($language == 'fil') {
                $language = 'Filipino';

                return $language;
            }
        }
    }
}

// Get Payment Method  in all module

if (! function_exists('GetPaymentMethod')) {
    function GetPaymentMethod($id)
    {

        $tbl_payments = DB::table('tbl_payments')->where('id', '=', $id)->first();

        if (! empty($tbl_payments)) {
            $payment = $tbl_payments->payment;

            return $payment;
        } else {
            $payment = $id;

            return $payment;
        }
    }
}

// Get Unit  name in Stock module

if (! function_exists('getUnitMeasurement')) {
    function getUnitMeasurement($id)
    {

        $tbl_products = DB::table('tbl_products')->where('id', '=', $id)->get()->toArray();

        if (! empty($tbl_products)) {
            $unit = [];
            foreach ($tbl_products as $tbl_productss) {
                $unit[] = $tbl_productss->unit;
            }

            $tbl_product_units = DB::table('tbl_product_units')->where('id', '=', $unit)->first();
            if (! empty($tbl_product_units)) {
                $name = $tbl_product_units->name;

                return $name;
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
}

// Get  purchase date
if (! function_exists('getPurchaseDate')) {
    function getPurchaseDate($id)
    {

        $tbl_purchases = DB::table('tbl_purchases')->where('id', '=', $id)->first();

        if (! empty($tbl_purchases)) {
            $date = $tbl_purchases->date;

            return $date;
        }
    }
}

// get user Language Code
if (! function_exists('getLangCode')) {
    function getLangCode()
    {
        $userlang = Auth::User()->language;

        return $userlang;
    }
}
if (! function_exists('getUserRole')) {
    function getUserRole()
    {
        $userRole = Auth::User()->role;

        return $userRole;
    }
}

// Get Latest  purchase date
if (! function_exists('getLatestPurchaseDate')) {
    function getLatestPurchaseDate($productId)
    {
        $productData = DB::table('tbl_products')->where('id', '=', $productId)->first();

        if (! empty($productData)) {
            $product_Id = $productData->id;

            $purchaseHistoryData = DB::table('tbl_purchase_history_records')->where('product_id', '=', $product_Id)->latest()->first();

            $tbl_purchases = DB::table('tbl_purchases')->where('id', '=', $purchaseHistoryData->purchase_id)->first();

            $purchaseNo = $tbl_purchases->date;

            return $purchaseNo;
        }

    }
}

// Get PurchaseSupplier
if (! function_exists('getPurchaseSupplier')) {
    function getPurchaseSupplier($id)
    {

        $tbl_purchases = DB::table('tbl_purchases')->where('id', '=', $id)->first();

        if (! empty($tbl_purchases)) {
            $supplier_id = $tbl_purchases->supplier_id;

            return $supplier_id;
        }
    }
}

// Get  purchase date
if (! function_exists('getPurchaseCode')) {
    function getPurchaseCode($id)
    {

        $tbl_purchases = DB::table('tbl_purchases')->where('id', '=', $id)->first();

        if (! empty($tbl_purchases)) {
            $purchase_no = $tbl_purchases->purchase_no;

            return $purchase_no;
        }
    }
}

// Get  purchase code latest
if (! function_exists('getLatestPurchaseCode')) {
    function getLatestPurchaseCode($productId)
    {
        $productData = DB::table('tbl_products')->where('id', '=', $productId)->first();

        if (! empty($productData)) {
            $product_Id = $productData->id;

            $purchaseHistoryData = DB::table('tbl_purchase_history_records')->where('product_id', '=', $product_Id)->latest()->first();

            $tbl_purchases = DB::table('tbl_purchases')->where('id', '=', $purchaseHistoryData->purchase_id)->first();

            $purchaseNo = $tbl_purchases->purchase_no;

            return $purchaseNo;
        }
    }
}
// Get  purchase date
if (! function_exists('getVehicleNumberPlate')) {
    function getVehicleNumberPlate($id)
    {

        $tbl_vehicles = DB::table('tbl_vehicles')->where('id', '=', $id)->first();

        if (! empty($tbl_vehicles)) {
            $numberPlate = $tbl_vehicles->number_plate;

            return $numberPlate;
        }
    }
}

// Get  purchase date
if (! function_exists('getVehicleNumberPlateFromService')) {
    function getVehicleNumberPlateFromService($id)
    {

        $tbl_services = DB::table('tbl_services')->where('id', '=', $id)->first();

        if (! empty($tbl_services)) {
            $vehicles = DB::table('tbl_vehicles')->where('id', '=', $tbl_services->vehicle_id)->first();
            if (! empty($vehicles)) {
                $numberPlate = $vehicles->number_plate;

                return $numberPlate;
            }
        }
    }
}

// Get purchase vehicle details from sale id
if (! function_exists('getVehicleNumberPlateFromSale')) {
    function getVehicleNumberPlateFromSale($id)
    {

        $tbl_sales = DB::table('tbl_sales')->where('id', '=', $id)->first();

        if (! empty($tbl_sales)) {
            $vehicles = DB::table('tbl_vehicles')->where('id', '=', $tbl_sales->vehicle_id)->first();
            if (! empty($vehicles)) {
                $numberPlate = $vehicles->number_plate;

                return $numberPlate;
            }
        }
    }

    // Get Currency code
    if (! function_exists('getCurrencyCode')) {
        function getCurrencyCode()
        {
            $setting = DB::table('tbl_settings')->first();
            $id = $setting->currancy;

            $currancy = DB::table('currencies')->where('id', '=', $id)->first();

            if (! empty($currancy)) {
                $code = $currancy->code;

                return $code;
            }
        }
    }

    // Get Jobcard number with start Q
    if (! function_exists('getQuotationNumber')) {
        function getQuotationNumber($jobcard_no)
        {
            if (! empty($jobcard_no)) {
                $quotationNumber = 'Q'.substr($jobcard_no, 1);

                return $quotationNumber;
            }
        }
    }

    // Get price of Quotation
    if (! function_exists('getTotalPriceOfQuotation')) {
        function getTotalPriceOfQuotation($id)
        {
            $services = DB::table('tbl_services')->where('id', '=', $id)->first();
            $sum1 = 0;
            if (! empty($services)) {
                if ($services->charge != '') {
                    $sum1 = $services->charge;
                }
            }

            $services_pros = DB::table('tbl_service_pros')->where('service_id', '=', $id)->get()->toArray();
            $sum2 = 0;
            if (! empty($services_pros)) {
                foreach ($services_pros as $value) {
                    if ($value->total_price != '' && $value->chargeable != 0) {
                        $sum2 += $value->total_price;
                    }
                }
            }

            $sum3 = 0;
            if ($services->mot_status == 1) {
                $sum3 = $services->mot_charge;
            }

            // Calculate total amount without taxes
            $totalAmount = $sum1 + $sum2 + $sum3;
            $service_pro2 = DB::table('tbl_service_pros')->where('service_id', '=', $id)->where('type', '=', 1)->get()->toArray();
            foreach ($service_pro2 as $pro) {
                // Assuming totalPprice is a field in your database table
                $totalAmount += $pro->total_price;
            }

            // Check for washbay and add its price to the total
            $washbay = DB::table('washbays')->where('service_id', '=', $id)->first();
            $totalamountservice = $totalAmount;
            if (! empty($washbay->price)) {
                $total = $washbay->price;
                $totalamountservice = $totalAmount + $total;
            }

            // Calculate taxes
            $total_tax_percent = 0;
            if (! empty($services->tax_id)) {
                $tax_ids = explode(', ', $services->tax_id);

                for ($i = 0; $i < count($tax_ids); $i++) {
                    if (! empty($tax_ids[$i])) {
                        $current_id = $tax_ids[$i];
                        $taxData = DB::table('tbl_account_tax_rates')->where('id', '=', $current_id)->first();
                        $tax_percent = $taxData->tax;

                        $total_tax_percent += $tax_percent;
                    }
                }
            }

            if ($total_tax_percent != 0) {
                $totalTaxAmount = ($totalamountservice * $total_tax_percent) / 100;
                $totalamountservice += $totalTaxAmount;
            }

            return $totalamountservice;
        }
    }

    // Get Observation price fill or not inside table
    if (! function_exists('getObservationPriceFillOrNot')) {
        function getObservationPriceFillOrNot($jobNo)
        {
            $tbl_services = DB::table('tbl_services')->where('job_no', '=', $jobNo)->first();
            $getServiceId = $tbl_services->id;

            $tbl_service_pro_datas = DB::table('tbl_service_pros')->where('service_id', '=', $getServiceId)->get()->toArray();

            $response = false;

            if (! empty($tbl_service_pro_datas)) {
                foreach ($tbl_service_pro_datas as $tbl_service_pro_data) {
                    if ($tbl_service_pro_data->total_price != null) {
                        return $response = true;
                    }
                }
            }

            return $response;
        }
    }

    // Get customer company name
    if (! function_exists('getCustomerCompanyName')) {
        function getCustomerCompanyName($id)
        {
            $customer = DB::table('users')->where([['id', '=', $id], ['role', '=', 'Customer']])->first();
            if (! empty($customer)) {
                return $customer->company_name;
            }
        }
    }

    // Get vehicle number plate
    if (! function_exists('getVehicleNumberPlate')) {
        function getVehicleNumberPlate($id)
        {
            $vehicle = DB::table('tbl_vehicles')->where('id', '=', $id)->first();
            if (! empty($vehicle)) {
                return $vehicle->number_plate;
            }
        }
    }

    // Get tax id from tax table
    if (! function_exists('getTaxPercentFromTaxTable')) {
        function getTaxPercentFromTaxTable($id)
        {
            $tax = DB::table('tbl_account_tax_rates')->where('id', '=', $id)->first();

            if (! empty($tax)) {
                return $tax->tax;
            }
        }
    }

    // Get tax name from tax table
    if (! function_exists('getTaxNameFromTaxTable')) {
        function getTaxNameFromTaxTable($id)
        {
            $tax = DB::table('tbl_account_tax_rates')->where('id', '=', $id)->first();

            if (! empty($tax)) {
                return $tax->taxname;
            }
        }
    }

    // Get tax percent and name from tax table
    if (! function_exists('getTaxNameAndPercentFromTaxTable')) {
        function getTaxNameAndPercentFromTaxTable($id)
        {
            $tax = DB::table('tbl_account_tax_rates')->where('id', '=', $id)->first();

            if (! empty($tax)) {
                return $tax->taxname.' '.$tax->tax;
            }
        }
    }

    // Get tax number from tax table
    if (! function_exists('getTaxNumberFromTaxTable')) {
        function getTaxNumberFromTaxTable($id)
        {
            $tax = DB::table('tbl_account_tax_rates')->where('id', '=', $id)->first();

            if (! empty($tax)) {
                return $tax->tax_number;
            }
        }
    }

    // Get User FullName
    if (! function_exists('getUserFullName')) {
        function getUserFullName($id)
        {
            $users = DB::table('users')->where('id', '=', $id)->first();
            if (! empty($users)) {
                $user_name = $users->name.' '.$users->lastname;

                return $user_name;
            }
        }
    }

    // Get getRadiolabelsList form add time for all modules
    if (! function_exists('getRadiolabelsList')) {
        function getRadiolabelsList($id)
        {
            $radioLabeltsData = DB::table('tbl_custom_fields')->where('id', '=', $id)->first();

            $radioLabelArray = [];
            if (! empty($radioLabeltsData)) {
                $radioLabelArray = json_decode($radioLabeltsData->radio_labels);

                return $radioLabelArray;
            }
        }
    }

    // Get getRadiolabelsListValue for form edit time for all users modules
    if (! function_exists('getRadioLabelValueForUpdate')) {
        function getRadioLabelValueForUpdate($userid, $customFiledTableId)
        {
            $userdata = DB::table('users')->where('id', '=', $userid)->first();
            $jsonn = $userdata->custom_field;

            $jsonns = json_decode($jsonn);
            if (! empty($jsonns)) {
                foreach ($jsonns as $key => $value) {
                    $ids = $value->id;
                    $value1 = $value->value;

                    if ($ids == $customFiledTableId) {
                        return $value1;
                    }
                }
            }
        }
    }

    // Get getRadioSelectedValue for form view page for all modules
    if (! function_exists('getRadioSelectedValue')) {
        function getRadioSelectedValue($tbl_custom_field_id, $datavalue)
        {
            $radioLabeltsData = DB::table('tbl_custom_fields')->where('id', '=', $tbl_custom_field_id)->first();

            $radioLabelArray = '';
            if (! empty($radioLabeltsData)) {
                $radioLabelArray = json_decode($radioLabeltsData->radio_labels);

                foreach ($radioLabelArray as $key => $value) {
                    if ($key == $datavalue) {
                        return $value;
                    }
                }
            }
        }
    }

    // Get getCheckboxlabelsList for all modules
    if (! function_exists('getCheckboxLabelsList')) {
        function getCheckboxLabelsList($id)
        {
            $checkboxLabeltsData = DB::table('tbl_custom_fields')->where('id', '=', $id)->first();

            $checkboxLabelArray = [];
            if (! empty($checkboxLabeltsData)) {
                $checkboxLabelArray = json_decode($checkboxLabeltsData->checkbox_labels);

                return $checkboxLabelArray;
            }
        }
    }

    // Get getCheckboxLabelValueForUpdate for edit time for user module
    if (! function_exists('getCheckboxLabelValueForUpdate')) {
        function getCheckboxLabelValueForUpdate($userid, $customFiledTableId)
        {
            $userdata = DB::table('users')->where('id', '=', $userid)->first();
            $jsonn = $userdata->custom_field;

            $jsonns = json_decode($jsonn);
            if (! empty($jsonns)) {
                foreach ($jsonns as $key => $value) {
                    $ids = $value->id;
                    $value1 = $value->value;

                    if ($ids == $customFiledTableId) {
                        // echo $value1;
                        $add_one_in = explode(',', $value1);

                        return $add_one_in;
                    }
                }
            }
        }
    }

    // Get getCheckboxLabelValueForUpdate for edit time for user module
    if (! function_exists('getCheckboxVal')) {
        function getCheckboxVal($userid, $customFiledTableId, $val)
        {
            $userdata = DB::table('users')->where('id', '=', $userid)->first();
            $jsonn = $userdata->custom_field;

            $jsonns = json_decode($jsonn);
            if (! empty($jsonns)) {
                foreach ($jsonns as $key => $value) {
                    $ids = $value->id;
                    $value1 = $value->value;

                    if ($ids == $customFiledTableId) {
                        // echo $value1;
                        // $add_one_in = explode(",", $value1);
                        $add_one_in = array_filter(explode(',', $value1));
                        if (! empty($add_one_in)) {
                            foreach ($add_one_in as $key => $value) {
                                if ($val == $value) {
                                    return $value;
                                }
                            }
                        } else {
                            return '';
                        }
                    }
                }
            }
        }
    }

    // Get getRadioLabelValueForUpdateForAllModules for form edit time for all modules
    if (! function_exists('getRadioLabelValueForUpdateForAllModules')) {
        function getRadioLabelValueForUpdateForAllModules($formName, $dataId, $customFiledTableId)
        {
            $customDdata = '';
            $jsonn = '';
            if ($formName == 'product') {
                $customDdata = DB::table('tbl_products')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'purchase') {
                $customDdata = DB::table('tbl_purchases')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'vehicle') {
                $customDdata = DB::table('tbl_vehicles')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'vehicletype') {
                $customDdata = DB::table('tbl_vehicle_types')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'vehiclebrand') {
                $customDdata = DB::table('tbl_vehicle_brands')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'color') {
                $customDdata = DB::table('tbl_colors')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'service') {
                $customDdata = DB::table('tbl_services')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'invoice') {
                $customDdata = DB::table('tbl_invoices')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'income') {
                $customDdata = DB::table('tbl_incomes')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'expense') {
                $customDdata = DB::table('tbl_expenses')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'sales') {
                $customDdata = DB::table('tbl_sales')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'salepart') {
                $customDdata = DB::table('tbl_sale_parts')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'rto') {
                $customDdata = DB::table('tbl_rto_taxes')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            }

            $jsonns = json_decode($jsonn);
            if (! empty($jsonns)) {
                foreach ($jsonns as $key => $value) {
                    $ids = $value->id;
                    $value1 = $value->value;

                    if ($ids == $customFiledTableId) {
                        return $value1;
                    }
                }
            }
        }
    }

    // Get getCheckboxLabelValueForUpdateForAllModules for edit time for user module
    if (! function_exists('getCheckboxLabelValueForUpdateForAllModules')) {
        function getCheckboxLabelValueForUpdateForAllModules($formName, $dataId, $customFiledTableId)
        {
            $customDdata = '';
            $jsonn = '';
            if ($formName == 'product') {
                $customDdata = DB::table('tbl_products')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'purchase') {
                $customDdata = DB::table('tbl_purchases')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'vehicle') {
                $customDdata = DB::table('tbl_vehicles')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'vehicletype') {
                $customDdata = DB::table('tbl_vehicle_types')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'vehiclebrand') {
                $customDdata = DB::table('tbl_vehicle_brands')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'color') {
                $customDdata = DB::table('tbl_colors')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'service') {
                $customDdata = DB::table('tbl_services')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'invoice') {
                $customDdata = DB::table('tbl_invoices')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'income') {
                $customDdata = DB::table('tbl_incomes')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'expense') {
                $customDdata = DB::table('tbl_expenses')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'sales') {
                $customDdata = DB::table('tbl_sales')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'salepart') {
                $customDdata = DB::table('tbl_sale_parts')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'rto') {
                $customDdata = DB::table('tbl_rto_taxes')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            }

            $jsonns = json_decode($jsonn);
            if (! empty($jsonns)) {
                foreach ($jsonns as $key => $value) {
                    $ids = $value->id;
                    $value1 = $value->value;

                    if ($ids == $customFiledTableId) {
                        // echo $value1;
                        $add_one_in = explode(',', $value1);

                        return $add_one_in;
                    }
                }
            }
        }
    }

    // Get getCheckboxValForAllModule for edit time for all module
    if (! function_exists('getCheckboxValForAllModule')) {
        function getCheckboxValForAllModule($formName, $dataId, $customFiledTableId, $val)
        {
            $customDdata = '';
            $jsonn = '';
            if ($formName == 'product') {
                $customDdata = DB::table('tbl_products')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'purchase') {
                $customDdata = DB::table('tbl_purchases')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'vehicle') {
                $customDdata = DB::table('tbl_vehicles')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'vehicletype') {
                $customDdata = DB::table('tbl_vehicle_types')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'vehiclebrand') {
                $customDdata = DB::table('tbl_vehicle_brands')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'color') {
                $customDdata = DB::table('tbl_colors')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'service') {
                $customDdata = DB::table('tbl_services')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'invoice') {
                $customDdata = DB::table('tbl_invoices')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'income') {
                $customDdata = DB::table('tbl_incomes')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'expense') {
                $customDdata = DB::table('tbl_expenses')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'sales') {
                $customDdata = DB::table('tbl_sales')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'salepart') {
                $customDdata = DB::table('tbl_sale_parts')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            } elseif ($formName == 'rto') {
                $customDdata = DB::table('tbl_rto_taxes')->where('id', '=', $dataId)->first();
                $jsonn = $customDdata->custom_field;
            }

            $jsonns = json_decode($jsonn);
            if (! empty($jsonns)) {
                foreach ($jsonns as $key => $value) {
                    $ids = $value->id;
                    $value1 = $value->value;

                    if ($ids == $customFiledTableId) {
                        // echo $value1;
                        // $add_one_in = explode(",", $value1);
                        $add_one_in = array_filter(explode(',', $value1));
                        if (! empty($add_one_in)) {
                            foreach ($add_one_in as $key => $value) {
                                if ($val == $value) {
                                    return $value;
                                }
                            }
                        } else {
                            return '';
                        }
                    }
                }
            }
        }
    }

    // Get getUnpaidInvoice invoice number from tbl_invoices
    if (! function_exists('getUnpaidInvoice')) {
        function getUnpaidInvoice($invoiceNumber)
        {
            $invoiceData = DB::table('tbl_invoices')->where('invoice_number', '=', $invoiceNumber)->first();

            if (! empty($invoiceData)) {
                if ($invoiceData->grand_total == $invoiceData->paid_amount || $invoiceData->grand_total == 0) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    function currencyList($key = null)
    {
        $currency_symbols = [
            'AED' => '&#1583;.&#1573;', // ?
            'AFN' => '&#65;&#102;',
            'ALL' => '&#76;&#101;&#107;',
            'AMD' => '',
            'ANG' => '&#402;',
            'AOA' => '&#75;&#122;', // ?
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&#402;',
            'AZN' => '&#1084;&#1072;&#1085;',
            'BAM' => '&#75;&#77;',
            'BBD' => '&#36;',
            'BDT' => '&#2547;', // ?
            'BGN' => '&#1083;&#1074;',
            'BHD' => '.&#1583;.&#1576;', // ?
            'BIF' => '&#70;&#66;&#117;', // ?
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => '&#36;&#98;',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTN' => '&#78;&#117;&#46;', // ?
            'BWP' => '&#80;',
            'BYR' => '&#112;&#46;',
            'BZD' => '&#66;&#90;&#36;',
            'CAD' => '&#36;',
            'CDF' => '&#70;&#67;',
            'CHF' => '&#67;&#72;&#70;',
            'CLF' => '', // ?
            'CLP' => '&#36;',
            'CNY' => '&#165;',
            'COP' => '&#36;',
            'CRC' => '&#8353;',
            'CUP' => '&#8396;',
            'CVE' => '&#36;', // ?
            'CZK' => '&#75;&#269;',
            'DJF' => '&#70;&#100;&#106;', // ?
            'DKK' => '&#107;&#114;',
            'DOP' => '&#82;&#68;&#36;',
            'DZD' => '&#1583;&#1580;', // ?
            'EGP' => '&#163;',
            'ETB' => '&#66;&#114;',
            'EUR' => '&#8364;',
            'FJD' => '&#36;',
            'FKP' => '&#163;',
            'GBP' => '&#163;',
            'GEL' => '&#4314;', // ?
            'GHS' => '&#162;',
            'GIP' => '&#163;',
            'GMD' => '&#68;', // ?
            'GNF' => '&#70;&#71;', // ?
            'GTQ' => '&#81;',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => '&#76;',
            'HRK' => '&#107;&#110;',
            'HTG' => '&#71;', // ?
            'HUF' => '&#70;&#116;',
            'IDR' => '&#82;&#112;',
            'ILS' => '&#8362;',
            'INR' => '&#8377;',
            'IQD' => '&#1593;.&#1583;', // ?
            'IRR' => '&#65020;',
            'ISK' => '&#107;&#114;',
            'JEP' => '&#163;',
            'JMD' => '&#74;&#36;',
            'JOD' => '&#74;&#68;', // ?
            'JPY' => '&#165;',
            'KES' => '&#75;&#83;&#104;', // ?
            'KGS' => '&#1083;&#1074;',
            'KHR' => '&#6107;',
            'KMF' => '&#67;&#70;', // ?
            'KPW' => '&#8361;',
            'KRW' => '&#8361;',
            'KWD' => '&#1583;.&#1603;', // ?
            'KYD' => '&#36;',
            'KZT' => '&#1083;&#1074;',
            'LAK' => '&#8365;',
            'LBP' => '&#163;',
            'LKR' => '&#8360;',
            'LRD' => '&#36;',
            'LSL' => '&#76;', // ?
            'LTL' => '&#76;&#116;',
            'LVL' => '&#76;&#115;',
            'LYD' => '&#1604;.&#1583;', // ?
            'MAD' => '&#1583;.&#1605;.', // ?
            'MDL' => '&#76;',
            'MGA' => '&#65;&#114;', // ?
            'MKD' => '&#1076;&#1077;&#1085;',
            'MMK' => '&#75;',
            'MNT' => '&#8366;',
            'MOP' => '&#77;&#79;&#80;&#36;', // ?
            'MRO' => '&#85;&#77;', // ?
            'MUR' => '&#8360;', // ?
            'MVR' => '.&#1923;', // ?
            'MWK' => '&#77;&#75;',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => '&#77;&#84;',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => '&#67;&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#65020;',
            'PAB' => '&#66;&#47;&#46;',
            'PEN' => '&#83;&#47;&#46;',
            'PGK' => '&#75;', // ?
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PYG' => '&#71;&#115;',
            'QAR' => '&#65020;',
            'RON' => '&#108;&#101;&#105;',
            'RSD' => '&#1044;&#1080;&#1085;&#46;',
            'RUB' => '&#1088;&#1091;&#1073;',
            'RWF' => '&#1585;.&#1587;',
            'SAR' => '&#65020;',
            'SBD' => '&#36;',
            'SCR' => '&#8360;',
            'SDG' => '&#163;', // ?
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&#163;',
            'SLL' => '&#76;&#101;', // ?
            'SOS' => '&#83;',
            'SRD' => '&#36;',
            'STD' => '&#68;&#98;', // ?
            'SVC' => '&#36;',
            'SYP' => '&#163;',
            'SZL' => '&#76;', // ?
            'THB' => '&#3647;',
            'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
            'TMT' => '&#109;',
            'TND' => '&#1583;.&#1578;',
            'TOP' => '&#84;&#36;',
            'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
            'TRL' => '&#163;',
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => 'Sh',
            'UAH' => '&#8372;',
            'UGX' => '&#85;&#83;&#104;',
            'USD' => '&#36;',
            'UYU' => '&#36;&#85;',
            'UZS' => '&#1083;&#1074;',
            'VEF' => '&#66;&#115;',
            'VND' => '&#8363;',
            'VUV' => '&#86;&#84;',
            'WST' => '&#87;&#83;&#36;',
            'XAF' => '&#70;&#67;&#70;&#65;',
            'XCD' => '&#36;',
            'XDR' => '',
            'XOF' => '&#70;&#67;&#70;&#65;',
            'XPF' => '&#70;',
            'YER' => '&#65020;',
            'ZAR' => '&#82;',
            'ZMK' => '&#90;&#75;', // ?
            'ZWL' => '&#90;&#36;',
            'NG' => '&#8358;',
            'ERN' => '',
            'AZ' => '&#1084;&#1072;&#1085;', // Azerbaijan New Manats (same as AZN)
            'BG' => '&#1083;&#1074;', // Bulgaria Leva (same as BGN)
            'MZ' => '&#77;&#84;', // Mozambique Meticais (same as MZN)
            'PE' => '&#83;&#47;&#46;', // Peru Nuevos Soles (same as PEN)
            'PL' => '&#122;&#322;', // Poland Zlotych (same as PLN)
            'RO' => '&#108;&#101;&#105;', // Romania New Lei (same as RON)
            'TVD' => '&#36;', // Tuvalu Dollars
            'ZWD' => '&#90;&#36;', // Zimbabwe Dollars (you have ZWL but need ZWD)

            'STN' => '&#68;&#98;', // Sao Tome and Principe Dobra (you have STD)
            'MRU' => '&#85;&#77;', // Mauritania Ouguiya (you have MRO)
            'ZMW' => '&#90;&#75;', // Zambia Kwacha (you have ZMK)
            'SSP' => '&#163;', // South Sudan Pound
        ];
        if ($key != null) {
            return $currency_symbols[$key] ?? null;
        } else {
            return $currency_symbols;
        }
    }

    // get branch name from ID
    if (! function_exists('getBranchName')) {
        function getBranchName($id)
        {

            $branch = DB::table('branches')->where('id', '=', $id)->first();

            if (! empty($branch)) {
                $branch_name = $branch->branch_name;

                return $branch_name;
            }
        }
    }

    // Get curret sysyem local
    if (! function_exists('getCurrentLocal')) {
        function getCurrentLocal()
        {
            $userid = Auth::User()->id;
            $data = DB::table('users')->where('id', '=', $userid)->first();

            if (! empty($data)) {
                return $data->language;
            }
        }
    }

    // Get customer image
    if (! function_exists('getCustomerImage')) {
        function getCustomerImage($id)
        {
            $customer = DB::table('users')->where([['id', '=', $id]])->first();
            if (! empty($customer)) {
                $image = $customer->image;

                return $image;
            }
        }
    }

    // Get  purchase date
    if (! function_exists('getPurchaseProducts')) {
        function getPurchaseProducts($id)
        {
            $purchaseDetails = DB::table('tbl_purchase_history_records')->where('purchase_id', '=', $id)->get()->toArray();
            $products = [];

            foreach ($purchaseDetails as $purchaseDetail) {
                $product = getProduct($purchaseDetail->product_id);
                $products[] = $product;
            }
            $name = implode(', ', $products);

            return $name;
        }
    }

    // Get Vehicle type value In vehicle brand module
    if (! function_exists('getModel')) {
        function getModel($id)
        {
            $modelName = DB::table('tbl_model_names')->where('id', '=', $id)->first();
            if (! empty($modelName)) {
                $vehicle_model = $modelName->model_name;

                return $vehicle_model;
            }
        }
    }

    // Get Vehicle model id from name
    if (! function_exists('getModel_id')) {
        function getModel_id($name)
        {
            $model = DB::table('tbl_model_names')->where('model_name', '=', $name)->first();
            if (! empty($model)) {
                $model_id = $model->id;

                return $model_id;
            }
        }
    }

    // Get Manufacturer Name
    if (! function_exists('getManufacturer')) {
        function getManufacturer($id)
        {
            $name = DB::table('tbl_product_types')->where('id', '=', $id)->first();
            if (! empty($name)) {
                $name = $name->type;

                return $name;
            }
        }
    }

    // Get Mobile Number
    if (! function_exists('getMobile')) {
        function getMobile($id)
        {
            $user = DB::table('users')->where('id', '=', $id)->first();

            if (! empty($user)) {
                $mobile = $user->mobile_no;

                return $mobile;
            }
        }
    }

    // Get Frontend booking status
    if (! function_exists('getFrontendBooking')) {
        function getFrontendBooking()
        {
            try {
                $system = DB::table('tbl_settings')->first();
                $status = $system->frontend_service;

                return (int) $status;
            } catch (Exception $e) {
                return 0;
            }
        }
    }

    // Get Edit service
    if (! function_exists('getEditService')) {
        function getEditService()
        {
            $system = DB::table('tbl_settings')->first();
            $status = $system->edit_service;

            return (int) $status;
        }
    }

    if (! function_exists('saveNotes')) {
        function saveNotes(Model $model, array $notes, ?string $email = null)
        {
            $sharedNotes = [];
            $attachments = [];

            if ($model->save()) {
                if (! empty($notes)) {
                    foreach ($notes as $note) {
                        $noteData = [
                            'notes' => $note['note_text'] ?? null,
                            'internal' => isset($note['internal']) ? 1 : 0,
                            'shared_with_customer' => isset($note['shared']) ? 1 : 0,
                        ];

                        // Check if note_id is provided
                        if (! empty($note['note_id'])) {
                            // Update existing note
                            $existingNote = $model->notes()->where('id', $note['note_id'])->first();
                            if ($existingNote) {
                                $existingNote->update($noteData);
                                if (isset($note['note_file'])) {
                                    foreach ($note['note_file'] as $attachment) {
                                        $originalFilename = $attachment->getClientOriginalName();
                                        $storedPath = $attachment->storeAs('notes', $originalFilename); // Save the file using the original name
                                        $publicPath = public_path('notes/'.$originalFilename); // Move the file to the public directory with the same name
                                        copy(storage_path('app/'.$storedPath), $publicPath);

                                        $note_attachments = new note_attachments;
                                        $note_attachments->note_id = $existingNote->id;
                                        $note_attachments->entity_id = $model->id;
                                        $note_attachments->attachment = $storedPath;
                                        $note_attachments->save();

                                        $attachments[] = $publicPath; // Collect attachment paths
                                    }
                                }

                                $attachments = note_attachments::where('note_id', $existingNote->id)->get(); // Fetch attachments associated with the note
                                $noteAttachments = []; // Initialize array to hold attachment paths for this note
                                foreach ($attachments as $attachment) { // Loop through fetched attachments
                                    $noteAttachments[] = $attachment->attachment; // Add attachment path to the array
                                }
                                // Collect shared note data
                                if ($noteData['shared_with_customer']) {
                                    $sharedNoteData = [
                                        'note' => $noteData,
                                        'attachments' => $noteAttachments,
                                    ];
                                    $sharedNotes[] = $sharedNoteData;
                                }
                            }
                        } else {
                            // Create new note
                            $noteData['create_by'] = Auth::User()->id;
                            $newNote = $model->notes()->create($noteData);
                            if (isset($note['note_file'])) {
                                foreach ($note['note_file'] as $attachment) {
                                    $originalFilename = $attachment->getClientOriginalName();
                                    $storedPath = $attachment->storeAs('notes', $originalFilename); // Save the file using the original name
                                    $publicPath = public_path('notes/'.$originalFilename); // Move the file to the public directory with the same name
                                    copy(storage_path('app/'.$storedPath), $publicPath);

                                    $note_attachments = new note_attachments;
                                    $note_attachments->note_id = $newNote->id;
                                    $note_attachments->entity_id = $model->id;
                                    $note_attachments->attachment = $storedPath;
                                    $note_attachments->save();

                                    $attachments[] = $publicPath; // Collect attachment paths
                                }
                            }

                            $attachments = note_attachments::where('note_id', $newNote->id)->get();
                            $noteAttachments = [];
                            foreach ($attachments as $attachment) {
                                $noteAttachments[] = $attachment->attachment;
                            }
                            // Collect shared note data
                            if ($noteData['shared_with_customer']) {
                                $sharedNoteData = [
                                    'note' => $noteData,
                                    'attachments' => $noteAttachments,
                                ];
                                $sharedNotes[] = $sharedNoteData;
                            }
                        }
                    }

                    // Send a single email with all shared notes
                    if (! empty($sharedNotes) && $email) {
                        sendNotesEmail($sharedNotes, $model, $email);
                    }
                }
            }
        }
    }

    function sendNotesEmail($sharedNotes, $model, ?string $toEmail = null)
    {
        try {
            // dd($toEmail);
            if (! $toEmail) {
                return; // If email is not provided, do not send the email
            }

            $modelType = class_basename($model); // Get the short class name of the model
            $mail_sub = "New notes for $modelType";
            $emailformat = DB::table('tbl_mail_notifications')->first();
            $mail_send_from = $emailformat->send_from;

            $blade_view = View::make('mail.shared_notes', [
                'sharedNotes' => $sharedNotes,
            ])->render();

            try {
                // Send email
                Mail::send([], [], function ($message) use ($toEmail, $mail_sub, $blade_view, $mail_send_from) {
                    $message->to($toEmail)->subject($mail_sub);
                    $message->from($mail_send_from);
                    $message->html($blade_view, 'text/html');
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send email: '.$e->getMessage());
            }

        } catch (\Exception $e) {
        }
    }
}
