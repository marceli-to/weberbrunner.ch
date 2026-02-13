<?php

namespace App\Console\Commands;

use App\Models\Section;
use Illuminate\Console\Command;

class SeedOfficeData extends Command
{
	protected $signature = 'app:seed-office-data';

	protected $description = 'Seed awards, jury entries and talks with their sections';

	public function handle(): void
	{
		$this->seedAwards();
		$this->seedJuries();
		$this->seedTalks();

		$this->info('Office data seeded.');
	}

	private function seedAwards(): void
	{
		$data = [
			'2023' => [
				'<p>Auszeichnung «best architects 24», Sanierung, Um- und Anbau, Haus B4, Zürich</p>',
			],
			'2021' => [
				'<p>Lobende Erwähnung – Hindernisfrei Architektur, Die Schweizer Fachstelle «40 Jahre Engagement für eine bessere Gesellschaft»</p>',
				'<p>WIA – Ausstellung Women in Architecture, Berlin</p>',
			],
			'2020' => [
				'<p>AW20 Architekturpreis Region Winterthur, für Wohnüberbauung Hagmannareal, Winterthur</p>',
				'<p>Auszeichnung «best architects 21», Loft Windisch</p>',
			],
			'2019' => [
				'<p>Architekturpreis Kanton Zürich Auszeichnung 19</p>',
			],
			'2018' => [
				'<p>Nominierung ARC-Award 2018, der Wohnüberbauung Hagmannareal, Winterthur</p>',
				'<p>Auszeichnung Gold «best architects 19», für Wohnüberbauung Hagmannareal, Winterthur</p>',
			],
			'2015' => [
				'<p>Auszeichnung «best architects 16», für Mehrfamilienhaus Im Amt, Gutenswil</p>',
				'<p>Auszeichnung «best architects 16», für Sportzentrum Eselriet, Effretikon</p>',
			],
			'2008' => [
				'<p>Auszeichnung «best architects 09», für Sporthalle Hardau, Zürich</p>',
				'<p>Europäischer Spengler-Metall Architekturpreis, für Sporthalle Hardau, Zürich</p>',
			],
			'2003' => [
				'<p>Denkmalschutzpreis «Wohnen unter Dächern», für Wohnhaus Schaufelberger</p>',
			],
		];

		$sortOrder = 0;
		foreach ($data as $year => $entries) {
			$section = Section::create([
				'title' => (string) $year,
				'type' => 'award',
				'sort_order' => $sortOrder++,
			]);

			$itemOrder = 0;
			foreach ($entries as $text) {
				$section->awards()->create([
					'text' => $text,
					'publish' => true,
					'sort_order' => $itemOrder++,
				]);
			}
		}
	}

	private function seedJuries(): void
	{
		$data = [
			'2026' => [
				'<p>Jurymitglied Technische Hochschule Köln – Neubau Mensa auf dem Campus Deutz (Elise Pischetsrieder)</p>',
			],
			'2025' => [
				'<p>Jurymitglied Projektwettbewerb Schulschwimmanlage Kesselhaus Letten (Eva Geering)</p>',
				'<p>Mitglied Gestaltungsbeirat Halle / Saale (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Bundesministerium, Berlin (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied DGNB-Platin Kommission für Gestaltungsqualität (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Deutscher Nachhaltigkeitspreis (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Schumacher Quartier Berlin-TXL (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied HolzbauPlus-Preis der FNR (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Berliner Holzbau Preis (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Quartiersentwicklung Vulkanstraße Berlin, Berlin (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Genossenschaftliches Wohnen, Joanes Preis (Elise Pischetsrieder)</p>',
			],
			'2024' => [
				'<p>Juryvorsitz Wohnhaus Du Lac, St. Moritz (Roger Weber)</p>',
				'<p>Jurymitglied Wohnquartier Luegisland, Gutenswil (Roger Weber)</p>',
				'<p>Jurymitglied Holzbaupreis Hessen 2024 (Elise Pischetsrieder)</p>',
				'<p>Juryvorsitzende Heinze Architektur Award (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Deutscher Hochschulbaupreis (Elise Pischetsrieder)</p>',
			],
			'2023' => [
				'<p>Jurypräsident Studienauftrag Ankenhof, Oberengstringen (Roger Weber)</p>',
				'<p>Jurymitglied Planungsstudie Farnbühl Wohlen (Eva Geering)</p>',
				'<p>Jurymitglied Studienauftrag Gesamtsanierung und Umnutzung Steinenvorstadt 5, Basel (Roger Weber)</p>',
				'<p>Jurymitglied Gubristareal, Weiningen (Roger Weber)</p>',
				'<p>Jurymitglied Laborgebäude Robert Koch Institut, Berlin (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Checkpoint Charlie Ost «Wohnen in Berlins historischer Mitte», Berlin (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied «Neuentwicklung eines gemischt genutzten Gebäudekomplexes M25 in Berlin-Wedding» (Elise Pischetsrieder)</p>',
			],
			'2022' => [
				'<p>Jurymitglied Projektwettbewerb Sportzentrum Witikon, Zürich (Roger Weber)</p>',
				'<p>Jurymitglied Studienauftrag Sanierung und Erweiterung Gemeindehaus Oberengstringen (Roger Weber)</p>',
				'<p>Jurymitglied Projektwettbewerb Erweiterung Schulanlage Eselriet, Effretikon (Roger Weber)</p>',
				'<p>Begleitung Master-Thesis, Berner Fachhochschule (Roger Weber)</p>',
				'<p>Jurymitglied FNR Bundeswettbewerb HolzbauPlus 2022: Klimaschutz durch Innovation (Elise Pischetsrieder)</p>',
				'<p>BDA CALLS – Wirtschaftsideen für ein Postwachstum im Bauen (Elise Pischetsrieder)</p>',
				'<p>Ideenwettbewerb Re-Use Berlin (Elise Pischetsrieder)</p>',
			],
		];

		$sortOrder = 0;
		foreach ($data as $year => $entries) {
			$section = Section::create([
				'title' => (string) $year,
				'type' => 'jury',
				'sort_order' => $sortOrder++,
			]);

			$itemOrder = 0;
			foreach ($entries as $text) {
				$section->juries()->create([
					'text' => $text,
					'publish' => true,
					'sort_order' => $itemOrder++,
				]);
			}
		}
	}

	private function seedTalks(): void
	{
		$data = [
			'2025' => [
				'<p>Sind Architekt:innen nur die Petersilie am Fisch der Immobilienwirtschaft? Podcast-Festival Kontxtr, Basel (Elise Pischetsrieder)</p>',
				'<p>Ökobilanzierung in der Praxis, Vorstellung Leitfaden, Umweltministerium Hessen, Architekten- und Stadtplanerkammer Hessen, Wiesbaden (Elise Pischetsrieder)</p>',
				'<p>Zukunftsfähige Baukultur, Vortrag, Holzbau-Fachkongress am Bodensee im Rahmen der Holzbau Offensive Baden-Württemberg (Elise Pischetsrieder)</p>',
				'<p>Ressourcenwende in der Architektur – mit neuen Strategien planen, Archikon Stuttgart (Elise Pischetsrieder)</p>',
				'<p>Bauen mit Holz: Klimafreundlich, bezahlbar und zukunftsfähig, Diskussionsbeitrag, Holzbau Symposium BMWK (Elise Pischetsrieder)</p>',
				'<p>Baukulturinitiative Dachkult, Impulsbeitrag, in der Reihe RoofTop Talks (Elise Pischetsrieder)</p>',
				'<p>Schnittstelle Planen und Bauen neu denken, Moderation, AKB (Elise Pischetsrieder)</p>',
				'<p>Wege zum ökologischen Planen und Bauen in die Zukunft, Impulsbeitrag, Klimafrühstück Wien (Elise Pischetsrieder)</p>',
				'<p>Gastkritikerin im Masterkolloquium TU Berlin, Lehrstuhl für Bauökonomie Prof. Kristin Wellner, Dekanin der Fakultät VI Planen Bauen Umwelt (Elise Pischetsrieder)</p>',
				'<p>Holzbau und klimagerechtes bauen skalieren, Vorlesung, IU Darmstadt</p>',
			],
			'2024' => [
				'<p>Von der Neustadt aus Holz zum zementfreien Haus – wie die Lebenszyklusanalyse das Planen und Bauen verändert, Architect at Work, Frankfurt am Main (Elise Pischetsrieder)</p>',
				'<p>GWP-Kennwerte in der Praxis – Der BKI-Konstruktionsatlas als Planungswerkzeug, Webinar des BKI (Elise Pischetsrieder)</p>',
				'<p>KI im Bau und in der Bildungsinfrastruktur, SCHULBAU Messe Berlin (Elise Pischetsrieder)</p>',
				'<p>Ästhetik &amp; ökologisch nachhaltige Gebäudegestaltung – Konflikt oder positive kreative Herausforderung? Architekturgalerie AEDES für SenUMVK (Elise Pischetsrieder)</p>',
				'<p>Klimafestival Preisverleihung Architektur Award 2024, Moderation (Elise Pischetsrieder)</p>',
				'<p>Optionen für zirkuläres und klimagerechtes Bauen im Forschungsbau, Helmholtz Kompetenznetzwerk Klimagerecht Bauen (Elise Pischetsrieder)</p>',
				'<p>Holz – Die Währung CO2 im Wohnungsbau, ELEMENTE materialForum Berlin (Elise Pischetsrieder)</p>',
				'<p>Gestalt und Gemeinschaft, Grundlagen zukunftsfähiger Konstruktionen mit ganzheitlicher Materialwahl, Vorlesung, Studentischer Entwurfswettbewerb Joanes Preis (Elise Pischetsrieder, Roger Weber)</p>',
				'<p>Haltung zeigen, Bayerischer Fachtag AKB, München (Elise Pischetsrieder)</p>',
				'<p>Wohnprojektetag NRW 2024, Gelsenkirchen (Elise Pischetsrieder)</p>',
				'<p>Datenbank zirkulär, Workshop, Bundesstiftung Bauakademie (Elise Pischetsrieder)</p>',
				'<p>Führung Wohnungsbau Bahrfeldtstraße, Tag der Architektur (Elise Pischetsrieder)</p>',
				'<p>Digitale Instrumente: Zirkularität in der Ökobilanzierung, Vorstellung Leitfaden im Kammerforum zirkuläres Bauen der AKB (Elise Pischetsrieder)</p>',
				'<p>Sanierung einer alten Baumschule in Gransee, Gastkritik TU-Seminar, organisiert vom Fachgebiet Architects for Future Gastprofessorin Elisabeth Broermann und Adrian Nägel (Marie Heyer, Elise Pischetsrieder)</p>',
				'<p>Die Währung CO2e im Bauen: Ökobilanzierung im Entwurf, Veranstaltung des BDA Mittelhessen (Elise Pischetsrieder)</p>',
			],
		];

		$sortOrder = 0;
		foreach ($data as $year => $entries) {
			$section = Section::create([
				'title' => (string) $year,
				'type' => 'talk',
				'sort_order' => $sortOrder++,
			]);

			$itemOrder = 0;
			foreach ($entries as $text) {
				$section->talks()->create([
					'text' => $text,
					'publish' => true,
					'sort_order' => $itemOrder++,
				]);
			}
		}
	}
}
