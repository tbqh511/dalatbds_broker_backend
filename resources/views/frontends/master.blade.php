<!DOCTYPE HTML>
<html lang="en">

<head>
    <!-- basic   -->
    <meta charset="UTF-8">
    <title>@yield('title', 'Đà Lạt BDS - Mạng lưới thổ địa Đà Lạt')</title>
    <meta name="robots" content="index, follow" />
    <meta name="keywords" content="@yield('meta_keywords', 'bất động sản, đà lạt, mua bán nhà đất, thổ địa')" />
    <meta name="description" content="@yield('meta_description', 'Tìm kiếm và đầu tư bất động sản tại Đà Lạt. Mạng lưới thổ địa uy tín, thông tin chính xác.')" />

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Social Media Meta -->
    @yield('social_meta')

    <!-- css   -->
    <link type="text/css" rel="stylesheet" href="{{asset('css/plugins.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('css/style.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('css/color.css')}}">
    @stack('styles')
    <!--  favicons  -->
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">

    @stack('head_scripts')
</head>

<body>
    <!--loader-->
    @include('frontends.loader_wrap')
    <!--loader end-->
    <!-- main -->
    <div id="main">
        <!-- header -->
        @include('frontends.header')
        <!-- header end  -->
        <!-- wrapper  -->
        <div id="wrapper">
            <!-- content -->
            @yield('content')
            <!-- content end -->
            @unless(View::hasSection('hide_newsletter'))
                @include('frontends.newsletter')
            @endunless

            @unless(View::hasSection('hide_footer'))
                @include('frontends.footer')
            @endunless
        </div>
        <!-- wrapper end -->
        <!--register form -->
        @include('frontends.register_form')
        <!--register form end -->
        <!--secondary-nav -->
        @include('frontends.secondary_nav')
        <!--secondary-nav end -->
        <a class="to-top color-bg"><i class="fas fa-caret-up"></i></a>
        <!--map-modal -->
        @include('frontends.map_model')
        <!--map-modal end -->
        <!-- Floating Chat Zalo -->
        <div class="chat-zalo">
            <a href="https://zalo.me/0918963878" target="_blank">
                <img title="Chat Zalo" src="{{ asset('images/zalo-icon.png') }}" alt="zalo-icon" width="40" height="40" />
            </a>
        </div>
        <!-- Floating Chat Zalo End -->
        <!-- Floating Call Button -->
        <div class="call-button">
            <a href="tel:0918963878" target="_self" title="0918963878">
                <img title="Gọi ngay" src="{{ asset('images/call-icon.png') }}" alt="call-icon" width="40" height="40" />
            </a>
        </div>
        <!-- Floating Call Button End -->
    </div>
    <!-- Main end -->
    <!--=============== scripts  ===============-->
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/plugins.js')}}"></script>
    <script src="{{asset('js/scripts.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.place_api_key') }}&libraries=places&loading=async"></script>
    <script src="{{asset('js/map-single.js')}}"></script>
    @stack('scripts')
</body>
</html>
