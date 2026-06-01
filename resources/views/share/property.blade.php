@extends('frontends.master')

@section('title', 'Bất động sản dành cho ' . $customerName . ' — DalatBDS')
@section('meta_description', 'Danh sách bất động sản được môi giới DalatBDS chọn lọc và gửi tới ' . $customerName . '.')

{{-- Trang public gửi khách: không index, ẩn newsletter để gọn gàng. --}}
@section('robots', 'noindex, nofollow')
@section('hide_newsletter')@endsection

@push('styles')
<style>
    /* ── Share-package: tinh chỉnh nhẹ trên theme gốc, không đụng class dùng chung ── */
    .sp-map-empty { height: 100%; display: flex; align-items: center; justify-content: center; color: #fff; background: #2b3344; font-size: 15px; text-align: center; padding: 0 20px; }
    .sp-intro { margin-bottom: 18px; }
    .sp-intro p { color: #50596b; margin-top: 4px; }
    /* Hộp phản hồi của khách gắn dưới mỗi card */
    .sp-fb { display: flex; gap: 10px; padding: 14px 0 2px; border-top: 1px solid #eee; margin-top: 14px; }
    .sp-fb-btn { flex: 1; padding: 10px 6px; border: 1px solid #e3e3e3; border-radius: 8px; background: #fff; font-size: 13px; font-weight: 600; cursor: pointer; color: #50596b; display: flex; flex-direction: column; align-items: center; gap: 4px; transition: .2s; line-height: 1.2; }
    .sp-fb-btn i { font-size: 16px; }
    .sp-fb-btn:hover { border-color: #c7c7c7; }
    .sp-fb-btn.done { background: #ecfdf5; border-color: #34d399; color: #065f46; cursor: default; flex: 1 1 100%; flex-direction: row; }
    .sp-legal-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 8px; }
    .sp-legal-grid a { aspect-ratio: 1; border-radius: 8px; overflow: hidden; border: 1px solid #eee; display: block; }
    .sp-legal-grid img { width: 100%; height: 100%; object-fit: cover; }
    .sp-sec-title { font-size: 13px; font-weight: 700; color: #50596b; text-transform: uppercase; letter-spacing: .03em; margin: 16px 0 4px; }
    /* Bottom sheet phản hồi */
    .sp-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, .5); display: none; align-items: flex-end; justify-content: center; z-index: 1000; }
    .sp-overlay.open { display: flex; }
    .sp-sheet { background: #fff; width: 100%; max-width: 520px; border-radius: 16px 16px 0 0; padding: 22px 20px calc(22px + env(safe-area-inset-bottom)); }
    @media (min-width: 768px) { .sp-overlay { align-items: center; } .sp-sheet { border-radius: 14px; } }
    .sp-sheet h3 { font-size: 18px; margin-bottom: 14px; color: #334e6f; }
    .sp-sheet textarea, .sp-sheet input { width: 100%; border: 1px solid #e3e3e3; border-radius: 8px; padding: 11px; font-size: 14px; margin-bottom: 12px; font-family: inherit; }
    .sp-sheet-row { display: flex; gap: 10px; }
    .sp-sheet-row > * { flex: 1; }
    .sp-toast { position: fixed; bottom: 26px; left: 50%; transform: translateX(-50%); background: #334e6f; color: #fff; padding: 12px 20px; border-radius: 8px; font-size: 14px; z-index: 1100; opacity: 0; transition: opacity .25s; pointer-events: none; }
    .sp-toast.show { opacity: 1; }
    .sp-empty-wrap { text-align: center; padding: 60px 20px; color: #878C9F; }
</style>
@endpush

@section('content')
<div class="content">
    <!-- Map -->
    <div class="map-container fw-map big_map">
        <div id="map-main">
            @if($properties->whereNotNull('latitude')->isEmpty())
            <div class="sp-map-empty">Các bất động sản trong gói này chưa cập nhật toạ độ bản đồ.</div>
            @endif
        </div>
        <ul class="mapnavigation no-list-style">
            <li><a href="#" class="prevmap-nav mapnavbtn"><span><i class="fas fa-caret-left"></i></span></a></li>
            <li><a href="#" class="nextmap-nav mapnavbtn"><span><i class="fas fa-caret-right"></i></span></a></li>
        </ul>
        <div class="scrollContorl mapnavbtn tolt" data-microtip-position="top-left" data-tooltip="Bật cuộn bản đồ"><span><i class="fal fa-unlock"></i></span></div>
        <div class="map-close"><i class="fas fa-times"></i></div>
    </div>
    <!-- Map end -->

    <!-- breadcrumbs-->
    <div class="breadcrumbs fw-breadcrumbs smpar fl-wrap">
        <div class="container">
            <div class="breadcrumbs-list">
                <a href="{{ url('/') }}">Trang chủ</a><span>BĐS dành cho {{ $customerName }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumbs end -->

    <!-- section -->
    <section class="gray-bg small-padding">
        <div class="container">
            <!-- list-main-wrap-header-->
            <div class="list-main-wrap-header box-list-header fl-wrap sp-intro">
                <div class="list-main-wrap-title">
                    <h2>Bất động sản dành cho <span>{{ $customerName }}</span> <strong>{{ $properties->count() }}</strong></h2>
                    <p>Được môi giới DalatBDS chọn lọc &amp; gửi riêng tới bạn. Bấm vào điểm trên bản đồ hoặc địa chỉ để xem vị trí.</p>
                </div>
            </div>
            <!-- list-main-wrap-header end-->

            <!-- listing-item-wrap-->
            <div class="listing-item-container box-list_ic fl-wrap">
                @forelse($properties as $i => $p)
                @php
                    $imgs = !empty($p['gallery']) ? $p['gallery'] : ($p['title_image'] ? [$p['title_image']] : []);
                    $hasGeo = $p['latitude'] && $p['longitude'];
                @endphp
                <!-- listing-item -->
                <div class="listing-item has_one_column" data-product="{{ $p['product_id'] }}" id="prop-{{ $i + 1 }}">
                    <article class="geodir-category-listing fl-wrap">
                        <div class="geodir-category-img fl-wrap">
                            <a href="#prop-{{ $i + 1 }}" onclick="return false;" class="geodir-category-img_item">
                                <img src="{{ $imgs[0] ?? asset('images/all/1.jpg') }}" alt="{{ $p['title'] }}" loading="lazy">
                                <div class="overlay"></div>
                            </a>
                            <div class="geodir-category-location">
                                @if($hasGeo)
                                <a href="#{{ $i + 1 }}" class="map-item scroll-top-map tolt" data-microtip-position="top-left" data-tooltip="Xem trên bản đồ"><i class="fas fa-map-marker-alt"></i> {{ $p['location'] }}</a>
                                @else
                                <a href="#" onclick="return false;"><i class="fas fa-map-marker-alt"></i> {{ $p['location'] }}</a>
                                @endif
                            </div>
                            <ul class="list-single-opt_header_cat">
                                <li><a href="#" onclick="return false;" class="cat-opt blue-bg">{{ $p['type_label'] }}</a></li>
                                @if($p['category'])<li><a href="#" onclick="return false;" class="cat-opt color-bg">{{ $p['category'] }}</a></li>@endif
                            </ul>
                            <div class="geodir-category-listing_media-list">
                                <span><i class="fas fa-camera"></i> {{ count($imgs) }}</span>
                            </div>
                        </div>
                        <div class="geodir-category-content fl-wrap">
                            <h3 class="title-sin_item"><a href="#" onclick="return false;">{{ $p['title'] }}</a></h3>
                            <div class="geodir-category-content_price">{{ $p['price'] ?: 'Giá thỏa thuận' }}</div>
                            @if($p['description'])
                            <p>{{ \Illuminate\Support\Str::limit($p['description'], 260) }}</p>
                            @endif
                            <div class="geodir-category-content-details">
                                <ul>
                                    @if($p['area'])<li><i class="fas fa-arrows-alt"></i><span>{{ $p['area'] }}</span></li>@endif
                                    @if($p['bedrooms'])<li><i class="fas fa-door-open"></i><span>{{ $p['bedrooms'] }}</span></li>@endif
                                    @if($p['bathrooms'])<li><i class="fal fa-bath"></i><span>{{ $p['bathrooms'] }}</span></li>@endif
                                    @if($p['floors'])<li><i class="fas fa-layer-group"></i><span>{{ $p['floors'] }}</span></li>@endif
                                </ul>
                            </div>

                            @if($p['show_legal'] && count($p['legal']))
                            <div class="sp-sec-title"><i class="fal fa-file-alt"></i> Giấy tờ pháp lý</div>
                            <div class="sp-legal-grid">
                                @foreach($p['legal'] as $lg)
                                <a href="{{ $lg }}" target="_blank" rel="noopener"><img src="{{ $lg }}" loading="lazy" alt="Pháp lý"></a>
                                @endforeach
                            </div>
                            @endif

                            <!-- feedback -->
                            <div class="sp-fb">
                                <button type="button" class="sp-fb-btn" onclick="spFeedback({{ $p['product_id'] }}, 'interested', this)"><i class="fal fa-heart"></i>Quan tâm</button>
                                <button type="button" class="sp-fb-btn" onclick="spOpenReason({{ $p['product_id'] }})"><i class="fal fa-thumbs-down"></i>Chưa hợp</button>
                                <button type="button" class="sp-fb-btn" onclick="spOpenBooking({{ $p['product_id'] }})"><i class="fal fa-calendar-alt"></i>Đặt lịch</button>
                            </div>
                            <!-- feedback end -->
                        </div>
                    </article>
                </div>
                <!-- listing-item end-->
                @empty
                <div class="sp-empty-wrap">
                    <h3>Chưa có bất động sản nào trong gói này.</h3>
                    <p>Vui lòng liên hệ lại môi giới của bạn.</p>
                </div>
                @endforelse
            </div>
            <!-- listing-item-wrap end-->
        </div>
    </section>
    <!-- section end -->
    <div class="limit-box fl-wrap"></div>
</div>

{{-- Sheet: lý do chưa phù hợp --}}
<div class="sp-overlay" id="spReasonOverlay" onclick="if(event.target===this)spCloseSheet('spReasonOverlay')">
    <div class="sp-sheet">
        <h3>Vì sao chưa phù hợp?</h3>
        <textarea id="spReasonText" rows="3" placeholder="VD: giá cao, vị trí xa, diện tích nhỏ..."></textarea>
        <button type="button" class="btn float-btn color-bg fw-btn" id="spReasonSubmit">Gửi phản hồi</button>
        <button type="button" class="btn float-btn fw-btn" style="background:#eef0f4;color:#50596b;margin-top:8px;" onclick="spCloseSheet('spReasonOverlay')">Đóng</button>
    </div>
</div>

{{-- Sheet: đặt lịch xem --}}
<div class="sp-overlay" id="spBookingOverlay" onclick="if(event.target===this)spCloseSheet('spBookingOverlay')">
    <div class="sp-sheet">
        <h3>Đặt lịch xem bất động sản</h3>
        <div class="sp-sheet-row">
            <input type="date" id="spBookDate">
            <input type="time" id="spBookTime">
        </div>
        <input type="text" id="spBookContact" placeholder="SĐT liên hệ (tuỳ chọn)">
        <button type="button" class="btn float-btn color-bg fw-btn" id="spBookingSubmit">Xác nhận đặt lịch</button>
        <button type="button" class="btn float-btn fw-btn" style="background:#eef0f4;color:#50596b;margin-top:8px;" onclick="spCloseSheet('spBookingOverlay')">Đóng</button>
    </div>
</div>

<div class="sp-toast" id="spToast"></div>
@endsection

@push('scripts')
{{-- Plugin map legacy (InfoBox + cluster) dùng chung với layout listing của theme --}}
<script src="{{ asset('js/map-plugins.js') }}"></script>
<script>
    window.SP_SHARE = {
        code: @json($code),
        markerIcon: @json(asset('images/marker-single.png')),
        properties: @json($mapMarkers),
    };
</script>
<script src="{{ asset('js/share-map.js') }}"></script>
<script>
    (function () {
        var CODE = window.SP_SHARE.code;
        var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var activeProduct = null;

        function toast(msg) {
            var t = document.getElementById('spToast');
            t.textContent = msg; t.classList.add('show');
            setTimeout(function () { t.classList.remove('show'); }, 2600);
        }
        function post(url, body) {
            return fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify(body || {})
            }).then(function (r) { return r.json(); });
        }
        function markDone(card, label) {
            if (!card) return;
            var fb = card.querySelector('.sp-fb');
            if (fb) fb.innerHTML = '<button type="button" class="sp-fb-btn done"><i class="fal fa-check"></i> ' + label + '</button>';
        }

        window.spCloseSheet = function (id) { document.getElementById(id).classList.remove('open'); };
        window.spOpenReason = function (pid) { activeProduct = pid; document.getElementById('spReasonText').value = ''; document.getElementById('spReasonOverlay').classList.add('open'); };
        window.spOpenBooking = function (pid) { activeProduct = pid; document.getElementById('spBookingOverlay').classList.add('open'); };

        window.spFeedback = function (pid, type, btn) {
            var card = btn ? btn.closest('.listing-item') : document.querySelector('.listing-item[data-product="' + pid + '"]');
            post('/s/' + CODE + '/feedback', { product_id: pid, type: type })
                .then(function (d) {
                    if (d.success) { toast(d.message || 'Đã gửi phản hồi'); markDone(card, 'Đã quan tâm'); }
                    else toast(d.message || 'Lỗi, thử lại');
                })
                .catch(function () { toast('Lỗi kết nối'); });
        };

        document.getElementById('spReasonSubmit').addEventListener('click', function () {
            var pid = activeProduct, reason = document.getElementById('spReasonText').value.trim();
            var card = document.querySelector('.listing-item[data-product="' + pid + '"]');
            post('/s/' + CODE + '/feedback', { product_id: pid, type: 'not_suitable', reason: reason })
                .then(function (d) {
                    spCloseSheet('spReasonOverlay');
                    if (d.success) { toast('Cảm ơn phản hồi!'); markDone(card, 'Đã ghi nhận'); }
                    else toast(d.message || 'Lỗi, thử lại');
                })
                .catch(function () { toast('Lỗi kết nối'); });
        });

        document.getElementById('spBookingSubmit').addEventListener('click', function () {
            var pid = activeProduct;
            var card = document.querySelector('.listing-item[data-product="' + pid + '"]');
            post('/s/' + CODE + '/feedback', {
                product_id: pid, type: 'book_viewing',
                booking_date: document.getElementById('spBookDate').value || null,
                booking_time: document.getElementById('spBookTime').value || null,
                contact: document.getElementById('spBookContact').value.trim() || null
            })
                .then(function (d) {
                    spCloseSheet('spBookingOverlay');
                    if (d.success) { toast('Đã đặt lịch xem!'); markDone(card, 'Đã đặt lịch'); }
                    else toast(d.message || 'Lỗi, thử lại');
                })
                .catch(function () { toast('Lỗi kết nối'); });
        });

        // Beacon: ghi nhận khách đã mở link (idempotent phía server)
        post('/s/' + CODE + '/seen').catch(function () { });
    })();
</script>
@endpush
