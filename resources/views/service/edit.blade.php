@extends('layouts.app')
@section('content')
<style>
    .bootstrap-datetimepicker-widget table td span {
        width: 0px !important;
    }

    .table-condensed>tbody>tr>td {
        padding: 3px;
    }

    .removeimage {
        float: left;
        padding: 5px;
        height: 70px;
    }

    .removeimage .text {
        position: relative;
        bottom: 45px;
        display: block;
        left: 20px;
        font-size: 18px;
        color: red;
        visibility: hidden;
    }

    .removeimage:hover .text {
        visibility: visible;
    }
    #vhi,.repair_category, .select_vhi {
        width: 115% !important;
    }
    .step1 {
        color: #333333 !important;
        /* background-color: #f5f5f5;
            border-color: #ddd; */
        /* padding: 10px 47px; */
        font-size: 16px;

    }
    .tab-container {
    width: 100%;
    margin-bottom: 2%;
    background: #fff;
    overflow: hidden;
}

.tab-menu {
    display: flex;
    background-color: transparent; /* Remove background color of tabs */
    border-bottom: 1px solid #959595; /* Optional: Add a bottom border for the container */
}

.tab-link {
    flex: 1;
    padding: 10px 15px;
    cursor: pointer;
    background: none; /* Remove background color */
    color: black; /* Default text color */
    border: none;
    text-align: center;
    font-size: 16px;
    transition: color 0.3s ease, border-bottom 0.3s ease;
    position: relative;
}

.tab-link:hover {
    color: #EA6B00; /* Hover text color */
}

.tab-link.active {
    color: #EA6B00; /* Active tab text color */
    font-weight: bold; /* Optional: Make the active tab text bold */
    border-bottom: 3px solid #EA6B00; /* Underline for the active tab */
}

.tab-content {
    display: none;
    padding: 20px;
}

.tab-content.active {
    display: block;
}
    @media screen and (max-width: 575px) {
        #service-print{
            margin-left:0px !important;
        }
    }
   
</style>
<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <div class="nav_menu">
            <nav>
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                    <a href="{!! url('/service/list') !!}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                            {{ trans('message.Edit Services') }}</span></a>
                </div>
                @include('dashboard.profile')
            </nav>
        </div>
    </div>
    <div class="tab-container ">
        <div class="tab-menu">
            <button class="tab-link active">{{trans('message.Step 1: Edit Service')}}</button>
            <button id="step2Btn" class="tab-link">{{trans('message.Step 2: Job Card Details')}}</button>
        </div>
    </div>
    <div class="row" id="printable-area">
        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
            <div class="x_panel editServiceDescription mb-0">
                <div class="x_content">

                    <div class="panel-heading step1 titleup">{{ trans('message.STEP - 1 : EDIT SERVICE DETAILS...') }}
                        <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
                    </div>
                    <form id="ServiceEdit-Form" method="post" action="update/{{ $service->id }}" enctype="multipart/form-data" class="form-horizontal upperform">

                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Jobcard Number') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="jobno" class="form-control" value="{{ $service->job_no }}" placeholder="{{ trans('message.Enter Job No') }}" readonly>
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Customer Name') }} <label class="color-danger">*</label></label>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <select name="" id="cust_id" class="form-control select_vhi select_customer_auto_search form-select" cus_url="{!! url('service/get_vehi_name') !!}?v_id={{ request('v_id') }}" required disabled>
                                        <option value="">{{ trans('message.Select Select Customer') }}</option>

                                        @if (!empty($customer))
                                        @foreach ($customer as $customers)
                                        <option value="{{ $customers->id }}" <?php if ($customers->id == $service->customer_id) {
                                                                                    echo 'selected';
                                                                                } ?>>
                                            {{ getCustomerName($customers->id) }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" name="Customername" value="{{ $service->customer_id }}">
                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove customerAddModel mt-0">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-outline-secondary fl margin-left-0">{{ trans('+') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Vehicle Name') }} <label class="color-danger">*</label></label>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <select name="vehicalname" id="vhi" class="form-control modelnameappend select_vehicle form-select " free_url="{!! url('service/get_free_service') !!}" required disabled>

                                        <option value="">{{ trans('message.Select vehicle Name') }}</option>
                                        @if (!empty($vehical))
                                        @foreach ($vehical as $vehicals)
                                        <option value="{{ $vehicals->id }}" <?php if ($vehicals->id == $service->vehicle_id) {
                                                                                echo 'selected';
                                                                            } ?>>
                                            {{ getvehicleBrand($vehicals->vehiclebrand_id) }}/{{ $vehicals->modelname }}/{{ $vehicals->number_plate }}/{{ $vehicals->id }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <input type="hidden" name="vehicalname" value="{{ $service->vehicle_id }}">

                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#vehiclemymodel" class="btn btn-outline-secondary vehiclemodel fl margin-left-0">{{ trans('+') }}
                                    </button>
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="Date">{{ trans('message.Date') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8  date">
                                         <input type='text' class="form-control datepicker" name="date" autocomplete="off" id='p_date' placeholder="<?php echo getDatepicker();
                                         echo ' hh:mm:ss'; ?>" value="<?php echo date(getDateFormat() . ' H:i:s', strtotime($service->service_date)); ?>" onkeypress="return false;" required />    
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Repair Category') }} <label class="color-danger">*</label></label>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <select name="repair_cat" id ="repair_cat" class="form-control repair_category form-select" required>
                                        <option value="">{{ trans('message.-- Select Repair Category--') }}
                                        </option>

                                        @if (!empty($repairCategoryList))
                                        @foreach ($repairCategoryList as $repairCategoryListData)
                                        <option value="<?php echo $repairCategoryListData->slug; ?>" <?php if ($service->service_category == $repairCategoryListData->slug) {
                                                                                                            echo 'selected';
                                                                                                        } ?>>
                                            {{ $repairCategoryListData->repair_category_name }}
                                        </option>
                                        @endforeach
                                        @endif

                                    </select>
                                </div>

                                <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                    <button type="button" data-bs-target="#responsive-modal-color" data-bs-toggle="modal" class="btn btn-outline-secondary fl margin-left-0">{{ trans('+') }}</button>
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Assign To') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <select id="AssigneTo" name="AssigneTo" class="form-control form-select" required>
                                        <option value="">-- {{ trans('message.Select Assign To') }} --
                                        </option>
                                        @if (!empty($employee))
                                        @foreach ($employee as $employees)
                                        <option value="{{ $employees->id }}" <?php if ($employees->id == $service->assign_to) {
                                                                                    echo 'selected';
                                                                                } ?>>
                                            {{ $employees->name }} {{ $employees->lastname }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row row-mb-0">

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-labe col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">{{ trans('message.Service Type') }}
                                    <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <!-- <label class="radio-inline free_service">
                <input type="radio" name="service_type" id="free" value="free" class="free_service" required>{{ trans('message.Free') }}</label> -->
                                    <label class="radio-inline">
                                        <input type="radio" name="service_type" id="paid" value="paid" required checked class="margin-left-10"> {{ trans('message.Paid') }}</label>
                                </div>
                                <div id="freeCouponList"></div>
                            </div>
                            <div id="dvCharge" class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 has-feedback {{ $errors->has('Charge') ? ' has-error' : '' }}">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency" for="last-name">{{ trans('message.Service Charge') }}
                                    (<?php echo getCurrencySymbols(); ?>) <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" id="charge_required" name="charge" class="form-control fixServiceCharge" placeholder="{{ trans('message.Enter Service Charge') }}" value="{{ $service->charge }}" maxlength="8" required>
                                </div>
                            </div>
                        </div>

                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="branch">{{ trans('message.Branch') }} <label class="color-danger">*</label></label>

                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <select class="form-control select_branch form-select" name="branch">
                                        @foreach ($branchDatas as $branchData)
                                        <option value="{{ $branchData->id }}" <?php if ($service->branch_id == $branchData->id) {
                                                                                    echo 'selected';
                                                                                } ?>>
                                            {{ $branchData->branch_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="details">{{ trans('message.Details') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <textarea class="form-control mb-2" name="details" id="details" maxlength="100">{{ $service->detail }}</textarea>
                                </div>
                            </div>


                        </div>
                         <!-- kilometers run -->
                         <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="branch">{{ trans('message.KMS Run') }}: <label class="color-danger">*</label></label>

                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" min='0' id="kms" name="kms" value="<?php if (!empty($services_kms)) {
                                                                                                    echo " $services_kms->kms_run";
                                                                                                } ?>" maxlength="10" class="form-control" required>
                                    </div>
                                </div>
                                 
                            </div>
                        <!-- Wash Bay Feature -->
                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 washbayLabel" for="washbay">{{ trans('message.Wash Bay') }} <label class="text-danger"></label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 washbayInputDiv">
                                    <input type="checkbox" name="washbay" id="washBay" class="washBayCheckbox" <?php echo $washbayPrice ? 'checked' : ''; ?> style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                                </div>
                            </div>

                            <div id="washBayCharge" style="" class="has-feedback {{ $errors->has('washBayCharge') ? ' has-error' : '' }} 
                                    row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency" for="washBayCharge">{{ trans('message.Wash Bay Charge') }} (<?php echo getCurrencySymbols(); ?>)
                                    <label class="text-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" id="washBayCharge_required" name="washBayCharge" class="form-control washbay_charge_textbox" placeholder="{{ trans('message.Enter Wash Bay Charge') }}" value="<?php echo $washbayPrice ? $washbayPrice : ''; ?>" maxlength="10">

                                    <span id="washbay_error_span" class="help-block error-help-block color-danger" style="display:none"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Wash Bay Feature -->
                        <!-- MOt Test Checkbox Start-->
                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 motTextLabel pt-1" for="">{{ trans('message.MOT Test') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="checkbox" name="motTestStatusCheckbox" id="motTestStatusCheckbox" class="motCheckbox form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;" <?php if ($service->mot_status == 1) {
                                                                                                                                                                                                                                                            echo 'checked';
                                                                                                                                                                                                                                                        } ?>>
                                </div>
                            </div>
                            <div id="motTestCharge" class="has-feedback mt-0 {{ $errors->has('motTestCharge') ? ' has-error' : '' }} row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 row">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency" for="motTestCharge">{{ trans('message.MOT Test Charges') }} (<?php echo getCurrencySymbols(); ?>)
                                    <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 mot_charge_detail">
                                    <input type="text" id="motTestCharge_required" name="motTestCharge" class="form-control mot_charge_textbox" placeholder="{{ trans('message.Enter MOT Test Charges') }}" value="<?php echo $service->mot_charge; ?>" maxlength="10">

                                    <span id="mot_error_span" class="help-block error-help-block text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <!-- MOt Test Checkbox End-->


                        <div class="row row-mb-0" >
                            <!-- Service images  -->
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 mt-1">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pt-0">{{ trans('message.Select Multiple Images') }}
                                </label>
                                <div class="form-group col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="file" name="image[]" class="form-control imageclass" data-max-file-size="5M" id="images" onchange="preview_images();" multiple>

                                    <div class="row mt-2 mx-0" id="image_preview">
                                        @if (!empty($images1))
                                        @foreach ($images1 as $images2)
                                        <div class="col-md-4 col-sm-4 col-xs-12 removeimage delete_image" id="image_remove_<?php echo $images2->id; ?>" imgaeid="{{ $images2->id }}" delete_image="{!! url('service/delete/getImages') !!}">
                                            <a href="#" class="delete-link" data-imageid="{{ $images2->id }}"><img src="{{ url('public/service/' . $images2->image) }}" width="100px" height="60px">
                                                <p class="text">{{ trans('message.Remove') }}</p>
                                            </a>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 " for="first-name">{{ trans('message.Title') }} </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="title" placeholder="{{ trans('message.Enter Title') }}" maxlength="30" value="{{ $service->title }}" class="form-control">
                                </div>
                            </div>
                                <!--Car Marker image -->
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 mt-3 d-block">
                                    <div id="car-image-area"  data-markers="{{ json_encode($markers) }}" style="position: relative; width: 100%; max-width: 433px;">
                                        <!-- <img src="\public\img\Carcondition\carimage2.png" name="image_path" id="car-image" alt="Car Image" style="width: 229px; height: 290px; border:1px solid #ccc;"> -->
                                        <img src="{{ asset('\public\img\Carcondition\carimage2.png') }}" id="car-image" alt="Car Image" style="width:100%; height:400px; border:1px solid #ccc;">
                                        <!-- Markers will be added dynamically -->
                                        <input type="hidden" name="markers" id="markers" value="{{ old('markers', $service->markers ?? '[]') }}">
                                    </div>
                                    <div class="mt-2 text-end" style="position: relative; width: 100%; max-width: 433px;">
                                    <a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary">
                                        <i class="fa fa-info-circle" style="color:#D9D9D9"></i>
                                    </a> {{ trans('message.Right click to remove marker') }}
                                    </div>
                                    <div style="position: relative; width: 100%; max-width: 433px;" class="text-end">
                                    <button id="save-image" type="button" class="btn btn-success mt-3 ">{{trans('message.Download Image')}}</button> 
                                    </div>
                                </div>
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 mt-3 d-block">
                               
                                    <label class="control-label col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12" for="car_details">{{ trans('message.Add Pre-Inspection Checklist') }}</label>
                                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-8">
                                        <textarea class="form-control mb-2 mt-3" name="car_details" id="car_details" rows="5">{{ $service->car_detail }}</textarea>
                                    </div>
                                     <canvas id="car-image-canvas" style="display:none"></canvas> <!-- Canvas for drawing markers -->
                                    
                                    <!-- <button id="save-pdf" class="btn btn-success mt-3">Save as PDF</button> -->
                                </div>
                    
                        </div>
                        <br>
                        <!-- Note Functionality -->
                        <div class="row col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 form-group note-row mt-4">
                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <h2 class="fw-bold">{{ trans('message.Add Notes') }} </h2></span>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 text-end">
                                <button type="button" class="btn btn-outline-secondary btn-sm addNotes mt-1 fl margin-left-0">{{ trans('+') }}</button><br>
                            </div>
                            <hr>
                            @foreach ($service->notes as $key => $note)
                            <div class="row notes-row" id="notes-{{ $key }}">
                                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2" for="">{{ trans('message.Notes') }} <label class="color-danger"></label></label>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3">
                                    <textarea class="form-control" id="" name="notes[{{ $key }}][note_text]" maxlength="100">{{ $note->notes }}</textarea>
                                    <input type="hidden" name="notes[{{ $key }}][note_id]" value="{{ $note->id }}">
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 form-group my-form-group">
                                    <input type="file" name="notes[{{ $key }}][note_file][]" class="form-control imageclass my-2" data-max-file-size="5M" accept="image/*,application/pdf,video/*" multiple />
                                    <div class="row mt-2 mx-0" id="image_preview">
                                        @php
                                        $attachmentIds = json_decode($note->attachment, true);
                                        $attachments = \App\note_attachments::where('note_id','=', $note->id)->get();
                                        @endphp
                                        @foreach ($attachments as $attachment)
                                        @php
                                        $extension = pathinfo($attachment->attachment, PATHINFO_EXTENSION);
                                        $attachmentUrl = URL::asset('public/notes/' . basename($attachment->attachment));
                                        @endphp
                                        <div class="col-md-3 col-sm-3 col-xs-12 removeimage delete_image" id="image_remove_<?php echo $attachment->id; ?>" imgaeid="{{ $attachment->id }}" delete_image="{!! url('/deleteAttachment') !!}">
                                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                            <a href=""><img src="{{ $attachmentUrl }}" width="50px" height="50px">
                                                <p class="text">{{ trans('message.Remove') }}</p>
                                            </a>
                                            @elseif ($extension === 'pdf')
                                            <a href=""><img src="{{ asset('public/img/icons/pdf_download.png') }}" width="50px" height="50px">
                                                <p class="text">{{ trans('message.Remove') }}</p>
                                            </a>
                                            @else
                                            <a href=""><img src="{{ asset('public/img/icons/video.png') }}" width="50px" height="50px">
                                                <p class="text">{{ trans('message.Remove') }}</p>
                                            </a>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 pt-0">
                                    <div class="d-flex">
                                        <input type="checkbox" name="notes[{{ $key }}][internal]" id="" class="form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;" {{ $note->internal ? 'checked' : '' }}>
                                        <label class="control-label pt-1" for="">{{ trans('message.Internal Notes') }} <label class="text-danger"></label></label>
                                    </div>
                                    <div class="d-flex">
                                        <input type="checkbox" name="notes[{{ $key }}][shared]" id="" class="form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;" {{ $note->shared_with_customer ? 'checked' : '' }}>
                                        <label class="control-label pt-1" for="">{{ trans('message.Shared with customer') }} <label class="text-danger"></label></label>
                                    </div>
                                </div>
                                <div class="col-md-1 col-lg-1 col-xl-1 col-xxl-1 col-sm-1 col-xs-1 text-center pt-3">
                                    <i class="fa fa-trash fa-2x deletedatas" url="{!! url('/deleteNote/' . $note->id ) !!}"></i>
                                </div>
                            </div>
                            @endforeach
                        </div>
                </div>

                <!-- Start Custom Field, (If register in Custom Field Module)  -->
                <!-- Custom field  -->
                @if (!empty($tbl_custom_fields))
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                    <h4><b>{{ trans('message.CUSTOM FIELDS') }}</b></h4>
                    <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                </div>
                <?php
                $subDivCount = 0;
                ?>
                @foreach ($tbl_custom_fields as $myCounts => $tbl_custom_field)
                <?php
                if ($tbl_custom_field->required == 'yes') {
                    $required = 'required';
                    $red = '*';
                } else {
                    $required = '';
                    $red = '';
                }

                $tbl_custom = $tbl_custom_field->id;
                $userid = $service->id;
                $datavalue = getCustomDataService($tbl_custom, $userid);

                $subDivCount++;
                ?>

                @if ($myCounts % 2 == 0)
                <div class="row row-mb-0">
                    @endif

                    <div class="row form-group col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 error_customfield_main_div_{{ $myCounts }}">
                        <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="account-no">{{ $tbl_custom_field->label }} <label class="color-danger">{{ $red }}</label></label>
                        <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                            @if ($tbl_custom_field->type == 'textarea')
                            <textarea name="custom[{{ $tbl_custom_field->id }}]" class="form-control textarea_{{ $tbl_custom_field->id }} textarea_simple_class common_simple_class common_value_is_{{ $myCounts }}" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" maxlength="100" isRequire="{{ $required }}" type="textarea" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }}>{{ $datavalue }}</textarea>

                            <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display: none"></span>
                            @elseif($tbl_custom_field->type == 'radio')
                            <?php
                            $radioLabelArrayList = getRadiolabelsList($tbl_custom_field->id);
                            ?>
                            @if (!empty($radioLabelArrayList))
                            <div style="margin-top: 5px;">
                                @foreach ($radioLabelArrayList as $k => $val)
                                <label><input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" value="{{ $k }}" <?php
                                                                                                                                                $getRadioValue = getRadioLabelValueForUpdate($customer->id, $tbl_custom_field->id);

                                                                                                                                                if ($k == $getRadioValue) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>> {{ $val }}</label> &nbsp;
                                @endforeach
                            </div>
                            @endif
                            @elseif($tbl_custom_field->type == 'checkbox')
                            <?php
                            $checkboxLabelArrayList = getCheckboxLabelsList($tbl_custom_field->id);
                            ?>
                            @if (!empty($checkboxLabelArrayList))
                            <?php
                            $getCheckboxValue = getCheckboxLabelValueForUpdate($customer->id, $tbl_custom_field->id);
                            ?>
                            <div class="required_checkbox_parent_div_{{ $tbl_custom_field->id }}" style="margin-top: 5px;">
                                @foreach ($checkboxLabelArrayList as $k => $val)
                                <label><input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}][]" value="{{ $val }} " isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" custm_isd="{{ $tbl_custom_field->id }}" class="checkbox_{{ $tbl_custom_field->id }} required_checkbox_{{ $tbl_custom_field->id }} checkbox_simple_class common_value_is_{{ $myCounts }} common_simple_class" rows_id="{{ $myCounts }}" <?php
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    if ($val == getCheckboxVal($customer->id, $tbl_custom_field->id, $val)) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        echo 'checked';
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ?>> {{ $val }}</label> &nbsp;
                                @endforeach
                                <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display: none"></span>
                            </div>
                            @endif
                            @elseif($tbl_custom_field->type == 'textbox')
                            <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" value="{{ $datavalue }}" maxlength="30" class="form-control textDate_{{ $tbl_custom_field->id }} textdate_simple_class common_value_is_{{ $myCounts }} common_simple_class" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }}>

                            <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display:none"></span>
                            @elseif($tbl_custom_field->type == 'date')
                            <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" value="{{ $datavalue }}" maxlength="30" class="form-control textDate_{{ $tbl_custom_field->id }} date_simple_class common_value_is_{{ $myCounts }} common_simple_class" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" onkeydown="return false" {{ $required }}>

                            <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger" style="display:none"></span>
                            @endif
                        </div>
                    </div>

                    @if ($myCounts % 2 != 0)
                </div>
                @endif
                @endforeach
                <?php
                if ($subDivCount % 2 != 0) {
                    echo '</div>';
                }
                ?>
                @endif
                <!-- Custom field  -->


                <div class="row">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <!-- <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
<a class="btn btn-primary serviceCancleButton" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a>
</div> -->
                    <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                        <button type="submit" id="submitButton" class="btn btn-success serviceSubmitButton">{{ trans('message.Save and continue') }}</button>
                    </div>
                    <div class="row col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 my-2" id="service-print" style="margin-left:-50px">
                    <button type="button" class="btn btn-outline-secondary printbtn btn-sm" id="printSpecificPart"><img src="{{ URL('public/img/icons/Print (1).png') }}" class="pdfButton"></button>
                    </div>
                    
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Repair Add or Remove Model Start-->
<div class="col-md-6">
    <div id="responsive-modal-color" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('message.Add Repair Category') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="" method="">
                        <div class="row">
                            <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 form-group data_popup">
                                <input type="text" class="form-control model_input repair_category_name" name="repair_category_name" placeholder="{{ trans('message.Enter repair category name') }}" maxlength="20" />
                            </div>
                            <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 form-group data_popup">
                                <button type="button" class="btn btn-success model_submit addcolor" colorurl="{!! url('/addRepairCategory') !!}">{{ trans('message.Submit') }}</button>
                            </div>
                        </div>
                        <table class="table colornametype" align="center">

                            <tbody>
                                @foreach ($repairCategoryList as $repairCategory)
                                <tr class="del-{{ $repairCategory->slug }} data_color_name row mx-1">
                                    <td class="text-start col-6">{{ $repairCategory->repair_category_name }}</td>
                                    <td class="text-end col-6">
                                        @if ($repairCategory->added_by_system !== '1' && $repairCategory->added_by_system !== 1)
                                        <button type="button" id="{{ $repairCategory->slug }}" deletecolor="{!! url('deleteRepairCategory') !!}" class="btn btn-danger text-white border-0 deletecolors "><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        @else
                                        {{ trans('message.Added by system') }}
                                        @endif

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Repair Add or Remove Model End-->

</div>
<!-- /page content -->
<script nonce="{{ $cspNonce }}">
    document.getElementById('step2Btn').addEventListener('click', function () {
    // Trigger the form's submit button
    document.getElementById('submitButton').click();

});
</script>
<script nonce="{{ $cspNonce }}">
    document.addEventListener("DOMContentLoaded", function () {
        const carImageArea = document.getElementById('car-image-area');
        const saveImageButton = document.getElementById('save-image');
        const baseImage = document.getElementById('car-image');
        const canvas = document.getElementById('car-image-canvas');
        const context = canvas.getContext('2d');
        const markersInput = document.getElementById('markers');

        // Ensure markers input is initialized (default to an empty array if missing or empty)
        let markers = [];
        try {
            const existingMarkers = markersInput.value.trim();
            if (existingMarkers) {
                markers = JSON.parse(existingMarkers);
            } else {
                // Initialize the hidden field if it's empty
                markersInput.value = JSON.stringify([]);
            }
        } catch (error) {
            console.error('Error parsing existing markers:', error);
            markers = [];
        }

        // Initialize marker count based on existing markers
        let markerCount = markers.length > 0
            ? Math.max(...markers.map(marker => parseInt(marker.number, 10)))
            : 0;

        // Function to render a marker on the car image area
        function renderMarker(marker) {
            const markerContainer = document.createElement('div');
            markerContainer.className = 'marker-container';
            markerContainer.style.position = 'absolute';
            markerContainer.style.left = `${marker.x}px`;
            markerContainer.style.top = `${marker.y}px`;

            // Create the number label
            const markerNumber = document.createElement('span');
            markerNumber.className = 'marker-number';
            markerNumber.innerText = marker.number;
            markerNumber.style.color = 'black';
            markerNumber.style.fontWeight = 'bold';

            // Create the dot marker
            const markerDot = document.createElement('div');
            markerDot.className = 'damage-marker';
            markerDot.style.width = '12px';
            markerDot.style.height = '12px';
            markerDot.style.borderRadius = '50%';
            markerDot.style.backgroundColor = marker.color || 'red';
            markerDot.title = `Damage Marker ${marker.number}`;

            // Double-click to toggle color
            markerDot.addEventListener('dblclick', function (event) {
                event.stopPropagation();
                if (markerDot.style.backgroundColor === 'red') {
                    markerDot.style.backgroundColor = 'red';
                    markerDot.title = `Confirmed Marker ${marker.number}`;
                } else {
                    markerContainer.remove();
                    updateMarkers();
                }
            });

            // Right-click to remove marker
            markerDot.addEventListener('contextmenu', function (event) {
                event.preventDefault();
                event.stopPropagation();
                markerContainer.remove();
                updateMarkers();
            });

            // Append marker elements
            markerContainer.appendChild(markerNumber);
            markerContainer.appendChild(markerDot);
            carImageArea.appendChild(markerContainer);
        }

        // Render all existing markers
        markers.forEach(renderMarker);

        // Add new markers dynamically on click
        carImageArea.addEventListener('click', function (event) {
            if (event.target.classList.contains('damage-marker') || event.target.classList.contains('marker-number')) {
                return; // Prevent adding markers on top of existing ones
            }

            // Get the bounding box of the container
            const rect = this.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            // Increment the marker count
            markerCount++;

            // Create a new marker object
            const newMarker = { x, y, number: markerCount, color: 'red' };

            // Render the new marker
            renderMarker(newMarker);

            // Update the markers input field
            updateMarkers();
        });

        // Function to update marker data in the hidden input
        function updateMarkers() {
            const markerContainers = document.querySelectorAll('.marker-container');
            const updatedMarkers = Array.from(markerContainers).map((container, index) => {
                const markerDot = container.querySelector('.damage-marker');
                const markerNumber = container.querySelector('.marker-number');

                // Ensure marker numbers are sequential
                const newNumber = index + 1; // Start from 1
                markerNumber.innerText = newNumber;

                return {
                    x: parseFloat(container.style.left),
                    y: parseFloat(container.style.top),
                    number: newNumber,
                    color: markerDot.style.backgroundColor,
                };
            });

            // Reset markerCount to reflect the current number of markers
            markerCount = updatedMarkers.length;

            // Update the hidden input field
            markersInput.value = JSON.stringify(updatedMarkers);
        }

        // Save the image with markers as a single image
        saveImageButton.addEventListener('click', function () {
            const rect = carImageArea.getBoundingClientRect();

            // Set canvas dimensions to match the car image area
            canvas.width = rect.width;
            canvas.height = rect.height;

            // Draw the base image
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.drawImage(baseImage, 0, 0, rect.width, rect.height);

            // Draw markers on the canvas
            const markerContainers = document.querySelectorAll('.marker-container');
            markerContainers.forEach(container => {
                const markerDot = container.querySelector('.damage-marker');
                const markerNumber = container.querySelector('.marker-number');

                const x = parseFloat(container.style.left);
                const y = parseFloat(container.style.top);

                // Draw the marker dot
                context.beginPath();
                context.arc(x + 6, y + 6, 6, 0, Math.PI * 2); // Adjust for marker size
                context.fillStyle = markerDot.style.backgroundColor;
                context.fill();

                // Draw the marker number
                context.font = '12px Arial';
                context.fillStyle = markerNumber.style.color;
                context.fillText(markerNumber.innerText, x + 15, y + 10); // Adjust position for text
            });

            // Convert the canvas to a downloadable image
            const image = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = image;
            link.download = 'car-image-with-markers.png';
            link.click();
        });
    });
</script>


<style>
.damage-marker {
    cursor: pointer;
    color:red;
    border: 2px solid black;
    box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.5);
    transition: transform 0.2s;
}
.marker-number {
    font-size: 12px; /* Adjust the font size */
    font-weight: bold;
    color: black;
    position: absolute;
    left: -12px; /* Offset the number to the left of the dot */
    top: 50%;
    transform: translateY(-50%); /* Vertically center the number */
}

.damage-marker:hover {
    transform: scale(1.2); /* Slight zoom on hover */
}

</style>
<!-- <script nonce="{{ $cspNonce }}">
    function PrintElem() {
            window.location.href = "{{ url('/service/list/edit/' . $service->id) }}";
             
                var restorepage = $('body').html();
                var printcontent = $('#').clone();
                $('body').empty().html(printcontent);
                window.print();
                // window.onload = function(){ window.print(); }     
                $('body').html(restorepage);
                window.location.reload();
            
        }
</script> -->
<!-- Add Preinspection checklist textarea enter key  -->
<script nonce="{{ $cspNonce }}">
    $(document).ready(function () {
    // Handle Enter key in the specific textarea
    $('#car_details').on('keypress', function (e) {
        if (e.key === 'Enter') {
            // Perform your specific AJAX operation for this textarea
            e.preventDefault(); // Prevent default Enter key behavior (like form submission)
            
            // Append a new line or process input data
            const currentValue = $(this).val();
            $(this).val(currentValue + '\n'); // Add a newline in the textarea

            // Optional: You can send the updated value via AJAX
            $.ajax({
                url: '/your-ajax-endpoint', // Replace with your endpoint
                type: 'POST',
                data: {
                    car_details: $(this).val(), // Send the textarea content
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token if needed
                },
                success: function (response) {
                    console.log('Data saved:', response);
                },
                error: function (xhr) {
                    console.error('Error saving data:', xhr);
                },
            });
        }
    });
});

</script>
<!-- Scripts starting -->
 <script nonce="{{ $cspNonce }}">
   //print screen
    const printbutton = document.getElementById("printSpecificPart");
    if (printbutton) {
        printbutton.addEventListener("click", function () {
            printSpecificPart();  
         });
    }
   function printSpecificPart() {
    var elementsToHide = document.querySelectorAll('button, .btn, .printbtn');
    var vehicalname = document.getElementById('vhi');
    var custname = document.getElementById('cust_id');
    var repairname = document.getElementById('repair_cat');
    vehicalname.style.width = '140%';
    custname.style.width = '140%';
    repairname.style.width = '140%';
    elementsToHide.forEach(function(element) {
        element.style.display = 'none';
    });
       
    var printContents = document.getElementById('printable-area').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;

    // Trigger the print action
    window.print();
    // Restore the original page content
    document.body.innerHTML = originalContents;
    location.reload(); // Reload to restore JavaScript functionality
}

</script>
<!-- <script nonce="{{ $cspNonce }}" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/jquery-3.7.1.min.js') }}"></script>
<script nonce="{{ $cspNonce }}">
    var msg35 = "{{ trans('message.OK') }}";
    $(document).ready(function() {
        $('.datepicker').datetimepicker({
            format: "<?php echo getDateTimepicker(); ?>",   
            todayBtn: true,
            autoclose: 1,
            // minView: 2,
            startDate: new Date(),
            language: "{{ getLangCode() }}",
        });
        /*Service type free and paid*/
        $(function() {
            $("input[name='service_type']").html(function() {
                if ($("#paid").is(":checked")) {
                    $("#dvCharge").show();
                    $("#charge_required").attr('required', true);
                } else {
                    $("#dvCharge").hide();
                    $("#charge_required").removeAttr('required', false);
                }
            });

            $("input[name='service_type']").click(function() {
                if ($("#paid").is(":checked")) {
                    $("#dvCharge").show();
                    $("#charge_required").attr('required', true);
                } else {
                    $("#dvCharge").hide();
                    $("#charge_required").removeAttr('required', false);
                }
            });
        });

        // Initialize select2
        // $(".select_customer_auto_search").select2();


        /*If date field have value then error msg and has error class remove*/
        $('body').on('change', '#p_date', function() {

            var pDateValue = $(this).val();

            if (pDateValue != null) {
                $('#p_date-error').css({
                    "display": "none"
                });
            }

            if (pDateValue != null) {
                $(this).parent().parent().removeClass('has-error');
            }
        });


        /*If select box have value then error msg and has error class remove*/
        $('#sup_id').on('change', function() {

            var supplierValue = $('select[name=Customername]').val();

            if (supplierValue != null) {
                $('#sup_id-error').css({
                    "display": "none"
                });
            }

            if (supplierValue != null) {
                $(this).parent().parent().removeClass('has-error');
            }
        });


        /*Inside fix service text box only enter numbers data*/
        $('.fixServiceCharge').on('keyup', function() {

            var valueIs = $(this).val();

            if (/\D/g.test(valueIs)) {
                $(this).val("");
            } else if (valueIs == 0) {
                $(this).val("");
            }
        });


        /*Custom Field manually validation*/
        var msg1 = "{{ trans('message.field is required') }}";
        var msg2 = "{{ trans('message.Only blank space not allowed') }}";
        var msg3 = "{{ trans('message.Special symbols are not allowed.') }}";
        var msg4 = "{{ trans('message.At first position only alphabets are allowed.') }}";

        /*Form submit time check validation for Custom Fields */
        $('body').on('click', '.updateServiceButton', function(e) {
            $('#ServiceEdit-Form input, #ServiceEdit-Form select, #ServiceEdit-Form textarea').each(

                function(index) {
                    var input = $(this);

                    if (input.attr('name') == "Customername" || input.attr('name') ==
                        "vehicalname" || input.attr(
                            'name') == "date" || input.attr('name') == "AssigneTo" || input.attr(
                            'name') == "repair_cat" ||
                        input.attr('name') == "service_type") {
                        if (input.val() == "") {
                            return true;
                        } else {
                            return true;
                        }
                    } else if (input.attr('isRequire') == 'required') {
                        var rowid = (input.attr('rows_id'));
                        var labelName = (input.attr('fieldnameis'));

                        if (input.attr('type') == 'textbox' || input.attr('type') == 'textarea') {
                            if (input.val() == '' || input.val() == null) {
                                $('.common_value_is_' + rowid).val("");
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                                $('#common_error_span_' + rowid).css({
                                    "display": ""
                                });
                                $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                e.preventDefault();
                                return false;
                            } else if (!input.val().replace(/\s/g, '').length) {
                                $('.common_value_is_' + rowid).val("");
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg2);
                                $('#common_error_span_' + rowid).css({
                                    "display": ""
                                });
                                $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                e.preventDefault();
                                return false;
                            } else if (!input.val().match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                                $('.common_value_is_' + rowid).val("");
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
                                $('#common_error_span_' + rowid).css({
                                    "display": ""
                                });
                                $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                e.preventDefault();
                                return false;
                            }
                        } else if (input.attr('type') == 'checkbox') {
                            var ids = input.attr('custm_isd');
                            if ($(".required_checkbox_" + ids).is(':checked')) {
                                $('#common_error_span_' + rowid).css({
                                    "display": "none"
                                });
                                $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                                $('.required_checkbox_parent_div_' + ids).css({
                                    "color": ""
                                });
                                $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                            } else {
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                                $('#common_error_span_' + rowid).css({
                                    "display": ""
                                });
                                $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                $('.required_checkbox_' + ids).css({
                                    "outline": "2px solid #a94442"
                                });
                                $('.required_checkbox_parent_div_' + ids).css({
                                    "color": "#a94442"
                                });
                                e.preventDefault();
                                return false;
                            }
                        } else if (input.attr('type') == 'date') {
                            if (input.val() == '' || input.val() == null) {
                                $('.common_value_is_' + rowid).val("");
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                                $('#common_error_span_' + rowid).css({
                                    "display": ""
                                });
                                $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                e.preventDefault();
                                return false;
                            } else {
                                $('#common_error_span_' + rowid).css({
                                    "display": "none"
                                });
                                $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                            }
                        }
                    } else if (input.attr('isRequire') == "") {
                        //Nothing to do
                    }
                }
            );


            /*if washbay checkbox is checked then washbay charge textbox is required*/
            var washbay_trans = "{{ trans('message.Wash Bay Charge') }}";
            var washbay_value = $('#washBayCharge_required').val();

            if ($(".washBayCheckbox").is(':checked') == true) {
                if (washbay_value == "") {
                    //alert("is checked true : ");
                    $('#washBayCharge').addClass('has-error');
                    $('#washbay_error_span').text(washbay_trans + " " + msg1);
                    $('#washbay_error_span').css({
                        "display": ""
                    });
                    e.preventDefault();
                }
            }


            // Check if MOT checkbox is checked
            if ($('#motTestStatusCheckbox').is(":checked")) {
                // Check if MOT charge field is empty
                if ($('#motTestCharge_required').val() == '') {
                    var mot_trans = "{{ trans('message.MOT Testing Charges') }}";
                    $('#motTestCharge').addClass('has-error');
                    $('#mot_error_span').text(mot_trans + " " + msg1);
                    $('#mot_error_span').css({
                        "display": ""
                    });
                    e.preventDefault();
                }
            }

        });


        /*Anykind of input time check for validation for Textbox, Date and Textarea*/
        $('body').on('keyup', '.common_simple_class', function() {

            var rowid = $(this).attr('rows_id');
            var valueIs = $('.common_value_is_' + rowid).val();
            var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
            var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
            var inputTypes = $('.common_value_is_' + rowid).attr('type');

            if (requireOrNot != "") {
                if (inputTypes != 'radio' && inputTypes != 'checkbox' && inputTypes != 'date') {
                    if (valueIs == "") {
                        $('.common_value_is_' + rowid).val("");
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    } else if (valueIs.match(/^\s+/)) {
                        $('.common_value_is_' + rowid).val("");
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg4);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                        $('.common_value_is_' + rowid).val("");
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    } else {
                        $('#common_error_span_' + rowid).css({
                            "display": "none"
                        });
                        $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                    }
                } else if (inputTypes == 'date') {
                    if (valueIs != "") {
                        $('#common_error_span_' + rowid).css({
                            "display": "none"
                        });
                        $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                    } else {
                        $('.common_value_is_' + rowid).val("");
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    }
                } else {
                    //alert("Yes i am radio and checkbox");
                }
            } else {
                if (inputTypes != 'radio' && inputTypes != 'checkbox' && inputTypes != 'date') {
                    if (valueIs != "") {
                        if (valueIs.match(/^\s+/)) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg4);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                        } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg3);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                        } else {
                            $('#common_error_span_' + rowid).css({
                                "display": "none"
                            });
                            $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                        }
                    } else {
                        $('#common_error_span_' + rowid).css({
                            "display": "none"
                        });
                        $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                    }
                }
            }
        });


        /*For required checkbox checked or not*/
        $('body').on('click', '.checkbox_simple_class', function() {

            var rowid = $(this).attr('rows_id');
            var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
            var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
            var inputTypes = $('.common_value_is_' + rowid).attr('type');
            var custId = $('.common_value_is_' + rowid).attr('custm_isd');

            if (requireOrNot != "") {
                if ($(".required_checkbox_" + custId).is(':checked')) {
                    $('.required_checkbox_' + custId).css({
                        "outline": ""
                    });
                    $('.required_checkbox_' + custId).css({
                        "color": ""
                    });
                    $('#common_error_span_' + rowid).css({
                        "display": "none"
                    });
                    $('.required_checkbox_parent_div_' + custId).css({
                        "color": ""
                    });
                    $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                } else {
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                    $('.required_checkbox_' + custId).css({
                        "outline": "2px solid #a94442"
                    });
                    $('.required_checkbox_' + custId).css({
                        "color": "#a94442"
                    });
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.required_checkbox_parent_div_' + custId).css({
                        "color": "#a94442"
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                }
            }
        });



        $('body').on('change', '.date_simple_class', function() {

            var rowid = $(this).attr('rows_id');
            var valueIs = $('.common_value_is_' + rowid).val();
            var requireOrNot = $('.common_value_is_' + rowid).attr('isrequire');
            var labelName = $('.common_value_is_' + rowid).attr('fieldnameis');
            var inputTypes = $('.common_value_is_' + rowid).attr('type');
            var custId = $('.common_value_is_' + rowid).attr('custm_isd');

            if (requireOrNot != "") {
                if (valueIs != "") {
                    $('#common_error_span_' + rowid).css({
                        "display": "none"
                    });
                    $('.error_customfield_main_div_' + rowid).removeClass('has-error');
                } else {
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg1);
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                }
            }
        });



        /*Wash-bay service charge textbox*/
        var isCheckWashbay = $(".washBayCheckbox").is(':checked');

        if (isCheckWashbay == true) {
            $("#washBayCharge").show();
            $("#washBayCharge_required").attr('required', true);
        } else {
            $("#washBayCharge").hide();
            $("#washBayCharge_required").removeAttr('required', false);
        }

        $('.washBayCheckbox').click(function() {

            if ($("#washBay").is(":checked")) {
                if ($("#washBay").is(":checked")) {
                    $(".washBayCharge").show();
                } else {
                    $(".washBayCharge").hide();
                }
                $("#washBayCharge").show();
                $("#washBayCharge_required").attr('required', true);
            } else {
                $("#washBayCharge").hide();
                $("#washBayCharge_required").removeAttr('required', false);
            }
        });


        $('body').on('keyup', '.washbay_charge_textbox', function() {

            var washbayVal = $(this).val();
            var numericDataWashbayMsg = "{{ trans('message.Only numeric data allowed.') }}";
            var washbay_trans = "{{ trans('message.Wash Bay Charge') }}";

            if (washbayVal != "") {
                if (!washbayVal.match(/^(0|[1-9][0-9]*)$/)) {
                    $(this).val("");
                    $('#washbay_error_span').text(numericDataWashbayMsg);
                    $('#washbay_error_span').css({
                        "display": ""
                    });
                    $('#washBayCharge').addClass('has-error');
                } else {
                    $('#washbay_error_span').css({
                        "display": "none"
                    });
                    $('#washBayCharge').removeClass('has-error');
                }
            } else {
                $('#washBayCharge').addClass('has-error');
                $('#washbay_error_span').text(washbay_trans + " " + msg1);
                $('#washbay_error_span').css({
                    "display": ""
                });
            }
        });

        // For MOT test charge field hide & show
        // Check if MOT checkbox is checked initially
        var isCheckMot = $("#motTestStatusCheckbox").is(':checked');

        // Function to toggle MOT charge field based on checkbox status
        function toggleMotChargeField() {
            if (isCheckMot == true) {
                $("#motTestCharge").show();
                $("#motTestCharge_required").attr('required', true);
            } else {
                $("#motTestCharge").hide();
                $("#motTestCharge_required").removeAttr('required', false);
            }
        }

        // Call the function initially to set up the state
        toggleMotChargeField();

        // Event listener for MOT checkbox click
        $('#motTestStatusCheckbox').click(function() {
            // Update the status of MOT checkbox
            isCheckMot = $(this).is(":checked");
            // Toggle MOT charge field
            toggleMotChargeField();
        });

        // Event listener for MOT charge input validation
        $('body').on('keyup', '.mot_charge_textbox', function() {
            var motVal = $(this).val();
            var numericDataMotMsg = "{{ trans('message.Only numeric data allowed.') }}";
            var mot_trans = "{{ trans('message.MOT Testing Charges') }}";

            if (motVal != "") {
                if (!motVal.match(/^(0|[1-9][0-9]*)$/)) {
                    $(this).val("");
                    $('#mot_error_span').text(numericDataMotMsg);
                    $('#mot_error_span').css({
                        "display": ""
                    });
                    $('#motTestCharge').addClass('has-error');
                } else {
                    $('#mot_error_span').css({
                        "display": "none"
                    });
                    $('#motTestCharge').removeClass('has-error');
                }
            } else {
                $('#motTestCharge').addClass('has-error');
                $('#mot_error_span').text(mot_trans + " " + msg31);
                $('#mot_error_span').css({
                    "display": ""
                });
            }
        });

        // added by arjun for color module dynamic add item and reove item

        var msg51 = "{{ trans('message.Please enter only alphanumeric data') }}";
        var msg52 = "{{ trans('message.Only blank space not allowed') }}";
        var msg53 = "{{ trans('message.This Record is Duplicate') }}";
        var msg54 = "{{ trans('message.An error occurred :') }}";

        /*color add  model*/
        $('.addcolor').click(function() {
            var repair_category_name = $('.repair_category_name').val();
            var url = $(this).attr('colorurl');

            var msg55 = "{{ trans('message.Please enter color name') }}";

            function define_variable() {
                return {
                    addcolor_value: $('.repair_category_name').val(),
                    addcolor_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                    addcolor_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/
                };
            }

            var call_var_addcoloradd = define_variable();

            if (repair_category_name == "") {
                swal({
                    title: msg55,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
                // } else if (!call_var_addcoloradd.addcolor_pattern.test(call_var_addcoloradd
                //         .addcolor_value)) {
                //     $('.repair_category_name').val("");
                //     swal({
                //         title: msg51,
                //         cancelButtonColor: '#C1C1C1',
                //         buttons: {
                //             cancel: msg35,
                //         },
                //         dangerMode: true,
                //     });
            } else if (!repair_category_name.replace(/\s/g, '').length) {
                $('.repair_category_name').val("");
                swal({
                    title: msg52,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
                // } else if (!call_var_addcoloradd.addcolor_pattern2.test(call_var_addcoloradd
                //         .addcolor_value)) {
                //     $('.repair_category_name').val("");
                //     swal({
                //         title: msg34,
                //         cancelButtonColor: '#C1C1C1',
                //         buttons: {
                //             cancel: msg35,
                //         },
                //         dangerMode: true,
                //     });
            } else {
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        repair_category_name: repair_category_name
                    },

                    //Form submit at a time only one for addColorModel
                    beforeSend: function() {
                        $(".addcolor").prop('disabled', true);
                    },

                    success: function(data) {
                        var newd = $.trim(data);
                        var classname = 'del-' + newd;

                        if (data == '01') {
                            swal({
                                title: msg53,
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            });
                        } else {
                            $('.colornametype').append('<tr class="row mx-1 ' + classname +
                             ' data_color_name"><td class="text-start">' +
                             repair_category_name +
                             '</td><td class="text-end"><button type="button" id="' +
                             data + '" deletecolor="{{!! url('colortypedelete') !!}}" class="btn btn-danger text-white border-0 deletecolors"> <i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>'
                            );

                            $('.repair_category').append('<option value=' + data + '>' +
                                repair_category_name + '</option>');

                            $('.repair_category_name').val('');
                        }

                        //Form submit at a time only one for addColorModel
                        $(".addcolor").prop('disabled', false);
                        return false;
                    },
                    error: function(e) {
                        alert(mag20 + ' ' + e.responseText);
                        console.log(e);
                    }
                });
            }
        });


        var msg101 = "{{ trans('message.Are You Sure?') }}";
        var msg102 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
        var msg103 = "{{ trans('message.Cancel') }}";
        var msg104 = "{{ trans('message.Yes, delete!') }}";
        var msg105 = "{{ trans('message.Done!') }}";
        var msg106 = "{{ trans('message.It was succesfully deleted!') }}";
        var msg107 = "{{ trans('message.Cancelled') }}";
        var msg108 = "{{ trans('message.Your data is safe') }}";

        /*color Delete  model*/
        $('body').on('click', '.deletecolors', function() {
            var colorid = $(this).attr('id');
            var url = $(this).attr('deletecolor');
            swal({
                title: msg101,
                text: msg102,
                icon: "warning",
                buttons: [msg103, msg104],
                dangerMode: true,
                cancelButtonColor: "#C1C1C1",
            }).then((isConfirm) => {
                if (isConfirm) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            colorid: colorid
                        },
                        success: function(data) {
                            $('.del-' + colorid).remove();
                            $(".color_name_data option[value=" + colorid + "]")
                                .remove();
                            swal({
                                title: msg105,
                                text: msg106,
                                icon: 'success',
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            });
                        }
                    });
                } else {
                    swal({
                        title: msg107,
                        text: msg108,
                        icon: 'error',
                        cancelButtonColor: '#C1C1C1',
                        buttons: {
                            cancel: msg35,
                        },
                        dangerMode: true,
                    });
                }
            })



        });
        /*show  images in multiple selected*/
        $(".imageclass").click(function() {
            $(".classimage").empty();
        });

        function preview_images() {
            var total_file = document.getElementById("images").files.length;
            for (var i = 0; i < total_file; i++) {
                $('#image_preview').append(
                    "<div class='col-md-3 col-sm-3 col-xs-12 removeimage delete_image classimage'><img src='" + URL
                    .createObjectURL(event.target.files[i]) + "' width='100px' height='60px'> </div>");
            }
        }

        $('body').on('click', '.delete_image', function() {

            var delete_image = $(this).attr('imgaeid');
            var url = $(this).attr('delete_image');

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    delete_image: delete_image
                },
                success: function(response) {
                    $('div#image_preview div#image_remove_' + delete_image).remove();
                },
                error: function(e) {
                    alert(msg100 + " " + e.responseText);
                    console.log(e);
                }
            });
            return false;
        });

    });
</script>

<!-- Form field validation -->
{!! JsValidator::formRequest('App\Http\Requests\ServiceAddEditFormRequest', '#ServiceEdit-Form') !!}
<script nonce="{{ $cspNonce }}" type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>

@endsection