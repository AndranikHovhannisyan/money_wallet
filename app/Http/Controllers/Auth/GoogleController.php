<?php
/**
 * Created by PhpStorm.
 * User: andranik
 * Date: 6/3/20
 * Time: 10:50 AM
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use Auth;
use Exception;
use App\User;

class GoogleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleGoogleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if(!$user){
                $user = User::where('email', $googleUser->email)->first();

                if ($user){
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
                else {
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => encrypt(uniqid()),
                        'email_verified_at' => new \DateTime()
                    ]);
                }
            }

            Auth::login($user);

            return redirect()->route("wallet.index");

        } catch (Exception $e) {
            return redirect()->route("wallet.index");
        }
    }
}
