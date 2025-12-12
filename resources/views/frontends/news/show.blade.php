@extends('frontends.master')
@section('content')
<div class="content">
    <!--  section  -->
    <section class="hidden-section single-par2  " data-scrollax-parent="true">
        <div class="bg-wrap bg-parallax-wrap-gradien">
            <div class="bg par-elem " data-bg="{{ asset('images/bg/1.jpg') }}" data-scrollax="properties: { translateY: '30%' }"></div>
        </div>
        <div class="container">
            <div class="section-title center-align big-title">
                <h2><span>{{ $post->post_title }}</span></h2>
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
                                    @php
                                        // Priority 1: Check metadata _thumbnail
                                        $featured = null;
                                        $thumbMeta = $post->meta->where('meta_key', '_thumbnail')->first();

                                        if ($thumbMeta && $thumbMeta->meta_value) {
                                            // Check direct public asset copy first
                                            if (file_exists(public_path('assets/images/posts/' . basename($thumbMeta->meta_value)))) {
                                                $featured = asset('assets/images/posts/' . basename($thumbMeta->meta_value));
                                            }
                                            // Check storage via symlink
                                            elseif (Storage::disk('public')->exists($thumbMeta->meta_value)) {
                                                $featured = Storage::url($thumbMeta->meta_value);
                                            }
                                        }

                                        // Priority 2: Extract from content if no metadata thumbnail
                                        $content = $post->post_content ?? '';
                                        if (empty($featured) && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m)) {
                                            $raw = $m[1];
                                            if (preg_match('/^(https?:)?\/\//', $raw) || str_starts_with($raw, '/')) {
                                                $featured = $raw;
                                            } else {
                                                $featured = asset($raw);
                                            }
                                        }

                                        // Priority 3: Fallback default
                                        if (empty($featured)) {
                                            $featured = asset('images/all/blog/1.jpg');
                                        }
                                    @endphp

                                    <div class="list-single-main-media fl-wrap">
                                        <img src="{{ $featured }}" alt="{{ $post->post_title }}" class="resp-img">
                                    </div>

                                    {!! $content !!}
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
