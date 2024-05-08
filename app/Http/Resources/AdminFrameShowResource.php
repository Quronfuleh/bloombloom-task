<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminFrameShowResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
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
