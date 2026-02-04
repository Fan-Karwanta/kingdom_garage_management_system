@extends('layouts.app')
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Payroll Settings</span>
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
                    <h2><i class="fa fa-cog me-2"></i>Payroll Configuration</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ url('/payroll/settings/update') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Work Schedule Settings -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fa fa-clock-o me-2"></i>Work Schedule</h5>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label">Regular Hours Per Day</label>
                                    <input type="number" name="regular_hours_per_day" class="form-control" 
                                           value="{{ $settings->regular_hours_per_day ?? 8 }}" min="1" max="24">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Work Days Per Week</label>
                                    <input type="number" name="work_days_per_week" class="form-control" 
                                           value="{{ $settings->work_days_per_week ?? 5 }}" min="1" max="7">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Pay Period</label>
                                    <select name="pay_period" class="form-control">
                                        <option value="weekly" {{ ($settings->pay_period ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="bi-weekly" {{ ($settings->pay_period ?? '') == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                        <option value="semi-monthly" {{ ($settings->pay_period ?? 'semi-monthly') == 'semi-monthly' ? 'selected' : '' }}>Semi-Monthly</option>
                                        <option value="monthly" {{ ($settings->pay_period ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Cutoff Day 1</label>
                                            <input type="number" name="cutoff_day_1" class="form-control" 
                                                   value="{{ $settings->cutoff_day_1 ?? 15 }}" min="1" max="31">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Cutoff Day 2</label>
                                            <input type="number" name="cutoff_day_2" class="form-control" 
                                                   value="{{ $settings->cutoff_day_2 ?? 30 }}" min="1" max="31">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pay Multipliers -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fa fa-calculator me-2"></i>Pay Multipliers</h5>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label">Default Hourly Rate</label>
                                    <input type="number" name="default_hourly_rate" class="form-control" step="0.01"
                                           value="{{ $settings->default_hourly_rate ?? 0 }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Overtime Multiplier</label>
                                    <div class="input-group">
                                        <input type="number" name="overtime_multiplier" class="form-control" step="0.01"
                                               value="{{ $settings->overtime_multiplier ?? 1.5 }}">
                                        <span class="input-group-text">x</span>
                                    </div>
                                    <small class="text-muted">e.g., 1.5 = 150% of regular rate</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Night Differential Multiplier</label>
                                    <div class="input-group">
                                        <input type="number" name="night_diff_multiplier" class="form-control" step="0.01"
                                               value="{{ $settings->night_diff_multiplier ?? 1.1 }}">
                                        <span class="input-group-text">x</span>
                                    </div>
                                    <small class="text-muted">e.g., 1.1 = 110% of regular rate</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Holiday Multiplier</label>
                                    <div class="input-group">
                                        <input type="number" name="holiday_multiplier" class="form-control" step="0.01"
                                               value="{{ $settings->holiday_multiplier ?? 2.0 }}">
                                        <span class="input-group-text">x</span>
                                    </div>
                                    <small class="text-muted">e.g., 2.0 = 200% of regular rate</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <!-- Night Differential Time -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fa fa-moon-o me-2"></i>Night Differential</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Start Time</label>
                                            <input type="time" name="night_diff_start" class="form-control" 
                                                   value="{{ $settings->night_diff_start ?? '22:00' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">End Time</label>
                                            <input type="time" name="night_diff_end" class="form-control" 
                                                   value="{{ $settings->night_diff_end ?? '06:00' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Government Contributions -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="fa fa-institution me-2"></i>Government Contributions</h5>
                                
                                <div class="form-group mb-3">
                                    <label class="form-label">SSS Contribution Rate</label>
                                    <div class="input-group">
                                        <input type="number" name="sss_contribution_rate" class="form-control" step="0.0001"
                                               value="{{ $settings->sss_contribution_rate ?? 0.045 }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Enter as decimal (e.g., 0.045 = 4.5%)</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">PhilHealth Contribution Rate</label>
                                    <div class="input-group">
                                        <input type="number" name="philhealth_contribution_rate" class="form-control" step="0.0001"
                                               value="{{ $settings->philhealth_contribution_rate ?? 0.025 }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Enter as decimal (e.g., 0.025 = 2.5%)</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Pag-IBIG Contribution Rate</label>
                                    <div class="input-group">
                                        <input type="number" name="pagibig_contribution_rate" class="form-control" step="0.0001"
                                               value="{{ $settings->pagibig_contribution_rate ?? 0.02 }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Enter as decimal (e.g., 0.02 = 2%)</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label">Default Tax Rate</label>
                                    <div class="input-group">
                                        <input type="number" name="tax_rate" class="form-control" step="0.0001"
                                               value="{{ $settings->tax_rate ?? 0 }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Save Settings
                            </button>
                            <a href="{{ url('/payroll') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-2"></i>Back to Dashboard
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
