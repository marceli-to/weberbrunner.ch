<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectBlockResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'type' => $this->type,
			'title' => $this->title,
			'content' => $this->content,
			'sort_order' => $this->sort_order,
			'media' => MediaResource::collection($this->whenLoaded('media')),
			'links' => ProjectBlockLinkResource::collection($this->whenLoaded('links')),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
