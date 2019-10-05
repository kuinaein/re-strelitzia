<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Log;
use Request;
use Socialite;
use URL;

class LoginController extends Controller
{
    private const REDIRECT_SESSION_KEY = 'redirect_to';

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Googleの認証ページヘユーザーをリダイレクト
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        Log::info('ログイン施行');
        Request::session()->flash(self::REDIRECT_SESSION_KEY, URL::previous());
        return Socialite::driver('google')->redirect();
    }

    /**
     * Googleログイン後のコールバック
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback()
    {
        $email = Socialite::driver('google')->user()->getEmail();
        if (env('LOGIN_USER') !== $email) {
            Log::warning('ログイン失敗', ['email' => $email]);
            abort(403);
            throw new Exception('到達不能コード');
        }
        /** @var \Illuminate\Contracts\Auth\Authenticatable */
        $user = \App\User::firstOrCreate(['email' => $email], ['name' => $email, 'password' => '']);
        \Auth::login($user);
        Log::info('ログイン', ['email' => $email]);
        return \Response::redirectTo(session(self::REDIRECT_SESSION_KEY));
    }
}
