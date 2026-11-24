@extends('admin.layout', ['title' => 'ویرایش سئو دسته‌بندی: ' . $category->name])

@section('content')
<div style="max-width:920px;margin:0 auto">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px">
    <div>
      <h1 style="margin:0 0 6px;font-size:21px;color:#0f172a">
        {!! svg_icon('edit', 15) !!} ویرایش سئو و محتوای دسته «{{ $category->name }}»
      </h1>
      <p style="margin:0;color:#64748b;font-size:13px">
        شاخه: {!! $category->role_icon !!} {{ $category->rootPillar()->name }} · آدرس: <code style="direction:ltr">{{ urldecode(parse_url($category->url, PHP_URL_PATH)) }}</code>
      </p>
    </div>
    <div style="display:flex;gap:8px">
      <a href="{{ $category->url }}" target="_blank" class="btn-action btn-view">مشاهده صفحه در سایت ↗</a>
      <a href="{{ route('admin.categories') }}" class="btn-action" style="background:#e2e8f0;color:#334155">بازگشت به لیست</a>
    </div>
  </div>

  <form method="post" action="{{ route('admin.categories.update', $category) }}" style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;display:flex;flex-direction:column;gap:18px">
    @csrf
    @method('PUT')

    <div>
      <label style="display:block;font-weight:700;font-size:13.5px;margin-bottom:6px;color:#0f172a">نام دسته‌بندی <span style="color:#ef4444">*</span></label>
      <input type="text" name="name" value="{{ old('name', $category->name) }}" required style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-family:inherit;font-size:14px;box-sizing:border-box">
    </div>

    <div>
      <label style="display:block;font-weight:700;font-size:13.5px;margin-bottom:6px;color:#0f172a">
        عنوان سئو (SEO Title)
        <span style="font-weight:400;font-size:12px;color:#64748b"> — اگر خالی باشد به صورت خودکار ساخته می‌شود</span>
      </label>
      <input type="text" name="seo_title" value="{{ old('seo_title', $category->seo_title) }}" placeholder="پیش‌فرض: {{ $category->smart_h1 }} | لیست کارخانه‌ها و تلفن تماس" style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-family:inherit;font-size:14px;box-sizing:border-box">
    </div>

    <div>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
        <label style="font-weight:700;font-size:13.5px;color:#0f172a">توضیحات متا (Meta Description)</label>
        <span id="meta_counter" style="font-size:12px;color:#64748b">۰ / ۱۶۰ کاراکتر</span>
      </div>
      <textarea name="seo_description" id="seo_description" rows="3" placeholder="توضیح جذاب برای افزایش نرخ کلیک در نتایج گوگل (بین ۱۲۰ تا ۱۶۰ کاراکتر)..." style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-family:inherit;font-size:13.5px;box-sizing:border-box">{{ old('seo_description', $category->seo_description) }}</textarea>
    </div>

    <div>
      <label style="display:block;font-weight:700;font-size:13.5px;margin-bottom:6px;color:#0f172a">
        متن تخصصی صفحه دسته‌بندی (نمایش در پایین لیست شرکت‌ها)
      </label>
      <textarea name="description" rows="7" style="width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px;font-family:inherit;font-size:13.5px;line-height:1.8;box-sizing:border-box">{{ old('description', $category->description) }}</textarea>
      <div style="font-size:12px;color:#64748b;margin-top:4px">پشتیبانی از تگ‌های HTML استاندارد مانند &lt;p&gt;، &lt;strong&gt;، &lt;h2&gt; و لیست‌ها</div>
    </div>

    <!-- سوالات متداول (FAQ) -->
    <div style="border-top:1px dashed #cbd5e1;padding-top:18px">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <div>
          <label style="font-weight:800;font-size:14px;color:#0f172a">{!! svg_icon('help', 15) !!} سوالات متداول اختصاصی این دسته (FAQ Schema)</label>
          <div style="font-size:12px;color:#64748b">درج سوال و پاسخ باعث نمایش FAQ در نتایج گوگل می‌شود.</div>
        </div>
        <button type="button" onclick="addFaqRow()" class="btn-action" style="background:#e0f2fe;color:#0369a1;font-weight:700;padding:7px 14px">+ افزودن سوال</button>
      </div>

      <div id="faq_container" style="display:flex;flex-direction:column;gap:12px">
        @php $faqs = old('faqs', $category->faqs ?? []); @endphp
        @foreach($faqs as $idx => $faq)
          <div class="faq-row" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px;display:flex;flex-direction:column;gap:8px">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
              <input type="text" name="faqs[{{ $idx }}][q]" value="{{ $faq['q'] ?? '' }}" placeholder="سوال..." style="flex:1;padding:8px 10px;border:1px solid #cbd5e1;border-radius:6px;font-family:inherit;font-size:13px">
              <button type="button" onclick="this.closest('.faq-row').remove()" style="background:#fee2e2;color:#b91c1c;border:none;border-radius:6px;padding:6px 10px;cursor:pointer;font-size:12px;font-family:inherit">حذف</button>
            </div>
            <textarea name="faqs[{{ $idx }}][a]" rows="2" placeholder="پاسخ..." style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:6px;font-family:inherit;font-size:13px;box-sizing:border-box">{{ $faq['a'] ?? '' }}</textarea>
          </div>
        @endforeach
      </div>
    </div>

    <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:10px">
      <button type="submit" class="btn" style="background:var(--admin-primary);color:#fff;padding:10px 26px;font-weight:700;font-size:14px">ذخیره تنظیمات سئو</button>
    </div>
  </form>
</div>

<script>
let faqIndex = {{ count(old('faqs', $category->faqs ?? [])) }};
function addFaqRow() {
 const html = `
 <div class="faq-row" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px;display:flex;flex-direction:column;gap:8px">
 <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
 <input type="text" name="faqs[${faqIndex}][q]" placeholder="سوال..." style="flex:1;padding:8px 10px;border:1px solid #cbd5e1;border-radius:6px;font-family:inherit;font-size:13px">
 <button type="button" onclick="this.closest('.faq-row').remove()" style="background:#fee2e2;color:#b91c1c;border:none;border-radius:6px;padding:6px 10px;cursor:pointer;font-size:12px;font-family:inherit">حذف</button>
 </div>
 <textarea name="faqs[${faqIndex}][a]" rows="2" placeholder="پاسخ..." style="width:100%;padding:8px 10px;border:1px solid #cbd5e1;border-radius:6px;font-family:inherit;font-size:13px;box-sizing:border-box"></textarea>
 </div>
 `;
 document.getElementById('faq_container').insertAdjacentHTML('beforeend', html);
 faqIndex++;
}

const metaInput = document.getElementById('seo_description');
const metaCounter = document.getElementById('meta_counter');
function updateMetaCounter() {
 if (!metaInput || !metaCounter) return;
 const len = metaInput.value.length;
 metaCounter.textContent = len + ' / ۱۶۰ کاراکتر';
 metaCounter.style.color = len > 165 ? '#dc2626' : (len >= 110 ? '#16a34a' : '#64748b');
}
if (metaInput) {
 metaInput.addEventListener('input', updateMetaCounter);
 updateMetaCounter();
}
</script>
@endsection
