<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_category', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->unsignedInteger('created_by')->index()->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('project_category_id')->index();
            $table->string('image');
            $table->string('title',200);
            $table->string('started');
            $table->string('ended')->nullable();
            $table->longText('language_used');
            $table->longText('description');
            $table->longText('github')->nullable();
            $table->longText('webapp')->nullable();
            $table->unsignedInteger('created_by')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_category');
        Schema::dropIfExists('projects');
    }
};
