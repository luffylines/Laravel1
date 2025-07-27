<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'recommendation_score',
        'reasoning',
        'user_preferences',
        'recommendation_type',
        'is_clicked',
        'is_booked',
        'clicked_at',
        'booked_at',
    ];

    protected $casts = [
        'recommendation_score' => 'decimal:4',
        'reasoning' => 'array',
        'user_preferences' => 'array',
        'is_clicked' => 'boolean',
        'is_booked' => 'boolean',
        'clicked_at' => 'datetime',
        'booked_at' => 'datetime',
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
    public function scopeHighScore($query, $threshold = 0.7)
    {
        return $query->where('recommendation_score', '>=', $threshold);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('recommendation_type', $type);
    }

    public function scopeClicked($query)
    {
        return $query->where('is_clicked', true);
    }

    public function scopeBooked($query)
    {
        return $query->where('is_booked', true);
    }

    // Helper methods
    public function getFormattedScoreAttribute()
    {
        return number_format($this->recommendation_score * 100, 1) . '%';
    }

    public function markAsClicked()
    {
        $this->update([
            'is_clicked' => true,
            'clicked_at' => now(),
        ]);
    }

    public function markAsBooked()
    {
        $this->update([
            'is_booked' => true,
            'booked_at' => now(),
        ]);
    }

    public function getMainReasonAttribute()
    {
        return $this->reasoning['primary_reason'] ?? 'Similar preferences';
    }
}
