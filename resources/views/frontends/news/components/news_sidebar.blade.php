@extends('frontends.master')
@section('content')
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
    @include('frontends.components.home_breadcrumb', [
    'title' => 'Wiki BDS',
    'nodes' => [
            ['title' => 'Trang chủ', 'url' => route('index')],
        ]
    ])
    <div class="gray-bg small-padding fl-wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="post-container fl-wrap">
                        @if ($news->count() > 0)
                            @foreach ($news as $new)
                                <article class="post-article fl-wrap">
                                    <div class="list-single-main-media fl-wrap">
                                        <div class="single-slider-wrapper carousel-wrap fl-wrap">
                                            <div class="single-slider fl-wrap carousel lightgallery">
                                                <div class="slick-slide-item">
                                                    <div class="box-item">
                                                        <a href="images/all/blog/1.jpg" class="gal-link popup-image"><i class="fal fa-search"></i></a>
                                                        <img src="images/all/blog/1.jpg" alt="dalat-bds">
                                                    </div>
                                                </div>
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
                                @endforeach
                            <div class="pagination">
                                {{ $news->links('frontends.components.pagination') }}
                            </div>
                        @else
                            <p>Không tìm thấy bài viết nào.</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box-widget-wrap fl-wrap fixed-bar">
                        <div class="box-widget fl-wrap">
                            <div class="search-widget fl-wrap">
                                <form action="{{ route('news.index') }}" method="GET" class="fl-wrap custom-form">
                                    <input name="se" id="se" type="text" class="search" placeholder="Tìm kiếm"
                                        value="{{ request('se') }}" />
                                    <button class="search-submit" id="submit_btn"><i class="far fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Danh mục</div>
                            <div class="box-widget-content fl-wrap">
                                <ul class="cat-item no-list-style">
                                    @foreach($categories as $category)
                                        @if(isset($category->term))
                                            <li><a href="{{ route('news.category', $category->term->slug) }}">{{ $category->term->name }}</a> <span>{{ $category->count }}</span></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="box-widget fl-wrap">
                            <div class="banner-widget fl-wrap">
                                <div class="bg-wrap bg-parallax-wrap-gradien">
                                    <div class="bg  " data-bg="https://i.pravatar.cc/388"></div>
                                </div>
                                <div class="banner-widget_content">
                                    <h5>Bạn có muốn tham gia mạng lưới thổ địa cùng Đà Lạt BDS?</h5>
                                    <a href="#" class="btn float-btn color-bg small-btn">Hãy trở thành Đối Tác của Đà Lạt BDS</a>
                                </div>
                            </div>
                        </div>
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Tags</div>
                            <div class="box-widget-content fl-wrap">
                                <div class="list-single-tags fl-wrap tags-stylwrap" style="margin-top: 20px;">
                                    @foreach($tags as $tag)
                                        @if(isset($tag->term))
                                            <a href="{{ route('news.tag', $tag->term->slug) }}">{{ $tag->term->name }}</a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Ngày đăng</div>
                            <div class="box-widget-content fl-wrap">
                                <ul class="cat-item cat-item_dec no-list-style">
                                    @if(isset($months) && $months->count() > 0)
                                        @foreach($months as $m)
                                            <li>
                                                <a href="{{ route('news.month', ['year' => $m->year, 'month' => $m->month]) }}">tháng {{ $m->month }} năm {{ $m->year }}</a>
                                                <span>({{ $m->count }})</span>
                                            </li>
                                        @endforeach
                                    @else
                                        <li>Không có bài viết</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection