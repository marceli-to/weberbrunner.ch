<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\TeamMember;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeedTeam extends Command
{
	protected $signature = 'app:seed-team';

	protected $description = 'Seed team members with media';

	private array $members = [
		['firstname' => 'Anna', 'name' => 'Müller', 'title' => 'M. Sc. Arch ETH', 'since' => 2018, 'email' => 'anna.mueller@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-1.jpg'],
		['firstname' => 'Boris', 'name' => 'Brunner', 'title' => 'dipl. Arch. FH / BSA / SIA', 'since' => 2015, 'email' => 'boris.brunner@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-2.jpg'],
		['firstname' => 'Carla', 'name' => 'Schneider', 'title' => 'M. Sc. Arch TU Zürich', 'since' => 2020, 'email' => 'carla.schneider@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-3.jpg'],
		['firstname' => 'Daniel', 'name' => 'Weber', 'title' => 'B. Sc. Architektur ZHAW', 'since' => 2021, 'email' => 'daniel.weber@weberbrunner.ch', 'location' => 'berlin', 'image' => 'images/dummy-team-4.jpg'],
		['firstname' => 'Elena', 'name' => 'Fischer', 'title' => 'M. Sc. Arch ETH', 'since' => 2019, 'email' => 'elena.fischer@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-5.jpg'],
		['firstname' => 'Florian', 'name' => 'Keller', 'title' => 'dipl. Arch. FH', 'since' => 2017, 'email' => 'florian.keller@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-6.jpg'],
		['firstname' => 'Gabriela', 'name' => 'Hofmann', 'title' => 'M. Sc. Arch TU Wien', 'since' => 2022, 'email' => 'gabriela.hofmann@weberbrunner.ch', 'location' => 'berlin', 'image' => 'images/dummy-team-7.jpg'],
		['firstname' => 'Hans', 'name' => 'Zimmermann', 'title' => 'B. Sc. Architektur FHNW', 'since' => 2020, 'email' => 'hans.zimmermann@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-8.jpg'],
		['firstname' => 'Irene', 'name' => 'Schmid', 'title' => 'M. Sc. Arch ETH', 'since' => 2016, 'email' => 'irene.schmid@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-9.jpg'],
		['firstname' => 'Jonas', 'name' => 'Meier', 'title' => 'Lernender', 'since' => 2024, 'email' => 'jonas.meier@weberbrunner.ch', 'location' => 'berlin', 'image' => 'images/dummy-team-10.jpg'],
		['firstname' => 'Kathrin', 'name' => 'Gerber', 'title' => 'M. Sc. Arch TU München', 'since' => 2019, 'email' => 'kathrin.gerber@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-11.jpg'],
		['firstname' => 'Lukas', 'name' => 'Huber', 'title' => 'dipl. Arch. FH / SIA', 'since' => 2018, 'email' => 'lukas.huber@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-12.jpg'],
		['firstname' => 'Maria', 'name' => 'Steiner', 'title' => 'M. Sc. Arch ETH', 'since' => 2021, 'email' => 'maria.steiner@weberbrunner.ch', 'location' => 'berlin', 'image' => 'images/dummy-team-13.jpg'],
		['firstname' => 'Niklaus', 'name' => 'Baumann', 'title' => 'B. Sc. Architektur HSR', 'since' => 2023, 'email' => 'niklaus.baumann@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-14.jpg'],
		['firstname' => 'Olivia', 'name' => 'Graf', 'title' => 'M. Sc. Arch TU Berlin', 'since' => 2020, 'email' => 'olivia.graf@weberbrunner.ch', 'location' => 'berlin', 'image' => 'images/dummy-team-15.jpg'],
		['firstname' => 'Patrick', 'name' => 'Frei', 'title' => 'dipl. Arch. FH', 'since' => 2017, 'email' => 'patrick.frei@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-16.jpg'],
		['firstname' => 'Rahel', 'name' => 'Widmer', 'title' => 'M. Sc. Arch ETH', 'since' => 2019, 'email' => 'rahel.widmer@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-17.jpg'],
		['firstname' => 'Stefan', 'name' => 'Moser', 'title' => 'M. Sc. Arch TU Darmstadt', 'since' => 2022, 'email' => 'stefan.moser@weberbrunner.ch', 'location' => 'berlin', 'image' => 'images/dummy-team-18.jpg'],
		['firstname' => 'Tamara', 'name' => 'Bühler', 'title' => 'B. Sc. Architektur ZHAW', 'since' => 2021, 'email' => 'tamara.buehler@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-19.jpg'],
		['firstname' => 'Urs', 'name' => 'Roth', 'title' => 'dipl. Arch. FH / BSA', 'since' => 2014, 'email' => 'urs.roth@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-20.jpg'],
		['firstname' => 'Vera', 'name' => 'Künzler', 'title' => 'M. Sc. Arch ETH', 'since' => 2020, 'email' => 'vera.kuenzler@weberbrunner.ch', 'location' => 'berlin', 'image' => 'images/dummy-team-21.jpg'],
		['firstname' => 'Werner', 'name' => 'Sutter', 'title' => 'M. Sc. Arch TU Graz', 'since' => 2018, 'email' => 'werner.sutter@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-22.jpg'],
		['firstname' => 'Xenia', 'name' => 'Lehmann', 'title' => 'Lernende', 'since' => 2025, 'email' => 'xenia.lehmann@weberbrunner.ch', 'location' => 'berlin', 'image' => 'images/dummy-team-23.jpg'],
		['firstname' => 'Yannick', 'name' => 'Wyss', 'title' => 'B. Sc. Architektur FHNW', 'since' => 2023, 'email' => 'yannick.wyss@weberbrunner.ch', 'location' => 'zuerich', 'image' => 'images/dummy-team-24.jpg'],
	];

	private array $periods = [
		'2005 – 2010',
		'2008 – 2013',
		'2010 – 2015',
		'2012 – 2017',
		'2014 – 2019',
		'2015 – 2018',
		'2016 – 2020',
		'2018 – 2021',
		'seit 2018',
		'seit 2019',
		'seit 2020',
		'seit 2021',
		'seit 2022',
	];

	private array $descriptions = [
		'Architekturstudium an der ETH Zürich | Austauschsemester an der TU Delft, Niederlande',
		'Architekturstudium an der TU München | Vertiefung Städtebau und Nachhaltigkeit',
		'Bachelor Architektur an der ZHAW Winterthur | Praxisjahr bei Herzog & de Meuron',
		'Master Architektur an der TU Wien | Forschungsprojekt zirkuläres Bauen',
		'Mitarbeit bei weberbrunner architekten AG',
		'Projektleitung weberbrunner architekten | Wettbewerbserfolge im Wohnungsbau',
		'Wissenschaftliche Mitarbeit am Lehrstuhl für Konstruktion und Entwurf',
		'Dozentin für Entwurf an der ZHAW Winterthur',
		'Mitglied Fachkommission Städtebau Zürich',
		'Architektin BDA | Mitglied im SIA Section Zürich',
		'Praktikum bei Caruso St John Architects, London',
		'Mitarbeit im Büro EM2N, Zürich | Schwerpunkt Wettbewerbe',
	];

	public function handle(): void
	{
		$locations = Location::all()->keyBy('slug');

		$this->info('Seeding team members...');
		$this->seedMembers($locations);

		$this->info("Done! Created " . count($this->members) . " team members.");
	}

	private function seedMembers($locations): void
	{
		$disk = Storage::disk('public');

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

			$imagePath = $data['image'];
			$fullPath = $disk->path($imagePath);
			$dimensions = file_exists($fullPath) ? @getimagesize($fullPath) : false;

			$member->media()->create([
				'file' => $imagePath,
				'original_name' => basename($imagePath),
				'mime_type' => 'image/jpeg',
				'size' => file_exists($fullPath) ? filesize($fullPath) : null,
				'alt' => $data['firstname'] . ' ' . $data['name'],
				'is_teaser' => true,
				'sort_order' => 0,
				'width' => $dimensions ? $dimensions[0] : null,
				'height' => $dimensions ? $dimensions[1] : null,
			]);

			$this->seedBios($member);

			$this->line("  Created: {$data['firstname']} {$data['name']}");
		}
	}

	private function seedBios(TeamMember $member): void
	{
		$count = rand(2, 5);
		$periods = collect($this->periods)->random($count)->values();
		$descriptions = collect($this->descriptions)->random($count)->values();

		foreach ($periods as $order => $period) {
			$member->bios()->create([
				'period' => $period,
				'description' => $descriptions[$order],
				'sort_order' => $order,
			]);
		}
	}
}
