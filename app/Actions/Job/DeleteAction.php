<?php

namespace App\Actions\Job;

use App\Models\Job;

class DeleteAction
{
	public function execute(Job $job): void
	{
		$job->delete();
	}
}
