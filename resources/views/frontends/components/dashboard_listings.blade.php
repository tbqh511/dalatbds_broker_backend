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
                            <input type="text" id="search-input" onkeyup="handleSearch(this)"
                                placeholder="Tìm kiếm theo tên đường, phường..." value="">
                            <button type="submit"><i class="far fa-search"></i></button>
                        </div>
                        <a href="{{ route('webapp.add_listing') }}" class="gradient-bg dashboard-addnew_btn">Đăng tin
                            mới <i class="fal fa-plus"></i></a>
                        <!-- price-opt-->
                        <div class="price-opt">
                            <span class="price-opt-title">Sắp xếp theo:</span>
                            <div class="listsearch-input-item">
                                <select id="sort-select" onchange="handleSortChange(this)" data-placeholder="Mới nhất"
                                    class="chosen-select no-search-select">
                                    <option value="latest">Mới nhất</option>
                                    <option value="oldest">Cũ nhất</option>
                                    <option value="price_asc">Giá: Thấp đến Cao</option>
                                    <option value="price_desc">Giá: Cao đến Thấp</option>
                                    <option value="area_asc">Diện tích: Bé đến Lớn</option>
                                    <option value="area_desc">Diện tích: Lớn đến Bé</option>
                                </select>
                            </div>
                        </div>
                        <!-- price-opt end-->
                    </div>
                    <!-- dashboard-listings-wrap-->
                    <div class="dashboard-listings-wrap fl-wrap" style="position: relative; min-height: 200px;">
                        <!-- Loading Overlay -->
                        <div id="loading-overlay"
                            style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 10; align-items: center; justify-content: center;">
                            <div class="spinner-border text-primary" role="status"
                                style="width: 3rem; height: 3rem; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite;">
                            </div>
                        </div>
                        <style>
                            @keyframes spin {
                                0% {
                                    transform: rotate(0deg);
                                }

                                100% {
                                    transform: rotate(360deg);
                                }
                            }
                        </style>

                        @include('frontends.components.dashboard_listings_items')
                    </div>
                    <!-- dashboard-listings-wrap end-->
                </div>
                <!-- pagination moved to inside partial -->
            </div>
        </div>
        <!-- dashboard-footer -->
        @include('components.dashboard.footer')
        <!-- dashboard-footer end -->
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let searchTimeout;
    let isBusy = false; // Prevent concurrent operations

    // Show/hide loading overlay helpers
    function showLoader() {
        const loader = document.getElementById('loading-overlay');
        if (loader) loader.style.display = 'flex';
    }
    function hideLoader() {
        const loader = document.getElementById('loading-overlay');
        if (loader) loader.style.display = 'none';
    }

    // Handle Search with Debounce
    function handleSearch(input) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchListings();
        }, 500);
    }

    // Handle Sort Change
    function handleSortChange(select) {
        fetchListings();
    }

    // Hook into Chosen plugin if it exists (since onchange on hidden select might not work directly)
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery) {
            $('#sort-select').on('change', function () {
                handleSortChange(this);
            });
        }
    });

    // Main Fetch Function - returns a Promise so callers can await completion
    function fetchListings(url = "{{ route('webapp.listings') }}") {
        const search = document.getElementById('search-input').value;
        const sort = document.getElementById('sort-select').value;

        showLoader();

        // Construct URL
        const targetUrl = new URL(url);
        if (search) targetUrl.searchParams.set('search', search);
        if (sort) targetUrl.searchParams.set('sort', sort);

        return fetch(targetUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.text())
            .then(html => {
                const wrapper = document.querySelector('.dashboard-listings-wrap');
                const overlay = document.getElementById('loading-overlay');

                // Replace content and re-append overlay
                wrapper.innerHTML = html;
                if (overlay) {
                    overlay.style.display = 'none';
                    wrapper.appendChild(overlay);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                hideLoader();
            });
    }

    // Helper to handle fetch errors
    async function handleFetchResponse(response) {
        if (!response.ok) {
            try {
                const data = await response.json();
                throw new Error(data.message || `Lỗi máy chủ: ${response.status}`);
            } catch (e) {
                if (e instanceof SyntaxError) {
                    // Response body was not JSON
                    if (response.status === 419) {
                        throw new Error('Phiên làm việc đã hết hạn. Vui lòng tải lại trang.');
                    }
                    throw new Error(`Lỗi kết nối: ${response.status} ${response.statusText}`);
                }
                throw e; // Re-throw the Error we created above
            }
        }
        return response.json();
    }

    // Retrieve CSRF token from meta tag
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    // Delete Listing
    function deleteListing(id) {
        if (isBusy) return;

        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Tin đăng sẽ bị xóa vĩnh viễn!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa ngay',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                isBusy = true;
                showLoader();

                fetch(`{{ url('/webapp/listings') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(handleFetchResponse)
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Đã xóa!',
                                'Tin đăng đã được xóa thành công.',
                                'success'
                            );
                            return fetchListings(); // Wait for reload to complete
                        } else {
                            throw new Error(data.message || 'Không thể xóa tin đăng.');
                        }
                    })
                    .catch(err => {
                        console.error('Delete Error:', err);
                        Swal.fire('Lỗi!', err.message || 'Lỗi kết nối máy chủ.', 'error');
                        hideLoader();
                    })
                    .finally(() => {
                        isBusy = false;
                    });
            }
        })
    }

    // Toggle Status
    function toggleListing(id) {
        if (isBusy) return; // Prevent concurrent operations
        isBusy = true;
        showLoader();

        fetch(`{{ url('/webapp/listings') }}/${id}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
            .then(handleFetchResponse)
            .then(data => {
                if (data.success) {
                    // Show toast
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                    return fetchListings(); // Wait for reload to complete before releasing busy flag
                } else {
                    throw new Error(data.message || 'Không thể cập nhật trạng thái.');
                }
            })
            .catch(err => {
                console.error('Toggle Error:', err);
                Swal.fire('Lỗi!', err.message || 'Lỗi kết nối máy chủ.', 'error');
                hideLoader();
            })
            .finally(() => {
                isBusy = false;
            });
    }
</script>