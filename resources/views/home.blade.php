@extends('layouts.app')
@section('hero')
<section class="hero"><div class="container">
  <h1>دایرکتوری کاغذ، چاپ و بسته‌بندی ایران</h1>
  <p>شرکت، تأمین‌کننده و نیروی متخصص را پیدا کنید</p>
  <form class="search" action="{{ route('directory') }}">
    <input name="q" placeholder="نام شرکت یا محصول...">
    <button class="btn" style="background:#fff;color:var(--primary)">جستجو</button>
  </form>
</div></section>
@endsection
@section('content')
<h2>دسته‌بندی‌ها</h2>
<div class="grid">
@foreach($categories as $c)
  <a class="card" href="{{ route('directory', ['cat' => $c->id]) }}" style="color:inherit"><h3>{{ $c->name }}</h3></a>
@endforeach
</div>
@if($featured->count())
<h2 style="margin-top:32px">شرکت‌های ویژه</h2>
<div class="grid">@foreach($featured as $company)@include('partials.card')@endforeach</div>
@endif
@endsection
