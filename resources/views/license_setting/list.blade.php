@extends('layouts.app')
@section('content')

<!-- page content -->
<style>
.table-responsive::-webkit-scrollbar {
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

.license-info {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.license-info h5 {
    color: #495057;
    margin-bottom: 15px;
}

.license-info p {
    margin-bottom: 8px;
    color: #6c757d;
}

.license-info strong {
    color: #495057;
}

.otp-section {
    display: none;
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
}

.verify-section {
    display: none;
    background: #d1ecf1;
    border: 1px solid #b8daff;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
}

.success-message {
    display: none;
    background: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    color: #155724;
}

.error-message {
    display: none;
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    color: #721c24;
}

.btn-otp {
    background-color: #EA6B00;
    border-color:rgb(202, 226, 230);
    color: white;
}

.btn-verify {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.btn-reset {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.loading {
    display: none;
}

.spinner-border {
    width: 1rem;
    height: 1rem;
}
</style>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{{ URL::previous() }}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                {{ trans('message.License Reset') }}</span></a>
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
            
            @can('license_view')
            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
              <h4><b>{{ trans('message.LICENSE RESET') }}</b></h4>
              <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
            </div>

            <!-- License Information Display -->
            <div class="license-info">
              <h5><i class="fa fa-key"></i> {{trans('message.License Information');}}</h5>
              <p><strong>{{trans("message.Current Registered Domain")}}:</strong> <span id="current-domain">{{ $settings_data->domain_name ?? 'Not Available' }}</span></p>
              <p><strong>{{trans("message.Registration Email")}}:</strong> <span id="registration-email">{{ $settings_data->email ?? 'Not Available' }}</span></p>
             
            </div>
             <div class="mt-3">
                <p class="text-warning"><i class="fa fa-exclamation-triangle text-black"></i> <strong style="color:rgb(59, 58, 58); font-weight: bold;">{{trans('message.Important')}}:</strong>{{trans('message.If you want to transfer your license to a new domain, please reset this license. This action will deactivate your current license on this domain and allow you to register a new domain.')}} </p>
              </div>
            <!-- Email Input Form -->
            <form id="license-reset-form" class="form-horizontal upperform">
              <div class="row mt-3">
                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext text-end" for="email">
                  {{ trans('message.Enter Purchase key') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                 <input type="text" name="pkey" value="" class="form-control" id="pkey" placeholder="{{trans('message.Enter Purchase key')}}">
                </div>
              </div>
              <div class="row">
                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 checkpointtext text-end" for="email">
                  {{ trans('message.Enter Email') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                  <input type="email" class="form-control" name="email" id="email" placeholder="{{trans('message.Enter your registered email')}}" required>
                </div>
              </div>
               <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3">
                  <button type="button" class="btn btn-otp" id="send-otp-btn">
                    <span class="btn-text">{{trans('message.Send OTP')}}</span>
                    <span class="loading spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  </button>
                </div>
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              
            </form>

            <!-- OTP Verification Section -->
            <div class="otp-section" id="otp-section">
              <h5><i class="fa fa-shield"></i>{{trans('message.OTP Verification')}}</h5>
              <p class="text-info">{{trans('message.An OTP has been sent to your email address. Please enter the 6-digit code below')}}:</p>
              <!-- Timer Display -->
              <div class="row mt-2">
                <div class="col-md-12">
                  <div class="alert alert-warning d-flex align-items-center" id="timer-alert">
                    <i class="fa fa-clock-o me-2"></i>
                    <span>{{trans('message.OTP will expire in')}}: <strong id="timer-display">01:00</strong></span>
                  </div>
                </div>
              </div>
              <form id="otp-verify-form" class="form-horizontal">
                <div class="row mt-3">
                  
                  <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                    <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter 6-digit OTP" maxlength="6" required>
                  </div>
                  <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3">
                    <button type="button" class="btn btn-verify" id="verify-otp-btn">
                      <span class="btn-text">{{trans('message.Verify OTP')}}</span>
                      <span class="loading spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                  </div>
                </div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
              </form>
               
              <div class="row mt-3">
                <div class="col-md-12">
                 <p class="text-muted">Didn't receive the OTP?<a href="#" id="resend-otp" class="text-primary">Resend OTP</a></p> 
                </div>
              </div>
            </div>

            <!-- Success Message -->
            <div class="success-message" id="success-message">
              <h5><i class="fa fa-check-circle"></i>{{trans('message.License Reset Successful!')}}</h5>
              <p>{{trans('message.Your license has been reset successfully. You can now register your application with a new domain.')}}</p>
              <p><strong>{{trans('message.Next Steps')}}:</strong></p>
              <ul>
                <li>{{trans('message.Restart your application')}}</li>
                <li>{{trans('message.Enter your license key when prompted')}}</li>
                <li>{{trans('message.Register with your new domain')}}</li>
              </ul>
            </div>

            <!-- Error Message -->
            <div class="error-message" id="error-message">
              <h5><i class="fa fa-exclamation-circle"></i> Error</h5>
              <p id="error-text">An error occurred. Please try again.</p>
            </div>

            @endcan
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- page content end -->

<script nonce="{{ $cspNonce }}" type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

<script nonce="{{ $cspNonce }}" type="text/javascript">
$(document).ready(function() {
    let otpTimer;
    let resendTimer;
    let otpTimeRemaining = 120; // 2 minute in seconds
    let resendTimeRemaining = 120; // 2 minute cooldown for resend
    
    // Send OTP functionality
    $('#send-otp-btn').click(function() {
        var email = $('#email').val();
        var pkey = $('input[name="pkey"]').val();
        
        if (!email) {
            showError('Please enter your email address');
            return;
        }
        
        if (!pkey) {
            showError('License key not found');
            return;
        }
        
        // Show loading state
        toggleLoading('#send-otp-btn', true);
        hideMessages();
        
        $.ajax({
            url: '{{ route("license.setting.send-otp") }}',
            method: 'POST',
            data: {
                email: email,
                pkey: pkey,
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                toggleLoading('#send-otp-btn', false);
                
                if (response.error) {
                    showError(response.message);
                } else {
                    $('#otp-section').slideDown();
                    showSuccess('OTP has been sent to your email address');
                    
                    // Start the OTP timer
                    startOtpTimer();
                    startResendTimer();
                    
                    // Hide success message after 3 seconds
                    setTimeout(function() {
                        $('#success-message').slideUp();
                    }, 3000);
                }
            },
            error: function(xhr) {
                toggleLoading('#send-otp-btn', false);
                var errorMessage = 'Failed to send OTP. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showError(errorMessage);
            }
        });
    });
    
    // Verify OTP functionality
    $('#verify-otp-btn').click(function() {
        var email = $('#email').val();
        var otp = $('#otp').val();
        
        if (!email || !otp) {
            showError('Please enter both email and OTP');
            return;
        }
        
        if (otp.length !== 6) {
            showError('OTP must be 6 digits');
            return;
        }
        
        // Check if OTP has expired
        if (otpTimeRemaining <= 0) {
            showError('OTP has expired. Please request a new one.');
            return;
        }
        
        // Show loading state
        toggleLoading('#verify-otp-btn', true);
        hideMessages();
        
        $.ajax({
            url: '{{ route("license.setting.verify-otp") }}',
            method: 'POST',
            data: {
                email: email,
                otp: otp,
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                toggleLoading('#verify-otp-btn', false);
                
                if (response.error) {
                    showError(response.message);
                } else {
                    // Stop timers
                    clearInterval(otpTimer);
                    clearInterval(resendTimer);
                    
                    $('#otp-section').slideUp();
                    $('#license-reset-form')[0].reset();
                    showSuccessMessage('Your license has been reset successfully! You can now register your application with a new domain.');
                }
            },
            error: function(xhr) {
                toggleLoading('#verify-otp-btn', false);
                var errorMessage = 'Failed to verify OTP. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showError(errorMessage);
            }
        });
    });
    
    // Resend OTP functionality
    $('#resend-otp').click(function(e) {
        e.preventDefault();
        
        if (resendTimeRemaining > 0) {
            showError('Please wait ' + resendTimeRemaining + ' seconds before requesting another OTP.');
            return;
        }
        
        // Reset timers
        otpTimeRemaining = 120;
        resendTimeRemaining = 120;
        
        // Clear existing timers
        clearInterval(otpTimer);
        clearInterval(resendTimer);
        
        // Trigger send OTP
        $('#send-otp-btn').click();
    });
    
    // OTP input formatting
    $('#otp').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Start OTP expiration timer
    function startOtpTimer() {
        otpTimeRemaining = 120; // Reset to 2 minute
        
        otpTimer = setInterval(function() {
            otpTimeRemaining--;
            
            // Format time display (MM:SS)
            var minutes = Math.floor(otpTimeRemaining / 60);
            var seconds = otpTimeRemaining % 60;
            var timeDisplay = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
            
            $('#timer-display').text(timeDisplay);
            
            // Change timer color based on remaining time
            var timerAlert = $('#timer-alert');
            if (otpTimeRemaining <= 10) {
                timerAlert.removeClass('alert-warning timer-warning').addClass('timer-expired');
                $('#timer-display').parent().html('<i class="fa fa-exclamation-triangle me-2"></i>OTP expired! Please request a new one.');
            } else if (otpTimeRemaining <= 30) {
                timerAlert.removeClass('alert-warning').addClass('timer-warning');
            }
            
            // When timer reaches 0
            if (otpTimeRemaining <= 0) {
                clearInterval(otpTimer);
                $('#verify-otp-btn').prop('disabled', true);
                $('#otp').prop('disabled', true);
            }
        }, 1000);
    }
    
    // Start resend cooldown timer
    function startResendTimer() {
        resendTimeRemaining = 120; // Reset to 2 minute
        
        // Hide resend link and show timer text
        $('#resend-link-wrapper').hide();
        $('#resend-timer-text').show();
        
        resendTimer = setInterval(function() {
            resendTimeRemaining--;
            $('#resend-timer').text(resendTimeRemaining);
            
            // When timer reaches 0
            if (resendTimeRemaining <= 0) {
                clearInterval(resendTimer);
                $('#resend-link-wrapper').show();
                $('#resend-timer-text').hide();
            }
        }, 1000);
    }
    
    // Helper functions
    function toggleLoading(buttonSelector, show) {
        var btn = $(buttonSelector);
        if (show) {
            btn.prop('disabled', true);
            btn.find('.btn-text').hide();
            btn.find('.loading').show();
        } else {
            btn.prop('disabled', false);
            btn.find('.btn-text').show();
            btn.find('.loading').hide();
        }
    }
    
    function showError(message) {
        $('#error-text').text(message);
        $('#error-message').slideDown();
        $('html, body').animate({
            scrollTop: $('#error-message').offset().top - 100
        }, 500);
    }
    
    function showSuccess(message) {
        $('#success-message h5').html('<i class="fa fa-check-circle"></i> Success!');
        $('#success-message p').first().text(message);
        $('#success-message ul').hide();
        $('#success-message').slideDown();
    }
    
    function showSuccessMessage(message) {
        $('#success-message h5').html('<i class="fa fa-check-circle"></i> License Reset Successful!');
        $('#success-message p').first().text(message);
        $('#success-message ul').show();
        $('#success-message').slideDown();
        $('html, body').animate({
            scrollTop: $('#success-message').offset().top - 100
        }, 500);
    }
    
    function hideMessages() {
        $('#error-message, #success-message').slideUp();
    }
});
</script>

@endsection