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

    .hidden {
        display: none !important;
    }

    /* Skeleton Animation */
    @keyframes pulse {
        0% {
            background-color: #f0f0f0;
        }

        50% {
            background-color: #e0e0e0;
        }

        100% {
            background-color: #f0f0f0;
        }
    }

    .skeleton-card {
        background-color: #fff;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .skeleton-img {
        width: 100%;
        height: 200px;
        animation: pulse 1.5s infinite;
    }

    .skeleton-content {
        padding: 20px;
        border: 1px solid #eee;
        border-top: none;
        border-radius: 0 0 6px 6px;
    }

    .skeleton-title {
        width: 80%;
        height: 20px;
        animation: pulse 1.5s infinite;
        margin-bottom: 15px;
        border-radius: 4px;
    }

    .skeleton-line {
        width: 100%;
        height: 14px;
        animation: pulse 1.5s infinite;
        margin-bottom: 10px;
        border-radius: 4px;
    }

    .skeleton-line.short {
        width: 60%;
    }

    .skeleton-loader-container {
        display: flex;
        justify-content: center;
        padding: 20px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
{{-- TEMPORARY: Dev mode flag to allow running WebApp outside Telegram --}}
{{-- HuyTBQ: @if(Auth::guard('webapp')->check()) --}}
@php($webappDevMode = env('WEBAPP_DEV_MODE', false))
@if($webappDevMode || Auth::guard('webapp')->check())
<script>document.body.classList.add('webapp-mode');</script>
@include('frontends.components.dashboard_home')
@else
{{-- User is NOT authenticated, show loader and run JS --}}
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
    //         ram WebApp Logic (Run on every page        f (window.Tel        dow.Te            
    const tg = window.Tel                  tg.expand();
    try {
        tg.setHeaderColor('#32                tg.set            '#ffffff'); // Set background color to match
        } catc              nswag    pp setHeaderColor faile    
        }
    }
</script>
@if(!env('WEBAPP_DEV_MODE', false) && !Auth::guard('webapp')->check())
<script>
          ecific Logic
    async function ini                const tg = windo        ebApp;
    // ... (rest of logic)

    // Set a timeout to show err        g takes too long (e                    setTimeout(() => {
    const statusEl = doc            ById('webapp-status');
    if (                .innerText) {
        statusEl.innerText = "Đang kết nối máy chủ.            m                              15000);

        const initDa        Data;
        if                       console.warn("Non-Telegram environment det                  document.getElementById('webapp-status').innerText = "Vui lòng mở ứng dụng trong Telegr              return icate with Laravel Backend
            = await axios.post('/api/webapp/login', {

            });
        data;
        if (data.statu            ated') {
                // Success
                   ad to let S                e handle the rest
        window.location.reload                se if (data.status === 'gu                    document.getElementById('webap = "Bạn chưa có tài khoản.";
        tg.showPopup({
            khoản',
                          Vui lòng quay lại Bot chat v                    để tạo tài khoản.',
                    buttons: [{ type: 'close' }]
                                      } catc h(error) {
            r("A            or           document.getEle            -status').innerText = "Lỗi kết nối má             }
    }
    window.onload = function () {
        initWebApp();
    };
</script>
@endif
@endpush