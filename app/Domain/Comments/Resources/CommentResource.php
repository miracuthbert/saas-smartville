<?php

namespace Smartville\Domain\Comments\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Smartville\Domain\Users\Resources\UserResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'owner' => $this->owner,
            'child' => !is_null($this->parent_id),
            'parent_id' => $this->parent_id,
            'user' => new UserResource($this->user),
            'children' => CommentResource::collection($this->whenLoaded('children')),
            'created_at' => $this->local_created_at,
            'edited_at' => $this->local_edited_at,
            'edited' => (bool)$this->isEdited(),
            'closed_at' => $this->local_closed_at,
            'closed' => (bool)$this->isClosed(),
        ];
    }
}
