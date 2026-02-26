<?php

namespace App\Actions\LandingItem;

use App\Models\LandingItem;

class StoreAction
{
	public function execute(array $data): LandingItem
	{
		$data['sort_order'] = LandingItem::where('column', $data['column'])->max('sort_order') + 1;

		return LandingItem::create($data);
	}
}
