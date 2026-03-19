<?php

namespace App\Actions\PublicationBlockLink;

use App\Models\PublicationBlockLink;

class ToggleAction
{
	public function execute(PublicationBlockLink $link): void
	{
		$link->update(['publish' => !$link->publish]);
	}
}
