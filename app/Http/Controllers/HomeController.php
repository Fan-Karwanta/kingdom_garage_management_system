<?php

namespace App\Http\Controllers;

use App\BranchSetting;
use App\BusinessHour;
use App\EmailLog;
use App\Holiday;
use App\JobcardDetail;
use App\Product;
use App\Sale;
use App\Service;
use App\Setting;
use App\User;
use App\Vehicle;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Mail;
use Session;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Dashboard view render with proper data role wise
    public function dashboard()
    {

        // For branching feature current user of branch or admin
        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        // $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

        $Customer = null;
        // $Customer = $Supplier = $employee = $product = $sales = $service = null;

        $Customere = null;
        $serviceevent = $Customer = $Supplier = $employee = $product = $sales = $service = $sale = $sale1 = $sale2 = $vehical = $performance = $holiday = $have_supportstaff = null;
        // $sale1 = $sale2 = $vehical = $performance = $holiday = $have_supportstaff =  null;
        $data = '';
        $one_day = '';
        $two_day = '';
        $more = '';
        $openinghours = '';
        $upcomingservice = null;
        $set_email_send = Session::get('email_sended');

        // timezone in run
        $users = DB::table('users')->where('id', '=', Auth::user()->id)->first();
        $timezone = $users->timezone;

        config(['message.timezone' => $timezone]);
        $currentfirstdate = new Carbon('first day of this month');
        $currentlastdate = new Carbon('last day of this month');

        $startdate = new Carbon('first day of next month');
        $lastdate = new Carbon('last day of next month');

        $nowmonthdate = $startdate->format('Y-m-d');
        $nowmonthdate1 = $lastdate->format('Y-m-d');
        $nowdate = date('Y-m-d');
        $m1 = $startdate->format('M');
        $y1 = $startdate->format('Y');

        $laststart = new Carbon('first day of last month');
        $lastend = new Carbon('last day of last month');
        $laststart1 = $laststart->format('Y-m-d');
        $lastend1 = $lastend->format('Y-m-d');
        $m = $laststart->format('m');
        $y = $laststart->format('Y');

        $admin = DB::table('users')->where('role', '=', 'admin')->first();
        $firstname = $admin->name;
        $email = $admin->email;
        $monthservice = DB::select("SELECT * FROM tbl_services where (done_status=1) and (service_date BETWEEN '".$laststart1."' AND  '".$lastend1."')");

        $logo = DB::table('tbl_settings')->first();
        $systemname = $logo->system_name;
        // Email notification for last monthly service for admin

        if (empty($set_email_send)) {
            $emailformats = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'Monthly_service_notification')->first();
            if ($emailformats->is_send == 0) {
                if ($currentfirstdate == $nowdate) {
                    $emailformat = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'Monthly_service_notification')->first();
                    $mail_format = $emailformat->notification_text;
                    $notification_label = $emailformat->notification_label;
                    $mail_subjects = $emailformat->subject;
                    $mail_send_from = $emailformat->send_from;
                    $search1 = ['{ system_name }', '{ month }', '{ year }'];
                    $replace1 = [$systemname, $m, $y];
                    $mail_sub = str_replace($search1, $replace1, $mail_subjects);

                    $message = '<html><body>';
                    $message .= '<br/><table rules="all" width="100%"style="border-color: #666;" border="1" cellpadding="10">';

                    $message .= '<table class="table table-bordered" width="100%"  style="border-collapse:collapse;">
					<h4 align="center" style="margin:0px;">Last Month Service List</h4></table><hr/>';

                    $message .= '<table class="table table-bordered" width="100%"  style="border-collapse:collapse;">';
                    $message .= '<tr><th align="left">#</th> <th align="left"><b>Jobcard Number</b></th> <th align="left"><b>Customer Name</b></th> <th align="left"><b>Vehicle Name</b></th> <th align="left"><b>Service Date</b></th> <th align="left"><b>AssignedTo</b></th> </tr><br/>';

                    if (! empty($monthservice)) {
                        $i = 1;
                        foreach ($monthservice as $services) {
                            $message .= '<tr><td align="left">'.$i++.'</td><td align="left">'.$services->job_no.'</td>
											<td align="left">'.getCustomerName($services->customer_id).'</td>
											<td align="left">'.getModelName($services->vehicle_id).'</td>
											<td align="left">'.date('Y-m-d', strtotime($services->service_date)).' </td>
											<td align="left">'.getAssignTo($services->assign_to).'</td></tr> ';
                        }
                    }
                    $message .= '</table><hr/>';
                    $message .= '</table><br/><br/>';
                    $message .= '</body></html>';

                    $search = ['{ system_name }', '{ admin }', '{ service_list }'];
                    $replace = [$systemname, $firstname, $message];

                    $email_content = str_replace($search, $replace, $mail_format);
                    $redirect_url = url('/');
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
                        \Log::error('Error sending email: '.$e->getMessage());
                    }
                    $messageBody = 'Your monthly service list is ready.Please check mail for more details.';
                    $receiverNumber = getMobileNoWithCode($admin->id);
                    if (\Schema::hasTable('sms_settings') && View::exists('smsaddon::sms_setting')) {
                        \Illuminate\Support\Facades\Http::post(route('smsaddon.send-sms'), ['messageBody' => $messageBody, 'receiver_mobile' => $receiverNumber]);
                    }
                    // Store email log entry
                    $emailLog = new EmailLog;
                    $emailLog->recipient_email = $email ?? null;
                    $emailLog->subject = $mail_sub;
                    $emailLog->content = $email_content;
                    $emailLog->save();
                }
            }
            // next month service notifcation for admin, employee,customer
            $emailformats = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'Service Due')->first();
            if ($emailformats->is_send == 0) {
                if ($currentfirstdate == $nowdate) {
                    $emailformat = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'Service Due')->first();

                    $mail_format = $emailformat->notification_text;
                    $mail_subjects = $emailformat->subject;
                    $mail_send_from = $emailformat->send_from;
                    $search1 = ['{ month_week }', '{ system_name }', '{ month }', '{ year }'];
                    $replace1 = ['Month', $systemname, $m1, $y1];
                    $mail_sub = str_replace($search1, $replace1, $mail_subjects);

                    $message = '<html><body>';
                    $message .= '<br/><table rules="all" width="100%"style="border-color: #666;" border="1" cellpadding="10">';

                    $message .= '<table class="table table-bordered" width="100%"  style="border-collapse:collapse;">
					<h4 align="center" style="margin:0px;">Next Month Service List</h4></table><hr/>';

                    $message .= '<table class="table table-bordered" width="100%"  style="border-collapse:collapse;">';
                    $message .= '<tr><th align="left">#</th> <th align="left"><b>Coupon Number</b></th> <th align="left"><b>Customer Name</b></th> <th align="left"><b>Vehicle Name</b></th> <th align="left"><b>Service Date</b></th> <th align="left"><b>AssignedTo</b></th> </tr><br/>';

                    $Upmonthservice = DB::select("SELECT * FROM tbl_services where (done_status=2) and (service_date BETWEEN '".$nowmonthdate."' AND  '".$nowmonthdate1."')");

                    $admin = DB::table('users')->where('role', '=', 'admin')->first();
                    if (! empty($admin)) {
                        if (! empty($Upmonthservice)) {
                            $i = 1;

                            foreach ($Upmonthservice as $services) {
                                $salesid = $services->sales_id;
                                if (! empty(getEmployeeservice($services->assign_to, $salesid, $nowmonthdate, $nowmonthdate1))) {

                                    $message .= '<tr><td align="left">'.$i++.'</td><td align="left">'.$services->job_no.'</td>
												<td align="left">'.getCustomerName($services->customer_id).'</td>
												<td align="left">'.getModelName($services->vehicle_id).'</td>
												<td align="left">'.date('Y-m-d', strtotime($services->service_date)).' </td>
												<td align="left">'.getAssignTo($services->assign_to).'</td></tr> ';
                                }
                            }
                        }
                    }
                    $message .= '</table><hr/>';
                    $message .= '</table><br/><br/>';
                    $message .= '</body></html>';
                    // admin notification
                    $admin = DB::table('users')->where('role', '=', 'admin')->first();
                    if (! empty($admin)) {
                        $search = ['{ system_name }', '{ user_name }', '{ month }', '{ year }', '{ service_list }'];
                        $replace = [$systemname, $firstname, $m1, $y1, $message];

                        $email_content = str_replace($search, $replace, $mail_format);
                        $actual_link = $_SERVER['HTTP_HOST'];
                        $startip = '0.0.0.0';
                        $endip = '255.255.255.255';
                        $data = [
                            'email' => $email,
                            'mail_sub1' => $mail_sub,
                            'email_content1' => $email_content,
                            'emailsend' => $mail_send_from,
                            'monthservice' => $monthservice,
                        ];

                        if (($actual_link == 'localhost' || $actual_link == 'localhost:8080') || ($actual_link >= $startip && $actual_link <= $endip)) {
                            // local format email
                            try {
                                $data1 = Mail::send('dashboard.monthlyservice', $data, function ($message) use ($data) {

                                    $message->from($data['emailsend'], 'noreply');

                                    $message->to($data['email'])->subject($data['mail_sub1']);
                                });
                            } catch (\Exception $e) {
                                \Log::info('Error sending email: '.$e->getMessage());
                            }

                        } else {
                            // Live format email

                            $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
                            $headers .= 'From:'.$mail_send_from."\r\n";

                            $data = mail($email, $mail_sub, $email_content, $headers);
                        }
                        // service due Notification send to user
                        $messageBody = 'Your monthly service list is ready.Please check mail for more details.';
                        $receiverNumber = getMobileNoWithCode($admin->id);
                        if (\Schema::hasTable('sms_settings') && View::exists('smsaddon::sms_setting')) {
                            \Illuminate\Support\Facades\Http::post(route('smsaddon.send-sms'), ['messageBody' => $messageBody, 'receiver_mobile' => $receiverNumber]);
                        }
                        $emailLog = new EmailLog;
                        $emailLog->recipient_email = $email ?? null;
                        $emailLog->subject = $mail_sub;
                        $emailLog->content = $email_content;
                        $emailLog->save();
                    }
                    // Employee notification
                    if (! empty($Upmonthservice)) {
                        $i = 1;
                        foreach ($Upmonthservice as $services) {
                            $assign_to = $services->assign_to;
                            $customer_id = $services->customer_id;

                            $emplo = DB::table('users')->where([['id', '=', $assign_to], ['role', '=', 'employee']])->first();
                            if (! empty($emplo)) {
                                $email1 = $emplo->email;
                                $name = $emplo->name;

                                $search = ['{ system_name }', '{ user_name }', '{ month }', '{ year }', '{ service_list }'];
                                $replace = [$systemname, $name, $m1, $y1, $message];

                                $email_content = str_replace($search, $replace, $mail_format);
                                $actual_link = $_SERVER['HTTP_HOST'];
                                $startip = '0.0.0.0';
                                $endip = '255.255.255.255';

                                if (($actual_link == 'localhost' || $actual_link == 'localhost:8080') || ($actual_link >= $startip && $actual_link <= $endip)) {
                                    // local format email
                                    $data = [
                                        'email' => $email1,
                                        'mail_sub1' => $mail_sub,
                                        'email_content1' => $email_content,
                                        'emailsend' => $mail_send_from,
                                        'monthservice' => $monthservice,
                                    ];
                                    try {
                                        $data1 = Mail::send('dashboard.monthlyservice', $data, function ($message) use ($data) {

                                            $message->from($data['emailsend'], 'noreply');

                                            $message->to($data['email'])->subject($data['mail_sub1']);
                                        });
                                    } catch (\Exception $e) {
                                        \Log::info('Error sending email: '.$e->getMessage());
                                    }

                                } else {
                                    // Live format email

                                    $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
                                    $headers .= 'From:'.$mail_send_from."\r\n";

                                    $data = mail($email1, $mail_sub, $email_content, $headers);
                                }
                                $emailLog = new EmailLog;
                                $emailLog->recipient_email = $email1 ?? null;
                                $emailLog->subject = $mail_sub;
                                $emailLog->content = $email_content;
                                $emailLog->save();
                            }
                            $custo = DB::table('users')->where([['id', '=', $customer_id], ['role', '=', 'Customer']])->first();
                            if (! empty($custo)) {
                                $cemail1 = $custo->email;
                                $cname = $custo->name;

                                $search = ['{ system_name }', '{ user_name }', '{ month }', '{ year }', '{ service_list }'];
                                $replace = [$systemname, $cname, $m1, $y1, $message];

                                $email_content = str_replace($search, $replace, $mail_format);
                                $actual_link = $_SERVER['HTTP_HOST'];
                                $startip = '0.0.0.0';
                                $endip = '255.255.255.255';

                                if (($actual_link == 'localhost' || $actual_link == 'localhost:8080') || ($actual_link >= $startip && $actual_link <= $endip)) {
                                    // local format email
                                    $data = [
                                        'email' => $cemail1,
                                        'mail_sub1' => $mail_sub,
                                        'email_content1' => $email_content,
                                        'emailsend' => $mail_send_from,
                                        'monthservice' => $monthservice,
                                    ];
                                    try {
                                        $data1 = Mail::send('dashboard.monthlyservice', $data, function ($message) use ($data) {

                                            $message->from($data['emailsend'], 'noreply');

                                            $message->to($data['email'])->subject($data['mail_sub1']);
                                        });
                                    } catch (\Exception $e) {
                                        \Log::info('Error sending email: '.$e->getMessage());
                                    }

                                } else {
                                    // Live format email

                                    $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
                                    $headers .= 'From:'.$mail_send_from."\r\n";

                                    $data = mail($cemail1, $mail_sub, $email_content, $headers);
                                }
                                $emailLog = new EmailLog;
                                $emailLog->recipient_email = $cemail1 ?? null;
                                $emailLog->subject = $mail_sub;
                                $emailLog->content = $email_content;
                                $emailLog->save();
                            }
                        }
                    }
                }
            }
            // Email notification weekly in Employee
            $startdate = new Carbon('first day of this month');
            $m = $startdate->format('m');
            $y = $startdate->format('Y');
            $nowdate = date('Y-m-d');

            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6 - $day).' days'));
            // $week_end1 = date('Y-m-d', strtotime('+'.(7-$day).' days'));
            // var_dump();
            // exit;
            $logo = DB::table('tbl_settings')->first();
            $systemname = $logo->system_name;
            $employee = DB::table('users')->where('role', '=', 'employee')->get()->toArray();
            foreach ($employee as $employees) {
                $firstname = $employees->name;
                $emp_id = $employees->id;
                $email = $employees->email;

                $weekservice = DB::select("SELECT * FROM tbl_services where (done_status=1) and (assign_to='$emp_id') and(service_date BETWEEN '".$week_start."' AND  '".$week_end."')");
                $emailformats = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'weekly_servicelist')->first();
                if ($emailformats->is_send == 0) {
                    if ($week_start == $nowdate) {
                        $emailformat = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'weekly_servicelist')->first();
                        $mail_format = $emailformat->notification_text;
                        $notification_label = $emailformat->notification_label;
                        $mail_subjects = $emailformat->subject;
                        $mail_send_from = $emailformat->send_from;
                        $search1 = ['{ system_name }', '{ month }', '{ year }'];
                        $replace1 = [$systemname, $m, $y];
                        $mail_sub = str_replace($search1, $replace1, $mail_subjects);

                        // employee in service list

                        $message = '<html><body>';
                        $message .= '<br/><table rules="all" width="100%"style="border-color: #666;" border="1" cellpadding="10">';

                        $message .= '<table class="table table-bordered" width="100%"  style="border-collapse:collapse;">
						<h4 align="center" style="margin:0px;">Last Week Service List</h4></table><hr/>';

                        $message .= '<table class="table table-bordered" width="100%"  style="border-collapse:collapse;">';
                        $message .= '<tr><th align="left">#</th> <th align="left"><b>Job Number</b></th> <th align="left"><b>Customer Name</b></th> <th align="left"><b>Vehicle Name</b></th> <th align="left"><b>Service Date</b></th> <th align="left"><b>Model Name</b></th> </tr><br/>';

                        if (! empty($weekservice)) {
                            $i = 1;
                            foreach ($weekservice as $services) {
                                $message .= '<tr><td align="left">'.$i++.'</td><td align="left">'.$services->job_no.'</td>
												<td align="left">'.getCustomerName($services->customer_id).'</td>
												<td align="left">'.getModelName($services->vehicle_id).'</td>
												<td align="left">'.date('Y-m-d', strtotime($services->service_date)).' </td>
												<td align="left">'.getVehicleName($services->vehicle_id).'</td></tr> ';
                            }
                        }
                        $message .= '</table><hr/>';
                        $message .= '</table><br/><br/>';
                        $message .= '</body></html>';

                        $search = ['{ system_name }', '{ employee }', '{ service_list }'];
                        $replace = [$systemname, $firstname, $message];

                        $email_content = str_replace($search, $replace, $mail_format);
                        $redirect_url = url('/');
                        $systemLink = url('/');
                        // Render Blade template with all required variables
                        $blade_view = View::make('mail.template', [
                            'notification_label' => $notification_label,
                            'email_content' => $email_content,
                            'redirect_url' => $redirect_url,
                            'system_link' => $systemLink,
                        ])->render();

                        try {
                            // Send email
                            Mail::send([], [], function ($message) use ($email, $mail_sub, $blade_view, $mail_send_from) {
                                $message->to($email)->subject($mail_sub);
                                $message->from($mail_send_from);
                                $message->html($blade_view, 'text/html');
                            });
                        } catch (\Exception $e) {
                            \Log::info('Error sending email: '.$e->getMessage());
                        }

                        // Weekly service Notification send to user
                        $messageBody = 'Weekly service completetion summry for '.$systemname.''.$m.''.$y;
                        $receiverNumber = getMobileNoWithCode($employee->id);
                        if (\Schema::hasTable('sms_settings') && View::exists('smsaddon::sms_setting')) {
                            \Illuminate\Support\Facades\Http::post(route('smsaddon.send-sms'), ['messageBody' => $messageBody, 'receiver_mobile' => $receiverNumber]);
                        }

                        // Store email log entry
                        $emailLog = new EmailLog;
                        $emailLog->recipient_email = $email ?? null;
                        $emailLog->subject = $mail_sub;
                        $emailLog->content = $email_content;
                        $emailLog->save();
                    }
                }
            }

            $d = strtotime('+1 week -1 day');
            $start_week = strtotime('last sunday midnight', $d);
            $end_week = strtotime('next saturday', $d);
            $start = date('Y-m-d', $start_week);
            $end = date('Y-m-d', $end_week);

            // next week service notification for admin
            $emailformats = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'Service Due')->first();
            if ($emailformats->is_send == 0) {
                if ($week_start == $nowdate) {
                    $emailformat = DB::table('tbl_mail_notifications')->where('notification_for', '=', 'Service Due')->first();

                    $mail_format = $emailformat->notification_text;
                    $mail_subjects = $emailformat->subject;
                    $mail_send_from = $emailformat->send_from;
                    $search1 = ['{ month_week }', '{ system_name }', '{ month }', '{ year }'];
                    $replace1 = ['Weekly', $systemname, $m1, $y1];
                    $mail_sub = str_replace($search1, $replace1, $mail_subjects);

                    $message = '<html><body>';
                    $message .= '<br/><table rules="all" width="100%"style="border-color: #666;" border="1" cellpadding="10">';

                    $message .= '<table class="table table-bordered" width="100%"  style="border-collapse:collapse;">
				<h4 align="center" style="margin:0px;">Next Week Service List</h4></table><hr/>';

                    $message .= '<table class="table table-bordered" width="100%"  style="border-collapse:collapse;">';
                    $message .= '<tr><th align="left">#</th> <th align="left"><b>Coupon Number</b></th> <th align="left"><b>Customer Name</b></th> <th align="left"><b>Vehicle Name</b></th><th align="left"><b>Service Date</b></th> <th align="left"><b>AssignedTo</b></th> </tr><br/>';

                    $Upnextweekservice = DB::select("SELECT * FROM tbl_services where (done_status=2) and (service_date BETWEEN '".$start."' AND  '".$end."')");

                    if (! empty($Upnextweekservice)) {
                        $i = 1;

                        foreach ($Upnextweekservice as $services) {
                            // $salesid = $services->sales_id;
                            // if(!empty(getEmployeeservice($services->assign_to,$salesid,$nowmonthdate,$nowmonthdate1)))
                            // {

                            $message .= '<tr><td align="left">'.$i++.'</td><td align="left">'.$services->job_no.'</td>
											<td align="left">'.getCustomerName($services->customer_id).'</td>
											<td align="left">'.getModelName($services->vehicle_id).'</td>
											<td align="left">'.date('Y-m-d', strtotime($services->service_date)).' </td>
											<td align="left">'.getAssignTo($services->assign_to).'</td></tr> ';
                            // }

                        }
                    }

                    $message .= '</table><hr/>';
                    $message .= '</table><br/><br/>';
                    $message .= '</body></html>';
                    // admin notification
                    $admin = DB::table('users')->where('role', '=', 'admin')->first();
                    if (! empty($admin)) {
                        $email = $admin->email;
                        $firstname = $admin->name;

                        $search = ['{ system_name }', '{ user_name }', '{ month }', '{ year }', '{ service_list }'];
                        $replace = [$systemname, $firstname, $m1, $y1, $message];

                        $email_content = str_replace($search, $replace, $mail_format);
                        $actual_link = $_SERVER['HTTP_HOST'];
                        $startip = '0.0.0.0';
                        $endip = '255.255.255.255';
                        $data = [
                            'email' => $email,
                            'mail_sub1' => $mail_sub,
                            'email_content1' => $email_content,
                            'emailsend' => $mail_send_from,
                            'monthservice' => $monthservice,
                        ];

                        if (($actual_link == 'localhost' || $actual_link == 'localhost:8080') || ($actual_link >= $startip && $actual_link <= $endip)) {
                            // local format email
                            try {
                                $data1 = Mail::send('dashboard.monthlyservice', $data, function ($message) use ($data) {

                                    $message->from($data['emailsend'], 'noreply');

                                    $message->to($data['email'])->subject($data['mail_sub1']);
                                });
                            } catch (\Exception $e) {
                                \Log::info('Error sending email: '.$e->getMessage());
                            }

                        } else {
                            // Live format email

                            $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
                            $headers .= 'From:'.$mail_send_from."\r\n";

                            $data = mail($email, $mail_sub, $email_content, $headers);
                        }
                        $emailLog = new EmailLog;
                        $emailLog->recipient_email = $email ?? null;
                        $emailLog->subject = $mail_sub;
                        $emailLog->content = $email_content;
                        $emailLog->save();
                    }

                    // Employee notification
                    if (! empty($Upnextweekservice)) {
                        $i = 1;
                        foreach ($Upnextweekservice as $services) {
                            $assign_to = $services->assign_to;
                            $customer_id = $services->customer_id;

                            $emplo = DB::table('users')->where([['id', '=', $assign_to], ['role', '=', 'employee']])->first();
                            if (! empty($emplo)) {
                                $email1 = $emplo->email;
                                $name = $emplo->name;

                                $search = ['{ system_name }', '{ user_name }', '{ month }', '{ year }', '{ service_list }'];
                                $replace = [$systemname, $name, $m1, $y1, $message];

                                $email_content = str_replace($search, $replace, $mail_format);
                                $actual_link = $_SERVER['HTTP_HOST'];
                                $startip = '0.0.0.0';
                                $endip = '255.255.255.255';

                                if (($actual_link == 'localhost' || $actual_link == 'localhost:8080') || ($actual_link >= $startip && $actual_link <= $endip)) {
                                    // local format email
                                    $data = [
                                        'email' => $email1,
                                        'mail_sub1' => $mail_sub,
                                        'email_content1' => $email_content,
                                        'emailsend' => $mail_send_from,
                                        'monthservice' => $monthservice,
                                    ];
                                    try {
                                        $data1 = Mail::send('dashboard.monthlyservice', $data, function ($message) use ($data) {

                                            $message->from($data['emailsend'], 'noreply');

                                            $message->to($data['email'])->subject($data['mail_sub1']);
                                        });
                                    } catch (\Exception $e) {
                                        \Log::info('Error sending email: '.$e->getMessage());
                                    }

                                } else {
                                    // Live format email

                                    $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
                                    $headers .= 'From:'.$mail_send_from."\r\n";

                                    $data = mail($email1, $mail_sub, $email_content, $headers);
                                }
                                $emailLog = new EmailLog;
                                $emailLog->recipient_email = $email1 ?? null;
                                $emailLog->subject = $mail_sub;
                                $emailLog->content = $email_content;
                                $emailLog->save();
                            }
                            $custo = DB::table('users')->where([['id', '=', $customer_id], ['role', '=', 'Customer']])->first();
                            if (! empty($custo)) {
                                $cemail1 = $custo->email;
                                $cname = $custo->name;

                                $search = ['{ system_name }', '{ user_name }', '{ month }', '{ year }', '{ service_list }'];
                                $replace = [$systemname, $cname, $m1, $y1, $message];

                                $email_content = str_replace($search, $replace, $mail_format);
                                $actual_link = $_SERVER['HTTP_HOST'];
                                $startip = '0.0.0.0';
                                $endip = '255.255.255.255';

                                if (($actual_link == 'localhost' || $actual_link == 'localhost:8080') || ($actual_link >= $startip && $actual_link <= $endip)) {
                                    // local format email
                                    $data = [
                                        'email' => $cemail1,
                                        'mail_sub1' => $mail_sub,
                                        'email_content1' => $email_content,
                                        'emailsend' => $mail_send_from,
                                        'monthservice' => $monthservice,
                                    ];
                                    try {
                                        $data1 = Mail::send('dashboard.monthlyservice', $data, function ($message) use ($data) {

                                            $message->from($data['emailsend'], 'noreply');

                                            $message->to($data['email'])->subject($data['mail_sub1']);
                                        });
                                    } catch (\Exception $e) {
                                        \Log::info('Error sending email: '.$e->getMessage());
                                    }

                                } else {
                                    // Live format email

                                    $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
                                    $headers .= 'From:'.$mail_send_from."\r\n";

                                    $data = mail($cemail1, $mail_sub, $email_content, $headers);
                                }
                                $emailLog = new EmailLog;
                                $emailLog->recipient_email = $cemail1 ?? null;
                                $emailLog->subject = $mail_sub;
                                $emailLog->content = $email_content;
                                $emailLog->save();
                            }
                        }
                    }
                }
            }
        }
        Session::put('email_sended', 1);

        // Monthly  service barchart
        $nowmonth = date('F-Y');
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');

        $dates = [];
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $dates[] = $date->format('d');
        }

        $month = date('m');
        $year = date('Y');
        $start_date = "$year/$month/01";
        $end_date = "$year/$month/30";

        if (isAdmin(Auth::User()->role_id)) {
            // top five vehicle service
            $vehical = DB::select("SELECT count(id) as count,`vehicle_id` as vid FROM tbl_services where (done_status=1) and (service_date BETWEEN '".$start_date."' AND  '".$end_date."') group by `vehicle_id` limit 5");

            // top five employee performance
            $performance = DB::select("SELECT count(id) as count,`assign_to` as a_id FROM tbl_services where (done_status=1) and (service_date BETWEEN '".$start_date."' AND  '".$end_date."') group by `assign_to` limit 5");

            // ontime service
            $datediff = DB::select("SELECT DATEDIFF(tbl_gatepasses.service_out_date,tbl_services.service_date) as days,COUNT(tbl_services.job_no) as counts FROM `tbl_services` join tbl_gatepasses on tbl_services.job_no=tbl_gatepasses.jobcard_id where tbl_services.done_status=1 and (tbl_services.service_date BETWEEN '".$start_date."' AND  '".$end_date."') and (tbl_gatepasses.service_out_date BETWEEN '".$start_date."' AND  '".$end_date."')GROUP BY days ");
        } elseif (getUsersRole(Auth::user()->role_id) == 'Support Staff' || getUsersRole(Auth::user()->role_id) == 'Accountant' || getUsersRole(Auth::user()->role_id) == 'Employee' || getUsersRole(Auth::user()->role_id) == 'Branch Admin') {

            // top five vehicle service
            $vehical = DB::select("SELECT count(id) as count,`vehicle_id` as vid FROM tbl_services where (done_status=1) and (branch_id = '".$currentUser->branch_id."') and (service_date BETWEEN '".$start_date."' AND  '".$end_date."') group by `vehicle_id` limit 5");

            // top five employee performance
            $performance = DB::select("SELECT count(id) as count,`assign_to` as a_id FROM tbl_services where (done_status=1) and (branch_id = '".$currentUser->branch_id."') and (service_date BETWEEN '".$start_date."' AND  '".$end_date."') group by `assign_to` limit 5");

            // ontime service
            $datediff = DB::select("SELECT DATEDIFF(tbl_gatepasses.service_out_date,tbl_services.service_date) as days,COUNT(tbl_services.job_no) as counts FROM `tbl_services` join tbl_gatepasses on tbl_services.job_no=tbl_gatepasses.jobcard_id where tbl_services.done_status=1 and (tbl_services.branch_id = '".$currentUser->branch_id."') and (tbl_services.service_date BETWEEN '".$start_date."' AND  '".$end_date."') and (tbl_gatepasses.service_out_date BETWEEN '".$start_date."' AND  '".$end_date."')GROUP BY days ");
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            // top five vehicle service
            $vehical = DB::select("SELECT count(id) as count,`vehicle_id` as vid FROM tbl_services where (done_status=1) and (service_date BETWEEN '".$start_date."' AND  '".$end_date."') group by `vehicle_id` limit 5");

            // top five employee performance
            $performance = DB::select("SELECT count(id) as count,`assign_to` as a_id FROM tbl_services where (done_status=1) and (service_date BETWEEN '".$start_date."' AND  '".$end_date."') group by `assign_to` limit 5");

            // ontime service
            $datediff = DB::select("SELECT DATEDIFF(tbl_gatepasses.service_out_date,tbl_services.service_date) as days,COUNT(tbl_services.job_no) as counts FROM `tbl_services` join tbl_gatepasses on tbl_services.job_no=tbl_gatepasses.jobcard_id where tbl_services.done_status=1 and (tbl_services.service_date BETWEEN '".$start_date."' AND  '".$end_date."') and (tbl_gatepasses.service_out_date BETWEEN '".$start_date."' AND  '".$end_date."')GROUP BY days ");
        }

        if (! empty($datediff)) {
            foreach ($datediff as $datediffs) {
                $days = $datediffs->days;
                if ($days == 0) {
                    $one_day = $datediffs->counts;
                }
                if ($days == 1) {
                    $two_day = $datediffs->counts;
                }
                if ($days > 1) {
                    $more = $datediffs->counts;
                }
            }
        }
        $nowdate = date('Y-m-d');

        // Get data of Dashboard related to assign Role
        if (! isAdmin(Auth::User()->role_id)) {
            if (getUsersRole(Auth::user()->role_id) == 'Customer') {
                if (Gate::allows('dashboard_owndata')) {
                    // Upcoming service
                    $sale = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_date', '>', $nowdate]])
                        ->where('tbl_services.customer_id', '=', Auth::User()->id)
                        ->whereNotIn('quotation_modify_status', [1])
                        ->orderBy('tbl_services.id', 'desc')->take(5)
                        ->where('soft_delete', '=', 0)
                        ->select('tbl_services.*')
                        ->get();

                    // Paid Service
                    $sale1 = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'paid']])
                        ->where('tbl_services.customer_id', '=', Auth::User()->id)
                        ->whereNotIn('quotation_modify_status', [1])
                        ->orderBy('tbl_services.id', 'desc')->take(5)
                        ->where('soft_delete', '=', 0)
                        ->select('tbl_services.*')
                        ->get();

                    // Repeat Job
                    $sale2 = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_category', '=', 'repeat job']])
                        ->where('tbl_services.customer_id', '=', Auth::User()->id)
                        ->whereNotIn('quotation_modify_status', [1])
                        ->orderBy('tbl_services.id', 'desc')->take(5)
                        ->where('soft_delete', '=', 0)
                        ->select('tbl_services.*')
                        ->get();

                    // Calendar Events
                    $serviceevent = Service::where([['done_status', '!=', 2], ['customer_id', '=', Auth::User()->id]])->where('soft_delete', '=', 0)->get();

                    // opening hours
                    $openinghours = BusinessHour::ORDERBY('day', 'ASC')->get();

                    // holiday
                    $holiday = Holiday::ORDERBY('date', 'ASC')->get();

                    // upcoming service
                    $nowdate = date('Y-m-d');

                    $upcomingservice = Service::where([['customer_id', '=', Auth::User()->id], ['job_no', 'like', 'J%'], ['service_date', '>', $nowdate]])->where('soft_delete', '=', 0)->take(5)->get();

                    $Customer = '';
                    $Supplier = '';
                    $employee = '';
                    $product = '';
                    $sales = '';
                    $service = '';
                    $Customere = '';

                    $have_supportstaff = '';
                    $have_vehicle = '';
                    $have_product = '';
                    $have_purchase = '';
                    $have_observationCount = '';
                } else {
                    // Upcoming service
                    $sale = DB::table('tbl_services')
                        ->where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_date', '>', $nowdate]])
                        ->where('tbl_services.customer_id', '=', Auth::User()->id)
                        ->whereNotIn('quotation_modify_status', [1])
                        ->orderBy('tbl_services.id', 'desc')->take(5)
                        ->select('tbl_services.*')
                        ->get()->toArray();

                    // Paid Service
                    $sale1 = DB::table('tbl_services')
                        ->where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'paid']])
                        ->where('tbl_services.customer_id', '=', Auth::User()->id)
                        ->whereNotIn('quotation_modify_status', [1])
                        ->orderBy('tbl_services.id', 'desc')->take(5)
                        ->select('tbl_services.*')
                        ->get()->toArray();

                    // Repeat Job
                    $sale2 = DB::table('tbl_services')
                        ->where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_category', '=', 'repeat job']])
                        ->where('tbl_services.customer_id', '=', Auth::User()->id)
                        ->whereNotIn('quotation_modify_status', [1])
                        ->orderBy('tbl_services.id', 'desc')->take(5)
                        ->select('tbl_services.*')
                        ->get()->toArray();

                    $sale = null;
                    $sale1 = null;
                    $sale2 = null;

                    // Calendar Events
                    $serviceevent = null;

                    // opening hours
                    $openinghours = BusinessHour::ORDERBY('day', 'ASC')->get();

                    // holiday
                    $holiday = Holiday::ORDERBY('date', 'ASC')->get();

                    // upcoming service
                    $nowdate = date('Y-m-d');
                    $upcomingservice = null;

                    $Customer = '';
                    $Supplier = '';
                    $employee = '';
                    $product = '';
                    $sales = '';
                    $service = '';
                    $Customere = '';

                    $have_supportstaff = '';
                    $have_vehicle = '';
                    $have_product = '';
                    $have_purchase = '';
                    $have_observationCount = '';
                }
            } elseif (getUsersRole(Auth::user()->role_id) == 'Employee') {
                // Upcoming service
                $sale = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_date', '>', $nowdate]])
                    ->where('tbl_services.assign_to', '=', Auth::User()->id)
                    ->whereNotIn('quotation_modify_status', [1])
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->where('soft_delete', '=', 0)
                    // ->where('branch_id','=',$currentUser->branch_id)
                    ->select('tbl_services.*')
                    ->get();

                // Paid Service
                $sale1 = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'paid']])
                    ->where('tbl_services.assign_to', '=', Auth::User()->id)
                    ->whereNotIn('quotation_modify_status', [1])
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->where('soft_delete', '=', 0)
                    ->where('branch_id', '=', $currentUser->branch_id)
                    ->select('tbl_services.*')
                    ->get();

                // Repeat Job
                $sale2 = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_category', '=', 'repeat job']])
                    ->where('tbl_services.assign_to', '=', Auth::User()->id)
                    ->whereNotIn('quotation_modify_status', [1])
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->where('soft_delete', '=', 0)
                    ->where('branch_id', '=', $currentUser->branch_id)
                    ->select('tbl_services.*')
                    ->get();

                // Recently Joined customer
                $Customere = User::join('tbl_services', 'users.id', '=', 'tbl_services.customer_id')
                    ->where([['tbl_services.assign_to', '=', Auth::User()->id], ['tbl_services.done_status', '!=', 2]])
                    ->where('tbl_services.branch_id', '=', $currentUser->branch_id)
                    ->orderBy('tbl_services.assign_to', 'desc')
                    ->groupBy('tbl_services.customer_id')
                    ->take(5)->get();

                // Calendar Events
                $serviceevent = Service::where([['done_status', '!=', 2], ['assign_to', '=', Auth::User()->id], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->get();

                // opening hours
                $openinghours = BusinessHour::ORDERBY('day', 'ASC')->get();

                // holiday
                $holiday = Holiday::ORDERBY('date', 'ASC')->get();

                // upcoming service
                $nowdate = date('Y-m-d');

                $upcomingservice = Service::where([['assign_to', '=', Auth::User()->id], ['job_no', 'like', 'J%'], ['service_date', '>', $nowdate], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->take(5)->get();

                $product = null;
                $sales = null;
                $service = null;

                $Customer = User::where([['role', '=', 'Customer'], ['soft_delete', '=', 0]])->count();
                $employee = User::where([['role', '=', 'employee'], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->count();
                $Supplier = User::where([['role', '=', 'Supplier'], ['soft_delete', '=', 0]])->count();
                $have_supportstaff = User::where([['role', '=', 'supportstaff'], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->count();
                $have_vehicle = Vehicle::where('soft_delete', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->count();
                $have_product = Product::where('soft_delete', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->count();
                $have_purchase = DB::table('tbl_purchases')->where('branch_id', '=', $currentUser->branch_id)->count();
                $have_observationCount = DB::table('tbl_points')->where('soft_delete', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->count();
            } elseif (getUsersRole(Auth::user()->role_id) == 'Support Staff' || getUsersRole(Auth::user()->role_id) == 'Accountant' || getUsersRole(Auth::user()->role_id) == 'Branch Admin') {

                $employee = User::where([['role', '=', 'employee'], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->count();
                $Customer = User::where([['role', '=', 'Customer'], ['soft_delete', '=', 0]])->count();
                $Supplier = User::where([['role', '=', 'Supplier'], ['soft_delete', '=', 0]])->count();
                $product = Product::where('soft_delete', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->count();
                $sales = Sale::where('soft_delete', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->count();
                $service = Service::where([['job_no', 'like', 'J%'], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->whereNotIn('quotation_modify_status', [1])->count();

                $have_supportstaff = User::where([['role', '=', 'supportstaff'], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->count();
                $have_vehicle = Vehicle::where('soft_delete', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->count();
                $have_product = Product::where('soft_delete', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->count();
                $have_purchase = DB::table('tbl_purchases')->where('branch_id', '=', $currentUser->branch_id)->count();
                $have_observationCount = DB::table('tbl_points')->where('soft_delete', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->count();

                // Upcoming service
                $sale = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_date', '>', $nowdate]])->orderBy('tbl_services.id', 'desc')->take(5)
                    ->where('soft_delete', '=', 0)
                    ->where('branch_id', '=', $currentUser->branch_id)
                    ->whereNotIn('quotation_modify_status', [1])
                    ->select('tbl_services.*')
                    ->get();

                // Paid service
                $sale1 = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'paid']])->orderBy('tbl_services.id', 'desc')->take(5)
                    ->where('soft_delete', '=', 0)
                    ->where('branch_id', '=', $currentUser->branch_id)
                    ->whereNotIn('quotation_modify_status', [1])
                    ->select('tbl_services.*')
                    ->get();

                // Repeat job service
                $sale2 = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_category', '=', 'repeat job']])
                    ->orderBy('tbl_services.id', 'desc')->take(5)
                    ->where('soft_delete', '=', 0)
                    ->whereNotIn('quotation_modify_status', [1])
                    ->where('branch_id', '=', $currentUser->branch_id)
                    ->select('tbl_services.*')
                    ->get();

                // Recent join customer
                $Customere = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->orderBy('id', 'desc')->take(5)->get();

                // Calendar Events
                $serviceevent = Service::where('tbl_services.done_status', '!=', 2)->where('soft_delete', '=', 0)->where('branch_id', '=', $currentUser->branch_id)->get();

                // holiday show Calendar
                $holiday = Holiday::ORDERBY('date', 'ASC')->get();
            }
            // For Service Count
            $totalService = Service::where('soft_delete', '0')->whereNotIn('quotation_modify_status', [1])->where('branch_id', $currentUser->branch_id)->get()->count();
            $freeService = Service::where('service_type', 'free')->whereNotIn('quotation_modify_status', [1])->where('soft_delete', '0')->where('branch_id', $currentUser->branch_id)->get()->count();
            $paidService = Service::where('service_type', 'paid')->whereNotIn('quotation_modify_status', [1])->where('soft_delete', '0')->where('branch_id', $currentUser->branch_id)->get()->count();
        } else {
            // For Service Count
            $totalService = Service::where('soft_delete', '0')->whereNotIn('quotation_modify_status', [1])->get()->count();
            $freeService = Service::where('service_type', 'free')->whereNotIn('quotation_modify_status', [1])->where('soft_delete', '0')->get()->count();
            $paidService = Service::where('service_type', 'paid')->whereNotIn('quotation_modify_status', [1])->where('soft_delete', '0')->get()->count();

            // count employee,customer,supplier,product,sales,service
            $employee = User::where([['role', '=', 'employee'], ['soft_delete', '=', 0]])->count();
            $Customer = User::where([['role', '=', 'Customer'], ['soft_delete', '=', 0]])->count();
            $Supplier = User::where([['role', '=', 'Supplier'], ['soft_delete', '=', 0]])->count();
            $product = Product::where('soft_delete', '=', 0)->count();
            $sales = Sale::where('soft_delete', '=', 0)->count();
            $service = Service::where([['job_no', 'like', 'J%'], ['soft_delete', '=', 0]])->whereNotIn('quotation_modify_status', [1])->count();

            $have_supportstaff = User::where([['role', '=', 'supportstaff'], ['soft_delete', '=', 0]])->count();
            $have_vehicle = Vehicle::where('soft_delete', '=', 0)->count();
            $have_product = Product::where('soft_delete', '=', 0)->count();
            $have_purchase = DB::table('tbl_purchases')->count();
            $have_observationCount = DB::table('tbl_points')->where('soft_delete', '=', 0)->count();

            // Upcoming service
            $sale = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_date', '>', $nowdate]])->orderBy('tbl_services.id', 'desc')->take(5)
                ->where('soft_delete', '=', 0)
                // ->where('branch_id', '=', $adminCurrentBranch->branch_id)
                ->whereNotIn('quotation_modify_status', [1])
                ->select('tbl_services.*')
                ->get();

            // Paid service
            $sale1 = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_type', '=', 'paid']])->orderBy('tbl_services.id', 'desc')->take(5)
                ->where('soft_delete', '=', 0)
                // ->where('branch_id', '=', $adminCurrentBranch->branch_id)
                ->whereNotIn('quotation_modify_status', [1])
                ->select('tbl_services.*')
                ->get();

            // Repeat job service
            $sale2 = Service::where([['tbl_services.done_status', '!=', 2], ['tbl_services.service_category', '=', 'repeat job']])
                ->orderBy('tbl_services.id', 'desc')->take(5)
                ->where('soft_delete', '=', 0)
                // ->where('branch_id', '=', $adminCurrentBranch->branch_id)
                ->whereNotIn('quotation_modify_status', [1])
                ->select('tbl_services.*')
                ->get();

            // Recent join customer
            $Customere = User::where([['role', '=', 'Customer'], ['soft_delete', 0]])->orderBy('id', 'desc')->take(8)->get();

            // Calendar Events
            $serviceevent = Service::where('tbl_services.done_status', '!=', 2)->where('soft_delete', '=', 0)->get();

            // holiday show Calendar
            $holiday = Holiday::ORDERBY('date', 'ASC')->get();
        }

        $settings = Setting::first();
        $phone_number = $settings->phone_number;
        $setting = empty($phone_number) ? 0 : 1;
        $sampleDataExists = DB::table('users')->count() > 2
        && DB::table('tbl_services')->exists()
        && DB::table('tbl_vehicles')->exists();
        // customer side modal detail
        $holiday = Holiday::ORDERBY('date', 'ASC')->get();

        $last_order = DB::table('tbl_services')->latest()->where('sales_id', '=', null)->get()->first();

        if (! empty($last_order)) {

            $last_full_job_number = $last_order->job_no;
            $last_job_number_digit = substr($last_full_job_number, 1);
            $new_number = 'J'.str_pad($last_job_number_digit + 1, 6, 0, STR_PAD_LEFT);
            // $new_number = "J" . str_pad((int)$last_job_number_digit + 1, 6, '0', STR_PAD_LEFT);

        } else {
            $new_number = 'J000001';
        }

        $code = $new_number;

        $country = DB::table('tbl_countries')->get()->toArray();
        $Customer_detail = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $state = DB::table('tbl_states')->where('country_id', $Customer_detail->country_id)->get()->toArray();
        $city = DB::table('tbl_cities')->where('state_id', $Customer_detail->state_id)->get()->toArray();
        // vehicle add
        $vehical_type = DB::table('tbl_vehicle_types')->where('soft_delete', '=', 0)->get()->toArray();
        $vehical_brand = DB::table('tbl_vehicle_brands')->where('soft_delete', '=', 0)->get()->toArray();
        $fuel_type = DB::table('tbl_fuel_types')->where('soft_delete', '=', 0)->get()->toArray();
        $model_name = DB::table('tbl_model_names')->where('soft_delete', '=', 0)->get()->toArray();
        // $number_plate = DB::table('tbl_vehicles')->select('number_plate')->where([['soft_delete', "=", 0],['customer_id', '=', Auth::User()->id]])->first();
        $vehicles_dd = DB::table('tbl_vehicles')->join('tbl_vehicle_types', 'tbl_vehicles.vehicletype_id', '=', 'tbl_vehicle_types.id') // Join to get vehicle type
            ->join('tbl_vehicle_brands', 'tbl_vehicles.vehiclebrand_id', '=', 'tbl_vehicle_brands.id') // Join to get vehicle brand
            ->join('tbl_model_names', 'tbl_vehicle_brands.id', '=', 'tbl_model_names.brand_id') // Join to get model name
            ->select(
                'tbl_vehicles.id',
                'tbl_vehicle_types.vehicle_type',
                'tbl_vehicles.number_plate',
                'tbl_vehicle_brands.vehicle_brand',
                'tbl_model_names.model_name',
                'tbl_vehicles.modelname',
                'tbl_vehicles.customer_id')
            ->where([
                    ['tbl_vehicles.soft_delete', '=', 0],
                    ['tbl_vehicles.customer_id', '=', Auth::User()->id],
                ])
            ->orderBy('tbl_vehicles.id', 'DESC')
            ->get()->first();

        $repairCategoryList = DB::table('table_repair_category')->where([['soft_delete', '=', 0]])->get()->toArray();

        return view('dashboard.dashboard', compact('setting', 'sampleDataExists', 'employee', 'Customer', 'Supplier', 'product', 'sales', 'service', 'Customere', 'sale', 'sale1', 'sale2', 'dates', 'data', 'vehical', 'performance', 'serviceevent', 'one_day', 'two_day', 'more', 'nowmonth', 'openinghours', 'holiday', 'upcomingservice', 'have_supportstaff', 'have_vehicle', 'have_product', 'have_purchase', 'have_observationCount', 'totalService', 'paidService', 'freeService', 'last_order', 'code', 'country', 'vehical_type', 'vehical_brand', 'fuel_type', 'model_name', 'repairCategoryList', 'Customer_detail', 'state', 'city', 'vehicles_dd'));
    }

    public function sendCustomerServiceReminders()
    {
        try {
            $adminTimezone = DB::table('users')->where('id', 1)->value('timezone') ?? 'UTC';
            $currentDateTime = \Carbon\Carbon::now($adminTimezone);

            // 1 Hour Reminder Window: services scheduled exactly 1 hour from now ± 3 minutes
            $oneHourStart = $currentDateTime->copy()->addHour()->subMinutes(3);
            $oneHourEnd = $currentDateTime->copy()->addHour()->addMinutes(3);

            // 24 Hours Reminder Window: services scheduled exactly 24 hours from now ± 3 minutes
            $twentyFourHourStart = $currentDateTime->copy()->addDay()->subMinutes(3);
            $twentyFourHourEnd = $currentDateTime->copy()->addDay()->addMinutes(3);

            // Get services due in 1 hour
            $servicesInOneHour = $this->getUpcomingServicesForCustomers($oneHourStart, $oneHourEnd, '1 Hour');

            // Get services due in 24 hours
            $servicesInTwentyFourHours = $this->getUpcomingServicesForCustomers($twentyFourHourStart, $twentyFourHourEnd, '24 Hours');

            // Send 1-hour reminders to customers
            $oneHourCount = $this->processCustomerServiceReminders($servicesInOneHour, '1 Hour');

            // Send 24-hour reminders to customers
            $twentyFourHourCount = $this->processCustomerServiceReminders($servicesInTwentyFourHours, '24 Hours');

            $message = "Customer service reminders sent successfully. 1-hour reminders: {$oneHourCount}, 24-hour reminders: {$twentyFourHourCount}";

            return response()->json(['success' => true, 'message' => $message]);

        } catch (\Exception $e) {
            \Log::error('Error sending customer service reminders: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function getUpcomingServicesForCustomers($startTime, $endTime, $reminderType)
    {
        $startTimeFormatted = $startTime->format('Y-m-d H:i:s');
        $endTimeFormatted = $endTime->format('Y-m-d H:i:s');

        return DB::table('tbl_services')
            ->join('users as customers', 'tbl_services.customer_id', '=', 'customers.id')
            ->join('tbl_vehicles', 'tbl_services.vehicle_id', '=', 'tbl_vehicles.id')
            ->leftJoin('users as employees', 'tbl_services.assign_to', '=', 'employees.id')
            ->select(
                'tbl_services.id',
                'tbl_services.job_no',
                'tbl_services.service_date',
                'tbl_services.customer_id',
                'tbl_services.service_category',
                'customers.name as customer_name',
                'customers.email as customer_email',
                'employees.name as employee_name',
                'tbl_vehicles.number_plate',
                'tbl_vehicles.modelname'
            )
            ->where('tbl_services.done_status', '!=', 1) // Not completed
            ->where('tbl_services.soft_delete', 0)
            ->where('customers.soft_delete', 0)
            ->whereBetween('tbl_services.service_date', [$startTimeFormatted, $endTimeFormatted])
            ->whereNotExists(function ($query) use ($reminderType) {
                $query->select(DB::raw(1))
                    ->from('tbl_service_reminders')
                    ->whereRaw('tbl_service_reminders.service_id = tbl_services.id')
                    ->where('reminder_type', $reminderType)
                    ->where('sent_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 3 HOUR)')); // Prevent duplicate sends within 3 hours
            })
            ->get();
    }

    private function processCustomerServiceReminders($services, $reminderType)
    {
        if ($services->isEmpty()) {
            \Log::info("No services found for {$reminderType} reminder");

            return 0;
        }

        $logo = DB::table('tbl_settings')->first();
        $systemname = $logo->system_name ?? 'Garage Management System';

        // Get email template for service reminders
        $emailformat = DB::table('tbl_mail_notifications')
            ->where('notification_for', '=', 'Service_Reminder')
            ->first();

        if (! $emailformat || $emailformat->is_send == 1) {
            \Log::info('Service reminder email notifications are disabled');

            return 0;
        }

        $sentCount = 0;

        foreach ($services as $service) {
            try {
                // Send reminder only to customer
                $this->sendCustomerReminderEmail($service, $emailformat, $systemname, $reminderType);

                // Send SMS to customer if enabled
                $this->sendCustomerReminderSMS($service, $systemname, $reminderType);

                // Log the reminder
                $this->logServiceReminder($service->id, $reminderType);

                $sentCount++;

                \Log::info("Service {$reminderType} reminder sent for Job No: {$service->job_no} to customer: {$service->customer_email}");

            } catch (\Exception $e) {
                \Log::error("Failed to send {$reminderType} reminder for service ID {$service->id}: ".$e->getMessage());
            }
        }

        return $sentCount;
    }

    private function sendCustomerReminderEmail($service, $emailformat, $systemname, $reminderType)
    {
        if (empty($service->customer_email)) {
            \Log::warning("No email found for customer ID: {$service->customer_id}");

            return;
        }

        $mail_format = $emailformat->notification_text;
        $notification_label = $emailformat->notification_label;
        $mail_subjects = $emailformat->subject;
        $mail_send_from = $emailformat->send_from;

        // Prepare email content based on reminder type
        $serviceDateTime = \Carbon\Carbon::parse($service->service_date)->format('d M Y, h:i A');
        $timeUntilService = $reminderType === '1 Hour' ? '1 hour' : '24 hours';
        $reminderTitle = $reminderType === '1 Hour' ? 'Urgent Reminder' : 'Advance Reminder';

        $search = [
            '{ system_name }',
            '{ customer_name }',
            '{ service_date }',
            '{ service_time }',
            '{ service_datetime }',
            '{ job_no }',
            '{ vehicle_model }',
            '{ vehicle_number }',
            '{ employee_name }',
            '{ time_until_service }',
            '{ reminder_type }',
            '{ reminder_title }',
            '{ service_category }',
        ];

        $replace = [
            $systemname,
            $service->customer_name,
            \Carbon\Carbon::parse($service->service_date)->format('d M Y'),
            \Carbon\Carbon::parse($service->service_date)->format('h:i A'),
            $serviceDateTime,
            $service->job_no,
            $service->modelname,
            $service->number_plate,
            $service->employee_name ?? 'Our Service Team',
            $timeUntilService,
            $reminderType,
            $reminderTitle,
            ucfirst($service->service_category ?? 'General Service'),
        ];

        $email_content = str_replace($search, $replace, $mail_format);
        $mail_subject = str_replace($search, $replace, $mail_subjects);

        $redirect_url = url('/');

        // Render Blade template
        $blade_view = View::make('mail.template', [
            'notification_label' => $notification_label,
            'email_content' => $email_content,
            'redirect_url' => $redirect_url,
            'system_link' => $redirect_url,      // same system link as redirect url
        ])->render();

        // Send email
        Mail::send([], [], function ($message) use ($service, $mail_subject, $blade_view, $mail_send_from) {
            $message->to($service->customer_email)->subject($mail_subject);
            $message->from($mail_send_from);
            $message->html($blade_view, 'text/html');
        });

        // Store email log
        $emailLog = new EmailLog;
        $emailLog->recipient_email = $service->customer_email ?? null;
        $emailLog->subject = $mail_subject;
        $emailLog->content = $email_content;
        $emailLog->save();
    }

    private function sendCustomerReminderSMS($service, $systemname, $reminderType)
    {
        // Send SMS to customer only
        $customerMobile = getMobileNoWithCode($service->customer_id);
        if ($customerMobile) {
            $serviceTime = \Carbon\Carbon::parse($service->service_date)->format('h:i A');
            $serviceDate = \Carbon\Carbon::parse($service->service_date)->format('d M Y');
            $timeUntil = $reminderType === '1 Hour' ? '1 hour' : '24 hours';

            if ($reminderType === '1 Hour') {
                $messageBody = "URGENT: Your service appointment for {$service->modelname} ({$service->number_plate}) is in 1 hour at {$serviceTime}. Job: {$service->job_no}. Please arrive on time. - {$systemname}";
            } else {
                $messageBody = "Reminder: Your service appointment for {$service->modelname} ({$service->number_plate}) is tomorrow {$serviceDate} at {$serviceTime}. Job: {$service->job_no}. - {$systemname}";
            }

            if (\Schema::hasTable('sms_settings') && View::exists('smsaddon::sms_setting')) {
                \Illuminate\Support\Facades\Http::post(route('smsaddon.send-sms'), [
                    'messageBody' => $messageBody,
                    'receiver_mobile' => $customerMobile,
                ]);
            }
        }
    }

    private function logServiceReminder($serviceId, $reminderType)
    {
        $adminTimezone = DB::table('users')->where('id', 1)->value('timezone') ?? 'UTC';
        $sent_at = \Carbon\Carbon::now($adminTimezone);
        DB::table('tbl_service_reminders')->updateOrInsert(
            ['service_id' => $serviceId, 'reminder_type' => $reminderType],
            ['sent_at' => $sent_at, 'created_at' => now(), 'updated_at' => now()]
        );
    }

    // Add Sample data
    public function addSampleData()
    {
        try {
            DB::transaction(function () {
                // Insert or update into users table
                // Define the data to insert or update
                $users = [
                    [
                        'name' => 'John',
                        'lastname' => 'Doe',
                        'company_name' => 'Audi',
                        'gender' => 0,
                        'email' => 'john@gmail.com',
                        'password' => bcrypt('john123'),
                        'mobile_no' => '1234567890',
                        'address' => 'XYZ Street Area',
                        'image' => 'avtar.png',
                        'designation' => null,
                        'country_id' => 101,
                        'state_id' => null,
                        'role' => 'Supplier',
                        'role_id' => null,
                        'branch_id' => 1,
                        'language' => 'en',
                        'timezone' => 'UTC',
                        'soft_delete' => 0,
                        'create_by' => 1,
                    ],
                    [
                        'name' => 'Joss',
                        'lastname' => 'Buttler',
                        'company_name' => null,
                        'gender' => 0,
                        'email' => 'jossb@gmail.com',
                        'password' => bcrypt('joss123'),
                        'mobile_no' => '9988776655',
                        'address' => 'Diamond Street',
                        'image' => 'avtar.png',
                        'designation' => null,
                        'country_id' => 101,
                        'state_id' => null,
                        'role' => 'Customer',
                        'role_id' => 2,
                        'language' => 'en',
                        'soft_delete' => 0,
                        'timezone' => 'UTC',
                        'create_by' => 1,
                    ],
                    [
                        'name' => 'Priya',
                        'lastname' => 'Kumari',
                        'company_name' => null,
                        'gender' => 1,
                        'email' => 'priya@gmail.com',
                        'password' => bcrypt('priya123'),
                        'mobile_no' => '1122334455',
                        'address' => 'Garden Area',
                        'image' => 'avtar.png',
                        'designation' => 'Supervisor',
                        'country_id' => 101,
                        'state_id' => null,
                        'role' => 'Employee',
                        'soft_delete' => 0,
                        'role_id' => 3,
                        'branch_id' => 1,
                        'language' => 'en',
                        'timezone' => 'UTC',
                        'create_by' => 1,
                    ],
                    [
                        'name' => 'Markus',
                        'lastname' => 'Stoinic',
                        'company_name' => null,
                        'gender' => 0,
                        'email' => 'markus@gmail.com',
                        'password' => bcrypt('markus123'),
                        'mobile_no' => '4512451245',
                        'address' => 'Rumble Part Area',
                        'image' => 'avtar.png',
                        'designation' => null,
                        'country_id' => 101,
                        'state_id' => null,
                        'role' => 'SupportStaff',
                        'soft_delete' => 0,
                        'role_id' => 4,
                        'branch_id' => 1,
                        'language' => 'en',
                        'timezone' => 'UTC',
                        'create_by' => 1,
                    ],
                ];

                // Loop through each user and either update or insert
                foreach ($users as $user) {
                    DB::table('users')->updateOrInsert(
                        ['email' => $user['email']], // Checking if the email already exists
                        array_merge($user, [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ])
                    );
                }
                $usercus = DB::table('users')->where('email', 'jossb@gmail.com')->value('id');
                $userem = DB::table('users')->where('email', 'priya@gmail.com')->value('id');
                $userSupport = DB::table('users')->where('email', 'markus@gmail.com')->value('id');
                $users = [
                    ['user_id' => $usercus ?? null, 'role_id' => 2],
                    ['user_id' => $userem ?? null, 'role_id' => 3],
                    ['user_id' => $userSupport ?? null, 'role_id' => 4],
                ];

                foreach ($users as $user) {
                    if ($user['user_id'] !== null) {
                        DB::table('role_users')->updateOrInsert(
                            ['user_id' => $user['user_id']], // Search condition
                            [
                                'role_id' => $user['role_id'],
                                'updated_at' => now(),
                                'created_at' => now(), // Only relevant for new inserts
                            ]
                        );
                    }
                }

                // Insert or update into tbl_product_types
                DB::table('tbl_product_types')->updateOrInsert(
                    ['type' => 'Bosch'], // Unique check
                    ['soft_delete' => 0, 'created_at' => now(), 'updated_at' => now()]
                );

                // Insert or update into tbl_product_units
                DB::table('tbl_product_units')->updateOrInsert(
                    ['name' => 'Numbers'], // Unique check
                    ['created_at' => now(), 'updated_at' => now()]
                );
                $supplier = DB::table('users')->where('role', 'Supplier')->first();
                $customer = DB::table('users')->where('role', 'Customer')->first();
                $employee = DB::table('users')->where('role', 'Employee')->first();
                $supplier_id = $supplier->id;
                $emp_id = $employee->id;
                $cus_id = $customer->id;
                if ($supplier) {

                    // Insert or update into tbl_products
                    DB::table('tbl_products')->updateOrInsert(
                        ['product_no' => 'PR083462'], // Unique check
                        [
                            'product_date' => '2024-04-03',
                            'product_image' => 'avtar.png',
                            'name' => 'Brake Pads',
                            'product_type_id' => 1,
                            'color_id' => 1,
                            'price' => 99,
                            'supplier_id' => $supplier_id,
                            'category' => 1,
                            'unit' => 1,
                            'create_by' => 1,
                            'branch_id' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // Insert or update into tbl_colors
                DB::table('tbl_colors')->updateOrInsert(
                    ['color' => 'Black'], // Unique check
                    ['color_code' => '#000000', 'created_at' => now(), 'updated_at' => now()]
                );

                // Insert or update into tbl_fuel_types
                DB::table('tbl_fuel_types')->updateOrInsert(
                    ['fuel_type' => 'Diesel'], // Unique check
                    ['soft_delete' => 0, 'created_at' => now(), 'updated_at' => now()]
                );

                // Insert or update into tbl_vehicles
                if ($customer) {
                    DB::table('tbl_vehicles')->updateOrInsert(
                        ['vehicletype_id' => 1, 'number_plate' => 'GJ-01-2020'], // Unique check
                        [
                            'vehiclebrand_id' => 1,
                            'modelyear' => 2020,
                            'fuel_id' => 1,
                            'modelname' => 'Audi A7',
                            'customer_id' => $cus_id,
                            'added_by_service' => 1,
                            'branch_id' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                // Insert or update into tbl_vehicle_types
                DB::table('tbl_vehicle_types')->updateOrInsert(
                    ['vehicle_type' => 'Car'], // Unique check
                    ['soft_delete' => 0, 'created_at' => now(), 'updated_at' => now()]
                );

                // Insert or update into tbl_vehicle_brands
                DB::table('tbl_vehicle_brands')->updateOrInsert(
                    ['vehicle_type_id' => 1, 'vehicle_brand' => 'Audi'], // Unique check
                    ['soft_delete' => 0, 'created_at' => now(), 'updated_at' => now()]
                );

                // Insert or update into tbl_model_names
                DB::table('tbl_model_names')->updateOrInsert(
                    ['brand_id' => 1, 'model_name' => 'Audi A7'], // Unique check
                    ['soft_delete' => 0, 'created_at' => now(), 'updated_at' => now()]
                );

                // Insert or update into tbl_vehicle_colors
                DB::table('tbl_vehicle_colors')->updateOrInsert(
                    ['vehicle_id' => 1, 'color' => 1], // Unique check
                    ['created_at' => now(), 'updated_at' => now()]
                );

                // Insert or update into tbl_services
                if ($employee && $customer) {
                    DB::table('tbl_services')->updateOrInsert(
                        ['job_no' => 'J000001'], // Unique check
                        [
                            'service_type' => 'paid',
                            'service_date' => now(),
                            'assign_to' => $emp_id,
                            'service_category' => 'booked vehicle',
                            'done_status' => 1,
                            'charge' => 0,
                            'customer_id' => $cus_id,
                            'vehicle_id' => 1,
                            'create_by' => 1,
                            'branch_id' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                // Insert or update into tbl_purchases
                if ($supplier) {
                    DB::table('tbl_purchases')->updateOrInsert(
                        ['purchase_no' => 'P683409'], // Unique check
                        [
                            'date' => now(),
                            'supplier_id' => $supplier_id,
                            'mobile' => '1234567890',
                            'email' => 'john@gmail.com',
                            'address' => 'XYZ Street Area',
                            'custom_field' => null,
                            'create_by' => 1,
                            'branch_id' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                // Insert or update into tbl_jobcard_details
                if ($customer) {
                    DB::table('tbl_jobcard_details')->updateOrInsert(
                        ['jocard_no' => 'J000001'], // Unique check
                        [
                            'service_id' => 1,
                            'customer_id' => $cus_id,
                            'vehicle_id' => 1,
                            'in_date' => now(),
                            'out_date' => now()->addDays(2),
                            'delay_date' => null,
                            'next_date' => now()->addMonths(1),
                            'kms_run' => '12000',
                            'next_kms_run' => '15000',
                            'done_status' => 1,
                            'coupan_no' => 'COUPON123',
                            'reminder_sent' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });

            return response()->json(['success' => true, 'message' => 'Sample data added/updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Method to check if sample data exists
    public function checkSampleData()
    {
        // Check if data exists in tbl_services or tbl_jobcard_details
        $sampleExists = DB::table('users')->count() > 2
        && DB::table('tbl_services')->exists()
        && DB::table('tbl_vehicles')->exists();  // or any table/condition where sample data might exist

        return response()->json(['sampleExists' => $sampleExists]);
    }

    public function frontendBooking(Request $request)
    {
        $Customer_detail = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $customer_id = $Customer_detail->id;
        // vehicle details
        $vehicle = Vehicle::updateOrCreate(
            // Condition to check if the vehicle already exists based on the number plate
            ['number_plate' => $request->number_plate],
            // Data to update or create
            [
                'vehicletype_id' => $request->vehical_id,
                'vehiclebrand_id' => $request->vehicabrand,
                'fuel_id' => $request->fueltype,
                'modelname' => $request->modelname,
                'customer_id' => $customer_id,
                'branch_id' => 1,
            ]
        );

        // service details
        $jobno = $request->jobno;
        $s_date = $request->s_date;
        $repair_cat = $request->repair_cat;
        $logo = DB::table('tbl_settings')->first();
        $service = new Service;
        $service->job_no = $jobno;
        $service->vehicle_id = $request->vehical_id;
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
        $tbl_jobcard_details->vehicle_id = $request->vehical_id;
        $tbl_jobcard_details->service_id = $service_id;
        $tbl_jobcard_details->jocard_no = $jobno;
        $tbl_jobcard_details->in_date = $s_date;
        $tbl_jobcard_details->save();
        $message = 'Service Booked Successfully';

        return redirect('/service/list')->with('message', $message);
    }

    // free service modal
    public function openmodel(Request $request)
    {
        // $serviceid = Input::get('open_id');
        $serviceid = $request->open_id;

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

        // $discount = null;
        // if (!empty($service_tax->discount)) {
        // 	$discount = $service_tax->discount;
        // }
        $discount = 0;
        if ($service_tax !== null) {
            $discount = $service_tax->discount;
        }

        $logo = DB::table('tbl_settings')->first();

        $html = view('dashboard.freeservice')->with(compact('serviceid', 'tbl_services', 'sales', 'logo', 'job', 's_date', 'vehical', 'customer', 'service_pro', 'service_pro2', 'tbl_service_observation_points', 'service_tax', 'discount', 'service_taxes'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // paid service modal
    public function closemodel(Request $request)
    {
        // $serviceid = Input::get('open_id');
        $serviceid = $request->open_id;

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

        $tbl_service_observation_points = DB::table('tbl_service_observation_points')->where('services_id', '=', $serviceid)->get();

        $service_tax = DB::table('tbl_invoices')->where('sales_service_id', '=', $serviceid)->first();
        if (! empty($service_tax->tax_name)) {
            $service_taxes = explode(', ', $service_tax->tax_name);
        } else {
            $service_taxes = '';
        }

        $discount = '';
        if ($service_tax == null) {
            // dd("hello");
            $discount = $service_tax;
        }
        $logo = DB::table('tbl_settings')->first();

        $html = view('dashboard.paidservice')->with(compact('serviceid', 'tbl_services', 'sales', 'logo', 'job', 's_date', 'vehical', 'customer', 'service_pro', 'service_pro2', 'tbl_service_observation_points', 'service_tax', 'service_taxes', 'discount'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    // repeat service modal
    public function upmodel(Request $request)
    {
        // $serviceid = Input::get('open_id');
        $serviceid = $request->open_id;

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
        $discount = '';
        if ($service_tax !== null) {
            $discount = $service_tax->discount;
        }
        // $discount = $service_tax->discount;

        $logo = DB::table('tbl_settings')->first();

        $html = view('dashboard.paidservice')->with(compact('serviceid', 'tbl_services', 'sales', 'logo', 'job', 's_date', 'vehical', 'customer', 'service_pro', 'service_pro2', 'tbl_service_observation_points', 'service_tax', 'discount', 'service_taxes'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }
}
