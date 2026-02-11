<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->uuid('uuid')->nullable()->after('id');
			$table->string('firstname')->nullable()->after('uuid');
			$table->string('role')->default('viewer')->after('email');
			$table->softDeletes();
		});

		DB::table('users')->whereNull('uuid')->orWhere('uuid', '')->get()->each(function ($row) {
			DB::table('users')->where('id', $row->id)->update(['uuid' => Str::uuid()]);
		});

		Schema::table('users', function (Blueprint $table) {
			$table->uuid('uuid')->unique()->change();
		});
	}

	public function down(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn(['uuid', 'firstname', 'role']);
			$table->dropSoftDeletes();
		});
	}
};
