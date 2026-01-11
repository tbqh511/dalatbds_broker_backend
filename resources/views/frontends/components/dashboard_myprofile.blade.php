<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Chỉnh sửa hồ sơ'])
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
            @include('components.dashboard.footer')
            <!-- dashboard-footer end -->
        </div>
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>
