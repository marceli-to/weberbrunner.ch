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
			'projectInfo' => $project->attributes->map(fn($attr) => [
				'label' => $attr->label,
				'value' => $attr->value,
			])->toArray(),
			'header' => $project->categories->first()?->title ?? 'weberbrunner architekten',
		];
	}
}
