<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::rename('landings', 'page_texts');

		Schema::table('page_texts', function (Blueprint $table) {
			$table->string('page')->unique()->after('uuid');
			$table->string('title')->nullable()->after('page');
		});

		DB::table('page_texts')->whereNull('page')->update(['page' => 'landing']);
	}

	public function down(): void
	{
		Schema::table('page_texts', function (Blueprint $table) {
			$table->dropColumn(['page', 'title']);
		});

		Schema::rename('page_texts', 'landings');
	}
};
