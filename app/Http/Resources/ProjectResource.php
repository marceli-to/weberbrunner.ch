<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'priority' => $this->priority,
			'title' => $this->title,
			'full_title' => $this->full_title,
			'number' => $this->number,
			'slug' => $this->slug,
			'short_description' => $this->short_description,
			'description' => $this->description,
			'meta_description' => $this->meta_description,
			'city' => $this->city,
			'publish' => $this->publish,
			'sort_order' => $this->sort_order,
			'location' => new LocationResource($this->whenLoaded('location')),
			'attributes' => ProjectAttributeResource::collection($this->whenLoaded('attributes')),
			'media' => MediaResource::collection($this->whenLoaded('media')),
			'teaser' => MediaResource::collection($this->whenLoaded('teaser')),
			'categories' => CategoryResource::collection($this->whenLoaded('categories')),
			'statuses' => StatusResource::collection($this->whenLoaded('statuses')),
			'links' => ProjectLinkResource::collection($this->whenLoaded('links')),
			'blocks' => ProjectBlockResource::collection($this->whenLoaded('blocks')),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'can' => [
				'update' => $request->user()?->can('update', $this->resource) ?? false,
				'delete' => $request->user()?->can('delete', $this->resource) ?? false,
			],
		];
	}
}
