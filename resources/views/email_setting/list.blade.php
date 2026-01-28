@extends('layouts.app')
@section('content')
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
}</style>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{!! url('setting/general_setting/list') !!}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                {{ trans('message.Email Settings') }}</span></a>
          </div>
          @include('dashboard.profile')
        </nav>
      </div>
    </div>
    @include('success_message.message')
    
    <div class="x_content table-responsive">
      @include('settings_navbar.settings_nav')
    </div>
    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            <form id="email_setting_form" method="post" action="{{ url('setting/email_setting/store') }}" enctype="multipart/form-data" class="form-horizontal upperform">

              <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                <h4><b>{{ trans('message.Update Your Email Configuration Here!') }}</b></h4>
                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
              </div>

              <div class="row mt-3">
                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-end" for="Phone_Number">{{ trans('message.Email Driver') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                  <input type="text" name="MAIL_DRIVER" id="MAIL_DRIVER" class="form-control" placeholder="{{ trans('message.Enter Email Driver') }}" value="{{ $configData['MAIL_DRIVER'] }}" required>
                </div>
                <div class="control-label col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6"><a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> {{ trans('message.Specify the email driver. Use smtp for SMTP protocol') }} </div>
              </div>
              <div class="row mt-3">
                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-end" for="Phone_Number">{{ trans('message.SMTP Server') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                  <input type="text" name="MAIL_HOST" id="MAIL_HOST" class="form-control" placeholder="{{ trans('message.Enter SMTP Server') }}" value="{{ $configData['MAIL_HOST'] }}" required>
                </div>
                <div class="control-label col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6"><a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> {{ trans('message.Enter the SMTP server address. Example: smtp.gmail.com') }} </div>
              </div>
              <div class="row mt-3">
                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-end" for="Phone_Number">{{ trans('message.SMTP Port') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                  <input type="text" name="MAIL_PORT" id="MAIL_PORT" class="form-control" placeholder="{{ trans('message.Enter SMTP Port') }}" value="{{ $configData['MAIL_PORT'] }}" required>
                </div>
                <div class="control-label col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6"><a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> {{ trans('message.Specify the SMTP port number. Example: 465 for SSL') }} </div>
              </div>
              <div class="row mt-3">
                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-end" for="Phone_Number">{{ trans('message.Email Address') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                  <input type="email" name="MAIL_USERNAME" id="MAIL_USERNAME" class="form-control" placeholder="{{ trans('message.Enter Email Address') }}" value="{{ $configData['MAIL_USERNAME'] }}" required>
                </div>
                <div class="control-label col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6"><a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> {{ trans('message.Enter your full email address') }} </div>
              </div>
              <div class="row mt-3">
                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-end" for="Phone_Number">{{ trans('message.Password') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                  <input type="password" name="MAIL_PASSWORD" id="MAIL_PASSWORD" class="form-control" placeholder="{{ trans('message.Enter Password') }}" value="{{ $configData['MAIL_PASSWORD'] }}" required>
                </div>
                <div class="control-label col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6"><a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> {{ trans('message.Provide your email account password. Keep it confidential') }} </div>
              </div>
              <div class="row mt-3">
                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-end" for="Phone_Number">{{ trans('message.Encryption') }} <label class="color-danger"></label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                  <input type="text" name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION" class="form-control" placeholder="{{ trans('message.Enter Encryption') }}" value="{{ $configData['MAIL_ENCRYPTION'] }}">
                </div>
                <div class="control-label col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6"><a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> {{ trans('message.Choose the encryption method: ssl for SSL/TLS, or null for no encryption') }} </div>
              </div>
              <!-- Add form field-form email -->
              <div class="row mt-3">
                <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-end" for="Phone_Number">{{ trans('message.From Email') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                  <input type="email" name="MAIL_FROM_ADDRESS" id="FROM_USERNAME" class="form-control"  value="{{$configData['MAIL_FROM_ADDRESS']}}" required>
                  @error('MAIL_FROM_ADDRESS')
                       <div class="text-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="control-label col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6"><a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> {{ trans('message.Enter the address that will be displayed as the sender') }} </div>
              </div>
              <!-- ending New form field -->
              <div class="row mt-3">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group">
                  <button type="submit" class="btn btn_success_margin">{{ trans('message.UPDATE') }}</button>
                </div>
              </div>
            </form>
            <form action="{{ url('setting/email_setting/sendTest') }}" id="test-email-form" method="POST" class="form-horizontal upperform">
              @csrf
              <div class="row mt-5">
                  <label class="control-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2 text-end" for="Test_mail">
                      {{ trans('message.Email Address') }} <label class="color-danger">*</label>
                  </label>
                  <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                      <input type="email" name="Test_mail" id="Test_mail" class="form-control" placeholder="{{ trans('message.Enter Email Address') }}" required>
                  </div>
                  <div class="control-label col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                      <a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary">
                          <i class="fa fa-info-circle" style="color:#D9D9D9"></i>
                      </a> {{ trans('message.Enter Receiver address to send Test Email') }}
                  </div>
              </div>
              <div class="row mt-3">
                
                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group">
                  <button type="submit" class="btn btn_success_margin" id="send-test-email-button">{{ trans('message.Send Test Email') }}</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- page content end -->

<script nonce="{{ $cspNonce }}" nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script nonce="{{ $cspNonce }}" nonce="{{ $cspNonce }}">
    document.addEventListener('DOMContentLoaded', function () {
        const emailInput = document.getElementById('Test_mail');
        const submitButton = document.getElementById('send-test-email-button');
        const emailConfigForm = document.querySelector('#email_setting_form'); // Replace with the correct ID of the Email Configuration form
        const testEmailForm = document.querySelector('#test-email-form');

        // Save Email Configuration form values to localStorage
        function saveEmailConfigValues() {
            const formData = new FormData(emailConfigForm);
            for (let [key, value] of formData.entries()) {
                localStorage.setItem(`emailConfig_${key}`, value);
            }
        }

        // Function to validate email
        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        // Event listener for input change
        emailInput.addEventListener('input', function () {
            if (isValidEmail(emailInput.value)) {
                submitButton.disabled = false; // Enable button
            } else {
                submitButton.disabled = true; // Disable button
            }
        });

        // Initialize button state
        submitButton.disabled = true; // Disable on page load
        
        // Restore Email Configuration form values from localStorage
        function restoreEmailConfigValues() {
            const inputs = emailConfigForm.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                const savedValue = localStorage.getItem(`emailConfig_${input.name}`);
                if (savedValue !== null) {
                    input.value = savedValue;
                }
            });
        }

        // Add event listener to Test Email form submission
        testEmailForm.addEventListener('submit', function () {
            saveEmailConfigValues(); // Save values before the form is submitted
        });
        // Clear saved values after restoring them
        function clearEmailConfigValues() {
            const inputs = emailConfigForm.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                localStorage.removeItem(`emailConfig_${input.name}`);
            });
        }

        // Restore values on page load
        restoreEmailConfigValues();
    });
</script>

<!-- Form field validation -->
{!! JsValidator::formRequest('App\Http\Requests\EmailRequest', '') !!}
<script nonce="{{ $cspNonce }}" nonce="{{ $cspNonce }}" type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>

@endsection