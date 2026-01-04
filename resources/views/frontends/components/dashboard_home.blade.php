<div class="content">
    <div class="dashbard-menu-overlay"></div>
    <div class="dashbard-menu-wrap">
        <div class="dashbard-menu-close"><i class="fal fa-times"></i></div>
        <div class="dashbard-menu-container">
            <!-- user-profile-menu-->
            <div class="user-profile-menu">
                <h3>Menu Chính</h3>
                <ul class="no-list-style">
                    <li><a href="#" class="user-profile-act"><i class="fal fa-chart-line"></i>Tổng quan</a></li>
                    <li><a href="#"><i class="fal fa-user-edit"></i> Chỉnh sửa hồ sơ</a></li>
                    <li><a href="#"><i class="fal fa-envelope"></i> Tin nhắn <span>3</span></a></li>
                    <li><a href="#"><i class="fal fa-users"></i> Danh sách môi giới</a></li>
                </ul>
            </div>
            <!-- user-profile-menu end-->
            <!-- user-profile-menu-->
            <div class="user-profile-menu">
                <h3>Quản lý tin</h3>
                <ul class="no-list-style">
                    <li><a href="#"><i class="fal fa-th-list"></i> Tin đăng của tôi</a></li>
                    <li><a href="#"> <i class="fal fa-calendar-check"></i> Lịch hẹn <span>2</span></a></li>
                    <li><a href="#"><i class="fal fa-comments-alt"></i> Đánh giá </a></li>
                    <li><a href="#"><i class="fal fa-file-plus"></i> Đăng tin mới</a></li>
                </ul>
            </div>
            <!-- user-profile-menu end-->
        </div>
        <div class="dashbard-menu-footer">© Đà Lạt BDS 2022 . Đã đăng ký bản quyền.</div>
    </div>

    <!-- dashboard content -->
    <div class="dashboard-content">
        <div class="dashboard-menu-btn color-bg"><span><i class="fas fa-bars"></i></span>Menu quản lý</div>
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            <div class="dashboard-title fl-wrap">
                <div class="dashboard-title-item"><span>Tổng quan</span></div>
                <div class="dashbard-menu-header">
                    <div class="dashbard-menu-avatar fl-wrap">
                        <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                        <h4>Xin chào, <span>{{ auth()->check() ? auth()->user()->name : 'User' }}</span></h4>
                    </div>
                    <a href="{{ url('/') }}" class="log-out-btn tolt" data-microtip-position="bottom" data-tooltip="Đăng xuất"><i class="far fa-power-off"></i></a>
                </div>
                <!--Tariff Plan menu-->
                <div class="tfp-det-container">
                    <div class="tfp-btn"><span>Gói dịch vụ : </span> <strong>Cao cấp</strong></div>
                    <div class="tfp-det">
                        <p>Bạn đang sử dụng gói <a href="#">Cao cấp</a> . Nhấn vào link bên dưới để xem chi tiết hoặc nâng cấp. </p>
                        <a href="#" class="tfp-det-btn color-bg">Chi tiết</a>
                    </div>
                </div>
                <!--Tariff Plan menu end-->
            </div>
            <!-- dashboard-title end -->

            <div class="dasboard-wrapper fl-wrap no-pag">
                <div class="dashboard-stats-container fl-wrap">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="dashboard-stats fl-wrap">
                                <i class="fal fa-map-marked"></i>
                                <h4>Tin đang hiển thị</h4>
                                <div class="dashboard-stats-count">124</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-stats fl-wrap">
                                <i class="fal fa-chart-bar"></i>
                                <h4>Lượt xem tin</h4>
                                <div class="dashboard-stats-count">1056<span>(<strong>+356</strong> tuần này)</span></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-stats fl-wrap">
                                <i class="fal fa-comments-alt"></i>
                                <h4>Đánh giá của bạn</h4>
                                <div class="dashboard-stats-count">357<span>(<strong>+12</strong> tuần này)</span></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-stats fl-wrap">
                                <i class="fal fa-heart"></i>
                                <h4>Lượt quan tâm</h4>
                                <div class="dashboard-stats-count">2329<span>(<strong>+234</strong> tuần này)</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
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
                </div>
            </div>

            <!-- dashboard-footer -->
            <div class="dashboard-footer">
                <div class="dashboard-footer-links fl-wrap">
                    <span>Liên kết hữu ích:</span>
                    <ul>
                        <li><a href="#">Giới thiệu</a></li>
                        <li><a href="#">Tin tức</a></li>
                        <li><a href="#">Bảng giá</a></li>
                        <li><a href="#">Liên hệ</a></li>
                        <li><a href="#">Trung tâm trợ giúp</a></li>
                    </ul>
                </div>
                <a href="#main" class="dashbord-totop  custom-scroll-link"><i class="fas fa-caret-up"></i></a>
            </div>
            <!-- dashboard-footer end -->
        </div>
    </div>
</div>
