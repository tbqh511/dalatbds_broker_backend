<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Tin đăng của bạn'])
            <!-- dashboard-title end -->
            
            <div class="dasboard-wrapper fl-wrap">
                <div class="dasboard-listing-box fl-wrap">
                    <div class="dasboard-opt sl-opt fl-wrap">
                        <div class="dashboard-search-listing">
                            <input type="text" onclick="this.select()" placeholder="Tìm kiếm..." value="">
                            <button type="submit"><i class="far fa-search"></i></button>
                        </div>
                        <a href="#" class="gradient-bg dashboard-addnew_btn">Đăng tin mới <i class="fal fa-plus"></i></a>
                        <!-- price-opt-->
                        <div class="price-opt">
                            <span class="price-opt-title">Sắp xếp theo:</span>
                            <div class="listsearch-input-item">
                                <select data-placeholder="Mới nhất" class="chosen-select no-search-select" >
                                    <option>Mới nhất</option>
                                    <option>Cũ nhất</option>
                                    <option>Đánh giá trung bình</option>
                                    <option>Tên: A-Z</option>
                                    <option>Tên: Z-A</option>
                                </select>
                            </div>
                        </div>
                        <!-- price-opt end-->
                    </div>
                    <!-- dashboard-listings-wrap-->
                    <div class="dashboard-listings-wrap fl-wrap">
                        <div class="row">
                            @if(isset($properties) && $properties->count())
                                @foreach($properties as $property)
                                    <div class="col-md-6">
                                        <div class="dashboard-listings-item fl-wrap">
                                            <div class="dashboard-listings-item_img">
                                                <div class="bg-wrap">
                                                    <div class="bg" data-bg="{{ $property->title_image ?: asset('images/all/1.jpg') }}"></div>
                                                </div>
                                                <div class="overlay"></div>
                                                <a href="{{ isset($property->slug) ? route('bds.show', $property->slug) : '#' }}" class="color-bg">Xem</a>
                                            </div>
                                            <div class="dashboard-listings-item_content">
                                                <h4><a href="{{ isset($property->slug) ? route('bds.show', $property->slug) : '#' }}">{{ $property->title }}</a></h4>
                                                <div class="geodir-category-location">
                                                    <a href="#"><i class="fas fa-map-marker-alt"></i>
                                                        <span> {{ $property->address_location ?? $property->address }}</span></a>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="listing-rating card-popup-rainingvis tolt" data-microtip-position="right" data-tooltip="" data-starrating2="{{ $property->rating ?? 0 }}"></div>
                                                <div class="dashboard-listings-item_opt">
                                                    <span class="viewed-counter"><i class="fas fa-eye"></i> Lượt xem - {{ $property->total_click ?? 0 }} </span>
                                                    <ul>
                                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Chỉnh sửa"><i class="far fa-edit"></i></a></li>
                                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Ẩn/Hiện"><i class="far fa-signal-alt-slash"></i></a></li>
                                                        <li><a href="#" class="tolt" data-microtip-position="top-left" data-tooltip="Xóa"><i class="far fa-trash-alt"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-info">Bạn chưa có tin đăng nào.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- dashboard-listings-wrap end-->
                </div>
                <!-- pagination-->
                @if(isset($properties) && $properties->hasPages())
                    @php
                        // Windowed pagination settings
                        $last = $properties->lastPage();
                        $current = $properties->currentPage();
                        $window = 5; // max middle pages to display
                        $half = (int) floor($window / 2);

                        // Determine start and end for middle window (excluding first and last)
                        if ($last <= ($window + 2)) {
                            $start = 2;
                            $end = $last - 1;
                        } else {
                            $start = max(2, $current - $half);
                            $end = min($last - 1, $current + $half);

                            // Adjust window when close to edges
                            if ($current - $start < $half) {
                                $end = min($last - 1, $end + ($half - ($current - $start)));
                            }
                            if ($end - $current < $half) {
                                $start = max(2, $start - ($half - ($end - $current)));
                            }
                        }
                    @endphp

                    <div class="pagination float-pagination" id="properties-pagination">
                        {{-- Prev --}}
                        @if($properties->onFirstPage())
                            <a class="prevposts-link disabled"><i class="fa fa-caret-left"></i></a>
                        @else
                            <a href="{{ $properties->previousPageUrl() }}" class="prevposts-link ajax-pagination-link"><i class="fa fa-caret-left"></i></a>
                        @endif

                        {{-- First page --}}
                        @if(1 == $current)
                            <a class="current-page" href="{{ $properties->url(1) }}">1</a>
                        @else
                            <a href="{{ $properties->url(1) }}" class="ajax-pagination-link">1</a>
                        @endif

                        {{-- Left ellipsis --}}
                        @if($start > 2)
                            <span class="dots">&hellip;</span>
                        @endif

                        {{-- Middle window --}}
                        @for($i = $start; $i <= $end; $i++)
                            @if($i == $current)
                                <a href="{{ $properties->url($i) }}" class="current-page">{{ $i }}</a>
                            @else
                                <a href="{{ $properties->url($i) }}" class="ajax-pagination-link">{{ $i }}</a>
                            @endif
                        @endfor

                        {{-- Right ellipsis --}}
                        @if($end < $last - 1)
                            <span class="dots">&hellip;</span>
                        @endif

                        {{-- Last page --}}
                        @if($last > 1)
                            @if($last == $current)
                                <a class="current-page" href="{{ $properties->url($last) }}">{{ $last }}</a>
                            @else
                                <a href="{{ $properties->url($last) }}" class="ajax-pagination-link">{{ $last }}</a>
                            @endif
                        @endif

                        {{-- Next --}}
                        @if($properties->hasMorePages())
                            <a href="{{ $properties->nextPageUrl() }}" class="nextposts-link ajax-pagination-link"><i class="fa fa-caret-right"></i></a>
                        @else
                            <a class="nextposts-link disabled"><i class="fa fa-caret-right"></i></a>
                        @endif
                    </div>

                    {{-- Pagination JS: intercept ajax-pagination-link clicks to load listings smoothly --}}
                    <script>
                        (function () {
                            // Ensure separation of concerns: basic DOM-only JS, no inline PHP logic
                            var containerSelector = '.dasboard-listing-box'; // wrapper to replace
                            var listingWrapSelector = '.dashboard-listings-wrap';

                            function fetchAndReplace(url) {
                                if (!url) return;
                                var container = document.querySelector(containerSelector);
                                if (!container) return;

                                // Smooth transition: fade out
                                container.style.transition = 'opacity 200ms ease';
                                container.style.opacity = '0.0';

                                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                                    .then(function (resp) { return resp.text(); })
                                    .then(function (html) {
                                        // Parse returned HTML and extract the listings block
                                        var parser = new DOMParser();
                                        var doc = parser.parseFromString(html, 'text/html');
                                        var newBox = doc.querySelector(containerSelector);
                                        if (newBox) {
                                            // Replace inner listing box
                                            container.innerHTML = newBox.innerHTML;
                                        }
                                        // Smooth fade in
                                        setTimeout(function () { container.style.opacity = '1'; }, 50);
                                        // Update browser URL (push state)
                                        try { window.history.pushState({}, '', url); } catch (e) { /* noop */ }
                                    })
                                    .catch(function (err) {
                                        console.error('Pagination load failed', err);
                                        container.style.opacity = '1';
                                    });
                            }

                            // Event delegation for pagination links
                            document.addEventListener('click', function (e) {
                                var el = e.target;
                                // traverse up to anchor if clicked element is icon or span
                                while (el && el.nodeName !== 'A') el = el.parentElement;
                                if (!el || !el.classList) return;
                                if (el.classList.contains('ajax-pagination-link')) {
                                    e.preventDefault();
                                    var url = el.getAttribute('href');
                                    fetchAndReplace(url);
                                }
                            });

                            // Handle back/forward navigation
                            window.addEventListener('popstate', function () {
                                fetchAndReplace(window.location.href);
                            });
                        })();
                    </script>
                    <style>
                        /* Small CSS for ellipsis and disabled state, responsive-friendly */
                        .pagination .dots { display: inline-block; padding: 6px 10px; color: #777; }
                        .pagination a.disabled { pointer-events: none; opacity: 0.5; }
                        .pagination a { display: inline-block; margin: 0 4px; padding: 6px 10px; }
                        @media (max-width: 576px) {
                            .pagination a, .pagination .dots { padding: 6px 8px; margin: 0 2px; }
                        }
                    </style>
                @endif
                <!-- pagination end-->
            </div>    
        </div>
        <!-- dashboard-footer -->
        @include('components.dashboard.footer')
        <!-- dashboard-footer end -->
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>
