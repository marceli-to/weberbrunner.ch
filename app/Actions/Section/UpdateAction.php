<?php

namespace App\Actions\Section;

use App\Models\Section;

class UpdateAction
{
	public function execute(Section $section, array $data): Section
	{
		$section->update($data);

		return $section;
	}
}
