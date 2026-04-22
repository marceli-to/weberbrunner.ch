<?php

namespace App\View\Components\Media;

use App\Models\Media;
use App\Support\ImageUrlSigner;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Image extends Component
{
	public string $src;
	public string $alt;
	public int $width;
	public int $height;
	public float $aspectRatio;
	public string $fit;
	public int $quality;
	public array $formats;
	public string $class;
	public string $loading;
	public string $sizes;
	public array $sources = [];
	public string $fallbackUrl;

	public function __construct(
		Media $media,
		string $sizes = '100vw',
		int $maxWidth = 1600,
		?string $alt = null,
		string $fit = 'crop',
		?int $quality = null,
		?array $formats = null,
		string $class = '',
		string $loading = 'lazy',
	) {
		$this->src = $media->file;
		$this->alt = $alt ?? $media->alt ?? '';
		$this->fit = $fit;
		$this->quality = $quality ?? (int) config('media.quality', 90);
		$this->formats = $formats ?? config('media.formats', ['avif', 'webp', 'jpg']);
		$this->class = $class;
		$this->loading = $loading;
		$this->sizes = $sizes;

		$baseWidth = $media->width ?? 1;
		$baseHeight = $media->height ?? 1;
		$this->aspectRatio = $baseHeight / max($baseWidth, 1);

		$widths = array_values(array_filter(config('media.widths', []), fn ($w) => $w <= $maxWidth));
		if ($media->width) {
			$widths = array_values(array_filter($widths, fn ($w) => $w <= $media->width));
		}
		if (empty($widths)) {
			$widths = [min($maxWidth, $media->width ?: $maxWidth)];
		}

		$this->width = end($widths);
		$this->height = (int) round($this->width * $this->aspectRatio);

		$this->buildSources($widths);
	}

	protected function buildSources(array $widths): void
	{
		foreach ($this->formats as $format) {
			if ($format === 'jpg' || $format === 'jpeg') {
				continue;
			}

			$this->sources[] = [
				'srcset' => $this->buildSrcset($format, $widths),
				'type' => $this->getMimeType($format),
				'sizes' => $this->sizes,
			];
		}

		$this->fallbackUrl = $this->buildUrl('jpg', $this->width, $this->height);
	}

	protected function buildSrcset(string $format, array $widths): string
	{
		$parts = [];
		foreach ($widths as $w) {
			$h = (int) round($w * $this->aspectRatio);
			$parts[] = $this->buildUrl($format, $w, $h) . ' ' . $w . 'w';
		}

		return implode(', ', $parts);
	}

	protected function buildUrl(string $format, int $width, int $height): string
	{
		return ImageUrlSigner::signUrl($this->src, [
			'w' => $width,
			'h' => $height,
			'fit' => $this->fit,
			'fm' => $format,
			'q' => $this->quality,
		]);
	}

	protected function getMimeType(string $format): string
	{
		return match ($format) {
			'avif' => 'image/avif',
			'webp' => 'image/webp',
			'jpg', 'jpeg' => 'image/jpeg',
			'png' => 'image/png',
			default => 'image/jpeg',
		};
	}

	public function render(): View|Closure|string
	{
		return view('components.media.image');
	}
}
