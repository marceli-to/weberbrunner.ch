<?php

namespace App\Actions\MasterdataGroup;

use App\Models\MasterdataGroup;

class UpdateAction
{
	public function execute(MasterdataGroup $masterdataGroup, array $data): MasterdataGroup
	{
		$masterdataGroup->update($data);

		return $masterdataGroup;
	}
}
