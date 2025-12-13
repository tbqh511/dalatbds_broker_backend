@extends('frontends.master')

@php
    // Prepare SEO and Social data
    $pageTitle = $post->post_title ?? 'Tin tức';
    $pageDescription = Str::limit(strip_tags($post->post_excerpt ?? $post->post_content), 155);

    // Prepare featured image URL
    $featuredImageUrl = asset('images/all/blog/1.jpg'); // Default
    $thumbMeta = $post->meta->where('meta_key', '_thumbnail')->first();
    if ($thumbMeta && $thumbMeta->meta_value) {
        if (file_exists(public_path('assets/images/posts/' . basename($thumbMeta->meta_value)))) {
            $featuredImageUrl = asset('assets/images/posts/' . basename($thumbMeta->meta_value));
        } elseif (Storage::disk('public')->exists($thumbMeta->meta_value)) {
            $featuredImageUrl = Storage::url($thumbMeta->meta_value);
        }
    }
@endphp

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('social_meta')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $featuredImageUrl }}">
    <meta property="article:published_time" content="{{ $post->created_at->toIso8601String() }}" />
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $pageTitle }}">
    <meta property="twitter:description" content="{{ $pageDescription }}">
    <meta property="twitter:image" content="{{ $featuredImageUrl }}">
@endsection

@section('content')
<div class="content">
    <!--  section  -->
    <section class="hidden-section single-par2" data-scrollax-parent="true">
        <div class="bg-wrap bg-parallax-wrap-gradien">
            <div class="bg par-elem" data-bg="{{ $featuredImageUrl }}" data-scrollax="properties: { translateY: '30%' }"></div>
        </div>
        <div class="container">
            <div class="section-title center-align big-title">
                <h1><span>{{ $post->post_title }}</span></h1>
                <h4>Bất Động Sản Đà Lạt</h4>
            </div>
            <div class="scroll-down-wrap">
                <div class="mousey">
                    <div class="scroller"></div>
                </div>
                <span>Scroll Down To Discover</span>
            </div>
        </div>
    </section>
    <!--  section  end-->
    <!-- breadcrumbs-->
    @include('frontends.components.home_breadcrumb', [
    'title' => $post->post_title,
    'nodes' => [
            ['title' => 'Trang chủ', 'url' => route('index')],
            ['title' => 'Tin tức', 'url' => route('news.index')],
        ]
    ])
    <!-- breadcrumbs end -->
    <!-- col-list-wrap -->
    <div class="gray-bg small-padding fl-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="post-container fl-wrap">
                        <article class="post-article fl-wrap">
                            <div class="list-single-main-item fl-wrap block_box">
                                <div class="post-author">
                                    @if ($post->author)
                                        <a href="#"><img src="{{ asset('images/avatar/1.jpg') }}" alt="author"><span>By , {{ $post->author->name }}</span></a>
                                    @endif
                                </div>
                                <div class="post-opt">
                                    <ul class="no-list-style">
                                        <li><i class="fal fa-calendar"></i> <span>{{ $post->created_at->format('d/m/Y') }}</span></li>
                                        <li><i class="fal fa-eye"></i> <span>{{ $post->comment_count ?? 0 }}</span></li>
                                        <li><i class="fal fa-folder"></i>
                                            @if($post->categories && $post->categories->count() > 0)
                                                @foreach($post->categories as $idx => $cat)
                                                    @if($cat->term)
                                                        <a href="{{ route('news.category', $cat->term->slug) }}">{{ $cat->term->name }}</a>{{ $idx < $post->categories->count() - 1 ? ' ,' : '' }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </li>
                                        <li><i class="fal fa-tags"></i>
                                            @if($post->tags && $post->tags->count() > 0)
                                                @foreach($post->tags as $index => $tag)
                                                    @if($tag->term)
                                                        <a href="{{ route('news.tag', $tag->term->slug) }}">{{ $tag->term->name }}</a>{{ $index < $post->tags->count() - 1 ? ' ,' : '' }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                                <span class="fw-separator fl-wrap"></span>

                                <div class="clearfix"></div>
                                <div class="post-content">
                                    <div class="list-single-main-media fl-wrap">
                                        <img src="{{ $featuredImageUrl }}" alt="{{ $post->post_title }}" class="resp-img" loading="lazy" style="max-width: 100%; height: auto; object-fit: cover;">
                                    </div>

                                    <style>
                                        .post-content img {
                                            max-width: 100%;
                                            height: auto;
                                            object-fit: cover;
                                            margin: 15px 0;
                                        }
                                    </style>

                                    {!! $post->post_content !!}
                                </div>

                                <span class="fw-separator fl-wrap"></span>

                            </div>
                        </article>
                    </div>
                </div>
                <!-- col-md 8 end -->
                <!--  sidebar-->
                @include('frontends.news.components.news_sidebar', [
                    'categories' => $categories,
                    'tags' => $tags,
                ])
                <!-- sidebar end-->
            </div>
        </div>
    </div>
    <div class="limit-box fl-wrap"></div>
</div>
@endsection

@push('scripts')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "{{ $pageTitle }}",
  "image": [
    "{{ $featuredImageUrl }}"
   ],
  "datePublished": "{{ $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": [{
      "@type": "Person",
      "name": "{{ $post->author->name ?? 'Admin' }}",
      "url": "{{ route('index') }}"
    }],
  "publisher": {
      "@type": "Organization",
      "name": "{{ config('app.name', 'Đà Lạt BDS') }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ asset('images/logo.png') }}"
      }
  },
  "description": "{{ $pageDescription }}"
}
</script>
@endpush
