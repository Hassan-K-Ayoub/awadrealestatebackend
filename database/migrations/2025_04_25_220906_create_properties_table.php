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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title')->required();
            $table->json('images')->required();
            $table->string('description')->required();
            $table->float('space');
            $table->float('price')->required();
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->integer('salons');
            $table->integer('living_rooms');
            $table->integer('kitchens');
            $table->integer('floors');
            $table->boolean('terraces')->default(false);
            $table->integer('terraces_count')->nullable();
            $table->boolean('garden')->default(false);
            $table->integer('garden_count')->nullable();
            $table->boolean('swimming_pools')->default(false);
            $table->integer('swimming_pools_count')->nullable();
            $table->boolean('parking')->default(false);
            $table->integer('parking_count')->nullable();
            $table->string('condition')->required();
            $table->boolean('featured')->default(false);
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
