@extends('layouts.app', ['title' => 'انتخاب پلن ثبت شرکت | ۲ کیلو کاغذ'])

@section('content')
<div class="page-wrapper" style="margin:24px 0 60px">
  @include('partials.subscription-plans', ['mode' => 'create'])
</div>
@endsection
