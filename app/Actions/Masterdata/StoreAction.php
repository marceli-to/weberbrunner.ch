<?php

namespace App\Actions\Masterdata;

use App\Models\Masterdata;

class StoreAction
{
	public function execute(array $data): Masterdata
	{
		return Masterdata::create($data);
	}
}
