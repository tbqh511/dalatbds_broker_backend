<div class="dashbard-menu-overlay"></div>
@include('components.dashboard.sidebar')

<!-- dashboard content -->
<div class="dashboard-content">
    @include('components.dashboard.mobile_btn')
    <div class="container dasboard-container">
        <!-- dashboard-title -->
        @include('components.dashboard.header', ['title' => 'Tổng quan'])
        <!-- dashboard-title end -->

        <div class="dasboard-wrapper fl-wrap no-pag">
            <div class="dashboard-stats-container fl-wrap">
                <div class="row">
                    <!--dashboard-stats-->
                    <div class="col-md-3">
                        <div class="dashboard-stats fl-wrap">
                            <i class="fal fa-map-marked"></i>
                            <h4>Tin đang hiển thị</h4>
                            <div class="dashboard-stats-count">{{ $stats['properties_count'] ?? 0 }}</div>
                        </div>
                    </div>
                    <!-- dashboard-stats end -->
                    <!--dashboard-stats-->
                    <div class="col-md-3">
                        <div class="dashboard-stats fl-wrap">
                            <i class="fal fa-chart-bar"></i>
                            <h4>Lượt xem tin</h4>
                            <div class="dashboard-stats-count">{{ $stats['views_count'] ?? 0 }}<span>(<strong>+{{ $stats['views_count_week'] ?? 0 }}</strong> trong tuần)</span></div>
                        </div>
                    </div>
                    <!-- dashboard-stats end -->
                    <!--dashboard-stats-->
                    <div class="col-md-3">
                        <div class="dashboard-stats fl-wrap">
                            <i class="fal fa-comments-alt"></i>
                            <h4>Đánh giá của bạn</h4>
                            <div class="dashboard-stats-count">{{ $stats['reviews_count'] ?? 0 }}<span>(<strong>+{{ $stats['reviews_count_week'] ?? 0 }}</strong> trong tuần)</span></div>
                        </div>
                    </div>
                    <!-- dashboard-stats end -->
                    <!--dashboard-stats-->
                    <div class="col-md-3">
                        <div class="dashboard-stats fl-wrap">
                            <i class="fal fa-heart"></i>
                            <h4>Lượt quan tâm</h4>
                            <div class="dashboard-stats-count">{{ $stats['favourites_count'] ?? 0 }}<span>(<strong>+{{ $stats['favourites_count_week'] ?? 0 }}</strong> trong tuần)</span></div>
                        </div>
                    </div>
                    <!-- dashboard-stats end -->
                </div>
            </div>
            <div class="clearfix"></div>
            {{-- <div class="row">
                <div class="col-md-8">
                    <div class="notification success-notif  fl-wrap">
                        <p>Tin đăng <a href="#">Nhà phố tại Phường 1</a> của bạn đã được duyệt!</p>
                        <a class="notification-close" href="#"><i class="fal fa-times"></i></a>
                    </div>
                    <div class="dashboard-widget-title fl-wrap">Thống kê của bạn</div>
                    <div class="dasboard-content fl-wrap">
                        <div class="chart-wrap fl-wrap">
                            <div class="chart-header fl-wrap">
                                <div class="listsearch-input-item">
                                    <select data-placeholder="Tuần" class="chosen-select no-search-select">
                                        <option>Tuần</option>
                                        <option>Tháng</option>
                                        <option>Năm</option>
                                    </select>
                                </div>
                                <div id="myChartLegend"></div>
                            </div>
                            <canvas id="canvas-chart"></canvas>
                        </div>
                    </div>
                    <div class="dashboard-widget-title fl-wrap">Hoạt động gần đây</div>
                    <div class="dashboard-list-box fl-wrap">
                        <div class="dashboard-list fl-wrap">
                            <div class="dashboard-message">
                                <span class="close-dashboard-item color-bg"><i class="fal fa-times"></i></span>
                                <div class="main-dashboard-message-icon color-bg"><i class="far fa-check"></i></div>
                                <div class="main-dashboard-message-text">
                                    <p>Tin đăng <a href="#">Căn hộ cao cấp</a> của bạn đã được duyệt! </p>
                                </div>
                                <div class="main-dashboard-message-time"><i class="fal fa-calendar-week"></i> 28 Th5 2020</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dasboard-widget fl-wrap">
                        <div class="dasboard-widget-title fl-wrap">
                            <h5><i class="fas fa-comment-alt"></i>Tin nhắn mới nhất</h5>
                        </div>
                        <div class="chat-contacts fl-wrap">
                            <a class="chat-contacts-item" href="#">
                                <div class="dashboard-message-avatar">
                                    <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                    <div class="message-counter">2</div>
                                </div>
                                <div class="chat-contacts-item-text">
                                    <h4>Minh Tuấn</h4>
                                    <span>27 Th12 2018 </span>
                                    <p>Chào bạn, tôi quan tâm đến căn nhà bạn đăng bán...</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="box-widget fl-wrap">
                        <div class="banner-widget fl-wrap">
                            <div class="bg-wrap bg-parallax-wrap-gradien">
                                <div class="bg" data-bg="{{ asset('images/bg/1.jpg') }}"></div>
                            </div>
                            <div class="banner-widget_content">
                                <h5>Tham gia chương trình khách hàng thân thiết. Giới thiệu bạn bè và nhận ưu đãi.</h5>
                                <a href="#" class="btn float-btn color-bg small-btn">Xem thêm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <!-- dashboard-footer -->
        @include('components.dashboard.footer')
    <!-- dashboard-footer end -->
</div>
<div class="dashbard-bg gray-bg"></div>