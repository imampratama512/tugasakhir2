<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'category_id', 'code', 'name',
        'current_stock', 'minimum_stock', 'price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getStockStatusAttribute()
    {
        if ($this->current_stock == 0) return 'Habis';
        if ($this->current_stock <= $this->minimum_stock) return 'Menipis';
        return 'Aman';
    }
}
