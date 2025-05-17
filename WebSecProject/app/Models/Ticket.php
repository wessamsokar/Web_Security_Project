<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'description',
        'user_id',
        'order_id',
        'status',
        'priority',
        'assigned_to'
    ];

    // Ticket statuses
    const STATUS_OPEN = 'open';
    const STATUS_PENDING = 'pending';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    // Ticket priorities
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function responses()
    {
        return $this->hasMany(TicketResponse::class);
    }

    // Status color for UI display
    public function getStatusColorAttribute()
    {
        return [
            self::STATUS_OPEN => 'primary',
            self::STATUS_PENDING => 'warning',
            self::STATUS_RESOLVED => 'success',
            self::STATUS_CLOSED => 'secondary',
        ][$this->status] ?? 'info';
    }

    // Priority color for UI display
    public function getPriorityColorAttribute()
    {
        return [
            self::PRIORITY_LOW => 'info',
            self::PRIORITY_MEDIUM => 'warning',
            self::PRIORITY_HIGH => 'danger',
            self::PRIORITY_URGENT => 'dark',
        ][$this->priority] ?? 'secondary';
    }
}
?>