<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\User;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user as default owner
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            $this->command->error('Admin user not found. Please run AdminSeeder first.');
            return;
        }

        $rooms = [
            [
                'name' => 'Luxury Downtown Apartment',
                'description' => 'Beautiful luxury apartment in the heart of downtown. Features modern amenities, stunning city views, and premium furnishings. Perfect for business travelers and couples seeking elegance.',
                'type' => 'apartment',
                'price_per_night' => 150.00,
                'capacity' => 4,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area_sqm' => 85.5,
                'amenities' => ['WiFi', 'Air Conditioning', 'TV', 'Kitchen', 'Parking', 'Balcony'],
                'images' => ['/images/rooms/luxury-apt-1.jpg', '/images/rooms/luxury-apt-2.jpg'],
                'address' => '123 Main Street',
                'city' => 'New York',
                'country' => 'USA',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'is_featured' => true,
                'rating' => 4.8,
                'total_reviews' => 45,
            ],
            [
                'name' => 'Cozy Studio Near Beach',
                'description' => 'Charming studio apartment just minutes from the beach. Enjoy ocean breezes and easy access to restaurants and nightlife. Ideal for solo travelers or couples.',
                'type' => 'studio',
                'price_per_night' => 85.00,
                'capacity' => 2,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'area_sqm' => 45.0,
                'amenities' => ['WiFi', 'Air Conditioning', 'TV', 'Kitchen', 'Beach Access'],
                'images' => ['/images/rooms/beach-studio-1.jpg'],
                'address' => '456 Ocean Drive',
                'city' => 'Miami',
                'country' => 'USA',
                'latitude' => 25.7617,
                'longitude' => -80.1918,
                'is_featured' => false,
                'rating' => 4.5,
                'total_reviews' => 28,
            ],
            [
                'name' => 'Modern Suite with City View',
                'description' => 'Spacious modern suite featuring panoramic city views, luxury bedding, and a fully equipped kitchen. Business center and concierge services available.',
                'type' => 'suite',
                'price_per_night' => 220.00,
                'capacity' => 6,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqm' => 120.0,
                'amenities' => ['WiFi', 'Air Conditioning', 'TV', 'Kitchen', 'Parking', 'Gym', 'Concierge'],
                'images' => ['/images/rooms/modern-suite-1.jpg', '/images/rooms/modern-suite-2.jpg'],
                'address' => '789 Business District',
                'city' => 'Chicago',
                'country' => 'USA',
                'latitude' => 41.8781,
                'longitude' => -87.6298,
                'is_featured' => true,
                'rating' => 4.9,
                'total_reviews' => 67,
            ],
            [
                'name' => 'Luxury Villa with Pool',
                'description' => 'Stunning luxury villa featuring a private pool, garden, and multiple bedrooms. Perfect for families or groups seeking privacy and luxury amenities.',
                'type' => 'villa',
                'price_per_night' => 350.00,
                'capacity' => 10,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'area_sqm' => 250.0,
                'amenities' => ['WiFi', 'Air Conditioning', 'TV', 'Kitchen', 'Parking', 'Pool', 'Garden', 'Terrace', 'Hot Tub'],
                'images' => ['/images/rooms/luxury-villa-1.jpg', '/images/rooms/luxury-villa-2.jpg'],
                'address' => '555 Luxury Lane',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'is_featured' => true,
                'rating' => 5.0,
                'total_reviews' => 89,
            ]
        ];

        foreach ($rooms as $roomData) {
            Room::create(array_merge($roomData, ['owner_id' => $admin->id]));
        }

        $this->command->info('Sample rooms created successfully!');
    }
}
