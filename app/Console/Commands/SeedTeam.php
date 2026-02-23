<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\TeamMember;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeedTeam extends Command
{
	protected $signature = 'app:seed-team';

	protected $description = 'Seed team members with images';

	private array $members = [
		['firstname' => 'Alessandra', 'name' => 'Ortelli', 'title' => 'M. Sc. Arch ETH', 'since' => 2025, 'email' => 'alessandra.ortelli@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/alessandra-ortelli.jpg'],
		['firstname' => 'Ali', 'name' => 'Rashedi', 'title' => 'Lernender', 'since' => 2024, 'email' => 'ali.rashedi@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/ali.jpg'],
		['firstname' => 'Angel', 'name' => 'Dodov', 'title' => 'B. Sc. Architektur TU-Wien', 'since' => 2020, 'email' => 'angel.dodov@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/angel_2-scaled.jpg'],
		['firstname' => 'Anna', 'name' => 'Januszkiewicz', 'title' => 'M. Sc. Arch TU Warschau', 'since' => 2020, 'email' => 'anna.januszkiewicz@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/wb_potraits_anna_klein.jpg'],
		['firstname' => 'Basil', 'name' => 'Wirth', 'title' => 'Lernender', 'since' => 2022, 'email' => 'basil.wirth@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/basil_2-scaled.jpg'],
		['firstname' => 'Beatrice', 'name' => 'Borggreve', 'title' => 'B.A. TU München', 'since' => 2025, 'email' => 'beatrice.borggreve@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/beatrice.jpeg'],
		['firstname' => 'Boris', 'name' => 'Brunner', 'title' => 'dipl. Arch. FH / BSA / SIA / AKB', 'since' => null, 'email' => 'boris.brunner@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/boris_2-scaled.jpg'],
		['firstname' => 'Carsten', 'name' => 'Pesch', 'title' => 'M.Sc. TU Dortmund', 'since' => 2020, 'email' => null, 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/carsten.jpg'],
		['firstname' => 'Caspar', 'name' => 'Süß', 'title' => 'Praktikant, RWTH Aachen', 'since' => 2025, 'email' => 'caspar.suess@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/000-profil-caspar-suess_.jpg'],
		['firstname' => 'Deyari', 'name' => 'Said', 'title' => 'M. A. FH Potsdam', 'since' => 2024, 'email' => 'deyari.said@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/deyari-said.jpg'],
		['firstname' => 'Ekin', 'name' => 'Eryilmaz', 'title' => 'M. Sc. TU Berlin', 'since' => 2025, 'email' => 'ekin.eryilmaz@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/ekin-bearb.jpg'],
		['firstname' => 'Elise', 'name' => 'Pischetsrieder', 'title' => 'dipl.-Ing. Architektin BDA / AKB / SIA', 'since' => 2006, 'email' => 'elise.pischetsrieder@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/elise_low.jpg'],
		['firstname' => 'Eszter', 'name' => 'David', 'title' => 'M. Sc. Arch TU Budapest', 'since' => 2023, 'email' => 'eszter.david@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/eszter_2.jpg'],
		['firstname' => 'Eva', 'name' => 'Geering', 'title' => 'dipl. Arch. ETH', 'since' => 2004, 'email' => 'eva.geering@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/eva_2-scaled.jpg'],
		['firstname' => 'Fabian', 'name' => 'Bürgler', 'title' => 'Zeichner EFZ Fachrichtung Architektur', 'since' => 2020, 'email' => 'fabian.buergler@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/wb_portraits_fabian_klein.jpg'],
		['firstname' => 'Fabian', 'name' => 'Friedli', 'title' => 'BIM-Koordinator, Hochbauzeichner', 'since' => 2006, 'email' => 'fabian.friedli@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/fabian.jpg'],
		['firstname' => 'Feia', 'name' => 'Nehl', 'title' => 'M.Sc. TU Berlin', 'since' => 2023, 'email' => 'feia.nehl@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/feia_2-scaled.jpg'],
		['firstname' => 'Francesco', 'name' => 'Turrini', 'title' => 'MSc Arch PoliMi', 'since' => 2023, 'email' => 'francesco.turrini@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/wb_portraits_francesco_2022.jpg'],
		['firstname' => 'Henrike', 'name' => 'Gosda', 'title' => 'M.Sc. BUW', 'since' => 2025, 'email' => 'henrike.gosda@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/henrike.jpg'],
		['firstname' => 'Iris', 'name' => 'Bergamaschi', 'title' => 'MSc Arch PoliMi', 'since' => 2015, 'email' => 'iris.bergamaschi@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/iris_2-scaled.jpg'],
		['firstname' => 'Johannes', 'name' => 'Boden', 'title' => 'dipl.-Ing. BUW / Architekt HAK', 'since' => 2019, 'email' => 'johannes.boden@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/johannes.jpg'],
		['firstname' => 'Jonas', 'name' => 'Korten', 'title' => 'M.Sc. Uni Kassel', 'since' => 2025, 'email' => 'jonas.korten@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/jonas_klein.jpg'],
		['firstname' => 'Jule', 'name' => 'Jünger', 'title' => 'M.Sc. TU Berlin', 'since' => 2021, 'email' => 'jule.juenger@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/jule_2-scaled.jpg'],
		['firstname' => 'Kim', 'name' => 'Ballmann', 'title' => 'Lernende', 'since' => 2024, 'email' => 'kim.ballmann@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/kim.jpg'],
		['firstname' => 'Laurent', 'name' => 'Baumgartner', 'title' => 'dipl. Arch. FH', 'since' => 2001, 'email' => 'laurent.baumgartner@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/laurent.jpg'],
		['firstname' => 'Liva', 'name' => 'Roze', 'title' => 'Werkstudentin, TU Berlin', 'since' => 2024, 'email' => 'liva.roze@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/liva-roze.jpg'],
		['firstname' => 'Luis', 'name' => 'Betancort', 'title' => 'MSc Arch ETSAM', 'since' => 2023, 'email' => 'luis.betancort@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/luis_2-scaled.jpg'],
		['firstname' => 'Magdalena', 'name' => 'Biermann', 'title' => 'M.Sc. TU Braunschweig, Architektin AKB', 'since' => 2016, 'email' => 'magdalena.biermann@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/magdalena_2-scaled.jpg'],
		['firstname' => 'Marie', 'name' => 'Heyer', 'title' => 'M.Sc. BUW', 'since' => 2023, 'email' => 'marie.heyer@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/marie_2-scaled.jpg'],
		['firstname' => 'Miriam', 'name' => 'Attallah', 'title' => 'M.A. UdK Berlin', 'since' => 2022, 'email' => 'miriam.attallah@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/miriam_2-scaled.jpg'],
		['firstname' => 'Mirjam', 'name' => 'von Busch', 'title' => null, 'since' => 2021, 'email' => 'mirjam.vonbusch@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/mirjam_2-scaled.jpg'],
		['firstname' => 'Nicole', 'name' => 'Hangartner', 'title' => 'Hochbauzeichnerin / Administration', 'since' => 2006, 'email' => 'nicole.hangartner@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/wb_portraits_nicole.jpg'],
		['firstname' => 'Pablo', 'name' => 'De Sola Montiel', 'title' => 'PgD The Berlage / MSc TUDelft / MSc ETSAS', 'since' => 2021, 'email' => 'pablo.desola@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/pablo.jpg'],
		['firstname' => 'René', 'name' => 'Breuer', 'title' => 'dipl. Hochbautechniker HF', 'since' => 2017, 'email' => 'rene.breuer@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/rene_2-scaled.jpg'],
		['firstname' => 'Roger', 'name' => 'Weber', 'title' => 'dipl. Arch. FH / BSA / SIA / AKB', 'since' => null, 'email' => 'roger.weber@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/roger_2-scaled.jpg'],
		['firstname' => 'Sabine', 'name' => 'Besch', 'title' => 'eidg. dipl. Bauleiterin', 'since' => 2011, 'email' => 'sabine.besch@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/sabine_2-scaled.jpg'],
		['firstname' => 'Sena', 'name' => 'Gür', 'title' => 'M. Sc. TU Berlin', 'since' => 2023, 'email' => 'sena.guer@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/sena_2-scaled.jpg'],
		['firstname' => 'Silke', 'name' => 'Geuer', 'title' => 'dipl. Ing. RWTH', 'since' => 2011, 'email' => 'silke.geuer@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/silke_2-scaled.jpg'],
		['firstname' => 'Sophie', 'name' => 'Ziemer', 'title' => 'M.A. Architektur TU München', 'since' => 2024, 'email' => 'sophie.ziemer@weberbrunner.de', 'location' => 'berlin', 'image' => 'https://weberbrunner.eu/content/uploads/sophie.jpg'],
		['firstname' => 'Tamas', 'name' => 'Ozvald', 'title' => 'dipl. Ing. Arch.', 'since' => 2010, 'email' => 'tamas.ozvald@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/tamas_2-scaled.jpg'],
		['firstname' => 'Volker', 'name' => 'Schopp', 'title' => 'dipl. Ing. FH / Arch.', 'since' => 2007, 'email' => 'volker.schopp@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'https://weberbrunner.eu/content/uploads/volker_2-scaled.jpg'],
	];

	public function handle(): void
	{
		$locations = Location::all()->keyBy('slug');
		$disk = Storage::disk('public');

		$this->info('Seeding team members...');

		foreach ($this->members as $order => $data) {
			$slug = Str::slug($data['firstname'] . ' ' . $data['name']);

			$member = TeamMember::create([
				'firstname' => $data['firstname'],
				'name' => $data['name'],
				'email' => $data['email'],
				'title' => $data['title'],
				'since' => $data['since'],
				'location_id' => $locations[$data['location']]->id,
				'slug' => $slug,
				'publish' => true,
				'sort_order' => $order,
			]);

			if ($data['image']) {
				$this->downloadImage($member, $data['image'], $disk);
			}

			$this->line("  Created: {$data['firstname']} {$data['name']}");
		}

		$this->info("Done! Created " . count($this->members) . " team members.");
	}

	private function downloadImage(TeamMember $member, string $url, $disk): void
	{
		try {
			$response = Http::timeout(15)->get($url);

			if (!$response->successful()) {
				$this->warn("  Failed to download: {$url}");
				return;
			}

			$originalName = basename(parse_url($url, PHP_URL_PATH));
			$extension = pathinfo($originalName, PATHINFO_EXTENSION) ?: 'jpg';
			$filename = Str::slug($member->firstname . '-' . $member->name)
				. '-' . Str::random(6) . '.' . $extension;

			$disk->put("uploads/{$filename}", $response->body());

			$fullPath = $disk->path("uploads/{$filename}");
			$dimensions = @getimagesize($fullPath);
			$mimeType = $dimensions['mime'] ?? ('image/' . $extension);

			$member->media()->create([
				'file' => "uploads/{$filename}",
				'original_name' => $originalName,
				'mime_type' => $mimeType,
				'size' => $disk->size("uploads/{$filename}"),
				'alt' => $member->firstname . ' ' . $member->name,
				'is_teaser' => true,
				'sort_order' => 0,
				'width' => $dimensions[0] ?? null,
				'height' => $dimensions[1] ?? null,
			]);
		} catch (\Exception $e) {
			$this->warn("  Image error for {$member->firstname} {$member->name}: {$e->getMessage()}");
		}
	}
}
