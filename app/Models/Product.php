<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'name',
        'code',
        'category_id',
        'brand_id',
        'price',
        'stock_alert',
        'note',
        'product_qty',
        'discount',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function productSupplies()
    {
        return $this->hasMany(ProductSupply::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplies')
            ->withPivot('unit_price', 'qty_supplied', 'status', 'invoice_number', 'supply_date')
            ->withTimestamps();
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
     public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    // Helper method to check if product is in stock
    public function isInStock()
    {
        return $this->product_qty > 0;
    }

    // Helper method to check if stock is low
    public function isLowStock()
    {
        return $this->product_qty <= $this->stock_alert;
    }
}
