<div class="dashbard-menu-overlay"></div>
@include('components.dashboard.sidebar')

<!-- dashboard content -->
<div class="dashboard-content">
    @include('components.dashboard.mobile_btn')
    <div class="container dasboard-container">
        <!-- dashboard-title -->
        @include('components.dashboard.header', ['title' => 'Tổng quan'])
        <!-- dashboard-title end -->

        <div class="dasboard-wrapper fl-wrap no-pag">
            <div class="dashboard-stats-container fl-wrap">
                <div class="row">
                    <!--dashboard-stats-->
                    <div class="col-md-3">
                        <div class="dashboard-stats fl-wrap">
                            <i class="fal fa-map-marked"></i>
                            <h4>Tin đang hiển thị</h4>
                            <div class="dashboard-stats-count">{{ $stats['properties_count'] ?? 0 }}</div>
                        </div>
                    </div>
                    <!-- dashboard-stats end -->
                    <!--dashboard-stats-->
                    <div class="col-md-3">
                        <div class="dashboard-stats fl-wrap">
                            <i class="fal fa-chart-bar"></i>
                            <h4>Lượt xem tin</h4>
                            <div class="dashboard-stats-count">{{ $stats['views_count'] ?? 0 }}<span>(<strong>+{{
                                        $stats['views_count_week'] ?? 0 }}</strong> trong tuần)</span></div>
                        </div>
                    </div>
                    <!-- dashboard-stats end -->
                    <!--dashboard-stats-->
                    <div class="col-md-3">
                        <div class="dashboard-stats fl-wrap">
                            <i class="fal fa-comments-alt"></i>
                            <h4>Đánh giá của bạn</h4>
                            <div class="dashboard-stats-count">{{ $stats['reviews_count'] ?? 0 }}<span>(<strong>+{{
                                        $stats['reviews_count_week'] ?? 0 }}</strong> trong tuần)</span></div>
                        </div>
                    </div>
                    <!-- dashboard-stats end -->
                    <!--dashboard-stats-->
                    <div class="col-md-3">
                        <div class="dashboard-stats fl-wrap">
                            <i class="fal fa-heart"></i>
                            <h4>Lượt quan tâm</h4>
                            <div class="dashboard-stats-count">{{ $stats['favourites_count'] ?? 0 }}<span>(<strong>+{{
                                        $stats['favourites_count_week'] ?? 0 }}</strong> trong tuần)</span></div>
                        </div>
                    </div>
                    <!-- dashboard-stats end -->
                </div>
            </div>
            <div class="clearfix"></div>

            <!-- FEED SECTION START -->
            <div class="dashboard-title fl-wrap"
                style="margin-top: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                <h3><i class="fal fa-list"></i> Bất động sản mới nhất</h3>
            </div>
            <div class="dashboard-listings-wrap fl-wrap"
                style="position: relative; min-height: 200px; margin-top: 20px;">

                <!-- Properties Container -->
                <div class="row" id="feed-container">
                    @include('frontends.components.dashboard_feed_items')
                </div>

                <!-- Infinite Scroll Trigger -->
                <div id="load-more-trigger" class="skeleton-loader-container" style="display: none;">
                    <div class="row" style="width: 100%;">
                        <div class="col-md-12">
                            <div class="skeleton-card" style="display: flex;">
                                <div class="skeleton-img"
                                    style="width: 240px; min-width: 240px; height: 200px; border-radius: 4px 0 0 4px;">
                                </div>
                                <div class="skeleton-content"
                                    style="flex: 1; border: 1px solid #eee; border-left: none; border-radius: 0 4px 4px 0;">
                                    <div class="skeleton-title"></div>
                                    <div class="skeleton-line"></div>
                                    <div class="skeleton-line short"></div>
                                    <div class="skeleton-line" style="margin-top: 40px; width: 40%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No more data message -->
                <div id="no-more-data" style="display: none; text-align: center; padding: 20px; color: #666;">
                    Bạn đã xem hết các tin Bất động sản mới nhất.
                </div>

            </div>
            <!-- FEED SECTION END -->

            {{-- <div class="row">
                <div class="col-md-8">
                    <div class="notification success-notif  fl-wrap">
                        <p>Tin đăng <a href="#">Nhà phố tại Phường 1</a> của bạn đã được duyệt!</p>
                        <a class="notification-close" href="#"><i class="fal fa-times"></i></a>
                    </div>
                    <div class="dashboard-widget-title fl-wrap">Thống kê của bạn</div>
                    <div class="dasboard-content fl-wrap">
                        <div class="chart-wrap fl-wrap">
                            <div class="chart-header fl-wrap">
                                <div class="listsearch-input-item">
                                    <select data-placeholder="Tuần" class="chosen-select no-search-select">
                                        <option>Tuần</option>
                                        <option>Tháng</option>
                                        <option>Năm</option>
                                    </select>
                                </div>
                                <div id="myChartLegend"></div>
                            </div>
                            <canvas id="canvas-chart"></canvas>
                        </div>
                    </div>
                    <div class="dashboard-widget-title fl-wrap">Hoạt động gần đây</div>
                    <div class="dashboard-list-box fl-wrap">
                        <div class="dashboard-list fl-wrap">
                            <div class="dashboard-message">
                                <span class="close-dashboard-item color-bg"><i class="fal fa-times"></i></span>
                                <div class="main-dashboard-message-icon color-bg"><i class="far fa-check"></i></div>
                                <div class="main-dashboard-message-text">
                                    <p>Tin đăng <a href="#">Căn hộ cao cấp</a> của bạn đã được duyệt! </p>
                                </div>
                                <div class="main-dashboard-message-time"><i class="fal fa-calendar-week"></i> 28 Th5
                                    2020</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dasboard-widget fl-wrap">
                        <div class="dasboard-widget-title fl-wrap">
                            <h5><i class="fas fa-comment-alt"></i>Tin nhắn mới nhất</h5>
                        </div>
                        <div class="chat-contacts fl-wrap">
                            <a class="chat-contacts-item" href="#">
                                <div class="dashboard-message-avatar">
                                    <img src="{{ asset('images/avatar/1.jpg') }}" alt="">
                                    <div class="message-counter">2</div>
                                </div>
                                <div class="chat-contacts-item-text">
                                    <h4>Minh Tuấn</h4>
                                    <span>27 Th12 2018 </span>
                                    <p>Chào bạn, tôi quan tâm đến căn nhà bạn đăng bán...</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="box-widget fl-wrap">
                        <div class="banner-widget fl-wrap">
                            <div class="bg-wrap bg-parallax-wrap-gradien">
                                <div class="bg" data-bg="{{ asset('images/bg/1.jpg') }}"></div>
                            </div>
                            <div class="banner-widget_content">
                                <h5>Tham gia chương trình khách hàng thân thiết. Giới thiệu bạn bè và nhận ưu đãi.</h5>
                                <a href="#" class="btn float-btn color-bg small-btn">Xem thêm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <!-- dashboard-footer -->
    @include('components.dashboard.footer')
    <!-- dashboard-footer end -->
</div>
<div class="dashbard-bg gray-bg"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentPage = 1;
        let isLoading = false;
        let hasMore = true; // Set to false if last page reached

        // Pagination info from Laravel
        @if (isset($properties) && !$properties -> hasMorePages())
            hasMore = false;
        document.getElementById('no-more-data').style.display = 'block';
        @endif

        const trigger = document.getElementById('load-more-trigger');

        // Fallback if IntersectionObserver isn't supported
        if (!('IntersectionObserver' in window)) {
            console.warn("IntersectionObserver not supported.");
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !isLoading && hasMore) {
                loadMoreProperties();
            }
        }, {
            root: null, // window
            rootMargin: '0px',
            threshold: 0.1 // Trigger when 10% of the loader is visible
        });

        // Start observing
        if (hasMore) {
            trigger.style.display = 'flex';
            observer.observe(trigger);
        }

        function loadMoreProperties() {
            if (isLoading || !hasMore) return;

            isLoading = true;
            currentPage++;

            // Ensure skeleton loader is visible
            trigger.style.display = 'flex';

            const url = `{{ route('webapp.feed') }}?page=${currentPage}`;

            setTimeout(() => {
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Network error');
                        return response.text();
                    })
                    .then(html => {
                        if (!html.trim()) {
                            hasMore = false;
                            observer.disconnect();
                            trigger.style.display = 'none';
                            document.getElementById('no-more-data').style.display = 'block';
                        } else {
                            const container = document.getElementById('feed-container');
                            container.insertAdjacentHTML('beforeend', html);

                            if (html.indexOf('no-more-items-flag') !== -1) {
                                hasMore = false;
                                observer.disconnect();
                                trigger.style.display = 'none';
                                document.getElementById('no-more-data').style.display = 'block';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching feed:', error);
                        currentPage--; // Revert page number
                    })
                    .finally(() => {
                        isLoading = false;
                        if (!hasMore) {
                            trigger.style.display = 'none';
                        }
                    });
            }, 500); // 500ms delay for skeleton effect
        }
    });
</script>