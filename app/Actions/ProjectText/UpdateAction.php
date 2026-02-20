<?php

namespace App\Actions\ProjectText;

use App\Models\Project;

class UpdateAction
{
	public function execute(Project $project, array $data): void
	{
		$project->update($data);
	}
}
