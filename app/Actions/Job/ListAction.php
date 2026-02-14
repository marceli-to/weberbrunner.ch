<?php

namespace App\Actions\Job;

use App\Models\Location;

class ListAction
{
	public function execute()
	{
		return Location::query()
			->orderBy('sort_order')
			->with(['jobs' => fn ($q) => $q->published()->orderBy('sort_order')])
			->get()
			->filter(fn ($location) => $location->jobs->isNotEmpty());
	}
}
