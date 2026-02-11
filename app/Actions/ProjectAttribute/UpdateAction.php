<?php

namespace App\Actions\ProjectAttribute;

use App\Models\ProjectAttribute;

class UpdateAction
{
	public function execute(ProjectAttribute $attribute, array $data): ProjectAttribute
	{
		$attribute->update($data);

		return $attribute;
	}
}
