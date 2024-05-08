<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->insert([
            ['code' => 'USD', 'name' => 'US Dollar'],
            ['code' => 'GBP', 'name' => 'British Pound'],
            ['code' => 'EUR', 'name' => 'Euro'],
            ['code' => 'JOD', 'name' => 'Jordanian Dinar'],
            ['code' => 'JPY', 'name' => 'Japanese Yen']
        ]); 
    }
}
