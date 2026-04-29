<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_completed_reservation_creates_pending_payment_for_customer(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $car = Car::create([
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'category' => 'Sedan',
            'year' => 2022,
            'plate_number' => 'ABC-123',
            'daily_price' => 20000,
            'status' => 'available',
        ]);
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'pickup_location' => 'Budapest',
            'dropoff_location' => 'Budapest',
            'total_price' => 40000,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($admin)->patch('/admin/reservations/' . $reservation->id, [
            'status' => 'completed',
        ]);

        $response->assertRedirect('/admin/dashboard');

        $this->assertDatabaseHas('payments', [
            'reservation_id' => $reservation->id,
            'user_id' => $user->id,
            'amount' => '40000.00',
            'status' => 'pending',
            'method' => 'card',
        ]);
    }

    public function test_customer_can_complete_pending_demo_payment(): void
    {
        $user = User::factory()->create();
        $payment = Payment::create([
            'reservation_id' => Reservation::create([
                'user_id' => $user->id,
                'car_id' => Car::create([
                    'brand' => 'Ford',
                    'model' => 'Focus',
                    'category' => 'Hatchback',
                    'year' => 2021,
                    'plate_number' => 'XYZ-987',
                    'daily_price' => 15000,
                    'status' => 'available',
                ])->id,
                'start_date' => now()->addDay()->toDateString(),
                'end_date' => now()->addDays(3)->toDateString(),
                'pickup_location' => 'Győr',
                'dropoff_location' => 'Győr',
                'total_price' => 45000,
                'status' => 'completed',
            ])->id,
            'user_id' => $user->id,
            'amount' => 45000,
            'method' => 'card',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->post('/payments/' . $payment->id . '/pay');

        $response->assertRedirect('/client/dashboard');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'paid',
            'method' => 'card',
            'transaction_id' => 'DEMO-' . $payment->id,
        ]);
        $this->assertNotNull($payment->fresh()->paid_at);
    }

    public function test_admin_can_mark_reservation_as_active_rental(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();
        $car = Car::create([
            'brand' => 'Skoda',
            'model' => 'Octavia',
            'category' => 'Kombi',
            'year' => 2023,
            'plate_number' => 'ACT-555',
            'daily_price' => 18000,
            'status' => 'available',
        ]);
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'pickup_location' => 'Pécs',
            'dropoff_location' => 'Pécs',
            'total_price' => 36000,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($admin)->patch('/admin/reservations/' . $reservation->id, [
            'status' => 'active',
        ]);

        $response->assertRedirect('/admin/dashboard');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'active',
        ]);
        $this->assertDatabaseMissing('payments', [
            'reservation_id' => $reservation->id,
        ]);
    }
}