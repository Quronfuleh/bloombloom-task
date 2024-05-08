<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomGlassesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {

        return [
            'message' => 'Custom glasses created successfully.',
            'frame_details' => [
                'id' => $this->frame->id,
                'name' => $this->frame->name,
                'stock' => $this->lens->stock,
                'price' => $this->frame_price, 


            ],
            'lens_details' => [
                'id' => $this->lens->id,
                'type' => $this->lens->lens_type,
                'stock' => $this->lens->stock, 
                'price' => $this->lens_price,  
            ],
            'total_price' => $this->total_price,
            'currency' => $this->currency
        ];
    }
}
