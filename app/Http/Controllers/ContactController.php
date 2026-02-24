<?php

namespace App\Http\Controllers;

use App\Actions\Contact\ListAction;
use Illuminate\View\View;

class ContactController extends Controller
{
	public function __invoke(): View
	{
		$locations = (new ListAction)->execute(published: true);
		return view('pages.about.contact', compact('locations'));
	}
}
