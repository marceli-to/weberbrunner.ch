<?php

namespace App\Http\Controllers;

use App\Actions\Jury\ListAction;
use Illuminate\View\View;

class JuryController extends Controller
{
	public function __invoke(): View
	{
		$sections = (new ListAction)->execute();
		return view('pages.about.jury', compact('sections'));
	}
}
