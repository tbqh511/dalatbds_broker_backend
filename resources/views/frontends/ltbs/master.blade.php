<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Đà Lạt BĐS — Mạng lưới thổ địa Đà Lạt')</title>
    <meta name="robots" content="@yield('robots', 'index, follow')" />
    <meta name="keywords" content="@yield('meta_keywords', 'bất động sản, đà lạt, mua bán nhà đất, thổ địa')" />
    <meta name="description"
        content="@yield('meta_description', 'Tìm kiếm và đầu tư bất động sản tại Đà Lạt. Mạng lưới thổ địa uy tín, thông tin chính xác.')" />
    <link rel="canonical" href="{{ url()->current() }}" />
    @yield('social_meta')

    <link type="text/css" rel="stylesheet" href="{{ asset('css/ltbs-core.css') }}">
    @stack('styles')

    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    @stack('head_scripts')
</head>

<body class="ltbs">
    <div class="page">
        @include('frontends.ltbs.header')

        @yield('content')

        @unless(View::hasSection('hide_footer'))
            @include('frontends.ltbs.footer')
        @endunless
    </div>

    <div class="ltbs-toast-wrap" id="ltbsToastWrap"></div>

    <script src="{{ asset('js/ltbs-core.js') }}"></script>
    @stack('scripts')
</body>

</html>
