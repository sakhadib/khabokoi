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
        Schema::create('branch_cuisines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('cuisine_id');
            $table->string('nickname')->nullable();
            $table->float('regular_price');
            $table->float('discount_percentage')->nullable()->check('discount_percentage >= 0 and discount_percentage <= 100');
            $table->text('details')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('cuisine_id')->references('id')->on('cuisines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_cuisines');
    }
};
