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
                        <span class="titleup">Attendance Records
                            <a href="{{ url('/payroll/attendance/add') }}" class="addbotton">
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

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Employee</label>
                            <select id="filter_employee" class="form-control">
                                <option value="">All Employees</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} {{ $emp->lastname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Start Date</label>
                            <input type="date" id="filter_start_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label>End Date</label>
                            <input type="date" id="filter_end_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label>Status</label>
                            <select id="filter_status" class="form-control">
                                <option value="">All Status</option>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="half-day">Half Day</option>
                                <option value="on-leave">On Leave</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="btn_filter" class="btn btn-primary me-2">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <button type="button" id="btn_reset" class="btn btn-secondary me-2">
                                <i class="fa fa-refresh"></i> Reset
                            </button>
                            <a href="{{ url('/payroll/attendance/import') }}" class="btn btn-success">
                                <i class="fa fa-upload"></i> Import CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <table id="attendanceTable" class="table jambo_table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Total Hours</th>
                            <th>Overtime</th>
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
    var table = $('#attendanceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url('/payroll/attendance') }}',
            type: 'GET',
            data: function(d) {
                d.employee_id = $('#filter_employee').val();
                d.start_date = $('#filter_start_date').val();
                d.end_date = $('#filter_end_date').val();
                d.status = $('#filter_status').val();
            }
        },
        columns: [
            { data: 'employee', name: 'employee' },
            { data: 'date', name: 'date' },
            { data: 'clock_in', name: 'clock_in' },
            { data: 'clock_out', name: 'clock_out' },
            { data: 'total_hours', name: 'total_hours' },
            { data: 'overtime', name: 'overtime' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            processing: '<div class="loading-indicator"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>'
        },
        order: [[1, 'desc']],
        responsive: true
    });

    $('#btn_filter').click(function() {
        table.ajax.reload();
    });

    $('#btn_reset').click(function() {
        $('#filter_employee').val('');
        $('#filter_start_date').val('');
        $('#filter_end_date').val('');
        $('#filter_status').val('');
        table.ajax.reload();
    });

    $('body').on('click', '.deletedatas', function() {
        var url = $(this).attr('url');
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
