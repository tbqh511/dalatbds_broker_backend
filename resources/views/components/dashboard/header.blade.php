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
        <!-- Shutdown button: always shown. Attempts Telegram.WebApp.close(), then window.close(), then fallback redirect -->
        <a href="javascript:void(0)" onclick="event.preventDefault(); (window.handleShutdownClick ? window.handleShutdownClick(event) : null);" class="log-out-btn tolt" data-microtip-position="bottom" data-tooltip="Tắt ứng dụng"><i class="far fa-power-off"></i></a>

        <script src="https://telegram.org/js/telegram-web-app.js"></script>

        <script>
            (function () {
                window.handleShutdownClick = function (e) {
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

                    try {
                        // Try to close the browser window (may be blocked by browsers)
                        window.close();
                        // Some browsers won't close; give a short delay then fallback
                        setTimeout(function () {
                            // Fallback: redirect to home. Adjust if you have a dedicated shutdown route.
                            window.location.href = "{{ url('/') }}";
                        }, 250);
                    } catch (closeErr) {
                        console.error('window.close() failed:', closeErr);
                        window.location.href = "{{ url('/') }}";
                    }
                };
            })();
        </script>
    </div>
    <!--Tariff Plan menu-->
    <!-- <div class="tfp-det-container">
        <div class="tfp-btn"><span>Gói dịch vụ : </span> <strong>Cao cấp</strong></div>
        <div class="tfp-det">
            <p>Bạn đang sử dụng gói <a href="#">Cao cấp</a> . Nhấn vào link bên dưới để xem chi tiết hoặc nâng cấp. </p>
            <a href="#" class="tfp-det-btn color-bg">Chi tiết</a>
        </div>
    </div> -->
    <!--Tariff Plan menu end-->
</div>
