<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\Masterdata;
use App\Models\MasterdataProject;
use App\Models\Project;

class ReorderAction
{
	public function execute(Project $project, array $items): void
	{
		foreach ($items as $item) {
			$masterdata = Masterdata::where('uuid', $item['uuid'])->firstOrFail();
			MasterdataProject::where('project_id', $project->id)
				->where('masterdata_id', $masterdata->id)
				->update(['sort_order' => $item['sort_order']]);
		}
	}
}
