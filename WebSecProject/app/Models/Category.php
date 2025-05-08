<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Helper method to get products count
    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }
}
