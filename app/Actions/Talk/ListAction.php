<?php

namespace App\Actions\Talk;

use App\Models\Section;

class ListAction
{
	public function execute()
	{
		return Section::query()
			->where('type', 'talk')
			->orderBy('sort_order')
			->with(['talks' => fn ($q) => $q->published()->orderBy('sort_order')])
			->get()
			->filter(fn ($section) => $section->talks->isNotEmpty());
	}
}
