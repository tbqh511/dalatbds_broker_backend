@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
    <style>
        /* Hide Web elements for Telegram Mini App */
        .main-header, .footer-inner, .to-top, .chat-zalo, .call-button {
            display: none !important;
        }
        #main {
            padding-top: 0 !important;
        }
        .dashboard-content {
            margin-top: 0 !important;
            padding-top: 20px;
        }
        
        /* Loading Overlay */
        #webapp-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .hidden { display: none !important; }
    </style>
@endpush

@section('content')
    @include('frontends.components.dashboard_home')
    {{-- <!-- Telegram Loading Overlay -->
    <div id="webapp-loading">
        <div class="loader-inner">
            <div class="loader-line-wrap">
                <div class="loader-line"></div>
            </div>
            <div class="loader-line-wrap">
                <div class="loader-line"></div>
            </div>
            <div class="loader-line-wrap">
                <div class="loader-line"></div>
            </div>
            <div class="loader-line-wrap">
                <div class="loader-line"></div>
            </div>
            <div class="loader-line-wrap">
                <div class="loader-line"></div>
            </div>
        </div>
        <h4 style="margin-top: 20px;">Đang tải dữ liệu...</h4>
        <div id="webapp-status" style="margin-top: 10px; color: red;"></div>
    </div>

    <!-- Main Content (Initially Hidden) -->
    <div id="webapp-main" class="hidden">
        @include('frontends.components.dashboard_home')
    </div> --}}
@endsection

@push('head_scripts')
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('js/charts.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    
    <script>
        // Telegram WebApp Logic
        const tg = window.Telegram.WebApp;
        tg.expand();
        
        // Define Main Color based on theme
        tg.setHeaderColor('#3270FC'); 

        async function initWebApp() {
            const initData = tg.initData;

            if (!initData) {
                // Not in Telegram
                console.warn("Non-Telegram environment detected.");
                // For dev/testing, you might want to bypass or show a warning
                // document.getElementById('webapp-loading').classList.add('hidden');
                // document.getElementById('webapp-main').classList.remove('hidden');
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
                    console.log("Authenticated as:", data.user);
                    localStorage.setItem('auth_token', data.access_token);
                    
                    // Update UI elements if needed (e.g., user name)
                    // const userNameElements = document.querySelectorAll('.user-name-display');
                    // userNameElements.forEach(el => el.innerText = data.user.name);

                    // Hide Loading, Show Content
                    document.getElementById('webapp-loading').classList.add('hidden');
                    document.getElementById('webapp-main').classList.remove('hidden');

                } else if (data.status === 'guest') {
                    // Guest Mode
                    document.getElementById('webapp-status').innerText = "Bạn chưa có tài khoản. Vui lòng chia sẻ số điện thoại với Bot.";
                    tg.showPopup({
                        title: 'Chưa có tài khoản',
                        message: 'Vui lòng quay lại Bot chat và chia sẻ số điện thoại để tạo tài khoản.',
                        buttons: [{type: 'close'}]
                    });
                }

            } catch (error) {
                console.error("Auth Error:", error);
                document.getElementById('webapp-status').innerText = "Lỗi kết nối máy chủ.";
                if(error.response && error.response.status === 403) {
                     document.getElementById('webapp-status').innerText = "Lỗi xác thực: Chữ ký không hợp lệ.";
                }
            }
        }

        // Run on load
        window.onload = function() {
            initWebApp();
        };
    </script>
@endpush
