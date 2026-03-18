<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\Masterdata;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ReorderAction
{
	public function execute(Project $project, array $items): void
	{
		foreach ($items as $item) {
			$masterdata = Masterdata::where('uuid', $item['uuid'])->firstOrFail();
			DB::table('masterdata_project')
				->where('project_id', $project->id)
				->where('masterdata_id', $masterdata->id)
				->update(['sort_order' => $item['sort_order']]);
		}
	}
}
