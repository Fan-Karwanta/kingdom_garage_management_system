@extends('layouts.app')
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">File Leave Request</span>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>

    @include('success_message.message')

    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-calendar me-2"></i>Leave Request Details</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ url('/payroll/leave/store') }}" method="POST">
                        @csrf
                        
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

                        <div class="form-group mb-3">
                            <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                            <select name="leave_type" class="form-control" required>
                                <option value="vacation" {{ old('leave_type') == 'vacation' ? 'selected' : '' }}>Vacation Leave</option>
                                <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                                <option value="emergency" {{ old('leave_type') == 'emergency' ? 'selected' : '' }}>Emergency Leave</option>
                                <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>Maternity Leave</option>
                                <option value="paternity" {{ old('leave_type') == 'paternity' ? 'selected' : '' }}>Paternity Leave</option>
                                <option value="unpaid" {{ old('leave_type') == 'unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_paid" class="form-check-input" id="is_paid" checked>
                            <label class="form-check-label" for="is_paid">Paid Leave</label>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control" rows="3">{{ old('reason') }}</textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Submit Leave Request
                            </button>
                            <a href="{{ url('/payroll/leave') }}" class="btn btn-secondary">
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
