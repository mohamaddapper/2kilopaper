<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url><loc>{{ url('/') }}</loc></url>
<url><loc>{{ route('directory') }}</loc></url>
<url><loc>{{ route('categories.index') }}</loc></url>
<url><loc>{{ route('locations.index') }}</loc></url>
<url><loc>{{ route('jobs.index') }}</loc></url>
<url><loc>{{ route('subscription.fee') }}</loc></url>
<url><loc>{{ route('advertising') }}</loc></url>
<url><loc>{{ route('pages.phone-numbers') }}</loc></url>
<url><loc>{{ route('pages.app') }}</loc></url>
<url><loc>{{ route('pages.faqs') }}</loc></url>
<url><loc>{{ route('pages.terms') }}</loc></url>
<url><loc>{{ route('pages.privacy') }}</loc></url>
<url><loc>{{ route('pages.about') }}</loc></url>
<url><loc>{{ route('pages.contact') }}</loc></url>
@if(isset($categories))
@foreach($categories as $cat)
<url><loc>{{ $cat->url }}</loc></url>
@endforeach
@endif
@if(isset($provinces))
@foreach($provinces as $prov)
<url><loc>{{ $prov->url }}</loc></url>
@endforeach
@endif
@foreach($companies as $c)
<url><loc>{{ $c->url }}</loc><lastmod>{{ $c->updated_at->toAtomString() }}</lastmod></url>
@endforeach
</urlset>
