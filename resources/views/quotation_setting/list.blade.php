@extends('layouts.app')
@section('content')
<!-- Quill CSS -->
<link href="{{ URL::asset('vendors/quill/quilleditor.css') }}" rel="stylesheet">

<!-- page content -->
<style>
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background:rgb(196, 194, 193);
}

.table-responsive::-webkit-scrollbar-thumb {
    background:rgb(214, 214, 213);
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: grey;
}

/* Quill Editor Styles */
.quill-editor-container {
    min-height: 250px !important;
    margin-bottom: 10px !important;
    background: white;
    border: 1px solid #ccc;
    border-radius: 4px;
    position: relative !important;
    display: block !important;
    clear: both !important;
}

.quill-editor-container .ql-toolbar {
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    background: #f8f9fa;
    border-bottom: 1px solid #ccc;
    position: relative !important;
}

.quill-editor-container .ql-container {
    min-height: 250px !important;
    max-height: 400px !important;
    overflow-y: auto !important;
    font-size: 14px;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    position: relative !important;
}

.quill-editor-container .ql-editor {
    min-height: 250px !important;
}

.quill-editor-container .ql-editor.ql-blank::before {
    font-style: italic;
    color: #adb5bd;
}

/* Force proper spacing for submit button */
.btn_success_margin {
    margin-top: 20px !important;
    position: relative !important;
    /* z-index: 10 !important; */
}
</style>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="nav_menu">
        <nav>
          <div class="nav toggle">
          <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a href="{{ URL::previous() }}" id=""><i class=""><img src="{{ URL::asset('public/supplier/Back Arrow.png') }}" class="back-arrow"></i><span class="titleup">
                {{ trans('message.Quotation Setting') }}</span></a>
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
            <form id="quotation_setting_form" method="post" action="{{ url('setting/quotation_setting/list') }}" enctype="multipart/form-data" class="form-horizontal upperform">
             @csrf
              @can('quotationsetting_view')
              <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 space">
                <h4><b>{{ trans('message.QUOTATION SETTING') }}</b></h4>
                <p class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12 ln_solid"></p>
              </div>

              <div class="row mt-3"> 
                    <label for="first_name" class="col-md-3 col-lg-3 col-xl-3 col-xxl-3 col-sm-3 col-xs-3 mb-2 control-label checkpointtext text-end">{{ trans('message.Terms & Condition Here :') }}
                    </label>
                    <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-8 col-sm-8 col-xs-8">
                      <textarea name="terms_and_condition_text" id="editor_quotation" class="form-control validate[required] txt_area quill-editor">{{ old('terms_and_condition_text', $terms_and_condition) }}</textarea>
                    </div>
                  </div>
              @endcan

              <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="row ">
                <div class="row col-md-6 col-lg-6 col-xl-6 col-xxl-6 col-sm-6 col-xs-6 form-group">
                  <button type="submit" class="btn btn_success_margin">{{ trans('message.SUBMIT') }}</button>
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

<!-- Load jQuery first -->
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/jquery-3.7.1.min.js') }}"></script>

<!-- Load Quill library -->
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/quill/quill.min.js') }}"></script>

<!-- Load Quill initialization for quotation (NEW FILE) -->
<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/quill/quotation-editor.js') }}"></script>

@endsection