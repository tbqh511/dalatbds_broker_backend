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
                    <li><a href="{{ route('webapp.messages') }}"><i class="fal fa-envelope"></i> Tin nhắn <span>3</span></a></li>
                    <li><a href="#"><i class="fal fa-users"></i> Danh sách môi giới</a></li>
                </ul>
            </div>
            <!-- user-profile-menu end-->
            <!-- user-profile-menu-->
            <div class="user-profile-menu">
                <h3>Quản lý tin</h3>
                <ul class="no-list-style">
                    <li><a href="{{ route('webapp.listings') }}" class="user-profile-act"><i class="fal fa-th-list"></i> Tin đăng của tôi</a></li>
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
                <div class="dashboard-title-item"><span>Tin đăng của bạn</span></div>
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
            
            <div class="dasboard-wrapper fl-wrap">
                <div class="dasboard-listing-box fl-wrap">
                    <div class="dasboard-opt sl-opt fl-wrap">
                        <div class="dashboard-search-listing">
                            <input type="text" onclick="this.select()" placeholder="Tìm kiếm..." value="">
                            <button type="submit"><i class="far fa-search"></i></button>
                        </div>
                        <a href="#" class="gradient-bg dashboard-addnew_btn">Đăng tin mới <i class="fal fa-plus"></i></a>
                        <!-- price-opt-->
                        <div class="price-opt">
                            <span class="price-opt-title">Sắp xếp theo:</span>
                            <div class="listsearch-input-item">
                                <select data-placeholder="Mới nhất" class="chosen-select no-search-select" >
                                    <option>Mới nhất</option>
                                    <option>Cũ nhất</option>
                                    <option>Đánh giá trung bình</option>
                                    <option>Tên: A-Z</option>
                                    <option>Tên: Z-A</option>
                                </select>
                            </div>
                        </div>
                        <!-- price-opt end-->
                    </div>
                    <!-- dashboard-listings-wrap-->
                    <div class="dashboard-listings-wrap fl-wrap">
                        <div class="row">
                            <!-- dashboard-listings-item-->
                            <div class="col-md-6">
                                <div class="dashboard-listings-item fl-wrap">
                                    <div class="dashboard-listings-item_img">
                                        <div class="bg-wrap">
                                            <div class="bg" data-bg="{{ asset('images/all/1.jpg') }}"></div>
                                        </div>
                                        <div class="overlay"></div>
                                        <a href="#" class="color-bg">Xem</a>
                                    </div>
                                    <div class="dashboard-listings-item_content">
                                        <h4><a href="#">Bán nhà đẹp tại Đà Lạt</a></h4>
                                        <div class="geodir-category-location">
                                            <a href="#"><i class="fas fa-map-marker-alt"></i> <span> 70 Đường Phù Đổng Thiên Vương, Đà Lạt</span></a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="right" data-tooltip="Tốt" data-starrating2="4"></div>
                                        <div class="dashboard-listings-item_opt">
                                            <span class="viewed-counter"><i class="fas fa-eye"></i> Lượt xem - 645 </span>
                                            <ul>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Chỉnh sửa"><i class="far fa-edit"></i></a></li>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Ẩn/Hiện"><i class="far fa-signal-alt-slash"></i></a></li>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Xóa"><i class="far fa-trash-alt"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- dashboard-listings-item end-->
                            <!-- dashboard-listings-item-->
                            <div class="col-md-6">
                                <div class="dashboard-listings-item fl-wrap">
                                    <div class="dashboard-listings-item_img">
                                        <div class="bg-wrap">
                                            <div class="bg" data-bg="{{ asset('images/all/1.jpg') }}"></div>
                                        </div>
                                        <div class="overlay"></div>
                                        <a href="#" class="color-bg">Xem</a>
                                    </div>
                                    <div class="dashboard-listings-item_content">
                                        <h4><a href="#">Biệt thự nghỉ dưỡng cao cấp</a></h4>
                                        <div class="geodir-category-location">
                                            <a href="#"><i class="fas fa-map-marker-alt"></i> <span> 40 Đường Hùng Vương, Đà Lạt</span></a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="right" data-tooltip="Tuyệt vời" data-starrating2="5"></div>
                                        <div class="dashboard-listings-item_opt">
                                            <span class="viewed-counter"><i class="fas fa-eye"></i> Lượt xem - 247 </span>
                                            <ul>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Chỉnh sửa"><i class="far fa-edit"></i></a></li>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Ẩn/Hiện"><i class="far fa-signal-alt-slash"></i></a></li>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Xóa"><i class="far fa-trash-alt"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- dashboard-listings-item end-->
                            <!-- dashboard-listings-item-->
                            <div class="col-md-6">
                                <div class="dashboard-listings-item fl-wrap">
                                    <div class="dashboard-listings-item_img">
                                        <div class="bg-wrap">
                                            <div class="bg" data-bg="{{ asset('images/all/1.jpg') }}"></div>
                                        </div>
                                        <div class="overlay"></div>
                                        <a href="#" class="color-bg">Xem</a>
                                    </div>
                                    <div class="dashboard-listings-item_content">
                                        <h4><a href="#">Nhà phố cho thuê</a></h4>
                                        <div class="geodir-category-location">
                                            <a href="#"><i class="fas fa-map-marker-alt"></i> <span> 34-42 Đường Hai Bà Trưng, Đà Lạt</span></a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="right" data-tooltip="Tốt" data-starrating2="4"></div>
                                        <div class="dashboard-listings-item_opt">
                                            <span class="viewed-counter"><i class="fas fa-eye"></i> Lượt xem - 24 </span>
                                            <ul>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Chỉnh sửa"><i class="far fa-edit"></i></a></li>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Ẩn/Hiện"><i class="far fa-signal-alt-slash"></i></a></li>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Xóa"><i class="far fa-trash-alt"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- dashboard-listings-item end-->
                            <!-- dashboard-listings-item-->
                            <div class="col-md-6">
                                <div class="dashboard-listings-item fl-wrap">
                                    <div class="dashboard-listings-item_img">
                                        <div class="bg-wrap">
                                            <div class="bg" data-bg="{{ asset('images/all/1.jpg') }}"></div>
                                        </div>
                                        <div class="overlay"></div>
                                        <a href="#" class="color-bg">Xem</a>
                                    </div>
                                    <div class="dashboard-listings-item_content">
                                        <h4><a href="#">Căn hộ hiện đại</a></h4>
                                        <div class="geodir-category-location">
                                            <a href="#"><i class="fas fa-map-marker-alt"></i> <span> Đường Yersin, Đà Lạt</span></a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="right" data-tooltip="Tuyệt vời" data-starrating2="5"></div>
                                        <div class="dashboard-listings-item_opt">
                                            <span class="viewed-counter"><i class="fas fa-eye"></i> Lượt xem - 921 </span>
                                            <ul>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Chỉnh sửa"><i class="far fa-edit"></i></a></li>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Ẩn/Hiện"><i class="far fa-signal-alt-slash"></i></a></li>
                                                <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Xóa"><i class="far fa-trash-alt"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- dashboard-listings-item end-->
                        </div>
                    </div>
                    <!-- dashboard-listings-wrap end-->
                </div>
                <!-- pagination-->
                <div class="pagination float-pagination">
                    <a href="#" class="prevposts-link"><i class="fa fa-caret-left"></i></a>
                    <a href="#">1</a>
                    <a href="#" class="current-page">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <a href="#" class="nextposts-link"><i class="fa fa-caret-right"></i></a>
                </div>
                <!-- pagination end-->
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
