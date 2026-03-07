<?php

namespace App\Providers;

use App\Services\SMSService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Blaze\Blaze;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'portal.')) {
            config(['session.cookie' => 'portal_session']);
        }

        $this->app->singleton(SMSService::class, function ($app) {
            return new SMSService;
        });
    }

    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Optimize Livewire components in the specified directory
        Blaze::optimize()->in(resource_path('views/components'));

        Paginator::useBootstrapFive();

        Auth::provider('pppoe_provider', function ($app, array $config) {
            return new class($app['hash'], $config['model']) extends \Illuminate\Auth\EloquentUserProvider {
                public function validateCredentials(\Illuminate\Contracts\Auth\Authenticatable $user, array $credentials)
                {
                    $plain = $credentials['password'];
                    $auth_password = $user->getAuthPassword();

                    // Check plain text first
                    if ($plain === $auth_password) {
                        return true;
                    }

                    // Fallback to standard hashing only if it looks like a hash
                    if (str_starts_with($auth_password, '$2y$') || str_starts_with($auth_password, '$2a$')) {
                        return parent::validateCredentials($user, $credentials);
                    }

                    return false;
                }
            };
        });
    }
}
