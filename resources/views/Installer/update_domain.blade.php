@extends('layouts.app')
@section('content')

<!-- page content -->
<div class="right_col" role="main">
  <div class="page-title">
    <div class="nav_menu">
      <nav>
        <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
          <span class="titleup">{{ trans('License Settings') }}
          </span>
        </div>
        @include('dashboard.profile')
      </nav>
    </div>
  </div>
  <!-- Error Message Display Code -->
  @if (session('message'))
  <div class="row massage">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="checkbox checkbox-success checkbox-circle mb-2 alert alert-success alert-dismissible fade show">
        @if (session('message') == '1')
        <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Please enter correct purchase key') }}</label>
        @elseif(session('message') == '2')
        <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.This purchase key is already registered. If you have any issue please contact us at sales@mojoomla.com') }}</label>
        @elseif(session('message') == '3')
        <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Please enter correct domain name.') }}</label>
        @elseif(session('message') == '4')
        <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.There seems to be some problem please try after sometime or contact us on sales@mojoomla.com') }}</label>
        @elseif(session('message') == '5')
        <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ trans('message.Connection Problem occurs because server is down.') }}</label>
        @endif
      </div>
    </div>
  </div>
  <br>
  @endif
  <!-- Error Message Display Code End-->
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_content">
          <form id="colorAdd-Form" method="post" action="{!! url('/update_domain') !!}" enctype="multipart/form-data" class="form-horizontal upperform colorAddForm">
            <div class="row row-mb-0">
              <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('Servername') }} <label class="text-danger">*</label></label>
                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                  <input type="text" id="domain_name" name="domain_name" class="form-control" value="{{ $_SERVER['SERVER_NAME'] }}" required readonly>
                </div>
              </div>
              <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('Purchase Key') }} <label class="text-danger">*</label></label>
                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                  <input type="text" id="purchase_key" name="purchase_key" class="form-control" placeholder="{{ trans('Enter Purchase Key') }}" required>
                </div>
              </div>
            </div>
            <div class="row row-mb-0">
              <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6">
                <label class="control-label col-md-4 col-lg-4 col-xl-4 col-xxl-4 col-sm-4 col-xs-4" for="first-name">{{ trans('E-Mail') }} <label class="text-danger">*</label></label>
                <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                  <input type="email" id="purchase_email" name="purchase_email" class="form-control" placeholder="{{ trans('Enter E-Mail') }}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 my-2 mx-0">
                <button type="submit" class="btn btn-success colorname colorAddSubmitButton">{{ trans('message.SUBMIT') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection