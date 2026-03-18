<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\Masterdata;
use App\Models\MasterdataProject;
use App\Models\Project;

class UpdateValuesAction
{
	public function execute(Project $project, array $entries): void
	{
		foreach ($entries as $entry) {
			$masterdata = Masterdata::where('uuid', $entry['uuid'])->firstOrFail();
			$pivot = MasterdataProject::where('project_id', $project->id)
				->where('masterdata_id', $masterdata->id)
				->first();

			if (!$entry['value']) {
				$pivot?->delete();
				continue;
			}

			if ($pivot) {
				$pivot->update(['value' => $entry['value']]);
			} else {
				$maxSortOrder = MasterdataProject::where('project_id', $project->id)->max('sort_order') ?? 0;
				MasterdataProject::create([
					'project_id' => $project->id,
					'masterdata_id' => $masterdata->id,
					'value' => $entry['value'],
					'publish' => false,
					'sort_order' => $maxSortOrder + 1,
				]);
			}
		}
	}
}
