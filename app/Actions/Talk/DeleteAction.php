<?php

namespace App\Actions\Talk;

use App\Models\Talk;

class DeleteAction
{
	public function execute(Talk $talk): void
	{
		$talk->delete();
	}
}
