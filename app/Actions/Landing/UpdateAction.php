<?php

namespace App\Actions\Landing;

use App\Models\Landing;

class UpdateAction
{
	public function execute(Landing $landing, array $data): Landing
	{
		$landing->update($data);

		return $landing;
	}
}
