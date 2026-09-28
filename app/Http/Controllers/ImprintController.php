<?php

namespace App\Http\Controllers;

use App\Models\PageText;
use Illuminate\View\View;

class ImprintController extends Controller
{
	public function __invoke(): View
	{
		$page = PageText::where('page', 'impressum')
			->with(['blocks.media', 'blocks.links.linkedProject'])
			->first();

		return view('pages.misc.imprint', [
			'blocks' => $page?->blocks ?? collect(),
		]);
	}
}
