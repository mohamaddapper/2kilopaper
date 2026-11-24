@extends('layouts.app', [
 'title' => 'دسته‌بندی شرکت‌ها و کارخانجات صنایع سلولزی بر اساس استان',
 'description' => 'مشاهده فهرست تولیدکنندگان، واردکنندگان، فروشندگان و چاپخانه‌های صنعت کاغذ و کارتن ایران به تفکیک ۳۱ استان کشور.',
 'canonical' => route('locations.index'),
])

@push('head')
<script type="application/ld+json">{!! json_encode([
 '@context' => 'https://schema.org',
 '@type' => 'BreadcrumbList',
 'itemListElement' => [
 ['@type' => 'ListItem', 'position' => 1, 'name' => 'خانه', 'item' => url('/')],
 ['@type' => 'ListItem', 'position' => 2, 'name' => 'دسته‌بندی بر اساس استان', 'item' => route('locations.index')],
 ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
<div style="margin:20px 0 60px;width:100%">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" style="font-size:12.5px;color:#64748b;margin-bottom:14px;display:flex;align-items:center;gap:6px;flex-wrap:wrap">
    <a href="{{ route('home') }}" style="color:#64748b;text-decoration:none">خانه</a>
    <span>›</span>
    <span style="color:#0f172a;font-weight:700">دسته‌بندی بر اساس موقعیت مکانی (استان‌ها)</span>
  </nav>

  <!-- Header -->
  <div style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);color:#fff;border-radius:18px;padding:28px 30px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:18px">
    <div>
      <h1 style="margin:0 0 8px;font-size:24px;font-weight:900;color:#fff">
        {!! svg_icon('map-pin', 15) !!} دسته‌بندی تأمین‌کنندگان و کارخانجات بر اساس استان
      </h1>
      <p style="margin:0;font-size:14px;color:#cbd5e1;line-height:1.7;max-width:740px">
        انتخاب استان مورد نظر برای دسترسی سریع به <strong>{{ number_format($totalCompanies) }}</strong> کارخانه، فروشنده، واردکننده و چاپخانه فعال در سراسر ایران.
      </p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a href="{{ route('categories.index') }}" class="btn small" style="background:rgba(255,255,255,0.12);color:#fff;border:1px solid rgba(255,255,255,0.2);font-weight:700">
        {!! svg_icon('folder', 15) !!} مشاهده بر اساس زمینه فعالیت
      </a>
      <a href="{{ route('directory') }}" class="btn small" style="background:#0ea5e9;color:#fff;font-weight:700">
        {!! svg_icon('search', 15) !!} جستجو در دایرکتوری
      </a>
    </div>
  </div>

  <!-- Provinces Grid -->
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px">
    @foreach($provinces as $prov)
      <a href="{{ $prov->url }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:16px 18px;text-decoration:none;display:flex;align-items:center;justify-content:space-between;gap:10px;transition:all 0.2s;box-shadow:0 1px 2px rgba(0,0,0,0.02)">
        <div style="display:flex;align-items:center;gap:10px">
          <span style="width:36px;height:36px;border-radius:10px;background:#f0f9ff;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0">{!! svg_icon('map-pin', 15) !!}</span>
          <span style="font-size:15px;font-weight:800;color:#0f172a">{{ $prov->name }}</span>
        </div>
        <span style="font-size:12px;font-weight:700;padding:3px 9px;border-radius:20px;{{ $prov->companies_count > 0 ? 'background:#ecfdf5;color:#047857;border:1px solid #a7f3d0' : 'background:#f8fafc;color:#94a3b8;border:1px solid #e2e8f0' }}">
          {{ number_format($prov->companies_count) }} شرکت
        </span>
      </a>
    @endforeach
  </div>
</div>
@endsection
