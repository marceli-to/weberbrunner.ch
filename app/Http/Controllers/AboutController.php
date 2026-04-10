<?php

namespace App\Http\Controllers;

use App\Models\PageText;
use Illuminate\View\View;

class AboutController extends Controller
{
	public function __invoke(): View
	{
		$intro = PageText::where('page', 'office')->first();

		return view('pages.about.index', [
			'title' => $intro?->title,
			'text' => $intro?->text,
		]);
	}
}
