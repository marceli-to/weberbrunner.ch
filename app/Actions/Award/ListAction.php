<?php

namespace App\Actions\Award;

use App\Models\Section;

class ListAction
{
	public function execute()
	{
		return Section::query()
			->where('type', 'award')
			->orderBy('sort_order')
			->with(['awards' => fn ($q) => $q->published()->orderBy('sort_order')])
			->get()
			->filter(fn ($section) => $section->awards->isNotEmpty());
	}
}
