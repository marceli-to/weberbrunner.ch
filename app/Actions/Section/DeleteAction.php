<?php

namespace App\Actions\Section;

use App\Models\Section;
use Illuminate\Support\Facades\DB;

class DeleteAction
{
	public function execute(Section $section): void
	{
		DB::transaction(function () use ($section): void {
			$section->awards()->delete();
			$section->juries()->delete();
			$section->talks()->delete();
			$section->delete();
		});
	}
}
