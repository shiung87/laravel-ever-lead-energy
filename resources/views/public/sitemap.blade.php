{!! '<' . '?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ route('home') }}</loc><priority>1.0</priority></url>
    <url><loc>{{ route('products.index') }}</loc><priority>0.9</priority></url>
    <url><loc>{{ route('battery-finder') }}</loc><priority>0.9</priority></url>
    <url><loc>{{ route('promotions.index') }}</loc><priority>0.8</priority></url>
    <url><loc>{{ route('dealer.index') }}</loc><priority>0.8</priority></url>
    <url><loc>{{ route('enquiry.create') }}</loc><priority>0.8</priority></url>
    <url><loc>{{ route('contact') }}</loc><priority>0.7</priority></url>
    @foreach($products as $product)
        <url>
            <loc>{{ route('products.show', $product->slug) }}</loc>
            <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($promotions as $promo)
        <url>
            <loc>{{ route('promotions.show', $promo->slug) }}</loc>
            <lastmod>{{ $promo->updated_at->toAtomString() }}</lastmod>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>
