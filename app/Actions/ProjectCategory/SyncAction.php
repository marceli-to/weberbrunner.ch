<?php

namespace App\Actions\ProjectCategory;

use App\Models\Project;

class SyncAction
{
	public function execute(Project $project, array $categoryIds): void
	{
		$project->categories()->sync($categoryIds);
	}
}
