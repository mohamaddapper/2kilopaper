@extends('layouts.app', ['title' => $company->exists ? 'ویرایش شرکت' : 'ثبت شرکت'])
@section('content')
<div class="center-box" style="max-width:640px">
<h2 style="margin-top:0">{{ $company->exists ? 'ویرایش شرکت' : 'ثبت شرکت' }}</h2>
<form method="post" action="{{ $company->exists ? route('companies.update', $company) : route('companies.store') }}">
  @csrf @if($company->exists) @method('PUT') @endif
  @php($f = fn($k) => old($k, $company->$k))
  <div class="field"><label>نام شرکت</label><input name="name" value="{{ $f('name') }}" required></div>
  <div class="field"><label>دسته‌بندی</label><select name="category_id" required>
    @foreach($categories as $p)<optgroup label="{{ $p->name }}">
      @foreach($p->children as $ch)<option value="{{ $ch->id }}" @selected($f('category_id')==$ch->id)>{{ $ch->name }}</option>@endforeach
    </optgroup>@endforeach</select></div>
  <div class="field"><label>شهر</label><select name="city_id" required>
    @foreach($cities as $c)<option value="{{ $c->id }}" @selected($f('city_id')==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
  <div class="field"><label>معرفی</label><textarea name="description" rows="5">{{ $f('description') }}</textarea></div>
  <div class="field"><label>تلفن</label><input name="phone" value="{{ $f('phone') }}"></div>
  <div class="field"><label>وب‌سایت</label><input name="website" value="{{ $f('website') }}" placeholder="مثال: 2kilopaper.ir"></div>
  <div class="field"><label>آدرس</label><input name="address" value="{{ $f('address') }}"></div>
  @if($errors->any())<ul style="color:#c0392b">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
  <button class="btn">ذخیره</button>
</form></div>
@endsection
