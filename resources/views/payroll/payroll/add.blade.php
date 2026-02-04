@extends('layouts.app')
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Create New Payroll Period</span>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>

    @include('success_message.message')

    <div class="row">
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-calendar me-2"></i>Payroll Period Details</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ url('/payroll/payroll/store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label class="form-label">Payroll Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', 'Payroll - ' . date('F Y')) }}" required>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control" 
                                           value="{{ old('start_date', date('Y-m-01')) }}" required>
                                    @error('start_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" class="form-control" 
                                           value="{{ old('end_date', date('Y-m-15')) }}" required>
                                    @error('end_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Pay Date</label>
                            <input type="date" name="pay_date" class="form-control" 
                                   value="{{ old('pay_date', date('Y-m-20')) }}">
                            <small class="text-muted">The date when employees will receive their pay</small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>
                            After creating the payroll period, you will be redirected to process and calculate employee payroll.
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-arrow-right me-2"></i>Create & Process Payroll
                            </button>
                            <a href="{{ url('/payroll/payroll') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-2"></i>Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-cog me-2"></i>Quick Settings</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if($settings)
                    <table class="table table-sm">
                        <tr>
                            <td>Pay Period</td>
                            <td><strong>{{ ucfirst(str_replace('-', ' ', $settings->pay_period)) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Regular Hours/Day</td>
                            <td><strong>{{ $settings->regular_hours_per_day }} hrs</strong></td>
                        </tr>
                        <tr>
                            <td>Overtime Multiplier</td>
                            <td><strong>{{ $settings->overtime_multiplier }}x</strong></td>
                        </tr>
                        <tr>
                            <td>SSS Rate</td>
                            <td><strong>{{ $settings->sss_contribution_rate * 100 }}%</strong></td>
                        </tr>
                        <tr>
                            <td>PhilHealth Rate</td>
                            <td><strong>{{ $settings->philhealth_contribution_rate * 100 }}%</strong></td>
                        </tr>
                        <tr>
                            <td>Pag-IBIG Rate</td>
                            <td><strong>{{ $settings->pagibig_contribution_rate * 100 }}%</strong></td>
                        </tr>
                    </table>
                    <a href="{{ url('/payroll/settings') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fa fa-cog me-2"></i>Modify Settings
                    </a>
                    @else
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        No payroll settings configured yet.
                    </div>
                    <a href="{{ url('/payroll/settings') }}" class="btn btn-sm btn-primary w-100">
                        <i class="fa fa-cog me-2"></i>Configure Settings
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
@endsection
