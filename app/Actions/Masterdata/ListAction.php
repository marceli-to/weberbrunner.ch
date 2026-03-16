<?php

namespace App\Actions\Masterdata;

use App\Models\MasterdataGroup;

class ListAction
{
	public function execute()
	{
		return MasterdataGroup::query()
			->orderBy('sort_order')
			->with(['masterdata' => fn ($q) => $q->orderBy('sort_order')])
			->get();
	}
}
