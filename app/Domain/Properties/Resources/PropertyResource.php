<?php

namespace Smartville\Domain\Properties\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'overview_short' => $this->overview_short,
            'overview' => $this->overview,
            'price' => $this->formattedPrice,
            'size' => $this->size,
            'live' => $this->live,
            'image' => $this->imageUrl,
            'status' => $this->status,
            'occupied_at' => $this->occupied_at,
            'lease' => $this->whenLoaded('currentLease'),
            'amenities' => $this->whenLoaded('amenities'),
            'utilities' => $this->whenLoaded('utilities'),
        ];
    }
}
