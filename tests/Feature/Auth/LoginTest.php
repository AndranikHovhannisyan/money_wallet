<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginView()
    {
        //Check login page if unauthorized
        $response = $this->get('/login');

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');

        //Check does redirect from /login to /wallet if authorized
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/wallet');
    }

    public function testLoginCorrectCredentials()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'correct_password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/wallet');
        $this->assertAuthenticatedAs($user);
    }

    public function testLoginIncorrectCredentials()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'correct_password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }


    public function testLoginWithoutVerifyEmail()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'correct_password'),
            'email_verified_at' => null
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertSuccessful();
        $response->assertViewIs('auth.verify');
    }
}
