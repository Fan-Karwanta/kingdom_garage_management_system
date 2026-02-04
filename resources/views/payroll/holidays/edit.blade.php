@extends('layouts.app')
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Edit Holiday</span>
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
                    <h2><i class="fa fa-gift me-2"></i>Edit Holiday Details</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ url('/payroll/holidays/update/' . $holiday->id) }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label class="form-label">Holiday Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $holiday->name }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="{{ $holiday->date->format('Y-m-d') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Holiday Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" required>
                                <option value="regular" {{ $holiday->type == 'regular' ? 'selected' : '' }}>Regular Holiday</option>
                                <option value="special" {{ $holiday->type == 'special' ? 'selected' : '' }}>Special Non-Working Holiday</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Pay Multiplier</label>
                            <div class="input-group">
                                <input type="number" name="pay_multiplier" class="form-control" step="0.01" 
                                       value="{{ $holiday->pay_multiplier }}">
                                <span class="input-group-text">x</span>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_recurring" class="form-check-input" id="is_recurring"
                                   {{ $holiday->is_recurring ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_recurring">Recurring every year</label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Update Holiday
                            </button>
                            <a href="{{ url('/payroll/holidays') }}" class="btn btn-secondary">
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
