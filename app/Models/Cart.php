<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cart extends Model
{
    protected $fillable = [
        'table_id',
        'food_id',
        'quantity',
        'price',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
}
