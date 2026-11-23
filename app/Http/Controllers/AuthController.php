<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show() { return view('auth.login'); }

    public function send(Request $r, OtpService $otp)
    {
        $phone = normalize_phone((string) $r->input('phone'));
        if (!preg_match('/^09\d{9}$/', $phone)) {
            return back()->withErrors(['phone' => 'شماره موبایل معتبر نیست (مثال: 09123456789)'])->withInput();
        }
        if ($err = $otp->send($phone, $r->ip())) {
            return back()->withErrors(['phone' => $err])->withInput();
        }
        session(['otp_phone' => $phone]);
        return redirect()->route('login.code');
    }

    public function codeForm()
    {
        abort_unless(session('otp_phone'), 404);
        return view('auth.code', ['phone' => session('otp_phone')]);
    }

    public function verify(Request $r, OtpService $otp)
    {
        $phone = session('otp_phone');
        abort_unless($phone, 404);
        $code = normalize_phone((string) $r->input('code'));
        if (!$otp->verify($phone, $code)) {
            return back()->withErrors(['code' => 'کد نادرست یا منقضی شده است.']);
        }
        $user = User::firstOrCreate(['phone' => $phone]);
        $r->session()->forget('otp_phone');
        $r->session()->regenerate();
        Auth::login($user, true);
        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect('/');
    }
}
