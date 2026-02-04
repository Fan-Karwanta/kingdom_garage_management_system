@extends('layouts.app')
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Add Attendance Record</span>
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
                    <h2>Attendance Details</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ url('/payroll/attendance/store') }}" method="POST" class="form-horizontal">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-control select2" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ old('user_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} {{ $emp->lastname }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                                    @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Clock In</label>
                                    <input type="time" name="clock_in" class="form-control" value="{{ old('clock_in') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Clock Out</label>
                                    <input type="time" name="clock_out" class="form-control" value="{{ old('clock_out') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Late</option>
                                        <option value="half-day" {{ old('status') == 'half-day' ? 'selected' : '' }}>Half Day</option>
                                        <option value="on-leave" {{ old('status') == 'on-leave' ? 'selected' : '' }}>On Leave</option>
                                        <option value="holiday" {{ old('status') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Late Minutes</label>
                                    <input type="number" name="late_minutes" class="form-control" value="{{ old('late_minutes', 0) }}" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Save Attendance
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
