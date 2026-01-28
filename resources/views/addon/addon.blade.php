@extends('layouts.app')
@section('content')
  <!-- page content -->
  <style>
     .card {
            width: 100%;
            margin: 12px 0;
            border: 1px solid #ddd;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .card img {
            width: 100%;
            height: 100%;
        }
        .img-container{
          width: 100%;
          height: 130px;
        }
        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;
            row-gap: 10px
        }

        .card-text {
           height: 1.5rem;
           text-align: start;
          
        }

        .get-it-now-btn {
            background-color: #EA6B00;
            color: #FFFFFF;
            width: 100%;
            text-align: center;
            padding: 8px 0;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
        }
       
        .get-it-now-btn:hover {
            background-color: #c55d00;
            color: #FFFFFF;
        }
        @media only screen  and (max-width:540px){
            .card{
                width: 95%;
                margin:12px auto;
            }
       
        }
  </style>
  <div class="right_col" role="main">
    <div class="">
    <div class="page-title">
      <div class="nav_menu">
      <nav>
        <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{{ URL::previous() }}" id=""><i
          class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span
          class="titleup">
          {{ trans('message.Addons') }}</span></a>
        </div>
        @include('dashboard.profile')
      </nav>
      </div>
    </div>
    @include('success_message.message')
    @if (session('package_error'))
      <div class="alert alert-success alert-dismissible checkbox checkbox-success checkbox-circle px-2" id="package-error">
          {{ session('package_error') }}
      </div>
      <script nonce="{{ $cspNonce }}">
        setTimeout(function () {
            $('#package-error').fadeOut('slow');
        }, 5000); // 3 seconds
    </script>
    @endif
    @if(Auth::check() && isAdmin(Auth::user()->role_id))
    <div class="container mt-4">
      <div class="row justify-content-start">

          <div class="col-xxl-3 col-lg-4 col-sm-6 col-xs-12">
              <div class="card">
              <div class="img-container">
                  <img src="{{ URL::asset('public/img/addons/Garage-Mobile-App-Mojoomla.jpg') }}" class="card-img-top" alt="Garage App">
              </div>
                  <div class="card-body">
                      <p class="card-text">{{ trans('message.Garage Master – Mobile App for Garage Master Software') }}</p>
                      <a href="https://mojoomla.com/product/garage-master-app-mobile-app-for-garage-master/"    target="_blank" class="get-it-now-btn">{{ trans('message.GET IT NOW') }}</a>
                  </div>
              </div>
          </div>
          <div class="col-xxl-3 col-lg-4 col-sm-6 col-xs-12">
              <div class="card">
                  <div class="img-container">
                  <img src="{{ URL::asset('public/img/addons/installation.jpg') }}" class="card-img-top" alt="Installation Plugins">
                  </div>
                  <div class="card-body">
                      <p class="card-text">{{ trans('message.Garage Master Installation') }}</p>
                      <a href="https://mojoomla.com/product/installation-of-plugins/" target="_blank" class="get-it-now-btn">{{ trans('message.GET IT NOW') }}</a>
                  </div>
              </div>
          </div>
          <div class="col-xxl-3 col-lg-4 col-sm-6 col-xs-12">
              <div class="card">
                  <div class="img-container">
                  <img src="{{ URL::asset('public/img/addons/Smsscreenpreview.png') }}" class="card-img-top package-image" alt="Garage App">
                  </div>
              
                  <div class="card-body">
                      <p class="card-text">{{ trans('message.Garage Sms Package') }}</p>
                
                      @if(View::exists('smsaddon::sms_setting') || \Schema::hasTable('sms_settings'))
                      <a href="#"    data-bs-toggle="modal" data-bs-target="#smsSettingsModal" class="get-it-now-btn">{{ trans('message.CONFIGURE IT') }}</a>
                      @else
                      <a href="https://mojoomla.com/product/mj-sms-multi-gateway-plugin-for-garage-master/"    target="_blank" class="get-it-now-btn">{{ trans('message.GET IT NOW') }}</a>
                      @endif
                    </div>
              </div>
          </div>
          <div class="col-xxl-3 col-lg-4 col-sm-6 col-xs-12">
              <div class="card">
                 <div class="img-container">
                  <img src="{{ URL::asset('public/img/addons/payscreenpreview.png') }}" class="card-img-top package-image" alt="Installation Plugins">
                 </div>
                  <div class="card-body">
                      <p class="card-text">{{ trans('message.Garage Payment Package') }}</p>
                      
                      @if(View::exists('easypay::payment_gateway_setting') || \Schema::hasTable('payment_gateways'))
                      <a href="#" data-bs-toggle="modal" data-bs-target="#paymentGatewayModal" class="get-it-now-btn">{{ trans('message.CONFIGURE IT') }}</a>
                
                      @else
                      <a href="https://mojoomla.com/product/mj-pay-payment-gateway-for-garage-master/" target="_blank" class="get-it-now-btn">{{ trans('message.GET IT NOW') }}</a>
                      @endif
                    </div>
              </div>
          </div>

        
      </div>
  </div>
  @else
  @include('errors.403')
  @endif


 


  </div>
  </div>
  <!-- page content end -->
  
    <!-- SMS Settings Modal -->
<div class="modal fade" id="smsSettingsModal" tabindex="-1" aria-labelledby="smsSettingsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="smsSettingsModalLabel">{{trans('message.SMS Settings')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="x_panel">
              <div class="x_content">
                @if(Auth::check() && isAdmin(Auth::user()->role_id))
                    @if(View::exists('smsaddon::sms_setting'))
                        @include('smsaddon::sms_setting')
                    @else
                        <div class="alert alert-warning">
                            ⚠️ Please add the <strong>SMS Addon package</strong> in the package directory to use this feature.
                        </div>
                    @endif
                @else
                    @include('errors.403')
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Payment Gateway Configuration Modal -->
<div class="modal fade" id="paymentGatewayModal" tabindex="-1" aria-labelledby="paymentGatewayModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentGatewayModalLabel">{{trans('message.Payment Gateway Settings')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="x_panel">
              <div class="x_content">
                @if(Auth::check() && isAdmin(Auth::user()->role_id))
                    @if (View::exists('easypay::payment_gateway_setting'))
                        @include('easypay::payment_gateway_setting')
                    @else
                        <div class="alert alert-warning">
                            ⚠️ Addon package not found. Please check that the <strong>easypay</strong> package exists in the packages folder.
                        </div>
                    @endif
                @else
                    @include('errors.403')
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


  <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>

@endsection