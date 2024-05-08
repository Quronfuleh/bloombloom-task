<?php

namespace App\Services;

use App\Models\Lens;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LensService
{
   /**
     * Get all lenses that have prices in a specified currency.
     *
     * @param string $currencyCode
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllLensesWithCurrency($currencyCode)
    {
        return Lens::whereHas('prices.currency', function ($query) use ($currencyCode) {
            $query->where('code', $currencyCode);
        })
        ->with(['prices' => function ($query) use ($currencyCode) {
            $query->whereHas('currency', function ($q) use ($currencyCode) {
                $q->where('code', $currencyCode);
            });
        }])
        ->get();
    }

    public function listLenses()
    {
        return Lens::with('prices.currency')->get();
    }

    /**
     * Create a lens with prices.
     *
     * @param array $data
     * @return Lens
     * @throws \Exception
     */
    public function createLensWithPrices(array $data)
    {
        DB::beginTransaction();
        try {
            $lens = Lens::create([
                'colour' => $data['colour'],
                'description' => $data['description'],
                'prescription_type' => $data['prescription_type'],
                'lens_type' => $data['lens_type'],
                'stock' => $data['stock']
            ]);

            foreach ($data['prices'] as $priceData) {
                $currency = Currency::where('code', $priceData['currency_code'])->firstOrFail();
                $existingPrice = DB::table('prices')->where([
                    ['priceable_type', '=', Lens::class],
                    ['priceable_id', '=', $lens->id],
                    ['currency_id', '=', $currency->id],
                ])->exists();

                if ($existingPrice) {
                    throw new ValidationException("The price for each currency must be unique per lens.");
                }

                $lens->prices()->create([
                    'price' => $priceData['price'],
                    'currency_id' => $currency->id
                ]);
            }

            DB::commit();
            return $lens;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
