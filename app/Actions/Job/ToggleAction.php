<?php

namespace App\Actions\Job;

use App\Models\Job;

class ToggleAction
{
	public function execute(Job $job): void
	{
		$job->update(['publish' => !$job->publish]);
	}
}
