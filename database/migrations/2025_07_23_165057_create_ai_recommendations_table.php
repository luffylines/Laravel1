<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->decimal('recommendation_score', 5, 4); // 0.0000 to 1.0000
            $table->json('reasoning'); // AI reasoning for recommendation
            $table->json('user_preferences'); // User preferences used
            $table->enum('recommendation_type', ['collaborative', 'content_based', 'hybrid', 'popular']);
            $table->boolean('is_clicked')->default(false);
            $table->boolean('is_booked')->default(false);
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('booked_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'recommendation_score']);
            $table->index(['room_id', 'recommendation_score']);
            $table->index(['recommendation_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_recommendations');
    }
};
