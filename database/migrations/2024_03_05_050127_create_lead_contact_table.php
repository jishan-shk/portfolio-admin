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
        Schema::create('lead_contact', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('name');
            $table->string('subject');
            $table->longText('message');
            $table->boolean('email_send')->default(0)->comment('0 = No , 1 = Yes')->index();
            $table->boolean('connected')->default(0)->comment('0 = No , 1 = Yes')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_contact');
    }
};
