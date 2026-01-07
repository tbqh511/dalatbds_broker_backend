@extends('frontends.master')
@section('hide_newsletter')@endsection
@section('hide_footer')@endsection

@section('title', 'Đánh giá - Đà Lạt BDS')

@section('content')
    @include('frontends.components.dashboard_reviews')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
