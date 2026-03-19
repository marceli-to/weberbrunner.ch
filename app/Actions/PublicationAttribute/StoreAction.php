<?php

namespace App\Actions\PublicationAttribute;

use App\Models\Publication;
use App\Models\PublicationAttribute;

class StoreAction
{
	public function execute(Publication $publication, array $data): PublicationAttribute
	{
		return $publication->attributes()->create($data);
	}
}
