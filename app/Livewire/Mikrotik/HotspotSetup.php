<?php

namespace App\Livewire\Mikrotik;

use App\Http\Controllers\MikrotikController;
use App\Models\RouterList;
use Livewire\Component;

class HotspotSetup extends Component
{
    public string $selectedRouter = '';

    public string $activeTab = 'servers';

    // User form
    public string $u_name = '';

    public string $u_password = '';

    public string $u_profile = 'default';

    public string $u_comment = '';

    public ?string $editUserId = null;

    // User Profile form
    public string $up_name = '';

    public string $up_rate_limit = '';

    public int $up_shared_users = 1;

    public string $up_session_timeout = '';

    public ?string $editUserProfileId = null;

    // Data
    public array $servers = [];

    public array $profiles = [];

    public array $users = [];

    public array $userProfiles = [];

    public array $sessions = [];

    public function mount(): void
    {
        if (! hasAccess(['Super Admin'], ['mikrotik-setup'])) {
            abort(403);
        }
        $first = RouterList::where('action', 'connected')->first();
        if ($first) {
            $this->selectedRouter = $first->router_name;
            $this->loadData();
        }
    }

    public function updatedSelectedRouter(): void
    {
        $this->resetData();
        if ($this->selectedRouter) {
            $this->loadData();
        }
    }

    public function loadData(): void
    {
        if (! $this->selectedRouter) {
            return;
        }
        $ctrl = app(MikrotikController::class);
        try {
            $this->servers = $ctrl->getHotspotServers($this->selectedRouter);
            $this->profiles = $ctrl->getHotspotProfiles($this->selectedRouter);
            $this->users = $ctrl->getHotspotUsers($this->selectedRouter);
            $this->userProfiles = $ctrl->getHotspotUserProfiles($this->selectedRouter);
            $this->sessions = $ctrl->getHotspotActiveSessions($this->selectedRouter);
            $this->dispatch('reinit-datatables');
        } catch (\Exception $e) {
            flash()->error('Load error: '.$e->getMessage());
        }
    }

    public function editUser(array $u): void
    {
        $this->editUserId = $u['.id'] ?? null;
        $this->u_name = $u['name'] ?? '';
        $this->u_password = $u['password'] ?? '';
        $this->u_profile = $u['profile'] ?? 'default';
        $this->u_comment = $u['comment'] ?? '';
    }

    public function addUser(): void
    {
        $this->validate([
            'u_name' => 'required|string|max:100',
            'u_password' => 'required|string|max:100',
            'u_profile' => 'required|string',
        ]);
        try {
            app(MikrotikController::class)->addHotspotUser($this->selectedRouter, [
                'name' => $this->u_name, 'password' => $this->u_password,
                'profile' => $this->u_profile, 'comment' => $this->u_comment,
            ], $this->editUserId);
            flash()->success($this->editUserId ? 'Hotspot user updated!' : 'Hotspot user added!');
            $this->reset(['u_name', 'u_password', 'u_comment', 'editUserId']);
            $this->users = app(MikrotikController::class)->getHotspotUsers($this->selectedRouter);
            $this->dispatch('reinit-datatables');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }
    }

    public function removeUser(string $name): void
    {
        try {
            app(MikrotikController::class)->removeHotspotUser($this->selectedRouter, $name);
            flash()->success('User removed.');
            $this->users = app(MikrotikController::class)->getHotspotUsers($this->selectedRouter);
            $this->dispatch('reinit-datatables');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }
    }

    public function editUserProfile(array $up): void
    {
        $this->editUserProfileId = $up['.id'] ?? null;
        $this->up_name = $up['name'] ?? '';
        $this->up_rate_limit = $up['rate-limit'] ?? '';
        $this->up_shared_users = (int) ($up['shared-users'] ?? 1);
        $this->up_session_timeout = $up['session-timeout'] ?? '';
    }

    public function addUserProfile(): void
    {
        $this->validate([
            'up_name' => 'required|string|max:100',
            'up_shared_users' => 'required|integer|min:1',
        ]);
        try {
            app(MikrotikController::class)->addHotspotUserProfile($this->selectedRouter, [
                'name' => $this->up_name, 'rate_limit' => $this->up_rate_limit,
                'shared_users' => $this->up_shared_users, 'session_timeout' => $this->up_session_timeout,
            ], $this->editUserProfileId);
            flash()->success($this->editUserProfileId ? 'User profile updated!' : 'User profile added!');
            $this->reset(['up_name', 'up_rate_limit', 'up_session_timeout', 'editUserProfileId']);
            $this->up_shared_users = 1;
            $this->userProfiles = app(MikrotikController::class)->getHotspotUserProfiles($this->selectedRouter);
            $this->dispatch('reinit-datatables');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }
    }

    public function removeUserProfile(string $name): void
    {
        try {
            app(MikrotikController::class)->removeHotspotUserProfile($this->selectedRouter, $name);
            flash()->success('Profile removed.');
            $this->userProfiles = app(MikrotikController::class)->getHotspotUserProfiles($this->selectedRouter);
            $this->dispatch('reinit-datatables');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }
    }

    public function refreshSessions(): void
    {
        try {
            $this->sessions = app(MikrotikController::class)->getHotspotActiveSessions($this->selectedRouter);
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }
    }

    private function resetData(): void
    {
        $this->servers = $this->profiles = $this->users = $this->userProfiles = $this->sessions = [];
    }

    public function render()
    {
        $routers = RouterList::where('action', 'connected')->orderBy('router_name')->get();

        return view('livewire.mikrotik.hotspot-setup', compact('routers'))->layout('layouts.app');
    }
}
