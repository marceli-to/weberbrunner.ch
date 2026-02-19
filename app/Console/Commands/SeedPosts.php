<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeedPosts extends Command
{
	protected $signature = 'app:seed-posts';

	protected $description = 'Seed 20 dummy blog posts with random images';

	private array $titles = [
		'Nachhaltigkeit im Städtebau',
		'Zirkuläres Bauen als Zukunftsmodell',
		'Wohnungsbau neu gedacht',
		'Holzbau und Verdichtung',
		'Architektur im Dialog mit dem Bestand',
		'Genossenschaftliches Wohnen heute',
		'Energieeffiziente Sanierung',
		'Städtische Freiräume gestalten',
		'Materialität und Nachhaltigkeit',
		'Bauen mit Recyclingbeton',
		'Partizipation im Planungsprozess',
		'Klimaanpassung im Hochbau',
		'Lebenszyklusanalyse in der Praxis',
		'Transformation industrieller Areale',
		'Verdichtung und Lebensqualität',
		'Suffizienz in der Architektur',
		'Biodiversität und Baukultur',
		'Denkmalpflege und Weiterbauen',
		'Ressourcenschonung im Bauwesen',
		'Quartierentwicklung in Zürich',
	];

	private array $paragraphs = [
		'Das Projekt entstand aus einem Wettbewerb und überzeugt durch seine klare städtebauliche Setzung. Die Gebäude bilden einen Hofraum, der als gemeinschaftlicher Aussenraum dient und vielfältige Nutzungsmöglichkeiten bietet.',
		'Die Architektur reagiert sensibel auf den Kontext und schafft einen Dialog zwischen Alt und Neu. Materialität und Farbgebung orientieren sich an der bestehenden Bebauung und interpretieren diese zeitgenössisch.',
		'Nachhaltigkeit stand von Beginn an im Zentrum des Entwurfs. Das Gebäude erfüllt den Minergie-P-ECO-Standard und setzt auf erneuerbare Energien sowie eine ressourcenschonende Bauweise.',
		'Die Wohnungen sind flexibel konzipiert und ermöglichen unterschiedliche Wohnformen. Grosszügige Gemeinschaftsräume und geteilte Infrastrukturen fördern das nachbarschaftliche Zusammenleben.',
		'Der Entwurf schafft eine Balance zwischen Dichte und Freiraum. Die gestaffelte Volumetrie ermöglicht optimale Besonnung und Aussicht für alle Wohnungen.',
		'Die konstruktive Logik des Gebäudes ist ablesbar und bestimmt den architektonischen Ausdruck. Tragende Elemente aus Sichtbeton gliedern die Fassade und verleihen dem Bau eine tektonische Klarheit.',
		'Der Umgang mit dem Bestand war ein zentrales Thema. Durch gezielte Eingriffe wurde die bestehende Substanz erhalten und gleichzeitig zeitgemässer Wohnraum geschaffen.',
		'Das Konzept basiert auf der Idee einer durchlässigen Erdgeschosszone. Gewerbe, Gemeinschaftsräume und öffentliche Nutzungen beleben das Quartier und schaffen Identität.',
		'Die Fassade aus vorgefertigten Holzelementen verbindet ökologische Anforderungen mit einer hochwertigen Gestaltung. Die natürliche Vergrauung des Holzes wird als gestalterisches Mittel eingesetzt.',
		'Im Zentrum steht die Frage, wie verdichtetes Bauen mit hoher Wohnqualität vereinbar ist. Die Antwort liegt in der sorgfältigen Dimensionierung der Räume und der Beziehung zwischen Innen und Aussen.',
	];

	public function handle(): void
	{
		$this->info('Seeding 20 blog posts...');

		shuffle($this->titles);

		for ($i = 0; $i < 20; $i++) {
			$title = $this->titles[$i];
			$post = Post::create([
				'title' => $title,
				'slug' => Str::slug($title) . '-' . ($i + 1),
				'content' => $this->generateContent(),
				'publish' => rand(0, 1) === 1,
				'sort_order' => $i,
			]);

			$numImages = rand(1, 3);
			for ($j = 0; $j < $numImages; $j++) {
				$this->attachImage($post, $j, $j === 0);
			}

			$this->line("  Created: {$title}");
		}

		$this->info('Done! Created 20 posts with images.');
	}

	private function generateContent(): string
	{
		$count = rand(2, 4);
		$selected = array_rand($this->paragraphs, $count);
		if (!is_array($selected)) {
			$selected = [$selected];
		}

		return implode("\n\n", array_map(fn ($i) => $this->paragraphs[$i], $selected));
	}

	private function attachImage(Post $post, int $sortOrder, bool $isTeaser): void
	{
		$sourceNum = rand(1, 5);
		$sourcePath = "images/dummy-project-{$sourceNum}.jpg";

		if (!Storage::disk('public')->exists($sourcePath)) {
			$this->warn("  Missing: {$sourcePath}");
			return;
		}

		$filename = Str::slug(pathinfo("dummy-project-{$sourceNum}", PATHINFO_FILENAME))
			. '-' . Str::random(6) . '.jpg';

		Storage::disk('public')->copy($sourcePath, "uploads/{$filename}");

		$fullPath = Storage::disk('public')->path("uploads/{$filename}");
		$dimensions = @getimagesize($fullPath);

		$post->media()->create([
			'file' => "uploads/{$filename}",
			'original_name' => "dummy-project-{$sourceNum}.jpg",
			'mime_type' => 'image/jpeg',
			'size' => Storage::disk('public')->size("uploads/{$filename}"),
			'width' => $dimensions[0] ?? null,
			'height' => $dimensions[1] ?? null,
			'alt' => $post->title,
			'is_teaser' => $isTeaser,
			'sort_order' => $sortOrder,
		]);
	}
}
