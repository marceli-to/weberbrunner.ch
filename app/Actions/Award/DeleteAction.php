<?php

namespace App\Actions\Award;

use App\Models\Award;

class DeleteAction
{
	public function execute(Award $award): void
	{
		$award->delete();
	}
}
