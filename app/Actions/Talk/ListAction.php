<?php

namespace App\Actions\Talk;

use App\Models\Section;

class ListAction
{
	public function execute(bool $published = false)
	{
		return Section::query()
			->where('type', 'talk')
			->orderBy('sort_order')
			->with(['talks' => fn ($q) => $q->when($published, fn ($q) => $q->published())->orderBy('sort_order')])
			->get()
			->when($published, fn ($sections) => $sections->filter(fn ($section) => $section->talks->isNotEmpty()));
	}
}
