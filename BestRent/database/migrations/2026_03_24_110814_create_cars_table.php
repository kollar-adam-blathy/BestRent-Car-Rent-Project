<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->string('plate_number')->unique();
            $table->string('color')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->unsignedTinyInteger('seats')->default(5);
            $table->decimal('daily_price', 10, 2);
            $table->string('status')->default('available');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
