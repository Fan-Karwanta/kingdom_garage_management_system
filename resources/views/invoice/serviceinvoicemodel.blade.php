<html>

<head>
    <script nonce="{{ $cspNonce }}" language="javascript">
        function PrintElem(el) {
            if ("{{ $page_action }}" === "mobile_app") {
                window.location.href = "{{ url('invoice/servicepdf/' . $invoice->id) }}?page_action={{ $page_action }}";
            } else {
                var modalContentUrl = "{{ url('/jobcard/modalprint') }}?job_no={{ $tbl_services->job_no }}";
                // Open modal content URL in a new tab
                var newTab = window.open(modalContentUrl, '_blank');
                newTab.onload = function() {                
                    var spinner = newTab.document.querySelector('.loading-spinner'); // Replace with your actual class or ID
                    if (spinner) spinner.style.display = 'none';

                    // Delay printing to ensure the spinner is removed
                    setTimeout(() => {
                        newTab.print();
                    }, 300);
                };
            }
        }
    </script>
    <!-- <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/datatables.net/media/js/jquery.dataTables.min.js') }}"></script> -->
    <style>
   .grand_total_freeservice {
    float: {{ getLangCode() === 'ar' ? 'left' : 'right' }};
  }
 @media screen and (max-width:540px) {
    .printbtn {
      display: none !important;
    }
    
  }
 </style>
</head>

<body>
    <div id="sales_print">

        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
                Session::forget('success');
            @endphp
        </div>
        @endif

        <div class="modal-header">
            <h3 class="text-start"><?php echo $logo->system_name; ?></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        
            <div class="modal-body">
                <!-- <table border="0" width="100%">
                    <tbody>
                        <tr>
                            <td align="right">
                                <?php $nowdate = date('y-m-d'); ?>
                                <strong>{{ trans('message.Date') }} : </strong><?php echo date(getDateFormat(), strtotime($nowdate)); ?>
                            </td>
                        </tr>
                    </tbody>
                </table> -->
                
                <!-- \Log::debug("address");
                \Log::debug($branchDatas); -->
                <div class="row mt-2 mb-0">
                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                        <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 printimg position-relative mx-0">
                            <!-- <img src="{{ URL('public/vehicle/service.png') }}" class="system_logo_img"> -->
                            @php
                           // Define the paths
                          $defaultPath = asset('public/general_setting/' . $invoice->branch->branch_image);
                          $updatedPath = asset('public/img/branch/' . $invoice->branch->branch_image);
        
                        // Check if the updated image exists (you can also use File::exists if needed)
                       $imagePath = file_exists(public_path('img/branch/' . $invoice->branch->branch_image)) ? $updatedPath : $defaultPath;
                       @endphp
                            <!-- <img src="{{ url::asset('public/general_setting/'.$invoice->branch->branch_image) }}" class="system_logo_img">  .$logo->logo_image old image data -->
                            <img src="{{ $imagePath }}" class="system_logo_img">
                        </div>
                        
                        <div class="row col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ms-0 mt-3 serviceinvoicemodal">
                            <p class="mb-0">
                                <img src="{{ URL::asset('public/img/icons/Vector (15).png') }}">
                               
                                <?php
                                     echo '  '.$invoice->branch->branch_email;     //.$logo->email;
                                    echo '<br><i class="fa fa-phone fa-lg"></i>&nbsp;&nbsp;' .$invoice->branch->contact_number; //$logo->phone_number;
                                ?>        
                                        <div class="col-12 d-flex align-items-start m-1 mx-0">
                                            <img src="{{ url::asset('public/img/icons/Vector (14).png') }}">
                                                               
                                            <!-- <div class="col mx-2"> 
                                                    <?php 
                                                        echo $invoice->branch->branch_address . ' '; 
                                                    
                                                        // echo $branchData->branch_name;
                                                        // echo $customer->address;
                                                                   
                                                        // echo $customer->address;
                                                        // echo $branchDatas->branch_address;
                                                        // echo $branchData->city_id;
                                                        // echo $settings_data->address . ' ';
                                                        // echo $branchData->branch_address . ' '; 
                                                        // echo $settings_data->address . ' '; 
                                                        // echo $branch->branch_address = $address . ' ';
                                                    ?>
                                                </div>  -->
                                            
                                            <div class="col mx-2">
                                                <?php
                                                    echo $invoice->branch->branch_address.' ';
                                                    echo ', ' . getCityName($invoice->branch->city_id);
                                                    echo ', ' . getStateName($invoice->branch->state_id);
                                                    echo ', ' . getCountryName($invoice->branch->country_id);
                                                ?>
                                            
                                            </div>
                                        </div>
                                  
                                        <br>
                                    
                                <!-- <div class="col-6 m-1 mx-0">
                                    <?php
                                    if ($taxName !== null && $taxNumber !== null) {
                                        echo '<b>' . $taxName . ':&nbsp;</b>' . $taxNumber;
                                    }
                                    ?>
                                </div> -->
                            </p>
                        </div>
                       
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 mx-0 ps-4">
                        <table class="table halfpaidview">  
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                        <p class="fw-bold mb-0"><i class="fa fa-user fa-lg"></i></p>
                                        <p class="cname mb-0 ps-2"><?php echo getCustomerName($tbl_services->customer_id); ?></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                        <p class="fw-bold mb-0"><img src="{{ URL::asset('public/img/icons/Vector (14).png') }}"></p>
                                        <p class="cname mb-0 ps-2"><?php echo $customer->address;
                                                                    echo ', ';
                                                                    echo getCityName("$customer->city_id") != null ? getCityName("$customer->city_id") . ', ' : ''; ?><?php echo getStateName("$customer->state_id,");
                                                                                                                                                                        echo ', ';
                                                                                                                                                                        echo getCountryName($customer->country_id); ?></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                        <p class="fw-bold mb-0"><i class="fa fa-phone fa-lg"></i></p>
                                        <p class="cname mb-0 ps-2"><?php echo "$customer->mobile_no"; ?></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                        <p class="fw-bold mb-0"><img src="{{ URL::asset('public/img/icons/Vector (15).png') }}" class="m-0"></p>
                                        <p class="cname mb-0 ps-2"><?php echo "$customer->email"; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                        <p class="fw-bold mb-0">{{ trans('message.Invoice') }}:</p>
                                        <p class="cname mb-0 ps-2"><?php echo getInvoiceNumbersForServiceInvoice($tbl_services->job_no); ?></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                        <p class="fw-bold mb-0">{{ trans('message.Status :') }}</p>
                                        <p class="cname mb-0 ps-2"><?php if ($invoice->payment_status == 0) {
                                                                        echo '<span style="color: rgb(255, 0, 0);">' .  trans('message.Unpaid') . '</span>';
                                                                    } elseif ($invoice->payment_status == 1) {
                                                                        echo '<span style="color: rgb(255, 165, 0);">' .  trans('message.Partially paid') . '</span>';
                                                                    } elseif ($invoice->payment_status == 2) {
                                                                        echo '<span style="color: rgb(0, 128, 0);">' .  trans('message.Full Paid') . '</span>';
                                                                    } else {
                                                                        echo '<span style="color: rgb(255, 0, 0);">' .  trans('message.Unpaid') . '</span>';
                                                                    }
                                                                    
                                                                    ?>
                                        </p>
                                    </div>
                                </div>
                                <?php
                                if ($service_tax !== null) {
                                ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                            <p class="fw-bold mb-0">{{ trans('message.Date :') }}</p>
                                            <p class="cname mb-0 ps-2"><?php echo date(getDateFormat(), strtotime($service_tax->date)); ?></p>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <?php
                                if ($customer->tax_id !== null) {
                                ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                            <p class="fw-bold mb-0">{{ trans('message.Tax Id') }}:</p>
                                            <p class="cname mb-0 ps-2">{{ $customer->tax_id }}</p>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                                <?php
                                if ($invoice->details !== null) {
                                ?>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                            <p class="fw-bold mb-0">{{ trans('message.Details') }}:</p>
                                            <p class="cname mb-0 ps-2"><?php echo $invoice->details ?? 'Not Added'; ?></p>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </table>
                    </div>
                    <hr />
                    <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                        <table class="table halfpaymentview" border="1" style="border-collapse:collapse;" width="100%">
                            <thead>
                                <tr>
                                    <th class="cname text-start">{{ trans('message.Jobcard Number') }}</th>
                                    <th class="cname text-start">{{ trans('message.Coupon Number') }}</th>
                                    <th class="cname text-start">{{ trans('message.Vehicle Name') }}</th>
                                    <th class="cname text-start">{{ trans('message.Number Plate') }}</th>
                                    <th class="cname text-start">{{ trans('message.In Date') }}</th>
                                    <th class="cname text-start">
                                        {{ trans('message.Out Date') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="cname text-start fw-bold"><?php echo "$tbl_services->job_no"; ?></td>
                                    <td class="cname text-start fw-bold"><?php if (!empty($job->coupan_no)) {
                                                                                echo $job->coupan_no;
                                                                            } else {
                                                                                echo trans('message.Paid Service');
                                                                            } ?></td>
                                    <td class="cname text-start fw-bold"><?php if (!empty($job->vehicle_id)) {
                                                                                echo getVehicleName($job->vehicle_id);
                                                                            } ?></td>
                                    <td class="cname text-start fw-bold"><?php if (!empty($job)) {
                                                                                echo getVehicleNumberPlate($job->vehicle_id);
                                                                            } ?></td>
                                    <td class="cname text-start fw-bold"><?php if (!empty($job)) {
                                                                                echo $job->in_date;
                                                                            } ?> </td>
                                    <td class="cname text-start fw-bold">
                                        <?php if (!empty($job)) {
                                            echo $job->out_date;
                                        } ?> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 text-nowrap">
                        <table class="table halfpaymentview" border="1" style="border-collapse:collapse;" width="100%">
                            <thead>
                                <tr>
                                    <th class="cname text-start">{{ trans('message.Assigned To') }}</th>
                                    <th class="cname text-start">{{ trans('message.Repair Category') }}</th>
                                    <th class="cname text-start">{{ trans('message.Service Type') }}</th>
                                    <th class="cname text-start" style="width: 275px; border-right-width: 1px;">
                                        {{ trans('message.Details') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="cname text-start fw-bold"><?php echo getAssignedName($tbl_services->assign_to); ?> </td>
                                    <td class="cname text-start fw-bold"><?php echo ucwords($tbl_services->service_category); ?> </td>
                                    <td class="cname text-start fw-bold"><?php echo ucwords($tbl_services->service_type); ?> </td>
                                    <td class="cname text-start fw-bold" style="width: 275px; border-right-width: 1px;">
                                        <?php echo $tbl_services->detail ?? 'Not Added'; ?> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    @if($invoice->notes->isEmpty())
                    @else
                    <div class="row ps-4">
                        <div class="col-xl-12 col-md-12 col-sm-12">
                            <p class="fw-bold overflow-visible h5">{{ trans('message.Notes') }}</p>
                            <div class="row">
                                <ul class="list-unstyled scroll-view mb-0">
                                    @foreach ($invoice->notes as $key => $note)
                                    <li class="row media event d-flex align-items-center guardian_div my-3 left-border">
                                        <div class="media-body col-xl-6 col-md-6 col-sm-6">
                                            <p><strong>Notes By {{ getUserFullName($note->create_by) }} On {{ $note->created_at->setTimezone(Auth::User()->timezone) }}</strong></p>
                                            <p>{{ $note->notes }}</p>
                                        </div>
                                        <div class="media-body col-xl-6 col-md-6 col-sm-6">
                                            @php
                                            $attachments = \App\note_attachments::where('note_id','=', $note->id)->get();
                                            @endphp
                                            @php
                                            $attachments = \App\note_attachments::where('note_id','=', $note->id)->get();
                                            @endphp
                                            @if($attachments->isEmpty())
                                            <br><br><br><br>
                                            @else
                                            <strong>
                                                <p>{{ trans('message.Attachments') }} :</p>
                                            </strong>
                                            @foreach ($attachments as $attachment)
                                            @php
                                            $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                            $attachmentUrl = URL::asset('public/notes/' . basename($attachment->attachment));
                                            @endphp
                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                            <a href="{{ $attachmentUrl }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{ basename($attachment->attachment) }}" class="text-primary">
                                                <img src="{{ $attachmentUrl }}" width="55px" class="rounded me-2">
                                            </a>
                                            @elseif ($extension === 'pdf')
                                            <a href="{{ $attachmentUrl }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{ basename($attachment->attachment) }}" class="text-primary">
                                                <img src="{{ asset('public/img/icons/pdf_download.png') }}" width="55px" class="rounded me-2">
                                            </a>
                                            @else
                                            <a href="{{ $attachmentUrl }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{ basename($attachment->attachment) }}" class="text-primary">
                                                <img src="{{ asset('public/img/icons/video.png') }}" width="55px" class="rounded me-2">
                                            </a>
                                            @endif
                                            @endforeach
                                            @endif
                                        </div>
                                    </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <?php
                    $total1 = 0;
                    $i = 1;
                    // echo $service_pro[];
                    // Log::debug($service_pro);
                    if ($service_pro === []) {
                    ?>
                        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                        <?php
                    } else { ?>
                            <table class="table table-bordered mx-3" border="0" width="100%" style="border-collapse:collapse;">
                                <tr class="printimg">
                                    <td class="cname" style="font-size: 14px;"><B>{{ trans('message.Observation Charges') }}</B>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered adddatatable" width="100%" border="1" style="border-collapse:collapse;">
                                <thead>
                                    <tr>
                                        <th class="text-start" style="width: 5%;">#</th>
                                        <th class="text-start">{{ trans('message.Product Name') }}</th>
                                        <th class="text-start">{{ trans('message.Observation Point') }}</th>
                                        <th class="text-start">{{ trans('message.Category') }}</th>
                                        <th class="text-start">{{ trans('message.Service Charge') }}</th>
                                        <th class="text-start">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                                        <th class="text-start">{{ trans('message.Quantity') }} </th>
                                        <th class="text-start">{{ trans('message.Discount') }}(%) </th>
                                        <th class="text-start">{{ trans('message.Tax') }}(%) </th>
                                        <th class="text-start" style="width: 25%;">
                                            {{ trans('message.Total Price') }} (<?php echo getCurrencySymbols(); ?>)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($service_pro as $service_pros) { ?>


                                        <!-- <tr>
                                    <td class="cname text-center" colspan="7">
                                        {{ trans('message.No data available in table.') }}</td>
                                </tr> -->

                                        <tr>
                                            <td class="text-start cname"><?php echo $i++; ?></td>
                                            <td class="text-start cname"> <?php echo getProduct($service_pros->product_id); ?></td>
                                            <td class="text-start cname"> <?php echo $service_pros->obs_point; ?></td>
                                            <td class="text-start cname"> <?php echo $service_pros->category; ?></td>
                                            <td class="text-start cname"> <?php echo number_format((float) $service_pros->service_charge, 2); ?></td>
                                            <td class="text-start cname"> <?php echo number_format((float) $service_pros->price, 2); ?></td>
                                            <td class="text-start cname"><?php echo $service_pros->quantity; ?></td>
                                            <td class="text-start cname"><?php echo $service_pros->discount ?? 0; ?></td>
                                            <td class="text-start cname"><?php echo $service_pros->tax ?? 0; ?></td>
                                            <td class="text-end cname">
                                                <?php echo number_format((float) $service_pros->total_price, 2); ?></td>
                                            <?php
                                            if ($service_pros->total_price !== "") {
                                                $total1 += $service_pros->total_price;
                                            } else {
                                                $total1 += 0;
                                            }
                                            ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    <?php }

                    ?>


                    <?php
                    $total2 = 0;
                    $total3 = 0;
                    $total4 = 0;
                    $i = 1;
                    $mot_status = $tbl_services->mot_status;
                    if (sizeof($service_pro2) != 0 || !empty($washbay_data) || $mot_status == 1) {
                    ?>
                        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ms-0">

                            <table class="table table-bordered" border="0" style="border-collapse:collapse;" width="100%">
                                <tr class="printimg">
                                    <td class="cname" style="font-size: 14px;">
                                        <b>{{ trans('message.Other Service Charges') }}</b>
                                    </td>
                                </tr>
                            </table>


                            <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                                <table class="table table-bordered adddatatable" border="0" style="border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <th class="text-start" style="width: 5%;">#</th>
                                            <th class="text-start">{{ trans('message.Charge for') }}</th>
                                            <!-- <th class="text-start">{{ trans('message.Product Name') }}</th> -->
                                            <th class="text-start">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                                            <th class="text-start" style="width: 25%;">
                                                {{ trans('message.Total Price') }} (<?php echo getCurrencySymbols(); ?>)
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <!-- <tr>
                                    <td class="cname text-center" colspan="5">
                                        {{ trans('message.No data available in table.') }}
                                    </td>
                                </tr> -->
                                        <?php


                                        if ($washbay_data != null) {
                                        ?>
                                            <tr>
                                                <td class="text-start cname"><?php echo $i++; ?></td>
                                                <td class="text-start cname">{{ trans('message.Wash Bay Service') }}</td>
                                                <td class="text-start cname"><?php echo number_format((float) $washbay_data->price, 2); ?></td>
                                                <td class="text-end cname">
                                                    <?php echo number_format((float) $washbay_data->price, 2); ?></td>
                                                <?php $total4 += $washbay_data->price; ?>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <?php

                                        if ($mot_status == 1) {

                                        ?>
                                            <tr>
                                                <td class="text-start cname"><?php echo $i++; ?></td>
                                                <td class="text-start cname">{{ trans('message.MOT Testing Charges') }}</td>
                                                <!-- <td class="text-start cname">{{ trans('message.Completed') }}</td> -->
                                                <td class="text-start cname"><?php echo number_format((float)  $tbl_services->mot_charge, 2); ?></td>
                                                <td class="text-end cname">
                                                    <?php echo number_format((float)  $tbl_services->mot_charge, 2); ?></td>
                                                <?php $total3 += $tbl_services->mot_charge; ?>
                                            </tr>

                                        <?php
                                        }
                                        ?>
                                        <?php foreach ($service_pro2 as $service_pros) { ?>
                                            <tr>
                                                <td class="text-start cname"><?php echo $i++; ?></td>
                                                <!-- <td class="text-start cname">{{ trans('message.Other Charges') }}</td> -->
                                                <td class="text-start cname"><?php echo $service_pros->comment; ?></td>
                                                <td class="text-start cname"><?php echo number_format((float) $service_pros->total_price, 2); ?></td>
                                                <td class="text-end cname">
                                                    <?php echo number_format((float) $service_pros->total_price, 2); ?></td>
                                                <?php
                                                if ($service_pros->total_price !== "") {
                                                    $total2 += $service_pros->total_price;
                                                } else {
                                                    $total2 += 0;
                                                }
                                                ?>
                                            </tr>
                                        <?php }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        <?php }

                        ?>

                        <!-- For Custom Field Of Customer Module (User table)-->

                        @if (empty($tbl_custom_fields_customers) == 0)
                        @php $showTableHeading = false; @endphp
                        @foreach ($tbl_custom_fields_customers as $tbl_custom_fields_customer)
                        @php
                        $tbl_custom = $tbl_custom_fields_customer->id;
                        $userid = $tbl_services->customer_id;
                        $datavalue = getCustomData($tbl_custom, $userid);
                        @endphp

                        @if ($tbl_custom_fields_customer->type == 'radio' && $datavalue != '')
                        @php $showTableHeading = true; @endphp
                        @elseif ($datavalue != null)
                        @php $showTableHeading = true; @endphp
                        @endif
                        @endforeach

                        @if ($showTableHeading)
                        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered" border="0" style="border-collapse:collapse;" width="100%">
                                <tr class="printimg">
                                    <td class="cname" style="font-size: 14px;">
                                        <b>{{ trans('message.Customer Other Details') }}</b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered adddatatable" border="1" style="border-collapse:collapse; width:99%">
                                @foreach ($tbl_custom_fields_customers as $tbl_custom_fields_customer)
                                <?php
                                $tbl_custom = $tbl_custom_fields_customer->id;
                                $userid = $tbl_services->customer_id;

                                $datavalue = getCustomData($tbl_custom, $userid);
                                ?>

                                @if ($tbl_custom_fields_customer->type == 'radio')
                                @if ($datavalue != '')
                                <?php
                                $radio_selected_value = getRadioSelectedValue($tbl_custom_fields_customer->id, $datavalue);
                                ?>

                                <tr>
                                    <th class="text-start">{{ $tbl_custom_fields_customer->label }} :</th>
                                    <td class="text-start cname" style="width: 25%;">{{ $radio_selected_value }}</td>
                                </tr>

                                @endif
                                @else
                                @if ($datavalue != null)

                                <tr>
                                    <th class="text-start">{{ $tbl_custom_fields_customer->label }} :</th>
                                    <td class="text-start cname" style="width: 25%;">{{ $datavalue }}</td>
                                </tr>
                                @endif
                                @endif
                                @endforeach
                            </table>
                        </div>
                        @endif
                        @endif
                        <!-- For Custom Field End Customer Module (User table)-->

                        <!-- For Custom Field Of Invoice Module-->

                        @if (!empty($tbl_custom_fields_invoice))
                        @php $showTableHeading = false; @endphp
                        @foreach ($tbl_custom_fields_invoice as $tbl_custom_fields_invoices)
                        @php
                        $tbl_custom = $tbl_custom_fields_invoices->id;
                        $userid = $service_tax->id;

                        $datavalue = getCustomDataInvoice($tbl_custom, $userid);
                        @endphp

                        @if ($tbl_custom_fields_invoices->type == 'radio' && $datavalue != '')
                        @php $showTableHeading = true; @endphp
                        @elseif ($datavalue != null)
                        @php $showTableHeading = true; @endphp
                        @endif
                        @endforeach

                        @if ($showTableHeading)
                        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered" border="0" style="border-collapse:collapse;" width="100%">
                                <tr class="printimg">
                                    <td class="cname" style="font-size: 14px;">
                                        <b>{{ trans('message.Other Information Of Invoice') }}</b>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered adddatatable" border="1" style="border-collapse:collapse; width:99%">
                                @foreach ($tbl_custom_fields_invoice as $tbl_custom_fields_invoices)
                                <?php
                                $tbl_custom = $tbl_custom_fields_invoices->id;
                                $userid = $service_tax->id;

                                $datavalue = getCustomDataInvoice($tbl_custom, $userid);
                                ?>

                                @if ($tbl_custom_fields_invoices->type == 'radio')
                                @if ($datavalue != '')
                                <?php
                                $radio_selected_value = getRadioSelectedValue($tbl_custom_fields_invoices->id, $datavalue);
                                ?>

                                <tr>
                                    <th class="text-start">{{ $tbl_custom_fields_invoices->label }} :</th>
                                    <td class="text-start cname" style="width: 25%;">{{ $radio_selected_value }}</td>
                                </tr>
                                @endif
                                @else
                                @if ($datavalue != null)
                                <tr>
                                    <th class="text-start">{{ $tbl_custom_fields_invoices->label }} :</th>
                                    <td class="text-start cname" style="width: 25%;">{{ $datavalue }}</td>
                                </tr>
                                @endif
                                @endif
                                @endforeach
                            </table>
                        </div>

                        @endif
                        @endif
                        <!-- For Custom Field End -->

                        <!-- For Custom Field Of Service Module-->

                        @if (!empty($tbl_custom_fields_service))
                        @php $showTableHeading = false; @endphp
                        @foreach ($tbl_custom_fields_service as $tbl_custom_fields_services)
                        @php
                        $tbl_custom = $tbl_custom_fields_services->id;
                        $userid = $tbl_services->id;

                        $datavalue = getCustomDataService($tbl_custom, $userid);
                        @endphp

                        @if ($tbl_custom_fields_services->type == 'radio' && $datavalue != '')
                        @php $showTableHeading = true; @endphp
                        @elseif ($datavalue != null)
                        @php $showTableHeading = true; @endphp
                        @endif
                        @endforeach

                        @if ($showTableHeading)

                        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered" border="0" style="border-collapse:collapse;" width="100%">
                                <tr class="printimg">
                                    <td class="cname"><b>{{ trans('message.Other Information Of Service') }}</b></td>
                                </tr>
                            </table>
                        </div>
                        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered adddatatable" border="1" style="border-collapse:collapse; width: 99%;">
                                @foreach ($tbl_custom_fields_service as $tbl_custom_fields_services)
                                <?php
                                $tbl_custom = $tbl_custom_fields_services->id;
                                $userid = $tbl_services->id;

                                $datavalue = getCustomDataService($tbl_custom, $userid);
                                ?>
                                @if ($tbl_custom_fields_services->type == 'radio')
                                @if ($datavalue != '')
                                <?php
                                $radio_selected_value = getRadioSelectedValue($tbl_custom_fields_services->id, $datavalue);
                                ?>

                                <tr>
                                    <th class="text-start">{{ $tbl_custom_fields_services->label }} :</th>
                                    <td class="text-start cname" style="width: 25%;">{{ $radio_selected_value }}</td>
                                </tr>
                                @endif
                                @else
                                @if ($datavalue != null)
                                <tr>
                                    <th class="text-start">{{ $tbl_custom_fields_services->label }} :</th>
                                    <td class="text-start cname" style="width: 25%;">{{ $datavalue }}</td>
                                </tr>
                                @endif
                                @endif
                                @endforeach
                            </table>
                        </div>
                        @endif
                        @endif
                        <!-- For Custom Field End -->

                        <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered halfpaymentcharge" style="border-collapse:collapse; width: 99%;">
                                <tr>
                                    <?php
                                    $terms = $logo->terms_and_condition;

                                    // Remove all HTML tags and whitespace
                                    $plainTerms = trim(strip_tags($terms));
                                    if(!empty($service_taxes)){
                                        if($plainTerms != '') { echo '<td class="terms_condition" rowspan="8" style="width:53%;vertical-align: top !important; font-family:Google Sans, Roboto, arial, sans-serif">';echo '&nbsp;&nbsp;<b>'.trans("message.Terms & Condition").' </b><br><br>'; echo $logo->terms_and_condition; echo '</td>';}
                                    }
                                    else{
                                        if($plainTerms != '') { echo '<td class="terms_condition" rowspan="7" style="width:53%;vertical-align: top !important; font-family:Google Sans, Roboto, arial, sans-serif">';echo '&nbsp;&nbsp;<b>'.trans("message.Terms & Condition").'</b> <br><br>'; echo $logo->terms_and_condition; echo '</td>';}
                                    }
                                    ?>
                                    <td class="text-end cname">
                                        {{ trans('message.Fixed Service Charge') }} (<?php echo getCurrencySymbols(); ?>):
                                    </td>
                                    <td class="text-end fw-bold cname gst f-17" style="width: 20%;"><b><?php $fix = $tbl_services->charge;
                                                                                    if (!empty($fix)) {
                                                                                        echo number_format($fix, 2);
                                                                                    } else {
                                                                                        echo trans('message.Free Service');
                                                                                    } ?></b></td>
                                </tr>
                                <tr>
                                    <td class="text-end cname">
                                        {{ trans('message.Total Service Amount') }} (<?php echo getCurrencySymbols(); ?>) :
                                    </td>
                                    <td class="text-end fw-bold cname gst f-17"><b><?php $total_amt = $total1 + $total2 + $total3 + $total4 + $fix;
                                                                                    echo number_format($total_amt, 2); ?></b></td>
                                </tr>
                                <?php
                                // if (!empty($service_tax->discount)) {
                                ?>
                                <tr>
                                    <td class="text-end cname">{{ trans('message.Discount') }}
                                        (<?php echo $dis = $service_tax->discount . '%'; ?>) :</td>
                                    <td class="text-end fw-bold cname gst f-17"><b><?php $dis = $service_tax->discount;
                                                                                    $discount = ($total_amt * $dis) / 100;
                                                                                    echo number_format($discount, 2); ?></b></td>
                                </tr>
                                <?php
                                // }
                                ?>

                                <tr>
                                    <td class="text-end cname">{{ trans('message.Total') }}
                                        (<?php echo getCurrencySymbols(); ?>) :</td>
                                    <td class="text-end fw-bold cname gst f-17">
                                        <b><?php $after_dis_total = ($discount != null && $discount != 'null') ? $total_amt - $discount : $total_amt;
                                            echo number_format($after_dis_total, 2); ?></b>
                                    </td>
                                </tr>
                                <?php

                                if (!empty($service_taxes)) {
                                    $total_tax = 0;
                                    $taxes_amount = 0;
                                    $taxName = null;
                                    foreach ($service_taxes as $ser_tax) {
                                        // $taxes_per = preg_replace("/[^0-9,.]/", "", $tax);
                                        $taxes_to_count = getTaxPercentFromTaxTable($ser_tax);
                                        $taxes_amount = ($after_dis_total * $taxes_to_count) / 100;

                                        $total_tax +=  $taxes_amount
                                ?>
                                        <tr>
                                            <td class="text-end cname"><?php echo getTaxNameAndPercentFromTaxTable($ser_tax); ?> (%) :
                                            </td>
                                            <td class="text-end fw-bold cname gst f-17"><b><?php $taxes_amount;
                                                                                            echo number_format($taxes_amount, 2); ?></b></td>
                                        </tr>
                                        <?php $final_grand_total = 0; ?>
                                <?php
                                    }

                                    $final_grand_total = $after_dis_total + $total_tax;
                                } else {
                                    $final_grand_total = $after_dis_total;
                                }
                                ?>
                                <!-- <tr>
                                <td class="text-end cname">{{ trans('message.Grand Total') }}
                                        (<?php echo getCurrencySymbols(); ?>) :</td>
                                <td class="text-left cname"><b><?php $final_grand_total;
                                                                echo number_format($final_grand_total, 2); ?></b></td> 
                            </tr> -->



                                <?php
                                if ($service_tax !== null) {
                                    $paid_amount = $service_tax->paid_amount;
                                } else {
                                    $paid_amount = 0;
                                }

                                // $paid_amount = $service_tax->paid_amount;
                                $Adjustmentamount = $final_grand_total - $paid_amount; ?>
                                <tr>
                                    <td class="text-end cname">
                                        {{ trans('message.Adjustment Amount') }}({{trans('message.Paid Amount')}})
                                        (<?php echo getCurrencySymbols(); ?>) :
                                    </td>
                                    <td class="text-end fw-bold cname gst f-17"><b><?php $invoice->paid_amount;
                                                                                    echo number_format($invoice->paid_amount, 2); ?></b></td>
                                </tr>

                                <tr>
                                    <td class="text-end cname">{{ trans('message.Due Amount') }}
                                        ({{ getCurrencySymbols() }}) :
                                    </td>
                                    <td class="text-end fw-bold cname gst f-17"><b><?php $Adjustmentamount;
                                                                                    echo number_format($Adjustmentamount, 2); ?></b></td>
                                </tr>

                                <tr class="large-screen">
                                    <td class="text-right cname" colspan="2">
                                        <div class="row col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 grand_total_freeservice pt-2">
                                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 text-end fullpaid_invoice_list pt-1 mps-0">
                                                {{ trans('message.Grand Total') }}( <?php echo getCurrencySymbols(); ?> ):
                                            </div>
                                            <div class="row col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                                <label class="total_amount pt-1"><?php $final_grand_total;
                                                                                    echo number_format($final_grand_total, 2); ?> </label>
                                            </div>

                                        </div>
                                    </td>
                                </tr>

                                <tr class="small-screen">
                                    <td class="text-end cname text-light" width="81.5%">{{ trans('message.Grand Total') }} (<?php echo getCurrencySymbols(); ?>) :</td>
                                    <td class="text-right fw-bold cname gst text-light text-end"><?php $final_grand_total;
                                                                                                    echo number_format($final_grand_total, 2); ?> </td>
                                </tr>
                            </table>
                        </div>
                        </div>
                </div>
           
            </div>
        
    </div>

    <div class="row me-4 pe-1">
        <div class="col-md-12 col-sm-12 col-xs-12 ms-3 ps-4">
            <div class="col-md-12 col-sm-12 col-xs-12 mpPayWithCardButtonForSmallDevices">
                @if (Auth::user()->role != 'employee')
                @if ($Adjustmentamount != 0 && $Adjustmentamount < 999999 && $p_key !=null) <script nonce="{{ $cspNonce }}" src="https://js.stripe.com/v3/">
                    </script>
                    <form method="get" action="{{ url('stripe/checkout') }}" class="medium" id="medium">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type='hidden' name="invoice_amount" value="{{ $Adjustmentamount }}">
                        <input type='hidden' name="invoice_id" value="{{ $service_tax->id }}">
                        <input type='hidden' name="invoice_no" value="{{ $service_tax->invoice_number }}">
                        <input type="submit" class="submit2  btn btn-default text-right" value="{{ trans('message.Pay with card') }}" data-key="<?php echo $p_key; ?>" data-email="{{ $customer->email }}" data-name="{{ $logo->system_name }}" data-description="Invoice Number - {{ $service_tax->invoice_number }}" data-amount="{{ $Adjustmentamount * 100 }}" data-locale="auto" data-currency="{{ strtolower(getCurrencyCode()) }}" />
                    </form>
                    @endif
                    @endif
            </div>
        </div>
    </div>
    <div class="row mx-0 w-100">
        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 modal-footer ps-2 pl-40 ps-4">

            <button type="button" class="btn btn-outline-secondary printbtn btn-sm mx-2" id="printBtn"><img src="{{ URL('public/img/icons/Print (1).png') }}" class="pdfButton"></button>
            <a href="{{ url('invoice/servicepdf/' . $invoice->id) }}?page_action=default" id="pdfDownloadBtn" class="prints tagAforPdfBtn">
                <button type="button" class="btn btn-outline-secondary pdfButton btn-sm mx-0"><img src="{{ URL('public/img/icons/PDF.png') }}" class="pdfButton"></button></a>
            <a href="" class="prints tagAforCloseBtn"><button type="button" data-bs-dismiss="modal" class="btn btn-outline-secondary closeButton mx-1">{{ trans('message.Close') }}</button></a>
        </div>
    </div>

    @if ($Adjustmentamount > 999999)
    <br />
    <div class="col-md-12 col-sm-12 col-xs-12">
        <h3 class="text-danger" style="text-align: center;">
            <b>{{ trans('message.You can not pay more than 999999 using the card.') }}
        </h3>
        </b>
    </div>
    @endif

    {{-- Stripe API key warning commented out per user request
    @if ($p_key == null)
    <br />

    <div class="col-md-12 col-sm-12 col-xs-12">
        <h4 class="text-danger" style="text-align: center;">
            <b>{{ trans('message.Please update stripe API key details to take payment directly from the card!') }}
        </h4></b>
    </div>
    @endif
    --}}
</body>
<script nonce="{{ $cspNonce }}">
    const printBtn = document.getElementById('printBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function () {
            PrintElem('sales_print');
        });
    }
    const isMobile = window.innerWidth < 500;
    console.log(isMobile);
    const btn = document.getElementById('pdfDownloadBtn');
    if (isMobile && btn) {
      const currentHref = btn.getAttribute('href');
      const updatedHref = currentHref.replace(/page_action=default/, 'page_action=mobile_app');
      btn.setAttribute('href', updatedHref);
    }
</script>

</html>
