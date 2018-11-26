<?php

namespace Smartville\Domain\Notification\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $timezone = optional($request->user())->timezone;

        return array_merge(
            array_except(parent::toArray($request), ['created_at', 'updated_at', 'read_at']), [
            'created_at' => Carbon::parse($this->created_at, $timezone)->toDateTimeString(),
            'read_at' => !$this->read_at ? null : Carbon::parse($this->read_at, $timezone)->toDateTimeString()
        ]);
    }
}
