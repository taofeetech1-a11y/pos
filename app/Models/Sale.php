<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
     protected $fillable = [
        'sub_total',
        'discount',
        'discount_type',
        'shipping',
        'grand_total',
        'payment_method',
        'cashier_id',
        'payment_status',
        'receipt_number'
    ];

    protected $casts = [
        'sub_total' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping' => 'decimal:2',
        'grand_total' => 'decimal:2'
    ];

    // Relationships
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    // Calculate grand total automatically
    public function calculateGrandTotal()
    {
        $subTotal = $this->sub_total;
        $discountAmount = 0;

        if ($this->discount_type === 'percentage') {
            $discountAmount = ($subTotal * $this->discount) / 100;
        } else {
            $discountAmount = $this->discount;
        }

        $this->grand_total = $subTotal - $discountAmount + $this->shipping;
        return $this->grand_total;
    }
}
