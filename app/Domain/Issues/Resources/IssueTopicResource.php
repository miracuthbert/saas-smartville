<?php

namespace Smartville\Domain\Issues\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class IssueTopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function toArray($request)
    {
        if ($this->resource instanceof Collection) {
            return IssueTopicResource::collection($this->resource);
        }

        // optional: add checks or create a method to pluck of the topic name
        $name = optional($this->issueable)->name ?: optional($this->issueable)->title;

        return [
            'id' => $this->id,
            'name' => isset($name) ? $name : null,
        ];
    }
}
