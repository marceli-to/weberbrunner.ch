<?php

namespace App\Actions\MasterdataGroup;

use App\Models\MasterdataGroup;

class DeleteAction
{
	public function execute(MasterdataGroup $masterdataGroup): void
	{
		$masterdataGroup->masterdata()->delete();
		$masterdataGroup->delete();
	}
}
