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
            $table->string('title');
            $table->string('path')->nullable();
            $table->string('creator')->nullable();
            $table->string('access_level')->nullable();
            $table->string('license_condition')->nullable();
            $table->string('contributor')->nullable();
            $table->string('pub_date')->nullable();
            $table->string('pub_type')->nullable();
            $table->string('resource_identifier')->nullable();
            $table->string('proj_identifier')->nullable();
            $table->string('date')->nullable();
            $table->string('dataset_ref')->nullable();
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->string('publisher')->nullable();
            $table->string('language')->nullable();
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
