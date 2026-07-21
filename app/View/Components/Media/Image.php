<?php

namespace App\View\Components\Media;

use App\Models\Media;
use App\Support\ImageUrlSigner;
use App\Support\ImageVariants;
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
	public int $sharpen;
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
		?array $displayHeights = null,
		?int $sharpen = null,
	) {
		$this->src = $media->file;
		$this->alt = $alt ?? $media->alt ?? '';
		$this->fit = $fit;
		$this->quality = $quality ?? (int) config('media.quality', 90);
		$this->sharpen = $sharpen ?? (int) config('media.sharpen', 0);
		$this->formats = $formats ?? config('media.formats', ['avif', 'webp', 'jpg']);
		$this->class = $class;
		$this->loading = $loading;
		$this->sizes = $sizes;

		$baseWidth = $media->width ?? 1;
		$baseHeight = $media->height ?? 1;
		$this->aspectRatio = $baseHeight / max($baseWidth, 1);

		$variants = $displayHeights
			? $this->variantsByHeight($media, $displayHeights)
			: $this->variantsByWidth($media, $maxWidth);

		if ($displayHeights) {
			$this->sizes = $this->buildSizesFromHeights($displayHeights);
		}

		$last = end($variants);
		$this->width = $last['w'];
		$this->height = $last['h'];

		$this->buildSources($variants);
	}

	protected function variantsByWidth(Media $media, int $maxWidth): array
	{
		$widths = array_values(array_filter(config('media.widths', []), fn ($w) => $w <= $maxWidth));
		if ($media->width) {
			$widths = array_values(array_filter($widths, fn ($w) => $w <= $media->width));
		}
		if (empty($widths)) {
			$widths = [min($maxWidth, $media->width ?: $maxWidth)];
		}

		return array_map(fn ($w) => [
			'w' => $w,
			'h' => (int) round($w * $this->aspectRatio),
		], $widths);
	}

	protected function variantsByHeight(Media $media, array $displayHeights): array
	{
		$maxHeight = max($displayHeights) * 2;
		$variants = ImageVariants::byHeight($media->width, $media->height, $maxHeight);

		if (empty($variants)) {
			$variants = $this->variantsByWidth($media, 1920);
		}

		return $variants;
	}

	protected function buildSizesFromHeights(array $displayHeights): string
	{
		krsort($displayHeights);

		$parts = [];
		foreach ($displayHeights as $minWidth => $cssHeight) {
			$w = (int) round($cssHeight / max($this->aspectRatio, 0.0001));
			$parts[] = $minWidth > 0 ? "(min-width: {$minWidth}px) {$w}px" : "{$w}px";
		}

		return implode(', ', $parts);
	}

	protected function buildSources(array $variants): void
	{
		foreach ($this->formats as $format) {
			if ($format === 'jpg' || $format === 'jpeg') {
				continue;
			}

			$this->sources[] = [
				'srcset' => $this->buildSrcset($format, $variants),
				'type' => $this->getMimeType($format),
				'sizes' => $this->sizes,
			];
		}

		$this->fallbackUrl = $this->buildUrl('jpg', $this->width, $this->height);
	}

	protected function buildSrcset(string $format, array $variants): string
	{
		$parts = [];
		foreach ($variants as $variant) {
			$parts[] = $this->buildUrl($format, $variant['w'], $variant['h']) . ' ' . $variant['w'] . 'w';
		}

		return implode(', ', $parts);
	}

	protected function buildUrl(string $format, int $width, int $height): string
	{
		$params = [
			'w' => $width,
			'h' => $height,
			'fit' => $this->fit,
			'fm' => $format,
			'q' => $this->quality,
		];

		if ($this->sharpen > 0) {
			$params['sharp'] = $this->sharpen;
		}

		return ImageUrlSigner::signUrl($this->src, $params);
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
