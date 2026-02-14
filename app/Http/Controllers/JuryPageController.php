<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\View\View;

class JuryPageController extends Controller
{
	public function __invoke(): View
	{
		$sections = Section::query()
			->where('type', 'jury')
			->orderBy('sort_order')
			->with(['juries' => fn ($q) => $q->published()->orderBy('sort_order')])
			->get()
			->filter(fn ($section) => $section->juries->isNotEmpty());

		return view('pages.about.jury', compact('sections'));
	}
}
