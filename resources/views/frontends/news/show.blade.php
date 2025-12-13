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
                                <img src="{{ $featuredImageUrl }}" alt="{{ $post->post_title }}" class="resp-img" loading="lazy" style="width: 100%; height: auto;">
                            </div>
                            <div class="list-single-main-item fl-wrap block_box">
                                <div class="single-article-header fl-wrap">
                                    <h1 class="post-opt-title">{{ $post->post_title }}</h1>
                                    <span class="fw-separator"></span>
                                    <div class="clearfix"></div>
                                    <div class="post-author">
                                        @if ($post->author)
                                            <a href="#"><img src="{{ asset('images/avatar/1.jpg') }}" alt="{{ $post->author->name }}"><span>By, {{ $post->author->name }}</span></a>
                                        @endif
                                    </div>
                                    <div class="post-opt">
                                        <ul class="no-list-style">
                                            <li><i class="fal fa-calendar"></i> <span>{{ $post->created_at ? $post->created_at->format('d/m/Y') : '' }}</span></li>
                                            <li><i class="fal fa-eye"></i> <span>{{ $post->comment_count ?? 0 }}</span></li>
                                            @if($post->categories->count() > 0)
                                                <li><i class="fal fa-folder"></i>
                                                @foreach($post->categories as $idx => $cat)
                                                    @if($cat->term)
                                                        <a href="{{ route('news.category', $cat->term->slug) }}">{{ $cat->term->name }}</a>@if(!$loop->last),@endif
                                                    @endif
                                                @endforeach
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <span class="fw-separator fl-wrap"></span>

                                <div class="post-content fl-wrap">
                                    {!! $post->post_content !!}
                                </div>

                                <span class="fw-separator fl-wrap"></span>

                                @if($post->tags->count() > 0)
                                    <div class="list-single-tags tags-stylwrap">
                                        <span class="tags-title">  Tags : </span>
                                        @foreach($post->tags as $tag)
                                            @if($tag->term)
                                                <a href="{{ route('news.tag', $tag->term->slug) }}">{{ $tag->term->name }}</a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>
                        <!-- article end -->

                        <!-- author-bio -->
                        <div class="author-bio-wrap fl-wrap block_box">
                            <div class="author-bio-img">
                                <img src="{{ asset('images/avatar/1.jpg') }}" alt="{{ $post->author->name ?? 'Admin' }}" class="resp-img">
                            </div>
                            <div class="author-bio-content">
                                <h4><a>{{ $post->author->name ?? 'Admin' }}</a></h4>
                                <p>Đội ngũ biên tập viên chuyên nghiệp của Đà Lạt BDS, mang đến những thông tin thị trường nhanh chóng và chính xác nhất.</p>
                            </div>
                            <a href="#" class="author-bio-link">Xem tất cả bài viết</a>
                        </div>
                        <!-- author-bio end -->

                        <!-- Post-nav -->
                        {{-- You can add logic for Next/Prev post here if needed --}}

                        <!-- list-single-main-item -->
                        <div class="list-single-main-item fl-wrap" id="sec-comments">
                            <div class="list-single-main-item-title">
                                <h3>Bình luận <span>0</span></h3>
                            </div>
                            <div class="list-single-main-item_content fl-wrap">
                                {{-- Comment list can be implemented here --}}
                                <p>Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                            </div>
                        </div>
                        <!-- list-single-main-item end -->

                        <!-- list-single-main-item -->
                        <div class="list-single-main-item fl-wrap" id="sec-add-comment">
                            <div class="list-single-main-item-title fl-wrap">
                                <h3>Để lại bình luận</h3>
                            </div>
                            <div id="add-review" class="add-review-box">
                                <form class="add-comment custom-form">
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Tên* <span class="dec-icon"><i class="fas fa-user"></i></span></label>
                                                <input name="name" type="text" onClick="this.select()" value="">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Email* <span class="dec-icon"><i class="fas fa-envelope"></i></span></label>
                                                <input name="email" type="text" onClick="this.select()" value="">
                                            </div>
                                        </div>
                                        <textarea cols="40" rows="3" placeholder="Nội dung bình luận:"></textarea>
                                    </fieldset>
                                    <button class="btn big-btn color-bg float-btn">Gửi bình luận <i class="fa fa-paper-plane-o"></i></button>
                                </form>
                            </div>
                        </div>
                        <!-- list-single-main-item end -->
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
