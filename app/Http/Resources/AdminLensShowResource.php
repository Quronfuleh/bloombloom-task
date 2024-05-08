<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminLensShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'colour' => $this->colour,
            'description' => $this->description,
            'prescription_type' => $this->prescription_type,
            'lens_type' => $this->lens_type,
            'stock' => $this->stock,
            'prices' => $this->prices->map(function ($price) {
                return [
                    'currency' => $price->currency->code,
                    'amount' => $price->price
                ];
            }),
        ];
    }
}
