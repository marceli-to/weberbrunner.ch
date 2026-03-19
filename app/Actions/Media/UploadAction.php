<?php

namespace App\Actions\Media;

use App\Support\ImageUrlSigner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UploadAction
{
	private const ALLOWED_MIMES = [
		'image/jpeg',
		'image/png',
		'image/webp',
		'image/gif',
		'application/pdf',
	];

	public function execute(UploadedFile $file): array
	{
		$this->validateFileContent($file);

		$directory = 'temp';
		$filename = $this->uniqueFilename($file->getClientOriginalName());

		$file->storeAs($directory, $filename, 'public');

		$isImage = str_starts_with($file->getMimeType(), 'image/');
		$dimensions = $isImage ? @getimagesize($file->getRealPath()) : null;

		return [
			'uuid' => Str::uuid()->toString(),
			'file' => $filename,
			'original_name' => $file->getClientOriginalName(),
			'mime_type' => $file->getMimeType(),
			'size' => $file->getSize(),
			'width' => $dimensions[0] ?? null,
			'height' => $dimensions[1] ?? null,
			'alt' => null,
			'caption' => null,
			'is_teaser' => false,
			'is_image' => $isImage,
			'sort_order' => 0,
			'orientation' => $this->orientation($dimensions[0] ?? null, $dimensions[1] ?? null),
			'thumbnail_url' => $isImage ? ImageUrlSigner::signUrl('temp/' . $filename, ['w' => 200, 'h' => 200, 'fit' => 'crop']) : null,
			'preview_url' => $isImage ? ImageUrlSigner::signUrl('temp/' . $filename, ['w' => 800, 'fit' => 'max']) : null,
			'file_url' => !$isImage ? asset('storage/temp/' . $filename) : null,
			'download_url' => asset('storage/temp/' . $filename),
			'_temp' => true,
		];
	}

	private function uniqueFilename(string $originalName): string
	{
		$name = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
		$extension = Str::lower(pathinfo($originalName, PATHINFO_EXTENSION));
		$suffix = Str::random(6);

		return $name . '-' . $suffix . '.' . $extension;
	}

	private function validateFileContent(UploadedFile $file): void
	{
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$detectedMime = finfo_file($finfo, $file->getRealPath());
		finfo_close($finfo);

		if (!in_array($detectedMime, self::ALLOWED_MIMES, true)) {
			throw ValidationException::withMessages([
				'file' => 'The file content does not match an allowed type.',
			]);
		}

		if (str_starts_with($detectedMime, 'image/') && !@getimagesize($file->getRealPath())) {
			throw ValidationException::withMessages([
				'file' => 'The file is not a valid image.',
			]);
		}

		$content = file_get_contents($file->getRealPath(), false, null, 0, 1024);
		if (str_contains($content, '<svg') || str_contains($content, '<?php') || str_contains($content, '<script')) {
			throw ValidationException::withMessages([
				'file' => 'The file contains invalid content.',
			]);
		}
	}

	private function orientation(?int $width, ?int $height): string
	{
		if (!$width || !$height) {
			return 'unknown';
		}
		if ($width > $height) {
			return 'landscape';
		}
		if ($height > $width) {
			return 'portrait';
		}
		return 'square';
	}
}
