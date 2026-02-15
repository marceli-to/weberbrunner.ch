<?php

namespace App\Actions\NetworkEntry;

use App\Models\Section;

class ListAction
{
	public function execute(bool $published = false)
	{
		return Section::query()
			->where('type', 'network')
			->orderBy('sort_order')
			->with(['networkEntries' => fn ($q) => $q->when($published, fn ($q) => $q->published())->orderBy('sort_order')])
			->get()
			->when($published, fn ($sections) => $sections->filter(fn ($section) => $section->networkEntries->isNotEmpty()));
	}
}
