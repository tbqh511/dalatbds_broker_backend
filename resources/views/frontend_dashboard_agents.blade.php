@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection
@section('hide_secondary_nav')@endsection

@section('content')
    @include('frontends.components.dashboard_agents')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
@endpush
@push('head_scripts')
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
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
