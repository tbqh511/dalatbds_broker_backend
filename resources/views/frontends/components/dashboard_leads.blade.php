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

            <div class="dasboard-wrapper fl-wrap">
                <div class="dasboard-widget-title fl-wrap">
                    <h5><i class="fal fa-users"></i>Danh sách Lead</h5>
                </div>
                <div class="dasboard-widget-box fl-wrap">
                    <!-- Status filter pills -->
                    <div class="webapp-filter-pills">
                        <a href="{{ route('webapp.leads') }}"
                            class="filter-pill {{ !request('status') ? 'pill-active' : '' }}">Tất cả</a>
                        <a href="{{ route('webapp.leads', ['status' => 'new']) }}"
                            class="filter-pill pill-new {{ request('status') == 'new' ? 'pill-active' : '' }}">Mới</a>
                        <a href="{{ route('webapp.leads', ['status' => 'contacted']) }}"
                            class="filter-pill pill-contacted {{ request('status') == 'contacted' ? 'pill-active' : '' }}">Đã liên hệ</a>
                        <a href="{{ route('webapp.leads', ['status' => 'converted']) }}"
                            class="filter-pill pill-converted {{ request('status') == 'converted' ? 'pill-active' : '' }}">Chuyển đổi</a>
                        <a href="{{ route('webapp.leads', ['status' => 'lost']) }}"
                            class="filter-pill pill-lost {{ request('status') == 'lost' ? 'pill-active' : '' }}">Thất bại</a>
                    </div>
                    @php
                        $statusLabels = [
                            'New'       => 'Mới',
                            'Contacted' => 'Đã liên hệ',
                            'Converted' => 'Chuyển đổi',
                            'Lost'      => 'Thất bại',
                        ];
                    @endphp
                    <div class="row">
                        @forelse($leads as $lead)
                        @php
                            // Loại BĐS
                            $catNames = collect($lead->categories ?? [])
                                ->map(fn($id) => $categoryMap[$id] ?? null)
                                ->filter()
                                ->implode(', ');

                            // Khu vực
                            $wardNames = collect($lead->wards ?? [])
                                ->map(fn($code) => $wardMap[$code] ?? null)
                                ->filter()
                                ->implode(', ');

                            // Đường (lấy từ note: "... - Tên đường: X")
                            $streetName = '';
                            if ($lead->note && str_contains($lead->note, 'Tên đường:')) {
                                $streetName = trim(substr($lead->note, strpos($lead->note, 'Tên đường:') + strlen('Tên đường:')));
                            }

                            $statusLabel = $statusLabels[$lead->status] ?? $lead->status;
                            $rawStatus   = strtolower($lead->getRawOriginal('status'));
                        @endphp
                        <!-- bookings-item -->
                        <div class="col-md-6">
                            <div class="bookings-item fl-wrap">
                                <div class="bookings-item-header fl-wrap">
                                    <h4>{{ $lead->customer ? $lead->customer->full_name : 'Khách vãng lai' }}</h4>
                                    <span class="new-bookmark status-{{ $rawStatus }}">{{ $statusLabel }}</span>
                                </div>
                                <div class="bookings-item-content fl-wrap">
                                    <ul>
                                        @if($catNames)
                                        <li>Loại BĐS: <span>{{ $catNames }}</span></li>
                                        @endif
                                        <li>Nhu cầu: <span>{{ $lead->lead_type === 'Buy' ? 'Mua' : 'Thuê' }}</span></li>
                                        <li>Ngân sách: <span>{{ format_vnd($lead->demand_rate_min) }} – {{ format_vnd($lead->demand_rate_max) }}</span></li>
                                        @if($wardNames)
                                        <li>Khu vực: <span>{{ $wardNames }}</span></li>
                                        @endif
                                        @if($streetName)
                                        <li>Đường: <span>{{ $streetName }}</span></li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="bookings-item-footer fl-wrap">
                                    <div class="message-date">{{ $lead->created_at->format('d/m/Y') }}</div>
                                    <ul>
                                        <li><a href="tel:{{ $lead->customer ? $lead->customer->contact : '' }}"
                                                class="tolt" data-microtip-position="top-left"
                                                data-tooltip="Gọi điện"><i class="far fa-phone"></i></a></li>
                                        <li><a href="{{ route('webapp.leads.edit', $lead->id) }}" class="tolt"
                                                data-microtip-position="top-left"
                                                data-tooltip="Chỉnh sửa"><i class="far fa-edit"></i></a></li>
                                        <li>
                                            <form action="{{ route('webapp.leads.destroy', $lead->id) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa lead này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="tolt bookings-footer-btn"
                                                    data-microtip-position="top-left"
                                                    data-tooltip="Xóa"><i class="far fa-trash"></i></button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--bookings-item end-->
                        @empty
                        <div class="col-md-12">
                            <p>Không tìm thấy lead nào.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <!-- pagination-->
                <div class="pagination" style="display: flex; justify-content: center; margin-top: 30px; gap: 5px;">
                    {{ $leads->links('pagination::bootstrap-4') }}
                </div>
                <!-- pagination end-->
            </div>
        </div>
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>