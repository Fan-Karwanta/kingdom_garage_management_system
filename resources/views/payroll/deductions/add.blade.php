@extends('layouts.app')
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Add Deduction</span>
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
                    <h2><i class="fa fa-minus-circle me-2"></i>Deduction Details</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ url('/payroll/deductions/store') }}" method="POST">
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
                            <label class="form-label">Deduction Type <span class="text-danger">*</span></label>
                            <select name="deduction_type" class="form-control" required>
                                <option value="loan" {{ old('deduction_type') == 'loan' ? 'selected' : '' }}>Loan</option>
                                <option value="cash_advance" {{ old('deduction_type') == 'cash_advance' ? 'selected' : '' }}>Cash Advance</option>
                                <option value="other" {{ old('deduction_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" name="description" class="form-control" value="{{ old('description') }}" required>
                            @error('description')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" step="0.01" value="{{ old('amount') }}" required>
                            @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Monthly Deduction <span class="text-danger">*</span></label>
                            <input type="number" name="monthly_deduction" class="form-control" step="0.01" value="{{ old('monthly_deduction') }}" required>
                            <small class="text-muted">Amount to deduct per payroll period</small>
                            @error('monthly_deduction')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Save Deduction
                            </button>
                            <a href="{{ url('/payroll/deductions') }}" class="btn btn-secondary">
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
