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
                        <article class="post-article fl-wrap">
                            <div class="list-single-main-media fl-wrap">
                                <div class="single-slider-wrapper carousel-wrap fl-wrap">
                                    <div class="single-slider fl-wrap carousel lightgallery">
                                        <!-- Featured image (use public copy or storage url) -->
                                        <div class="slick-slide-item">
                                            <div class="box-item">
                                                <a href="{{ $featuredImageUrl }}" class="gal-link popup-image"><i class="fal fa-search"></i></a>
                                                <img src="{{ $featuredImageUrl }}" alt="{{ $post->post_title }}">
                                            </div>
                                        </div>
                                        @if(method_exists($post, 'gallery') && $post->gallery && count($post->gallery))
                                            @foreach($post->gallery as $g)
                                                @php
                                                    $gUrl = (file_exists(public_path('assets/images/posts/' . basename($g))) ? asset('assets/images/posts/' . basename($g)) : (\Storage::disk('public')->exists($g) ? \Storage::url($g) : $featuredImageUrl));
                                                @endphp
                                                <div class="slick-slide-item">
                                                    <div class="box-item">
                                                        <a href="{{ $gUrl }}" class="gal-link popup-image"><i class="fal fa-search"></i></a>
                                                        <img src="{{ $gUrl }}" alt="{{ $post->post_title }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="swiper-button-prev ssw-btn"><i class="fas fa-caret-left"></i></div>
                                    <div class="swiper-button-next ssw-btn"><i class="fas fa-caret-right"></i></div>
                                </div>
                            </div>
                            <div class="list-single-main-item fl-wrap block_box">
                                <div class="single-article-header fl-wrap">
                                    <h2 class="post-opt-title"><a href="#">{{ $post->post_title }}</a></h2>
                                    <span class="fw-separator"></span>
                                    <div class="clearfix"></div>
                                    <div class="post-author">
                                        <a href="#">
                                            <img src="{{ optional($post->author)->avatar ? asset(optional($post->author)->avatar) : asset('images/avatar/1.jpg') }}" alt="{{ optional($post->author)->name ?? 'Admin' }}">
                                            <span>By {{ optional($post->author)->name ?? 'Admin' }}</span>
                                        </a>
                                    </div>
                                    <div class="post-opt">
                                        <ul class="no-list-style">
                                            <li><i class="fal fa-calendar"></i> <span>{{ $post->created_at->format('d F Y') }}</span></li>
                                            <li><i class="fal fa-eye"></i> <span>{{ $post->view_count ?? $post->views ?? 0 }}</span></li>
                                            <li><i class="fal fa-tags"></i>
                                                @if(isset($post->tags) && count($post->tags))
                                                        @foreach($post->tags as $t)
                                                            @php
                                                                // Determine slug or id from possible structures
                                                                $slug = $t->slug ?? data_get($t, 'term.slug') ?? data_get($t, 'term_id') ?? data_get($t, 'id');
                                                                $label = $t->name ?? data_get($t, 'term.name') ?? $t->tag ?? ($slug ?? 'Tag');
                                                                if ($slug) {
                                                                    if (function_exists('route') && (Route::has('news.tag'))) {
                                                                        $tagUrl = route('news.tag', $slug);
                                                                    } else {
                                                                        $tagUrl = url('/tin-tuc/tag/' . $slug);
                                                                    }
                                                                } else {
                                                                    $tagUrl = '#';
                                                                }
                                                            @endphp
                                                            <a href="{{ $tagUrl }}">{{ $label }}</a>@if(!$loop->last), @endif
                                                        @endforeach
                                                    @else
                                                    @if(isset($post->meta))
                                                        @php
                                                            $tagMeta = $post->meta->where('meta_key','_tags')->first();
                                                            $tagNames = $tagMeta ? explode(',', $tagMeta->meta_value) : [];
                                                        @endphp
                                                        @foreach($tagNames as $tn)
                                                            <a href="#">{{ trim($tn) }}</a>@if(!$loop->last), @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <span class="fw-separator fl-wrap"></span>
                                {{-- Post content --}}
                                {!! $post->post_content !!}
                                <span class="fw-separator fl-wrap"></span>
                                <div class="list-single-tags tags-stylwrap">
                                    <span class="tags-title">Tags:</span>
                                    @if(isset($post->tags) && $post->tags->count() > 0)
                                        @foreach($post->tags as $tag)
                                            @if(isset($tag->term))
                                                <a href="{{ route('news.tag', $tag->term->slug) }}">{{ $tag->term->name }}</a>
                                            @endif
                                        @endforeach
                                    @else
                                        <span>Không có tags</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                        <!-- article end -->
                        <!--content-nav_holder-->
                        <div class="content-nav_holder fl-wrap color-bg">
                            <div class="content-nav">
                                @php
                                    $makeNewsUrl = function ($p) {
                                        if (!$p) return '#';
                                        $slug = $p->post_slug ?? $p->slug ?? $p->ID ?? $p->id;
                                        return url('/tin-tuc/' . $slug);
                                    };

                                    $getThumbnailUrl = function ($p) {
                                        if (!$p) return '';
                                        $thumb = $p->_thumbnail ?? null;
                                        if (!$thumb) return '';
                                        
                                        // Check if public file exists
                                        $publicPath = 'assets/images/posts/' . basename($thumb);
                                        if (file_exists(public_path($publicPath))) {
                                            return asset($publicPath);
                                        }
                                        
                                        // Check storage
                                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($thumb)) {
                                            return \Illuminate\Support\Facades\Storage::url($thumb);
                                        }

                                        return '';
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
                                            <a href="{{ $makeNewsUrl($prevPost) }}" class="ln"><i class="fal fa-long-arrow-left"></i><span>Trước <strong>- {{ Str::limit($prevPost->post_title ?? ($prevPost->title ?? ''), 40) }}</strong></span></a>
                                            <div class="content-nav-media">
                                                <div class="bg" data-bg="{{ $getThumbnailUrl($prevPost) }}"></div>
                                            </div>
                                        @else
                                            <a href="#" class="ln disabled"><i class="fal fa-long-arrow-left"></i><span>Trước <strong>-</strong></span></a>
                                        @endif
                                    </li>
                                    <li>
                                        @if($nextPost)
                                            <a href="{{ $makeNewsUrl($nextPost) }}" class="rn"><span>Sau <strong>- {{ Str::limit($nextPost->post_title ?? ($nextPost->title ?? ''), 40) }}</strong></span> <i class="fal fa-long-arrow-right"></i></a>
                                            <div class="content-nav-media">
                                                <div class="bg" data-bg="{{ $getThumbnailUrl($nextPost) }}"></div>
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
