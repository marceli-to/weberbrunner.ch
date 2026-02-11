<?php

namespace App\Actions\ProjectAttribute;

use App\Models\Project;
use App\Models\ProjectAttribute;

class StoreAction
{
	public function execute(Project $project, array $data): ProjectAttribute
	{
		return $project->attributes()->create($data);
	}
}
