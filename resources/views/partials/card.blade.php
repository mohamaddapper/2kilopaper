@php
  $tier = $company->activeTier();
  $tierTitles = [
      'gold' => 'طلایی',
      'silver' => 'نقره‌ای',
      'bronze' => 'برنزی',
  ];
  $tierStyles = [
      'gold' => [
          'border' => '1.5px solid #f59e0b',
          'badge_bg' => '#fef3c7',
          'badge_text' => '#92400e',
          'badge_border' => '#fcd34d',
          'shadow' => '0 4px 16px rgba(245, 158, 11, 0.12)',
      ],
      'silver' => [
          'border' => '1.5px solid #94a3b8',
          'badge_bg' => '#f1f5f9',
          'badge_text' => '#334155',
          'badge_border' => '#cbd5e1',
          'shadow' => '0 4px 16px rgba(100, 116, 139, 0.1)',
      ],
      'bronze' => [
          'border' => '1.5px solid #d97706',
          'badge_bg' => '#ffedd5',
          'badge_text' => '#9a3412',
          'badge_border' => '#fed7aa',
          'shadow' => '0 4px 16px rgba(217, 119, 6, 0.1)',
      ],
  ];
  $currentTier = $tier ? ($tierStyles[$tier] ?? null) : null;

  // دسته‌بندی‌ها و ۲ زمینه فعالیت اول شرکت
  $categoriesText = '';
  if ($company->categories && $company->categories->count()) {
      $categoriesText = $company->categories->pluck('name')->take(2)->join('، ');
  } elseif (!empty($company->services) && is_array($company->services)) {
      $categoriesText = implode('، ', array_slice($company->services, 0, 2));
  } else {
      $categoriesText = $company->category->name ?? 'صنایع کاغذ و بسته‌بندی';
  }

  // بررسی وضعیت استخدام
  $isHiring = $company->relationLoaded('jobPostings') ? ($company->jobPostings->where('status', 'active')->count() > 0) : false;

  // آمار بازدید و علاقه‌مندی
  $likesCount = $company->relationLoaded('bookmarks') ? $company->bookmarks->count() : ($company->bookmarks_count ?? 0);
  $viewsCount = $company->views_count ?? 0;
@endphp

<a href="{{ $company->url }}" class="card company-card @if($tier) tier-card tier-{{ $tier }} @endif" style="color:inherit;display:flex;flex-direction:column;justify-content:space-between;text-decoration:none;position:relative;background:#fff;border-radius:14px;padding:0!important;overflow:hidden!important;transition:all 0.25s ease;@if($currentTier) border:{{ $currentTier['border'] }};box-shadow:{{ $currentTier['shadow'] }}; @else border:1px solid #e2e8f0; @endif">
  
  <!-- بخش بالای کارت: کادر مربعی بزرگ لوگو چسبیده به اطراف -->
  <div style="width:100%;aspect-ratio:1/1;position:relative;background:#ffffff;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:center;overflow:hidden;padding:0!important;box-sizing:border-box">
    
    <!-- نشان سطح اشتراک (★ طلایی، نقره‌ای، برنزی) در گوشه چپ -->
    @if($tier && isset($tierTitles[$tier]))
      <div style="position:absolute;left:10px;top:10px;z-index:3">
        <span style="display:inline-flex;align-items:center;gap:3px;font-size:11px;font-weight:700;padding:2px 8px;border-radius:12px;background:{{ $currentTier['badge_bg'] }};color:{{ $currentTier['badge_text'] }};border:1px solid {{ $currentTier['badge_border'] }};white-space:nowrap;box-shadow:0 2px 6px rgba(0,0,0,0.06)">
          ★ {{ $tierTitles[$tier] }}
        </span>
      </div>
    @endif

    <!-- نشان جذب نیرو در گوشه راست (در صورت داشتن آگهی فعال) -->
    @if($isHiring)
      <div style="position:absolute;right:10px;top:10px;z-index:3">
        <span style="display:inline-flex;align-items:center;font-size:10.5px;font-weight:800;padding:2px 8px;border-radius:12px;background:#059669;color:#ffffff;white-space:nowrap;box-shadow:0 2px 6px rgba(5,150,105,0.2)">
          جذب نیرو
        </span>
      </div>
    @endif

    <!-- تصویر لوگوی بزرگ و چسبیده به کادر -->
    @if($company->logo)
      <img src="{{ asset($company->logo) }}" alt="{{ $company->name }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.3s ease">
    @else
      <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;color:#94a3b8;position:relative;width:100%;height:100%;user-select:none;background:#f8fafc">
        <span style="position:absolute;font-size:68px;font-weight:900;color:#e2e8f0;z-index:1">2K</span>
        <span style="position:relative;z-index:2;font-size:14px;font-weight:800;color:#64748b">بدون تصویر!</span>
      </div>
    @endif

  </div>

  <!-- بخش پایین کارت: اطلاعات شرکت (راست‌چین) -->
  <div style="padding:12px 11px 9px;display:flex;flex-direction:column;align-items:flex-start;text-align:right;flex:1;justify-content:space-between;background:#ffffff;box-sizing:border-box;direction:rtl">
    
    <div style="width:100%;text-align:right">
      <!-- نام شرکت (بزرگ‌تر و واضح) -->
      <h3 style="margin:0 0 5px;font-size:14.5px;font-weight:800;color:#0f172a;line-height:1.4;text-align:right;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden" title="{{ $company->name }}">
        {{ $company->name }}
      </h3>
      
      <!-- ۲ زمینه فعالیت اول زیر نام شرکت -->
      <p style="margin:0 0 4px;font-size:12px;font-weight:600;color:#475569;line-height:1.45;text-align:right;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $categoriesText }}">
        {{ $categoriesText }}
      </p>

      <!-- موقعیت مکانی (شهر و استان - بزرگ‌تر و خواناتر) -->
      <p style="margin:0 0 5px;font-size:11.5px;color:#64748b;line-height:1.4;text-align:right;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
        {{ $company->city->name ?? 'ایران' }}
        @if($company->province && $company->city && $company->province->name !== $company->city->name)
          · {{ $company->province->name }}
        @endif
      </p>
    </div>

    <!-- ردیف آمار و بازدید پایین کارت (مشابه ۲ کیلو کاغذ) -->
    <div style="width:100%;padding-top:7px;border-top:1px dashed #f1f5f9;display:flex;align-items:center;justify-content:space-between;font-size:11px;color:#94a3b8;margin-top:auto">
      <span style="display:inline-flex;align-items:center;gap:3px" title="علاقه‌مندی">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        <span>{{ $likesCount }}</span>
      </span>

      <span style="display:inline-flex;align-items:center;gap:3px" title="تعداد بازدید">
        <span>{{ number_format($viewsCount) }}</span>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
      </span>
    </div>

  </div>

</a>
