<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Insert lenses and retrieve their IDs
        $lensIds = [
            DB::table('lenses')->insertGetId([
                'colour' => 'Transparent',
                'description' => 'Clear lenses for everyday use.',
                'prescription_type' => 'single_vision',
                'lens_type' => 'classic',
                'stock' => 50
            ]),
            DB::table('lenses')->insertGetId([
                'colour' => 'Blue',
                'description' => 'Blue light blocking lenses.',
                'prescription_type' => 'single_vision',
                'lens_type' => 'blue_light',
                'stock' => 30
            ])
        ];
    
        // Fetch all currency IDs
        $currencies = DB::table('currencies')->get();
    
        // Prepare price data for each lens and currency
        foreach ($lensIds as $lensId) {
            foreach ($currencies as $currency) {
                DB::table('prices')->insert([
                    'priceable_type' => 'App\Models\Lens',
                    'priceable_id' => $lensId,
                    'currency_id' => $currency->id,
                    'price' => rand(50, 150) // Random price for the example
                ]);
            }
        }
    }
    
}
