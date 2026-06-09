<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageList;
use App\Models\Reseller;
use App\Models\User;
use App\Services\ResellerWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ResellerController extends Controller
{
    protected $walletService;

    public function __construct(ResellerWalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Display a listing of the resellers.
     */
    public function index()
    {
        $month = request('month', now()->month);
        $year  = request('year', now()->year);

        $resellersQuery = Reseller::with('user');

        if ($month !== 'all') {
            $monthVal = (int) $month;
            $yearVal  = (int) $year;
            $from = \Carbon\Carbon::create($yearVal, $monthVal, 1)->startOfMonth();
            $to   = \Carbon\Carbon::create($yearVal, $monthVal, 1)->endOfMonth();

            $resellersQuery->withSum(['commissions as total_profit' => function($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            }], 'amount')
            ->withSum(['collections as total_collected' => function($q) use ($from, $to) {
                $q->whereBetween('collection_date', [$from, $to]);
            }], 'collection_amount');
        } else {
            $resellersQuery->withSum('commissions as total_profit', 'amount')
                ->withSum('collections as total_collected', 'collection_amount');
        }

        $resellers = $resellersQuery->get();

        $months = [
            'all' => 'All Time',
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
            7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $years = range(now()->year, now()->year - 4);

        return view('admin.resellers.index', compact('resellers', 'month', 'year', 'months', 'years'));
    }

    /**
     * Show the form for creating a new reseller.
     */
    public function create()
    {
        $packages = PackageList::whereNull('reseller_id')->get();
        $resellerPermissions = $this->getResellerPermissions();
        $defaultPermissions  = $this->getDefaultPermissions();
        return view('admin.resellers.create', compact('packages', 'resellerPermissions', 'defaultPermissions'));
    }

    /**
     * Store a newly created reseller in storage.
     */
    public function store(Request $request)
    {
        $allowedPermissions = array_keys(array_merge(...array_values($this->getResellerPermissions())));

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'company' => 'nullable|string|max:255',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,suspended',
            'packages' => 'nullable|array',
            'packages.*' => 'exists:package_lists,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:' . implode(',', $allowedPermissions),
        ]);

        DB::beginTransaction();
        try {
            // 1. Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => bcrypt($request->password),
            ]);

            // Assign Reseller role
            $resellerRole = Role::firstOrCreate(['name' => 'Reseller']);
            $user->assignRole($resellerRole);

            // 2. Create the reseller profile
            $reseller = Reseller::create([
                'user_id' => $user->id,
                'company' => $request->company,
                'commission_percentage' => $request->commission_percentage,
                'balance' => 0.00,
                'status' => $request->status,
                'phone' => $request->mobile,
            ]);

            // 3. Sync packages
            if ($request->has('packages')) {
                $reseller->assignedPackages()->sync($request->packages);
            }

            // 4. Sync permissions
            $selectedPermissions = $request->input('permissions', []);
            $user->syncPermissions($selectedPermissions);

            // Log activity
            activity()
                ->performedOn($reseller)
                ->causedBy(auth()->user())
                ->log("Created reseller account for {$user->name} with " . count($selectedPermissions) . " permissions");

            DB::commit();
            flash()->success('Reseller created successfully.');
            return redirect()->route('admin.resellers.index');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create reseller: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified reseller.
     */
    public function edit(Reseller $reseller)
    {
        $reseller->load('user', 'assignedPackages');
        $packages = PackageList::whereNull('reseller_id')->get();
        $assignedPackageIds = $reseller->assignedPackages->pluck('id')->toArray();
        $resellerPermissions = $this->getResellerPermissions();
        $userPermissions = $reseller->user->getDirectPermissions()->pluck('name')->toArray();
        
        // Fetch activity logs
        $logs = Activity::where('causer_id', $reseller->user_id)
            ->orWhere(function($q) use ($reseller) {
                $q->where('subject_type', Reseller::class)
                  ->where('subject_id', $reseller->id);
            })
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('admin.resellers.edit', compact('reseller', 'packages', 'assignedPackageIds', 'logs', 'resellerPermissions', 'userPermissions'));
    }

    /**
     * Update the specified reseller in storage.
     */
    public function update(Request $request, Reseller $reseller)
    {
        $allowedPermissions = array_keys(array_merge(...array_values($this->getResellerPermissions())));

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $reseller->user_id,
            'mobile' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'company' => 'nullable|string|max:255',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,suspended',
            'packages' => 'nullable|array',
            'packages.*' => 'exists:package_lists,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:' . implode(',', $allowedPermissions),
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $user = $reseller->user;
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => bcrypt($request->password)]);
            }

            // Track changes for activity log
            $oldPercentage = (float) $reseller->commission_percentage;
            $newPercentage = (float) $request->commission_percentage;
            $oldStatus = $reseller->status;
            $newStatus = $request->status;
            $oldCompany = $reseller->company;
            $newCompany = $request->company;

            $changes = [];
            if ($oldPercentage !== $newPercentage) {
                $changes[] = "percentage: {$oldPercentage}% -> {$newPercentage}%";
            }
            if ($oldStatus !== $newStatus) {
                $changes[] = "status: {$oldStatus} -> {$newStatus}";
            }
            if ($oldCompany !== $newCompany) {
                $changes[] = "company updated";
            }

            // Update reseller
            $reseller->update([
                'company' => $request->company,
                'commission_percentage' => $request->commission_percentage,
                'status' => $request->status,
                'phone' => $request->mobile,
            ]);

            // Sync packages
            $reseller->assignedPackages()->sync($request->packages ?? []);

            // Sync permissions
            $selectedPermissions = $request->input('permissions', []);
            $reseller->user->syncPermissions($selectedPermissions);

            // Log activity
            $logMessage = "Updated reseller account for {$user->name}";
            if (count($changes) > 0) {
                $logMessage .= " (" . implode(', ', $changes) . ")";
            }
            $logMessage .= " with " . count($selectedPermissions) . " permissions";

            activity()
                ->performedOn($reseller)
                ->causedBy(auth()->user())
                ->log($logMessage);

            DB::commit();
            flash()->success('Reseller updated successfully.');
            return redirect()->route('admin.resellers.index');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update reseller: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified reseller from storage.
     */
    public function destroy(Reseller $reseller)
    {
        DB::beginTransaction();
        try {
            $user = $reseller->user;
            
            // Delete reseller (associated commissions/transactions/vouchers will cascade delete due to migration definition)
            $reseller->delete();
            $user->delete();

            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->log("Deleted reseller account for {$user->name}");

            DB::commit();
            flash()->success('Reseller deleted successfully.');
            return redirect()->route('admin.resellers.index');
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Failed to delete reseller: ' . $e->getMessage());
            return back();
        }
    }

    /**
     * Adjust the reseller's wallet balance.
     */
    public function adjustBalance(Request $request, Reseller $reseller)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:credit,debit',
            'description' => 'required|string|max:255',
        ]);

        try {
            $this->walletService->adjustBalance(
                $reseller,
                (float) $request->amount,
                $request->type,
                $request->description
            );

            // Log activity
            activity()
                ->performedOn($reseller)
                ->causedBy(auth()->user())
                ->log("Adjusted reseller wallet: {$request->type} of BDT {$request->amount} - Reason: {$request->description}");

            flash()->success('Wallet balance adjusted successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            flash()->error('Wallet adjustment failed: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Return categorized permissions available for resellers.
     * Each group: ['permission-name' => 'Label']
     */
    private function getResellerPermissions(): array
    {
        return [
            'Customer Management' => [
                'create-customer'   => 'Create Customer',
                'edit-customer'     => 'Edit Customer',
                'delete-customer'   => 'Delete Customer',
                'view-customer'     => 'View Customer',
            ],
            'Customer Status Control' => [
                'enable-customer'     => 'Enable Customer',
                'disable-customer'    => 'Disable Customer',
                'inactive-customer'   => 'Mark Inactive',
                'pending-customer'    => 'Mark Pending',
                'collection-customer' => 'Mark In-Collection',
            ],
            'Billing & Payments' => [
                'payment-collection'       => 'Collect Payment',
                'payment-collection-edit'  => 'Edit Payment',
                'payment-collection-invoice'  => 'Payment Invoice',
                'payment-history'          => 'View Payment History',
                'customer-billing-info'    => 'View Billing Info',
                'amount-collection'        => 'Amount Collection',
                'payment-collection-report' => 'Collection Report',
            ],
            'Reports & Lists' => [
                'collection-list'         => 'Collection List',
                'without-collection-list' => 'Without Collection List',
                'recent-customer'         => 'Recent Customers',
                'all-customer'            => 'All Customers List',
            ],
            'Setup & Access' => [
                'package-setup' => 'Package Management',
            ],
        ];
    }

    /**
     * Permissions that are pre-selected when creating a new reseller.
     */
    private function getDefaultPermissions(): array
    {
        return [
            'create-customer',
            'edit-customer',
            'view-customer',
            'payment-collection',
            'payment-history',
            'customer-billing-info',
            'amount-collection',
            'amount-collection-report',
            'package-setup',
            'address-setup',
        ];
    }
}
