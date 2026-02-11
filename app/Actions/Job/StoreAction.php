<?php

namespace App\Actions\Job;

use App\Models\Job;

class StoreAction
{
	public function execute(array $data): Job
	{
		return Job::create($data);
	}
}
