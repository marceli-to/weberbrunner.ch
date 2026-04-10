<?php

namespace App\Actions\PageText;

use App\Models\PageText;

class FindAction
{
	public function execute(string $page): PageText
	{
		return PageText::firstOrCreate(['page' => $page], [
			'title' => null,
			'text' => null,
		]);
	}
}
