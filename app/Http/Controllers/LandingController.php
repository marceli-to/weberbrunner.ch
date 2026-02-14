<?php

namespace App\Http\Controllers;

use App\Actions\Project\ListAction;
use Illuminate\View\View;

class LandingController extends Controller
{
	public function __invoke(): View
	{
		$projects = (new ListAction)->execute();

		return view('pages.landing', [
			'columns' => $this->splitIntoColumns($projects, 3),
		]);
	}

	protected function splitIntoColumns($items, int $count): array
	{
		$columns = array_fill(0, $count, []);

		foreach ($items->values() as $index => $item) {
			$columns[$index % $count][] = $item;
		}

		return $columns;
	}
}
