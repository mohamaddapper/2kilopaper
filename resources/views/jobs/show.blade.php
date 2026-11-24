@extends('layouts.app', [
 'title' => $job->title . ' در ' . ($job->company->name ?? 'کارخانجات کاغذ و کارتن'),
  'description' => 'استخدام ' . $job->title . ' در ' . ($job->company->name ?? 'صنعت چاپ و بسته‌بندی') . ' - حقوق، شرایط سرویس و ارسال رزومه'
])

@section('content')
<div style="margin:20px 0 40px">
  
  <!-- ناوبری سلسله‌مراتبی (Breadcrumb) -->
  <div style="font-size:13px;color:var(--muted);margin-bottom:16px;display:flex;align-items:center;gap:6px;flex-wrap:wrap">
    <a href="{{ route('home') }}" style="color:var(--muted);text-decoration:none">خانه</a>
    <span>/</span>
    <a href="{{ route('jobs.index') }}" style="color:var(--muted);text-decoration:none">فرصت‌های شغلی</a>
    @if($job->category)
      <span>/</span>
      <a href="{{ route('jobs.index', ['cat' => $job->category_id]) }}" style="color:var(--muted);text-decoration:none">{{ $job->category->name }}</a>
    @endif
    <span>/</span>
    <span style="color:var(--text);font-weight:600">{{ $job->title }}</span>
  </div>

  <div class="job-detail-layout">
    
    <!-- محتوای اصلی شغل (سمت راست در RTL) -->
    <div>
      
      <!-- سربرگ آگهی -->
      <div class="card" style="padding:24px;margin-bottom:20px;border-radius:12px;background:#fff">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
          <div>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px">
              <h1 style="margin:0;font-size:22px;color:var(--text)">{{ $job->title }}</h1>
              @if($job->is_immediate)
                <span class="badge bad" style="font-size:11.5px;font-weight:700">فوری</span>
              @endif
              @if($job->is_featured)
                <span class="badge gold" style="font-size:11.5px">ویژه</span>
              @endif
            </div>

            <div style="display:flex;align-items:center;gap:12px;font-size:14px;color:var(--muted);flex-wrap:wrap">
              @if($job->company)
                <a href="{{ $job->company->url }}" style="color:var(--primary);font-weight:700;text-decoration:none">
                  {!! svg_icon('building', 15) !!} {{ $job->company->name }}
                </a>
              @endif
              <span>{!! svg_icon('map-pin', 15) !!} {{ $job->province->name ?? '' }} {{ $job->city ? '· ' . $job->city->name : '' }}</span>
              <span>•</span>
              <span>{!! svg_icon('eye', 15) !!} {{ $job->views_count }} بازدید</span>
              <span>•</span>
              <span>{!! svg_icon('calendar', 15) !!} {{ $job->created_at->diffForHumans() }}</span>
            </div>
          </div>

          <a href="#apply_section" class="btn" style="padding:10px 24px;font-size:14px;font-weight:700">
            ارسال رزومه و درخواست
          </a>
        </div>

        <hr style="border:none;border-top:1px solid var(--border);margin:18px 0">

        <!-- مشخصات و شرایط کلیدی شغل (شبکه خلاصه) -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px">
          <div style="background:#f8fafc;padding:12px 14px;border-radius:8px">
            <span style="font-size:12px;color:var(--muted);display:block">نوع همکاری</span>
            <b style="font-size:14px;color:var(--text)">{{ $job->job_type_label }}</b>
          </div>

          <div style="background:#f8fafc;padding:12px 14px;border-radius:8px">
            <span style="font-size:12px;color:var(--muted);display:block">شیفت کاری</span>
            <b style="font-size:14px;color:var(--text)">{{ $job->work_shift_label ?: 'عادی / روز' }}</b>
          </div>

          <div style="background:#f8fafc;padding:12px 14px;border-radius:8px">
            <span style="font-size:12px;color:var(--muted);display:block">میزان حقوق پیشنهادی</span>
            <b style="font-size:14px;color:#16a34a">{{ $job->salary_label }}</b>
          </div>

          <div style="background:#f8fafc;padding:12px 14px;border-radius:8px">
            <span style="font-size:12px;color:var(--muted);display:block">محل کار</span>
            <b style="font-size:14px;color:var(--text)">{{ $job->workplace_type_label }}</b>
          </div>

          <div style="background:#f8fafc;padding:12px 14px;border-radius:8px">
            <span style="font-size:12px;color:var(--muted);display:block">سابقه کار مورد نیاز</span>
            <b style="font-size:14px;color:var(--text)">{{ $job->experience_label }}</b>
          </div>

          <div style="background:#f8fafc;padding:12px 14px;border-radius:8px">
            <span style="font-size:12px;color:var(--muted);display:block">جنسیت و خدمت سربازی</span>
            <b style="font-size:14px;color:var(--text)">{{ $job->gender_label }} {{ $job->military_service === 'exempt_or_done' ? '· کارت پایان خدمت' : '' }}</b>
          </div>
        </div>

        @if($job->address)
          <div style="margin-top:14px;font-size:13.5px;color:var(--muted);background:#fffdf0;border:1px solid #fef08a;padding:10px 14px;border-radius:8px">
            <b>{!! svg_icon('map-pin', 15) !!} آدرس دقیق / نام شهرک صنعتی:</b> {{ $job->address }}
          </div>
        @endif
      </div>

      <!-- امکانات رفاهی و مزایای کارخانه -->
      @if(!empty($job->benefits) && count(array_filter($job->benefits)) > 0)
        <div class="card" style="padding:20px;margin-bottom:20px;border-radius:12px;background:#fff">
          <h3 style="margin:0 0 14px;font-size:16px;color:var(--text);display:flex;align-items:center;gap:6px">
            <span>{!! svg_icon('gift', 15) !!} امکانات و تسهیلات رفاهی کارفرما</span>
          </h3>

          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:10px">
            @if(!empty($job->benefits['has_service']))
              <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#eff6ff;border-radius:8px;color:#1e40af;font-size:13.5px">
                <span style="font-size:18px">{!! svg_icon('bus', 15) !!}</span>
                <div>
                  <b>سرویس رفت‌وآمد: دارد</b>
                  @if(!empty($job->benefits['service_routes']))
                    <div style="font-size:12px;color:#3b82f6;margin-top:2px">مسیرها: {{ $job->benefits['service_routes'] }}</div>
                  @endif
                </div>
              </div>
            @endif

            @if(!empty($job->benefits['has_meal']))
              <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#f0fdf4;border-radius:8px;color:#166534;font-size:13.5px">
                <span style="font-size:18px">{!! svg_icon('utensils', 15) !!}</span>
                <b>وعده غذایی / ناهار گرم</b>
              </div>
            @endif

            @if(!empty($job->benefits['has_accommodation']))
              <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#fef3c7;border-radius:8px;color:#92400e;font-size:13.5px">
                <span style="font-size:18px">{!! svg_icon('bed', 15) !!}</span>
                <b>خوابگاه / محل اسکان پرسنل</b>
              </div>
            @endif

            @if(!empty($job->benefits['has_insurance']))
              <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#f8fafc;border-radius:8px;color:#334155;font-size:13.5px">
                <span style="font-size:18px">{!! svg_icon('shield', 15) !!}</span>
                <b>بیمه تأمین اجتماعی از روز اول</b>
              </div>
            @endif

            @if(!empty($job->benefits['has_supplementary_insurance']))
              <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#f8fafc;border-radius:8px;color:#334155;font-size:13.5px">
                <span style="font-size:18px">{!! svg_icon('heart-pulse', 15) !!}</span>
                <b>بیمه درمان تکمیلی</b>
              </div>
            @endif

            @if(!empty($job->benefits['has_bonus']))
              <div style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#fdf2f8;border-radius:8px;color:#9d174d;font-size:13.5px">
                <span style="font-size:18px">{!! svg_icon('wallet', 15) !!}</span>
                <b>پاداش تولید / آکورد ماهانه</b>
              </div>
            @endif
          </div>
        </div>
      @endif

      <!-- مهارت‌ها و دستگاه‌های تخصصی -->
      @if(!empty($job->skills) && count($job->skills) > 0)
        <div class="card" style="padding:20px;margin-bottom:20px;border-radius:12px;background:#fff">
          <h3 style="margin:0 0 12px;font-size:16px;color:var(--text)">{!! svg_icon('settings', 15) !!} مهارت‌ها و دستگاه‌های مورد نظر</h3>
          <div style="display:flex;gap:8px;flex-wrap:wrap">
            @foreach($job->skills as $skill)
              <span class="badge" style="background:#eef3f9;color:var(--primary);padding:6px 14px;font-size:13px;border-radius:8px;font-weight:600">
                {{ $skill }}
              </span>
            @endforeach
          </div>
        </div>
      @endif

      <!-- شرح شغل و وظایف -->
      @if($job->description)
        <div class="card" style="padding:20px;margin-bottom:20px;border-radius:12px;background:#fff">
          <h3 style="margin:0 0 12px;font-size:16px;color:var(--text)">{!! svg_icon('clipboard', 15) !!} شرح وظایف و مسئولیت‌ها</h3>
          <div style="line-height:2;color:#334155;font-size:14.5px;white-space:pre-line">
            {{ $job->description }}
          </div>
        </div>
      @endif

      <!-- شرایط احراز شغل -->
      @if($job->requirements)
        <div class="card" style="padding:20px;margin-bottom:20px;border-radius:12px;background:#fff">
          <h3 style="margin:0 0 12px;font-size:16px;color:var(--text)">{!! svg_icon('target', 15) !!} شرایط احراز و شایستگی‌ها</h3>
          <div style="line-height:2;color:#334155;font-size:14.5px;white-space:pre-line">
            {{ $job->requirements }}
          </div>
        </div>
      @endif

      <!-- تماس مستقیم کارفرما (برای کارهای فوری کارگاهی) -->
      @if($job->contact_phone || $job->contact_whatsapp)
        <div class="card" style="padding:20px;margin-bottom:20px;border-radius:12px;background:#f0fdf4;border:1px solid #bbf7d0">
          <h3 style="margin:0 0 10px;font-size:16px;color:#166534">{!! svg_icon('phone', 15) !!} راه‌های تماس مستقیم جهت استخدام فوری</h3>
          <p style="margin:0 0 14px;font-size:13.5px;color:#15803d">در صورت تمایل، می‌توانید مستقیماً با مسئول استخدام تماس حاصل فرمایید:</p>
          <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
            @if($job->contact_phone)
              <a href="tel:{{ $job->contact_phone }}" class="btn" style="background:#16a34a;padding:9px 20px;font-size:14px">
                {!! svg_icon('phone', 15) !!} تماس تلفنی: {{ $job->contact_phone }}
              </a>
            @endif
            @if($job->contact_whatsapp)
              <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $job->contact_whatsapp) }}" target="_blank" class="btn" style="background:#25d366;padding:9px 20px;font-size:14px">
                {!! svg_icon('message', 15) !!} پیام در واتساپ: {{ $job->contact_whatsapp }}
              </a>
            @endif
          </div>
        </div>
      @endif

      <!-- فرم ارسال رزومه و درخواست همکاری -->
      <div id="apply_section" class="card" style="padding:24px;border-radius:12px;background:#fff;border:2px solid var(--primary)">
        <h3 style="margin:0 0 8px;font-size:18px;color:var(--text)">{!! svg_icon('mail', 15) !!} فرم ارسال رزومه و درخواست همکاری</h3>
        <p style="margin:0 0 20px;color:var(--muted);font-size:13.5px">رزومه و اطلاعات شما مستقیماً در پنل مدیریت این شرکت قرار خواهد گرفت.</p>

        <form method="post" action="{{ route('jobs.apply', $job) }}" enctype="multipart/form-data">
          @csrf

          <div class="grid-2" style="margin-bottom:14px">
            <div>
              <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">نام و نام خانوادگی <span style="color:red">*</span></label>
              <input name="full_name" required placeholder="مثال: علی احمدی" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px">
            </div>

            <div>
              <label style="display:block;margin-bottom:6px;font-size:13px;font-weight:600">شماره موبایل تماس <span style="color:red">*</span></label>
              <input name="phone" required placeholder="مثال: 09121234567" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px">
            </div>
          </div>

          <div class="grid-3" style="margin-bottom:14px">
            <div>
              <label style="display:block;margin-bottom:6px;font-size:13px">سال تولد</label>
              <input name="birth_year" placeholder="مثال: 1370" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px">
            </div>

            <div>
              <label style="display:block;margin-bottom:6px;font-size:13px">وضعیت خدمت</label>
              <select name="military_status" style="width:100%">
                <option value="">انتخاب...</option>
                <option value="پایان خدمت">پایان خدمت</option>
                <option value="معافیت دائم">معافیت دائم</option>
                <option value="معافیت تحصیلی">معافیت تحصیلی</option>
                <option value="مشمول">مشمول / در حال خدمت</option>
              </select>
            </div>

            <div>
              <label style="display:block;margin-bottom:6px;font-size:13px">میزان سابقه کار</label>
              <select name="experience_years" style="width:100%">
                <option value="">انتخاب...</option>
                <option value="بدون سابقه">بدون سابقه</option>
                <option value="کمتر از ۲ سال">کمتر از ۲ سال</option>
                <option value="۲ تا ۵ سال">۲ تا ۵ سال</option>
                <option value="بیش از ۵ سال">بیش از ۵ سال</option>
              </select>
            </div>
          </div>

          <div class="grid-2" style="margin-bottom:14px">
            <div>
              <label style="display:block;margin-bottom:6px;font-size:13px">آخرین شرکت یا کارخانه محل کار</label>
              <input name="last_company" placeholder="مثال: کارتن میهن، چاپ افست آریا..." style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px">
            </div>

            <div>
              <label style="display:block;margin-bottom:6px;font-size:13px">حقوق پیشنهادی درخواستی</label>
              <input name="expected_salary" placeholder="مثال: توافقی یا ۱۸ میلیون تومان" style="width:100%;padding:9px 12px;border:1px solid var(--border);border-radius:8px">
            </div>
          </div>

          <div style="margin-bottom:14px">
            <label style="display:block;margin-bottom:6px;font-size:13px">فایل رزومه PDF (اختیاری - در صورت داشتن فایل رزومه)</label>
            <input type="file" name="resume_file" accept=".pdf,.doc,.docx" style="width:100%;padding:8px;border:1px dashed var(--border);border-radius:8px;background:#fafafa">
            <span style="font-size:12px;color:var(--muted);display:block;margin-top:4px">فرمت‌های مجاز: PDF, DOC - حداکثر ۵ مگابایت</span>
          </div>

          <div style="margin-bottom:20px">
            <label style="display:block;margin-bottom:6px;font-size:13px">توضیحات یا پیام شما برای کارفرما (اختیاری)</label>
            <textarea name="cover_letter" rows="3" placeholder="توضیحاتی در مورد مهارت‌ها، دستگاه‌هایی که کار کرده‌اید یا شرایط کاری..." style="width:100%;padding:10px;border:1px solid var(--border);border-radius:8px"></textarea>
          </div>

          <button type="submit" class="btn" style="padding:12px 32px;font-size:15px;font-weight:700;width:100%">
            {!! svg_icon('rocket', 15) !!} ثبت و ارسال درخواست همکاری به کارفرما
          </button>
        </form>
      </div>

    </div>

    <!-- ستون کناری (اطلاعات شرکت و سایر آگهی‌ها) -->
    <div>
      @if($job->company)
        <div class="card" style="padding:20px;border-radius:12px;background:#fff;margin-bottom:20px">
          <div style="text-align:center;margin-bottom:14px">
            <div style="width:80px;height:80px;border-radius:14px;background:#f1f5f9;margin:0 auto 10px;display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid #e2e8f0">
              @if($job->company->logo)
                <img src="{{ asset($job->company->logo) }}" alt="{{ $job->company->name }}" style="width:100%;height:100%;object-fit:cover">
              @else
                <span style="font-size:32px">{!! svg_icon('factory', 15) !!}</span>
              @endif
            </div>
            <h3 style="margin:0 0 4px;font-size:16.5px;color:var(--text)">{{ $job->company->name }}</h3>
            @if($job->company->brand_name)
              <span style="font-size:12.5px;color:var(--muted)">نام تجاری: {{ $job->company->brand_name }}</span>
            @endif
          </div>

          @if($job->company->description)
            <p style="margin:0 0 14px;font-size:13px;color:#475569;line-height:1.7;text-align:justify">
              {{ Str::limit(strip_tags($job->company->description), 140) }}
            </p>
          @endif

          <div style="font-size:13px;color:var(--muted);margin-bottom:16px;display:flex;flex-direction:column;gap:6px">
            @if($job->company->city)
              <div>{!! svg_icon('map-pin', 15) !!} <b>شهر:</b> {{ $job->company->province->name ?? '' }} · {{ $job->company->city->name }}</div>
            @endif
            @if($job->company->website)
              <div>{!! svg_icon('globe', 15) !!} <b>سایت:</b> <a href="https://{{ preg_replace('#^https?://#', '', $job->company->website) }}" target="_blank" style="color:var(--primary)">{{ $job->company->website }}</a></div>
            @endif
          </div>

          <a href="{{ $job->company->url }}" class="btn ghost small" style="width:100%;text-align:center;padding:8px 0;font-size:13px">
            مشاهده کامل مشخصات و محصولات کارخانه ←
          </a>
        </div>
      @endif

      <!-- آگهی‌های مرتبط -->
      @if($relatedJobs->count() > 0)
        <div class="card" style="padding:18px;border-radius:12px;background:#fff">
          <h4 style="margin:0 0 12px;font-size:15px;color:var(--text)">فرصت‌های شغلی مشابه</h4>
          <div style="display:flex;flex-direction:column;gap:10px">
            @foreach($relatedJobs as $rJob)
              <a href="{{ route('jobs.show', $rJob) }}" style="display:block;padding:10px;border-radius:8px;background:#f8fafc;text-decoration:none;transition:background 0.15s">
                <b style="color:var(--text);font-size:13.5px;display:block;margin-bottom:4px">{{ $rJob->title }}</b>
                <span style="font-size:12px;color:var(--muted)">{{ $rJob->company->name ?? 'کارفرما' }} · {{ $rJob->city->name ?? '' }}</span>
              </a>
            @endforeach
          </div>
        </div>
      @endif
    </div>

  </div>

</div>
@endsection
