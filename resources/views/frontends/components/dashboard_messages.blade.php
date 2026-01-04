<div class="content">
    <div class="dashbard-menu-overlay"></div>
    <div class="dashbard-menu-wrap">
        <div class="dashbard-menu-close"><i class="fal fa-times"></i></div>
        <div class="dashbard-menu-container">
            <!-- user-profile-menu-->
            <div class="user-profile-menu">
                <h3>Menu Chính</h3>
                <ul class="no-list-style">
                    <li><a href="{{ route('webapp') }}"><i class="fal fa-chart-line"></i>Tổng quan</a></li>
                    <li><a href="{{ route('webapp.profile') }}"><i class="fal fa-user-edit"></i> Chỉnh sửa hồ sơ</a></li>
                    <li><a href="{{ route('webapp.messages') }}" class="user-profile-act"><i class="fal fa-envelope"></i> Tin nhắn <span>3</span></a></li>
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
                <div class="dashboard-title-item"><span>Tin nhắn</span></div>
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
                <!-- dashboard-list-box-->
                <div class="dashboard-list-box fl-wrap">
                    <div class="dasboard-widget-title fl-wrap">
                        <h5><i class="fas fa-comment-alt"></i>Tin nhắn gần đây<span> ( +3 Mới ) </span></h5>
                        <a href="#" class="mark-btn tolt" data-microtip-position="bottom" data-tooltip="Đánh dấu tất cả là đã đọc"><i class="far fa-comment-alt-check"></i> </a>
                    </div>
                    <div class="chat-wrapper fl-wrap">
                        <!-- chat-box-->
                        <div class="chat-box fl-wrap">
                            <div class="chat-box-scroll fl-wrap full-height" data-simplebar="init">
                                <!-- message-->
                                <div class="chat-message fl-wrap">
                                    <div class="dashboard-message-avatar">
                                        <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                        <span class="chat-message-user-name cmun_sm">Andy</span>
                                    </div>
                                    <span class="massage-date">25 th5 2018 <span>7.51 CH</span></span>
                                    <p>Vivamus lobortis vel nibh nec maximus. Donec dolor erat, rutrum ut feugiat sed, ornare vitae nunc. Donec massa nisl, bibendum id ultrices sed, accumsan sed dolor.</p>
                                </div>
                                <!-- message end-->
                                <!-- message-->
                                <div class="chat-message chat-message_user fl-wrap">
                                    <div class="dashboard-message-avatar">
                                        <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                        <span class="chat-message-user-name cmun_sm">Bạn</span>
                                    </div>
                                    <span class="massage-date">25 th5 2018 <span>7.51 CH</span></span>
                                    <p>Nulla eget erat consequat quam feugiat dapibus eget sed mauris.</p>
                                </div>
                                <!-- message end-->
                                <!-- message-->
                                <div class="chat-message fl-wrap">
                                    <div class="dashboard-message-avatar">
                                        <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                        <span class="chat-message-user-name cmun_sm">Andy</span>
                                    </div>
                                    <span class="massage-date">25 th5 2018 <span>7.51 CH</span></span>
                                    <p>Sed non neque faucibus, condimentum lectus at, accumsan enim. Fusce pretium egestas cursus..</p>
                                </div>
                                <!-- message end-->
                                <!-- message-->
                                <div class="chat-message chat-message_user fl-wrap">
                                    <div class="dashboard-message-avatar">
                                        <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                        <span class="chat-message-user-name cmun_sm">Bạn</span>
                                    </div>
                                    <span class="massage-date">25 th5 2018 <span>7.51 CH</span></span>
                                    <p>Donec a consectetur nulla. Nulla posuere sapien vitae lectus suscipit, et pulvinar nisi tincidunt. Aliquam erat volutpat. Curabitur convallis fringilla diam sed aliquam. Sed tempor iaculis massa faucibus feugiat. In fermentum facilisis massa, a consequat .</p>
                                </div>
                                <!-- message end-->
                            </div>
                        </div>
                        <div class="chat-widget_input">
                            <textarea placeholder="Nhập tin nhắn..."></textarea>
                            <button type="submit" class="color-bg"><i class="fal fa-paper-plane"></i></button>
                        </div>
                        <!-- chat-box end-->
                        <!-- chat-contacts-->
                        <div class="chat-contacts">
                            <!-- chat-contacts-item-->
                            <a class="chat-contacts-item" href="#">
                                <div class="dashboard-message-avatar">
                                    <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                    <div class="message-counter">2</div>
                                </div>
                                <div class="chat-contacts-item-text">
                                    <h4>Mark Rose</h4>
                                    <span>27 Th12 2018 </span>
                                    <p>Vivamus lobortis vel nibh nec maximus. Donec dolor erat, rutrum ut feugiat sed, ornare vitae nunc. Donec massa nisl, bibendum id ultrices sed, accumsan sed dolor.</p>
                                </div>
                            </a>
                            <!-- chat-contacts-item -->
                            <!-- chat-contacts-item-->
                            <a class="chat-contacts-item chat-contacts-item_active" href="#">
                                <div class="dashboard-message-avatar">
                                    <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                </div>
                                <div class="chat-contacts-item-text">
                                    <h4>Adam Koncy</h4>
                                    <span>27 Th12 2018 </span>
                                    <p>Vivamus lobortis vel nibh nec maximus. Donec dolor erat, rutrum ut feugiat sed, ornare vitae nunc. Donec massa nisl, bibendum id ultrices sed, accumsan sed dolor.</p>
                                </div>
                            </a>
                            <!-- chat-contacts-item -->
                            <!-- chat-contacts-item-->
                            <a class="chat-contacts-item" href="#">
                                <div class="dashboard-message-avatar">
                                    <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                    <div class="message-counter">3</div>
                                </div>
                                <div class="chat-contacts-item-text">
                                    <h4>Andy Smith</h4>
                                    <span>27 Th12 2018 </span>
                                    <p>Vivamus lobortis vel nibh nec maximus. Donec dolor erat, rutrum ut feugiat sed, ornare vitae nunc. Donec massa nisl, bibendum id ultrices sed, accumsan sed dolor.</p>
                                </div>
                            </a>
                            <!-- chat-contacts-item -->
                            <!-- chat-contacts-item-->
                            <a class="chat-contacts-item" href="#">
                                <div class="dashboard-message-avatar">
                                    <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                    <div class="message-counter">4</div>
                                </div>
                                <div class="chat-contacts-item-text">
                                    <h4>Joe Frick</h4>
                                    <span>27 Th12 2018 </span>
                                    <p>Vivamus lobortis vel nibh nec maximus. Donec dolor erat, rutrum ut feugiat sed, ornare vitae nunc. Donec massa nisl, bibendum id ultrices sed, accumsan sed dolor.</p>
                                </div>
                            </a>
                            <!-- chat-contacts-item -->
                        </div>
                        <!-- chat-contacts end-->
                    </div>
                    <!-- dashboard-list-box end-->
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
                <a href="#main" class="dashbord-totop custom-scroll-link"><i class="fas fa-caret-up"></i></a>
            </div>
            <!-- dashboard-footer end -->
        </div>
    </div>
</div>
