<?php

namespace App\Actions\ProjectStatus;

use App\Models\Project;

class SyncAction
{
	public function execute(Project $project, array $statusIds): void
	{
		$project->statuses()->sync($statusIds);
	}
}
