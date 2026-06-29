<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $provinceId = DB::table('provinces')->insertGetId([
            'province' => 'Girona',
        ]);

        $cityId = DB::table('cities')->insertGetId([
            'city' => 'Salt',
            'province_id' => $provinceId,
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => 'carlessaubi',
            'email' => 'info@fusteriasaubi.com',
            'password' => Hash::make('saubi17190'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('customers')->insert([
            'first_name' => 'Carles',
            'last_name' => 'Saubí',
            'phone' => '972230680',
            'street' => 'Carrer Cardenal Vidal i Barraquer',
            'address_number' => '18',
            'address_floor' => 'Planta Baixa',
             'city_id' => $cityId,
            'postal_code' => '17190',
            'user_id' => $userId,
        ]);
    }
}
