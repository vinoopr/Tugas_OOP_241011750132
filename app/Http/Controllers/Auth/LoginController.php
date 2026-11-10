<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Autentikasi, pembatasan percobaan login, dan regenerasi sesi dari Laravel UI.
    use AuthenticatesUsers;

    protected $redirectTo = '/admin';

    protected function credentials(Request $request)
    {
        return $request->only('email', 'password') + ['is_admin' => true];
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }
}
