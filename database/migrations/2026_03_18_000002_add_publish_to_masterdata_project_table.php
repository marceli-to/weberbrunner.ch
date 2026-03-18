<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('masterdata_project', function (Blueprint $table) {
			$table->boolean('publish')->default(true)->after('sort_order');
		});
	}

	public function down(): void
	{
		Schema::table('masterdata_project', function (Blueprint $table) {
			$table->dropColumn('publish');
		});
	}
};
