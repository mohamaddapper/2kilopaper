@extends('layouts.app', ['title' => 'دایرکتوری شرکت‌ها'])
@section('content')
<h1>دایرکتوری شرکت‌ها</h1>
<form class="search" method="get" style="margin:0 0 20px;max-width:none">
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
