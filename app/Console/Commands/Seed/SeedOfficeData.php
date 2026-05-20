<?php

namespace App\Console\Commands\Seed;

use App\Models\Location;
use App\Models\Section;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Storage;

class SeedOfficeData extends Command
{
	use ConfirmableTrait;

	protected $signature = 'app:seed-office-data {--force : Force the operation to run in production}';

	protected $description = 'Seed awards, jury entries, talks and contacts';

	public function handle(): void
	{
		if (!$this->confirmToProceed()) {
			return;
		}

		$this->seedAwards();
		$this->seedJuries();
		$this->seedTalks();
		$this->seedContacts();

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
				'<p>Mitglied Gestaltungsbeirat Halle / Saale (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Bundesministerium, Berlin (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied DGNB-Platin Kommission für Gestaltungsqualität (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Deutscher Nachhaltigkeitspreis (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Schumacher Quartier Berlin-TXL (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Projektwettbewerb Schulschwimmanlage Kesselhaus Letten (Eva Geering)</p>',
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
				'<p>Jurymitglied FNR Bundeswettbewerb HolzbauPlus 2022: Klimaschutz durch Innovation (Elise Pischetsrieder)</p>',
				'<p>BDA CALLS – Wirtschaftsideen für ein Postwachstum im Bauen (Elise Pischetsrieder)</p>',
				'<p>Ideenwettbewerb Re-Use Berlin (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Projektwettbewerb Sportzentrum Witikon, Zürich (Roger Weber)</p>',
				'<p>Mitglied Architekturbeirat AKB – Tag der Architektur Berlin (Elise Pischetsrieder)</p>',
				'<p>Jurymitglied Studienauftrag Sanierung und Erweiterung Gemeindehaus Oberengstringen (Roger Weber)</p>',
				'<p>Jurymitglied Projektwettbewerb Erweiterung Schulanlage Eselriet, Effretikon (Roger Weber)</p>',
				'<p>Begleitung Master-Thesis, Berner Fachhochschule (Roger Weber)</p>',
			],
			'2021' => [
				'<p>Jurymitgliedschaft Holzbauatlas Berlin / Brandenburg Visionen in Zusammenarbeit mit Natural Building Lab, TU Berlin und SenUVK (Elise Pischetsrieder)</p>',
			],
			'2020' => [
				'<p>Jurymitglied ZZM Hottingen, Zürich (Boris Brunner)</p>',
			],
			'2019' => [
				'<p>Jurymitglied Erneuerung KaWeDe, Bern (Roger Weber)</p>',
				'<p>Jurymitglied Kindergarten Rosswinkel, Effretikon (Roger Weber)</p>',
				'<p>Jurymitglied Ersatzneubau Mehrzweckgebäude, Aesch ZH (Boris Brunner)</p>',
			],
			'2018' => [
				'<p>Jurymitglied Schulerweiterung BoTa, Dürnten (Roger Weber)</p>',
			],
			'2017' => [
				'<p>Jurymitglied Doppelsporthalle Wehntal, Niederweningen (Roger Weber)</p>',
				'<p>Jurymitglied Erweiterung Schulanlage Hofacker, Zürich (Boris Brunner)</p>',
			],
			'2016' => [
				'<p>Jurymitglied Planerwahlverfahren ZHAW, Winterthur (Boris Brunner)</p>',
				'<p>Gastkritik Masterstudiengang bei Ursula Stücheli und Beat Mathys Berner Fachhochschule, Burgdorf (Roger Weber)</p>',
			],
			'2015' => [
				'<p>Jurymitglied Erweiterung Sportzentrum Kerenzerberg, Filzbach (Boris Brunner)</p>',
				'<p>Gastkritik Hochschule für Technik Zürich HSZ-T (Boris Brunner)</p>',
			],
			'2014' => [
				'<p>Jurymitglied Inselspital, Universitätsspital Bern, Neubau für Organzentren (Boris Brunner)</p>',
				'<p>Jurymitglied Schulanlage Hofacker, Zürich (Boris Brunner)</p>',
			],
			'2013' => [
				'<p>Wettbewerbsbegleitung und Jury Schulanlage Hagen, Illnau (Roger Weber)</p>',
			],
			'2012' => [
				'<p>Jurymitglied Sportzentrum Heuried, Zürich (Roger Weber)</p>',
			],
			'2010' => [
				'<p>Jurymitglied Testplanung Sulzerareal Werk 1, Winterthur (Boris Brunner)</p>',
			],
			'2009' => [
				'<p>Gastkritik Lehrstuhl Christian Kerez ETH, Zürich (Roger Weber)</p>',
			],
			'2007' => [
				'<p>Gastkritik im Rahmen des Joint Master of Architecture. HSB, Burgdorf (Boris Brunner)</p>',
			],
			'2005' => [
				'<p>Gastkritik EPF, Lausanne (Roger Weber, Boris Brunner)</p>',
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
			'2026' => [
				['text' => '<p>Seminarmodul «LCA und Kreislaufwirtschaft», AKB (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vortrag und Gespräch «Holzbauweise als Schlüssel zu Klimaschutz – Ökobilanz und Kosten im Vergleich», Landesenergieagentur Hessen (Elise Pischetsrieder)</p>'],
				['text' => '<p>Seminar «Ökobilanzierung – voll konkret», BDA Hessen (Elise Pischetsrieder)</p>'],
				['text' => '<p>Impulsvortrag «Denken statt Dämmen», BDA Hessen (Elise Pischetsrieder)</p>'],
				['text' => '<p>Impuls zum remotebasierten Zusammenarbeiten, Swissbau (Jonas Korten)</p>'],
			],
			'2025' => [
				['text' => '<p>Impulsvortrag «Zirkuläres Bauen und LCA im Bundesbau», Kongress der BImA (Elise Pischetsrieder)</p>'],
				['text' => '<p>Preisverleihung Joanes Preis «Was tut der Mensch, wenn er wohnt» für die Berliner Baugenossenschaft bbg (Elise Pischetsrieder)</p>'],
				['text' => '<p>Architektinnengespräch «Zusammenarbeitskultur» (Elise Pischetsrieder)</p>'],
				['text' => '<p>Ökobilanzierung «Typenhaus STADT UND LAND» (Elise Pischetsrieder)</p>'],
				['text' => '<p>«Zirkuläres Bauen – Erfahrungen mit LCA &amp; QNG», Effizienztagung klimaneutral Bauen + Modernisieren Hannover (Elise Pischetsrieder)</p>'],
				['text' => '<p>Podium Parlamentarischer Abend / Holzbau Deutschland / Bauhaus Erde (Elise Pischetsrieder)</p>'],
				['text' => '<p>Podiumsdiskussion «Werke von Architektinnen», Jung aber Denkmal, Urania Berlin, Veranstaltung der Architektenkammer Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p>Fachdialog Urbaner Holzbau «Ressourcen schonen durch Holzbau», Veranstalterin: SenUVK (Elise Pischetsrieder)</p>'],
				['text' => '<p>Preisverleihung Berliner Holzbaupreis 2025, Deutscher Holzbau Kongress (Elise Pischetsrieder)</p>'],
				['text' => '<p>Podcast-Festival Kontxtr «Sind Architekt:innen nur die Petersilie am Fisch der Immobilienwirtschaft?», Basel (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vorstellung Leitfaden «Ökobilanzierung in der Praxis», Umweltministerium Hessen, Architekten- und Stadtplanerkammer Hessen, Wiesbaden (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vortrag «Zukunftsfähige Baukultur», Holzbau-Fachkongress am Bodensee im Rahmen der Holzbau Offensive Baden-Württemberg (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vortrag «Ressourcenwende in der Architektur – mit neuen Strategien planen», Archikon Stuttgart (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vorlesung «Zusammenarbeit in der Fassadenplanung», ZHAW Departement Architektur, Gestaltung und Bauingenieurwesen (Eva Geering)</p>'],
				['text' => '<p>Diskussionsbeitrag «Bauen mit Holz: Klimafreundlich, bezahlbar und zukunftsfähig», Holzbau Symposium BMWK (Elise Pischetsrieder)</p>'],
				['text' => '<p>Impulsbeitrag «Baukulturinitiative Dachkult», in der Reihe RoofTop Talks (Elise Pischetsrieder)</p>'],
				['text' => '<p>Moderation «Schnittstelle Planen und Bauen neu denken», AKB (Elise Pischetsrieder)</p>'],
				['text' => '<p>Impulsbeitrag «Wege zum ökologischen Planen und Bauen in die Zukunft», Klimafrühstück Wien (Elise Pischetsrieder)</p>'],
				['text' => '<p>Gastkritikerin im Masterkolloquium TU Berlin, Lehrstuhl für Bauökonomie Prof. Kristin Wellner, Dekanin der Fakultät VI Planen Bauen Umwelt (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vorlesung «Holzbau und klimagerechtes bauen skalieren», IU Darmstadt</p>'],
			],
			'2024' => [
				['text' => '<p>«Von der Neustadt aus Holz zum zementfreien Haus – wie die Lebenszyklusanalyse das Planen und Bauen verändert» Architect at Work, Frankfurt am Main (Elise Pischetsrieder)</p>'],
				['text' => '<p>«GWP-Kennwerte in der Praxis – Der BKI-Konstruktionsatlas als Planungswerkzeug» Webinar des BKI (Elise Pischetsrieder) <a href="https://bki-files.de/videos/webinare/KA2_Oekobilanzierung_in_der_Praxis_CO2optimierte_Planung.mp4">zum Video</a></p>'],
				['text' => '<p>«KI im Bau und in der Bildungsinfrastruktur», SCHULBAU Messe Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p>«Ästhetik &amp; ökologisch nachhaltige Gebäudegestaltung – Konflikt oder positive kreative Herausforderung?» Architekturgalerie AEDES für SenUMVK (Elise Pischetsrieder)</p>'],
				['text' => '<p>Moderation Klimafestival Preisverleihung Architektur Award 2024 (Elise Pischetsrieder)</p>'],
				['text' => '<p>«Optionen für zirkuläres und klimagerechtes Bauen im Forschungsbau» Helmholtz Kompetenznetzwerk Klimagerecht Bauen (Elise Pischetsrieder)</p>'],
				['text' => '<p>«Holz – Die Währung CO2 im Wohnungsbau» ELEMENTE materialForum Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vorlesung «Gestalt und Gemeinschaft, Grundlagen zukunftsfähiger Konstruktionen mit ganzheitlicher Materialwahl», Studentischer Entwurfswettbewerb Joanes Preis (Elise Pischetsrieder, Roger Weber)</p>'],
				['text' => '<p>«Haltung zeigen» Bayerischer Fachtag AKB, München (Elise Pischetsrieder) <a href="https://byak.cloud.panopto.eu/Panopto/Pages/Viewer.aspx?id=47f2d70e-fccf-4a37-af31-b21f008f80f7">zum Vortrag</a></p>'],
				['text' => '<p>Wohnprojektetag NRW 2024, Gelsenkirchen (Elise Pischetsrieder)</p>'],
				['text' => '<p>Workshop «Datenbank zirkulär», Bundesstiftung Bauakademie (Elise Pischetsrieder)</p>'],
				['text' => '<p>Führung Wohnungsbau Bahrfeldtstraße, Tag der Architektur (Elise Pischetsrieder)</p>'],
				['text' => '<p>«Digitale Instrumente: Zirkularität in der Ökobilanzierung», Vorstellung Leitfaden im Kammerforum zirkuläres Bauen der AKB (Elise Pischetsrieder)</p>'],
				['text' => '<p>Gastkritik TU-Seminar «Sanierung einer alten Baumschule in Gransee», organisiert vom Fachgebiet Architects for Future Gastprofessorin Elisabeth Broermann und Adrian Nägel (Marie Heyer, Elise Pischetsrieder)</p>'],
				['text' => '<p>«Die Währung CO2e im Bauen: Ökobilanzierung im Entwurf», Veranstaltung des BDA Mittelhessen (Elise Pischetsrieder)</p>'],
				['text' => '<p>Symposium München «Klimaneutrale Bauwerke als Ziel bei öffentlichen Auftraggeberschaften» (Elise Pischetsrieder)</p>'],
				['text' => '<p>Fortbildung &amp; Fachaustausch Berliner Schulbauoffensive: «Ökologisch-ökonomische Zielbetrachtung – Bestandsertüchtigung versus Abriss/Neubau» (Miriam Attallah)</p>'],
				['text' => '<p>Ausstellungsgespräch «Wood\'s Up – Holz für die Bauwende: Weiterbauen – Anbau – Umbau», Architekturforum Berlin (Elise Pischetsrieder) <a href="https://vimeo.com/973062009?share=copy">Zum Gespräch</a></p>'],
				['text' => '<p>«Energie-Einsparung im Bestand – dynamische LCA zur Reduktion von THG-Emissionen im Lebenszyklus» im Expertennetzwerk KoBI (Elise Pischetsrieder)</p>'],
				['text' => '<p>«Schinkel und die Nachhaltigkeit», Schinkel-Lectures, Friedrichswerdersche Kirche Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p>Anwendung der THG-Währung für öffentliche Bauherrnschaften, für Hochbauamt Ingolstadt (Elise Pischetsrieder)</p>'],
				['text' => '<p>«CO2-Bepreisung am Beispiel der Salvador-Allende-Straße», Bateg GmbH (Elise Pischetsrieder)</p>'],
				['text' => '<p>«LCA in der Praxisanwendung am Bsp. einer Fassadensanierung», Klimafrühstück Wien (Miriam Attallah)</p>'],
				['text' => '<p>«Einführung in Ökobilanzierung von Gebäuden», Swissbau (Miriam Attallah)</p>'],
			],
			'2023' => [
				['text' => '<p>«Die Währung CO2e als neue Einheit im Bauen. Auswirkungen auf Planen und Bauen in der Phase 0», Expertennetzwerk KoBI – Klimaoptimierung Bau und Infrastruktur bei öffentlichen AG (Elise Pischetsrieder, Mitglied wissenschaftlicher Beirat KoBI)</p>'],
				['text' => '<p>«Klima- und ressourcengerechtes Entwerfen im Wohnungsbau», Studiengang Green Building, Campus Wien (Elise Pischetsrieder)</p>'],
				['text' => '<p>Internationales Holzbauforum Innsbruck «LCA eines Holzbauquartiers in Berlin» (Elise Pischetsrieder)</p>'],
				['text' => '<p>«Aus der Praxis: Hagmannareal, Neustadt aus Holz, Holzbauquartier in Berlin», pro:Holz Austria (Boris Brunner)</p>'],
				['text' => '<p>«Die Bilanz muss stimmen. Anwendung und Werkzeuge der Ökobilanzierung als Entwurfsstrategie», BDA Hessen (Elise Pischetsrieder)</p>'],
				['text' => '<p>BKI-Autorinlesung zum «Konstruktionsatlas Ökobilanzierung»</p>'],
				['text' => '<p>Fachkongress Holzbau Hessen «Lebenszyklusanalyse und Holzbau» (Elise Pischetsrieder)</p>'],
				['text' => '<p>Podiumsdiskussion «Bauakademie. Jetzt! Wenn gestern die Zukunft sein soll, was ist dann morgen …?» Institut für Architektur, TU Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p>«Klimaschonendes Bauen – LCA-Strategien und LCA-Bauteilaufbauten», BNB-Netzwerktreffen der Landesbaudirektion Bayern (Elise Pischetsrieder)</p>'],
				['text' => '<p>«LCA im Wohnungsbau» Stuttgarter Immobilientalk (Elise Pischetsrieder)</p>'],
				['text' => '<p>«Ein Wohngruppenprojekt revisited beim Tag der Architektur. Zehn Jahre nach Dennewitz Eins. Wohin läuft der Wohnungsbau?», Auftakt-Podiumsgespräch Tag der Architektur Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p>Berliner Energietage, Podium als stellvertretendes Mitglied des Klimaschutzrat, Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p>LCA-Strategien für klimagerechtes Planen und Bauen, BAU München (Elise Pischetsrieder)</p>'],
				['text' => '<p>Biomass &amp; Timber Dialogue at Henning Larsen\'s «Changing our Footprint» Aedes Exhibition, Berlin (Diskussion Elise Pischetsrieder)</p>'],
				['text' => '<p>Resilient Communities, UIA World Congress of Architects, Nordische Botschaften (Elise Pischetsrieder)</p>'],
				['text' => '<p>Ökobilanzierung in Holz- &amp; Holzhybridkonstruktionen, AG Stadtentwicklung Die Grünen (Elise Pischetsrieder)</p>'],
				['text' => '<p>BDA calls – Bauen mit Stroh, Deutsches Architekturzentrum (Moderation: Elise Pischetsrieder)</p>'],
				['text' => '<p>WBM – Holzbau: Theorie und Praxisanwendung (Elise Pischetsrieder)</p>'],
			],
			'2022' => [
				['text' => '<p>Deutsches Architekturmuseum Frankfurt «Zirkuläre Architektur» (Elise Pischetsrieder &amp; Roger Weber)</p>'],
				['text' => '<p>Klimakompetenz Camp (Miriam Attallah)</p>'],
				['text' => '<p>Runder Tisch beim Buildings Performance Institute Europe (Eva-Maria Friedel)</p>'],
				['text' => '<p>Fachforum «Lebenszyklusbetrachtung von Gebäuden in Politik und Praxis», Deutsche Umwelthilfe (Elise Pischetsrieder)</p>'],
				['text' => '<p>SIA-Werkstattgespräch «Lokstadt Winterthur: Elefant» (Roger Weber)</p>'],
				['text' => '<p>«Bauwende_konkret» Weiterbildungsmodul der Architektenkammer Berlin (Eva-Maria Friedel)</p>'],
				['text' => '<p>Impulsvortrag «Kriterien zur Kreislauffähigkeit von Baustoffen», Energiewende-Kongress dena (Eva-Maria Friedel)</p>'],
				['text' => '<p>Holzbautag Bateg «Holzhybridbauweise und zur CO2 Bilanz» (Elise Pischetsrieder)</p>'],
				['text' => '<p>Think Tank Bauakademie «Ökobilanzierung verschiedener Fassadenkonstruktionen – Die Bedeutung der Konstruktion für Klima- und Ressourcenschutz» (Elise Pischetsrieder)</p>'],
				['text' => '<p>Einführung Workshop-Reihe «Bauwende_konkret» der Architektenkammer Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p>Holzbaulabor Expertenkreis der Holzbau-Initiative Potsdam (Elise Pischetsrieder)</p>'],
				['text' => '<p>Timber Thinktank bei Sauerbruch Hutton (Elise Pischetsrieder)</p>'],
				['text' => '<p>BDA Stadtsalon «Einfach Bauen – Ein zementfreies Haus» (Elise Pischetsrieder)</p>'],
				['text' => '<p>Fachgespräch «Inspiration Holz – Zukunft bauen. Jetzt!» Zentrum Architektur Zürich (Boris Brunner)</p>'],
				['text' => '<p>Deutscher Holzbau Kongress «Städtisches Bauen im grossen Stil – Das Hagmann Areal» (Elise Pischetsrieder)</p>'],
				['text' => '<p>Paradigmenwechsel im Planen und Bauen, Wüest &amp; Partner (Roger Weber)</p>'],
				['text' => '<p>Nachhaltigkeitskongress BMSWB und BBSR «Zukunft denken, nachhaltig bauen» – Vortrag zum Nachhaltigen Wohnungsbau in der Praxis (Elise Pischetsrieder)</p>'],
				['text' => '<p>Impulsvortrag Maßnahmen Klimagerechtes und zirkuläres Bauen, Bauwende Festival A4F (Eva-Maria Friedel)</p>'],
				['text' => '<p>Impulsvortrag David Chipperfield Architects – Ökobilanzierung von Gebäuden und das 1.5°-Ziel: Welche Rolle spielt urbaner Holzbau und durch welche Konstruktionen lässt sich der ökologische Fußabdruck verringern? (Elise Pischetsrieder)</p>'],
				['text' => '<p>Schulung zum zirkulären Bauen für bauende Behörden in Berlin, im Auftrag der SenUMVK (Eva-Maria Friedel)</p>'],
				['text' => '<p>7. Fachdialog Urbaner Holzbau – Maßnahmenkatalog klimagerechtes und zirkuläres Bauen (Elise Pischetsrieder)</p>'],
				['text' => '<p>Den ganzen Lebenszyklus von Gebäuden in den Blick nehmen – eine Schlüsselfrage für den Klimaschutz, Veranstalterin Deutsche Umwelthilfe (Elise Pischetsrieder)</p>'],
				['text' => '<p>Werkstattgespräch Ökobilanzierung und Holzbaukultur mit Blocher Partners (Elise Pischetsrieder)</p>'],
				['text' => '<p>Holz baut Netzwerke, Landesbeirat Holz Berlin/Brandenburg – Urbaner Holzbau (Elise Pischetsrieder)</p>'],
				['text' => '<p>Informations- und Kompetenzzentrum für zukunftsgerechtes Bauen – Nachhaltiges und zirkuläres Bauen in Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p>Fachgespräch Ökobilanzierung DHV (Elise Pischetsrieder)</p>'],
			],
			'2021' => [
				['text' => '<p>Zukunftstag Genossenschaft Möckernkiez – Gemeinschaft Bauen (Elise Pischetsrieder)</p>'],
				['text' => '<p>ANCB – Building Communities, who do we want to share with? (Elise Pischetsrieder)</p>'],
				['text' => '<p>Fachgespräch Nachhaltigkeit im öffentlichen Bau: «Das Schulhaus von morgen» House of Switzerland, Stuttgart (Roger Weber)</p>'],
				['text' => '<p>Vortrag 5. Österreichische Fachkonferenz für Modulbau (Boris Brunner)</p>'],
				['text' => '<p>Vortrag + Fachdialog Kreislaufwirtschaft und Dämmstoffe (Eva-Maria Friedel)</p>'],
				['text' => '<p>Vortrag und Podiumsdiskussion Leichte vs. Schwere Fassade, ZHAW School of Engineering (Volker Schopp)</p>'],
				['text' => '<p>Fachgespräch Bauwende_konkret, Architektenkammer Berlin (Boris Brunner)</p>'],
				['text' => '<p>Vortrag und Expertengespräch Offene Rennbahn Oerlikon bei der Wissenschaftlich-Technischen Arbeitsgemeinschaft WTA (Volker Schopp)</p>'],
				['text' => '<p>Fachgespräch Ökobilanzierung, Deutscher Holzwirtschaftsrat (Elise Pischetsrieder, Eva-Maria Friedel)</p>'],
				['text' => '<p>Vortrag Massnahmen und Entscheidungshilfe wie die Bauwende im Wohnungsbau gelingt (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vortrag Hagmann Areal – Architektur, Klima, Holzbau, Holzbautag Biel (Boris Brunner)</p>'],
				['text' => '<p>Fachgespräch Women in Architecture – Nachwachsende Häuser zusammen mit Susanne Scharabi, Minka Kersten, Susanne Sturm (Elise Pischetsrieder)</p>'],
				['text' => '<p>Fachdialog Urban Tech Summit: Holzbau-Know How in Berlin (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vortrag Kriterien für nachhaltigen Wohnungsbau in Berlin, Fachdialog Urbaner Holzbau (Elise Pischetsrieder)</p>'],
				['text' => '<p>Keynote Timber tales: Timber construction returns to the city, by wood innovators (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vortrag Holzgeschichten – 3 Thesen zum urbanen Holzbau, Dessauer Gespräche (Roger Weber)</p>'],
			],
			'2020' => [
				['text' => '<p>Digitale Fachtagung 1. Expertenforum «Schumacher Quartier im Dialog» 12.11.2020 (Elise Pischetsrieder)</p>'],
				['text' => '<p>Studio Talk und Vortrag «Lohnt sich Holzbau im urbanen Kontext? – Von der Rückkehr der Holzbauweise im städtischen Wohnungsbau» im Rahmen des Innovationsprogramms Zukunft Bau des BMI und BBSR, Bautec 20, Berlin 19.02.2020 (Elise Pischetsrieder)</p>'],
				['text' => '<p>Fachgespräch «Ökumenisches Zentrum Luther-Kirche, Bülowstraße Berlin», Berlin 16.01.2020 (Elise Pischetsrieder)</p>'],
				['text' => '<p>Vortrag «Nachhaltig Bauen? Holz ist das Material der Wahl!» SWISSBAU, Basel 14.01.2020 (Boris Brunner)</p>'],
			],
			'2019' => [
				['text' => '<p>Holzgeschichten, Ausstellung Architekturforum Aedes, Berlin</p>'],
				['text' => '<p>Vortrag «Analoge Techniken für digitale Prozesse» 14.11.2019, Wien (Boris Brunner)</p>'],
				['text' => '<p>Bauten und Projekte, Architekturforum Langenthal (Roger Weber)</p>'],
				['text' => '<p>Zentrumsentwicklung wohin? Vortrag und ZBV Podiumsdiskussion, Zürich (Roger Weber)</p>'],
			],
			'2018' => [
				['text' => '<p>Vortrag «Lignum sue&amp;til, 09.08.2018», Lignum Holzwirtschaft Schweiz (Boris Brunner)</p>'],
				['text' => '<p>Largest residential construction in timber, Baukongress, Wien (Boris Brunner)</p>'],
			],
			'2017' => [
				['text' => '<p>Vortrag «Das grösste Wohnhaus aus Holz der Schweiz», Lignum Holzwirtschaft Schweiz 24.11.2017</p>'],
			],
			'2016' => [
				['text' => '<p>Vortrag «Station Langenthal» Berner Fachhochschule, Burgdorf (Roger Weber)</p>'],
				['text' => '<p>«Baustart für grösstes Holzbauwohnprojekt der Schweiz», Lignum Holzwirtschaft Schweiz 11.04.2016</p>'],
			],
			'2015' => [
				['text' => '<p>Vortrag Wohnüberbauung sue &amp; til, ETH Wahlfach Meisterkurs Holzbau (Boris Brunner)</p>'],
				['text' => '<p>Vortrag Sportbauten, Hochschule für Technik Zürich HSZ-T (Boris Brunner)</p>'],
				['text' => '<p>«Grösstes Holzbauprojekt der Schweiz startklar» 06.07.2015</p>'],
			],
			'2010' => [
				['text' => '<p>Vortrag «Ein Haus von…», Fachhochschule beider Basel (Boris Brunner)</p>'],
				['text' => '<p>Vortrag und Diskussion «Bauen wir eine neue Stadt – Plädoyer für eine Stadt im Glatttal», Architekturforum Zürich (Architektengruppe Krokodil)</p>'],
			],
			'2008' => [
				['text' => '<p>Vortrag «Junge Schweizer Architekten und Architektinnen» Architekturforum Zürich (Roger Weber, Boris Brunner)</p>'],
			],
			'2007' => [
				['text' => '<p>Vortrag Sportbauten Bauhaus-Universität, Weimar (Roger Weber, Boris Brunner)</p>'],
				['text' => '<p>Vortrag Zentrumsentwicklung Schlieren Zürcher Studiengesellschaft für Bau- und Verkehrsfragen, Zürich (Roger Weber)</p>'],
			],
			'2006' => [
				['text' => '<p>Vortrag «Die fünfte Fassade oder ein Garten als Intarsie» Fachhochschule beider Basel (Roger Weber)</p>'],
				['text' => '<p>Vortrag Kehrichtverwertungsanlage Bern HTA, Luzern (Boris Brunner)</p>'],
			],
			'2004' => [
				['text' => '<p>Vortrag «Zwei Bäder» Annual04, FH Münster (Roger Weber)</p>'],
				['text' => '<p>Vortrag Sporthalle Hardau, Hochschule für Technik und Architektur, Zürich (Roger Weber)</p>'],
			],
			'2003' => [
				['text' => '<p>Vortrag «Bauten und Projekte» :mlzd, Biel (Roger Weber)</p>'],
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
					'publish' => true,
					'sort_order' => $itemOrder++,
				]);
			}
		}
	}

	private function seedContacts(): void
	{
		$data = [
			'zuerich' => [
				'company_name' => 'weberbrunner architektur ag',
				'address' => 'Binzstrasse 23, 8045 Zürich',
				'phone' => '+41 44 405 20 80',
				'email' => 'info@weberbrunner.ch',
				'maps_url' => 'https://maps.google.ch',
				'image' => 'images/dummy-location-zuerich.jpg',
			],
			'berlin' => [
				'company_name' => "weberbrunner pischetsrieder architektur\nGesellschaft von Architekten mbH",
				'address' => 'Zehdenicker Straße 21, 10119 Berlin',
				'phone' => '+49 30 92 10 13 330',
				'email' => 'info@wbp-architektur.de',
				'maps_url' => null,
				'image' => 'images/dummy-location-berlin.jpg',
			],
		];

		$disk = Storage::disk('public');
		$sortOrder = 0;

		foreach ($data as $locationSlug => $contactData) {
			$location = Location::where('slug', $locationSlug)->first();
			if (!$location) {
				$this->warn("Location '{$locationSlug}' not found, skipping.");
				continue;
			}

			$imagePath = $contactData['image'];
			unset($contactData['image']);

			$contact = $location->contacts()->create([
				...$contactData,
				'publish' => true,
				'sort_order' => $sortOrder++,
			]);

			if ($disk->exists($imagePath)) {
				$fullPath = $disk->path($imagePath);
				$dimensions = @getimagesize($fullPath);

				$contact->media()->create([
					'file' => $imagePath,
					'original_name' => basename($imagePath),
					'mime_type' => $dimensions['mime'] ?? 'image/jpeg',
					'size' => $disk->size($imagePath),
					'width' => $dimensions[0] ?? null,
					'height' => $dimensions[1] ?? null,
					'is_teaser' => false,
					'publish' => true,
					'sort_order' => 0,
				]);
			}
		}
	}
}
