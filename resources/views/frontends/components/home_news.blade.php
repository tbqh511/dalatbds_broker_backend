<section class="gray-bg small-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="section-title fl-wrap">
                    <h4>Tin tức thị trường</h4>
                    <h2>Tin tức mới nhất</h2>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- grid-item-holder-->
        <div class="grid-item-holder gallery-items gisp fl-wrap">
            @foreach($recentNews as $newsItem)
            <!-- gallery-item-->
            <div class="gallery-item">
                <!-- listing-item -->
                <div class="listing-item">
                    <article class="geodir-category-listing fl-wrap">
                        <div class="geodir-category-img">
                            <a href="{{ route('news.show', $newsItem->post_name) }}"><img src="images/all/blog/1.jpg" alt=""></a>
                            <div class="geodir-category-opt">
                                <div class="listing-rating card-popup-rainingvis" data-starrating2="5"></div>
                                <div class="rate-class-name">
                                    <div class="score"><strong></strong></div>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="geodir-category-content fl-wrap title-sin_item">
                            <div class="geodir-category-content-title fl-wrap">
                                <div class="geodir-category-content-title-item">
                                    <h3 class="title-sin_map"><a href="{{ route('news.show', $newsItem->post_name) }}">{{ $newsItem->post_title }}</a></h3>
                                </div>
                            </div>
                            <p>{{ $newsItem->post_excerpt }}</p>
                            <ul class="list-single-opt">
                                <li><span class="cat-icon"><i class="fal fa-calendar"></i></span><span class="cat-text">{{ $newsItem->created_at->format('d M Y') }}</span></li>
                            </ul>
                        </div>
                    </article>
                </div>
                <!-- listing-item end-->
            </div>
            <!-- gallery-item end-->
            @endforeach
        </div>
        <!-- grid-item-holder-->
        <a href="{{ route('news.index') }}" class="btn float-btn small-btn color-bg" style=" position: relative; top:50%; left:50%;">Xem thêm</a>
    </div>
</section>
