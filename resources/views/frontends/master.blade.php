<!DOCTYPE HTML>
<html lang="en">

<head>
    <!-- basic   -->
    <meta charset="UTF-8">
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
            <!-- subscribe-wrap -->
            @include('frontends.newsletter')
            <!-- subscribe-wrap end -->
            <!-- footer -->
            @include('frontends.footer')
            <!-- footer end -->
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
    </div>
    <!-- Main end -->
    <!--=============== scripts  ===============-->
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/plugins.js')}}"></script>
    <script src="{{asset('js/scripts.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCxEHw0sJRjvQtF50i3y2vxFTr3qkx728k&libraries=places"></script>
    <script src="{{asset('js/map-single.js')}}"></script>

    <!-- Floating Chat Zalo -->
    <div class="float-contact">
        <div class="chat-zalo">
            <a href="https://zalo.me/09x.123.5678" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 48 48">
                    <circle cx="24" cy="24" r="24" fill="#0084ff" />
                    <path fill="#fff" d="M24 15c-5.2 0-9.4 3.5-9.4 7.9 0 2.5 1.5 4.7 3.7 6.1.2.1.3.2.3.4l-.3 2.6c-.1.4.4.7.7.4l3.1-2.6h.1c5.2 0 9.4-3.5 9.4-7.9S29.2 15 24 15zM19 25.5c-.8-.1-1.5-.4-2.2-.8-.3-.2-.4-.5-.3-.9s.6-.6 1-.4c.6.3 1.2.5 1.8.6.5 0 .9-.3 1-.7.1-.4-.3-.9-.7-1-.8-.1-1.6-.3-2.3-.7-1-.6-1.4-1.9-1-3.1.2-.6.6-1.1 1.2-1.4.5-.3 1.2-.4 1.8-.3.3 0 .6.3.6.7s-.3.6-.6.7c-.5-.1-1-.1-1.4.1-.4.2-.6.5-.7.8-.2.8.1 1.5.7 1.9.6.4 1.3.5 2 .6.4 0 .8.4.7.8-.1.4-.4.7-.8.7-.5.1-1 0-1.4-.1zm11.2-2.7c-.3.2-.7.2-1.1.1-.7-.2-1.4-.5-2-.8-.3-.1-.6-.2-.8-.3-.3-.2-.5-.5-.4-.8s.3-.6.6-.7c.3-.1.6 0 .8.2.2.2.5.4.8.5.5.2 1 .4 1.5.5.3 0 .6.1.7.4.3.5.1.9-.1 1zm2.3-2.4c-.3 0-.6-.2-.7-.5-.2-.4-.4-.7-.7-1-.2-.2-.1-.6.1-.8.3-.2.6-.2.8.1.3.3.5.6.7.9.2.3.2.6 0 .8-.2.3-.5.5-.9.5z" />
                </svg>
            </a>
        </div>
    </div>

<!-- Floating Chat Zalo End -->
</body>
</html>
