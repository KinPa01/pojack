<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Cart extends Model
{
    use HasFactory;

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

    public function CategoryController()
    {
        return $this->belongsTo(CategoryController::class);
    }
}
