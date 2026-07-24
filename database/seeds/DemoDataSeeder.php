<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        $branchId = 1;

        // ───────────────────────────────────────────────
        // 1. USERS — 10 Customers + 5 Employees + 2 Suppliers
        // ───────────────────────────────────────────────
        $password = Hash::make('password123');

        $customers = [];
        $customerNames = [
            ['John', 'Doe', 'john.doe@example.com', '0712345601'],
            ['Mary', 'Wilson', 'mary.wilson@example.com', '0712345602'],
            ['Robert', 'Brown', 'robert.brown@example.com', '0712345603'],
            ['Patricia', 'Taylor', 'patricia.taylor@example.com', '0712345604'],
            ['Michael', 'Davis', 'michael.davis@example.com', '0712345605'],
            ['Linda', 'Anderson', 'linda.anderson@example.com', '0712345606'],
            ['James', 'Thomas', 'james.thomas@example.com', '0712345607'],
            ['Barbara', 'Moore', 'barbara.moore@example.com', '0712345608'],
            ['William', 'Jackson', 'william.jackson@example.com', '0712345609'],
            ['Elizabeth', 'Martin', 'elizabeth.martin@example.com', '0712345610'],
        ];

        // Start customer IDs at 100 to avoid conflicts
        $customerIdStart = 100;
        for ($i = 0; $i < 10; $i++) {
            $customers[] = [
                'id'         => $customerIdStart + $i,
                'name'       => $customerNames[$i][0],
                'lastname'   => $customerNames[$i][1],
                'display_name' => $customerNames[$i][0] . ' ' . $customerNames[$i][1],
                'company_name' => null,
                'email'      => $customerNames[$i][2],
                'password'   => $password,
                'mobile_no'  => $customerNames[$i][3],
                'address'    => '123 Demo Street, Demo City',
                'image'      => 'avtar.png',
                'join_date'  => null,
                'designation'=> null,
                'role'       => 'Customer',
                'role_id'    => 2,
                'language'   => 'en',
                'timezone'   => 'UTC',
                'soft_delete' => 0,
                'branch_id'  => $branchId,
                'create_by'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $employees = [];
        $employeeNames = [
            ['Carlos', 'Mendez', 'carlos.mendez@garage.com', 'Mechanic', 'Senior Mechanic'],
            ['Aisha', 'Khan', 'aisha.khan@garage.com', 'Mechanic', 'Junior Mechanic'],
            ['David', 'Chen', 'david.chen@garage.com', 'Mechanic', 'Electrician'],
            ['Sophie', 'Dubois', 'sophie.dubois@garage.com', 'Service Advisor', 'Service Advisor'],
            ['Omar', 'Hassan', 'omar.hassan@garage.com', 'Cleaner', 'Wash Bay Attendant'],
        ];

        $employeeIdStart = 110;
        for ($i = 0; $i < 5; $i++) {
            $employees[] = [
                'id'         => $employeeIdStart + $i,
                'name'       => $employeeNames[$i][0],
                'lastname'   => $employeeNames[$i][1],
                'display_name' => $employeeNames[$i][0] . ' ' . $employeeNames[$i][1],
                'company_name' => null,
                'email'      => $employeeNames[$i][2],
                'password'   => $password,
                'mobile_no'  => '0798765' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'address'    => '45 Garage Lane, Workshop District',
                'image'      => 'avtar.png',
                'join_date'  => '2023-01-15',
                'designation'=> $employeeNames[$i][3],
                'role'       => 'employee',
                'role_id'    => 3,
                'language'   => 'en',
                'timezone'   => 'UTC',
                'soft_delete' => 0,
                'branch_id'  => $branchId,
                'create_by'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $suppliers = [];
        $supplierNames = [
            ['AutoZone', 'contact@autozone.com', '0800123456'],
            ['Bosch Parts', 'orders@boschparts.com', '0800654321'],
        ];

        $supplierIdStart = 120;
        for ($i = 0; $i < 2; $i++) {
            $suppliers[] = [
                'id'         => $supplierIdStart + $i,
                'name'       => $supplierNames[$i][0],
                'lastname'   => null,
                'display_name' => $supplierNames[$i][0],
                'company_name' => $supplierNames[$i][0],
                'email'      => $supplierNames[$i][1],
                'password'   => $password,
                'mobile_no'  => $supplierNames[$i][2],
                'address'    => 'Industrial Zone, Supplier District',
                'image'      => 'avtar.png',
                'join_date'  => null,
                'designation'=> null,
                'role'       => 'Supplier',
                'role_id'    => null,
                'language'   => 'en',
                'timezone'   => 'UTC',
                'soft_delete' => 0,
                'branch_id'  => $branchId,
                'create_by'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('users')->insertOrIgnore(array_merge($customers, $employees, $suppliers));

        // role_users mapping
        $roleUsers = [];
        foreach ($customers as $c) {
            $roleUsers[] = ['user_id' => $c['id'], 'role_id' => 2, 'created_at' => $now, 'updated_at' => $now];
        }
        foreach ($employees as $e) {
            $roleUsers[] = ['user_id' => $e['id'], 'role_id' => 3, 'created_at' => $now, 'updated_at' => $now];
        }
        DB::table('role_users')->insertOrIgnore($roleUsers);

        // ───────────────────────────────────────────────
        // 2. VEHICLES — 10 entries
        // ───────────────────────────────────────────────
        $vehicleData = [
            ['ABC-1001', 'BMW', '320d', '2021', '1', 'Diesel'],
            ['DEF-1002', 'BMW', 'X5', '2020', '1', 'Diesel'],
            ['GHI-1003', 'BMW', '5 Series', '2022', '1', 'Diesel'],
            ['JKL-1004', 'BMW', '3 Series', '2019', '1', 'Diesel'],
            ['MNO-1005', 'BMW', 'X3', '2023', '1', 'Diesel'],
            ['PQR-1006', 'BMW', '7 Series', '2021', '1', 'Diesel'],
            ['STU-1007', 'BMW', 'X1', '2020', '1', 'Diesel'],
            ['VWX-1008', 'BMW', '5 Series', '2022', '1', 'Diesel'],
            ['YZA-1009', 'BMW', '3 Series', '2018', '1', 'Diesel'],
            ['BCD-1010', 'BMW', 'X5', '2023', '1', 'Diesel'],
        ];

        $vehicles = [];
        $vehicleIdStart = 100;
        for ($i = 0; $i < 10; $i++) {
            $vehicles[] = [
                'id'         => $vehicleIdStart + $i,
                'vehicletype_id' => 1,
                'number_plate' => $vehicleData[$i][0],
                'vehiclebrand_id' => 1,
                'modelyear'  => $vehicleData[$i][3],
                'fuel_id'    => 1,
                'modelname'  => $vehicleData[$i][2],
                'odometerreading' => (string) (15000 + $i * 8500),
                'customer_id'=> $customerIdStart + $i,
                'added_by_service' => 1,
                'soft_delete' => 0,
                'branch_id'  => $branchId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('tbl_vehicles')->insertOrIgnore($vehicles);

        // ───────────────────────────────────────────────
        // 3. SERVICES (Job Cards) — 10 entries
        // ───────────────────────────────────────────────
        $serviceCategories = ['booked vehicle', 'breakdown', 'repeat job', 'customer waiting', 'booked vehicle', 'breakdown', 'repeat job', 'booked vehicle', 'customer waiting', 'booked vehicle'];
        $serviceCharges = [250, 500, 150, 350, 800, 1200, 200, 450, 600, 300];
        $doneStatuses = [1, 1, 0, 1, 1, 0, 1, 1, 0, 1];
        $assignTo = $employeeIdStart; // Carlos

        $services = [];
        $jobcardDetails = [];
        $servicePros = [];
        $washbays = [];
        $gatepasses = [];
        $serviceIdStart = 100;

        for ($i = 0; $i < 10; $i++) {
            $jobNo = 'J' . str_pad($serviceIdStart + $i, 6, '0', STR_PAD_LEFT);
            $serviceDate = Carbon::now()->subDays(30 - $i * 2);

            $services[] = [
                'id'         => $serviceIdStart + $i,
                'job_no'     => $jobNo,
                'service_type' => $i % 3 === 0 ? 'free' : 'paid',
                'service_date' => $serviceDate->format('Y-m-d H:i:s'),
                'title'      => 'Service for ' . $vehicleData[$i][2],
                'assign_to'  => $employeeIdStart + ($i % 5),
                'service_category' => $serviceCategories[$i],
                'done_status'=> (string) $doneStatuses[$i],
                'charge'     => (string) $serviceCharges[$i],
                'customer_id'=> $customerIdStart + $i,
                'vehicle_id' => $vehicleIdStart + $i,
                'create_by'  => 1,
                'is_appove'  => $doneStatuses[$i] === 1 ? 1 : 0,
                'mot_status' => 0,
                'soft_delete' => 0,
                'is_quotation' => $i % 4 === 0 ? 1 : 0,
                'quotation_modify_status' => $i % 4 === 0 ? 2 : 0,
                'branch_id'  => $branchId,
                'created_at' => $serviceDate,
                'updated_at' => $serviceDate,
            ];

            $jobcardDetails[] = [
                'service_id' => $serviceIdStart + $i,
                'jocard_no'  => $jobNo,
                'customer_id'=> $customerIdStart + $i,
                'vehicle_id' => $vehicleIdStart + $i,
                'in_date'    => $serviceDate->format('Y-m-d H:i:s'),
                'out_date'   => $doneStatuses[$i] === 1 ? $serviceDate->copy()->addHours(4)->format('Y-m-d H:i:s') : null,
                'next_date'  => $doneStatuses[$i] === 1 ? $serviceDate->copy()->addMonth()->format('Y-m-d') : null,
                'kms_run'    => (string) (15000 + $i * 8500),
                'done_status'=> $doneStatuses[$i],
                'soft_delete' => 0,
                'reminder_sent' => 0,
                'created_at' => $serviceDate,
                'updated_at' => $serviceDate,
            ];

            $servicePros[] = [
                'service_id' => $serviceIdStart + $i,
                'comment'    => ['Engine Check', 'Brake Inspection', 'Oil Change', 'Tyre Rotation', 'Battery Test', 'AC Service', 'Wheel Alignment', 'Clutch Repair', 'Full Diagnostic', 'Coolant Flush'][$i],
                'total_price'=> (string) $serviceCharges[$i],
                'type'       => 1,
                'soft_delete' => 0,
                'created_at' => $serviceDate,
                'updated_at' => $serviceDate,
            ];

            $washbays[] = [
                'service_id' => $serviceIdStart + $i,
                'jobcard_no' => $jobNo,
                'vehicle_id' => $vehicleIdStart + $i,
                'customer_id'=> $customerIdStart + $i,
                'price'      => '50',
                'initiate_status' => $doneStatuses[$i] === 1 ? 2 : 0,
                'created_at' => $serviceDate,
                'updated_at' => $serviceDate,
            ];

            if ($doneStatuses[$i] === 1) {
                $gatepasses[] = [
                    'gatepass_no' => 'G' . str_pad($serviceIdStart + $i, 6, '0', STR_PAD_LEFT),
                    'jobcard_id'  => $jobNo,
                    'customer_id' => $customerIdStart + $i,
                    'vehicle_id'  => $vehicleIdStart + $i,
                    'ser_pro_status' => 1,
                    'create_by'   => 1,
                    'gatepass_create_date' => $serviceDate->format('Y-m-d H:i:s'),
                    'service_out_date' => $serviceDate->copy()->addHours(5)->format('Y-m-d H:i:s'),
                    'created_at'  => $serviceDate,
                    'updated_at'  => $serviceDate,
                ];
            }
        }

        DB::table('tbl_services')->insertOrIgnore($services);
        DB::table('tbl_jobcard_details')->insertOrIgnore($jobcardDetails);
        DB::table('tbl_service_pros')->insertOrIgnore($servicePros);
        DB::table('washbays')->insertOrIgnore($washbays);
        if (!empty($gatepasses)) {
            DB::table('tbl_gatepasses')->insertOrIgnore($gatepasses);
        }

        // ───────────────────────────────────────────────
        // 4. INVOICES — 10 entries
        // ───────────────────────────────────────────────
        $invoices = [];
        $incomes = [];
        $incomeHistoryRecords = [];
        $invoiceIdStart = 100;

        for ($i = 0; $i < 10; $i++) {
            $invDate = Carbon::now()->subDays(28 - $i * 2);
            $totalAmount = $serviceCharges[$i] + 50; // service charge + washbay
            $grandTotal = $totalAmount * 1.15; // with 15% tax
            $paymentStatus = $doneStatuses[$i] === 1 ? 2 : ($i % 3 === 0 ? 1 : 0);
            $invoiceNumber = str_pad($invoiceIdStart + $i, 8, '0', STR_PAD_LEFT);

            $invoices[] = [
                'invoice_number' => $invoiceNumber,
                'payment_number' => 'P' . str_pad($invoiceIdStart + $i, 6, '0', STR_PAD_LEFT),
                'customer_id'   => (string) ($customerIdStart + $i),
                'job_card'      => 'J' . str_pad($serviceIdStart + $i, 6, '0', STR_PAD_LEFT),
                'payment_type'  => '1',
                'payment_status'=> $paymentStatus,
                'total_amount'  => $totalAmount,
                'grand_total'   => round($grandTotal, 2),
                'discount'      => $i % 5 === 0 ? 25.00 : 0.00,
                'paid_amount'   => $paymentStatus === 2 ? round($grandTotal, 2) : ($paymentStatus === 1 ? round($grandTotal / 2, 2) : 0),
                'amount_recevied' => $paymentStatus === 2 ? round($grandTotal, 2) : ($paymentStatus === 1 ? round($grandTotal / 2, 2) : 0),
                'tax_name'      => '1, 2',
                'date'          => $invDate->format('Y-m-d'),
                'type'          => 0,
                'create_by'     => 1,
                'sales_service_id' => $serviceIdStart + $i,
                'soft_delete'   => 0,
                'branch_id'     => $branchId,
                'created_at'    => $invDate,
                'updated_at'    => $invDate,
            ];

            $incomes[] = [
                'invoice_number' => $invoiceNumber,
                'payment_number' => 'P' . str_pad($invoiceIdStart + $i, 6, '0', STR_PAD_LEFT),
                'customer_id'   => $customerIdStart + $i,
                'status'        => $paymentStatus,
                'payment_type'  => '1',
                'date'          => $invDate->format('Y-m-d'),
                'main_label'    => 'Service',
                'soft_delete'   => 0,
                'branch_id'     => $branchId,
                'created_at'    => $invDate,
                'updated_at'    => $invDate,
            ];

            $incomeHistoryRecords[] = [
                'tbl_income_id' => $invoiceIdStart + $i,
                'income_amount' => $paymentStatus > 0 ? round($grandTotal, 2) : 0,
                'income_label'  => 'Service',
                'soft_delete'   => 0,
                'branch_id'     => $branchId,
                'created_at'    => $invDate,
                'updated_at'    => $invDate,
            ];
        }

        DB::table('tbl_invoices')->insertOrIgnore($invoices);
        DB::table('tbl_incomes')->insertOrIgnore($incomes);
        DB::table('tbl_income_history_records')->insertOrIgnore($incomeHistoryRecords);

        // ───────────────────────────────────────────────
        // 5. EXPENSES — 10 entries
        // ───────────────────────────────────────────────
        $expenseLabels = ['Rent', 'Electricity Bill', 'Equipment Purchase', 'Staff Salary', 'Fuel for Workshop', 'Cleaning Supplies', 'Tool Replacement', 'Insurance Premium', 'Marketing & Ads', 'Miscellaneous'];
        $expenseAmounts = [1500, 350, 800, 3000, 200, 75, 150, 400, 250, 100];

        $expenses = [];
        $expenseHistoryRecords = [];
        $expenseIdStart = 100;

        for ($i = 0; $i < 10; $i++) {
            $expDate = Carbon::now()->subDays(29 - $i * 3);

            $expenses[] = [
                'main_label'  => $expenseLabels[$i],
                'status'      => 1,
                'date'        => $expDate->format('Y-m-d'),
                'branch_id'   => $branchId,
                'created_at'  => $expDate,
                'updated_at'  => $expDate,
            ];

            $expenseHistoryRecords[] = [
                'tbl_expenses_id' => $expenseIdStart + $i,
                'expense_amount'  => (string) $expenseAmounts[$i],
                'label_expense'   => $expenseLabels[$i],
                'created_at'      => $expDate,
                'updated_at'      => $expDate,
            ];
        }

        DB::table('tbl_expenses')->insertOrIgnore($expenses);
        DB::table('tbl_expenses_history_records')->insertOrIgnore($expenseHistoryRecords);

        // ───────────────────────────────────────────────
        // 6. PRODUCTS — 10 entries
        // ───────────────────────────────────────────────
        $productNames = ['Brake Pads', 'Oil Filter', 'Air Filter', 'Spark Plugs', 'Clutch Wire', 'Headlight Bulb', 'Wiper Blades', 'Brake Disc', 'Shock Absorber', 'Radiator Coolant'];
        $productPrices = [45, 15, 20, 30, 25, 12, 18, 65, 80, 10];

        $products = [];
        for ($i = 0; $i < 10; $i++) {
            $prodDate = Carbon::now()->subDays(20 - $i);
            $products[] = [
                'product_no'   => 'PR' . str_pad(100001 + $i, 6, '0', STR_PAD_LEFT),
                'product_date' => $prodDate->format('Y-m-d'),
                'product_image' => 'avtar.png',
                'name'         => $productNames[$i],
                'product_type_id' => 1,
                'color_id'     => 1,
                'price'        => (string) $productPrices[$i],
                'supplier_id'  => $supplierIdStart + ($i % 2),
                'warranty'     => $i % 3 === 0 ? '6 months' : 'No warranty',
                'quantity'     => (string) (50 + $i * 5),
                'category'     => 1,
                'unit'         => 1,
                'create_by'    => 1,
                'soft_delete'  => 0,
                'branch_id'    => $branchId,
                'created_at'   => $prodDate,
                'updated_at'   => $prodDate,
            ];
        }
        DB::table('tbl_products')->insertOrIgnore($products);

        // ───────────────────────────────────────────────
        // 7. SALES — 10 entries
        // ───────────────────────────────────────────────
        $sales = [];
        for ($i = 0; $i < 10; $i++) {
            $saleDate = Carbon::now()->subDays(25 - $i * 2);
            $price = $productPrices[$i] * 2;
            $qty = 2 + $i;
            $sales[] = [
                'customer_id'   => $customerIdStart + $i,
                'bill_no'       => 'S' . str_pad(100001 + $i, 6, '0', STR_PAD_LEFT),
                'payment_type_id' => 1,
                'date'          => $saleDate->format('Y-m-d'),
                'vehicle_brand' => 1,
                'status'        => 'Completed',
                'vehicle_id'    => $vehicleIdStart + $i,
                'color_id'      => 1,
                'quantity'      => $qty,
                'price'         => (string) $price,
                'total_price'   => (string) ($price * $qty),
                'salesmanname'  => (string) ($employeeIdStart + 3),
                'assigne_to'    => (string) ($employeeIdStart + ($i % 5)),
                'soft_delete'   => 0,
                'branch_id'     => $branchId,
                'created_at'    => $saleDate,
                'updated_at'    => $saleDate,
            ];
        }
        DB::table('tbl_sales')->insertOrIgnore($sales);

        // ───────────────────────────────────────────────
        // 8. SALE PARTS — 10 entries
        // ───────────────────────────────────────────────
        $saleParts = [];
        for ($i = 0; $i < 10; $i++) {
            $spDate = Carbon::now()->subDays(22 - $i * 2);
            $price = $productPrices[$i];
            $qty = 1 + ($i % 3);
            $saleParts[] = [
                'bill_no'        => 'SP' . str_pad(100001 + $i, 6, '0', STR_PAD_LEFT),
                'quantity'       => $qty,
                'salesmanname'   => (string) ($employeeIdStart + 3),
                'date'           => $spDate->format('Y-m-d'),
                'product_id'     => 100 + $i,
                'total_price'    => $price * $qty,
                'price'          => $price,
                'customer_id'    => $customerIdStart + $i,
                'product_type_id'=> 1,
                'soft_delete'    => 0,
                'branch_id'      => $branchId,
                'created_at'     => $spDate,
                'updated_at'     => $spDate,
            ];
        }
        DB::table('tbl_sale_parts')->insertOrIgnore($saleParts);

        // ───────────────────────────────────────────────
        // 9. PURCHASES — 10 entries
        // ───────────────────────────────────────────────
        $purchases = [];
        $purchaseHistoryRecords = [];
        for ($i = 0; $i < 10; $i++) {
            $purDate = Carbon::now()->subDays(26 - $i * 2);
            $supplierId = $supplierIdStart + ($i % 2);
            $purchases[] = [
                'purchase_no'  => 'PC' . str_pad(100001 + $i, 6, '0', STR_PAD_LEFT),
                'date'         => $purDate->format('Y-m-d'),
                'supplier_id'  => $supplierId,
                'mobile'       => '0800123456',
                'email'        => $supplierId === $supplierIdStart ? 'contact@autozone.com' : 'orders@boschparts.com',
                'address'      => 'Industrial Zone, Supplier District',
                'branch_id'    => $branchId,
                'create_by'    => 1,
                'created_at'   => $purDate,
                'updated_at'   => $purDate,
            ];

            $qty = 10 + $i * 3;
            $unitPrice = $productPrices[$i];
            $purchaseHistoryRecords[] = [
                'purchase_id'  => 100 + $i,
                'product_id'   => 100 + $i,
                'qty'          => $qty,
                'category'     => 1,
                'price'        => (string) $unitPrice,
                'total_amount' => (string) ($unitPrice * $qty),
                'branch_id'    => $branchId,
                'created_at'   => $purDate,
                'updated_at'   => $purDate,
            ];
        }
        DB::table('tbl_purchases')->insertOrIgnore($purchases);
        DB::table('tbl_purchase_history_records')->insertOrIgnore($purchaseHistoryRecords);

        // ───────────────────────────────────────────────
        // 10. PAYROLL DATA
        // ───────────────────────────────────────────────

        // Payroll Settings (1 row)
        DB::table('payroll_settings')->insertOrIgnore([
            'default_hourly_rate' => 15.00,
            'overtime_multiplier' => 1.50,
            'night_diff_multiplier' => 1.10,
            'holiday_multiplier' => 2.00,
            'regular_hours_per_day' => 8,
            'work_days_per_week' => 5,
            'night_diff_start' => '22:00:00',
            'night_diff_end' => '06:00:00',
            'pay_period' => 'semi-monthly',
            'cutoff_day_1' => 15,
            'cutoff_day_2' => 30,
            'sss_contribution_rate' => 0.045,
            'philhealth_contribution_rate' => 0.025,
            'pagibig_contribution_rate' => 0.020,
            'tax_rate' => 0.10,
            'branch_id' => $branchId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Employee Salaries — 5 entries
        $salaries = [3500, 2500, 2800, 3000, 1800];
        $empSalaries = [];
        foreach ($employees as $idx => $emp) {
            $empSalaries[] = [
                'user_id'    => $emp['id'],
                'salary_type'=> 'monthly',
                'basic_salary' => $salaries[$idx],
                'hourly_rate'  => round($salaries[$idx] / 22 / 8, 2),
                'daily_rate'   => round($salaries[$idx] / 22, 2),
                'allowance'    => 200,
                'transportation_allowance' => 100,
                'meal_allowance' => 100,
                'housing_allowance' => 0,
                'sss_enabled' => 1,
                'philhealth_enabled' => 1,
                'pagibig_enabled' => 1,
                'tax_enabled' => 1,
                'effective_date' => '2024-01-01',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('employee_salaries')->insertOrIgnore($empSalaries);

        // Employee Schedules — 5 employees × 5 working days = 25 entries
        $empSchedules = [];
        foreach ($employees as $emp) {
            for ($day = 1; $day <= 5; $day++) {
                $empSchedules[] = [
                    'user_id'     => $emp['id'],
                    'day_of_week' => $day,
                    'start_time'  => '08:00:00',
                    'end_time'    => '17:00:00',
                    'break_start' => '12:00:00',
                    'break_end'   => '13:00:00',
                    'is_rest_day' => 0,
                    'is_active'   => 1,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }
        DB::table('employee_schedules')->insertOrIgnore($empSchedules);

        // Attendance Records — 10 days × 5 employees = 50 entries
        $attendanceRecords = [];
        $attendanceDate = Carbon::now()->subDays(10);
        for ($day = 0; $day < 10; $day++) {
            $recordDate = $attendanceDate->copy()->addDays($day);
            foreach ($employees as $idx => $emp) {
                $isLate = ($day + $idx) % 4 === 0;
                $isAbsent = ($day + $idx) % 7 === 0;
                $clockIn = $recordDate->copy()->setTime(8, $isLate ? 15 + $idx : 0, 0);
                $clockOut = $isAbsent ? null : $recordDate->copy()->setTime(17, $idx * 2, 0);

                $attendanceRecords[] = [
                    'user_id'        => $emp['id'],
                    'date'           => $recordDate->format('Y-m-d'),
                    'clock_in'       => $isAbsent ? null : $clockIn->format('Y-m-d H:i:s'),
                    'clock_out'      => $clockOut ? $clockOut->format('Y-m-d H:i:s') : null,
                    'total_hours'    => $isAbsent ? 0 : 8.00,
                    'regular_hours'  => $isAbsent ? 0 : 8.00,
                    'overtime_hours' => ($day + $idx) % 3 === 0 ? 1.50 : 0,
                    'late_minutes'   => $isLate ? 15 + $idx : 0,
                    'status'         => $isAbsent ? 'absent' : ($isLate ? 'late' : 'present'),
                    'source'         => 'manual',
                    'created_by'     => 1,
                    'created_at'     => $recordDate,
                    'updated_at'     => $recordDate,
                ];
            }
        }
        DB::table('attendance_records')->insertOrIgnore($attendanceRecords);

        // Payroll Periods — 3 entries (last 3 semi-monthly periods)
        $payrollPeriods = [];
        $periodData = [
            ['1st Half Jan 2025', '2025-01-01', '2025-01-15', '2025-01-20', 'paid'],
            ['2nd Half Jan 2025', '2025-01-16', '2025-01-31', '2025-02-05', 'paid'],
            ['1st Half Feb 2025', '2025-02-01', '2025-02-15', '2025-02-20', 'approved'],
        ];

        foreach ($periodData as $idx => $pd) {
            $periodDate = Carbon::parse($pd[1]);
            $totalGross = 0;
            $totalDeductions = 0;
            $totalNet = 0;

            // Calculate totals for 5 employees
            foreach ($salaries as $sal) {
                $gross = $sal / 2; // semi-monthly
                $deductions = $gross * 0.09; // SSS + PhilHealth + PagIBIG + Tax approx
                $net = $gross - $deductions;
                $totalGross += $gross;
                $totalDeductions += $deductions;
                $totalNet += $net;
            }

            $payrollPeriods[] = [
                'name'             => $pd[0],
                'start_date'       => $pd[1],
                'end_date'         => $pd[2],
                'pay_date'         => $pd[3],
                'status'           => $pd[4],
                'branch_id'        => $branchId,
                'total_gross'      => round($totalGross, 2),
                'total_deductions' => round($totalDeductions, 2),
                'total_net'        => round($totalNet, 2),
                'employee_count'   => 5,
                'created_by'       => 1,
                'approved_by'      => $idx < 2 ? 1 : null,
                'approved_at'      => $idx < 2 ? Carbon::parse($pd[3]) : null,
                'created_at'       => $periodDate,
                'updated_at'       => $periodDate,
            ];
        }
        DB::table('payroll_periods')->insertOrIgnore($payrollPeriods);

        // Payroll Records — 3 periods × 5 employees = 15 entries
        $payrollRecords = [];
        $periodIdStart = 1; // Assuming these are the first payroll periods
        foreach ($periodData as $pIdx => $pd) {
            foreach ($employees as $eIdx => $emp) {
                $semiMonthlySalary = $salaries[$eIdx] / 2;
                $sss = $semiMonthlySalary * 0.045;
                $philhealth = $semiMonthlySalary * 0.025;
                $pagibig = $semiMonthlySalary * 0.02;
                $tax = $semiMonthlySalary * 0.02;
                $totalDeductions = $sss + $philhealth + $pagibig + $tax;
                $grossPay = $semiMonthlySalary + 150; // allowance
                $netPay = $grossPay - $totalDeductions;

                $payrollRecords[] = [
                    'payroll_period_id' => $periodIdStart + $pIdx,
                    'user_id'           => $emp['id'],
                    'days_worked'       => 10,
                    'regular_hours'     => 80.00,
                    'overtime_hours'    => 3.50,
                    'basic_pay'         => round($semiMonthlySalary, 2),
                    'overtime_pay'      => round($semiMonthlySalary / 22 / 8 * 1.5 * 3.5, 2),
                    'allowances'        => 150.00,
                    'gross_pay'         => round($grossPay, 2),
                    'sss_contribution'  => round($sss, 2),
                    'philhealth_contribution' => round($philhealth, 2),
                    'pagibig_contribution' => round($pagibig, 2),
                    'tax_withholding'   => round($tax, 2),
                    'total_deductions'  => round($totalDeductions, 2),
                    'net_pay'           => round($netPay, 2),
                    'status'            => $pd[4] === 'paid' ? 'paid' : 'approved',
                    'created_at'        => Carbon::parse($pd[1]),
                    'updated_at'        => Carbon::parse($pd[1]),
                ];
            }
        }
        DB::table('payroll_records')->insertOrIgnore($payrollRecords);

        // Payroll Deductions — 5 entries (loans/advances for employees)
        $deductionTypes = ['loan', 'cash_advance', 'loan', 'cash_advance', 'loan'];
        $deductionAmounts = [1000, 500, 2000, 300, 1500];
        $monthlyDeductions = [100, 50, 200, 75, 150];

        $payrollDeductions = [];
        foreach ($employees as $idx => $emp) {
            $payrollDeductions[] = [
                'user_id'           => $emp['id'],
                'deduction_type'    => $deductionTypes[$idx],
                'description'       => $deductionTypes[$idx] === 'loan' ? 'Personal loan repayment' : 'Cash advance',
                'amount'            => $deductionAmounts[$idx],
                'monthly_deduction' => $monthlyDeductions[$idx],
                'remaining_balance' => $deductionAmounts[$idx] - $monthlyDeductions[$idx] * 2,
                'start_date'        => '2025-01-01',
                'end_date'          => $deductionTypes[$idx] === 'loan' ? '2025-06-30' : null,
                'status'            => 'active',
                'notes'             => 'Auto-deducted from payroll',
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }
        DB::table('payroll_deductions')->insertOrIgnore($payrollDeductions);

        // Leave Requests — 10 entries
        $leaveTypes = ['vacation', 'sick', 'emergency', 'vacation', 'sick', 'unpaid', 'emergency', 'vacation', 'sick', 'unpaid'];
        $leaveStatuses = ['approved', 'approved', 'rejected', 'pending', 'approved', 'pending', 'approved', 'approved', 'pending', 'rejected'];

        $leaveRequests = [];
        for ($i = 0; $i < 10; $i++) {
            $empIdx = $i % 5;
            $startDate = Carbon::now()->addDays($i * 3 + 1);
            $endDate = $startDate->copy()->addDays(1 + ($i % 3));

            $leaveRequests[] = [
                'user_id'     => $employees[$empIdx]['id'],
                'leave_type'  => $leaveTypes[$i],
                'start_date'  => $startDate->format('Y-m-d'),
                'end_date'    => $endDate->format('Y-m-d'),
                'days_count'  => 1 + ($i % 3),
                'is_paid'     => $leaveTypes[$i] !== 'unpaid',
                'status'      => $leaveStatuses[$i],
                'reason'      => ['Family vacation', 'Medical appointment', 'Family emergency', 'Annual leave', 'Flu symptoms', 'Personal matters', 'Urgent errand', 'Holiday trip', 'Dental procedure', 'Unpaid leave for personal reasons'][$i],
                'remarks'     => $leaveStatuses[$i] === 'approved' ? 'Approved by management' : ($leaveStatuses[$i] === 'rejected' ? 'Insufficient coverage' : null),
                'approved_by' => $leaveStatuses[$i] === 'approved' ? 1 : null,
                'approved_at' => $leaveStatuses[$i] === 'approved' ? $now : null,
                'created_at'  => $startDate->copy()->subDays(7),
                'updated_at'  => $startDate->copy()->subDays(7),
            ];
        }
        DB::table('leave_requests')->insertOrIgnore($leaveRequests);

        // Payroll Holidays — 5 entries
        $holidays = [
            ['New Year\'s Day', '2025-01-01', 'regular', 2.0],
            ['Labor Day', '2025-05-01', 'regular', 2.0],
            ['Independence Day', '2025-07-04', 'regular', 2.0],
            ['Christmas Day', '2025-12-25', 'regular', 2.0],
            ['Company Anniversary', '2025-06-15', 'special', 1.3],
        ];

        $payrollHolidays = [];
        foreach ($holidays as $h) {
            $payrollHolidays[] = [
                'name'          => $h[0],
                'date'          => $h[1],
                'type'          => $h[2],
                'pay_multiplier'=> $h[3],
                'is_recurring'  => 1,
                'branch_id'     => $branchId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }
        DB::table('payroll_holidays')->insertOrIgnore($payrollHolidays);

        // tbl_holidays — 5 entries (for the calendar view)
        $tblHolidays = [];
        foreach ($holidays as $h) {
            $tblHolidays[] = [
                'title'       => $h[0],
                'date'        => $h[1],
                'description' => $h[2] . ' holiday - ' . $h[0] . ' (Pay multiplier: ' . $h[3] . 'x)',
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }
        DB::table('tbl_holidays')->insertOrIgnore($tblHolidays);

        // ───────────────────────────────────────────────
        // 11. EMAIL LOGS — 10 entries
        // ───────────────────────────────────────────────
        $emailLogs = [];
        $emailSubjects = ['Job Card Created', 'Invoice Generated', 'Service Completed', 'Payment Received', 'Quotation Sent', 'Welcome Email', 'Service Reminder', 'Wash Bay Complete', 'Gate Pass Issued', 'Monthly Summary'];
        for ($i = 0; $i < 10; $i++) {
            $logDate = Carbon::now()->subDays(20 - $i * 2);
            $emailLogs[] = [
                'recipient_email' => $customerNames[$i % 10][2],
                'subject'         => $emailSubjects[$i],
                'content'         => 'This is a demo email notification for ' . $emailSubjects[$i],
                'created_at'      => $logDate,
                'updated_at'      => $logDate,
            ];
        }
        DB::table('email_logs')->insertOrIgnore($emailLogs);
    }
}
