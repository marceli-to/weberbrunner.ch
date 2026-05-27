<?php

namespace App\Actions\Project;

use App\Models\Project;

class PrepareViewDataAction
{
	public function execute(Project $project, bool $isPreview): array
	{
		return [
			'project' => $project,
			'isPreview' => $isPreview,
			'slides' => $project->blocks->firstWhere('type', 'fixed-slider')?->media ?? collect(),
			'projectInfo' => $project->masterdata
				->filter(fn ($m) => (bool) $m->pivot->publish)
				->sortBy(fn ($m) => $m->pivot->sort_order)
				->map(fn ($m) => [
					'label' => $m->title,
					'value' => $m->pivot->value,
				])
				->values()
				->toArray(),
			'header' => $project->categories->sortBy('sort_order')->pluck('title')->filter()->implode(', ') ?: 'weberbrunner architekten',
			'city' => $project->city,
		];
	}
}
