<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });
        $brands = [
            'ADATA',
            'ASUS',
            'BIOSTAR',
            'CORSAIR',
            'DEEPCOOL',
            'GIGABYTE',
            'HyperX',
            'INTEL',
            'KCC',
            'KINGSTON',
            'LIAN-LI',
            'LOGITECH',
            'MIK',
            'MSI',
            'NZXT',
            'PNY',
            'PowerColor',
            'SAMSUNG',
            'SAPPHIRE',
            'Segotep',
            'SteelSeries',
            'Super Flower',
            'Thermalright',
            'Thermaltake',
            'TOSHIBA',
            'VSP',
            'XIGMATEK',
        ];

        $data = [];

        foreach ($brands as $brand) {
            $data[] = [
                'name' => $brand,
                'slug' => Str::slug($brand),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('brands')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
