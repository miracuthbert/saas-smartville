<?php

namespace Smartville\Domain\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Smartville\App\Tenant\Traits\ForTenants;
use Smartville\App\Traits\Eloquent\UsableTrait;

class CompanyPaymentMethod extends Model
{
    use SoftDeletes,
        ForTenants,
        UsableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'details',
        'usable',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];
}
