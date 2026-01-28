@extends('layouts.app')
@section('content')
<?php $timezone = Auth::User()->timezone; ?>

<style>
    .bootstrap-datetimepicker-widget table td span {
        width: 0px !important;
    }

    .table-condensed>tbody>tr>td {
        padding: 3px;
    }
    span.select2-selection.select2-selection--single {
    width: 115% !important;
    }
    #vhi,.repair_category {
        width: 115% !important;
    }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
   
    right: -39px !important;
   
}
.select2-selection__rendered{
    padding-right: 9px !important;
}
@media (max-width: 575px) and (min-width: 354px) {
    #cust_id-error {
        position: static !important;
        top: auto !important;
        display: block;
        width: 100%;
        margin-top: 5px;
        font-size: 12px;
        line-height: 1.4;
    }
    span.select2.select2-container.select2-container--default{
        width: 87% !important;
    }
    #vhi, .repair_category{
         width: 98% !important;
    }
}

</style>
<style>
    .step1 {
        color: #333333 !important;
        /* background-color: #f5f5f5;
            border-color: #ddd; */
        /* padding: 10px 47px; */
        font-size: 16px;

    }
    .nav-link-active{
        color:#EA6B00;
    }
    .invalid-feedback {
        color: red;
    }

    .panel-heading.step1.titleup>p.col-md-12.col-xs-12.col-sm-12.ln_solid {
        width: 102%;
        margin-bottom: 10px;
    }

    /* 
    input#free {
        margin-left: -37px;
    } */

    .blur-modal {
        filter: blur(2px);
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
.select2-dropdown--below{
    width: 264px !important;
}
.select2-container--default .select2-results > .select2-results__options {
    max-height: 300px; 
    overflow-y: auto; 
    scrollbar-width: thin; /* For modern browsers, make scrollbar thinner */
}

@if(getLangCode() === 'ar')

   .select2-container--default .select2-selection--single .select2-selection__arrow {
  
     right: -5px !important;
}
#vhi,.repair_category {
        width: 110% !important;
    }
    span.select2-selection.select2-selection--single {
    width: 110% !important;
    margin-left:-20px !important;
}
.select2-dropdown--below {
    width: 240px !important;
    margin-left:-20px !important;
}
.form-select.is-valid:not([multiple]):not([size]), .form-select.is-valid:not([multiple])[size="1"], .was-validated .form-select:valid:not([multiple]):not([size]), .was-validated .form-select:valid:not([multiple])[size="1"] {
    padding-right: 1.125rem;
}
@endif
</style>
<!-- page content -->
<div class="right_col" role="main">

    <div class="page-title">
        <div class="nav_menu">
            <nav>
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                    <a href="{!! url('/service/list') !!}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                            {{ trans('message.Add Services') }}</span></a>
                </div>
                @include('dashboard.profile')
            </nav>
        </div>
    </div>
    <div class="tab-container ">
        <div class="tab-menu">
            <button class="tab-link active">{{trans('message.Step 1: Add Service')}}</button>
            <button id="step2Btn" class="tab-link">{{trans('message.Step 2: Job Card Details')}}</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
            <div class="x_panel AddSeviceDescription mb-0">
                <div class="x_content">
                    <div class="panel panel-default">
                        <div class="panel-heading step1 titleup">{{ trans('message.STEP - 1 : ADD SERVICE DETAILS...') }}
                            <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
                        </div>
                        <form id="ServiceAdd-Form" method="post" action="{{ url('/service/store') }}" enctype="multipart/form-data" class="form-horizontal upperform serviceAddForm" border="10">

                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Jobcard Number') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="jobno" name="jobno" class="form-control" value="{{ $code }}" readonly>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Customer Name') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                      
                                        <!-- select with search option  -->
                                        <select name="Customername" id="cust_id" 
                                                class="form-control select_vhi select_customer_auto_search form-select" 
                                                cus_url="{!! url('service/get_vehi_name') !!}?v_id={{ request('v_id') }}" 
                                                required>
                                            <option value="">{{ trans('message.Select Customer') }}</option>
                                            @if (!empty($customer))
                                                @foreach ($customer as $customers)
                                                    <option value="{{ $customers->id }}"  
                                                            {{ request()->input('c_id') == $customers->id ? 'selected' : '' }}>
                                                        {{ getCustomerName($customers->id) }} - {{ getCustomerMobile($customers->id) }}
                                                    </option>
                                                @endforeach
                                            @endif

                                        </select>

                                    </div>
                                    <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove customerAddModel mt-0">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-outline-secondary fl margin-left-0">{{ trans('+') }}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 mt-3">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Vehicle Name') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                        <select name="vehicalname" id="vhi" class="form-control modelnameappend select_vehicle form-select" free_url="{!! url('service/get_free_service') !!}" required>

                                            <!-- <select name="vehicalname" class="form-control modelnameappend" id="vhi" required> -->
                                            <option value="">{{ trans('message.Select vehicle Name') }}</option>
                                            @if (!empty($vehicals))
                                            @foreach ($vehicals as $vehical)
                                            <option value="{{ $vehical->vehicle_id }}" class="modelnms">
                                                <!-- {{ getVehicles($cus_id); }} -->
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#vehiclemymodel" class="btn btn-outline-secondary vehiclemodel fl margin-left-0">{{ trans('+') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 mt-3">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="Date">{{ trans('message.Date') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8  date">
                                        <input type='text' class="form-control datepicker" name="date" autocomplete="off" id='p_date' placeholder="<?php echo getDatepicker();
                                                                                                                                                    echo ' hh:mm:ss'; ?>" value="{{ !empty(request()->input('date')) ? request()->input('date') : now()->setTimezone($timezone)->format('Y-m-d H:i:s') }}" onkeypress="return false;" required />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Repair Category') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                        <select name="repair_cat" class="form-control repair_category form-select" required>
                                            <option value="">{{ trans('message.-- Select Repair Category--') }}
                                            </option>

                                            @if (!empty($repairCategoryList))
                                            @foreach ($repairCategoryList as $repairCategoryListData)
                                            <option value="<?php echo $repairCategoryListData->slug; ?>">
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
                                            <option value="{{ $employees->id }}">{{ $employees->name }} {{ $employees->lastname }}
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
                                    <!-- <input type="text" id="charge_required" name="charge" class="form-control fixServiceCharge" placeholder="{{ trans('message.Enter Service Charge') }}" maxlength="8" required> -->
                                    <input type="text" id="charge_required" name="charge" class="form-control fixServiceCharge" placeholder="{{ trans('message.Enter Service Charge') }}" maxlength="8" required>
                                </div>
                                </div>
                            </div>

                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="branch">{{ trans('message.Branch') }} <label class="color-danger">*</label></label>

                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select class="form-control select_branch form-select" name="branch">
                                            @foreach ($branchDatas as $branchData)
                                            <option value="{{ $branchData->id }}">
                                                {{ $branchData->branch_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="details">{{ trans('message.Details') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <textarea class="form-control mb-2" name="details" id="details" maxlength="100">{{ old('details') }}</textarea>
                                    </div>
                                </div>
                            </div>
                             <!-- kilometers run -->
                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="branch">{{ trans('message.KMS Run') }}: <label class="color-danger">*</label></label>

                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" min='0' id="kms" name="kms" value="" maxlength="10" class="form-control" required>
                                    </div>
                                </div>
                                 
                            </div>
                            <!-- Wash Bay Feature -->
                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 washbayLabel" for="washbay">{{ trans('message.Wash Bay') }} <label class="text-danger"></label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 washbayInputDiv pt-0">
                                        <input type="checkbox" name="washbay" id="washBay" class="washBayCheckbox form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                                    </div>
                                </div>

                                <div id="washBayCharge" class="has-feedback mt-0 {{ $errors->has('washBayCharge') ? ' has-error' : '' }} row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 row">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency" for="washBayCharge">{{ trans('message.Wash Bay Charge') }} (<?php echo getCurrencySymbols(); ?>)
                                        <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 washbay_charge_detail">
                                        <input type="text" id="washBayCharge_required" name="washBayCharge" class="form-control washbay_charge_textbox" placeholder="{{ trans('message.Enter Wash Bay Charge') }}" value="{{ old('washBayCharge') }}" maxlength="10">

                                        <span id="washbay_error_span" class="help-block error-help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- Wash Bay Feature -->

                            <!-- MOt Test Checkbox Start-->
                            <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 motTextLabel" for="">{{ trans('message.MOT Test') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="checkbox" name="motTestStatusCheckbox" id="motTestStatusCheckbox" class="motCheckbox form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                                    </div>
                                </div>
                                <div id="motTestCharge" class="has-feedback mt-0 {{ $errors->has('motTestCharge') ? ' has-error' : '' }} row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 row">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency" for="motTestCharge">{{ trans('message.MOT Test Charges') }} (<?php echo getCurrencySymbols(); ?>)
                                        <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 mot_charge_detail">
                                        <input type="text" id="motTestCharge_required" name="motTestCharge" class="form-control mot_charge_textbox" placeholder="{{ trans('message.Enter MOT Test Charges') }}" value="{{ old('motTestCharge') }}" maxlength="10">

                                        <span id="mot_error_span" class="help-block error-help-block text-danger"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- MOt Test Checkbox End-->


                            <div class="row row-mb-0">
                                @if ($errors->has('charge'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('reg_no') }}</strong>
                                </span>
                                @endif

                                <!-- Service images  -->
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pt-0" for="first-name">{{ trans('message.Select Multiple Images') }} <label class="color-danger"></label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="file" name="image[]" class="form-control imageclass" id="images" onchange="preview_images();" data-max-file-size="5M" multiple />
                                    </div>
                                </div>
                                <!-- Service images  -->
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Title') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" name="title" placeholder="{{ trans('message.Enter Title') }}" value="{{ old('title') }}" maxlength="50" class="form-control">
                                    </div>
                                </div>
                                <!--Car Marker image -->
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 mt-3 d-block">
                                    <div id="car-image-area" style="position: relative; width:100%;max-width:433px;">
                                        <!-- <img src="\public\img\Carcondition\carimage2.png" name="image_path" id="car-image" alt="Car Image" style="width: 229px; height: 290px; border:1px solid #ccc;"> -->
                                        <img src="{{ asset('\public\img\Carcondition\carimage2.png') }}" id="car-image" alt="Car Image" style="width:100%; height:400px; border:1px solid #ccc;">
                                    <!-- Markers will be added dynamically -->
                                    <input type="hidden" name="markers" id="markers">
                                    </div>
                                    <div class="mt-2 text-end" style="position: relative; width: 100%; max-width: 433px;">
                                    <a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary">
                                        <i class="fa fa-info-circle" style="color:#D9D9D9"></i>
                                    </a> {{ trans('message.Right click to remove marker') }}
                                    </div>
                                    <div style="position: relative; width: 100%; max-width: 433px;" class="text-end">
                                    <button id="save-image" type="button" class="btn btn-success mt-3">{{trans('message.Download Image')}}</button>
                                    </div>
                                    
                        
                                </div>
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 mt-3 d-block">
                               
                                    <label class="control-label col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12" for="car_details">{{ trans('message.Add Pre-Inspection Checklist') }}</label>
                                    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 mt-3">
                                        <textarea class="form-control mb-2" name="car_details" id="car_details" rows="5">{{ old('car_details') }}</textarea>
                                    </div>
                                     <canvas id="car-image-canvas"></canvas> <!-- Canvas for drawing markers -->
                                    
                                    <!-- <button id="save-pdf" class="btn btn-success mt-3">Save as PDF</button> -->
                                </div>
                            </div>

                            <!-- Note Functionality -->
                            <div class="row col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 form-group note-row">
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <h2 class="fw-bold">{{ trans('message.Add Notes') }} </h2></span>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 text-end">
                                    <button type="button" class="btn btn-outline-secondary btn-sm addNotes mt-1 fl margin-left-0">{{ trans('+') }}</button><br>
                                </div>
                                <hr>
                                <div class="row notes-row" id="notes-1">
                                    <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2" for="">{{ trans('message.Notes') }} <label class="color-danger"></label></label>
                                    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3">
                                        <textarea class="form-control" id="" name="notes[1][note_text]" maxlength="100"></textarea>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 form-group my-form-group">
                                        <input type="file" name="notes[1][note_file][]" class="form-control imageclass mt-2" data-max-file-size="5M" accept="image/*,application/pdf,video/*" multiple />
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 pt-0">
                                        <div class="d-flex">
                                            <input type="checkbox" name="notes[1][internal]" id="" class="form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                                            <label class="control-label pt-1" for="">{{ trans('message.Internal Notes') }} <label class="text-danger"></label></label>
                                        </div>
                                        <div class="d-flex">
                                            <input type="checkbox" name="notes[1][shared]" id="" class="form-check" style="height:20px; width:20px; margin-right:5px; position: relative; top: 1px; margin-bottom: 12px;">
                                            <label class="control-label pt-1" for="">{{ trans('message.Shared with customer') }} <label class="text-danger"></label></label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-xl-1 col-xxl-1 col-sm-1 col-xs-1 text-center pt-3">
                                        <i class="fa fa-trash fa-2x deleteNotes"></i>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <!-- Start Custom Field, (If register in Custom Field Module)  -->
                    @if (!empty($tbl_custom_fields))
                    <div class="col-md-12 col-xs-12 col-sm-12 space">
                        <h4><b>{{ trans('message.Custom Fields') }}</b></h4>
                        <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
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

                    $subDivCount++;
                    ?>
                    @if ($myCounts % 2 == 0)
                    <div class="row col-md-12 col-sm-6 col-xs-12 row-mb-0">
                        @endif
                        <div class="form-group row  col-md-6 col-sm-6 col-xs-12 error_customfield_main_div_{{ $myCounts }}">

                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="account-no">{{ $tbl_custom_field->label }} <label class="color-danger">{{ $red }}</label></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                @if ($tbl_custom_field->type == 'textarea')
                                <textarea name="custom[{{ $tbl_custom_field->id }}]" class="form-control textarea_{{ $tbl_custom_field->id }} textarea_simple_class common_simple_class common_value_is_{{ $myCounts }}" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" maxlength="100" isRequire="{{ $required }}" type="textarea" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }}></textarea>

                                <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger"></span>
                                @elseif($tbl_custom_field->type == 'radio')
                                <?php
                                $radioLabelArrayList = getRadiolabelsList($tbl_custom_field->id);
                                ?>
                                @if (!empty($radioLabelArrayList))
                                <div class="mt-3">
                                    @foreach ($radioLabelArrayList as $k => $val)
                                    <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" value="{{ $k }}" <?php if ($k == 0) {
                                                                                                                                                echo 'checked';
                                                                                                                                            } ?>>{{ $val }}
                                    &nbsp;
                                    @endforeach
                                </div>
                                @endif
                                @elseif($tbl_custom_field->type == 'checkbox')
                                <?php
                                $checkboxLabelArrayList = getCheckboxLabelsList($tbl_custom_field->id);
                                $cnt = 0;
                                ?>

                                @if (!empty($checkboxLabelArrayList))
                                <div class="required_checkbox_parent_div_{{ $tbl_custom_field->id }}">
                                    @foreach ($checkboxLabelArrayList as $k => $val)
                                    <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}][]" value="{{ $val }}" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" custm_isd="{{ $tbl_custom_field->id }}" class="checkbox_{{ $tbl_custom_field->id }} required_checkbox_{{ $tbl_custom_field->id }} checkbox_simple_class common_value_is_{{ $myCounts }} common_simple_class" rows_id="{{ $myCounts }}"> {{ $val }}
                                    &nbsp;
                                    <?php $cnt++; ?>
                                    @endforeach
                                    <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger"></span>
                                </div>
                                <input type="hidden" name="checkboxCount" value="{{ $cnt }}">
                                @endif
                                @elseif($tbl_custom_field->type == 'textbox')
                                <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" class="form-control textDate_{{ $tbl_custom_field->id }} textdate_simple_class common_value_is_{{ $myCounts }} common_simple_class" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" maxlength="30" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }}>

                                <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger"></span>
                                @elseif($tbl_custom_field->type == 'date')
                                <input type="{{ $tbl_custom_field->type }}" name="custom[{{ $tbl_custom_field->id }}]" class="form-control textDate_{{ $tbl_custom_field->id }} date_simple_class common_value_is_{{ $myCounts }} common_simple_class" placeholder="{{ trans('message.Enter') }} {{ $tbl_custom_field->label }}" maxlength="30" isRequire="{{ $required }}" fieldNameIs="{{ $tbl_custom_field->label }}" rows_id="{{ $myCounts }}" {{ $required }} onkeydown="return false">

                                <span id="common_error_span_{{ $myCounts }}" class="help-block error-help-block color-danger"></span>
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
                    <!-- End Custom Field -->

                    <div class="row">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!-- <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                        <a class="btn btn-primary serviceCancleButton" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a>
                    </div> -->
                        <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                            <button type="submit" id="submitButton" class="btn btn-success serviceSubmitButton">{{ trans('message.Save and continue') }}</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>


    <!--customer add model -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel1">{{ trans('message.Customer Details') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="x_content">
                        <form id="formcustomer" method="POST" name="formcustomer" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left input_mask">

                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                                <h4><b>{{ trans('message.PERSONAL INFORMATION') }}</b></h4>
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                            </div>
                            <div class="row mt-3">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.First Name') }} <label class="color-danger">*</label> </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="firstname" name="firstname" class="form-control" value="{{ old('firstname') }}" placeholder="{{ trans('message.Enter First Name') }}" maxlength="25" required />
                                        <span class="color-danger" id="errorlfirstname"></span>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Last Name') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="lastname" name="lastname" placeholder="{{ trans('message.Enter Last Name') }}" value="{{ old('lastname') }}" maxlength="25" class="form-control" required>
                                        <span class="color-danger" id="errorllastname"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                        {{ trans('message.Gender') }}
                                        <label class="color-danger"></label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 gender ps-0">
                                        <input type="radio" class="gender" name="gender" value="0">{{ trans('message.Male') }}
                                        <input type="radio" class="gender" name="gender" value="1">
                                        {{ trans('message.Female') }}
                                    </div>
                                </div>
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="mobile">{{ trans('message.Mobile No') }}. <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="mobile" name="mobile" placeholder="{{ trans('message.Enter Mobile No') }}" value="{{ old('mobile') }}" class="form-control" maxlength="16" minlength="6" required>
                                        <span class="color-danger" id="errorlmobile"></span>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-3">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="Email">{{ trans('message.Email') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="email" name="email" placeholder="{{ trans('message.Enter Email') }}" value="{{ old('email') }}" class="form-control" maxlength="50" required>
                                        <span class="color-danger" id="errorlemail"></span>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="Password">{{ trans('message.Password') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="password" id="password" name="password" placeholder="{{ trans('message.Enter Password') }}" class="form-control col-md-7 col-xs-12" maxlength="20" required>
                                        <span class="color-danger" id="errorlpassword"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 currency p-0 ps-2 px-5" for="Password">{{ trans('message.Confirm Password') }}
                                        <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ trans('message.Enter Confirm Password') }}" class="form-control col-md-7 col-xs-12" maxlength="20" required>
                                        <span class="color-danger" id="errorlpassword_confirmation"></span>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">{{ trans('message.Date of Birth') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date ">
                                        <input type="text" id="datepicker" autocomplete="off" class="form-control datepickercustmore" placeholder="<?php echo getDatepicker(); ?>" name="dob" value="{{ old('dob') }}" onkeypress="return false;" />
                                    </div>
                                    <span class="color-danger" id="errorldatepicker"></span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('displayname') ? ' has-error' : '' }} ">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="display-name">{{ trans('message.Display Name') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="displayname" name="displayname" placeholder="{{ trans('message.Enter Display Name') }}" value="{{ old('displayname') }}" class="form-control" maxlength="25">
                                        <span class="color-danger" id="errorldisplayname"></span>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('company_name') ? ' has-error' : '' }} ">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 p-0" for="display-name">{{ trans('message.Company Name') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="company_name" name="company_name" placeholder="{{ trans('message.Enter Company Name') }}" value="{{ old('company_name') }}" class="form-control" maxlength="25">
                                        <span class="color-danger" id="errorlcompanyName"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('landlineno') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="landline-no">{{ trans('message.Landline No') }}. </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="text" id="landlineno" name="landlineno" placeholder="{{ trans('message.Enter LandLine No') }}" value="{{ old('landlineno') }}" class="form-control">
                                        <span class="color-danger" id="errorllandlineno"></span>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="image">
                                        {{ trans('message.Image') }} </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <input type="file" id="image" name="image" value="{{ old('image') }}" class="form-control chooseImage">

                                        <img src="{{ url('public/customer/avtar.png') }}" id="imagePreview" alt="User Image" class="datatable_img mt-2" style="width: 52px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                                <h4><b>{{ trans('message.ADDRESS') }}</b></h4>
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                            </div>
                            <div class="row mt-3">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="Country">{{ trans('message.Country') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select class="form-control select_country form-select" id="country_id" name="country_id" countryurl="{!! url('/getstatefromcountry') !!}" required>
                                            <option value="">{{ trans('message.Select Country') }}</option>
                                            @foreach ($country as $countrys)
                                            <option value="{{ $countrys->id }}">{{ $countrys->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="color-danger" id="errorlcountry_id"></span>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="State ">{{ trans('message.State') }} </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select class="form-control state_of_country form-select" id="state_id" name="state_id" stateurl="{!! url('/getcityfromstate') !!}">
                                            <option value="">{{ trans('message.Select State') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="Town/City">{{ trans('message.Town/City') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <select class="form-control city_of_state form-select" id="city" name="city">
                                            <option value="">{{ trans('message.Select City') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="Address">{{ trans('message.Address') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                        <textarea class="form-control" id="address" name="address" maxlength="100" required>{{ old('address') }}</textarea>
                                        <span class="color-danger" id="errorladdress"></span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    <!-- <a class="btn btn-primary cancelcustomer" data-bs-dismiss="modal">{{ trans('message.CANCEL') }}</a> -->
                                    <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                                        <button type="submit" class="btn btn-success addcustomer">{{ trans('message.SUBMIT') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm mx-1" data-bs-dismiss="modal">{{ trans('message.Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="vehiclemymodel" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">{{ trans('message.Vehicle Details') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @include('success_message.message')
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal upperform" id="add_vehi">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="customer_id" value="" class="hidden_customer_id">
                        <div class="row">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Vehicle Type') }} <label class="color-danger">*</label></label>
                                <div class="col-md-12 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <select class="form-control select_vehicaltype form-select" id="vehical_id1" name="vehical_id" vehicalurl="{!! url('/vehicle/vehicaltypefrombrand') !!}" required>
                                        <option value="">{{ trans('message.Select Vehicle Type') }}</option>
                                        @if (!empty($vehical_type))
                                        @foreach ($vehical_type as $vehical_types)
                                        <option value="{{ $vehical_types->id }}">
                                            {{ $vehical_types->vehicle_type }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="color-danger" id="errorlvehical_id1"></span>
                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                   
                                </div>
                            </div>

                            <!-- <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="branch">{{ trans('message.Branch') }} <label class="color-danger">*</label></label>

                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <select class="form-control select_branch_vehicle form-select" id="select_branch_vehicle" name="branch_vehicle">
                                        @foreach ($branchDatas as $branchData)
                                        <option value="{{ $branchData->id }}">{{ $branchData->branch_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Number Plate') }} <label class="text-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" id="number_plate" name="number_plate" value="{{ old('number_plate') }}" placeholder="{{ trans('message.Enter Number Plate') }}" maxlength="30" class="form-control" required>
                                    <span class="color-danger" id="npe"></span>
                                    @if ($errors->has('price'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="row mt-2">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Vehicle Brand') }}<label class="color-danger">*</label></label>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <select class="form-control select_vehicalbrand form-select" id="vehicabrand1" name="vehicabrand" url="{!! url('/vehicle/vehicalmodelfrombrand') !!}">
                                        <option value="">{{ trans('message.Select Brand') }}</option>
                                            @if (!empty($vehical_brand))
                                            @foreach ($vehical_brand as $vehical_brands)
                                            <option value="{{ $vehical_brands->id }}">{{ $vehical_brands->vehicle_brand }}
                                            </option>
                                            @endforeach
                                            @endif
                                    </select>
                                    <span class="color-danger">
                                        <strong id="errorlvehicabrand1"></strong>
                                    </span>
                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                            
                                </div>
                            </div>
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Chasic No') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="chasicno" id="chasicno1" value="{{ old('chasicno') }}" placeholder="{{ trans('message.Enter ChasicNo') }}" maxlength="30" class="form-control">
                                    <span class="color-danger" id="errorlchasicno1"></span>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-2">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Model Name') }} <label class="color-danger">*</label></label>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <select class="form-control model_addname form-select" id="modelname1" name="modelname" required>
                                        <option value="">{{ trans('message.Select Model') }}</option>
                                            @if (!empty($model_name))
                                            @foreach ($model_name as $model_names)
                                            <option value="{{ $model_names->id }}">{{ $model_names->model_name }}
                                            </option>
                                            @endforeach
                                            @endif

                                    </select>
                                    <span class="color-danger" id="errorlmodelname1"></span>
                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                                
                                </div>
                            </div>

                            <!-- <div class="{{ $errors->has('price') ? ' has-error' : '' }} row 
                                col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">
                                    {{ trans('message.Price') }} (<?php echo getCurrencySymbols(); ?>) <label class="color-danger">*</label>
                                </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="price" id="price1" value="{{ old('price') }}" placeholder="{{ trans('message.Enter Price') }}" class="form-control" maxlength="10">
                                    <span class="color-danger" id="ppe"></span>
                                    @if ($errors->has('price'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('price') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> -->
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Model Years') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 input-groups date">
                                    <input type="text" name="modelyear" id="modelyear1" class="form-control myDatepicker2" />
                                </div> 
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Fuel Type') }}<label class="color-danger">*</label></label>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <select class="form-control select_fueltype form-select" id="fueltype1" name="fueltype">
                                        <option value="">{{ trans('message.Select fuel type') }} </option>
                                        @if (!empty($fuel_type))
                                        @foreach ($fuel_type as $fuel_types)
                                        <option value="{{ $fuel_types->id }}">{{ $fuel_types->fuel_type }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="color-danger" id="fuel1"></span>
                                </div>
                                <div class="col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 addremove">
                    
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Total Gears') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="gearno" id="gearno1" value="{{ old('gearno') }}" placeholder="{{ trans('message.Enter Total Gears') }}" maxlength="5" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 {{ $errors->has('odometerreading') ? ' has-error' : '' }}">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Odometer Reading') }} </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="odometerreading" id="odometerreading1" value="{{ old('odometerreading') }}" placeholder="{{ trans('message.Enter Odometer Reading') }}" maxlength="20" class="form-control">
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Date Of Manufacturing') }} </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 date">
                                    <input type="text" name="dom" id="dom1" class="form-control datepicker1" placeholder="<?php echo getDatepicker(); ?>" onkeypress="return false;" />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Gear Box') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="gearbox" id="gearbox1" value="{{ old('gearbox') }}" placeholder="{{ trans('message.Enter Grear Box') }}" maxlength="30" class="form-control">
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Gear Box No') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="gearboxno" id="gearboxno1" value="{{ old('gearboxno') }}" placeholder="{{ trans('message.Enter Gearbox No') }}" maxlength="30" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Engine No') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="engineno" id="engineno1" value="{{ old('engineno') }}" placeholder="{{ trans('message.Enter Engine No') }}" maxlength="30" class="form-control">
                                    <span class="color-danger" id="errorlengineno1"></span>
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="last-name">{{ trans('message.Engine Size') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="enginesize" id="enginesize1" value="{{ old('enginesize') }}" placeholder="{{ trans('message.Enter Engine Size') }}" maxlength="30" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Key No') }} </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="keyno" id="keyno1" value="{{ old('keyno') }}" placeholder="{{ trans('message.Enter Key No') }}" maxlength="30" class="form-control">
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Engine') }} </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="engine" id="engine1" value="{{ old('engine') }}" placeholder="{{ trans('message.Enter Engine') }}" maxlength="30" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('message.Chasic No') }}</label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                                    <input type="text" name="chasicno" id="chasicno1" value="{{ old('chasicno') }}" placeholder="{{ trans('message.Enter ChasicNo') }}" maxlength="30" class="form-control">
                                    <span class="color-danger" id="errorlchasicno1"></span>
                                </div>
                            </div>

                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 text-center">
                                <!-- <a class="btn btn-primary cancelvehicleservice" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a> -->
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                                    <button type="button" class="btn btn-success addvehicleservice">{{ trans('message.SUBMIT') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary vhc_close btn-sm mx-1" data-bs-dismiss="modal">{{ trans('message.Close') }}</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Model Name -->
    <div class="col-md-6">
        <div id="responsive-modal-vehi-model" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('message.Add Model Name') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="" method="post">
                            <div class="row">
                                <div class="col-md-5 form-group data_popup">
                                    <select class="form-control model_input form-select vehi_brand_id" name="vehical_id" id="vehicleBrandSelect" vehicalurl="{!! url('/vehicle/vehicalformtype') !!}" required>
                                        <option value="">{{ trans('message.Select Brand') }}</option>
                                        @if (!empty($vehical_brand))
                                        @foreach ($vehical_brand as $vehical_brands)
                                        <option value="{{ $vehical_brands->id }}">{{ $vehical_brands->vehicle_brand }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 form-group data_popup">
                                    <input type="text" class="form-control model_input vehi_modal_name" name="model_name" id="model_name" placeholder="{{ trans('message.Enter Model Name') }}" maxlength="20" required />
                                </div>
                                <div class="col-md-4 form-group data_popup">
                                    <button type="button" class="btn btn-success model_submit vehi_model_add" modelurl="{!! url('/vehicle/vehicle_model_add') !!}">{{ trans('message.Submit') }}</button>
                                </div>
                            </div>
                            <table class="table vehi_model_class">

                                <tbody>

                                    @if (!empty($model_name))
                                    @foreach ($model_name as $model_names)
                                    <tr class="mod-{{ $model_names->id }} data_color_name row mx-1">
                                        <td class="text-start col-6">{{ $model_names->model_name }}
                                        </td>
                                        <td class="text-end col-6">
                                            <button type="button" modelid="{{ $model_names->id }}" deletemodel="{!! url('/vehicle/vehicle_model_delete') !!}" class="btn btn-danger text-white border-0 modeldeletes"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Model Name -->
    <!-- Vehicle Type  -->
    <div class="col-md-6">
        <div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> {{ trans('message.Add Vehicle Type') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal formaction" action="" method="">
                            <div class="row">
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 form-group data_popup">
                                    <input type="text" class="form-control model_input vehical_type" name="vehical_type" id="vehical_type" placeholder="{{ trans('message.Enter Vehicle Type') }}" maxlength="20" required />
                                </div>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 form-group data_popup">

                                    <button type="button" class="btn btn-success model_submit vehicaltypeadd" url="{!! url('/vehicle/vehicle_type_add') !!}">{{ trans('message.Submit') }}</button>
                                </div>
                            </div>
                            <table class="table vehical_type_class" align="center">
                                <tbody>
                                    @if (!empty($vehical_type))
                                    @foreach ($vehical_type as $vehical_types)
                                    <tr class="del-{{ $vehical_types->id }} data_vehicle_type_name row mx-1">
                                        <td class="text-start col-6 w-50">{{ $vehical_types->vehicle_type }}</td>
                                        <td class="text-end col-6 w-50">
                                            <button type="button" vehicletypeid="{{ $vehical_types->id }}" deletevehical="{!! url('/vehicle/vehicaltypedelete') !!}" class="btn btn-danger text-white border-0 deletevehicletype"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End  Vehicle Type  -->

    <!-- Vehicle Brand -->
    <div class="col-md-6">
        <div id="responsive-modal-brand" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('message.Add Vehicle Brand') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="" method="">
                            <div class="row">
                                <div class="col-md-8 form-group data_popup">
                                    <select class="form-control model_input vehical_id form-select vehicle_type_model" name="vehical_id" id="vehicleTypeSelect" vehicalurl="{!! url('/vehicle/vehicalformtype') !!}" required>
                                        <option value="">{{ trans('message.Select Vehicle Type') }}</option>
                                        @if (!empty($vehical_type))
                                        @foreach ($vehical_type as $vehical_types)
                                        <option value="{{ $vehical_types->id }}">
                                            {{ $vehical_types->vehicle_type }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 form-group data_popup">
                                    <input type="text" class="form-control model_input vehical_brand" name="vehical_brand" id="vehical_brand" placeholder="{{ trans('message.Enter Vehicle brand') }}" maxlength="25" required />
                                </div>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 form-group data_popup">
                                    <button type="button" class="btn btn-success model_submit vehicalbrandadd" vehiclebrandurl="{!! url('/vehicle/vehicle_brand_add') !!}">{{ trans('message.Submit') }}</button>
                                </div>
                            </div>
                            <table class="table vehical_brand_class" align="center">
                                <tbody>
                                    @if (!empty($vehical_brand))
                                    @foreach ($vehical_brand as $vehical_brands)
                                    <tr class="del-{{ $vehical_brands->id }} data_vehicle_brand_name row mx-1">
                                        <td class="text-start col-6 w-50">{{ $vehical_brands->vehicle_brand }}</td>
                                        <td class="text-end col-6 w-50">

                                            <button type="button" brandid="{{ $vehical_brands->id }}" deletevehicalbrand="{!! url('/vehicle/vehicalbranddelete') !!}" class="btn btn-danger text-white border-0 deletevehiclebrands"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Vehicle Brand -->
    <!-- Fuel Type -->
    <div class="col-md-6">
        <div id="responsive-modal-fuel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ trans('message.Add Fuel Type') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="" method="post">
                            <div class="row">
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 form-group data_popup">
                                    <input type="text" class="form-control model_input fuel_type" name="fuel_type" id="fuel_type" placeholder="{{ trans('message.Enter Fuel Type') }}" maxlength="20" required />
                                </div>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 form-group data_popup">

                                    <button type="button" class="btn btn-success model_submit fueltypeadd" fuelurl="{!! url('/vehicle/vehicle_fuel_add') !!}">{{ trans('message.Submit') }}</button>
                                </div>

                            </div>
                            <table class="table fuel_type_class" align="center">

                                <tbody>
                                    @if (!empty($fuel_type))
                                    @foreach ($fuel_type as $fuel_types)
                                    <tr class="del-{{ $fuel_types->id }} data_fuel_type_name row mx-1">
                                        <td class="text-start col-6 w-50">{{ $fuel_types->fuel_type }}</td>
                                        <td class="text-end col-6 w-50">
                                            <button type="button" fuelid="{{ $fuel_types->id }}" deletefuel="{!! url('/vehicle/fueltypedelete') !!}" class="btn btn-danger text-white border-0 fueldeletes"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end Fuel Type -->


    <!-- Repair Add or Remove Model Start-->
    <div class="col-md-6">
        <div id="responsive-modal-color" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
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
                                            <button type="button" id="{{ $repairCategory->slug }}" deletecolor="{!! url('deleteRepairCategory') !!}" class="btn btn-danger text-white border-0 deletecolors"><i class="fa fa-trash" aria-hidden="true"></i></button>
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


<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/jquery-3.7.1.min.js') }}"></script>
<script nonce="{{ $cspNonce }}">
    document.getElementById('step2Btn').addEventListener('click', function () {
    // Trigger the form's submit button
    document.getElementById('submitButton').click();
});
</script>
<script nonce="{{ $cspNonce }}">
 // Add markers dynamically
let markerCount = 0; // Initialize a counter for marker numbers

document.getElementById('car-image-area').addEventListener('click', function (event) {
    // Prevent creating markers when clicking on existing markers
    if (event.target.classList.contains('damage-marker') || event.target.classList.contains('marker-number')) {
        return;
    }

    // Get the bounding box of the container
    const rect = this.getBoundingClientRect();
    const x = event.clientX - rect.left; // X-coordinate relative to the container
    const y = event.clientY - rect.top;  // Y-coordinate relative to the container

    // Increment the marker count
    markerCount++;

    // Create a new marker container to hold the number and the red dot
    const markerContainer = document.createElement('div');
    markerContainer.className = 'marker-container';
    markerContainer.style.position = 'absolute';
    markerContainer.style.left = `${x}px`; // Accurate position of the container
    markerContainer.style.top = `${y}px`; // Accurate position of the container

    // Create a number label
    const markerNumber = document.createElement('span');
    markerNumber.className = 'marker-number';
    markerNumber.innerText = markerCount; // Set the number
    markerNumber.style.position = 'absolute';
    markerNumber.style.left = '-5px'; // Offset the number to the left of the dot
    markerNumber.style.top = '10%';
    markerNumber.style.transform = 'translateY(-50%)'; // Vertically center the number
    markerNumber.style.color = 'black'; // Set the number color
    markerNumber.style.fontWeight = 'bold';

    // Create the red dot marker
    const marker = document.createElement('div');
    marker.className = 'damage-marker';
    marker.style.position = 'absolute';
    marker.style.width = '12px'; // Size of the red dot
    marker.style.height = '12px';
    marker.style.borderRadius = '50%';
    marker.style.backgroundColor = 'red'; // Default color for the dot
    marker.style.left = '0'; // Position the red dot relative to the container
    marker.style.top = '0';
    marker.title = `Damage Marker ${markerCount}`;

    // Add double-click listener to toggle marker color
    marker.addEventListener('dblclick', function (event) {
        event.stopPropagation(); // Prevent triggering parent events
        if (marker.style.backgroundColor === 'red') {
            marker.style.backgroundColor = 'red';
            marker.title = `Confirmed Marker ${markerCount}`;
        } else {
            markerContainer.remove(); // Remove the entire container (marker and number)
            updateMarkers(); // Update marker data after removal
        }
    });

    // Add right-click listener to remove marker
    marker.addEventListener('contextmenu', function (event) {
        event.preventDefault(); // Prevent the context menu
        event.stopPropagation(); // Prevent triggering parent events
        markerContainer.remove(); // Remove the entire container (marker and number)
        updateMarkers(); // Update marker data after removal
    });

    // Append the number and the marker to the container
    markerContainer.appendChild(markerNumber);
    markerContainer.appendChild(marker);

    // Append the container to the car image area
    document.getElementById('car-image-area').appendChild(markerContainer);

    // Update marker data after adding a new marker
    updateMarkers();
});

// Function to update the hidden input field with marker data
function updateMarkers() {
    const markerContainers = document.querySelectorAll('.marker-container');
    const markers = [];

    // Reset markerCount if no markers exist
    if (markerContainers.length === 0) {
        markerCount = 0;
    }

    markerContainers.forEach((container, index) => {
        const x = parseFloat(container.style.left);
        const y = parseFloat(container.style.top);

        const marker = container.querySelector('.damage-marker');
        const markerNumber = container.querySelector('.marker-number');

        // Update the marker number to maintain sequential order
        markerNumber.innerText = index + 1;
        marker.title = `Damage Marker ${index + 1}`;

        markers.push({
            x: x,
            y: y,
            number: markerNumber.innerText,
            color: marker.style.backgroundColor
        });
    });

    // Update the hidden input field with marker JSON
    document.getElementById('markers').value = JSON.stringify(markers);
}

// Function to save the image with markers
document.getElementById('save-image').addEventListener('click', function () {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const carImage = document.getElementById('car-image');

    // Ensure the image is fully loaded
    if (!carImage.complete) {
        alert('Image is not fully loaded. Please try again.');
        return;
    }

    // Set canvas size to match image size
    const imageWidth = carImage.naturalWidth;
    const imageHeight = carImage.naturalHeight;
    canvas.width = imageWidth;
    canvas.height = imageHeight;

    // Scale markers proportionally
    const scaleX = canvas.width / carImage.width;
    const scaleY = canvas.height / carImage.height;

    // Draw the car image onto the canvas
    ctx.drawImage(carImage, 0, 0, canvas.width, canvas.height);

    // Draw each marker on top of the image
    const markerContainers = document.querySelectorAll('.marker-container');
    markerContainers.forEach(container => {
        const x = parseFloat(container.style.left) * scaleX;
        const y = parseFloat(container.style.top) * scaleY;

        const marker = container.querySelector('.damage-marker');
        const markerNumber = container.querySelector('.marker-number');

        // Draw the red dot on the canvas
        ctx.beginPath();
        ctx.arc(x, y, 6, 0, 2 * Math.PI);
        ctx.fillStyle = marker.style.backgroundColor;
        ctx.fill();

        // Draw the number near the red dot
        ctx.fillStyle = 'black';
        ctx.font = 'bold 12px Arial';
        ctx.fillText(markerNumber.innerText, x - 15, y + 5);
    });

    // Save the canvas as an image (PNG)
    const imageURL = canvas.toDataURL('image/png');
    const link = document.createElement('a');
    link.href = imageURL;
    link.download = 'car_with_markers.png';
    link.click();
});
</script>

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
<style>
.damage-marker {
    color: red;
    cursor: pointer;
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
<script nonce="{{ $cspNonce }}">
    $(document).ready(function() {
        // Initialize Select2 on the dropdown
        $('#cust_id').select2({
            placeholder: "{{ trans('message.Select Customer') }}",
            allowClear: true, // Adds a clear button
            width: '100%' // Ensures the dropdown matches the input width
        });
    });
</script>
<!-- customer add -->
<script nonce="{{ $cspNonce }}">
    var msg35 = "{{ trans('message.OK') }}";
    var submitmsg = "{{ trans('message.Submitted Successfully') }}";
    var vtypedelete = "{{ trans('message.Vehicle Type Deleted Successfully') }}";
    var vbranddelete = "{{ trans('message.Vehicle Brand Deleted Successfully') }}";
    var fueldelete = "{{ trans('message.Fuel Type Deleted Successfully') }}";
    var modeldelete = "{{ trans('message.Model Deleted Successfully') }}";

    $(document).ready(function() {
        var element = document.getElementById("footerforid");
        element.classList.remove("bottom-0");
        // alert($errors['has']);

        $('body').on('change', '.chooseImage', function() {
            var imageName = $(this).val();
            var imageExtension = /(\.jpg|\.jpeg|\.png)$/i;

            if (imageExtension.test(imageName)) {
                $('.imageHideShow').css({
                    "display": ""
                });
            } else {
                $('.imageHideShow').css({
                    "display": "none"
                });
            }
        });

        var msg1 = "{{ trans('message.Are You Sure?') }}";
        var msg2 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
        var msg3 = "{{ trans('message.Cancel') }}";
        var msg4 = "{{ trans('message.Yes, delete!') }}";
        var msg5 = "{{ trans('message.Done!') }}";
        var msg6 = "{{ trans('message.It was succesfully deleted!') }}";
        var msg7 = "{{ trans('message.Cancelled') }}";
        var msg8 = "{{ trans('message.Your data is safe') }}";


        var msg100 = "{{ trans('message.An error occurred :') }}";

        $("#formcustomer").on('submit', (function(event) {

            function define_variable() {
                return {
                    firstname: $("#firstname").val(),
                    lastname: $("#lastname").val(),
                    displayname: $("#displayname").val(),
                    company_name: $("#company_name").val(),
                    email: $("#email").val(),
                    password: $("#password").val(),
                    password_confirmation: $("#password_confirmation").val(),
                    mobile: $("#mobile").val(),
                    landlineno: $("#landlineno").val(),
                    image: $("#image").val(),
                    country_id: $("#country_id option:selected").val(),
                    state_id: $("#state_id option:selected").val(),
                    city: $("#city option:selected").val(),
                    address: $("#address").val(),
                    name_pattern: /^[a-zA-Z\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                    name_pattern2: /^[a-zA-Z\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/,
                    company_patt: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/,
                    lenghtLimit: /^[0-9]{6,16}$/,
                    mobile_pattern: /^[- +()]*[0-9][- +()0-9]*$/,
                    email_pattern: /^([a-zA-Z0-9_\.\-\+\'])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
                };
            }

            event.preventDefault();
            var call_var_customeradd = define_variable();
            var errro_msg = [];
            //first name
            if (call_var_customeradd.firstname == "") {
                var msg = "{{ trans('message.First name is required.') }}";
                $('#errorlfirstname').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlfirstname').html("");
                errro_msg = [];
            }

            if (!call_var_customeradd.name_pattern.test(call_var_customeradd.firstname)) {
                var msg = "{{ trans('message.First name is only alphabets and space.') }}";
                $("#firstname").val("");
                $('#errorlfirstname').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlfirstname').html("");
                errro_msg = [];
            }

            if (!call_var_customeradd.firstname.replace(/\s/g, '').length) {

                var msg = "{{ trans('message.Only blank space not allowed') }}";
                $("#firstname").val("");
                $('#errorlfirstname').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlfirstname').html("");
                errro_msg = [];
            }

            if (!call_var_customeradd.name_pattern2.test(call_var_customeradd.firstname)) {
                var msg = "{{ trans('message.At first position only alphabets are allowed.') }}";
                $("#firstname").val("");
                $('#errorlfirstname').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlfirstname').html("");
                errro_msg = [];
            }

            //last name
            if (call_var_customeradd.lastname == "") {
                var msg = "{{ trans('message.Last name is required.') }}";
                $('#errorllastname').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorllastname').html("");
                errro_msg = [];
            }
            if (!call_var_customeradd.name_pattern.test(call_var_customeradd.lastname)) {
                var msg = "{{ trans('message.Last name is only alphabets and space.') }}";
                $('#errorllastname').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorllastname').html("");
                errro_msg = [];
            }

            if (!call_var_customeradd.lastname.replace(/\s/g, '').length) {

                var msg = "{{ trans('message.Only blank space not allowed') }}";
                $("#lastname").val("");
                $('#errorllastname').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorllastname').html("");
                errro_msg = [];
            }

            if (!call_var_customeradd.name_pattern2.test(call_var_customeradd.lastname)) {
                var msg = "{{ trans('message.At first position only alphabets are allowed.') }}";
                $('#errorllastname').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorllastname').html("");
                errro_msg = [];
            }
            //Display name
            if (call_var_customeradd.displayname != "") {

                if (!call_var_customeradd.name_pattern.test(call_var_customeradd.displayname)) {
                    var msg = "{{ trans('message.Display name is only alphabets and space.') }}";
                    $("#displayname").val("");
                    $('#errorldisplayname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else if (!call_var_customeradd.displayname.replace(/\s/g, '').length) {

                    var msg = "{{ trans('message.Only blank space not allowed') }}";
                    $("#displayname").val("");
                    $('#errorldisplayname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else if (!call_var_customeradd.name_pattern2.test(call_var_customeradd
                        .displayname)) {
                    var msg =
                        "{{ trans('message.At first position only alphabets are allowed.') }}";
                    $("#displayname").val("");
                    $('#errorldisplayname').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorldisplayname').html("");
                    errro_msg = [];
                }
            } else {
                $('#errorldisplayname').html("");
                errro_msg = [];
            }

            //Company name
            if (call_var_customeradd.company_name != "") {

                if (!call_var_customeradd.company_name.replace(/\s/g, '').length) {

                    var msg = "{{ trans('message.Only blank space not allowed') }}";
                    $("#company_name").val("");
                    $('#errorlcompanyName').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else if (!call_var_customeradd.company_patt.test(call_var_customeradd
                        .company_name)) {
                    var msg =
                        "{{ trans('message.Only alphanumeric, space, dot, @, _, and - are allowed.') }}";
                    $("#company_name").val("");
                    $('#errorlcompanyName').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else if (!call_var_customeradd.name_pattern2.test(call_var_customeradd
                        .company_name)) {
                    var msg =
                        "{{ trans('message.At first position only alphabets are allowed.') }}";
                    $("#company_name").val("");
                    $('#errorlcompanyName').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorlcompanyName').html("");
                    errro_msg = [];
                }
            } else {
                $('#errorlcompanyName').html("");
                errro_msg = [];
            }
            //Email 
            if (call_var_customeradd.email == "") {
                var msg = "{{ trans('message.Email is required.') }}";
                $('#errorlemail').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlemail').html("");
                errro_msg = [];
            }

            if (!call_var_customeradd.email.replace(/\s/g, '').length) {

                var msg = "{{ trans('message.Only blank space not allowed') }}";
                $("#email").val("");
                $('#errorlemail').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlfirstname').html("");
                errro_msg = [];
            }

            if (!call_var_customeradd.email_pattern.test(call_var_customeradd.email)) {
                var msg =
                    "{{ trans('message.Please enter a valid email address. Like : sales@mojoomla.com') }}";
                $('#errorlemail').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlemail').html("");
                errro_msg = [];
            }

            //Password 
            if (call_var_customeradd.password == "") {
                var msg = "{{ trans('message.Password is required.') }}";
                $('#errorlpassword').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlpassword').html("");
                errro_msg = [];
            }
            //Confirm Password 
            if (call_var_customeradd.password_confirmation == "") {
                var msg = "{{ trans('message.Confirm password is required.') }}";
                $('#errorlpassword_confirmation').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlpassword_confirmation').html("");
                errro_msg = [];
            }

            //same Password and password_confirmation  
            if (call_var_customeradd.password != call_var_customeradd.password_confirmation) {
                var msg = "{{ trans('message.Password and Confirm Password does not match.') }}";
                $('#errorlpassword_confirmation').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlpassword').html("");
                errro_msg = [];
            }

            //Mobile number 
            if (call_var_customeradd.mobile == "") {
                var msg = "{{ trans('message.Contact number is required.') }}";
                $('#errorlmobile').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlmobile').html("");
                errro_msg = [];
            }
            if (!call_var_customeradd.mobile_pattern.test(call_var_customeradd.mobile)) {
                var msg =
                    "{{ trans('message.Contact number must be number, plus, minus and space only.') }}";
                $("#mobile").val("");
                $('#errorlmobile').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlmobile').html("");
                errro_msg = [];
            }

            if (!call_var_customeradd.mobile.replace(/\s/g, '').length) {

                var msg = "{{ trans('message.Only blank space not allowed') }}";
                $("#mobile").val("");
                $('#errorlmobile').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlmobile').html("");
                errro_msg = [];
            }

            //LandLine number
            if (call_var_customeradd.landlineno != "") {
                if (!call_var_customeradd.mobile_pattern.test(call_var_customeradd.landlineno)) {
                    var msg =
                        "{{ trans('message.Landline number must be number, plus, minus and space only.') }}";
                    $("#landlineno").val("");
                    $('#errorllandlineno').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else if (!call_var_customeradd.lenghtLimit.test(call_var_customeradd
                        .landlineno)) {
                    var msg = "{{ trans('message.Landline number between 6 to 16 digits only') }}";
                    $("#landlineno").val("");
                    $('#errorllandlineno').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else if (!call_var_customeradd.landlineno.replace(/\s/g, '').length) {

                    var msg = "{{ trans('message.Only blank space not allowed') }}";
                    $("#landlineno").val("");
                    $('#errorllandlineno').html(msg);
                    errro_msg.push(msg);
                    return false;
                } else {
                    $('#errorllandlineno').html("");
                    errro_msg = [];
                }
            } else {
                $('#errorllandlineno').html("");
                errro_msg = [];
            }

            //Country 
            if (call_var_customeradd.country_id == "") {
                var msg = "{{ trans('message.Country field is required.') }}";
                $('#errorlcountry_id').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlcountry_id').html("");
                errro_msg = [];
            }
            //Address 
            if (call_var_customeradd.address == "") {
                var msg = "{{ trans('message.Address field is required.') }}";
                $('#errorladdress').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorladdress').html("");
                errro_msg = [];
            }

            if (!call_var_customeradd.address.replace(/\s/g, '').length) {

                var msg = "{{ trans('message.Only blank space not allowed') }}";
                $("#address").val("");
                $('#errorladdress').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorladdress').html("");
                errro_msg = [];
            }

            if (errro_msg == "") {
                var firstname = $('#firstname').val();
                var lastname = $('#lastname').val();
                var displayname = $('#displayname').val();
                var company_name = $('#company_name').val();
                var gender = $(".gender:checked").val();
                var dob = $("#datepicker").val();
                var email = $("#email").val();
                var password = $("#password").val();
                var mobile = $("#mobile").val();
                var landlineno = $("#landlineno").val();
                var image = $("#image").val();
                var country_id = $("#country_id option:selected").val();
                var state_id = $("#state_id option:selected").val();
                var city = $("#city option:selected").val();
                var address = $("#address").val();

                $.ajax({
                    type: 'POST',
                    url: '{!! url('service/customeradd') !!}',
                    data: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    contentType: false,
                    cache: false,
                    processData: false,

                    success: function(data) {
                        $('.select_vhi').append('<option selected value=' + data['customerId'] +
                            '>' + data[
                                'customer_fullname'] + '</option>');

                        var firstname = $('#firstname').val('');
                        var lastname = $('#lastname').val('');
                        var displayname = $('#displayname').val('');
                        var gender = $(".gender:checked").val('');
                        var dob = $("#datepicker").val('');
                        var email = $("#email").val('');
                        var password = $("#password").val('');
                        var mobile = $("#mobile").val('');
                        var landlineno = $("#landlineno").val('');
                        var image = $("#image").val('');
                        var country_id = $("#country_id option:selected").val('');
                        var state_id = $("#state_id option:selected").val('');
                        var city = $("#city option:selected").val('');
                        var address = $("#address").val('');
                        var company_name = $("#company_name").val('');
                        $(".addcustomermsg").removeClass("hide");

                        $('.hidden_customer_id').val(data['customerId']);

                        // modal close after add data
                        $('#myModal').modal('toggle');

                    },
                    error: function(e) {
                        alert(msg100 + " " + e.responseText);
                        console.log(e);
                    }
                });
            }
        }));


        /*customer model state to city*/
        $('.select_country').change(function() {
            countryid = $(this).val();
            var url = $(this).attr('countryurl');
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    countryid: countryid
                },
                success: function(response) {
                    $('.state_of_country').html(response);
                }
            });
        });


        $('body').on('change', '.state_of_country', function() {
            stateid = $(this).val();

            var url = $(this).attr('stateurl');
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    stateid: stateid
                },
                success: function(response) {
                    $('.city_of_state').html(response);
                }
            });
        });

        /*vehical Type from brand*/
        $('.select_vehicaltype').change(function() {
            vehical_id = $(this).val();
            var url = $(this).attr('vehicalurl');
            sessionStorage.setItem('selectedType', vehical_id);

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    vehical_id: vehical_id
                },
                success: function(response) {
                    $('.select_vehicalbrand').html(response);

                    $('.select_vehicalbrand').trigger('change');
                }
            });
        });

        // Function to set selected vehicle type when modal is shown
        $('#responsive-modal-brand').on('shown.bs.modal', function() {
            var selectedType = sessionStorage.getItem('selectedType');
            $('#vehicleTypeSelect').val(selectedType);
        });

        /*vehical Model from brand*/
        $('.select_vehicalbrand').change(function() {
            id = $(this).val();
            var url = $(this).attr('url');
            sessionStorage.setItem('selectedBrand', id);

            $('.vehical_id').val(id).trigger('change');

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    id: id
                },
                success: function(response) {
                    $('.model_addname').html(response);
                }
            });
        });

        // Function to set selected brand when modal is shown
        $('#responsive-modal-vehi-model').on('shown.bs.modal', function() {
            var selectedBrand = sessionStorage.getItem('selectedBrand');
            $('#vehicleBrandSelect').val(selectedBrand);
        });

        /*images show in multiple in for loop*/
        $(".imageclass").click(function() {
            $(".classimage").empty();
        });


        function preview_images() {
            var total_file = document.getElementById("images").files.length;

            for (var i = 0; i < total_file; i++) {
                $('#image_preview').append(
                    "<div class='col-md-3 col-sm-3 col-xs-12' style='padding:5px;'><img class='uploadImage' src='" +
                    URL
                    .createObjectURL(event.target.files[i]) + "' width='100px' height='60px'> </div>");
            }
        }


    // /*vehicle add*/
        // $('body').on('click', '.addvehicleservice', function(event) {
        //     console.log("called");
        //     function define_variable() {
        //         return {
        //             vehical_id1: $("#vehical_id1").val(),
        //             chasicno1: $("#chasicno1").val(),
        //             vehicabrand1: $("#vehicabrand1").val(),
        //             modelname1: $("#modelname1").val(),
        //             engineno1: $("#engineno1").val(),
        //             pp: $('#fueltype1').val(),
        //             pricePattern: /^[0-9]*$/,
        //             np: $('#number_plate').val(),
        //         };
        //     }

        //     event.preventDefault();
        //     var call_var_vehicleadd = define_variable();
        //     var errro_msg = [];

        //     //Vehicle type
        //     if (call_var_vehicleadd.vehical_id1 == "") {
        //         var msg = "Vehical type is required";
        //         $('#errorlvehical_id1').html(msg);
        //         errro_msg.push(msg);
        //         return false;
        //     } else {
        //         $('#errorlvehical_id1').html("");
        //         errro_msg = [];
        //     }
        //     //Vehical brand
        //     if (call_var_vehicleadd.vehicabrand1 == "") {
        //         var msg = "Vehical brand is required";
        //         $('#errorlvehicabrand1').html(msg);
        //         errro_msg.push(msg);
        //         return false;
        //     } else {
        //         $('#errorlvehicabrand1').html("");
        //         errro_msg = [];
        //     }
        //     //Model name
        //     if (call_var_vehicleadd.modelname1 == "") {
        //         var msg = "Model name is required";
        //         $('#errorlmodelname1').html(msg);
        //         errro_msg.push(msg);
        //         return false;
        //     } else {
        //         $('#errorlmodelname1').html("");
        //         errro_msg = [];
        //     }
        //     //Fuel Type
        //     if (call_var_vehicleadd.pp == "") {
        //         var msg = "Fuel Type is required";
        //         $('#fuel1').html(msg);
        //         errro_msg.push(msg);
        //         return false;
        //     } else {
        //         $('#fuel1').html("");
        //         errro_msg = [];
        //     }
        //     //Number Plate
        //     // if (call_var_vehicleadd.np == "") {
        //     //     var msg = "Number Plate is required";
        //     //     $('#npe').html(msg);
        //     //     errro_msg.push(msg);
        //     //     return false;
        //     // } else {
        //     //     $('#npe').html("");
        //     //     errro_msg = [];
        //     // }

        //     if (call_var_vehicleadd.np == "") {
        //         var msg = "Number Plate is required";
        //         $('#npe').html(msg);
        //         errro_msg.push(msg);
        //         return false;
        //     } else {
        //         // $('#npe').html("");
        //         // errro_msg = [];
        //         var numberPlate = call_var_vehicleadd.np;
        //         $.ajax({
        //             type: 'GET',
        //             url: '{!! url('/vehicle/check-number-plate-unique') !!}',
        //             data: {numberPlate: numberPlate},
        //             success: function(response) {
        //                 if(response.unique == false) {
        //                     var msg = "Number Plate you entered is already registered.";
        //                     $('#npe').html(msg);
        //                     errro_msg.push(msg);
        //                     canSubmitForm = false;
        //                 } else if(response.unique == true) {
        //                     alert('else if called');
        //                     $('#npe').html("");
        //                     errro_msg = [];
        //                     canSubmitForm = true;
        //                 }
        //             }
        //         });
        //     }

        //     if (!call_var_vehicleadd.pp.replace(/\s/g, '').length) {
        //         var msg = "Only blank space not allowed";
        //         $('#price1').val("");
        //         $('#ppe').html(msg);
        //         errro_msg.push(msg);
        //         return false;
        //     } else {
        //         $('#ppe').html("");
        //         errro_msg = [];
        //     }

        //     if (!call_var_vehicleadd.pricePattern.test(call_var_vehicleadd.pp)) {
        //         var msg = "Only numeric data allowed";
        //         $('#price1').val("");
        //         $('#ppe').html(msg);
        //         errro_msg.push(msg);
        //         return false;
        //     } else {
        //         $('#ppe').html("");
        //         errro_msg = [];
        //     }

        //     if (errro_msg == "") {
        //         alert(canSubmitForm);
        //         if (!canSubmitForm) {
        //             // Form submission is not allowed due to previous validation checks
        //             return false;
        //         }
        //         var vehical_id1 = $('#vehical_id1').val();
        //         var chasicno1 = $('#chasicno1').val();
        //         var vehicabrand1 = $('#vehicabrand1').val();
        //         var modelyear1 = $('#modelyear1').val();
        //         var fueltype1 = $('#fueltype1').val();
        //         var gearno1 = $('#gearno1').val();
        //         var modelname1 = $('#modelname1').val();
        //         var price1 = $('#price1').val();
        //         var odometerreading1 = $('#odometerreading1').val();
        //         var dom1 = $('#dom1').val();
        //         var gearbox1 = $('#gearbox1').val();
        //         var gearboxno1 = $('#gearboxno1').val();
        //         var engineno1 = $('#engineno1').val();
        //         var enginesize1 = $('#enginesize1').val();
        //         var keyno1 = $('#keyno1').val();
        //         var engine1 = $('#engine1').val();
        //         var numberPlate = $('#number_plate').val();
        //         var customer_id = $('.hidden_customer_id').val();
        //         var branch_id_vehicle = $('.select_branch_vehicle').val();

        //         $.ajax({
        //             type: 'get',
        //             url: '{!! url('/service/vehicleadd') !!}',
        //             data: {
        //                 vehical_id1: vehical_id1,
        //                 chasicno1: chasicno1,
        //                 vehicabrand1: vehicabrand1,
        //                 modelyear1: modelyear1,
        //                 fueltype1: fueltype1,
        //                 gearno1: gearno1,
        //                 modelname1: modelname1,
        //                 price1: price1,
        //                 odometerreading1: odometerreading1,
        //                 dom1: dom1,
        //                 gearbox1: gearbox1,
        //                 gearboxno1: gearboxno1,
        //                 engineno1: engineno1,
        //                 enginesize1: enginesize1,
        //                 keyno1: keyno1,
        //                 engine1: engine1,
        //                 numberPlate: numberPlate,
        //                 customer_id: customer_id,
        //                 branch_id_vehicle: branch_id_vehicle
        //             },
        //             success: function(data) {
        //                 var modelname1 = $('#modelname1').val();
        //                 var vehicabrand1 = $('#vehicabrand1').find(":selected").text();
        //                 var numberPlate = $('#number_plate').val();
        //                 // var vehical_id1 = $('#vehical_id1').val();
        //                 var vehical_id1 = data;

        //                 var optionText = vehicabrand1 + '/' + modelname1 + '/' + numberPlate + '/' + vehical_id1;

        //                 $('.modelnameappend').append('<option value="' + vehical_id1 + '">' + optionText + '</option>');
        //                 var vehical_id1 = $('#vehical_id1').val('');
        //                 var chasicno1 = $('#chasicno1').val('');
        //                 var vehicabrand1 = $('#vehicabrand1').val('');
        //                 var modelyear1 = $('#modelyear1').val('');
        //                 var fueltype1 = $('#fueltype1').val('');
        //                 var gearno1 = $('#gearno1').val('');
        //                 var modelname1 = $('#modelname1').val('');
        //                 var price1 = $('#price1').val('');
        //                 var odometerreading1 = $('#odometerreading1').val('');
        //                 var dom1 = $('#dom1').val('');
        //                 var gearbox1 = $('#gearbox1').val('');
        //                 var gearboxno1 = $('#gearboxno1').val('');
        //                 var engineno1 = $('#engineno1').val('');
        //                 var enginesize1 = $('#enginesize1').val('');
        //                 var keyno1 = $('#keyno1').val('');
        //                 var engine1 = $('#engine1').val('');
        //                 var number_plate = $('#number_plate').val('');
        //                 $(".addvehiclemsg").removeClass("hide");


        //                 $('#vehiclemymodel').modal('toggle');
        //             },
        //             error: function(e) {
        //                 alert(msg42 + " " + e.responseText);
        //                 console.log(e);
        //             }
        //         });
        //     }
    // });

        $('body').on('click', '.addvehicleservice', function(event) {
            function define_variable() {
                return {
                    vehical_id1: $("#vehical_id1").val(),
                    chasicno1: $("#chasicno1").val(),
                    vehicabrand1: $("#vehicabrand1").val(),
                    modelname1: $("#modelname1").val(),
                    engineno1: $("#engineno1").val(),
                    pp: $('#fueltype1').val(),
                    pricePattern: /^[0-9]*$/,
                    np: $('#number_plate').val(),
                };
            }

            event.preventDefault();
            var call_var_vehicleadd = define_variable();
            var errro_msg = [];

            //Vehicle type
            if (call_var_vehicleadd.vehical_id1 == "") {
                var msg = "Vehical type is required";
                $('#errorlvehical_id1').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlvehical_id1').html("");
                errro_msg = [];
            }

            //Vehical brand
            if (call_var_vehicleadd.vehicabrand1 == "") {
                var msg = "Vehical brand is required";
                $('#errorlvehicabrand1').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlvehicabrand1').html("");
                errro_msg = [];
            }

            //Model name
            if (call_var_vehicleadd.modelname1 == "") {
                var msg = "Model name is required";
                $('#errorlmodelname1').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#errorlmodelname1').html("");
                errro_msg = [];
            }

            //Fuel Type
            if (call_var_vehicleadd.pp == "") {
                var msg = "Fuel Type is required";
                $('#fuel1').html(msg);
                errro_msg.push(msg);
                return false;
            } else {
                $('#fuel1').html("");
                errro_msg = [];
            }

            // Number Plate
            if (call_var_vehicleadd.np == "") {
                var msg = "Number Plate is required";
                $('#npe').html(msg);
                errro_msg.push(msg);
                return false;
            }
                       
            // AJAX request to check number plate uniqueness

            $.ajax({
                type: 'GET',
                url: '{!! url('/vehicle/check-number-plate-unique') !!}',
                data: {numberPlate: call_var_vehicleadd.np},
                success: function(response) {
                    if(response.unique == false) {
                        var msg = "Number Plate you entered is already registered.";
                        $('#npe').html(msg);
                        errro_msg.push(msg);
                        submitForm(errro_msg);
                    } else if(response.unique == true) {
                        errro_msg = [];
                        submitForm(errro_msg);
                    }
                }
            });

            if (errro_msg == "") {
                // Proceed with form submission
                submitForm();
            }

            // Function to submit form with error messages
            function submitForm(errro_msg) {
                if (errro_msg == "") {
                    // Proceed with form submission
                    var vehical_id1 = $('#vehical_id1').val();
                    var chasicno1 = $('#chasicno1').val();
                    var vehicabrand1 = $('#vehicabrand1').val();
                    var modelyear1 = $('#modelyear1').val();
                    var fueltype1 = $('#fueltype1').val();
                    var gearno1 = $('#gearno1').val();
                    var modelname1 = $('#modelname1').val();
                    var price1 = $('#price1').val();
                    var odometerreading1 = $('#odometerreading1').val();
                    var dom1 = $('#dom1').val();
                    var gearbox1 = $('#gearbox1').val();
                    var gearboxno1 = $('#gearboxno1').val();
                    var engineno1 = $('#engineno1').val();
                    var enginesize1 = $('#enginesize1').val();
                    var keyno1 = $('#keyno1').val();
                    var engine1 = $('#engine1').val();
                    var numberPlate = $('#number_plate').val();
                    var customer_id = $('.hidden_customer_id').val();
                    var branch_id_vehicle = $('.select_branch_vehicle').val();

                    $.ajax({
                        type: 'get',
                        url: '{!! url('/service/vehicleadd') !!}',
                        data: {
                            vehical_id1: vehical_id1,
                            chasicno1: chasicno1,
                            vehicabrand1: vehicabrand1,
                            modelyear1: modelyear1,
                            fueltype1: fueltype1,
                            gearno1: gearno1,
                            modelname1: modelname1,
                            price1: price1,
                            odometerreading1: odometerreading1,
                            dom1: dom1,
                            gearbox1: gearbox1,
                            gearboxno1: gearboxno1,
                            engineno1: engineno1,
                            enginesize1: enginesize1,
                            keyno1: keyno1,
                            engine1: engine1,
                            numberPlate: numberPlate,
                            customer_id: customer_id,
                            branch_id_vehicle: branch_id_vehicle
                        },
                        success: function(data) {
                            var modelname1 = $('#modelname1').val();
                            var vehicabrand1 = $('#vehicabrand1').find(":selected").text();
                            var numberPlate = $('#number_plate').val();
                            var vehical_id1 = data;

                            var optionText = vehicabrand1 + '/' + modelname1 + '/' + numberPlate + '/' + vehical_id1;

                            $('.modelnameappend').append('<option selected value="' + vehical_id1 + '">' + optionText + '</option>');
                            var vehical_id1 = $('#vehical_id1').val('');
                            var chasicno1 = $('#chasicno1').val('');
                            var vehicabrand1 = $('#vehicabrand1').val('');
                            var modelyear1 = $('#modelyear1').val('');
                            var fueltype1 = $('#fueltype1').val('');
                            var gearno1 = $('#gearno1').val('');
                            var modelname1 = $('#modelname1').val('');
                            var price1 = $('#price1').val('');
                            var odometerreading1 = $('#odometerreading1').val('');
                            var dom1 = $('#dom1').val('');
                            var gearbox1 = $('#gearbox1').val('');
                            var gearboxno1 = $('#gearboxno1').val('');
                            var engineno1 = $('#engineno1').val('');
                            var enginesize1 = $('#enginesize1').val('');
                            var keyno1 = $('#keyno1').val('');
                            var engine1 = $('#engine1').val('');
                            var number_plate = $('#number_plate').val('');
                            $(".addvehiclemsg").removeClass("hide");

                            $('#vehiclemymodel').modal('toggle');
                        },
                        error: function(e) {
                            alert(msg42 + " " + e.responseText);
                            console.log(e);
                        }
                    });
                }
            }
        });

        var msg10 = "{{ trans('message.Please enter only alphanumeric data') }}";
        var msg11 = "{{ trans('message.Only blank space not allowed') }}";
        var msg12 = "{{ trans('message.This Record is Duplicate') }}";
        /*Add Vehicle Model*/
        $('.vehi_model_add').click(function() {
            var model_name = $('.vehi_modal_name').val();
            var model_url = $(this).attr('modelurl');
            var brand_id = $('.vehi_brand_id').val();

            var msg9 = "{{ trans('message.Please enter model name') }}";

            function define_variable() {
                return {
                    vehicle_model_value: $('.vehi_modal_name').val(),
                    vehicle_model_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                    vehicle_model_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/
                };
            }

            var call_var_vehiclemodeladd = define_variable();

            if (model_name == "") {
                swal({
                    title: msg9,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else if (!call_var_vehiclemodeladd.vehicle_model_pattern.test(call_var_vehiclemodeladd
                    .vehicle_model_value)) {
                $('.vehi_modal_name').val("");
                swal({
                    title: msg14,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else if (!model_name.replace(/\s/g, '').length) {
                $('.vehi_modal_name').val("");
                swal({
                    title: msg15,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else if (!call_var_vehiclemodeladd.vehicle_model_pattern2.test(call_var_vehiclemodeladd
                    .vehicle_model_value)) {
                $('.vehi_modal_name').val("");
                swal({
                    title: msg34,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else {
                $.ajax({
                    type: 'GET',
                    url: model_url,
                    data: {
                        model_name: model_name,
                        brand_id: brand_id
                    },

                    beforeSend: function() {
                        $(".vehi_model_add").prop('disabled', true);
                    },

                    success: function(data) {
                        var newd = $.trim(data);
                        var classname = 'mod-' + newd;

                        if (newd == '01') {
                            swal({
                                title: msg16,
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            });
                        } else {
                            $('.vehi_model_class').append('<tr class=" data_color_name row mx-1 ' + classname +
                                '"><td class="text-start col-6">' +
                                model_name +
                                '</td><td class="text-end col-6"><button type="button" modelid=' +
                                data +
                                ' deletemodel="{!! url(' / vehicle / vehicle_model_delete ') !!}" class="btn btn-danger text-white border-0 modeldeletes"><i class="fa fa-trash" aria-hidden="true"></i></button></a></td><tr>'
                            );
                            $('.model_addname').append("<option selected value='" + model_name +
                                "'>" + model_name +
                                "</option>");
                            $('.vehi_modal_name').val('');

                            swal({
                                title: msg5,
                                text: submitmsg,
                                icon: 'success',
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            }).then(() => {
                                sessionStorage.removeItem('selectedBrand');
                                $('#responsive-modal-vehi-model').modal('hide'); // Close the modal
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                $('body').css('padding-right', '');
                            });
                        }

                        $(".vehi_model_add").prop('disabled', false);
                        return false;
                    },
                });
            }
        });



        $('body').on('click', '.modeldeletes', function() {
            var mod_del_id = $(this).attr('modelid');
            var del_url = $(this).attr('deletemodel');
            var msg1 = "{{ trans('message.Are You Sure?') }}";
            var msg2 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
            var msg3 = "{{ trans('message.Cancel') }}";
            var msg4 = "{{ trans('message.Yes, delete!') }}";
            var msg5 = "{{ trans('message.Done!') }}";
            // var msg6 = "{{ trans('message.It was succesfully deleted!') }}";
            var msg7 = "{{ trans('message.Cancelled') }}";
            var msg8 = "{{ trans('message.Your data is safe') }}";
            swal({
                title: msg1,
                text: msg2,
                icon: "warning",
                buttons: [msg3, msg4],
                dangerMode: true,
                cancelButtonColor: "#C1C1C1",
            }).then((isConfirm) => {
                if (isConfirm) {
                    $.ajax({
                        type: 'GET',
                        url: del_url,
                        data: {
                            mod_del_id: mod_del_id
                        },
                        success: function(data) {
                            $('.del-' + mod_del_id).remove();
                            $(".model_addname option[value=" + mod_del_id + "]")
                                .remove();
                            swal({
                                title: msg5,
                                text: modeldelete,
                                icon: 'success',
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            }).then(() => {
                                $('#responsive-modal-vehi-model').modal('hide'); // Close the modal
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                $('body').css('padding-right', '');
                            });
                        }
                    });
                } else {
                    swal({
                        title: msg7,
                        text: msg8,
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
        var msg14 = "{{ trans('message.Please enter only alphanumeric data') }}";

        /*vehicle type*/
        $('.vehicaltypeadd').click(function() {

            var vehical_type = $('.vehical_type').val();
            var url = $(this).attr('url');

            var msg13 = "{{ trans('message.Please enter vehicle type') }}";
            var msg15 = "{{ trans('message.Only blank space not allowed') }}";
            var msg16 = "{{ trans('message.This Record is Duplicate') }}";

            function define_variable() {
                return {
                    vehicle_type_value: $('.vehical_type').val(),
                    vehicle_type_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                    vehicle_type_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/
                };
            }

            var call_var_vehicletypeadd = define_variable();

            if (vehical_type == "") {
                swal({
                    title: msg13,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else if (!call_var_vehicletypeadd.vehicle_type_pattern.test(call_var_vehicletypeadd
                    .vehicle_type_value)) {
                $('.vehical_type').val("");
                swal({
                    title: msg14,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else if (!vehical_type.replace(/\s/g, '').length) {
                $('.vehical_type').val("");
                swal({
                    title: msg15,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else if (!call_var_vehicletypeadd.vehicle_type_pattern2.test(call_var_vehicletypeadd
                    .vehicle_type_value)) {
                $('.vehical_type').val("");
                swal({
                    title: msg34,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else {
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        vehical_type: vehical_type
                    },
                    success: function(data) {
                        var newd = $.trim(data);
                        var classname = 'del-' + newd;

                        if (newd == '01') {
                            swal({
                                title: msg16,
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            });
                        } else {
                            $('.vehical_type_class').append('<tr class=" row mx-1 ' + classname +
                                ' data_vehicle_type_name "><td class="text-start w-50">' +
                                vehical_type +
                                '</td><td class="text-end w-50"><button type="button" vehicletypeid=' +
                                data +
                                ' deletevehical="{!! url(' / vehicle / vehicaltypedelete ') !!}" class="btn btn-danger text-white border-0 deletevehicletype"><i class="fa fa-trash" aria-hidden="true"></i></button></a></td><tr>'
                            );

                            $('.select_vehicaltype').append('<option selected value=' + data + '>' +
                                vehical_type + '</option>');

                            $('.vehical_type').val('');

                            $('.vehical_id').append('<option value=' + data + '>' +
                                vehical_type + '</option>');

                            $('.vehical_type').val('');

                            $('.select_vehicaltype').trigger('change');

                            swal({
                                title: msg5,
                                text: submitmsg,
                                icon: 'success',
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            }).then(() => {
                                $('#responsive-modal').modal('hide'); // Close the modal
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                $('body').css('padding-right', '');
                            });
                        }
                    },
                });
            }
        });



        /*vehical Type delete*/
        $('body').on('click', '.deletevehicletype', function() {

            var vtypeid = $(this).attr('vehicletypeid');
            var url = $(this).attr('deletevehical');

            var msg1 = "{{ trans('message.Are You Sure?') }}";
            var msg2 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
            var msg3 = "{{ trans('message.Cancel') }}";
            var msg4 = "{{ trans('message.Yes, delete!') }}";
            var msg5 = "{{ trans('message.Done!') }}";
            // var msg6 = "{{ trans('message.It was succesfully deleted!') }}";
            var msg7 = "{{ trans('message.Cancelled') }}";
            var msg8 = "{{ trans('message.Your data is safe') }}";
            swal({
                title: msg1,
                text: msg2,
                icon: "warning",
                buttons: [msg3, msg4],
                dangerMode: true,
                cancelButtonColor: "#C1C1C1",
            }).then((isConfirm) => {
                if (isConfirm) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            vtypeid: vtypeid
                        },
                        success: function(data) {
                            $('.del-' + vtypeid).remove();
                            $(".select_vehicaltype option[value=" + vtypeid + "]")
                                .remove();
                            swal({
                                title: msg5,
                                text: vtypedelete,
                                icon: 'success',
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            }).then(() => {
                                $('#responsive-modal').modal('hide'); // Close the modal
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                $('body').css('padding-right', '');
                            });
                        }
                    });
                } else {
                    swal({
                        title: msg7,
                        text: msg8,
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


        /*vehical brand*/
        $('.vehicalbrandadd').click(function() {
            var vehical_id = $('.vehicle_type_model').val();
            console.log(vehical_id);
            var vehical_brand = $('.vehical_brand').val();
            var url = $(this).attr('vehiclebrandurl');

            var msg17 = "{{ trans('message.Please first select vehicle type') }}";
            var msg18 = "{{ trans('message.Please enter vehicle brand') }}";
            var msg19 = "{{ trans('message.Please enter only alphanumeric data') }}";
            var msg20 = "{{ trans('message.Only blank space not allowed') }}";
            var msg21 = "{{ trans('message.This Record is Duplicate') }}";

            function define_variable() {
                return {
                    vehicle_brand_value: $('.vehical_brand').val(),
                    vehicle_brand_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                    vehicle_brand_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/
                };
            }

            var call_var_vehiclebrandadd = define_variable();

            if ($(".vehicle_type_model")[0].selectedIndex <= 0) {
                swal({
                    title: msg17,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else {
                if (vehical_brand == "") {
                    swal({
                        title: msg18,
                        cancelButtonColor: '#C1C1C1',
                        buttons: {
                            cancel: msg35,
                        },
                        dangerMode: true,
                    });
                } else if (!call_var_vehiclebrandadd.vehicle_brand_pattern.test(call_var_vehiclebrandadd
                        .vehicle_brand_value)) {
                    $('.vehical_brand').val("");
                    swal({
                        title: msg19,
                        cancelButtonColor: '#C1C1C1',
                        buttons: {
                            cancel: msg35,
                        },
                        dangerMode: true,
                    });

                } else if (!vehical_brand.replace(/\s/g, '').length) {
                    // var str = "    ";
                    $('.vehical_brand').val("");
                    swal({
                        title: msg20,
                        cancelButtonColor: '#C1C1C1',
                        buttons: {
                            cancel: msg35,
                        },
                        dangerMode: true,
                    });
                } else if (!call_var_vehiclebrandadd.vehicle_brand_pattern2.test(
                        call_var_vehiclebrandadd
                        .vehicle_brand_value)) {
                    $('.vehical_brand').val("");
                    swal({
                        title: msg34,
                        cancelButtonColor: '#C1C1C1',
                        buttons: {
                            cancel: msg35,
                        },
                        dangerMode: true,
                    });

                } else {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            vehical_id: vehical_id,
                            vehical_brand: vehical_brand
                        },
                        success: function(data) {
                            var newd = $.trim(data);
                            var classname = 'del-' + newd;

                            if (newd == "01") {
                                swal({
                                    title: msg21,
                                    cancelButtonColor: '#C1C1C1',
                                    buttons: {
                                        cancel: msg35,
                                    },
                                    dangerMode: true,
                                });
                            } else {
                                $('.vehical_brand_class').append('<tr class=" row mx-1 ' + classname +
                                    ' data_vehicle_brand_name"><td class="text-start w-50">' +
                                    vehical_brand +
                                    '</td><td class="text-end w-50"><button type="button" brandid=' +
                                    data +
                                    'deletevehicalbrand="{!! url('vehicle/vehicalbranddelete') !!}" class="btn btn-danger text-white border-0 deletevehiclebrands"><i class="fa fa-trash" aria-hidden="true"></i></button></a></td><tr>'
                                );

                                $('.select_vehicalbrand').append('<option selected value=' + data +
                                    '>' + vehical_brand +
                                    '</option>');

                                $('.vehi_brand_id').append('<option value=' + data +
                                    '>' + vehical_brand +
                                    '</option>');

                                $('.vehical_brand').val('');

                                $('.select_vehicalbrand').trigger('change');

                                swal({
                                    title: msg5,
                                    text: submitmsg,
                                    icon: 'success',
                                    cancelButtonColor: '#C1C1C1',
                                    buttons: {
                                        cancel: msg35,
                                    },
                                    dangerMode: true,
                                }).then(() => {
                                    sessionStorage.removeItem('selectedType');
                                    $('#responsive-modal-brand').modal('hide'); // Close the modal
                                    $('.modal-backdrop').remove();
                                    $('body').removeClass('modal-open');
                                    $('body').css('padding-right', '');
                                });
                            }
                        },
                    });
                }
            }
        });
        /*vehical brand delete*/
        $('body').on('click', '.deletevehiclebrands', function() {
            var vbrandid = $(this).attr('brandid');
            var url = $(this).attr('deletevehicalbrand');

            var msg1 = "{{ trans('message.Are You Sure?') }}";
            var msg2 = "{{ trans('message.You will not be able to recover this data afterwards!') }}";
            var msg3 = "{{ trans('message.Cancel') }}";
            var msg4 = "{{ trans('message.Yes, delete!') }}";
            var msg5 = "{{ trans('message.Done!') }}";
            // var msg6 = "{{ trans('message.It was succesfully deleted!') }}";
            var msg7 = "{{ trans('message.Cancelled') }}";
            var msg8 = "{{ trans('message.Your data is safe') }}";
            swal({
                title: msg1,
                text: msg2,
                icon: "warning",
                buttons: [msg3, msg4],
                dangerMode: true,
                cancelButtonColor: "#C1C1C1",
            }).then((isConfirm) => {
                if (isConfirm) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            vbrandid: vbrandid
                        },
                        success: function(data) {
                            $('.del-' + vbrandid).remove();
                            $(".select_vehicalbrand option[value=" + vbrandid + "]")
                                .remove();
                            swal({
                                title: msg5,
                                text: vbranddelete,
                                icon: 'success',
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            }).then(() => {
                                $('#responsive-modal-brand').modal('hide'); // Close the modal
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                $('body').css('padding-right', '');
                            });
                        }
                    });
                } else {
                    swal({
                        title: msg7,
                        text: msg8,
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

        /*Fuel  Type delete*/
        $('body').on('click', '.fueldeletes', function() {
            var fueltypeid = $(this).attr('fuelid');
            var url = $(this).attr('deletefuel');
            swal({
                title: msg1,
                text: msg102,
                icon: "warning",
                buttons: [msg3, msg4],
                dangerMode: true,
                cancelButtonColor: "#C1C1C1",
            }).then((isConfirm) => {
                if (isConfirm) {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            fueltypeid: fueltypeid
                        },
                        success: function(data) {
                            $('.del-' + fueltypeid).remove();
                            $(".select_fueltype option[value=" + fueltypeid + "]")
                                .remove();
                            swal({
                                title: msg5,
                                text: fueldelete,
                                icon: 'success',
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            }).then(() => {
                                $('#responsive-modal-fuel').modal('hide'); // Close the modal
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                $('body').css('padding-right', '');
                            });
                        }
                    });
                } else {
                    swal({
                        title: msg7,
                        text: msg8,
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

        /*Datepicker*/
        var today = new Date();
        var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var dateTime = date + ' ' + time;
        $('.datepicker').datetimepicker({
            format: "<?php echo getDateTimepicker(); ?>",
            todayBtn: true,
            autoclose: 1,
            // minView: 2,
            startDate: new Date(),
            language: "{{ getLangCode() }}",
        });



        $(".datepickercustmore").datetimepicker({
            format: "<?php echo getDatepicker(); ?>",
            minDate: new Date(),
            todayBtn: true,
            autoclose: 1,
            minView: 2,
            language: "{{ getLangCode() }}",
        });

        $('.datepicker1').datetimepicker({
            format: "<?php echo getDatepicker(); ?>",
            todayBtn: true,
            autoclose: 1,
            minView: 2,
            endDate: new Date(),
            language: "{{ getLangCode() }}",
        });
        $('.myDatepicker2').datetimepicker({
            format: "yyyy",
            endDate: new Date(),
            minView: 4,
            autoclose: true,
            startView: 4,
            language: "{{ getLangCode() }}",
        });

        $(function() {
            $("input[name='service_type']").click(function() {
                // alert($("#paid").is(":checked"))
                if ($("#paid").is(":checked")) {
                    $("#dvCharge").show();
                    $("#charge_required").attr('required', true);
                } else {
                    $("#dvCharge").hide();
                    $("#charge_required").removeAttr('required', false);
                }
            });
        });

        var msg1 = "{{ trans('message.Alert') }}";
        var msg2 = "{{ trans('message.Please select customer!') }}";

        $('body').on('change', '.select_vhi', function() {

            var url = $(this).attr('cus_url');
            var cus_id = $(this).val();
            var modelnms = $(this).val();

            $.ajax({

                type: 'GET',
                url: url,
                data: {
                    cus_id: cus_id,
                    modelnms: modelnms
                },
                success: function(response) {

                    $('.modelnms').remove();
                    $('#vhi').append(response);
                }

            });
        });


        $('body').on('click', '#vhi', function() {

            var cus_id = $('.select_vhi').val();

            if (cus_id == "") {
                swal({
                    title: msg1,
                    text: msg2,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });

                return false;
            }
        });


        /*If vehicle add when customer is selected otherwise not add vehicle*/
        $('body').on('click', '.vehiclemodel', function() {

            var cus_id = $('.select_vhi').val();

            if (cus_id == "") {
                swal({
                    title: msg1,
                    text: msg2,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
                $('#vehiclemymodel').modal('toggle');
                return false;
            } else {
                $('#vehiclemymodel').show();
            }
        });


        $('body').on('change', '#vhi', function() {
            var vehi_id = $('.modelnms:selected').val();
            var url = '{{ url('service/getregistrationno') }}';
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    vehi_id: vehi_id
                },
                success: function(response) {
                    var res = $.trim(response);
                    if (res == "") {
                        $('#reg_no').val(res);
                        $('#reg_no').removeAttr('readonly');
                    } else {
                        $('#reg_no').val(res);
                        $('#reg_no').attr('readonly', true);
                    }
                }
            });
        });


        /*Fuel type*/
        $('.fueltypeadd').click(function() {

            var fuel_type = $('.fuel_type').val();
            var url = $(this).attr('fuelurl');
            var msg21 = "{{ trans('message.Please enter fuel type') }}";
            var msg22 = "{{ trans('message.Please enter only alphanumeric data') }}";
            var msg23 = "{{ trans('message.Only blank space not allowed') }}";
            var msg24 = "{{ trans('message.This Record is Duplicate') }}";
            var msg25 = "{{ trans('message.An error occurred :') }}";

            function define_variable() {
                return {
                    vehicle_fuel_value: $('.fuel_type').val(),
                    vehicle_fuel_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                    vehicle_fuel_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/
                };
            }

            var call_var_vehiclefueladd = define_variable();

            if (fuel_type == "") {
                swal({
                    title: msg21,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else if (!call_var_vehiclefueladd.vehicle_fuel_pattern.test(call_var_vehiclefueladd
                    .vehicle_fuel_value)) {
                $('.fuel_type').val("");
                swal({
                    title: msg22,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else if (!fuel_type.replace(/\s/g, '').length) {
                // var str = "    ";
                $('.fuel_type').val("");
                swal({
                    title: msg23,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });
            } else if (!call_var_vehiclefueladd.vehicle_fuel_pattern2.test(call_var_vehiclefueladd
                    .vehicle_fuel_value)) {
                $('.fuel_type').val("");
                swal({
                    title: msg34,
                    cancelButtonColor: '#C1C1C1',
                    buttons: {
                        cancel: msg35,
                    },
                    dangerMode: true,
                });

            } else {
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        fuel_type: fuel_type
                    },
                    success: function(data) {
                        var newd = $.trim(data);
                        var classname = 'del-' + newd;

                        if (newd == '01') {
                            swal({
                                title: msg24,
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            });
                        } else {
                            $('.fuel_type_class').append('<tr class=" row mx-1 ' + classname +
                                ' data_fuel_type_name"><td class="text-start w-50">' +
                                fuel_type +
                                '</td><td class="text-end w-50"><button type="button" fuelid=' +
                                data +
                                ' deletefuel="{!! url(' / vehicle / fueltypedelete ') !!}" class="btn btn-danger text-white border-0 fueldeletes"><i class="fa fa-trash" aria-hidden="true"></i></button></a></td><tr>'
                            );

                            $('.select_fueltype').append('<option selected value=' + data + '>' +
                                fuel_type + '</option>');

                            $('.fuel_type').val('');

                            swal({
                                title: msg5,
                                text: submitmsg,
                                icon: 'success',
                                cancelButtonColor: '#C1C1C1',
                                buttons: {
                                    cancel: msg35,
                                },
                                dangerMode: true,
                            }).then(() => {
                                $('#responsive-modal-fuel').modal('hide'); // Close the modal
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                $('body').css('padding-right', '');
                            });
                        }
                    },
                });
            }
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
        $('#cust_id').on('change', function() {

            var supplierValue = $('select[name=Customername]').val();

            if (supplierValue != null) {
                $('#cust_id-error').css({
                    "display": "none",
                    "padding" : "10px",
                });

                /*If select customer after customer id assigned to vehicle add form customer_id inputbox*/
                $('.hidden_customer_id').val(supplierValue);
            }

            if (supplierValue != null) {
                $(this).parent().parent().removeClass('has-error');
            }
        });


        /*Inside fix service text box only enter numbers data*/
        // $('.fixServiceCharge').on('keyup', function() {

        //     var valueIs = $(this).val();
         
        //     if (/\D/g.test(valueIs). length) {
        //         $(this).val("");
        //     } else if (valueIs == 0) {
        //         $(this).val("");
        //     }
        // });

        /*If firstly enter any whitespace then clear textbox*/
        $('body').on('keyup', '#firstname', function() {

            var firstname = $(this).val();

            if (!firstname.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#lastname', function() {

            var lastname = $(this).val();

            if (!lastname.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#displayname', function() {

            var displayname = $(this).val();

            if (!displayname.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#company_name', function() {

            var company_name = $(this).val();

            if (!company_name.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#mobile', function() {

            var mobile = $(this).val();

            if (!mobile.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#landlineno', function() {

            var landlineno = $(this).val();

            if (!landlineno.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#address', function() {

            var address = $(this).val();

            if (!address.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '.vehical_type', function() {

            var vehical_typeVal = $(this).val();

            if (!vehical_typeVal.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '.vehical_brand', function() {

            var vehical_brandVal = $(this).val();

            if (!vehical_brandVal.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '.fuel_type', function() {

            var fuel_typeVal = $(this).val();

            if (!fuel_typeVal.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '.vehi_modal_name', function() {

            var vehi_modal_nameVal = $(this).val();

            if (!vehi_modal_nameVal.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });


        $('body').on('keyup', '#chasicno1', function() {

            var chasicno1 = $(this).val();

            if (!chasicno1.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#gearno1', function() {

            var gearno1 = $(this).val();

            if (!gearno1.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#price1', function() {

            var price1 = $(this).val();

            if (!price1.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#odometerreading1', function() {

            var odometerreading1 = $(this).val();

            if (!odometerreading1.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#gearbox1', function() {

            var gearbox1 = $(this).val();

            if (!gearbox1.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#gearboxno1', function() {

            var vehi_modal_nameVal = $(this).val();

            if (!vehi_modal_nameVal.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#engineno1', function() {

            var engineno1 = $(this).val();

            if (!engineno1.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#enginesize1', function() {

            var enginesize1 = $(this).val();

            if (!enginesize1.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#engine1', function() {

            var engine1 = $(this).val();

            if (!engine1.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#keyno1', function() {

            var keyno1 = $(this).val();

            if (!keyno1.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });

        $('body').on('keyup', '#number_plate', function() {

            var number_plate = $(this).val();

            if (!number_plate.replace(/\s/g, '').length) {
                $(this).val("");
            }
        });
        /*Custom Field manually validation*/
        var msg31 = "{{ trans('message.field is required') }}";
        var msg32 = "{{ trans('message.Only blank space not allowed') }}";
        var msg33 = "{{ trans('message.Special symbols are not allowed.') }}";
        var msg34 = "{{ trans('message.At first position only alphabets are allowed.') }}";

        /*Form submit time check validation for Custom Fields */
        $('body').on('click', '.serviceSubmitButton', function(e) {
            $('#ServiceAdd-Form input, #ServiceAdd-Form select, #ServiceAdd-Form textarea').each(

                function(index) {
                    var input = $(this);
                    if ($('#cust_id').val() == "") {
                        $('.customerAddModel').css({
                            "margin-top": "20px"
                        });
                    }
                    if (input.attr('name') == "Customername" || input.attr('name') ==
                        "vehicalname" || input.attr(
                            'name') == "date" || input.attr('name') == "AssigneTo" || input.attr(
                            'name') == "repair_cat" ||
                        input.attr('name') == "service_type") {
                        if (input.val() == "") {
                            return false;
                        }
                    } else if (input.attr('isRequire') == 'required') {
                        var rowid = (input.attr('rows_id'));
                        var labelName = (input.attr('fieldnameis'));

                        if (input.attr('type') == 'textbox' || input.attr('type') == 'textarea') {
                            if (input.val() == '' || input.val() == null) {
                                $('.common_value_is_' + rowid).val("");
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
                                $('#common_error_span_' + rowid).css({
                                    "display": ""
                                });
                                $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                e.preventDefault();
                                return false;
                            } else if (!input.val().replace(/\s/g, '').length) {
                                $('.common_value_is_' + rowid).val("");
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg32);
                                $('#common_error_span_' + rowid).css({
                                    "display": ""
                                });
                                $('.error_customfield_main_div_' + rowid).addClass('has-error');
                                e.preventDefault();
                                return false;
                            } else if (!input.val().match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                                $('.common_value_is_' + rowid).val("");
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg33);
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
                                $('.error_customfield_main_div_' + ids).removeClass('has-error');
                            } else {
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
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
                                $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
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

            if ($("#washBay").is(':checked') == true) {
                if (washbay_value == "") {
                    //alert("is checked true : ");
                    $('#washBayCharge').addClass('has-error');
                    $('#washbay_error_span').text(washbay_trans + " " + msg31);
                    $('#washbay_error_span').css({
                        "display": true
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
                    $('#mot_error_span').text(mot_trans + " " + msg31);
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
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    } else if (valueIs.match(/^\s+/)) {
                        $('.common_value_is_' + rowid).val("");
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg34);
                        $('#common_error_span_' + rowid).css({
                            "display": ""
                        });
                        $('.error_customfield_main_div_' + rowid).addClass('has-error');
                    } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                        $('.common_value_is_' + rowid).val("");
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg33);
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
                        $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
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
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg34);
                            $('#common_error_span_' + rowid).css({
                                "display": ""
                            });
                            $('.error_customfield_main_div_' + rowid).addClass('has-error');
                        } else if (!valueIs.match(/^[(a-zA-Z0-9\s)\p{L}]+$/u)) {
                            $('.common_value_is_' + rowid).val("");
                            $('#common_error_span_' + rowid).text(labelName + " : " + msg33);
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
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
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
                    $('#common_error_span_' + rowid).text(labelName + " : " + msg31);
                    $('#common_error_span_' + rowid).css({
                        "display": ""
                    });
                    $('.error_customfield_main_div_' + rowid).addClass('has-error');
                }
            }
        });
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
                if (!washbayVal.match(/^(0|[1-9][0-9]*)$/). length) {
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
                $('#washbay_error_span').text(washbay_trans + " " + msg31);
                $('#washbay_error_span').css({
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
        // $('.addcolor').click(function() {
        //     var repair_category_name = $('.repair_category_name').val();
        //     var url = $(this).attr('colorurl');

        //     var msg55 = "{{ trans('message.Please enter repair category name') }}";

        //     function define_variable() {
        //         return {
        //             addcolor_value: $('.repair_category_name').val(),
        //             addcolor_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
        //             addcolor_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/
        //         };
        //     }

        //     var call_var_addcoloradd = define_variable();

        //     if (repair_category_name == "") {
        //         swal({
        //             title: msg55,
        //             cancelButtonColor: '#C1C1C1',
        //             buttons: {
        //                 cancel: msg35,
        //             },
        //             dangerMode: true,
        //         });
        //         // } else if (!call_var_addcoloradd.addcolor_pattern.test(call_var_addcoloradd
        //         //         .addcolor_value)) {
        //         //     $('.repair_category_name').val("");
        //         //     swal({
        //         //         title: msg51,
        //         //         cancelButtonColor: '#C1C1C1',
        //         //         buttons: {
        //         //             cancel: msg35,
        //         //         },
        //         //         dangerMode: true,
        //         //     });
        //     } else if (!repair_category_name.replace(/\s/g, '').length) {
        //         $('.repair_category_name').val("");
        //         swal({
        //             title: msg21,
        //             cancelButtonColor: '#C1C1C1',
        //             buttons: {
        //                 cancel: msg35,
        //             },
        //             dangerMode: true,
        //         });
        //         // } else if (!call_var_addcoloradd.addcolor_pattern2.test(call_var_addcoloradd
        //         //         .addcolor_value)) {
        //         //     $('.repair_category_name').val("");
        //         //     swal({
        //         //         title: msg34,
        //         //         cancelButtonColor: '#C1C1C1',
        //         //         buttons: {
        //         //             cancel: msg35,
        //         //         },
        //         //         dangerMode: true,
        //         //     });
        //     } else {
        //         $.ajax({
        //             type: 'GET',
        //             url: url,
        //             data: {
        //                 repair_category_name: repair_category_name
        //             },

        //             //Form submit at a time only one for addColorModel
        //             beforeSend: function() {
        //                 $(".addcolor").prop('disabled', true);
        //             },

        //             success: function(data) {
        //                 var newd = $.trim(data);
        //                 var classname = 'del-' + newd;
        //                 if (data == '01') {
        //                     swal({
        //                         title: msg53,
        //                         cancelButtonColor: '#C1C1C1',
        //                         buttons: {
        //                             cancel: msg35,
        //                         },
        //                         dangerMode: true,
        //                     });
        //                 } else {
        //                     $('.colornametype').append('<tr class=" row mx-1 ' + classname +
        //                         ' data_color_name"><td class="text-start">' +
        //                         repair_category_name +
        //                         '</td><td class="text-end"><button type="button" id=' +
        //                         data +
        //                         'deletecolor="{!! url('deleteRepairCategory') !!}" class="btn btn-danger text-white border-0 deletecolors"><i class="fa fa-trash" aria-hidden="true"></i></button></a></td><tr>'
        //                     );

        //                     $('.repair_category').append('<option selected value=' + data + '>' +
        //                         repair_category_name + '</option>');

        //                     $('.repair_category_name').val('');

        //                     swal({
        //                         title: msg5,
        //                         text: submitmsg,
        //                         icon: 'success',
        //                         cancelButtonColor: '#C1C1C1',
        //                         buttons: {
        //                             cancel: msg35,
        //                         },
        //                         dangerMode: true,
        //                     }).then(() => {
        //                         $('#responsive-modal-color').modal('hide'); // Close the modal
        //                     });
        //                 }

        //                 //Form submit at a time only one for addColorModel
        //                 $(".addcolor").prop('disabled', false);
        //                 return false;
        //             },
        //             error: function(e) {
        //                 alert(mag20 + ' ' + e.responseText);
        //                 console.log(e);
        //             }
        //         });
        //     }
        // });
        /*in commented code-delete repair category not work after adding repair category*/ 
        /*color add  model*/ 
        $('.addcolor').click(function () {
            var repair_category_name = $('.repair_category_name').val();
            var url = $(this).attr('colorurl');
            var msg55 = "{{ trans('message.Please enter repair category name') }}";

            function define_variable() {
                return {
                    addcolor_value: $('.repair_category_name').val(),
                    addcolor_pattern: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]+$/,
                    addcolor_pattern2: /^[a-zA-Z0-9\u0621-\u064A\u00C0-\u017F\u0600-\u06FF\u0750-\u077F\uFB50-\uFDFF\uFE70-\uFEFF\u2E80-\u2FD5\u3190-\u319f\u3400-\u4DBF\u4E00-\u9FCC\uF900-\uFAAD\u0900-\u097F\s]*$/,
                };
            }

            var call_var_addcoloradd = define_variable();

            if (repair_category_name === "") {
                swal({
                    title: msg55,
                    cancelButtonColor: '#C1C1C1',
                    buttons: { cancel: msg35 },
                    dangerMode: true,
                });
            } else if (!repair_category_name.replace(/\s/g, '').length) {
                $('.repair_category_name').val('');
                swal({
                    title: msg21,
                    cancelButtonColor: '#C1C1C1',
                    buttons: { cancel: msg35 },
                    dangerMode: true,
                });
            } else {
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: { repair_category_name: repair_category_name },

                    beforeSend: function () {
                        $(".addcolor").prop('disabled', true);
                    },

                    success: function (data) {
                        var newd = $.trim(data);
                        var classname = 'del-' + newd;

                        if (data === '01') {
                            swal({
                                title: msg53,
                                cancelButtonColor: '#C1C1C1',
                                buttons: { cancel: msg35 },
                                dangerMode: true,
                            });
                        } else {
                            $('.colornametype').append('<tr class="row mx-1 ' + classname + ' data_color_name">' +
                                '<td class="text-start">' + repair_category_name + '</td>' +
                                '<td class="text-end">' +
                                '<button type="button" id="' + data + '" deletecolor="{!! url('deleteRepairCategory') !!}" class="btn btn-danger text-white border-0 deletecolors">' +
                                '<i class="fa fa-trash" aria-hidden="true"></i></button>' +
                                '</td></tr>');

                            $('.repair_category').append('<option selected value="' + data + '">' + repair_category_name + '</option>');

                            $('.repair_category_name').val('');

                            swal({
                                title: msg5,
                                text: submitmsg,
                                icon: 'success',
                                cancelButtonColor: '#C1C1C1',
                                buttons: { cancel: msg35 },
                                dangerMode: true,
                            }).then(() => {
                                $('#responsive-modal-color').modal('hide'); // Close the modal
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                $('body').css('padding-right', '');
                            });
                        }

                        $(".addcolor").prop('disabled', false);
                        return false;
                    },
                    error: function (e) {
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
        var rcategorydelete = "{{ trans('message.Repair Category Deleted Successfully') }}";
        /*color Delete model*/
        $('body').on('click', '.deletecolors', function () {
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
                        data: { colorid: colorid },
                        success: function () {
                            $('.del-' + colorid).remove(); // Remove the row from the table
                            $(".repair_category option[value='" + colorid + "']").remove(); // Remove the option from the select dropdown

                            swal({
                                title: msg105,
                                text: rcategorydelete,
                                icon: 'success',
                                cancelButtonColor: '#C1C1C1',
                                buttons: { cancel: msg35 },
                                dangerMode: true,
                            });
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                } else {
                    swal({
                        title: msg107,
                        text: msg108,
                        icon: 'error',
                        cancelButtonColor: '#C1C1C1',
                        buttons: { cancel: msg35 },
                        dangerMode: true,
                    });
                }
            });
        });


        /*For image preview at selected image*/
        function readUrl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#image").change(function() {
            readUrl(this);
            $("#imagePreview").css("display", "block");
        });
        Array.from(document.getElementsByClassName('showmodal')).forEach((e) => {
            e.addEventListener('click', function(element) {
                element.preventDefault();
                if (e.hasAttribute('data-show-modal')) {
                    showModal(e.getAttribute('data-show-modal'));
                }
            });
        });

        // Show modal dialog
        function showModal(modal) {
            const mid = document.getElementById(modal);
            let myModal = new bootstrap.Modal(mid);
            myModal.show();
        }
    });

    $('.select_vehicle').on('change', function() {
        // console.log("change vehicle");
        var vehi_id = $(this).val(); // Get the value of the selected option
        var customer_id = $(".select_vhi").val();
        // console.log(customer_id);
        var url = '{{ url('service/free_service') }}';
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                vehi_id: vehi_id,
                customer_id: customer_id,
            },
            beforeSend: function() {
                console.log("beforeSend");
            },

            success: function(data) {
                console.log("success");

                if (data.length === 0) {
                    // If the array is empty, hide the label and button
                    $('.free_service').hide();
                } else {
                    // If the array is not empty, show the label and button
                    $('.free_service').show();
                }
            },

            error: function(e) {
                console.log(e);
            }
        });

    });

    $(document).ready(function() {
        $('.modal').on('show.bs.modal', function() {
            $('.modal').removeClass('blur-modal');
            // Add blur class to all modals except the currently shown modal
            $('.modal').not(this).addClass('blur-modal');
        });

        $('.modal').on('hidden.bs.modal', function() {
            // Remove blur class from all modals
            $('.modal').removeClass('blur-modal');
        });
        //For customer vehicle dropdown
        handleCustomerChange();

        //For free service radio button
        serviceType();

        function serviceType() {
            var urlParams = new URLSearchParams(window.location.search);
            var vehi_id = urlParams.get('v_id');
            var customer_id = urlParams.get('c_id');
            var url = '{{ url('service/free_service') }}';

            console.log(vehi_id);
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    vehi_id: vehi_id,
                    customer_id: customer_id,
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.length === 0) {
                        $('.free_service').hide();
                    } else {
                        $('.free_service').show();
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }

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
                if (!motVal.match(/^(0|[1-9][0-9]*)$/). length) {
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

    });

    function handleCustomerChange() {
        var url = $('.select_vhi').attr('cus_url');
        var cus_id = $('.select_vhi').val();
        var modelnms = $('.select_vhi').val();

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                cus_id: cus_id,
                modelnms: modelnms
            },
            success: function(response) {
                $('.modelnms').remove();
                $('#vhi').append(response);
            }
        });
    }
</script>

<!-- Form field validation -->
{!! JsValidator::formRequest('App\Http\Requests\ServiceAddEditFormRequest', '#ServiceAdd-Form') !!}
<script nonce="{{ $cspNonce }}" type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>

@endsection