<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->morphs('priceable');
            $table->decimal('price', 8, 2);
            $table->unsignedBigInteger('currency_id');
            $table->timestamps();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->unique(['priceable_type', 'priceable_id', 'currency_id'], 'unique_price_per_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
