<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // Pastikan ini ada:
    protected $appends = ['total_spent', 'transaction_count'];

    public function getTotalSpentAttribute()
    {
        // Hitung total_amount dari semua transaksi
        return $this->transactions()->sum('total_amount');
    }

    public function getTransactionCountAttribute()
    {
        return $this->transactions()->count();
    }
}
