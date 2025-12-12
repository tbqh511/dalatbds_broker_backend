<!DOCTYPE HTML>
<html lang="en">

<head>
    <!-- basic   -->
    <meta charset="UTF-8">
    <meta name="zalo-platform-site-verification" content="E_E22Fdm52aStev_d_nVUsY8YNk4_budD3Cu" />
    <title>Đà Lạt BDS - Mạng lưới thổ địa Đà Lạt</title>
    <meta name="robots" content="index, follow" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <!-- css   -->
    <link type="text/css" rel="stylesheet" href="{{asset('css/plugins.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('css/style.css')}}">
    <link type="text/css" rel="stylesheet" href="{{asset('css/color.css')}}">
    <!--  favicons  -->
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}">
    <!--  push componet scripts  -->
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
            @hasSection('hide_newsletter')
            @else
            @include('frontends.newsletter')
            @endif
            @hasSection('hide_footer')
            @else
            @include('frontends.footer')
            @endif
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCxEHw0sJRjvQtF50i3y2vxFTr3qkx728k&libraries=places"></script>
    <script src="{{asset('js/map-single.js')}}"></script>
</body>
</html>
