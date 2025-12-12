<div class="col-md-4">
    <div class="box-widget-wrap fl-wrap fixed-bar">
        <!-- Search Widget -->
        <div class="box-widget fl-wrap">
            <div class="search-widget fl-wrap">
                <form action="{{ route('news.index') }}" method="GET" class="fl-wrap custom-form">
                    <input name="se" id="se" type="text" class="search" placeholder="Tìm kiếm"
                        value="{{ request('se') }}" />
                    <button class="search-submit" id="submit_btn"><i class="far fa-search"></i></button>
                </form>
            </div>
        </div>

        <!-- Recent Posts Widget -->
        @php
            // Use $recent_news if available (passed from controller), otherwise fallback to $news if it's a paginator
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
                                            // Thumbnail logic - UPDATED PRIORITY FOR SIDEBAR
                                            $thumbUrl = asset('images/all/blog/1.jpg'); // Default fallback
                                            $thumbMeta = $recent->meta->where('meta_key', '_thumbnail')->first();

                                            if ($thumbMeta && $thumbMeta->meta_value) {
                                                // PRIORITY 1: Check direct public asset copy (most reliable if symlink fails)
                                                if (file_exists(public_path('assets/images/posts/' . basename($thumbMeta->meta_value)))) {
                                                    $thumbUrl = asset('assets/images/posts/' . basename($thumbMeta->meta_value));
                                                }
                                                // PRIORITY 2: Check storage via symlink
                                                elseif (Storage::disk('public')->exists($thumbMeta->meta_value)) {
                                                    $thumbUrl = Storage::url($thumbMeta->meta_value);
                                                }
                                            }
                                        @endphp
                                        <img src="{{ $thumbUrl }}" alt="{{ $recent->post_title }}">
                                    </a>
                                </div>
                                <div class="widget-posts-descr">
                                    <h4><a href="{{ route('news.show', $recent->post_name) }}">{{ $recent->post_title }}</a></h4>
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
                    <div class="bg  " data-bg="https://i.pravatar.cc/388"></div>
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
                <div class="list-single-tags fl-wrap tags-stylwrap" style="margin-top: 20px;">
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
        @if(isset($months))
        <div class="box-widget fl-wrap">
            <div class="box-widget-title fl-wrap">Ngày đăng</div>
            <div class="box-widget-content fl-wrap">
                <ul class="cat-item cat-item_dec no-list-style">
                    @if($months->count() > 0)
                        @foreach($months as $m)
                            <li>
                                <a href="{{ route('news.month', ['year' => $m->year, 'month' => $m->month]) }}">tháng {{ $m->month }} năm {{ $m->year }}</a>
                                <span>({{ $m->count }})</span>
                            </li>
                        @endforeach
                    @else
                        <li>Không có bài viết</li>
                    @endif
                </ul>
            </div>
        </div>
        @endif

    </div>
</div>
