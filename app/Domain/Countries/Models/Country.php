<?php

namespace Smartville\Domain\Countries\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'dial_code',
        'currency',
        'currency_name',
        'usable',
    ];
}
