<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

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
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended(route('home'))->with('success', 'Berhasil masuk dengan akun Google.');
            } else {
                // --- LOGIKA BARU UNTUK MENYIMPAN AVATAR ---
                $avatarPath = null;
                if ($user->getAvatar()) {
                    // Ambil konten gambar dari URL Google
                    $avatarContents = Http::get($user->getAvatar())->body();
                    // Buat nama file yang unik
                    $avatarName = 'avatars/' . Str::random(40) . '.jpg';
                    // Simpan gambar ke public storage
                    Storage::disk('public')->put($avatarName, $avatarContents);
                    $avatarPath = $avatarName;
                }


                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'avatar' => $avatarPath,
                    'email_verified_at' => now(),
                ]);

                Auth::login($newUser);

                // Redirect setelah login berhasil
                return redirect()->intended(route('home'))->with('success', 'Berhasil masuk dengan akun Google.');
            }
        } catch (\Throwable $th) {
            if (env('APP_ENV') === 'local') {
                dd($th->getMessage());
            } else {
                return redirect()->route('login')->with('error', 'Something went wrong while logging in with Google.');
            }
        }
    }
}
