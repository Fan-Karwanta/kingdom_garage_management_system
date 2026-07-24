@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ URL::asset('vendors/chart/Chart.min.css') }}">
<style>
    .analytics-wrap { padding: 10px 18px 40px; }
    .an-header { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin-bottom:18px; }
    .an-header h2 { margin:0; font-weight:700; color:#2b2f77; font-size:22px; }
    .an-header .an-sub { color:#8a94a6; font-size:13px; margin-top:2px; }
    .an-export-btn { background:#2b2f77; border:none; color:#fff; padding:10px 20px; border-radius:8px; font-weight:600; font-size:14px; display:inline-flex; align-items:center; gap:8px; cursor:pointer; transition:.2s; }
    .an-export-btn:hover { background:#EA6B00; color:#fff; }
    .an-export-btn:disabled { opacity:.6; cursor:not-allowed; }

    .kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:22px; }
    @media (max-width:1100px){ .kpi-grid{ grid-template-columns:repeat(2,1fr);} }
    @media (max-width:560px){ .kpi-grid{ grid-template-columns:1fr;} }
    .kpi-card { background:#fff; border-radius:12px; padding:18px; box-shadow:0 2px 10px rgba(43,47,119,.06); border-left:4px solid #2b2f77; }
    .kpi-card .kpi-label { color:#8a94a6; font-size:12px; text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
    .kpi-card .kpi-value { color:#2b2f77; font-size:24px; font-weight:700; margin-top:6px; word-break:break-word; }
    .kpi-card .kpi-icon { float:right; font-size:26px; color:#e2e5f5; }
    .kpi-card.green { border-left-color:#26b99a; } .kpi-card.green .kpi-value{ color:#26b99a; }
    .kpi-card.orange { border-left-color:#EA6B00; } .kpi-card.orange .kpi-value{ color:#EA6B00; }
    .kpi-card.red { border-left-color:#e74c3c; } .kpi-card.red .kpi-value{ color:#e74c3c; }

    .chart-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:18px; }
    @media (max-width:900px){ .chart-grid{ grid-template-columns:1fr;} }
    .chart-card { background:#fff; border-radius:12px; padding:18px; box-shadow:0 2px 10px rgba(43,47,119,.06); }
    .chart-card.full { grid-column:1 / -1; }
    .chart-card h4 { font-size:15px; font-weight:700; color:#37404f; margin:0 0 14px; display:flex; align-items:center; gap:8px; }
    .chart-card h4 i { color:#2b2f77; }
    .chart-holder { position:relative; height:300px; }
    .chart-holder.tall { height:340px; }

    .mini-table { width:100%; border-collapse:collapse; font-size:13px; }
    .mini-table th { text-align:left; color:#8a94a6; font-weight:600; padding:8px 6px; border-bottom:2px solid #eef0f5; text-transform:uppercase; font-size:11px; }
    .mini-table td { padding:9px 6px; border-bottom:1px solid #f2f4f8; color:#37404f; }
    .mini-table tr:last-child td { border-bottom:none; }
    .badge-low { background:#fdecea; color:#e74c3c; padding:2px 9px; border-radius:20px; font-weight:600; font-size:12px; }
    .an-empty { color:#b0b7c3; font-style:italic; padding:24px 0; text-align:center; }
</style>

<div class="right_col" role="main">
    <div class="page-title">
        <div class="nav_menu">
            <nav>
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                    <a><span class="titleup">{{ trans('message.Analytics') }}</span></a>
                </div>
                @include('dashboard.profile')
            </nav>
        </div>
    </div>

    <div class="analytics-wrap">
        <div class="an-header">
            <div>
                <h2>Business Analytics</h2>
                <div class="an-sub">Comprehensive performance overview &bull; Last 12 months trends</div>
            </div>
            <form id="pdfExportForm" method="POST" action="{{ url('/analytics/export-pdf') }}">
                @csrf
                <input type="hidden" name="charts" id="chartsInput">
                <button type="button" id="exportPdfBtn" class="an-export-btn">
                    <i class="fa-solid fa-file-pdf"></i> Export as PDF
                </button>
            </form>
        </div>

        {{-- KPI CARDS --}}
        <div class="kpi-grid">
            <div class="kpi-card green">
                <i class="kpi-icon fa-solid fa-sack-dollar"></i>
                <div class="kpi-label">Total Revenue</div>
                <div class="kpi-value">{{ $currency }} {{ number_format($kpi['totalRevenue'], 2) }}</div>
            </div>
            <div class="kpi-card">
                <i class="kpi-icon fa-solid fa-hand-holding-dollar"></i>
                <div class="kpi-label">Amount Received</div>
                <div class="kpi-value">{{ $currency }} {{ number_format($kpi['totalReceived'], 2) }}</div>
            </div>
            <div class="kpi-card red">
                <i class="kpi-icon fa-solid fa-clock"></i>
                <div class="kpi-label">Outstanding</div>
                <div class="kpi-value">{{ $currency }} {{ number_format($kpi['outstanding'], 2) }}</div>
            </div>
            <div class="kpi-card orange">
                <i class="kpi-icon fa-solid fa-money-bill-trend-up"></i>
                <div class="kpi-label">Total Expenses</div>
                <div class="kpi-value">{{ $currency }} {{ number_format($kpi['totalExpenses'], 2) }}</div>
            </div>

            <div class="kpi-card {{ $kpi['netProfit'] >= 0 ? 'green' : 'red' }}">
                <i class="kpi-icon fa-solid fa-chart-line"></i>
                <div class="kpi-label">Net Profit</div>
                <div class="kpi-value">{{ $currency }} {{ number_format($kpi['netProfit'], 2) }}</div>
            </div>
            <div class="kpi-card">
                <i class="kpi-icon fa-solid fa-receipt"></i>
                <div class="kpi-label">Avg Invoice Value</div>
                <div class="kpi-value">{{ $currency }} {{ number_format($kpi['avgInvoice'], 2) }}</div>
            </div>
            <div class="kpi-card">
                <i class="kpi-icon fa-solid fa-users"></i>
                <div class="kpi-label">Customers</div>
                <div class="kpi-value">{{ number_format($counts['customers']) }}</div>
            </div>
            <div class="kpi-card">
                <i class="kpi-icon fa-solid fa-car-side"></i>
                <div class="kpi-label">Vehicles</div>
                <div class="kpi-value">{{ number_format($counts['vehicles']) }}</div>
            </div>

            <div class="kpi-card">
                <i class="kpi-icon fa-solid fa-wrench"></i>
                <div class="kpi-label">Services</div>
                <div class="kpi-value">{{ number_format($counts['services']) }}</div>
            </div>
            <div class="kpi-card">
                <i class="kpi-icon fa-solid fa-file-invoice-dollar"></i>
                <div class="kpi-label">Quotations</div>
                <div class="kpi-value">{{ number_format($counts['quotations']) }}</div>
            </div>
            <div class="kpi-card">
                <i class="kpi-icon fa-solid fa-boxes-stacked"></i>
                <div class="kpi-label">Products</div>
                <div class="kpi-value">{{ number_format($counts['products']) }}</div>
            </div>
            <div class="kpi-card">
                <i class="kpi-icon fa-solid fa-code-branch"></i>
                <div class="kpi-label">Active Branches</div>
                <div class="kpi-value">{{ number_format($counts['branches']) }}</div>
            </div>
        </div>

        {{-- CHARTS --}}
        <div class="chart-grid">
            <div class="chart-card full">
                <h4><i class="fa-solid fa-chart-area"></i> Revenue vs Expenses vs Profit (12 months)</h4>
                <div class="chart-holder tall"><canvas id="chartFinancial"></canvas></div>
            </div>

            <div class="chart-card">
                <h4><i class="fa-solid fa-chart-pie"></i> Invoice Payment Status</h4>
                <div class="chart-holder"><canvas id="chartPaymentStatus"></canvas></div>
            </div>
            <div class="chart-card">
                <h4><i class="fa-solid fa-layer-group"></i> Revenue Source (Invoice Type)</h4>
                <div class="chart-holder"><canvas id="chartInvoiceType"></canvas></div>
            </div>

            <div class="chart-card">
                <h4><i class="fa-solid fa-wrench"></i> Services Created per Month</h4>
                <div class="chart-holder"><canvas id="chartServices"></canvas></div>
            </div>
            <div class="chart-card">
                <h4><i class="fa-solid fa-user-plus"></i> New Customers per Month</h4>
                <div class="chart-holder"><canvas id="chartNewCustomers"></canvas></div>
            </div>

            <div class="chart-card">
                <h4><i class="fa-solid fa-circle-check"></i> Job Completion Status</h4>
                <div class="chart-holder"><canvas id="chartCompletion"></canvas></div>
            </div>
            <div class="chart-card">
                <h4><i class="fa-solid fa-credit-card"></i> Payments by Method</h4>
                <div class="chart-holder"><canvas id="chartPayMethod"></canvas></div>
            </div>

            <div class="chart-card">
                <h4><i class="fa-solid fa-car"></i> Vehicles by Type</h4>
                <div class="chart-holder"><canvas id="chartVehicleType"></canvas></div>
            </div>
            <div class="chart-card">
                <h4><i class="fa-solid fa-industry"></i> Vehicles by Brand</h4>
                <div class="chart-holder"><canvas id="chartVehicleBrand"></canvas></div>
            </div>

            <div class="chart-card">
                <h4><i class="fa-solid fa-code-branch"></i> Revenue by Branch</h4>
                <div class="chart-holder"><canvas id="chartBranch"></canvas></div>
            </div>
            <div class="chart-card">
                <h4><i class="fa-solid fa-user-gear"></i> Top Employees (Services Handled)</h4>
                <div class="chart-holder"><canvas id="chartEmployee"></canvas></div>
            </div>

            <div class="chart-card">
                <h4><i class="fa-solid fa-trophy"></i> Top Customers by Revenue</h4>
                @if(count($topCustomers))
                <table class="mini-table">
                    <thead><tr><th>#</th><th>Customer</th><th>Inv.</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @foreach($topCustomers as $i => $c)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $c['name'] }}</td>
                            <td>{{ $c['invoices'] }}</td>
                            <td>{{ $currency }} {{ number_format($c['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else<div class="an-empty">No invoice data available.</div>@endif
            </div>
            <div class="chart-card">
                <h4><i class="fa-solid fa-box-open"></i> Top Selling Products</h4>
                <div class="chart-holder"><canvas id="chartProducts"></canvas></div>
            </div>

            <div class="chart-card full">
                <h4><i class="fa-solid fa-triangle-exclamation"></i> Low Stock Alerts (&le; 10 units)</h4>
                @if(count($lowStock))
                <table class="mini-table">
                    <thead><tr><th>Product</th><th>Code</th><th>Qty Remaining</th></tr></thead>
                    <tbody>
                        @foreach($lowStock as $p)
                        <tr>
                            <td>{{ $p['name'] }}</td>
                            <td>{{ $p['code'] }}</td>
                            <td><span class="badge-low">{{ $p['quantity'] }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else<div class="an-empty">All products are sufficiently stocked.</div>@endif
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}" id="analyticsData" type="application/json">
@json($chartData)
</script>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('build/js/jscharts.js') }}"></script>
<script nonce="{{ $cspNonce }}">
(function () {
    var A = JSON.parse(document.getElementById('analyticsData').textContent);
    var CUR = A.currency || '';
    var PALETTE = ['#2b2f77', '#EA6B00', '#26b99a', '#3498db', '#9b59b6', '#e74c3c', '#f1c40f', '#1abc9c', '#e67e22', '#34495e'];
    var registry = [];

    if (typeof Chart === 'undefined') { return; }
    Chart.defaults.font.family = "'Helvetica Neue', Roboto, Arial, sans-serif";
    Chart.defaults.color = '#73879C';

    function money(v) { return CUR + ' ' + Number(v).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}); }

    function make(id, title, config) {
        var el = document.getElementById(id);
        if (!el) return;
        config.options = config.options || {};
        config.options.responsive = true;
        config.options.maintainAspectRatio = false;
        config.options.animation = { duration: 600 };
        var chart = new Chart(el, config);
        registry.push({ id: id, title: title, chart: chart });
    }

    var moneyTip = {
        plugins: {
            legend: { labels: { boxWidth: 14, padding: 12 } },
            tooltip: { callbacks: { label: function (c) { return c.dataset.label ? c.dataset.label + ': ' + money(c.parsed.y) : money(c.parsed.y || c.parsed); } } }
        }
    };

    // 1. Financial trend
    make('chartFinancial', 'Revenue vs Expenses vs Profit', {
        type: 'line',
        data: {
            labels: A.monthLabels,
            datasets: [
                { label: 'Revenue', data: A.revenueTrend, borderColor: '#26b99a', backgroundColor: 'rgba(38,185,154,.12)', fill: true, tension: .35 },
                { label: 'Expenses', data: A.expenseTrend, borderColor: '#EA6B00', backgroundColor: 'rgba(234,107,0,.10)', fill: true, tension: .35 },
                { label: 'Profit', data: A.profitTrend, borderColor: '#2b2f77', backgroundColor: 'rgba(43,47,119,.08)', fill: false, tension: .35, borderDash: [5,4] }
            ]
        },
        options: { plugins: { tooltip: { callbacks: { label: function (c) { return c.dataset.label + ': ' + money(c.parsed.y); } } } }, scales: { y: { ticks: { callback: function (v) { return money(v); } } } } }
    });

    // 2. Payment status doughnut
    make('chartPaymentStatus', 'Invoice Payment Status', {
        type: 'doughnut',
        data: { labels: A.paymentStatus.labels, datasets: [{ data: A.paymentStatus.data, backgroundColor: ['#e74c3c', '#f1c40f', '#26b99a'] }] },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    // 3. Invoice type
    make('chartInvoiceType', 'Revenue Source', {
        type: 'pie',
        data: { labels: A.invoiceType.labels, datasets: [{ data: A.invoiceType.data, backgroundColor: ['#2b2f77', '#EA6B00', '#26b99a'] }] },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    // 4. Services per month
    make('chartServices', 'Services per Month', {
        type: 'bar',
        data: { labels: A.monthLabels, datasets: [{ label: 'Services', data: A.servicesTrend, backgroundColor: '#3498db', borderRadius: 4 }] },
        options: { plugins: { legend: { display: false } } }
    });

    // 5. New customers
    make('chartNewCustomers', 'New Customers per Month', {
        type: 'line',
        data: { labels: A.monthLabels, datasets: [{ label: 'New Customers', data: A.newCustomersTrend, borderColor: '#9b59b6', backgroundColor: 'rgba(155,89,182,.15)', fill: true, tension: .35 }] },
        options: { plugins: { legend: { display: false } } }
    });

    // 6. Completion
    make('chartCompletion', 'Job Completion Status', {
        type: 'doughnut',
        data: { labels: A.serviceCompletion.labels, datasets: [{ data: A.serviceCompletion.data, backgroundColor: ['#26b99a', '#f1c40f'] }] },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    // 7. Payment method
    make('chartPayMethod', 'Payments by Method', {
        type: 'doughnut',
        data: { labels: A.paymentMethod.labels, datasets: [{ data: A.paymentMethod.data, backgroundColor: PALETTE }] },
        options: { plugins: { legend: { position: 'bottom' }, tooltip: { callbacks: { label: function (c) { return c.label + ': ' + money(c.parsed); } } } } }
    });

    // 8. Vehicle type
    make('chartVehicleType', 'Vehicles by Type', {
        type: 'pie',
        data: { labels: A.vehicleType.labels, datasets: [{ data: A.vehicleType.data, backgroundColor: PALETTE }] },
        options: { plugins: { legend: { position: 'bottom' } } }
    });

    // 9. Vehicle brand
    make('chartVehicleBrand', 'Vehicles by Brand', {
        type: 'bar',
        data: { labels: A.vehicleBrand.labels, datasets: [{ label: 'Vehicles', data: A.vehicleBrand.data, backgroundColor: '#2b2f77', borderRadius: 4 }] },
        options: { indexAxis: 'y', plugins: { legend: { display: false } } }
    });

    // 10. Revenue by branch
    make('chartBranch', 'Revenue by Branch', {
        type: 'bar',
        data: { labels: A.revenueByBranch.labels, datasets: [{ label: 'Revenue', data: A.revenueByBranch.data, backgroundColor: '#EA6B00', borderRadius: 4 }] },
        options: { plugins: { legend: { display: false }, tooltip: { callbacks: { label: function (c) { return money(c.parsed.y); } } } }, scales: { y: { ticks: { callback: function (v) { return money(v); } } } } }
    });

    // 11. Employee performance
    make('chartEmployee', 'Top Employees', {
        type: 'bar',
        data: { labels: A.employeePerformance.labels, datasets: [{ label: 'Services', data: A.employeePerformance.data, backgroundColor: '#26b99a', borderRadius: 4 }] },
        options: { indexAxis: 'y', plugins: { legend: { display: false } } }
    });

    // 12. Top products
    make('chartProducts', 'Top Selling Products', {
        type: 'bar',
        data: { labels: A.topProducts.labels, datasets: [{ label: 'Revenue', data: A.topProducts.data, backgroundColor: '#3498db', borderRadius: 4 }] },
        options: { indexAxis: 'y', plugins: { legend: { display: false }, tooltip: { callbacks: { label: function (c) { return money(c.parsed.x); } } } } }
    });

    // ---- PDF export ----
    var btn = document.getElementById('exportPdfBtn');
    if (btn) {
        btn.addEventListener('click', function () {
            btn.disabled = true;
            var original = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating...';
            try {
                var payload = registry.map(function (r) {
                    var img = null;
                    try { img = r.chart.toBase64Image('image/png', 1); } catch (e) { img = null; }
                    return { title: r.title, image: img };
                }).filter(function (x) { return x.image; });
                document.getElementById('chartsInput').value = JSON.stringify(payload);
                document.getElementById('pdfExportForm').submit();
            } finally {
                setTimeout(function () { btn.disabled = false; btn.innerHTML = original; }, 2500);
            }
        });
    }
})();
</script>
@endsection
