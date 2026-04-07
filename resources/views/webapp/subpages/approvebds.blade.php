<!-- ========== SUBPAGE: DUYỆT BĐS ========== -->
<div class="subpage" id="subpage-approvebds">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('approvebds')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg> Duyệt BĐS</span></div>
    <div class="sp-actions">
      <button class="sp-action-btn" onclick="loadApprovalBds(true)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></button>
    </div>
  </div>

  <div class="admin-hero blue-grad">
    <div class="ah-label">HÀNG CHỜ DUYỆT — OPERATOR</div>
    <div class="ah-main"><span id="abdsHeroMain">— BĐS chờ xem xét</span></div>
    <div class="ah-grid">
      <div class="ah-stat ah-stat--clickable ah-stat--active" data-tab="pending" onclick="switchAbdsStatTab('pending',this)"><div class="ah-stat-val" id="abdsPendingCount">—</div><div class="ah-stat-lbl">Chờ duyệt</div></div>
      <div class="ah-stat ah-stat--clickable" data-tab="approved_today" onclick="switchAbdsStatTab('approved_today',this)"><div class="ah-stat-val" id="abdsApprovedToday">—</div><div class="ah-stat-lbl">Hôm nay</div></div>
      {{-- Tab "Đã duyệt" — chỉ hiện cho Admin (dashboard quản lý) --}}
      @if(auth('webapp')->user()?->role === 'admin')
      <div class="ah-stat ah-stat--clickable" data-tab="approved" onclick="switchAbdsStatTab('approved',this)"><div class="ah-stat-val" id="abdsTotalApproved">—</div><div class="ah-stat-lbl">Đã duyệt</div></div>
      @endif
      <div class="ah-stat ah-stat--clickable" data-tab="rejected" onclick="switchAbdsStatTab('rejected',this)"><div class="ah-stat-val" id="abdsRejectedCount">—</div><div class="ah-stat-lbl">Từ chối</div></div>
    </div>
  </div>

  {{-- ── FILTER BAR: Chỉ hiện cho Admin, chỉ khi ở tab "Đã duyệt" ── --}}
  @if(auth('webapp')->user()?->role === 'admin')
  <div id="abdsApprovedFilterBar" style="display:none; background:var(--bg-card); border-bottom:1px solid var(--border);">

    {{-- Hàng 1: Loại giao dịch --}}
    <div class="filter-bar" style="padding:8px 16px 0;">
      <div class="chip active" onclick="abdsSetFilter('property_type','',this)">Tất cả</div>
      <div class="chip" onclick="abdsSetFilter('property_type','0',this)">Bán</div>
      <div class="chip" onclick="abdsSetFilter('property_type','1',this)">Cho thuê</div>
    </div>

    {{-- Hàng 2: Khoảng giá --}}
    <div class="filter-bar" style="padding:4px 16px 0;">
      <div class="chip active" onclick="abdsSetFilter('price_range','',this)">Tất cả giá</div>
      <div class="chip" onclick="abdsSetFilter('price_range','under1b',this)">Dưới 1 tỷ</div>
      <div class="chip" onclick="abdsSetFilter('price_range','1to2b',this)">1–2 tỷ</div>
      <div class="chip" onclick="abdsSetFilter('price_range','2to3b',this)">2–3 tỷ</div>
      <div class="chip" onclick="abdsSetFilter('price_range','3to5b',this)">3–5 tỷ</div>
      <div class="chip" onclick="abdsSetFilter('price_range','5to10b',this)">5–10 tỷ</div>
      <div class="chip" onclick="abdsSetFilter('price_range','over10b',this)">Trên 10 tỷ</div>
    </div>

    {{-- Hàng 3: Khu vực (phường) — lấy từ DB, ưu tiên các phường trung tâm --}}
    <div class="filter-bar" style="padding:4px 16px 8px;">
      <div class="chip active" onclick="abdsSetFilter('ward_id','',this)">Tất cả khu vực</div>
      @php
        $abdsWards = \App\Models\LocationsWard::where('district_code', config('location.district_code'))
          ->orderByRaw("FIELD(code,'24796','24790','24778','24769','24811')")
          ->get();
      @endphp
      @foreach($abdsWards as $w)
      <div class="chip" onclick="abdsSetFilter('ward_id','{{ $w->code }}',this)">{{ trim($w->full_name) }}</div>
      @endforeach
    </div>

  </div>
  @endif

  <div class="sp-scroll" style="padding-bottom:16px;">
    <div id="abdsListContainer"></div>
    <div style="height:16px;"></div>
  </div>

  <!-- Reject sheet -->
  <div class="reject-sheet" id="rejectSheet">
    <div class="reject-sheet-inner">
      <div class="rs-handle"></div>
      <div class="rs-title">✕ Lý do từ chối / Yêu cầu bổ sung</div>
      <div class="rs-reasons">
        <div class="rs-reason" data-reason="Thiếu giấy tờ pháp lý (sổ đỏ/hồng)" onclick="selectRejectReason(this)">
          <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
          <span class="rs-reason-text">Thiếu giấy tờ pháp lý (sổ đỏ/hồng)</span>
        </div>
        <div class="rs-reason" data-reason="Ảnh không đủ / chất lượng kém" onclick="selectRejectReason(this)">
          <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></span>
          <span class="rs-reason-text">Ảnh không đủ / chất lượng kém</span>
        </div>
        <div class="rs-reason" data-reason="Thông tin vị trí không chính xác" onclick="selectRejectReason(this)">
          <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
          <span class="rs-reason-text">Thông tin vị trí không chính xác</span>
        </div>
        <div class="rs-reason" data-reason="Giá bất hợp lý / không thực tế" onclick="selectRejectReason(this)">
          <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
          <span class="rs-reason-text">Giá bất hợp lý / không thực tế</span>
        </div>
        <div class="rs-reason" data-reason="Đất trong vùng tranh chấp / quy hoạch đặc biệt" onclick="selectRejectReason(this)">
          <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
          <span class="rs-reason-text">Đất trong vùng tranh chấp / quy hoạch đặc biệt</span>
        </div>
        <div class="rs-reason" data-reason="Khác" onclick="selectRejectReason(this)">
          <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span>
          <span class="rs-reason-text">Khác (ghi rõ bên dưới)</span>
        </div>
      </div>
      <textarea id="rsNoteText" class="rs-note" rows="2" placeholder="Ghi chú thêm cho Broker (tùy chọn)..."></textarea>
      <button class="rs-submit" onclick="submitReject()">Gửi yêu cầu bổ sung → Broker</button>
    </div>
  </div>

  {{-- ── ADMIN-ONLY MODALS ── --}}
  @if(auth('webapp')->user()?->role === 'admin')

  {{-- Bottom sheet xác nhận ẩn BĐS --}}
  <div id="abdsHideSheet" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5);" onclick="if(event.target===this)closeAbdsHideSheet()">
    <div style="position:absolute;bottom:0;left:0;right:0;background:var(--bg-card);border-radius:20px 20px 0 0;padding:20px 16px 32px;">
      <div style="width:36px;height:4px;background:var(--border);border-radius:2px;margin:0 auto 16px;"></div>
      <div style="font-weight:700;font-size:15px;margin-bottom:6px;">Xác nhận ẩn BĐS</div>
      <div id="abdsHideSheetTitle" style="font-size:13px;color:var(--text-secondary);margin-bottom:16px;line-height:1.4;"></div>
      <div style="font-size:12px;color:var(--text-tertiary);background:var(--bg-surface);border-radius:10px;padding:10px 12px;margin-bottom:16px;line-height:1.5;">
        BĐS sẽ chuyển về hàng chờ duyệt và <strong>không còn hiển thị</strong> trên trang công khai cho đến khi được duyệt lại.
      </div>
      <div style="display:flex;gap:10px;">
        <button onclick="closeAbdsHideSheet()" style="flex:1;padding:12px;border:1.5px solid var(--border);border-radius:12px;font-size:14px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);cursor:pointer;">Huỷ</button>
        <button id="abdsHideConfirmBtn" onclick="adminAbdsHideExecute()" style="flex:1;padding:12px;border:none;border-radius:12px;font-size:14px;font-weight:600;color:#fff;background:var(--danger);cursor:pointer;">Xác nhận ẩn</button>
      </div>
    </div>
  </div>

  {{-- Bottom sheet thông tin liên hệ --}}
  <div id="abdsContactSheet" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5);" onclick="if(event.target===this)document.getElementById('abdsContactSheet').style.display='none'">
    <div style="position:absolute;bottom:0;left:0;right:0;background:var(--bg-card);border-radius:20px 20px 0 0;padding:20px 16px 32px;">
      <div style="width:36px;height:4px;background:var(--border);border-radius:2px;margin:0 auto 16px;"></div>
      <div style="font-weight:700;font-size:15px;margin-bottom:14px;">Liên hệ đối tượng liên quan</div>
      <div id="abdsContactContent"></div>
      <button onclick="document.getElementById('abdsContactSheet').style.display='none'"
        style="width:100%;padding:12px;border:1.5px solid var(--border);border-radius:12px;font-size:14px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);margin-top:12px;cursor:pointer;">Đóng</button>
    </div>
  </div>

  @endif

</div><!-- end subpage-approvebds -->

@if(auth('webapp')->user()?->role === 'admin')
@push('scripts')
<script>
// ════════════════════════════════════════════════════════════════
//  ADMIN — Bộ lọc tab "Đã Duyệt"
// ════════════════════════════════════════════════════════════════

// Lưu ID BĐS đang chờ xác nhận ẩn
var _abdsHidePendingId = null;

/**
 * Cập nhật một filter key và reload danh sách.
 * Được gọi bởi các chip trong filter bar.
 */
window.abdsSetFilter = function(key, val, chipEl) {
  // Đảm bảo _abdsFilters tồn tại (khai báo trong webapp-v2.js)
  if (typeof _abdsFilters === 'undefined') return;
  _abdsFilters[key] = val;

  // Cập nhật trạng thái active của chip trong cùng hàng
  if (chipEl) {
    var row = chipEl.closest('.filter-bar');
    if (row) {
      row.querySelectorAll('.chip').forEach(function(c) { c.classList.remove('active'); });
      chipEl.classList.add('active');
    }
  }

  // Reload danh sách với filter mới
  if (typeof loadApprovalBds === 'function') loadApprovalBds(true);
};

// ════════════════════════════════════════════════════════════════
//  ADMIN — Ẩn BĐS (Xác nhận + Gọi API)
// ════════════════════════════════════════════════════════════════

/**
 * Mở bottom sheet xác nhận trước khi ẩn BĐS.
 * @param {number} id    - ID của BĐS cần ẩn
 * @param {string} title - Tên BĐS (hiển thị trong confirm)
 */
window.adminAbdsHideConfirm = function(id, title) {
  _abdsHidePendingId = id;
  var titleEl = document.getElementById('abdsHideSheetTitle');
  if (titleEl) titleEl.textContent = '"' + title + '"';
  document.getElementById('abdsHideSheet').style.display = 'block';
};

/** Đóng sheet xác nhận ẩn, reset state. */
window.closeAbdsHideSheet = function() {
  document.getElementById('abdsHideSheet').style.display = 'none';
  _abdsHidePendingId = null;
};

/** Thực thi ẩn BĐS — gọi API sau khi admin xác nhận. */
window.adminAbdsHideExecute = function() {
  if (!_abdsHidePendingId) return;

  var btn = document.getElementById('abdsHideConfirmBtn');
  if (btn) { btn.disabled = true; btn.textContent = 'Đang xử lý...'; }

  var hiddenId = _abdsHidePendingId;
  var base = (window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes.adminPropertiesBase)
    || '/webapp/api/admin/properties/';

  fetch(base + hiddenId + '/hide', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': window.WEBAPP_CONFIG.csrfToken,
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
  })
  .then(function(r) { return r.json(); })
  .then(function(data) {
    closeAbdsHideSheet();
    if (data.success) {
      // Xoá card khỏi danh sách với animation mờ dần
      var card = document.getElementById('abds-' + hiddenId);
      if (card) {
        card.style.transition = 'opacity .3s';
        card.style.opacity = '0';
        setTimeout(function() { if (card.parentNode) card.parentNode.removeChild(card); }, 320);
      }
      showToast('✓ Đã ẩn BĐS — chuyển về hàng chờ duyệt');
      // Cập nhật badge đếm trên hero
      if (data.total_approved !== undefined) {
        var elTotal = document.getElementById('abdsTotalApproved');
        if (elTotal) elTotal.textContent = data.total_approved;
      }
      if (data.pending_count !== undefined) {
        var elPending = document.getElementById('abdsPendingCount');
        if (elPending) elPending.textContent = data.pending_count;
        var elHero = document.getElementById('abdsHeroMain');
        if (elHero) elHero.textContent = data.pending_count + ' BĐS chờ xem xét';
      }
    } else {
      showToast(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
    }
    if (btn) { btn.disabled = false; btn.textContent = 'Xác nhận ẩn'; }
  })
  .catch(function() {
    closeAbdsHideSheet();
    showToast('Lỗi kết nối. Vui lòng thử lại.');
    if (btn) { btn.disabled = false; btn.textContent = 'Xác nhận ẩn'; }
  });
};

// ════════════════════════════════════════════════════════════════
//  ADMIN — Hiển thị thông tin liên hệ (Host + Broker)
// ════════════════════════════════════════════════════════════════

/**
 * Mở bottom sheet hiển thị thông tin host và broker của BĐS.
 * @param {string} hostName    - Tên chủ nhà
 * @param {string} hostContact - SĐT chủ nhà
 * @param {string} brokerName  - Tên môi giới
 * @param {string} brokerPhone - SĐT môi giới
 */
window.adminAbdsContact = function(hostName, hostContact, brokerName, brokerPhone) {
  var html = '';

  // Thông tin chủ nhà / host
  if (hostName || hostContact) {
    html += '<div style="margin-bottom:10px;padding:10px 12px;background:var(--bg-surface);border-radius:10px;">'
      + '<div style="font-size:10px;font-weight:700;letter-spacing:.5px;color:var(--text-tertiary);margin-bottom:5px;">CHỦ NHÀ / HOST</div>'
      + (hostName ? '<div style="font-weight:600;font-size:14px;color:var(--text-primary);">' + hostName + '</div>' : '')
      + (hostContact
          ? '<a href="tel:' + hostContact + '" style="display:inline-flex;align-items:center;gap:5px;color:var(--primary);font-size:13px;margin-top:5px;text-decoration:none;">'
            + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6 6l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>'
            + hostContact + '</a>'
          : '')
      + '</div>';
  }

  // Thông tin môi giới / broker
  if (brokerName || brokerPhone) {
    html += '<div style="padding:10px 12px;background:var(--bg-surface);border-radius:10px;">'
      + '<div style="font-size:10px;font-weight:700;letter-spacing:.5px;color:var(--text-tertiary);margin-bottom:5px;">MÔI GIỚI / BROKER</div>'
      + (brokerName ? '<div style="font-weight:600;font-size:14px;color:var(--text-primary);">' + brokerName + '</div>' : '')
      + (brokerPhone
          ? '<a href="tel:' + brokerPhone + '" style="display:inline-flex;align-items:center;gap:5px;color:var(--primary);font-size:13px;margin-top:5px;text-decoration:none;">'
            + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6 6l.96-.96a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>'
            + brokerPhone + '</a>'
          : '')
      + '</div>';
  }

  // Fallback nếu không có thông tin liên hệ nào
  if (!html) {
    html = '<div style="color:var(--text-tertiary);text-align:center;padding:20px 0;font-size:13px;">Chưa có thông tin liên hệ.</div>';
  }

  document.getElementById('abdsContactContent').innerHTML = html;
  document.getElementById('abdsContactSheet').style.display = 'block';
};
</script>
@endpush
@endif
