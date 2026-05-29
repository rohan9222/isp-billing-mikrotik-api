<?php

namespace App\Http\Controllers;

use App\Models\CollectionSummary;
use App\Models\CustomersInfo;
use App\Models\HotspotSale;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = [];

        $currentYear = Carbon::now()->year;
        $previousYear = Carbon::now()->subYear()->year;
        $customersAllData = CustomersInfo::with('customerAddress', 'billing', 'official', 'pppUser')->get();
        $customersData = [
            'total' => CustomersInfo::count(),
            'active' => CustomersInfo::where('status', 'active')->count(),
            'pending' => CustomersInfo::where('status', 'pending')->count(),
            'free' => CustomersInfo::where('status', 'free')->count(),
            'temporary_disable' => CustomersInfo::where('status', 'disable')->count(),
            'inactive' => CustomersInfo::where('status', 'inactive')->count(),
            'recent' => CustomersInfo::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
        ];

        $billInformationData = [
            'monthly_rent' => $customersAllData->pluck('billing')->flatten()->sum('monthly_rent'),
            'previous_due' => -1 * $customersAllData->reject(function ($customer) {
                return $customer->status === 'inactive';
            })->pluck('billing')->flatten()->sum('previous_due'),
            'advance' => $customersAllData->pluck('billing')->flatten()->sum('advance'),
            'paid_amount' => $customersAllData->pluck('billing')->flatten()->sum('paid_amount'),
            'today_paid_amount' => CollectionSummary::whereDate('collection_date', Carbon::today())->sum('collection_amount'),
            'hotspot_total' => HotspotSale::sum('amount'),
            'hotspot_today' => HotspotSale::whereDate('sale_date', Carbon::today())->sum('amount'),
            'due_amount' => -1 * $customersAllData->reject(function ($customer) {
                return $customer->status === 'inactive';
            })->pluck('billing')->flatten()->sum('due_amount'),
        ];

        // Loop through each month
        for ($month = 1; $month <= 12; $month++) {
            // Cashflow (previous year's month's total)
            $cashflowPreviousYear = CollectionSummary::whereYear('collection_date', $previousYear)
                ->whereMonth('collection_date', $month)
                ->sum('collection_amount')
                + HotspotSale::whereYear('sale_date', $previousYear)
                    ->whereMonth('sale_date', $month)
                    ->sum('amount');

            // Income (current year's month's total)
            $incomeCurrentYear = CollectionSummary::whereYear('collection_date', $currentYear)
                ->whereMonth('collection_date', $month)
                ->sum('collection_amount')
                + HotspotSale::whereYear('sale_date', $currentYear)
                    ->whereMonth('sale_date', $month)
                    ->sum('amount');

            // Revenue Difference (current year's income - previous year's cashflow)
            $revenueDifference = $incomeCurrentYear - $cashflowPreviousYear;

            // Results array for each month
            $results[$month] = [
                'cashflow_previous_year' => $cashflowPreviousYear,
                'income_current_year' => $incomeCurrentYear,
                'revenue_difference' => $revenueDifference,
            ];
        }

        try {
            $systemOverview = app(MikrotikController::class)->systemOverview();
        } catch (\Exception $e) {
            $systemOverview = [];
        }

        // Calculate total cashflow, income, and revenue difference for the year
        return view('dashboard', compact('results', 'customersData', 'billInformationData', 'systemOverview'));
    }
}
