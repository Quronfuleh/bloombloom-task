<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFrameIndexResource extends JsonResource
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
            'name' => $this->name,
            'status' => $this->status,
            'stock' => $this->stock,
            'price' => $price ? $price->price : 'Price unavailable',
        ];
    }
}
