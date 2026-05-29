<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PPPSecrets extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory;

    protected $fillable = [
        'router_name',
        'username',
        'password',
        'service',
        'profile',
        'caller_id',
        'comment',
        'ppp_remote_ip',
        'bandwidth',
        'uptime',
        'downtime',
        'last_logged_out',
        'last_caller_id',
        'last_disconnect_reason',
        'routes',
        'ipv6_routes',
        'status',
        'package_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function customer()
    {
        return $this->hasOne(CustomersInfo::class, 'ppp_user_id', 'id');
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true; // You can add logic here to restrict access if needed
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the user's name for Filament.
     */
    public function getNameAttribute()
    {
        return $this->username;
    }

    /**
     * Get the user's avatar URL for Filament.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        $customer = $this->customer;
        if ($customer && $customer->photo_url) {
            return asset($customer->photo_url);
        }

        return null;
    }
}
