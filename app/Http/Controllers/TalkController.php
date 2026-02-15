<?php

namespace App\Http\Controllers;

use App\Actions\Talk\ListAction;
use Illuminate\View\View;

class TalkController extends Controller
{
	public function __invoke(): View
	{
		$sections = (new ListAction)->execute(published: true);
		return view('pages.about.talks', compact('sections'));
	}
}
