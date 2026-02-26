<?php

namespace App\Actions\LandingItem;

use App\Models\LandingItem;

class DeleteAction
{
	public function execute(LandingItem $item): void
	{
		$item->delete();
	}
}
