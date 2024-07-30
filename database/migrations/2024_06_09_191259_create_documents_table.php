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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('path')->nullable();
            $table->string('access_level')->nullable();
            $table->text('license_condition')->nullable();
            $table->string('embargo_end_date')->nullable();
            $table->string('pub_date')->nullable();
            $table->string('pub_version')->nullable();
            $table->string('pub_id')->nullable();
            $table->string('resource_id')->nullable();
            $table->text('source')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
