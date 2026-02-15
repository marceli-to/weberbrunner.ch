<?php

namespace App\Actions\Award;

use App\Models\Section;

class ListAction
{
	public function execute(bool $published = false)
	{
		return Section::query()
			->where('type', 'award')
			->orderBy('sort_order')
			->with(['awards' => fn ($q) => $q->when($published, fn ($q) => $q->published())->orderBy('sort_order')])
			->get()
			->when($published, fn ($sections) => $sections->filter(fn ($section) => $section->awards->isNotEmpty()));
	}
}
