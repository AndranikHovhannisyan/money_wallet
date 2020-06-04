<?php

namespace Tests\Feature;

use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecordTest extends TestCase
{
    use RefreshDatabase;

    protected function signInAndCreateWallet()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $wallet = factory(Wallet::class)->create([
            'user_id' => $user->id
        ]);

        return $wallet;
    }

    public function testRecordFormView()
    {
        $wallet = $this->signInAndCreateWallet();

        $response = $this->get("record/{$wallet->id}");
        $response->assertSuccessful();
        $response->assertViewIs('record.index');
    }

    public function testRecordAndWalletBalance()
    {
        $wallet = $this->signInAndCreateWallet();
        $url = "record/{$wallet->id}";


        $this->from($url)->post($url, [
            'amount' => 1500,
            'isCredit' => 'on'
        ]);

        $response = $this->from($url)->post($url, [
            'amount' => 400
        ]);

        $response->assertRedirect($url);

        $response = $this->get($url);
        $response->assertSeeText("1500");
        $response->assertSeeText("400");
        $response->assertSeeText("1100");

        $response = $this->get("/wallet");
        $response->assertSeeText("Credit Card");
        $response->assertSeeText("Cash");
        $response->assertSeeText("1100");
    }
}
