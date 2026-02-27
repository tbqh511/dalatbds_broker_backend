@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection
@section('hide_secondary_nav')@endsection

@section('content')
@include('frontends.components.dashboard_feed')
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
<link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
<style>
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
@push('head_scripts')
<script src="https://telegram.org/js/telegram-web-app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endpush
@push('scripts')
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
@endpush