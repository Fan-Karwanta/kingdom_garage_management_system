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
    .login_form {
      background: #2A3F54;
    }

    .login_content {
      text-shadow: none;
    }
   .footer-line{
    position: fixed;
    bottom: 10px;
    left:45%;
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
   .otpButton{
    border: 1px;
    color: white;
    padding: 3px 15px 3px 15px;
    background: #EA6B00;
    border-radius: 4px;
    cursor: pointer;
    }

   /* New styles for tabs */
   .login-tabs {
    display: flex;
    margin-bottom: 35px;
    border-radius: 4px;
    background: white;
    overflow: hidden;
   }

   .tab-button {
    flex: 1;
    padding: 10px 15px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    color: #666;
   }

   .tab-button.active {
    border-bottom: 3px solid #EA6B00;
    color: black;
   }

   .tab-content {
    display: none;
   }

   .tab-content.active {
    display: block;
   }

   .otp-container {
    text-align: center;
   }

   .countdown-timer {
    background: #fff3cd;
    color: #856404;
    padding: 8px;
    border-radius: 4px;
    margin: 10px 0;
    font-weight: 600;
    font-size: 12px;
   }

   .resend-link {
    color: #EA6B00;
    cursor: pointer;
    text-decoration: underline;
    font-weight: 600;
   }

   .resend-link:hover {
    color: #d15700;
   }

   .resend-link.disabled {
    color: #ccc;
    cursor: not-allowed;
    text-decoration: none;
   }

   .success-message {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
    border: 1px solid #c3e6cb;
    font-size: 13px;
   }

   .error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 3px;
    display: block;
   }

   .mobile-login-section {
    margin-top: 20px;
   }

   .back-button {
    background: #6c757d;
    color: white;
    padding: 3px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
    font-size: 12px;
   }
   #mobile_no{
    border: none;
    border-bottom: 1px solid black;
   }
    @media only screen and (max-width: 575px) {
      .content-form-login-page-school-plugin.md-form {
        margin-left: 50px;
      }


    }

    @media only screen and (width: 575px) {
      .logo-title-img-school-plugin {
        margin-top: 60px;
        margin-left: 50px;
        width: 450px;
      }

      .forgot_pwd_scl,
      .forgot_pwd_scl:hover {
        margin-left: 0px;
      }
    }

    @media only screen and (max-width: 575px) {
      .header-title-trusted-plugin {
        font-size: 30px;
      }

      .heade-content-login-page {
        margin: 0px 0px 0px;
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;
      }

      .main-div-school-container {
        background-color: transparent;
        border-radius: 0px;
        box-shadow: 0px 0px 0px 0px rgba(20, 20, 20, 0.5);
      }

      .img-second-right-side-min-sch .img-second-bck-contn-sch {
        display: none;
      }

      .img-one-right-side-min-sch .img-first-bck-contn-sch {
        display: none;
      }

      img.img-first-bck-contn-sch-round {
        display: none;
      }

      .logo-title-img-school-plugin {
        margin-top: 60px;
        margin-left: auto;
      }

      .logo-title-img-school-plugin a img {
        width: auto;
        background-color: rgba(234, 107, 0, 0.07);
      }

      .col-sm-7.col-sm-offset-3.col-md-7.col-md-offset-2.main.content-start {
        margin-top: 0px;

      }

      .background-main-div-plugin-login .container {
        top: 0px;
        width: 100%;
      }

      img.head_logo {
        display: none;
      }

    }

    @media (max-width: 1024px) and (min-width: 768px) {
      .header-title-trusted-plugin {
        font-size: 50px;
      }

      .heade-content-login-page {
        margin: 0px 0px 0px;
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
      }

      img.head_logo {
        display: none;
      }

      .school-page .navbar-inverse {
        background-color: rgba(234, 107, 0, 0.07);
        border-color: rgba(234, 107, 0, 0.07);
        padding: 0px 0px;
        border-radius: 0px;
        left: 18px;
        width: 43rem;
      }

      .col-sm-7.col-sm-offset-3.col-md-7.col-md-offset-2.main.content-start {
        /* margin-top: 10px; */
        width: 100%;
        margin: 8px 10px;
      }

      img.img-first-bck-contn-sch-round {
        position: absolute;
        top: 620px;
        right: 30px;
      }

    }
  </style>
  <!-- Add this CSS to your existing <style> section -->
  <style>
  .password-container {
    position: relative;
  }

  .password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #666;
    font-size: 16px;
    user-select: none;
  }

  .password-toggle:hover {
    color: #EA6B00;
  }

  .login-password {
    position: relative;
  }

  .login-password .input {
    padding-right: 40px; /* Make space for the icon */
  }
  </style>
  <style>
    @media (max-width: 375px) {

      .forgot_pwd_scl,
      .forgot_pwd_scl:hover {
        margin-left: 80px;
      }
      .footer-line{
         left:30% !important;
      }
    }

    @media (max-width: 320px) {
      .content-form-login-page-school-plugin.md-form {
        margin-left: 30px;
      }

      .forgot_pwd_scl,
      .forgot_pwd_scl:hover {
        margin-left: 80px;
      }
    }

    @media (min-width: 425px) and (max-width: 767px) {
      .content-form-login-page-school-plugin.md-form {
        margin-left: 80px;
      }

      .forgot_pwd_scl,
      .forgot_pwd_scl:hover {
        margin-left: 80px;
      }
    }

    @media (min-width: 768px) and (max-width: 991px) {
      .school-page .navbar-inverse {
        left: 0px;
      }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
      .school-page .navbar-inverse {
        left: 0px;
        width: 51rem;
      }
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

  <div class="background-main-div-plugin-login">
    <div class="container">
      <div class="main-div-school-container">
        <div class="header-bnner-login-page-mc">
          <div class="heade-content-login-page">
            <h1 class="header-title-trusted-plugin">
              <img src="{{ URL::asset('public/general_setting/medal 1.png') }}" class="head_logo">
              <span class="double_shadow_to_text_plugin_trusted"> {{ getNameSystem() }}</span>
            </h1>
            <!-- <h3 class="selling-codecanyon-plugin">Best in segment on codecanyon</h3> -->
          </div>
        </div>

        <div class="row">

          <div class="col-sm-5 col-md-5 sidebar">
            <div class="image-container">
              <img src="{{ URL::asset('/public/general_setting/' . getLogoSystem()) }}" alt="Your Image Description">
            </div>
            <?php if (getFrontendBooking() === 1): ?>
              <div class="w-auto mx-0 my-5">
                  <a class="bookService rounded-right" href="{{ url('/service/frontendBook') }}">{{trans('message.Book an appoinment')}}</a>
              </div>
            <?php endif; ?>
          </div>

          <div class="col-sm-7 col-sm-offset-3 col-md-7 col-md-offset-2 main content-start">
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
                  <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" />&nbsp;{{trans('message.Remember me')}}</label>
                  </p>
                  <a class="forgot_pwd_scl" href="{{ url('/password/reset') }}" title="Lost Password">{{trans('message.Forgot Password')}}?</a>

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

            </div>
          </div>
        </div>


      </div>
      <div class="footer-line">
      Powered By<a href="https://mojoomla.com/product/garage-master-garage-management-system">GarageMaster</a>
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