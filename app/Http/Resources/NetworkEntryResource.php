<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NetworkEntryResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'title' => $this->title,
			'description' => $this->description,
			'category' => $this->category,
			'link' => $this->link,
			'publish' => $this->publish,
			'sort_order' => $this->sort_order,
			'media' => MediaResource::collection($this->whenLoaded('media')),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'can' => [
				'update' => $request->user()?->can('update', $this->resource) ?? false,
				'delete' => $request->user()?->can('delete', $this->resource) ?? false,
			],
		];
	}
}
