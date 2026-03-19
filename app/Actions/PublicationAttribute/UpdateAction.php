<?php

namespace App\Actions\PublicationAttribute;

use App\Models\PublicationAttribute;

class UpdateAction
{
	public function execute(PublicationAttribute $attribute, array $data): PublicationAttribute
	{
		$attribute->update($data);
		return $attribute;
	}
}
