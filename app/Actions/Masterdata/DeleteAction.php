<?php

namespace App\Actions\Masterdata;

use App\Models\Masterdata;

class DeleteAction
{
	public function execute(Masterdata $masterdata): void
	{
		$masterdata->delete();
	}
}
