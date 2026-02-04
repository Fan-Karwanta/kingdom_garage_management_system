@extends('layouts.app')
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Import Biometrics / Attendance CSV</span>
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
                    <h2><i class="fa fa-upload me-2"></i>Upload CSV File</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form action="{{ url('/payroll/attendance/import/process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label">Select CSV File <span class="text-danger">*</span></label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv,.txt" required>
                            @error('csv_file')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fa fa-info-circle me-2"></i>CSV Format Requirements:</h6>
                            <p class="mb-2">The CSV file should have the following columns in order:</p>
                            <ol class="mb-0">
                                <li><strong>Employee ID / Email</strong> - Employee identifier</li>
                                <li><strong>Date</strong> - Format: YYYY-MM-DD or MM/DD/YYYY</li>
                                <li><strong>Clock In</strong> - Format: HH:MM or HH:MM:SS (24-hour)</li>
                                <li><strong>Clock Out</strong> - Format: HH:MM or HH:MM:SS (24-hour)</li>
                            </ol>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="fa fa-exclamation-triangle me-2"></i>Sample CSV Format:</h6>
                            <code>
                                employee_id,date,clock_in,clock_out<br>
                                1,2024-01-15,08:00,17:00<br>
                                john@email.com,2024-01-15,08:30,17:30<br>
                                2,2024-01-15,09:00,18:00
                            </code>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-upload me-2"></i>Import CSV
                            </button>
                            <a href="{{ url('/payroll/attendance') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-2"></i>Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-history me-2"></i>Recent Imports</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th>Records</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($imports as $import)
                                <tr>
                                    <td>
                                        <small>{{ $import->original_filename }}</small>
                                    </td>
                                    <td>
                                        <span class="text-success">{{ $import->successful_records }}</span> / 
                                        <span class="text-danger">{{ $import->failed_records }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'completed' => 'success',
                                                'failed' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$import->status] ?? 'secondary' }}">
                                            {{ ucfirst($import->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $import->created_at->format('M d, Y H:i') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="text-muted mb-0">No import history</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
@endsection
