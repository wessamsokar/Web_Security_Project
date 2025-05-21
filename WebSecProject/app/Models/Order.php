<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total',
        'payment_method'
    ];

    // Accessor to get status color
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'pending payment':
                return 'warning';
            case 'Accept':
                return 'success';
            case 'Reject':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

}
