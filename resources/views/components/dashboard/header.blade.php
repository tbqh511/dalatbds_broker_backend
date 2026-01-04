<div class="dashboard-title fl-wrap">
    <div class="dashboard-title-item"><span>{{ $title }}</span></div>
    <div class="dashbard-menu-header">
        <div class="dashbard-menu-avatar fl-wrap">
            <img src="{{ auth()->check() ? asset('images/avatar/'.(auth()->user()->avatar ?? '1.jpg')) : asset('images/avatar/1.jpg') }}" alt="">
            <h4>Xin chào, <span>{{ auth()->check() ? auth()->user()->name : 'Khách' }}</span></h4>
        </div>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="log-out-btn tolt" data-microtip-position="bottom" data-tooltip="Đăng xuất"><i class="far fa-power-off"></i></a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
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