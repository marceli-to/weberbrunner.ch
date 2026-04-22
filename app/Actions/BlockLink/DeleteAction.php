<?php

namespace App\Actions\BlockLink;

use App\Models\BlockLink;

class DeleteAction
{
	public function execute(BlockLink $link): void
	{
		$link->delete();
	}
}
