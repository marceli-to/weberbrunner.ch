<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicationResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'title' => $this->title,
			'subtitle' => $this->subtitle,
			'meta_description' => $this->meta_description,
			'location_id' => $this->location_id,
			'publish' => $this->publish,
			'sort_order' => $this->sort_order,
			'location' => new LocationResource($this->whenLoaded('location')),
			'attributes' => PublicationAttributeResource::collection($this->whenLoaded('attributes')),
			'blocks' => PublicationBlockResource::collection($this->whenLoaded('blocks')),
			'media' => MediaResource::collection($this->whenLoaded('media')),
			'teaser' => new MediaResource($this->whenLoaded('teaser', fn() => $this->teaser->first())),
			'og' => new MediaResource($this->whenLoaded('og', fn() => $this->og->first())),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'can' => [
				'update' => $request->user()?->can('update', $this->resource) ?? false,
				'delete' => $request->user()?->can('delete', $this->resource) ?? false,
			],
		];
	}
}
