<?php

namespace Smartville\Domain\Amenities\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Smartville\App\Tenant\Traits\ForTenants;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Properties\Models\Property;

class Amenity extends Model
{
    use ForTenants,
        Sluggable,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'details',
        'usable',
        'all_properties',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['name'],
                'includeTrashed' => true,
                'maxLength' => 255,
            ]
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get amenity identifier.
     *
     * @return string
     */
    public function getIdentifierAttribute()
    {
        return uniqid(true);
    }

    /**
     * Scope a query to only include for all_properties amenities.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeForAllProperties(Builder $builder)
    {
        return $builder->where('all_properties', true);
    }

    /**
     * Scope a query to only include live (active) amenities.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where('usable', true);
    }

    /**
     * Get all of the properties that are assigned this amenity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function properties()
    {
        return $this->morphedByMany(Property::class, 'amenitiable');
    }

    /**
     * Get all of the owning amenitiable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function amenitiable()
    {
        return $this->morphTo();
    }

    /**
     * Get company that owns amenity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
