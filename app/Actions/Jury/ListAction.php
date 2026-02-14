<?php

namespace App\Actions\Jury;

use App\Models\Section;

class ListAction
{
	public function execute()
	{
		return Section::query()
			->where('type', 'jury')
			->orderBy('sort_order')
			->with(['juries' => fn ($q) => $q->published()->orderBy('sort_order')])
			->get()
			->filter(fn ($section) => $section->juries->isNotEmpty());
	}
}
