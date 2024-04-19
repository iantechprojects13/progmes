<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Inertia\Inertia;
use Throwable;
use Auth;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function callback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $current_user = User::where('google_id', $user->id)->first();

            if ($current_user) {
                Auth::login($current_user);
            } else {
                $newUser = User::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'google_id' => $user->id,
                        'name' => $user->name,
                        'password' => encrypt('accountpassword'),
                        'avatar' => $user->avatar,
                    ]
                );
                Auth::login($newUser);
            }

            return redirect()->route('register.select');

        } catch (Throwable $thr) {
            return redirect()->back();
        }
    }
}
