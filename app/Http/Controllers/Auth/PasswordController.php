<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm($token)
    {
        $recordToken = DB::table('password_reset_tokens')->where('email', request()->email)->first();

        if (!$recordToken) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
        }

        if (!Hash::check($token, $recordToken->token)) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
        }

        if (Carbon::parse($recordToken->created_at)->addMinutes(30)->isPast()) {
            return redirect()->route('password.request')->withErrors(['email' => 'Token sudah kadaluarsa silahkan kirim ulang kembali!']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $recordToken->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
