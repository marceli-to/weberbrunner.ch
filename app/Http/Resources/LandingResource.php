<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LandingResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'text' => $this->text,
			'publish' => $this->publish,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
