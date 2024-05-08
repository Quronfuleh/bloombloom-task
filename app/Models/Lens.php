<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lens extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function prices()
    {
        return $this->morphMany(Price::class, 'priceable');
    }
}
