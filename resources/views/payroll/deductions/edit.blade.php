@extends('layouts.app')
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Edit Deduction</span>
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
                    <h2><i class="fa fa-minus-circle me-2"></i>Edit Deduction Details</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ url('/payroll/deductions/update/' . $deduction->id) }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label class="form-label">Employee <span class="text-danger">*</span></label>
                            <select name="user_id" class="form-control select2" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ $deduction->user_id == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} {{ $emp->lastname }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Deduction Type <span class="text-danger">*</span></label>
                            <select name="deduction_type" class="form-control" required>
                                <option value="loan" {{ $deduction->deduction_type == 'loan' ? 'selected' : '' }}>Loan</option>
                                <option value="cash_advance" {{ $deduction->deduction_type == 'cash_advance' ? 'selected' : '' }}>Cash Advance</option>
                                <option value="other" {{ $deduction->deduction_type == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" name="description" class="form-control" value="{{ $deduction->description }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" step="0.01" value="{{ $deduction->amount }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Monthly Deduction <span class="text-danger">*</span></label>
                            <input type="number" name="monthly_deduction" class="form-control" step="0.01" value="{{ $deduction->monthly_deduction }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Remaining Balance</label>
                            <input type="number" name="remaining_balance" class="form-control" step="0.01" value="{{ $deduction->remaining_balance }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ $deduction->start_date ? $deduction->start_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ $deduction->end_date ? $deduction->end_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ $deduction->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ $deduction->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $deduction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $deduction->notes }}</textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Update Deduction
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
