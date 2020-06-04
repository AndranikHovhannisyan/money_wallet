<?php

namespace Tests\Feature;

use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    protected function signIn()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        return $this;
    }

    public function testWalletList()
    {
        $this->signIn();
        $response = $this->get('/wallet');

        $response->assertSuccessful();
        $response->assertViewIs('wallet.index');
    }

    public function testCreateWalletFormView()
    {
        $this->signIn();
        $response = $this->get('/wallet/create');

        $response->assertSuccessful();
        $response->assertViewIs('wallet.form');
    }

    public function testCreateAndDestroyWallet()
    {
        $this->signIn();

        //Test wallet create functionality
        $response = $this->post('/wallet', $wallet = [
            'name' => 'XXX Bank',
            'type' => 'Cash ZZZ',
        ]);

        $response->assertRedirect('/wallet');
        $this->assertDatabaseHas('wallets', $wallet);


        //Test wallet destroy functionality
        $walletObj = Wallet::firstWhere('name', $wallet['name']);
        $response = $this->get("/wallet/{$walletObj->id}/destroy");

        $response->assertRedirect('/wallet');
        $this->assertDatabaseMissing('wallets', $wallet);
    }
}
