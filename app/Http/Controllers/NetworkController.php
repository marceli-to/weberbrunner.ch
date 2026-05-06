<?php

namespace App\Http\Controllers;

use App\Models\PageText;
use Illuminate\View\View;

class NetworkController extends Controller
{
	public function __invoke(): View
	{
		$page = PageText::where('page', 'network')
			->with(['blocks.media', 'blocks.links.linkedProject'])
			->first();

		return view('pages.about.network', [
			'blocks' => $page?->blocks ?? collect(),
		]);
	}
}
