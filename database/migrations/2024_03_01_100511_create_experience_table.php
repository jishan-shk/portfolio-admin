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
        Schema::create('experience', function (Blueprint $table) {
            $table->id();
            $table->string('company_logo')->nullable();
            $table->string('company_name');
            $table->longText('role');
            $table->string('start');
            $table->string('end')->nullable();
            $table->string('end_status')->nullable();
            $table->longText('description');
            $table->longText('skills');
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('experience_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('experience_id')->index();
            $table->string('file_name');
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experience');
        Schema::dropIfExists('experience_documents');
    }
};
