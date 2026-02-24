<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('contacts', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->foreignId('location_id')->constrained()->cascadeOnDelete();
			$table->string('company_name');
			$table->text('address');
			$table->string('phone')->nullable();
			$table->string('email')->nullable();
			$table->string('maps_url')->nullable();
			$table->boolean('publish')->default(false);
			$table->integer('sort_order')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('contacts');
	}
};
