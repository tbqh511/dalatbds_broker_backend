@extends('frontends.master')

@section('content')
<!-- content -->	
<div class="content">
    <!-- breadcrumbs-->
    <div class="breadcrumbs fw-breadcrumbs sp-brd fl-wrap   top-smpar  ">
        <div class="container">
            <div class="breadcrumbs-list">
                <a href="#">Home</a><a href="#">Agency</a> <span>Agent Single</span>
            </div>
            <div class="share-holder hid-share">
                <a href="#" class="share-btn showshare sfcs">  <i class="fas fa-share-alt"></i>  Share   </a>
                <div class="share-container  isShare"></div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs end -->
    <!-- col-list-wrap -->
    <section class="gray-bg small-padding ">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card-info smpar fl-wrap">
                        <div class="box-widget-menu-btn smact"><i class="far fa-ellipsis-h"></i></div>
                        <div class="show-more-snopt-tooltip bxwt">
                            <a href="#"> <i class="fas fa-comment-alt"></i> Write a review</a>
                            <a href="#"> <i class="fas fa-exclamation-triangle"></i> Report </a>
                        </div>
                        <div class="bg-wrap bg-parallax-wrap-gradien">
                            <div class="bg"  data-bg="images/bg/1.jpg"></div>
                        </div>
                        <div class="card-info-media">
                            <div class="bg"  data-bg="images/agency/agent/1.jpg"></div>
                        </div>
                        <div class="card-info-content">
                            <div class="agent_card-title fl-wrap">
                                <h4> Andy Sposty </h4>
                                <div class="geodir-category-location fl-wrap">
                                    <h5><a href="agency-single.html">Mavers RealEstate Agency</a></h5>
                                    <div class="listing-rating card-popup-rainingvis" data-starrating2="4"><span class="re_stars-title">Good</span></div>
                                </div>
                            </div>
                            <div class="list-single-stats">
                                <ul class="no-list-style">
                                    <li><span class="viewed-counter"><i class="fas fa-eye"></i> Viewed -  156 </span></li>
                                    <li><span class="bookmark-counter"><i class="fas fa-comment-alt"></i> Reviews -  4 </span></li>
                                    <li><span class="bookmark-counter"><i class="fas fa-sitemap"></i> Listings -  6 </span></li>
                                </ul>
                            </div>
                            <div class="card-verified tolt" data-microtip-position="left" data-tooltip="Verified"><i class="fal fa-user-check"></i></div>
                        </div>
                    </div>
                    <div class="list-single-main-container fl-wrap">
                        <!-- list-single-main-item -->
                        <div class="list-single-main-item fl-wrap">
                            <div class="list-single-main-item-title">
                                <h3>About This Agent</h3>
                            </div>
                            <div class="list-single-main-item_content fl-wrap">
                                <p>Vivamus vel lacus lacinia, condimentum nunc non, iaculis diam. Proin in mollis augue, eget fermentum quam. Donec semper purus ut ante tempus gravida. Quisque et ante orci. Sed venenatis turpis mi, non varius justo scelerisque id. Maecenas fermentum nunc sed maximus efficitur. Pellentesque nec turpis viverra, cursus ex porta, porttitor odio. In gravida ante nec eleifend commodo. Cras gravida interdum nisl, vel venenatis nisl consectetur ut. Duis in erat quis dui imperdiet pretium et eu purus. Fusce purus ex, placerat et eros quis, lacinia tempor ligula.  </p>
                                <p>Placerat in egestas erat imperdiet sed euismod. Erat pellentesque adipiscing commodo elit at imperdiet dui accumsan sit. Quam viverra orci sagittis eu volutpat odio facilisis. Sit amet massa vitae tortor condimentum lacinia. Lacus vestibulum sed arcu non odio. Sodales ut eu sem integer vitae justo. Amet tellus cras adipiscing enim eu turpis egestas.</p>
                                <div class="list-single-tags fl-wrap tags-stylwrap" style="margin-top: 20px;">
                                    <span>Service Areas:</span>
                                    <a href="#">London</a>
                                    <a href="#">NewYork</a>
                                    <a href="#">Rome</a>
                                    <a href="#">Dubai</a>
                                </div>
                            </div>
                        </div>
                        <!-- list-single-main-item end -->             						
                    </div>
                    <!-- content-tabs-wrap -->
                    <div class="content-tabs-wrap tabs-act fl-wrap">
                        <div class="content-tabs fl-wrap">
                            <ul class="tabs-menu fl-wrap no-list-style">
                                <li class="current"><a href="#tab-listing">  Listing  </a></li>
                                <li><a href="#tab-reviews">Reviews</a></li>
                            </ul>
                        </div>
                        <!--tabs -->                       
                        <div class="tabs-container">
                            <!--tab -->
                            <div class="tab">
                                <div id="tab-listing" class="tab-content first-tab">
                                    <!-- listing-item-wrap-->
                                    <div class="listing-item-container one-column-grid-wrap  box-list_ic fl-wrap">
                                        <!-- listing-item -->
                                        <div class="listing-item">
                                            <article class="geodir-category-listing fl-wrap">
                                                <div class="geodir-category-img fl-wrap">
                                                    <a href="listing-single.html" class="geodir-category-img_item">
                                                        <img src="images/all/1.jpg" alt="">
                                                        <div class="overlay"></div>
                                                    </a>
                                                    <div class="geodir-category-location">
                                                        <a href="#" class="single-map-item tolt" data-newlatitude="40.72956781" data-newlongitude="-73.99726866"   data-microtip-position="top-left" data-tooltip="On the map"><i class="fas fa-map-marker-alt"></i> <span>  70 Bright St New York, USA</span></a>
                                                    </div>
                                                    <ul class="list-single-opt_header_cat">
                                                        <li><a href="#" class="cat-opt blue-bg">Sale</a></li>
                                                        <li><a href="#" class="cat-opt color-bg">Apartment</a></li>
                                                    </ul>
                                                    <a href="#" class="geodir_save-btn tolt" data-microtip-position="left" data-tooltip="Save"><span><i class="fal fa-heart"></i></span></a>
                                                    <a href="#" class="compare-btn tolt" data-microtip-position="left" data-tooltip="Compare"><span><i class="fal fa-random"></i></span></a>
                                                    <div class="geodir-category-listing_media-list">
                                                        <span><i class="fas fa-camera"></i> 8</span>
                                                    </div>
                                                </div>
                                                <div class="geodir-category-content fl-wrap">
                                                    <h3 class="title-sin_item"><a href="listing-single.html">Gorgeous House For Sale</a></h3>
                                                    <div class="geodir-category-content_price">$ 600,000</div>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla.</p>
                                                    <div class="geodir-category-content-details">
                                                        <ul>
                                                            <li><i class="fal fa-bed"></i><span>3</span></li>
                                                            <li><i class="fal fa-bath"></i><span>2</span></li>
                                                            <li><i class="fal fa-cube"></i><span>450 ft2</span></li>
                                                        </ul>
                                                    </div>
                                                    <div class="geodir-category-footer fl-wrap">
                                                        <a href="agent-single.html" class="gcf-company"><img src="images/avatar/1.jpg" alt=""><span>By Liza Rose</span></a>
                                                        <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="top" data-tooltip="Good" data-starrating2="4"></div>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                        <!-- listing-item end-->	
                                        <!-- listing-item -->
                                        <div class="listing-item">
                                            <article class="geodir-category-listing fl-wrap">
                                                <div class="geodir-category-img fl-wrap">
                                                    <a href="listing-single.html" class="geodir-category-img_item">
                                                        <img src="images/all/1.jpg" alt="">
                                                        <div class="overlay"></div>
                                                    </a>
                                                    <div class="geodir-category-location">
                                                        <a href="#" class="single-map-item tolt" data-newlatitude="40.88496706" data-newlongitude="-73.88191222" data-microtip-position="top-left" data-tooltip="On the map"><i class="fas fa-map-marker-alt"></i> <span>  40 Journal Square  , NJ, USA</span></a>												
                                                    </div>
                                                    <ul class="list-single-opt_header_cat">
                                                        <li><a href="#" class="cat-opt blue-bg">Sale</a></li>
                                                        <li><a href="#" class="cat-opt color-bg">Apartment</a></li>
                                                    </ul>
                                                    <a href="#" class="geodir_save-btn tolt" data-microtip-position="left" data-tooltip="Save"><span><i class="fal fa-heart"></i></span></a>
                                                    <a href="#" class="compare-btn tolt" data-microtip-position="left" data-tooltip="Compare"><span><i class="fal fa-random"></i></span></a>
                                                    <div class="geodir-category-listing_media-list">
                                                        <span><i class="fas fa-camera"></i> 47</span>
                                                    </div>
                                                </div>
                                                <div class="geodir-category-content fl-wrap">
                                                    <h3 class="title-sin_item"><a href="listing-single.html">Luxury Family Home</a></h3>
                                                    <div class="geodir-category-content_price">$ 320,000</div>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla.</p>
                                                    <div class="geodir-category-content-details">
                                                        <ul>
                                                            <li><i class="fal fa-bed"></i><span>4</span></li>
                                                            <li><i class="fal fa-bath"></i><span>2</span></li>
                                                            <li><i class="fal fa-cube"></i><span>460 ft2</span></li>
                                                        </ul>
                                                    </div>
                                                    <div class="geodir-category-footer fl-wrap">
                                                        <a href="agent-single.html" class="gcf-company"><img src="images/avatar/1.jpg" alt=""><span>By Anna Lips</span></a>
                                                        <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="top" data-tooltip="Excellent" data-starrating2="5"></div>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                        <!-- listing-item end-->	
                                        <!-- listing-item -->
                                        <div class="listing-item">
                                            <article class="geodir-category-listing fl-wrap">
                                                <div class="geodir-category-img fl-wrap">
                                                    <a href="listing-single.html" class="geodir-category-img_item">
                                                        <img src="images/all/1.jpg" alt="">
                                                        <div class="overlay"></div>
                                                    </a>
                                                    <div class="geodir-category-location">
                                                        <a href="#" class="single-map-item tolt" data-newlatitude="40.94982541" data-newlongitude="-73.84357452" data-microtip-position="top-left" data-tooltip="On the map"><i class="fas fa-map-marker-alt"></i> <span> 34-42 Montgomery St , NY, USA</span></a>													
                                                    </div>
                                                    <ul class="list-single-opt_header_cat">
                                                        <li><a href="#" class="cat-opt blue-bg">Rent</a></li>
                                                        <li><a href="#" class="cat-opt color-bg">House</a></li>
                                                    </ul>
                                                    <a href="#" class="geodir_save-btn tolt" data-microtip-position="left" data-tooltip="Save"><span><i class="fal fa-heart"></i></span></a>
                                                    <a href="#" class="compare-btn tolt" data-microtip-position="left" data-tooltip="Compare"><span><i class="fal fa-random"></i></span></a>
                                                    <div class="geodir-category-listing_media-list">
                                                        <span><i class="fas fa-camera"></i> 4</span>
                                                    </div>
                                                </div>
                                                <div class="geodir-category-content fl-wrap">
                                                    <h3 class="title-sin_item"><a href="listing-single.html">Family House for Rent</a></h3>
                                                    <div class="geodir-category-content_price">$ 700 / per month</div>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla.</p>
                                                    <div class="geodir-category-content-details">
                                                        <ul>
                                                            <li><i class="fal fa-bed"></i><span>2</span></li>
                                                            <li><i class="fal fa-bath"></i><span>1</span></li>
                                                            <li><i class="fal fa-cube"></i><span>220 ft2</span></li>
                                                        </ul>
                                                    </div>
                                                    <div class="geodir-category-footer fl-wrap">
                                                        <a href="agent-single.html" class="gcf-company"><img src="images/avatar/1.jpg" alt=""><span>By Mark Frosty</span></a>
                                                        <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="top" data-tooltip="Good" data-starrating2="4"></div>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                        <!-- listing-item end-->							
                                        <!-- listing-item -->
                                        <div class="listing-item">
                                            <article class="geodir-category-listing fl-wrap">
                                                <div class="geodir-category-img fl-wrap">
                                                    <a href="listing-single.html" class="geodir-category-img_item">
                                                        <img src="images/all/1.jpg" alt="">
                                                        <div class="overlay"></div>
                                                    </a>
                                                    <div class="geodir-category-location">
                                                        <a href="#" class="single-map-item tolt" data-newlatitude="40.72228267" data-newlongitude="-73.99246214" data-microtip-position="top-left" data-tooltip="On the map"><i class="fas fa-map-marker-alt"></i> <span> W 85th St, New York, USA</span></a>												
                                                    </div>
                                                    <ul class="list-single-opt_header_cat">
                                                        <li><a href="#" class="cat-opt blue-bg">Sale</a></li>
                                                        <li><a href="#" class="cat-opt color-bg">Apartment</a></li>
                                                    </ul>
                                                    <a href="#" class="geodir_save-btn tolt" data-microtip-position="left" data-tooltip="Save"><span><i class="fal fa-heart"></i></span></a>
                                                    <a href="#" class="compare-btn tolt" data-microtip-position="left" data-tooltip="Compare"><span><i class="fal fa-random"></i></span></a>
                                                    <div class="geodir-category-listing_media-list">
                                                        <span><i class="fas fa-camera"></i> 13</span>
                                                    </div>
                                                </div>
                                                <div class="geodir-category-content fl-wrap">
                                                    <h3 class="title-sin_item"><a href="listing-single.html">Contemporary Apartment</a></h3>
                                                    <div class="geodir-category-content_price">$ 1,600,000</div>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas in pulvinar neque. Nulla finibus lobortis pulvinar. Donec a consectetur nulla.</p>
                                                    <div class="geodir-category-content-details">
                                                        <ul>
                                                            <li><i class="fal fa-bed"></i><span>4</span></li>
                                                            <li><i class="fal fa-bath"></i><span>1</span></li>
                                                            <li><i class="fal fa-cube"></i><span>550 ft2</span></li>
                                                        </ul>
                                                    </div>
                                                    <div class="geodir-category-footer fl-wrap">
                                                        <a href="agent-single.html" class="gcf-company"><img src="images/avatar/1.jpg" alt=""><span>By Bill Trust</span></a>
                                                        <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="top" data-tooltip="Excellent
                                                            " data-starrating2="5"></div>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                        <!-- listing-item end-->								
                                    </div>
                                    <!-- listing-item-wrap end-->
                                    <!-- pagination-->
                                    <div class="pagination">
                                        <a href="#" class="prevposts-link"><i class="fa fa-caret-left"></i></a>
                                        <a href="#" >1</a>
                                        <a href="#" class="current-page">2</a>
                                        <a href="#">3</a>
                                        <a href="#">4</a>
                                        <a href="#" class="nextposts-link"><i class="fa fa-caret-right"></i></a>
                                    </div>
                                    <!-- pagination end-->						
                                </div>
                            </div>
                            <!--tab  end-->
                            <!--tab -->
                            <div class="tab">
                                <div id="tab-reviews" class="tab-content">
                                    <div class="list-single-main-container fl-wrap" style="margin-top: 30px;">
                                        <!-- list-single-main-item -->
                                        <div class="list-single-main-item fl-wrap" id="sec6">
                                            <div class="list-single-main-item-title">
                                                <h3>Reviews <span>2</span></h3>
                                            </div>
                                            <div class="list-single-main-item_content fl-wrap">
                                                <div class="reviews-comments-wrap fl-wrap">
                                                    <div class="review-total">
                                                        <span class="review-number blue-bg">5.0</span>
                                                        <div class="listing-rating card-popup-rainingvis" data-starrating2="5"><span class="re_stars-title">Excellent</span></div>
                                                    </div>
                                                    <!-- reviews-comments-item -->  
                                                    <div class="reviews-comments-item">
                                                        <div class="review-comments-avatar">
                                                            <img src="images/avatar/1.jpg" alt=""> 
                                                        </div>
                                                        <div class="reviews-comments-item-text smpar">
                                                            <div class="box-widget-menu-btn smact"><i class="far fa-ellipsis-h"></i></div>
                                                            <div class="show-more-snopt-tooltip bxwt">
                                                                <a href="#"> <i class="fas fa-reply"></i> Reply</a>
                                                                <a href="#"> <i class="fas fa-exclamation-triangle"></i> Report </a>
                                                            </div>
                                                            <h4><a href="#">Liza Rose</a></h4>
                                                            <div class="listing-rating card-popup-rainingvis" data-starrating2="5"><span class="re_stars-title">Excellent</span></div>
                                                            <div class="clearfix"></div>
                                                            <p>" Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. "</p>
                                                            <div class="reviews-comments-item-date"><span class="reviews-comments-item-date-item"><i class="far fa-calendar-check"></i>12 April 2018</span><a href="#" class="rate-review"><i class="fal fa-thumbs-up"></i>  Helpful Review  <span>6</span> </a></div>
                                                        </div>
                                                    </div>
                                                    <!--reviews-comments-item end--> 
                                                    <!-- reviews-comments-item -->  
                                                    <div class="reviews-comments-item">
                                                        <div class="review-comments-avatar">
                                                            <img src="images/avatar/1.jpg" alt=""> 
                                                        </div>
                                                        <div class="reviews-comments-item-text smpar">
                                                            <div class="box-widget-menu-btn smact"><i class="far fa-ellipsis-h"></i></div>
                                                            <div class="show-more-snopt-tooltip bxwt">
                                                                <a href="#"> <i class="fas fa-reply"></i> Reply</a>
                                                                <a href="#"> <i class="fas fa-exclamation-triangle"></i> Report </a>
                                                            </div>
                                                            <h4><a href="#">Adam Koncy</a></h4>
                                                            <div class="listing-rating card-popup-rainingvis" data-starrating2="5"><span class="re_stars-title">Excellent</span></div>
                                                            <div class="clearfix"></div>
                                                            <p>" Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc posuere convallis purus non cursus. Cras metus neque, gravida sodales massa ut. "</p>
                                                            <div class="reviews-comments-item-date"><span class="reviews-comments-item-date-item"><i class="far fa-calendar-check"></i>03 December 2017</span><a href="#" class="rate-review"><i class="fal fa-thumbs-up"></i>  Helpful Review  <span>2</span> </a></div>
                                                        </div>
                                                    </div>
                                                    <!--reviews-comments-item end-->                                                                  
                                                </div>
                                            </div>
                                        </div>
                                        <!-- list-single-main-item end -->                                             
                                        <!-- list-single-main-item -->
                                        <div class="list-single-main-item fl-wrap" id="sec5">
                                            <div class="list-single-main-item-title fl-wrap">
                                                <h3>Add Your Review</h3>
                                            </div>
                                            <!-- Add Review Box -->
                                            <div id="add-review" class="add-review-box">
                                                <div class="leave-rating-wrap">
                                                    <span class="leave-rating-title">Your rating  for this listing : </span>
                                                    <div class="leave-rating">
                                                        <input type="radio"    data-ratingtext="Excellent"   name="rating" id="rating-1" value="1"/>
                                                        <label for="rating-1" class="fal fa-star"></label>
                                                        <input type="radio" data-ratingtext="Good" name="rating" id="rating-2" value="2"/>
                                                        <label for="rating-2" class="fal fa-star"></label>
                                                        <input type="radio" name="rating"  data-ratingtext="Average" id="rating-3" value="3"/>
                                                        <label for="rating-3" class="fal fa-star"></label>
                                                        <input type="radio" data-ratingtext="Fair" name="rating" id="rating-4" value="4"/>
                                                        <label for="rating-4" class="fal fa-star"></label>
                                                        <input type="radio" data-ratingtext="Very Bad "   name="rating" id="rating-5" value="5"/>
                                                        <label for="rating-5"    class="fal fa-star"></label>
                                                    </div>
                                                    <div class="count-radio-wrapper">
                                                        <span id="count-checked-radio">Your Rating</span>  
                                                    </div>
                                                </div>
                                                <!-- Review Comment -->
                                                <form   class="add-comment custom-form">
                                                    <fieldset>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Your name* <span class="dec-icon"><i class="fas fa-user"></i></span></label>
                                                                <input   name="phone" type="text"    onClick="this.select()" value="">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Yourmail* <span class="dec-icon"><i class="fas fa-envelope"></i></span></label>
                                                                <input   name="reviewwname" type="text"    onClick="this.select()" value="">
                                                            </div>
                                                        </div>
                                                        <textarea cols="40" rows="3" placeholder="Your Review:"></textarea>
                                                    </fieldset>
                                                    <button class="btn big-btn color-bg float-btn">Submit Review <i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                                                </form>
                                            </div>
                                            <!-- Add Review Box / End -->
                                        </div>
                                        <!-- list-single-main-item end -->             										
                                    </div>
                                </div>
                            </div>
                            <!--tab end-->							
                        </div>
                        <!--tabs end-->  
                    </div>
                    <!-- content-tabs-wrap end -->
                </div>
                <!-- col-md 8 end -->
                <!--  sidebar-->
                <div class="col-md-4">
                    <!--box-widget-->
                    <div class="box-widget bwt-first fl-wrap">
                        <div class="box-widget-title fl-wrap box-widget-title-color color-bg no-top-margin">Agent Contacts</div>
                        <div class="box-widget-content fl-wrap">
                            <div class="contats-list clm fl-wrap">
                                <ul class="no-list-style">
                                    <li><span><i class="fal fa-phone"></i> Phone :</span> <a href="#">+7(123)987654</a></li>
                                    <li><span><i class="fal fa-envelope"></i> Mail :</span> <a href="#">MaversRealEstate@domain.com</a></li>
                                    <li><span><i class="fal fa-map-marker"></i> Adress :</span> <a href="#"> 70 Bright St New York, USA</a></li>
                                    <li><span><i class="fal fa-browser"></i> Website :</span> <a href="#">themeforest.net</a></li>
                                </ul>
                            </div>
                            <div class="profile-widget-footer fl-wrap">
                                <div class="card-info-content_social ">
                                    <ul>
                                        <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                        <li><a href="#" target="_blank"><i class="fab fa-vk"></i></a></li>
                                    </ul>
                                </div>
                                <a href="#sec-contact" class="custom-scroll-link tolt csls" data-microtip-position="left" data-tooltip="Write Message"><i class="fal fa-paper-plane"></i></a>
                            </div>
                        </div>
                    </div>
                    <!--box-widget end --> 									
                    <!--box-widget-->
                    <div class="box-widget fl-wrap">
                        <div class="box-widget-fixed-init fl-wrap" id="sec-contact">
                            <div class="box-widget-title fl-wrap box-widget-title-color color-bg no-top-margin">Get In Touch</div>
                            <div class="box-widget-content fl-wrap">
                                <div class="custom-form">
                                    <form method="post"  name="contact-property-form">
                                        <label>Your name* <span class="dec-icon"><i class="fas fa-user"></i></span></label>
                                        <input   name="phone" type="text"    onClick="this.select()" value="">
                                        <label>Your mail  * <span class="dec-icon"><i class="fas fa-envelope"></i></span></label>
                                        <input   name="mail" type="text"    onClick="this.select()" value="">      
                                        <textarea cols="40" rows="3" placeholder="Your Message:" style="height: 150px"></textarea>
                                        <button type="submit" class="btn float-btn color-bg fw-btn"> Send</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--box-widget end -->               									
                </div>
                <!--   sidebar end-->								
            </div>
        </div>
        <div class="limit-box fl-wrap"></div>
    </section>
</div>
<!-- content end -->
@endsection
