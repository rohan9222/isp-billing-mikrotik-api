<?php

namespace App\Livewire\Mikrotik;

use App\Http\Controllers\MikrotikController;
use App\Models\RouterList;
use Livewire\Component;

class PppoeSetup extends Component
{
    public string $selectedRouter = '';
    public string $activeTab      = 'servers';

    // PPPoE Server form
    public string $srv_name           = '';
    public string $srv_interface      = '';
    public string $srv_service_name   = 'pppoe-server';
    public int    $srv_max_mtu        = 1480;
    public int    $srv_max_mru        = 1480;
    public int    $srv_keepalive      = 10;
    public string $srv_authentication = 'mschap2';
    public ?string $editServerId      = null;

    // PPP Secret form
    public string $sec_name     = '';
    public string $sec_password = '';
    public string $sec_profile  = 'default';
    public string $sec_service  = 'pppoe';
    public string $sec_comment  = '';
    public ?string $editSecretId = null;

    // Data
    public array $pppoeServers   = [];
    public array $pppProfiles    = [];
    public array $pppSecrets     = [];
    public array $activeSessions = [];
    public array $interfaces     = [];

    public function mount(): void
    {
        if (! hasAccess(['Super Admin'], ['mikrotik-setup'])) abort(403);
        $first = RouterList::where('action', 'connected')->first();
        if ($first) { $this->selectedRouter = $first->router_name; $this->loadData(); }
    }

    public function updatedSelectedRouter(): void { $this->resetData(); if ($this->selectedRouter) $this->loadData(); }

    public function loadData(): void
    {
        if (! $this->selectedRouter) return;
        $ctrl = app(MikrotikController::class);
        try {
            $this->pppoeServers   = $ctrl->getPppoeServers($this->selectedRouter);
            $this->pppProfiles    = $ctrl->getPppProfiles($this->selectedRouter);
            $this->pppSecrets     = $ctrl->getPppSecrets($this->selectedRouter);
            $this->activeSessions = $ctrl->getActivePppSessions($this->selectedRouter);
            $this->interfaces     = collect($ctrl->getInterfaces($this->selectedRouter))->pluck('name')->filter()->values()->toArray();
            $this->dispatch('reinit-datatables');
        } catch (\Exception $e) { flash()->error('Load error: ' . $e->getMessage()); }
    }

    public function editPppoeServer(array $p): void
    {
        $this->editServerId       = $p['.id'] ?? null;
        $this->srv_name           = $p['service-name'] ?? ''; // Display name
        $this->srv_interface      = $p['interface'] ?? '';
        $this->srv_service_name   = $p['service-name'] ?? 'pppoe-server';
        $this->srv_max_mtu        = (int)($p['max-mtu'] ?? 1480);
        $this->srv_max_mru        = (int)($p['max-mru'] ?? 1480);
        $this->srv_keepalive      = (int)($p['keepalive-timeout'] ?? 10);
        $this->srv_authentication = $p['authentication'] ?? 'mschap2';
    }

    public function addPppoeServer(): void
    {
        $this->validate([
            'srv_interface'    => 'required|string',
            'srv_service_name' => 'required|string|max:100',
            'srv_name'         => 'required|string|max:100',
        ]);
        try {
            app(MikrotikController::class)->addPppoeServer($this->selectedRouter, [
                'interface'      => $this->srv_interface,
                'service_name'   => $this->srv_service_name,
                'name'           => $this->srv_name,
                'max_mtu'        => $this->srv_max_mtu,
                'max_mru'        => $this->srv_max_mru,
                'keepalive'      => $this->srv_keepalive,
                'authentication' => $this->srv_authentication,
            ], $this->editServerId);
            flash()->success($this->editServerId ? 'PPPoE Server updated!' : 'PPPoE Server added!');
            $this->reset(['srv_name', 'srv_interface', 'srv_service_name', 'editServerId']);
            $this->pppoeServers = app(MikrotikController::class)->getPppoeServers($this->selectedRouter);
            $this->dispatch('reinit-datatables');
        } catch (\Exception $e) { flash()->error($e->getMessage()); }
    }

    public function removePppoeServer(string $name): void
    {
        try {
            app(MikrotikController::class)->removePppoeServer($this->selectedRouter, $name);
            flash()->success('PPPoE Server removed.');
            $this->pppoeServers = app(MikrotikController::class)->getPppoeServers($this->selectedRouter);
            $this->dispatch('reinit-datatables');
        } catch (\Exception $e) { flash()->error($e->getMessage()); }
    }

    public function editSecret(array $s): void
    {
        $this->editSecretId = $s['.id'] ?? null;
        $this->sec_name     = $s['name'] ?? '';
        $this->sec_password = $s['password'] ?? '';
        $this->sec_profile  = $s['profile'] ?? 'default';
        $this->sec_service  = $s['service'] ?? 'pppoe';
        $this->sec_comment  = $s['comment'] ?? '';
    }

    public function addSecret(): void
    {
        $this->validate([
            'sec_name'     => 'required|string|max:100',
            'sec_password' => 'required|string|max:100',
            'sec_profile'  => 'required|string|max:100',
        ]);
        try {
            app(MikrotikController::class)->addPppSecret($this->selectedRouter, [
                'name'     => $this->sec_name,
                'password' => $this->sec_password,
                'profile'  => $this->sec_profile,
                'service'  => $this->sec_service,
                'comment'  => $this->sec_comment,
            ], $this->editSecretId);
            flash()->success($this->editSecretId ? 'PPP Secret updated!' : 'PPP Secret added!');
            $this->reset(['sec_name', 'sec_password', 'sec_comment', 'editSecretId']);
            $this->pppSecrets = app(MikrotikController::class)->getPppSecrets($this->selectedRouter);
            $this->dispatch('reinit-datatables');
        } catch (\Exception $e) { flash()->error($e->getMessage()); }
    }

    public function removeSecret(string $name): void
    {
        try {
            app(MikrotikController::class)->deletePppSecret($this->selectedRouter, $name);
            flash()->success('Secret removed.');
            $this->pppSecrets = app(MikrotikController::class)->getPppSecrets($this->selectedRouter);
            $this->dispatch('reinit-datatables');
        } catch (\Exception $e) { flash()->error($e->getMessage()); }
    }

    public function refreshSessions(): void
    {
        try { $this->activeSessions = app(MikrotikController::class)->getActivePppSessions($this->selectedRouter); }
        catch (\Exception $e) { flash()->error($e->getMessage()); }
    }

    private function resetData(): void { $this->pppoeServers = $this->pppProfiles = $this->pppSecrets = $this->activeSessions = $this->interfaces = []; }

    public function render()
    {
        $routers = RouterList::where('action', 'connected')->orderBy('router_name')->get();
        return view('livewire.mikrotik.pppoe-setup', compact('routers'))->layout('layouts.app');
    }
}
