<?php

namespace App\Actions\Award;

use App\Models\Award;

class UpdateAction
{
	public function execute(Award $award, array $data): Award
	{
		$award->update($data);

		return $award;
	}
}
