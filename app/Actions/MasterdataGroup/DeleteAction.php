<?php

namespace App\Actions\MasterdataGroup;

use App\Models\MasterdataGroup;
use Illuminate\Support\Facades\DB;

class DeleteAction
{
	public function execute(MasterdataGroup $masterdataGroup): void
	{
		DB::transaction(function () use ($masterdataGroup): void {
			$masterdataGroup->masterdata()->delete();
			$masterdataGroup->delete();
		});
	}
}
