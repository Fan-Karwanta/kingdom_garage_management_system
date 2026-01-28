@extends('layouts.app')
@section('content')
<style>
    @media (max-width: 767px) {
        .small-screen {
            background-color: #EA6B00 !important;
        }
    }

    .grand_total_freeservice{
        background-color: #ffffff;
    }
</style>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{!! url('/quotation/list') !!}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                                {{ trans('message.Accept/Reject Quotation') }}</span></a>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
        @include('success_message.message')
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel table_up_div mb-0">
                    <div id="sales_print">

                        <div class="row mx-0">
                            <div class="row">
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6  printimg position-relative ms-2">
                                        <img src="{{ url('/public/general_setting/' . $logo->logo_image) }}" class="system_logo_img">
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 system_address mt-1 ms-1">
                                        <p class="mb-0">
                                            <img src="{{ URL::asset('public/img/icons/Vector (15).png') }}" class="m-2">

                                            <?php
                                            $taxNumber = $taxName = null;
                                            if (!empty($service_taxes)) {
                                                foreach ($service_taxes as $tax) {
                                                    $taxName = getTaxNameFromTaxTable($tax);
                                                    $taxNumber = getTaxNumberFromTaxTable($tax);
                                                }
                                            }
                                            echo ' ' . $logo->email;
                                            echo '<br>&nbsp;&nbsp;<i class="fa fa-phone fa-lg" aria-hidden="true" class="mb-0"></i>&nbsp;&nbsp;&nbsp;&nbsp;' . $logo->phone_number;
                                            ?>
                                        <div class="col-12 d-flex align-items-start m-1">
                                            <img src="{{ URL::asset('public/img/icons/Vector (14).png') }}" class="m-1">
                                            <div class="col mx-2">
                                                <?php
                                                echo '&nbsp;' . $logo->address . ' ';
                                                echo ' ' . getCityName($logo->city_id);
                                                echo ',&nbsp;' . getStateName($logo->state_id);
                                                echo ',&nbsp;' . getCountryName($logo->country_id);
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        if ($taxName !== null && $taxNumber !== null) {
                                            // echo '<br>' . $taxName . ': &nbsp;' . $taxNumber;
                                            echo '<b>&nbsp; ' . $taxName . ' :</b> ' . $taxNumber;
                                        }
                                        ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 quotation">
                                    <table class="table quotation">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                        <p class="fw-bold mb-0"><i class="fa fa-user fa-lg"></i></p>
                                                        <p class="cname mb-0 ps-2"><?php echo getCustomerName($customer->id); ?></p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                        <p class="fw-bold mb-0"><img src="{{ URL::asset('public/img/icons/Vector (14).png') }}"></p>
                                                        <p class="cname mb-0 ps-2"><?php echo getCustomerAddress($customer->id) . ', '; ?> <?php echo getCityName($customer->city_id) != null ? getCityName($customer->city_id) . ', ' : ''; ?> <?php echo getStateName($customer->state_id) . ', ' . getCountryName($customer->country_id); ?></p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                        <p class="fw-bold mb-0"><i class="fa fa-phone fa-lg"></i></p>
                                                        <p class="cname mb-0 ps-2"><?php echo $customer->mobile_no; ?></p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                        <p class="fw-bold mb-0"><img src="{{ URL::asset('public/img/icons/Vector (15).png') }}" class="m-0"></p>
                                                        <p class="cname mb-0 ps-2"><?php echo $customer->email; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                @if (getCustomerCompanyName($customer->id) != '')
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                        <p class="fw-bold mb-0">{{ trans('message.Company') }}:</p>
                                                        <p class="cname mb-0 ps-2"><?php echo getCustomerCompanyName($customer->id); ?></p>
                                                    </div>
                                                </div>
                                                @endif

                                                <?php
                                                if ($customer->tax_id !== null) {
                                                ?>

                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 d-inline-flex">
                                                            <p class="fw-bold mb-0">{{ trans('message.Tax Id') }} :</p>
                                                            <p class="cname mb-0 ps-2"><?php echo $customer->tax_id; ?></p>
                                                        </div>
                                                    </div>
                                                <?php
                                                } ?>

                                            </div>
                                            </tbody>
                                        </div>
                                    </table>

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 table-responsive ms-0 ps-0">
                                    <table class="table table-bordered table-responsive adddatatable quotationDetail mb-0" width="100%">
                                        <span class="border-0">
                                            <thead>
                                                <tr>
                                                    <th class="cname text-left">{{ trans('message.Quotation Number') }}</th>
                                                    <th class="cname text-left">{{ trans('message.Vehicle Name') }}</th>
                                                    <th class="cname text-left">{{ trans('message.Number Plate' ?? '-') }}</th>
                                                    <th class="cname text-left">{{ trans('message.Date') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="cname text-left fw-bold"><?php echo getQuotationNumber($tbl_services->job_no); ?></td>
                                                    <td class="cname text-left fw-bold"><?php echo getVehicleName($tbl_services->vehicle_id); ?></td>
                                                    <td class="cname text-left fw-bold"><?php echo getVehicleNumberPlate($tbl_services->vehicle_id); ?></td>
                                                    <td class="cname text-left fw-bold"><?php echo $tbl_services->service_date; ?></td>
                                                </tr>
                                            </tbody>
                                        </span>
                                    </table>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 table-responsive ms-0 ps-0">
                                    <table class="table table-bordered table-responsive adddatatable quotationDetail mb-0" width="100%">
                                        <span class="border-0">
                                            <thead>
                                                <tr>
                                                    <th class="cname text-left">{{ trans('message.Repair Category') }}</th>
                                                    <th class="cname text-left">{{ trans('message.Service Type') }}</th>
                                                    <th class="cname text-left">{{ trans('message.Details') }}</th>
                                                    <th class="cname text-left"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="cname text-left fw-bold"><?php echo ucwords($tbl_services->service_category); ?></td>
                                                    <td class="cname text-left fw-bold"><?php echo ucwords($tbl_services->service_type); ?></td>
                                                    <td class="cname text-left fw-bold"><?php echo $tbl_services->detail; ?></td>
                                                    <td class="cname text-left fw-bold"> </td>
                                                </tr>
                                            </tbody>
                                        </span>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <?php
                            $total1 = 0;
                            $i = 1;
                            if (!empty($all_data)) {
                            ?>
                                <div class="row mx-0 table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <span class="border-0">
                                            <tbody>
                                                <tr class="printimg">
                                                    <td class="cname fw-bold">{{ trans('message.Observation Charges') }}</td>
                                                </tr>
                                            </tbody>
                                        </span>
                                    </table>
                                </div>
                                <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                                    <table class="table table-bordered adddatatable">
                                        <thead>
                                            <tr>
                                                <th class="text-start" style="width: 5%;">#</th>
                                                <th class="text-left">{{ trans('message.Product Name') }}</th>
                                                <th class="text-left">{{ trans('message.Category') }}</th>
                                                <th class="text-left">{{ trans('message.Observation Point') }}</th>
                                                <th class="text-left">{{ trans('message.Service Charge') }}</th>
                                                <th class="text-left">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                                                <th class="text-left">{{ trans('message.Quantity') }} </th>
                                                <th class="text-left">{{ trans('message.Discount') }}(%) </th>
                                                <th class="text-left">{{ trans('message.Tax') }}(%) </th>
                                                <!-- <th class="text-left">{{ trans('message.Charge') }}</th> -->
                                                <th class="text-left" style="width: 25%;">{{ trans('message.Total Price') }}
                                                    (<?php echo getCurrencySymbols(); ?>)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($all_data as $ser_proc) {
                                            ?>
                                                <tr>
                                                    <td class="text-center cname"><?php echo $i++; ?></td>
                                                    <td class="text-center cname">
                                                        <?php echo isset($ser_proc->product_id) ? getProduct($ser_proc->product_id) : '-'; ?>
                                                    </td>
                                                    <td class="text-center cname">
                                                        <?php echo isset($ser_proc->category) ? $ser_proc->category : '-'; ?>
                                                    </td>
                                                    <td class="text-center cname">
                                                        <?php echo isset($ser_proc->obs_point) ? $ser_proc->obs_point : '-'; ?>
                                                    </td>
                                                    <td class="text-start cname"> <?php echo number_format((float) $ser_proc->service_charge, 2); ?></td>

                                                    <td class="text-center cname">
                                                        <?php echo isset($ser_proc->price) ? number_format((float) $ser_proc->price, 2) : '-'; ?>
                                                    </td>
                                                    <td class="text-center cname">
                                                        <?php echo isset($ser_proc->quantity) ? $ser_proc->quantity : '-'; ?>
                                                    </td>
                                                    <td class="text-center cname">
                                                        <?php echo isset($ser_proc->discount) ? $ser_proc->discount : '-'; ?>
                                                    </td>
                                                    <td class="text-center cname">
                                                        <?php echo isset($ser_proc->tax) ? $ser_proc->tax : '-'; ?>
                                                    </td>

                                                    <?php if (!empty($ser_proc->total_price) && $ser_proc->chargeable != 0) {
                                                        $total1 += $ser_proc->total_price;
                                                    } ?>

                                                    <td class="text-end cname"><?php echo number_format((float) $ser_proc->total_price, 2); ?></td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                        } ?>

                                        </tbody>
                                    </table>
                                </div>

                                <?php
                                $mot_status = $tbl_services->mot_status;
                                $i = 1;
                                $total2 = 0;
                                $total3 = 0;
                                $total4 = 0;
                                if (!empty($all_data2) || !empty($washbay_data) || $mot_status == 1) {
                                ?>


                                    <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ms-0">
                                        <table class="table table-bordered">
                                            <tr class="printimg">
                                                <td class="cname fw-bold" colspan="7">{{ trans('message.Other Service Charges') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="table table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 mb-0">
                                        <table class="table table-bordered adddatatable mx-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 5%;">#</th>
                                                    <th class="text-center">{{ trans('message.Charge for') }}</th>
                                                    <!-- <th class="text-center">{{ trans('message.Product Name') }}</th> -->
                                                    <th class="text-center">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                                                    <th class="text-center" style="width: 25%;">{{ trans('message.Total Price') }}
                                                        (<?php echo getCurrencySymbols(); ?>)
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                if ($washbay_data != null) {
                                                ?>
                                                    <tr>
                                                        <td class="text-center cname"><?php echo $i++; ?></td>
                                                        <td class="text-center cname">{{ trans('message.Wash Bay Service') }}</td>
                                                        <td class="text-center cname"><?php echo number_format((float) $washbay_data->price, 2); ?></td>
                                                        <td class="text-end cname"><?php echo number_format((float) $washbay_data->price, 2); ?></td>
                                                        <?php $total4 += $washbay_data->price; ?>
                                                    </tr>
                                                <?php
                                                }
                                                //$tbl_services->mot_status


                                                if ($mot_status == 1) {
                                                ?>
                                                    <tr>
                                                        <td class="text-center cname"><?php echo $i++; ?></td>
                                                        <td class="text-center cname">{{ trans('message.MOT Testing Charges') }}</td>
                                                        <!-- <td class="text-left cname">{{ trans('message.Completed') }}</td> -->
                                                        <td class="text-center cname"><?php echo number_format((float) $tbl_services->mot_charge, 2); ?></td>
                                                        <td class="text-end cname"><?php echo number_format((float) $tbl_services->mot_charge, 2); ?></td>
                                                        <?php $total3 += $tbl_services->mot_charge; ?>
                                                    </tr>
                                                <?php
                                                } ?>
                                                <?php

                                                if (!empty($all_data2)) {
                                                    foreach ($all_data2 as $ser_proc2) {
                                                ?>
                                                        <tr>
                                                            <td class="text-center cname" style="width: 10px;"><?php echo $i++; ?></td>
                                                            <!-- <td class="text-center cname">{{ trans('message.Other Charges') }}</td> -->
                                                            <td class="text-center cname"><?php echo $ser_proc2->comment; ?></td>
                                                            <td class="text-center cname"><?php echo number_format((float) $ser_proc2->total_price, 2); ?></td>
                                                            <td class="text-end cname"><?php echo number_format((float) $ser_proc2->total_price, 2); ?></td>
                                                            <?php if (!empty($ser_proc2->total_price)) {
                                                                $total2 += $ser_proc2->total_price;
                                                            } ?>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else { ?>

                                            <?php
                                                }
                                            }
                                            ?>
                                            <!-- For Custom Field -->
                                            @if (!empty($tbl_custom_fields))
                                            <div class="table-responsive row mx-0 mb-0">
                                                <table class="table table-bordered">
                                                    <tr class="printimg">
                                                        <td class="cname fw-bold" colspan="">{{ trans('message.Other Information') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="table-responsive col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 mx-1">
                                                <table class="table table-bordered adddatatable" style="width: 98%;">
                                                    @foreach ($tbl_custom_fields as $tbl_custom_field)
                                                    <?php
                                                    $tbl_custom = $tbl_custom_field->id;
                                                    $userid = $tbl_services->id;

                                                    $datavalue = getCustomDataService($tbl_custom, $userid);
                                                    ?>
                                                    @if ($tbl_custom_field->type == 'radio')
                                                    @if ($datavalue != '')
                                                    <?php
                                                    $radio_selected_value = getRadioSelectedValue($tbl_custom_field->id, $datavalue);
                                                    ?>

                                                    <tr>
                                                        <th class="text-center" style="width: 10%;">{{ $tbl_custom_field->label }} :</th>
                                                        <td class="text-left cname">{{ $radio_selected_value }}</td>
                                                    </tr>
                                                    @else
                                                    <tr>
                                                        <th class="text-center" style="width: 10%;">{{ $tbl_custom_field->label }} :</th>
                                                        <td class="text-left cname">
                                                            {{ trans('message.Data not available') }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @else
                                                    @if ($datavalue != null)
                                                    <tr>
                                                        <th class="text-center" style="width: 10%;">{{ $tbl_custom_field->label }} :</th>
                                                        <td class="text-left cname">{{ $datavalue }}</td>
                                                    </tr>
                                                    @else
                                                    <tr>
                                                        <th class="text-center" style="width: 10%;">{{ $tbl_custom_field->label }} :</th>
                                                        <td class="text-left cname">
                                                            {{ trans('message.Data not available') }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                </table>
                                            </div>
                                            @endif
                                            <!-- For Custom Field End -->

                                            <div class="row table-responsive">
                                                <table class="table table-bordered quotation_total">
                                                    <tr>
                                                        <td class="text-end cname" style="width: 75%;">{{ trans('message.Fixed Service Charge') }} (<?php echo getCurrencySymbols(); ?>) :</td>
                                                        <td class="text-end cname fw-bold gst f-17"><?php $fix = $tbl_services->charge;
                                                                                                    if (!empty($fix)) {
                                                                                                        echo number_format($fix, 2);
                                                                                                    } else {
                                                                                                        echo 'Free Service';
                                                                                                    } ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-end cname">{{ trans('message.Total Service Amount') }}
                                                            (<?php echo getCurrencySymbols(); ?>)
                                                            :
                                                        </td>
                                                        <td class="text-end cname fw-bold gst f-17"><b><?php $total_amt = $total1 + $total2 + $total3 + $total4 + $fix;
                                                                                                        echo number_format($total_amt, 2); ?></b></td>
                                                    </tr>
                                                    <?php
                                                    if (!empty($service_taxes)) {
                                                        $all_taxes = 0;
                                                        $total_tax = 0;
                                                        foreach ($service_taxes as $ser_tax) {
                                                            $taxes_to_count = getTaxPercentFromTaxTable($ser_tax);
                                                            $all_taxes = ($total_amt * $taxes_to_count) / 100;
                                                            $total_tax +=  $all_taxes;
                                                    ?>
                                                            <tr>
                                                                <td class="text-end cname"><?php echo getTaxNameAndPercentFromTaxTable($ser_tax); ?> (%) :</td>
                                                                <td class="text-end cname fw-bold gst f-17"><b><?php $all_taxes;
                                                                                                                echo number_format($all_taxes, 2); ?></b></td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    } else {
                                                        $total_tax = 0;
                                                    }
                                                    ?>

                                                    <tr class="">
                                                        <td class="text-end cname" width="75%">{{ trans('message.Grand Total') }} (<?php echo getCurrencySymbols(); ?>) :</td>
                                                        <td class="text-end fw-bold cname gst f-17"><?php $grd_total = $total_amt + $total_tax;
                                                                                                    echo number_format($grd_total, 2); ?></td>
                                                    </tr>
                                                </table>
                                            </div>

                                    </div>
                        </div>
                        <div class="ps-2 ps-lg-0">
                            <form action="status_save/{{ $tbl_services->id }}" method="POST" enctype="multipart/form-data">
                                <div class="row mx-0">
                                    <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 ps-2">
                                        <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Status') }} <label class="color-danger">*</label></label>
                                        <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                            <label class="radio-inline"><input type="radio" name="status" id="accept" value="1" required class="margin-left-10" <?php if ($tbl_services->is_appove == '1') {
                                                                                                                                                                    echo 'checked';
                                                                                                                                                                } ?>>{{ trans('message.Accepted') }}</label>
                                            <label class="radio-inline"><input type="radio" name="status" id="reject" value="0" required class="margin-left-10" <?php if ($tbl_services->is_appove == '0') {
                                                                                                                                                                    echo 'checked';
                                                                                                                                                                } ?>>{{ trans('message.Rejected') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="ps-2 m-3">
                                    <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-success">{{ trans('message.SUBMIT') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
@endsection