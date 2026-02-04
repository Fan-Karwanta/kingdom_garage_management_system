@extends('layouts.app')
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Edit Employee Salary - {{ $employee->name }} {{ $employee->lastname }}</span>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>

    @include('success_message.message')

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-money me-2"></i>Salary Information</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ url('/payroll/salary/update/' . $employee->id) }}" method="POST">
                        @csrf
                        
                        <!-- Employee Info Card -->
                        <div class="card mb-4 bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('public/employee/' . ($employee->image ?? 'avtar.png')) }}" 
                                         class="rounded-circle me-3" width="60" height="60">
                                    <div>
                                        <h5 class="mb-1">{{ $employee->name }} {{ $employee->lastname }}</h5>
                                        <p class="text-muted mb-0">{{ $employee->designation ?? 'No designation' }} | {{ $employee->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fa fa-calculator me-2"></i>Salary Details</h5>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label">Salary Type <span class="text-danger">*</span></label>
                                    <select name="salary_type" class="form-control" required>
                                        <option value="monthly" {{ ($salary->salary_type ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="daily" {{ ($salary->salary_type ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="hourly" {{ ($salary->salary_type ?? '') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                                    <input type="number" name="basic_salary" class="form-control" step="0.01" 
                                           value="{{ $salary->basic_salary ?? 0 }}" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Hourly Rate</label>
                                    <input type="number" name="hourly_rate" class="form-control" step="0.01" 
                                           value="{{ $salary->hourly_rate ?? 0 }}">
                                    <small class="text-muted">Used for overtime calculations if not auto-computed</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Daily Rate</label>
                                    <input type="number" name="daily_rate" class="form-control" step="0.01" 
                                           value="{{ $salary->daily_rate ?? 0 }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Effective Date</label>
                                    <input type="date" name="effective_date" class="form-control" 
                                           value="{{ $salary->effective_date ?? '' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fa fa-gift me-2"></i>Allowances</h5>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label">General Allowance</label>
                                    <input type="number" name="allowance" class="form-control" step="0.01" 
                                           value="{{ $salary->allowance ?? 0 }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Transportation Allowance</label>
                                    <input type="number" name="transportation_allowance" class="form-control" step="0.01" 
                                           value="{{ $salary->transportation_allowance ?? 0 }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Meal Allowance</label>
                                    <input type="number" name="meal_allowance" class="form-control" step="0.01" 
                                           value="{{ $salary->meal_allowance ?? 0 }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Housing Allowance</label>
                                    <input type="number" name="housing_allowance" class="form-control" step="0.01" 
                                           value="{{ $salary->housing_allowance ?? 0 }}">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fa fa-minus-circle me-2"></i>Deduction Settings</h5>
                                
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="sss_enabled" class="form-check-input" id="sss_enabled"
                                           {{ ($salary->sss_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sss_enabled">SSS Contribution</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input type="checkbox" name="philhealth_enabled" class="form-check-input" id="philhealth_enabled"
                                           {{ ($salary->philhealth_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="philhealth_enabled">PhilHealth Contribution</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input type="checkbox" name="pagibig_enabled" class="form-check-input" id="pagibig_enabled"
                                           {{ ($salary->pagibig_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pagibig_enabled">Pag-IBIG Contribution</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input type="checkbox" name="tax_enabled" class="form-check-input" id="tax_enabled"
                                           {{ ($salary->tax_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tax_enabled">Tax Withholding</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fa fa-bank me-2"></i>Bank Details</h5>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" 
                                           value="{{ $salary->bank_name ?? '' }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Account Number</label>
                                    <input type="text" name="bank_account_number" class="form-control" 
                                           value="{{ $salary->bank_account_number ?? '' }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Account Name</label>
                                    <input type="text" name="bank_account_name" class="form-control" 
                                           value="{{ $salary->bank_account_name ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="3">{{ $salary->notes ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Save Salary Information
                            </button>
                            <a href="{{ url('/payroll/salary') }}" class="btn btn-secondary">
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
@endsection
