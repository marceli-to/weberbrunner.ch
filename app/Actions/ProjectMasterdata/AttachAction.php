<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\Masterdata;
use App\Models\MasterdataProject;
use App\Models\Project;

class AttachAction
{
	public function execute(Project $project, Masterdata $masterdata): void
	{
		$pivot = MasterdataProject::where('project_id', $project->id)
			->where('masterdata_id', $masterdata->id)
			->first();

		if (!$pivot) {
			$maxSortOrder = MasterdataProject::where('project_id', $project->id)->max('sort_order') ?? 0;

			MasterdataProject::create([
				'project_id' => $project->id,
				'masterdata_id' => $masterdata->id,
				'publish' => false,
				'sort_order' => $maxSortOrder + 1,
			]);
		}
	}
}
