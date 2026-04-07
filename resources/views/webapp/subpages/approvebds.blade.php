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
      {{-- Tab "Đã duyệt" — CHỈ DÀNH CHO ADMIN --}}
      @if(isset($customer) && $customer->role === 'admin')
      <div class="ah-stat ah-stat--clickable" data-tab="approved" onclick="switchAbdsStatTab('approved',this)"><div class="ah-stat-val" id="abdsTotalApproved">—</div><div class="ah-stat-lbl">Đã duyệt</div></div>
      @endif
      <div class="ah-stat ah-stat--clickable" data-tab="rejected" onclick="switchAbdsStatTab('rejected',this)"><div class="ah-stat-val" id="abdsRejectedCount">—</div><div class="ah-stat-lbl">Từ chối</div></div>
    </div>
  </div>

  {{-- ===== FILTER BAR CHO TAB "ĐÃ DUYỆT" — CHỈ ADMIN ===== --}}
  @if(isset($customer) && $customer->role === 'admin')
  <div id="abdsApprovedFilter" style="display:none;background:var(--bg-primary,#fff);border-bottom:1px solid var(--border,#e5e7eb);">

    {{-- Thanh tìm kiếm --}}
    <div style="padding:10px 12px 6px;display:flex;align-items:center;gap:8px;">
      <div style="flex:1;display:flex;align-items:center;gap:8px;background:var(--bg-secondary,#f3f4f6);border-radius:10px;padding:7px 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;color:var(--text-tertiary);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input id="abdsApprovedSearch" type="text" placeholder="Tìm BĐS, địa chỉ, môi giới..."
          style="border:none;background:none;outline:none;flex:1;font-size:13px;color:var(--text-primary);"
          oninput="abdsApprovedFilterApply()" />
        <button onclick="document.getElementById('abdsApprovedSearch').value='';abdsApprovedFilterApply();"
          style="background:none;border:none;color:var(--text-tertiary);cursor:pointer;padding:0;line-height:1;flex-shrink:0;">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </div>

    {{-- Chips lọc loại BĐS --}}
    <div class="filter-bar" style="padding:0 12px 4px;gap:6px;">
      <div class="chip active abds-cat-chip" data-filter="category" data-value="" onclick="abdsChipSelect(this,'abds-cat-chip')">Tất cả loại</div>
      @foreach(\App\Models\Category::where('status',1)->orderBy('order')->get() as $cat)
      <div class="chip abds-cat-chip" data-filter="category" data-value="{{ $cat->category }}" onclick="abdsChipSelect(this,'abds-cat-chip')">{{ $cat->category }}</div>
      @endforeach
    </div>

    {{-- Chips lọc giá --}}
    <div class="filter-bar" style="padding:0 12px 4px;gap:6px;">
      <div class="chip active abds-price-chip" data-filter="price" data-value="" onclick="abdsChipSelect(this,'abds-price-chip')">Tất cả giá</div>
      <div class="chip abds-price-chip" data-filter="price" data-value="lt1" onclick="abdsChipSelect(this,'abds-price-chip')">Dưới 1 tỷ</div>
      <div class="chip abds-price-chip" data-filter="price" data-value="1-2" onclick="abdsChipSelect(this,'abds-price-chip')">1–2 tỷ</div>
      <div class="chip abds-price-chip" data-filter="price" data-value="2-3" onclick="abdsChipSelect(this,'abds-price-chip')">2–3 tỷ</div>
      <div class="chip abds-price-chip" data-filter="price" data-value="3-5" onclick="abdsChipSelect(this,'abds-price-chip')">3–5 tỷ</div>
      <div class="chip abds-price-chip" data-filter="price" data-value="5-10" onclick="abdsChipSelect(this,'abds-price-chip')">5–10 tỷ</div>
      <div class="chip abds-price-chip" data-filter="price" data-value="gt10" onclick="abdsChipSelect(this,'abds-price-chip')">Trên 10 tỷ</div>
    </div>

    {{-- Chips lọc phường/xã --}}
    <div class="filter-bar" style="padding:0 12px 8px;gap:6px;">
      <div class="chip active abds-ward-chip" data-filter="ward" data-value="" onclick="abdsChipSelect(this,'abds-ward-chip')">Tất cả khu vực</div>
      @php
        $abdsWards = \App\Models\LocationsWard::where('district_code', config('location.district_code'))
          ->orderByRaw("FIELD(code,'24796','24790','24778','24769','24811')")
          ->get();
      @endphp
      @foreach($abdsWards as $w)
      <div class="chip abds-ward-chip" data-filter="ward" data-value="{{ trim($w->full_name) }}" onclick="abdsChipSelect(this,'abds-ward-chip')">{{ trim($w->full_name) }}</div>
      @endforeach
    </div>

  </div>
  @endif

  <div class="sp-scroll" style="padding-bottom:16px;">
    <div id="abdsListContainer"></div>
    {{-- Thông báo khi filter không có kết quả --}}
    <div id="abdsFilterEmptyMsg" style="display:none;text-align:center;padding:40px 16px;color:var(--text-tertiary);">
      <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.35;margin-bottom:10px;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <div style="font-size:14px;font-weight:500;">Không tìm thấy BĐS nào</div>
      <div style="font-size:12px;margin-top:4px;">Thử thay đổi từ khoá hoặc bộ lọc</div>
    </div>
    <div style="height:16px;"></div>
  </div>

  {{-- ===== MODAL: ĐỐI TƯỢNG LIÊN QUAN — CHỈ ADMIN ===== --}}
  @if(isset($customer) && $customer->role === 'admin')
  <div id="abdsRelatedModal"
    style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.45);-webkit-backdrop-filter:blur(2px);backdrop-filter:blur(2px);"
    onclick="if(event.target===this)closeAbdsRelatedModal()">
    <div style="position:absolute;bottom:0;left:0;right:0;background:#fff;border-radius:18px 18px 0 0;max-height:75vh;overflow-y:auto;">
      {{-- Handle --}}
      <div style="text-align:center;padding:12px 0 2px;">
        <div style="width:40px;height:4px;background:var(--border,#e5e7eb);border-radius:2px;display:inline-block;"></div>
      </div>
      <div style="padding:12px 16px 4px;">
        <div style="font-size:15px;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:7px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          Đối tượng liên quan
        </div>
      </div>
      <div style="padding:10px 16px 16px;" id="abdsRelatedContent"></div>
      <div style="padding:0 16px 28px;">
        <button onclick="closeAbdsRelatedModal()"
          style="width:100%;padding:12px;background:var(--bg-secondary,#f3f4f6);border:none;border-radius:12px;font-size:14px;font-weight:500;color:var(--text-secondary);cursor:pointer;">
          Đóng
        </button>
      </div>
    </div>
  </div>
  @endif

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
</div><!-- end subpage-approvebds -->

@if(isset($customer) && $customer->role === 'admin')
<script>
// ============================================================
// JS CHO TAB "ĐÃ DUYỆT" — CHỈ DÀNH CHO ADMIN
// ============================================================

// --- Trạng thái bộ lọc hiện tại ---
var _abdsActiveCategory = '';
var _abdsActivePrice    = '';
var _abdsActiveWard     = '';

// --- Cache dữ liệu card để filter client-side (nạp khi render card) ---
if(typeof window._abdsCardData === 'undefined') {
  window._abdsCardData = {};
}

/**
 * Chọn chip bộ lọc (loại BĐS / giá / khu vực).
 * Deactivate các chip cùng nhóm, activate chip được chọn,
 * cập nhật biến filter và áp dụng filter ngay lập tức.
 */
window.abdsChipSelect = function(el, groupClass) {
  document.querySelectorAll('.' + groupClass).forEach(function(c) {
    c.classList.remove('active');
  });
  el.classList.add('active');
  var filter = el.getAttribute('data-filter') || '';
  var value  = el.getAttribute('data-value') || '';
  if(filter === 'category') _abdsActiveCategory = value;
  else if(filter === 'price') _abdsActivePrice = value;
  else if(filter === 'ward')  _abdsActiveWard  = value;
  abdsApprovedFilterApply();
};

/**
 * Kiểm tra giá (price_raw tính bằng VND) có nằm trong khoảng range không.
 */
function _abdsMatchPrice(priceRaw, range) {
  if(!range) return true;
  var TY = 1000000000;
  if(range === 'lt1')  return priceRaw > 0 && priceRaw < 1 * TY;
  if(range === '1-2')  return priceRaw >= 1*TY && priceRaw < 2*TY;
  if(range === '2-3')  return priceRaw >= 2*TY && priceRaw < 3*TY;
  if(range === '3-5')  return priceRaw >= 3*TY && priceRaw < 5*TY;
  if(range === '5-10') return priceRaw >= 5*TY && priceRaw < 10*TY;
  if(range === 'gt10') return priceRaw >= 10*TY;
  return true;
}

/**
 * Áp dụng tất cả bộ lọc (search + category + price + ward) lên các card đang hiển thị.
 * Chạy client-side, không gọi API.
 */
window.abdsApprovedFilterApply = function() {
  var searchEl  = document.getElementById('abdsApprovedSearch');
  var searchVal = searchEl ? searchEl.value.toLowerCase().trim() : '';

  var cards = document.querySelectorAll('#abdsListContainer .abds-card');
  var visible = 0;

  cards.forEach(function(card) {
    var id = parseInt((card.id || '').replace('abds-', ''), 10);
    var d  = window._abdsCardData[id] || {};

    // Kiểm tra từ khoá tìm kiếm (title, địa chỉ, tên môi giới)
    var matchSearch = !searchVal
      || (d.title  || '').includes(searchVal)
      || (d.broker || '').includes(searchVal)
      || (d.addr   || '').includes(searchVal);

    // Kiểm tra loại BĐS
    var matchCat  = !_abdsActiveCategory || d.category === _abdsActiveCategory;

    // Kiểm tra khoảng giá
    var matchPrice = _abdsMatchPrice(d.price || 0, _abdsActivePrice);

    // Kiểm tra khu vực (phường/xã)
    var matchWard = !_abdsActiveWard || d.ward === _abdsActiveWard;

    var show = matchSearch && matchCat && matchPrice && matchWard;
    card.style.display = show ? '' : 'none';
    if(show) visible++;
  });

  // Hiển thị thông báo trống nếu không có kết quả
  var emptyMsg = document.getElementById('abdsFilterEmptyMsg');
  if(emptyMsg) {
    emptyMsg.style.display = (visible === 0 && cards.length > 0) ? 'block' : 'none';
  }
};

/**
 * Reset toàn bộ bộ lọc: xoá text search, đặt lại tất cả chip về "Tất cả".
 */
function _abdsResetApprovedFilter() {
  _abdsActiveCategory = '';
  _abdsActivePrice    = '';
  _abdsActiveWard     = '';
  var filterEl = document.getElementById('abdsApprovedFilter');
  if(filterEl) {
    // Bỏ active khỏi tất cả chip
    filterEl.querySelectorAll('.chip').forEach(function(c) { c.classList.remove('active'); });
    // Active lại chip "Tất cả" (data-value="")
    filterEl.querySelectorAll('.chip[data-value=""]').forEach(function(c) { c.classList.add('active'); });
    // Xoá text search
    var searchEl = document.getElementById('abdsApprovedSearch');
    if(searchEl) searchEl.value = '';
  }
  var emptyMsg = document.getElementById('abdsFilterEmptyMsg');
  if(emptyMsg) emptyMsg.style.display = 'none';
}

/**
 * Ẩn BĐS đã duyệt: hiện confirm dialog, gọi API PATCH để set status=0.
 * @param {number} id     - ID của BĐS
 * @param {string} title  - Tên BĐS để hiển thị trong confirm
 */
window.hideAbds = function(id, title) {
  if(!confirm('Bạn có chắc chắn muốn ẩn BĐS này?\n\n"' + (title || 'BĐS') + '"')) return;

  var cfg  = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  var url  = cfg && cfg.adminPropertiesBase ? cfg.adminPropertiesBase + id + '/hide' : null;
  var csrf = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if(!url || !csrf) { if(typeof showToast === 'function') showToast('Lỗi cấu hình', 'error'); return; }

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({}),
  })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if(data.success) {
        // Xoá card khỏi DOM với hiệu ứng fade
        var card = document.getElementById('abds-' + id);
        if(card) {
          card.style.transition = 'opacity .3s, transform .3s';
          card.style.opacity    = '0';
          card.style.transform  = 'translateX(20px)';
          setTimeout(function() { if(card.parentNode) card.parentNode.removeChild(card); }, 300);
        }
        // Giảm số đếm "Đã duyệt" trên stat hero
        var totalEl = document.getElementById('abdsTotalApproved');
        if(totalEl) totalEl.textContent = Math.max(0, (parseInt(totalEl.textContent, 10) || 1) - 1);
        if(typeof showToast === 'function') showToast('BĐS đã được ẩn thành công', 'success');
      } else {
        if(typeof showToast === 'function') showToast(data.message || 'Có lỗi xảy ra', 'error');
      }
    })
    .catch(function() {
      if(typeof showToast === 'function') showToast('Lỗi kết nối. Vui lòng thử lại.', 'error');
    });
};

/**
 * Mở modal "Đối tượng liên quan" cho một BĐS.
 * Đọc dữ liệu từ cache _abdsCardData thay vì nhúng vào attribute onclick.
 * @param {number} id - ID của BĐS
 */
window.openAbdsRelatedModal = function(id) {
  var modal   = document.getElementById('abdsRelatedModal');
  var content = document.getElementById('abdsRelatedContent');
  if(!modal || !content) return;

  var d = window._abdsCardData[id] || {};
  var html = '';

  // --- Thông tin Môi giới ---
  html += '<div style="background:var(--bg-secondary,#f3f4f6);border-radius:12px;padding:14px;margin-bottom:10px;">';
  html += '<div style="font-size:10px;font-weight:700;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px;">'
        + '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
        + 'Môi giới / Broker</div>';
  if(d.broker_name) {
    html += '<div style="font-size:14px;font-weight:600;color:var(--text-primary);">' + _escModalText(d.broker_name) + '</div>';
  }
  if(d.broker_phone) {
    html += '<a href="tel:' + _escModalText(d.broker_phone) + '" style="display:inline-flex;align-items:center;gap:5px;margin-top:6px;color:var(--primary);font-size:13px;text-decoration:none;">'
          + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>'
          + _escModalText(d.broker_phone) + '</a>';
  }
  if(!d.broker_name && !d.broker_phone) {
    html += '<div style="font-size:13px;color:var(--text-tertiary);">Không có thông tin môi giới</div>';
  }
  html += '</div>';

  // --- Thông tin Chủ nhà ---
  html += '<div style="background:var(--bg-secondary,#f3f4f6);border-radius:12px;padding:14px;">';
  html += '<div style="font-size:10px;font-weight:700;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:.6px;margin-bottom:10px;">'
        + '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>'
        + 'Chủ nhà / Host</div>';
  if(d.host_name) {
    html += '<div style="font-size:14px;font-weight:600;color:var(--text-primary);">' + _escModalText(d.host_name) + '</div>';
  }
  if(d.host_contact) {
    html += '<a href="tel:' + _escModalText(d.host_contact) + '" style="display:inline-flex;align-items:center;gap:5px;margin-top:6px;color:var(--primary);font-size:13px;text-decoration:none;">'
          + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>'
          + _escModalText(d.host_contact) + '</a>';
  }
  if(!d.host_name && !d.host_contact) {
    html += '<div style="font-size:13px;color:var(--text-tertiary);">Không có thông tin chủ nhà</div>';
  }
  html += '</div>';

  content.innerHTML = html;
  modal.style.display = 'block';
  // Ngăn scroll trang nền khi modal mở
  document.body.style.overflow = 'hidden';
};

/** Đóng modal đối tượng liên quan */
window.closeAbdsRelatedModal = function() {
  var modal = document.getElementById('abdsRelatedModal');
  if(modal) modal.style.display = 'none';
  document.body.style.overflow = '';
};

/** Escape an toàn text cho nội dung modal (tránh XSS) */
function _escModalText(s) {
  if(!s) return '';
  return String(s)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}
</script>
@endif
