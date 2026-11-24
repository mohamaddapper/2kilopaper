@extends('layouts.app', ['title' => request('featured') ? 'شرکت‌های ویژه' : 'دایرکتوری شرکت‌ها'])
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:12px">
  <h1 style="margin:0;font-size:24px;font-weight:900;display:flex;align-items:center;gap:8px">
    @if(request('featured'))
      <span style="color:#f59e0b">★</span>
      <span>شرکت‌های ویژه</span>
    @else
      <span>دایرکتوری شرکت‌ها</span>
    @endif
  </h1>
  @if(request('featured'))
    <a href="{{ route('directory') }}" style="font-size:13.5px;color:var(--muted);text-decoration:none">
      ← مشاهده همه شرکت‌های دایرکتوری
    </a>
  @else
    <a href="{{ route('directory', ['featured' => 1]) }}" style="font-size:13.5px;color:#d97706;font-weight:700;text-decoration:none">
      ★ مشاهده شرکت‌های ویژه
    </a>
  @endif
</div>

<form class="search" method="get" style="margin:0 0 20px;max-width:none">
  @if(request('featured'))
    <input type="hidden" name="featured" value="1">
  @endif
  <input name="q" value="{{ request('q') }}" placeholder="جستجو..." style="border:1px solid var(--border)">
  <select name="cat" style="border:1px solid var(--border)"><option value="">همه دسته‌ها</option>
    @foreach($categories as $c)
      <option value="{{ $c->id }}" @selected(request('cat')==$c->id)>{{ $c->name }}</option>
      @foreach($c->children as $ch)<option value="{{ $ch->id }}" @selected(request('cat')==$ch->id)>— {{ $ch->name }}</option>@endforeach
    @endforeach
  </select>
  <select name="city" style="border:1px solid var(--border)"><option value="">همه شهرها</option>
    @foreach($cities as $c)<option value="{{ $c->id }}" @selected(request('city')==$c->id)>{{ $c->name }}</option>@endforeach
  </select>
  <button class="btn">اعمال</button>
</form>
<div class="grid">@forelse($companies as $company)@include('partials.card')@empty<p>شرکتی یافت نشد.</p>@endforelse</div>
<div style="margin-top:20px">{{ $companies->links() }}</div>
@endsection
