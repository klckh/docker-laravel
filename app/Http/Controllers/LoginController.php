<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     * Adapted from Laravel docs
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Login successful
            return $this->buildResponse(self::CODE_SUCCESS, message: 'Login successful');
        }

        // Email or password was incorrect
        return $this->buildResponse(self::CODE_ERROR, message: 'Incorrect credentials');
    }
}
