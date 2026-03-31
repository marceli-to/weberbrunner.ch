<?php

namespace App\Actions\Publication;

use App\Models\Publication;

class PrepareViewDataAction
{
	public function execute(Publication $publication, bool $isPreview = false): array
	{
		return [
			'publication' => $publication,
			'isPreview' => $isPreview,
			'slides' => $publication->blocks->firstWhere('type', 'fixed-slider')?->media ?? collect(),
			'publicationInfo' => $publication->attributes->map(fn ($attr) => [
				'label' => $attr->key,
				'value' => $attr->value,
			])->toArray(),
		];
	}
}
