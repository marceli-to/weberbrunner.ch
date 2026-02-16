<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->integer('number');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('publish')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
