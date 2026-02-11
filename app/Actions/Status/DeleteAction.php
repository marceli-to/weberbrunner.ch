<?php

namespace App\Actions\Status;

use App\Models\Status;

class DeleteAction
{
	public function execute(Status $status): void
	{
		$status->delete();
	}
}
