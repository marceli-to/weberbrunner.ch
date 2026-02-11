<?php

namespace App\Actions\Job;

use App\Models\Job;

class UpdateAction
{
	public function execute(Job $job, array $data): Job
	{
		$job->update($data);

		return $job;
	}
}
