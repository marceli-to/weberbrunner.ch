<?php

namespace App\Actions\Job;

use App\Models\Location;

class ListAction
{
	public function execute(bool $published = false)
	{
		return Location::query()
			->orderBy('sort_order')
			->with(['jobs' => fn ($q) => $q->when($published, fn ($q) => $q->published())->orderBy('sort_order')])
			->get()
			->when($published, fn ($locations) => $locations->filter(fn ($location) => $location->jobs->isNotEmpty()));
	}
}
