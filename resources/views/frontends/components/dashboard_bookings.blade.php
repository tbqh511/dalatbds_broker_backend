<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Lịch hẹn'])
            <!-- dashboard-title end -->
            
            <div class="dasboard-wrapper fl-wrap">
                <div class="dasboard-widget-title fl-wrap">
                    <h5><i class="fal fa-comments-alt"></i>Lịch hẹn gần đây <span> ( +3 Mới ) </span></h5>
                    <a href="#" class="mark-btn tolt" data-microtip-position="bottom" data-tooltip="Đánh dấu tất cả là đã đọc"><i class="far fa-comment-alt-check"></i> </a>
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
                                </select>
                            </div>
                        </div>
                        <!-- price-opt end-->
                    </div>
                    <div class="row">
                        <!-- bookings-item -->
                        <div class="col-md-6">
                            <div class="bookings-item fl-wrap">
                                <div class="bookings-item-header fl-wrap">
                                    <img src="{{ asset('images/all/1.jpg') }}" alt="">
                                    <h4>Tin: <a href="#" target="_blank">Bán nhà đẹp tại Đà Lạt</a></h4>
                                    <span class="new-bookmark">Mới</span>
                                </div>
                                <div class="bookings-item-content fl-wrap">
                                    <ul>
                                        <li>Tên: <span>Nguyễn Văn A</span></li>
                                        <li>Điện thoại: <span>0901234567</span></li>
                                        <li>Ngày: <span>18.05.2021</span></li>
                                        <li>Giờ: <span>12 AM</span></li>
                                    </ul>
                                    <p>Tôi muốn xem nhà vào cuối tuần này. Vui lòng liên hệ lại với tôi.</p>
                                </div>
                                <div class="bookings-item-footer fl-wrap">
                                    <span class="message-date">12 Tháng 12 2020</span>
                                    <ul>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Gửi tin nhắn"><i class="far fa-envelope"></i></a></li>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Gọi điện"><i class="far fa-phone"></i></a></li>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Xóa"><i class="far fa-trash"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--bookings-item end-->
                        <!-- bookings-item -->
                        <div class="col-md-6">
                            <div class="bookings-item fl-wrap">
                                <div class="bookings-item-header fl-wrap">
                                    <img src="{{ asset('images/all/1.jpg') }}" alt="">
                                    <h4>Tin: <a href="#" target="_blank">Biệt thự nghỉ dưỡng cao cấp</a></h4>
                                    <span class="new-bookmark">Mới</span>
                                </div>
                                <div class="bookings-item-content fl-wrap">
                                    <ul>
                                        <li>Tên: <span>Trần Thị B</span></li>
                                        <li>Điện thoại: <span>0909876543</span></li>
                                        <li>Ngày: <span>28.05.2020</span></li>
                                        <li>Giờ: <span>10 AM</span></li>
                                    </ul>
                                    <p>Căn này có sổ đỏ chưa ạ? Tôi muốn hẹn xem giấy tờ.</p>
                                </div>
                                <div class="bookings-item-footer fl-wrap">
                                    <span class="message-date">05 Tháng 10 2020</span>
                                    <ul>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Gửi tin nhắn"><i class="far fa-envelope"></i></a></li>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Gọi điện"><i class="far fa-phone"></i></a></li>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Xóa"><i class="far fa-trash"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--bookings-item end-->
                        <!-- bookings-item -->
                        <div class="col-md-6">
                            <div class="bookings-item fl-wrap">
                                <div class="bookings-item-header fl-wrap">
                                    <img src="{{ asset('images/all/1.jpg') }}" alt="">
                                    <h4>Tin: <a href="#" target="_blank">Nhà phố cho thuê</a></h4>
                                </div>
                                <div class="bookings-item-content fl-wrap">
                                    <ul>
                                        <li>Tên: <span>Lê Văn C</span></li>
                                        <li>Điện thoại: <span>0912345678</span></li>
                                        <li>Ngày: <span>14.05.2020</span></li>
                                        <li>Giờ: <span>5 PM</span></li>
                                    </ul>
                                    <p>Tôi muốn thuê dài hạn, giá có thương lượng được không?</p>
                                </div>
                                <div class="bookings-item-footer fl-wrap">
                                    <span class="message-date">25 Tháng 5 2020</span>
                                    <ul>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Gửi tin nhắn"><i class="far fa-envelope"></i></a></li>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Gọi điện"><i class="far fa-phone"></i></a></li>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Xóa"><i class="far fa-trash"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--bookings-item end-->
                        <!-- bookings-item -->
                        <div class="col-md-6">
                            <div class="bookings-item fl-wrap">
                                <div class="bookings-item-header fl-wrap">
                                    <img src="{{ asset('images/all/1.jpg') }}" alt="">
                                    <h4>Tin: <a href="#" target="_blank">Căn hộ hiện đại</a></h4>
                                    <span class="new-bookmark">Mới</span>
                                </div>
                                <div class="bookings-item-content fl-wrap">
                                    <ul>
                                        <li>Tên: <span>Phạm Thị D</span></li>
                                        <li>Điện thoại: <span>0987654321</span></li>
                                        <li>Ngày: <span>28.05.2020</span></li>
                                        <li>Giờ: <span>10 AM</span></li>
                                    </ul>
                                    <p>Vị trí này có gần chợ không ạ?</p>
                                </div>
                                <div class="bookings-item-footer fl-wrap">
                                    <span class="message-date">12 Tháng 12 2020</span>
                                    <ul>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Gửi tin nhắn"><i class="far fa-envelope"></i></a></li>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Gọi điện"><i class="far fa-phone"></i></a></li>
                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Xóa"><i class="far fa-trash"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--bookings-item end-->
                    </div>
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
