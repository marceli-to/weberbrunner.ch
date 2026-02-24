<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'company_name' => $this->company_name,
			'address' => $this->address,
			'phone' => $this->phone,
			'email' => $this->email,
			'maps_url' => $this->maps_url,
			'publish' => $this->publish,
			'sort_order' => $this->sort_order,
			'location' => new LocationResource($this->whenLoaded('location')),
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
