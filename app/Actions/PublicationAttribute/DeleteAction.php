<?php

namespace App\Actions\PublicationAttribute;

use App\Models\PublicationAttribute;

class DeleteAction
{
	public function execute(PublicationAttribute $attribute): void
	{
		$attribute->delete();
	}
}
