<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('project_block_links', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->foreignId('project_block_id')->constrained()->cascadeOnDelete();
			$table->string('title')->nullable();
			$table->string('url')->nullable();
			$table->string('link_type')->default('external');
			$table->foreignId('linked_project_id')->nullable()->constrained('projects')->nullOnDelete();
			$table->integer('sort_order')->default(0);
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('project_block_links');
	}
};
