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
                        {{-- <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Popular Posts</div>
                            <div class="box-widget-content fl-wrap">
                                <!--widget-posts-->
                                <div class="widget-posts  fl-wrap">
                                    <ul class="no-list-style">
                                        <li>
                                            <div class="widget-posts-img"><a href="blog-single.html"><img
                                                        src="images/all/blog/1.jpg" alt="dalat-bds"></a></div>
                                            <div class="widget-posts-descr">
                                                <h4><a href="listing-single.html">Nullam dictum felis</a></h4>
                                                <div class="geodir-category-location fl-wrap"><a href="#"><i
                                                            class="fal fa-calendar"></i> 27 Mar 2020</a></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="widget-posts-img"><a href="blog-single.html"><img
                                                        src="images/all/blog/1.jpg" alt="dalat-bds"></a></div>
                                            <div class="widget-posts-descr">
                                                <h4><a href="listing-single.html">Scrambled it to mak</a></h4>
                                                <div class="geodir-category-location fl-wrap"><a href="#"><i
                                                            class="fal fa-calendar"></i> 12 May 2020</a></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="widget-posts-img"><a href="blog-single.html"><img
                                                        src="images/all/blog/1.jpg" alt="dalat-bds"></a> </div>
                                            <div class="widget-posts-descr">
                                                <h4><a href="listing-single.html">Fermentum nis type</a></h4>
                                                <div class="geodir-category-location fl-wrap"><a href="#"><i
                                                            class="fal fa-calendar"></i>22 Feb 2020</a></div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="widget-posts-img"><a href="blog-single.html"><img
                                                        src="images/all/blog/1.jpg" alt="dalat-bds"></a> </div>
                                            <div class="widget-posts-descr">
                                                <h4><a href="listing-single.html">Rutrum elementum</a></h4>
                                                <div class="geodir-category-location fl-wrap"><a href="#"><i
                                                            class="fal fa-calendar"></i> 7 Mar 2019</a></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- widget-posts end-->
                            </div>
                        </div> --}}
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
                        <!--box-widget end -->
                        <!--box-widget-->
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Tags</div>
                            <div class="box-widget-content fl-wrap">
                                <!--tags-->
                                <div class="list-single-tags fl-wrap tags-stylwrap" style="margin-top: 20px;">
                                    <a href="#">Nhà bán</a>
                                    <a href="#">Đất bán</a>
                                    <a href="#">Nhà Đà Lạt bán</a>
                                    <a href="#">Villa Đà Lạt bán</a>
                                    <a href="#">Nhà phố</a>
                                    <a href="#">Khách sạn bán</a>
                                </div>
                                <!--tags end-->
                            </div>
                        </div>
                        <!--box-widget end -->
                        <!--box-widget-->
                        <div class="box-widget fl-wrap">
                            <div class="box-widget-title fl-wrap">Ngày đăng</div>
                            <div class="box-widget-content fl-wrap">
                                <ul class="cat-item cat-item_dec no-list-style">
                                    <li><a href="#">tháng 6 năm 2023</a></li>
                                    <li><a href="#">tháng 5 năm 2023</a></li>
                                    <li><a href="#">tháng 4 năm 2023</a></li>
                                </ul>
                            </div>
                        </div>
                        <!--box-widget end -->
                    </div>
                    <!-- sidebar end-->
                </div>
            </div>
        </div>
    </div>
    <div class="limit-box fl-wrap"></div>
</div>
@endsection
