<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use App\Http\Middleware\DisableBackButton;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    /**
     * Login user
     *
     * @param  App\Http\Requests
     * @return Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $credentials['name'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Check if user is Admin, Room Coordinator or Department head, redirect accordingly if not, if valid login creds. 
            if ($user->college === 'ADMIN' && $user->department === 'ADMIN') {
                Auth::login($user);
                return redirect()->route('dashboard.adminIndex'); 
            } else if ($user->college === 'ROOM COORDINATOR' && $user->department === 'ROOM COORDINATOR') {
                Auth::login($user);
                return redirect()->route('dashboard.roomCoordIndex');
            } else {
                Auth::login($user);
                return redirect()->route('dashboard.index');
            }
        } else {
            // Password is incorrect
            throw ValidationException::withMessages([
                'login' => 'Invalid username or password',
            ]);
        }

    }

    /**
     * Log out the user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
