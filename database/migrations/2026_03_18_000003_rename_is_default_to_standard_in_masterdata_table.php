<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('masterdata', function (Blueprint $table) {
			$table->renameColumn('is_default', 'standard');
		});
	}

	public function down(): void
	{
		Schema::table('masterdata', function (Blueprint $table) {
			$table->renameColumn('standard', 'is_default');
		});
	}
};
