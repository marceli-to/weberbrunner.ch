<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::dropIfExists('network_entries');
	}

	public function down(): void
	{
		Schema::create('network_entries', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->text('text')->nullable();
			$table->foreignId('section_id')->nullable()->constrained('sections');
			$table->boolean('publish')->default(true);
			$table->integer('sort_order')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}
};
