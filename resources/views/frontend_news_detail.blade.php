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
                <h2><span>{{ $news->post_title }}</span></h2>
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
    'title' => $news->post_title,
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
                                    @if ($news->author)
                                        <a href="#"><img src="{{ asset('images/avatar/1.jpg') }}" alt="author"><span>By , {{ $news->author->name }}</span></a>
                                    @endif
                                </div>
                                <div class="post-opt">
                                    <ul class="no-list-style">
                                        <li><i class="fal fa-calendar"></i> <span>{{ $news->created_at->format('d M Y') }}</span></li>
                                        <li><i class="fal fa-eye"></i> <span>164</span></li>
                                        <li><i class="fal fa-tags"></i> <a href="#">Shop</a> , <a href="#">Hotels</a></li>
                                    </ul>
                                </div>
                                <span class="fw-separator fl-wrap"></span>
                                
                                <div class="clearfix"></div>
                                <div class="post-content">
                                    {!! $news->post_content !!}
                                </div>

                                <span class="fw-separator fl-wrap"></span>
                                
                            </div>
                        </article>
                    </div>
                </div>
                <!-- col-md 8 end -->
                <!--  sidebar-->
                <div class="col-md-4">
                     <div class="box-widget-wrap fl-wrap fixed-bar">
                        <!--box-widget-->
                        <div class="box-widget fl-wrap">
                            <div class="search-widget fl-wrap">
                                <form action="{{ route('news.index') }}" method="GET" class="fl-wrap custom-form">
                                    <input name="se" id="se" type="text" class="search" placeholder="Tìm kiếm"
                                        value="{{ request('se') }}" />
                                    <button class="search-submit" id="submit_btn"><i class="far fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <!--box-widget end -->
                        
                        <!--box-widget-->
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Danh mục</div>
                            <div class="box-widget-content fl-wrap">
                                <ul class="cat-item no-list-style">
                                    <li><a href="#">Tin thị trường</a> <span>3</span></li>
                                    <li><a href="#">Quy hoạch</a> <span>6 </span></li>
                                    <li><a href="#">Chính sách</a> <span>12 </span></li>
                                </ul>
                            </div>
                        </div>
                        <!--box-widget end -->
                       
                        <!--box-widget-->
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Tags</div>
                            <div class="box-widget-content fl-wrap">
                                <!--tags-->
                                <div class="list-single-tags fl-wrap tags-stylwrap" style="margin-top: 20px;">
                                    <a href="#">Nhà bán</a>
                                    <a href="#">Đất bán</a>
                                </div>
                                <!--tags end-->
                            </div>
                        </div>
                        <!--box-widget end -->
                    </div>
                </div>
                <!-- sidebar end-->
            </div>
        </div>
    </div>
    <div class="limit-box fl-wrap"></div>
</div>
@endsection