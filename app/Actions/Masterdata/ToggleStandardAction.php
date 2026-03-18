<?php

namespace App\Actions\Masterdata;

use App\Models\Masterdata;

class ToggleStandardAction
{
	public function execute(Masterdata $masterdata): void
	{
		$masterdata->update(['standard' => !$masterdata->standard]);
	}
}
