<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('masterdata_project', function (Blueprint $table) {
			$table->id();
			$table->foreignId('masterdata_id')->constrained('masterdata')->cascadeOnDelete();
			$table->foreignId('project_id')->constrained()->cascadeOnDelete();
			$table->text('value')->nullable();
			$table->timestamps();

			$table->unique(['masterdata_id', 'project_id']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('masterdata_project');
	}
};
