<?php

namespace App\Actions\Talk;

use App\Models\Talk;

class StoreAction
{
	public function execute(array $data): Talk
	{
		return Talk::create($data);
	}
}
