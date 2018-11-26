<?php

namespace Smartville\Domain\Properties\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyFeature extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'count',
        'details'
    ];

    /**
     * Get the correct type features count.
     *
     * @param $value
     * @return float|int
     */
    public function getCountAttribute($value)
    {
        if ((fmod($value, 2)) > 0) {
            return (float)$value;
        }

        return (int)$value;
    }

    /**
     * Get property that owns feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
