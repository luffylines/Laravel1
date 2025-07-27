<?php

namespace App\Services;

use App\Models\Room;
use App\Models\User;
use App\Models\Booking;
use App\Models\AiRecommendation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AIRecommendationService
{
    /**
     * Generate AI-powered room recommendations for a user
     */
    public function generateRecommendations(User $user, array $preferences = []): Collection
    {
        // Combine different recommendation strategies
        $collaborativeRecs = $this->getCollaborativeRecommendations($user);
        $contentBasedRecs = $this->getContentBasedRecommendations($user, $preferences);
        $popularRecs = $this->getPopularRecommendations($user);
        
        // Merge and weight the recommendations
        $allRecommendations = $this->mergeRecommendations([
            'collaborative' => $collaborativeRecs,
            'content_based' => $contentBasedRecs,
            'popular' => $popularRecs
        ]);

        // Store recommendations in database
        $this->storeRecommendations($user, $allRecommendations);

        return $allRecommendations->take(10);
    }

    /**
     * Collaborative filtering recommendations based on similar users
     */
    private function getCollaborativeRecommendations(User $user): Collection
    {
        // Find users with similar booking patterns
        $similarUsers = $this->findSimilarUsers($user);
        
        if ($similarUsers->isEmpty()) {
            return collect();
        }

        // Get rooms booked by similar users that current user hasn't booked
        $userBookedRoomIds = $user->bookings()->pluck('room_id')->toArray();
        
        $recommendations = Room::whereIn('id', function($query) use ($similarUsers) {
            $query->select('room_id')
                  ->from('bookings')
                  ->whereIn('user_id', $similarUsers->pluck('id'))
                  ->where('status', 'completed')
                  ->where('rating', '>=', 4.0);
        })
        ->whereNotIn('id', $userBookedRoomIds)
        ->available()
        ->get()
        ->map(function($room) use ($similarUsers) {
            $score = $this->calculateCollaborativeScore($room, $similarUsers);
            return [
                'room' => $room,
                'score' => $score,
                'type' => 'collaborative',
                'reasoning' => [
                    'primary_reason' => 'Users with similar preferences loved this place',
                    'similar_users_count' => $similarUsers->count(),
                    'average_rating' => $room->rating
                ]
            ];
        });

        return $recommendations;
    }

    /**
     * Content-based recommendations based on user preferences and room features
     */
    private function getContentBasedRecommendations(User $user, array $preferences): Collection
    {
        $userPreferences = $this->analyzeUserPreferences($user, $preferences);
        
        $recommendations = Room::available()
            ->get()
            ->map(function($room) use ($userPreferences) {
                $score = $this->calculateContentScore($room, $userPreferences);
                return [
                    'room' => $room,
                    'score' => $score,
                    'type' => 'content_based',
                    'reasoning' => [
                        'primary_reason' => $this->getContentReasonForRoom($room, $userPreferences),
                        'matched_features' => $this->getMatchedFeatures($room, $userPreferences),
                        'preference_match' => round($score * 100, 1) . '%'
                    ]
                ];
            })
            ->filter(function($rec) {
                return $rec['score'] > 0.3; // Only recommend rooms with decent match
            });

        return $recommendations;
    }

    /**
     * Popular recommendations based on trending and highly-rated rooms
     */
    private function getPopularRecommendations(User $user): Collection
    {
        $popularRooms = Room::available()
            ->where('rating', '>=', 4.0)
            ->where('total_reviews', '>=', 5)
            ->orderByDesc('rating')
            ->orderByDesc('total_reviews')
            ->take(20)
            ->get();

        $recommendations = $popularRooms->map(function($room) {
            $trendingScore = $this->calculateTrendingScore($room);
            return [
                'room' => $room,
                'score' => min(0.9, 0.6 + $trendingScore), // Cap at 0.9 for popular items
                'type' => 'popular',
                'reasoning' => [
                    'primary_reason' => 'Trending and highly rated by guests',
                    'rating' => $room->rating,
                    'total_reviews' => $room->total_reviews,
                    'trending_factor' => round($trendingScore * 100, 1) . '%'
                ]
            ];
        });

        return $recommendations;
    }

    /**
     * Find users with similar booking patterns
     */
    private function findSimilarUsers(User $user): Collection
    {
        $userBookings = $user->bookings()->with('room')->get();
        
        if ($userBookings->isEmpty()) {
            return collect();
        }

        $userRoomTypes = $userBookings->pluck('room.type')->unique();
        $userCities = $userBookings->pluck('room.city')->unique();
        $userPriceRange = [
            'min' => $userBookings->min('room.price_per_night'),
            'max' => $userBookings->max('room.price_per_night')
        ];

        // Find users who booked similar room types and locations
        $similarUsers = User::whereHas('bookings.room', function($query) use ($userRoomTypes, $userCities, $userPriceRange) {
            $query->whereIn('type', $userRoomTypes)
                  ->orWhereIn('city', $userCities)
                  ->orWhereBetween('price_per_night', [
                      $userPriceRange['min'] * 0.8, 
                      $userPriceRange['max'] * 1.2
                  ]);
        })
        ->where('id', '!=', $user->id)
        ->take(10)
        ->get();

        return $similarUsers;
    }

    /**
     * Analyze user preferences from booking history and explicit preferences
     */
    private function analyzeUserPreferences(User $user, array $explicitPreferences): array
    {
        $bookingHistory = $user->bookings()->with('room')->where('rating', '>=', 4)->get();
        
        $preferences = [
            'preferred_types' => [],
            'preferred_cities' => [],
            'price_range' => ['min' => 0, 'max' => 1000],
            'preferred_amenities' => [],
            'capacity_preference' => 2,
            'rating_threshold' => 4.0
        ];

        // Learn from booking history
        if ($bookingHistory->isNotEmpty()) {
            $preferences['preferred_types'] = $bookingHistory->pluck('room.type')->unique()->values()->toArray();
            $preferences['preferred_cities'] = $bookingHistory->pluck('room.city')->unique()->values()->toArray();
            $preferences['price_range'] = [
                'min' => $bookingHistory->min('room.price_per_night') * 0.8,
                'max' => $bookingHistory->max('room.price_per_night') * 1.2
            ];
            
            // Extract common amenities from liked rooms
            $allAmenities = $bookingHistory->pluck('room.amenities')->flatten()->countBy();
            $preferences['preferred_amenities'] = $allAmenities->sortDesc()->take(5)->keys()->toArray();
            
            $preferences['capacity_preference'] = round($bookingHistory->avg('guests'));
        }

        // Override with explicit preferences
        return array_merge($preferences, $explicitPreferences);
    }

    /**
     * Calculate collaborative filtering score
     */
    private function calculateCollaborativeScore(Room $room, Collection $similarUsers): float
    {
        $bookings = Booking::where('room_id', $room->id)
                          ->whereIn('user_id', $similarUsers->pluck('id'))
                          ->where('status', 'completed')
                          ->get();

        if ($bookings->isEmpty()) {
            return 0.0;
        }

        $avgRating = $bookings->avg('rating') ?? $room->rating;
        $bookingCount = $bookings->count();
        $popularityBoost = min(0.2, $bookingCount * 0.05);

        return min(1.0, ($avgRating / 5.0) + $popularityBoost);
    }

    /**
     * Calculate content-based score
     */
    private function calculateContentScore(Room $room, array $preferences): float
    {
        $score = 0.0;
        $maxScore = 1.0;

        // Type preference (30% weight)
        if (in_array($room->type, $preferences['preferred_types'])) {
            $score += 0.3;
        }

        // Location preference (25% weight)
        if (in_array($room->city, $preferences['preferred_cities'])) {
            $score += 0.25;
        }

        // Price range (20% weight)
        $priceScore = $this->calculatePriceScore($room->price_per_night, $preferences['price_range']);
        $score += $priceScore * 0.2;

        // Amenities match (15% weight)
        $amenitiesScore = $this->calculateAmenitiesScore($room->amenities ?? [], $preferences['preferred_amenities']);
        $score += $amenitiesScore * 0.15;

        // Rating factor (10% weight)
        $ratingScore = $room->rating / 5.0;
        $score += $ratingScore * 0.1;

        return min($maxScore, $score);
    }

    /**
     * Calculate trending score based on recent bookings
     */
    private function calculateTrendingScore(Room $room): float
    {
        $recentBookings = $room->bookings()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        $recentRating = $room->bookings()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->avg('rating') ?? $room->rating;

        $trendingFactor = min(0.3, $recentBookings * 0.02);
        $qualityFactor = $recentRating / 5.0 * 0.1;

        return $trendingFactor + $qualityFactor;
    }

    /**
     * Merge different recommendation types with weights
     */
    private function mergeRecommendations(array $recommendationsByType): Collection
    {
        $weights = [
            'collaborative' => 0.4,
            'content_based' => 0.4,
            'popular' => 0.2
        ];

        $merged = collect();
        
        foreach ($recommendationsByType as $type => $recommendations) {
            foreach ($recommendations as $rec) {
                $roomId = $rec['room']->id;
                $weightedScore = $rec['score'] * $weights[$type];
                
                if ($merged->has($roomId)) {
                    // Combine scores from different algorithms
                    $existing = $merged[$roomId];
                    $existing['score'] = ($existing['score'] + $weightedScore) / 2;
                    $existing['reasoning']['combined_methods'][] = $type;
                } else {
                    $rec['score'] = $weightedScore;
                    $rec['reasoning']['methods'] = [$type];
                    $merged[$roomId] = $rec;
                }
            }
        }

        return $merged->sortByDesc('score')->values();
    }

    /**
     * Store recommendations in database for analytics
     */
    private function storeRecommendations(User $user, Collection $recommendations): void
    {
        $recommendationData = $recommendations->take(20)->map(function($rec) use ($user) {
            return [
                'user_id' => $user->id,
                'room_id' => $rec['room']->id,
                'recommendation_score' => $rec['score'],
                'reasoning' => $rec['reasoning'],
                'user_preferences' => [], // Store current user preferences
                'recommendation_type' => $rec['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        // Clear old recommendations
        AiRecommendation::where('user_id', $user->id)
            ->where('created_at', '<', now()->subDays(7))
            ->delete();

        // Insert new recommendations
        AiRecommendation::insert($recommendationData);
    }

    /**
     * Helper methods
     */
    private function calculatePriceScore(float $roomPrice, array $priceRange): float
    {
        if ($roomPrice >= $priceRange['min'] && $roomPrice <= $priceRange['max']) {
            return 1.0;
        }
        
        $distance = min(
            abs($roomPrice - $priceRange['min']),
            abs($roomPrice - $priceRange['max'])
        );
        
        $maxDistance = $priceRange['max'] - $priceRange['min'];
        return max(0.0, 1.0 - ($distance / $maxDistance));
    }

    private function calculateAmenitiesScore(array $roomAmenities, array $preferredAmenities): float
    {
        if (empty($preferredAmenities)) {
            return 0.5;
        }

        $matches = array_intersect($roomAmenities, $preferredAmenities);
        return count($matches) / count($preferredAmenities);
    }

    private function getContentReasonForRoom(Room $room, array $preferences): string
    {
        if (in_array($room->type, $preferences['preferred_types'])) {
            return "Matches your preferred room type: {$room->type}";
        }
        
        if (in_array($room->city, $preferences['preferred_cities'])) {
            return "Located in {$room->city}, a city you've enjoyed before";
        }
        
        if ($room->rating >= 4.5) {
            return "Highly rated ({$room->rating}/5) with excellent reviews";
        }
        
        return "Great value and amenities for your preferences";
    }

    private function getMatchedFeatures(Room $room, array $preferences): array
    {
        $matched = [];
        
        if (in_array($room->type, $preferences['preferred_types'])) {
            $matched[] = "Room type: {$room->type}";
        }
        
        if (in_array($room->city, $preferences['preferred_cities'])) {
            $matched[] = "Location: {$room->city}";
        }
        
        $amenityMatches = array_intersect($room->amenities ?? [], $preferences['preferred_amenities']);
        foreach ($amenityMatches as $amenity) {
            $matched[] = "Amenity: {$amenity}";
        }
        
        return $matched;
    }
}
