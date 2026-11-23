<?php
namespace App\Services;

use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class OtpService
{
    const TTL = 120;
    const MAX_ATTEMPTS = 5;

    /** @return string|null پیام خطا؛ null یعنی موفق */
    public function send(string $phone, string $ip): ?string
    {
        if (RateLimiter::tooManyAttempts("otp-phone-min:$phone", 1)) {
            return 'لطفاً ۶۰ ثانیه صبر کنید و دوباره تلاش کنید.';
        }
        if (RateLimiter::tooManyAttempts("otp-phone-hour:$phone", 5) || RateLimiter::tooManyAttempts("otp-ip:$ip", 20)) {
            return 'تعداد درخواست‌ها زیاد است. بعداً تلاش کنید.';
        }
        RateLimiter::hit("otp-phone-min:$phone", 60);
        RateLimiter::hit("otp-phone-hour:$phone", 3600);
        RateLimiter::hit("otp-ip:$ip", 3600);

        $code = (string) random_int(10000, 99999);
        Otp::where('phone', $phone)->whereNull('used_at')->update(['used_at' => now()]);
        Otp::create([
            'phone' => $phone,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addSeconds(self::TTL),
        ]);

        return $this->deliver($phone, $code) ? null : 'ارسال پیامک ناموفق بود. دوباره تلاش کنید.';
    }

    public function verify(string $phone, string $code): bool
    {
        $otp = Otp::where('phone', $phone)->whereNull('used_at')->latest('id')->first();
        if (!$otp || $otp->expires_at->isPast() || $otp->attempts >= self::MAX_ATTEMPTS) return false;
        $otp->increment('attempts');
        if (!Hash::check($code, $otp->code_hash)) return false;
        $otp->update(['used_at' => now()]);
        return true;
    }

    private function deliver(string $phone, string $code): bool
    {
        $c = config('gap.sms');
        if ($c['driver'] === 'log') {
            Log::info("OTP for $phone: $code");
            return true;
        }
        try {
            $res = Http::asForm()->timeout(10)->post('https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber', [
                'username' => $c['username'],
                'password' => $c['password'],
                'text' => $code,
                'to' => $phone,
                'bodyId' => $c['otp_body_id'],
            ]);
            $ok = $res->successful() && ((int) $res->json('RetStatus') === 1);
            if (!$ok) Log::warning('Melipayamak failed', ['body' => $res->body()]);
            return $ok;
        } catch (\Throwable $e) {
            Log::error('Melipayamak error: ' . $e->getMessage());
            return false;
        }
    }
}
