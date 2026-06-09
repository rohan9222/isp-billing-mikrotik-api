<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectionSummary;
use App\Models\HotspotSale;
use App\Models\IspExpense;
use App\Models\ResellerCommission;
use Carbon\Carbon;

class ProfitSummaryController extends Controller
{
    public function index()
    {
        $month = (int) request('month', now()->month);
        $year  = (int) request('year',  now()->year);

        $from = Carbon::create($year, $month, 1)->startOfMonth();
        $to   = Carbon::create($year, $month, 1)->endOfMonth();

        // ── Revenue ───────────────────────────────────────────────
        $collectionRevenue = CollectionSummary::whereBetween('collection_date', [$from, $to])
            ->sum('collection_amount');

        $hotspotRevenue = HotspotSale::whereBetween('sale_date', [$from, $to])
            ->sum('amount');

        $totalRevenue = $collectionRevenue + $hotspotRevenue;

        // ── Commissions (cost) ────────────────────────────────────
        $resellerCommissions = ResellerCommission::whereBetween('created_at', [$from, $to])
            ->sum('amount');

        // ── Expenses by category ──────────────────────────────────
        $expensesByCategory = IspExpense::whereBetween('expense_date', [$from, $to])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $totalExpenses = $expensesByCategory->sum();

        // ── Net Profit ────────────────────────────────────────────
        $netProfit = $totalRevenue - $resellerCommissions - $totalExpenses;

        // ── Detailed Lists ────────────────────────────────────────
        $itemPurchases = IspExpense::whereBetween('expense_date', [$from, $to])
            ->where('category', 'item_purchase')
            ->orderBy('expense_date', 'desc')
            ->get();

        $employeeSalaries = IspExpense::whereBetween('expense_date', [$from, $to])
            ->where('category', 'employee_salary')
            ->orderBy('expense_date', 'desc')
            ->get();

        // ── Year-over-year chart data (last 12 months) ────────────
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date       = Carbon::now()->subMonths($i);
            $mStart     = $date->copy()->startOfMonth();
            $mEnd       = $date->copy()->endOfMonth();

            $mRevenue  = CollectionSummary::whereBetween('collection_date', [$mStart, $mEnd])->sum('collection_amount')
                + HotspotSale::whereBetween('sale_date', [$mStart, $mEnd])->sum('amount');

            $mExpense  = IspExpense::whereBetween('expense_date', [$mStart, $mEnd])->sum('amount')
                + ResellerCommission::whereBetween('created_at', [$mStart, $mEnd])->sum('amount');

            $chartData[] = [
                'label'   => $date->format('M Y'),
                'revenue' => round($mRevenue, 2),
                'expense' => round($mExpense, 2),
                'profit'  => round($mRevenue - $mExpense, 2),
            ];
        }

        $categories  = IspExpense::$categories;
        $months      = [
            1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
            7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December',
        ];
        $years = range(now()->year, now()->year - 4);

        return view('admin.profit-summary', compact(
            'month', 'year',
            'totalRevenue', 'collectionRevenue', 'hotspotRevenue',
            'resellerCommissions',
            'expensesByCategory', 'totalExpenses',
            'netProfit',
            'chartData',
            'categories', 'months', 'years',
            'itemPurchases', 'employeeSalaries'
        ));
    }
}
