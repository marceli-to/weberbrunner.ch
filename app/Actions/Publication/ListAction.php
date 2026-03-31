<?php

namespace App\Actions\Publication;

use App\Models\Publication;
use Illuminate\Database\Eloquent\Collection;

class ListAction
{
	public function execute(bool $published = false): Collection
	{
		return Publication::query()
			->when($published, fn ($q) => $q->published())
			->with(['teaser'])
			->orderBy('sort_order')
			->get();
	}
}
