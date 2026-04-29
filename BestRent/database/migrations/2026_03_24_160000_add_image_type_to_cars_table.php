<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('image_type')->nullable()->after('image');
        });

        $cars = DB::table('cars')->select('id', 'image')->get();

        foreach ($cars as $car) {
            if (! $car->image) {
                continue;
            }

            DB::table('cars')
                ->where('id', $car->id)
                ->update([
                    'image_type' => Str::startsWith($car->image, ['http://', 'https://']) ? 'link' : 'file',
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('image_type');
        });
    }
};
