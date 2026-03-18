<?php

namespace App\Actions\ProjectMasterdata;

use App\Models\Project;
use Illuminate\Support\Collection;

class ListAttachedAction
{
	public function execute(Project $project): Collection
	{
		return $project->masterdata()
			->orderByPivot('sort_order')
			->get();
	}
}
