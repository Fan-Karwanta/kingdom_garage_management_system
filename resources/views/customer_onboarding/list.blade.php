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
}</style>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">
                {{trans('message.Customer Onboarding') }}</span></a>
          </div>
          @include('dashboard.profile')
        </nav>
      </div>
    </div>
    @include('success_message.message')
    <?php   $setting = \App\Setting::find(1); ?>
    <div class="x_content table-responsive">
      @include('settings_navbar.settings_nav')
    </div>
    <div class="row">
      <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
         
            <form id="general_setting_edit_form" method="post" action="{{ url('setting/customerOnboarding/store') }}" enctype="multipart/form-data" class="form-horizontal upperform">

              <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                <h4><b>{{ trans('message.CUSTOMER ONBOARDING SETTING') }} </b></h4>
                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
              </div>
                 
               </div>
              <div class="row mt-3 row-mb-0 has-feedback">
                <label class="control-label col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 checkpointtext text-end" for="System_Name">{{ trans('message.Enable Customer Login') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                   <label><input type="radio" name="enable_customer_login" value="yes"  {{ old('enable_customer_login', $setting->customer_login == 1 ? 'yes' : 'no') == 'yes' ? 'checked' : '' }}> {{trans('message.Yes')}}</label>
                   <label><input type="radio" name="enable_customer_login" value="no" {{ old('enable_customer_login', $setting->customer_login == 1 ? 'yes' : 'no') == 'no' ? 'checked' : '' }} > {{trans('message.No') }}</label>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3"></div>
              </div>
              <div class="row mt-3 row-mb-0 has-feedback" id="logintype" style="{{ $setting->customer_login == 1 ? '' : 'display: none;' }}">
                <label class="control-label col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 checkpointtext text-end" for="System_Name">{{ trans('message.Please Select Login Type') }} <label class="color-danger">*</label>
                </label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                   <label><input type="radio" name="customer_email_login" value="yes" {{ old('customer_email_login', $setting->is_mobile == 1 ? 'yes' : 'no') == 'yes' ? 'checked' : '' }}> {{trans('message.Email Login')}}</label>
                   <label><input type="radio" id="mobile_login" name="customer_email_login" value="no" {{ old('customer_email_login', $setting->is_mobile == 1 ? 'yes' : 'no') == 'no' ? 'checked' : '' }} > {{trans('message.Mobile Login')}}</label>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3"></div>
              </div>
              <div class="row ">
                <div class="control-label col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 mt-2"><a data-toggle="tooltip" data-placement="bottom" title="Information" class="text-primary"><i class="fa fa-info-circle" style="color:#D9D9D9"></i></a> Note :<span style="color:#e78604">{{ trans('message.Before enabling mobile login, Please update your general setting first.') }}</span></div>
               
              </div>
              <!-- <div class="row row-mb-0 has-feedback">
                <label class="form-check-label col-md-2 col-lg-2 col-xl-2 col-xxl-2 col-sm-2 col-xs-2" for="mobile_login">{{trans('message.Enable Mobile Login')}}</label>
                <div class="col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4">
                  <div class="form-check">
                  <input type="checkbox" id="mobile_login" name="mobile_login" class="form-check-input">
                  </div>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3"></div>
              </div> -->
              
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

           

              <div class="row row-mb-0 has-feedback">
                <!-- <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group ">
                  <a class="btn cancel" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a>
                </div> -->
                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group my-2 mx-0">
                  <input type="submit" class="btn update " value="{{ trans('message.UPDATE') }}" />
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
@php
    $smsAddonExists = View::exists('smsaddon::sms_setting');
@endphp

<script nonce="{{ $cspNonce }}">
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('mobile_login');
    const smsAddonExists = @json($smsAddonExists);

    checkbox.addEventListener('change', function () {
        if (this.checked && !smsAddonExists) {
            swal({
                title: "SMS Service Not Found",
                content: {
                    element: "div",
                    attributes: {
                        innerHTML: 'Please purchase the SMS plugin from <a href="https://mojoomla.com/product/mj-sms-multi-gateway-plugin-for-garage-master/" target="_blank" style="color:#007bff;text-decoration:underline;">mojoomla.com</a> to enable Mobile Login.'
                    }
                },
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Okay",
                        visible: true,
                        closeModal: true,
                    }
                },
                dangerMode: true,
            });

            checkbox.checked = false;
        }
    });
});

  document.addEventListener('DOMContentLoaded', function () {
    const loginTypeSection = document.getElementById('logintype');
    const enableLoginRadios = document.getElementsByName('enable_customer_login');

    function toggleLoginType() {
      const isEnabled = [...enableLoginRadios].find(r => r.checked).value === 'yes';
      loginTypeSection.style.display = isEnabled ? 'flex' : 'none';
    }

    // Initial check on page load
    toggleLoginType();

    // Add change event listeners
    enableLoginRadios.forEach(radio => {
      radio.addEventListener('change', toggleLoginType);
    });
  });

</script>


<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>

@endsection