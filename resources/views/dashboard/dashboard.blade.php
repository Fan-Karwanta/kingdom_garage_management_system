@extends('layouts.app')
@section('content')
{{-- <script nonce="{{ $cspNonce }}" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> --}}
<style>
    ul.top_profiles {
        height: auto !important;
    }

    @media (max-width: 533px) {
        .fc .fc-toolbar.fc-header-toolbar {
            display: block;
            text-align: center;
        }

        .fc-header-toolbar .fc-toolbar-chunk {
            display: block;
        }
    }

    @media (min-width: 320px) and (max-width: 370px) {
        .fc-scrollgrid-sync-inner {
            text-align: left !important;
        }
    }
</style>

<!-- For Service Chart -->
<!-- <link rel="stylesheet" href="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.css"> -->
 <link rel="stylesheet" href="{{ URL::asset('vendors/chart/Chart.min.css') }}">

<!-- For Dashborad Page all parts to make proper border for this css  -->
<link type="text/css" href="{{ URL::asset('public/css/dashboard_page_all_part_styles.css') }}" rel="stylesheet">

<!-- CSS For Chart -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/js/49/css/tooltip.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/js/49/css/util.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendors/fullcalendar/lib/main.css') }}">

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('build/js/jscharts.js') }}" defer="defer"></script>

<div class="right_col position-relative " role="main" id="dashboard">
    <!--  Free service view -->
    <div id="myModal-open-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg modal-xs">
            <!-- Modal content-->
            <div class="modal-content">
                <!-- <div class="modal-header">
               
                    <p id="myLargeModalLabel" class="modal-title h5 overflow-visible">
                        {{ trans('message.Free Service Details') }}

                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div> -->
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!--  Paid service view -->
    <div id="myModal-com-service" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg modal-xs">
            <!-- Modal content-->
            <div class="modal-content">
                <!-- <div class="modal-header">
                    <h4 id="myLargeModalLabel" class="modal-title overflow-visible">
                        {{ trans('message.Paid Service Details') }}
                    </h4>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div> -->
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!--  Repeat Job Service view -->
    <div id="myModal-serviceup" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg modal-xs">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="myLargeModalLabel" class="modal-title overflow-visible">
                        {{ trans('message.Repeat Job Service Details') }}
                    </h4>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!--  Free service customer view -->
    <div id="myModal-customer-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg modal-xs">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup"> {{ trans('message.Dashboard') }} :@if (Auth::user()->role == 'Customer') {{trans('message.Customer')}} @elseif (Auth::user()->role == 'supportstaff') {{trans('message.Supportstaff')}} @elseif (Auth::user()->role == 'accountant') {{trans('message.Accountant')}} @elseif (Auth::user()->role == 'employee') {{trans('message.Employee')}} @else {{trans('message.Admin')}} @endif </span>
                    </div>

                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>

    <div class="x_panel mb-0">
        <!-- For Garage wizard steps start -->
        @if (getUsersRole(Auth::user()->role_id) != 'Customer' and getUsersRole(Auth::user()->role_id) != 'Employee')
        <div class="row mainRowDiv" isHide="1" isArabic="<?php if (getValue() == 'rtl') {
                                                                echo 'rtl';
                                                            } else {
                                                                echo 'ltr';
                                                            } ?>">
            @if (getValue() == 'rtl')
            <div class="shadow p-3 pb-0 border" id="setup_wizard" style="width: 99%;margin-left: 10px;">
                <div class="row">
                    <div class="col">
                        <span class="titleup mt-2">{{ trans('message.Setup Wizard') }} </span>
                    </div>

                    @if ($setting == 0 ||
                    $Customer == 0 ||
                    $employee == 0 ||
                    $Supplier == 0 ||
                    $have_vehicle == 0 ||
                    $have_product == 0 ||
                    $have_observationCount == 0 ||
                    $service == 0 ||
                    $have_purchase == 0)
                    <div class="col text-end" style="color: #818386;">
                        <span class="arrow-toggle">
                            <i class="fas fa-chevron-down arrow-down m-2" style="display: none;"></i>
                            <i class="fas fa-chevron-up arrow-up m-2"></i>
                        </span>
                        <i class="fas fa-times fa-lg close-icon m-2" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="step-group">
                    @else
                    <div class="col text-end" style="color: #818386;">
                        <span class="arrow-toggle">
                            <i class="fas fa-chevron-down arrow-down m-2"></i>
                            <i class="fas fa-chevron-up arrow-up m-2" style="display: none;"></i>
                        </span>
                        <i class="fas fa-times fa-lg close-icon m-2" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="step-group" style="display: none;">
                    @endif
                    <hr>
                    <div class="wizard_main">

                        <div class="steps clearfix">
                            <ul role="tablist">
                                <li role="tab" class="first last_child wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                    @if ($setting != 0)
                                    @if (getActiveAdmin(Auth::User()->id) == 'yes')
                                    <a href="{{ url('/setting/general_setting/list') }}" target="">
                                        @else
                                        @if (Gate::allows('generalsetting_view'))
                                        @can('generalsetting_view')
                                        <a href="{{ url('/setting/general_setting/list') }}" target="">
                                            @endcan
                                            @else
                                            @can('timezone_view')
                                            <a href="{{ url('/setting/timezone/list') }}" target="">
                                                @endcan
                                                @endif
                                                @endif <span class="current-info audible"> </span>
                                                <div class="title wizard-title">
                                                    <span class="step-icon greenCircle">
                                                        <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/setting.png') }}" alt="Avatar" class="main-image">
                                                        <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                                    </span>
                                                    <span class="name-text_green">{{ trans('message.Settings') }}</span>
                                                </div>
                                            </a>
                                            @else
                                            <a href="setting/general_setting/list" target="">
                                                <span class="current-info audible"> </span>
                                                <div class="title wizard-title">
                                                    <span class="step-icon blueCircle">
                                                        <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/setting.png') }}" alt="Avatar" class="center wizard_setting rounded-circle blueCircle">
                                                        <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                                    </span>
                                                    <span class="name-text_blue">{{ trans('message.Settings') }}</span>
                                                </div>
                                            </a>
                                            @endif
                                </li>
                                <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                    @if ($Customer != 0)
                                    <a href="customer/list" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon greenCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/users.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_green">{{ trans('message.Customers') }}</span>
                                        </div>
                                    </a>
                                    @else
                                    <a href="customer/add" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon blueCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/users.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_blue">{{ trans('message.Customers') }}</span>
                                        </div>
                                    </a>
                                    @endif
                                </li>
                                <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">

                                    @if ($employee != 0)
                                    <a href="employee/list" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon greenCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/employee.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_green">{{ trans('message.Employees') }}</span>
                                        </div>
                                    </a>
                                    @else
                                    <a href="employee/add" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon blueCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/employee.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_blue">{{ trans('message.Employees') }}</span>
                                        </div>
                                    </a>
                                    @endif
                                </li>
                                <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">

                                    @if ($Supplier != 0)
                                    <a href="supplier/list" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon greenCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/supplier.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_green">{{ trans('message.Suppliers') }}</span>
                                        </div>
                                    </a>
                                    @else
                                    <a href="supplier/add" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon blueCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/supplier.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_blue">{{ trans('message.Suppliers') }}</span>
                                        </div>
                                    </a>
                                    @endif
                                </li>
                                <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                    @if ($have_vehicle != 0)
                                    <a href="vehicle/list" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon greenCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/vehicle.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_green">{{ trans('message.Vehicles') }}</span>
                                        </div>
                                    </a>
                                    @else
                                    <a href="vehicle/add" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon blueCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/vehicle.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_blue">{{ trans('message.Vehicles') }}</span>
                                        </div>
                                    </a>
                                    @endif
                                </li>
                                <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                    @if ($have_product != 0)
                                    <a href="product/list" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon greenCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/product.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_green">{{ trans('message.Products') }}</span>
                                        </div>
                                    </a>
                                    @else
                                    <a href="product/add" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon blueCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/product.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_blue">{{ trans('message.Products') }}</span>
                                        </div>
                                    </a>
                                    @endif
                                </li>
                                <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                    @if ($have_purchase != 0)
                                    <a href="purchase/list" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon greenCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/purchase.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_green">{{ trans('message.Purchase') }}</span>
                                        </div>
                                    </a>
                                    @else
                                    <a href="purchase/add" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon blueCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/purchase.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_blue">{{ trans('message.Purchase') }}</span>
                                        </div>
                                    </a>
                                    @endif
                                </li>
                                <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                    @if ($have_observationCount != 0)
                                    <a href="observation/list" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon greenCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/observation.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_green obj">{{ trans('message.Observation Library') }}</span>
                                        </div>
                                    </a>
                                    @else
                                    <a href="observation/add" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon blueCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/observation.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_blue obj">{{ trans('message.Observation Library') }}</span>
                                        </div>
                                    </a>
                                    @endif
                                </li>
                                <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                    @if ($service != 0)
                                    <a href="jobcard/list" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon greenCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/service.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_green">{{ trans('message.Job Card') }}</span>
                                        </div>
                                    </a>
                                    @else
                                    <a href="service/add" target="">
                                        <span class="current-info audible"> </span>
                                        <div class="title wizard-title">
                                            <span class="step-icon blueCircle">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/service.png') }}" alt="Avatar" class="main-image">
                                                <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                            </span>
                                            <span class="name-text_blue">{{ trans('message.Job Card') }}</span>
                                        </div>
                                    </a>
                                    @endif
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                @else
                <div class="shadow p-3 pb-0 border" id="setup_wizard" style="width: 98%;margin-left: 10px;">
                    <div class="row">
                        <div class="col d-flex">
                            <span class="titleup mt-1" style="font-size: 15px;">{{ trans('message.Setup Wizard') }} </span>
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 p-0 mx-2" style="position:relative">
                              <button class=" btn btn-success" id="add-sample-data-btn" style="display: {{ $sampleDataExists ? 'none' : 'block' }};border-radius:30px">{{ trans('message.Add Sample Data') }}</button>
                            </div>
                        </div>
                        @if ($setting == 0 ||
                        $Customer == 0 ||
                        $employee == 0 ||
                        $Supplier == 0 ||
                        $have_vehicle == 0 ||
                        $have_product == 0 ||
                        $have_observationCount == 0 ||
                        $service == 0 ||
                        $have_purchase == 0)
                        <div class="col text-end" style="color: #818386;">
                            <span class="arrow-toggle">
                                <i class="fas fa-chevron-down arrow-down m-2" style="display: none;"></i>
                                <i class="fas fa-chevron-up arrow-up m-2"></i>
                            </span>
                            <i class="fas fa-times fa-lg close-icon m-2" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="step-group">
                        @else
                        <div class="col text-end" style="color: #818386;">
                            <span class="arrow-toggle">
                                <i class="fas fa-chevron-down arrow-down m-2"></i>
                                <i class="fas fa-chevron-up arrow-up m-2" style="display: none;"></i>
                            </span>
                            <i class="fas fa-times fa-lg close-icon m-2" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="step-group" style="display: none;">
                        @endif
                        <hr>
                        <div class="wizard_main">

                            <div class="steps clearfix">
                                <ul role="tablist">
                                    <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                        @if ($setting != 0)
                                        @if (getActiveAdmin(Auth::User()->id) == 'yes')
                                        <a href="{{ url('/setting/general_setting/list') }}" target="">
                                            @else
                                            @if (Gate::allows('generalsetting_view'))
                                            @can('generalsetting_view')
                                            <a href="{{ url('/setting/general_setting/list') }}" target="">
                                                @endcan
                                                @else
                                                @can('timezone_view')
                                                <a href="{{ url('/setting/timezone/list') }}" target="">
                                                    @endcan
                                                    @endif
                                                    @endif <span class="current-info audible"> </span>
                                                    <div class="title wizard-title">
                                                        <span class="step-icon greenCircle">
                                                            <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/setting.png') }}" alt="Avatar" class="main-image">
                                                            <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                                        </span>
                                                        <span class="name-text_green">{{ trans('message.Settings') }}</span>
                                                    </div>
                                                </a>
                                                @else
                                                <a href="setting/general_setting/list" target="">
                                                    <span class="current-info audible"> </span>
                                                    <div class="title wizard-title">
                                                        <span class="step-icon blueCircle">
                                                            <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/setting.png') }}" alt="Avatar" class="center wizard_setting rounded-circle blueCircle">
                                                            <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                                        </span>
                                                        <span class="name-text_blue">{{ trans('message.Settings') }}</span>
                                                    </div>
                                                </a>
                                                @endif
                                    </li>
                                    <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                        @if ($Customer != 0)
                                        <a href="customer/list" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon greenCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/users.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_green">{{ trans('message.Customers') }}</span>
                                            </div>
                                        </a>
                                        @else
                                        <a href="customer/add" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon blueCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/users.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_blue">{{ trans('message.Customers') }}</span>
                                            </div>
                                        </a>
                                        @endif
                                    </li>
                                    <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">

                                        @if ($employee != 0)
                                        <a href="employee/list" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon greenCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/employee.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_green">{{ trans('message.Employees') }}</span>
                                            </div>
                                        </a>
                                        @else
                                        <a href="employee/add" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon blueCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/employee.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_blue">{{ trans('message.Employees') }}</span>
                                            </div>
                                        </a>
                                        @endif
                                    </li>
                                    <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">

                                        @if ($Supplier != 0)
                                        <a href="supplier/list" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon greenCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/supplier.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_green">{{ trans('message.Suppliers') }}</span>
                                            </div>
                                        </a>
                                        @else
                                        <a href="supplier/add" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon blueCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/supplier.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_blue">{{ trans('message.Suppliers') }}</span>
                                            </div>
                                        </a>
                                        @endif
                                    </li>
                                    <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                        @if ($have_vehicle != 0)
                                        <a href="vehicle/list" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon greenCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/vehicle.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_green">{{ trans('message.Vehicles') }}</span>
                                            </div>
                                        </a>
                                        @else
                                        <a href="vehicle/add" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon blueCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/vehicle.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_blue">{{ trans('message.Vehicles') }}</span>
                                            </div>
                                        </a>
                                        @endif
                                    </li>
                                    <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                        @if ($have_product != 0)
                                        <a href="product/list" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon greenCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/product.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_green">{{ trans('message.Products') }}</span>
                                            </div>
                                        </a>
                                        @else
                                        <a href="product/add" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon blueCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/product.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_blue">{{ trans('message.Products') }}</span>
                                            </div>
                                        </a>
                                        @endif
                                    </li>
                                    <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                        @if ($have_purchase != 0)
                                        <a href="purchase/list" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon greenCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/purchase.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_green">{{ trans('message.Purchase') }}</span>
                                            </div>
                                        </a>
                                        @else
                                        <a href="purchase/add" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon blueCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/purchase.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_blue">{{ trans('message.Purchase') }}</span>
                                            </div>
                                        </a>
                                        @endif
                                    </li>
                                    <li role="tab" class="first wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                        @if ($have_observationCount != 0)
                                        <a href="observation/list" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon greenCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/observation.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_green obj">{{ trans('message.Observation Library') }}</span>
                                            </div>
                                        </a>
                                        @else
                                        <a href="observation/add" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon blueCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/observation.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_blue obj">{{ trans('message.Observation Library') }}</span>
                                            </div>
                                        </a>
                                        @endif
                                    </li>
                                    <li role="tab" class="first last_child wizard_responsive disabled done" aria-disabled="false" aria-selected="true">
                                        @if ($service != 0)
                                        <a href="jobcard/list" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon greenCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/service.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/Check.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_green">{{ trans('message.Job Card') }}</span>
                                            </div>
                                        </a>
                                        @else
                                        <a href="service/add" target="">
                                            <span class="current-info audible"> </span>
                                            <div class="title wizard-title">
                                                <span class="step-icon blueCircle">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/service.png') }}" alt="Avatar" class="main-image">
                                                    <img src="{{ URL::asset('public/img/dashboard/wizard_setup_image/3-dot.png') }}" alt="Avatar" class="status_image">
                                                </span>
                                                <span class="name-text_blue">{{ trans('message.Job Card') }}</span>
                                            </div>
                                        </a>
                                        @endif
                                    </li>
                                    @endif
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <br />
            @endif

            <!-- Active(login) in show admin , supportstaff,accountant -->
            @if (getUsersRole(Auth::user()->role_id) == 'Super Admin' ||
            getUsersRole(Auth::user()->role_id) == 'Support Staff' ||
            getUsersRole(Auth::user()->role_id) == 'Accountant' ||
            getUsersRole(Auth::user()->role_id) == 'Branch Admin')
            @can('dashboard_view')

            <div class="row rtlrow" style="margin-top: -8px;">
                <div class="col-md-6">
                    <div class="boxes">
                        <div class="row mb-1">
                            <div class="col-md-4">
                                <a href="{{ URL::to('/employee/list')}}" target="">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/employee.png') }}" width="40px" height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                    @if (isset($employee))
                                                    <?php echo $employee; ?>
                                                    @else
                                                    <?php echo '0'; ?>
                                                    @endif
                                                </p><br>
                                                <span class="info-box-title">{{ trans('message.EMPLOYEES') }}</span>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{URL::to('/customer/list')}}" target="">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/customer.png') }}" width="40px" height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">

                                                    @if (isset($Customer))
                                                    <?php echo $Customer; ?>
                                                    @else
                                                    <?php echo '0'; ?>
                                                    @endif
                                                </p></br>
                                                <span class="info-box-title">{{ trans('message.CUSTOMERS') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{URL::to('/supplier/list')}}" target="">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/supplier.png') }}" width="40px" height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                    @if (isset($Supplier))
                                                    <?php echo $Supplier; ?>
                                                    @else
                                                    <?php echo '0'; ?>
                                                    @endif
                                                </p></br>

                                                <span class="info-box-title">{{ trans('message.SUPPLIERS') }} </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <a href="{{URL::to('/product/list')}}" target="">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/product.png') }}" width="40px" height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                    @if ($product)
                                                    <?php echo $product; ?>
                                                    @else
                                                    <?php echo '0'; ?>
                                                    @endif
                                                </p></br>
                                                <span class="info-box-title">{{ trans('message.PRODUCTS') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <!-- <a href="sales/list" target="blank"> -->
                                <a href="{{URL::to('/vehicle/list')}}" target="">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/sales.png') }}" width="40px" height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                    @if ($sales)
                                                    <?php echo $sales; ?>
                                                    @else
                                                    <?php echo '0'; ?>
                                                    @endif
                                                </p></br>

                                                <span class="info-box-title"> {{ trans('message.VEHICLE SELL') }}</span>
                                            </div>

                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{URL::to('/service/list')}}" target="">
                                    <div class="panel info-box panel-white">
                                        <div class="panel-body member shadow">
                                            <img src="{{ URL::asset('public/img/dashboard/service.png') }}" width="40px" height="40px" class="dashboard_background" alt="">
                                            <div class="info-box-stats">
                                                <p class="counter">
                                                    @if ($service)
                                                    <?php echo $service; ?>
                                                    @else
                                                    <?php echo '0'; ?>
                                                    @endif
                                                </p></br>

                                                <span class="info-box-title">{{ trans('message.SERVICES') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <div class="x_panel dashboard_x_panel shadow">
                        <div class="x_title row">
                            <div class="col-10">
                                <p class="fw-500 overflow-visible h5">
                                    {{ trans('message.Services') }}
                                </p>
                            </div>
                            <div class="col-2">
                                <ul class="nav navbar-right margin-left-10">
                                    <li>
                                        <form method="get" action="service/list">
                                            <input type="hidden" name="free" value="<?php echo 'free'; ?>" />
                                            <button type="submit" class="btn  btn-default1 border-0"><img src="{{ URL::asset('public/img/dashboard/view.png') }}" style="width: 18px; height: 18px;"></button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="service-chart">
                                <div style="height: 289px; width: 240px;">
                                    <canvas id="chartJSContainer" width="242" height="197" class="serviceChart"></canvas>
                                </div>
                                <div class="servicecount">
                                    <h3 class="text-center">
                                        {{ $totalService }}
                                    </h3>
                                    <h6>
                                        {{ trans('message.Total Services') }}
                                    </h6>
                                </div>
                                <div class="text-center freeservicecount">
                                    <p class="square" style="background-color:#FF9054;"></p>
                                    <h3 class="me-14">{{ $freeService }}</h3>
                                    <h6>{{ trans('message.Free Services') }}</h6>
                                </div>
                                <div class="text-center paidservicecount">
                                    <p class="square" style="background-color:#44CB7F;"></p>
                                    <h3 class="me-14">{{ $paidService }}</h3>
                                    <h6>{{ trans('message.Paid Services') }}</h6>
                                </div>
                                <input type="hidden" id="freeServices" value="{{ $freeService }}">
                                <input type="hidden" id="paidServices" value="{{ $paidService }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @endif
            <!-- end Active(login) in show admin , supportstaff,accountant -->


            <!-- Active(login) in show customer , employee -->
            @if (getUsersRole(Auth::user()->role_id) == 'Customer' || getUsersRole(Auth::user()->role_id) == 'Employee')
            @can('dashboard_view')
            <div class="row">
                <!-- Opening Hours -->
                <div class="col-md-4 col-xs-12 col-sm-12">
                    <div class="x_panel dashboard_x_panel shadow pb-1">
                        <div class="x_title">
                            <p class="w-500 overflow-visible h5">{{ trans('message.Opening Hours') }}</p>
                            <div class="clearfix"></div>
                        </div>
                        <div class="table-responsive pb-3">
                            <table class="table table-borderless text-nowrap">

                                @if (count($openinghours) !== 0)
                                @foreach ($openinghours as $openinghourss)
                                <tr class="bessuhours" id="bessuhours">

                                    <td width="30%" class="fw-bold">{{ trans('message.' . getDayName($openinghourss->day)) }}</td>

                                    @if ($openinghourss->from == $openinghourss->to)
                                    <td class="dayhours text-end">- - - - - {{ trans('message.Day off') }} - - - - -
                                    </td>
                                    @else
                                    <td width="70%" class="text-end">
                                        <span class="dayhours">{{ getOpenHours($openinghourss->from) }}</span>
                                        <span class="dayhours">{{ trans('message.To') }}</span>
                                        <span class="dayhours">{{ getCloseHours($openinghourss->to) }}</span>
                                    </td>
                                    @endif

                                </tr>
                                @endforeach
                                @else
                                <p style="text-align: center;">{{ trans('message.Data not available') }}</p>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Calendar Events -->
                <div class="col-md-8 col-xs-12 col-sm-12">
                    <div class="x_panel dashboard_x_panel shadow pb-0">
                        <div class="row x_title m-0 p-0">
                            <div class="col-6 fw-500 overflow-visible h5">{{ trans('message.Calendar') }}</div>
                            <div class="col-6 text-end"><span class="service-indic service-open-indic"></span>{{ trans('message.Open') }}<span class="service-indic service-complete-indic ms-3"></span>{{ trans('message.Completed') }}</div>
                        </div>
                        <div class="x_content">

                            <div id="calendar"></div>

                        </div>
                    </div>
                </div>

            </div>
            <!-- free service -->
            @can('dashboard_owndata')
            <div class="row">
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <div class="x_panel dashboard_x_panel shadow">
                        <div class="x_title row">
                            <div class="col-10">
                                <p class="fw-500 overflow-visible h5">
                                    {{ trans('message.Free Service Details') }}
                                </p>
                            </div>
                            <div class="col-2">
                                <ul class="nav navbar-right margin-left-10">
                                    <li>
                                        <form method="get" action="jobcard/list">
                                            <input type="hidden" name="free" value="<?php echo 'free'; ?>" />
                                            <button type="submit" class="btn  btn-default1 border-0"><img src="{{ URL::asset('public/img/dashboard/view.png') }}" style="width: 18px; height: 18px;"></button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php $userid = Auth::User()->id; ?>
                        <ul class="list-unstyled top_profiles scroll-view ps-2">
                            @if (count($sale) != 0)
                            <?php $colors = array('date-color1', 'date-color2', 'date-color3', 'date-color4', 'date-color5'); // Define an array of colors
                            $index = 0; ?>
                            @foreach ($sale as $saless)
                            <?php
                            $class = 'float-start date ' . $colors[$index % count($colors)]; // Get the color class based on the current index
                            $index++; ?>
                            <div class="x_content">
                                <?php
                                $date = $saless->service_date;
                                $month = date('M', strtotime($date));
                                $day = date('d', strtotime($date));

                                ?>
                                <article class="media event">
                                    <?php echo '<a class="' . $class . '">'; ?>
                                    <p class="month"><?php echo $month; ?></p>
                                    <p class="day"><?php echo $day; ?></p>
                                    </a>
                                    <?php $view_data = getInvoiceStatus($saless->job_no); ?>
                                    @if ($view_data == 'Yes')
                                    <a href="{!! url('/jobcard/list/' . $saless->id) !!}">
                                        @else
                                        <a href="" data-bs-toggle="modal" open_id="{{ $saless->id }}" job_no="{{ $saless->job_no }}" url="{!! url('/jobcard/modalview') !!}" data-bs-target="#myModal-open-modal" print="20" class="openmodel">
                                            @endif
                                            <div class="media-body pt-1">
                                                <?php $dateservicefree = date('Y-m-d', strtotime($saless->service_date)); ?>
                                                <span class="jobdetails">{{ $saless->job_no }}
                                                    <i>&nbsp;&nbsp;{{ date(getDateFormat(), strtotime($dateservicefree)) }}</i> </span></br>
                                                <span><img src="{{ url('public/employee/' . getCustomerImage($saless->assign_to) ) }}" width="25px" class="rounded-circle"> {{ getAssignedName($saless->assign_to) }} <a data-toggle="tooltip" data-placement="bottom" title="Employee Name" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a></span>
                                            </div>
                                            @if ($view_data == 'Yes')
                                        </a>
                                        @else
                                        @endif
                                    </a>
                                </article>
                            </div>
                            @endforeach
                            @else
                            @can('service_add')
                            <p style="text-align: center;">
                                <a id="" href="{!! url('/service/add') !!}">
                                    <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="mb-2"> {{ trans('message.Add Services') }}
                                </a>
                            </p>
                            @else
                            <p style="text-align: center;">{{ trans('message.No data available.') }}</p>
                            @endcan
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- paid service -->
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <div class="x_panel dashboard_x_panel shadow">
                        <div class="x_title">
                            <div class="row">
                                <div class="col-10">
                                    <p class="fw-500 overflow-visible h5">
                                        {{ trans('message.Paid Service Details') }}
                                    </p>
                                </div>
                                <div class="col-2">
                                    <ul class="nav navbar-right margin-left-10">
                                        <form method="get" action="jobcard/list">
                                            <input type="hidden" name="paid" value="<?php echo 'paid'; ?>" />
                                            <button type="submit" class="btn  btn-default1 border-0"><img src="{{ URL::asset('public/img/dashboard/view.png') }}" style="width: 18px; height: 18px;">
                                            </button>
                                        </form>
                                    </ul>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                        @if (count($sale1) != 0)
                        <?php $colors = array('date-color1', 'date-color2', 'date-color3', 'date-color4', 'date-color5'); // Define an array of colors
                        $index = 0; ?>
                        @foreach ($sale1 as $sale1s)
                        <?php
                        $class = 'float-start date ' . $colors[$index % count($colors)]; // Get the color class based on the current index
                        $index++; ?>
                        <div class="x_content mb-0">
                            <?php
                            $date = $sale1s->service_date;
                            $month = date('M', strtotime($date));
                            $day = date('d', strtotime($date));

                            ?>
                            <article class="media event">
                                <?php echo '<a class="' . $class . '">'; ?>
                                <p class="month"><?php echo $month; ?></p>
                                <p class="day"><?php echo $day; ?></p>
                                </a>
                                <?php $view_data = getInvoiceStatus($sale1s->job_no); ?>
                                @if ($view_data == 'Yes')
                                <a href="" data-bs-toggle="modal" c_service="{{ $sale1s->id }}" job_no="{{ $sale1s->job_no }}" url="{!! url('/jobcard/modalview') !!}" data-bs-target="#myModal-com-service" print="20" class="completedservice">
                                    @else
                                    <a href="{!! url('/jobcard/list/' . $sale1s->id) !!}">
                                        @endif
                                        <div class="media-body">
                                            <?php $dateservicepaid = date('Y-m-d', strtotime($sale1s->service_date)); ?>

                                            <span class="jobdetails">{{ $sale1s->job_no }} |
                                                {{ date(getDateFormat(), strtotime($dateservicepaid)) }} </span></br>
                                            <span><img src="{{ url('public/employee/' . getCustomerImage($sale1s->assign_to) ) }}" width="25px" class="rounded-circle"> {{ getAssignedName($sale1s->assign_to) }} <a data-toggle="tooltip" data-placement="bottom" title="Employee Name" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a></span>
                                        </div>
                                        @if ($view_data == 'Yes')
                                    </a>
                                    @else
                                    @endif
                            </article>
                        </div>
                        @endforeach
                        @else
                        @can('service_add')
                        <p style="text-align: center;">
                            <a id="" href="{!! url('/service/add') !!}">
                                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="mb-2"> {{ trans('message.Add Services') }}
                            </a>
                        </p>
                        @else
                        <p style="text-align: center;">{{ trans('message.No data available.') }}</p>
                        @endcan
                        @endif
                    </div>
                </div>
            </div>
            @endcan

            <div class="row">
                <!-- Upcoming service  service -->
                @can('dashboard_owndata')
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <div class="x_panel dashboard_x_panel shadow">
                        <!-- <div class="x_title">
                            <h2 class="overflow-visible">{{ trans('message.Upcoming Service Details') }}</h2>

                            <div class="clearfix"></div>
                        </div> -->
                         <div class="x_title row">
                            <div class="col-10">
                                <p class="fw-500 overflow-visible h5">
                                    {{ trans('message.Upcoming Service Details') }}
                                </p>
                            </div>
                            <div class="col-2">
                                <ul class="nav navbar-right">
                                    <li>
                                        <form method="get" action="jobcard/list">
                                            <!-- <input type="hidden" name="free" value="<?php echo 'free'; ?>" /> -->
                                            <button type="submit" class="btn  btn-default1 border-0"><img src="{{ URL::asset('public/img/dashboard/view.png') }}" style="width: 18px; height: 18px;"></button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php $userid = Auth::User()->id; ?>
                        @if (count($upcomingservice) !== 0)
                        <?php $colors = array('date-color1', 'date-color2', 'date-color3', 'date-color4', 'date-color5'); // Define an array of colors
                        $index = 0; ?>
                        @foreach ($upcomingservice as $upcomingservices)
                        <?php
                        $class = 'float-start date ' . $colors[$index % count($colors)]; // Get the color class based on the current index
                        $index++; ?>
                        <div class="x_content">
                            <?php
                            $date = $upcomingservices->service_date;
                            $month = date('M', strtotime($date));
                            $day = date('d', strtotime($date));

                            ?>
                            <article class="media event">
                                <?php echo '<a class="' . $class . '">'; ?>
                                <p class="month"><?php echo $month; ?></p>
                                <p class="day"><?php echo $day; ?></p>
                                </a>
                                <div class="media-body">
                                    <?php $upcomingservicesdate = date('Y-m-d', strtotime($upcomingservices->service_date)); ?>
                                    <span class="jobdetails">{{ $upcomingservices->job_no }} |
                                        {{ date(getDateFormat(), strtotime($upcomingservicesdate)) }}
                                    </span></br>
                                    <span> {{ getAssignedName($upcomingservices->assign_to) }} |
                                        {{ getVehicleName($upcomingservices->vehicle_id) }}</span>
                                </div>

                            </article>
                        </div>
                        @endforeach
                        @else
                        @can('service_add')
                        <p style="text-align: center;">
                            <a id="" href="{!! url('/service/add') !!}">
                                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="mb-2"> {{ trans('message.Add Services') }}
                            </a>
                        </p>
                        @else
                        <p style="text-align: center;">{{ trans('message.No data available.') }}</p>
                        @endcan
                        @endif
                    </div>
                </div>
                @endcan

                <!-- Holiday List -->
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <div class="x_panel dashboard_x_panel shadow">
                        <div class="x_title">
                            <h2 class="overflow-visible">{{ trans('message.Holiday List') }}</h2>
                            <div class="clearfix"></div>
                        </div>
                        @if (count($holiday) !== 0)
                        @foreach ($holiday as $holidays)
                        <div class="bessuhours">
                            <div class="row">
                                <div class="col-md-4 col-sm-12 bessuhoursday">
                                    <b>{{ date(getDateFormat(), strtotime($holidays->date)) }}</b>
                                </div>
                                <div class="col-md-8 col-sm-12 bessuhoursday">
                                    <span class="dayhours">{{ $holidays->title }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        @can('businesshours_add')
                        <p style="text-align: center;">
                            <a id="" href="{!! url('/setting/hours/list') !!}">
                                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="mb-2"> {{ trans('message.Add Holiday') }}
                            </a>
                        </p>
                        @else
                        <p style="text-align: center;">{{ trans('message.No data available.') }}</p>
                        @endcan
                        @endif
                    </div>
                </div>

            </div>

            @endcan
            @endif
            <!-- end Active(login) in show customer , employee -->


            <!--- Active(login) in show admin,supportstaff,accountant -->
            @if (getUsersRole(Auth::user()->role_id) == 'Super Admin' ||
            getUsersRole(Auth::user()->role_id) == 'Support Staff' ||
            getUsersRole(Auth::user()->role_id) == 'Accountant' ||
            getUsersRole(Auth::user()->role_id) == 'Branch Admin')
            @can('dashboard_view')
            <div class="row rtlrow" style="margin-top: -15px;">
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="x_panel dashboard_x_panel shadow pb-4">
                        <div class="x_title row">
                            <div class="col-10">
                                <p class="fw-500 overflow-visible h5">{{ trans('message.Recently Joined customer') }}</p>
                            </div>
                            <div class="col-2">
                                <ul class="nav navbar-right">
                                    <li><a href="{!! url('/customer/list') !!}" class="p-0"><img src="{{ URL::asset('public/img/dashboard/view.png') }}" style="width: 18px; height: 18px;"></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <ul class="list-unstyled top_profiles scroll-view ps-2">
                            @if (count($Customere) !== 0)
                            @foreach ($Customere as $user)
                            <div class="x_content mb-0">
                                <a class="userpic">
                                    <img src="{{ URL::asset('public/customer/' . $user->image) }}" style="width: 40px; height: 40px;margin-right: 18px;" class="rounded">
                                </a>
                                <div>
                                    <a class="title" href="customer/list/{{ $user->id }}"><strong>{{ $user->name }} {{ $user->lastname }}</a>
                                    </strong>
                                    <p> {{ $user->email }} </p>
                                    </p>
                                </div>
                            </div>
                            @endforeach
                            @else
                            @can('customer_add')
                            <p style="text-align: center;">
                                <a href="{!! url('/customer/add') !!}" id="">
                                    <img src="{{ URL::asset('public/img/icons/plus Button.png') }}"> {{ trans('message.Add Customer') }}
                                </a>
                            </p>
                            @else
                            <p style="text-align: center;">{{ trans('message.No data available.') }}</p>
                            @endcan
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                    <div class="x_panel dashboard_x_panel shadow pb-0">
                        <div class="x_title row mb-0 pb-0">
                            <div class="col-md-6 col-xs-6 col-sm-6">
                                <p class="fw-500 overflow-visible h5">{{ trans('message.Calendar') }}
                            </div>
                            <div class="col-6 text-end"><span class="service-indic service-open-indic"></span>{{ trans('message.Open') }}<span class="service-indic service-complete-indic ms-3"></span>{{ trans('message.Completed') }}</div>
                        </div>
                        <div class="x_content">

                            <div id="calendar"></div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming service details -->
            <div class="row rtlrow" style="margin-top: -5px;">
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <div class="x_panel dashboard_x_panel shadow">
                        <div class="x_title row">
                            <div class="col-10">
                                <p class="fw-500 overflow-visible h5">
                                    {{ trans('message.Upcoming Service Details') }}
                                </p>
                            </div>
                            <div class="col-2">
                                <ul class="nav navbar-right">
                                    <li>
                                        <form method="get" action="jobcard/list">
                                            <!-- <input type="hidden" name="free" value="<?php echo 'free'; ?>" /> -->
                                            <button type="submit" class="btn  btn-default1 border-0"><img src="{{ URL::asset('public/img/dashboard/view.png') }}" style="width: 18px; height: 18px;"></button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <?php $userid = Auth::User()->id; ?>
                        <ul class="list-unstyled top_profiles scroll-view ps-2">
                            @if (count($sale) != 0)
                            <?php $colors = array('date-color1', 'date-color2', 'date-color3', 'date-color4', 'date-color5'); // Define an array of colors
                            $index = 0; ?>
                            @foreach ($sale as $saless)
                            <?php
                            $class = 'float-start date ' . $colors[$index % count($colors)]; // Get the color class based on the current index
                            $index++; ?>
                            <div class="x_content">
                                <?php
                                $date = $saless->service_date;
                                $month = date('M', strtotime($date));
                                $day = date('d', strtotime($date));

                                ?>
                                <article class="media event">
                                    <?php echo '<a class="' . $class . '">'; ?>
                                    <p class="month"><?php echo $month; ?></p>
                                    <p class="day"><?php echo $day; ?></p>
                                    </a>
                                    <?php $view_data = getInvoiceStatus($saless->job_no); ?>
                                    @if ($view_data == 'Yes')
                                    <a href="" data-bs-toggle="modal" open_id="{{ $saless->id }}" job_no="{{ $saless->job_no }}" url="{!! url('/jobcard/modalview') !!}" data-bs-target="#myModal-open-modal" print="20" class="openmodel">
                                        @else
                                        <a href="{!! url('/jobcard/list/' . $saless->id) !!}">
                                            @endif
                                            <div class="media-body pt-1">
                                                <?php $dateservicefree = date('Y-m-d', strtotime($saless->service_date)); ?>
                                                <span class="jobdetails">{{ $saless->job_no }}
                                                    <i>&nbsp;&nbsp;{{ date(getDateFormat(), strtotime($dateservicefree)) }}</i> </span></br>
                                                <span><img src="{{ url('public/employee/' . getCustomerImage($saless->assign_to) ) }}" width="25px" class="rounded-circle"> {{ getAssignedName($saless->assign_to) }} <a data-toggle="tooltip" data-placement="bottom" title="Employee Name" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a></span>
                                            </div>
                                            @if ($view_data == 'Yes')
                                        </a>
                                        @else
                                        @endif
                                    </a>
                                </article>
                            </div>
                            @endforeach
                            @else
                            @can('service_add')
                            <p style="text-align: center;">
                                <a id="" href="{!! url('/service/add') !!}">
                                    <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="mb-2"> {{ trans('message.Add Services') }}
                                </a>
                            </p>
                            @else
                            <p style="text-align: center;">{{ trans('message.No data available.') }}</p>
                            @endcan
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- paid service -->
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <div class="x_panel dashboard_x_panel shadow">
                        <div class="x_title row">
                            <div class="col-10">
                                <p class="fw-500 overflow-visible h5">
                                    {{ trans('message.Paid Service Details') }}
                                </p>
                            </div>
                            <div class="col-2">
                                <ul class="nav navbar-right">
                                    <form method="get" action="jobcard/list">
                                        <input type="hidden" name="paid" value="<?php echo 'paid'; ?>" />
                                        <button type="submit" class="btn  btn-default1 border-0"><img src="{{ URL::asset('public/img/dashboard/view.png') }}" style="width: 18px; height: 18px;">
                                        </button>
                                    </form>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <ul class="list-unstyled top_profiles scroll-view ps-2">
                            @if (count($sale1) != 0)
                            <?php $colors = array('date-color1', 'date-color2', 'date-color3', 'date-color4', 'date-color5'); // Define an array of colors
                            $index = 0; ?>
                            @foreach ($sale1 as $sale1s)
                            <?php
                            $class = 'float-start date ' . $colors[$index % count($colors)]; // Get the color class based on the current index
                            $index++; ?>
                            <div class="x_content">
                                <?php
                                $date = $sale1s->service_date;
                                $month = date('M', strtotime($date));
                                $day = date('d', strtotime($date));

                                ?>
                                <article class="media event">
                                    <?php echo '<a class="' . $class . '">'; ?>
                                    <p class="month"><?php echo $month; ?></p>
                                    <p class="day"><?php echo $day; ?></p>
                                    </a>
                                    <?php $view_data = getInvoiceStatus($sale1s->job_no); ?>
                                    @if ($view_data == 'Yes')
                                    <a href="" data-bs-toggle="modal" c_service="{{ $sale1s->id }}" job_no="{{ $sale1s->job_no }}" url="{!! url('/jobcard/modalview') !!}" data-bs-target="#myModal-com-service" print="20" class="completedservice">
                                        @else
                                        <a href="{!! url('/jobcard/list/' . $sale1s->id) !!}">
                                            @endif
                                            <div class="media-body pt-0">
                                                <?php $dateservicefree = date('Y-m-d', strtotime($sale1s->service_date)); ?>

                                                <span class="jobdetails">{{ $sale1s->job_no }}
                                                    <i>&nbsp;&nbsp;{{ date(getDateFormat(), strtotime($dateservicefree)) }}</i> </span></br>
                                                <span><img src="{{ url('public/employee/' . getCustomerImage($sale1s->assign_to) ) }}" width="25px" class="rounded-circle"> {{ getAssignedName($sale1s->assign_to) }} <a data-toggle="tooltip" data-placement="bottom" title="Employee Name" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a></span>
                                            </div>
                                            @if ($view_data == 'Yes')
                                        </a>
                                        @else
                                        @endif
                                </article>
                            </div>
                            @endforeach
                            @else
                            @can('service_add')
                            <p style="text-align: center;">
                                <a id="" href="{!! url('/service/add') !!}">
                                    <img src="{{ URL::asset('public/img/icons/plus Button.png') }}" class="mb-2"> {{ trans('message.Add Services') }}
                                </a>
                            </p>
                            @else
                            <p style="text-align: center;">{{ trans('message.No data available.') }}</p>
                            @endcan
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            @endcan
            @endif
            <!---end Active(login) in show admin,supportstaff,accountant-->

        </div>
        <div id="myModal-job" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <a href=""><button type="button" class="close">&times;</button></a>
                        <h4 id="myLargeModalLabel" class="modal-title">{{ trans('message.Invoice') }}</h4>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Customer side service Booking Modal -->
         <!--service Modal -->
    <div class="modal fade" id="myserviceModal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('message.Book Services') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ServiceAdd-Form" method="post" action="{{ route('customer.addservice') }}" enctype="multipart/form-data" class="form-horizontal upperform serviceAddForm" border="10">

                        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 mt-3 space pb-4 ps-0">
                            <h4><b>{{ strtoupper(trans('message.Service Details')) }}</b></h4>
                            <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid mb-0"></p>
                        </div>

                        <div class="row mt-2 row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pe-0 width-100" for="first-name">{{ trans('message.JobCard No. ') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <input type="text" id="jobno" name="jobno" class="form-control" value="{{ $code }}" readonly>
                                </div>
                            </div>
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pe-0 width-100" for="Date">{{ trans('message.Date') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100 date">
                                    <input type='text' class="form-control datepicker" name="s_date" autocomplete="off" id='s_date' placeholder="<?php echo getDatepicker();
                                                                                                                                                echo ' hh:mm:ss'; ?>" value="" onkeypress="return false;" required/>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-2">

                        <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pe-0 width-100" for="first-name">{{ trans('message.Category') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <select name="repair_cat" class="form-control form-select w-115" id="repair_cat" name="repair_cat" required>
                                        <option value="">{{ trans('message.Select Repair Category') }}
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
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space pb-4 ps-0 mt-2">
                            <h4><b>{{ strtoupper(trans('message.Customer Details')) }}</b></h4>
                            <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid mb-0"></p>
                        </div>

                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="first-name">{{ trans('message.First Name') }} <label class="color-danger">*</label> </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <input type="text" id="firstname" name="firstname" class="form-control" value="{{ $Customer_detail->name }}" placeholder="{{ trans('message.Enter First Name') }}" maxlength="25" required readonly/>
                                    <span class="color-danger" id="errorlfirstname"></span>
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('lastname') ? ' has-error' : '' }} display-content ">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="last-name">{{ trans('message.Last Name') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <input type="text" id="lastname" name="lastname" placeholder="{{ trans('message.Enter Last Name') }}" value="{{ $Customer_detail->lastname  }}" maxlength="25" class="form-control" required readonly>
                                    <span class="color-danger" id="errorllastname"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('mobile') ? ' has-error' : '' }} display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="mobile">{{ trans('message.Mobile No') }}. <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <input type="text" id="mobile" name="mobile" placeholder="{{ trans('message.Enter Mobile No') }}" value="{{ $Customer_detail->mobile_no ?? ''  }}" class="form-control" maxlength="16" minlength="6" required readonly>
                                    <span class="color-danger" id="errorlmobile"></span>
                                </div>
                            </div>
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }} display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="Email">{{ trans('message.Email') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <input type="text" id="email" name="email" placeholder="{{ trans('message.Enter Email') }}" value="{{ $Customer_detail->email ?? ''  }}" class="form-control" maxlength="50" required readonly>
                                    <span class="color-danger" id="errorlemail"></span>
                                </div>
                            </div>

                        </div>

                        <div class="row row-mb-0">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback display-content">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="Country">{{ trans('message.Country') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                        <select class="form-control select_country form-select" id="country_id" name="country_id" countryurl="{!! url('/getstatefromcountry') !!}" required>
                                            <option value="">{{ trans('message.Select Country') }}</option>
                                            @foreach ($country as $countrys)
                                            <option value="{{ $countrys->id }}" <?php if(!empty($Customer_detail->country_id)){if($Customer_detail->country_id ==$countrys->id){ echo 'selected';}} ?>>{{ $countrys->name }} </option>
                                            @endforeach
                                        </select>
                                        <span class="color-danger" id="errorlcountry_id"></span>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback display-content">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="State ">{{ trans('message.State') }} </label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                        <select class="form-control state_of_country form-select" id="state_id" name="state_id" stateurl="{!! url('/getcityfromstate') !!}">
                                            <option value="">{{ trans('message.Select State') }}</option>
                                            @if ($state != null)
                                            @foreach ($state as $states)
                                            <option value="{!! $states->id !!}" <?php if(!empty($Customer_detail->state_id)){if ($Customer_detail->state_id == $states->id) {
                                                                                    echo 'selected';
                                                                                } }?>>{!! $states->name !!}</option>
                                            @endforeach
                                            @else
                                            <option value=""></option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback display-content">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="Town/City">{{ trans('message.Town/City') }}</label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                        <select class="form-control city_of_state form-select" id="city" name="city">
                                            <option value="">{{ trans('message.Select City') }}</option>
                                            @if ($city != null)
                                        @foreach ($city as $citys)
                                        <option value="{!! $citys->id !!}" <?php if(!empty($Customer_detail->city_id)){if ($Customer_detail->city_id == $citys->id) {
                                                                                echo 'selected';
                                                                            } }?>>{!! $citys->name !!}</option>
                                        @endforeach
                                        @else
                                        <option value=""></option>
                                        @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback display-content">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="Address">{{ trans('message.Address') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                        <textarea class="form-control" id="address" name="address" maxlength="100" required>{{ $Customer_detail->address ?? '' }}</textarea>
                                        <span class="color-danger" id="errorladdress"></span>
                                    </div>
                                </div>
                            </div>

                        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space pb-4 ps-0">
                            <h4><b>{{ strtoupper(trans('message.Vehicle Details')) }}</b></h4>
                            <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid m-0"></p>
                        </div>

                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pe-0 width-100" for="first-name">{{ trans('message.Vehicle Type') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <select class="form-control select_vehicaltype form-select" id="vehical_id1" name="vehical_id" vehicalurl="{!! url('/vehicle/vehicaltypefrombrand') !!}" required>
                                        <option value="">{{ trans('message.Select Vehicle Type') }}</option>
                                        @if (!empty($vehical_type))
                                        @foreach ($vehical_type as $vehical_types)
                                        <option value="{{ $vehical_types->id }}" <?php if(!empty($vehicles_dd->vehicle_type)){if($vehicles_dd->vehicle_type == $vehical_types->vehicle_type){echo 'selected';}}?>>
                                            {{ $vehical_types->vehicle_type }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="color-danger" id="errorlvehical_id1"></span>
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pe-0 width-100" for="first-name">{{ trans('message.Fuel Type') }} <label class="color-danger">*</label></label>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 width-100">
                                    <select class="form-control select_fueltype form-select" id="fueltype1" name="fueltype" required>
                                        <option value="">{{ trans('message.Select fuel type') }} </option>
                                        @if (!empty($fuel_type))
                                        @foreach ($fuel_type as $fuel_types)
                                        <option value="{{ $fuel_types->id }}">{{ $fuel_types->fuel_type ?? '' }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="color-danger" id="fuel1"></span>
                                </div>
                            </div>

                        </div>

                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pe-0 width-100" for="first-name">{{ trans('message.Vehicle Brand') }} <label class="color-danger">*</label></label>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 width-100">
                                    <select class="form-control select_vehicalbrand form-select" id="vehicabrand1" name="vehicabrand" url="{!! url('/vehicle/vehicalmodelfrombrand') !!}">
                                        <option value="">{{ trans('message.Select Brand') }}</option>
                                        @if (!empty($vehical_brand))
                                            @foreach ($vehical_brand as $vehical_brands)
                                            <option value="{{ $vehical_brands->id }}" <?php if(!empty($vehicles_dd->vehicle_brand)){ if($vehicles_dd->vehicle_brand == $vehical_brands->vehicle_brand){echo 'selected';}}?>>{{ $vehical_brands->vehicle_brand }}
                                            </option>
                                            @endforeach
                                            @endif
                                    </select>
                                    <span class="color-danger">
                                        <strong id="errorlvehicabrand1"></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pe-0 width-100" for="first-name">{{ trans('message.Number Plate') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <input type="text" id="number_plate" name="number_plate" value="{{ $vehicles_dd->number_plate ?? '' }}" placeholder="{{ trans('message.Enter Number Plate') }}" maxlength="30" class="form-control" required>
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
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pe-0 width-100" for="last-name">{{ trans('message.Model Name') }} <label class="color-danger">*</label></label>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 width-100">
                                    <select class="form-control model_addname form-select" id="modelname1" name="modelname" required>
                                        <option value="">{{ trans('message.Select Model') }}</option>
                                        @if (!empty($model_name))
                                            @foreach ($model_name as $model_names)
                                            <option value="{{ $model_names->id }}" <?php if(!empty($vehicles_dd->model_name)){if($vehicles_dd->model_name == $model_names->model_name){echo 'selected';}}?>> {{ $model_names->model_name }}
                                            </option>
                                            @endforeach
                                            @endif
                                    </select>
                                    <span class="color-danger" id="errorlmodelname1"></span>
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 mx-0">
                                <button type="submit" id="submitButton" class="btn btn-success serviceSubmitButton border-0">{{ trans('message.Submit') }}</button>
                            </div>
                        </div>
                    </form> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm mx-1" data-bs-dismiss="modal">{{ trans('message.Close') }}</button>
                </div>
            </div>

        </div>
    </div>


    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- All Js file for Charts -->
    <script nonce="{{ $cspNonce }}" type="text/javascript" src="{{ URL::asset('public/js/loader.min.js') }}"></script>
    {{-- <script nonce="{{ $cspNonce }}" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> --}}
    {{-- <script nonce="{{ $cspNonce }}" src="{{ URL::asset('public/js/49/loader.js') }}"
    defer="defer"></script> --}}

    <!-- service event in calendar -->
    <?php
    $service_data_array = null;
    if (!empty($serviceevent)) {
        foreach ($serviceevent as $serviceevents) {
            $i = 1;
            $n_start_date = date('Y-m-d', strtotime($serviceevents->service_date));
            $n_end_date = date('Y-m-d', strtotime($serviceevents->service_date));
            $sid = $serviceevents->job_no;
            $userid = Auth::User()->id;
            if (!empty(getActiveCustomer($userid) == 'yes' || getActiveEmployee($userid) == 'yes')) {
                $view_data = getInvoiceStatus($serviceevents->job_no);

                if ($view_data == 'No') {
                    $service_data_array[] = ['title' => $serviceevents->job_no, 'title1' => $serviceevents->job_no, 'dates' => date(getDateFormat(), strtotime($serviceevents->service_date)), 'customer' => getAssignedName($serviceevents->assign_to), 'vehicle' => getVehicleName($serviceevents->vehicle_id), 'plateno' => getRegistrationNo($serviceevents->vehicle_id), 'url' => 'jobcard/list/' . $serviceevents->id, 'start' => $n_start_date, 'end' => $n_end_date, 'color' => '#f0ad4e'];
                } else {
                    $service_data_array[] = ['title' => $serviceevents->job_no, 'title1' => $serviceevents->job_no, 'dates' => date(getDateFormat(), strtotime($serviceevents->service_date)), 'customer' => getAssignedName($serviceevents->assign_to), 'vehicle' => getVehicleName($serviceevents->vehicle_id), 'plateno' => getRegistrationNo($serviceevents->vehicle_id), 's_id' => $serviceevents->id, 'url1' => 'dashboard/open-modal', 'start' => $n_start_date, 'end' => $n_end_date, 'color' => '#5FCE9B'];
                }
            } else {
                $view_data = getInvoiceStatus($serviceevents->job_no);

                if ($view_data == 'No') {
                    $service_data_array[] = ['title' => $serviceevents->job_no, 'title1' => $serviceevents->job_no, 'dates' => date(getDateFormat(), strtotime($serviceevents->service_date)), 'customer' => getAssignedName($serviceevents->assign_to), 'vehicle' => getVehicleName($serviceevents->vehicle_id), 'plateno' => getRegistrationNo($serviceevents->vehicle_id), 's_id' => $serviceevents->id, 'url11' => 'service/list/view', 'start' => $n_start_date, 'end' => $n_end_date, 'color' => '#f0ad4e'];
                } else {
                    $service_data_array[] = ['title' => $serviceevents->job_no, 'title1' => $serviceevents->job_no, 'dates' => date(getDateFormat(), strtotime($serviceevents->service_date)), 'customer' => getAssignedName($serviceevents->assign_to), 'vehicle' => getVehicleName($serviceevents->vehicle_id), 'plateno' => getRegistrationNo($serviceevents->vehicle_id), 's_id' => $serviceevents->id, 'url1' => 'dashboard/open-modal', 'start' => $n_start_date, 'end' => $n_end_date, 'color' => '#5FCE9B'];
                }
            }
        }
    }

    //Holiday Event
    if (!empty($holiday)) {
        foreach ($holiday as $holidays) {
            $i = 1;
            $n_start_date = date('Y-m-d', strtotime($holidays->date));
            $n_end_date = date('Y-m-d', strtotime($holidays->date));
            $service_data_array[] = ['title' => substr($holidays->title, 0, 10), 'title1' => $holidays->title, 'dates' => date(getDateFormat(), strtotime($holidays->date)), 'description' => $holidays->description, 'customer' => 'Holiday', 'vehicle' => '', 'plateno' => '', 'start' => $n_start_date, 'end' => $n_end_date, 'color' => '#3a87ad'];
        }
    }
    if (!empty($service_data_array)) {
        $data1 = json_encode($service_data_array);
    } else {
        $data1 = json_encode('0');
    }
    ?>

    {{-- <script nonce="{{ $cspNonce }}" src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> --}}

    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/fullcalendar/lib/main.js') }}" defer="defer"></script>
    <!-- sample data add script -->
    <script nonce="{{ $cspNonce }}">
        // Check if sample data already exists and hide the button if it does
        window.onload = function () {
        // Check if the sample data exists in localStorage
    
            // Fetch the data from the server
            fetch("{{ route('check.sample.data') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.sampleExists) {
                        // Hide the button and store the state in localStorage
                        document.getElementById('add-sample-data-btn').style.display = 'none';
                        localStorage.setItem('sampleDataExists', 'true');
                    } else {
                        console.log('Data does not exist');
                        localStorage.setItem('sampleDataExists', 'false');
                    }
                })
                .catch(error => console.error('Error:', error));
        
    };

        // Button click event for adding sample data
        document.getElementById('add-sample-data-btn').addEventListener('click', function () {
            if (confirm('Are you sure you want to add sample data?')) {
                fetch("{{ route('add.sample.data') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);

                        // Hide the button if data is successfully added
                        document.getElementById('add-sample-data-btn').style.display = 'none';
                        window.location.reload();
                    } else {
                        alert('Failed to add sample data: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                    console.error(error);
                });
            }
        });
    </script>
    <script nonce="{{ $cspNonce }}">
        document.addEventListener('DOMContentLoaded', function() {
            var today = "{{ trans('message.today') }}";
            var dayGridMonth = "{{ trans('message.dayGridMonth') }}";
            var timeGridWeek = "{{ trans('message.timeGridWeek') }}";
            var timeGridDay = "{{ trans('message.timeGridDay') }}";
            var bookservice = "{{trans('message.+ Book Service')}}";
            var element = document.getElementById("footerforid");
            element.classList.remove("bottom-0");
            var calendarEl = document.getElementById('calendar');
            var esLocale = "{{ getCurrentLocal() }}";
            var userRole = "{{ getUsersRole(Auth::user()->role_id) }}";
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: "prev,today,next",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay"
                },
                buttonText: {
                    month: dayGridMonth, 
                    day: timeGridDay, 
                    week: timeGridWeek,
                    today: today
                },
                initialDate: new Date(),
                // responsive: "true",
                locale: 'en',
                dayMaxEventRows: 2,
                navLinks: true, // can click day/week na  mes to navigate views
                editable: true,
                // toolkip: true,
                events: <?php if (!empty($data1)) {
                            echo $data1;
                        } ?>,

                eventDidMount: function(info) {
                    var title1 = !info.event.extendedProps.title1 ? "" : info.event.extendedProps
                        .title1 + " | "
                    var title2 = !info.event.extendedProps.dates ? "" : info.event.extendedProps.dates +
                        "<br>"
                    var title3 = !info.event.extendedProps.customer ? "" : info.event.extendedProps
                        .customer + " | "
                    var title4 = !info.event.extendedProps.plateno ? "" : info.event.extendedProps
                        .plateno + " | "
                    var title5 = !info.event.extendedProps.vehicle ? "" : info.event.extendedProps
                        .vehicle
                    $(info.el).tooltip({
                        title: title1 + title2 + title3 + title4 + title5,
                        placement: "left",
                        trigger: "hover",
                        html: true,
                        container: "body",
                    });
                },
               
                dayCellDidMount: function(info) {
                // Give the cell a relative position
                info.el.style.position = 'relative';
                 // Get the translation for "+ Book Service" from Laravel
                // Create a button element
                var button = document.createElement('button');
                if (window.innerWidth <= 768) {
                    button.innerHTML = "+"; // Just plus sign for mobile
                } else if (window.innerWidth <= 1024) {
                    button.innerHTML = "Book"; // Short text for tablets
                } else {
                    button.innerHTML = bookservice; // Full text for desktop
                }
                button.style.display = 'none'; // Initially hide the button
                button.style.color = 'white';
                button.style.border = 'none';
                button.style.width = '-webkit-fill-available';
                button.style.position = 'absolute';
                button.style.bottom = '5%';

                // Append button to the day cell
                info.el.appendChild(button);

                // Add tooltip to the button
                // $(button).tooltip({
                //     title: 'Book Service',
                //     placement: "top",
                //     trigger: "hover",
                //     container: "body"
                // });

                // Show button on cell hover
                info.el.addEventListener('mouseenter', function() {
                    var currentDate = new Date(); // Get current date
                    var currentYear = currentDate.getFullYear();
                    var currentMonth = currentDate.getMonth() + 1; // Month is zero-based
                    var currentDay = currentDate.getDate();
                    var currentDateFormatted = currentYear + '-' + ('0' + currentMonth).slice(-2) + '-' + ('0' + currentDay).slice(-2);

                    var cellDate = info.date; // Get date associated with the cell
                    var cellYear = cellDate.getFullYear();
                    var cellMonth = cellDate.getMonth() + 1; // Month is zero-based
                    var cellDay = cellDate.getDate();
                    var cellDateFormatted = cellYear + '-' + ('0' + cellMonth).slice(-2) + '-' + ('0' + cellDay).slice(-2);

                    if (currentDateFormatted <= cellDateFormatted) {
                        // Dates are the same or current date is earlier, show button
                        button.style.display = 'inline-block';
                        button.style.background = '#EA6B00';
                    } else {
                        // Cell date is in the past, disable button
                        button.style.display = 'inline-block';
                        button.style.background = '#b0b0b0';
                        button.disabled = 'true';
                    }
                });

                // Hide button when mouse leaves cell
                info.el.addEventListener('mouseleave', function() {
                    button.style.display = 'none';
                });

                // Add event listener to button
                button.addEventListener('click', function() {
                    // Handle button click event here
                    var date = info.date;
                    var year = date.getFullYear();
                    var month = (date.getMonth() + 1).toString().padStart(2, '0'); // Month is zero-based
                    var day = date.getDate().toString().padStart(2, '0');
                    var hour = ('0' + new Date().getHours()).slice(-2);
                    var minute = ('0' + new Date().getMinutes()).slice(-2);
                    var second = ('0' + new Date().getSeconds()).slice(-2);
                    var formattedDate = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
                    console.log('Button clicked on ' + formattedDate);
                    // Redirect to the 'service/add' route with formatted date parameter
                    if (userRole === 'Customer') {
                    // For customers, open the modal and set the date
                    var date = info.date;
                    var year = date.getFullYear();
                    var month = (date.getMonth() + 1).toString().padStart(2, '0'); // Month is zero-based
                    var day = date.getDate().toString().padStart(2, '0');
                    var hour = ('0' + new Date().getHours()).slice(-2);
                    var minute = ('0' + new Date().getMinutes()).slice(-2);
                    var second = ('0' + new Date().getSeconds()).slice(-2);
                    var formattedDate = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
                    console.log('Button clicked on ' + formattedDate);
                    $('#s_date').val(formattedDate);
                    $('#myserviceModal').modal('show');
                    } else {
                        // For non-customers, redirect to service/add with date parameter
                        window.location.href = '{{ url("service/add") }}?date=' + formattedDate;
                    }
                    // window.location.href = '{{ url("service/add") }}?date=' + formattedDate;
                    });
                },

                eventClick: function(event) {
                    if (event.url) {
                        window.location(event.url);
                    }
                    if (event.url1) {
                        $('#myModal-job').toggle();
                        $('.modal-body').html("");
                        var serviceid = (event.s_id);
                        var url = (event.url1);

                        $.ajax({
                            type: 'GET',
                            url: url,
                            data: {
                                open_id: serviceid
                            },
                            success: function(data) {
                                $('.modal-body').html(data.html);
                            },
                            beforeSend: function() {
                                $(".modal-body").html(
                                    "<center><h2 class=text-muted><b>Loading...</b></h2></center>"
                                );
                            },
                            error: function(e) {
                                alert("An error occurred: " + e.responseText);
                                // console.log(e);
                            }
                        });
                    }
                    if (event.url11) {
                        $('#myModal-customer-modal').modal();
                        $('.modal-body').html("");
                        var servicesid = (event.s_id);
                        var url = (event.url11);

                        $.ajax({
                            type: 'GET',
                            url: url,
                            data: {
                                servicesid: servicesid
                            },
                            success: function(data) {
                                $('.modal-body').html(data.html);
                            },
                            beforeSend: function() {
                                $(".modal-body").html(
                                    "<center><h2 class=text-muted><b>Loading...</b></h2></center>"
                                );
                            },
                            error: function(e) {
                                alert("An error occurred: " + e.responseText);
                                // console.log(e);
                            }
                        });
                    }
                }

            });
            // alert(esLocale)
            calendar.render();
            calendar.setOption('locale', esLocale);
        });
    </script>
    <script nonce="{{ $cspNonce }}">
    $(document).ready(function() {
        // var oldUserRadio = document.getElementById("old");

        // oldUserRadio.addEventListener("change", function () {
        //     window.location.href = "{!! url('/login') !!}"; // Change the URL to your login page
        //     $('#myModal').modal('hide'); // Change 'myModal' to your modal ID
        // });

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
        });
    });
</script>
    <script nonce="{{ $cspNonce }}">
        // For get getParameterByName
        function getParameterByName(name, url = window.location.href) {
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }
        /*Free service*/
        $(".openmodel").click(function() {

            $('.modal-body').html("");
            var open_id = $(this).attr("open_id");
            var job_no = $(this).attr("job_no");

            var url = $(this).attr('url');
            var currentPageAction = getParameterByName('page_action');
            // Construct the URL for AJAX request with page_action parameter
            if (currentPageAction) {
                url += '?page_action=' + currentPageAction;
            }
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    serviceid: open_id,
                    job_no: job_no
                },
                dataType:'json',
                success: function(data) {
                    $('.modal-body').html(data.html);
                },

                beforeSend: function() {
                    $(".modal-body").html(
                        "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                },

                error: function(e) {
                    alert("An error occurred: " + e.responseText);
                    // console.log(e);
                }
            });
        });


        /*Paid service*/
        $(".completedservice").click(function() {

            $('.modal-body').html("");

            var c_service = $(this).attr("c_service");
            var job_no = $(this).attr("job_no");

            var url = $(this).attr('url');
            var currentPageAction = getParameterByName('page_action');
            // Construct the URL for AJAX request with page_action parameter
            if (currentPageAction) {
                url += '?page_action=' + currentPageAction;
            }

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    open_id: c_service,
                    job_no: job_no
                },
                dataType:'json',
                success: function(data) {
                    $('.modal-body').html(data.html);
                },

                beforeSend: function() {
                    $(".modal-body").html(
                        "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                },

                error: function(e) {
                    alert("An error occurred: " + e.responseText);
                    // console.log(e);
                }
            });
        });



        /*Repeat Job service*/
        $(".service-up").click(function() {

            $('.modal-body').html("");

            var u_service = $(this).attr("u_service");

            var url = $(this).attr('url');

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    open_id: u_service
                },

                success: function(data) {
                    $('.modal-body').html(data.html);
                },

                beforeSend: function() {
                    $(".modal-body").html(
                        "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                },

                error: function(e) {
                    alert("An error occurred: " + e.responseText);
                    // console.log(e);
                }
            });
        });



        /*Free customer model service*/
        $(".customeropenmodel").click(function() {

            $('.modal-body').html("");

            var open_customer_id = $(this).attr("open_customer_id");
            var url = $(this).attr('url');

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    servicesid: open_customer_id
                },

                success: function(data) {
                    $('.modal-body').html(data.html);
                },

                beforeSend: function() {
                    $(".modal-body").html(
                        "<center><h2 class=text-muted><b>Loading...</b></h2></center>");
                },

                error: function(e) {
                    alert("An error occurred: " + e.responseText);
                    // console.log(e);
                }
            });
        });
        $(".toogle_item").click(function() {

            if (document.getElementById("app-layout").classList.contains('nav-sm')) {

                document.getElementById("app-layout").classList.add('nav-md');

                document.getElementById("app-layout").classList.remove('nav-sm');
            } else {
                document.getElementById("app-layout").classList.add('nav-sm');

                document.getElementById("app-layout").classList.remove('nav-md');
            }
        });




        /*if check wizard is displaying or hide*/
        var hideVal = $('.mainRowDiv').attr('isHide');

        if (hideVal == 1) {
            //Nothing to do
        } else {
            $('.mainBoxClass').removeClass('calculationBoxes');
        }
    </script>


    <!-- <script nonce="{{ $cspNonce }}" type="text/javascript">
        /*Monthly service in barchart*/
        google.load("visualization", "1", {
            packages: ["corechart"]
        });
        google.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["{{ trans('message.Date') }}", "{{ trans('message.Service') }}", {
                    role: 'style'
                }, {
                    role: 'annotation'
                }],

                <?php
                for ($i = 1; $i <= sizeof($dates); $i++) {
                    $count =  getNumberOfService($i);

                ?>['<?php echo $i; ?>', <?php echo $count; ?>, '', ''],
                <?php

                }
                ?>
            ]);

            var options = {
                legend: 'none',
                heigth: 150,
                chartArea: {
                    left: 40,
                    'width': '90%',
                    top: 20,
                    bottom: 50,
                },
                fontSize: 10,
                color: '#73879C',
                hAxis: {
                    title: "{{ trans('message.Dates') }}",
                    titleTextStyle: {
                        fontSize: 12,
                        color: '#4E5E6A',
                        fontName: 'Roboto'
                    },
                },
                vAxis: {
                    title: "{{ trans('message.Number Of Service') }}",
                    titleTextStyle: {
                        fontSize: 12,
                        color: '#4E5E6A',
                        fontName: 'Roboto'
                    },
                    format: 'decimal',
                },
            };

            var chart = new google.visualization.ColumnChart(document.getElementById("barchart"));
            chart.draw(data, options);
        }
    </script> -->

    <!-- Ontime donutchart-->
    <!-- <script nonce="{{ $cspNonce }}" type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["{{ trans('message.Hours') }}", "{{ trans('message.No of service') }}"],
                ["{{ trans('message.24-Hours') }}", <?php if (!empty($one_day)) {
                                                        echo $one_day;
                                                    } else {
                                                        echo '0';
                                                    } ?>],
                ["{{ trans('message.48-Hours') }}", <?php if (!empty($two_day)) {
                                                        echo $two_day;
                                                    } else {
                                                        echo '0';
                                                    } ?>],
                ["{{ trans('message.48-Hours After') }}", <?php if (!empty($more)) {
                                                                echo $more;
                                                            } else {
                                                                echo '0';
                                                            } ?>],
            ]);

            var options = {
                fontSize: 10,
                fontName: 'sans-serif',
                height: 150,
                chartArea: {
                    left: 1,
                    right: 2,
                    bottom: 30,
                    top: 30
                },
                legend: {
                    position: 'right',
                    maxLines: 5,
                    textStyle: {
                        fontSize: 10,
                        color: '#73879C',
                        bold: true
                    }
                },
                isStacked: 'relative',
                vAxis: {
                    minValue: 0,
                    ticks: [0, .3, .6, .9, 1]
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchartontime'));
            chart.draw(data, options);
        }
    </script> -->

    <!-- Vehicle  donutchart-->
    <!-- <script nonce="{{ $cspNonce }}" type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["{{ trans('message.Vehicle') }}", "{{ trans('message.Number of service') }}"],
                @if($vehical)
                @foreach($vehical as $vehicals)
                <?php $v_name = getVehicleName($vehicals->vid); ?>['<?php echo $v_name; ?>', <?php echo $vehicals->count; ?>],
                @endforeach
                @endif
            ]);

            var options = {
                is3D: true,
                fontSize: 10,
                fontName: 'sans-serif',
                height: 150,
                chartArea: {
                    left: 3,
                    right: 3,
                    bottom: 30,
                    top: 10
                },
                legend: {
                    position: 'right',
                    maxLines: 5,
                    textStyle: {
                        fontSize: 10,
                        color: '#73879C',
                        bold: true
                    }
                },
                isStacked: 'relative',
                vAxis: {
                    minValue: 0,
                    ticks: [0, .3, .6, .9, 1]
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
    </script> -->

    <!-- Performance  donutchart-->
    <!-- <script nonce="{{ $cspNonce }}" type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["{{ trans('message.Employee') }}", "{{ trans('message.No of service') }}"],
                @if($performance)
                @foreach($performance as $performances)
                <?php $assigne = getAssignedName($performances->a_id); ?>['<?php echo $assigne; ?>', <?php echo $performances->count; ?>],
                @endforeach
                @endif
            ]);

            var options = {
                is3D: true,
                fontSize: 10,
                fontName: 'sans-serif',
                height: 180,
                chartArea: {
                    left: 5,
                    right: 5,
                    bottom: 5,
                    top: 15
                },
                legend: {
                    position: 'right',
                    maxLines: 15,
                    textStyle: {
                        fontSize: 12,
                        padding: '5px',
                        color: '#73879C',
                        bold: true
                    }
                },
                isStacked: 'relative',
                vAxis: {
                    minValue: 0,
                    ticks: [0, .3, .6, .9, 1]
                }
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchartperformance'));
            chart.draw(data, options);
        }
    </script> -->

    <!-- Add By Dhara -->

    <!-- SetUp Wizard Start -->
    <script nonce="{{ $cspNonce }}">
        $(document).ready(function() {
            $(".arrow-down").click(function() {
                $(".arrow-down").hide();
                $(".arrow-up").show();
                $(".step-group").slideDown();
            });

            $(".arrow-up").click(function() {
                $(".arrow-up").hide();
                $(".arrow-down").show();
                $(".step-group").slideUp();
            });

            $(".close-icon").click(function() {
                $("#setup_wizard").hide();
            });
        });
    </script>
    <!-- SetUp Wizard End -->

    <!-- Service Chart Start -->
    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('public/js/Chart.min.js') }}"></script>
    <!-- <script nonce="{{ $cspNonce }}" src="https://github.com/chartjs/Chart.js/releases/download/v2.9.3/Chart.min.js"></script> -->

    <script nonce="{{ $cspNonce }}">
        var freeServices = parseInt(document.getElementById('freeServices').value);
        var paidServices = parseInt(document.getElementById('paidServices').value);

        var options1 = {
            type: 'doughnut',
            data: {
                labels: ['Free Services', 'Paid Services'],
                datasets: [{
                    data: [freeServices, paidServices],
                    backgroundColor: [
                        '#FF9054',
                        '#44CB7F',
                    ],
                    borderColor: [
                        'rgba(255, 255, 255 ,1)',
                        'rgba(255, 255, 255 ,1)',
                    ],
                    borderWidth: 5,
                    // borderRadius: 10
                }]
            },
            options: {
                rotation: 1 * Math.PI,
                circumference: 1 * Math.PI,
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                },
                cutoutPercentage: 85,
                plugins: {
                    roundedCorners: true
                }
            }
        }
        var ctx1 = document.getElementById('chartJSContainer').getContext('2d');
        new Chart(ctx1, options1);
        var options2 = {
            type: 'doughnut',
            data: {
                labels: ['Free Services', 'Paid Services'],
                datasets: [{
                    data: [88.5, 1],
                    backgroundColor: [
                        "rgba(0,0,0,0)",
                        "rgba(255,255,255,1)",
                    ],
                    borderColor: [
                        'rgba(0, 0, 0 ,0)',
                        'rgba(46, 204, 113, 1)',
                    ],
                    borderWidth: 5,
                    // borderRadius: 10
                }]
            },
            options: {
                cutoutPercentage: 95,
                rotation: 1 * Math.PI,
                circumference: 1 * Math.PI,
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: false
                },
                plugins: {
                    roundedCorners: true
                }
            }
        }
        // var ctx2 = document.getElementById('secondContainer').getContext('2d');
        // new Chart(ctx2, options2);
    </script>
    <!-- Service Chart End-->

    @endsection