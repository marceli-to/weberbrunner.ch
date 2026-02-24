<?php

namespace App\Actions\Contact;

use App\Models\Location;

class ListAction
{
	public function execute(bool $published = false)
	{
		return Location::query()
			->orderBy('sort_order')
			->with(['contacts' => fn ($q) => $q->when($published, fn ($q) => $q->published())->with('image')->orderBy('sort_order')])
			->get()
			->when($published, fn ($locations) => $locations->filter(fn ($location) => $location->contacts->isNotEmpty()));
	}
}
