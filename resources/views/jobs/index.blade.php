@extends('layouts.app', [
  'title' => 'فرصت‌های شغلی و استخدام صنعت کاغذ، چاپ و بسته‌بندی',
  'description' => 'جدیدترین آگهی‌های استخدام و کاریابی در کارخانجات کارتن‌سازی، ورق‌سازی، چاپ و بسته‌بندی و ماشین‌آلات ایران'
])

@section('content')
<div style="margin:24px 0 16px">
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:18px">
    <div>
      <h1 style="margin:0 0 6px;font-size:24px;color:var(--text)">💼 فرصت‌های شغلی صنعت کاغذ، چاپ و بسته‌بندی</h1>
      <p style="margin:0;color:var(--muted);font-size:14px">آگهی‌های استخدام کارشناس، مهندس، اپراتور خط تولید، تکنسین و کارگر فنی کارخانه</p>
    </div>
    @auth
      <a href="{{ route('employer.jobs.create') }}" class="btn" style="font-weight:700;padding:10px 20px">+ درج آگهی استخدام</a>
    @else
      <a href="{{ route('login') }}" class="btn ghost" style="font-size:13.5px">ورود کارفرمایان / درج آگهی</a>
    @endauth
  </div>

  <!-- فرم جستجو و فیلترهای هوشمند -->
  <form method="get" action="{{ route('jobs.index') }}" style="background:#fff;border:1px solid var(--border);border-radius:12px;padding:18px;margin-bottom:24px">
    <div class="jobs-search-grid">
      <input name="q" value="{{ request('q') }}" placeholder="عنوان شغل، تخصص، دستگاه (مثال: اپراتور دایکات، کارشناس فروش...)" style="width:100%;border:1px solid var(--border)">
      
      <select name="cat" style="border:1px solid var(--border)">
        <option value="">همه زمینه‌ها</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected(request('cat') == $c->id)>{{ $c->name }}</option>
          @foreach($c->children as $ch)
            <option value="{{ $ch->id }}" @selected(request('cat') == $ch->id)>— {{ $ch->name }}</option>
          @endforeach
        @endforeach
      </select>

      <select name="province" style="border:1px solid var(--border)">
        <option value="">همه استان‌ها</option>
        @foreach($provinces as $p)
          <option value="{{ $p->id }}" @selected(request('province') == $p->id)>{{ $p->name }}</option>
        @endforeach
      </select>

      <button type="submit" class="btn" style="padding:10px 22px">جستجو</button>
    </div>

    <!-- فیلترهای سریع و امکانات -->
    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-top:14px;padding-top:14px;border-top:1px solid #f1f5f9;font-size:13px">
      <span style="color:var(--muted);font-weight:700;font-size:12.5px">⚡ فیلتر سریع:</span>
      
      <!-- فقط آگهی‌های فوری -->
      <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;padding:6px 14px;border-radius:20px;background:{{ request('immediate') ? '#fef2f2' : '#f8fafc' }};border:1px solid {{ request('immediate') ? '#f87171' : '#e2e8f0' }};transition:all .15s;white-space:nowrap;user-select:none">
        <input type="checkbox" name="immediate" value="1" @checked(request('immediate')) onchange="this.form.submit()" style="accent-color:#ef4444;width:16px;height:16px;margin:0;cursor:pointer">
        <span style="font-size:12.5px;font-weight:{{ request('immediate') ? '700' : '600' }};color:{{ request('immediate') ? '#dc2626' : '#475569' }};white-space:nowrap">🚨 فقط آگهی‌های فوری</span>
      </label>

      <span style="color:#cbd5e1;margin:0 2px">|</span>
      <span style="color:var(--muted);font-weight:600;font-size:12px">امکانات کارخانه:</span>

      <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;padding:6px 12px;border-radius:20px;background:{{ request('has_service') ? '#eff6ff' : '#f8fafc' }};border:1px solid {{ request('has_service') ? '#3b82f6' : '#e2e8f0' }};transition:all .15s;white-space:nowrap;user-select:none">
        <input type="checkbox" name="has_service" value="1" @checked(request('has_service')) onchange="this.form.submit()" style="accent-color:#0284c7;width:16px;height:16px;margin:0;cursor:pointer">
        <span style="font-size:12.5px;font-weight:{{ request('has_service') ? '700' : '500' }};color:{{ request('has_service') ? '#0369a1' : '#475569' }};white-space:nowrap">🚌 سرویس رفت‌وآمد</span>
      </label>

      <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;padding:6px 12px;border-radius:20px;background:{{ request('has_meal') ? '#f0fdf4' : '#f8fafc' }};border:1px solid {{ request('has_meal') ? '#22c55e' : '#e2e8f0' }};transition:all .15s;white-space:nowrap;user-select:none">
        <input type="checkbox" name="has_meal" value="1" @checked(request('has_meal')) onchange="this.form.submit()" style="accent-color:#16a34a;width:16px;height:16px;margin:0;cursor:pointer">
        <span style="font-size:12.5px;font-weight:{{ request('has_meal') ? '700' : '500' }};color:{{ request('has_meal') ? '#15803d' : '#475569' }};white-space:nowrap">🍽️ وعده غذایی</span>
      </label>

      <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;padding:6px 12px;border-radius:20px;background:{{ request('has_accommodation') ? '#faf5ff' : '#f8fafc' }};border:1px solid {{ request('has_accommodation') ? '#a855f7' : '#e2e8f0' }};transition:all .15s;white-space:nowrap;user-select:none">
        <input type="checkbox" name="has_accommodation" value="1" @checked(request('has_accommodation')) onchange="this.form.submit()" style="accent-color:#9333ea;width:16px;height:16px;margin:0;cursor:pointer">
        <span style="font-size:12.5px;font-weight:{{ request('has_accommodation') ? '700' : '500' }};color:{{ request('has_accommodation') ? '#7e22ce' : '#475569' }};white-space:nowrap">🛏️ دارای خوابگاه</span>
      </label>

      <label style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;padding:6px 12px;border-radius:20px;background:{{ request('has_bonus') ? '#fffbeb' : '#f8fafc' }};border:1px solid {{ request('has_bonus') ? '#f59e0b' : '#e2e8f0' }};transition:all .15s;white-space:nowrap;user-select:none">
        <input type="checkbox" name="has_bonus" value="1" @checked(request('has_bonus')) onchange="this.form.submit()" style="accent-color:#d97706;width:16px;height:16px;margin:0;cursor:pointer">
        <span style="font-size:12.5px;font-weight:{{ request('has_bonus') ? '700' : '500' }};color:{{ request('has_bonus') ? '#b45309' : '#475569' }};white-space:nowrap">💰 پاداش تولید</span>
      </label>

      @if(request()->hasAny(['q', 'cat', 'province', 'immediate', 'has_service', 'has_meal', 'has_accommodation', 'has_bonus']))
        <a href="{{ route('jobs.index') }}" style="color:#ef4444;font-size:12px;margin-right:auto;text-decoration:none;font-weight:600;padding:4px 10px;border-radius:6px;background:#fef2f2;white-space:nowrap">✕ حذف همه فیلترها</a>
      @endif
    </div>
  </form>

  <!-- لیست آگهی‌ها -->
  @if($jobs->count() > 0)
    <div style="display:flex;flex-direction:column;gap:12px">
      @foreach($jobs as $job)
        <div class="card" style="padding:18px 20px;transition:transform 0.15s, box-shadow 0.15s;border:1px solid var(--border);border-radius:12px;background:#fff;position:relative">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
            
            <div style="display:flex;gap:16px;align-items:flex-start">
              <!-- لوگو یا آیکون شرکت -->
              <div style="width:58px;height:58px;border-radius:10px;background:#f1f5f9;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
                @if($job->company && $job->company->logo)
                  <img src="{{ asset($job->company->logo) }}" alt="{{ $job->company->name }}" style="width:100%;height:100%;object-fit:cover">
                @else
                  <span style="font-size:24px">🏭</span>
                @endif
              </div>

              <div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                  <a href="{{ route('jobs.show', $job) }}" style="font-size:17px;font-weight:700;color:var(--text);text-decoration:none">
                    {{ $job->title }}
                  </a>
                  @if($job->is_immediate)
                    <span class="badge bad" style="font-size:11px;font-weight:700">فوری</span>
                  @endif
                  @if($job->is_featured)
                    <span class="badge gold" style="font-size:11px">ویژه</span>
                  @endif
                </div>

                <div style="display:flex;align-items:center;gap:10px;margin-top:6px;font-size:13.5px;color:var(--muted);flex-wrap:wrap">
                  @if($job->company)
                    <a href="{{ route('company', $job->company) }}" style="color:var(--primary);font-weight:600;text-decoration:none">
                      🏢 {{ $job->company->name }}
                    </a>
                  @else
                    <span>🏢 کارفرمای صنعتی</span>
                  @endif
                  <span>•</span>
                  <span>📍 {{ $job->province->name ?? '' }} {{ $job->city ? '· ' . $job->city->name : '' }}</span>
                  <span>•</span>
                  <span>🏢 {{ $job->workplace_type_label }}</span>
                </div>

                <!-- تگ‌های ویژگی و امکانات -->
                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:10px">
                  <span class="badge" style="background:#f1f5f9;color:#334155;font-size:12px">{{ $job->job_type_label }}</span>
                  @if($job->work_shift_label)
                    <span class="badge" style="background:#f8fafc;color:#475569;font-size:12px">{{ $job->work_shift_label }}</span>
                  @endif
                  <span class="badge ok" style="font-size:12px">💵 {{ $job->salary_label }}</span>
                  
                  @if(!empty($job->benefits['has_service']))
                    <span class="badge" style="background:#eff6ff;color:#1e40af;font-size:12px">🚌 دارای سرویس</span>
                  @endif
                  @if(!empty($job->benefits['has_meal']))
                    <span class="badge" style="background:#f0fdf4;color:#166534;font-size:12px">🍽️ ناهار</span>
                  @endif
                  @if(!empty($job->benefits['has_accommodation']))
                    <span class="badge" style="background:#fef3c7;color:#92400e;font-size:12px">🛏️ خوابگاه</span>
                  @endif
                </div>
              </div>
            </div>

            <!-- دکمه اقدام و تاریخ -->
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;margin-top:4px">
              <span style="font-size:12px;color:var(--muted)">{{ $job->created_at->diffForHumans() }}</span>
              <a href="{{ route('jobs.show', $job) }}" class="btn ghost small" style="padding:7px 16px;font-size:13px;border-radius:8px">
                مشاهده و ارسال رزومه ←
              </a>
            </div>

          </div>
        </div>
      @endforeach
    </div>

    <div style="margin-top:24px">
      {{ $jobs->links() }}
    </div>
  @else
    <div class="card" style="padding:48px;text-align:center;color:var(--muted);background:#fff;border-radius:12px">
      <span style="font-size:42px;display:block;margin-bottom:12px">🔍</span>
      <h3 style="margin:0 0 8px;color:var(--text)">هیچ فرصت شغلی با فیلترهای انتخابی یافت نشد!</h3>
      <p style="margin:0 0 16px;font-size:14px">می‌توانید فیلترها را تغییر دهید یا تمام آگهی‌ها را مشاهده نمایید.</p>
      <a href="{{ route('jobs.index') }}" class="btn small">مشاهده همه آگهی‌ها</a>
    </div>
  @endif
</div>
@endsection
