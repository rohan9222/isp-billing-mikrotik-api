<?php

namespace App\Livewire\Mikrotik;

use App\Http\Controllers\MikrotikController;
use App\Models\PermittedUrl;
use App\Models\RouterList;
use App\Services\MikrotikSSHService;
use Livewire\Component;

class WalledGardenSetup extends Component
{
    public string $selectedRouter = '';
    public string $url_or_ip = '';
    public string $type = 'url';
    public string $comment = '';
    
    // Setup fields
    public string $portal_ip = '';
    public string $expired_speed = '128k/128k';

    public function mount(): void
    {
        $first = RouterList::where('action', 'connected')->first();
        if ($first) {
            $this->selectedRouter = (string) $first->id;
        }
        $this->portal_ip = request()->server('SERVER_ADDR') ?? '';
    }

    public function addPermitted()
    {
        $this->validate([
            'selectedRouter' => 'required',
            'url_or_ip' => 'required',
            'type' => 'required|in:url,ip',
        ]);

        $router = RouterList::find($this->selectedRouter);
        if (!$router) {
            flash()->error('Router not found.');
            return;
        }

        try {
            $ctrl = app(MikrotikController::class);
            $routerName = $router->router_name;
            
            // 1. Add to Hotspot Walled Garden
            try {
                if ($this->type == 'url') {
                    $ctrl->singleWrite($routerName, "/ip hotspot walled-garden add dst-host={$this->url_or_ip} comment=\"{$this->comment}\"");
                } else {
                    $ctrl->singleWrite($routerName, "/ip hotspot walled-garden ip add dst-address={$this->url_or_ip} action=accept comment=\"{$this->comment}\"");
                }
            } catch (\Exception $e) {
                // Ignore if it already exists or if hotspot package is missing
                if (!str_contains($e->getMessage(), 'already has') && !str_contains($e->getMessage(), 'no such item')) {
                    throw $e;
                }
            }

            // 2. Add to Firewall Address List for PPPoE redirection bypass
            try {
                $ctrl->singleWrite($routerName, "/ip firewall address-list add list=PERMITTED_URLS address={$this->url_or_ip} comment=\"{$this->comment}\"");
            } catch (\Exception $e) {
                // Ignore if it already exists
                if (!str_contains($e->getMessage(), 'already has')) {
                    throw $e;
                }
            }

            PermittedUrl::create([
                'router_id' => $router->id,
                'url_or_ip' => $this->url_or_ip,
                'type' => $this->type,
                'comment' => $this->comment,
            ]);

            flash()->success('Permitted URL/IP added and synced to Mikrotik router.');
            $this->reset(['url_or_ip', 'type', 'comment']);
            
        } catch (\Exception $e) {
            flash()->error('Mikrotik sync error: ' . $e->getMessage());
        }
    }

    public function deletePermitted($id)
    {
        $permitted = PermittedUrl::find($id);
        if (!$permitted) return;

        $router = RouterList::find($permitted->router_id);
        if ($router) {
            try {
                $ctrl = app(MikrotikController::class);
                $routerName = $router->router_name;
                
                // Remove from Hotspot Walled Garden
                try {
                    if ($permitted->type == 'url') {
                        $ctrl->singleWrite($routerName, "/ip hotspot walled-garden remove [/ip hotspot walled-garden find dst-host=\"{$permitted->url_or_ip}\"]");
                    } else {
                        $ctrl->singleWrite($routerName, "/ip hotspot walled-garden ip remove [/ip hotspot walled-garden ip find dst-address=\"{$permitted->url_or_ip}\"]");
                    }
                } catch (\Exception $e) {
                    // Ignore "no such item" errors on delete
                }

                // Remove from Firewall Address List
                try {
                    $ctrl->singleWrite($routerName, "/ip firewall address-list remove [/ip firewall address-list find address=\"{$permitted->url_or_ip}\" list=PERMITTED_URLS]");
                } catch (\Exception $e) {
                    // Ignore "no such item" errors on delete
                }

            } catch (\Exception $e) {
                flash()->error('Failed to remove from Mikrotik router: ' . $e->getMessage());
                return;
            }
        }

        $permitted->delete();
        flash()->success('Permitted URL/IP removed.');
    }

    public function runRouterSetup()
    {
        $this->validate([
            'selectedRouter' => 'required',
            'portal_ip' => 'required|ip',
            'expired_speed' => 'required',
        ]);

        $router = RouterList::find($this->selectedRouter);
        if (!$router) {
            flash()->error('Router not found.');
            return;
        }

        try {
            $ctrl = app(MikrotikController::class);
            $routerName = $router->router_name;

            // 1. Create/Update PPP Profile
            try {
                // Try to add, catch if exists
                $ctrl->singleWrite($routerName, "/ppp profile add name=Expired rate-limit=\"{$this->expired_speed}\" address-list=EXPIRED_USERS comment=\"Managed by ISP Billing\"");
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'already has')) {
                    $ctrl->singleWrite($routerName, "/ppp profile set [find name=Expired] rate-limit=\"{$this->expired_speed}\" address-list=EXPIRED_USERS");
                } else {
                    throw $e;
                }
            }

            // 2. Create/Update Hotspot User Profile
            try {
                $ctrl->singleWrite($routerName, "/ip hotspot user profile add name=Expired address-list=EXPIRED_USERS rate-limit=\"{$this->expired_speed}\" comment=\"Managed by ISP Billing\"");
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'already has')) {
                    $ctrl->singleWrite($routerName, "/ip hotspot user profile set [find name=Expired] address-list=EXPIRED_USERS rate-limit=\"{$this->expired_speed}\"");
                } elseif (str_contains($e->getMessage(), 'no such item')) {
                    // Hotspot package likely missing, ignore
                } else {
                    throw $e;
                }
            }

            // 3. Create Firewall NAT Rule
            // First, remove old redirect rule if exists to avoid duplicates
            try {
                $ctrl->singleWrite($routerName, "/ip firewall nat remove [find comment=\"Redirect Expired Users\"]");
            } catch (\Exception $e) {}

            $ctrl->singleWrite($routerName, "/ip firewall nat add chain=dstnat src-address-list=EXPIRED_USERS dst-address-list=!PERMITTED_URLS protocol=tcp dst-port=80 action=dst-nat to-addresses=\"{$this->portal_ip}\" to-ports=80 comment=\"Redirect Expired Users\"");

            flash()->success('Router redirection setup completed successfully!');
            
        } catch (\Exception $e) {
            flash()->error('Router setup failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $urls = [];
        if ($this->selectedRouter) {
            $urls = PermittedUrl::where('router_id', $this->selectedRouter)->get();
        }

        return view('livewire.mikrotik.walled-garden-setup', [
            'routers' => RouterList::where('action', 'connected')->get(),
            'urls' => $urls
        ])->layout('layouts.app', ['title' => 'Walled Garden Setup']);
    }
}
