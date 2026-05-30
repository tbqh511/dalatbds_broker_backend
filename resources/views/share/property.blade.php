<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>BĐS gửi tới {{ $customerName }} — DalatBDS</title>
    <style>
        :root { --primary:#2563eb; --bg:#f1f5f9; --card:#fff; --text:#1e293b; --muted:#64748b; --line:#e2e8f0; }
        * { box-sizing:border-box; margin:0; padding:0; -webkit-tap-highlight-color:transparent; }
        body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; background:var(--bg); color:var(--text); line-height:1.5; padding-bottom:40px; }
        .sp-header { background:var(--primary); color:#fff; padding:18px 16px 22px; text-align:center; }
        .sp-header h1 { font-size:17px; font-weight:700; }
        .sp-header p { font-size:13px; opacity:.9; margin-top:4px; }
        .sp-wrap { max-width:560px; margin:0 auto; padding:14px; }
        .sp-card { background:var(--card); border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,.08); margin-bottom:16px; }
        .sp-gallery { display:flex; overflow-x:auto; scroll-snap-type:x mandatory; gap:0; background:#0f172a; }
        .sp-gallery::-webkit-scrollbar { display:none; }
        .sp-gallery img { width:100%; flex:0 0 100%; height:230px; object-fit:cover; scroll-snap-align:start; }
        .sp-noimg { height:160px; display:flex; align-items:center; justify-content:center; background:#e2e8f0; color:var(--muted); font-size:14px; }
        .sp-body { padding:14px 16px; }
        .sp-title { font-size:16px; font-weight:700; margin-bottom:8px; }
        .sp-meta { display:flex; flex-wrap:wrap; gap:6px 14px; font-size:14px; color:var(--text); margin-bottom:10px; }
        .sp-meta .price { color:var(--primary); font-weight:700; }
        .sp-meta span { display:inline-flex; align-items:center; gap:4px; }
        .sp-desc { font-size:14px; color:var(--muted); white-space:pre-line; margin-bottom:12px; }
        .sp-section { font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.04em; margin:14px 0 8px; }
        .sp-legal-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
        .sp-legal-grid a { aspect-ratio:1; border-radius:10px; overflow:hidden; border:1px solid var(--line); display:block; }
        .sp-legal-grid img { width:100%; height:100%; object-fit:cover; }
        .sp-map { width:100%; height:200px; border:0; border-radius:12px; margin-top:4px; }
        .sp-map-link { display:inline-block; margin-top:8px; font-size:13px; color:var(--primary); text-decoration:none; }
        .sp-fb { display:flex; gap:8px; padding:14px 16px; border-top:1px solid var(--line); background:#fafbff; }
        .sp-fb button { flex:1; padding:11px 6px; border:1px solid var(--line); border-radius:10px; background:#fff; font-size:13px; font-weight:600; cursor:pointer; color:var(--text); display:flex; flex-direction:column; align-items:center; gap:3px; }
        .sp-fb button.done { background:#ecfdf5; border-color:#34d399; color:#065f46; }
        .sp-fb button.like:active { background:#fef2f2; }
        .sp-empty { text-align:center; padding:40px 20px; color:var(--muted); }
        .sp-foot { text-align:center; font-size:12px; color:var(--muted); padding:8px 0 0; }
        /* Bottom sheet */
        .sp-overlay { position:fixed; inset:0; background:rgba(0,0,0,.45); display:none; align-items:flex-end; z-index:50; }
        .sp-overlay.open { display:flex; }
        .sp-sheet { background:#fff; width:100%; max-width:560px; margin:0 auto; border-radius:18px 18px 0 0; padding:18px 16px calc(18px + env(safe-area-inset-bottom)); }
        .sp-sheet h3 { font-size:16px; margin-bottom:12px; }
        .sp-sheet textarea, .sp-sheet input { width:100%; border:1px solid var(--line); border-radius:10px; padding:10px; font-size:14px; margin-bottom:10px; font-family:inherit; }
        .sp-sheet-row { display:flex; gap:8px; }
        .sp-sheet-row > * { flex:1; }
        .sp-btn { width:100%; padding:12px; border:0; border-radius:10px; background:var(--primary); color:#fff; font-weight:700; font-size:15px; cursor:pointer; }
        .sp-btn.ghost { background:#f1f5f9; color:var(--text); margin-top:8px; }
        .sp-toast { position:fixed; bottom:24px; left:50%; transform:translateX(-50%); background:#1e293b; color:#fff; padding:11px 18px; border-radius:10px; font-size:14px; z-index:60; opacity:0; transition:opacity .25s; pointer-events:none; }
        .sp-toast.show { opacity:1; }
    </style>
</head>
<body>
    <div class="sp-header">
        <h1>Bất động sản dành cho {{ $customerName }}</h1>
        <p>Được gửi bởi môi giới DalatBDS</p>
    </div>

    <div class="sp-wrap">
        @forelse($properties as $p)
            <div class="sp-card" data-product="{{ $p['product_id'] }}">
                @php $imgs = !empty($p['gallery']) ? $p['gallery'] : ($p['title_image'] ? [$p['title_image']] : []); @endphp
                @if(count($imgs))
                    <div class="sp-gallery">
                        @foreach($imgs as $img)
                            <img src="{{ $img }}" loading="lazy" alt="{{ $p['title'] }}">
                        @endforeach
                    </div>
                @else
                    <div class="sp-noimg">Chưa có hình ảnh</div>
                @endif

                <div class="sp-body">
                    <div class="sp-title">{{ $p['title'] }}</div>
                    <div class="sp-meta">
                        @if($p['price'])<span class="price">💰 {{ $p['price'] }}</span>@endif
                        @if($p['area'])<span>📐 {{ $p['area'] }}</span>@endif
                        @if($p['location'])<span>📍 {{ $p['location'] }}</span>@endif
                    </div>
                    @if($p['description'])
                        <div class="sp-desc">{{ \Illuminate\Support\Str::limit($p['description'], 400) }}</div>
                    @endif

                    @if($p['show_legal'] && count($p['legal']))
                        <div class="sp-section">📄 Giấy tờ pháp lý</div>
                        <div class="sp-legal-grid">
                            @foreach($p['legal'] as $lg)
                                <a href="{{ $lg }}" target="_blank" rel="noopener"><img src="{{ $lg }}" loading="lazy" alt="Pháp lý"></a>
                            @endforeach
                        </div>
                    @endif

                    @if($p['latitude'] && $p['longitude'])
                        <div class="sp-section">🗺 Vị trí</div>
                        <iframe class="sp-map" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            src="https://maps.google.com/maps?q={{ $p['latitude'] }},{{ $p['longitude'] }}&z=16&output=embed"></iframe>
                        <a class="sp-map-link" href="https://www.google.com/maps?q={{ $p['latitude'] }},{{ $p['longitude'] }}" target="_blank" rel="noopener">Mở Google Maps →</a>
                    @endif
                </div>

                <div class="sp-fb">
                    <button class="like" onclick="sendFeedback({{ $p['product_id'] }}, 'interested', this)">❤️<span>Quan tâm</span></button>
                    <button onclick="openReason({{ $p['product_id'] }})">👎<span>Không hợp</span></button>
                    <button onclick="openBooking({{ $p['product_id'] }})">📅<span>Đặt lịch</span></button>
                </div>
            </div>
        @empty
            <div class="sp-empty">Hiện chưa có BĐS nào trong gói này.</div>
        @endforelse

        <div class="sp-foot">DalatBDS E-Broker · Mã: {{ $code }}</div>
    </div>

    {{-- Sheet: lý do không phù hợp --}}
    <div class="sp-overlay" id="reasonOverlay" onclick="if(event.target===this)closeSheet('reasonOverlay')">
        <div class="sp-sheet">
            <h3>Vì sao chưa phù hợp?</h3>
            <textarea id="reasonText" rows="3" placeholder="VD: giá cao, vị trí xa, diện tích nhỏ..."></textarea>
            <button class="sp-btn" id="reasonSubmit">Gửi phản hồi</button>
            <button class="sp-btn ghost" onclick="closeSheet('reasonOverlay')">Đóng</button>
        </div>
    </div>

    {{-- Sheet: đặt lịch xem --}}
    <div class="sp-overlay" id="bookingOverlay" onclick="if(event.target===this)closeSheet('bookingOverlay')">
        <div class="sp-sheet">
            <h3>Đặt lịch xem BĐS</h3>
            <div class="sp-sheet-row">
                <input type="date" id="bookDate">
                <input type="time" id="bookTime">
            </div>
            <input type="text" id="bookContact" placeholder="SĐT liên hệ (tuỳ chọn)">
            <button class="sp-btn" id="bookingSubmit">Xác nhận đặt lịch</button>
            <button class="sp-btn ghost" onclick="closeSheet('bookingOverlay')">Đóng</button>
        </div>
    </div>

    <div class="sp-toast" id="spToast"></div>

    <script>
        var CODE = @json($code);
        var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var _activeProduct = null;

        function spToast(msg) {
            var t = document.getElementById('spToast');
            t.textContent = msg; t.classList.add('show');
            setTimeout(function(){ t.classList.remove('show'); }, 2600);
        }
        function closeSheet(id){ document.getElementById(id).classList.remove('open'); }
        function openReason(pid){ _activeProduct = pid; document.getElementById('reasonText').value=''; document.getElementById('reasonOverlay').classList.add('open'); }
        function openBooking(pid){ _activeProduct = pid; document.getElementById('bookingOverlay').classList.add('open'); }

        function post(url, body) {
            return fetch(url, {
                method:'POST',
                headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'X-Requested-With':'XMLHttpRequest' },
                body: JSON.stringify(body || {})
            });
        }

        function markDone(card, label) {
            if (!card) return;
            var fb = card.querySelector('.sp-fb');
            if (fb) fb.innerHTML = '<button class="done" style="flex:1;cursor:default;">✓ '+label+'</button>';
        }

        function sendFeedback(pid, type, btn) {
            var card = btn ? btn.closest('.sp-card') : document.querySelector('.sp-card[data-product="'+pid+'"]');
            post('/s/'+CODE+'/feedback', { product_id:pid, type:type })
                .then(function(r){ return r.json(); })
                .then(function(d){
                    if (d.success) { spToast(d.message || 'Đã gửi phản hồi'); markDone(card, 'Đã quan tâm'); }
                    else spToast(d.message || 'Lỗi, thử lại');
                })
                .catch(function(){ spToast('Lỗi kết nối'); });
        }

        document.getElementById('reasonSubmit').addEventListener('click', function(){
            var pid = _activeProduct, reason = document.getElementById('reasonText').value.trim();
            var card = document.querySelector('.sp-card[data-product="'+pid+'"]');
            post('/s/'+CODE+'/feedback', { product_id:pid, type:'not_suitable', reason:reason })
                .then(function(r){ return r.json(); })
                .then(function(d){
                    closeSheet('reasonOverlay');
                    if (d.success) { spToast('Cảm ơn phản hồi!'); markDone(card, 'Đã ghi nhận'); }
                    else spToast(d.message || 'Lỗi, thử lại');
                })
                .catch(function(){ spToast('Lỗi kết nối'); });
        });

        document.getElementById('bookingSubmit').addEventListener('click', function(){
            var pid = _activeProduct;
            var card = document.querySelector('.sp-card[data-product="'+pid+'"]');
            var body = {
                product_id: pid, type:'book_viewing',
                booking_date: document.getElementById('bookDate').value || null,
                booking_time: document.getElementById('bookTime').value || null,
                contact: document.getElementById('bookContact').value.trim() || null
            };
            post('/s/'+CODE+'/feedback', body)
                .then(function(r){ return r.json(); })
                .then(function(d){
                    closeSheet('bookingOverlay');
                    if (d.success) { spToast('Đã đặt lịch xem!'); markDone(card, 'Đã đặt lịch'); }
                    else spToast(d.message || 'Lỗi, thử lại');
                })
                .catch(function(){ spToast('Lỗi kết nối'); });
        });

        // View beacon (idempotent server-side)
        post('/s/'+CODE+'/seen').catch(function(){});
    </script>
</body>
</html>
