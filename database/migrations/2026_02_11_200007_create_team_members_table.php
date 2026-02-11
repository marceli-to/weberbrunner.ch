<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('team_members', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->string('firstname');
			$table->string('name');
			$table->string('email')->nullable();
			$table->string('title')->nullable();
			$table->unsignedSmallInteger('since')->nullable();
			$table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
			$table->string('slug')->unique();
			$table->boolean('publish')->default(true);
			$table->integer('sort_order')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('team_members');
	}
};
