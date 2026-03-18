<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\Project;
use Illuminate\Support\Collection;

class ListAvailableAction
{
	public function execute(Project $project): Collection
	{
		return $project->masterdata()
			->whereNotNull('masterdata_project.value')
			->where('masterdata_project.value', '!=', '')
			->orderByDesc('masterdata.standard')
			->orderBy('masterdata.sort_order')
			->get();
	}
}
