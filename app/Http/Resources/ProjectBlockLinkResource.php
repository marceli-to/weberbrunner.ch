<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectBlockLinkResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'title' => $this->title,
			'url' => $this->url,
			'link_type' => $this->link_type,
			'linked_project_id' => $this->linked_project_id,
			'linked_project' => new ProjectResource($this->whenLoaded('linkedProject')),
			'publish' => $this->publish,
			'sort_order' => $this->sort_order,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
