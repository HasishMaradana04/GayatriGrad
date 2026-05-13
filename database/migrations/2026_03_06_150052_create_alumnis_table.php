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
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('graduation_year')->index();
            $table->string('degree')->nullable();
            $table->string('department')->nullable()->index();
            $table->string('current_position')->nullable();
            $table->string('organization')->nullable();
            $table->string('location')->nullable()->index();
            $table->string('profile_photo_path')->nullable();
            $table->text('achievements')->nullable();
            $table->boolean('is_distinguished')->default(false)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
