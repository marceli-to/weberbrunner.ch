<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\Masterdata;
use App\Models\MasterdataProject;
use App\Models\Project;

class DestroyAction
{
	public function execute(Project $project, Masterdata $masterdata): void
	{
		MasterdataProject::where('project_id', $project->id)
			->where('masterdata_id', $masterdata->id)
			->update(['publish' => false]);
	}
}
