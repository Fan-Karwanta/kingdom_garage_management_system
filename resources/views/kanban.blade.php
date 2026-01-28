@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('public/css/jkanban.min.css') }}" />
<style>
    .kanban-board {
        background: #ffffff;
    }

    header.kanban-board-header {
        border: 1px solid;
        box-shadow: 5px 5px #DEAA7F;
        width: 50%;
        text-align: center;
        margin-left: 25%;
        margin-top: 5%;
        background: #ffffff;
    }

    .kanban-item {
        background: #e2e4e6;
        padding: 0px;
        margin-bottom: 2%;
    }

    .kanban-board .kanban-drag {
        padding: 5% 0%;
    }
</style>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a><span class="titleup">{{ trans('message.JobBoard') }}
                        </span>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
        @include('success_message.message')
        <div class="row">
            <div class="kanban-board"></div>
        </div>
    </div>
</div>
<!-- /page content -->


<!-- Scripts starting -->
<script src="{{ asset('public/js/jkanban.min.js') }}"></script>
{!! $kanban->scripts() !!}
@endsection