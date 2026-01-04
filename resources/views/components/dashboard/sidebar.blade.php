<div class="dashbard-menu-wrap">
    <div class="dashbard-menu-close"><i class="fal fa-times"></i></div>
    <div class="dashbard-menu-container">
        <!-- user-profile-menu-->
        <div class="user-profile-menu">
            <h3>Menu Chính</h3>
            <ul class="no-list-style">
                <li><a href="{{ route('webapp') }}" class="{{ request()->routeIs('webapp') ? 'user-profile-act' : '' }}"><i class="fal fa-chart-line"></i>Tổng quan</a></li>
                <li><a href="{{ route('webapp.profile') }}" class="{{ request()->routeIs('webapp.profile') ? 'user-profile-act' : '' }}"><i class="fal fa-user-edit"></i> Chỉnh sửa hồ sơ</a></li>
                <li><a href="{{ route('webapp.messages') }}" class="{{ request()->routeIs('webapp.messages') ? 'user-profile-act' : '' }}"><i class="fal fa-envelope"></i> Tin nhắn <span>3</span></a></li>
                <li><a href="{{ route('webapp.agents') }}" class="{{ request()->routeIs('webapp.agents') ? 'user-profile-act' : '' }}"><i class="fal fa-users"></i> Danh sách môi giới</a></li>
            </ul>
        </div>
        <!-- user-profile-menu end-->
        <!-- user-profile-menu-->
        <div class="user-profile-menu">
            <h3>Quản lý tin</h3>
            <ul class="no-list-style">
                <li><a href="{{ route('webapp.listings') }}" class="{{ request()->routeIs('webapp.listings') ? 'user-profile-act' : '' }}"><i class="fal fa-th-list"></i> Tin đăng của tôi</a></li>
                <li><a href="{{ route('webapp.bookings') }}" class="{{ request()->routeIs('webapp.bookings') ? 'user-profile-act' : '' }}"> <i class="fal fa-calendar-check"></i> Lịch hẹn <span>2</span></a></li>
                <li><a href="{{ route('webapp.reviews') }}" class="{{ request()->routeIs('webapp.reviews') ? 'user-profile-act' : '' }}"><i class="fal fa-comments-alt"></i> Đánh giá </a></li>
                <li><a href="{{ route('webapp.add_listing') }}" class="{{ request()->routeIs('webapp.add_listing') ? 'user-profile-act' : '' }}"><i class="fal fa-file-plus"></i> Đăng tin mới</a></li>
            </ul>
        </div>
        <!-- user-profile-menu end-->
    </div>
    <div class="dashbard-menu-footer">© Đà Lạt BDS 2022 . Đã đăng ký bản quyền.</div>
</div>