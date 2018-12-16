<?php

namespace Smartville\Domain\Issues\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Smartville\Domain\Users\Resources\UserResource;

class IssueIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->local_created_at,
            'edited_at' => $this->local_edited_at,
            'edited' => (bool)$this->isEdited(),
            'closed_at' => $this->local_closed_at,
            'closed' => (bool)$this->isClosed(),
            'user' => new UserResource($this->user),
            'owner' => $this->owner,
            'topics' => IssueTopicResource::collection($this->whenLoaded('topics')),
        ];
    }
}
