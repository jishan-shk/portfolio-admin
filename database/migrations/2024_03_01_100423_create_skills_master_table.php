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
        Schema::create('skills_master', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('skills_category_id')->index();
            $table->string('name')->index();
            $table->string('logo')->index();
            $table->unsignedInteger('created_by')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills_master');
    }
};
