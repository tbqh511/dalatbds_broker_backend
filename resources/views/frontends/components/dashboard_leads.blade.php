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
                <div class="dasboard-widget-title fl-wrap"
                    style="display: flex; align-items: center; justify-content: space-between;">
                    <h5><i class="fal fa-users"></i>Danh sách Lead</h5>
                    <a href="{{ route('webapp.leads.create') }}" class="mark-btn color-bg"
                        style="position: static; margin: 0; transform: none; display: flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; color: #fff; font-weight: 500; box-shadow: 0 4px 12px rgba(50, 112, 252, 0.3);">
                        <i class="fal fa-plus"></i> Thêm mới
                    </a>
                </div>
                <div class="dasboard-widget-box fl-wrap">
                    <div class="dasboard-opt fl-wrap">
                        <!-- price-opt-->
                        <div class="price-opt">
                            <span class="price-opt-title">Lọc theo trạng thái:</span>
                            <div class="listsearch-input-item">
                                <form action="{{ route('webapp.leads') }}" method="GET" id="filter-form">
                                    <select name="status" data-placeholder="Tất cả"
                                        class="chosen-select no-search-select" onchange="this.form.submit()">
                                        <option value="">Tất cả</option>
                                        <option value="new" {{ request('status')=='new' ? 'selected' : '' }}>Mới
                                        </option>
                                        <option value="contacted" {{ request('status')=='contacted' ? 'selected' : ''
                                            }}>Đã liên hệ</option>
                                        <option value="converted" {{ request('status')=='converted' ? 'selected' : ''
                                            }}>Đã chuyển đổi</option>
                                        <option value="lost" {{ request('status')=='lost' ? 'selected' : '' }}>Thất bại
                                        </option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <!-- price-opt end-->
                    </div>
                    <div class="row">
                        @forelse($leads as $lead)
                        <!-- bookings-item -->
                        <div class="col-md-6">
                            <div class="bookings-item fl-wrap">
                                <div class="bookings-item-header fl-wrap">
                                    <img src="{{ $lead->customer && $lead->customer->avatar ? asset($lead->customer->avatar) : asset('images/avatar/1.jpg') }}"
                                        onerror="this.onerror=null;this.src='{{ asset('images/avatar/1.jpg') }}';"
                                        alt="" width="60" height="60" style="object-fit: cover;">
                                    <h4>{{ $lead->customer ? $lead->customer->full_name : 'Khách vãng lai' }}</h4>
                                    <span class="new-bookmark status-{{ $lead->status }}">{{ $lead->status }}</span>
                                </div>
                                <div class="bookings-item-content fl-wrap">
                                    <ul>
                                        <li>Điện thoại: <span>{{ $lead->customer ? $lead->customer->contact : 'N/A'
                                                }}</span></li>
                                        <li>Loại nhu cầu: <span>{{ $lead->lead_type == 'buy' ? 'Mua' : 'Thuê' }}</span>
                                        </li>
                                        <li>Ngân sách: <span>{{ number_format($lead->demand_rate_min) }} - {{
                                                number_format($lead->demand_rate_max) }}</span></li>
                                        <li>Ngày tạo: <span>{{ $lead->created_at->format('d/m/Y H:i') }}</span></li>
                                    </ul>
                                    @if($lead->source_note)
                                    <p>Ghi chú: {{ $lead->source_note }}</p>
                                    @endif
                                </div>
                                <div class="bookings-item-footer fl-wrap">
                                    <ul
                                        style="display: flex; align-items: center; list-style: none; padding: 0; margin: 0;">
                                        <li style="margin-right: 10px;"><a
                                                href="tel:{{ $lead->customer ? $lead->customer->contact : '' }}"
                                                class="tolt" data-microtip-position="top-left" data-tooltip="Gọi điện"
                                                style="display: flex; align-items: center; justify-content: center;"><i
                                                    class="far fa-phone"></i></a></li>
                                        <li style="margin-right: 10px;"><a
                                                href="{{ route('webapp.leads.edit', $lead->id) }}" class="tolt"
                                                data-microtip-position="top-left" data-tooltip="Chỉnh sửa"
                                                style="display: flex; align-items: center; justify-content: center;"><i
                                                    class="far fa-edit"></i></a></li>
                                        <li>
                                            <form action="{{ route('webapp.leads.destroy', $lead->id) }}" method="POST"
                                                style="display:inline; margin: 0;"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa lead này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="tolt"
                                                    style="background:none;border:none;padding:0;color:#666; display: flex; align-items: center; justify-content: center;"
                                                    data-microtip-position="top-left" data-tooltip="Xóa"><i
                                                        class="far fa-trash"></i></button>
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
        <!-- dashboard-footer -->
        @include('components.dashboard.footer')
        <!-- dashboard-footer end -->
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>
</div>
<!-- pagination-->
<div class="pagination" style="display: flex; justify-content: center; margin-top: 30px; gap: 5px;">
    {{ $leads->links('pagination::bootstrap-4') }}
</div>
<!-- pagination end-->
</div>
</div>
<!-- dashboard-footer -->
@include('components.dashboard.footer')
<!-- dashboard-footer end -->
</div>
<div class="dashbard-bg gray-bg"></div>
</div>