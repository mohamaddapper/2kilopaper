@extends('layouts.app', [
 'title' => $company->name . ($company->slogan ? ' - ' . $company->slogan : ' | ' . ($company->category?->name ?? 'صنایع سلولزی') . ' در ' . ($company->city?->name ?? 'ایران')),
  'description' => \Illuminate\Support\Str::limit(strip_tags($company->description), 150),
  'canonical' => $company->url,
])

@push('head')
<script type="application/ld+json">{!! json_encode(array_filter([
 '@context' => 'https://schema.org',
 '@type' => 'LocalBusiness',
 'name' => $company->name,
 'description' => $company->description,
 'telephone' => $company->phone,
 'url' => $company->website ?: $company->url,
 'image' => $company->logo ? asset($company->logo) : null,
 'address' => [
 '@type' => 'PostalAddress',
 'addressLocality' => $company->city?->name ?? 'ایران',
 'streetAddress' => $company->factory_address ?: $company->address,
 'addressCountry' => 'IR',
 ],
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
<div class="page-wrapper" style="margin:-8px 0 40px">

  @php
 $isBookmarked = auth()->check() && $company->isBookmarkedBy(auth()->user());
 $bookmarksCount = $company->bookmarks()->count();
 $tier = $company->activeTier();
 $headBorderStyle = match($tier) {
 'gold' => 'border:1.5px solid #f59e0b;box-shadow:0 4px 16px rgba(245, 158, 11, 0.12);',
 'silver' => 'border:1.5px solid #94a3b8;box-shadow:0 4px 16px rgba(100, 116, 139, 0.1);',
 'bronze' => 'border:1.5px solid #d97706;box-shadow:0 4px 16px rgba(217, 119, 6, 0.1);',
 default => 'border:1px solid var(--border);',
 };
 try {
 $activeJobs = \Illuminate\Support\Facades\Schema::hasTable('job_postings')
 ? $company->jobPostings()->active()->latest()->get()
 : collect();
 } catch (\Throwable $e) {
 $activeJobs = collect();
 }
 @endphp

  <!-- بنرهای تبلیغاتی بالای صفحه (تنها برای شرکت‌های عادی / بدون اشتراک فعال نمایش داده می‌شود) -->
  @if(!$company->hasActiveBenefits())
    <div class="company-ad-banners" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:14px;margin-bottom:20px">
      <!-- بنر راست: ارتقای صفحه و حذف تبلیغات -->
      <a href="{{ route('subscription.fee') }}" class="ad-banner-box" style="display:flex;align-items:center;justify-content:space-between;padding:16px 18px;background:linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);border:1.5px dashed #f59e0b;border-radius:12px;text-decoration:none;transition:all 0.2s ease;box-shadow:0 1px 3px rgba(0,0,0,0.03)">
        <div style="display:flex;align-items:center;gap:12px">
          <div style="width:44px;height:44px;border-radius:10px;background:#fef08a;display:flex;align-items:center;justify-content:center;font-size:22px;color:#b45309;flex-shrink:0">
            {!! svg_icon('star-filled', 15) !!}
          </div>
          <div>
            <div style="font-size:14px;font-weight:800;color:#92400e;margin-bottom:2px">جایگاه ویژه کسب‌وکار شما</div>
            <div style="font-size:12px;color:#78350f">با تهیه اشتراک، بنرهای تبلیغاتی از صفحه شما حذف می‌شوند!</div>
          </div>
        </div>
        <span class="btn small" style="background:#d97706;color:#fff;font-size:11.5px;padding:6px 12px;border-radius:6px;font-weight:700;white-space:nowrap;margin-right:8px">ارتقای صفحه</span>
      </a>

      <!-- بنر چپ: محل درج تبلیغات -->
      <a href="{{ route('advertising') }}" class="ad-banner-box" style="display:flex;align-items:center;justify-content:space-between;padding:16px 18px;background:linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);border:1.5px dashed #0284c7;border-radius:12px;text-decoration:none;transition:all 0.2s ease;box-shadow:0 1px 3px rgba(0,0,0,0.03)">
        <div style="display:flex;align-items:center;gap:12px">
          <div style="width:44px;height:44px;border-radius:10px;background:#bae6fd;display:flex;align-items:center;justify-content:center;font-size:22px;color:#0369a1;flex-shrink:0">
            {!! svg_icon('megaphone', 15) !!}
          </div>
          <div>
            <div style="font-size:14px;font-weight:800;color:#0369a1;margin-bottom:2px">محل درج بنر تبلیغاتی شما</div>
            <div style="font-size:12px;color:#0c4a6e">دیده‌شدن توسط هزاران فعال صنعت چاپ، کارتن‌سازی و بسته‌بندی</div>
          </div>
        </div>
        <span class="btn small" style="background:#0284c7;color:#fff;font-size:11.5px;padding:6px 12px;border-radius:6px;font-weight:700;white-space:nowrap;margin-right:8px">سفارش تبلیغات</span>
      </a>
    </div>
  @endif

  <style>
    @media (max-width: 640px) {
      .company-head {
        flex-direction: column !important;
        align-items: flex-start !important;
        text-align: right !important;
      }
      .company-header-logo {
        width: 50% !important;
        height: auto !important;
        aspect-ratio: 1 / 1;
        margin: 0 !important;
        align-self: flex-start !important;
      }
    }
  </style>

  <!-- هدر پروفایل شرکت -->
  <div class="company-head" style="margin-bottom:18px;position:relative;background:#fff;{{ $headBorderStyle }}border-radius:12px;padding:24px">
    <div class="logo-box company-header-logo" style="width:200px;height:200px;border-radius:14px;overflow:hidden;border:1px solid var(--border);background:#fff;flex-shrink:0">
      @if($company->logo)
        <img src="{{ asset($company->logo) }}" alt="{{ $company->name }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:contain;padding:8px">
      @else
        <span style="font-size:52px;font-weight:800;color:var(--primary)">{{ mb_substr($company->name, 0, 1) }}</span>
      @endif
    </div>

    <div style="flex:1;min-width:220px">
      <h1 style="margin:0;font-size:24px;color:var(--text)">{{ $company->name }}</h1>

      @if($company->activeTier() || $activeJobs->count() > 0)
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:8px">
          @if($company->activeTier() === 'gold')
            <span class="badge gold" style="font-size:13px;padding:3px 12px">{!! svg_icon('star-filled', 14) !!} طلایی</span>
          @elseif($company->activeTier() === 'silver')
            <span class="badge silver" style="font-size:13px;padding:3px 12px">{!! svg_icon('star-filled', 14) !!} نقره‌ای</span>
          @elseif($company->activeTier() === 'bronze')
            <span class="badge bronze" style="font-size:13px;padding:3px 12px">{!! svg_icon('star-filled', 14) !!} برنزی</span>
          @endif
          @if($activeJobs->count() > 0)
            <a href="#company-jobs-section" class="badge" style="background:#dcfce7;color:#15803d;border:1px solid #86efac;font-size:13px;padding:3px 12px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:5px">
              {!! svg_icon('briefcase', 14) !!} در حال استخدام
            </a>
          @endif
        </div>
      @endif

      @if($company->slogan)
        <p style="margin:6px 0;font-size:15px;color:var(--primary);font-weight:600">{{ $company->slogan }}</p>
      @endif

      <div style="display:flex;gap:12px;color:var(--muted);font-size:13.5px;margin-top:8px;flex-wrap:wrap;align-items:center">
        <span>{!! svg_icon('map-pin', 15) !!} {{ $company->city->name }}</span>
        @if(data_get($company->tech_specs, 'established_year'))
          <span>{!! svg_icon('calendar', 15) !!} <b>تأسیس:</b> {{ data_get($company->tech_specs, 'established_year') }}</span>
        @endif
        <span>{!! svg_icon('eye', 15) !!} <b>بازدید:</b> {{ number_format($company->views_count ?? 0) }}</span>
        <span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-0.18em;flex-shrink:0"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg> <b>ذخیره:</b> <span id="header-bookmarks-count">{{ number_format($bookmarksCount) }}</span></span>
      </div>

      <!-- برچسب‌های دسته‌بندی و زمینه‌های فعالیت به تفکیک نقش -->
      @php $roleGroups = $company->groupedCategories(); @endphp
      @if(!empty($roleGroups))
        <div style="display:flex;flex-direction:column;gap:7px;margin-top:12px">
          @foreach($roleGroups as $group)
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
              <span class="badge" style="background:{{ $group['bg'] }};color:{{ $group['color'] }};border:1px solid {{ $group['border'] }};font-weight:800;font-size:12px;padding:3px 10px;display:inline-flex;align-items:center;gap:4px">
                <span style="display:inline-flex;align-items:center">{!! $group['icon'] !!}</span>
                <span>{{ $group['label'] }}</span>
              </span>
              @foreach($group['items'] as $catItem)
                <a href="{{ $catItem['url'] ?? route('directory', ['cat' => $catItem['id']]) }}"
                   class="badge"
                   style="@if($catItem['is_primary']) background:#e0f2fe;color:#0369a1;font-weight:700;border:1px solid #bae6fd; @else background:#f8fafc;color:#334155;border:1px solid #e2e8f0; @endif text-decoration:none;font-size:12px;padding:3px 10px">
                  {{ $catItem['name'] }}
                </a>
              @endforeach
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  <!-- نوار دکمه‌های اقدام (راست‌چین: ذخیره، ثبت نظر و در انتها آیکن‌های ویرایش/حذف) -->
  @php $canManage = auth()->check() && (auth()->id() === $company->user_id || auth()->user()->isAdmin()); @endphp
  <div style="display:flex;align-items:center;justify-content:flex-start;gap:8px;flex-wrap:wrap;margin-bottom:18px">
    <button type="button"
            onclick="toggleBookmark()"
            id="bookmark-btn-head"
            class="btn ghost small"
            title="{{ $isBookmarked ? 'حذف از ذخیره‌شده‌ها' : 'ذخیره شرکت' }}"
            style="padding:7px 14px;font-size:13px;font-weight:700;border-radius:8px;display:inline-flex;align-items:center;gap:6px;cursor:pointer;transition:all 0.2s ease;{{ $isBookmarked ? 'background:#f0fdf4;color:#166534;border:1.5px solid #86efac' : 'background:#fff;color:#475569;border:1.5px solid #cbd5e1' }}">
      <svg id="bookmark-icon-head" width="15" height="15" viewBox="0 0 24 24" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:{{ $isBookmarked ? '#16a34a' : '#64748b' }}"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
      <span id="bookmark-text-head">{{ $isBookmarked ? 'ذخیره شده' : 'ذخیره' }}</span>
    </button>

    <button type="button"
            onclick="openCommentNotice()"
            class="btn ghost small"
            style="padding:7px 14px;font-size:13px;font-weight:700;border-radius:8px;display:inline-flex;align-items:center;gap:6px;cursor:pointer;background:#fff;color:#475569;border:1.5px solid #cbd5e1">
      {!! svg_icon('message', 15) !!}
      <span>ثبت نظر</span>
    </button>

    @if($canManage)
      <a href="{{ route('companies.edit', ['company' => $company, 'return_to' => $company->url]) }}"
         class="btn small"
         title="ویرایش اطلاعات این شرکت"
         aria-label="ویرایش اطلاعات این شرکت"
         style="background:#0284c7;color:#fff;padding:8px 10px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center">
        {!! svg_icon('edit', 16) !!}
      </a>
      <form method="POST" action="{{ route('companies.destroy', $company) }}" style="display:inline;margin:0" onsubmit="return confirm('آیا از حذف کامل شرکت «{{ addslashes($company->name) }}» اطمینان دارید؟ این عملیات قابل بازگشت نیست.')">
        @csrf
        @method('DELETE')
        <input type="hidden" name="redirect_to" value="directory">
        <button type="submit"
                class="btn small"
                title="حذف شرکت"
                aria-label="حذف شرکت"
                style="background:#dc2626;color:#fff;padding:8px 10px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;border:none;cursor:pointer">
          {!! svg_icon('trash', 16) !!}
        </button>
      </form>
    @endif
  </div>
  <div id="comment-notice-box" style="display:none;margin-bottom:16px;padding:10px 14px;border-radius:8px;background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;font-size:13px;font-weight:600">
    بخش ثبت نظرات و امتیازدهی کاربران به‌زودی در این صفحه فعال می‌شود.
  </div>

  <!-- چیدمان دو ستونه محتوا -->
  <div class="company-content-grid">
    
    <!-- ستون اصلی (راست) -->
    <div>
      <!-- برچسب‌های خدمات و محصولات تخصصی (در ستون راست) -->
      @if($company->services && count($company->services) > 0)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px;margin-bottom:12px">{!! svg_icon('box', 16) !!} خدمات و محصولات تخصصی</h3>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            @foreach($company->services as $srv)
              <span class="badge" style="background:#eef3f9;color:#1e3a8a;font-size:13px;padding:5px 12px;border:1px solid #dbeafe">{{ $srv }}</span>
            @endforeach
          </div>
        </div>
      @endif

      <!-- ۱. ویدیو معرفی -->
      @if($company->video_code)
        @php
 $rawVideo = trim($company->video_code);
 $embedUrl = null;
 if (preg_match('/aparat\.com\/v\/([a-zA-Z0-9]+)/', $rawVideo, $m)) {
 $embedUrl = "https://www.aparat.com/video/video/embed/videohash/{$m[1]}/vt/frame";
 }
 @endphp
        @if($embedUrl)
          <div class="card" style="margin-bottom:20px">
            <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">{!! svg_icon('video', 15) !!} ویدیو</h3>
            <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:8px">
              <iframe src="{{ $embedUrl }}" loading="lazy" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0" allowfullscreen></iframe>
            </div>
          </div>
        @elseif(str_contains($rawVideo, '<iframe'))
          <div class="card" style="margin-bottom:20px">
            <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">{!! svg_icon('video', 15) !!} ویدیو</h3>
            <div style="margin-top:10px;text-align:center">
              {!! $rawVideo !!}
            </div>
          </div>
        @endif
      @endif

      <!-- ۲. گالری تصاویر -->
      @if($company->gallery && count($company->gallery) > 0)
        @php $galleryUrls = array_values(array_map(fn($g) => asset($g), $company->gallery)); @endphp
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px">
            <span style="display:inline-flex;align-items:center;gap:6px">{!! svg_icon('camera', 16) !!} تصاویر</span>
            <span style="font-size:12px;font-weight:500;color:var(--muted)">برای بزرگ‌نمایی روی تصویر کلیک کنید</span>
          </h3>
          <div class="company-gallery-grid">
            @foreach($galleryUrls as $idx => $imgUrl)
              <button type="button" onclick="openLightbox({{ $idx }})" class="gallery-thumb-btn" aria-label="بزرگ‌نمایی تصویر {{ $idx + 1 }}">
                <img src="{{ $imgUrl }}" alt="تصویر {{ $company->name }}" loading="lazy" decoding="async">
                <span class="gallery-zoom-badge">{!! svg_icon('search', 14) !!}</span>
              </button>
            @endforeach
          </div>
        </div>

        <!-- Lightbox Modal -->
        <div id="companyLightbox" class="lightbox-overlay" role="dialog" aria-modal="true" aria-label="نمایشگر تصاویر" onclick="if(event.target === this) closeLightbox()">
          <div class="lightbox-topbar">
            <div id="lightboxCounter" class="lightbox-counter">۱ از {{ count($galleryUrls) }}</div>
            <button type="button" class="lightbox-close-btn" onclick="closeLightbox()" aria-label="بستن">
              {!! svg_icon('x', 22) !!}
            </button>
          </div>

          <div class="lightbox-stage" onclick="if(event.target === this) closeLightbox()">
            @if(count($galleryUrls) > 1)
              <button type="button" class="lightbox-nav-btn prev" onclick="stepLightbox(-1)" aria-label="تصویر قبلی">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </button>
            @endif

            <img id="lightboxMainImg" src="" alt="تصویر {{ $company->name }}" class="lightbox-main-img">

            @if(count($galleryUrls) > 1)
              <button type="button" class="lightbox-nav-btn next" onclick="stepLightbox(1)" aria-label="تصویر بعدی">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
              </button>
            @endif
          </div>

          @if(count($galleryUrls) > 1)
            <div class="lightbox-thumbs" id="lightboxThumbs">
              @foreach($galleryUrls as $idx => $imgUrl)
                <button type="button" class="lightbox-thumb-item" onclick="setLightboxIndex({{ $idx }})" data-idx="{{ $idx }}">
                  <img src="{{ $imgUrl }}" alt="بندانگشتی {{ $idx + 1 }}">
                </button>
              @endforeach
            </div>
          @endif
        </div>

        <style>
          .company-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
            margin-top: 14px;
          }
          @media (max-width: 640px) {
            .company-gallery-grid {
              grid-template-columns: repeat(2, minmax(0, 1fr));
              gap: 10px;
            }
            .gallery-thumb-btn {
              height: 120px !important;
            }
          }
          .gallery-thumb-btn {
            position: relative;
            display: block;
            width: 100%;
            height: 140px;
            padding: 0;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: #f8fafc;
            cursor: zoom-in;
          }
          .gallery-thumb-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
          }
          .gallery-thumb-btn:hover img {
            transform: scale(1.06);
          }
          .gallery-zoom-badge {
            position: absolute;
            bottom: 8px;
            left: 8px;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(15, 23, 42, 0.65);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
          }
          .gallery-thumb-btn:hover .gallery-zoom-badge {
            opacity: 1;
          }
          .lightbox-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(6px);
            display: none;
            flex-direction: column;
            justify-content: space-between;
            padding: 16px 20px;
            box-sizing: border-box;
          }
          .lightbox-overlay.open {
            display: flex;
          }
          .lightbox-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #fff;
            flex-shrink: 0;
          }
          .lightbox-counter {
            font-size: 13.5px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.12);
            padding: 6px 14px;
            border-radius: 20px;
          }
          .lightbox-close-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
          }
          .lightbox-close-btn:hover {
            background: rgba(239, 68, 68, 0.85);
          }
          .lightbox-stage {
            flex: 1;
            min-height: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 12px 54px;
          }
          .lightbox-main-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            user-select: none;
          }
          .lightbox-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(15, 23, 42, 0.65);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
          }
          .lightbox-nav-btn:hover {
            background: var(--primary);
          }
          .lightbox-nav-btn.prev { right: 4px; }
          .lightbox-nav-btn.next { left: 4px; }
          .lightbox-thumbs {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            overflow-x: auto;
            padding: 8px 0 2px;
            flex-shrink: 0;
          }
          .lightbox-thumb-item {
            width: 60px;
            height: 46px;
            padding: 0;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid transparent;
            opacity: 0.55;
            cursor: pointer;
            flex-shrink: 0;
            background: #000;
            transition: opacity 0.2s, border-color 0.2s, transform 0.2s;
          }
          .lightbox-thumb-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
          }
          .lightbox-thumb-item.active {
            opacity: 1;
            border-color: #38bdf8;
            transform: scale(1.06);
          }
          @media (max-width: 640px) {
            .lightbox-stage { padding: 10px 6px; }
            .lightbox-nav-btn { width: 36px; height: 36px; background: rgba(15, 23, 42, 0.78); }
          }
        </style>

        <script>
          const galleryImages = @json($galleryUrls);
          let currentLightboxIndex = 0;
          let lbTouchStartX = 0;

          function toPersianDigits(n) {
            return String(n).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
          }

          function openLightbox(idx) {
            const modal = document.getElementById('companyLightbox');
            if (!modal || !galleryImages.length) return;
            setLightboxIndex(idx);
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
          }

          function closeLightbox() {
            const modal = document.getElementById('companyLightbox');
            if (!modal) return;
            modal.classList.remove('open');
            document.body.style.overflow = '';
          }

          function setLightboxIndex(idx) {
            if (!galleryImages.length) return;
            currentLightboxIndex = (idx + galleryImages.length) % galleryImages.length;
            const imgEl = document.getElementById('lightboxMainImg');
            const counterEl = document.getElementById('lightboxCounter');
            if (imgEl) imgEl.src = galleryImages[currentLightboxIndex];
            if (counterEl) {
              counterEl.textContent = toPersianDigits(currentLightboxIndex + 1) + ' از ' + toPersianDigits(galleryImages.length);
            }
            document.querySelectorAll('#lightboxThumbs .lightbox-thumb-item').forEach((btn, i) => {
              btn.classList.toggle('active', i === currentLightboxIndex);
            });
          }

          function stepLightbox(delta) {
            setLightboxIndex(currentLightboxIndex + delta);
          }

          document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('companyLightbox');
            if (!modal || !modal.classList.contains('open')) return;
            if (e.key === 'Escape') closeLightbox();
            else if (e.key === 'ArrowLeft') stepLightbox(1);
            else if (e.key === 'ArrowRight') stepLightbox(-1);
          });

          const lbStage = document.querySelector('#companyLightbox .lightbox-stage');
          if (lbStage) {
            lbStage.addEventListener('touchstart', (e) => { lbTouchStartX = e.changedTouches[0].clientX; }, { passive: true });
            lbStage.addEventListener('touchend', (e) => {
              const dx = e.changedTouches[0].clientX - lbTouchStartX;
              if (Math.abs(dx) > 45) stepLightbox(dx > 0 ? 1 : -1);
            }, { passive: true });
          }
        </script>
      @endif

      <!-- ۳. متن معرفی شرکت -->
      <div class="card" style="margin-bottom:20px">
        <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">شرح کالا و خدمات</h3>
        <div style="line-height:2;color:#334155;font-size:15px;white-space:pre-line">{!! e($company->description) !!}</div>
      </div>

      <!-- ۴. فرصت‌های شغلی فعال شرکت -->
      @if($activeJobs->count() > 0)
        <div id="company-jobs-section" class="card" style="margin-bottom:20px;border:1px solid #bae6fd;background:#f0f9ff">
          <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #e0f2fe;padding-bottom:10px;margin-bottom:12px">
            <h3 style="margin:0;font-size:17px;color:#0369a1;display:flex;align-items:center;gap:6px">
              <span>{!! svg_icon('briefcase', 15) !!} فرصت‌های شغلی و استخدام فعال</span>
              <span class="badge" style="background:#0284c7;color:#fff;font-size:11.5px">{{ $activeJobs->count() }} آگهی</span>
            </h3>
            <a href="{{ route('jobs.index', ['q' => $company->name]) }}" style="font-size:12.5px;color:#0284c7;font-weight:600;text-decoration:none">مشاهده در کاریابی ←</a>
          </div>

          <div style="display:flex;flex-direction:column;gap:10px">
            @foreach($activeJobs as $aJob)
              <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px;background:#fff;border-radius:8px;border:1px solid #e2e8f0;flex-wrap:wrap;gap:8px">
                <div>
                  <a href="{{ route('jobs.show', $aJob) }}" style="font-size:15px;font-weight:700;color:var(--text);text-decoration:none">
                    {{ $aJob->title }}
                  </a>
                  <div style="font-size:12.5px;color:var(--muted);margin-top:4px;display:flex;gap:8px;align-items:center">
                    <span>{{ $aJob->job_type_label }}</span>
                    <span>•</span>
                    <span style="color:#16a34a">{{ $aJob->salary_label }}</span>
                    @if(!empty($aJob->benefits['has_service']))
                      <span>•</span>
                      <span style="color:#0284c7">{!! svg_icon('bus', 15) !!} سرویس دارد</span>
                    @endif
                  </div>
                </div>

                <a href="{{ route('jobs.show', $aJob) }}" class="btn small" style="padding:6px 14px;font-size:12.5px">
                  مشاهده و ارسال رزومه
                </a>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      <!-- ۵. موقعیت‌های مکانی و دفاتر شرکت -->
      @php
        $locations = (array)($company->locations ?? []);
        if (!empty($locations)) {
            usort($locations, function($a, $b) {
                $pA = !empty($a['is_primary']) ? 1 : 0;
                $pB = !empty($b['is_primary']) ? 1 : 0;
                return $pB <=> $pA;
            });
        }
      @endphp
      @if($locations && count($locations) > 0)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">{!! svg_icon('map-pin', 15) !!} موقعیت‌های مکانی</h3>
          
          <div style="display:flex;flex-direction:column;gap:12px;margin-top:14px">
            @foreach($locations as $loc)
              @php
 $tInfo = \App\Models\Company::getLocationTypeInfo($loc['type'] ?? 'factory');
 $addrParts = array_filter([
 $loc['province_name'] ?? null,
 $loc['city_name'] ?? null,
 $loc['address'] ?? null,
 ]);
 @endphp
              <div style="background:#fbfcfe;border:1px solid var(--border);border-radius:10px;padding:14px">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px">
                  <span style="display:inline-flex;align-items:center;color:var(--primary)">{!! $tInfo['icon'] !!}</span>
                  <b style="color:var(--primary);font-size:15px">
                    {{ $tInfo['title'] }}
                  </b>
                </div>

                <p style="margin:4px 0 0;color:var(--text);font-size:14px;line-height:1.8">
                  {{ implode('، ', $addrParts) }}
                </p>

                @if(!empty($loc['phone']) || !empty($loc['phone2']))
                  <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px">
                    @if(!empty($loc['phone']))
                      <a href="tel:{{ $loc['phone'] }}" dir="ltr" class="btn ghost small" style="font-size:15px;padding:6px 12px;font-weight:700;display:inline-flex;align-items:center;gap:6px">
                        {!! svg_icon('phone', 15) !!} <span>{{ $loc['phone'] }}</span>
                      </a>
                    @endif
                    @if(!empty($loc['phone2']))
                      <a href="tel:{{ $loc['phone2'] }}" dir="ltr" class="btn ghost small" style="font-size:15px;padding:6px 12px;font-weight:700;display:inline-flex;align-items:center;gap:6px">
                        {!! svg_icon('phone', 15) !!} <span>{{ $loc['phone2'] }}</span>
                      </a>
                    @endif
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @elseif($company->factory_address || $company->address || $company->office_address)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">{!! svg_icon('map-pin', 15) !!} موقعیت‌های مکانی</h3>
          
          @if($company->factory_address || $company->address)
            <div style="margin-bottom:14px">
              <b style="color:var(--primary);font-size:14.5px">{!! svg_icon('factory', 15) !!} آدرس کارخانه / کارگاه:</b>
              <p style="margin:4px 0 0;color:var(--text)">
                @php
 $fParts = array_filter([$company->factory_province, $company->factory_city, $company->factory_address ?: $company->address]);
 @endphp
                {{ implode('، ', $fParts) }}
              </p>
            </div>
          @endif

          @if($company->office_address)
            <div>
              <b style="color:var(--primary);font-size:14.5px">{!! svg_icon('building', 15) !!} دفتر مرکزی / فروشگاه:</b>
              <p style="margin:4px 0 0;color:var(--text)">
                @php
 $oParts = array_filter([$company->office_province, $company->office_city, $company->office_address]);
 @endphp
                {{ implode('، ', $oParts) }}
              </p>
            </div>
          @endif
        </div>
      @endif
    </div>

    <!-- ستون کناری (چپ) -->
    <div>
      <!-- کارت ارتباط و اقدامات سریع (۲ دکمه در هر خط) -->
      @php
        $phones = $company->phone ? array_filter(array_map('trim', explode(';', $company->phone))) : [];
        $hasQuickActions = !empty($phones) || $company->email || $company->catalog || $company->website;
      @endphp
      @if($hasQuickActions)
        <div class="card" style="margin-bottom:20px;padding:16px">
          <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px">
            @foreach($phones as $pNum)
              <a href="tel:{{ $pNum }}" dir="ltr" class="btn" style="text-align:center;font-weight:700;display:flex;align-items:center;justify-content:center;gap:5px;padding:10px 8px;font-size:15px;background:#0284c7;color:#fff;text-decoration:none;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                {!! svg_icon('phone', 15) !!} <span>{{ $pNum }}</span>
              </a>
            @endforeach
            @if($company->email)
              <a href="mailto:{{ $company->email }}" class="btn ghost" style="text-align:center;font-size:12.5px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:5px;padding:9px 8px;text-decoration:none;white-space:nowrap">
                {!! svg_icon('mail', 14) !!} <span>ارسال ایمیل</span>
              </a>
            @endif
            @if($company->catalog)
              <a href="{{ asset($company->catalog) }}" target="_blank" class="btn ghost" style="text-align:center;font-size:12.5px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:5px;padding:9px 8px;text-decoration:none;white-space:nowrap">
                {!! svg_icon('file-text', 14) !!} <span>دریافت کاتالوگ</span>
              </a>
            @endif
            @if($company->website)
              <a href="{{ $company->website }}" target="_blank" rel="nofollow noopener" class="btn ghost" style="text-align:center;font-size:12.5px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:5px;padding:9px 8px;text-decoration:none;white-space:nowrap">
                {!! svg_icon('globe', 14) !!} <span>وب‌سایت رسمی</span>
              </a>
            @endif
          </div>
        </div>
      @endif

      <!-- افراد کلیدی و مدیران -->
      @if($company->contacts && count(array_filter($company->contacts)) > 0)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:16.5px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:8px">{!! svg_icon('users', 16) !!} افراد کلیدی و مسئولین</h3>
          <ul style="list-style:none;padding:0;margin:0;font-size:15px">
            @if(data_get($company->contacts, 'owner_name'))
              <li style="padding:9px 0;border-bottom:1px dashed var(--border)">
                <div style="color:var(--muted);font-size:13.5px;margin-bottom:2px">مالک شرکت:</div>
                <b style="font-size:15.5px">{{ data_get($company->contacts, 'owner_name') }}</b>
              </li>
            @endif

            @if(data_get($company->contacts, 'ceo_name'))
              <li style="padding:9px 0;border-bottom:1px dashed var(--border)">
                <div style="color:var(--muted);font-size:13.5px;margin-bottom:4px">مدیرعامل:</div>
                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;font-size:15.5px;font-weight:700">
                  <span>{{ data_get($company->contacts, 'ceo_name') }}</span>
                  @if(data_get($company->contacts, 'ceo_phone'))
                    <span style="color:#cbd5e1;font-size:14px">●</span>
                    <a href="tel:{{ data_get($company->contacts, 'ceo_phone') }}" dir="ltr" style="font-size:15px;font-weight:700;display:inline-flex;align-items:center;gap:5px">{!! svg_icon('phone', 15) !!} <span>{{ data_get($company->contacts, 'ceo_phone') }}</span></a>
                  @endif
                </div>
              </li>
            @endif

            @if(data_get($company->contacts, 'sales_name'))
              <li style="padding:9px 0;border-bottom:1px dashed var(--border)">
                <div style="color:var(--muted);font-size:13.5px;margin-bottom:4px">مدیر فروش:</div>
                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;font-size:15.5px;font-weight:700">
                  <span>{{ data_get($company->contacts, 'sales_name') }}</span>
                  @if(data_get($company->contacts, 'sales_phone'))
                    <span style="color:#cbd5e1;font-size:14px">●</span>
                    <a href="tel:{{ data_get($company->contacts, 'sales_phone') }}" dir="ltr" style="color:#059669;font-weight:700;font-size:15px;display:inline-flex;align-items:center;gap:5px">{!! svg_icon('phone', 15) !!} <span>{{ data_get($company->contacts, 'sales_phone') }}</span></a>
                  @endif
                </div>
              </li>
            @endif

            @if(data_get($company->contacts, 'purchase_name'))
              <li style="padding:9px 0;border-bottom:1px dashed var(--border)">
                <div style="color:var(--muted);font-size:13.5px;margin-bottom:4px">مسئول خرید / تدارکات:</div>
                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;font-size:15.5px;font-weight:700">
                  <span>{{ data_get($company->contacts, 'purchase_name') }}</span>
                  @if(data_get($company->contacts, 'purchase_phone'))
                    <span style="color:#cbd5e1;font-size:14px">●</span>
                    <a href="tel:{{ data_get($company->contacts, 'purchase_phone') }}" dir="ltr" style="font-size:15px;font-weight:700;display:inline-flex;align-items:center;gap:5px">{!! svg_icon('phone', 15) !!} <span>{{ data_get($company->contacts, 'purchase_phone') }}</span></a>
                  @endif
                </div>
              </li>
            @endif

            @if(data_get($company->contacts, 'support_mobile'))
              <li style="padding:9px 0">
                <div style="color:var(--muted);font-size:13.5px;margin-bottom:4px">همراه پشتیبانی:</div>
                <a href="tel:{{ data_get($company->contacts, 'support_mobile') }}" dir="ltr" style="font-size:15px;font-weight:700;display:inline-flex;align-items:center;gap:5px">{!! svg_icon('phone', 15) !!} <span>{{ data_get($company->contacts, 'support_mobile') }}</span></a>
              </li>
            @endif
          </ul>
        </div>
      @endif

      <!-- توانمندی‌ها و مشخصات فنی -->
      @if($company->tech_specs && count(array_filter($company->tech_specs)) > 0)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:16px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:8px">{!! svg_icon('settings', 15) !!} مشخصات فنی کارخانه</h3>
          <ul style="list-style:none;padding:0;margin:0;font-size:14px">
            @if(data_get($company->tech_specs, 'capacity'))
              <li style="padding:7px 0;border-bottom:1px dashed var(--border)">
                <span style="color:var(--muted)">ظرفیت تولید:</span>
                <b>{{ data_get($company->tech_specs, 'capacity') }}</b>
              </li>
            @endif
            @if(data_get($company->tech_specs, 'moq'))
              <li style="padding:7px 0;border-bottom:1px dashed var(--border)">
                <span style="color:var(--muted)">حداقل تیراژ سفارش:</span>
                <b>{{ data_get($company->tech_specs, 'moq') }}</b>
              </li>
            @endif
            @if(data_get($company->tech_specs, 'production_width'))
              <li style="padding:7px 0;border-bottom:1px dashed var(--border)">
                <span style="color:var(--muted)">عرض تولید:</span>
                <b>{{ data_get($company->tech_specs, 'production_width') }}</b>
              </li>
            @endif
            @if(data_get($company->tech_specs, 'gsm_range'))
              <li style="padding:7px 0;border-bottom:1px dashed var(--border)">
                <span style="color:var(--muted)">محدوده گراماژ:</span>
                <b>{{ data_get($company->tech_specs, 'gsm_range') }}</b>
              </li>
            @endif
            @if(data_get($company->tech_specs, 'custom_order'))
              <li style="padding:7px 0;border-bottom:1px dashed var(--border)">
                <span style="color:var(--muted)">سفارش اختصاصی:</span>
                <b>{{ data_get($company->tech_specs, 'custom_order') }}</b>
              </li>
            @endif
          </ul>

          @if(data_get($company->tech_specs, 'certificates'))
            @php
              $rawCerts = data_get($company->tech_specs, 'certificates');
              $certItems = is_array($rawCerts) ? $rawCerts : preg_split('/[;,،]+/u', (string) $rawCerts, -1, PREG_SPLIT_NO_EMPTY);
              $certItems = array_values(array_filter(array_map('trim', $certItems)));
            @endphp
            @if(!empty($certItems))
              <div style="margin-top:12px">
                <span style="font-size:14px;font-weight:600;color:var(--muted);display:block;margin-bottom:8px">گواهینامه‌ها:</span>
                <div style="display:flex;gap:6px;flex-wrap:wrap">
                  @foreach($certItems as $cert)
                    @php $isLatinCert = (bool) preg_match('/[A-Za-z0-9]/', $cert); @endphp
                    <span class="badge ok" style="font-size:13px;padding:5px 10px;@if($isLatinCert) font-family:ui-sans-serif,system-ui,-apple-system,sans-serif;direction:ltr;unicode-bidi:isolate; @endif">{{ $cert }}</span>
                  @endforeach
                </div>
              </div>
            @endif
          @endif
        </div>
      @endif

      <!-- شبکه‌های اجتماعی و پیام‌رسان‌ها (۲ دکمه در هر خط) -->
      @if($company->socials && count(array_filter($company->socials)) > 0)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:16px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:8px">{!! svg_icon('smartphone', 15) !!} شبکه‌های اجتماعی</h3>
          <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;margin-top:10px">
            @if(data_get($company->socials, 'whatsapp'))
              @php
                $waRaw = trim((string) data_get($company->socials, 'whatsapp'));
                if (str_starts_with($waRaw, 'http')) {
                    $waUrl = $waRaw;
                } else {
                    $waDigits = preg_replace('/\D+/', '', fa_to_en_digits($waRaw));
                    if (str_starts_with($waDigits, '0')) {
                        $waDigits = '98' . substr($waDigits, 1);
                    } elseif (str_starts_with($waDigits, '9') && strlen($waDigits) === 10) {
                        $waDigits = '98' . $waDigits;
                    }
                    $waUrl = 'https://wa.me/' . $waDigits;
                }
              @endphp
              <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn ghost small" style="text-align:center;border-color:#25D366;color:#15803d;background:#ecfdf5;display:flex;align-items:center;justify-content:center;gap:5px;padding:8px;font-weight:700">{!! svg_icon('message', 15) !!} <span>واتساپ</span></a>
            @endif
            @if(data_get($company->socials, 'instagram'))
              <a href="https://instagram.com/{{ ltrim(data_get($company->socials, 'instagram'), '@') }}" target="_blank" class="btn ghost small" style="text-align:center;border-color:#e1306c;color:#be185d;background:#fdf2f8;display:flex;align-items:center;justify-content:center;gap:5px;padding:8px;font-weight:700">{!! svg_icon('camera', 15) !!} <span>اینستاگرام</span></a>
            @endif
            @if(data_get($company->socials, 'telegram'))
              <a href="https://t.me/{{ ltrim(data_get($company->socials, 'telegram'), '@') }}" target="_blank" class="btn ghost small" style="text-align:center;border-color:#0088cc;color:#0369a1;background:#f0f9ff;display:flex;align-items:center;justify-content:center;gap:5px;padding:8px;font-weight:700">{!! svg_icon('send', 15) !!} <span>تلگرام</span></a>
            @endif
            @if(data_get($company->socials, 'bale'))
              <a href="{{ str_starts_with(data_get($company->socials, 'bale'), 'http') ? data_get($company->socials, 'bale') : 'https://ble.ir/' . ltrim(data_get($company->socials, 'bale'), '@') }}" target="_blank" class="btn ghost small" style="text-align:center;border-color:#f97316;color:#c2410c;background:#fff7ed;display:flex;align-items:center;justify-content:center;gap:5px;padding:8px;font-weight:700">{!! svg_icon('message', 15) !!} <span>بله / ایتا</span></a>
            @endif
          </div>
        </div>
      @endif

      <!-- اطلاعات صفحه (تاریخ ایجاد و به‌روزرسانی در یک خط) -->
      @php
        $shortCopyUrl = rtrim(url('/'), '/') . '/?p=' . $company->id;
        $shortDisplayUrl = preg_replace('~^https?://~', '', $shortCopyUrl);
      @endphp
      <div class="card" style="margin-bottom:20px;padding:16px">
        <h3 style="margin-top:0;font-size:16px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:8px">{!! svg_icon('info', 16) !!} اطلاعات صفحه</h3>
        
        <div style="display:flex;flex-direction:column;gap:12px;font-size:14px;color:#475569;margin-top:10px">
          <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;padding-bottom:10px;border-bottom:1px dashed var(--border)">
            <span style="display:inline-flex;align-items:center;gap:4px">{!! svg_icon('calendar', 15) !!} ایجاد: <b style="color:var(--text)">{{ jalali_date($company->created_at) }}</b></span>
            <span style="display:inline-flex;align-items:center;gap:4px">{!! svg_icon('clock', 15) !!} به‌روزرسانی: <b style="color:var(--text)">{{ jalali_date($company->updated_at) }}</b></span>
          </div>

          <div style="display:flex;align-items:center;gap:6px">
            <label for="short_link_input" style="font-size:14px;font-weight:700;color:#334155;white-space:nowrap;display:inline-flex;align-items:center;gap:4px;margin:0">
              {!! svg_icon('link', 14) !!} لینک کوتاه
            </label>
            <input type="text" id="short_link_input" value="{{ $shortDisplayUrl }}" data-copy-url="{{ $shortCopyUrl }}" readonly style="direction:ltr;text-align:left;background:#f8fafc;border:1px solid #cbd5e1;padding:7px 9px;border-radius:6px;font-size:14px;flex:1;min-width:0;color:#1e293b;font-family:ui-sans-serif,system-ui,-apple-system,sans-serif;box-sizing:border-box">
            <button type="button" id="copy_short_link_btn" onclick="copyShortLink()" class="btn" title="کپی لینک کوتاه" aria-label="کپی لینک کوتاه" style="background:#0284c7;color:#fff;padding:7px 10px;cursor:pointer;flex-shrink:0;border-radius:6px;display:inline-flex;align-items:center;justify-content:center">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- بخش شرکت‌های وابسته و هم‌گروه -->
  @if(isset($affiliateCompanies) && $affiliateCompanies->count())
    <section class="affiliate-companies-section" style="margin-top:0;margin-bottom:20px">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px">
        <h2 style="margin:0;font-size:20px;font-weight:900;color:#0f172a;display:flex;align-items:center;gap:8px">
          <span>{!! svg_icon('building', 16) !!}</span>
          <span>شرکت‌های وابسته</span>
        </h2>
        <span class="badge" style="background:#eef3f9;color:var(--primary);font-weight:700">{{ $affiliateCompanies->count() }} مجموعه مرتبط</span>
      </div>

      <div class="company-carousel-wrap" data-carousel="affiliates">
        <button type="button" class="company-carousel-nav-btn prev-btn" aria-label="قبلی" onclick="slideCarouselRow('carousel-affiliates', -1)">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>

        <div class="company-carousel-viewport" id="carousel-affiliates">
          <div class="company-carousel-track">
            @foreach($affiliateCompanies as $affiliate)
              <div class="company-carousel-slide">
                @include('partials.card', ['company' => $affiliate])
              </div>
            @endforeach
          </div>
        </div>

        <button type="button" class="company-carousel-nav-btn next-btn" aria-label="بعدی" onclick="slideCarouselRow('carousel-affiliates', 1)">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
      </div>
    </section>
  @endif

  <!-- بخش شرکت‌های مشابه (اسلایدر ۶ تایی با چرخش خودکار) -->
  @if(isset($similarCompanies) && $similarCompanies->count())
    <section class="similar-companies-section" style="margin-top:0">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px">
        <div>
          <h2 style="margin:0;font-size:20px;font-weight:900;color:#0f172a;display:flex;align-items:center;gap:8px">
            <span>{!! svg_icon('factory', 15) !!}</span>
            <span>شرکت‌های مشابه</span>
          </h2>
        </div>
        @if($company->category)
          <a href="{{ $company->category->url }}" style="font-size:13px;font-weight:700;color:var(--primary);display:inline-flex;align-items:center;gap:4px">
            <span>مشاهده همه</span>
            <span>←</span>
          </a>
        @endif
      </div>

      <div class="company-carousel-wrap" data-carousel="similar">
        <button type="button" class="company-carousel-nav-btn prev-btn" aria-label="قبلی" onclick="slideCarouselRow('carousel-similar', -1)">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>

        <div class="company-carousel-viewport" id="carousel-similar">
          <div class="company-carousel-track">
            @foreach($similarCompanies as $similar)
              <div class="company-carousel-slide">
                @include('partials.card', ['company' => $similar])
              </div>
            @endforeach
          </div>
        </div>

        <button type="button" class="company-carousel-nav-btn next-btn" aria-label="بعدی" onclick="slideCarouselRow('carousel-similar', 1)">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>
      </div>
    </section>
  @endif

  @if((isset($affiliateCompanies) && $affiliateCompanies->count()) || (isset($similarCompanies) && $similarCompanies->count()))
    <style>
    /* استایل‌های کاروسل ۶ تایی شرکت‌های مشابه */
    .company-carousel-wrap {
      position: relative;
      width: 100%;
      margin: 14px 0 0;
    }
    .company-carousel-viewport {
      overflow-x: auto;
      scroll-behavior: smooth;
      scrollbar-width: none;
      -ms-overflow-style: none;
      padding: 8px 4px 14px;
      -webkit-overflow-scrolling: touch;
    }
    .company-carousel-viewport::-webkit-scrollbar {
      display: none;
    }
    .company-carousel-track {
      display: flex;
      gap: 14px;
      align-items: stretch;
    }
    .company-carousel-slide {
      flex: 0 0 calc((100% - 70px) / 6);
      min-width: calc((100% - 70px) / 6);
      max-width: calc((100% - 70px) / 6);
      box-sizing: border-box;
      display: flex;
    }
    .company-carousel-slide > .company-card {
      width: 100%;
    }
    @media (max-width: 1024px) {
      .company-carousel-slide {
        flex: 0 0 calc((100% - 42px) / 4);
        min-width: calc((100% - 42px) / 4);
        max-width: calc((100% - 42px) / 4);
      }
    }
    @media (max-width: 768px) {
      .company-carousel-track {
        gap: 12px;
      }
      .company-carousel-slide {
        flex: 0 0 calc((100% - 24px) / 3);
        min-width: calc((100% - 24px) / 3);
        max-width: calc((100% - 24px) / 3);
      }
      .company-carousel-nav-btn {
        display: none;
      }
    }
    @media (max-width: 540px) {
      .company-carousel-slide {
        flex: 0 0 calc((100% - 12px) / 2);
        min-width: calc((100% - 12px) / 2);
        max-width: calc((100% - 12px) / 2);
      }
    }
    @media (max-width: 380px) {
      .company-carousel-slide {
        flex: 0 0 75%;
        min-width: 75%;
        max-width: 75%;
      }
    }

    .company-carousel-nav-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #ffffff;
      border: 1.5px solid #e2e8f0;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 10;
      color: #1e293b;
      transition: all 0.2s ease;
      user-select: none;
    }
    .company-carousel-nav-btn:hover {
      background: var(--primary);
      border-color: var(--primary);
      color: #ffffff;
      transform: translateY(-50%) scale(1.08);
      box-shadow: 0 6px 18px rgba(31, 95, 168, 0.25);
    }
    .company-carousel-nav-btn.prev-btn {
      right: -12px;
    }
    .company-carousel-nav-btn.next-btn {
      left: -12px;
    }
    </style>
  @endif

  <script>
 function slideCarouselRow(carouselId, dir) {
 const vp = document.getElementById(carouselId);
 if (!vp) return;
 const slide = vp.querySelector('.company-carousel-slide');
 const step = slide ? (slide.offsetWidth + 14) : 200;
 const maxScroll = vp.scrollWidth - vp.clientWidth;
 if (maxScroll <= 5) return;

 const isRTLNegative = (vp.scrollLeft <= 0);
 const currentScroll = Math.abs(vp.scrollLeft);

 if (dir > 0) {
 if (currentScroll >= maxScroll - 20) {
 vp.scrollTo({ left: 0, behavior: 'smooth' });
 } else {
 const scrollAmount = isRTLNegative ? -step : step;
 vp.scrollBy({ left: scrollAmount, behavior: 'smooth' });
 }
 } else {
 if (currentScroll <= 20) {
 const endPos = isRTLNegative ? -maxScroll : maxScroll;
 vp.scrollTo({ left: endPos, behavior: 'smooth' });
 } else {
 const scrollAmount = isRTLNegative ? step : -step;
 vp.scrollBy({ left: scrollAmount, behavior: 'smooth' });
 }
 }
 }

 // راه‌اندازی چرخش خودکار اسلایدرها (در صورت نیاز به اسکرول)
 document.addEventListener('DOMContentLoaded', function () {
 document.querySelectorAll('.company-carousel-wrap[data-carousel]').forEach(wrap => {
 const vp = wrap.querySelector('.company-carousel-viewport');
 if (!vp) return;
 const isScrollable = (vp.scrollWidth - vp.clientWidth) > 5;
 if (!isScrollable) {
 wrap.querySelectorAll('.company-carousel-nav-btn').forEach(b => { b.style.display = 'none'; });
 return;
 }

 let timer = null;
 function startAutoScroll() {
 if (timer) clearInterval(timer);
 timer = setInterval(() => {
 slideCarouselRow(vp.id, 1);
 }, 3500);
 }

 function stopAutoScroll() {
 if (timer) {
 clearInterval(timer);
 timer = null;
 }
 }

 wrap.addEventListener('mouseenter', stopAutoScroll);
 wrap.addEventListener('mouseleave', startAutoScroll);
 wrap.addEventListener('touchstart', stopAutoScroll, { passive: true });
 wrap.addEventListener('touchend', () => {
 setTimeout(startAutoScroll, 2000);
 }, { passive: true });

 startAutoScroll();
 });
 });
 function copyShortLink() {
 const input = document.getElementById('short_link_input');
 const btn = document.getElementById('copy_short_link_btn');
 if (!input) return;

 const fullUrl = input.getAttribute('data-copy-url') || input.value;

 if (navigator.clipboard && window.isSecureContext) {
 navigator.clipboard.writeText(fullUrl).then(() => {
 onCopied();
 }).catch(() => {
 fallbackCopy();
 });
 } else {
 fallbackCopy();
 }

 function fallbackCopy() {
 try {
 const tmp = document.createElement('textarea');
 tmp.value = fullUrl;
 tmp.style.position = 'fixed';
 tmp.style.opacity = '0';
 document.body.appendChild(tmp);
 tmp.select();
 document.execCommand('copy');
 document.body.removeChild(tmp);
 onCopied();
 } catch (err) {
 alert('خطا در کپی خودکار لینک. لطفاً متن داخل کادر را به صورت دستی کپی نمایید.');
 }
 }

 function onCopied() {
 if (btn) {
 const origHtml = btn.innerHTML;
 btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
 btn.style.background = '#16a34a';
 setTimeout(() => {
 btn.innerHTML = origHtml;
 btn.style.background = '#0284c7';
 }, 2000);
 }
 }
 }

 function openCommentNotice() {
 const box = document.getElementById('comment-notice-box');
 if (!box) return;
 box.style.display = box.style.display === 'none' ? 'block' : 'none';
 }

 function toggleBookmark() {
 @guest
 window.location.href = "{{ route('login') }}";
 return;
 @endguest

 const headBtn = document.getElementById('bookmark-btn-head');
 const headIcon = document.getElementById('bookmark-icon-head');
 const headText = document.getElementById('bookmark-text-head');

 fetch("{{ route('companies.bookmark', $company) }}", {
 method: 'POST',
 headers: {
 'Content-Type': 'application/json',
 'X-CSRF-TOKEN': '{{ csrf_token() }}',
 'Accept': 'application/json'
 }
 })
 .then(res => {
 if (res.status === 401) {
 window.location.href = "{{ route('login') }}";
 return null;
 }
 return res.json();
 })
 .then(data => {
 if (!data) return;
 const countEl = document.getElementById('header-bookmarks-count');
 if (countEl) {
 const cur = parseInt((countEl.textContent || '0').replace(/[^\d]/g, ''), 10) || 0;
 countEl.textContent = (data.saved ? cur + 1 : Math.max(0, cur - 1)).toLocaleString('en-US');
 }
 if (data.saved) {
 if (headBtn) {
 headBtn.style.background = '#f0fdf4';
 headBtn.style.color = '#166534';
 headBtn.style.borderColor = '#86efac';
 if (headIcon) { headIcon.setAttribute('fill', 'currentColor'); headIcon.style.color = '#16a34a'; }
 if (headText) headText.textContent = 'ذخیره شده';
 }
 } else {
 if (headBtn) {
 headBtn.style.background = '#fff';
 headBtn.style.color = '#475569';
 headBtn.style.borderColor = '#cbd5e1';
 if (headIcon) { headIcon.setAttribute('fill', 'none'); headIcon.style.color = '#64748b'; }
 if (headText) headText.textContent = 'ذخیره';
 }
 }
 })
 .catch(err => {
 console.error('Error toggling bookmark:', err);
 });
 }

 document.addEventListener('click', function (e) {
   const link = e.target.closest('a[href]');
   if (!link) return;
   const href = link.getAttribute('href') || '';
   let channel = null;
   if (href.startsWith('tel:')) channel = 'phone';
   else if (href.includes('wa.me')) channel = 'whatsapp';
   @if($company->website)
   else if (href === @json($company->website)) channel = 'website';
   @endif
   if (!channel) return;
   fetch('{{ route('track.company.click', $company) }}', {
     method: 'POST',
     keepalive: true,
     headers: {
       'Content-Type': 'application/json',
       'X-CSRF-TOKEN': '{{ csrf_token() }}'
     },
     body: JSON.stringify({ channel })
   }).catch(() => {});
 });
 </script>
</div>
@endsection
