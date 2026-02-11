<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('awards', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->string('title');
			$table->string('description')->nullable();
			$table->unsignedSmallInteger('year');
			$table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
			$table->string('link')->nullable();
			$table->boolean('publish')->default(true);
			$table->integer('sort_order')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('awards');
	}
};
