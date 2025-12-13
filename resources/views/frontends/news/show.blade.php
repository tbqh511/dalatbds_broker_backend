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
                                                    <div class="single-slider fl-wrap carousel lightgallery"  >
                                                        <!--  slick-slide-item -->
                                                        <div class="slick-slide-item">
                                                            <div class="box-item">
                                                                <a href="../../../Downloads/themeforest-8CFJTVUZ-homeradar-directory-listing-real-estate-template/homeradar/light/images/all/blog/1.jpg" class="gal-link popup-image"><i class="fal fa-search"  ></i></a>
                                                                <img src="../../../Downloads/themeforest-8CFJTVUZ-homeradar-directory-listing-real-estate-template/homeradar/light/images/all/blog/1.jpg" alt="">
                                                            </div>
                                                        </div>
                                                        <!--  slick-slide-item end -->
                                                        <!--  slick-slide-item -->
                                                        <div class="slick-slide-item">
                                                            <div class="box-item">
                                                                <a href="../../../Downloads/themeforest-8CFJTVUZ-homeradar-directory-listing-real-estate-template/homeradar/light/images/all/blog/1.jpg" class="gal-link popup-image"><i class="fal fa-search"  ></i></a>
                                                                <img src="../../../Downloads/themeforest-8CFJTVUZ-homeradar-directory-listing-real-estate-template/homeradar/light/images/all/blog/1.jpg" alt="">
                                                            </div>
                                                        </div>
                                                        <!--  slick-slide-item end -->
                                                        <!--  slick-slide-item -->
                                                        <div class="slick-slide-item">
                                                            <div class="box-item">
                                                                <a href="../../../Downloads/themeforest-8CFJTVUZ-homeradar-directory-listing-real-estate-template/homeradar/light/images/all/blog/1.jpg" class="gal-link popup-image"><i class="fal fa-search"  ></i></a>
                                                                <img src="../../../Downloads/themeforest-8CFJTVUZ-homeradar-directory-listing-real-estate-template/homeradar/light/images/all/blog/1.jpg" alt="">
                                                            </div>
                                                        </div>
                                                        <!--  slick-slide-item end -->
                                                    </div>
                                                    <div class="swiper-button-prev ssw-btn"><i class="fas fa-caret-left"></i></div>
                                                    <div class="swiper-button-next ssw-btn"><i class="fas fa-caret-right"></i></div>
                                                </div>
                                            </div>
                                            <div class="list-single-main-item fl-wrap block_box">
                                                <div class="single-article-header fl-wrap">
                                                    <h2 class="post-opt-title"><a href="blog-single.html">Best House to Your Family .</a></h2>
                                                    <span class="fw-separator"></span>
                                                    <div class="clearfix"></div>
                                                    <div class="post-author"><a href="#"><img src="../../../Downloads/themeforest-8CFJTVUZ-homeradar-directory-listing-real-estate-template/homeradar/light/images/avatar/1.jpg" alt=""><span>By , Alisa Noory</span></a></div>
                                                    <div class="post-opt">
                                                        <ul class="no-list-style">
                                                            <li><i class="fal fa-calendar"></i> <span>15 April 2019</span></li>
                                                            <li><i class="fal fa-eye"></i> <span>164</span></li>
                                                            <li><i class="fal fa-tags"></i> <a href="#">Shop</a> , <a href="#">Hotels</a> </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <span class="fw-separator fl-wrap"></span>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis mollis et sem sed sollicitudin. Donec non odio neque. Aliquam hendrerit sollicitudin purus, quis rutrum mi accumsan nec. Quisque bibendum orci ac nibh facilisis, at malesuada orci congue. Nullam tempus sollicitudin cursus. Ut et adipiscing erat. Curabitur this is a text link libero tempus congue.</p>
                                                <p>Duis mattis laoreet neque, et ornare neque sollicitudin at. Proin sagittis dolor sed mi elementum pretium. Donec et justo ante. Vivamus egestas sodales est, eu rhoncus urna semper eu. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer tristique elit lobortis purus bibendum, quis dictum metus mattis. Phasellus posuere felis sed eros porttitor mattis. Curabitur massa magna, tempor in blandit id, porta in ligula. Aliquam laoreet nisl massa, at interdum mauris sollicitudin et</p>
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
                                                <ul>
                                                    <li>
                                                        <a href="blog-single.html" class="ln"><i class="fal fa-long-arrow-left"></i><span>Prev <strong>- Post Title</strong></span></a>
                                                        <div class="content-nav-media">
                                                            <div class="bg"  data-bg="images/bg/1.jpg"></div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <a href="blog-single.html" class="rn"><span >Next <strong>- Post Title</strong></span> <i class="fal fa-long-arrow-right"></i></a>
                                                        <div class="content-nav-media">
                                                            <div class="bg"  data-bg="images/bg/1.jpg"></div>
                                                        </div>
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
