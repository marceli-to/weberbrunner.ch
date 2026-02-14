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
				'<p><strong>Auszeichnung «best architects 24»</strong> Sanierung, Um- und Anbau, Haus B4, Zürich</p>',
			],
			'2021' => [
				'<p><strong>Lobende Erwähnung – Hindernisfrei Architektur</strong> – Die Schweizer Fachstelle «40 Jahre Engagement für eine bessere Gesellschaft»</p>',
				'<p><strong>WIA – Ausstellung Women in Architecture</strong>, Berlin</p>',
			],
			'2020' => [
				'<p><strong>AW20 Architekturpreis Region Winterthur</strong> für Wohnüberbauung Hagmannareal, Winterthur</p>',
				'<p><strong>Auszeichnung «best architects 21»</strong> Loft Windisch</p>',
			],
			'2019' => [
				'<p><strong>Architekturpreis Kanton Zürich Auszeichnung 19</strong></p>',
			],
			'2018' => [
				'<p><strong>Nominierung ARC-Award 2018</strong> der Wohnüberbauung Hagmannareal, Winterthur</p>',
				'<p><strong>Auszeichnung Gold «best architects 19»</strong> für Wohnüberbauung Hagmannareal, Winterthur</p>',
			],
			'2015' => [
				'<p><strong>Auszeichnung «best architects 16»</strong> für Mehrfamilienhaus Im Amt, Gutenswil</p>',
				'<p><strong>Auszeichnung «best architects 16»</strong> für Sportzentrum Eselriet, Effretikon</p>',
			],
			'2008' => [
				'<p><strong>Auszeichnung «best architects 09»</strong> für Sporthalle Hardau, Zürich</p>',
				'<p><strong>Europäischer Spengler-Metall Architekturpreis</strong> für Sporthalle Hardau, Zürich</p>',
			],
			'2003' => [
				'<p><strong>Denkmalschutzpreis «Wohnen unter Dächern»</strong> für Wohnhaus Schaufelberger</p>',
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
				['text' => '<p><strong>«Sind Architekt:innen nur die Petersilie am Fisch der Immobilienwirtschaft?»</strong> Podcast-Festival Kontxtr, Basel (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Ökobilanzierung in der Praxis»</strong> Vorstellung Leitfaden, Umweltministerium Hessen, Architekten- und Stadtplanerkammer Hessen, Wiesbaden (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Zukunftsfähige Baukultur»</strong> Vortrag, Holzbau-Fachkongress am Bodensee im Rahmen der Holzbau Offensive Baden-Württemberg (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Ressourcenwende in der Architektur – mit neuen Strategien planen»</strong> Archikon Stuttgart (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Bauen mit Holz: Klimafreundlich, bezahlbar und zukunftsfähig»</strong> Diskussionsbeitrag, Holzbau Symposium BMWK (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Baukulturinitiative Dachkult»</strong> Impulsbeitrag, in der Reihe RoofTop Talks (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Schnittstelle Planen und Bauen neu denken»</strong> Moderation, AKB (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Wege zum ökologischen Planen und Bauen in die Zukunft»</strong> Impulsbeitrag, Klimafrühstück Wien (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Gastkritikerin im Masterkolloquium TU Berlin»</strong> Lehrstuhl für Bauökonomie Prof. Kristin Wellner, Dekanin der Fakultät VI Planen Bauen Umwelt (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Holzbau und klimagerechtes bauen skalieren»</strong> Vorlesung, IU Darmstadt</p>'],
			],
			'2024' => [
				['text' => '<p><strong>«Von der Neustadt aus Holz zum zementfreien Haus – wie die Lebenszyklusanalyse das Planen und Bauen verändert»</strong> Architect at Work, Frankfurt am Main (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«GWP-Kennwerte in der Praxis – Der BKI-Konstruktionsatlas als Planungswerkzeug»</strong> Webinar des BKI (Elise Pischetsrieder)</p>', 'link' => '#'],
				['text' => '<p><strong>«KI im Bau und in der Bildungsinfrastruktur»</strong> SCHULBAU Messe Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Ästhetik &amp; ökologisch nachhaltige Gebäudegestaltung – Konflikt oder positive kreative Herausforderung?»</strong> Architekturgalerie AEDES für SenUMVK (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Klimafestival Preisverleihung Architektur Award 2024»</strong> Moderation (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Optionen für zirkuläres und klimagerechtes Bauen im Forschungsbau»</strong> Helmholtz Kompetenznetzwerk Klimagerecht Bauen (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Holz – Die Währung CO2 im Wohnungsbau»</strong> ELEMENTE materialForum Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Gestalt und Gemeinschaft, Grundlagen zukunftsfähiger Konstruktionen mit ganzheitlicher Materialwahl»</strong> Vorlesung, Studentischer Entwurfswettbewerb Joanes Preis (Elise Pischetsrieder, Roger Weber)</p>'],
				['text' => '<p><strong>«Haltung zeigen»</strong> Bayerischer Fachtag AKB, München (Elise Pischetsrieder)</p>', 'link' => '#'],
				['text' => '<p><strong>«Wohnprojektetag NRW 2024»</strong> Gelsenkirchen (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Datenbank zirkulär»</strong> Workshop, Bundesstiftung Bauakademie (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Führung Wohnungsbau Bahrfeldtstraße»</strong> Tag der Architektur (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Digitale Instrumente: Zirkularität in der Ökobilanzierung»</strong> Vorstellung Leitfaden im Kammerforum zirkuläres Bauen der AKB (Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Sanierung einer alten Baumschule in Gransee»</strong> Gastkritik TU-Seminar, organisiert vom Fachgebiet Architects for Future Gastprofessorin Elisabeth Broermann und Adrian Nägel (Marie Heyer, Elise Pischetsrieder)</p>'],
				['text' => '<p><strong>«Die Währung CO2e im Bauen: Ökobilanzierung im Entwurf»</strong> Veranstaltung des BDA Mittelhessen (Elise Pischetsrieder)</p>'],
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
			foreach ($entries as $entry) {
				$section->talks()->create([
					'text' => $entry['text'],
					'link' => $entry['link'] ?? null,
					'publish' => true,
					'sort_order' => $itemOrder++,
				]);
			}
		}
	}
}
