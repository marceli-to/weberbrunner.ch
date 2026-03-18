<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\MasterdataGroup;
use App\Models\Project;

class ListAction
{
	public function execute(Project $project)
	{
		$groups = MasterdataGroup::query()
			->orderBy('sort_order')
			->with(['masterdata' => fn ($q) => $q->orderBy('sort_order')])
			->get();

		$values = $project->masterdata()->get()->pluck('pivot.value', 'id');

		foreach ($groups as $group) {
			foreach ($group->masterdata as $entry) {
				$entry->project_value = $values->get($entry->id);
			}
		}

		return $groups;
	}
}
