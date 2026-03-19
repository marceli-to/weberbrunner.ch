<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('publications', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->string('title');
			$table->string('subtitle')->nullable();
			$table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
			$table->boolean('publish')->default(true);
			$table->integer('sort_order')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('publications');
	}
};
