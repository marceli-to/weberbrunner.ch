<?php

namespace App\Actions\Media;

use App\Support\ImageDownsizer;
use App\Support\ImageUrlSigner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
		$storedPath = Storage::disk('public')->path($directory . '/' . $filename);

		$width = null;
		$height = null;
		$size = $file->getSize();

		if ($isImage) {
			$this->archiveOriginal($storedPath, $filename);

			$info = ImageDownsizer::downsizeIfNeeded($storedPath);
			if ($info) {
				$width = $info['width'];
				$height = $info['height'];
				$size = $info['size'];
			}
		}

		return [
			'uuid' => Str::uuid()->toString(),
			'file' => $filename,
			'original_name' => $file->getClientOriginalName(),
			'mime_type' => $file->getMimeType(),
			'size' => $size,
			'width' => $width,
			'height' => $height,
			'alt' => null,
			'caption' => null,
			'is_teaser' => false,
			'is_image' => $isImage,
			'sort_order' => 0,
			'orientation' => $this->orientation($width, $height),
			'thumbnail_url' => $isImage ? ImageUrlSigner::signUrl('temp/' . $filename, ['w' => 200, 'h' => 200, 'fit' => 'crop']) : null,
			'preview_url' => $isImage ? ImageUrlSigner::signUrl('temp/' . $filename, ['w' => 800, 'fit' => 'max']) : null,
			'file_url' => !$isImage ? asset('storage/temp/' . $filename) : null,
			'download_url' => asset('storage/temp/' . $filename),
			'_temp' => true,
		];
	}

	private function archiveOriginal(string $sourcePath, string $filename): void
	{
		$stream = fopen($sourcePath, 'r');
		if ($stream === false) {
			return;
		}

		try {
			Storage::disk('originals')->writeStream('temp/' . $filename, $stream);
		} finally {
			if (is_resource($stream)) {
				fclose($stream);
			}
		}
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
