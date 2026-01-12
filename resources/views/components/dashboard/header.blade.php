<div class="dashboard-title fl-wrap">
    <div class="dashboard-title-item"><span>{{ $title }}</span></div>
    <div class="dashbard-menu-header">
        @php
            $currentUser = null;
            $userAvatar = asset('images/avatar/1.jpg');
            $userName = 'Khách';

            if (Auth::guard('webapp')->check()) {
                $currentUser = Auth::guard('webapp')->user();
                $userName = $currentUser->name;
                // Customer model has getProfileAttribute which returns full URL or default
                $userAvatar = $currentUser->profile; 
            } elseif (auth()->check()) {
                $currentUser = auth()->user();
                $userName = $currentUser->name;
                $userAvatar = asset('images/avatar/'.($currentUser->avatar ?? '1.jpg'));
            }
        @endphp
        <div class="dashbard-menu-avatar fl-wrap">
            <img src="{{ $userAvatar }}" alt="{{ $userName }}" style="object-fit: cover;">
            <h4>Xin chào, <span>{{ $userName }}</span></h4>
        </div>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); (window.handleLogoutClick ? window.handleLogoutClick(event) : document.getElementById('logout-form').submit());" class="log-out-btn tolt" data-microtip-position="bottom" data-tooltip="Đăng xuất"><i class="far fa-power-off"></i></a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <script>
            (function () {
                window.handleLogoutClick = function (e) {
                    try {
                        if (window && window.Telegram && window.Telegram.WebApp && typeof window.Telegram.WebApp.close === 'function') {
                            try {
                                window.Telegram.WebApp.close();
                                return;
                            } catch (innerErr) {
                                console.error('Telegram.WebApp.close() failed:', innerErr);
                            }
                        }
                    } catch (err) {
                        console.error('Telegram WebApp detection error:', err);
                    }

                    var form = document.getElementById('logout-form');
                    if (form) {
                        try {
                            form.submit();
                        } catch (submitErr) {
                            console.error('Logout form submit failed:', submitErr);
                        }
                    }
                };
            })();
        </script>
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
