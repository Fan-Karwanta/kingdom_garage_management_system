<!DOCTYPE html>

<html lang="en">

<head>
  <!-- <meta content="text/html; charset=UTF-8"> -->
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ URL::asset('fevicol.png') }}" type="image/gif" sizes="16x16">
  <title>{{ getNameSystem() }}</title>

  <!-- Bootstrap -->
  <link href="{{ URL::asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="{{ URL::asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
  <!-- NProgress -->
  <link href="{{ URL::asset('vendors/nprogress/nprogress.css') }}" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  {{-- <link href="{{ URL::asset('vendors/bootstrap-daterangepicker/daterangepicker.css') }} "
  rel="stylesheet"> --}}

  <!-- Custom Theme Style -->
  <link href="{{ URL::asset('build/css/custom.min.css') }} " rel="stylesheet">
  <!-- Own Theme Style -->
  <link href="{{ URL::asset('build/css/own.css') }} " rel="stylesheet">
  <link href="{{ URL::asset('build/css/roboto.css') }} " rel="stylesheet">

  <!-- sweetalert -->
  {{-- <link href="{{ URL::asset('vendors/sweetalert/sweetalert.css') }}"
  rel="stylesheet"
  type="text/css"> --}}

  <!-- Custom Theme Scripts -->
  <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
  <script nonce="{{ $cspNonce }}" src="{{ URL::asset('build/js/custom.min.js') }}" defer="defer"></script>
  <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/sweetalert/dist/sweetalert.min.js') }}"></script>
  <script nonce="{{ $cspNonce }}">
    $(document).ready(function() {
      $(".input").click(function() {
        $('.login-demo label').addClass("active");
        $('.login-password label').addClass("active");
      });
    });

    //
  </script>
  <script nonce="{{ $cspNonce }}">
    document.addEventListener('DOMContentLoaded', function () {
        const alert = document.getElementById('package-error');
        if (alert) {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 2000); // 2 seconds
        }
    });
</script>
  <style>
    /* ============ Modern Login Revamp ============ */
    * {
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    body.school-login-page {
      font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
      background: #ececf0 !important;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px 56px;
    }

    .login-shell {
      width: 100%;
      max-width: 1040px;
      background: #ffffff;
      border-radius: 28px;
      box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14);
      display: flex;
      overflow: hidden;
      min-height: 640px;
    }

    /* ---------- Left visual panel ---------- */
    .login-visual {
      flex: 0 0 46%;
      position: relative;
      margin: 20px;
      border-radius: 22px;
      overflow: hidden;
      background:
        linear-gradient(200deg, rgba(10, 12, 18, 0.82) 0%, rgba(20, 24, 34, 0.35) 42%, rgba(234, 107, 0, 0.38) 100%),
        url("{{ URL::asset('public/garragelogo/bg_login_104.png') }}") center / cover no-repeat;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 40px 36px;
      min-height: 600px;
    }

    .login-visual h2 {
      color: #ffffff;
      font-size: 40px;
      line-height: 1.15;
      font-weight: 700;
      letter-spacing: -0.5px;
      margin: 0;
      text-shadow: 0 2px 12px rgba(0, 0, 0, 0.35);
    }

    .login-visual .visual-tagline {
      color: rgba(255, 255, 255, 0.92);
      font-size: 15px;
      line-height: 1.6;
      margin: 0;
      text-shadow: 0 1px 8px rgba(0, 0, 0, 0.4);
    }

    /* ---------- Right form panel ---------- */
    .login-panel {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 48px 56px;
    }

    .brand-logo img {
      max-height: 120px;
      max-width: 320px;
      width: auto;
      height: auto;
      object-fit: contain;
      margin-bottom: 18px;
    }

    @media (max-width: 480px) {
      .brand-logo img {
        max-height: 80px;
        max-width: 220px;
      }
    }

    .login-heading {
      font-size: 30px;
      font-weight: 700;
      color: #111827;
      margin: 0 0 6px;
      letter-spacing: -0.4px;
    }

    .login-subheading {
      color: #9ca3af;
      font-size: 14.5px;
      line-height: 1.55;
      margin: 0 0 28px;
    }

    .field-label {
      display: block;
      font-size: 12.5px;
      font-weight: 500;
      color: #6b7280;
      margin: 0 0 6px 2px;
    }

    .login-demo,
    .login-password {
      margin: 0 0 16px;
    }

    .login-demo label,
    .login-password label {
      display: block;
      font-size: 12.5px;
      font-weight: 500;
      color: #6b7280;
      margin: 0 0 6px 2px;
    }

    input.input,
    #mobile_no,
    #otp {
      width: 100%;
      height: 48px;
      padding: 12px 16px;
      font-size: 14.5px;
      color: #111827;
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      outline: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
      -webkit-appearance: none;
    }

    input.input:focus,
    #mobile_no:focus,
    #otp:focus {
      border-color: #EA6B00;
      box-shadow: 0 0 0 3px rgba(234, 107, 0, 0.12);
    }

    input.input::placeholder {
      color: #c3c8d0;
    }

    .login-remember {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 4px 0 20px;
    }

    .login-remember label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13.5px;
      color: #4b5563;
      font-weight: 400;
      margin: 0;
      cursor: pointer;
    }

    .login-remember input[type="checkbox"] {
      width: 16px;
      height: 16px;
      accent-color: #EA6B00;
      margin: 0;
      cursor: pointer;
    }

    .forgot_pwd_scl,
    .forgot_pwd_scl:hover,
    .forgot_pwd_scl:focus {
      font-size: 13.5px;
      color: #EA6B00;
      font-weight: 500;
      text-decoration: none;
      margin: 0;
    }

    .forgot_pwd_scl:hover {
      text-decoration: underline;
    }

    .form-row-flex {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 4px 0 20px;
    }

    .form-row-flex .login-remember {
      margin: 0;
    }

    .login-submit {
      margin: 0 0 8px;
    }

    .login-submit .button.button-primary,
    .otpButton {
      width: 100%;
      height: 50px;
      background: #16181d;
      color: #ffffff;
      font-size: 15px;
      font-weight: 600;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: background 0.2s ease, transform 0.1s ease, box-shadow 0.2s ease;
      box-shadow: 0 6px 16px rgba(22, 24, 29, 0.22);
    }

    .login-submit .button.button-primary:hover,
    .otpButton:hover {
      background: #EA6B00;
      box-shadow: 0 8px 20px rgba(234, 107, 0, 0.3);
    }

    .login-submit .button.button-primary:active,
    .otpButton:active {
      transform: translateY(1px);
    }

    /* ---------- Divider + Book appointment ---------- */
    .login-divider {
      display: flex;
      align-items: center;
      gap: 14px;
      margin: 22px 0;
      color: #9ca3af;
      font-size: 13px;
    }

    .login-divider::before,
    .login-divider::after {
      content: "";
      flex: 1;
      height: 1px;
      background: #e5e7eb;
    }

    a.bookService {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      height: 50px;
      background: #ffffff;
      color: #111827 !important;
      font-size: 14.5px;
      font-weight: 600;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      text-decoration: none !important;
      transition: border-color 0.2s ease, background 0.2s ease, color 0.2s ease;
    }

    a.bookService:hover {
      border-color: #EA6B00;
      color: #EA6B00 !important;
      background: rgba(234, 107, 0, 0.05);
    }

    a.bookService .fa {
      color: #EA6B00;
    }

    /* ---------- Password toggle ---------- */
    .password-container {
      position: relative;
    }

    .login-password {
      position: relative;
    }

    .login-password .input {
      padding-right: 44px;
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      margin-top: 11px; /* shift down by half label height so it centers inside the input */
      cursor: pointer;
      color: #9ca3af;
      font-size: 15px;
      user-select: none;
      line-height: 1;
    }

    .password-toggle:hover {
      color: #EA6B00;
    }

    @media (max-width: 480px) {
      .password-toggle {
        right: 12px;
        font-size: 14px;
      }
    }

    /* ---------- Tabs (email / mobile login) ---------- */
    .login-tabs {
      display: flex;
      margin-bottom: 26px;
      border-radius: 12px;
      background: #f3f4f6;
      padding: 4px;
      overflow: hidden;
    }

    .tab-button {
      flex: 1;
      padding: 10px 14px;
      background: transparent;
      border: none;
      border-radius: 9px;
      cursor: pointer;
      font-size: 13.5px;
      font-weight: 600;
      transition: all 0.25s ease;
      color: #6b7280;
    }

    .tab-button.active {
      background: #ffffff;
      color: #111827;
      box-shadow: 0 2px 6px rgba(15, 23, 42, 0.1);
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    /* ---------- OTP / mobile login ---------- */
    .otp-container {
      text-align: center;
    }

    .countdown-timer {
      background: #fff7ed;
      color: #9a3412;
      padding: 10px;
      border-radius: 10px;
      margin: 12px 0;
      font-weight: 600;
      font-size: 12.5px;
      border: 1px solid #ffedd5;
    }

    .resend-link {
      color: #EA6B00;
      cursor: pointer;
      text-decoration: underline;
      font-weight: 600;
    }

    .resend-link:hover {
      color: #c25800;
    }

    .resend-link.disabled {
      color: #d1d5db;
      cursor: not-allowed;
      text-decoration: none;
    }

    .success-message {
      background: #ecfdf5;
      color: #065f46;
      padding: 12px 14px;
      border-radius: 10px;
      margin-bottom: 16px;
      border: 1px solid #d1fae5;
      font-size: 13px;
    }

    .error-message {
      color: #dc2626;
      font-size: 12px;
      margin-top: 4px;
      display: block;
    }

    .help-block.text-danger {
      color: #dc2626;
      font-size: 12.5px;
      margin-top: 6px;
      display: block;
      width: auto !important;
    }

    .mobile-login-section {
      margin-top: 8px;
    }

    .back-button {
      background: #f3f4f6;
      color: #4b5563;
      padding: 10px 18px;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      cursor: pointer;
      margin-top: 10px;
      font-size: 13px;
      font-weight: 500;
      transition: background 0.2s ease;
    }

    .back-button:hover {
      background: #e5e7eb;
    }

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
      color: #EA6B00;
    }

    .footer-line a:hover {
      text-decoration: underline;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 900px) {
      .login-visual {
        display: none;
      }

      .login-panel {
        padding: 44px 36px;
      }
    }

    @media (max-width: 480px) {
      body.school-login-page {
        padding: 12px 10px 48px;
      }

      .login-shell {
        border-radius: 20px;
        min-height: auto;
      }

      .login-panel {
        padding: 36px 24px;
      }

      .login-heading {
        font-size: 25px;
      }
    }

    /* ---------- Overrides for legacy own.css ---------- */
    .content-form-login-page-school-plugin.md-form form p.login-demo,
    .content-form-login-page-school-plugin.md-form form p.login-password,
    .content-form-login-page-school-plugin.md-form form p.login-remember,
    .content-form-login-page-school-plugin .login-demo,
    .content-form-login-page-school-plugin .login-password {
      display: block !important;
      width: 100% !important;
      max-width: none !important;
      margin: 0 0 16px !important;
      position: relative;
    }

    .content-form-login-page-school-plugin .login-demo label,
    .content-form-login-page-school-plugin .login-password label {
      position: static !important;
      transform: none !important;
      display: block !important;
      font-size: 12.5px !important;
      font-weight: 500 !important;
      color: #6b7280 !important;
      margin: 0 0 6px 2px !important;
    }

    .md-form label.active {
      transform: none !important;
    }

    .content-form-login-page-school-plugin .login-demo #user_login,
    .content-form-login-page-school-plugin .login-demo #email_reset,
    .content-form-login-page-school-plugin .login-password #user_pass,
    .content-form-login-page-school-plugin input.input {
      width: 100% !important;
      height: 48px !important;
      padding: 12px 16px !important;
      font-size: 14.5px !important;
      color: #111827 !important;
      background: #ffffff !important;
      border: 1px solid #e5e7eb !important;
      border-radius: 12px !important;
      letter-spacing: normal !important;
      -webkit-appearance: none;
    }

    .content-form-login-page-school-plugin .login-password #user_pass {
      padding-right: 44px !important;
    }

    .content-form-login-page-school-plugin .login-demo #user_login:focus,
    .content-form-login-page-school-plugin .login-demo #email_reset:focus,
    .content-form-login-page-school-plugin .login-password #user_pass:focus,
    .content-form-login-page-school-plugin input.input:focus {
      border-color: #EA6B00 !important;
      box-shadow: 0 0 0 3px rgba(234, 107, 0, 0.12) !important;
      outline: 0 !important;
    }

    .content-form-login-page-school-plugin .login-submit input {
      width: 100% !important;
      height: 50px !important;
      padding: 0 !important;
      background: #16181d !important;
      border-radius: 12px !important;
      font-size: 15px !important;
      text-transform: none !important;
    }

    .content-form-login-page-school-plugin .login-submit input:hover {
      background: #EA6B00 !important;
    }

    .content-form-login-page-school-plugin .login-remember label {
      position: static !important;
      transform: none !important;
      color: #4b5563 !important;
      font-size: 13.5px !important;
    }

    .content-form-login-page-school-plugin .forgot_pwd_scl,
    .content-form-login-page-school-plugin .forgot_pwd_scl:hover {
      margin-left: 0 !important;
      color: #EA6B00 !important;
    }
  </style>
</head>

<script nonce="{{ $cspNonce }}">
  $(document).ready(function() {
    $("#user_login").attr("autocomplete", "off");
    $("#user_pass").attr("autocomplete", "new-password");
  });
</script>
<!-- Rest of your HTML content -->

<!-- <body class="login"> -->

<body class="school-login-page school-page">

  <div class="login-shell">

    <div class="login-visual">
      <h2>Keep Your Vehicle<br>Running Perfectly</h2>
      <p class="visual-tagline">{{ getNameSystem() }} &mdash; complete garage management, from job cards to invoices, all in one place.</p>
    </div>

    <div class="login-panel">
      <div class="brand-logo">
        <img src="{{ URL::asset('/public/general_setting/' . getLogoSystem()) }}" alt="{{ getNameSystem() }}">
      </div>

      <h1 class="login-heading">Welcome Back</h1>
      <p class="login-subheading">Sign in to {{ getNameSystem() }} to manage your garage operations.</p>

      <div class="content-form-login-page-school-plugin md-form">

              @php
                  $smsAddonExists = \Illuminate\Support\Facades\View::exists('smsaddon::sms_setting');
                  if($smsAddonExists) {
                     $isMobileLoginActive = \Illuminate\Support\Facades\DB::table('tbl_settings')->value('is_mobile');
                  }
                  else {
                    $isMobileLoginActive = 1;
                  }
              @endphp

              @if($smsAddonExists && $isMobileLoginActive == 0)
                <!-- Login Tabs -->
                <div class="login-tabs">
                  <button type="button" class="tab-button active" id="email-tab">
                    📧 Email Login
                  </button>
                  <button type="button" class="tab-button" id="mobile-tab">
                    📱 Mobile Login
                  </button>
                </div>
              @endif

              <!-- Email Login Tab -->
              <div id="email-tab" class="tab-content active">
                <form class="form-horizontal" method="POST" action="{{ url('/login') }}">
                  <input type="hidden" name="_token" value="ng6dqKQpcfVoWUABxW33aHAYV681V6asws3AxuZ0">
                  {{ csrf_field() }}
                  <p class="login-demo">
                    <label for="user_login"> {{trans('message.Email')}} </label>
                    <input type="text" name="email" id="user_login" autocomplete="off" class="input" value="" size="20">
                    @if ($errors->has('email'))
                    <span class="help-block text-danger mt-1" style="width: 280px;">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                  </p>
                  <p class="login-password">
                    <label for="user_pass">{{ trans('message.Password') }}</label>
                    <input type="password" name="password" id="user_pass" autocomplete="new-password" class="input" value="" size="20">
                     <span class="password-toggle" id="togglePassword">
                        <i class="fa fa-eye" id="toggleIcon"></i>
                      </span>
                    @if ($errors->has('password'))
                    <span class="help-block text-danger">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                  </p>
                  <div class="form-row-flex">
                    <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" />&nbsp;{{trans('message.Remember me')}}</label>
                    </p>
                    <a class="forgot_pwd_scl" href="{{ url('/password/reset') }}" title="Lost Password">{{trans('message.Forgot Password')}}?</a>
                  </div>

                  <p class="login-submit">
                    <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="{{trans('message.Log In')}}" title="Log In">
                    <input type="hidden" name="redirect_to" value=" ">
                  </p>
                </form>
              </div>
              
              @if($smsAddonExists && $isMobileLoginActive == 0)
                <!-- Mobile Login Tab -->
                <div id="mobile-tab" class="tab-content">
                  <div class="mobile-login-section">

                    <!-- Send OTP Form -->
                    <div id="send-otp-form">
                      @if(session('otp_sent'))
                        <div class="success-message">
                          OTP has been sent to your mobile number. Please check your SMS.
                        </div>
                      @endif

                      <form method="POST" action="{{ route('login.otp.send') }}" >
                        @csrf
                        <p class="login-demo">
                          <label for="mobile_no">{{trans('message.Mobile Number')}}</label>
                          <input type="tel" name="mobile_no" id="mobile_no" class="input" placeholder="" required>
                          <span class="error-message" id="mobile-error" style="display: none;"></span>
                        </p>
                        <p class="login-submit">
                          <button type="submit" class="otpButton" id="send-otp-btn">{{trans('message.Send OTP')}}</button>
                        </p>
                      </form>
                    </div>

                    <!-- Verify OTP Form -->
                    <div id="verify-otp-form" style="display: none;">
                      <div class="success-message">
                        OTP has been sent to your mobile number. Please check your SMS.
                      </div>

                      <form method="POST" action="{{ route('login.otp.verify.submit') }}" id="otp-form">
                        @csrf
                        <p class="login-password otp-container p-2">
                          <label for="otp">{{trans('message.Enter OTP')}}</label>
                          <input type="text" name="otp" id="otp" class="input" placeholder="000000" maxlength="6" style="text-align: center; letter-spacing: 2px;" required>
                          <span class="error-message" id="otp-error" style="display: none;"></span>
                        </p>

                        <div class="countdown-timer" id="countdown-timer">
                          Resend OTP in: <span id="countdown">60</span> seconds
                        </div>

                        <div style="text-align: center; margin-bottom: 15px;">
                          <span>Didn't receive OTP? </span>
                          <span class="resend-link disabled" id="resend-link">
                            Resend OTP
                          </span>
                        </div>

                        <p class="login-submit">
                          <button type="submit" class="otpButton">{{trans('message.Verify OTP')}}</button>
                        </p>
                      </form>

                      <p class="login-submit">
                        <button type="button" class="back-button" id="backToMobile">
                          Back to Mobile Number
                        </button>
                      </p>
                    </div>

                  </div>
                </div>
              @endif

        <?php if (getFrontendBooking() === 1): ?>
          <div class="login-divider">Or</div>
          <a class="bookService" href="{{ url('/service/frontendBook') }}"><i class="fa fa-calendar"></i>{{trans('message.Book an appoinment')}}</a>
        <?php endif; ?>

      </div>
    </div>
  </div>

  @if (!empty(session('firsttime')))
  <script nonce="{{ $cspNonce }}">
    var msg1 = "Your Installation is Successful"
    $(document).ready(function() {
      swal({
        title: msg1,

      }, function() {

        window.location.reload()
      });
    });
  </script>
  <?php Session::flush(); ?>
  @endif
<script nonce="{{ $cspNonce }}">
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("togglePassword");
    toggleBtn.addEventListener("click", function () {
        togglePassword("user_pass", toggleBtn);
    });

    const resendlink = document.getElementById("resend-link");
    resendlink.addEventListener("click", function () {
        resendOTP();
    })
    
    const backToMobile = document.getElementById("backToMobile");
    backToMobile.addEventListener("click", function () {
        backToMobile();
    })

    const emailTab = document.getElementById("email-tab");
    const mobileTab = document.getElementById("mobile-tab");

    emailTab.addEventListener("click", function () {
        switchTab("email");
    });

    mobileTab.addEventListener("click", function () {
        switchTab("mobile");
    });

    const otpForm = document.getElementById("otp-form");

    otpForm.addEventListener("submit", function (event) {
        handleVerifyOTP(event);
    });
});
function togglePassword(inputId, toggleElement) {
  const passwordInput = document.getElementById(inputId);
  const icon = toggleElement.querySelector('i');
  
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}
</script>
  <script nonce="{{ $cspNonce }}">
    let countdownTimer = null;
    let currentMobileNumber = '';

    // Tab switching functionality
    function switchTab(tabName) {
      // Remove active class from all tabs
      document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

      // Add active class to selected tab
      document.querySelector(`button[onclick="switchTab('${tabName}')"]`).classList.add('active');
      document.getElementById(`${tabName}-tab`).classList.add('active');

      // Reset mobile login forms when switching tabs
      if (tabName === 'email') {
        resetMobileLogin();
      }
    }

    // Handle Send OTP form submission
    function handleSendOTP(event) {
          event.preventDefault();
      // Don't prevent default for Laravel form submission
      const mobileInput = document.getElementById('mobile_no');
      const mobileNumber = mobileInput.value.trim();

      // Basic validation
      if (!mobileNumber) {
        event.preventDefault();
        showError('mobile-error', 'Mobile number is required');
        return;
      }

      if (!/^\d{10}$/.test(mobileNumber)) {
        event.preventDefault();
        showError('mobile-error', 'Please enter a valid 10-digit mobile number');
        return;
      }

      // Clear any previous errors
      hideError('mobile-error');

      // Store mobile number
      currentMobileNumber = mobileNumber;

      // Show loading state
      const sendButton = document.getElementById('send-otp-btn');
      sendButton.textContent = 'Sending...';
      sendButton.disabled = true;

    }

    // Handle Verify OTP form submission
    function handleVerifyOTP(event) {
      const otpInput = document.getElementById('otp');
      const otp = otpInput.value.trim();

      // Basic validation
      if (!otp) {
        event.preventDefault();
        showError('otp-error', 'OTP is required');
        return;
      }

      if (!/^\d{6}$/.test(otp)) {
        event.preventDefault();
        showError('otp-error', 'Please enter a valid 6-digit OTP');
        return;
      }

      // Clear any previous errors
      hideError('otp-error');

      // Show loading state
      const verifyButton = event.target.querySelector('button[type="submit"]');
      verifyButton.textContent = 'Verifying...';
      verifyButton.disabled = true;
    }

    // Show OTP verification form
    function showOTPForm() {
      document.getElementById('send-otp-form').style.display = 'none';
      document.getElementById('verify-otp-form').style.display = 'block';
      startCountdown();
    }

    // Start countdown timer
    function startCountdown() {
      let timeLeft = 60;
      const countdownElement = document.getElementById('countdown');
      const resendLink = document.getElementById('resend-link');
      const timerContainer = document.getElementById('countdown-timer');

      // Reset resend link
      resendLink.classList.add('disabled');
      timerContainer.style.display = 'block';

      countdownTimer = setInterval(() => {
        timeLeft--;
        countdownElement.textContent = timeLeft;

        if (timeLeft <= 0) {
          clearInterval(countdownTimer);
          resendLink.classList.remove('disabled');
          timerContainer.style.display = 'none';
        }
      }, 1000);
    }

    // Resend OTP
    function resendOTP() {
      const resendLink = document.getElementById('resend-link');

      if (resendLink.classList.contains('disabled')) {
        return;
      }

      // Clear previous timer
      if (countdownTimer) {
        clearInterval(countdownTimer);
      }

      // Simulate resend API call
      resendLink.textContent = 'Sending...';

      // Make actual request to resend OTP
      fetch('{{ route("login.otp.send") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          mobile_no: currentMobileNumber
        })
      })
      .then(response => response.json())
      .then(data => {
        resendLink.textContent = 'Resend OTP';
        startCountdown();

        // Show success message
        const successMsg = document.createElement('div');
        successMsg.className = 'success-message';
        successMsg.textContent = 'OTP has been resent to your mobile number.';
        successMsg.style.marginBottom = '15px';

        const otpContainer = document.querySelector('.otp-container');
        const existingSuccess = otpContainer.previousElementSibling;
        if (existingSuccess && existingSuccess.classList.contains('success-message')) {
          existingSuccess.remove();
        }

        otpContainer.parentNode.insertBefore(successMsg, otpContainer);

        // Remove success message after 3 seconds
        setTimeout(() => successMsg.remove(), 3000);
      })
      .catch(error => {
        resendLink.textContent = 'Resend OTP';
        console.error('Error:', error);
      });
    }

    // Back to mobile number entry
    function backToMobile() {
      resetMobileLogin();
    }

    // Reset mobile login forms
    function resetMobileLogin() {
      document.getElementById('send-otp-form').style.display = 'block';
      document.getElementById('verify-otp-form').style.display = 'none';
      document.getElementById('mobile_no').value = '';
      document.getElementById('otp').value = '';

      // Clear timer
      if (countdownTimer) {
        clearInterval(countdownTimer);
        countdownTimer = null;
      }

      // Clear errors
      hideError('mobile-error');
      hideError('otp-error');

      currentMobileNumber = '';
    }

    // Utility functions for error handling
    function showError(elementId, message) {
      const errorElement = document.getElementById(elementId);
      errorElement.textContent = message;
      errorElement.style.display = 'block';
    }

    function hideError(elementId) {
      const errorElement = document.getElementById(elementId);
      errorElement.style.display = 'none';
    }

    // Auto-format OTP input
    document.addEventListener('DOMContentLoaded', function() {
      const otpInput = document.getElementById('otp');
      if (otpInput) {
        otpInput.addEventListener('input', function(e) {
          // Only allow numbers
          e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
      }

      // Auto-format mobile number input
      const mobileInput = document.getElementById('mobile_no');
      if (mobileInput) {
        mobileInput.addEventListener('input', function(e) {
          // Only allow numbers
          e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
      }

      // Check if we should show OTP form based on session
      
      @if(session('otp_sent'))
        switchTab('mobile');
        showOTPForm();
      @endif
    });

    // Handle label activation for mobile inputs
    $(document).ready(function() {
      $("#mobile_no, #otp").click(function() {
        $(this).parent().find('label').addClass("active");
      });
    });
  </script>

</body>

</html>