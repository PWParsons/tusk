<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{
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
    protected string $redirectTo = RouteServiceProvider::HOME;

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
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    protected function validateLogin(Request $request): void
    {
        $attributes = [
            $this->username() => ['required', 'string'],
            'password' => ['required', 'string'],
        ];

        if ($request->wantsJson()) {
            $attributes = array_merge([
                'device_name' => ['required', 'string'],
            ], $attributes);
        }

        $request->validate($attributes);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        if ($request->wantsJson()) {
            return new Response([
                'token' => $request->user()->createToken($request->device_name)->plainTextToken,
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath());
    }
}
