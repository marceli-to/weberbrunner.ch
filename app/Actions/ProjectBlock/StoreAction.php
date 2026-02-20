<?php

namespace App\Actions\ProjectBlock;

use App\Models\Project;
use App\Models\ProjectBlock;

class StoreAction
{
	public function execute(Project $project, array $data): ProjectBlock
	{
		return $project->blocks()->create($data);
	}
}
