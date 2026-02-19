<?php

namespace App\Actions\Project;

use App\Models\Project;

class ToggleAction
{
	public function execute(Project $project): void
	{
		$project->update(['publish' => !$project->publish]);
	}
}
