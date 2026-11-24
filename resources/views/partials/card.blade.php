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
          'shadow' => '0 2px 10px rgba(245, 158, 11, 0.1)',
      ],
      'silver' => [
          'border' => '1.5px solid #94a3b8',
          'badge_bg' => '#f1f5f9',
          'badge_text' => '#334155',
          'badge_border' => '#cbd5e1',
          'shadow' => '0 2px 10px rgba(100, 116, 139, 0.1)',
      ],
      'bronze' => [
          'border' => '1.5px solid #d97706',
          'badge_bg' => '#ffedd5',
          'badge_text' => '#9a3412',
          'badge_border' => '#fed7aa',
          'shadow' => '0 2px 10px rgba(217, 119, 6, 0.1)',
      ],
  ];
  $currentTier = $tier ? ($tierStyles[$tier] ?? null) : null;
@endphp

<a href="{{ route('company', $company) }}" class="card company-card @if($tier) tier-card tier-{{ $tier }} @endif" style="color:inherit;display:flex;flex-direction:column;justify-content:space-between;text-decoration:none;position:relative;background:#fff;border-radius:12px;padding:16px;transition:all 0.2s ease;@if($currentTier) border:{{ $currentTier['border'] }};box-shadow:{{ $currentTier['shadow'] }}; @endif">
  
  <div>
    <!-- ردیف بالای کارت: لوگو، عنوان و نشان سطح اشتراک -->
    <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:12px;position:relative">
      
      <!-- لوگوی شرکت -->
      <div style="width:52px;height:52px;border-radius:10px;background:#f8fafc;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;padding:3px">
        @if($company->logo)
          <img src="{{ asset($company->logo) }}" alt="{{ $company->name }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:contain">
        @else
          <span style="font-size:22px;font-weight:800;color:var(--primary)">{{ mb_substr($company->name, 0, 1) }}</span>
        @endif
      </div>

      <!-- نام شرکت و دسته‌بندی -->
      <div style="flex:1;min-width:0;padding-left:@if($tier) 68px @else 0 @endif">
        <h3 style="margin:0 0 4px;font-size:14.5px;font-weight:700;color:#0f172a;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis" title="{{ $company->name }}">
          {{ $company->name }}
        </h3>
        <p style="margin:0;color:#64748b;font-size:12px;line-height:1.4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
          {{ $company->category->name ?? 'صنایع کاغذ' }} · {{ $company->city->name ?? 'ایران' }}
        </p>
      </div>

      <!-- نشان سطح اشتراک (بدون کلمه ویژه، فقط طلایی، نقره‌ای، برنزی) -->
      @if($tier && isset($tierTitles[$tier]))
        <div style="position:absolute;left:0;top:0">
          <span style="display:inline-flex;align-items:center;gap:3px;font-size:11px;font-weight:700;padding:2px 8px;border-radius:12px;background:{{ $currentTier['badge_bg'] }};color:{{ $currentTier['badge_text'] }};border:1px solid {{ $currentTier['badge_border'] }};white-space:nowrap">
            ★ {{ $tierTitles[$tier] }}
          </span>
        </div>
      @endif
    </div>

    <!-- شرح مختصر فعالیت -->
    @if($company->description)
      <p style="margin:0 0 14px;color:#475569;font-size:12.5px;line-height:1.7;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis">
        {{ \Illuminate\Support\Str::limit(strip_tags($company->description), 110) }}
      </p>
    @endif
  </div>

  <!-- ردیف پایین: اطلاعات مکان و لینک ورود -->
  <div style="display:flex;justify-content:space-between;align-items:center;padding-top:10px;border-top:1px solid #f1f5f9;margin-top:auto;font-size:12px">
    <span style="color:#64748b;display:flex;align-items:center;gap:4px">
      📍 {{ $company->city->name ?? 'ایران' }}
    </span>
    <span style="color:var(--primary);font-weight:700;display:flex;align-items:center;gap:2px">
      مشاهده شرکت ←
    </span>
  </div>

</a>
