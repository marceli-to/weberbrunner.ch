<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('publication_blocks', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->foreignId('publication_id')->constrained()->cascadeOnDelete();
			$table->string('type');
			$table->string('title')->nullable();
			$table->text('content')->nullable();
			$table->string('url')->nullable();
			$table->integer('sort_order')->default(0);
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('publication_blocks');
	}
};
