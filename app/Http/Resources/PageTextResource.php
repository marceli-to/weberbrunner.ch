<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageTextResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'page' => $this->page,
			'title' => $this->title,
			'text' => $this->text,
			'blocks' => BlockResource::collection($this->whenLoaded('blocks')),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
