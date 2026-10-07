<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    //
    protected $fillable = [
        'sale_id',
        'product_id',
        'qty',
        'unit_price',
        'total_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    // Relationships
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Automatically calculate total_price
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->total_price = $item->unit_price * $item->qty;
        });

        static::updating(function ($item) {
            $item->total_price = $item->unit_price * $item->qty;
        });
    }
}
