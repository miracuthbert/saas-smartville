<?php

namespace Smartville\Domain\Tenant\Models;

use Illuminate\Database\Eloquent\Model;

class TenantConnection extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'database',
    ];
}
