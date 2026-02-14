<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\View\View;

class TalkPageController extends Controller
{
	public function __invoke(): View
	{
		$sections = Section::query()
			->where('type', 'talk')
			->orderBy('sort_order')
			->with(['talks' => fn ($q) => $q->published()->orderBy('sort_order')])
			->get()
			->filter(fn ($section) => $section->talks->isNotEmpty());

		return view('pages.about.talks', compact('sections'));
	}
}
