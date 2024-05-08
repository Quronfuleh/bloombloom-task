<?php

namespace App\Services;

use App\Models\Frame;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FrameService
{
    /**
     * Get active frames that have prices in a specified currency.
     *
     * @param string $currencyCode
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveFramesWithCurrency($currencyCode)
    {
        return Frame::whereHas('prices.currency', function ($query) use ($currencyCode) {
            $query->where('code', $currencyCode);
        })
        ->where('status', 'active')
        ->with(['prices' => function ($query) use ($currencyCode) {
            $query->whereHas('currency', function ($q) use ($currencyCode) {
                $q->where('code', $currencyCode);
            });
        }])
        ->get();
    }

    public function listFrames()
    {
        return Frame::with('prices.currency')->get();
    }
    /**
     * Create a frame with prices.
     *
     * @param array $data
     * @return Frame
     * @throws \Exception
     */
    public function createFrameWithPrices(array $data)
    {
        DB::beginTransaction();
        try {
            $frame = Frame::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'status' => $data['status'],
                'stock' => $data['stock']
            ]);

            foreach ($data['prices'] as $priceData) {
                $currency = Currency::where('code', $priceData['currency_code'])->firstOrFail();
                $existingPrice = DB::table('prices')->where([
                    ['priceable_type', '=', Frame::class],
                    ['priceable_id', '=', $frame->id],
                    ['currency_id', '=', $currency->id],
                ])->exists();

                if ($existingPrice) {
                    throw new ValidationException("The price for each currency must be unique per frame.");
                }

                $frame->prices()->create([
                    'price' => $priceData['price'],
                    'currency_id' => $currency->id
                ]);
            }

            DB::commit();
            return $frame;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
