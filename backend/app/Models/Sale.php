<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['total','cashier_id'];

    public function products()
{
    return $this->belongsToMany(Product::class, 'sale_product', 'sale_id', 'product_id')
                ->withPivot('quantity', 'price', 'subtotal')
                ->withTimestamps();
}
public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

}
