<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Log;
use Request;
use Response;
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
        $prevUrl = URL::previous();
        Log::info('ログイン試行');
        if (config('services.google.client_id')) {
            Request::session()->flash(self::REDIRECT_SESSION_KEY, $prevUrl);
            return Socialite::driver('google')->redirect();
        } else {
            return $this->doLogin('master', $prevUrl);
        }
    }

    private function doLogin(string $email, string $prevUrl)
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable */
        $user = User::firstOrCreate(['email' => $email], ['name' => $email, 'password' => '']);
        Auth::login($user);
        Log::notice('ログイン', ['email' => $email]);
        return Response::redirectTo($prevUrl);
    }

    /**
     * Googleログイン後のコールバック
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback()
    {
        $email = Socialite::driver('google')->user()->getEmail();
        if (env('LOGIN_EMAIL') !== $email) {
            Log::warning('ログイン失敗', ['email' => $email]);
            abort(403);
            throw new Exception('到達不能コード');
        }
        return $this->doLogin($email, session(self::REDIRECT_SESSION_KEY));
    }
}
