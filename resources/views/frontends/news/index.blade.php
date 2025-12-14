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
                                @include('frontends.news.components.article_news', ['post' => $new, 'type' => 'list'])
                                <!-- article end -->
                            @endforeach
                        @else
                            <p>Không có bài viết nào.</p>
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
