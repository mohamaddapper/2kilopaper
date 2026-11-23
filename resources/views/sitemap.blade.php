<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url><loc>{{ url('/') }}</loc></url>
<url><loc>{{ route('directory') }}</loc></url>
@foreach($companies as $c)
<url><loc>{{ route('company', $c->slug) }}</loc><lastmod>{{ $c->updated_at->toAtomString() }}</lastmod></url>
@endforeach
</urlset>
