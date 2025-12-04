@extends('frontends.master')
@section('content')
<div class="content">
    <!--  section  -->
    <section class="hidden-section single-par2  " data-scrollax-parent="true">
        <div class="bg-wrap bg-parallax-wrap-gradien">
            <div class="bg par-elem " data-bg="images/bg/1.jpg" data-scrollax="properties: { translateY: '30%' }"></div>
        </div>
        <div class="container">
            <div class="section-title center-align big-title">
                <h2><span>Tin tức mới nhất</span></h2>
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
    'title' => 'Wiki BDS',
    'nodes' => [
            ['title' => 'Trang chủ', 'url' => route('index')],
        ]
    ])
    <!-- breadcrumbs end -->
    <!-- col-list-wrap -->
    <div class="gray-bg small-padding fl-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="post-container fl-wrap">
                        @if ($news->count() > 0)
                            @foreach ($news as $new)
                                <!-- article> -->
                                <article class="post-article fl-wrap">
                                    <div class="list-single-main-media fl-wrap">
                                        <div class="single-slider-wrapper carousel-wrap fl-wrap">
                                            <div class="single-slider fl-wrap carousel lightgallery">
                                                <!--  slick-slide-item -->
                                                <div class="slick-slide-item">
                                                    <div class="box-item">
                                                        <a href="images/all/blog/1.jpg" class="gal-link popup-image"><i class="fal fa-search"></i></a>
                                                        <img src="images/all/blog/1.jpg" alt="dalat-bds">
                                                    </div>
                                                </div>
                                                <!--  slick-slide-item end -->
                                            </div>
                                            <div class="swiper-button-prev ssw-btn"><i class="fas fa-caret-left"></i></div>
                                            <div class="swiper-button-next ssw-btn"><i class="fas fa-caret-right"></i></div>
                                        </div>
                                    </div>
                                    <div class="list-single-main-item fl-wrap block_box">
                                        <h2 class="post-opt-title"><a href="{{ route('news.show', $new->post_name) }}">{{ $new->post_title }}</a></h2>
                                        <p>{{ $new->post_excerpt }}</p>
                                        <span class="fw-separator fl-wrap"></span>
                                        <div class="post-author">
                                            @if ($new->author)
                                                <a href="#"><img src="images/avatar/1.jpg" alt="author"><span>By , {{ $new->author->name }}</span></a>
                                            @endif
                                        </div>
                                        <div class="post-opt">
                                            <ul class="no-list-style">
                                                <li><i class="fal fa-calendar"></i> <span>{{ $new->created_at->format('d M Y') }}</span></li>
                                                <li><i class="fal fa-eye"></i> <span>{{ $new->comment_count }}</span></li>
                                                @if($new->tags->count() > 0)
                                                    <li><i class="fal fa-tags"></i>
                                                        @foreach($new->tags as $index => $tag)

                                                            <a href="{{ route('news.tag', $tag->term->slug) }}">{{ $tag->term->name }}</a>{{ $index < $new->tags->count() - 1 ? ' ,' : '' }}

                                                        @endforeach
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                        <a href="{{ route('news.show', $new->post_name) }}" class="btn color-bg float-btn small-btn">Xem thêm</a>
                                    </div>
                                </article>
                                <!-- article end -->
                            @endforeach
                        @else
                            <p>No news posts found.</p>
                        @endif

                        <!-- pagination-->
                        <div class="pagination">
                            {{ $news->links() }}
                        </div>
                        <!-- pagination end-->
                    </div>
                </div>
                <!-- col-md 8 end -->
                <!--  sidebar-->
                @include('frontends.news.news_sidebar', [
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
