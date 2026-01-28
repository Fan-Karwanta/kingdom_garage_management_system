<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">
    @if (getLangCode()==='hi')body,
    p,
    td,
    div,
    th {
      font-family: freeserif;
    }

    @elseif (getLangCode()==='gu') body,
    p,
    td,
    div,
    th {
      font-family: freeserif;
    }

    @elseif (getLangCode()==='ja') body,
    p,
    td,
    div,
    th {
      font-family: freeserif;
    }

    @elseif (getLangCode()==='zhcn') body,
    p,
    td,
    div,
    th {
      font-family: DejaVu Sans, freeserif; // not working
    }

    @elseif (getLangCode()==='th') body,
    p,
    td,
    div,
    th,
    strong {
      font-family: bitstreamcyberbit, freeserif, garuda, norasi, quivira;
    }

    @elseif (getLangCode()==='mr') body,
    p,
    td,
    div,
    th {
      font-family: freeserif;
    }

    @elseif (getLangCode()==='ta') body,
    p,
    td,
    div,
    th,
    strong {
      font-family: ind_ta_1_001, freeserif;
    }

    @else body,
    p,
    td,
    div,
    th,
    strong {
      font-family: freeserif;
    }

    @endif
  </style>
</head>
<style>
  body {
    font-family: 'Helvetica';
    font-style: normal;
    font-weight: normal;
    color: #333;
  }

  .itemtable th,
  td {
    padding: 0px 14px 6px 14px;
    font-size: 14px;
  }

  #imggrapish {
    margin-top: -50px;
    margin-left: 5px;
  }

  page[size="A4"] {
    background: white;
    width: 21cm;
    height: 29.7cm;
    display: block;
    margin: 0 auto;
    margin-bottom: 0.5cm;
  }

  .mail_img {
    width: 12px;
  }

  .addr_img {
    width: 10px;
  }

  .user_img {
    width: 14px;
    height: 12px;
  }

  .phoneimg {
    width: 14px;
    height: 12px;
    /* margin-bottom: 2px; */
    margin-right: 1px;
  }

  .invoice_detail {
    line-height: 25px;
    font-size: 14px;
  }

  .system_addr_details {
    line-height: 25px;
    font-size: 14px;
  }

  .customer_details {
    line-height: 25px;
    margin-top: 3px;
  }

  .cust_addr_details {
    line-height: 25px;
  }

  .grand_total_modal_invoice {
    background: #EA6B00;
    color: #FFFFFF;
    margin-left: 55%;
    margin-bottom: 0px;
  }

  @media print {

    body,
    page[size="A4"] {
      margin: 0;
      box-shadow: 0;
    }
  }
</style>

<body>

  <div class="row" id="imggrapish">
    <br>
    <table width="100%" border="0" style="margin:0px 8px 0px 8px; font-family:Poppins;">
      <tr>
        <td align="left">
          <h3 style="font-size:18px;"><?php echo $logo->system_name; ?></h3>
        </td>
      </tr>
    </table>
    <hr />
    <table>
      <tbody>
        <tr class="customer_details">
          <td width="15%" style="vertical-align:top;float:left;" align="left">
           
            @php
            // Use absolute file paths instead of asset URLs
            $defaultPath = public_path('general_setting/' . $invoice_pdf->branch->branch_image); 
            $updatedPath = public_path('img/branch/' . $invoice_pdf->branch->branch_image); 
            
            // Check if the updated image exists
            $imagePath = file_exists($updatedPath) ? $updatedPath : $defaultPath;
         @endphp
          <span style="width:100%;">
           <img src="{{$imagePath}}" width="230px" height="100px">
          </span> 
          </td>
          <td style="width: 45%;" vertical-align:top;">
            <span style="float:right; class=" cust_addr_details">
              <b><img src="{{ base_path() }}/public/img/icons/user_img.png" class="user_img"></b> <?php echo getCustomerName($tbl_invoices->customer_id); ?>
              <br>
              <b><img src="{{ base_path() }}/public/img/icons/Vector (14).png" class="addr_img"></b> <?php echo $customer->address;
                                                                                                      echo ', ';
                                                                                                      echo getCityName("$customer->city_id") != null ? getCityName("$customer->city_id") . ', ' : ''; ?><?php echo getStateName("$customer->state_id,");
                                                                                                                                                                                                        echo ', ';
                                                                                                                                                                                                        echo getCountryName($customer->country_id); ?>
              <br>
              <b><img src="{{ base_path() }}/public/img/icons/phoneimg1.png" class="phoneimg"></b> <?php echo "$customer->mobile_no"; ?>
              <br>
              <b><img src="{{ base_path() }}/public/img/icons/Vector (15).png" class=" mail_img"></b> <?php echo $customer->email; ?>
              <br>
              <b>{{ trans('message.Invoice') }} :</b> <?php echo $tbl_invoices->invoice_number; ?>
              <br>
              <b>{{ trans('message.Status :') }}</b><?php if ($tbl_invoices->payment_status == 0) {
                                                      echo '<span style="color: rgb(255, 0, 0);">' .  trans('message.Unpaid') . '</span>';
                                                    } elseif ($tbl_invoices->payment_status == 1) {
                                                      echo '<span style="color: rgb(255, 165, 0);">' .  trans('message.Partially paid') . '</span>';
                                                    } elseif ($tbl_invoices->payment_status == 2) {
                                                      echo '<span style="color: rgb(0, 128, 0);">' .  trans('message.Full Paid') . '</span>';
                                                    } else {
                                                      echo '<span style="color: rgb(255, 0, 0);">' .  trans('message.Unpaid') . '</span>';
                                                    } ?><br>
              <b>{{ trans('message.Date') }} :</b> <?php echo date(getDateFormat(), strtotime($tbl_invoices->date)); ?>
              <br>
              @if(($customer->tax_id) != '')
              <b>{{ trans('message.Tax Id') }} :</b>
              <?php echo $customer->tax_id; ?>
              @endif
              @if(($tbl_invoices->details) != '')
              <b>{{ trans('message.Details') }} :</b> <?php echo $tbl_invoices->details ?? 'Not Added'; ?>
              @endif
            </span>
          </td>

        </tr>
        <tr>
          <td width="50%" style="vertical-align:top;float:left;">
            <span style="float:left; font-size: 14px;" class="system_addr_details">
              <img src="{{ base_path() }}/public/img/icons/Vector (15).png" class="mail_img">
              <?php
              echo '' .$invoice_pdf->branch->branch_email;      //$logo->email; 
              ?>
              <br>
              <img src="{{ base_path() }}/public/img/icons/phoneimg1.png" class="phoneimg">
              <?php
              echo '' .$invoice_pdf->branch->contact_number;        //$logo->phone_number;
              ?>
              <br>
              <div class="col-12 d-flex align-items-start" style="margin-top: 2px;">
                <img src="{{ base_path() }}/public/img/icons/Vector (14).png" class="addr_img">
                <?php
                $taxNumber = $taxName = null;
                if (!empty($service_taxes)) {
                  foreach ($service_taxes as $tax) {

                    if (substr_count($tax, ' ') > 1) {
                      $taxNumberArray = explode(" ", $tax);

                      $taxName = $taxNumberArray[0];
                      $taxNumber = $taxNumberArray[2];
                    }
                  }
                }

                //echo $logo->address ? ', <br>' : '';
                echo  $invoice_pdf->branch->branch_address. ' ';       //   $logo->address
                echo ' ' . getCityName($invoice_pdf->branch->city_id);    //$logo->city_id
                echo ', ' . getStateName($invoice_pdf->branch->state_id);   //$logo->state_id
                echo ', ' . getCountryName($invoice_pdf->branch->country_id);  //$logo->country_id

                // if ($taxName !== null && $taxNumber !== null) {
                //   echo '<br> ' . $taxName . ':- ' . $taxNumber;
                // }

                ?>
              </div>
            </span>
          </td>

        </tr>
      </tbody>
    </table>

    <hr />
    <table class="table " border="0" width="100%" style="border-collapse:collapse;">
      <thead></thead>

      <tbody class="itemtable">
        <tr>
          <th align="left" style="padding:8px;">{{ trans('message.Jobcard Number') }}</th>
          <th align="left" style="padding:8px;">{{ trans('message.Coupon Number') }}</th>
          <th align="left" style="padding:8px;">{{ trans('message.Vehicle Name') }}</th>
          <th align="left" style="padding:8px;">{{ trans('message.Number Plate') }}</th>
          <th align="left" style="padding:8px;">{{ trans('message.In Date') }}</th>
          <th align="left" style="padding:8px;">{{ trans('message.Out Date') }}</th>
        </tr>

        <tr>
          <td align="left" style="padding:8px;"><?php echo "$tbl_services->job_no"; ?></td>
          <td align="left" style="padding:8px;"><?php if (!empty($job->coupan_no)) {
                                                  echo $job->coupan_no;
                                                } else {
                                                  echo trans('message.Paid Service');
                                                } ?></td>
          <td align="left" style="padding:8px;"><?php if (!empty($job->vehicle_id)) {
                                                  echo getVehicleName($job->vehicle_id);
                                                } ?></td>
          <td align="left" style="padding:8px;"><?php if (!empty($job->vehicle_id)) {
                                                  echo getVehicleNumberPlate($job->vehicle_id);
                                                } ?></td>
          <td align="left" style="padding:8px;"><?php if (!empty($job)) {
                                                  echo $job->in_date;
                                                } ?> </td>
          <td align="left" style="padding:8px;"><?php if (!empty($job)) {
                                                  echo $job->out_date;
                                                } ?> </td>
        </tr>
        <tr>
          <th align="left" style="padding:8px;">{{ trans('message.Assigned To') }}</th>
          <th align="left" style="padding:8px;">{{ trans('message.Repair Category') }}</th>
          <th align="left" style="padding:8px;">{{ trans('message.Service Type') }}</th>
          <th align="left" style="padding:8px;" colspan="3">{{ trans('message.Details') }}</th>
        </tr>
        <tr>
          <td align="left" style="padding:8px;"><?php echo getAssignedName($tbl_services->assign_to); ?> </td>
          <td align="left" style="padding:8px;"><?php echo $tbl_services->service_category; ?> </td>
          <td align="left" /* style="padding:8px;"><?php echo $tbl_services->service_type; ?> </td> */
          <td align="left" style="padding:8px;" colspan="3"><?php echo $tbl_services->detail ?? 'Not Added'; ?> </td>
        </tr>
      </tbody>
    </table>
    <hr />
    <?php
    if ($tbl_invoices->notes->isEmpty()) {
    } else {
    ?>
      <table class="table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
        <tbody>
          <tr class="printimg" style=" color:#333;">
            <th align="left" style="padding:8px; font-size:14px;" colspan="2">{{ trans('message.Notes') }}</th>
          </tr>
          <?php
          foreach ($tbl_invoices->notes as $key => $note) {
          ?>
            <tr>
              <td align="left" style="padding:8px;">
                <p><strong>Notes By {{ getUserFullName($note->create_by) }} On {{ $note->created_at->setTimezone(Auth::User()->timezone) }}</strong></p>
                <p>{{ $note->notes }}</p>
              </td>
              <td align="left" style="padding:8px;">
                <strong>
                  <p class="text-start mb-0">{{ trans('message.Attachments') }} :</p>
                </strong>
                <?php
                $attachments = \App\note_attachments::where('note_id', '=', $note->id)->get();
                if ($attachments->isEmpty()) {
                ?>
                  <p class="text-start text-danger">{{ trans('message.Not Added') }} :</p>
                <?php
                } else {
                ?>
                  <p class="text-start text-danger">{{ count($attachments) }} attachment(s)</p>
                <?php } ?>
              </td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    <?php
    }
    ?>

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
                                        <th class="text-start">#</th>
                                        <th class="text-start">{{ trans('message.Product Name') }}</th>
                                        <th class="text-start">{{ trans('message.Observation Point') }}</th>
                                        <th class="text-start">{{ trans('message.Category') }}</th>
                                        <th class="text-start">{{ trans('message.Service Charge') }}</th>
                                        <th class="text-start">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                                        <th class="text-start">{{ trans('message.Quantity') }} </th>
                                        <th class="text-start">{{ trans('message.Discount') }}(%) </th>
                                        <th class="text-start">{{ trans('message.Tax') }}(%) </th>
                                        <th class="text-start">
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
    //}
    $total2 = 0;
    $total3 = 0;
    $total4 = 0;
    $i = 1;
    $mot_status = $tbl_services->mot_status;
    if (!empty($service_pro2) || !empty($washbay_data) || $mot_status == 1) {
    ?>
      <br />
      <table class="table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
        <tbody>
          <tr style="color:#333;">
            <th align="left" style="font-size:14px;padding:8px;" colspan="6">{{ trans('message.Other Service Charges') }}</th>
          </tr>
          <tr>
            <th align="left" style="padding:8px; width: 5%;">#</th>
            <th align="left" style="padding:8px;" colspan="2">{{ trans('message.Charge for') }}</th>
            <th align="left" style="padding:8px;" colspan="2">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
            <th align="left" style="padding:8px; width: 20%;">{{ trans('message.Total Price') }} (<?php echo getCurrencySymbols(); ?>) </th>
          </tr>
          <?php

          if ($washbay_data != null) {
          ?>
            <tr>
              <td align="left" style="padding:8px; width: 5%;"><?php echo $i++; ?></td>
              <td align="left" style="padding:8px;" colspan="2">{{ trans('message.Wash Bay Service') }}</td>
              <td align="left" style="padding:8px;" colspan="2"><?php echo number_format((float) $washbay_data->price, 2); ?></td>
              <td align="right" style="padding:8px; width: 20%;"><?php echo number_format((float) $washbay_data->price, 2); ?></td>
              <?php $total4 += $washbay_data->price; ?>
            </tr>
          <?php
          }
          ?>
          <?php


          if ($mot_status == 1) {
          ?>
            <tr>
              <td align="left" style="padding:8px; width: 5%;"><?php echo $i++; ?></td>
              <td align="left" style="padding:8px;" colspan="2">{{ trans('message.MOT Testing Charges') }}</td>
              <td align="left" style="padding:8px;" colspan="2"><?php echo number_format((float)  $tbl_services->mot_charge, 2); ?></td>
              <td align="right" style="padding:8px; width: 20%;"><?php echo number_format((float)  $tbl_services->mot_charge, 2); ?></td>
              <?php $total3 += $tbl_services->mot_charge; ?>
            </tr>
          <?php
          }
          ?>
          <?php foreach ($service_pro2 as $service_pros) { ?>
            <tr>
              <td align="left" style="padding:8px; width: 5%;"><?php echo $i++; ?></td>
              <td align="left" style="padding:8px;" colspan="2"><?php echo $service_pros->comment; ?></td>
              <td align="left" style="padding:8px;" colspan="2"><?php echo number_format((float) $service_pros->total_price, 2); ?></td>
              <td align="right" style="padding:8px; width: 20%;"><?php echo number_format((float) $service_pros->total_price, 2); ?></td>
              <?php $total2 += $service_pros->total_price; ?>
            </tr>
          <?php } ?>

        </tbody>
      </table>
    <?php } ?>

    <!-- Custom Field Of Customer Module (User table)-->
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
    <br />
    <table class="table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
      <tr class="printimg" style="color:#333;">
        <th align="left" style="padding:8px; font-size:14px;" colspan="2">
          {{ trans('message.Customer Other Details') }}
        </th>
      </tr>
      @foreach ($tbl_custom_fields_customers as $tbl_custom_fields_customer)
      <?php
      $tbl_custom = $tbl_custom_fields_customer->id;
      $userid = $tbl_invoices->customer_id;

      $datavalue = getCustomData($tbl_custom, $userid);
      ?>

      @if ($tbl_custom_fields_customer->type == 'radio')
      @if ($datavalue != '')
      <?php
      $radio_selected_value = getRadioSelectedValue($tbl_custom_fields_customer->id, $datavalue);
      ?>
      <tr>
        <th align="left" style="padding:8px;">
          {{ $tbl_custom_fields_customer->label }} :
        </th>
        <td align="left" style="padding:8px;">{{ $radio_selected_value }}</td>
      </tr>

      @endif
      @else
      @if ($datavalue != null)
      <tr>
        <th align="left" style="padding:8px;">
          {{ $tbl_custom_fields_customer->label }} :
        </th>
        <td align="left" style="padding:8px;">{{ $datavalue }}</td>
      </tr>
      @endif
      @endif
      @endforeach
    </table>
    @endif
    @endif
    <!-- Custom Field Invoice Customer Module (User table)-->

    <!-- Custom Field Of Invoice Module-->

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
    <br />
    <table class="table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
      <tr class="printimg" style="color:#333;">
        <th align="left" style="padding:8px; font-size:14px;" colspan="2">
          {{ trans('message.Other Information Of Invoice') }}
        </th>
      </tr>
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
        <th align="left" style="padding:8px; ">
          {{ $tbl_custom_fields_invoices->label }} :
        </th>
        <td align="left" style="padding:8px;">{{ $radio_selected_value }}</td>
      </tr>
      @endif
      @else
      @if ($datavalue != null)
      <tr>
        <th align="left" style="padding:8px;">
          {{ $tbl_custom_fields_invoices->label }} :
        </th>
        <td align="left" style="padding:8px;">{{ $datavalue }}</td>
      </tr>
      @endif
      @endif
      @endforeach
    </table>
    @endif
    @endif
    <!-- Custom Field Invoice Module End -->

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
    <br />
    <table class="table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
      <tr class="printimg" style="color:#333;">
        <th align="left" style="padding:8px; font-size:14px;" colspan="2">
          {{ trans('message.Other Information Of Service') }}
        </th>
      </tr>
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
        <th align="left" style="padding:8px;">
          {{ $tbl_custom_fields_services->label }} :
        </th>
        <td align="left" style="padding:8px;">{{ $radio_selected_value }}</td>
      </tr>
      @endif
      @else
      @if ($datavalue != null)
      <tr>
        <th align="left" style="padding:8px;">
          {{ $tbl_custom_fields_services->label }} :
        </th>
        <td align="left" style="padding:8px;">{{ $datavalue }}</td>
      </tr>
      @endif
      @endif
      @endforeach
    </table>
    @endif
    @endif
    <!-- For Custom Field Service Module End -->

    <br />
    <table class="table table-bordered itemtable" width="100%" border="1" style="border-collapse:collapse;">
      <tbody>
        <tr>
        <?php
         $terms = $logo->terms_and_condition;

            // Remove all HTML tags and whitespace
            $plainTerms = trim(strip_tags($terms));
            if(!empty($service_taxes)){
                if($plainTerms != '') { echo '<td class="terms_condition" rowspan="8" style="width:53%;vertical-align: top !important;padding:8px; font-family:Google Sans, Roboto, arial, sans-serif">';echo '<br>&nbsp;&nbsp;<b>'.trans("message.Terms & Condition").' </b><br><br>'; echo $logo->terms_and_condition; echo '</td>';}
              }
              else{
                if($plainTerms != '') { echo '<td class="terms_condition" rowspan="7" style="width:53%;vertical-align: top !important; font-family:Google Sans, Roboto, arial, sans-serif">';echo '<br>&nbsp;&nbsp;<b>'.trans("message.Terms & Condition").'</b> <br><br>'; echo $logo->terms_and_condition; echo '</td>';}
              }
        ?>
          <td align="right" style="padding:8px;">{{ trans('message.Fixed Service Charge') }} (<?php echo getCurrencySymbols(); ?>) :</td>
          <td align="right" style="padding:8px; width:20%; font-size: 17px;"><b><?php $fix = $tbl_services->charge;
                                                                      if (!empty($fix)) {
                                                                        echo number_format($fix, 2);
                                                                      } else {
                                                                        echo trans('message.Free Service');
                                                                      } ?></b></td>
        </tr>
        <tr>
          <td align="right" style="padding:8px;">{{ trans('message.Total Service Amount') }} (<?php echo getCurrencySymbols(); ?>) :</td>
          <td align="right" style="padding:8px; font-size: 17px;"><b><?php $total_amt = $total1 + $total2 + $total3 + $total4 + $fix;
                                                                      echo number_format($total_amt, 2); ?></b></td>
        </tr>
        <tr>
          <td align="right" style="padding:8px;">{{ trans('message.Discount') }} (<?php echo $tbl_invoices->discount . '%'; ?>) :</td>
          <td align="right" style="padding:8px; font-size: 17px;"><b><?php $discount = ($total_amt * $tbl_invoices->discount) / 100;
                                                                      echo number_format($discount, 2); ?></b></td>
        </tr>
        <tr>
          <td align="right" style="padding:8px;">{{ trans('message.Total') }} (<?php echo getCurrencySymbols(); ?>) :</td>
          <td align="right" style="padding:8px; font-size: 17px;"><b><?php $after_dis_total = $total_amt - $discount;
                                                                      echo number_format($after_dis_total, 2); ?></b></td>
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

            $total_tax +=  $taxes_amount;

            // if (substr_count($tax, ' ') > 1) {
            //   $taxNumberArray = explode(" ", $tax);

            //   $taxName = $taxNumberArray[0] . " " . $taxNumberArray[1];
            // } else {
            //   $taxName = $tax;
            // }
        ?>
            <tr>
              <td align="right" style="padding:8px;"><b><?php echo getTaxNameAndPercentFromTaxTable($ser_tax); ?> (%) :</b></td>
              <td align="right" style="padding:8px; font-size: 17px;"><b><?php $taxes_amount;
                                                                          echo number_format($taxes_amount, 2); ?></b></td>
            </tr>

        <?php  }
          $final_grand_total = $after_dis_total + $total_tax;
        } else {
          $final_grand_total = $after_dis_total;
        }
        ?>

        <?php
        $paid_amount = $tbl_invoices->paid_amount;
        $Adjustmentamount = $final_grand_total - $paid_amount; ?>

        <tr>
          <td align="right" style="padding:8px;" >
            {{ trans('message.Adjustment Amount') }}({{trans('message.Paid Amount')}})(<?php echo getCurrencySymbols(); ?>) :
          </td>

          <td align="right" style="padding:8px; font-size: 17px;"><b>
              <?php $paid_amount;
              echo number_format($paid_amount, 2); ?></b>
          </td>
        </tr>

        <tr>
          <td align="right" style="padding:8px;" >{{ trans('message.Due Amount') }} (<?php echo getCurrencySymbols(); ?>):

          </td>
          <td align="right" style="padding:8px; font-size: 17px;"><b><?php $Adjustmentamount;
                                                                      echo number_format($Adjustmentamount, 2); ?></b></td>

        </tr>

        <tr class="grand_total_modal_invoice">
          <td align="right" style="padding:8px;">{{ trans('message.Grand Total') }} (<?php echo getCurrencySymbols(); ?>) :</td>
          <td align="right" style="padding:8px; font-size: 17px;"><b><?php $final_grand_total;
                                                                      echo number_format($final_grand_total, 2); ?></b></td>
        </tr>
      </tbody>
    </table>
  </div>


</body>

</html>