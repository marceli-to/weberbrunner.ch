<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Location;
use App\Models\Project;
use App\Models\Status;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeedProjects extends Command
{
    protected $signature = 'app:seed-projects';

    protected $description = 'Seed categories, statuses, and 50 dummy projects';

    private array $categories = [
        'oeffentliche-gebaeude' => 'Öffentliche Gebäude',
        'wohnungsbau' => 'Wohnungsbau',
        'bauen-im-bestand' => 'Bauen im Bestand',
        'zustandsanalyse' => 'Zustandsanalyse',
        'zirkulaeres-bauen' => 'Zirkuläres Bauen',
        'lca' => 'LCA',
    ];

    private array $statuses = [
        'projekte' => 'Projekte',
        'in-bearbeitung' => 'In Bearbeitung',
        'realisiert' => 'Realisiert',
    ];

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

    private array $locations = [
        'zurich' => ['Zürich', 'Winterthur', 'Zürich-Affoltern', 'Windisch', 'Langenthal', 'Dietikon', 'Baden', 'Uster'],
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

    private array $attributeLabels = [
        'Auftraggeberin',
        'Entwurf und Generalplanung LP 1-8',
        'Projekt',
        'Umsetzung',
        'Budget',
        'Auszeichnungen',
        'Anzahl Wohnungen',
        'Geschossfläche',
        'Bauherrschaft',
        'Landschaftsarchitektur',
    ];

    private array $attributeValues = [
        'Auftraggeberin' => ['Fa. Bateg GmbH (GÜ) für die HOWOGE', 'Stadt Zürich', 'Baugenossenschaft Mehr als Wohnen', 'Allgemeine Baugenossenschaft Zürich', 'Stiftung PWG'],
        'Entwurf und Generalplanung LP 1-8' => ['ZOOMARCHITEKTEN', 'weberbrunner architekten', 'EM2N', 'Gigon/Guyer', 'Caruso St John'],
        'Projekt' => ['Neubau', 'Umbau', 'Sanierung', 'Erweiterung', 'Aufstockung'],
        'Umsetzung' => ['2020', '2021', '2022', '2023', '2024', '2025'],
        'Budget' => ['1.2 Mio.', '2.5 Mio.', '4.8 Mio.', '12 Mio.', '25 Mio.', '48 Mio.'],
        'Auszeichnungen' => ['Architekturpreis 2024', 'Gute Bauten 2023 (1. Platz)', 'best architect 19, gold award', 'AW20 Architekturpreis Region Winterthur'],
        'Anzahl Wohnungen' => ['12 Wohnungen', '24 Wohnungen', '48 Wohnungen', '63 teilweise geförderte Wohnungen', '120 Wohnungen'],
        'Geschossfläche' => ['1\'200 m²', '3\'500 m²', '8\'000 m²', '15\'000 m²', '25\'000 m²'],
        'Bauherrschaft' => ['Privat', 'Öffentlich', 'Genossenschaft', 'Stiftung'],
        'Landschaftsarchitektur' => ['Rotzler Krebs Partner', 'Westpol Landschaftsarchitektur', 'Studio Vulkan', 'Antón & Ghiggi'],
    ];

    public function handle(): void
    {
        $this->info('Seeding categories...');
        $categories = $this->seedCategories();

        $this->info('Seeding statuses...');
        $statuses = $this->seedStatuses();

        $this->info('Seeding 50 projects...');
        $this->seedProjects($categories, $statuses);

        $this->info('Done! Created 50 projects with attributes and media.');
    }

    private function seedCategories(): array
    {
        $result = [];
        foreach ($this->categories as $slug => $title) {
            $result[$slug] = Category::firstOrCreate(
                ['slug' => $slug],
                ['title' => $title]
            );
        }
        return $result;
    }

    private function seedStatuses(): array
    {
        $result = [];
        foreach ($this->statuses as $slug => $title) {
            $result[$slug] = Status::firstOrCreate(
                ['slug' => $slug],
                ['title' => $title]
            );
        }
        return $result;
    }

    private function seedProjects(array $categories, array $statuses): void
    {
        $teaserImages = range(1, 13);
        $projectImages = range(1, 5);
        $locations = Location::all();
        $numberCounters = [];

        foreach ($locations as $loc) {
            $numberCounters[$loc->id] = $loc->slug === 'berlin' ? 1000 : 100;
        }

        for ($i = 1; $i <= 50; $i++) {
            $title = $this->projectTitles[array_rand($this->projectTitles)];
            $location = $locations->random();
            $cities = $this->locations[$location->slug] ?? $this->locations['zurich'];
            $cityName = $cities[array_rand($cities)];

            $number = (string) $numberCounters[$location->id]++;
            $slug = Str::slug($title . ' ' . $cityName) . '-' . $i;

            $project = Project::create([
                'title' => $title,
                'number' => $number,
                'slug' => $slug,
                'short_description' => $this->generateShortDescription(),
                'description' => $this->generateDescription(),
                'meta_description' => $this->generateMetaDescription(),
                'city' => $cityName,
                'location_id' => $location->id,
                'publish' => rand(0, 1) === 1,
            ]);

            // Add 3-7 attributes
            $numAttributes = rand(3, 7);
            $selectedLabels = array_rand(array_flip($this->attributeLabels), $numAttributes);
            if (!is_array($selectedLabels)) {
                $selectedLabels = [$selectedLabels];
            }

            foreach ($selectedLabels as $order => $label) {
                $values = $this->attributeValues[$label] ?? ['Muster'];
                $project->attributes()->create([
                    'label' => $label,
                    'value' => $values[array_rand($values)],
                    'sort_order' => $order,
                ]);
            }

            // Add teaser image
            $teaserNum = $teaserImages[array_rand($teaserImages)];
            $this->attachImage($project, "dummy-teaser-{$teaserNum}.jpg", $title, 0, true);

            // Add OG image
            $ogNum = $teaserImages[array_rand($teaserImages)];
            $this->attachImage($project, "dummy-teaser-{$ogNum}.jpg", $title, 0, false, true);

            // Add 3-8 project images
            $numImages = rand(3, 8);
            for ($j = 1; $j <= $numImages; $j++) {
                $imgNum = $projectImages[array_rand($projectImages)];
                $this->attachImage($project, "dummy-project-{$imgNum}.jpg", "{$title} - Bild {$j}", $j, false);
            }

            // Attach 1-3 categories
            $numCategories = rand(1, 3);
            $categoryKeys = array_rand($categories, $numCategories);
            if (!is_array($categoryKeys)) {
                $categoryKeys = [$categoryKeys];
            }
            foreach ($categoryKeys as $key) {
                $project->categories()->attach($categories[$key]->id);
            }

            // Attach 1 status
            $statusKey = array_rand($statuses);
            $project->statuses()->attach($statuses[$statusKey]->id);

            // Add 0-3 links
            $numLinks = rand(0, 3);
            $selectedUrls = array_rand(array_flip($this->linkUrls), max(1, $numLinks));
            if (!is_array($selectedUrls)) {
                $selectedUrls = [$selectedUrls];
            }
            if ($numLinks > 0) {
                foreach (array_slice($selectedUrls, 0, $numLinks) as $order => $url) {
                    $project->links()->create([
                        'url' => $url,
                        'sort_order' => $order,
                    ]);
                }
            }

            $this->line("  Created: {$title}");
        }
    }

    private function generateShortDescription(): string
    {
        $descriptions = [
            'Neubau einer Wohnüberbauung mit gemeinschaftlichem Aussenraum.',
            'Sanierung und Erweiterung eines denkmalgeschützten Gebäudes.',
            'Nachhaltige Holzbauweise mit Minergie-P-ECO-Standard.',
            'Städtebaulicher Wettbewerbsbeitrag für ein neues Quartierzentrum.',
            'Umnutzung eines Industrieareals zu Wohn- und Gewerbeflächen.',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function generateMetaDescription(): string
    {
        $descriptions = [
            'Neubau einer nachhaltigen Wohnüberbauung mit innovativer Holzbauweise und gemeinschaftlichem Aussenraum.',
            'Sanierung und Erweiterung eines denkmalgeschützten Gebäudes im Herzen der Stadt.',
            'Wettbewerbsbeitrag für ein zukunftsweisendes Quartierzentrum mit Minergie-P-ECO-Standard.',
            'Umnutzung eines Industrieareals zu einem lebendigen Wohn- und Gewerbequartier.',
            'Zeitgenössische Architektur im Dialog mit dem historischen Kontext und nachhaltiger Bauweise.',
            'Ressourcenschonender Neubau mit flexiblen Wohnkonzepten und gemeinschaftlicher Infrastruktur.',
            'Städtebauliche Neuinterpretation mit Fokus auf Nachhaltigkeit und soziale Durchmischung.',
            'Architektonisch anspruchsvoller Bau mit ökologischem Energiekonzept und hoher Wohnqualität.',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function generateDescription(): string
    {
        $paragraphs = [
            'Das Projekt entstand aus einem Wettbewerb und überzeugt durch seine klare städtebauliche Setzung. Die Gebäude bilden einen Hofraum, der als gemeinschaftlicher Aussenraum dient und vielfältige Nutzungsmöglichkeiten bietet.',
            'Die Architektur reagiert sensibel auf den Kontext und schafft einen Dialog zwischen Alt und Neu. Materialität und Farbgebung orientieren sich an der bestehenden Bebauung und interpretieren diese zeitgenössisch.',
            'Nachhaltigkeit stand von Beginn an im Zentrum des Entwurfs. Das Gebäude erfüllt den Minergie-P-ECO-Standard und setzt auf erneuerbare Energien sowie eine ressourcenschonende Bauweise.',
            'Die Wohnungen sind flexibel konzipiert und ermöglichen unterschiedliche Wohnformen. Grosszügige Gemeinschaftsräume und geteilte Infrastrukturen fördern das nachbarschaftliche Zusammenleben.',
            'Der Entwurf schafft eine Balance zwischen Dichte und Freiraum. Die gestaffelte Volumetrie ermöglicht optimale Besonnung und Aussicht für alle Wohnungen.',
        ];

        return $paragraphs[array_rand($paragraphs)];
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

        $project->media()->create([
            'file' => "uploads/{$filename}",
            'original_name' => $sourceFile,
            'mime_type' => 'image/jpeg',
            'size' => $disk->size("uploads/{$filename}"),
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'alt' => $alt,
            'is_teaser' => $isTeaser,
            'is_og' => $isOg,
            'sort_order' => $sortOrder,
        ]);
    }
}
