<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\View\View;

class AwardPageController extends Controller
{
	public function __invoke(): View
	{
		$sections = Section::query()
			->where('type', 'award')
			->orderBy('sort_order')
			->with(['awards' => fn ($q) => $q->published()->with('project')->orderBy('sort_order')])
			->get()
			->filter(fn ($section) => $section->awards->isNotEmpty());

		return view('pages.about.awards', compact('sections'));
	}
}
