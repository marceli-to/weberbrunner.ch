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
			'title' => $this->title,
			'slug' => $this->slug,
			'description' => $this->description,
			'publish' => $this->publish,
			'sort_order' => $this->sort_order,
			'location' => new LocationResource($this->whenLoaded('location')),
			'attributes' => ProjectAttributeResource::collection($this->whenLoaded('attributes')),
			'media' => MediaResource::collection($this->whenLoaded('media')),
			'categories' => CategoryResource::collection($this->whenLoaded('categories')),
			'statuses' => StatusResource::collection($this->whenLoaded('statuses')),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'can' => [
				'update' => $request->user()?->can('update', $this->resource) ?? false,
				'delete' => $request->user()?->can('delete', $this->resource) ?? false,
			],
		];
	}
}
