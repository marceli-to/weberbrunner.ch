<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectMasterdataResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'uuid' => $this->uuid,
			'title' => $this->title,
			'standard' => $this->standard,
			'sort_order' => $this->whenPivotLoaded('masterdata_project', fn () => $this->pivot->sort_order, fn () => $this->sort_order),
			'value' => $this->whenPivotLoaded('masterdata_project', fn () => $this->pivot->value, fn () => $this->project_value ?? null),
			'publish' => $this->whenPivotLoaded('masterdata_project', fn () => (bool) $this->pivot->publish, fn () => $this->publish ?? false),
			'is_attached' => $this->whenPivotLoaded('masterdata_project', fn () => true, fn () => $this->is_attached ?? false),
		];
	}
}
