<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'guests',
        'total_price',
        'service_fee',
        'status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'special_requests',
        'cancellation_reason',
        'cancelled_at',
        'rating',
        'review',
        'reviewed_at',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'rating' => 'decimal:2',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('check_in_date', '>', now())
                    ->whereIn('status', ['confirmed', 'pending']);
    }

    public function scopeActive($query)
    {
        return $query->where('check_in_date', '<=', now())
                    ->where('check_out_date', '>=', now())
                    ->where('status', 'confirmed');
    }

    // Helper methods
    public function getTotalNightsAttribute()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    public function getFormattedTotalPriceAttribute()
    {
        return '$' . number_format($this->total_price, 2);
    }

    public function canBeCancelled()
    {
        return $this->status === 'pending' || 
               ($this->status === 'confirmed' && $this->check_in_date->gt(now()->addHours(24)));
    }

    public function canBeReviewed()
    {
        return $this->status === 'completed' && 
               $this->check_out_date->lt(now()) && 
               is_null($this->reviewed_at);
    }

    public function isActive()
    {
        return $this->check_in_date->lte(now()) && 
               $this->check_out_date->gte(now()) && 
               $this->status === 'confirmed';
    }
}
