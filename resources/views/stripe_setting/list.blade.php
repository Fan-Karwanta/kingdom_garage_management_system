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
        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{{ URL::previous() }}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
          {{ trans('message.Stripe Settings') }}</span></a>
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

        @can('stripesetting_view')
        <form id="stripe_settings_edit_form" method="post" action="{{ url('setting/stripe/store') }}" enctype="multipart/form-data" class="form-horizontal upperform">

          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
          <h4><b>{{ trans('message.Stripe API Key Information') }} :
            {{ trans('message.Update Your Live Stripe Keys Here!') }} </b></h4>
          <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
          </div>

          <div class="row mt-3 has-feedback">
          <label class="control-label col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 checkpointtext text-end" for="publish_key">{{ trans('message.Stripe Publishable key') }} <label class="color-danger">*</label>
          </label>
          <div class="col-md-5 col-lg-5 col-xl-5 col-xxl-5 col-sm-5 col-xs-5 ps-0">
            <input type="text" name="publish_key" class="form-control" placeholder="{{ trans('message.Enter Stripe Publishable Key') }}" required maxlength="500" value="{{ $settings_data->publish_key }}">
            @if ($errors->has('publish_key'))
            <span class="help-block">
            <span class="text-danger">{{ $errors->first('publish_key') }}</span>
            </span>
            @endif
          </div>
          <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3"></div>
          </div>

          <div class="row mt-3 has-feedback">
          <label class="control-label col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 checkpointtext text-end" for="secret_key">{{ trans('message.Stripe Secret key') }} <label class="color-danger">*</label>
          </label>
          <div class="col-md-5 col-lg-5 col-xl-5 col-xxl-5 col-sm-5 col-xs-5 ps-0">
            <input type="text" name="secret_key" class="form-control" placeholder="{{ trans('message.Enter Stripe Secret Key') }}" required value="{{ $settings_data->secret_key }}">
            @if ($errors->has('secret_key'))
            <span class="help-block">
            <span class="text-danger">{{ $errors->first('secret_key') }}</span>
            </span>
            @endif
          </div>
          <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3"></div>
          </div>

          <input type="hidden" name="stripe_id" value="{{ $settings_data->stripe_id }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          @can('stripesetting_edit')

          <!-- <div class="row m-3 has-feedback margin-right-0">
          <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group">
           <a class="btn stripesettingCancel" href="{{ URL::previous() }}">{{ trans('message.CANCEL') }}</a>
          </div>
          <div class="row col-md-7 col-lg-7 col-xl-7 col-xxl-7 col-sm-7 col-xs-7 form-group padding-right-0">
            <input type="submit" class="btn stripesettingSubmit" value="{{ trans('message.UPDATE') }}" />
          </div>
          </div> -->
          <div class="row mt-3 has-feedback margin-right-0">
          <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8 ps-0">
            <input type="submit" class="btn btn-success stripesettingSubmit form-control" value="{{ trans('message.UPDATE') }}" />
          </div>
          <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3"></div>
          </div>

          @endcan
        </form>
        @endcan
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <!-- page content end -->

  <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>

  <!-- Form field validation -->
  {!! JsValidator::formRequest('App\Http\Requests\StoreStripeSettingEditFormRequest', '#stripe_settings_edit_form') !!}
  <script nonce="{{ $cspNonce }}" type="text/javascript" src="{{ asset('public/vendor/jsvalidation/js/jsvalidation.js') }}"></script>

@endsection