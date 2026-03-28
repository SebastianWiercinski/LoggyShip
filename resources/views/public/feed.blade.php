{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $siteName }} Updates</title>
        <link>{{ url('/updates') }}</link>
        <description>Latest product updates from {{ $siteName }}</description>
        <language>{{ app()->getLocale() }}</language>
        <atom:link href="{{ url('/updates/feed.xml') }}" rel="self" type="application/rss+xml" />
        @foreach($posts as $post)
        <item>
            <title>{{ htmlspecialchars($post->title, ENT_XML1) }}</title>
            <link>{{ url('/updates/' . $post->slug) }}</link>
            <guid isPermaLink="true">{{ url('/updates/' . $post->slug) }}</guid>
            <pubDate>{{ $post->published_at->toRfc2822String() }}</pubDate>
            <category>{{ $post->category }}</category>
            <description>{{ htmlspecialchars($post->teaser ?: strip_tags($post->body_html), ENT_XML1) }}</description>
        </item>
        @endforeach
    </channel>
</rss>
