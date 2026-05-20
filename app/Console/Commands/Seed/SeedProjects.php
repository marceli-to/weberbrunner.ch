<?php

namespace App\Console\Commands\Seed;

use App\Models\Category;
use App\Models\Location;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeedProjects extends Command
{
	use ConfirmableTrait;

	protected $signature = 'app:seed-projects {--dummy : Seed with dummy data instead of project-data.json} {--force : Force the operation to run in production}';

	protected $description = 'Seed projects from project-data.json or with dummy data';

	private array $projectTitles = [
		'Recyclingzentrum Juch-Areal',
		'Wohnhaus H. Weiningen',
		'Lokstadt',
		'Stadtraum Bahnhof',
		'Puschkinallee',
		'Spinnerei III',
		'Bergacker',
		'Hagmannareal',
		'Kulturzentrum Rote Fabrik',
		'Genossenschaft Kalkbreite',
		'Wohnüberbauung Sihlbogen',
		'Schulhaus Leutschenbach',
		'Hunziker Areal',
		'Freilager',
		'Zollhaus',
		'Gewerbehaus Binz',
	];

	private array $cityNames = [
		'zuerich' => ['Zürich', 'Winterthur', 'Zürich-Affoltern', 'Windisch', 'Langenthal', 'Dietikon', 'Baden', 'Uster'],
		'berlin' => ['Berlin', 'Berlin-Mitte', 'Kreuzberg', 'Prenzlauer Berg', 'Friedrichshain', 'Charlottenburg'],
	];

	private array $linkUrls = [
		'https://competitions.espazium.ch/de/wettbewerbe/entscheide',
		'https://www.swiss-architects.com/de/weberbrunner-architekten-zurich',
		'https://afasiaarchzine.com/2024/03/weberbrunner/',
		'https://www.archdaily.com/tag/weberbrunner',
		'https://www.dezeen.com/tag/zurich/',
		'https://www.baunetz.de/meldungen/',
		'https://www.hochparterre.ch/nachrichten/architektur/',
		'https://www.espazium.ch/de/aktuelles/architektur',
	];

private array $shortDescriptions = [
		'Neubau einer Wohnüberbauung mit gemeinschaftlichem Aussenraum.',
		'Sanierung und Erweiterung eines denkmalgeschützten Gebäudes.',
		'Nachhaltige Holzbauweise mit Minergie-P-ECO-Standard.',
		'Städtebaulicher Wettbewerbsbeitrag für ein neues Quartierzentrum.',
		'Umnutzung eines Industrieareals zu Wohn- und Gewerbeflächen.',
	];

	private array $descriptions = [
		'Das Projekt entstand aus einem Wettbewerb und überzeugt durch seine klare städtebauliche Setzung. Die Gebäude bilden einen Hofraum, der als gemeinschaftlicher Aussenraum dient und vielfältige Nutzungsmöglichkeiten bietet.',
		'Die Architektur reagiert sensibel auf den Kontext und schafft einen Dialog zwischen Alt und Neu. Materialität und Farbgebung orientieren sich an der bestehenden Bebauung und interpretieren diese zeitgenössisch.',
		'Nachhaltigkeit stand von Beginn an im Zentrum des Entwurfs. Das Gebäude erfüllt den Minergie-P-ECO-Standard und setzt auf erneuerbare Energien sowie eine ressourcenschonende Bauweise.',
		'Die Wohnungen sind flexibel konzipiert und ermöglichen unterschiedliche Wohnformen. Grosszügige Gemeinschaftsräume und geteilte Infrastrukturen fördern das nachbarschaftliche Zusammenleben.',
		'Der Entwurf schafft eine Balance zwischen Dichte und Freiraum. Die gestaffelte Volumetrie ermöglicht optimale Besonnung und Aussicht für alle Wohnungen.',
	];

	private array $metaDescriptions = [
		'Neubau einer nachhaltigen Wohnüberbauung mit innovativer Holzbauweise und gemeinschaftlichem Aussenraum.',
		'Sanierung und Erweiterung eines denkmalgeschützten Gebäudes im Herzen der Stadt.',
		'Wettbewerbsbeitrag für ein zukunftsweisendes Quartierzentrum mit Minergie-P-ECO-Standard.',
		'Umnutzung eines Industrieareals zu einem lebendigen Wohn- und Gewerbequartier.',
		'Zeitgenössische Architektur im Dialog mit dem historischen Kontext und nachhaltiger Bauweise.',
		'Ressourcenschonender Neubau mit flexiblen Wohnkonzepten und gemeinschaftlicher Infrastruktur.',
		'Städtebauliche Neuinterpretation mit Fokus auf Nachhaltigkeit und soziale Durchmischung.',
		'Architektonisch anspruchsvoller Bau mit ökologischem Energiekonzept und hoher Wohnqualität.',
	];

	private array $captions = [
		'Visualisierung Aussenraum © weberbrunner architektur',
		'Ansicht Südfassade mit vorgelagertem Gartenbereich',
		'Blick in den gemeinschaftlichen Innenhof',
		'Detailansicht Fassade mit Holzverkleidung',
		'Grundriss Erdgeschoss mit Erschliessung',
		'Schnitt durch das Hauptgebäude',
		'Situationsplan mit Umgebungsgestaltung',
		'Materialisierung Fassade: Holz und Sichtbeton',
		'Vogelperspektive der Gesamtanlage',
		'Modellaufnahme Wettbewerbsbeitrag',
		'Innenraum Wohnung mit Blick nach Süden',
		'Treppenhaus mit natürlicher Belichtung',
		'Übergang Innen- und Aussenraum',
		'Konstruktionsdetail Dachaufbau',
		'Nachtansicht mit Beleuchtungskonzept',
	];

	public function handle(): void
	{
		if (!$this->confirmToProceed()) {
			return;
		}

		if ($this->option('dummy')) {
			$this->seedDummy();
		} else {
			$this->seedFromJson();
		}
	}

	private function seedFromJson(): void
	{
		$path = storage_path('app/project-data.json');

		if (!file_exists($path)) {
			$this->error('File not found: storage/app/project-data.json');
			return;
		}

		$data = json_decode(file_get_contents($path), true);

		if (!is_array($data)) {
			$this->error('Invalid JSON in project-data.json');
			return;
		}

		$zurich = Location::where('slug', 'zuerich')->first();
		$berlin = Location::where('slug', 'berlin')->first();

		if (!$zurich || !$berlin) {
			$this->error('Locations not found. Make sure zurich and berlin locations exist.');
			return;
		}

		$this->info('Seeding ' . count($data) . ' projects...');

		$created = 0;

		foreach ($data as $entry) {
			$number = $entry['Projektnummer'];
			$title = $entry['Projektname'];
			$city = $entry['Ort'] ?? null;
			$priority = $entry['Priorität (A=hoch 50, B=mittel 200, C=tief 300)'] ?? null;
			$location = (int) $number >= 1000 ? $berlin : $zurich;

			$slug = Str::slug($title) . '-' . $number;

			if (Project::where('slug', $slug)->exists()) {
				$slug .= '-' . Str::random(4);
			}

			$project = Project::create([
				'priority' => $priority,
				'number' => $number,
				'title' => $title,
				'slug' => $slug,
				'city' => $city,
				'location_id' => $location->id,
				'publish' => true,
			]);

			$this->attachPlaceholder($project);

			$created++;
			$this->line("  [{$number}] {$title}");
		}

		$this->info("Done! Created {$created} projects.");
	}

	private function seedDummy(): void
	{
		$teaserImages = range(1, 13);
		$projectImages = range(1, 5);
		$locations = Location::all();
		$categories = Category::all()->keyBy('slug');
		$statuses = Status::all()->keyBy('slug');
		$numberCounters = [];

		foreach ($locations as $loc) {
			$numberCounters[$loc->id] = $loc->slug === 'berlin' ? 1000 : 100;
		}

		$this->info('Seeding 50 dummy projects...');

		for ($i = 1; $i <= 50; $i++) {
			$title = $this->projectTitles[array_rand($this->projectTitles)];
			$location = $locations->random();
			$cities = $this->cityNames[$location->slug] ?? $this->cityNames['zuerich'];
			$cityName = $cities[array_rand($cities)];

			$number = (string) $numberCounters[$location->id]++;
			$slug = Str::slug($title . ' ' . $cityName) . '-' . $i;

			$project = Project::create([
				'title' => $title,
				'number' => $number,
				'slug' => $slug,
				'short_description' => $this->shortDescriptions[array_rand($this->shortDescriptions)],
				'description' => $this->descriptions[array_rand($this->descriptions)],
				'meta_description' => $this->metaDescriptions[array_rand($this->metaDescriptions)],
				'city' => $cityName,
				'location_id' => $location->id,
				'publish' => rand(0, 1) === 1,
			]);

			$teaserNum = $teaserImages[array_rand($teaserImages)];
			$this->attachImage($project, "dummy-teaser-{$teaserNum}.jpg", $title, 0, true);

			$ogNum = $teaserImages[array_rand($teaserImages)];
			$this->attachImage($project, "dummy-teaser-{$ogNum}.jpg", $title, 0, false, true);

			$numImages = rand(3, 8);
			for ($j = 1; $j <= $numImages; $j++) {
				$imgNum = $projectImages[array_rand($projectImages)];
				$this->attachImage($project, "dummy-project-{$imgNum}.jpg", "{$title} - Bild {$j}", $j, false);
			}

			if ($categories->isNotEmpty()) {
				$numCategories = rand(1, min(3, $categories->count()));
				$project->categories()->attach($categories->random($numCategories)->pluck('id'));
			}

			if ($statuses->isNotEmpty()) {
				$project->statuses()->attach($statuses->random()->id);
			}

			$numLinks = rand(0, 3);
			if ($numLinks > 0) {
				$selectedUrls = array_rand(array_flip($this->linkUrls), max(1, $numLinks));
				if (!is_array($selectedUrls)) {
					$selectedUrls = [$selectedUrls];
				}
				foreach (array_slice($selectedUrls, 0, $numLinks) as $order => $url) {
					$project->links()->create([
						'url' => $url,
						'sort_order' => $order,
					]);
				}
			}

			$this->line("  Created: {$title}");
		}

		$this->info('Done! Created 50 dummy projects with media.');
	}

	private function attachPlaceholder(Project $project): void
	{
		$disk = Storage::disk('public');
		$sourcePath = 'images/placeholder.png';

		if (!$disk->exists($sourcePath)) {
			$this->warn('  Missing: ' . $sourcePath);
			return;
		}

		$filename = 'placeholder-' . Str::random(6) . '.png';
		$disk->copy($sourcePath, "uploads/{$filename}");

		$fullPath = $disk->path("uploads/{$filename}");
		$dimensions = @getimagesize($fullPath);

		$project->media()->create([
			'file' => "uploads/{$filename}",
			'original_name' => 'placeholder.png',
			'mime_type' => 'image/png',
			'size' => $disk->size("uploads/{$filename}"),
			'width' => $dimensions[0] ?? null,
			'height' => $dimensions[1] ?? null,
			'alt' => $project->title,
			'is_teaser' => true,
			'is_og' => true,
			'sort_order' => 0,
		]);
	}

	private function attachImage(Project $project, string $sourceFile, string $alt, int $sortOrder, bool $isTeaser, bool $isOg = false): void
	{
		$disk = Storage::disk('public');
		$sourcePath = "images/{$sourceFile}";

		if (!$disk->exists($sourcePath)) {
			$this->warn("  Missing: {$sourcePath}");
			return;
		}

		$filename = Str::slug(pathinfo($sourceFile, PATHINFO_FILENAME))
			. '-' . Str::random(6) . '.jpg';

		$disk->copy($sourcePath, "uploads/{$filename}");

		$fullPath = $disk->path("uploads/{$filename}");
		$dimensions = @getimagesize($fullPath);

		$caption = $this->captions[array_rand($this->captions)];

		$project->media()->create([
			'file' => "uploads/{$filename}",
			'original_name' => $sourceFile,
			'mime_type' => 'image/jpeg',
			'size' => $disk->size("uploads/{$filename}"),
			'width' => $dimensions[0] ?? null,
			'height' => $dimensions[1] ?? null,
			'alt' => $alt,
			'caption' => $caption,
			'is_teaser' => $isTeaser,
			'is_og' => $isOg,
			'sort_order' => $sortOrder,
		]);
	}
}
