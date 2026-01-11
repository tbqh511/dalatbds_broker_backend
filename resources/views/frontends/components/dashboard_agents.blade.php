<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Danh sách môi giới'])
            <!-- dashboard-title end -->
            
            <div class="dasboard-wrapper fl-wrap">
                <div class="dasboard-listing-box fl-wrap">
                    <div class="dasboard-opt sl-opt fl-wrap">
                        <div class="dashboard-search-listing">
                            <input type="text" onclick="this.select()" placeholder="Tìm kiếm..." value="">
                            <button type="submit"><i class="far fa-search"></i></button>
                        </div>
                        <a href="#" class="gradient-bg dashboard-addnew_btn show-popup-form">Thêm mới <i class="fal fa-plus"></i></a>
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
                        <div class="popup-form">
                            <div class="custom-form">
                                <label>Tên <span class="dec-icon"><i class="fas fa-user"></i></span></label>
                                <input type="text" placeholder="Nguyễn Văn A" value=""/>
                                <label>Địa chỉ Email <span class="dec-icon"><i class="far fa-envelope"></i></span></label>
                                <input type="text" placeholder="nguyenvana@domain.com" value=""/>
                                <label>Link Môi giới<span class="dec-icon"><i class="fal fa-link"></i></span></label>
                                <input type="text" placeholder="dalatbds.com/agent-nguyenvana/" value=""/>
                                <button type="submit" class="btn float-btn color-bg fw-btn"> Gửi</button>
                            </div>
                        </div>
                    </div>
                    <!-- dashboard-listings-wrap-->
                    <div class="dashboard-listings-wrap fl-wrap">
                        <div class="row">
                            <!-- dashboard-listings-item-->
                            <div class="col-md-4">
                                <!--  agent card item -->
                                <div class="listing-item">
                                    <article class="geodir-category-listing fl-wrap">
                                        <div class="geodir-category-img fl-wrap agent_card">
                                            <a href="#" class="geodir-category-img_item">
                                            <img src="{{ asset('images/agency/agent/1.jpg') }}" alt="">
                                            </a>
                                            <div class="listing-rating card-popup-rainingvis" data-starrating2="5"><span class="re_stars-title">Tuyệt vời</span></div>
                                        </div>
                                        <div class="geodir-category-content fl-wrap">
                                            <div class="card-verified tolt" data-microtip-position="left" data-tooltip="Đã xác thực"><i class="fal fa-user-check"></i></div>
                                            <div class="agent_card-title fl-wrap">
                                                <h4><a href="#" >Liza Rose</a></h4>
                                                <h5><a href="#">Bất động sản Đà Lạt</a></h5>
                                            </div>
                                            <div class="agent-card-facts fl-wrap">
                                                <ul>
                                                    <li>Tin đăng<span>24</span></li>
                                                    <li>Đánh giá<span>18</span></li>
                                                    <li>Lịch hẹn<span>124</span></li>
                                                </ul>
                                            </div>
                                            <div class="geodir-category-footer fl-wrap">
                                                <a href="#" class="btn float-btn color-bg small-btn">Xem hồ sơ</a>
                                                <a href="#" class="tolt ftr-btn" data-microtip-position="left" data-tooltip="Xóa"><i class="fal fa-trash"></i></a>
                                                <a href="#" class="tolt ftr-btn" data-microtip-position="left" data-tooltip="Xác thực"><i class="fal fa-user-check"></i></a>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <!--  agent card item end -->
                            </div>
                            <!-- dashboard-listings-item end-->
                            <!-- dashboard-listings-item-->
                            <div class="col-md-4">
                                <!--  agent card item -->
                                <div class="listing-item">
                                    <article class="geodir-category-listing fl-wrap">
                                        <div class="geodir-category-img fl-wrap agent_card">
                                            <a href="#" class="geodir-category-img_item">
                                            <img src="{{ asset('images/agency/agent/1.jpg') }}" alt="">
                                            </a>
                                            <div class="listing-rating card-popup-rainingvis" data-starrating2="3"><span class="re_stars-title">Trung bình</span></div>
                                        </div>
                                        <div class="geodir-category-content fl-wrap">
                                            <div class="card-verified cv_not tolt" data-microtip-position="left" data-tooltip="Chưa xác thực"><i class="fal fa-minus-octagon"></i></div>
                                            <div class="agent_card-title fl-wrap">
                                                <h4><a href="#" >Jane Kobart</a></h4>
                                                <h5><a href="#">Bất động sản Đà Lạt</a></h5>
                                            </div>
                                            <div class="agent-card-facts fl-wrap">
                                                <ul>
                                                    <li>Tin đăng<span>14</span></li>
                                                    <li>Đánh giá<span>28</span></li>
                                                    <li>Lịch hẹn<span>321</span></li>
                                                </ul>
                                            </div>
                                            <div class="geodir-category-footer fl-wrap">
                                                <a href="#" class="btn float-btn color-bg small-btn">Xem hồ sơ</a>
                                                <a href="#" class="tolt ftr-btn" data-microtip-position="left" data-tooltip="Xóa"><i class="fal fa-trash"></i></a>
                                                <a href="#" class="tolt ftr-btn" data-microtip-position="left" data-tooltip="Xác thực"><i class="fal fa-user-check"></i></a>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <!--  agent card item end -->
                            </div>
                            <!-- dashboard-listings-item end-->
                            <!-- dashboard-listings-item-->
                            <div class="col-md-4">
                                <!--  agent card item -->
                                <div class="listing-item">
                                    <article class="geodir-category-listing fl-wrap">
                                        <div class="geodir-category-img fl-wrap agent_card">
                                            <a href="#" class="geodir-category-img_item">
                                            <img src="{{ asset('images/agency/agent/1.jpg') }}" alt="">
                                            </a>
                                            <div class="listing-rating card-popup-rainingvis" data-starrating2="5"><span class="re_stars-title">Tuyệt vời</span></div>
                                        </div>
                                        <div class="geodir-category-content fl-wrap">
                                            <div class="card-verified tolt" data-microtip-position="left" data-tooltip="Đã xác thực"><i class="fal fa-user-check"></i></div>
                                            <div class="agent_card-title fl-wrap">
                                                <h4><a href="#" >Bill Trust</a></h4>
                                                <h5><a href="#">Bất động sản Đà Lạt</a></h5>
                                            </div>
                                            <div class="agent-card-facts fl-wrap">
                                                <ul>
                                                    <li>Tin đăng<span>12</span></li>
                                                    <li>Đánh giá<span>38</span></li>
                                                    <li>Lịch hẹn<span>68</span></li>
                                                </ul>
                                            </div>
                                            <div class="geodir-category-footer fl-wrap">
                                                <a href="#" class="btn float-btn color-bg small-btn">Xem hồ sơ</a>
                                                <a href="#" class="tolt ftr-btn" data-microtip-position="left" data-tooltip="Xóa"><i class="fal fa-trash"></i></a>
                                                <a href="#" class="tolt ftr-btn" data-microtip-position="left" data-tooltip="Xác thực"><i class="fal fa-user-check"></i></a>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <!--  agent card item end -->
                            </div>
                            <!-- dashboard-listings-item end-->
                            <!-- dashboard-listings-item-->
                            <div class="col-md-4">
                                <!--  agent card item -->
                                <div class="listing-item">
                                    <article class="geodir-category-listing fl-wrap">
                                        <div class="geodir-category-img fl-wrap agent_card">
                                            <a href="#" class="geodir-category-img_item">
                                            <img src="{{ asset('images/agency/agent/1.jpg') }}" alt="">
                                            </a>
                                            <div class="listing-rating card-popup-rainingvis" data-starrating2="4"><span class="re_stars-title">Tốt</span></div>
                                        </div>
                                        <div class="geodir-category-content fl-wrap">
                                            <div class="card-verified cv_not tolt" data-microtip-position="left" data-tooltip="Chưa xác thực"><i class="fal fa-minus-octagon"></i></div>
                                            <div class="agent_card-title fl-wrap">
                                                <h4><a href="#" >Andy Sposty</a></h4>
                                                <h5><a href="#">Bất động sản Đà Lạt</a></h5>
                                            </div>
                                            <div class="agent-card-facts fl-wrap">
                                                <ul>
                                                    <li>Tin đăng<span>10</span></li>
                                                    <li>Đánh giá<span>44</span></li>
                                                    <li>Lịch hẹn<span>98</span></li>
                                                </ul>
                                            </div>
                                            <div class="geodir-category-footer fl-wrap">
                                                <a href="#" class="btn float-btn color-bg small-btn">Xem hồ sơ</a>
                                                <a href="#" class="tolt ftr-btn" data-microtip-position="left" data-tooltip="Xóa"><i class="fal fa-trash"></i></a>
                                                <a href="#" class="tolt ftr-btn" data-microtip-position="left" data-tooltip="Xác thực"><i class="fal fa-user-check"></i></a>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                <!--  agent card item end -->
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
    <div class="dashbard-bg gray-bg"></div>
</div>
