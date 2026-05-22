<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectListResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'uuid' => $this->uuid,
			'priority' => $this->priority,
			'number' => $this->number,
			'title' => $this->title,
			'city' => $this->city,
		];
	}
}
