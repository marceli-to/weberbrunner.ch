<?php

namespace App\Actions\Masterdata;

use App\Models\Masterdata;

class UpdateAction
{
	public function execute(Masterdata $masterdata, array $data): Masterdata
	{
		$masterdata->update($data);

		return $masterdata;
	}
}
