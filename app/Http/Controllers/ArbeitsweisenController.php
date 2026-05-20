<?php

namespace App\Http\Controllers;

use App\Models\PageText;
use Illuminate\View\View;

class ArbeitsweisenController extends Controller
{
	public function __invoke(): View
	{
		$page = PageText::where('page', 'arbeitsweisen')
			->with(['blocks.media', 'blocks.links.linkedProject'])
			->first();

		return view('pages.about.arbeitsweisen', [
			'blocks' => $page?->blocks ?? collect(),
		]);
	}
}
