<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class Order extends Model
{
    protected $fillable = ['user_id', 'status', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function getStatusColorAttribute()
    {
        return [
            'Pending' => 'secondary',
            'Processing' => 'warning',
            'Shipped' => 'info',
            'Delivered' => 'success',
            'Cancelled' => 'danger',
        ][$this->status] ?? 'secondary';
    }
}
