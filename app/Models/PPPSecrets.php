<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPPSecrets extends Model
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
}
