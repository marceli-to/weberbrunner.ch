<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\Masterdata;
use App\Models\Project;

class DestroyAction
{
	public function execute(Project $project, Masterdata $masterdata): void
	{
		$project->masterdata()->detach($masterdata->id);
	}
}
