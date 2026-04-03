<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
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
    }

    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'hasRole')) {
                return $user->hasRole('Super Admin') ? true : null;
            }

            return null;
        });

        // Optimize Livewire components in the specified directory
        Blaze::optimize()->in(resource_path('views/components'));

        Paginator::useBootstrapFive();

        Auth::provider('pppoe_provider', function ($app, array $config) {
            return new class($app['hash'], $config['model']) extends EloquentUserProvider
            {
                public function validateCredentials(Authenticatable $user, array $credentials)
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
