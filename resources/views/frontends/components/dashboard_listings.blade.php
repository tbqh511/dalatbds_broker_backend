<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Tin đăng của bạn'])
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
            @include('components.dashboard.footer')
            <!-- dashboard-footer end -->
        </div>
    </div>
</div>
