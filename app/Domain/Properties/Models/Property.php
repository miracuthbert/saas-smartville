<?php

namespace Smartville\Domain\Properties\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Smartville\App\Money\Money;
use Smartville\App\Tenant\Manager;
use Smartville\App\Tenant\Scopes\TenantScope;
use Smartville\App\Tenant\Traits\ForTenants;
use Smartville\App\Traits\Eloquent\Dates\UsesFormattedDates;
use Smartville\App\Traits\Eloquent\Deleteable\InTrashTrait;
use Smartville\App\Traits\Eloquent\Finished;
use Smartville\App\Traits\Eloquent\HasPrice;
use Smartville\Domain\Amenities\Models\Amenity;
use Smartville\Domain\Categories\Models\Category;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Properties\Filters\PropertyFilters;
use Smartville\Domain\Properties\Observers\PropertyObserver;
use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Users\Models\UserInvitation;
use Smartville\Domain\Utilities\Models\Utility;

class Property extends Model
{
    use ForTenants,
        SoftDeletes,
        Sluggable,
        Finished,
        InTrashTrait,
        UsesFormattedDates,
        HasPrice;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'overview_short',
        'overview',
        'image',
        'price',
        'live',
        'finished',
        'currency',
        'occupied_at',
        'size',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'occupied_at',
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'status',
        'isVacant',
        'imageUrl',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        $manager = app(Manager::class);

        if (null !== ($manager->getTenant())) {
            static::addGlobalScope(
                new TenantScope($manager->getTenant())
            );

            static::observe(
                app(PropertyObserver::class)
            );
        }
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
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['company.short_name', 'name'],
                'includeTrashed' => true,
                'maxLength' => 255,
            ]
        ];
    }

    /**
     * Get property image storage directory.
     *
     * @return string
     */
    public function getImageStorageDir()
    {
        return "companies/{$this->company->uuid}/properties/{$this->id}/images";
    }

    /**
     * Add tenant to property.
     *
     * @param User $user
     * @param UserInvitation $invitation
     * @return bool
     */
    public function addTenant(User $user, UserInvitation $invitation)
    {
        $company = $this->company;

        // add user to company
        $user->companies()->syncWithoutDetaching($company->id);

        // assign user company role

        // add user to leases
        $lease = $this->leases()->where('invitation_id', $invitation->id)->first();
        $lease->user()->associate($user);
        $lease->save();

        // update property status
        $this->updatePropertyOccupancyStatus();

        return true;
    }

    /**
     * Update property occupancy status.
     */
    public function updatePropertyOccupancyStatus()
    {
        $this->update([
            'live' => true,
            'occupied_at' => Carbon::now(),
        ]);
    }

    /**
     * Update property to vacant status.
     */
    public function updatePropertyToVacantStatus()
    {
        $this->update([
            'live' => true,
            'occupied_at' => null,
        ]);
    }

    /**
     * Handle adding and deleting of property utilities.
     *
     * @param $utilities
     */
    public function syncUtilities($utilities)
    {
        $this->deleteRemovedUtilities($utilities);

        $this->addUtilities($utilities);
    }

    /**
     * Add passed utilities to property.
     *
     * @param $utilities
     */
    public function addUtilities($utilities)
    {
        $this->utilities()->syncWithoutDetaching($utilities);
    }

    /**
     * Delete utilities not currently available in property new utilities.
     *
     * @param $newUtilities
     */
    public function deleteRemovedUtilities($newUtilities)
    {
        if ($this->utilities->isEmpty()) {
            return;
        }

        $oldUtilities = $this->utilities()->whereNotIn('utility_id', $newUtilities)
            ->pluck('utility_id')
            ->toArray();

        $this->utilities()->detach($oldUtilities);
    }

    /**
     * Handle adding and deleting of property amenities.
     *
     * @param $amenities
     */
    public function syncAmenities($amenities)
    {
        $this->deleteRemovedAmenities($amenities);

        $this->addAmenities($amenities);
    }

    /**
     * Add passed amenities to property.
     *
     * @param $amenities
     */
    public function addAmenities($amenities)
    {
        $this->amenities()->syncWithoutDetaching($amenities);
    }

    /**
     * Delete amenities not currently available in property new amenities.
     *
     * @param $newAmenities
     */
    public function deleteRemovedAmenities($newAmenities)
    {
        if ($this->amenities->isEmpty()) {
            return;
        }

        $oldAmenities = $this->amenities()->whereNotIn('amenity_id', $newAmenities)
            ->pluck('amenity_id')
            ->toArray();

        $this->amenities()->detach($oldAmenities);
    }

    /**
     * Check if property has active tenant via lease.
     *
     * @return bool
     */
    public function hasActiveTenant()
    {
        return (bool)optional($this->currentLease)->exists;
    }

    /**
     * Return if property is occupied.
     *
     * @return mixed
     */
    public function isOccupied()
    {
        if ($this->isNotLive()) {
            return false;
        }

        if (!$this->occupied_at) {
            return false;
        }

        return true;
    }

    /**
     * Return if property vacant.
     *
     * @return mixed
     */
    public function isVacant()
    {
        return !$this->isOccupied() ? true : false;
    }

    /**
     * Return if property initial step is completed.
     *
     * @return mixed
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * Return if property initial step is not completed.
     *
     * @return mixed
     */
    public function isNotFinished()
    {
        return !$this->isFinished();
    }

    /**
     * Return if property is live.
     *
     * @return mixed
     */
    public function isLive()
    {
        return $this->live;
    }

    /**
     * Return if property is not live.
     *
     * @return bool
     */
    public function isNotLive()
    {
        return !$this->isLive();
    }

    /**
     * Get property price as instance of Money.
     *
     * @param $value
     * @return Money
     */
    public function getPriceAttribute($value)
    {
        $money = new Money($value, $this->company->currency);

        return $money;
    }

    /**
     * Get property image url.
     *
     * @return null
     */
    public function getImageUrlAttribute()
    {
        if ($this->image != "logo.png" || $this->image != null) {
            return asset("storage/" . $this->image);
        }

        return null;
    }

    /**
     * Return if property vacancy as attribute.
     *
     * @return mixed
     */
    public function getIsVacantAttribute()
    {
        return $this->isVacant();
    }

    /**
     * Get property status.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->isLive()) {
            return 'Live';
        }

        return 'Disabled';
    }

    /**
     * Scope a query to include only properties that match passed filters.
     *
     * @param Builder $builder
     * @param $request
     * @param array $filters
     *
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new PropertyFilters($request))->add($filters)->filter($builder);
    }

    /**
     * Scope a query to only include occupied properties.
     *
     * @param Builder $builder
     * @return Builder|static
     */
    public function scopeOccupied(Builder $builder)
    {
        return $builder->whereNotNull('occupied_at')->whereHas('leases', function ($builder) {
            $builder->whereNotNull('moved_in_at')->whereDate('moved_out_at', '>', Carbon::now());
        });
    }

    /**
     * Scope a query to only include vacant properties.
     *
     * @param Builder $builder
     * @return Builder|static
     */
    public function scopeVacant(Builder $builder)
    {
        return $builder->whereNull('occupied_at')->whereDoesntHave('leases', function ($builder) {
            $builder->whereNull('moved_in_at')->orWhereNull('moved_out_at');
        });
    }

    /**
     * Get all of the utilities for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function utilities()
    {
        return $this->morphToMany(Utility::class, 'utilisable')->withTimestamps();
    }

    /**
     * Get features owned by property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function features()
    {
        return $this->hasMany(PropertyFeature::class);
    }

    /**
     * Get all of the amenities for the property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function amenities()
    {
        return $this->morphToMany(Amenity::class, 'amenitiable')->withTimestamps();
    }

    /**
     * Get current running lease of property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentLease()
    {
        return $this->hasOne(Lease::class)->whereNotNull('moved_in_at')->whereNull('vacated_at');
    }

    /**
     * Get leases owned by property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    /**
     * Get category that property belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get company that owns property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
