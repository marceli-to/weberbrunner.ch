<?php

namespace App\Actions\Jury;

use App\Models\Jury;

class UpdateAction
{
	public function execute(Jury $jury, array $data): Jury
	{
		$jury->update($data);

		return $jury;
	}
}
