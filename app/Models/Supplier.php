<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //
        protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'image'
    ];

    // Relationships
    public function productSupplies()
    {
        return $this->hasMany(ProductSupply::class);
    }

    // Many-to-many with products through product_supplies
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplies')
            ->withPivot('unit_price', 'qty_supplied', 'status', 'invoice_number', 'supply_date')
            ->withTimestamps();
    }
}
