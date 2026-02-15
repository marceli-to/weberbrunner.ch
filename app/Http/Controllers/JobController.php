<?php

namespace App\Http\Controllers;

use App\Actions\Job\ListAction;
use Illuminate\View\View;

class JobController extends Controller
{
	public function __invoke(): View
	{
		$locations = (new ListAction)->execute(published: true);
		return view('pages.about.jobs', compact('locations'));
	}
}
