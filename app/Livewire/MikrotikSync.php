<?php

namespace App\Livewire;

use App\Models\CustomersInfo;
use App\Models\PPPSecrets;
use App\Models\RouterList;
use App\Services\MikrotikSSHService;
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

    // Updated rules method
    public function role()
    {
        return [
            'router_name' => 'required|string|max:255|unique:router_lists,router_name,'.$this->RouterListId,
            // Unique check for the combination of ip_address and ssh_port
            'ip_address' => 'required|ip|unique:router_lists,ip_address,'.$this->RouterListId.',id,ssh_port,'.$this->ssh_port,
            'username' => 'required|string|max:255',
            // Password required only if creating a new entry
            'password' => 'required_if:RouterListId,null|string|max:255',
            'ssh_port' => 'required|integer|min:1|max:65535',
        ];
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

    public function dataSync($id)
    {
        $routerList = RouterList::find($id);
        if ($routerList && $routerList->action === 'connected') {
            try {
                $mikrotikSSHService = new MikrotikSSHService(
                    $routerList->ip_address,
                    $routerList->ssh_port,
                    $routerList->username,
                    $routerList->password
                );
                $pppSecrets = $mikrotikSSHService->getPPPSecrets();
                dd($pppSecrets);
                // Mark all existing PPP secrets as removed
                PPPSecrets::where('router_name', $routerList->router_name)->update(['status' => 'removed']);

                foreach ($pppSecrets as $secret) {
                    $existingSecret = PPPSecrets::where('router_name', $routerList->router_name)
                        ->whereRaw('BINARY `username` = ?', [$secret['name']])->first();

                    // Update existing secret or create a new one
                    if ($existingSecret) {
                        $existingSecret->update([
                            'caller_id' => $secret['caller_id'] ?? '',
                            'service' => $secret['service'] ?? '-',
                            'profile' => $secret['profile'] ?? '-',
                            'password' => $secret['password'] ?? '',
                            'comment' => $secret['comment'] ?? '',
                            'status' => $secret['active'] === 'disable' ? 'disable' : $secret['active'],
                        ]);
                    } else {
                        PPPSecrets::create([
                            'router_name' => $routerList->router_name,
                            'username' => $secret['name'],
                            'caller_id' => $secret['caller_id'] ?? '',
                            'service' => $secret['service'] ?? '-',
                            'profile' => $secret['profile'] ?? '-',
                            'password' => $secret['password'] ?? '',
                            'comment' => $secret['comment'] ?? '',
                            'status' => $secret['active'] === 'disable' ? 'disable' : $secret['active'],
                        ]);
                    }
                }

                // Remove old secrets marked as removed
                // PPPSecrets::where('status', 'removed')->delete();
                flash()->success('Router Sync successfully!');
                // $this->dispatch('showToast', 'Router Sync successfully!', 'success');
            } catch (\Exception $e) {
                $this->dispatch('showToast', $e->getMessage(), 'error');
            }
        } else {
            flash()->error('Router is not connected or not found!');
            // $this->dispatch('showToast', 'Router is not connected or not found!', 'error');
        }
    }

    public function allSync()
    {
        $routerLists = RouterList::where('action', 'connected')->get();
        foreach ($routerLists as $routerList) {
            try {
                $mikrotikSSHService = new MikrotikSSHService(
                    $routerList->ip_address,
                    $routerList->ssh_port,
                    $routerList->username,
                    $routerList->password
                );
                $pppSecrets = $mikrotikSSHService->getPPPSecrets();

                PPPSecrets::where('router_name', $routerList->router_name)->update(['status' => 'removed']);

                foreach ($pppSecrets as $secret) {
                    $existingSecret = PPPSecrets::where('router_name', $routerList->router_name)
                        ->whereRaw('BINARY `username` = ?', [$secret['name']])->first();

                    if ($existingSecret) {
                        $existingSecret->update([
                            'caller_id' => $secret['caller_id'] ?? '',
                            'service' => $secret['service'] ?? '-',
                            'profile' => $secret['profile'] ?? '-',
                            'password' => $secret['password'] ?? '',
                            'comment' => $secret['comment'] ?? '',
                            'status' => $secret['active'] === 'disable' ? 'disable' : $secret['active'],
                        ]);
                        CustomersInfo::where('ppp_user_id', $existingSecret->id)
                            ->whereNotIn('status', ['free', 'pending', 'deleted'])
                            ->update(['status' => $existingSecret->status]);

                    } else {
                        PPPSecrets::create([
                            'router_name' => $routerList->router_name,
                            'username' => $secret['name'],
                            'caller_id' => $secret['caller_id'] ?? '',
                            'service' => $secret['service'] ?? '-',
                            'profile' => $secret['profile'] ?? '-',
                            'password' => $secret['password'] ?? '',
                            'comment' => $secret['comment'] ?? '',
                            'status' => $secret['active'] === 'disable' ? 'disable' : $secret['active'],
                        ]);
                    }
                }

                // PPPSecrets::where('status', 'removed')->delete();
                flash()->success('Router Sync successfully!');
                // $this->dispatch('showToast', 'Router Sync successfully!', 'success');
            } catch (\Exception $e) {
                flash()->error($e->getMessage());
                // $this->dispatch('showToast', $e->getMessage(), 'error');
            }
        }
    }

    public function submit()
    {
        $this->validate($this->role());

        // Data preparation for creating or updating a router
        $data = [
            'router_name' => $this->router_name,
            'ip_address' => $this->ip_address,
            'username' => $this->username,
            'ssh_port' => $this->ssh_port,
            'api_port' => $this->api_port, // Default API port
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
        // $this->dispatch('showToast', 'Router added successfully!', 'success');
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
