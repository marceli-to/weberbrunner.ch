<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LandingItemResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'project_id' => $this->project_id,
			'column' => $this->column,
			'sort_order' => $this->sort_order,
			'project' => new ProjectResource($this->whenLoaded('project')),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
