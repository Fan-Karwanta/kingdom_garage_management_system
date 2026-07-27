@extends('layouts.app')
@section('content')
<!-- page content -->
<style>.table-responsive::-webkit-scrollbar {
    height: 8px; /* Height of the horizontal scrollbar */
}

.table-responsive::-webkit-scrollbar-track {
    background:rgb(196, 194, 193); /* Background of the scrollbar track */
}

.table-responsive::-webkit-scrollbar-thumb {
    background:rgb(214, 214, 213); /* Scrollbar thumb color */
    border-radius: 4px; /* Rounded edges for thumb */
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: grey; /* Change color on hover for better visibility */
}
/* Adjust the dropdown width if needed */
.select2-container--default .select2-dropdown {
    border-radius: 4px; /* Optional: rounded corners */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional: add shadow */
    width: 100%; /* Ensure dropdown matches width */
}
/* Only for searchable-single dropdowns  select product*/
.select2-container .select2-selection--single {
    width: 330px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    right: 15px !important;
}
@media only screen and (max-width: 600px) {
     .select2-container .select2-selection--single {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
    right: 7px !important;
}
}
</style>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="/" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                                {{ trans('message.Settings') }}</span></a>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
        @include('success_message.message')
        
        <div class="x_content table-responsive">
            @include('settings_navbar.settings_nav')
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <form id="other_setting_edit_form" method="post" action="{{ url('setting/currancy/store') }}" enctype="multipart/form-data" class="form-horizontal upperform">
                            @can('timezone_view')
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                                <h4><b>{{ trans('message.TIMEZONE') }}</b></h4>
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                            </div>

                            <div class="row has-feedback">
                                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext text-end" for="Country">{{ trans('message.Select Timezone') }} <label class="color-danger">*</label>
                                </label>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                    <select class="form-control timezone form-select searchable-select" name="timezone" required>

                                        <option value="">{{ trans('message.Please select timezone') }}</option>
                                        @if (!empty($currancy))
                                        @foreach ($currancy as $currancys)
                                        <option value="{{ $currancys->timezone }}" <?php if ($user->timezone == $currancys->timezone) {
                                                                                        echo 'selected';
                                                                                    } ?>>
                                            {{ $currancys->timezone }}
                                        </option>
                                        @endforeach
                                        @endif

                                    </select>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3"></div>
                            </div>
                            @endcan

                            @can('language_view')
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space mt-4">
                                <h4><b>{{ trans('message.LANGUAGE') }}</b></h4>
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                            </div>

                            <div class="row has-feedback">
                                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                                    <div class="alert alert-info">
                                        <strong>English</strong> is the only supported language for this application.
                                    </div>
                                </div>
                            </div>
                            @endcan

                            <!-- Date and Currency Start -->
                            @can('dateformat_view')
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space mt-4">
                                <h4><b>{{ trans('message.DATE FORMAT') }}</b></h4>
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                            </div>

                            <div class="row has-feedback">
                                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext text-end">{{ trans('message.Select Date Format') }}
                                    <label class="color-danger">*</label>
                                </label>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                    <select class="form-control dateformat form-select" name="dateformat" required>
                                        <option value="">{{ trans('message.Select Date Format') }}</option>
                                        <option value="Y-m-d" <?php if ($tbl_settings->date_format == 'Y-m-d') {
                                                                    echo 'selected';
                                                                } ?>><?php echo 'yyyy-mm-dd'; ?></option>
                                        <option value="m-d-Y" <?php if ($tbl_settings->date_format == 'm-d-Y') {
                                                                    echo 'selected';
                                                                } ?>><?php echo 'mm-dd-yyyy'; ?></option>
                                        <option value="d-m-Y" <?php if ($tbl_settings->date_format == 'd-m-Y') {
                                                                    echo 'selected';
                                                                } ?>><?php echo 'dd-mm-yyyy'; ?></option>
                                        <!-- <option value="M-d-Y" <?php if ($tbl_settings->date_format == 'M-d-Y') {
                                                                        echo 'selected';
                                                                    } ?>><?php echo 'MM-dd-yyyy'; ?></option> -->
                                    </select>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3"></div>
                            </div>
                            @endcan

                            @can('currency_view')
                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space mt-4">
                                <h4><b>{{ trans('message.CURRENCY') }}</b></h4>
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                            </div>
                            <div class="row has-feedback">
                                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext text-end">{{ trans('message.Select Currency') }}
                                    <label class="color-danger">*</label>
                                </label>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                   <select class="form-control Currency form-select searchable-select" name="Currency" required>
                                        <option value="">{{ trans('message.Select Currency') }}</option>
                                        @if (!empty($currencies))
                                            @foreach ($currencies as $currancyss)
                                                <option value="{{ $currancyss->id }}" {{ $currancyss->id == $tbl_settings->currancy ? 'selected' : '' }}>
                                                    {{ $currancyss->country }} - {{ $currancyss->currency }} - {{ $currancyss->code }} - {{ $currancyss->symbol }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3"></div>
                            </div>
                            @endcan
                            <!-- Date and Currency End -->

                            @can('email_view')

                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space mt-4">
                                <h4><b>{{ strtoupper(trans('message.Service')) }}</b></h4>
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                            </div>

                            <div class="row has-feedback">
                                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext">{{ trans('message.Edit service after creating invoice') }}
                                    <label class="color-danger"></label>
                                </label>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                    <input type="checkbox" name="edit_service" id="edit_service" class="form-check d-inline" style="height:20px; width:20px; margin-right:5px; position: relative; top: 7px; margin-bottom: 12px;" <?php if ($tbl_settings->edit_service == 1) {
                                                                                                                                                                                                                                        echo 'checked';
                                                                                                                                                                                                                                    } ?>>{{ trans('message.Enable') }}
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3"></div>
                            </div>

                            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space mt-4">
                                <h4><b>{{ strtoupper(trans('message.Frontend Service Booking')) }}</b></h4>
                                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
                            </div>

                            <div class="row has-feedback">
                                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext">{{ trans('message.Frontend Service Booking') }}
                                    <label class="color-danger"></label>
                                </label>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                    <input type="checkbox" name="frontend_service" id="frontend_service" class="bookingCheckbox form-check d-inline" style="height:20px; width:20px; margin-right:5px; position: relative; top: 7px; margin-bottom: 12px;" <?php if ($tbl_settings->frontend_service == 1) {
                                                                                                                                                                                                                                                                echo 'checked';
                                                                                                                                                                                                                                                            } ?>>{{ trans('message.Enable') }}
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3"></div>
                            </div>

                            <div class="row has-feedback">
                                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext">{{ trans('message.Assign Job To') }}
                                    <label class="color-danger">*</label>
                                </label>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                    <select class="form-control default_emp form-select" name="default_emp" required>
                                        <!-- <option value="">{{ trans('message.Select Employee') }}</option>    -->
                                        @if (!empty($employees))
                                        @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" <?php if ($employee->id == $tbl_settings->default_emp) {
                                                                                echo 'selected';
                                                                            } ?>>{{ $employee->name }} {{ $employee->lastname }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3"></div>
                            </div>

                            <div class="row has-feedback">
                                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext d-inline-block">{{ trans('message.Default Service Charge') }}(<?php echo getCurrencySymbols(); ?>)
                                    <label class="color-danger">*</label>
                                </label>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                    <input type="text" id="default_charge" name="default_charge" class="form-control" placeholder="{{ trans('message.Enter Default Service Charge') }}" maxlength="8" value="{{ $tbl_settings->default_charge }}" required>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3"></div>
                            </div>

                            <div class="row has-feedback">
                                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext">{{ trans('message.Default Password') }}
                                    <label class="color-danger">*</label>
                                </label>
                                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                                    <input type="text" class="form-control" name="default_password" placeholder="{{ trans('message.Enter Default Password') }}" value="{{ $tbl_settings->default_password }}" required>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3"></div>
                            </div>
                            @endcan


                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            @canany(['timezone_edit', 'language_edit', 'dateformat_edit', 'currency_edit'])
                            <div class="row has-feedback">
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                                    <button type="submit" class="btn timezonesubmit form-control">{{ trans('message.UPDATE') }}</button>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3"></div>
                            </div>
                            <!-- <div class="row space">
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group ">
                                    <a class="btn timezonecancel" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a>
                                </div>
                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-1 form-group ">
                                    <button type="submit" class="btn timezonesubmit form-control">{{ trans('message.UPDATE') }}</button>
                                </div>
                            </div> -->
                            @endcanany

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- page content end -->

<script nonce="{{ $cspNonce }}" type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script nonce="{{ $cspNonce }}">
$(document).ready(function() {
    $('.searchable-select').select2({
        placeholder: "{{ trans('message.Select Currency') }}",
        allowClear: true,
        width: '100%'
    });
});
</script>

<!-- Form field validation -->
{!! JsValidator::formRequest('App\Http\Requests\StoreOtherSettingEditFormRequest', '#other_setting_edit_form') !!}
<script nonce="{{ $cspNonce }}" type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>


@endsection