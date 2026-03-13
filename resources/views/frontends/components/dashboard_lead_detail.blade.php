@php
    $statusLabels  = ['new'=>'Mới','contacted'=>'Đã liên hệ','converted'=>'Chuyển đổi','lost'=>'Thất bại'];
    $rawStatus     = strtolower($lead->getRawOriginal('status'));
    $catNames      = collect($lead->categories ?? [])->map(fn($id) => $categoryMap[$id] ?? null)->filter()->implode(', ');
    $wardNames     = collect($lead->wards ?? [])->map(fn($c) => $wardMap[$c] ?? null)->filter()->implode(', ');
    $street        = '';
    if ($lead->note && str_contains($lead->note, 'Tên đường:')) {
        $street = trim(substr($lead->note, strpos($lead->note, 'Tên đường:') + strlen('Tên đường:')));
    }
    $isSale      = $customer->isSale();
    $isSaleAdmin = $customer->isSaleAdmin();
    $isOwner     = $lead->user_id == $customer->id;
@endphp

<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            @include('components.dashboard.header', ['title' => 'Chi tiết Lead'])

            <div class="dasboard-wrapper fl-wrap"
                 x-data="leadDetailApp()"
                 x-init="init()">

                {{-- ── A. HEADER CARD ── --}}
                <div class="dasboard-widget-title fl-wrap">
                    <h5><i class="fal fa-user-circle"></i>
                        {{ $lead->customer?->full_name ?? 'Khách vãng lai' }}
                        <span class="new-bookmark status-{{ $rawStatus }}" style="margin-left:8px">
                            {{ $statusLabels[$rawStatus] ?? $lead->status }}
                        </span>
                    </h5>
                    <div class="lead-header-actions">
                        <a href="{{ route('webapp.leads') }}" class="btn-sm color-btn-light" style="margin-right:8px">
                            <i class="fal fa-arrow-left"></i> Quay lại
                        </a>
                        @if($isOwner || $isSaleAdmin)
                        <a href="{{ route('webapp.leads.edit', $lead->id) }}" class="btn-sm color-bg">
                            <i class="fal fa-edit"></i> Chỉnh sửa
                        </a>
                        @endif
                    </div>
                </div>

                {{-- ── B. THÔNG TIN NHU CẦU ── --}}
                <div class="dasboard-widget-box fl-wrap" style="margin-bottom:20px">
                    <div class="lead-detail-section-title"><i class="fal fa-clipboard-list"></i> Thông tin nhu cầu</div>
                    <ul class="lead-detail-info-list">
                        <li><span class="ldi-label">Nhu cầu:</span> <span>{{ $lead->lead_type === 'Buy' ? 'Mua' : 'Thuê' }}</span></li>
                        @if($catNames)
                        <li><span class="ldi-label">Loại BĐS:</span> <span>{{ $catNames }}</span></li>
                        @endif
                        <li><span class="ldi-label">Ngân sách:</span>
                            <span>{{ format_vnd($lead->demand_rate_min) }} – {{ format_vnd($lead->demand_rate_max) }}</span>
                        </li>
                        @if($wardNames)
                        <li><span class="ldi-label">Khu vực:</span> <span>{{ $wardNames }}</span></li>
                        @endif
                        @if($street)
                        <li><span class="ldi-label">Đường:</span> <span>{{ $street }}</span></li>
                        @endif
                        @if($lead->purpose)
                        <li><span class="ldi-label">Mục đích:</span> <span>{{ $lead->purpose }}</span></li>
                        @endif
                        @if($lead->source_note)
                        <li><span class="ldi-label">Ghi chú:</span> <span>{{ $lead->source_note }}</span></li>
                        @endif
                        <li><span class="ldi-label">Ngày tạo:</span> <span>{{ $lead->created_at->format('d/m/Y H:i') }}</span></li>
                        <li>
                            <span class="ldi-label">SĐT khách:</span>
                            <a href="tel:{{ $lead->customer?->contact }}" class="lead-call-btn"
                               @if($isSale) @click.prevent="logCallAndDial('{{ $lead->customer?->contact }}')" @endif>
                                <i class="fal fa-phone"></i> {{ $lead->customer?->contact ?? 'N/A' }}
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- ── C. TRẠNG THÁI & PHÂN CÔNG ── --}}
                <div class="dasboard-widget-box fl-wrap" style="margin-bottom:20px">
                    <div class="lead-detail-section-title"><i class="fal fa-tasks"></i> Trạng thái chăm sóc</div>

                    {{-- Status pills --}}
                    <div class="webapp-filter-pills" style="margin-bottom:16px">
                        @foreach(['new'=>'Mới','contacted'=>'Đã liên hệ','converted'=>'Chuyển đổi','lost'=>'Thất bại'] as $val => $lbl)
                        <button type="button"
                            :class="currentStatus === '{{ $val }}' ? 'filter-pill pill-{{ $val }} pill-active' : 'filter-pill pill-{{ $val }}'"
                            @click="updateStatus('{{ $val }}')">{{ $lbl }}</button>
                        @endforeach
                    </div>
                    <div x-show="statusUpdating" style="font-size:13px;color:#3270FC">
                        <i class="fal fa-spinner fa-spin"></i> Đang cập nhật…
                    </div>

                    {{-- Assignment (sale_admin only) --}}
                    @if($isSaleAdmin && $salesList->count())
                    <div class="lead-assign-wrap" style="margin-top:12px">
                        <span class="ldi-label" style="margin-right:8px">Phụ trách:</span>
                        <select x-model="selectedSale" @change="assignSale()" class="lead-assign-select">
                            <option value="">-- Chọn Sale --</option>
                            @foreach($salesList as $s)
                            <option value="{{ $s->id }}" {{ $lead->sale_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <span x-show="assignUpdating" style="font-size:13px;color:#3270FC;margin-left:8px">
                            <i class="fal fa-spinner fa-spin"></i>
                        </span>
                        <span x-show="assignSuccess" style="font-size:13px;color:green;margin-left:8px" x-text="'✓ Đã phân công cho ' + assignedName"></span>
                    </div>
                    @else
                    <div>
                        <span class="ldi-label">Phụ trách:</span>
                        <span>{{ $lead->sale?->name ?? 'Chưa phân công' }}</span>
                    </div>
                    @endif
                </div>

                {{-- ── D. LỊCH SỬ HOẠT ĐỘNG ── --}}
                <div class="dasboard-widget-box fl-wrap" style="margin-bottom:20px">
                    <div class="lead-detail-section-title"><i class="fal fa-history"></i> Lịch sử hoạt động</div>

                    <div class="lead-timeline" id="activity-timeline">
                        @forelse($lead->activities as $act)
                        <div class="lead-timeline-item">
                            <div class="lt-icon lt-icon-{{ $act->type }}">
                                <i class="fal {{ $act->getTypeIcon() }}"></i>
                            </div>
                            <div class="lt-body">
                                <div class="lt-header">
                                    <strong>{{ $act->getTypeLabel() }}</strong>
                                    <span class="lt-actor">{{ $act->actor?->name ?? '' }}</span>
                                    <span class="lt-time">{{ $act->created_at->format('H:i d/m/Y') }}</span>
                                </div>
                                @if($act->content)
                                <div class="lt-content">{{ $act->content }}</div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div style="color:#aaa;font-size:14px;padding:12px 0">Chưa có hoạt động nào.</div>
                        @endforelse

                        {{-- Alpine-rendered new activities --}}
                        <template x-for="act in newActivities" :key="act.id">
                            <div class="lead-timeline-item">
                                <div class="lt-icon" :class="'lt-icon-' + act.type">
                                    <i class="fal" :class="act.type_icon"></i>
                                </div>
                                <div class="lt-body">
                                    <div class="lt-header">
                                        <strong x-text="act.type_label"></strong>
                                        <span class="lt-actor" x-text="act.actor_name"></span>
                                        <span class="lt-time" x-text="act.time"></span>
                                    </div>
                                    <div class="lt-content" x-show="act.content" x-text="act.content"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Note input (sale/sale_admin) --}}
                    @if($isSale)
                    <div class="lead-note-form" style="margin-top:16px">
                        <textarea x-model="noteText" class="lead-note-input" placeholder="Thêm ghi chú…" rows="2"></textarea>
                        <button type="button" class="btn color-bg" style="margin-top:8px" @click="addNote()" :disabled="!noteText.trim()">
                            <i class="fal fa-plus"></i> Thêm ghi chú
                        </button>
                    </div>
                    @endif
                </div>

                {{-- ── E. TIẾN TRÌNH DEAL ── --}}
                <div class="dasboard-widget-box fl-wrap" style="margin-bottom:20px">
                    <div class="lead-detail-section-title"><i class="fal fa-handshake"></i> Tiến trình deal</div>

                    @if($lead->deal)
                        @forelse($lead->deal->products as $product)
                        <div class="deal-product-card">
                            <div class="dpc-header">
                                @if($product->property?->title_image)
                                <img src="{{ $product->property->title_image }}" alt="{{ $product->property->title }}" class="dpc-thumb">
                                @endif
                                <div class="dpc-info">
                                    <div class="dpc-title">{{ $product->property?->title ?? 'BĐS không còn tồn tại' }}</div>
                                    <span class="dpc-status">{{ $product->status?->label() ?? '' }}</span>
                                    @if($product->reason_dont_like)
                                    <div class="dpc-reason"><i class="fal fa-times-circle"></i> {{ $product->reason_dont_like }}</div>
                                    @endif
                                </div>
                            </div>

                            {{-- Bookings for this property --}}
                            @if($product->bookings->count())
                            <div class="dpc-bookings">
                                @foreach($product->bookings as $booking)
                                <div class="dpc-booking-item">
                                    <span class="dpc-booking-date">
                                        <i class="fal fa-calendar"></i>
                                        {{ $booking->booking_date?->format('d/m/Y') }} {{ $booking->booking_time }}
                                    </span>
                                    <span class="dpc-booking-status">{{ $booking->status?->label() ?? '' }}</span>
                                    @if($booking->customer_feedback)
                                    <div class="dpc-feedback"><i class="fal fa-comment"></i> {{ $booking->customer_feedback }}</div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @empty
                        <p style="color:#aaa;font-size:14px">Deal chưa có BĐS nào được thêm vào.</p>
                        @endforelse

                        @if($isSale)
                        <a href="#" style="font-size:13px;color:#3270FC">
                            <i class="fal fa-plus"></i> Thêm BĐS vào Deal
                        </a>
                        @endif

                    @else
                        <p style="color:#aaa;font-size:14px">Chưa có deal nào được tạo cho lead này.</p>

                        @if($isSale)
                        <button type="button" class="btn color-bg" @click="createDeal()" :disabled="dealCreating">
                            <i class="fal fa-plus"></i>
                            <span x-show="!dealCreating">Tạo Deal</span>
                            <span x-show="dealCreating"><i class="fal fa-spinner fa-spin"></i> Đang tạo…</span>
                        </button>
                        @endif
                    @endif
                </div>

            </div>{{-- end dasboard-wrapper --}}
        </div>
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>

{{-- Call outcome modal --}}
@if($isSale)
<div id="call-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:24px;width:90%;max-width:380px">
        <h5 style="margin-bottom:12px"><i class="fal fa-phone"></i> Kết quả cuộc gọi</h5>
        <textarea id="call-note-input" placeholder="Nhập kết quả gọi điện..." rows="3"
            style="width:100%;border:1px solid #ddd;border-radius:8px;padding:10px;font-size:14px;resize:none"></textarea>
        <div style="display:flex;gap:8px;margin-top:12px">
            <button onclick="submitCallNote()" class="btn color-bg" style="flex:1">Lưu</button>
            <button onclick="closeCallModal()" class="btn" style="flex:1;background:#eee">Bỏ qua</button>
        </div>
    </div>
</div>
@endif

<script>
function leadDetailApp() {
    return {
        currentStatus: '{{ $rawStatus }}',
        statusUpdating: false,
        selectedSale: '{{ $lead->sale_id ?? "" }}',
        assignUpdating: false,
        assignSuccess: false,
        assignedName: '',
        newActivities: [],
        noteText: '',
        dealCreating: false,
        pendingCallActivityId: null,

        init() {},

        async updateStatus(newStatus) {
            if (this.currentStatus === newStatus || this.statusUpdating) return;
            this.statusUpdating = true;
            try {
                await axios.patch('{{ route('webapp.leads.update-status', $lead->id) }}',
                    { status: newStatus },
                    { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } }
                );
                this.currentStatus = newStatus;
            } catch (e) { alert('Cập nhật thất bại'); }
            finally { this.statusUpdating = false; }
        },

        async assignSale() {
            if (!this.selectedSale || this.assignUpdating) return;
            this.assignUpdating = true;
            this.assignSuccess = false;
            try {
                const r = await axios.post('{{ route('webapp.leads.assign-sale', $lead->id) }}',
                    { sale_id: this.selectedSale },
                    { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } }
                );
                this.assignedName = r.data.sale_name;
                this.assignSuccess = true;
            } catch (e) { alert('Phân công thất bại'); }
            finally { this.assignUpdating = false; }
        },

        async addNote() {
            if (!this.noteText.trim()) return;
            try {
                const r = await axios.post('{{ route('webapp.leads.activities.store', $lead->id) }}',
                    { type: 'note', content: this.noteText },
                    { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } }
                );
                this.newActivities.push(r.data.activity);
                this.noteText = '';
            } catch (e) { alert('Lưu ghi chú thất bại'); }
        },

        async logCallAndDial(phone) {
            // Log the call attempt first
            try {
                const r = await axios.post('{{ route('webapp.leads.activities.store', $lead->id) }}',
                    { type: 'call', content: '' },
                    { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } }
                );
                this.pendingCallActivityId = r.data.activity?.id;
                this.newActivities.push(r.data.activity);
            } catch (e) {}

            // Open dialer
            window.location.href = 'tel:' + phone;

            // Show outcome modal after 4s
            setTimeout(() => showCallModal(), 4000);
        },

        async createDeal() {
            if (this.dealCreating) return;
            if (!confirm('Tạo deal từ lead này?')) return;
            this.dealCreating = true;
            try {
                const r = await axios.post('{{ route('webapp.leads.create-deal', $lead->id) }}',
                    {},
                    { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } }
                );
                if (r.data.success) {
                    location.reload();
                }
            } catch (e) {
                alert(e.response?.data?.message ?? 'Tạo deal thất bại');
                this.dealCreating = false;
            }
        },
    };
}

@if($isSale)
function showCallModal() {
    document.getElementById('call-modal').style.display = 'flex';
}
function closeCallModal() {
    document.getElementById('call-modal').style.display = 'none';
}
async function submitCallNote() {
    const note = document.getElementById('call-note-input').value.trim();
    if (!note) { closeCallModal(); return; }
    try {
        await axios.post('{{ route('webapp.leads.activities.store', $lead->id) }}',
            { type: 'call', content: note },
            { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } }
        );
    } catch (e) {}
    closeCallModal();
}
@endif
</script>
