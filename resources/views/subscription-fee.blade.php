@extends('layouts.app', [
 'title' => 'هزینه اشتراک‌ها و تعرفه خدمات | ۲ کیلو کاغذ',
 'description' => 'تعرفه اشتراک کارخانجات و شرکت‌های صنعت کاغذ، چاپ و بسته‌بندی در دایرکتوری ۲ کیلو کاغذ'
])

@section('content')
<div class="page-wrapper" style="margin:24px 0 60px">
  @include('partials.subscription-plans', ['mode' => 'info'])
</div>
@endsection
