@extends('layouts.app')
@section('content')
<!-- page content -->
<style>@media print {
    .right_col {
        margin-right: 0px !important;
    }
}
 @media screen and (max-width:540px) {
    .printbtn {
      display: none !important;
    }
    
  }
</style>
<div class="right_col" role="main">
    <div id="sales_print" class="col-md-12">

        <!-- <table width="100%" border="0">
            <tbody>
                <tr>
                    <td align="right">
                        <?php $nowdate = date('Y-m-d'); ?>
                        <strong>{{ trans('message.Date') }} : </strong><?php echo date(getDateFormat(), strtotime($nowdate)); ?>
                    </td>
                </tr>
            </tbody>
        </table> -->
        <!-- <div class="modal-header">
        <h3 class="text-center"><?php echo $logo->system_name; ?></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div> -->
        <div class="row mt-1">
            <div class="col-md-7 col-sm-7 col-xs-7 mx-2 w-50">

                <div class="col-md-6 col-sm-12 col-xs-12 printimg position-relative mx-0">
                    <!-- <img src="{{ URL('public/vehicle/service.png') }}" style="width: 250px; height: 90px;"> -->
                    <img src="{{ URL::asset('public/general_setting/' . $logo->logo_image) }}" class="system_logo_img">
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 sale_partmodal mt-2">
                    <p class="mb-0">
                        <img src="{{ URL::asset('public/img/icons/Vector (15).png') }}">
                        <?php
                        echo '' . $logo->email;
                        echo '<br><i class="fa fa-phone fa-lg" aria-hidden="true"></i>&nbsp;' . $logo->phone_number;
                        ?>
                        <br>
                    <div class="col-9 d-flex align-items-start m-1 mx-0">
                        <img src="{{ URL::asset('public/img/icons/Vector (14).png') }}">
                        <div class="col mx-2">
                            <?php

                            $taxNumber = $taxName = null;
                            if (!empty($taxes)) {
                                foreach ($taxes as $tax) {
                                    if (substr_count($tax, ' ') > 1) {
                                        $taxNumberArray = explode(' ', $tax);

                                        $taxName = $taxNumberArray[0];
                                        $taxNumber = $taxNumberArray[2];
                                    }
                                }
                            }

                            echo $logo->address . ' ';
                            echo ', ' . getCityName($logo->city_id);
                            echo ', ' . getStateName($logo->state_id);
                            echo ', ' . getCountryName($logo->country_id);
                            ?>
                        </div>
                    </div>
                    <?php
                    if ($taxName !== null && $taxNumber !== null) {
                        echo '<b> ' . $taxName . ':</b> ' . $taxNumber;
                    }
                    ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4">
                <table class="table invoice_sale_part text-nowrap" width="100%" style="border-collapse:collapse;">
                    <tr>
                        <th class="cname">{{ trans('message.Bill Number :') }} </th>
                        <td class="cname"> <?php echo $sales->bill_no; ?> </td>
                    </tr>
                    <tr>
                        <th class="cname">{{ trans('message.Invoice Number') }} : </th>
                        <td class="cname"> <?php echo getInvoiceNumbersForSalepartInvoice($sales->id); ?> </td>
                    </tr>
                    <tr>
                        <th class="cname">{{ trans('message.Date :') }} </th>
                        <td class="cname"> <?php echo date(getDateFormat(), strtotime($invioce->date)); ?></td>
                    </tr>
                    <tr>
                        <th class="cname">{{ trans('message.Status :') }} </th>
                        <td class="cname"><?php if ($invioce->payment_status == 0) {
                                                echo '<span style="color: rgb(255, 0, 0);">' .  trans('message.Unpaid') . '</span>';
                                            } elseif ($invioce->payment_status == 1) {
                                                echo '<span style="color: rgb(255, 165, 0);">' .  trans('message.Partially paid') . '</span>';
                                            } elseif ($invioce->payment_status == 2) {
                                                echo '<span style="color: rgb(0, 128, 0);">' .  trans('message.Full Paid') . '</span>';
                                            } else {
                                                echo '<span style="color: rgb(255, 0, 0);">' .  trans('message.Unpaid') . '</span>';
                                            }
                                            ?></td>
                    </tr>
                    <!-- <tr>
                        <th class="cname">{{ trans('message.Sale Amount :') }} (<?php echo getCurrencySymbols(); ?>) </th>
                        <td class="cname"><?php echo number_format($invioce->grand_total, 2); ?></td>
                    </tr> -->
                    <?php
                    if ($invioce->details !== null) {
                    ?>
                        <tr>
                            <th class="cname">{{ trans('message.Details') }} </th>
                            <td class="cname"><?php echo $invioce->details; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
        <hr />
        <div class="mx-2">
            <table width="100%" border="0">
                <thead>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6 payment_to">
                            <h4>{{ trans('message.Payment To,') }} </h4>
                            <div class="col-10">
                                <img src="{{ URL::asset('public/img/icons/Vector (14).png') }}">
                                <?php echo getCustomerAddress($sales->customer_id);
                                echo ', '; ?> <?php echo getCustomerCity($sales->customer_id) != null ? getCustomerCity("$sales->customer_id") . ',' : ''; ?><?php echo getCustomerState("$sales->customer_id,");
                                                                                                                                                                echo ', ';
                                                                                                                                                                echo getCustomerCountry($sales->customer_id); ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4 bill_to mx-4">
                            <h4>{{ trans('message.Bill To,') }} </h4>
                            <div class="col-11"> <b><i class="fa fa-user fa-lg"></i></b>&nbsp; <?php echo getCustomerName($sales->customer_id); ?><br>
                                <b><i class="fa fa-phone fa-lg"></i></b>&nbsp;&nbsp;<?php echo getCustomerMobile($sales->customer_id); ?><br>
                                <b><img src="{{ URL::asset('public/img/icons/Vector (15).png') }}" class="m-0"></b>&nbsp;<?php echo getCustomerEmail($sales->customer_id); ?>
                            </div>
                            <?php if (getCustomerTaxid($sales->customer_id) !== null) { ?>
                                <b>{{ trans('message.Tax Id') }} : </b><?php echo getCustomerTaxid($sales->customer_id); ?><br>
                            <?php } ?>
                        </div>
                    </div>
                </thead>
            </table>
            <hr />
            <!-- For Custom Field Customer Module (User table) -->
            @if (!empty($tbl_custom_fields_customers))
            @php $showTableHeading = false; @endphp
            @foreach ($tbl_custom_fields_customers as $tbl_custom_fields_customer)
            @php
            $tbl_custom = $tbl_custom_fields_customer->id;
            $userid = $sales->customer_id;

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
                    $userid = $sales->customer_id;

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

            <div class="table-responsive w-100 pl-22 pr-15">
                <table class="table table-bordered" width="100%" border="0" style="border-collapse:collapse;">
                    <thead>
                        <tr class="printimg cname">
                            <th class="text-start cname fw-bold text-r" colspan="4">
                                {{ trans('message.Part Details') }}
                            </th>
                        </tr>
                        <tr>
                            <th class="text-start cname">{{ trans('message.Manufacturer Name') }} </th>
                            <th class="text-start cname">{{ trans('message.Product Name') }}</th>
                            <th class="text-start cname">{{ trans('message.Quantity') }}</th>
                            <th class="text-start cname">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                            <th class="text-start cname">{{ trans('message.Amount') }} (<?php echo getCurrencySymbols(); ?>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saless as $d)
                        <tr>
                            <td class="text-start cname">{{ getManufacturer($d->product_type_id) }}</td>
                            <td class="text-start cname">{{ getPart($d->product_id)->name }}</td>
                            <td class="text-start cname">{{ $d->quantity }}</td>
                            <td class="text-start cname">{{ $d->price }}</td>
                            <td class="text-end cname">{{ $d->total_price }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($invioce->notes->isEmpty())
            @else
            <div class="row px-3">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <p class="fw-bold overflow-visible h5">{{ trans('message.Notes') }}</p>
                    <div class="row">
                        <ul class="list-unstyled scroll-view mb-0">
                            @foreach ($invioce->notes as $key => $note)
                            <li class="row media event d-flex align-items-center guardian_div my-3 left-border">
                                <div class="media-body col-xl-6 col-md-6 col-sm-6">
                                    <p><strong>Notes By {{ getUserFullName($note->create_by) }} On {{ $note->created_at->setTimezone(Auth::User()->timezone) }}</strong></p>
                                    <p>{{ $note->notes }}</p>
                                </div>
                                <div class="media-body text-end align-items-center col-xl-6 col-md-6 col-sm-6">
                                    <strong>
                                        <p class="text-start mb-0">{{ trans('message.Attachments') }} :</p>
                                    </strong>
                                    @php
                                    $attachments = \App\note_attachments::where('note_id','=', $note->id)->get();
                                    @endphp
                                    @if($attachments->isEmpty())
                                    <p class="text-start text-danger">{{ trans('message.Not Added') }} :</p>
                                    @else
                                    <p class="text-start text-danger">{{ count($attachments) }} attachment(s) :</p>
                                    <!-- @foreach ($attachments as $attachment)
                                        @php
                                        $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                        $attachmentUrl = URL::asset('public/notes/' . basename($attachment->attachment));
                                        @endphp
                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <a href="{{ $attachmentUrl }}" target="_blank">
                                            <img src="{{ $attachmentUrl }}" width="55px" class="rounded me-2">
                                        </a>
                                        @elseif ($extension === 'pdf')
                                        <a href="{{ $attachmentUrl }}" target="_blank">
                                            <img src="{{ asset('public/img/icons/pdf_download.png') }}" width="55px" class="rounded me-2">
                                        </a>
                                        @else
                                        <a href="{{ $attachmentUrl }}" target="_blank">
                                            <img src="{{ asset('public/img/icons/video.png') }}" width="55px" class="rounded me-2">
                                        </a>
                                        @endif
                                        @endforeach -->
                                    @endif
                                </div>
                            </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- For Custom Field -->
            @if (!empty($tbl_custom_fields_salepart))
            @php $showTableHeading = false; @endphp
            @foreach ($tbl_custom_fields_salepart as $tbl_custom_fields_saleparts)
            @php
            $tbl_custom = $tbl_custom_fields_saleparts->id;
            $userid = $sales->id;

            $datavalue = getCustomDataSalepart($tbl_custom, $userid);
            @endphp

            @if ($tbl_custom_fields_saleparts->type == 'radio' && $datavalue != '')
            @php $showTableHeading = true; @endphp
            @elseif ($datavalue != null)
            @php $showTableHeading = true; @endphp
            @endif
            @endforeach

            @if ($showTableHeading)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" border="0" style="border-collapse:collapse;">
                    <tr class="printimg cname">
                        <th class="text-start cname" colspan="2">
                            {{ trans('message.Other Information') }}
                        </th>
                    </tr>

                    @foreach ($tbl_custom_fields_salepart as $tbl_custom_fields_saleparts)
                    <?php
                    $tbl_custom = $tbl_custom_fields_saleparts->id;
                    $userid = $sales->id;

                    $datavalue = getCustomDataSalepart($tbl_custom, $userid);
                    ?>

                    @if ($tbl_custom_fields_saleparts->type == 'radio')
                    @if ($datavalue != '')
                    <?php
                    $radio_selected_value = getRadioSelectedValue($tbl_custom_fields_saleparts->id, $datavalue);
                    ?>
                    <tr>
                        <th class="text-start cname">{{ $tbl_custom_fields_saleparts->label }} :</th>
                        <td class="text-start cname" style="width: 25%;">{{ $radio_selected_value }}</td>
                    </tr>
                    @else
                    <tr>
                        <th class="text-start cname">{{ $tbl_custom_fields_saleparts->label }} :</th>
                        <td class="text-center cname">{{ trans('message.Data not available') }}</td>
                    </tr>
                    @endif
                    @else
                    @if ($datavalue != null)
                    <tr>
                        <th class="text-start cname">{{ $tbl_custom_fields_saleparts->label }} :</th>
                        <td class="text-start cname" style="width: 25%;">{{ $datavalue }}</td>
                    </tr>
                    @else
                    <tr>
                        <th class="text-start cname">{{ $tbl_custom_fields_saleparts->label }} :</th>
                        <td class="text-center cname">{{ trans('message.Data not available') }}</td>
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
                <table class="table table-bordered invoicesale_part" style="border-collapse:collapse; width: 99%;">
                    <thead>

                        <tr class="printimg">
                            <th class="text-end fw-bold cname">{{ trans('message.Description') }}&nbsp;&nbsp;</th>
                            <th class="text-start fw-bold cname">{{ trans('message.Amount') }} (<?php echo getCurrencySymbols(); ?>)</th>
                        </tr>

                    </thead>
                    <tbody>
                        <!-- @foreach ($saless as $d)
                        <tr>
                            <td class="text-end cname"><?php echo $vehicale->name;
                                                        echo ' :'; ?></td>
                            <td class="text-right fw-bold cname gst" style="width: 25%;"><b><?php $total_price = $d->total_price;
                                                                                            echo number_format($total_price, 2); ?></b></td>
                        </tr>
                        @endforeach -->
                        <!-- <tr>
                            <td class="text-right cname" colspan="1"></td>
                        </tr> -->
                        <tr>

                            <td class="text-end end">{{ trans('message.Total Amount') }} (<?php echo getCurrencySymbols(); ?>) :</td>

                            <td class="text-end fw-bold cname gst f-17"><b><?php $total_amt = $salesps->total_price;
                                                                            echo number_format($salesps->total_price, 2); ?></b></td>
                        </tr>
                        <tr>
                            <td class="text-end cname">{{ trans('message.Discount') }} (<?php echo $invioce->discount . '%'; ?>) :
                            </td>
                            <td class="text-end fw-bold cname gst f-17"><b><?php $discount = ($total_amt * $invioce->discount) / 100;
                                                                            echo number_format($discount, 2); ?></b></td>
                        </tr>
                        <tr>
                            <td class="text-end cname">{{ trans('message.Total') }} (<?php echo getCurrencySymbols(); ?>) :</td>
                            <td class="text-end fw-bold cname gst f-17"><b><?php $after_dis_total = $total_amt - $discount;
                                                                            echo number_format($after_dis_total, 2); ?></b></td>
                        </tr>
                        <!-- <tr>
                            <td class="cname" colspan="2"></td>
                        </tr> -->
                        <?php
                        if (!empty($taxes)) {
                            $total_tax = 0;
                            $taxes_amount = 0;
                            $taxName = null;
                            foreach ($taxes as $ser_tax) {
                                $taxes_to_count = getTaxPercentFromTaxTable($ser_tax);

                                $taxes_amount = ($after_dis_total * $taxes_to_count) / 100;

                                $total_tax +=  $taxes_amount;

                                // if (substr_count($tax, ' ') > 1) {
                                //     $taxNumberArray = explode(" ", $tax);

                                //     $taxName = $taxNumberArray[0] . " " . $taxNumberArray[1];
                                // } else {
                                //     $taxName = $tax;
                                // }
                        ?>
                                <tr>
                                    <td class="text-end cname"><?php echo getTaxNameAndPercentFromTaxTable($ser_tax); ?> (%) :</td>
                                    <td class="text-end fw-bold cname gst f-17"><b><?php $taxes_amount;
                                                                                    echo number_format($taxes_amount, 2); ?></b></td>
                                </tr>
                        <?php }
                            $final_grand_total = $after_dis_total + $total_tax;
                        } else {
                            $final_grand_total = $after_dis_total;
                        } ?>
                        <!-- <tr>
                            <td class="text-end cname">{{ trans('message.Grand Total') }} (<?php echo getCurrencySymbols(); ?>)
                                    :</td>
                            <td class="text-right cname"><b><?php $final_grand_total;
                                                            echo number_format($final_grand_total, 2); ?></b></td>
                        </tr> -->



                        <?php
                        $paid_amount = $invioce->paid_amount;
                        $Adjustmentamount = $final_grand_total - $paid_amount; ?>
                        <tr>
                            <td class="text-end cname" width="81.5%">{{ trans('message.Adjustment Amount')}}({{trans('message.Paid Amount')}})
                                (<?php echo getCurrencySymbols(); ?>) :</td>
                            <td class="text-end fw-bold cname gst f-17"><b><?php $paid_amount;
                                                                            echo number_format($paid_amount, 2); ?></b></td>
                        </tr>

                        <tr>
                            <td class="text-end cname" width="81.5%">{{ trans('message.Due Amount') }}
                                ({{ getCurrencySymbols() }}) :</td>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
@endsection