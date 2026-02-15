<?php

namespace App\Actions\Jury;

use App\Models\Section;

class ListAction
{
	public function execute(bool $published = false)
	{
		return Section::query()
			->where('type', 'jury')
			->orderBy('sort_order')
			->with(['juries' => fn ($q) => $q->when($published, fn ($q) => $q->published())->orderBy('sort_order')])
			->get()
			->when($published, fn ($sections) => $sections->filter(fn ($section) => $section->juries->isNotEmpty()));
	}
}
