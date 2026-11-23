@extends('layouts.app', ['title' => 'کد تأیید'])
@section('content')
<div class="center-box">
  <p>کد ۵ رقمی به <b>{{ $phone }}</b> ارسال شد (اعتبار ۲ دقیقه).</p>
  <form method="post" action="{{ route('login.verify') }}">@csrf
    <div class="field"><label>کد تأیید</label><input name="code" inputmode="numeric" maxlength="5" required autofocus autocomplete="one-time-code"></div>
    @error('code')<p style="color:#c0392b">{{ $message }}</p>@enderror
    <button class="btn" style="width:100%">ورود</button>
  </form>
  <p class="note"><a href="{{ route('login') }}">تغییر شماره / ارسال مجدد</a></p>
</div>
@endsection
