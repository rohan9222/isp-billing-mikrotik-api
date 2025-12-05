<?php

namespace App\Livewire;

use App\Models\CustomersInfo;
use App\Models\PPPSecrets;
use App\Models\RouterList;
use App\Models\BillingInfo;
use App\Models\OfficialInfo;

use App\Http\Controllers\MikrotikController;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class MikrotikSync extends Component
{
    use WithPagination;

    public $RouterListId;

    public $router_name;

    public $ip_address;

    public $username;

    public $password;

    public $ssh_port;

    public $api_port;

    public function mount()
    {
        if (! hasAccess(['Super Admin'], ['mikrotik-setup'])) {
            abort(403, 'Unauthorized action.');
        }
    }
    public function render()
    {
        // Pagination of routers
        $routers = RouterList::paginate(10);
        $routers->map(function ($router) {
            $router->user_list_count = PPPSecrets::where('router_name', $router->router_name)->where('status', '!=', 'removed')->count();
            return $router;
        });

        return view('livewire.mikrotik-sync', ['routers' => $routers])->layout('layouts.app');
    }

    public function rules()
    {
        return [
            'router_name' => ['required','string','max:255','unique:router_lists,router_name,' . $this->RouterListId,],
            'ip_address' => ['required','ip',
                function ($attribute, $value, $fail) {
                    $exists = RouterList::where('ip_address', $value)
                        ->where(function ($query) {
                            $query->where('ssh_port', $this->ssh_port)
                                ->orWhere('api_port', $this->api_port);
                        })
                        ->when($this->RouterListId, fn($q) => $q->where('id', '!=', $this->RouterListId))
                        ->exists();
                    if ($exists) {
                        $fail('This IP address is already used with the same SSH or API port.');
                        return;
                    }
                },
            ],
            'username' => 'required|string|max:255',
            'password' => 'required_if:RouterListId,null|string|max:255',
            'ssh_port' => 'nullable|required_without:api_port|integer|min:1|max:65535',
            'api_port' => 'nullable|required_without:ssh_port|integer|min:1|max:65535',
        ];
    }

    public function submit()
    {
        $this->validate($this->rules());

        // Data preparation for creating or updating a router
        $data = [
            'router_name' => $this->router_name,
            'ip_address' => $this->ip_address,
            'username' => $this->username,
            'ssh_port' => $this->ssh_port ?? null,
            'api_port' => $this->api_port ?? null,
        ];

        // Include password only if provided
        if (! empty($this->password)) {
            $data['password'] = $this->password;
        }

        RouterList::updateOrCreate(
            ['id' => $this->RouterListId],
            $data
        );

        $this->reset();
        flash()->success('Router added successfully!');
    }

    public function connect_toggle($routerId)
    {
        $router = RouterList::find($routerId);
        if ($router) {  // Check if router exists
            $router->action = $router->action === 'connected' ? 'disconnected' : 'connected';
            $router->save();
            flash()->success('Router '.$router->router_name.' is '.$router->action.' successfully!');
            // $this->dispatch('showToast', 'Router ' . $router->router_name . ' is ' . $router->action . ' successfully!', 'success');
            if ($router->action === 'connected') {
                $this->dataSync($routerId);
            }
        } else {
            flash()->error('Router not found!');
            // $this->dispatch('showToast', 'Router not found!', 'error');
        }
    }

    public function userSync($pppSecrets)
    {
        foreach ($pppSecrets as $index => $users) {
            if (is_array($users)) {
                PPPSecrets::where('router_name', $index)->where('status', '!=', 'removed')->update(['status' => 'removed']);
                foreach ($users as $user) {
                    try {
                        $lastLoggedOut = Carbon::createFromFormat('M/d/Y H:i:s', $user['last-logged-out'])->format('Y');
                        if ((int) $lastLoggedOut < 2000) {
                            $lastLoggedOut = null;
                        }else {
                            $lastLoggedOut = Carbon::createFromFormat('M/d/Y H:i:s', $user['last-logged-out'])->format('Y-m-d H:i:s');
                        }
                    } catch (\Exception $e) {
                        $lastLoggedOut = null;
                    }

                    $existingSecret = PPPSecrets::where('router_name', $index)
                        ->whereRaw('BINARY `username` = ?', [$user['name']])->first();

                    // Update existing secret or create a new one
                    if ($existingSecret) {
                        try {
                            $existingSecret->update([
                                'router_name' => $index,
                                'username' => $user['name'],
                                'password' => $user['password'] ?? '',
                                'service' => $user['service'] ?? '-',
                                'profile' => $user['profile'] ?? '-',
                                'caller_id' => $user['caller-id'] ?? '',
                                'comment' => $user['comment'] ?? '',
                                'ppp_remote_ip' => $user['ppp_remote_ip'] ?? '',
                                'bandwidth' => trim(($user['limit-bytes-in'] ?? '') . '/' . ($user['limit-bytes-out'] ?? ''), '/'),
                                'last_logged_out' => $lastLoggedOut ?? null,
                                'last_caller_id' => $user['last-caller-id'] ?? '',
                                'last_disconnect_reason' => $user['last-disconnect-reason'] ?? '',
                                'routes' => $user['routes'] ?? '',
                                'ipv6_routes' => $user['ipv6-routes'] ?? '',
                                'status' => $user['status'] ?? 'disable',
                            ]);
                            CustomersInfo::where('ppp_user_id', $existingSecret->id)
                                ->whereNotIn('status', ['free', 'pending', 'deleted'])
                                ->update(['status' => $existingSecret->status]);
                        } catch (\Exception $e) {
                            flash()->error($e->getMessage());
                        }
                    } else {
                        DB::beginTransaction();
                        try {
                            PPPSecrets::create([
                                'router_name' => $index,
                                'username' => $user['name'],
                                'password' => $user['password'] ?? '',
                                'service' => $user['service'] ?? '-',
                                'profile' => $user['profile'] ?? '-',
                                'caller_id' => $user['caller-id'] ?? '',
                                'comment' => $user['comment'] ?? '',
                                'ppp_remote_ip' => $user['ppp_remote_ip'] ?? '',
                                'bandwidth' => trim(($user['limit-bytes-in'] ?? '') . '/' . ($user['limit-bytes-out'] ?? ''), '/'),
                                'last_logged_out' => $lastLoggedOut ?? null,
                                'last_caller_id' => $user['last-caller-id'] ?? '',
                                'last_disconnect_reason' => $user['last-disconnect-reason'] ?? '',
                                'routes' => $user['routes'] ?? '',
                                'ipv6_routes' => $user['ipv6-routes'] ?? '',
                                'status' => $user['status'] ?? 'disable',
                            ]);

                            // create customers_info table record
                            $lastCustomer = CustomersInfo::latest('customer_unique_id')->pluck('customer_unique_id')->first();
                            if ($lastCustomer) {
                                $lastId = (int) substr($lastCustomer, 5); // 'FCNET' + 3 digits
                                $newId = 'FCNET'.($lastId + 1); // create new id
                            } else {
                                $newId = 'FCNET100'; // if no record found, start from 100
                            }
                            // Save customer info
                            $customer = new CustomersInfo;
                            $customer->customer_unique_id = $newId;
                            $customer->ppp_user_id = PPPSecrets::latest()->first()->id;
                            $customer->customer_name = $user['name'];
                            $customer->status = 'pending';
                            $customer->save();
                            // create billing_info table record
                            $customerBilling = new BillingInfo;
                            $customerBilling->customer_bill_unique_id = $customer->customer_unique_id;
                            $customerBilling->billing_type = 'prepaid';
                            $customerBilling->auto_disable_date = Carbon::now();
                            $customerBilling->save();
                            // create official_info table record
                            $customerOfficial = new OfficialInfo;
                            $customerOfficial->customer_office_unique_id = $customer->customer_unique_id;
                            $customerOfficial->save();
                            // commit transaction
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            flash()->error($e->getMessage());
                        }
                    }
                }
                PPPSecrets::where('router_name', $index)->where('status', 'removed')->where('updated_at', '<', Carbon::now()->subDays(7))->delete();
                flash()->success('Router ' . $index . ' users synchronized successfully!');
            }else {
                flash()->error($users);
            }
        }
    }

    public function dataSync($id)
    {
        $routerList = RouterList::find($id);
        if ($routerList && $routerList->action === 'connected') {
            $pppSecrets = app(MikrotikController::class)->routerList($routerList->router_name, '/ppp/secret/print', '/ppp secret print without-paging terse');
            if (is_array($pppSecrets)) {
                $this->userSync($pppSecrets);
            } else {
                flash()->error($pppSecrets);
            }
        } else {
            flash()->error('Router is not connected or not found!');
        }
    }

    public function allSync()
    {
        $pppSecrets = app(MikrotikController::class)->routerList(null, '/ppp/secret/print', '/ppp secret print without-paging terse');
        if (is_array($pppSecrets)) {
            $this->userSync($pppSecrets);
        } else {
            flash()->error($pppSecrets);
        }
    }

    public function edit($id)
    {
        $router = RouterList::find($id);  // Use meaningful variable name

        if ($router) {
            $this->RouterListId = $id;
            $this->router_name = $router->router_name;
            $this->ip_address = $router->ip_address;
            $this->username = $router->username;
            $this->password = '';  // Reset password field
            $this->ssh_port = $router->ssh_port;
            $this->api_port = $router->api_port;
        }
    }

    public function delete($id)
    {
        $router = RouterList::find($id);  // Check if router exists
        if ($router) {
            $router->delete();
            flash()->success('Router deleted successfully!');
            // $this->dispatch('showToast', 'Router deleted successfully!', 'success');
        } else {
            flash()->error('Router not found!');
            // $this->dispatch('showToast', 'Router not found!', 'error');
        }
    }
}
