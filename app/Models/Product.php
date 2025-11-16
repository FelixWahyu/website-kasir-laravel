<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'product_name',
        'image',
        'sku',
        'purchase_price',
        'selling_price',
        'stock',
        'stock_minimum',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
