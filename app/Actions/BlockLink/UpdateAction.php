<?php

namespace App\Actions\BlockLink;

use App\Models\BlockLink;

class UpdateAction
{
	public function execute(BlockLink $link, array $data): BlockLink
	{
		$link->update($data);

		return $link;
	}
}
