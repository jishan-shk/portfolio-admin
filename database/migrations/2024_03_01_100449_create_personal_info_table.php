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
        Schema::create('personal_info', function (Blueprint $table) {
            $table->id();
            $table->string('profile_logo')->nullable();
            $table->string('resume');
            $table->text('position');
            $table->string('full_name');
            $table->string('email',50);
            $table->string('date_of_birth');
            $table->string('Availablity',50);
            $table->string('work_started');
            $table->string('total_experience',50);
            $table->text('about_me');
            $table->text('facebook_link')->nullable();
            $table->text('linkedin_link')->nullable();
            $table->text('instagram_link')->nullable();
            $table->text('twitter_link')->nullable();
            $table->text('github_link')->nullable();
            $table->text('whatshapp_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_info');
    }
};
