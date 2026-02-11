<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('projects', function (Blueprint $table) {
			$table->uuid('uuid')->nullable()->after('id');
			$table->integer('sort_order')->default(0)->after('publish');
			$table->foreignId('location_id')->nullable()->after('description')->constrained()->nullOnDelete();
			$table->softDeletes();
		});

		// Backfill UUIDs for existing records
		DB::table('projects')->whereNull('uuid')->orWhere('uuid', '')->get()->each(function ($row) {
			DB::table('projects')->where('id', $row->id)->update(['uuid' => Str::uuid()]);
		});

		Schema::table('projects', function (Blueprint $table) {
			$table->uuid('uuid')->unique()->change();
		});

		// Drop the old location enum column
		Schema::table('projects', function (Blueprint $table) {
			$table->dropColumn('location');
		});
	}

	public function down(): void
	{
		Schema::table('projects', function (Blueprint $table) {
			$table->dropForeign(['location_id']);
			$table->dropColumn(['uuid', 'sort_order', 'location_id']);
			$table->dropSoftDeletes();
			$table->enum('location', ['zurich', 'berlin'])->after('description');
		});
	}
};
