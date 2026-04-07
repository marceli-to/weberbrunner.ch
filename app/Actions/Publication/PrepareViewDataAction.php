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

		$download = null;
		if ($downloadFile) {
			$download = [
				'url' => '/' . $downloadFile->file,
				'extension' => strtoupper(pathinfo($downloadFile->original_name ?? $downloadFile->file, PATHINFO_EXTENSION)),
				'size' => $this->formatFileSize($downloadFile->size),
			];
		}

		return [
			'publication' => $publication,
			'isPreview' => $isPreview,
			'slides' => $publication->blocks->firstWhere('type', 'fixed-slider')?->media ?? collect(),
			'publicationInfo' => $info,
			'download' => $download,
		];
	}

	private function formatFileSize(?int $bytes): ?string
	{
		if (!$bytes) {
			return null;
		}

		if ($bytes >= 1048576) {
			return round($bytes / 1048576, 1) . ' MB';
		}

		return round($bytes / 1024) . ' KB';
	}
}
