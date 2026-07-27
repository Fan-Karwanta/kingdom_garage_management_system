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


    <link rel="icon" type="image/x-icon" href="{{ URL::asset('garragelogo/favicons_kingdom/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('garragelogo/favicons_kingdom/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('garragelogo/favicons_kingdom/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ URL::asset('garragelogo/favicons_kingdom/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ URL::asset('garragelogo/favicons_kingdom/site.webmanifest') }}">
    <meta name="theme-color" content="#ffffff">
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
            .noprint { display: none }
        }

        /* ============ Modern Booking Revamp (matches login) ============ */
        * { box-sizing: border-box; }

        html, body { margin: 0; padding: 0; }

        body.login_reset_pwd {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
            background: #ececf0 !important;
            min-height: 100vh;
            color: #111827;
        }

        /* Hide legacy decorative background images */
        .img-all-background-box-bck-main-cont,
        .img-first-bck-contn-sch,
        .img-second-bck-contn-sch,
        .img-first-bck-contn-sch-round { display: none !important; }

        a { color: #EA6B00; }

        /* ---------- Top header bar ---------- */
        .booking-header {
            position: sticky;
            top: 0;
            z-index: 30;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 2px 12px rgba(15, 23, 42, 0.06);
        }

        .booking-header-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .booking-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .booking-brand img {
            max-height: 46px;
            max-width: 180px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .booking-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* ---------- Buttons ---------- */
        .bookService {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #16181d !important;
            color: #ffffff !important;
            border-radius: 12px !important;
            padding: 11px 20px !important;
            border: 0 !important;
            font-size: 14.5px;
            font-weight: 600;
            text-decoration: none !important;
            cursor: pointer;
            transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.1s ease;
            box-shadow: 0 6px 16px rgba(22, 24, 29, 0.18);
        }

        .bookService:hover {
            background: #EA6B00 !important;
            color: #ffffff !important;
            box-shadow: 0 8px 20px rgba(234, 107, 0, 0.28);
        }

        .bookService:active { transform: translateY(1px); }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #ffffff !important;
            color: #111827 !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 12px !important;
            padding: 10px 18px !important;
            font-size: 14.5px;
            font-weight: 600;
            text-decoration: none !important;
            cursor: pointer;
            transition: border-color 0.2s ease, color 0.2s ease, background 0.2s ease;
        }

        .btn-ghost:hover {
            border-color: #EA6B00 !important;
            color: #EA6B00 !important;
            background: rgba(234, 107, 0, 0.05) !important;
        }

        /* ---------- Main content ---------- */
        .booking-main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 28px 20px 90px;
        }

        .booking-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.10);
            padding: 24px;
        }

        .booking-card-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 2px;
            letter-spacing: -0.3px;
        }

        .booking-card-sub {
            color: #9ca3af;
            font-size: 14px;
            margin: 0 0 18px;
        }

        .booking-hint {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #ffedd5;
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
        }

        /* ---------- FullCalendar theming ---------- */
        #calendar { max-width: 100%; }

        .fc { font-size: 14px; }

        .fc .fc-toolbar-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .fc .fc-button-primary {
            background: #16181d;
            border-color: #16181d;
            box-shadow: none;
            text-transform: capitalize;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 10px;
        }

        .fc .fc-button-primary:not(:disabled):hover {
            background: #EA6B00;
            border-color: #EA6B00;
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background: #EA6B00;
            border-color: #EA6B00;
            box-shadow: none;
        }

        .fc .fc-button-primary:focus,
        .fc .fc-button-primary:not(:disabled).fc-button-active:focus {
            box-shadow: 0 0 0 3px rgba(234, 107, 0, 0.25);
        }

        .fc .fc-button .fc-icon { vertical-align: middle; }

        .fc-theme-standard td,
        .fc-theme-standard th { border-color: #eef0f3; }

        .fc-theme-standard .fc-scrollgrid { border-color: #eef0f3; border-radius: 12px; overflow: hidden; }

        .fc .fc-col-header-cell-cushion {
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.4px;
            padding: 8px 4px;
        }

        .fc .fc-daygrid-day-number { color: #374151; font-weight: 500; }

        .fc .fc-day-today { background: rgba(234, 107, 0, 0.06) !important; }

        .fc .fc-daygrid-day {
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .fc .fc-daygrid-day:hover:not(.fc-day-past) { background: rgba(234, 107, 0, 0.08); }

        .fc .fc-day-past { background: #fafafa; cursor: not-allowed; }
        .fc .fc-day-past .fc-daygrid-day-number { color: #c3c8d0; }

        .fc-daygrid-day-frame { position: relative; min-height: 74px; }

        /* + hint that appears on hover for bookable days */
        .fc-book-hint {
            position: absolute;
            left: 6px;
            bottom: 6px;
            right: 6px;
            background: #EA6B00;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 6px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s ease;
        }

        .fc-daygrid-day:hover:not(.fc-day-past) .fc-book-hint { opacity: 1; }

        /* ---------- Footer ---------- */
        .footer-line {
            position: fixed;
            bottom: 14px;
            left: 0;
            right: 0;
            font-weight: 400;
            color: #9ca3af;
            text-align: center;
            font-size: 13px;
        }

        .footer-line a {
            text-decoration: none;
            font-weight: 600;
            margin-left: 4px;
            color: #EA6B00 !important;
        }

        .footer-line a:hover { text-decoration: underline; }

        /* ---------- Modal ---------- */
        #myModal .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 70px rgba(15, 23, 42, 0.25);
        }

        #myModal .modal-header {
            background: #16181d;
            color: #ffffff;
            border: none;
            padding: 20px 26px;
        }

        #myModal .modal-title { font-weight: 700; font-size: 20px; }
        #myModal .modal-header .btn-close { filter: invert(1) grayscale(1) brightness(2); opacity: 0.8; }

        #myModal .modal-body { padding: 24px 26px; }

        #myModal .modal-footer {
            border-top: 1px solid #eef0f3;
            padding: 16px 26px;
        }

        .form-section {
            margin-bottom: 22px;
        }

        .form-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #EA6B00;
            margin: 0 0 14px;
        }

        .form-section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #eef0f3;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-grid .full { grid-column: 1 / -1; }

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
        }

        .field-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #6b7280;
            margin: 0 0 6px 2px;
        }

        .field-label .color-danger { color: #dc2626; }

        #myModal .form-control,
        #myModal .form-select {
            width: 100% !important;
            min-height: 46px;
            padding: 10px 14px;
            font-size: 14.5px;
            color: #111827;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            -webkit-appearance: none;
        }

        #myModal textarea.form-control { min-height: 80px; resize: vertical; }

        #myModal .form-control:focus,
        #myModal .form-select:focus {
            border-color: #EA6B00;
            box-shadow: 0 0 0 3px rgba(234, 107, 0, 0.12);
        }

        .user-type-toggle {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .user-type-toggle label.radio-inline {
            flex: 1;
            min-width: 130px;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            margin: 0;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .user-type-toggle label.radio-inline:hover { border-color: #EA6B00; }

        .user-type-toggle input[type="radio"] { accent-color: #EA6B00; width: 16px; height: 16px; margin: 0; }

        .color-danger { color: #dc2626; font-size: 12px; }

        .serviceSubmitButton {
            width: 100%;
            height: 50px;
            background: #16181d !important;
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 600;
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 6px 16px rgba(22, 24, 29, 0.2);
            transition: background 0.2s ease;
        }

        .serviceSubmitButton:hover { background: #EA6B00 !important; }

        @media (max-width: 640px) {
            .booking-header-inner { padding: 12px 14px; }
            .booking-actions { width: 100%; }
            .booking-actions .bookService,
            .booking-actions .btn-ghost { flex: 1; }
            .booking-main { padding: 18px 12px 90px; }
            .booking-card { padding: 16px; border-radius: 16px; }
            .fc .fc-toolbar.fc-header-toolbar {
                flex-direction: column;
                gap: 10px;
                align-items: stretch;
            }
            .fc .fc-toolbar-title { font-size: 17px; text-align: center; }
            .fc-daygrid-day-frame { min-height: 58px; }
        }
    </style>
    <!-- colorpicker links -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script nonce="{{ $cspNonce }}" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script nonce="{{ $cspNonce }}" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <script nonce="{{ $cspNonce }}" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/js/bootstrap-colorpicker.min.js"></script> -->
    {{--
        NOTE: bootstrapnew.min.css (Bootstrap 3.3.6) and the colorpicker assets were
        removed from this page. This page runs on Bootstrap 5 (bootstrap.min.css +
        bootstrap.bundle.min.js). Loading Bootstrap 3 CSS afterwards overrode the modal
        fade rules (.fade{opacity:0} revealed via .in instead of .show), which left the
        Bootstrap 5 booking modal invisible (opacity:0) when opened. The colorpicker is
        not used on this booking page.
    --}}
    <script nonce="{{ $cspNonce }}" src="{{ asset('vendors/jquery/jquery.js') }}"></script>
    <script nonce="{{ $cspNonce }}" src="{{ asset('vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Safety overrides: guarantee the Bootstrap 5 modal is visible even if a legacy
         (Bootstrap 3/4) stylesheet is present elsewhere in the asset pipeline. -->
    <style>
        #myModal.modal.fade .modal-dialog,
        #successModal.modal.fade .modal-dialog {
            transition: transform 0.2s ease-out;
            transform: translateY(-40px);
        }
        #myModal.modal.show .modal-dialog,
        #successModal.modal.show .modal-dialog {
            transform: none !important;
        }
        #myModal.modal.fade,
        #successModal.modal.fade {
            opacity: 1 !important;
        }
        .modal-backdrop.show {
            opacity: 0.5 !important;
        }
    </style>
</head>

<body class="login_reset_pwd">

    <!-- Top header -->
    <header class="booking-header">
        <div class="booking-header-inner">
            <div class="booking-brand">
                <img src="{{ URL::asset('/public/general_setting/' . getLogoSystem()) }}" alt="{{ getNameSystem() }}">
            </div>
            <div class="booking-actions">
                <a class="btn-ghost" href="{{ url('/') }}"><i class="fa fa-arrow-left"></i>Back</a>
                <button type="button" data-bs-toggle="modal" data-bs-target="#myModal" class="bookService"><i class="fa fa-calendar-plus"></i>Book</button>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="booking-main">
        <div class="booking-card">
            <h1 class="booking-card-title">{{ trans('message.Booking Calendar') }}</h1>
            <p class="booking-card-sub">{{ getNameSystem() }} &mdash; pick an available date to schedule your service appointment.</p>
            <div class="booking-hint"><i class="fa fa-hand-pointer-o"></i>{{ trans('message.Tap any available date to book a service') }}</div>
            <div id="calendar"></div>
        </div>
    </main>

    <!-- powered by KingDom MS
    <div class="footer-line">
        Powered By<a href="#">KingDom MS</a>
    </div> -->

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
                    <form id="ServiceAdd-Form" method="post" action="{{ url('/service/forntendAdd') }}" enctype="multipart/form-data" class="serviceAddForm">

                        <!-- Service Details -->
                        <div class="form-section">
                            <h5 class="form-section-title">{{ trans('message.Service Details') }}</h5>
                            <div class="form-grid">
                                <div>
                                    <label class="field-label" for="jobno">{{ trans('message.JobCard No. ') }} <span class="color-danger">*</span></label>
                                    <input type="text" id="jobno" name="jobno" class="form-control" value="{{ $code }}" readonly>
                                </div>
                                <div>
                                    <label class="field-label" for="s_date">{{ trans('message.Date') }} <span class="color-danger">*</span></label>
                                    <input type='text' class="form-control datepicker" name="s_date" autocomplete="off" id='s_date' placeholder="<?php echo getDatepicker(); echo ' hh:mm:ss'; ?>" value="" readonly required/>
                                </div>
                                <div class="full">
                                    <label class="field-label" for="repair_cat">{{ trans('message.Category') }} <span class="color-danger">*</span></label>
                                    <select name="repair_cat" class="form-control form-select" id="repair_cat" required>
                                        <option value="">{{ trans('message.Select Repair Category') }}</option>
                                        @if (!empty($repairCategoryList))
                                            @foreach ($repairCategoryList as $repairCategoryListData)
                                                <option value="<?php echo $repairCategoryListData->slug; ?>">{{ $repairCategoryListData->repair_category_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Details -->
                        <div class="form-section">
                            <h5 class="form-section-title">{{ trans('message.Customer Details') }}</h5>
                            <div class="form-grid">
                                <div class="full">
                                    <label class="field-label">{{ trans('message.User Type') }} <span class="color-danger">*</span></label>
                                    <div class="user-type-toggle">
                                        <label class="radio-inline">
                                            <input type="radio" name="user_type" id="new" value="new" class="free_service" required checked>{{ trans('message.New User') }}
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_type" id="old" value="old" required class="margin-left-10"> {{ trans('message.Existing User') }}
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="field-label" for="firstname">{{ trans('message.First Name') }} <span class="color-danger">*</span></label>
                                    <input type="text" id="firstname" name="firstname" class="form-control" value="{{ old('firstname') }}" placeholder="{{ trans('message.Enter First Name') }}" maxlength="25" required />
                                    <span class="color-danger" id="errorlfirstname"></span>
                                </div>
                                <div>
                                    <label class="field-label" for="lastname">{{ trans('message.Last Name') }} <span class="color-danger">*</span></label>
                                    <input type="text" id="lastname" name="lastname" placeholder="{{ trans('message.Enter Last Name') }}" value="{{ old('lastname') }}" maxlength="25" class="form-control" required>
                                    <span class="color-danger" id="errorllastname"></span>
                                </div>

                                <div>
                                    <label class="field-label" for="mobile">{{ trans('message.Mobile No') }}. <span class="color-danger">*</span></label>
                                    <input type="text" id="mobile" name="mobile" placeholder="{{ trans('message.Enter Mobile No') }}" value="{{ old('mobile') }}" class="form-control" maxlength="16" minlength="6" required>
                                    <span class="color-danger" id="errorlmobile"></span>
                                </div>
                                <div>
                                    <label class="field-label" for="email">{{ trans('message.Email') }} <span class="color-danger">*</span></label>
                                    <input type="text" id="email" name="email" placeholder="{{ trans('message.Enter Email') }}" value="{{ old('email') }}" class="form-control" maxlength="50" required>
                                    <span class="color-danger" id="errorlemail"></span>
                                </div>

                                <div>
                                    <label class="field-label" for="country_id">{{ trans('message.Country') }} <span class="color-danger">*</span></label>
                                    <select class="form-control select_country form-select" id="country_id" name="country_id" countryurl="{!! url('/getstatefromcountry') !!}" required>
                                        <option value="">{{ trans('message.Select Country') }}</option>
                                        @foreach ($country as $countrys)
                                            <option value="{{ $countrys->id }}">{{ $countrys->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="color-danger" id="errorlcountry_id"></span>
                                </div>
                                <div>
                                    <label class="field-label" for="state_id">{{ trans('message.State') }}</label>
                                    <select class="form-control state_of_country form-select" id="state_id" name="state_id" stateurl="{!! url('/getcityfromstate') !!}">
                                        <option value="">{{ trans('message.Select State') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="field-label" for="city">{{ trans('message.Town/City') }}</label>
                                    <select class="form-control city_of_state form-select" id="city" name="city">
                                        <option value="">{{ trans('message.Select City') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="field-label" for="address">{{ trans('message.Address') }} <span class="color-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" maxlength="100" placeholder="{{ trans('message.Address') }}" required>{{ old('address') }}</textarea>
                                    <span class="color-danger" id="errorladdress"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Details -->
                        <div class="form-section">
                            <h5 class="form-section-title">{{ trans('message.Vehicle Details') }}</h5>
                            <div class="form-grid">
                                <div>
                                    <label class="field-label" for="vehical_id1">{{ trans('message.Vehicle Type') }} <span class="color-danger">*</span></label>
                                    <select class="form-control select_vehicaltype form-select" id="vehical_id1" name="vehical_id" vehicalurl="{!! url('/vehicle/vehicaltypefrombrand') !!}" required>
                                        <option value="">{{ trans('message.Select Vehicle Type') }}</option>
                                        @if (!empty($vehical_type))
                                            @foreach ($vehical_type as $vehical_types)
                                                <option value="{{ $vehical_types->id }}">{{ $vehical_types->vehicle_type }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="color-danger" id="errorlvehical_id1"></span>
                                </div>
                                <div>
                                    <label class="field-label" for="fueltype1">{{ trans('message.Fuel Type') }} <span class="color-danger">*</span></label>
                                    <select class="form-control select_fueltype form-select" id="fueltype1" name="fueltype" required>
                                        <option value="">{{ trans('message.Select fuel type') }}</option>
                                        @if (!empty($fuel_type))
                                            @foreach ($fuel_type as $fuel_types)
                                                <option value="{{ $fuel_types->id }}">{{ $fuel_types->fuel_type }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="color-danger" id="fuel1"></span>
                                </div>

                                <div>
                                    <label class="field-label" for="vehicabrand1">{{ trans('message.Vehicle Brand') }} <span class="color-danger">*</span></label>
                                    <select class="form-control select_vehicalbrand form-select" id="vehicabrand1" name="vehicabrand" url="{!! url('/vehicle/vehicalmodelfrombrand') !!}">
                                        <option value="">{{ trans('message.Select Brand') }}</option>
                                    </select>
                                    <span class="color-danger"><strong id="errorlvehicabrand1"></strong></span>
                                </div>
                                <div>
                                    <label class="field-label" for="modelname1">{{ trans('message.Model Name') }} <span class="color-danger">*</span></label>
                                    <select class="form-control model_addname form-select" id="modelname1" name="modelname" required>
                                        <option value="">{{ trans('message.Select Model') }}</option>
                                    </select>
                                    <span class="color-danger" id="errorlmodelname1"></span>
                                </div>

                                <div class="full">
                                    <label class="field-label" for="number_plate">{{ trans('message.Number Plate') }} <span class="color-danger">*</span></label>
                                    <input type="text" id="number_plate" name="number_plate" value="{{ old('number_plate') }}" placeholder="{{ trans('message.Enter Number Plate') }}" maxlength="30" class="form-control" required>
                                    <span class="color-danger" id="npe"></span>
                                    @if ($errors->has('price'))
                                        <span class="help-block"><strong>{{ $errors->first('price') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" id="submitButton" class="serviceSubmitButton">{{ trans('message.Submit') }}</button>
                    </form> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm mx-1" data-bs-dismiss="modal">{{ trans('message.Close') }}</button>
                </div>
            </div>

        </div>
    </div>

    <script nonce="{{ $cspNonce }}">
    // Close the booking modal cleanly via the Bootstrap 5 API (removes backdrop too)
    function closeModal() {
        var myModalEl = document.getElementById('myModal');
        if (myModalEl && typeof bootstrap !== 'undefined') {
            bootstrap.Modal.getOrCreateInstance(myModalEl).hide();
        }
    }
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

        var calendarEl = document.getElementById('calendar');
        var esLocale = "en";

        // Returns YYYY-MM-DD for a Date object (local time)
        function toDateOnly(d) {
            return d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2);
        }

        // Opens the booking modal with the chosen date prefilled.
        // Works for both mouse clicks (desktop) and taps (mobile).
        function openBookingModal(dateObj) {
            var todayOnly = toDateOnly(new Date());
            var cellOnly = toDateOnly(dateObj);

            // Prevent booking a date in the past
            if (cellOnly < todayOnly) {
                if (typeof swal !== 'undefined') {
                    swal({
                        title: "{{ trans('message.Invalid Date') }}",
                        text: "{{ trans('message.Please choose today or a future date to book a service.') }}",
                        icon: "warning"
                    });
                }
                return;
            }

            var now = new Date();
            var hour = ('0' + now.getHours()).slice(-2);
            var minute = ('0' + now.getMinutes()).slice(-2);
            var formattedDate = cellOnly + ' ' + hour + ':' + minute;

            $('#s_date').val(formattedDate);

            var myModalEl = document.getElementById('myModal');
            if (myModalEl && typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(myModalEl).show();
            }
        }

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
            locale: 'en',
            height: 'auto',
            dayMaxEventRows: 2,
            navLinks: false,
            selectable: true,
            editable: false,
            events: <?php if (!empty($data1)) {
                        echo $data1;
                    } ?>,

            // Fires on click OR tap of a date cell -> reliable on mobile
            dateClick: function(info) {
                openBookingModal(info.date);
            },

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
                var todayOnly = toDateOnly(new Date());
                var cellOnly = toDateOnly(info.date);

                if (cellOnly < todayOnly) {
                    // Mark past days as unavailable
                    info.el.classList.add('fc-day-past');
                    return;
                }

                // Add a subtle "+ Book" hint (revealed on hover via CSS)
                var frame = info.el.querySelector('.fc-daygrid-day-frame') || info.el;
                var hint = document.createElement('div');
                hint.className = 'fc-book-hint';
                hint.textContent = '+ {{ trans('message.Book') }}';
                frame.appendChild(hint);
            },
        });

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
                    var bookModalEl = document.getElementById('myModal');
                    if (bookModalEl && typeof bootstrap !== 'undefined') {
                        bootstrap.Modal.getOrCreateInstance(bookModalEl).hide();
                    }
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
        document.addEventListener('DOMContentLoaded', function () {
            // Show the modal with the success message (Bootstrap 5 API)
            var successEl = document.getElementById('successModal');
            if (successEl && typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(successEl).show();
            }
        });
    @endif
</script>

</html>