<?php

namespace App\Actions\Jury;

use App\Models\Jury;

class DeleteAction
{
	public function execute(Jury $jury): void
	{
		$jury->delete();
	}
}
