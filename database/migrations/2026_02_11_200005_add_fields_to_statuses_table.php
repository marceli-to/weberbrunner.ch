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
		Schema::table('statuses', function (Blueprint $table) {
			$table->uuid('uuid')->nullable()->after('id');
			$table->integer('sort_order')->default(0)->after('slug');
			$table->softDeletes();
		});

		DB::table('statuses')->whereNull('uuid')->orWhere('uuid', '')->get()->each(function ($row) {
			DB::table('statuses')->where('id', $row->id)->update(['uuid' => Str::uuid()]);
		});

		Schema::table('statuses', function (Blueprint $table) {
			$table->uuid('uuid')->unique()->change();
		});
	}

	public function down(): void
	{
		Schema::table('statuses', function (Blueprint $table) {
			$table->dropColumn(['uuid', 'sort_order']);
			$table->dropSoftDeletes();
		});
	}
};
