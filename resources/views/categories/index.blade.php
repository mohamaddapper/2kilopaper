@extends('layouts.app', [
 'title' => 'دسته‌بندی تأمین‌کنندگان و فعالان صنایع سلولزی، کارتن و چاپ',
 'description' => 'مرجع دسته‌بندی تولیدکنندگان، واردکنندگان، فروشندگان، چاپخانه‌ها و خدمات‌دهندگان صنعت کاغذ، مقوا، کارتن و بسته‌بندی ایران.',
 'canonical' => route('categories.index'),
])

@push('head')
<script type="application/ld+json">{!! json_encode([
 '@context' => 'https://schema.org',
 '@type' => 'BreadcrumbList',
 'itemListElement' => [
 ['@type' => 'ListItem', 'position' => 1, 'name' => 'خانه', 'item' => url('/')],
 ['@type' => 'ListItem', 'position' => 2, 'name' => 'دسته‌بندی فعالیت‌ها', 'item' => route('categories.index')],
 ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
<div class="hub-page" style="margin:20px 0 60px;width:100%">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" style="font-size:12.5px;color:#64748b;margin-bottom:14px;display:flex;align-items:center;gap:6px;flex-wrap:wrap">
    <a href="{{ route('home') }}" style="color:#64748b;text-decoration:none">خانه</a>
    <span>›</span>
    <span style="color:#0f172a;font-weight:700">دسته‌بندی فعالان بر اساس زمینه فعالیت</span>
  </nav>

  <!-- Header -->
  <div style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);color:#fff;border-radius:18px;padding:28px 30px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:18px">
    <div>
      <h1 style="margin:0 0 8px;font-size:24px;font-weight:900;color:#fff">
        {!! svg_icon('folder', 15) !!} دسته‌بندی تأمین‌کنندگان بر اساس زمینه فعالیت
      </h1>
      <p style="margin:0;font-size:14px;color:#cbd5e1;line-height:1.7;max-width:740px">
        دسترسی سریع به فهرست تخصصی <strong>{{ number_format($totalCompanies) }}</strong> کارخانه، واردکننده، فروشنده، چاپخانه و واحد خدماتی در صنعت کاغذ، مقوا و کارتن ایران.
      </p>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a href="{{ route('locations.index') }}" class="btn small" style="background:rgba(255,255,255,0.12);color:#fff;border:1px solid rgba(255,255,255,0.2);font-weight:700">
        {!! svg_icon('map-pin', 15) !!} مشاهده بر اساس استان
      </a>
      <a href="{{ route('directory') }}" class="btn small" style="background:#0ea5e9;color:#fff;font-weight:700">
        {!! svg_icon('search', 15) !!} جستجو در دایرکتوری
      </a>
    </div>
  </div>

  <!-- Quick Pillar Jump Links -->
  <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:26px">
    @foreach($pillars as $pillar)
      <a href="#pillar-{{ $pillar->id }}" style="display:inline-flex;align-items:center;gap:7px;padding:9px 16px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;color:#0f172a;font-weight:700;font-size:13.5px;text-decoration:none;box-shadow:0 1px 2px rgba(0,0,0,0.03)">
        <span>{!! emoji_to_svg($pillar->icon ?: '', 16) !!}</span>
        <span>{{ $pillar->name }}</span>
      </a>
    @endforeach
  </div>

  <!-- Pillars & Subcategories Tree -->
  <div style="display:flex;flex-direction:column;gap:26px">
    @foreach($pillars as $pillar)
      <section id="pillar-{{ $pillar->id }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:22px 24px">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding-bottom:14px;margin-bottom:18px;border-bottom:1.5px solid #f1f5f9">
          <h2 style="margin:0;font-size:19px;font-weight:900;color:#0f172a;display:flex;align-items:center;gap:8px">
            <span>{!! emoji_to_svg($pillar->icon ?: '', 16) !!}</span>
            <a href="{{ $pillar->url }}" style="color:#0f172a;text-decoration:none">{{ $pillar->name }}</a>
          </h2>
          <a href="{{ $pillar->url }}" style="font-size:13px;font-weight:700;color:var(--primary);text-decoration:none">
            مشاهده همه شرکت‌های این شاخه ←
          </a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:12px">
          @foreach($pillar->children as $child)
            @php
 $childCount = $countsByCatId[$child->id] ?? 0;
 foreach ($child->children as $gc) {
 $childCount += ($countsByCatId[$gc->id] ?? 0);
 }
 @endphp
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px 14px;display:flex;flex-direction:column;gap:8px">
              <div style="display:flex;align-items:center;justify-content:space-between;gap:8px">
                <a href="{{ $child->url }}" style="font-size:14px;font-weight:800;color:#0f172a;text-decoration:none">
                  {{ $child->name }}
                </a>
                <span style="font-size:11.5px;font-weight:700;background:#e0f2fe;color:#0369a1;padding:2px 8px;border-radius:20px;white-space:nowrap">
                  {{ number_format($childCount) }} شرکت
                </span>
              </div>

              @if($child->children->isNotEmpty())
                <div style="display:flex;flex-wrap:wrap;gap:6px;padding-top:6px;border-top:1px dashed #e2e8f0">
                  @foreach($child->children as $grandChild)
                    <a href="{{ $grandChild->url }}" style="font-size:12px;color:#475569;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:2px 8px;text-decoration:none">
                      {{ $grandChild->name }}
                      @if(($countsByCatId[$grandChild->id] ?? 0) > 0)
                        <span style="color:#94a3b8">({{ $countsByCatId[$grandChild->id] }})</span>
                      @endif
                    </a>
                  @endforeach
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </section>
    @endforeach
  </div>
</div>
@endsection
