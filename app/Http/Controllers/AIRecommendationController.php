<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIRecommendationService;
use App\Models\AiRecommendation;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class AIRecommendationController extends Controller
{
    protected $aiService;

    public function __construct(AIRecommendationService $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware('auth');
    }

    /**
     * Get AI-powered room recommendations for the authenticated user
     */
    public function getRecommendations(Request $request)
    {
        $user = Auth::user();
        
        // Get user preferences from request
        $preferences = $request->only([
            'preferred_types',
            'preferred_cities', 
            'price_range',
            'capacity_preference',
            'preferred_amenities'
        ]);

        // Generate fresh recommendations
        $recommendations = $this->aiService->generateRecommendations($user, $preferences);

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations->map(function($rec) {
                return [
                    'room' => $rec['room'],
                    'score' => round($rec['score'], 3),
                    'score_percentage' => round($rec['score'] * 100, 1) . '%',
                    'type' => $rec['type'],
                    'reasoning' => $rec['reasoning'],
                    'formatted_price' => '$' . number_format($rec['room']->price_per_night, 2)
                ];
            })
        ]);
    }

    /**
     * Get stored recommendations from database
     */
    public function getStoredRecommendations(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);

        $recommendations = AiRecommendation::with('room')
            ->where('user_id', $user->id)
            ->orderByDesc('recommendation_score')
            ->orderByDesc('created_at')
            ->take($limit)
            ->get();

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations->map(function($rec) {
                return [
                    'id' => $rec->id,
                    'room' => $rec->room,
                    'score' => $rec->recommendation_score,
                    'score_percentage' => $rec->formatted_score,
                    'type' => $rec->recommendation_type,
                    'reasoning' => $rec->reasoning,
                    'is_clicked' => $rec->is_clicked,
                    'is_booked' => $rec->is_booked,
                    'main_reason' => $rec->main_reason,
                    'created_at' => $rec->created_at->format('Y-m-d H:i:s')
                ];
            })
        ]);
    }

    /**
     * Mark a recommendation as clicked (for analytics)
     */
    public function markAsClicked(Request $request, $recommendationId)
    {
        $user = Auth::user();
        
        $recommendation = AiRecommendation::where('id', $recommendationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $recommendation->markAsClicked();

        return response()->json([
            'success' => true,
            'message' => 'Recommendation marked as clicked'
        ]);
    }

    /**
     * Mark a recommendation as booked (for analytics)
     */
    public function markAsBooked(Request $request, $recommendationId)
    {
        $user = Auth::user();
        
        $recommendation = AiRecommendation::where('id', $recommendationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $recommendation->markAsBooked();

        return response()->json([
            'success' => true,
            'message' => 'Recommendation marked as booked'
        ]);
    }

    /**
     * Get recommendation analytics for admin
     */
    public function getAnalytics(Request $request)
    {
        // Only admin can access analytics
        if (!Auth::user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $analytics = [
            'total_recommendations' => AiRecommendation::count(),
            'clicked_recommendations' => AiRecommendation::where('is_clicked', true)->count(),
            'booked_recommendations' => AiRecommendation::where('is_booked', true)->count(),
            'click_through_rate' => 0,
            'conversion_rate' => 0,
            'by_type' => AiRecommendation::selectRaw('recommendation_type, count(*) as count')
                ->groupBy('recommendation_type')
                ->get()
                ->pluck('count', 'recommendation_type'),
            'top_performing_rooms' => AiRecommendation::with('room')
                ->selectRaw('room_id, count(*) as recommendation_count, avg(recommendation_score) as avg_score')
                ->where('is_clicked', true)
                ->groupBy('room_id')
                ->orderByDesc('recommendation_count')
                ->take(10)
                ->get(),
            'user_engagement' => AiRecommendation::selectRaw('user_id, count(*) as total_recs, sum(case when is_clicked then 1 else 0 end) as clicked_recs')
                ->groupBy('user_id')
                ->having('total_recs', '>', 5)
                ->orderByDesc('clicked_recs')
                ->take(10)
                ->get()
        ];

        // Calculate rates
        if ($analytics['total_recommendations'] > 0) {
            $analytics['click_through_rate'] = round(
                ($analytics['clicked_recommendations'] / $analytics['total_recommendations']) * 100, 
                2
            );
        }

        if ($analytics['clicked_recommendations'] > 0) {
            $analytics['conversion_rate'] = round(
                ($analytics['booked_recommendations'] / $analytics['clicked_recommendations']) * 100, 
                2
            );
        }

        return response()->json([
            'success' => true,
            'analytics' => $analytics
        ]);
    }

    /**
     * Get personalized room suggestions based on search criteria
     */
    public function getPersonalizedSearch(Request $request)
    {
        $user = Auth::user();
        
        $searchCriteria = $request->only([
            'city',
            'check_in',
            'check_out', 
            'guests',
            'min_price',
            'max_price',
            'room_type',
            'amenities'
        ]);

        // Start with basic search
        $query = Room::available();

        if ($searchCriteria['city']) {
            $query->inCity($searchCriteria['city']);
        }

        if ($searchCriteria['min_price'] && $searchCriteria['max_price']) {
            $query->priceRange($searchCriteria['min_price'], $searchCriteria['max_price']);
        }

        if ($searchCriteria['room_type']) {
            $query->byType($searchCriteria['room_type']);
        }

        if ($searchCriteria['guests']) {
            $query->where('capacity', '>=', $searchCriteria['guests']);
        }

        // Get base results
        $rooms = $query->get();

        // Apply AI-powered personalization
        $personalizedResults = $rooms->map(function($room) use ($user) {
            // Get or calculate recommendation score for this room
            $existingRec = AiRecommendation::where('user_id', $user->id)
                ->where('room_id', $room->id)
                ->first();

            $personalizedScore = $existingRec 
                ? $existingRec->recommendation_score 
                : $this->calculateQuickPersonalizationScore($room, $user);

            return [
                'room' => $room,
                'personalization_score' => $personalizedScore,
                'is_recommended' => $personalizedScore > 0.6,
                'recommendation_reason' => $this->getQuickRecommendationReason($room, $user)
            ];
        })->sortByDesc('personalization_score');

        return response()->json([
            'success' => true,
            'results' => $personalizedResults->values(),
            'total_found' => $personalizedResults->count(),
            'personalized' => true
        ]);
    }

    /**
     * Quick personalization score calculation for search results
     */
    private function calculateQuickPersonalizationScore(Room $room, $user): float
    {
        $score = 0.5; // Base score

        // Check user's booking history
        $userBookings = $user->bookings()->with('room')->where('rating', '>=', 4)->get();
        
        if ($userBookings->isNotEmpty()) {
            // Prefer similar room types
            $preferredTypes = $userBookings->pluck('room.type')->unique();
            if ($preferredTypes->contains($room->type)) {
                $score += 0.2;
            }

            // Prefer similar cities
            $preferredCities = $userBookings->pluck('room.city')->unique();
            if ($preferredCities->contains($room->city)) {
                $score += 0.15;
            }

            // Prefer similar price range
            $avgPrice = $userBookings->avg('room.price_per_night');
            $priceDiff = abs($room->price_per_night - $avgPrice) / $avgPrice;
            if ($priceDiff < 0.3) { // Within 30% of average
                $score += 0.1;
            }
        }

        // Rating boost
        if ($room->rating >= 4.5) {
            $score += 0.1;
        }

        // Featured boost
        if ($room->is_featured) {
            $score += 0.05;
        }

        return min(1.0, $score);
    }

    /**
     * Get quick recommendation reason
     */
    private function getQuickRecommendationReason(Room $room, $user): string
    {
        $userBookings = $user->bookings()->with('room')->where('rating', '>=', 4)->get();
        
        if ($userBookings->isEmpty()) {
            return $room->rating >= 4.5 ? 'Highly rated by other guests' : 'Popular choice';
        }

        $preferredTypes = $userBookings->pluck('room.type')->unique();
        if ($preferredTypes->contains($room->type)) {
            return "Matches your preferred {$room->type} room type";
        }

        $preferredCities = $userBookings->pluck('room.city')->unique();
        if ($preferredCities->contains($room->city)) {
            return "You've enjoyed staying in {$room->city} before";
        }

        return 'Recommended based on your preferences';
    }
}
