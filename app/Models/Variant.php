<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        'title', 'description'
    ];

    public function getVariantDetailsAttribute()
    {
        return $this->productItems()->select('variant')->groupBy('variant')->get();
    }

    public function productItems()
    {
        return $this->hasMany(ProductVariant::class);
    }

}
