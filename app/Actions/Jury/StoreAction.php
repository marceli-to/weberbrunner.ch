<?php

namespace App\Actions\Jury;

use App\Models\Jury;

class StoreAction
{
	public function execute(array $data): Jury
	{
		return Jury::create($data);
	}
}
