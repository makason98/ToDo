<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\EmailVerificationCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EmailVerificationCodeController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-code');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        $verification = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $verification) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $user->markEmailAsVerified();

        EmailVerificationCode::where('user_id', $user->id)->delete();

        return redirect()->route('dashboard');
    }

    public function resend(): RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        self::sendCode($user);

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    public static function sendCode($user): void
    {
        EmailVerificationCode::where('user_id', $user->id)->delete();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new VerificationCodeMail($code));
    }
}
