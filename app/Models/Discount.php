<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['name', 'description', 'min_total_transaction', 'max_transactions_count', 'percentage', 'is_active', 'type'];
}
