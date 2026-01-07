@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection

@section('title', 'Đăng tin mới - Đà Lạt BDS')

@section('content')
    @include('frontends.components.dashboard_add_listing')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
