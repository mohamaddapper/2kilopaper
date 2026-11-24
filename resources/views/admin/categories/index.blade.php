@extends('admin.layout', ['title' => 'مدیریت دسته‌بندی‌ها و سئو'])

@section('content')
<div>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:22px;color:#0f172a">🏷️ مدیریت دسته‌بندی‌ها و سئو (SEO)</h1>
      <p style="margin:0;color:#64748b;font-size:13.5px">ویرایش عنوان سئو، متا دیسکریپشن، متن تخصصی و سوالات متداول برای جذب حداکثری ورودی از گوگل</p>
    </div>
  </div>

  <form method="get" action="{{ route('admin.categories') }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:18px">
    <div style="display:grid;grid-template-columns:2fr 1.5fr auto;gap:12px;align-items:center">
      <input name="q" value="{{ request('q') }}" placeholder="جستجو در نام یا اسلاگ دسته‌بندی..." style="padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-family:inherit">
      <select name="pillar" style="padding:9px 12px;border:1px solid #cbd5e1;border-radius:8px;font-family:inherit;background:#fff">
        <option value="">همه شاخه‌های اصلی</option>
        @foreach($pillars as $p)
          <option value="{{ $p->id }}" @selected(request('pillar') == $p->id)>{{ $p->icon }} {{ $p->name }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn" style="background:var(--admin-primary);padding:9px 20px">فیلتر</button>
    </div>
  </form>

  <div class="admin-table-card" style="overflow-x:auto">
    <table style="width:100%;border-collapse:collapse;font-size:13.5px">
      <thead>
        <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;text-align:right">
          <th style="padding:12px 14px">نام دسته‌بندی</th>
          <th style="padding:12px 14px">شاخه / نقش</th>
          <th style="padding:12px 14px">تعداد شرکت</th>
          <th style="padding:12px 14px">وضعیت سئو</th>
          <th style="padding:12px 14px;text-align:left">عملیات</th>
        </tr>
      </thead>
      <tbody>
        @foreach($categories as $cat)
          <tr style="border-bottom:1px solid #f1f5f9">
            <td style="padding:12px 14px;font-weight:700;color:#0f172a">
              {{ $cat->name }}
              <div style="font-size:11.5px;color:#64748b;font-weight:400;margin-top:2px;direction:ltr;text-align:right">
                {{ urldecode(parse_url($cat->url, PHP_URL_PATH)) }}
              </div>
            </td>
            <td style="padding:12px 14px;color:#475569">
              {{ $cat->role_icon }} {{ $cat->rootPillar()->name }}
            </td>
            <td style="padding:12px 14px">
              <span style="background:#e0f2fe;color:#0369a1;padding:2px 9px;border-radius:12px;font-size:12px;font-weight:700">
                {{ $cat->companies_count }} شرکت
              </span>
            </td>
            <td style="padding:12px 14px">
              <div style="display:flex;gap:6px;flex-wrap:wrap">
                @if($cat->seo_title)
                  <span style="background:#dcfce7;color:#166534;padding:2px 8px;border-radius:8px;font-size:11.5px;font-weight:700">✓ Title</span>
                @else
                  <span style="background:#f1f5f9;color:#64748b;padding:2px 8px;border-radius:8px;font-size:11.5px">خودکار</span>
                @endif
                @if($cat->seo_description)
                  <span style="background:#dcfce7;color:#166534;padding:2px 8px;border-radius:8px;font-size:11.5px;font-weight:700">✓ Meta</span>
                @endif
                @if(!empty($cat->faqs))
                  <span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:8px;font-size:11.5px;font-weight:700">✓ {{ count($cat->faqs) }} FAQ</span>
                @endif
              </div>
            </td>
            <td style="padding:12px 14px;text-align:left">
              <div style="display:inline-flex;gap:6px">
                <a href="{{ $cat->url }}" target="_blank" class="btn-action btn-view">مشاهده ↗</a>
                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn-action" style="background:#0284c7;color:#fff;font-weight:700">✏️ ویرایش سئو</a>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div style="margin-top:18px">
    {{ $categories->links() }}
  </div>
</div>
@endsection
