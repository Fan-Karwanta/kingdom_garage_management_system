<!DOCTYPE html>

<html lang="en">

<head>
    <meta content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap -->
    <link href="{{ URL::asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- <script nonce="{{ $cspNonce }}" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
    <script nonce="{{ $cspNonce }}" src="{{ asset('vendors/jquery/jquery-3.7.1.min.js') }}"></script>

    <!-- Font Awesome  V6.1.1-->
    <link href="{{ URL::asset('vendors/font-awesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('vendors/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('vendors/font-awesome/4.7.0/fontawesome.min.css') }}">

    <!-- NProgress -->
    <link href="{{ URL::asset('vendors/nprogress/nprogress.css') }}" rel="stylesheet">

    <link href="{{ URL::asset('vendors/select2/css/select2.min.css') }}" rel="stylesheet">

    <!-- FullCalendar V5.11.0 -->
    <link href="{{ URL::asset('vendors/fullcalendar/lib/main.min.css') }}" rel="stylesheet">

    <link href="{{ URL::asset('vendors/bootstrap-date-time-picker/bootstrap5/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">

    <!-- dropify CSS -->
    <link rel="stylesheet" href="{{ URL::asset('vendors/dropify/css/dropify.min.css') }}">


    <link rel="icon" href="{{ URL::asset('fevicol.png') }}" type="image/gif" sizes="16x16">
    <title>{{ getNameSystem() }}</title>


    <!-- bootstrap-daterangepicker -->
  {{-- <link href="{{ URL::asset('vendors/bootstrap-daterangepicker/daterangepicker.css') }} "
  rel="stylesheet"> --}}
  {{-- <link href="{{ URL::asset('vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css') }}"
  rel="stylesheet"> --}}
  <link href="{{ URL::asset('vendors/bootstrap-date-time-picker/bootstrap5/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
  {{-- E:\xampp\htdocs\garagemaster_web\vendors\bootstrap-date-time-picker\bootstrap5\css\bootstrap-datetimepicker.css --}}

    <!-- Custom Theme Style -->
    <link href="{{ URL::asset('build/css/custom.min.css') }} " rel="stylesheet">

    <!-- Own Theme Style -->
    <link href="{{ URL::asset('build/css/own.css') }} " rel="stylesheet">


    <!-- Our Custom stylesheet -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/responsive_styles.css') }}">

    <!-- MoT Custom stylesheet -->
    <link rel="stylesheet" type="text/css" href=" {{ URL::asset('public/css/custom_mot_styles.css') }} ">
    <!-- Datatables -->
    <!-- <link href="{{ URL::asset('https://code.jquery.com/jquery-3.5.1.js') }}" rel="stylesheet">
  <link href="{{ URL::asset('https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js') }}" rel="stylesheet">
  <link href="{{ URL::asset('https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js') }}" rel="stylesheet"> -->

    <link href="{{ URL::asset('vendors/datatable/jquery-3.5.1.js') }}" type="text/js" rel="stylesheet">
    <link href="{{ URL::asset('vendors/datatable/jquery.dataTables.min.js') }}" type="text/js" rel="stylesheet">
    <link href="{{ URL::asset('vendors/datatable/dataTables.bootstrap5.min.js') }}" type="text/js" rel="stylesheet">
    <!-- Datatables -->

    <!-- AutoComplete CSS -->
    <link href="{{ URL::asset('build/css/themessmoothness.css') }}" rel="stylesheet">
    <!-- Multiselect CSS -->
    <link href="{{ URL::asset('build/css/multiselect.css') }}" rel="stylesheet">

    <!-- <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'> -->
    <link rel="stylesheet" href="{{ URL::asset('vendors/font-awesome/4.7.0/popins.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/google_api_font.css') }}">

    <style>
        @media print {
            .noprint {
                display: none
            }
        }

        select#vhi,
        #cust_id {
            width: 450px;
        }

        button.btn.btn-outline-secondary {
            float: right;
        }

        .main-div-school-container {
            top: 50px !important;
            position: relative !important;
        }

        .heade-content-login-page {
            margin: -16px 100px 0px;
            padding: 0px 20px 1px !important;
            /* width: fit-content; */
        }

        .header-title-trusted-plugin {
            font-size: 30px !important;
        }

        a {
            color: #333333 !important;
        }

        .blur-modal {
            filter: blur(2px);
        }

        select.form-control.select_vehicaltype {
            width: 100%;
        }

        select.form-control.select_vehicalbrand,
        select.form-control.model_addname,
        select.form-control.select_fueltype {

            width: 139%;
        }

        .h5,
        h5 {
            font-size: 18px !important;
        }
        select.form-select {
            background-image: linear-gradient(45deg, transparent 50%, #ccc 50%), linear-gradient(135deg, #ccc 50%, transparent 50%);
            background-size: 5px 5px;
            background-position: calc(100% - 20px) calc(1em + 2px), calc(100% - 15px) calc(1em + 2px);
            background-repeat: no-repeat;
        }

       .calendar-content {
            padding: 0px;
            margin-left: 12% !important;
            margin-top: 0% !important;
            width: 75% !important;
        }

        .image-container img {
            max-width: 50%;
            float: right;
        }

        .image-container{
            margin: auto;
        }

        .ln_solid{
            margin: 0px !important;
        }

        /* Small screens */
	@media (max-width: 767px) {
		a.fc-col-header-cell-cushion {
			font-size: 40%;
		}

		.fc .fc-toolbar {
            display: block;
        }
        
        .fc .fc-toolbar.fc-header-toolbar {
            text-align: center;
        }

        .image-container img {
            margin-top: 10%;
            margin-right: 25%;
        }

        .display-content{
            display: contents;
        }
        .width-100{
            width: 100% !important;
        }

        select.form-control.select_vehicalbrand,
        select.form-control.model_addname,
        select.form-control.select_fueltype {

            width: 100%;
        }
	}
    .footer-line{
    position: absolute;
    bottom: 10px;
    left:43%;
    font-weight: 300;
    color:grey;
    text-align: center;
    align-items: center;
   }
   .footer-line a{
    text-decoration:underline;
    font-weight: 300;
    margin-left: 4px;
   }
   .footer-line a:hover{
    color: #2596BE !important;
   }
       
    </style>
    <!-- colorpicker links -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script nonce="{{ $cspNonce }}" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script nonce="{{ $cspNonce }}" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <script nonce="{{ $cspNonce }}" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/js/bootstrap-colorpicker.min.js"></script> -->
    <link href="{{ URL::asset('vendors/bootstrap/dist/css/bootstrapnew.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('vendors/bootstrap/dist/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
    <script nonce="{{ $cspNonce }}" src="{{ asset('vendors/jquery/jquery.js') }}"></script>
    <script nonce="{{ $cspNonce }}" src="{{ asset('vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script nonce="{{ $cspNonce }}" src="{{ asset('vendors/bootstrap/dist/js/bootstrap-colorpicker.min.js') }}"></script>
</head>

<body class="login_reset_pwd">

    <div class="img-all-background-box-bck-main-cont">
        <div class="img-one-right-side-min-sch">
            <img class="img-first-bck-contn-sch" src="{{ URL::asset('public/general_setting/Group 18368.png') }}">
        </div>

        <div class="img-second-right-side-min-sch">
            <img class="img-second-bck-contn-sch" src="{{ URL::asset('public/general_setting/Group 18367 (1).png') }}">
        </div>
        <div class="img-one-right-side-min-sch">
            <img class="img-first-bck-contn-sch-round" src="{{ URL::asset('public/general_setting/Group 18369.png') }}">
        </div>
    </div>
    <div>
        <!-- <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a> -->

        <div class="background-main-div-plugin-login">
            <div class="container">
                <div class="main-div-school-container">
                    <!-- <div class="header-bnner-login-page-mc">
                        <div class="heade-content-login-page">
                            <h1 class="header-title-trusted-plugin">
                                <img src="{{ URL::asset('public/general_setting/medal 1.png') }}" class="head_logo">
                                <span class="double_shadow_to_text_plugin_trusted"> {{ getNameSystem() }}</span>
                            </h1>
                            <h3 class="selling-codecanyon-plugin">Best in segment on codecanyon</h3>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-sm-4 col-md-4 sidebar">
                            <!-- <h3 class="ps-2">{{ getNameSystem() }}</h3> -->
                            <div class="w-auto mx-0 mt-5">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#myModal" class="bookService rounded-right">{{ trans('message.Book an appoinment') }}</button>
                            </div> 
                            <div class="w-auto mx-0 mt-4">
                                <a class="bookService rounded-right" href="{{ url('/') }}">{{ trans('message.Back to Login') }}</a>
                            </div>   
                        </div>
                        <div class="col-sm-8 col-md-8 sidebar">
                            <div class="image-container">
                                <img src="{{ URL::asset('/public/general_setting/' . getLogoSystem()) }}" alt="Your Image Description">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12 col-sm-12 m-5 calendar-content">
                            <div class="x_panel shadow pb-0">
                                <div class="row x_title m-0 p-0">
                                    <div class="col-6 fw-500 overflow-visible h5 w-100">{{ trans('message.Booking Calendar') }}</div>
                                </div>
                                <div class="x_content">

                                    <div id="calendar"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- powered by KingDom MS -->
                    <div class="footer-line">
                    Powered By<a href="#">KingDom MS</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--service Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('message.Book Services') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ServiceAdd-Form" method="post" action="{{ url('/service/forntendAdd') }}" enctype="multipart/form-data" class="form-horizontal upperform serviceAddForm" border="10">

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

                        <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space pb-4 ps-0">
                            <h4><b>{{ strtoupper(trans('message.Customer Details')) }}</b></h4>
                            <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid mb-0"></p>
                        </div>

                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback display-content">
                            <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100">{{ trans('message.User Type') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                        <label class="radio-inline">
                                            <input type="radio" name="user_type" id="new" value="new" class="free_service" required checked>{{ trans('message.New User') }}</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_type" id="old" value="old" required class="margin-left-10"> {{ trans('message.Existing User') }}</label>
                                    </div>
                            </div>
                        </div>

                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="first-name">{{ trans('message.First Name') }} <label class="color-danger">*</label> </label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <input type="text" id="firstname" name="firstname" class="form-control" value="{{ old('firstname') }}" placeholder="{{ trans('message.Enter First Name') }}" maxlength="25" required />
                                    <span class="color-danger" id="errorlfirstname"></span>
                                </div>
                            </div>

                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('lastname') ? ' has-error' : '' }} display-content ">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="last-name">{{ trans('message.Last Name') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <input type="text" id="lastname" name="lastname" placeholder="{{ trans('message.Enter Last Name') }}" value="{{ old('lastname') }}" maxlength="25" class="form-control" required>
                                    <span class="color-danger" id="errorllastname"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row row-mb-0">
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('mobile') ? ' has-error' : '' }} display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="mobile">{{ trans('message.Mobile No') }}. <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <input type="text" id="mobile" name="mobile" placeholder="{{ trans('message.Enter Mobile No') }}" value="{{ old('mobile') }}" class="form-control" maxlength="16" minlength="6" required>
                                    <span class="color-danger" id="errorlmobile"></span>
                                </div>
                            </div>
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }} display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="Email">{{ trans('message.Email') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                    <input type="text" id="email" name="email" placeholder="{{ trans('message.Enter Email') }}" value="{{ old('email') }}" class="form-control" maxlength="50" required>
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
                                            <option value="{{ $countrys->id }}">{{ $countrys->name }}</option>
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
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group has-feedback display-content">
                                    <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 width-100" for="Address">{{ trans('message.Address') }} <label class="color-danger">*</label></label>
                                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
                                        <textarea class="form-control" id="address" name="address" maxlength="100" required>{{ old('address') }}</textarea>
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
                                        <option value="{{ $vehical_types->id }}">
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
                                        <option value="{{ $fuel_types->id }}">{{ $fuel_types->fuel_type }}
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
                                    </select>
                                    <span class="color-danger">
                                        <strong id="errorlvehicabrand1"></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pe-0 width-100" for="first-name">{{ trans('message.Number Plate') }} <label class="color-danger">*</label></label>
                                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 width-100">
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
                            <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 display-content">
                                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4 pe-0 width-100" for="last-name">{{ trans('message.Model Name') }} <label class="color-danger">*</label></label>
                                <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 width-100">
                                    <select class="form-control model_addname form-select" id="modelname1" name="modelname" required>
                                        <option value="">{{ trans('message.Select Model') }}</option>
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
                    <button type="button" class="btn btn-outline-secondary btn-sm mx-1" data-bs-dismiss="modal" onclick="closeModal()">{{ trans('message.Close') }}</button>
                </div>
            </div>

        </div>
    </div>

    <script nonce="{{ $cspNonce }}">
    function closeModal() {
        var myModal = document.getElementById('myModal');
        if (myModal) {
            myModal.classList.remove('show');
            myModal.style.display = 'none';
            myModal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
            var backdrop = document.getElementById('modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }
    }
    // Also handle close button in header
    document.addEventListener('DOMContentLoaded', function() {
        var closeButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
        closeButtons.forEach(function(btn) {
            btn.addEventListener('click', closeModal);
        });
    });
    </script>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ trans('message.Alert') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ session('message') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm mx-1" data-bs-dismiss="modal">{{ trans('message.Close') }}</button>
                </div>
            </div>
        </div>
    </div>

</body>
<?php
//Holiday Event
if (!empty($holiday)) {
    foreach ($holiday as $holidays) {
        $i = 1;
        $n_start_date = date('Y-m-d', strtotime($holidays->date));
        $n_end_date = date('Y-m-d', strtotime($holidays->date));
        $service_data_array[] = ['title' => substr($holidays->title, 0, 10), 'title1' => $holidays->title, 'dates' => date(getDateFormat(), strtotime($holidays->date)), 'description' => $holidays->description, 'customer' => 'Holiday', 'vehicle' => '', 'plateno' => '', 'start' => $n_start_date, 'end' => $n_end_date, 'color' => '#ee7f25'];
    }
}
if (!empty($service_data_array)) {
    $data1 = json_encode($service_data_array);
} else {
    $data1 = json_encode('0');
}
?>
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/fullcalendar/lib/main.js') }}" defer="defer"></script>
  <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/sweetalert/dist/sweetalert.min.js') }}" defer="defer"></script>
<!-- bootstrap-daterangepicker -->
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/moment/moment.min.js') }}" defer="defer"></script>
  {{-- <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"
  defer="defer"></script> --}}
  <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/bootstrap-date-time-picker/bootstrap5/js/bootstrap-datetimepicker.min.js') }}" defer="defer"></script>
  <script nonce="{{ $cspNonce }}" src="{{ URL::asset('/vendors/bootstrap-date-time-picker/bootstrap5/js/locales/bootstrap-datetimepicker.en.js') }}" defer="defer"></script>

  {{-- <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/bootstrap-daterangepicker/daterangepicker.js') }}"
  defer="defer"></script> --}}
<script nonce="{{ $cspNonce }}">
    document.addEventListener('DOMContentLoaded', function() {
        var today = "{{ trans('message.today') }}";
        var dayGridMonth = "{{ trans('message.dayGridMonth') }}";
        var timeGridWeek = "{{ trans('message.timeGridWeek') }}";
        var timeGridDay = "{{ trans('message.timeGridDay') }}";

        var element = document.getElementById("footerforid");
        // element.classList.remove("bottom-0");
        var calendarEl = document.getElementById('calendar');
        var esLocale = "en";
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

                // Create a button element
                var button = document.createElement('button');
                button.innerHTML = '+ Book Service'; // Set button text
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
                    // Media query for small screens -Book service label
                    if (window.matchMedia("(min-width:320px) and (max-width: 492px)").matches) {
                        button.style.fontSize = '6px'; // Smaller font for mobile
                        // button.style.padding = '3px';
                        button.style.overflow = 'hidden'; 
                        button.style.whiteSpace = '-2px';
                    }
                    if (window.matchMedia("(max-width: 320px)").matches) {
                        button.style.fontSize = '6px'; // Smaller font for mobile
                        // button.style.padding = '3px';
                        button.style.overflow = 'hidden'; 
                        button.style.whiteSpace = '-2px';
                    }
                    if (currentDateFormatted <= cellDateFormatted) {
                        // Dates are the same or current date is earlier, show button
                        button.style.display = 'inline-block';
                        button.style.background = '#2596BE';
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
                    // var second = ('0' + new Date().getSeconds()).slice(-2);
                    var formattedDate = year + '-' + month + '-' + day + ' ' + hour + ':' + minute;
                    console.log('Button clicked on ' + formattedDate);
                    $('#s_date').val(formattedDate);
                    // Show modal using Bootstrap 5 API
                    var myModalEl = document.getElementById('myModal');
                    if (myModalEl && typeof bootstrap !== 'undefined') {
                        var modal = new bootstrap.Modal(myModalEl);
                        modal.show();
                    }
                });
            },

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
       /*show sweet alert message and then login*/
        var oldUserRadio = document.getElementById("old");

        oldUserRadio.addEventListener("change", function () {
            var msg1 = "Please login for this existing user";
            var msg2 = "and add or book service in dashboard.";
            var msg3 = "Cancel";
            var msg4 = "Login";

            swal({
                title: msg1,
                text: msg2,
                icon: 'info',
                cancelButtonColor: '#C1C1C1',
                buttons: [msg3, msg4],
                dangerMode: false,
            }).then((willLogin) => {
                if (willLogin) {
                    $('#myModal').modal('hide'); // Hide modal if confirmed
                    window.location.href = "{!! url('/login') !!}";
                } else {
                    // Optional: uncheck the radio if cancelled
                    oldUserRadio.checked = false;
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
    // Check if a success message is present in the session
    @if(session('message'))
        $(document).ready(function(){
            // Show the modal with the success message
            $('#successModal').modal('show');
        });
    @endif
</script>

</html>