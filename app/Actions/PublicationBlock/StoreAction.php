<?php

namespace App\Actions\PublicationBlock;

use App\Models\Publication;
use App\Models\PublicationBlock;

class StoreAction
{
	public function execute(Publication $publication, array $data): PublicationBlock
	{
		return $publication->blocks()->create($data);
	}
}
