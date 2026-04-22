<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('blocks', function (Blueprint $table) {
			$table->id();
			$table->uuid('uuid')->unique();
			$table->string('blockable_type');
			$table->unsignedBigInteger('blockable_id');
			$table->string('type');
			$table->string('title')->nullable();
			$table->text('content')->nullable();
			$table->string('url')->nullable();
			$table->integer('sort_order')->default(0);
			$table->timestamps();

			$table->index(['blockable_type', 'blockable_id', 'sort_order'], 'blocks_blockable_sort_index');
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('blocks');
	}
};
