<?php

namespace App\Actions\ProjectMeta;

use App\Models\Project;

class UpdateAction
{
	public function execute(Project $project, array $data): void
	{
		$project->update($data);
	}
}
