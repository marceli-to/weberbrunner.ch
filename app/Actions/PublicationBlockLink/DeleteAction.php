<?php

namespace App\Actions\PublicationBlockLink;

use App\Models\PublicationBlockLink;

class DeleteAction
{
	public function execute(PublicationBlockLink $link): void
	{
		$link->delete();
	}
}
