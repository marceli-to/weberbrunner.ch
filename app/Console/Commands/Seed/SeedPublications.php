<?php

namespace App\Console\Commands\Seed;

use App\Models\Block;
use App\Models\BlockLink;
use App\Models\Publication;
use App\Models\PublicationAttribute;
use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeedPublications extends Command
{
	use ConfirmableTrait;

	protected $signature = 'app:seed-publications {--force : Force the operation to run in production}';

	protected $description = 'Seed publications from storage/app/publications/publikationen.json';

	private array $typeLabels = [
		'book' => 'Buch',
		'brochure' => 'Broschüre',
		'catalogue' => 'Katalog',
		'guide' => 'Leitfaden',
		'article' => 'Artikel',
		'award' => 'Auszeichnung',
		'video' => 'Video',
	];

	public function handle(): void
	{
		if (!$this->confirmToProceed()) {
			return;
		}

		$path = storage_path('app/publications/publikationen.json');

		if (!file_exists($path)) {
			$this->error('File not found: storage/app/publications/publikationen.json');
			return;
		}

		$data = json_decode(file_get_contents($path), true);

		if (!is_array($data)) {
			$this->error('Invalid JSON in publikationen.json');
			return;
		}

		DB::statement('SET FOREIGN_KEY_CHECKS=0');
		$publicationBlockIds = Block::where('blockable_type', Publication::class)->pluck('id');
		BlockLink::whereIn('block_id', $publicationBlockIds)->delete();
		Media::where('mediable_type', Block::class)->whereIn('mediable_id', $publicationBlockIds)->delete();
		Block::where('blockable_type', Publication::class)->delete();
		PublicationAttribute::truncate();
		Media::where('mediable_type', Publication::class)->delete();
		Publication::truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1');

		$this->info('Seeding ' . count($data) . ' publications...');

		$created = 0;

		foreach ($data as $index => $entry) {
			$slug = Str::slug($entry['title']);

			if (Publication::where('slug', $slug)->exists()) {
				$slug .= '-' . Str::random(4);
			}

			$publication = Publication::create([
				'title' => $entry['title'],
				'slug' => $slug,
				'meta_description' => $entry['description'] ?? null,
				'publish' => true,
				'sort_order' => $index,
			]);

			$this->seedAttributes($publication, $entry);
			$this->seedTeaserImage($publication, $entry);
			$this->seedFixedSliderBlock($publication, $entry);
			$this->seedFile($publication, $entry);
			$this->seedExternalLinks($publication, $entry);

			$created++;
			$this->line("  [{$entry['id']}] {$entry['title']}");
		}

		$this->info("Done! Created {$created} publications.");
	}

	private function seedAttributes(Publication $publication, array $entry): void
	{
		$sortOrder = 0;

		$type = $entry['type'] ?? null;
		if ($type) {
			$publication->attributes()->create([
				'key' => 'Typ',
				'value' => $this->typeLabels[$type] ?? $type,
				'sort_order' => $sortOrder++,
			]);
		}

		$isbn = $entry['isbn'] ?? null;
		if ($isbn) {
			$publication->attributes()->create([
				'key' => 'ISBN',
				'value' => $isbn,
				'sort_order' => $sortOrder++,
			]);
		}

		$issn = $entry['issn'] ?? null;
		if ($issn) {
			$publication->attributes()->create([
				'key' => 'ISSN',
				'value' => $issn,
				'sort_order' => $sortOrder++,
			]);
		}

		$year = $entry['year'] ?? null;
		if ($year) {
			$publication->attributes()->create([
				'key' => 'Jahr',
				'value' => (string) $year,
				'sort_order' => $sortOrder++,
			]);
		}

	}

	private function seedTeaserImage(Publication $publication, array $entry): void
	{
		$image = $entry['image'] ?? null;
		if (!$image || !isset($image['filename'])) {
			return;
		}

		$sourcePath = storage_path("app/publications/images/{$image['filename']}");
		if (!file_exists($sourcePath)) {
			$this->warn("  Missing image: {$image['filename']}");
			return;
		}

		$disk = Storage::disk('public');
		$extension = pathinfo($image['filename'], PATHINFO_EXTENSION);
		$filename = Str::slug(pathinfo($image['filename'], PATHINFO_FILENAME))
			. '-' . Str::random(6) . '.' . $extension;

		$disk->put("uploads/{$filename}", file_get_contents($sourcePath));

		$fullPath = $disk->path("uploads/{$filename}");
		$dimensions = @getimagesize($fullPath);
		$mimeType = mime_content_type($fullPath) ?: 'image/jpeg';

		$publication->media()->create([
			'file' => "uploads/{$filename}",
			'original_name' => $image['filename'],
			'mime_type' => $mimeType,
			'size' => $disk->size("uploads/{$filename}"),
			'width' => $dimensions[0] ?? null,
			'height' => $dimensions[1] ?? null,
			'alt' => $entry['title'],
			'is_teaser' => true,
			'is_og' => true,
			'sort_order' => 0,
		]);
	}

	private function seedFixedSliderBlock(Publication $publication, array $entry): void
	{
		$image = $entry['image'] ?? null;
		if (!$image || !isset($image['filename'])) {
			return;
		}

		$sourcePath = storage_path("app/publications/images/{$image['filename']}");
		if (!file_exists($sourcePath)) {
			return;
		}

		$block = $publication->blocks()->create([
			'type' => 'fixed-slider',
			'sort_order' => 0,
		]);

		$disk = Storage::disk('public');
		$extension = pathinfo($image['filename'], PATHINFO_EXTENSION);
		$filename = Str::slug(pathinfo($image['filename'], PATHINFO_FILENAME))
			. '-' . Str::random(6) . '.' . $extension;

		$disk->put("uploads/{$filename}", file_get_contents($sourcePath));

		$fullPath = $disk->path("uploads/{$filename}");
		$dimensions = @getimagesize($fullPath);
		$mimeType = mime_content_type($fullPath) ?: 'image/jpeg';

		$block->media()->create([
			'file' => "uploads/{$filename}",
			'original_name' => $image['filename'],
			'mime_type' => $mimeType,
			'size' => $disk->size("uploads/{$filename}"),
			'width' => $dimensions[0] ?? null,
			'height' => $dimensions[1] ?? null,
			'alt' => $entry['title'],
			'is_teaser' => false,
			'sort_order' => 0,
		]);
	}

	private function seedFile(Publication $publication, array $entry): void
	{
		$file = $entry['file'] ?? null;
		if (!$file || !isset($file['filename'])) {
			return;
		}

		$sourcePath = storage_path("app/publications/files/{$file['filename']}");
		if (!file_exists($sourcePath)) {
			$this->warn("  Missing file: {$file['filename']}");
			return;
		}

		$disk = Storage::disk('public');
		$extension = pathinfo($file['filename'], PATHINFO_EXTENSION);
		$filename = Str::slug(pathinfo($file['filename'], PATHINFO_FILENAME))
			. '-' . Str::random(6) . '.' . $extension;

		$disk->put("uploads/{$filename}", file_get_contents($sourcePath));

		$fullPath = $disk->path("uploads/{$filename}");
		$mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

		$publication->media()->create([
			'file' => "uploads/{$filename}",
			'original_name' => $file['filename'],
			'mime_type' => $mimeType,
			'size' => $disk->size("uploads/{$filename}"),
			'alt' => $entry['title'],
			'is_teaser' => false,
			'is_download' => true,
			'sort_order' => 1,
		]);
	}

	private function seedExternalLinks(Publication $publication, array $entry): void
	{
		$links = $entry['externalLinks'] ?? [];
		if (empty($links)) {
			return;
		}

		$block = $publication->blocks()->create([
			'type' => 'links',
			'title' => 'Links',
			'sort_order' => 10,
		]);

		foreach ($links as $index => $url) {
			$host = parse_url($url, PHP_URL_HOST);
			$label = $host ? str_replace('www.', '', $host) : $url;

			$block->links()->create([
				'title' => $label,
				'url' => $url,
				'link_type' => 'external',
				'sort_order' => $index,
				'publish' => true,
			]);
		}
	}
}
