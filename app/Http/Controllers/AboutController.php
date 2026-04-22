<?php

namespace App\Http\Controllers;

use App\Models\PageText;
use Illuminate\View\View;

class AboutController extends Controller
{
	public function __invoke(): View
	{
		$intro = PageText::where('page', 'office')
			->with(['blocks.media', 'blocks.links.linkedProject'])
			->first();

		return view('pages.about.index', [
			'blocks' => $intro?->blocks ?? collect(),
		]);
	}
}
