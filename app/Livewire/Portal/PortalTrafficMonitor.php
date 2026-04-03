<?php

namespace App\Livewire\Portal;

use App\Http\Controllers\MikrotikController;
use Livewire\Component;

class PortalTrafficMonitor extends Component
{
    public string $selectedRouter = '';

    public string $selectedInterface = '';

    // UI Data
    public float $rxSpeed = 0;

    public float $txSpeed = 0;

    public float $lastPollTime = 0;

    public function mount(): void
    {
        $user = auth()->guard('ppp')->user();
        if ($user) {
            $this->selectedRouter = $user->router_name ?? '';
            // For PPPoE, interface is usually <pppoe-USERNAME>
            $this->selectedInterface = '<pppoe-'.$user->username.'>';
        }
    }

    public function poll(): void
    {
        // Throttle to prevent request stacking if server/mikrotik is slow
        if (! $this->selectedRouter || ! $this->selectedInterface) {
            return;
        }

        // Ensure at least 1.5s passed since last backend poll to prevent overlapping
        if (microtime(true) - $this->lastPollTime < 1.5) {
            return;
        }
        $this->lastPollTime = microtime(true);

        try {
            // Ensure session is still valid for guard 'ppp'
            if (! auth()->guard('ppp')->check()) {
                $this->redirect(route('login'));

                return;
            }

            $ctrl = app(MikrotikController::class);
            $data = $ctrl->getLiveTraffic($this->selectedRouter, $this->selectedInterface);

            // Only update and dispatch if we got valid data
            if (isset($data['rx-bits-per-second']) || isset($data['tx-bits-per-second'])) {
                $this->rxSpeed = (float) ($data['rx-bits-per-second'] ?? 0);
                $this->txSpeed = (float) ($data['tx-bits-per-second'] ?? 0);
                $this->dispatch('traffic-updated', rx: $this->rxSpeed, tx: $this->txSpeed);
            }
        } catch (\Throwable $e) {
            // Silently log or ignore to prevent 500 error modal from disturbing the user
            report($e);
        }
    }

    public function render()
    {
        return view('livewire.portal.portal-traffic-monitor');
    }
}
