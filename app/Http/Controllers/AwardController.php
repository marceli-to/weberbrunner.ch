<?php

namespace App\Http\Controllers;

use App\Actions\Award\ListAction;
use Illuminate\View\View;

class AwardController extends Controller
{
	public function __invoke(): View
	{
		$sections = (new ListAction)->execute(published: true);
		return view('pages.about.awards', compact('sections'));
	}
}
