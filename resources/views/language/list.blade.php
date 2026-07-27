@extends('layouts.app')
@section('content')

  <!-- page content -->
  <?php $userid = Auth::user()->id; ?>
  @if (getAccessStatusUser('Settings', $userid) == 'yes')
    <div class="right_col"
      role="main">
      <div class="">
        <div class="page-title">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"> </i><span class="titleup">&nbsp
                    {{ trans('message.Language List') }}</span></a>
              </div>
              @include('dashboard.profile')
            </nav>
          </div>
          @if (session('message'))
            <div class="row massage">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="checkbox checkbox-success checkbox-circle mb-2 alert alert-success alert-dismissible fade show">
                  <label for="checkbox-10 colo_success" style="margin-left: 20px;font-weight: 600;"> {{ session('message') }} </label>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1rem 0.75rem;"></button>
                </div>
              </div>
            </div>
          @endif
        </div>
        <div class="x_content">
          <ul class="nav nav-tabs bar_tabs"
            role="tablist">
            <li role="presentation"
              class=""><a href="{!! url('setting/timezone/list') !!}"><span class="visible-xs"><i
                    class="ti-info-alt"></i></span> {{ trans('message.Change Timezone') }}</a></li>
            <li role="presentation"
              class=""><a href="{!! url('setting/accessrights/list') !!}"><span class="visible-xs"><i
                    class="ti-info-alt"></i></span> {{ trans('message.Access Rights') }}</a></li>
            <li role="presentation"
              class=""><a href="{!! url('setting/general_setting/list') !!}"><span class="visible-xs"><i
                    class="ti-info-alt"></i></span> {{ trans('message.General Settings') }}</a></li>
            <li role="presentation"><a href="{!! url('setting/hours/list') !!}"><span class="visible-xs"><i
                    class="ti-info-alt"></i></span> {{ trans('message.Business Hours') }}</a></li>

          </ul>
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">

              <div class="x_content">
                <div class="alert alert-info" style="margin: 20px;">
                  <strong>English</strong> is the only supported language for this application.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="right_col"
      role="main">
      <div class="nav_menu main_title"
        style="margin-top:4px;margin-bottom:15px;">

        <div class="nav toggle"
          style="padding-bottom:16px;">
          <span class="titleup">&nbsp {{ trans('message.You are not authorize this page.') }}</span>
        </div>




      </div>


    </div>
  @endif


@endsection
