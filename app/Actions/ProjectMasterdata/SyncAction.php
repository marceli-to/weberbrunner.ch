<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\Masterdata;
use App\Models\Project;

class SyncAction
{
	public function execute(Project $project, array $entries): void
	{
		$existing = $project->masterdata()->get()->keyBy('id');
		$maxSortOrder = $existing->max(fn ($m) => $m->pivot->sort_order) ?? 0;

		$syncData = collect($entries)
			->mapWithKeys(function ($entry) use ($existing, &$maxSortOrder) {
				$masterdata = Masterdata::where('uuid', $entry['uuid'])->firstOrFail();
				$sortOrder = $existing->has($masterdata->id)
					? $existing->get($masterdata->id)->pivot->sort_order
					: ++$maxSortOrder;

				return [$masterdata->id => ['value' => $entry['value'], 'sort_order' => $sortOrder]];
			})
			->all();

		$project->masterdata()->sync($syncData);
	}
}
