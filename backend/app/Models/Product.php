<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'harga',
        'stok',
        'kategori_id',
        'expired_date',
        'kode_produk',
        'modal_per_produk'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (!$product->expired_date) {
                $product->expired_date = now()->addDays(30); // default 30 hari
            }
        });
    }

    // Accessor untuk sisa hari sebelum expired
    public function getDaysLeftAttribute()
    {
        return now()->diffInDays($this->expired_date, false);
    }

    // Accessor untuk status otomatis berdasarkan sisa hari
    public function getAutoStatusAttribute()
    {
        $daysLeft = $this->days_left;

        if ($daysLeft <= 0) return 'expired';
        if ($daysLeft <= 5) return 'warning';
        return 'active';
    }

    // Relasi ke kategori
    public function kategori()
{
    return $this->belongsTo(Category::class, 'kategori_id', 'id');
}


public function sales()
{
    return $this->belongsToMany(Sale::class, 'sale_product', 'product_id', 'sale_id')
                ->withPivot('quantity', 'price', 'subtotal')
                ->withTimestamps();
}


// di App\Models\Product.php





}
