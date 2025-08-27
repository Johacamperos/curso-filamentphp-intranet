<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorldSeeder extends Seeder
{
    public function run(): void
    {
        // Países de ejemplo
        DB::table('countries')->insert([
            [
                'iso2' => 'CO',
                'iso3' => 'COL',
                'name' => 'Colombia',
                'status' => 1,
                'phone_code' => '+57',
                'region' => 'Americas',
                'subregion' => 'South America',
            ],
            [
                'iso2' => 'US',
                'iso3' => 'USA',
                'name' => 'United States',
                'status' => 1,
                'phone_code' => '+1',
                'region' => 'Americas',
                'subregion' => 'North America',
            ],
        ]);

        // Estados de ejemplo
        DB::table('states')->insert([
            ['country_id' => 1, 'name' => 'Cundinamarca'],
            ['country_id' => 1, 'name' => 'Antioquia'],
            ['country_id' => 2, 'name' => 'California'],
            ['country_id' => 2, 'name' => 'Texas'],
        ]);

        // Ciudades de ejemplo
        DB::table('cities')->insert([
            ['country_id' => 1, 'state_id' => 1, 'country_code' => 'CO', 'name' => 'Bogotá'],
            ['country_id' => 1, 'state_id' => 2, 'country_code' => 'CO', 'name' => 'Medellín'],
            ['country_id' => 2, 'state_id' => 3, 'country_code' => 'US', 'name' => 'Los Angeles'],
            ['country_id' => 2, 'state_id' => 4, 'country_code' => 'US', 'name' => 'Houston'],
        ]);
    }
}
