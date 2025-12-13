<div class="col-md-4">
    <div class="box-widget-wrap fl-wrap fixed-bar">
        <!-- Search Widget -->
        <div class="box-widget fl-wrap">
            <div class="box-widget-title fl-wrap">Tìm kiếm</div>
            <div class="search-widget fl-wrap">
                <form action="{{ route('news.index') }}" method="GET" class="fl-wrap custom-form">
                    <input name="se" id="se" type="text" class="search" placeholder="Tìm kiếm tin tức..."
                        value="{{ request('se') }}" style="width: 100%; padding-right: 50px;" />
                    <button class="search-submit" id="submit_btn" style="position: absolute; right: 0; top: 0; height: 100%; width: 50px; background: transparent; border: none; cursor: pointer;">
                        <i class="far fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Recent Posts Widget -->
        @php
            $sidebarPosts = $recent_news ?? ($news ?? collect([]));
            if ($sidebarPosts instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $sidebarPosts = $sidebarPosts->getCollection();
            }
        @endphp

        @if($sidebarPosts->count() > 0)
        <div class="box-widget fl-wrap">
            <div class="box-widget-title fl-wrap">Bài viết mới nhất</div>
            <div class="box-widget-content fl-wrap">
                <div class="widget-posts fl-wrap">
                    <ul class="no-list-style">
                        @foreach($sidebarPosts->take(5) as $recent)
                            <li>
                                <div class="widget-posts-img">
                                    <a href="{{ route('news.show', $recent->post_name) }}">
                                        @php
                                            $thumbUrl = asset('images/all/blog/1.jpg');
                                            $thumbMeta = $recent->meta->where('meta_key', '_thumbnail')->first();
                                            if ($thumbMeta && $thumbMeta->meta_value) {
                                                if (file_exists(public_path('assets/images/posts/' . basename($thumbMeta->meta_value)))) {
                                                    $thumbUrl = asset('assets/images/posts/' . basename($thumbMeta->meta_value));
                                                } elseif (Storage::disk('public')->exists($thumbMeta->meta_value)) {
                                                    $thumbUrl = Storage::url($thumbMeta->meta_value);
                                                }
                                            }
                                        @endphp
                                        <img src="{{ $thumbUrl }}" alt="{{ $recent->post_title }}">
                                    </a>
                                </div>
                                <div class="widget-posts-descr">
                                    <h4><a href="{{ route('news.show', $recent->post_name) }}">{{ Str::limit($recent->post_title, 40) }}</a></h4>
                                    <div class="geodir-category-location fl-wrap">
                                        <a href="#"><i class="fal fa-calendar"></i> {{ $recent->created_at ? $recent->created_at->format('d/m/Y') : '' }}</a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Categories Widget -->
        <div class="box-widget fl-wrap">
            <div class="box-widget-title fl-wrap">Danh mục</div>
            <div class="box-widget-content fl-wrap">
                <ul class="cat-item no-list-style">
                    @if(isset($categories))
                        @foreach($categories as $category)
                            @if(isset($category->term))
                                <li><a href="{{ route('news.category', $category->term->slug) }}">{{ $category->term->name }}</a> <span>{{ $category->count }}</span></li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>

        <!-- Banner Widget -->
        <div class="box-widget fl-wrap">
            <div class="banner-widget fl-wrap">
                <div class="bg-wrap bg-parallax-wrap-gradien">
                    <div class="bg" data-bg="https://bds.dalat.vn/hinh-anh/quang-cao/mua-nha-da-lat-gia-re.jpg"></div>
                </div>
                <div class="banner-widget_content">
                    <h5>Bạn có muốn tham gia mạng lưới thổ địa cùng Đà Lạt BDS?</h5>
                    <a href="#" class="btn float-btn color-bg small-btn">Hãy trở thành Đối Tác của Đà Lạt BDS</a>
                </div>
            </div>
        </div>

        <!-- Tags Widget -->
        <div class="box-widget fl-wrap">
            <div class="box-widget-title fl-wrap">Tags</div>
            <div class="box-widget-content fl-wrap">
                <div class="list-single-tags fl-wrap tags-stylwrap">
                    @if(isset($tags))
                        @foreach($tags as $tag)
                            @if(isset($tag->term))
                                <a href="{{ route('news.tag', $tag->term->slug) }}">{{ $tag->term->name }}</a>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Archive (Months) Widget -->
        @if(isset($months) && $months->count() > 0)
        <div class="box-widget fl-wrap">
            <div class="box-widget-title fl-wrap">Ngày đăng</div>
            <div class="box-widget-content fl-wrap">
                <ul class="cat-item cat-item_dec no-list-style">
                    @foreach($months as $m)
                        <li>
                            <a href="{{ route('news.month', ['year' => $m->year, 'month' => $m->month]) }}">tháng {{ $m->month }} năm {{ $m->year }}</a>
                            <span>({{ $m->count }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

    </div>
</div>
