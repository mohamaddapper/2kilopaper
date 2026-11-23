@extends('layouts.app', ['title' => 'ورود'])
@section('content')
<div class="center-box">
  <h2 style="margin-top:0">ورود با شماره موبایل</h2>
  <form method="post" action="{{ route('login.send') }}">@csrf
    <div class="field"><label>شماره موبایل</label>
      <input name="phone" value="{{ old('phone') }}" inputmode="numeric" placeholder="09123456789" required autofocus></div>
    @error('phone')<p style="color:#c0392b">{{ $message }}</p>@enderror
    <button class="btn" style="width:100%">ارسال کد تأیید</button>
  </form>
</div>
@endsection
