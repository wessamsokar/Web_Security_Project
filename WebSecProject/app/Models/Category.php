<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'gender'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function getUnisexCategory()
    {
        return static::firstOrCreate(
            ['gender' => 'Unisex'],
            ['name' => 'Unisex Products']
        );
    }
}
