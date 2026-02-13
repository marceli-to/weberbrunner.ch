<?php

use App\Models\Section;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
	public function up(): void
	{
		// Add section_id to all three tables
		Schema::table('awards', function (Blueprint $table) {
			$table->foreignId('section_id')->nullable()->after('uuid')->constrained()->nullOnDelete();
		});

		Schema::table('juries', function (Blueprint $table) {
			$table->foreignId('section_id')->nullable()->after('uuid')->constrained()->nullOnDelete();
		});

		Schema::table('talks', function (Blueprint $table) {
			$table->foreignId('section_id')->nullable()->after('uuid')->constrained()->nullOnDelete();
		});

		// Migrate existing award years into sections
		$awardYears = DB::table('awards')->whereNotNull('year')->distinct()->pluck('year')->sortDesc()->values();
		foreach ($awardYears as $index => $year) {
			$section = Section::create([
				'title' => (string) $year,
				'type' => 'award',
				'sort_order' => $index,
			]);
			DB::table('awards')->where('year', $year)->update(['section_id' => $section->id]);
		}

		// Migrate existing jury years into sections
		$juryYears = DB::table('juries')->whereNotNull('year')->distinct()->pluck('year')->sortDesc()->values();
		foreach ($juryYears as $index => $year) {
			$section = Section::create([
				'title' => (string) $year,
				'type' => 'jury',
				'sort_order' => $index,
			]);
			DB::table('juries')->where('year', $year)->update(['section_id' => $section->id]);
		}

		// Drop year columns
		Schema::table('awards', function (Blueprint $table) {
			$table->dropColumn('year');
		});

		Schema::table('juries', function (Blueprint $table) {
			$table->dropColumn('year');
		});
	}

	public function down(): void
	{
		Schema::table('awards', function (Blueprint $table) {
			$table->unsignedSmallInteger('year')->nullable()->after('description');
		});

		Schema::table('juries', function (Blueprint $table) {
			$table->unsignedSmallInteger('year')->nullable()->after('description');
		});

		// Migrate section titles back to year columns
		$awardSections = DB::table('sections')->where('type', 'award')->get();
		foreach ($awardSections as $section) {
			DB::table('awards')->where('section_id', $section->id)->update(['year' => (int) $section->title]);
		}

		$jurySections = DB::table('sections')->where('type', 'jury')->get();
		foreach ($jurySections as $section) {
			DB::table('juries')->where('section_id', $section->id)->update(['year' => (int) $section->title]);
		}

		Schema::table('awards', function (Blueprint $table) {
			$table->dropConstrainedForeignId('section_id');
		});

		Schema::table('juries', function (Blueprint $table) {
			$table->dropConstrainedForeignId('section_id');
		});

		Schema::table('talks', function (Blueprint $table) {
			$table->dropConstrainedForeignId('section_id');
		});
	}
};
