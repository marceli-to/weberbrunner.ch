<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicationAttributeResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'key' => $this->key,
			'value' => $this->value,
			'sort_order' => $this->sort_order,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
