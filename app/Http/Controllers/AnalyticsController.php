<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (! isAdmin(Auth::user()->role_id)) {
                abort(403, 'Only administrators can access Analytics.');
            }

            return $next($request);
        });
    }

    /**
     * Build the last N months as 'Y-m' keys with readable labels.
     */
    private function monthWindow($count = 12)
    {
        $keys = [];
        $labels = [];
        for ($i = $count - 1; $i >= 0; $i--) {
            $ts = strtotime(date('Y-m-01')." -$i months");
            $keys[] = date('Y-m', $ts);
            $labels[] = date('M Y', $ts);
        }

        return [$keys, $labels];
    }

    /**
     * Aggregate every dataset the Analytics dashboard needs.
     */
    private function buildAnalytics()
    {
        [$monthKeys, $monthLabels] = $this->monthWindow(12);

        /* ---------------- Financial KPIs ---------------- */
        $totalRevenue = (float) DB::table('tbl_invoices')->where('soft_delete', 0)->sum('grand_total');
        $totalReceived = (float) DB::table('tbl_invoices')->where('soft_delete', 0)->sum('amount_recevied');
        $outstanding = max($totalRevenue - $totalReceived, 0);

        $totalExpenses = (float) DB::table('tbl_expenses_history_records as eh')
            ->join('tbl_expenses as e', 'e.id', '=', 'eh.tbl_expenses_id')
            ->sum(DB::raw('CAST(eh.expense_amount AS DECIMAL(15,2))'));

        $extraIncome = (float) DB::table('tbl_income_history_records')->where('soft_delete', 0)->sum('income_amount');

        $netProfit = ($totalRevenue + $extraIncome) - $totalExpenses;

        $invoiceCount = (int) DB::table('tbl_invoices')->where('soft_delete', 0)->count();
        $avgInvoice = $invoiceCount > 0 ? $totalRevenue / $invoiceCount : 0;

        /* ---------------- Entity counts ---------------- */
        $counts = [
            'customers' => (int) DB::table('users')->where([['role', 'Customer'], ['soft_delete', 0]])->count(),
            'employees' => (int) DB::table('users')->where([['role', 'employee'], ['soft_delete', 0]])->count(),
            'vehicles' => (int) DB::table('tbl_vehicles')->where('soft_delete', 0)->count(),
            'services' => (int) DB::table('tbl_services')->where([['soft_delete', 0], ['is_quotation', 0]])->count(),
            'quotations' => (int) DB::table('tbl_services')->where([['soft_delete', 0], ['is_quotation', 1]])->count(),
            'invoices' => $invoiceCount,
            'products' => (int) DB::table('tbl_products')->where('soft_delete', 0)->count(),
            'branches' => (int) DB::table('branches')->where([['soft_delete', 0], ['branch_status', 1]])->count(),
        ];

        /* ---------------- Revenue vs Expense trend (12 mo) ---------------- */
        $revByMonth = DB::table('tbl_invoices')
            ->where('soft_delete', 0)
            ->whereNotNull('date')
            ->select(DB::raw("DATE_FORMAT(date,'%Y-%m') as ym"), DB::raw('SUM(grand_total) as total'))
            ->groupBy('ym')->pluck('total', 'ym');

        $expByMonth = DB::table('tbl_expenses_history_records as eh')
            ->join('tbl_expenses as e', 'e.id', '=', 'eh.tbl_expenses_id')
            ->whereNotNull('e.date')
            ->select(DB::raw("DATE_FORMAT(e.date,'%Y-%m') as ym"), DB::raw('SUM(CAST(eh.expense_amount AS DECIMAL(15,2))) as total'))
            ->groupBy('ym')->pluck('total', 'ym');

        $revenueTrend = [];
        $expenseTrend = [];
        $profitTrend = [];
        foreach ($monthKeys as $k) {
            $r = (float) ($revByMonth[$k] ?? 0);
            $e = (float) ($expByMonth[$k] ?? 0);
            $revenueTrend[] = round($r, 2);
            $expenseTrend[] = round($e, 2);
            $profitTrend[] = round($r - $e, 2);
        }

        /* ---------------- Invoice payment status ---------------- */
        $statusRaw = DB::table('tbl_invoices')->where('soft_delete', 0)
            ->select('payment_status', DB::raw('COUNT(*) as c'))
            ->groupBy('payment_status')->pluck('c', 'payment_status');
        $paymentStatus = [
            'labels' => ['Unpaid', 'Half Paid', 'Fully Paid'],
            'data' => [
                (int) ($statusRaw[0] ?? 0),
                (int) ($statusRaw[1] ?? 0),
                (int) ($statusRaw[2] ?? 0),
            ],
        ];

        /* ---------------- Invoice type breakdown ---------------- */
        $typeRaw = DB::table('tbl_invoices')->where('soft_delete', 0)
            ->select('type', DB::raw('COUNT(*) as c'))
            ->groupBy('type')->pluck('c', 'type');
        $invoiceType = [
            'labels' => ['Service', 'Vehicle Sales', 'Part Sales'],
            'data' => [
                (int) ($typeRaw[0] ?? 0),
                (int) ($typeRaw[1] ?? 0),
                (int) ($typeRaw[2] ?? 0),
            ],
        ];

        /* ---------------- Payment method distribution ---------------- */
        $payMethod = DB::table('tbl_payment_records')->where('soft_delete', 0)
            ->select('payment_type', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_type')->orderByDesc('total')->get();
        $paymentMethod = [
            'labels' => $payMethod->pluck('payment_type')->map(fn ($v) => $v ?: 'Other')->toArray(),
            'data' => $payMethod->pluck('total')->map(fn ($v) => round((float) $v, 2))->toArray(),
        ];

        /* ---------------- Services created per month ---------------- */
        $svcByMonth = DB::table('tbl_services')
            ->where([['soft_delete', 0], ['is_quotation', 0]])
            ->whereNotNull('service_date')
            ->select(DB::raw("DATE_FORMAT(service_date,'%Y-%m') as ym"), DB::raw('COUNT(*) as c'))
            ->groupBy('ym')->pluck('c', 'ym');
        $servicesTrend = [];
        foreach ($monthKeys as $k) {
            $servicesTrend[] = (int) ($svcByMonth[$k] ?? 0);
        }

        /* ---------------- Service completion status ---------------- */
        $doneCount = (int) DB::table('tbl_jobcard_details as j')
            ->join('tbl_services as s', 's.id', '=', 'j.service_id')
            ->where([['s.soft_delete', 0], ['j.done_status', 1]])->count();
        $totalJob = (int) DB::table('tbl_jobcard_details as j')
            ->join('tbl_services as s', 's.id', '=', 'j.service_id')
            ->where('s.soft_delete', 0)->count();
        $serviceCompletion = [
            'labels' => ['Completed', 'In Progress'],
            'data' => [$doneCount, max($totalJob - $doneCount, 0)],
        ];

        /* ---------------- Vehicles by type ---------------- */
        $vehByType = DB::table('tbl_vehicles as v')
            ->leftJoin('tbl_vehicle_types as t', 't.id', '=', 'v.vehicletype_id')
            ->where('v.soft_delete', 0)
            ->select(DB::raw('COALESCE(t.vehicle_type, "Unknown") as label'), DB::raw('COUNT(*) as c'))
            ->groupBy('label')->orderByDesc('c')->limit(8)->get();
        $vehicleType = [
            'labels' => $vehByType->pluck('label')->toArray(),
            'data' => $vehByType->pluck('c')->map(fn ($v) => (int) $v)->toArray(),
        ];

        /* ---------------- Vehicles by brand (top 8) ---------------- */
        $vehByBrand = DB::table('tbl_vehicles as v')
            ->leftJoin('tbl_vehicle_brands as b', 'b.id', '=', 'v.vehiclebrand_id')
            ->where('v.soft_delete', 0)
            ->select(DB::raw('COALESCE(b.vehicle_brand, "Unknown") as label'), DB::raw('COUNT(*) as c'))
            ->groupBy('label')->orderByDesc('c')->limit(8)->get();
        $vehicleBrand = [
            'labels' => $vehByBrand->pluck('label')->toArray(),
            'data' => $vehByBrand->pluck('c')->map(fn ($v) => (int) $v)->toArray(),
        ];

        /* ---------------- Top customers by revenue ---------------- */
        $topCustRaw = DB::table('tbl_invoices')->where('soft_delete', 0)
            ->whereNotNull('customer_id')
            ->select('customer_id', DB::raw('SUM(grand_total) as total'), DB::raw('COUNT(*) as inv'))
            ->groupBy('customer_id')->orderByDesc('total')->limit(10)->get();
        $topCustomers = $topCustRaw->map(function ($row) {
            $u = User::find($row->customer_id);

            return [
                'name' => $u ? trim($u->name.' '.$u->lastname) : 'Customer #'.$row->customer_id,
                'total' => round((float) $row->total, 2),
                'invoices' => (int) $row->inv,
            ];
        })->toArray();

        /* ---------------- Top selling parts/products ---------------- */
        $topProdRaw = DB::table('tbl_sale_parts as sp')
            ->leftJoin('tbl_products as p', 'p.id', '=', 'sp.product_id')
            ->where('sp.soft_delete', 0)
            ->select(DB::raw('COALESCE(p.name, "Unknown") as label'),
                DB::raw('SUM(sp.quantity) as qty'),
                DB::raw('SUM(sp.total_price) as total'))
            ->groupBy('label')->orderByDesc('total')->limit(10)->get();
        $topProducts = [
            'labels' => $topProdRaw->pluck('label')->toArray(),
            'data' => $topProdRaw->pluck('total')->map(fn ($v) => round((float) $v, 2))->toArray(),
            'rows' => $topProdRaw->map(fn ($r) => [
                'name' => $r->label,
                'qty' => (int) $r->qty,
                'total' => round((float) $r->total, 2),
            ])->toArray(),
        ];

        /* ---------------- Employee performance (services handled) ---------------- */
        $empRaw = DB::table('tbl_services as s')
            ->join('users as u', 'u.id', '=', 's.assign_to')
            ->where([['s.soft_delete', 0], ['s.is_quotation', 0]])
            ->select(DB::raw("CONCAT(u.name,' ',COALESCE(u.lastname,'')) as label"), DB::raw('COUNT(*) as c'))
            ->groupBy('label')->orderByDesc('c')->limit(10)->get();
        $employeePerformance = [
            'labels' => $empRaw->pluck('label')->map(fn ($v) => trim($v))->toArray(),
            'data' => $empRaw->pluck('c')->map(fn ($v) => (int) $v)->toArray(),
        ];

        /* ---------------- Revenue by branch ---------------- */
        $branchRaw = DB::table('tbl_invoices as i')
            ->leftJoin('branches as b', 'b.id', '=', 'i.branch_id')
            ->where('i.soft_delete', 0)
            ->select(DB::raw('COALESCE(b.branch_name, "Unassigned") as label'), DB::raw('SUM(i.grand_total) as total'))
            ->groupBy('label')->orderByDesc('total')->limit(10)->get();
        $revenueByBranch = [
            'labels' => $branchRaw->pluck('label')->toArray(),
            'data' => $branchRaw->pluck('total')->map(fn ($v) => round((float) $v, 2))->toArray(),
        ];

        /* ---------------- New customers per month ---------------- */
        $custByMonth = DB::table('users')->where([['role', 'Customer'], ['soft_delete', 0]])
            ->whereNotNull('created_at')
            ->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m') as ym"), DB::raw('COUNT(*) as c'))
            ->groupBy('ym')->pluck('c', 'ym');
        $newCustomersTrend = [];
        foreach ($monthKeys as $k) {
            $newCustomersTrend[] = (int) ($custByMonth[$k] ?? 0);
        }

        /* ---------------- Low stock products ---------------- */
        $lowStock = DB::table('tbl_products')->where('soft_delete', 0)
            ->whereRaw('CAST(quantity AS DECIMAL(10,2)) <= 10')
            ->select('name', 'code', 'quantity')
            ->orderByRaw('CAST(quantity AS DECIMAL(10,2)) ASC')
            ->limit(15)->get()
            ->map(fn ($r) => [
                'name' => $r->name,
                'code' => $r->code,
                'quantity' => (int) $r->quantity,
            ])->toArray();

        return [
            'kpi' => [
                'totalRevenue' => round($totalRevenue, 2),
                'totalReceived' => round($totalReceived, 2),
                'outstanding' => round($outstanding, 2),
                'totalExpenses' => round($totalExpenses, 2),
                'extraIncome' => round($extraIncome, 2),
                'netProfit' => round($netProfit, 2),
                'avgInvoice' => round($avgInvoice, 2),
            ],
            'counts' => $counts,
            'monthLabels' => $monthLabels,
            'revenueTrend' => $revenueTrend,
            'expenseTrend' => $expenseTrend,
            'profitTrend' => $profitTrend,
            'paymentStatus' => $paymentStatus,
            'invoiceType' => $invoiceType,
            'paymentMethod' => $paymentMethod,
            'servicesTrend' => $servicesTrend,
            'serviceCompletion' => $serviceCompletion,
            'vehicleType' => $vehicleType,
            'vehicleBrand' => $vehicleBrand,
            'topCustomers' => $topCustomers,
            'topProducts' => $topProducts,
            'employeePerformance' => $employeePerformance,
            'revenueByBranch' => $revenueByBranch,
            'newCustomersTrend' => $newCustomersTrend,
            'lowStock' => $lowStock,
        ];
    }

    public function index()
    {
        $data = $this->buildAnalytics();
        $currency = getCurrencySymbols();

        $chartData = [
            'currency' => $currency,
            'monthLabels' => $data['monthLabels'],
            'revenueTrend' => $data['revenueTrend'],
            'expenseTrend' => $data['expenseTrend'],
            'profitTrend' => $data['profitTrend'],
            'paymentStatus' => $data['paymentStatus'],
            'invoiceType' => $data['invoiceType'],
            'paymentMethod' => $data['paymentMethod'],
            'servicesTrend' => $data['servicesTrend'],
            'serviceCompletion' => $data['serviceCompletion'],
            'vehicleType' => $data['vehicleType'],
            'vehicleBrand' => $data['vehicleBrand'],
            'topProducts' => $data['topProducts'],
            'employeePerformance' => $data['employeePerformance'],
            'revenueByBranch' => $data['revenueByBranch'],
            'newCustomersTrend' => $data['newCustomersTrend'],
        ];

        return view('analytics.index', array_merge($data, [
            'currency' => $currency,
            'chartData' => $chartData,
        ]));
    }

    /**
     * Export the analytics dashboard as a PDF.
     * The client posts the rendered chart images (base64 PNG) plus KPI values,
     * which are embedded into a print-friendly Mpdf document.
     */
    public function exportPdf(Request $request)
    {
        $charts = json_decode($request->input('charts', '[]'), true) ?: [];
        $data = $this->buildAnalytics();
        $currency = getCurrencySymbols();
        $logo = DB::table('tbl_settings')->first();
        $systemName = $logo->system_name ?? 'Garage';
        $generated = date('d M Y, H:i');

        $fmt = function ($n) use ($currency) {
            return $currency.' '.number_format((float) $n, 2);
        };

        $kpi = $data['kpi'];
        $counts = $data['counts'];

        $html = '<html><head><style>
            body { font-family: "dejavusans"; color:#2d3748; }
            h1 { font-size:20pt; color:#2b2f77; margin:0 0 2px 0; }
            h2 { font-size:13pt; color:#2b2f77; border-bottom:2px solid #2b2f77; padding-bottom:4px; margin:18px 0 10px 0; }
            .sub { color:#718096; font-size:9pt; margin-bottom:14px; }
            .kpi-table { width:100%; border-collapse:collapse; margin-bottom:6px; }
            .kpi-table td { width:25%; padding:10px; border:1px solid #e2e8f0; background:#f8fafc; }
            .kpi-label { font-size:8pt; color:#718096; text-transform:uppercase; }
            .kpi-value { font-size:13pt; font-weight:bold; color:#2b2f77; }
            table.data { width:100%; border-collapse:collapse; font-size:9pt; }
            table.data th { background:#2b2f77; color:#fff; padding:6px; text-align:left; }
            table.data td { padding:6px; border:1px solid #e2e8f0; }
            .chart-img { width:100%; margin:6px 0 14px 0; }
            .half { width:49%; }
        </style></head><body>';

        $html .= '<h1>'.htmlspecialchars($systemName).' &mdash; Business Analytics</h1>';
        $html .= '<div class="sub">Generated on '.$generated.'</div>';

        // KPI grid
        $html .= '<table class="kpi-table"><tr>'
            .'<td><div class="kpi-label">Total Revenue</div><div class="kpi-value">'.$fmt($kpi['totalRevenue']).'</div></td>'
            .'<td><div class="kpi-label">Amount Received</div><div class="kpi-value">'.$fmt($kpi['totalReceived']).'</div></td>'
            .'<td><div class="kpi-label">Outstanding</div><div class="kpi-value">'.$fmt($kpi['outstanding']).'</div></td>'
            .'<td><div class="kpi-label">Total Expenses</div><div class="kpi-value">'.$fmt($kpi['totalExpenses']).'</div></td>'
            .'</tr><tr>'
            .'<td><div class="kpi-label">Net Profit</div><div class="kpi-value">'.$fmt($kpi['netProfit']).'</div></td>'
            .'<td><div class="kpi-label">Avg Invoice</div><div class="kpi-value">'.$fmt($kpi['avgInvoice']).'</div></td>'
            .'<td><div class="kpi-label">Invoices</div><div class="kpi-value">'.$counts['invoices'].'</div></td>'
            .'<td><div class="kpi-label">Services</div><div class="kpi-value">'.$counts['services'].'</div></td>'
            .'</tr><tr>'
            .'<td><div class="kpi-label">Customers</div><div class="kpi-value">'.$counts['customers'].'</div></td>'
            .'<td><div class="kpi-label">Vehicles</div><div class="kpi-value">'.$counts['vehicles'].'</div></td>'
            .'<td><div class="kpi-label">Products</div><div class="kpi-value">'.$counts['products'].'</div></td>'
            .'<td><div class="kpi-label">Quotations</div><div class="kpi-value">'.$counts['quotations'].'</div></td>'
            .'</tr></table>';

        // Charts (as images captured client-side)
        if (! empty($charts)) {
            $html .= '<h2>Visual Analytics</h2>';
            foreach ($charts as $chart) {
                if (empty($chart['image'])) {
                    continue;
                }
                $title = htmlspecialchars($chart['title'] ?? '');
                $html .= '<div style="margin-bottom:12px;">';
                $html .= '<div style="font-weight:bold;font-size:10pt;color:#2b2f77;margin-bottom:3px;">'.$title.'</div>';
                $html .= '<img class="chart-img" src="'.$chart['image'].'" />';
                $html .= '</div>';
            }
        }

        // Top customers table
        if (! empty($data['topCustomers'])) {
            $html .= '<h2>Top Customers by Revenue</h2><table class="data"><tr><th>#</th><th>Customer</th><th>Invoices</th><th>Revenue</th></tr>';
            foreach ($data['topCustomers'] as $i => $c) {
                $html .= '<tr><td>'.($i + 1).'</td><td>'.htmlspecialchars($c['name']).'</td><td>'.$c['invoices'].'</td><td>'.$fmt($c['total']).'</td></tr>';
            }
            $html .= '</table>';
        }

        // Top products table
        if (! empty($data['topProducts']['rows'])) {
            $html .= '<h2>Top Selling Products</h2><table class="data"><tr><th>#</th><th>Product</th><th>Qty Sold</th><th>Revenue</th></tr>';
            foreach ($data['topProducts']['rows'] as $i => $p) {
                $html .= '<tr><td>'.($i + 1).'</td><td>'.htmlspecialchars($p['name']).'</td><td>'.$p['qty'].'</td><td>'.$fmt($p['total']).'</td></tr>';
            }
            $html .= '</table>';
        }

        // Low stock table
        if (! empty($data['lowStock'])) {
            $html .= '<h2>Low Stock Alerts</h2><table class="data"><tr><th>Product</th><th>Code</th><th>Qty Remaining</th></tr>';
            foreach ($data['lowStock'] as $p) {
                $html .= '<tr><td>'.htmlspecialchars($p['name']).'</td><td>'.htmlspecialchars($p['code']).'</td><td>'.$p['quantity'].'</td></tr>';
            }
            $html .= '</table>';
        }

        $html .= '</body></html>';

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_top' => 14, 'margin_bottom' => 14]);
        $mpdf->autoLangToFont = true;
        $mpdf->autoScriptToLang = true;
        $mpdf->WriteHTML($html);

        $filename = 'Analytics-Report-'.date('Y-m-d').'.pdf';
        $content = $mpdf->Output($filename, Destination::STRING_RETURN);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
