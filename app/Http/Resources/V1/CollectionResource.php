<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\ContributorResource;

class CollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'descroption' => $this->description,
            'targetAmount' => $this->target_amount,
            'link' => $this->link,
            'contributors' => ContributorResource::collection($this->whenLoaded('contributors'))
        ];
    }
}
