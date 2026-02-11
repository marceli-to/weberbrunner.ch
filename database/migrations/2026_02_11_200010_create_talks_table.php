<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('talks', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->string('title');
			$table->string('event')->nullable();
			$table->string('location')->nullable();
			$table->date('date');
			$table->string('link')->nullable();
			$table->boolean('publish')->default(true);
			$table->integer('sort_order')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('talks');
	}
};
