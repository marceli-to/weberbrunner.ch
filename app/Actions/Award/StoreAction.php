<?php

namespace App\Actions\Award;

use App\Models\Award;

class StoreAction
{
	public function execute(array $data): Award
	{
		return Award::create($data);
	}
}
