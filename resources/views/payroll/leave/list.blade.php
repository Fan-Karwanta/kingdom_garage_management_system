@extends('layouts.app')
@section('content')
<style>
    div.dataTables_processing {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 150px;
        color:#EA6B00;
        margin-left: -100px;
        margin-top: -26px;
        text-align: center;
        padding: 3px 0;
        border: none;
    }
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Leave Requests
                            <a href="{{ url('/payroll/leave/add') }}" class="addbotton">
                                <img src="{{ URL::asset('public/img/icons/plus Button.png') }}">
                            </a>
                        </span>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>

    @include('success_message.message')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <table id="leaveTable" class="table jambo_table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Paid</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script nonce="{{ $cspNonce }}">
$(document).ready(function() {
    $('#leaveTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url('/payroll/leave') }}',
            type: 'GET'
        },
        columns: [
            { data: 'employee', name: 'employee' },
            { data: 'type', name: 'type' },
            { data: 'dates', name: 'dates' },
            { data: 'days', name: 'days' },
            { data: 'paid', name: 'paid' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            processing: '<div class="loading-indicator"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>'
        },
        order: [[0, 'desc']],
        responsive: true
    });

    $('body').on('click', '.deletedatas', function() {
        var url = $(this).attr('url');
        event.preventDefault();
        swal({
            title: "Are You Sure?",
            text: "You will not be able to recover this data afterwards!",
            icon: 'warning',
            buttons: ["Cancel", "Yes, delete!"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                window.location.href = url;
            }
        });
    });
});
</script>
@endsection
