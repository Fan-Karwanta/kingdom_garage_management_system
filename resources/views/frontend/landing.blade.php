<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ getNameSystem() }} &mdash; Book Your Auto Service Online</title>
    <meta name="description" content="Book your vehicle maintenance & repair appointment online with {{ getNameSystem() }}. Pick a date and time that works for you.">

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('garragelogo/favicons_kingdom/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('garragelogo/favicons_kingdom/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('garragelogo/favicons_kingdom/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ URL::asset('garragelogo/favicons_kingdom/apple-touch-icon.png') }}">

    <!-- Google fonts (autofix inspired) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Mulish:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@40,600,0,0" />

    <!-- Bootstrap 5 (for the booking modal + form controls) -->
    <link href="{{ URL::asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ URL::asset('vendors/font-awesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('vendors/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <!-- FullCalendar -->
    <link href="{{ URL::asset('vendors/fullcalendar/lib/main.min.css') }}" rel="stylesheet">
    <!-- Datetimepicker -->
    <link href="{{ URL::asset('vendors/bootstrap-date-time-picker/bootstrap5/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">

    <script nonce="{{ $cspNonce }}" src="{{ asset('vendors/jquery/jquery-3.7.1.min.js') }}"></script>

    <style>
        :root {
            --orange: #EA6B00;
            --orange-dark: #cf5f00;
            --ink: #16181d;
            --ink-soft: #1f232b;
            --slate: #6b7280;
            --line: #e5e7eb;
            --bg: #f5f6f8;
            --white: #ffffff;
        }

        * { box-sizing: border-box; }

        html, body { margin: 0; padding: 0; scroll-behavior: smooth; }

        body {
            font-family: 'Mulish', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: #111827;
            line-height: 1.6;
            overflow-x: hidden;
        }

        #calendar, .fc-view-harness, .fc-scrollgrid { max-width: 100%; }

        h1, h2, h3, h4, .display-font { font-family: 'Chakra Petch', sans-serif; }

        a { text-decoration: none; }

        .container-x {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 22px;
        }

        .material-symbols-rounded { vertical-align: middle; font-size: 1.2em; }

        /* ---------- Buttons ---------- */
        .btn-x {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 12px;
            padding: 12px 22px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Chakra Petch', sans-serif;
            cursor: pointer;
            border: 0;
            transition: transform .12s ease, box-shadow .2s ease, background .2s ease, color .2s ease, border-color .2s ease;
        }
        .btn-primary-x {
            background: var(--orange);
            color: #fff;
            box-shadow: 0 10px 24px rgba(234,107,0,.32);
        }
        .btn-primary-x:hover { background: var(--orange-dark); color: #fff; transform: translateY(-2px); }
        .btn-dark-x { background: var(--ink); color: #fff; }
        .btn-dark-x:hover { background: var(--orange); color: #fff; }
        .btn-ghost-x {
            background: transparent;
            color: #fff;
            border: 1.5px solid rgba(255,255,255,.35);
        }
        .btn-ghost-x:hover { border-color: var(--orange); color: var(--orange); }
        .btn-outline-dark-x {
            background: #fff;
            color: var(--ink);
            border: 1.5px solid var(--line);
        }
        .btn-outline-dark-x:hover { border-color: var(--orange); color: var(--orange); }

        /* ---------- Header ---------- */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(22,24,29,.96);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 14px 22px;
            max-width: 1180px;
            margin: 0 auto;
        }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand img { max-height: 46px; max-width: 190px; width: auto; object-fit: contain; }
        .brand .brand-name { color: #fff; font-family: 'Chakra Petch', sans-serif; font-weight: 700; font-size: 20px; letter-spacing: .3px; }

        .main-nav { display: flex; align-items: center; gap: 26px; }
        .main-nav a {
            color: #d7dae0;
            font-weight: 600;
            font-size: 15px;
            font-family: 'Chakra Petch', sans-serif;
            transition: color .2s ease;
        }
        .main-nav a:hover { color: var(--orange); }

        .header-actions { display: flex; align-items: center; gap: 12px; }

        .nav-toggle { display: none; background: none; border: 0; color: #fff; font-size: 26px; cursor: pointer; }

        /* ---------- Hero ---------- */
        .hero {
            position: relative;
            background:
                linear-gradient(115deg, rgba(16,18,22,.95) 0%, rgba(16,18,22,.78) 45%, rgba(16,18,22,.35) 100%),
                url('{{ URL::asset('frontend/images/hero-bg.jpg') }}') center/cover no-repeat;
            color: #fff;
            overflow: hidden;
        }
        .hero-inner {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            align-items: center;
            gap: 24px;
            padding: 64px 22px 76px;
            max-width: 1180px;
            margin: 0 auto;
        }
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--orange);
            font-weight: 700;
            font-family: 'Chakra Petch', sans-serif;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 13px;
            margin-bottom: 14px;
        }
        .hero h1 {
            font-size: clamp(34px, 5vw, 56px);
            line-height: 1.08;
            margin: 0 0 16px;
            font-weight: 700;
        }
        .hero h1 span { color: var(--orange); }
        .hero p.lead { color: #c9ccd3; font-size: 17px; max-width: 520px; margin: 0 0 28px; }
        .hero-cta { display: flex; flex-wrap: wrap; gap: 14px; }
        .hero-figure { text-align: center; position: relative; }
        .hero-figure img {
            width: 128%;
            max-width: 128%;
            margin-right: -22%;
            height: auto;
            filter: drop-shadow(0 34px 46px rgba(0,0,0,.5));
            animation: floaty 3.5s ease-in-out infinite;
        }
        @keyframes floaty { 0%,100%{ transform: translateY(0);} 50%{ transform: translateY(-14px);} }

        .hero-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 34px;
            margin-top: 34px;
        }
        .hero-stats .stat strong { display: block; font-family: 'Chakra Petch', sans-serif; font-size: 30px; color: #fff; }
        .hero-stats .stat span { color: #9ca3af; font-size: 13.5px; }

        /* ---------- Section shell ---------- */
        .section { padding: 74px 0; }
        .section-head { text-align: center; max-width: 620px; margin: 0 auto 46px; }
        .section-eyebrow {
            color: var(--orange);
            font-weight: 700;
            font-family: 'Chakra Petch', sans-serif;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-size: 13px;
        }
        .section-head h2 { font-size: clamp(26px, 3.4vw, 38px); margin: 8px 0 10px; }
        .section-head p { color: var(--slate); margin: 0; }

        /* ---------- Services ---------- */
        .service-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }
        .service-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 28px 24px;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .service-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(15,23,42,.10); border-color: transparent; }
        .service-card .icon {
            width: 66px; height: 66px;
            display: flex; align-items: center; justify-content: center;
            background: #fff4ec; border-radius: 14px; margin-bottom: 16px;
        }
        .service-card .icon img { width: 42px; height: 42px; object-fit: contain; }
        .service-card h3 { font-size: 20px; margin: 0 0 8px; }
        .service-card p { color: var(--slate); font-size: 14.5px; margin: 0; }

        /* ---------- Booking section ---------- */
        .booking { background: #fff; }
        .booking-wrap {
            display: grid;
            grid-template-columns: .8fr 1.2fr;
            gap: 34px;
            align-items: start;
        }
        .booking-side h2 { font-size: clamp(26px,3.2vw,36px); margin: 8px 0 14px; }
        .booking-side p { color: var(--slate); }
        .booking-points { list-style: none; padding: 0; margin: 22px 0 0; }
        .booking-points li { display: flex; gap: 12px; align-items: flex-start; margin-bottom: 14px; font-weight: 600; color: #374151; }
        .booking-points li .material-symbols-rounded { color: var(--orange); }
        .booking-card-outer {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(15,23,42,.08);
            padding: 22px;
        }
        .booking-hint {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff7ed; color: #9a3412; border: 1px solid #ffedd5;
            border-radius: 10px; padding: 8px 14px; font-size: 13px; font-weight: 600; margin-bottom: 16px;
        }

        /* ---------- FullCalendar theming ---------- */
        #calendar { max-width: 100%; }
        .fc { font-size: 14px; }
        .fc .fc-toolbar-title { font-size: 19px; font-weight: 700; color: #111827; font-family: 'Chakra Petch', sans-serif; }
        .fc .fc-button-primary { background: var(--ink); border-color: var(--ink); box-shadow: none; text-transform: capitalize; font-weight: 600; padding: 6px 12px; border-radius: 10px; }
        .fc .fc-button-primary:not(:disabled):hover { background: var(--orange); border-color: var(--orange); }
        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background: var(--orange); border-color: var(--orange); box-shadow: none; }
        .fc .fc-button-primary:focus, .fc .fc-button-primary:not(:disabled).fc-button-active:focus { box-shadow: 0 0 0 3px rgba(234,107,0,.25); }
        .fc-theme-standard td, .fc-theme-standard th { border-color: #eef0f3; }
        .fc-theme-standard .fc-scrollgrid { border-color: #eef0f3; border-radius: 12px; overflow: hidden; }
        .fc .fc-col-header-cell-cushion { color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: .4px; padding: 8px 4px; }
        .fc .fc-daygrid-day-number { color: #374151; font-weight: 500; }
        .fc .fc-day-today { background: rgba(234,107,0,.06) !important; }
        .fc .fc-daygrid-day { cursor: pointer; transition: background .15s ease; }
        .fc .fc-daygrid-day:hover:not(.fc-day-past) { background: rgba(234,107,0,.08); }
        .fc .fc-day-past { background: #fafafa; cursor: not-allowed; }
        .fc .fc-day-past .fc-daygrid-day-number { color: #c3c8d0; }
        .fc-daygrid-day-frame { position: relative; min-height: 70px; }
        .fc-book-hint { position: absolute; left: 6px; bottom: 6px; right: 6px; background: var(--orange); color: #fff; border: none; border-radius: 8px; font-size: 11px; font-weight: 600; padding: 3px 6px; opacity: 0; pointer-events: none; transition: opacity .15s ease; }
        .fc-daygrid-day:hover:not(.fc-day-past) .fc-book-hint { opacity: 1; }

        /* ---------- Steps ---------- */
        .steps { background: var(--ink); color: #fff; }
        .steps-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; }
        .step { text-align: center; padding: 10px; }
        .step .num { width: 58px; height: 58px; border-radius: 50%; background: var(--orange); color: #fff; font-family: 'Chakra Petch', sans-serif; font-weight: 700; font-size: 22px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
        .step h3 { font-size: 19px; margin: 0 0 6px; }
        .step p { color: #a9adb6; font-size: 14.5px; margin: 0; }
        .steps .section-head h2 { color: #fff; }
        .steps .section-head p { color: #a9adb6; }

        /* ---------- Footer ---------- */
        .site-footer { background: #0f1216; color: #c9ccd3; padding: 56px 0 26px; }
        .footer-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr; gap: 34px; }
        .site-footer h4 { color: #fff; font-size: 17px; margin: 0 0 16px; }
        .site-footer img.flogo { max-height: 48px; margin-bottom: 14px; }
        .footer-contact li { list-style: none; display: flex; gap: 10px; align-items: center; margin-bottom: 12px; }
        .footer-contact { padding: 0; margin: 0; }
        .footer-contact .material-symbols-rounded { color: var(--orange); }
        .footer-hours li { list-style: none; display: flex; justify-content: space-between; border-bottom: 1px dashed rgba(255,255,255,.12); padding: 8px 0; }
        .footer-hours { padding: 0; margin: 0; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,.1); margin-top: 40px; padding-top: 22px; text-align: center; font-size: 13.5px; color: #8b8f98; }
        .footer-bottom a { color: var(--orange); font-weight: 600; }

        /* ---------- Modal / form (matches existing booking form) ---------- */
        #myModal .modal-content { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 30px 70px rgba(15,23,42,.25); }
        #myModal .modal-header { background: var(--ink); color: #fff; border: none; padding: 20px 26px; }
        #myModal .modal-title { font-weight: 700; font-size: 20px; font-family: 'Chakra Petch', sans-serif; }
        #myModal .modal-header .btn-close { filter: invert(1) grayscale(1) brightness(2); opacity: .8; }
        #myModal .modal-body { padding: 24px 26px; }
        #myModal .modal-footer { border-top: 1px solid #eef0f3; padding: 16px 26px; }
        .form-section { margin-bottom: 22px; }
        .form-section-title { display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--orange); margin: 0 0 14px; font-family: 'Chakra Petch', sans-serif; }
        .form-section-title::after { content: ""; flex: 1; height: 1px; background: #eef0f3; }
        .form-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 16px; }
        .form-grid .full { grid-column: 1 / -1; }
        .field-label { display: block; font-size: 12.5px; font-weight: 600; color: #6b7280; margin: 0 0 6px 2px; }
        #myModal .form-control, #myModal .form-select { width: 100% !important; min-height: 46px; padding: 10px 14px; font-size: 14.5px; color: #111827; background-color: #fff; border: 1px solid #e5e7eb; border-radius: 12px; outline: none; transition: border-color .2s ease, box-shadow .2s ease; -webkit-appearance: none; }
        #myModal textarea.form-control { min-height: 80px; resize: vertical; }
        #myModal .form-control:focus, #myModal .form-select:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(234,107,0,.12); }
        .user-type-toggle { display: flex; gap: 10px; flex-wrap: wrap; }
        .user-type-toggle label.radio-inline { flex: 1; min-width: 130px; display: flex; align-items: center; gap: 8px; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px 14px; font-size: 14px; font-weight: 500; color: #374151; cursor: pointer; margin: 0; transition: border-color .2s ease, background .2s ease; }
        .user-type-toggle label.radio-inline:hover { border-color: var(--orange); }
        .user-type-toggle input[type="radio"] { accent-color: var(--orange); width: 16px; height: 16px; margin: 0; }
        .color-danger { color: #dc2626; font-size: 12px; }
        .serviceSubmitButton { width: 100%; height: 50px; background: var(--ink) !important; color: #fff !important; font-size: 15px; font-weight: 700; border: none !important; border-radius: 12px !important; box-shadow: 0 6px 16px rgba(22,24,29,.2); transition: background .2s ease; font-family: 'Chakra Petch', sans-serif; }
        .serviceSubmitButton:hover { background: var(--orange) !important; }
        #myModal.modal.fade .modal-dialog, #successModal.modal.fade .modal-dialog { transition: transform .2s ease-out; transform: translateY(-40px); }
        #myModal.modal.show .modal-dialog, #successModal.modal.show .modal-dialog { transform: none !important; }
        #myModal.modal.fade, #successModal.modal.fade { opacity: 1 !important; }
        .modal-backdrop.show { opacity: .5 !important; }

        /* ---------- Responsive ---------- */
        @media (max-width: 1024px) {
            .main-nav { display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--ink); flex-direction: column; padding: 18px 22px; gap: 16px; border-bottom: 1px solid rgba(255,255,255,.1); }
            .main-nav.active { display: flex; }
            .nav-toggle { display: block; }
        }

        @media (max-width: 960px) {
            .hero-inner { grid-template-columns: 1fr; text-align: center; gap: 8px; }
            .hero-eyebrow { justify-content: center; }
            .hero p.lead { margin-left: auto; margin-right: auto; }
            .hero-cta { justify-content: center; }
            .hero-stats { justify-content: center; }
            /* Show the car above the text on tablet, reset the desktop bleed */
            .hero-figure { order: -1; margin-bottom: 6px; }
            .hero-figure img { width: 100%; max-width: 560px; margin-right: 0; animation: none; }
            .booking-wrap { grid-template-columns: 1fr; }
            .service-grid { grid-template-columns: repeat(2, 1fr); }
            .steps-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 760px) {
            .header-actions .btn-label-hide { display: none; }
            .header-actions .btn-x { padding: 10px 14px; }
            .brand img { max-height: 40px; max-width: 150px; }
            .section { padding: 52px 0; }
            .service-grid { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
            .hero-inner { padding: 40px 18px 52px; }
            .hero-figure img { max-width: 100%; }
            .hero-stats { gap: 22px; }
            .hero-stats .stat strong { font-size: 24px; }
            .container-x { padding: 0 16px; }

            /* Booking card + calendar mobile optimisation */
            .booking-card-outer { padding: 14px; border-radius: 16px; }
            .booking-hint { font-size: 12px; padding: 7px 10px; width: 100%; }

            .fc { font-size: 12.5px; }
            .fc .fc-toolbar.fc-header-toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                margin-bottom: 12px;
            }
            .fc .fc-toolbar-chunk { display: flex; justify-content: center; }
            .fc .fc-toolbar-title { font-size: 16px; text-align: center; }
            .fc .fc-button-group { width: 100%; }
            .fc .fc-button { flex: 1; }
            .fc .fc-button-primary { padding: 7px 6px; font-size: 12px; border-radius: 8px; }
            .fc .fc-col-header-cell-cushion { font-size: 9.5px; letter-spacing: .2px; padding: 6px 1px; }
            .fc .fc-daygrid-day-number { font-size: 12px; padding: 3px 5px; }
            .fc-daygrid-day-frame { min-height: 44px; }
            .fc-book-hint { display: none; }
        }

        @media (max-width: 400px) {
            .fc .fc-toolbar-title { font-size: 15px; }
            .fc .fc-col-header-cell-cushion { font-size: 8.5px; }
            .fc-daygrid-day-frame { min-height: 38px; }
        }
    </style>
</head>

<body>

    <!-- ===== HEADER ===== -->
    <header class="site-header">
        <div class="header-inner">
            <a href="#top" class="brand">
                @php $logoFile = getLogoSystem(); @endphp
                @if (!empty($logoFile))
                    <img src="{{ URL::asset('/public/general_setting/' . $logoFile) }}" alt="{{ getNameSystem() }}">
                @else
                    <span class="brand-name">{{ getNameSystem() }}</span>
                @endif
            </a>

            <nav class="main-nav" id="mainNav">
                <a href="#top">Home</a>
                <a href="#services">Services</a>
                <a href="#booking">Book</a>
                <a href="#how">How It Works</a>
                <a href="#contact">Contact</a>
            </nav>

            <div class="header-actions">
                <a href="#booking" class="btn-x btn-primary-x"><span class="material-symbols-rounded">calendar_add_on</span><span class="btn-label-hide">Book Now</span></a>
                <a href="{{ route('login') }}" class="btn-x btn-ghost-x"><span class="material-symbols-rounded">login</span>Login</a>
                <button class="nav-toggle" id="navToggle" aria-label="Toggle menu"><span class="material-symbols-rounded">menu</span></button>
            </div>
        </div>
    </header>

    <!-- ===== HERO ===== -->
    <section class="hero" id="top">
        <div class="hero-inner">
            <div class="hero-content">
                <span class="hero-eyebrow"><span class="material-symbols-rounded">verified</span>Trusted Auto Care Specialists</span>
                <h1>Book Your <span>Vehicle Service</span> In Just A Few Clicks</h1>
                <p class="lead">Welcome to {{ getNameSystem() }}. Choose a date and time that suits you and let our expert mechanics take care of the rest &mdash; no phone calls, no queues.</p>
                <div class="hero-cta">
                    <a href="#booking" class="btn-x btn-primary-x"><span class="material-symbols-rounded">event_available</span>Book an Appointment</a>
                    <a href="#services" class="btn-x btn-ghost-x">Our Services<span class="material-symbols-rounded">arrow_forward</span></a>
                </div>
                <div class="hero-stats">
                    <div class="stat"><strong>15+</strong><span>Years of Experience</span></div>
                    <div class="stat"><strong>10K+</strong><span>Happy Customers</span></div>
                    <div class="stat"><strong>24/7</strong><span>Online Booking</span></div>
                </div>
            </div>
            <figure class="hero-figure">
                <img src="{{ URL::asset('frontend/images/hero-banner.png') }}" alt="Vehicle service">
            </figure>
        </div>
    </section>

    <!-- ===== SERVICES ===== -->
    <section class="section" id="services">
        <div class="container-x">
            <div class="section-head">
                <span class="section-eyebrow">Our Services</span>
                <h2>Complete Care For Your Vehicle</h2>
                <p>From routine maintenance to major repairs, our certified team keeps your vehicle running at its best.</p>
            </div>
            <div class="service-grid">
                <div class="service-card">
                    <div class="icon"><img src="{{ URL::asset('frontend/images/services-1.png') }}" alt=""></div>
                    <h3>Engine Repair</h3>
                    <p>Diagnostics and repair for smooth, reliable engine performance.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><img src="{{ URL::asset('frontend/images/services-2.png') }}" alt=""></div>
                    <h3>Brake Service</h3>
                    <p>Inspection and replacement to keep your braking safe and responsive.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><img src="{{ URL::asset('frontend/images/services-3.png') }}" alt=""></div>
                    <h3>Tyre & Wheels</h3>
                    <p>Tyre fitting, balancing and alignment for a comfortable ride.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><img src="{{ URL::asset('frontend/images/services-4.png') }}" alt=""></div>
                    <h3>Battery Service</h3>
                    <p>Battery testing and replacement so you never get stranded.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><img src="{{ URL::asset('frontend/images/services-6.png') }}" alt=""></div>
                    <h3>Steering & Suspension</h3>
                    <p>Precise handling and a smoother drive on every road.</p>
                </div>
                <div class="service-card">
                    <div class="icon"><img src="{{ URL::asset('frontend/images/services-1.png') }}" alt=""></div>
                    <h3>General Maintenance</h3>
                    <p>Scheduled servicing to extend the life of your vehicle.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== BOOKING ===== -->
    <section class="section booking" id="booking">
        <div class="container-x">
            <div class="section-head">
                <span class="section-eyebrow">Online Booking</span>
                <h2>Pick A Date That Works For You</h2>
                <p>Tap any available date on the calendar to schedule your service appointment.</p>
            </div>
            <div class="booking-wrap">
                <div class="booking-side">
                    <span class="section-eyebrow">Why Book Online?</span>
                    <h2>Fast, Easy &amp; Convenient</h2>
                    <p>Skip the phone calls. Reserve your slot in under a minute and get instant confirmation of your appointment.</p>
                    <ul class="booking-points">
                        <li><span class="material-symbols-rounded">schedule</span>Choose your preferred date &amp; time</li>
                        <li><span class="material-symbols-rounded">directions_car</span>Register your vehicle details once</li>
                        <li><span class="material-symbols-rounded">mail</span>Receive email confirmation instantly</li>
                        <li><span class="material-symbols-rounded">support_agent</span>Existing customers can log in to manage bookings</li>
                    </ul>
                    <a href="#" class="btn-x btn-dark-x" style="margin-top:24px;" data-bs-toggle="modal" data-bs-target="#myModal"><span class="material-symbols-rounded">calendar_add_on</span>Book Now</a>
                </div>

                <div class="booking-card-outer">
                    <div class="booking-hint"><span class="material-symbols-rounded">touch_app</span>{{ trans('message.Tap any available date to book a service') }}</div>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section class="section steps" id="how">
        <div class="container-x">
            <div class="section-head">
                <span class="section-eyebrow">How It Works</span>
                <h2>Book In Three Simple Steps</h2>
            </div>
            <div class="steps-grid">
                <div class="step">
                    <div class="num">1</div>
                    <h3>Choose A Date</h3>
                    <p>Select an available day and time from our live calendar.</p>
                </div>
                <div class="step">
                    <div class="num">2</div>
                    <h3>Enter Your Details</h3>
                    <p>Tell us about you and your vehicle so we're ready when you arrive.</p>
                </div>
                <div class="step">
                    <div class="num">3</div>
                    <h3>Get Confirmation</h3>
                    <p>Receive instant confirmation and bring your vehicle in on the day.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="site-footer" id="contact">
        <div class="container-x">
            <div class="footer-grid">
                <div>
                    @if (!empty($logoFile))
                        <img class="flogo" src="{{ URL::asset('/public/general_setting/' . $logoFile) }}" alt="{{ getNameSystem() }}">
                    @else
                        <h4>{{ getNameSystem() }}</h4>
                    @endif
                    <p>Your trusted partner for professional vehicle maintenance and repair. Book online anytime and drive with confidence.</p>
                    <a href="{{ route('login') }}" class="btn-x btn-primary-x" style="margin-top:8px;"><span class="material-symbols-rounded">login</span>Staff / Customer Login</a>
                </div>
                <div>
                    <h4>Contact Info</h4>
                    <ul class="footer-contact">
                        <li><span class="material-symbols-rounded">location_on</span><span>Visit us at our garage</span></li>
                        <li><span class="material-symbols-rounded">call</span><span>Call to learn more</span></li>
                        <li><span class="material-symbols-rounded">mail</span><span>Reach out via email</span></li>
                    </ul>
                </div>
                <div>
                    <h4>Opening Hours</h4>
                    <ul class="footer-hours">
                        <li><span>Mon &ndash; Fri</span><span>8:00 &ndash; 18:00</span></li>
                        <li><span>Saturday</span><span>9:00 &ndash; 15:00</span></li>
                        <li><span>Sunday</span><span>Closed</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} {{ getNameSystem() }}. All Rights Reserved. &nbsp;|&nbsp; <a href="{{ route('login') }}">Internal Login</a>
            </div>
        </div>
    </footer>

    <!-- ===== BOOKING MODAL (same form as existing booking page) ===== -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
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

    <!-- Scripts -->
    <script nonce="{{ $cspNonce }}" src="{{ asset('vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/fullcalendar/lib/main.js') }}"></script>
    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/moment/moment.min.js') }}"></script>
    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/bootstrap-date-time-picker/bootstrap5/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('/vendors/bootstrap-date-time-picker/bootstrap5/js/locales/bootstrap-datetimepicker.en.js') }}"></script>

    <?php
    $service_data_array = [];
    if (!empty($holiday)) {
        foreach ($holiday as $holidays) {
            $n_start_date = date('Y-m-d', strtotime($holidays->date));
            $n_end_date = date('Y-m-d', strtotime($holidays->date));
            $service_data_array[] = ['title' => substr($holidays->title, 0, 10), 'title1' => $holidays->title, 'dates' => date(getDateFormat(), strtotime($holidays->date)), 'description' => $holidays->description, 'customer' => 'Holiday', 'vehicle' => '', 'plateno' => '', 'start' => $n_start_date, 'end' => $n_end_date, 'color' => '#ee7f25'];
        }
    }
    $data1 = !empty($service_data_array) ? json_encode($service_data_array) : json_encode('0');
    ?>

    <script nonce="{{ $cspNonce }}">
        // Mobile nav toggle
        (function () {
            var t = document.getElementById('navToggle');
            var n = document.getElementById('mainNav');
            if (t && n) {
                t.addEventListener('click', function () { n.classList.toggle('active'); });
                n.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { n.classList.remove('active'); }); });
            }
        })();

        function closeModal() {
            var myModalEl = document.getElementById('myModal');
            if (myModalEl && typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(myModalEl).hide();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var today = "{{ trans('message.today') }}";
            var dayGridMonth = "{{ trans('message.dayGridMonth') }}";
            var timeGridWeek = "{{ trans('message.timeGridWeek') }}";
            var timeGridDay = "{{ trans('message.timeGridDay') }}";

            var calendarEl = document.getElementById('calendar');

            function toDateOnly(d) {
                return d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2);
            }

            function openBookingModal(dateObj) {
                var todayOnly = toDateOnly(new Date());
                var cellOnly = toDateOnly(dateObj);

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

            if (calendarEl && typeof FullCalendar !== 'undefined') {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: "prev,today,next",
                        center: "title",
                        right: "dayGridMonth,timeGridWeek,timeGridDay"
                    },
                    buttonText: { month: dayGridMonth, day: timeGridDay, week: timeGridWeek, today: today },
                    initialDate: new Date(),
                    locale: 'en',
                    height: 'auto',
                    dayMaxEventRows: 2,
                    navLinks: false,
                    selectable: true,
                    editable: false,
                    events: <?php echo $data1; ?>,
                    dateClick: function (info) { openBookingModal(info.date); },
                    eventDidMount: function (info) {
                        var title1 = !info.event.extendedProps.title1 ? "" : info.event.extendedProps.title1 + " | ";
                        var title2 = !info.event.extendedProps.dates ? "" : info.event.extendedProps.dates + "<br>";
                        var title3 = !info.event.extendedProps.customer ? "" : info.event.extendedProps.customer + " | ";
                        var title4 = !info.event.extendedProps.plateno ? "" : info.event.extendedProps.plateno + " | ";
                        var title5 = !info.event.extendedProps.vehicle ? "" : info.event.extendedProps.vehicle;
                        $(info.el).tooltip({ title: title1 + title2 + title3 + title4 + title5, placement: "left", trigger: "hover", html: true, container: "body" });
                    },
                    dayCellDidMount: function (info) {
                        var todayOnly = toDateOnly(new Date());
                        var cellOnly = toDateOnly(info.date);
                        if (cellOnly < todayOnly) { info.el.classList.add('fc-day-past'); return; }
                        var frame = info.el.querySelector('.fc-daygrid-day-frame') || info.el;
                        var hint = document.createElement('div');
                        hint.className = 'fc-book-hint';
                        hint.textContent = '+ {{ trans('message.Book') }}';
                        frame.appendChild(hint);
                    }
                });
                calendar.render();
            }
        });

        $(document).ready(function () {
            var oldUserRadio = document.getElementById("old");
            if (oldUserRadio) {
                oldUserRadio.addEventListener("change", function () {
                    swal({
                        title: "Please login for this existing user",
                        text: "and add or book service in dashboard.",
                        icon: 'info',
                        cancelButtonColor: '#C1C1C1',
                        buttons: ["Cancel", "Login"],
                        dangerMode: false,
                    }).then(function (willLogin) {
                        if (willLogin) {
                            closeModal();
                            window.location.href = "{!! route('login') !!}";
                        } else {
                            oldUserRadio.checked = false;
                        }
                    });
                });
            }

            $('.select_vehicaltype').change(function () {
                var vehical_id = $(this).val();
                var url = $(this).attr('vehicalurl');
                $.ajax({ type: 'GET', url: url, data: { vehical_id: vehical_id }, success: function (response) { $('.select_vehicalbrand').html(response); $('.select_vehicalbrand').trigger('change'); } });
            });

            $('.select_vehicalbrand').change(function () {
                var id = $(this).val();
                var url = $(this).attr('url');
                $.ajax({ type: 'GET', url: url, data: { id: id }, success: function (response) { $('.model_addname').html(response); } });
            });

            $('.select_country').change(function () {
                var countryid = $(this).val();
                var url = $(this).attr('countryurl');
                $.ajax({ type: 'GET', url: url, data: { countryid: countryid }, success: function (response) { $('.state_of_country').html(response); } });
            });

            $('body').on('change', '.state_of_country', function () {
                var stateid = $(this).val();
                var url = $(this).attr('stateurl');
                $.ajax({ type: 'GET', url: url, data: { stateid: stateid }, success: function (response) { $('.city_of_state').html(response); } });
            });

            $('.datepicker').datetimepicker({
                format: "<?php echo getDateTimepicker(); ?>",
                todayBtn: true,
                autoclose: 1,
                startDate: new Date(),
            });
        });

        @if(session('message'))
            document.addEventListener('DOMContentLoaded', function () {
                var successEl = document.getElementById('successModal');
                if (successEl && typeof bootstrap !== 'undefined') {
                    bootstrap.Modal.getOrCreateInstance(successEl).show();
                }
            });
        @endif
    </script>

</body>
</html>
