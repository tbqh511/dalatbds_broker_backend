<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Tin nhắn'])
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
            @include('components.dashboard.footer')
            <!-- dashboard-footer end -->
        </div>
    </div>
</div>
