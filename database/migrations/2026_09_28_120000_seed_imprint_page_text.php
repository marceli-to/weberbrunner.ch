<?php

use App\Models\PageText;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
	public function up(): void
	{
		DB::transaction(function () {
			$page = PageText::firstOrCreate(['page' => 'impressum'], [
				'title' => null,
				'text' => null,
			]);

			if ($page->blocks()->exists()) {
				return;
			}

			foreach ($this->sections() as $title => $content) {
				$page->blocks()->create([
					'type' => 'text',
					'title' => $title,
					'content' => $content,
				]);
			}
		});
	}

	public function down(): void
	{
		DB::transaction(function () {
			$page = PageText::where('page', 'impressum')->first();

			if (! $page) {
				return;
			}

			$page->blocks()->delete();
			$page->delete();
		});
	}

	private function sections(): array
	{
		return [
			'Impressum' => implode('', [
				'<h3>Fotografie:</h3>',
				'<p>Andrea Helbling, Zürich / Beat Bühler, Zürich / Georg Aerni, Zürich / Hannes Henz, Zürich / Volker Schopp, Zürich / Nils Koenning, Berlin / weberbrunner architekten ag</p>',
				'<h3>Modellbau:</h3>',
				'<p>Gruber.Forster, Zürich</p>',
				'<h3>Visualisierung:</h3>',
				'<p>YOS, Zürich / Raumgleiter, Zürich / Architron, Zürich / Maaars, Zürich / Atelier Brunecky, Zürich / Carsten Pesch, Berlin / Dalia Liksaite, Berlin</p>',
				'<h3>Portraits:</h3>',
				'<p>Michaela Medea, <a href="https://michaelamedea.ch" target="_blank">michaelamedea.ch</a></p>',
				'<h3>Programmierung:</h3>',
				'<p>Marcel Stadelmann, <a href="https://marceli.to" target="_blank">marceli.to</a></p>',
				'<h3>Design:</h3>',
				'<p>WBG AG, Visuelle Kommunikation, <a href="https://wbg.ch" target="_blank">wbg.ch</a></p>',
			]),
			'Impressum Zürich' => implode('', [
				'<h3>Rechtsform:</h3>',
				'<p>weberbrunner architektur ag</p>',
				'<h3>Gründung:</h3>',
				'<p>1999, Aktiengesellschaft seit 2008</p>',
				'<h3>Inhaber:</h3>',
				'<p>Boris Brunner, dipl. Arch. FH / SIA / BSA<br>Roger Weber, dipl. Arch. FH / SIA / BSA</p>',
				'<h3>Bankverbindung:</h3>',
				'<p>MWST-Nr. CHE-105.007.317 MWST<br>Bank Zürcher Kantonalbank<br>Postfach, 8010 Zürich<br>IBAN CH93 0070 0114 8046 4125 2<br>BIC ZKBKCHZZ80A</p>',
			]),
			'Impressum Berlin' => implode('', [
				'<h3>Rechtsform:</h3>',
				'<p>weberbrunner pischetsrieder Gesellschaft von Architekten mbH</p>',
				'<h3>Eintragung:</h3>',
				'<p>Architektenkammer Berlin 2016</p>',
				'<h3>Amtsgericht:</h3>',
				'<p>Berlin HRB 177184 B</p>',
				'<h3>Steuernummer:</h3>',
				'<p>30/581/50039<br>UStID: DE306243302</p>',
				'<h3>Gesellschafter:</h3>',
				'<p>Boris Brunner, dipl. Arch. FH / SIA / BSA<br>Roger Weber, dipl. Arch. FH / SIA / BSA<br>Elise Pischetsrieder, Dipl.-Ing. Architektin AKB / SIA</p>',
				'<h3>Geschäftsführerin:</h3>',
				'<p>Elise Pischetsrieder, Dipl.-Ing. Architektin AKB / SIA</p>',
				'<h3>Bankverbindung:</h3>',
				'<p>IBAN DE60 1005 0000 0190 5070 71<br>Bank Berliner Sparkasse, 10889 Berlin<br>BIC BELADEBEXXX</p>',
			]),
		];
	}
};
