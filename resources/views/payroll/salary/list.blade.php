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
                        <span class="titleup">Employee Salaries</span>
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
                <div class="x_title">
                    <h2><i class="fa fa-money me-2"></i>Salary Configuration</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="salaryTable" class="table jambo_table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Salary Type</th>
                                <th>Basic Salary</th>
                                <th>Allowances</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script nonce="{{ $cspNonce }}">
$(document).ready(function() {
    $('#salaryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url('/payroll/salary') }}',
            type: 'GET'
        },
        columns: [
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'designation', name: 'designation' },
            { data: 'salary_type', name: 'salary_type' },
            { data: 'basic_salary', name: 'basic_salary' },
            { data: 'allowances', name: 'allowances' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            processing: '<div class="loading-indicator"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>'
        },
        order: [[1, 'asc']],
        responsive: true
    });
});
</script>
@endsection
