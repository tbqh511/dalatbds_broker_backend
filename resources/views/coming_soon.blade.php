<!DOCTYPE HTML>
<html lang="en">

<head>
    <!--=============== basic  ===============-->
    <meta charset="UTF-8">
    <title>Homeradar - Real Estate Listing Template</title>
    <meta name="robots" content="index, follow" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <!--=============== css  ===============-->
    <link type="text/css" rel="stylesheet" href="css/reset.css">
    <link type="text/css" rel="stylesheet" href="css/plugins.css">
    <link type="text/css" rel="stylesheet" href="css/cs-style.css">
    <!--=============== favicons ===============-->
    <link rel="shortcut icon" href="images/favicon.ico">
</head>

<body>
    <!--loader-->
    @include('frontends.loader_wrap')
    <!--loader end-->
    <!-- main start  -->
    <div id="main">
        <div class="wrapper">
            <!--  logo  -->
            <div class="logo-holder"><a href="#"><img src="images/logo1.svg" alt="dalat-bds"></a></div>
            <!-- logo end  -->
            <div class="cs-content-wrapper">
                <h3>Đang Trong Quá Trình Xây Dựng                </h3>
                <h2>Trang web của chúng tôi sắp ra mắt</h2>
                <div class="cs-subcribe-form subcribe-form fl-wrap">
                    <p>Đăng ký ngay bây giờ để nhận thông tin qua bản tin của chúng tôi và bạn sẽ là người đầu tiên biết khi trang web mới của chúng tôi đã sẵn sàng
                    </p>
                    <form id="subscribe" class="fl-wrap" onsubmit="return redirectToHome(event)">
                        @csrf
                        <input class="enteremail" name="email" id="subscribe-email" placeholder="Your Email"
                            spellcheck="false" type="text">
                        <button type="submit" id="subscribe-button" class="subscribe-button color-bg">Send </button>
                        <label for="subscribe-email" class="subscribe-message"></label>
                    </form>
                </div>
                <div class="clearfix"></div>
                <div class="cs-social fl-wrap">
                    <ul>
                        <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-vk"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="pwh_bg">
                <div class="mrb_pin"></div>
                <div class="mrb_pin mrb_pin2"></div>
            </div>
        </div>
        <div class="cs-media">
            <div class="bg-wrap">
                <div class="slideshow-container">
                    <!-- slideshow-item -->
                    <div class="slideshow-item">
                        <div class="bg" data-bg="images/bg/3.jpg"></div>
                    </div>
                    <!--  slideshow-item end  -->
                    <!-- slideshow-item -->
                    <div class="slideshow-item">
                        <div class="bg" data-bg="images/bg/1.jpg"></div>
                    </div>
                    <!--  slideshow-item end  -->
                    <!-- slideshow-item -->
                    <div class="slideshow-item">
                        <div class="bg" data-bg="images/bg/2.jpg"></div>
                    </div>
                    <!--  slideshow-item end  -->
                </div>
                <div class="overlay"></div>
            </div>
            <div class="slider-progress-bar">
                <span>
                    <svg class="circ" width="30" height="30">
                        <circle class="circ2" cx="15" cy="15" r="13" stroke="rgba(255,255,255,0.4)" stroke-width="1"
                            fill="none" />
                        <circle class="circ1" cx="15" cy="15" r="13" stroke="#fff" stroke-width="2" fill="none" />
                    </svg>
                </span>
            </div>
            <!-- cs-media-container -->
            <div class="cs-media-container counter-widget fl-wrap" data-countDate="09/12/2021">
                <!-- countdown -->
                <div class="countdown">
                    <div class="cs-countdown-item">
                        <span class="days rot">60</span>
                        <p>Days</p>
                    </div>
                    <div class="cs-countdown-item">
                        <span class="hours rot">20</span>
                        <p>Hours </p>
                    </div>
                    <div class="cs-countdown-item">
                        <span class="minutes rot2">00</span>
                        <p>Minutes </p>
                    </div>
                    <div class="cs-countdown-item no-dec">
                        <span class="seconds rot2">00</span>
                        <p>Seconds</p>
                    </div>
                </div>
                <!-- countdown end -->
            </div>
            <!--cs-media-container end -->
            <!-- cs-contacts -->
            <div class="cs-contacts">
                <ul>
                    <li><span>Call :</span><a href="#">0918963878</a></li>
                    <li><span>Write :</span><a href="#">tbqh0511@gmail.com</a></li>
                    <li><span>Find us : </span><a href="#">27/6 Yersin, phường 10, TP Đà Lạt, Tỉnh Lâm Đồng</a></li>
                </ul>
            </div>
            <!-- cs-contacts end -->
            {{-- <div class="cf_btn">Get In Touch</div> --}}
        </div>
        <!--contact-form-wrap -->
        <div class="contact-form-wrap">
            <div class="contact-form-container">
                <div class="contact-form-main fl-wrap">
                    <div class="contact-form-header">
                        <h4>Get In touch</h4>
                        <span class="close-contact-form"><i class="fal fa-times"></i></span>
                    </div>
                    <div id="contact-form" class="contact-form fl-wrap">
                        <div id="message"></div>
                        <form class="custom-form" action="php/contact.php" name="contactform" id="contactform">
                            <fieldset>
                                <label>Your name* <span class="dec-icon"><i class="fas fa-user"></i></span></label>
                                <input type="text" name="name" id="name" placeholder="Your Name *" value="" />
                                <label>Your mail* <span class="dec-icon"><i class="fas fa-envelope"></i></span></label>
                                <input type="text" name="email" id="email" placeholder="Email Address*" value="" />
                                <textarea name="comments" id="comments" cols="40" rows="3"
                                    placeholder="Your Message:"></textarea>
                            </fieldset>
                            <button class="btn" style="margin-top:15px;" id="submit">Send Message</button>
                        </form>
                    </div>
                    <!-- contact form  end-->
                </div>
            </div>
            <div class="contact-form-overlay"></div>
        </div>
        <!--contact-form-wrap end-->
    </div>
    <!-- Main end -->
    <!--=============== scripts  ===============-->
    <script src="js/jquery.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/cs.js"></script>
    <!-- HuyTBQ: add role for coming_soon page -->
    <script>
        function redirectToHome(event) {
          event.preventDefault(); // Ngăn chặn hành vi mặc định của form

          var emailInput = document.getElementById('subscribe-email');

          if (emailInput.value.trim() !== '') {
                    window.location.href = '/home'; // Chuyển hướng đến trang chủ
          }
          return false;
        }
    </script>
</body>
</html>
