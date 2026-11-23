@php($tier = $company->activeTier())
<a href="{{ route('company', $company) }}" class="card" style="color:inherit;display:block">
  @if($tier)<span class="badge {{ $tier }} stamp">ویژه {{ ['gold'=>'طلایی','silver'=>'نقره‌ای','bronze'=>'برنزی'][$tier] }}</span>@endif
  <h3 @if($tier) style="margin-top:28px" @endif>{{ $company->name }}</h3>
  <p>{{ $company->category->name }} · {{ $company->city->name }}</p>
  <p>{{ \Illuminate\Support\Str::limit($company->description, 110) }}</p>
</a>
