@extends('frontends.master')

@section('title', 'Đăng tin mới - Đà Lạt BDS')

@section('content')
    @include('frontends.components.dashboard_add_listing')
@endsection

@push('styles')
    <style>
        .main-footer, .subscribe-wrap {
            display: none;
        }
    </style>
@endpush
