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
    <!-- breadcrumbs-->
    <div class="breadcrumbs fw-breadcrumbs sp-brd fl-wrap top-smpar">
        <div class="container">
            <div class="breadcrumbs-list">
                <a href="{{ route('index') }}">Trang chủ</a>
                <a href="{{ route('news.index') }}">Tin tức</a>
                <span>{{ $post->post_title }}</span>
            </div>
            <div class="share-holder hid-share">
                <a href="#" class="share-btn showshare sfcs">  <i class="fas fa-share-alt"></i>  Share   </a>
                <div class="share-container isShare"></div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs end -->

    <!-- col-list-wrap -->
    <div class="gray-bg small-padding fl-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="post-container fl-wrap">
                        <!-- article> -->
                        @include('frontends.news.components.article_news', ['post' => $post, 'type' => 'detail', 'isDetail' => true, 'thumbUrl' => $featuredImageUrl])
                        <!-- article end -->
                        <!--content-nav_holder-->
                        <div class="content-nav_holder fl-wrap color-bg">
                            <div class="content-nav">
                                @php
                                    $makeNewsUrl = function ($slug) {
                                        if (!$slug) return '#';
                                        return url('/tin-tuc/' . $slug);
                                    };
                                @endphp
                                <style>
                                    .content-nav li a {
                                        max-width: 100%;
                                        display: block;
                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                        white-space: nowrap;
                                        transition: all 0.3s ease;
                                        padding-right: 15px; /* space for arrow */
                                    }
                                    .content-nav li a.rn {
                                        padding-left: 15px;
                                        padding-right: 0;
                                        text-align: right;
                                    }
                                    .content-nav li a:hover {
                                        color: #3270FC; /* Theme color */
                                        transform: translateX(5px);
                                    }
                                    .content-nav li a.ln:hover {
                                        transform: translateX(-5px);
                                    }
                                    .content-nav-media .bg {
                                        background-color: #f5f7fb; /* Fallback color */
                                        transition: transform 0.4s ease;
                                    }
                                    .content-nav li:hover .content-nav-media .bg {
                                        transform: scale(1.1);
                                    }
                                </style>

                                <ul>
                                    <li>
                                        @if($prevPost)
                                            <a href="{{ $makeNewsUrl($prevPost->post_name ?? $prevPost->slug) }}" class="ln"><i class="fal fa-long-arrow-left"></i><span>Trước</span></a>
                                            <div class="content-nav-media">
                                                <div class="bg" data-bg="{{ asset('assets/images/posts/sidebar_banner.png') }}"></div>
                                            </div>
                                        @else
                                            <a href="#" class="ln disabled"><i class="fal fa-long-arrow-left"></i><span>Trước <strong>-</strong></span></a>
                                        @endif
                                    </li>
                                    <li>
                                        @if($nextPost)
                                            <a href="{{ $makeNewsUrl($nextPost->post_name ?? $nextPost->slug) }}" class="rn"><span>Sau</span> <i class="fal fa-long-arrow-right"></i></a>
                                            <div class="content-nav-media">
                                                <div class="bg" data-bg="{{ asset('assets/images/posts/sidebar_banner.png') }}"></div>
                                            </div>
                                        @else
                                            <a href="#" class="rn disabled"><span>Sau <strong>-</strong></span> <i class="fal fa-long-arrow-right"></i></a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--content-nav_holder end -->
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

@push('head_scripts')
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
