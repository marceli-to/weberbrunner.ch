<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\Masterdata;
use App\Models\Project;
use Illuminate\Support\Collection;

class ListAllAction
{
	public function execute(Project $project): Collection
	{
		$pivotValues = $project->masterdata()
			->get()
			->keyBy('id')
			->map(fn ($m) => $m->pivot->value);

		return Masterdata::query()
			->join('masterdata_groups', 'masterdata.masterdata_group_id', '=', 'masterdata_groups.id')
			->orderBy('masterdata_groups.sort_order')
			->orderBy('masterdata.sort_order')
			->select('masterdata.*')
			->get()
			->each(function ($entry) use ($pivotValues) {
				$entry->project_value = $pivotValues->get($entry->id);
			});
	}
}
