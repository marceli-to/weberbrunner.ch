<?php

namespace App\Actions\Section;

use App\Models\Section;

class DeleteAction
{
	public function execute(Section $section): void
	{
		$section->delete();
	}
}
