@extends('layouts.app', ['title' => 'پنل من'])
@section('content')
<h1>پنل من</h1>
<p><a class="btn" href="{{ route('companies.create') }}">ثبت شرکت جدید</a></p>
<div class="grid">
@forelse($companies as $c)
  <div class="card"><h3>{{ $c->name }}</h3>
    <p>وضعیت: {{ ['approved'=>'تأییدشده','pending'=>'در انتظار تأیید','rejected'=>'ردشده'][$c->status] ?? $c->status }}</p>
    <a class="btn small ghost" href="{{ route('companies.edit', $c) }}">ویرایش</a>
    @if($c->status==='approved')<a class="btn small ghost" href="{{ route('company', $c) }}">مشاهده</a>@endif
  </div>
@empty<p>هنوز شرکتی ثبت نکرده‌اید.</p>@endforelse
</div>
@endsection
