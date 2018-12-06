<?php

namespace Smartville\Domain\Issues\Models;

use Illuminate\Database\Eloquent\Model;

class IssueTopic extends Model
{
    /**
     * Get all of the owning issueable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function issueable()
    {
        return $this->morphTo();
    }
}
