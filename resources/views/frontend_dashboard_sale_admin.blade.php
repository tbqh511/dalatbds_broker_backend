@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection
@section('hide_secondary_nav')@endsection

@section('content')
    @include('frontends.components.dashboard_sale_admin')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
@endpush
@push('head_scripts')
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        if (window.Telegram && window.Telegram.WebApp) {
            const tg = window.Telegram.WebApp;
            tg.expand();
            try { tg.setHeaderColor('#3270FC'); tg.setBackgroundColor('#ffffff'); } catch (e) {}
        }
    </script>
@endpush
