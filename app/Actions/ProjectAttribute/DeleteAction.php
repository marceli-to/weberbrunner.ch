<?php

namespace App\Actions\ProjectAttribute;

use App\Models\ProjectAttribute;

class DeleteAction
{
	public function execute(ProjectAttribute $attribute): void
	{
		$attribute->delete();
	}
}
