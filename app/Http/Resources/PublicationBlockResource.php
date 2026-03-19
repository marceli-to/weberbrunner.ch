<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicationBlockResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'type' => $this->type,
			'title' => $this->title,
			'content' => $this->content,
			'url' => $this->url,
			'sort_order' => $this->sort_order,
			'media' => MediaResource::collection($this->whenLoaded('media')),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'can' => [
				'update' => $request->user()?->can('update', $this->publication) ?? false,
				'delete' => $request->user()?->can('update', $this->publication) ?? false,
			],
		];
	}
}
