<?php

namespace App\Actions\Talk;

use App\Models\Talk;

class UpdateAction
{
	public function execute(Talk $talk, array $data): Talk
	{
		$talk->update($data);

		return $talk;
	}
}
