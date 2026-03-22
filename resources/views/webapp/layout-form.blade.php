<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Đà Lạt BĐS — WebApp')</title>
<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
<link rel="stylesheet" href="{{ asset('css/webapp-v2.css') }}?v={{ filemtime(public_path('css/webapp-v2.css')) }}">
@stack('styles')
@stack('head_scripts')
</head>
<body>
@yield('content')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.place_api_key') }}&libraries=places,marker"></script>
@stack('scripts')
</body>
</html>
