<?php

namespace Tests\Unit\BestRent;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationAndProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_rejects_invalid_phone_number_format(): void
    {
        $response = $this->post('/register', [
            'name' => 'Teszt Elek',
            'email' => 'teszt@example.com',
            'phone_number' => '12345',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['phone_number']);
        $this->assertGuest();
    }

    public function test_registration_accepts_valid_phone_number_format(): void
    {
        $response = $this->post('/register', [
            'name' => 'Teszt Elek',
            'email' => 'teszt@example.com',
            'phone_number' => '+36201234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'teszt@example.com',
            'phone_number' => '+36201234567',
        ]);
    }

    public function test_user_can_update_own_profile_data(): void
    {
        $user = User::factory()->create([
            'phone_number' => '+36303333333',
        ]);

        $response = $this->actingAs($user)->patch('/client/profile', [
            'name' => 'Frissitett Nev',
            'email' => 'frissitett@example.com',
            'phone_number' => '06301234567',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect('/client/profile');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Frissitett Nev',
            'email' => 'frissitett@example.com',
            'phone_number' => '06301234567',
        ]);
    }

    public function test_user_can_delete_own_account_and_is_redirected_to_home(): void
    {
        $user = User::factory()->create([
            'phone_number' => '+36304444444',
        ]);

        $response = $this->actingAs($user)->delete('/client/profile');

        $response->assertRedirect('/');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
