<?php

namespace App\Actions\PublicationBlockLink;

use App\Models\PublicationBlockLink;

class UpdateAction
{
	public function execute(PublicationBlockLink $link, array $data): PublicationBlockLink
	{
		$link->update($data);

		return $link;
	}
}
