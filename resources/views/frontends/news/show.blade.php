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
                                        <li><i class="fal fa-calendar"></i> <span>{{ $post->created_at->format('d M Y') }}</span></li>
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
                                    <?php
                                        // Try to extract first image from post content to use as featured image
                                        $content = $post->post_content ?? '';
                                        $featured = '';
                                        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m)) {
                                            $raw = $m[1];
                                            // If relative path (not starting with http or /), convert via asset()
                                            if (preg_match('/^(https?:)?\/\//', $raw) || str_starts_with($raw, '/')) {
                                                $featured = $raw;
                                            } else {
                                                $featured = asset($raw);
                                            }
                                            // remove first img tag from content to avoid duplicate display
                                            $content = preg_replace('/<img[^>]*>/i', '', $content, 1);
                                        }
                                        // fallback to default image
                                        if (empty($featured)) {
                                            $featured = asset('images/all/blog/1.jpg');
                                        }
                                    ?>

                                    <div class="list-single-main-media fl-wrap">
                                        <img src="{{ $featured }}" alt="{{ $post->post_title }}">
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
