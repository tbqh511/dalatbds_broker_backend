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
                                                        <a href="{{ (function_exists('route') ? (Route::has('news.tag') ? route('news.tag', $t->slug ?? $t->id) : url('/tin-tuc/tag/' . ($t->slug ?? $t->id))) : url('/tin-tuc/tag/' . ($t->slug ?? $t->id))) }}">{{ $t->name ?? $t->tag }}</a>@if(!$loop->last), @endif
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
                                <blockquote>
                                    <p>Vestibulum id ligula porta felis euismod semper. Sed posuere consectetur est at lobortis. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper.</p>
                                </blockquote>
                                <p>Ut nec hinc dolor possim. An eros argumentum vel, elit diceret duo eu, quo et aliquid ornatus delicatissimi. Cu nam tale ferri utroque, eu habemus albucius mel, cu vidit possit ornatus eum. Eu ius postulant salutatus definitionem, an e trud erroribus explicari. Graeci viderer qui ut, at habeo facer solet usu. Pri choro pertinax indoctum ne, ad partiendo persecuti forensibus est.</p>
                                <div class="clearfix"></div>
                                <span class="fw-separator fl-wrap"></span>
                                <div class="list-single-tags tags-stylwrap">
                                    <span class="tags-title">  Tags : </span>
                                    <a href="#">Hotel</a>
                                    <a href="#">Hostel</a>
                                    <a href="#">Room</a>
                                    <a href="#">Spa</a>
                                    <a href="#">Restourant</a>
                                    <a href="#">Parking</a>
                                </div>
                            </div>
                        </article>
                        <!-- article end -->
                        <!--content-nav_holder-->
                        <div class="content-nav_holder fl-wrap color-bg">
                            <div class="content-nav">
                                @php
                                    // Prefer controller-provided $prevPost / $nextPost. Fallback to simple DB query using the same table as the current model.
                                    $prevPost = $prevPost ?? (is_object($post) && method_exists($post,'getTable') ? \DB::table($post->getTable())->where('id','<',$post->id)->orderBy('id','desc')->first() : null);
                                    $nextPost = $nextPost ?? (is_object($post) && method_exists($post,'getTable') ? \DB::table($post->getTable())->where('id','>',$post->id)->orderBy('id','asc')->first() : null);

                                    $makeNewsUrl = function ($p) {
                                        if (!$p) return '#';
                                        $slug = $p->post_slug ?? $p->slug ?? $p->id;
                                        return url('/tin-tuc/' . $slug);
                                    };
                                @endphp

                                <ul>
                                    <li>
                                        @if($prevPost)
                                            <a href="{{ $makeNewsUrl($prevPost) }}" class="ln"><i class="fal fa-long-arrow-left"></i><span>Prev <strong>- {{ Str::limit($prevPost->post_title ?? ($prevPost->title ?? ''), 50) }}</strong></span></a>
                                            <div class="content-nav-media">
                                                <div class="bg" data-bg="{{ isset($prevPost->id) ? (asset('assets/images/posts/' . basename(optional($prevPost)->_thumbnail ?? '') ) ) : '' }}"></div>
                                            </div>
                                        @else
                                            <a href="#" class="ln disabled"><i class="fal fa-long-arrow-left"></i><span>Prev <strong>-</strong></span></a>
                                        @endif
                                    </li>
                                    <li>
                                        @if($nextPost)
                                            <a href="{{ $makeNewsUrl($nextPost) }}" class="rn"><span>Next <strong>- {{ Str::limit($nextPost->post_title ?? ($nextPost->title ?? ''), 50) }}</strong></span> <i class="fal fa-long-arrow-right"></i></a>
                                            <div class="content-nav-media">
                                                <div class="bg" data-bg="{{ isset($nextPost->id) ? (asset('assets/images/posts/' . basename(optional($nextPost)->_thumbnail ?? '') ) ) : '' }}"></div>
                                            </div>
                                        @else
                                            <a href="#" class="rn disabled"><span>Next <strong>-</strong></span> <i class="fal fa-long-arrow-right"></i></a>
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
