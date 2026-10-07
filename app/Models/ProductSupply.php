<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSupply extends Model
{
    //
    protected $fillable = [
        'invoice_number',
        'supply_date',
        'product_id',
        'supplier_id',
        'unit_price',
        'qty_supplied',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'supply_date' => 'date'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Automatically calculate total_amount
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supply) {
            $supply->total_amount = $supply->unit_price * $supply->qty_supplied;
        });

        static::updating(function ($supply) {
            $supply->total_amount = $supply->unit_price * $supply->qty_supplied;
        });
    }
}
