<?php

namespace App\Actions\PageText;

use App\Models\PageText;

class UpdateAction
{
	public function execute(PageText $pageText, array $data): PageText
	{
		$pageText->update($data);

		return $pageText;
	}
}
