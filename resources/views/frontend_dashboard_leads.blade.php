@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection
@section('hide_secondary_nav')@endsection

@section('content')
    @include('frontends.components.dashboard_leads')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
    <style>
        .leads-fullwidth-container {
            max-width: 100% !important;
            width: 100% !important;
            padding: 0 !important;
        }
        @media (max-width: 800px) {
            .leads-fullwidth-container .dasboard-wrapper {
                margin-left: -16px;
                margin-right: -16px;
                width: calc(100% + 32px);
            }
        }
    </style>
@endpush
@push('head_scripts')
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
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
