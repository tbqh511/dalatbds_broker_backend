<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Quản lý Lead'])
            <!-- dashboard-title end -->

            <div class="dasboard-wrapper fl-wrap" x-data="leadsApp()">

                <div class="dasboard-widget-title fl-wrap">
                    <h5><i class="fal fa-users"></i>Danh sách Lead</h5>
                </div>

                <div class="dasboard-widget-box fl-wrap">

                    <!-- Search bar -->
                    <div class="leads-search-wrap">
                        <i class="fal fa-search leads-search-icon"></i>
                        <input
                            type="text"
                            class="leads-search-input"
                            placeholder="Tìm theo tên hoặc số điện thoại..."
                            x-model="search"
                            @input="onSearch()"
                        >
                        <button
                            type="button"
                            class="leads-search-clear"
                            x-show="search.length > 0"
                            @click="search = ''; fetchLeads(true)"
                            title="Xóa"
                        ><i class="fal fa-times"></i></button>
                    </div>

                    <!-- Status filter pills -->
                    <div class="webapp-filter-pills">
                        <button type="button" @click="setStatus('')"
                            :class="status === '' ? 'filter-pill pill-active' : 'filter-pill'">Tất cả</button>
                        <button type="button" @click="setStatus('new')"
                            :class="status === 'new' ? 'filter-pill pill-new pill-active' : 'filter-pill pill-new'">Mới</button>
                        <button type="button" @click="setStatus('contacted')"
                            :class="status === 'contacted' ? 'filter-pill pill-contacted pill-active' : 'filter-pill pill-contacted'">Đã liên hệ</button>
                        <button type="button" @click="setStatus('converted')"
                            :class="status === 'converted' ? 'filter-pill pill-converted pill-active' : 'filter-pill pill-converted'">Chuyển đổi</button>
                        <button type="button" @click="setStatus('lost')"
                            :class="status === 'lost' ? 'filter-pill pill-lost pill-active' : 'filter-pill pill-lost'">Thất bại</button>
                    </div>

                    <!-- Lead cards -->
                    <div class="row" id="leads-container">
                        <template x-for="(lead, index) in leads" :key="lead.id">
                            <div class="col-md-6">
                                <div class="bookings-item fl-wrap">
                                    <div class="bookings-item-header fl-wrap">
                                        <h4 x-text="lead.customer_name"></h4>
                                        <span class="new-bookmark" :class="'status-' + lead.status_raw" x-text="lead.status_label"></span>
                                    </div>
                                    <div class="bookings-item-content fl-wrap">
                                        <ul>
                                            <template x-if="lead.categories">
                                                <li>Loại BĐS: <span x-text="lead.categories"></span></li>
                                            </template>
                                            <li>Nhu cầu: <span x-text="lead.lead_type"></span></li>
                                            <li>Ngân sách: <span x-text="lead.budget"></span></li>
                                            <template x-if="lead.wards">
                                                <li>Khu vực: <span x-text="lead.wards"></span></li>
                                            </template>
                                            <template x-if="lead.street">
                                                <li>Đường: <span x-text="lead.street"></span></li>
                                            </template>
                                        </ul>
                                    </div>
                                    <div class="bookings-item-footer fl-wrap">
                                        <div class="message-date" x-text="lead.date"></div>
                                        <ul>
                                            <li>
                                                <a :href="'tel:' + lead.customer_contact"
                                                    class="tolt" data-microtip-position="top-left"
                                                    data-tooltip="Gọi điện"><i class="far fa-phone"></i></a>
                                            </li>
                                            <li>
                                                <a :href="lead.show_url" class="tolt"
                                                    data-microtip-position="top-left"
                                                    data-tooltip="Chi tiết"><i class="far fa-eye"></i></a>
                                            </li>
                                            <li>
                                                <a :href="lead.edit_url" class="tolt"
                                                    data-microtip-position="top-left"
                                                    data-tooltip="Chỉnh sửa"><i class="far fa-edit"></i></a>
                                            </li>
                                            <li>
                                                <button type="button"
                                                    class="tolt bookings-footer-btn"
                                                    data-microtip-position="top-left"
                                                    data-tooltip="Xóa"
                                                    @click="deleteLead(lead)">
                                                    <i class="far fa-trash"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Empty state -->
                    <div x-show="!loading && leads.length === 0" class="col-md-12" style="padding: 30px 0; text-align: center; color: #888;">
                        <i class="fal fa-inbox" style="font-size: 32px; display:block; margin-bottom:10px;"></i>
                        Không tìm thấy lead nào.
                    </div>

                    <!-- Loading spinner -->
                    <div x-show="loading" style="text-align:center; padding: 20px 0;">
                        <i class="fal fa-spinner fa-spin" style="font-size: 24px; color: #3270FC;"></i>
                    </div>

                    <!-- Infinite scroll sentinel -->
                    <div id="scroll-sentinel" x-ref="sentinel"></div>

                </div>
            </div>
        </div>
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>

<script>
function leadsApp() {
    return {
        leads: [],
        search: '',
        status: '',
        loading: false,
        hasMore: false,
        nextPage: 1,
        searchTimeout: null,

        init() {
            this.fetchLeads(true);
            this.setupIntersectionObserver();
        },

        setupIntersectionObserver() {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && this.hasMore && !this.loading) {
                    this.fetchLeads(false);
                }
            }, { rootMargin: '100px' });
            observer.observe(this.$refs.sentinel);
        },

        onSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => this.fetchLeads(true), 400);
        },

        setStatus(s) {
            this.status = s;
            this.fetchLeads(true);
        },

        async fetchLeads(reset) {
            if (this.loading) return;
            if (reset) {
                this.leads = [];
                this.nextPage = 1;
                this.hasMore = false;
            }
            this.loading = true;
            try {
                const params = new URLSearchParams({ page: this.nextPage });
                if (this.search) params.set('search', this.search);
                if (this.status) params.set('status', this.status);

                const resp = await axios.get('{{ route('webapp.leads') }}?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                this.leads.push(...resp.data.leads);
                this.hasMore    = resp.data.has_more;
                this.nextPage   = resp.data.next_page;
            } catch (e) {
                console.error('Failed to load leads', e);
            } finally {
                this.loading = false;
            }
        },

        async deleteLead(lead) {
            if (!confirm('Bạn có chắc muốn xóa lead này?')) return;
            try {
                await axios.delete(lead.delete_url, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                this.leads = this.leads.filter(l => l.id !== lead.id);
            } catch (e) {
                alert('Xóa thất bại, vui lòng thử lại.');
            }
        }
    }
}
</script>
