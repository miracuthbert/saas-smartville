<?php

namespace Smartville\Domain\Comments\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Smartville\App\Traits\Eloquent\Dates\UsesFormattedDates;
use Smartville\Domain\Users\Models\User;

class Comment extends Model
{
    use SoftDeletes,
        UsesFormattedDates;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'edited_at',
        'closed_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'owner',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'edited_at',
        'closed_at',
        'deleted_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($comment) {
            $comment->edited_at = Carbon::now();
        });
    }

    /**
     * Return if issue is closed.
     *
     * @return bool
     */
    public function isClosed()
    {
        return (bool)$this->closed_at;
    }

    /**
     * Return if issue was edited.
     *
     * @return bool
     */
    public function isEdited()
    {
        return !$this->edited_at ? false : true;
    }

    /**
     * Return if the request user is the issue owner.
     *
     * @return bool
     */
    public function getOwnerAttribute()
    {
        return $this->user_id === optional(auth()->user())->id;
    }

    /**
     * Get all of the comment's children.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get all of the owning commentable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * The user that owns the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
