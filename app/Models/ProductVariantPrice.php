<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    protected $appends = ['variant_name'];
    public $guarded = [];

    public function getVariantNameAttribute()
    {
        $variantOne = ProductVariant::find($this->product_variant_one) ? ProductVariant::find($this->product_variant_one)->variant : '';
        $variantTwo = ProductVariant::find($this->product_variant_two) ? ProductVariant::find($this->product_variant_two)->variant : '';
        $variantThree = ProductVariant::find($this->product_variant_three) ? ProductVariant::find($this->product_variant_three)->variant : '';

        return $variantOne.'/'.$variantTwo.'/'.$variantThree;
    }

    public function variantOne()
    {
        return $this->belongsTo(ProductVariant::Class,'product_variant_one');
    }

    public function variantDetails()
    {
        return $this->belongsTo(ProductVariant::class);
    }

}
