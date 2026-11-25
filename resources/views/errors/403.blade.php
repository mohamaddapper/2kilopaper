@extends('layouts.app', ['title' => 'عدم دسترسی'])

@section('content')
<div class="container" style="max-width:560px;margin:60px auto;text-align:center">
  <div class="card" style="padding:36px 28px">
    <div style="font-size:48px;font-weight:900;color:#dc2626;margin-bottom:8px">403</div>
    <h1 style="margin:0 0 10px;font-size:20px;color:var(--text)">دسترسی به این بخش مجاز نیست</h1>
    <p style="margin:0 0 22px;color:var(--muted);font-size:14px;line-height:1.8">
      {{ $exception->getMessage() ?: 'شما مجوز لازم برای مشاهده یا انجام این عملیات را ندارید.' }}
    </p>
    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
      @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="btn">بازگشت به پیشخوان مدیریت</a>
      @endif
      <a href="{{ route('home') }}" class="btn ghost">صفحه اصلی سایت</a>
    </div>
  </div>
</div>
@endsection
