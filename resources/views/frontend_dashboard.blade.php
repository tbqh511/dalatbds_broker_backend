@extends('frontends.master')

@section('hide_newsletter')@endsection
@section('hide_footer')@endsection

@section('content')
    @include('frontends.components.dashboard_home')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/webapp.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
