<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'firstname' => $this->firstname,
			'name' => $this->name,
			'email' => $this->email,
			'title' => $this->title,
			'since' => $this->since,
			'slug' => $this->slug,
			'publish' => $this->publish,
			'sort_order' => $this->sort_order,
			'location' => new LocationResource($this->whenLoaded('location')),
			'bios' => TeamMemberBioResource::collection($this->whenLoaded('bios')),
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
