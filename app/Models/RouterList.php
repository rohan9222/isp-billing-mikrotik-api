<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouterList extends Model
{
    use HasFactory;

    protected $fillable = ['router_name', 'ip_address', 'username', 'password', 'action', 'ssh_port', 'api_port'];
}
