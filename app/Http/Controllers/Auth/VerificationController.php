<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function resend(Request $request)
    {
        $unverifiedEmail = Session::get('unverified_email');
        $user = User::where('email', $unverifiedEmail)->first();

        if (!$user || $user->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect($this->redirectPath());
        }

        $user->sendEmailVerificationNotification();

        return $request->wantsJson()
            ? new Response('', 202)
            : redirect('login');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));
        if (!$user) {
            throw new AuthorizationException;
        }

        if (!hash_equals((string)$request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new Response('', 204)
                : redirect($this->redirectPath());
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        Auth::login($user);

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect($this->redirectPath())->with('verified', true);
    }
}
