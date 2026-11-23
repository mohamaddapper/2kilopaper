@extends('layouts.app', [
  'title' => $company->name . ' - ' . $company->category->name . ' در ' . $company->city->name,
  'description' => \Illuminate\Support\Str::limit(strip_tags($company->description), 150),
  'canonical' => route('company', $company),
])
@push('head')
<script type="application/ld+json">{!! json_encode(array_filter([
  '@context' => 'https://schema.org', '@type' => 'LocalBusiness',
  'name' => $company->name, 'description' => $company->description,
  'telephone' => $company->phone, 'url' => $company->website ?: route('company', $company),
  'address' => ['@type' => 'PostalAddress', 'addressLocality' => $company->city->name, 'streetAddress' => $company->address, 'addressCountry' => 'IR'],
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush
@section('content')
<div class="card">
  <h1 style="margin-top:0">{{ $company->name }}</h1>
  <p>{{ $company->category->name }} · {{ $company->city->name }}</p>
  <p>{!! nl2br(e($company->description)) !!}</p>
  @if($company->address)<p>آدرس: {{ $company->address }}</p>@endif
  @if($company->phone)<p>تلفن: <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a></p>@endif
  @if($company->website)<p>وب‌سایت: <a href="{{ $company->website }}" rel="nofollow noopener" target="_blank">{{ $company->website }}</a></p>@endif
</div>
@endsection
