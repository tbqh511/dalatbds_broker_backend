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
                    <li><a href="{{ route('webapp.profile') }}" class="user-profile-act"><i class="fal fa-user-edit"></i> Chỉnh sửa hồ sơ</a></li>
                    <li><a href="{{ route('webapp.messages') }}"><i class="fal fa-envelope"></i> Tin nhắn <span>3</span></a></li>
                    <li><a href="#"><i class="fal fa-users"></i> Danh sách môi giới</a></li>
                </ul>
            </div>
            <!-- user-profile-menu end-->
            <!-- user-profile-menu-->
            <div class="user-profile-menu">
                <h3>Quản lý tin</h3>
                <ul class="no-list-style">
                    <li><a href="{{ route('webapp.listings') }}"><i class="fal fa-th-list"></i> Tin đăng của tôi</a></li>
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
                <div class="dashboard-title-item"><span>Chỉnh sửa hồ sơ</span></div>
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
            <!-- dasboard-wrapper-->
            <div class="dasboard-wrapper fl-wrap no-pag">
                <div class="row">
                    <div class="col-md-7">
                        <div class="dasboard-widget-title fl-wrap">
                            <h5><i class="fas fa-user-circle"></i>Thay đổi ảnh đại diện</h5>
                        </div>
                        <div class="dasboard-widget-box nopad-dash-widget-box fl-wrap">
                            <div class="edit-profile-photo">
                                <img src="{{ asset('images/avatar/1.jpg') }}" class="respimg" alt="">
                                <div class="change-photo-btn">
                                    <div class="photoUpload">
                                        <span>Tải ảnh mới</span>
                                        <input type="file" class="upload">
                                    </div>
                                </div>
                            </div>
                            <div class="bg-wrap bg-parallax-wrap-gradien">
                                <div class="bg" data-bg="{{ asset('images/bg/1.jpg') }}"></div>
                            </div>
                            <div class="change-photo-btn cpb-2  ">
                                <div class="photoUpload color-bg">
                                    <span> <i class="far fa-camera"></i> Thay đổi ảnh bìa </span>
                                    <input type="file" class="upload">
                                </div>
                            </div>
                        </div>
                        <div class="dasboard-widget-title fl-wrap">
                            <h5><i class="fas fa-key"></i>Thông tin cá nhân</h5>
                        </div>
                        <div class="dasboard-widget-box fl-wrap">
                            <form method="post" class="custom-form">
                                @csrf
                                <label>Họ và tên <span class="dec-icon"><i class="far fa-user"></i></span></label>
                                <input type="text" placeholder="Nhập họ tên" value="{{ auth()->check() ? auth()->user()->name : '' }}"/>
                                
                                <label>Địa chỉ Email <span class="dec-icon"><i class="far fa-envelope"></i></span></label>
                                <input type="text" placeholder="example@domain.com" value="{{ auth()->check() ? auth()->user()->email : '' }}"/>
                                
                                <label>Số điện thoại<span class="dec-icon"><i class="far fa-phone"></i> </span></label>
                                <input type="text" placeholder="+84..." value=""/>
                                
                                <label>Địa chỉ <span class="dec-icon"><i class="fas fa-map-marker"></i> </span></label>
                                <input type="text" placeholder="Đà Lạt, Lâm Đồng" value=""/>
                                
                                <label>Website <span class="dec-icon"><i class="far fa-globe"></i> </span></label>
                                <input type="text" placeholder="dalatbds.com" value=""/>
                                
                                <label>Đại lý / Công ty<span class="dec-icon"><i class="far fa-home-lg-alt"></i> </span></label>
                                <input type="text" placeholder="Tên công ty" value=""/>
                                
                                <label>Ghi chú / Giới thiệu </label>
                                <textarea cols="40" rows="3" placeholder="Giới thiệu về bản thân" style="margin-bottom:20px;"></textarea>
                                
                                <button class="btn color-bg float-btn">Lưu thay đổi</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="dasboard-widget-title dbt-mm fl-wrap">
                            <h5><i class="fas fa-key"></i>Đổi mật khẩu</h5>
                        </div>
                        <div class="dasboard-widget-box fl-wrap">
                            <div class="custom-form">
                                <div class="pass-input-wrap fl-wrap">
                                    <label>Mật khẩu hiện tại<span class="dec-icon"><i class="far fa-lock-open-alt"></i></span></label>
                                    <input type="password" class="pass-input" placeholder="" value=""/>
                                    <span class="eye"><i class="far fa-eye" aria-hidden="true"></i> </span>
                                </div>
                                <div class="pass-input-wrap fl-wrap">
                                    <label>Mật khẩu mới<span class="dec-icon"><i class="far fa-lock-alt"></i></span></label>
                                    <input type="password" class="pass-input" placeholder="" value=""/>
                                    <span class="eye"><i class="far fa-eye" aria-hidden="true"></i> </span>
                                </div>
                                <div class="pass-input-wrap fl-wrap">
                                    <label>Xác nhận mật khẩu mới<span class="dec-icon"><i class="far fa-shield-check"></i> </span></label>
                                    <input type="password" class="pass-input" placeholder="" value=""/>
                                    <span class="eye"><i class="far fa-eye" aria-hidden="true"></i> </span>
                                </div>
                                <button class="btn color-bg float-btn">Lưu thay đổi</button>
                            </div>
                        </div>
                        <div class="dasboard-widget-title fl-wrap" style="margin-top: 30px;">
                            <h5><i class="fas fa-share-alt"></i>Mạng xã hội</h5>
                        </div>
                        <div class="dasboard-widget-box fl-wrap">
                            <div class="custom-form">
                                <label>Facebook <span class="dec-icon"><i class="fab fa-facebook"></i></span></label>
                                <input type="text" placeholder="https://www.facebook.com/" value=""/>
                                <label>Twitter <span class="dec-icon"><i class="fab fa-twitter"></i></span></label>
                                <input type="text" placeholder="https://twitter.com/" value=""/>
                                <label>Instagram<span class="dec-icon"><i class="fab fa-instagram"></i> </span></label>
                                <input type="text" placeholder="https://www.instagram.com/" value=""/>
                                <label>Zalo<span class="dec-icon"><i class="fas fa-comment-dots"></i> </span></label>
                                <input type="text" placeholder="https://zalo.me/..." value=""/>
                                <button class="btn color-bg float-btn">Lưu thay đổi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- dasboard-wrapper end -->
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
