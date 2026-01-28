@extends('layouts.app')
@section('content')
    <!-- page content -->
    <style>
        .table-responsive::-webkit-scrollbar {
            height: 8px;
            /* Height of the horizontal scrollbar */
        }

        .table-responsive::-webkit-scrollbar-track {
            background: rgb(196, 194, 193);
            /* Background of the scrollbar track */
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: rgb(214, 214, 213);
            /* Scrollbar thumb color */
            border-radius: 4px;
            /* Rounded edges for thumb */
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: grey;
            /* Change color on hover for better visibility */
        }
    </style>
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><a
                                href="{{ URL::previous() }}" id=""><i class=""><img
                                        src="{{ URL::asset('public/supplier/Back Arrow.png') }}"
                                        class="back-arrow"></i><span class="titleup">
                                    SMS SETTINGS </span></a>
                        </div>
                        @include('dashboard.profile')
                    </nav>
                </div>
            </div>
            {{-- @include('success_message.message') --}}
            
            <div class="x_content table-responsive">
                @include('settings_navbar.settings_nav')
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content">
                        @if(Auth::check() && isAdmin(Auth::user()->role_id))
                            @if (View::exists('smsaddon::sms_setting'))
                                @include('smsaddon::sms_setting')
                            @else 
                                <!-- @include('errors.403') -->
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
    <!-- page content end -->

    <script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>

@endsection