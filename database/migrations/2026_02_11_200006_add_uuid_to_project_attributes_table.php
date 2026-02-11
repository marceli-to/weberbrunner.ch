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
		Schema::table('project_attributes', function (Blueprint $table) {
			$table->uuid('uuid')->nullable()->after('id');
		});

		DB::table('project_attributes')->whereNull('uuid')->orWhere('uuid', '')->get()->each(function ($row) {
			DB::table('project_attributes')->where('id', $row->id)->update(['uuid' => Str::uuid()]);
		});

		Schema::table('project_attributes', function (Blueprint $table) {
			$table->uuid('uuid')->unique()->change();
		});
	}

	public function down(): void
	{
		Schema::table('project_attributes', function (Blueprint $table) {
			$table->dropColumn('uuid');
		});
	}
};
