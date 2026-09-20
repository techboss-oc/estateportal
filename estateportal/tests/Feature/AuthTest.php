<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create(['role' => 'admin', 'password' => bcrypt('password')]);
        
        $response = $this->post('/login', [
            'login_id' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_customer_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create(['role' => 'customer', 'password' => bcrypt('password')]);
        
        $response = $this->post('/login', [
            'login_id' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('customer.dashboard'));
    }
}
