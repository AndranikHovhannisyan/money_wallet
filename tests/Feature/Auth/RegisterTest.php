<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterView()
    {
        //Check registration page if unauthorized
        $response = $this->get('/register');

        $response->assertSuccessful();
        $response->assertViewIs('auth.register');

        //Check does redirect from /register to /wallet if authorized
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get('/register');
        $response->assertRedirect('/wallet');
    }


    public function testCorrectRegister()
    {
        Notification::fake();

        $user = [
            'name' => 'Joe Smith',
            'email' => 'joe.smith@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->post('/register', $user);

        $response->assertSuccessful();
        $response->assertViewIs('auth.verify');

        array_splice($user, 2, 2);
        $this->assertDatabaseHas('users', $user);

        $user = User::firstWhere('email', $user['email']);
        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
