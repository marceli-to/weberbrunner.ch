<?php

namespace App\Actions\PublicationBlock;

use App\Models\PublicationBlock;

class DeleteAction
{
	public function execute(PublicationBlock $block): void
	{
		$block->media()->delete();
		$block->delete();
	}
}
