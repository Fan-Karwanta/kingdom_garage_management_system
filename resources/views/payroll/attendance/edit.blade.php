@extends('layouts.app')
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Edit Attendance Record</span>
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
                    <h2>Edit Attendance Details</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ url('/payroll/attendance/update/' . $attendance->id) }}" method="POST" class="form-horizontal">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-control select2" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ $attendance->user_id == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} {{ $emp->lastname }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date" class="form-control" value="{{ $attendance->date->format('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Clock In</label>
                                    <input type="time" name="clock_in" class="form-control" 
                                           value="{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Clock Out</label>
                                    <input type="time" name="clock_out" class="form-control" 
                                           value="{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Late</option>
                                        <option value="half-day" {{ $attendance->status == 'half-day' ? 'selected' : '' }}>Half Day</option>
                                        <option value="on-leave" {{ $attendance->status == 'on-leave' ? 'selected' : '' }}>On Leave</option>
                                        <option value="holiday" {{ $attendance->status == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Late Minutes</label>
                                    <input type="number" name="late_minutes" class="form-control" value="{{ $attendance->late_minutes }}" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="3">{{ $attendance->remarks }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Update Attendance
                            </button>
                            <a href="{{ url('/payroll/attendance') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-2"></i>Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script nonce="{{ $cspNonce }}">
$(document).ready(function() {
    $('.select2').select2();
});
</script>
@endsection
