<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\View\View;

class JobPageController extends Controller
{
	public function __invoke(): View
	{
		$locations = Location::query()
			->orderBy('sort_order')
			->with(['jobs' => fn ($q) => $q->where('publish', true)->orderBy('sort_order')])
			->get()
			->filter(fn ($location) => $location->jobs->isNotEmpty());

		return view('pages.about.jobs', compact('locations'));
	}
}
