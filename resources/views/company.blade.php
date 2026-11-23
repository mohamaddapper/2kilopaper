@extends('layouts.app', [
  'title' => $company->name . ($company->slogan ? ' - ' . $company->slogan : ' | ' . $company->category->name . ' در ' . $company->city->name),
  'description' => \Illuminate\Support\Str::limit(strip_tags($company->description), 150),
  'canonical' => route('company', $company),
])

@push('head')
<script type="application/ld+json">{!! json_encode(array_filter([
  '@context' => 'https://schema.org',
  '@type' => 'LocalBusiness',
  'name' => $company->name,
  'description' => $company->description,
  'telephone' => $company->phone,
  'url' => $company->website ?: route('company', $company),
  'image' => $company->logo ? asset($company->logo) : null,
  'address' => [
    '@type' => 'PostalAddress',
    'addressLocality' => $company->city->name,
    'streetAddress' => $company->factory_address ?: $company->address,
    'addressCountry' => 'IR',
  ],
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
<div style="max-width:1050px;margin:15px auto">

  <!-- هدر پروفایل شرکت -->
  <div class="company-head" style="margin-bottom:20px;position:relative;background:#fff;border:1px solid var(--border);border-radius:12px;padding:24px">
    <div class="logo-box" style="width:100px;height:100px;border-radius:12px;overflow:hidden;border:1px solid var(--border);background:#fff">
      @if($company->logo)
        <img src="{{ asset($company->logo) }}" alt="{{ $company->name }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:contain;padding:4px">
      @else
        <span style="font-size:32px;font-weight:800;color:var(--primary)">{{ mb_substr($company->name, 0, 1) }}</span>
      @endif
    </div>

    <div style="flex:1;min-width:250px">
      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <h1 style="margin:0;font-size:24px;color:var(--text)">{{ $company->name }}</h1>
        @if(auth()->check() && (auth()->id() === $company->user_id || auth()->user()->is_admin))
          <a href="{{ route('companies.edit', $company) }}" class="btn small ghost" style="font-size:12px;padding:3px 10px;font-weight:700">
            ✏️ ویرایش
          </a>
        @endif
        @if($company->activeTier() === 'gold')
          <span class="badge gold" style="font-size:13px;padding:3px 12px">★ ویژه طلایی</span>
        @elseif($company->activeTier() === 'silver')
          <span class="badge silver" style="font-size:13px;padding:3px 12px">★ ویژه نقره‌ای</span>
        @elseif($company->activeTier() === 'bronze')
          <span class="badge bronze" style="font-size:13px;padding:3px 12px">★ ویژه برنزی</span>
        @endif
      </div>

      @if($company->slogan)
        <p style="margin:6px 0;font-size:15px;color:var(--primary);font-weight:600">{{ $company->slogan }}</p>
      @endif

      <div style="display:flex;gap:12px;color:var(--muted);font-size:13.5px;margin-top:6px;flex-wrap:wrap;align-items:center">
        <span>📍 {{ $company->city->name }}</span>
        @if($company->email)
          <span>✉️ <b>ایمیل:</b> {{ $company->email }}</span>
        @endif
        @if(data_get($company->tech_specs, 'established_year'))
          <span>📅 <b>سال تأسیس:</b> {{ data_get($company->tech_specs, 'established_year') }}</span>
        @endif
      </div>

      <!-- برچسب‌های دسته‌بندی و زمینه‌های فعالیت -->
      <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;align-items:center">
        @if($company->category)
          <a href="{{ route('directory', ['cat' => $company->category_id]) }}" class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;border:1px solid #bae6fd;text-decoration:none">
            {{ $company->category->name }}
          </a>
        @endif
        @if($company->relationLoaded('categories'))
          @foreach($company->categories as $secCat)
            @if($secCat->id !== $company->category_id)
              <a href="{{ route('directory', ['cat' => $secCat->id]) }}" class="badge" style="background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;text-decoration:none">
                {{ $secCat->name }}
              </a>
            @endif
          @endforeach
        @endif
      </div>
    </div>

    <!-- دکمه‌های اقدام سریع -->
    <div style="display:flex;gap:8px;flex-direction:column;min-width:180px">
      @if(auth()->check() && (auth()->id() === $company->user_id || auth()->user()->is_admin))
        <a href="{{ route('companies.edit', $company) }}" class="btn" style="background:#0284c7;text-align:center;font-weight:700;font-size:13.5px">
          ✏️ ویرایش اطلاعات شرکت
        </a>
      @endif
      @if($company->phone)
        <a href="tel:{{ $company->phone }}" class="btn" style="text-align:center;font-weight:700">📞 تماس: {{ $company->phone }}</a>
      @endif
      @if($company->email)
        <a href="mailto:{{ $company->email }}" class="btn ghost" style="text-align:center;font-size:13px">✉️ ارسال ایمیل</a>
      @endif
      @if($company->catalog)
        <a href="{{ asset($company->catalog) }}" target="_blank" class="btn ghost" style="text-align:center;font-size:13px">📄 دریافت کاتالوگ (PDF)</a>
      @endif
      @if($company->website)
        <a href="{{ $company->website }}" target="_blank" rel="nofollow noopener" class="btn ghost" style="text-align:center;font-size:13px">🌐 وب‌سایت رسمی</a>
      @endif
    </div>
  </div>

  <!-- برچسب‌های خدمات و محصولات تخصصی -->
  @if($company->services && count($company->services) > 0)
    <div class="card" style="margin-bottom:20px;padding:14px 18px">
      <div style="display:flex;gap:8px;flex-wrap:wrap">
        @foreach($company->services as $srv)
          <span class="badge" style="background:#eef3f9;color:#1e3a8a;font-size:13px;padding:5px 12px;border:1px solid #dbeafe">{{ $srv }}</span>
        @endforeach
      </div>
    </div>
  @endif

  <!-- چیدمان دو ستونه محتوا -->
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
    
    <!-- ستون اصلی (راست) -->
    <div>
      <!-- متن معرفی شرکت -->
      <div class="card" style="margin-bottom:20px">
        <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">شرح کالا و خدمات</h3>
        <div style="line-height:2;color:#334155;font-size:15px;white-space:pre-line">{!! e($company->description) !!}</div>
      </div>

      <!-- فرصت‌های شغلی فعال شرکت -->
      @php
        $activeJobs = $company->jobPostings()->active()->latest()->get();
      @endphp
      @if($activeJobs->count() > 0)
        <div class="card" style="margin-bottom:20px;border:1px solid #bae6fd;background:#f0f9ff">
          <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #e0f2fe;padding-bottom:10px;margin-bottom:12px">
            <h3 style="margin:0;font-size:17px;color:#0369a1;display:flex;align-items:center;gap:6px">
              <span>💼 فرصت‌های شغلی و استخدام فعال</span>
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
                      <span style="color:#0284c7">🚌 سرویس دارد</span>
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

      <!-- موقعیت‌های مکانی و دفاتر شرکت -->
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
          <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">📍 موقعیت‌های مکانی</h3>
          
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
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;margin-bottom:6px">
                  <div style="display:flex;align-items:center;gap:6px">
                    <span style="font-size:19px">{{ $tInfo['icon'] }}</span>
                    <b style="color:var(--primary);font-size:15px">
                      {{ $tInfo['title'] }}
                    </b>
                  </div>
                  <div style="display:flex;gap:6px;flex-wrap:wrap">
                    @if(!empty($loc['phone']))
                      <a href="tel:{{ $loc['phone'] }}" class="btn ghost small" style="font-size:12.5px;padding:4px 12px;font-weight:700">
                        📞 {{ $loc['phone'] }}
                      </a>
                    @endif
                    @if(!empty($loc['phone2']))
                      <a href="tel:{{ $loc['phone2'] }}" class="btn ghost small" style="font-size:12.5px;padding:4px 12px;font-weight:700">
                        📞 {{ $loc['phone2'] }}
                      </a>
                    @endif
                  </div>
                </div>

                <p style="margin:4px 0 0;color:var(--text);font-size:14px;line-height:1.8">
                  {{ implode('، ', $addrParts) }}
                </p>
              </div>
            @endforeach
          </div>
        </div>
      @elseif($company->factory_address || $company->address || $company->office_address)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">📍 موقعیت‌های مکانی</h3>
          
          @if($company->factory_address || $company->address)
            <div style="margin-bottom:14px">
              <b style="color:var(--primary);font-size:14.5px">🏭 آدرس کارخانه / کارگاه:</b>
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
              <b style="color:var(--primary);font-size:14.5px">🏢 دفتر مرکزی / فروشگاه:</b>
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

      <!-- گالری تصاویر -->
      @if($company->gallery && count($company->gallery) > 0)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">📸 تصاویر</h3>
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px;margin-top:14px">
            @foreach($company->gallery as $img)
              <a href="{{ asset($img) }}" target="_blank" style="display:block;height:140px;border-radius:8px;overflow:hidden;border:1px solid var(--border)">
                <img src="{{ asset($img) }}" alt="تصویر {{ $company->name }}" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
              </a>
            @endforeach
          </div>
        </div>
      @endif

      <!-- ویدیو معرفی -->
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
            <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">🎥 ویدیو</h3>
            <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:8px">
              <iframe src="{{ $embedUrl }}" loading="lazy" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0" allowfullscreen></iframe>
            </div>
          </div>
        @elseif(str_contains($rawVideo, '<iframe'))
          <div class="card" style="margin-bottom:20px">
            <h3 style="margin-top:0;font-size:17px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:10px">🎥 ویدیو</h3>
            <div style="margin-top:10px;text-align:center">
              {!! $rawVideo !!}
            </div>
          </div>
        @endif
      @endif
    </div>

    <!-- ستون کناری (چپ) -->
    <div>
      <!-- افراد کلیدی و مدیران -->
      @if($company->contacts && count(array_filter($company->contacts)) > 0)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:16px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:8px">👥 افراد کلیدی و مسئولین</h3>
          <ul style="list-style:none;padding:0;margin:0;font-size:13.5px">
            @if(data_get($company->contacts, 'ceo_name'))
              <li style="padding:8px 0;border-bottom:1px dashed var(--border)">
                <div style="color:var(--muted)">مدیرعامل:</div>
                <b>{{ data_get($company->contacts, 'ceo_name') }}</b>
                @if(data_get($company->contacts, 'ceo_phone'))
                  <div><a href="tel:{{ data_get($company->contacts, 'ceo_phone') }}">{{ data_get($company->contacts, 'ceo_phone') }}</a></div>
                @endif
              </li>
            @endif

            @if(data_get($company->contacts, 'sales_name'))
              <li style="padding:8px 0;border-bottom:1px dashed var(--border)">
                <div style="color:var(--muted)">مدیر فروش:</div>
                <b>{{ data_get($company->contacts, 'sales_name') }}</b>
                @if(data_get($company->contacts, 'sales_phone'))
                  <div><a href="tel:{{ data_get($company->contacts, 'sales_phone') }}" style="color:#059669;font-weight:700">📞 {{ data_get($company->contacts, 'sales_phone') }}</a></div>
                @endif
              </li>
            @endif

            @if(data_get($company->contacts, 'purchase_name'))
              <li style="padding:8px 0;border-bottom:1px dashed var(--border)">
                <div style="color:var(--muted)">مسئول خرید / تدارکات:</div>
                <b>{{ data_get($company->contacts, 'purchase_name') }}</b>
                @if(data_get($company->contacts, 'purchase_phone'))
                  <div><a href="tel:{{ data_get($company->contacts, 'purchase_phone') }}">{{ data_get($company->contacts, 'purchase_phone') }}</a></div>
                @endif
              </li>
            @endif

            @if(data_get($company->contacts, 'support_mobile'))
              <li style="padding:8px 0">
                <div style="color:var(--muted)">همراه پشتیبانی:</div>
                <a href="tel:{{ data_get($company->contacts, 'support_mobile') }}">{{ data_get($company->contacts, 'support_mobile') }}</a>
              </li>
            @endif
          </ul>
        </div>
      @endif

      <!-- توانمندی‌ها و مشخصات فنی -->
      @if($company->tech_specs && count(array_filter($company->tech_specs)) > 0)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:16px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:8px">⚙️ مشخصات فنی کارخانه</h3>
          <ul style="list-style:none;padding:0;margin:0;font-size:13.5px">
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
            <div style="margin-top:12px">
              <span style="font-size:12.5px;color:var(--muted);display:block;margin-bottom:6px">گواهینامه‌ها:</span>
              <div style="display:flex;gap:4px;flex-wrap:wrap">
                @foreach((array)data_get($company->tech_specs, 'certificates') as $cert)
                  <span class="badge ok" style="font-size:11px">{{ $cert }}</span>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      @endif

      <!-- شبکه‌های اجتماعی و پیام‌رسان‌ها -->
      @if($company->socials && count(array_filter($company->socials)) > 0)
        <div class="card" style="margin-bottom:20px">
          <h3 style="margin-top:0;font-size:16px;color:var(--text);border-bottom:1px solid var(--border);padding-bottom:8px">📱 شبکه‌های اجتماعی</h3>
          <div style="display:flex;gap:8px;flex-direction:column;margin-top:8px">
            @if(data_get($company->socials, 'instagram'))
              <a href="https://instagram.com/{{ ltrim(data_get($company->socials, 'instagram'), '@') }}" target="_blank" class="btn ghost small" style="text-align:center">📷 اینستاگرام</a>
            @endif
            @if(data_get($company->socials, 'telegram'))
              <a href="https://t.me/{{ ltrim(data_get($company->socials, 'telegram'), '@') }}" target="_blank" class="btn ghost small" style="text-align:center">✈️ تلگرام</a>
            @endif
            @if(data_get($company->socials, 'bale'))
              <a href="{{ str_starts_with(data_get($company->socials, 'bale'), 'http') ? data_get($company->socials, 'bale') : 'https://ble.ir/' . ltrim(data_get($company->socials, 'bale'), '@') }}" target="_blank" class="btn ghost small" style="text-align:center">💬 پیام‌رسان بله / ایتا</a>
            @endif
          </div>
        </div>
      @endif

    </div>
  </div>

  <!-- بخش اطلاعات صفحه (دقیقاً مشابه تصویر ارسالی) -->
  <div style="margin-top:30px">
    <div style="display:flex;align-items:center;margin-bottom:16px">
      <div style="flex:1;height:1px;background:#e2e8f0"></div>
      <span style="padding:0 16px;color:#64748b;font-weight:700;font-size:15px">اطلاعات صفحه</span>
      <div style="flex:1;height:1px;background:#e2e8f0"></div>
    </div>

    <div class="card" style="padding:22px;margin-bottom:24px">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
        <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:#475569">
          <span>📅 <b>تاریخ ایجاد صفحه:</b></span>
          <span style="font-weight:700;color:var(--text)">{{ jalali_date($company->created_at) }}</span>
        </div>

        <div style="display:flex;align-items:center;gap:8px;font-size:14px;color:#475569">
          <span>⏰ <b>آخرین به‌روزرسانی:</b></span>
          <span style="font-weight:700;color:var(--text)">{{ jalali_date($company->updated_at) }}</span>
        </div>
      </div>

      <div style="margin-top:18px;padding-top:16px;border-top:1px dashed var(--border)">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
          <label for="short_link_input" style="font-size:13.5px;font-weight:700;color:#334155;margin:0">
            🔗 لینک جهت درج در شبکه‌های اجتماعی:
          </label>
          <div style="display:flex;gap:8px;align-items:center;flex:1;max-width:440px;min-width:260px">
            <input type="text" id="short_link_input" value="{{ url('/?p=' . $company->id) }}" readonly style="direction:ltr;text-align:left;background:#f8fafc;border:1px solid #cbd5e1;padding:8px 12px;border-radius:6px;font-size:13.5px;flex:1;color:#1e293b;font-family:inherit">
            <button type="button" id="copy_short_link_btn" onclick="copyShortLink()" class="btn" style="background:#0284c7;font-size:13px;padding:8px 18px;white-space:nowrap;font-weight:700;cursor:pointer">
              برای کپی کلیک کنید
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
  function copyShortLink() {
    const input = document.getElementById('short_link_input');
    const btn = document.getElementById('copy_short_link_btn');
    if (!input) return;

    input.select();
    input.setSelectionRange(0, 99999);

    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(input.value).then(() => {
        onCopied();
      }).catch(() => {
        fallbackCopy();
      });
    } else {
      fallbackCopy();
    }

    function fallbackCopy() {
      try {
        document.execCommand('copy');
        onCopied();
      } catch (err) {
        alert('خطا در کپی خودکار لینک. لطفاً متن داخل کادر را به صورت دستی کپی نمایید.');
      }
    }

    function onCopied() {
      if (btn) {
        const origText = btn.textContent;
        btn.textContent = '✓ کپی شد!';
        btn.style.background = '#16a34a';
        setTimeout(() => {
          btn.textContent = origText;
          btn.style.background = '#0284c7';
        }, 2000);
      }
    }
  }
  </script>
</div>
@endsection
