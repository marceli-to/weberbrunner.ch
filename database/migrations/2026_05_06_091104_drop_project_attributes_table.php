<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::dropIfExists('project_attributes');
	}

	public function down(): void
	{
		Schema::create('project_attributes', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->foreignId('project_id')->constrained()->cascadeOnDelete();
			$table->string('label');
			$table->text('value');
			$table->integer('sort_order')->default(0);
			$table->timestamps();
		});
	}
};
