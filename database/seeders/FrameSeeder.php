<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Insert frames and retrieve their IDs
        $frameIds = DB::table('frames')->insertGetId([
            ['name' => 'Classic Black', 'description' => 'A timeless classic frame in black.', 'status' => 'active', 'stock' => 15],
            ['name' => 'Retro Blue', 'description' => 'Blue retro frames for the fashion-forward.', 'status' => 'active', 'stock' => 10]
        ]);
    
        // Fetch all currency IDs
        $currencies = DB::table('currencies')->get();
    
        // Prepare price data for each frame and currency
        foreach ([$frameIds] as $frameId) {
            foreach ($currencies as $currency) {
                DB::table('prices')->insert([
                    [
                        'priceable_type' => 'App\Models\Frame',
                        'priceable_id' => $frameId,
                        'currency_id' => $currency->id,
                        'price' => rand(50, 200) // Random price for the example
                    ]
                ]);
            }
        }
    }
    
}
