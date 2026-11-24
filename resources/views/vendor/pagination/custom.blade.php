@if ($paginator->hasPages())
  <nav role="navigation" aria-label="صفحه‌بندی" class="pagination-nav" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;margin:28px 0;width:100%">
    
    <!-- آمار نتایج به فارسی -->
    <div style="font-size:13.5px;color:#64748b;text-align:center">
      نمایش
      <b style="color:#0f172a">{{ function_exists('en_to_fa_digits') ? en_to_fa_digits($paginator->firstItem()) : $paginator->firstItem() }}</b>
      تا
      <b style="color:#0f172a">{{ function_exists('en_to_fa_digits') ? en_to_fa_digits($paginator->lastItem()) : $paginator->lastItem() }}</b>
      از
      <b style="color:#0f172a">{{ function_exists('en_to_fa_digits') ? en_to_fa_digits(number_format($paginator->total())) : number_format($paginator->total()) }}</b>
      نتیجه
    </div>

    <!-- شماره صفحات و دکمه‌های ناوبری -->
    <ul class="custom-pagination" style="display:flex;align-items:center;justify-content:center;gap:6px;list-style:none;padding:0;margin:0;flex-wrap:wrap">
      
      {{-- دکمه صفحه قبل (در RTL جهت بازگشت به راست است) --}}
      @if ($paginator->onFirstPage())
        <li style="display:inline-block">
          <span style="display:inline-flex;align-items:center;gap:4px;padding:8px 14px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#94a3b8;font-size:13px;cursor:not-allowed;user-select:none">
            <span>&rarr;</span>
            <span>قبلی</span>
          </span>
        </li>
      @else
        <li style="display:inline-block">
          <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display:inline-flex;align-items:center;gap:4px;padding:8px 14px;border-radius:8px;border:1px solid #cbd5e1;background:#fff;color:#334155;font-size:13px;text-decoration:none;font-weight:600;transition:all 0.15s ease">
            <span>&rarr;</span>
            <span>قبلی</span>
          </a>
        </li>
      @endif

      {{-- شماره صفحات --}}
      @foreach ($elements as $element)
        {{-- سه نقطه --}}
        @if (is_string($element))
          <li style="display:inline-block">
            <span style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;color:#94a3b8;font-size:14px">...</span>
          </li>
        @endif

        {{-- لیست صفحات --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li style="display:inline-block">
                <span aria-current="page" style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 10px;border-radius:8px;background:#0284c7;color:#fff;font-size:14px;font-weight:700;border:1px solid #0284c7;box-shadow:0 2px 4px rgba(2,132,199,0.25)">
                  {{ function_exists('en_to_fa_digits') ? en_to_fa_digits($page) : $page }}
                </span>
              </li>
            @else
              <li style="display:inline-block">
                <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 10px;border-radius:8px;border:1px solid #cbd5e1;background:#fff;color:#334155;font-size:14px;font-weight:600;text-decoration:none;transition:all 0.15s ease">
                  {{ function_exists('en_to_fa_digits') ? en_to_fa_digits($page) : $page }}
                </a>
              </li>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- دکمه صفحه بعد --}}
      @if ($paginator->hasMorePages())
        <li style="display:inline-block">
          <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display:inline-flex;align-items:center;gap:4px;padding:8px 14px;border-radius:8px;border:1px solid #cbd5e1;background:#fff;color:#334155;font-size:13px;text-decoration:none;font-weight:600;transition:all 0.15s ease">
            <span>بعدی</span>
            <span>&larr;</span>
          </a>
        </li>
      @else
        <li style="display:inline-block">
          <span style="display:inline-flex;align-items:center;gap:4px;padding:8px 14px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#94a3b8;font-size:13px;cursor:not-allowed;user-select:none">
            <span>بعدی</span>
            <span>&larr;</span>
          </span>
        </li>
      @endif

    </ul>
  </nav>
@endif
