
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
                                    @foreach($categories as $category)
                                        @if($category->term)
                                            <li><a href="{{ route('news.category', $category->term->slug) }}">{{ $category->term->name }}</a> <span>{{ $category->count }}</span></li>
                                        @endif
                                    @endforeach
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
                                    @foreach($tags as $tag)
                                        @if($tag->term)
                                            <a href="{{ route('news.tag', $tag->term->slug) }}">{{ $tag->term->name }}</a>
                                        @endif
                                    @endforeach
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
                        <!--box-widget end -->
                    </div>
                </div>