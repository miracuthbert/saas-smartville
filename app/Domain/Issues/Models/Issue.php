<?php

namespace Smartville\Domain\Issues\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Smartville\App\Traits\Eloquent\Dates\UsesFormattedDates;
use Smartville\Domain\Comments\Models\Comment;
use Smartville\Domain\Issues\Filters\IssueFilters;
use Smartville\Domain\Users\Models\User;

class Issue extends Model
{
    use SoftDeletes,
        UsesFormattedDates;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
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

        static::updating(function ($issue) {
            $issue->edited_at = Carbon::now();
        });
    }

    /**
     * Return formatted issue topics.
     *
     * @return mixed
     */
    public function formattedTopics()
    {
        return $this->topics->map(function ($topic) {
            return [
                'id' => $topic->id,
                'name' => optional($topic->issueable)->name ?: optional($topic->issueable)->title
            ];
        })->all();
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
     * Scope the query by the request filters.
     *
     * @param Builder $builder
     * @param $request
     * @param array $filters
     *
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new IssueFilters($request))->add($filters)->filter($builder);
    }

    /**
     * Get all of the issue's comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get all of the topics filed under the issue.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(IssueTopic::class);
    }

    /**
     * The user that posted the issue.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
