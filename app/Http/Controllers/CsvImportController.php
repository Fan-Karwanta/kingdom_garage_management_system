<?php

namespace App\Http\Controllers;

use App\EmailLog;
use App\Service;
use App\User;
use App\Vehicle;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Mail;
use URL;

class CsvImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        return view('csv_import.list');
    }

    // Users csv upload
    public function uploadUsersCsv(Request $request)
    {
        try {
            $request->validate([
                'csv_file_users' => 'required|mimes:csv,txt|max:2048',
            ]);

            $file = $request->file('csv_file_users');
            $csvFile = fopen($file->getPathname(), 'r');
            $header = fgetcsv($csvFile);

            if (isset($header[0])) {
                $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
            }

            $expectedColumns = ['Firstname', 'Lastname', 'Role', 'Email', 'Password', 'Mobile', 'Gender'];
            foreach ($expectedColumns as $column) {
                if (! in_array($column, array_map('trim', $header))) {
                    fclose($csvFile);

                    return redirect()->back()->with('error', "Missing column '$column' in CSV file.");
                }
            }

            $headerMap = [];
            foreach ($header as $index => $columnName) {
                $headerMap[strtolower(trim($columnName))] = $index;
            }

            $roleMapping = [
                1 => 2, // Customer -> role_id 2
                2 => 3, // Employee -> role_id 3
                3 => 4, // Support Staff -> role_id 4
                4 => 5,  // Accountant -> role_id 5
            ];

            $settings = DB::table('tbl_settings')->where('id', 1)->first();
            $countryId = $settings->country_id ?? 1;
            $admin = DB::table('users')->where('id', 1)->first();
            $language = $admin->language ?? 'en';
            $timezone = $admin->timezone ?? 'UTC';

            $validUsers = [];
            $errors = [];
            $line = 2;

            while (($record = fgetcsv($csvFile)) !== false) {
                if (empty($record[0]) && count(array_filter($record)) === 0) {
                    $line++;

                    continue;
                }

                $firstname = trim($record[$headerMap['firstname']] ?? '');
                $lastname = trim($record[$headerMap['lastname']] ?? '');
                $role = trim($record[$headerMap['role']] ?? '');
                $email = trim($record[$headerMap['email']] ?? '');
                $password = trim($record[$headerMap['password']] ?? '');
                $mobile = trim($record[$headerMap['mobile']] ?? '');
                $gender = trim($record[$headerMap['gender']] ?? '');
                $address = trim($record[$headerMap['address']] ?? '');

                if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($role)) {
                    $errors[] = "Line $line: Required fields cannot be empty";
                    $line++;

                    continue;
                }

                if (! array_key_exists((int) $role, $roleMapping)) {
                    $errors[] = "Line $line: Invalid role '$role'. Allowed numbers: 1 (Customer), 2 (Employee), 3 (Support Staff), 4 (Accountant)";
                    $line++;

                    continue;
                }
                $validator = Validator::make([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'password' => $password,
                    'mobile' => $mobile,
                ], [
                    'firstname' => 'required|regex:/^[\p{L}\p{M}\s\-\'\.]+$/u|max:50',
                    'lastname' => 'required|regex:/^[\p{L}\p{M}\s\-\'\.]+$/u|max:50',
                    'password' => 'required|min:6|max:12|regex:/^(?=.*[a-zA-Z\p{L}])(?=.*\d).+$/u',
                    'mobile' => 'required|min:6|max:16|regex:/^[- +()]*[0-9][- +()0-9]*$/',
                ], [
                    'firstname.regex' => trans('message.First name is only alphabets and space.'),
                    'firstname.max' => trans('message.First name should not more than 50 characters.'),
                    'lastname.regex' => trans('message.Last name is only alphabets and space.'),
                    'lastname.max' => trans('message.Last name should not more than 50 character.'),
                    'password.regex' => trans('message.Password must be combination of letters and numbers.'),
                    'password.min' => trans('message.Password length minimum 6 character.'),
                    'password.max' => trans('message.Password length maximum 12 character.'),
                    'mobile.min' => trans('message.Contact number must be at least 6 digits.'),
                    'mobile.max' => trans('message.Contact number must be at most 16 digits.'),
                    'mobile.regex' => trans('message.Contact number must be number, plus, minus and space only.'),
                ]);

                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $error) {
                        $errors[] = "Line $line: $error";
                    }
                    $line++;

                    continue;
                }

                $roleId = $roleMapping[(int) $role];

                if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Line $line: Invalid email format: $email";
                    $line++;

                    continue;
                }

                $emailExists = DB::table('users')->where('soft_delete', 0)->where('email', $email)->exists();
                if ($emailExists) {
                    $errors[] = "Line $line: Email already exists: $email";
                    $line++;

                    continue;
                }

                if (! empty($mobile)) {
                    $mobileExists = DB::table('users')->where('soft_delete', 0)->where('mobile_no', $mobile)->exists();
                    if ($mobileExists) {
                        $errors[] = "Line $line: Mobile number already exists: $mobile";
                        $line++;

                        continue;
                    }
                }

                $genderValue = strtolower($gender) === 'female' ? 1 : 0;
                $rolename = $roleId == 2 ? 'customer' : ($roleId == 3 ? 'employee' : ($roleId == 4 ? 'supportstaff' : 'accountant'));
                $userData = [
                    'name' => $firstname,
                    'lastname' => $lastname,
                    'gender' => $genderValue,
                    'email' => $email,
                    'password' => bcrypt($password),
                    'mobile_no' => $mobile,
                    'address' => $address,
                    'image' => 'avtar.png',
                    'join_date' => ($roleId == 3) ? now() : null,
                    'designation' => ($roleId == 3) ? 'employee' : null,
                    'country_id' => $countryId,
                    'role' => $rolename,
                    'role_id' => $roleId,
                    'language' => $language,
                    'timezone' => $timezone,
                    'soft_delete' => 0,
                    'branch_id' => ($roleId == 2) ? null : 1,
                    'create_by' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                // Send registration email
                try {
                    $logo = DB::table('tbl_settings')->first();
                    $systemname = $logo->system_name;
                    $emailformats = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'User_registration')->first();

                    if ($emailformats && $emailformats->is_send == 0) {
                        $emailformat = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'User_registration')->first();
                        $mail_format = $emailformat->notification_text;
                        $notification_label = $emailformat->notification_label;
                        $mail_subjects = $emailformat->subject;
                        $mail_send_from = $emailformat->send_from;

                        $search1 = ['{ system_name }'];
                        $replace1 = [$systemname];
                        $mail_sub = str_replace($search1, $replace1, $mail_subjects);
                        Log::info('User Registration Email Sent');
                        $systemlink = URL::to('/');
                        $search = ['{ system_name }', '{ user_name }', '{ email }', '{ Password }', '{ system_link }'];
                        $replace = [$systemname, $userData['name'], $userData['email'], $password, $systemlink];
                        $email_content = str_replace($search, $replace, $mail_format);

                        $redirect_url = url('/customer/list');

                        // Render Blade template with all required variables
                        $blade_view = View::make('mail.template', [
                            'notification_label' => $notification_label,
                            'email_content' => $email_content,
                            'redirect_url' => $redirect_url,
                            'system_link' => $systemlink,
                        ])->render();
                        $email = $userData['email'];
                        // Send email
                        try {
                            Mail::send([], [], function ($message) use ($email, $mail_sub, $blade_view, $mail_send_from) {
                                $message->to($email)->subject($mail_sub);
                                $message->from($mail_send_from);
                                $message->html($blade_view, 'text/html');
                            });
                        } catch (\Exception $e) {
                            Log::error('Error sending email: '.$e->getMessage());
                        }

                        // Store email log entry
                        $emailLog = new EmailLog;
                        $emailLog->recipient_email = $userData['email'];
                        $emailLog->subject = $mail_sub;
                        $emailLog->content = $email_content;
                        $emailLog->save();
                    }
                } catch (\Exception $e) {
                    Log::error('Email sending failed for user '.$userData['email'].': '.$e->getMessage());
                    // Continue with the next user even if email fails
                }

                $validUsers[] = $userData;
                $line++;
            }

            fclose($csvFile);

            if (! empty($errors)) {
                return redirect()->back()->with('error', implode('<br>', $errors));
            }

            DB::beginTransaction();
            try {
                foreach ($validUsers as $userData) {
                    $userId = DB::table('users')->insertGetId($userData);
                    DB::table('role_users')->insert([
                        'user_id' => $userId,
                        'role_id' => $userData['role_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::commit();

                return redirect()->back()->with('message', 'Users imported successfully!');
            } catch (\Exception $e) {
                DB::rollback();

                return redirect()->back()->with('error', 'Error processing CSV: '.$e->getMessage());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error uploading file: '.$e->getMessage());
        }
    }

    public function downloadUserSampleCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_users.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Firstname',
                'Lastname',
                'Role',
                'Email',
                'Password',
                'Mobile',
                'Gender',
                'Address',
            ]);

            // Add sample data
            fputcsv($file, [
                'John',
                'Doe',
                1,
                'johndoe123@example.com',
                'johnpass123',
                '1100110011',
                'Male',
                'Diamond City, New York',
            ]);

            // Add another sample row for Employee
            fputcsv($file, [
                'Kelly',
                'Martin',
                2,
                'kellymartin456@example.com',
                'kellypass456',
                '2200220022',
                'Female',
                'Diamond City, New York',
            ]);

            // Add sample for Support Staff
            fputcsv($file, [
                'Elon',
                'Doe',
                3,
                'elondoe123@example.com',
                'elonpass123',
                '3300330033',
                'Male',
                'Diamond Street, New York',
            ]);

            // Add sample for Accountant
            fputcsv($file, [
                'Milo',
                'Roy',
                4,
                'miloroy456@example.com',
                'milopass456',
                '4400440044',
                'Male',
                'Diamond Street, New York',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function uploadVehiclesCsv(Request $request)
    {
        try {
            $request->validate([
                'csv_file_vehicles' => 'required|mimes:csv,txt|max:2048',
            ]);

            $file = $request->file('csv_file_vehicles');
            $csvFile = fopen($file->getPathname(), 'r');
            $header = fgetcsv($csvFile);

            if (isset($header[0])) {
                $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
            }

            // Define expected columns
            $expectedColumns = ['Vehicle Type', 'Vehicle Brand', 'Model Name', 'Feul Type', 'Number Plate', 'Customer Email'];
            foreach ($expectedColumns as $column) {
                if (! in_array($column, array_map('trim', $header))) {
                    fclose($csvFile);

                    return redirect()->back()->with('error', "Missing column '$column' in CSV file.");
                }
            }

            $headerMap = [];
            foreach ($header as $index => $columnName) {
                $headerMap[strtolower(trim($columnName))] = $index;
            }

            // Pre-load all lookup tables with lowercase keys for case-insensitive matching
            $vehicleTypes = DB::table('tbl_vehicle_types')
                ->where('soft_delete', 0)
                ->get(['id', 'vehicle_type'])
                ->mapWithKeys(function ($item) {
                    return [strtolower($item->vehicle_type) => $item->id];
                })
                ->toArray();

            $fuelTypes = DB::table('tbl_fuel_types')
                ->where('soft_delete', 0)
                ->get(['id', 'fuel_type'])
                ->mapWithKeys(function ($item) {
                    return [strtolower($item->fuel_type) => $item->id];
                })
                ->toArray();

            $vehicleBrands = DB::table('tbl_vehicle_brands')
                ->where('soft_delete', 0)
                ->get(['id', 'vehicle_type_id', 'vehicle_brand'])
                ->groupBy('vehicle_type_id')
                ->toArray();

            $modelNames = DB::table('tbl_model_names')
                ->where('soft_delete', 0)
                ->get(['id', 'brand_id', 'model_name'])
                ->groupBy('brand_id')
                ->toArray();

            $users = DB::table('users')
                ->where('soft_delete', 0)
                ->get(['id', 'email'])
                ->mapWithKeys(function ($item) {
                    return [strtolower($item->email) => $item->id];
                })
                ->toArray();
            Log::info($users);
            $validVehicles = [];
            $errors = [];
            $line = 2;

            while (($record = fgetcsv($csvFile)) !== false) {
                if (empty($record[0]) && count(array_filter($record)) === 0) {
                    $line++;

                    continue;
                }

                $vehicleTypeName = trim($record[$headerMap['vehicle type']] ?? '');
                $vehicleBrandName = trim($record[$headerMap['vehicle brand']] ?? '');
                $modelName = trim($record[$headerMap['model name']] ?? '');
                $fuelTypeName = trim($record[$headerMap['feul type']] ?? '');
                $numberPlate = trim($record[$headerMap['number plate']] ?? '');
                $customerEmail = trim($record[$headerMap['customer email']] ?? '');

                // Validate required fields
                if (empty($vehicleTypeName) || empty($vehicleBrandName) || empty($modelName) ||
                    empty($fuelTypeName) || empty($numberPlate) || empty($customerEmail)) {
                    $errors[] = "Line $line: Required fields cannot be empty";
                    $line++;

                    continue;
                }

                // Validate and get vehicle type ID (case-insensitive)
                $vehicleTypeId = $vehicleTypes[strtolower($vehicleTypeName)] ?? null;
                if (! $vehicleTypeId) {
                    $errors[] = "Line $line: Vehicle type '$vehicleTypeName' not found in the system. Please add it first.";
                    $line++;

                    continue;
                }

                // Find vehicle brand ID that matches both name and vehicle type
                $vehicleBrandId = null;
                if (isset($vehicleBrands[$vehicleTypeId])) {
                    foreach ($vehicleBrands[$vehicleTypeId] as $brand) {
                        if (strtolower($brand->vehicle_brand) === strtolower($vehicleBrandName)) {
                            $vehicleBrandId = $brand->id;
                            break;
                        }
                    }
                }

                // If brand not found, create it
                if (! $vehicleBrandId) {
                    $vehicleBrandId = DB::table('tbl_vehicle_brands')->insertGetId([
                        'vehicle_type_id' => $vehicleTypeId,
                        'vehicle_brand' => $vehicleBrandName,
                        'soft_delete' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Find model name ID that matches both name and brand
                $modelId = null;
                if (isset($modelNames[$vehicleBrandId])) {
                    foreach ($modelNames[$vehicleBrandId] as $model) {
                        if (strtolower($model->model_name) === strtolower($modelName)) {
                            $modelId = $model->id;
                            break;
                        }
                    }
                }

                // If model not found, create it
                if (! $modelId) {
                    $modelId = DB::table('tbl_model_names')->insertGetId([
                        'brand_id' => $vehicleBrandId,
                        'model_name' => $modelName,
                        'soft_delete' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Validate and get fuel type ID
                $fuelId = $fuelTypes[strtolower($fuelTypeName)] ?? null;
                if (! $fuelId) {
                    $errors[] = "Line $line: Fuel type '$fuelTypeName' not found in the system. Please add it first.";
                    $line++;

                    continue;
                }

                // Validate and get customer ID
                $customerId = $users[strtolower($customerEmail)] ?? null;
                if (! $customerId) {
                    $errors[] = "Line $line: Customer with email '$customerEmail' not found in the system.";
                    $line++;

                    continue;
                }

                // Check for duplicate number plate
                $existingVehicle = DB::table('tbl_vehicles')
                    ->where('number_plate', $numberPlate)
                    ->where('soft_delete', 0)
                    ->exists();

                if ($existingVehicle) {
                    $errors[] = "Line $line: Vehicle with number plate '$numberPlate' already exists.";
                    $line++;

                    continue;
                }

                // Prepare vehicle data
                $vehicleData = [
                    'vehicletype_id' => $vehicleTypeId,
                    'vehiclebrand_id' => $vehicleBrandId,
                    'modelname' => $modelName,
                    'fuel_id' => $fuelId,
                    'number_plate' => $numberPlate,
                    'customer_id' => $customerId,
                    'added_by_service' => 1, // Assuming 1 means added by service
                    'branch_id' => auth()->user()->branch_id ?? 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'soft_delete' => 0,
                ];

                // Add odometer reading if provided
                if (isset($headerMap['odometer reading'])) {
                    $odometerReading = trim($record[$headerMap['odometer reading']] ?? '');
                    if (! empty($odometerReading) && is_numeric($odometerReading)) {
                        $vehicleData['odometereading'] = $odometerReading;
                    }
                }

                $validVehicles[] = $vehicleData;
                $line++;
            }

            fclose($csvFile);

            if (! empty($errors)) {
                return redirect()->back()->with('error', implode(' ', $errors));
            }

            DB::beginTransaction();
            try {
                foreach ($validVehicles as $vehicleData) {
                    DB::table('tbl_vehicles')->insert($vehicleData);
                }

                DB::commit();

                return redirect()->back()->with('message', 'Vehicles imported successfully!');
            } catch (\Exception $e) {
                DB::rollback();

                return redirect()->back()->with('error', 'Error processing CSV: '.$e->getMessage());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error uploading file: '.$e->getMessage());
        }
    }

    public function downloadVehicleSampleCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_vehicle.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Vehicle Type',
                'Vehicle Brand',
                'Model Name',
                'Feul Type',
                'Number Plate',
                'Customer Email',
            ]);

            // Add sample data
            fputcsv($file, [
                'Car',
                'BMW',
                '3 Series',
                'Petrol',
                'GJ01AB1234',
                'johndoe123@example.com',
            ]);

            // Add another sample row for Employee
            fputcsv($file, [
                'Car',
                'Audi',
                'EV6',
                'Diesel',
                'GJ01CD1584',
                'bhaliyaakshay27@gmail.com',
            ]);

            // Add sample for Support Staff
            fputcsv($file, [
                'Bike',
                'Honda',
                'Splender',
                'Petrol',
                'GH1204CD1548',
                'Samuel@gmail.com',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // product csv import
    public function uploadProductsCsv(Request $request)
    {
        $request->validate([
            'csv_file_products' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file_products');
        $csvFile = fopen($file->getPathname(), 'r');
        $header = fgetcsv($csvFile);

        if (isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        }

        $expectedColumns = ['name', 'price', 'manufacturer name', 'suppliers', 'unit of measurement'];
        foreach ($expectedColumns as $column) {
            if (! in_array($column, array_map('trim', $header))) {
                fclose($csvFile);

                return redirect()->back()->with('error', "Missing column '$column' in CSV file.");
            }
        }

        $headerMap = [];
        foreach ($header as $index => $columnName) {
            $headerMap[strtolower(trim($columnName))] = $index;
        }

        $errors = [];
        $successCount = 0;
        $line = 2;

        while (($record = fgetcsv($csvFile)) !== false) {
            if (empty($record[0]) && count(array_filter($record)) === 0) {
                $line++;

                continue;
            }

            $name = trim($record[$headerMap['name']] ?? '');
            $price = trim($record[$headerMap['price']] ?? 0);
            $manufacturer = trim($record[$headerMap['manufacturer name']] ?? '');
            $supplierName = trim($record[$headerMap['suppliers']] ?? '');
            $unitName = trim($record[$headerMap['unit of measurement']] ?? '');

            if (empty($name) || empty($price) || empty($manufacturer) || empty($supplierName) || empty($unitName)) {
                $errors[] = "Line $line: One or more required fields are empty.";
                $line++;

                continue;
            }

            // Generate unique product number
            do {
                $characters = '0123456789';
                $code = 'PR'.substr(str_shuffle($characters), 0, 6);
                $exists = DB::table('tbl_products')->where('product_no', $code)->exists();
            } while ($exists);
            $product_no = $code;

            // Check or insert manufacturer -> tbl_product_types
            $productType = DB::table('tbl_product_types')->where('type', $manufacturer)->where('soft_delete', '!=', 1)->first();
            if (! $productType) {
                $productTypeId = DB::table('tbl_product_types')->insertGetId([
                    'type' => $manufacturer,
                    'soft_delete' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $productTypeId = $productType->id;
            }

            // Check or insert unit -> tbl_product_units
            $unit = DB::table('tbl_product_units')->where('name', $unitName)->first();
            if (! $unit) {
                $unitId = DB::table('tbl_product_units')->insertGetId([
                    'name' => $unitName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $unitId = $unit->id;
            }

            // Get supplier ID
            $supplier = DB::table('users')->where('company_name', $supplierName)->where('role', 'Supplier')->where('soft_delete', 0)->first();
            if (! $supplier) {
                $errors[] = "Line $line: Supplier '$supplierName' not found. Please add the supplier first.";
                $line++;

                continue;
            }

            // Insert product
            DB::table('tbl_products')->insert([
                'name' => $name,
                'product_no' => $product_no,
                'product_date' => now(),
                'product_image' => 'avtar.png',
                'product_type_id' => $productTypeId,
                'color_id' => 1,
                'price' => $price,
                'supplier_id' => $supplier->id,
                'category' => 1,
                'unit' => $unitId,
                'create_by' => 1,
                'soft_delete' => 0,
                'branch_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $successCount++;
            $line++;
        }

        fclose($csvFile);

        if (! empty($errors)) {
            return redirect()->back()->with([
                'error' => implode('<br>', $errors),
                'success' => "$successCount products imported successfully.",
            ]);
        }

        return redirect()->back()->with('message', 'Products imported successfully.');
    }

    public function downloadProductsSampleCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_product.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'name',
                'price',
                'manufacturer name',
                'suppliers',
                'unit of measurement',
            ]);

            // Add sample data
            fputcsv($file, [
                'Seats',
                '50',
                'Honda',
                'Honda Motors',
                'Numbers',
            ]);

            // Add another sample row for Employee
            fputcsv($file, [
                'Engine',
                '200',
                'Audi',
                'Audi Motors',
                'Sets',
            ]);

            // Add sample for Support Staff
            fputcsv($file, [
                'Indicators',
                '100',
                'Kia',
                'Kia Motors',
                'peases',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
