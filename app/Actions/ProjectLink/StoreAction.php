<?php

namespace App\Actions\ProjectLink;

use App\Models\Project;
use App\Models\ProjectLink;

class StoreAction
{
	public function execute(Project $project, array $data): ProjectLink
	{
		return $project->links()->create($data);
	}
}
