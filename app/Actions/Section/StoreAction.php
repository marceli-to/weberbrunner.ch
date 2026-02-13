<?php

namespace App\Actions\Section;

use App\Models\Section;

class StoreAction
{
	public function execute(array $data): Section
	{
		return Section::create($data);
	}
}
