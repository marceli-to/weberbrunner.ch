<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterdataResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'title' => $this->title,
			'is_default' => $this->is_default,
			'masterdata_group' => new MasterdataGroupResource($this->whenLoaded('masterdataGroup')),
			'sort_order' => $this->sort_order,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'can' => [
				'update' => $request->user()?->can('update', $this->resource) ?? false,
				'delete' => $request->user()?->can('delete', $this->resource) ?? false,
			],
		];
	}
}
