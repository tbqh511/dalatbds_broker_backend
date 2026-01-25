@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection
@section('hide_secondary_nav')@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
    <style>
        /* Loading Overlay */
        #webapp-loading {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: #fff; z-index: 9999;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .hidden { display: none !important; }
    </style>
@endpush

@section('content')
    {{-- TEMPORARY: Dev mode flag to allow running WebApp outside Telegram --}}
    {{-- HuyTBQ: @if(Auth::guard('webapp')->check()) --}}
    @php($webappDevMode = env('WEBAPP_DEV_MODE', false))
    @if($webappDevMode || Auth::guard('webapp')->check())
        @include('frontends.components.dashboard_home')
    @else
        {{-- User is NOT authenticated, show loader and run JS --}}
        <div id="webapp-loading">
            <div class="loader-inner">
                <div class="loader-line-wrap"><div class="loader-line"></div></div>
                <div class="loader-line-wrap"><div class="loader-line"></div></div>
                <div class="loader-line-wrap"><div class="loader-line"></div></div>
                <div class="loader-line-wrap"><div class="loader-line"></div></div>
                <div class="loader-line-wrap"><div class="loader-line"></div></div>
            </div>
            <h4 style="margin-top: 20px;">Đang tải dữ liệu...</h4>
            <div id="webapp-status" style="margin-top: 10px; color: red;"></div>
        </div>
    @endif
@endsection

@push('head_scripts')
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('js/charts.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        // Global Telegram WebApp Logic (Run on every page load)
        if (window.Telegram && window.Telegram.WebApp) {
            const tg = window.Telegram.WebApp;
            tg.expand();
            try {
                tg.setHeaderColor('#3270FC');
                tg.setBackgroundColor('#ffffff'); // Set background color to match
            } catch (e) {
                console.warn('Telegram WebApp setHeaderColor failed:', e);
            }
        }
    </script>
    @if(!env('WEBAPP_DEV_MODE', false) && !Auth::guard('webapp')->check())
    <script>
        // Auth-specific Logic
        async function initWebApp() {
            const tg = window.Telegram.WebApp;
            // ... (rest of logic)

            // Set a timeout to show error if loading takes too long (e.g. 15 seconds)
            setTimeout(() => {
                const statusEl = document.getElementById('webapp-status');
                if (statusEl && !statusEl.innerText) {
                     statusEl.innerText = "Đang kết nối máy chủ... (Vui lòng kiểm tra mạng)";
                }
            }, 15000);

            const initData = tg.initData;
            if (!initData) {
                console.warn("Non-Telegram environment detected.");
                document.getElementById('webapp-status').innerText = "Vui lòng mở ứng dụng trong Telegram.";
                return;
            }
            try {
                // Authenticate with Laravel Backend
                const response = await axios.post('/api/webapp/login', {
                    initData: initData
                });
                const data = response.data;
                if (data.status === 'authenticated') {
                    // Success
                    // Reload to let Server-side Middleware handle the rest
                    window.location.reload();
                } else if (data.status === 'guest') {
                    document.getElementById('webapp-status').innerText = "Bạn chưa có tài khoản.";
                    tg.showPopup({
                        title: 'Chưa có tài khoản',
                        message: 'Vui lòng quay lại Bot chat và chia sẻ số điện thoại để tạo tài khoản.',
                        buttons: [{type: 'close'}]
                    });
                }
            } catch (error) {
                console.error("Auth Error:", error);
                document.getElementById('webapp-status').innerText = "Lỗi kết nối máy chủ.";
            }
        }
        window.onload = function() {
            initWebApp();
        };
    </script>
    @endif
@endpush
