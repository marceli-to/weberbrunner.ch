<?php

namespace App\Actions\Publication;

use App\Models\Publication;

class PrepareViewDataAction
{
	public function execute(Publication $publication, bool $isPreview = false): array
	{
		$downloadFile = $publication->download->first();
		$info = $publication->attributes->map(fn ($attr) => [
			'label' => $attr->key,
			'value' => $attr->value,
		])->toArray();

		if ($downloadFile && count($info)) {
			$info[array_key_last($info)]['link'] = '/' . $downloadFile->file;
		}

		return [
			'publication' => $publication,
			'isPreview' => $isPreview,
			'slides' => $publication->blocks->firstWhere('type', 'fixed-slider')?->media ?? collect(),
			'publicationInfo' => $info,
		];
	}
}
