<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Bất động sản mới nhất'])
            <!-- dashboard-title end -->

            <div class="dasboard-wrapper fl-wrap">
                <div class="dasboard-listing-box fl-wrap">
                    <!-- dashboard-listings-wrap-->
                    <div class="dashboard-listings-wrap fl-wrap" style="position: relative; min-height: 200px;">

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
                    <!-- dashboard-listings-wrap end-->
                </div>
            </div>
        </div>
        <!-- dashboard-footer -->
        @include('components.dashboard.footer')
        <!-- dashboard-footer end -->
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>

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
            console.warn("IntersectionObserver not supported, fallback to pagination button or scrolling event.");
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

            // Add a deliberate slight delay if we want skeletons to be seen (e.g. 500ms) or let it be instant
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
                            // Append HTML to container
                            const container = document.getElementById('feed-container');
                            container.insertAdjacentHTML('beforeend', html);

                            // Simple check if the payload had data but no "next page" indicator
                            // Laravel paginate links usually handles this, but since we just append HTML
                            // we can rely on empty HTML to stop, or we can look for a hidden flag.
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
            }, 500); // 500ms delay for skeleton effect (Optional, remove if you want max speed)
        }
    });
</script>