<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLensIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $currencyCode = $request->header('X-Currency');
        $price = $this->prices->where('currency.code', $currencyCode)->first();

        return [
            'id' => $this->id,
            'colour' => $this->colour,
            'prescription_type' => $this->prescription_type,
            'lens_type' => $this->lens_type,
            'stock' => $this->stock,
            'price' => $price ? $price->price : 'Price unavailable',
        ];
    }
}
