<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\CustomersInfo;
use App\Models\ResellerCommission;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResellerDashboardController extends Controller
{
    public function index()
    {
        $reseller = auth()->user()->reseller;

        // Stats queries
        $totalCustomers = CustomersInfo::where('reseller_id', $reseller->id)->count();
        
        $totalEarnings = ResellerCommission::where('reseller_id', $reseller->id)->sum('amount');
        
        $earningsToday = ResellerCommission::where('reseller_id', $reseller->id)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        $earningsMonth = ResellerCommission::where('reseller_id', $reseller->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        $activeVouchersCount = Voucher::where('reseller_id', $reseller->id)
            ->where('status', 'unused')
            ->whereDate('expiry_date', '>=', Carbon::today())
            ->count();

        $walletBalance = $reseller->balance;

        // Package sales breakdown
        $packageSales = CustomersInfo::where('reseller_id', $reseller->id)
            ->whereNotNull('package_id')
            ->groupBy('package_id')
            ->select('package_id', DB::raw('count(*) as count'))
            ->with('package')
            ->get();

        // Commission history chart data (last 7 days)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $amount = ResellerCommission::where('reseller_id', $reseller->id)
                ->whereDate('created_at', $date)
                ->sum('amount');
            
            $chartData[] = [
                'day' => $date->format('D, M d'),
                'amount' => (float) $amount
            ];
        }

        // Recent customers
        $recentCustomers = CustomersInfo::where('reseller_id', $reseller->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('reseller.dashboard', compact(
            'totalCustomers',
            'totalEarnings',
            'earningsToday',
            'earningsMonth',
            'activeVouchersCount',
            'walletBalance',
            'packageSales',
            'chartData',
            'recentCustomers'
        ));
    }
}
