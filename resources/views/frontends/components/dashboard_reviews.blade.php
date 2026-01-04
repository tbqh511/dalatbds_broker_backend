<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Đánh giá'])
            <!-- dashboard-title end -->
            <div class="dasboard-wrapper fl-wrap">
                <div class="dasboard-widget-title fl-wrap">
                    <h5><i class="fal fa-comments-alt"></i>Đánh giá mới nhất <span> ( +2 Mới ) </span></h5>
                    <a href="#" class="mark-btn tolt" data-microtip-position="bottom" data-tooltip="Đánh dấu tất cả đã đọc"><i class="far fa-comment-alt-check"></i> </a>
                </div>
                <div class="dasboard-widget-box fl-wrap">
                    <div class="dasboard-opt fl-wrap">
                        <!-- price-opt-->
                        <div class="price-opt">
                            <span class="price-opt-title">Sắp xếp theo:</span>
                            <div class="listsearch-input-item">
                                <select data-placeholder="Mới nhất" class="chosen-select no-search-select" >
                                    <option>Mới nhất</option>
                                    <option>Cũ nhất</option>
                                    <option>Đánh giá trung bình</option>
                                </select>
                            </div>
                        </div>
                        <!-- price-opt end-->
                    </div>
                    <!-- reviews-comments-item -->
                    <div class="reviews-comments-item">
                        <div class="review-comments-avatar">
                            <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                            <div class="review-notifer">Mới</div>
                        </div>
                        <div class="reviews-comments-item-text smpar">
                            <div class="box-widget-menu-btn smact"><i class="far fa-ellipsis-h"></i></div>
                            <div class="show-more-snopt-tooltip bxwt">
                                <a href="#"> <i class="fas fa-reply"></i> Trả lời</a>
                                <a href="#"> <i class="fas fa-exclamation-triangle"></i> Báo cáo </a>
                            </div>
                            <h4><a href="#">Liza Rose <span>cho Biệt thự gia đình sang trọng </span></a></h4>
                            <div class="listing-rating card-popup-rainingvis" data-starrating2="3"><span class="re_stars-title">Trung bình</span></div>
                            <div class="clearfix"></div>
                            <p>" Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. "</p>
                            <div class="reviews-comments-item-date"><span class="reviews-comments-item-date-item"><i class="far fa-calendar-check"></i>12 Tháng 4 2020</span><a href="#" class="rate-review"><i class="fal fa-thumbs-up"></i>  Đánh giá hữu ích  <span>6</span> </a></div>
                        </div>
                    </div>
                    <!--reviews-comments-item end-->
                    <!-- reviews-comments-item -->
                    <div class="reviews-comments-item">
                        <div class="review-comments-avatar">
                            <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                            <div class="review-notifer">Mới</div>
                        </div>
                        <div class="reviews-comments-item-text smpar">
                            <div class="box-widget-menu-btn smact"><i class="far fa-ellipsis-h"></i></div>
                            <div class="show-more-snopt-tooltip bxwt">
                                <a href="#"> <i class="fas fa-reply"></i> Trả lời</a>
                                <a href="#"> <i class="fas fa-exclamation-triangle"></i> Báo cáo </a>
                            </div>
                            <h4><a href="#">Adam Koncy <span>cho Nhà phố Kayak Point</span></a></h4>
                            <div class="listing-rating card-popup-rainingvis" data-starrating2="5"><span class="re_stars-title">Tuyệt vời</span></div>
                            <div class="clearfix"></div>
                            <p>" Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc posuere convallis purus non cursus. Cras metus neque, gravida sodales massa ut. "</p>
                            <div class="reviews-comments-item-date"><span class="reviews-comments-item-date-item"><i class="far fa-calendar-check"></i>03 Tháng 12 2019</span><a href="#" class="rate-review"><i class="fal fa-thumbs-up"></i>  Đánh giá hữu ích  <span>2</span> </a></div>
                        </div>
                    </div>
                    <!--reviews-comments-item end-->
                    <!-- reviews-comments-item -->
                    <div class="reviews-comments-item">
                        <div class="review-comments-avatar">
                            <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                        </div>
                        <div class="reviews-comments-item-text smpar">
                            <div class="box-widget-menu-btn smact"><i class="far fa-ellipsis-h"></i></div>
                            <div class="show-more-snopt-tooltip bxwt">
                                <a href="#"> <i class="fas fa-reply"></i> Trả lời</a>
                                <a href="#"> <i class="fas fa-exclamation-triangle"></i> Báo cáo </a>
                            </div>
                            <h4><a href="#">Mark Rose <span>cho Biệt thự đẹp cần bán </span></a></h4>
                            <div class="listing-rating card-popup-rainingvis" data-starrating2="5"><span class="re_stars-title">Tuyệt vời</span></div>
                            <div class="clearfix"></div>
                            <p>" Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. "</p>
                            <div class="reviews-comments-item-date"><span class="reviews-comments-item-date-item"><i class="far fa-calendar-check"></i>06 Tháng 12 2018</span><a href="#" class="rate-review"><i class="fal fa-thumbs-up"></i>  Đánh giá hữu ích  <span>2</span> </a></div>
                        </div>
                    </div>
                    <!--reviews-comments-item end-->
                </div>
                <!-- pagination-->
                <div class="pagination float-pagination">
                    <a href="#" class="prevposts-link"><i class="fa fa-caret-left"></i></a>
                    <a href="#" >1</a>
                    <a href="#" class="current-page">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <a href="#" class="nextposts-link"><i class="fa fa-caret-right"></i></a>
                </div>
                <!-- pagination end-->
            </div>
        </div>
        <!-- dashboard-footer -->
        @include('components.dashboard.footer')
        <!-- dashboard-footer end -->
    </div>
    <!-- content end -->
    <div class="dashbard-bg gray-bg"></div>
</div>
