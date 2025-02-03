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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('HouseNo')->nullable();
            $table->string('Block')->nullable();
            $table->string('Street')->nullable();
            $table->string('PostalCode')->nullable();
            $table->string('City')->nullable();
            $table->string('District');
            $table->string('Country')->default('Bangladesh');
            $table->json('opening_hours');
            $table->string('image')->nullable();
            $table->text('details');
            $table->timestamps();

            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
