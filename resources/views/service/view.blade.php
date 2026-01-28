@extends('layouts.app')
@section('content')
<style>
    .box_color {
        width: 40px;
        height: 10px;
        float: left;
        margin: 0px 5px 3px 0px;
    }

    .table>tbody>tr>td {
        padding: 10px 8px !important;
    }

    .right_side .table_row,
    .table_row {
        border-bottom: 1px solid #dedede;
        float: left;
        width: 100%;
        padding: 1px 0px 4px 2px;

    }

    .member_right {
        border: 1px solid #dedede;
        /* margin-left: 9px; */
    }

    .table_row .table_td {
        padding: 8px 8px !important;

    }

    .report_title {
        float: left;
        font-size: 20px;
        margin-bottom: 10px;
        padding-top: 10px;
        width: 100%;
    }

    .b-detail__head-title {
        border-left: 4px solid #2A3F54;
        padding-left: 15px;
        text-transform: capitalize;

    }

    .b-detail__head-price {
        width: 100%;
        float: right;
        text-align: center;
    }

    .b-detail__head-price-num {
        padding: 4px 34px;
        font: 700 23px 'PT Sans', sans-serif;

    }

    .thumb img {
        border-radius: 0px;
    }


    .item .thumb {
        width: 23%;
        cursor: pointer;
        /* float: left; */
        /* border: 1px solid; */
        margin: 3px;

    }

    .item .thumb img {
        width: 70px;
        height: 70px;
    }

    .item img {
        width: 435px;

    }

    .carousel-inner-1 {
        margin-top: 16px;
    }

    .carousel-inner>.item>a>img,
    .carousel-inner>.item>img,
    .img-responsive,
    .thumbnail a>img,
    .thumbnail>img {
        height: 268px;
        width: 268px;
    }

    .shiptitleright {
        float: right;
    }

    ul.bar_tabs>li.active {
        background: #fff !important;
    }

    img.up_arrow {
        margin-left: 35px;
    }

    img.down_arrow {
        margin-left: 33px;
    }

    div#carousel {
        margin-left: 45px;
        margin-top: -289px;
    }

    @media (max-width: 540px) {
        .view_top1 {
            margin-top: -6rem !important;
            margin-left: 58% !important;
        }

        .col-md-12 {
            /* margin-top: -99px; */
            /* margin-top: 3rem!important; */
        }
    }

    @media (width: 540px) {
        .view_top1 {
            margin-top: 1rem !important;
            margin-left: 5% !important;
        }
    }

    .zoom-container {
        overflow: hidden;
        position: relative;
    }

    .zoom-container img {
        transition: transform 0.25s ease;
        padding:10px !important;
        width: 100% !important;
    }

    .zoom-container:hover img {
        transform: scale(1.5);
    }
    @media (max-width: 460px) {
        .view_page_header_bg {
            height: auto !important;
        }
        .heading_view{
            color: white;
        }
        .zoom-container img {
        width: 100%  !important;
    }
    }
</style>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle">
                                <i class="fa fa-bars sidemenu_toggle"></i>
                            </a>
                            <span class="titleup">
                                <a href="{!! url('/service/list') !!}">
                                    <img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="me-2 back-arrow">
                                    {{ $service->job_no }}</a>
                            </span>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                <div class="row view_page_header_bg ms-0">
                    <div class="row">
                        <div class="col-xl-10 col-md-9 col-sm-10">
                            <div class="user_profile_header_left">
                                <img class="user_view_profile_image" src="{{ URL::asset('public/customer/' . $customer->image) }}">
                                
                                <div class="row">
                                    <div class="view_top1">
                                        <div class="col-xl-12 col-md-12 col-sm-12">
                                            <label class="nav_text h5 user-name fh5">
                                                {{ $customer->name . ' ' . $customer->lastname }}&nbsp;
                                            </label>
                                            @can('customer_edit')
                                            <div class="view_user_edit_btn d-inline">
                                                <a href="{!! url('/service/list/edit/' . $service->id) !!}">
                                                    <img src="{{ URL::asset('public/img/dashboard/Edit.png') }}">
                                                </a>
                                            </div>
                                            @endcan
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-sm-12 nav_text mt-2">
                                            <div class="d-lg-inline">
                                                <i class=" fa fa-phone"></i>
                                                {{ $customer->mobile_no }}
                                            </div>
                                            <div class="d-lg-inline">
                                                <i class=" fa fa-envelope"></i>
                                                {{ $customer->email }}
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-sm-12 heading_view mt-3" style="width: 90%;">
                                            <i class="fa-solid fa-location-dot"></i>
                                            <lable class="">
                                                {{ $customer->address }}
                                                <?php echo getCityName($customer->city_id) != null ? getCityName($customer->city_id) . ',' : ''; ?>{{ getStateName($customer->state_id) }}, {{ getCountryName($customer->country_id) }}
                                            </lable>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <div class="view_top1">
                                            <div class="row">
                                                <div class="col-md-12 heading_view"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-2">
                            <div class="group_thumbs">
                                <img src="{{ URL::asset('public/img/dashboard/Design.png') }}" height="93px" width="134px">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body padding_0 pb-0">
                    <div class="row mt-4">
                        <div class="col-xl-12 col-md-12 col-sm-12 pt-3">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active">
                                    <a href="{!! url('/service/list/view/' . $service->id) !!}" class="tab active fw-bold">
                                        {{ trans('message.GENERAL') }}
                                    </a>
                                </li>
                                <li class="text-uppercase">
                                    <a href="{!! url('/service/list/view/notes/' . $service->id) !!}" class="tab fw-bold">
                                        {{ trans('message.Notes') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row margin_top_15px mx-1">
                        <div class="col-xl-3 col-md-3 col-sm-6">
                            <label class="">{{ trans('message.Jobcard Number') }}</label>
                            <br>
                            <label class="fw-bold">
                                {{ $service->job_no }}
                                <br>
                            </label>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6">
                            <label class="">{{ trans('message.Vehicle Name') }}</label>
                            <br>
                            <label class="fw-bold">
                                {{ getVehicleName($service->vehicle_id) }}
                                <br>
                            </label>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6">
                            <label class="">{{ trans('message.Number Plate') }}</label>
                            <br>
                            <span class="txt_color fw-bold">
                                {{ getVehicleNumberPlate($service->vehicle_id) }}
                            </span>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6">
                            <label class="">{{ trans('message.Assigned To') }}</label>
                            <br>
                            <span class="txt_color fw-bold">
                                {{ getAssignedName($service->assign_to) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xl-6 col-md-6 col-sm-6">
                            <div class="guardian_div pb-0">
                                <div class="center-container mb-3">
                                    <section id="selected-image" class="zoom-container">
                                        <img src="{{ $available1[0] }}" alt="Selected Image" height="200px" class="center-image" id="zoomed-image">
                                    </section>
                                </div>
                                <div class="scroll-image">
                                    @foreach ($available1 as $key => $imageURL)
                                    <a href="javascript:void(0);" onclick="displayImage('{{ $imageURL }}')">
                                        <img src="{{ $imageURL }}" alt="Image {{ $key + 1 }}" height="50px" class="vehicleImg">
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6">
                            <div class="guardian_div mb-2 pb-2">
                                <p class="fw-bold overflow-visible h5"> {{ trans('message.More Info') }}.</p>
                                <div class="row">
                                    <!-- <div class="col-xl-12 col-md-12 col-sm-12 mt-1 ps-1">
                                        <img src="{{ URL::asset('public/img/icons/Vector (15).png') }}" class="m-1">
                                        <label class="fw-bold d-inline">
                                            {{ $logo->email }}
                                        </label>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 mt-1">
                                        <img src="{{ URL::asset('public/img/icons/Vector (14).png') }}">&nbsp;
                                        <label class="fw-bold d-inline">
                                            {{ $logo->address }} {{ getCityName($logo->city_id) }} {{ getStateName($logo->state_id) }} {{ getCountryName($logo->country_id) }}
                                        </label>
                                    </div>
                                    <hr> -->
                                    <!-- <div class="col-xl-12 col-md-12 col-sm-12 mt-1">
                                        <label class=""> {{ trans('message.Coupon Number') }} :</label>
                                        <label class="fw-bold">
                                            {{ trans('message.Paid Service') }}
                                        </label>
                                    </div> -->
                                    <div class="col-xl-12 col-md-12 col-sm-12 mt-1">
                                        <label class=""> {{ trans('message.In Date') }} :</label>
                                        <label class="fw-bold">
                                            {{ $job_card_data->in_date }}
                                        </label>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 mt-1">
                                        <label class=""> {{ trans('message.Out Date') }} :</label>
                                        <label class="fw-bold">
                                            {{ $job_card_data->out_date }}
                                        </label>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 mt-1">
                                        <label class=""> {{ trans('message.Repair Category') }} :</label>
                                        <label class="fw-bold">
                                            {{ ucwords($service->service_category) }}
                                        </label>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 mt-1">
                                        <label class=""> {{ trans('message.Service Type') }} :</label>
                                        <label class="fw-bold">
                                            {{ trans('message.' . ucwords($service->service_type)) }}
                                        </label>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 mt-1">
                                        <label class=""> {{ trans('message.Details') }} :</label>
                                        <label class="fw-bold">
                                            {{ $service->detail ?? trans('message.Not Added') }}
                                        </label>
                                    </div>
                                    <!-- For Custom Field -->
                                    @if (!empty($tbl_custom_fields))
                                    @php $showTableHeading = false; @endphp
                                    @foreach ($tbl_custom_fields as $tbl_custom_field)
                                    @php
                                    $tbl_custom = $tbl_custom_field->id;
                                    $userid = $service->id;
                                    $datavalue = getCustomDataService($tbl_custom, $userid);
                                    @endphp

                                    @if ($tbl_custom_field->type == 'radio' && $datavalue != '')
                                    @php $showTableHeading = true; @endphp
                                    @elseif ($datavalue != null)
                                    @php $showTableHeading = true; @endphp
                                    @endif
                                    @endforeach

                                    @if ($showTableHeading)
                                    @foreach ($tbl_custom_fields as $tbl_custom_field)
                                    <?php
                                    $tbl_custom = $tbl_custom_field->id;
                                    $userid = $service->id;

                                    $datavalue = getCustomDataService($tbl_custom, $userid);
                                    ?>
                                    @if ($tbl_custom_field->type == 'radio')
                                    @if ($datavalue != '')
                                    <?php
                                    $radio_selected_value = getRadioSelectedValue($tbl_custom_field->id, $datavalue);
                                    ?>
                                    <div class="col-xl-12 col-md-12 col-sm-12 mt-1">
                                        <label class=""> {{ $tbl_custom_field->label }}
                                            :</label>
                                        <label class="fw-bold">{{ $radio_selected_value }}</label>
                                    </div>
                                    @endif
                                    @else
                                    @if ($datavalue != null)
                                    <div class="col-xl-12 col-md-12 col-sm-12 mt-1">
                                        <label class=""> {{ $tbl_custom_field->label }}
                                            :</label>
                                        <label class="fw-bold">{{ $datavalue }}</label>
                                    </div>
                                    @endif
                                    @endif
                                    @endforeach
                                    </table>
                                    @endif
                                    @endif
                                    <!-- For Custom Field End -->

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xl-12 col-md-12 col-sm-12">
                            <div class="guardian_div mb-3">
                                <div class="x_title row mb-0">
                                    <div class="col-10 ps-0">
                                        <p class="padding_8 fw-bold overflow-visible h5">
                                            {{ trans('message.Charges') }}
                                        </p>
                                    </div>
                                    <?php
                                    $total1 = 0;
                                    $i = 1;
                                    if (!empty($all_data)) {
                                    ?>
                                        <div class=" table-responsive">
                                            <table class="table table-bordered mt-1 mb-2" width="98%" border="0">
                                                <tbody>
                                                    <tr class="printimg">
                                                        <td class="cname fw-bold">{{ trans('message.Observation Charges') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class=" table-responsive">
                                            <table class="table table-bordered adddatatable m-auto" width="98%" border="1">
                                                <thead>
                                                    <tr>
                                                        <th class="text-start" style="width: 5%;">#</th>
                                                        <th class="text-start">{{ trans('message.Category') }}</th>
                                                        <th class="text-start">{{ trans('message.Observation Point') }}</th>
                                                        <th class="text-start">{{ trans('message.Service Charge') }}</th>
                                                        <th class="text-start">{{ trans('message.Product Name') }}</th>
                                                        <th class="text-start">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                                                        <th class="text-start">{{ trans('message.Quantity') }} </th>
                                                         <th class="text-start">{{ trans('message.Discount') }}(%) </th>
                                                          <th class="text-start">{{ trans('message.Tax') }} (%)</th>
                                                        <th class="text-start" style="width: 25%;">{{ trans('message.Total Price') }} (<?php echo getCurrencySymbols(); ?>) </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($all_data as $ser_proc) { ?>
                                                        <tr>
                                                            <td class="text-start cname"><?php echo $i++; ?></td>
                                                            <td class="text-start cname"> <?php echo $ser_proc->category; ?></td>
                                                            <td class="text-start cname"> <?php echo $ser_proc->obs_point; ?></td>
                                                            <td class="text-start cname"> <?php echo number_format((float) $ser_proc->service_charge, 2); ?></td>
                                                            <td class="text-start cname"> <?php echo getProduct($ser_proc->product_id); ?></td>
                                                            <td class="text-start cname"> <?php echo number_format((float) $ser_proc->price, 2); ?></td>
                                                            <td class="text-start cname"><?php echo $ser_proc->quantity; ?></td>
                                                            <td class="text-start cname"><?php echo $ser_proc->discount ?? 0; ?></td>
                                                            <td class="text-start cname"><?php echo $ser_proc->tax ?? 0; ?></td>
                                                            <td class="text-end cname"><?php echo number_format((float) $ser_proc->total_price, 2); ?></td>

                                                            <?php if (!empty($ser_proc->total_price)) {
                                                                $total1 += $ser_proc->total_price;
                                                            } ?>
                                                        </tr>
                                                <?php
                                                    }
                                                } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                        $mot_status = $service->mot_status;
                                        $total2 = 0;
                                        $i = 1;
                                        if (!empty($all_data2) || !empty($washbay_data) || $mot_status == 1) {
                                        ?>
                                            <div class="table-responsive">
                                                <table class="table table-bordered mt-3 mb-2" width="98%" border="0">
                                                    <tr class="printimg">
                                                        <td class="cname fw-bold" colspan="7">{{ trans('message.Other Service Charges') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered adddatatable m-auto" width="98%" border="1">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-start" style="width: 5%;">#</th>
                                                            <th class="text-start">{{ trans('message.Charge for') }}</th>
                                                            <!-- <th class="text-start">{{ trans('message.Product Name') }}</th> -->
                                                            <th class="text-start">{{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>)</th>
                                                            <th class="text-start" style="width: 25%;">{{ trans('message.Total Price') }} (<?php echo getCurrencySymbols(); ?>) </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Washbay Service Charge Details Start -->
                                                        <?php
                                                        $total4 = 0;

                                                        if ($washbay_data != null) {
                                                        ?>
                                                            <tr>
                                                                <td class="text-start cname"><?php echo $i++; ?></td>
                                                                <td class="text-start cname">{{ trans('message.Wash Bay Service') }}</td>
                                                                <td class="text-start cname"><?php echo number_format((float) $washbay_data->price, 2); ?></td>
                                                                <td class="text-end cname"><?php echo number_format((float) $washbay_data->price, 2); ?></td>
                                                                <?php $total4 += $washbay_data->price; ?>
                                                            </tr>
                                                            <!-- Washbay Service Charge Details End -->

                                                            <!-- MOT Test Service Charge Details Start -->
                                                        <?php
                                                        }

                                                        $total3 = 0;

                                                        if ($mot_status == 1) {

                                                        ?>
                                                            <tr>
                                                                <td class="text-start cname"><?php echo $i++; ?></td>
                                                                <td class="text-start cname">{{ trans('message.MOT Testing Charges') }}</td>
                                                                <!-- <td class="text-start cname">{{ trans('message.Completed') }}</td> -->
                                                                <td class="text-start cname"><?php echo number_format((float) $service->mot_charge, 2); ?></td>
                                                                <td class="text-end cname"><?php echo number_format((float) $service->mot_charge, 2); ?></td>
                                                                <?php $total3 += $service->mot_charge; ?>
                                                            </tr>
                                                            <!-- MOT Test Service Charge Details End -->
                                                        <?php
                                                        }
                                                        foreach ($all_data2 as $ser_proc2) { ?>
                                                            <tr>
                                                                <td class="text-start cname"><?php echo $i++; ?></td>
                                                                <!-- <td class="text-start cname">{{ trans('message.Other Charges') }}</td> -->
                                                                <td class="text-start cname"><?php echo $ser_proc2->comment; ?></td>
                                                                <td class="text-start cname"><?php echo number_format((float) $ser_proc2->total_price, 2); ?></td>
                                                                <td class="text-end cname"><?php echo number_format((float) $ser_proc2->total_price, 2); ?></td>
                                                                <?php if (!empty($ser_proc2->total_price)) {
                                                                    $total2 += $ser_proc2->total_price;
                                                                } ?>
                                                            </tr>
                                                        <?php
                                                        }
                                                    } else { ?>
                                                        <!-- <tr>
                            <td class="cname text-center" colspan="5">{{ trans('message.No data available in table.') }}</td>
                        </tr> -->


                                                    <?php
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- @if($service->notes->isEmpty())
            @else
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <p class="fw-bold overflow-visible h5">{{ trans('message.Notes') }}</p>
                    <div class="row mx-1">
                        <ul class="list-unstyled scroll-view mb-0">
                            @foreach ($service->notes as $key => $note)
                            <li class="row media event d-flex align-items-center guardian_div my-3 left-border">
                                <div class="media-body col-xl-6 col-md-6 col-sm-6">
                                    <p><strong>Notes By {{ getUserFullName($note->create_by) }} On {{ $note->created_at->setTimezone(Auth::User()->timezone) }}</strong></p>
                                    <p>{{ $note->notes }}</p>
                                </div>
                                <div class="text-end col-xl-6 col-md-6 col-sm-6">
                                    <strong>
                                        <p class="text-center">{{ trans('message.Attachments') }} :</p>
                                    </strong>
                                    @php
                                    $attachments = \App\note_attachments::where('note_id','=', $note->id)->get();
                                    @endphp
                                    @if($attachments->isEmpty())
                                    <p class="text-center text-danger">{{ trans('message.Not Added') }} :</p>
                                    @else
                                    @foreach ($attachments as $attachment)
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
                                    @endforeach
                                    @endif
                                </div>
                            </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
            @endif -->
        </div>
    </div>
</div>
</div>
</div>
<!-- /page content -->
<script nonce="{{ $cspNonce }}">
    function displayImage(imageURL) {
        // Get the 'selected-image' section
        var selectedImageSection = document.getElementById("selected-image");

        // Create an image element
        var image = new Image();
        image.src = imageURL;
        image.alt = "Selected Image";
        image.style.height = "200px";
        // image.style.width = "400px";
        image.id = "zoomed-image";

        // Clear any previous content in the 'selected-image' section
        selectedImageSection.innerHTML = "";

        // Append the selected image to the 'selected-image' section
        selectedImageSection.appendChild(image);
    }
</script>
@endsection