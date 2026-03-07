<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouterList extends Model
{
    use HasFactory;

    protected $fillable = ['router_name', 'ip_address', 'username', 'password', 'action', 'ssh_port', 'api_port'];

    /**
     * Set the ssh port to null if empty.
     */
    protected function setSshPortAttribute($value)
    {
        $this->attributes['ssh_port'] = $value === '' ? null : $value;
    }

    /**
     * Set the api port to null if empty.
     */
    protected function setApiPortAttribute($value)
    {
        $this->attributes['api_port'] = $value === '' ? null : $value;
    }
}
