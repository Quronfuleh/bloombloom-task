<?php

namespace App\Services;

use App\Models\Frame;
use App\Models\Lens;

use Illuminate\Support\Facades\DB;

class CustomGlassesService
{
    /**
     * Create custom glasses with specified frame and lens IDs and currency code.
     *
     * @param int $frameId
     * @param int $lensId
     * @param string $currencyCode
     * @return array
     * @throws \Exception
     */
    public function createCustomGlasses($frameId, $lensId, $currencyCode)
    {
        DB::beginTransaction();
    
        try {
            $frame = Frame::findOrFail($frameId);
            $lens = Lens::findOrFail($lensId);
    
            if ($frame->stock < 1 || $lens->stock < 1) {
                throw new \Exception('The selected frame or lens is out of stock.');
            }
    
            $frame->decrement('stock');
            $lens->decrement('stock');
    
            $priceFrame = $frame->prices()->whereHas('currency', function ($query) use ($currencyCode) {
                $query->where('code', $currencyCode);
            })->first();
    
            $priceLens = $lens->prices()->whereHas('currency', function ($query) use ($currencyCode) {
                $query->where('code', $currencyCode);
            })->first();
    
            if (!$priceFrame || !$priceLens) {
                throw new \Exception("Prices for the selected currency code ($currencyCode) are not available for the selected frame and/or lens.");
            }
    
            DB::commit();
    
            return [
                'frame' => $frame,
                'lens' => $lens,
                'frame_price' => $priceFrame->price,
                'lens_price' => $priceLens->price,
                'total_price' => $priceFrame->price + $priceLens->price,
                'currency' => $currencyCode
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; 
        }
    }
    
    
}
