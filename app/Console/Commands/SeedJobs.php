<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\Location;
use Illuminate\Console\Command;

class SeedJobs extends Command
{
	protected $signature = 'app:seed-jobs';

	protected $description = 'Seed job listings';

	private array $jobs = [
		[
			'location' => 'zuerich',
			'title' => 'Juniorbauleitung für anspruchsvolle Projekte der öffentlichen Hand (m/w/d)',
			'description' => '<p>Für unsere Bauprojekte der öffentlichen Hand suchen wir eine Juniorbauleiterin oder einen Juniorbauleiter, die oder der Freude daran hat, auf der Baustelle Verantwortung zu übernehmen, von erfahrenen Kolleginnen und Kollegen zu lernen und Schritt für Schritt komplexe Projekte selbstständig zu leiten.</p><h3>Was dich erwartet</h3><ul class="basic-list"><li>Mitarbeit bei Ausschreibungen, Vergaben und Terminplanung</li><li>Unterstützung bei der Leitung von Bausitzungen und Koordination mit Planung und Ausführung</li><li>Begleitung von Qualitäts-, Kosten- und Terminkontrollen auf der Baustelle</li><li>Mitarbeit an der Digitalisierung der Bauleitung (BIM auf der Baustelle)</li><li>Die Möglichkeit, in grösseren Projekten mitzuwirken und mittelfristig kleinere Projekte eigenständig zu übernehmen</li></ul><h3>Was du mitbringst</h3><ul class="basic-list"><li>Eine handwerkliche, technische oder architektonische Ausbildung (z. B. Zeichner EFZ, Architektin/Architekt FH, Technikerin/Techniker, Zimmer:innenausbildung, Schreinerin/Schreiner oder gleichwertig)</li><li>Erste Erfahrungen im Planungs- oder Baubereich</li><li>Interesse, dich in die Bauleitung einzuarbeiten und Verantwortung zu übernehmen</li><li>Begeisterung für Architektur, präzises Arbeiten und nachhaltiges Bauen</li><li>Freude an digitalen Arbeitsmethoden (BIM, modellbasiertes Bauen)</li><li>Offenheit, Teamgeist und lösungsorientiertes Denken</li></ul><h3>Was wir dir bieten</h3><ul class="basic-list"><li>Eine strukturierte Einführung in die Bauleitung mit erfahrenem Coaching</li><li>Mitarbeit an Projekten mit architektonischer und gesellschaftlicher Relevanz</li><li>Raum für Eigeninitiative, Entwicklung und Weiterbildung</li><li>Ein kollegiales, interdisziplinäres Team in Zürich und Berlin</li><li>Faire Arbeitsbedingungen und moderne digitale Werkzeuge</li></ul>',
			'contact_email' => 'bewerbungen@weberbrunner.ch',
		],
		[
			'location' => 'zuerich',
			'title' => 'Kaufmännische Leitung Ressort Finanzen / HR 50% – 80% (m/w/d)',
			'description' => '<p>Wir suchen eine kaufmännische Leitung, die diese Grundlagen in unserem Planungsbüro steuert und weiterentwickelt. Die Position ist eng mit der Geschäftsleitung verbunden und übernimmt Verantwortung für zentrale kaufmännische und organisatorische Aufgaben.</p><h3>Deine Aufgabenbereiche</h3><h3>Finanzen</h3><ul class="basic-list"><li>Finanzbuchhaltung für Architekturbüro und Generalplaner:innenmandate</li><li>Verantwortung für Monats- und Jahresabschlüsse inklusive Reporting mit Kennzahlen</li><li>Liquiditätsplanung und -kontrolle</li><li>Verantwortung für Debitoren- und Kreditorenbuchungen</li><li>Verantwortung für monatliche Lohnabrechnungen und -zahlungen</li></ul><h3>Personalwesen</h3><ul class="basic-list"><li>Verantwortung für den administrativen Bereich des Personalwesens</li><li>Erfassung und Bearbeitung von Unfall- und Krankheitsmeldungen</li><li>Ansprechpartner für Versicherungen, Banken, Pensionskasse und weitere Partner:innen</li></ul><h3>Digitalisierung &amp; Prozessoptimierung</h3><ul class="basic-list"><li>Einführung und Weiterentwicklung digitaler Lösungen zur Optimierung der Finanzprozesse</li><li>Einführung und Weiterentwicklung von Prozessen der Projektadministration</li></ul><h3>Administration</h3><ul class="basic-list"><li>Unterstützung der Geschäftsleitung in administrativen Belangen</li></ul><h3>Wir bieten</h3><ul class="basic-list"><li>Vielseitige und verantwortungsvolle Tätigkeit mit Gestaltungsspielraum</li><li>flexible Arbeitsgestaltung, faire Bedingungen und Sozialleistungen</li><li>Perspektive auf Verantwortung für Finanzwesen und Generalplaner:innenadministration</li><li>Kollegiales Team und wertschätzende Unternehmenskultur</li></ul><h3>Dein Profil</h3><ul class="basic-list"><li>Kaufmännische Ausbildung im Bereich Finanz- und Rechnungswesen</li><li>Erfahrung in Finanz- und Rechnungswesen, Lohnbuchhaltung, Monats- und Jahresabschlüssen, idealerweise in einem Planungsbüro</li><li>Kenntnisse in der Personaladministration</li><li>Selbständige, verantwortungsbewusste Arbeitsweise</li><li>Lösungsorientiertes Denken und Teamplayer</li></ul>',
			'contact_email' => 'bewerbungen@weberbrunner.ch',
		],
	];

	public function handle(): void
	{
		foreach ($this->jobs as $order => $data) {
			$location = Location::where('slug', $data['location'])->first();

			$job = Job::create([
				'title' => $data['title'],
				'description' => $data['description'],
				'contact_email' => $data['contact_email'],
				'location_id' => $location->id,
				'publish' => true,
				'sort_order' => $order + 1,
			]);

			$this->line("  Created: {$job->title} ({$location->title})");
		}

		$this->info("Done! Created " . count($this->jobs) . " job listings.");
	}
}
