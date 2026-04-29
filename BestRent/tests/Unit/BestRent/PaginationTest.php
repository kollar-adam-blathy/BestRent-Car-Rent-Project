<?php

namespace Tests\Unit\BestRent;

use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cars_page_uses_nine_items_per_page(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Car::create([
                'brand' => 'Brand' . $i,
                'model' => 'Model' . $i,
                'category' => 'Sedan',
                'year' => 2020,
                'plate_number' => 'CAR-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'daily_price' => 10000 + $i,
                'status' => 'available',
            ]);
        }

        Car::create([
            'brand' => 'Hidden',
            'model' => 'Maintenance',
            'category' => 'Sedan',
            'year' => 2020,
            'plate_number' => 'HID-001',
            'daily_price' => 10000,
            'status' => 'maintenance',
        ]);

        $response = $this->get('/cars');

        $response->assertOk();

        $cars = $response->viewData('cars');

        $this->assertInstanceOf(LengthAwarePaginator::class, $cars);
        $this->assertSame(9, $cars->perPage());
        $this->assertSame(15, $cars->total());
        $this->assertCount(9, $cars->items());

        $pageTwoResponse = $this->get('/cars?page=2');

        $pageTwoResponse->assertOk();

        $pageTwoCars = $pageTwoResponse->viewData('cars');

        $this->assertCount(6, $pageTwoCars->items());
    }

    public function test_admin_reservations_are_paginated_by_ten_items(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'phone_number' => '+36301111111',
        ]);

        $customer = User::factory()->create([
            'phone_number' => '+36302222222',
        ]);

        $car = Car::create([
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'category' => 'Sedan',
            'year' => 2022,
            'plate_number' => 'ADM-001',
            'daily_price' => 20000,
            'status' => 'available',
        ]);

        for ($i = 1; $i <= 23; $i++) {
            Reservation::create([
                'user_id' => $customer->id,
                'car_id' => $car->id,
                'start_date' => now()->addDays($i)->toDateString(),
                'end_date' => now()->addDays($i + 1)->toDateString(),
                'pickup_location' => 'Budapest',
                'dropoff_location' => 'Budapest',
                'total_price' => 20000,
                'status' => 'pending',
            ]);
        }

        $response = $this->actingAs($admin)->get('/admin/dashboard?tab=reservations');

        $response->assertOk();

        $reservations = $response->viewData('reservations');

        $this->assertInstanceOf(LengthAwarePaginator::class, $reservations);
        $this->assertSame(10, $reservations->perPage());
        $this->assertSame(23, $reservations->total());
        $this->assertCount(10, $reservations->items());

        $pageThreeResponse = $this->actingAs($admin)->get('/admin/dashboard?tab=reservations&reservations_page=3');

        $pageThreeResponse->assertOk();

        $pageThreeReservations = $pageThreeResponse->viewData('reservations');

        $this->assertCount(3, $pageThreeReservations->items());
    }
}
