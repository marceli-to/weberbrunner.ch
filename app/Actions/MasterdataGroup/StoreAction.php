<?php

namespace App\Actions\MasterdataGroup;

use App\Models\MasterdataGroup;

class StoreAction
{
	public function execute(array $data): MasterdataGroup
	{
		return MasterdataGroup::create($data);
	}
}
