<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('masterdata', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->foreignId('masterdata_group_id')->nullable()->constrained('masterdata_groups')->nullOnDelete();
			$table->string('title');
			$table->boolean('is_default')->default(false);
			$table->integer('sort_order')->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('masterdata');
	}
};
