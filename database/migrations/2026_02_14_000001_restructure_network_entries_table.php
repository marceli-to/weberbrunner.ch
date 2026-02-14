<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('network_entries', function (Blueprint $table) {
			$table->dropColumn(['title', 'description', 'category', 'link']);
			$table->text('text')->nullable()->after('uuid');
			$table->foreignId('section_id')->nullable()->after('text')->constrained('sections');
		});
	}

	public function down(): void
	{
		Schema::table('network_entries', function (Blueprint $table) {
			$table->dropForeign(['section_id']);
			$table->dropColumn(['text', 'section_id']);
			$table->string('title')->after('uuid');
			$table->string('description')->nullable()->after('title');
			$table->string('category')->nullable()->after('description');
			$table->string('link')->nullable()->after('category');
		});
	}
};
