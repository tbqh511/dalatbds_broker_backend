<!-- ========== SUBPAGE: KHÁCH HÀNG (SALE CRM) ========== -->
<div class="subpage" id="subpage-clients">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('clients')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title">
      <span style="display:inline-flex;align-items:center;gap:5px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Khách của tôi
      </span>
    </div>
    <div class="sp-actions">
      <button class="sp-action-btn" onclick="openSheet()" title="Thêm khách hàng">＋</button>
    </div>
  </div>

  <!-- Summary strip — values populated by JS -->
  <div class="sp-summary">
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="clientsKpiNew" style="color:var(--danger);">—</div>
      <div class="sp-sum-lbl">Mới</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="clientsKpiCaring" style="color:var(--primary);">—</div>
      <div class="sp-sum-lbl">Đang chăm</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="clientsKpiViewing" style="color:var(--purple);">—</div>
      <div class="sp-sum-lbl">Hẹn xem</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="clientsKpiClosed" style="color:var(--success);">—</div>
      <div class="sp-sum-lbl">Đã chốt</div>
    </div>
  </div>

  <!-- Search bar -->
  <div class="sp-searchbar">
    <div class="sp-search-input">
      <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </span>
      <input type="text" id="clientsSearchInput" placeholder="Tên, số điện thoại..." oninput="clientsOnSearchInput(this.value)">
    </div>
    <button class="sp-filter-btn" onclick="openFilterSheet('filterSheet')" title="Lọc">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
    </button>
  </div>

  <!-- Status tabs — labels populated by JS -->
  <div class="sp-tabs">
    <button class="sp-tab active" id="clientsTabNew"     onclick="clientsTabSwitch(this,'new')">Mới (—)</button>
    <button class="sp-tab"        id="clientsTabCaring"  onclick="clientsTabSwitch(this,'caring')">Chăm (—)</button>
    <button class="sp-tab"        id="clientsTabViewing" onclick="clientsTabSwitch(this,'viewing')">Hẹn (—)</button>
    <button class="sp-tab"        id="clientsTabClosed"  onclick="clientsTabSwitch(this,'closed')">Chốt (—)</button>
  </div>

  <div class="sp-scroll">
    <!-- Loading state -->
    <div id="clientsLoading" style="padding:40px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">
      <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
      Đang tải...
    </div>

    <!-- Empty state -->
    <div id="clientsEmpty" style="display:none;padding:48px 24px;text-align:center;">
      <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      <div style="font-size:14px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Chưa có khách hàng nào</div>
      <div style="font-size:12px;color:var(--text-tertiary);">Khách hàng mới sẽ xuất hiện ở đây</div>
    </div>

    <!-- Dynamic list -->
    <div id="clientsList" style="display:none;"></div>

    <!-- Load more -->
    <div id="clientsLoadMore" style="display:none;padding:12px 16px;text-align:center;">
      <button onclick="clientsLoadMore()" style="padding:10px 28px;border:1.5px solid var(--border);border-radius:20px;font-size:13px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);cursor:pointer;">Xem thêm</button>
    </div>

    <div style="height:16px;"></div>
  </div>
</div>

<!-- ========== SUBPAGE: CLIENT DETAIL ========== -->
<div class="subpage" id="subpage-client-detail" style="z-index:510;">

  <!-- Header: back + title/subtitle block + avatar -->
  <div class="sp-header" style="gap:10px;">
    <button class="sp-back" onclick="closeClientDetail()"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="cd-header-meta">
      <div class="sp-title" id="cdSpTitle" style="font-size:14px;text-align:left;padding:0;">Chi tiết</div>
      <div class="cd-sp-subtitle" id="cdSpSubtitle"></div>
    </div>
    <div class="cd-header-avatar" id="cdHeaderAvatar"></div>
  </div>

  <!-- 5-step progress stepper (populated by JS) -->
  <div class="cd-stepper-wrap">
    <div class="cd-stepper" id="cdStepper"></div>
  </div>

  <!-- Scrollable body -->
  <div class="sp-scroll" style="padding-bottom:20px;">

    <!-- Section: Nhu cầu khách -->
    <div class="cd-section">
      <div class="cd-section-title">Nhu cầu khách</div>
      <div class="cd-needs-grid" id="cdNeedsGrid"></div>
    </div>

    <!-- Section: Hoạt động -->
    <div class="cd-section" style="padding:0;overflow:hidden;">
      <div class="cd-section-title" style="padding:14px 14px 10px;">Hoạt động</div>
      <div id="cdActionsBody"></div>
    </div>

    <!-- Section: Lịch sử tương tác -->
    <div class="cd-section">
      <div class="cd-section-title">Lịch sử tương tác</div>
      <div id="cdTimelineBody"></div>
    </div>

    <div style="height:8px;"></div>
  </div>
</div>

<!-- ========== OVERLAY: CALL CONFIRMATION MODAL ========== -->
<div class="cd-call-overlay" id="cdCallOverlay" onclick="closeCallConfirmModal()">
  <div class="cd-call-modal" onclick="event.stopPropagation()">
    <div class="cd-call-icon-wrap">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    </div>
    <div class="cd-call-label" id="cdCallLabel">Gọi cho khách</div>
    <div class="cd-call-phone" id="cdCallPhone">—</div>
    <div class="cd-call-note">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
      Cuộc gọi được lưu tự động
    </div>
    <div class="cd-call-btns">
      <button class="cd-call-btn-cancel" onclick="closeCallConfirmModal()">Hủy</button>
      <button class="cd-call-btn-confirm" id="cdCallConfirmBtn" onclick="confirmCall()">Gọi ngay</button>
    </div>
  </div>
</div>

<!-- ========== OVERLAY: GỬI THÔNG TIN BĐS SHEET ========== -->
<div class="cd-send-overlay" id="cdSendOverlay" onclick="closeSendPropSheet()">
  <div class="cd-send-sheet" onclick="event.stopPropagation()">
    <div class="cd-send-handle"></div>

    <div class="cd-send-header">
      <div class="cd-send-title">Tìm BĐS phù hợp</div>
      <div class="cd-send-subtitle">Chọn BĐS → chọn nội dung gửi → gửi cho khách</div>
    </div>

    <div style="overflow-y:auto;flex:1;min-height:0;">
      <!-- Chip thông tin khách -->
      <div style="padding:10px 12px 0;">
        <div class="cd-criteria-chip">
          <div class="cd-criteria-avatar" id="cdSendAvatar"></div>
          <div class="cd-criteria-body">
            <div class="cd-criteria-name" id="cdSendCriteriaName"></div>
            <div class="cd-criteria-tags" id="cdSendCriteriaTags"></div>
            <div class="cd-criteria-info" id="cdSendCriteriaInfo"></div>
          </div>
          <button class="cd-auto-filter-btn" id="cdAutoFilterBtn" onclick="_applySendAutoFilter(this)">Tự động lọc</button>
        </div>
      </div>

      <!-- Search -->
      <div class="cd-prop-search-row">
        <div class="cd-prop-search-wrap">
          <svg class="cd-prop-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="cdSendSearchInput" class="cd-prop-search-input" placeholder="Tiêu đề, địa chỉ...">
        </div>
      </div>

      <!-- Category filter chips -->
      <div class="cd-prop-type-chips" id="cdSendCatChips">
        <button class="cd-prop-type-chip active" onclick="_selectSendCatChip(this,'')">Tất cả</button>
        <button class="cd-prop-type-chip" onclick="_selectSendCatChip(this,'Nhà')">Nhà</button>
        <button class="cd-prop-type-chip" onclick="_selectSendCatChip(this,'Chung cư')">Chung cư</button>
        <button class="cd-prop-type-chip" onclick="_selectSendCatChip(this,'Biệt thự')">Biệt thự</button>
        <button class="cd-prop-type-chip" onclick="_selectSendCatChip(this,'Khách sạn')">Khách sạn</button>
        <button class="cd-prop-type-chip" onclick="_selectSendCatChip(this,'Đất')">Đất</button>
      </div>

      <!-- Danh sách BĐS -->
      <div class="cd-send-section-label">Chọn BĐS</div>
      <div id="cdSendPropList"></div>

      <!-- Nội dung gửi cho khách -->
      <div class="cd-send-section-label" style="margin-top:4px;">Nội dung gửi cho khách</div>
      <div class="cd-send-radios" id="cdSendRadios">
        <label class="cd-radio-opt selected" onclick="_selectSendRadio(this,'full')">
          <div class="cd-radio-indicator checked"></div>
          <div class="cd-radio-icon" style="background:#dbeafe;">🏠</div>
          <div class="cd-radio-text">
            <div class="cd-radio-title">Toàn bộ thông tin BĐS</div>
            <div class="cd-radio-sub">Tên, mô tả, giá, diện tích, hình ảnh</div>
          </div>
          <input type="radio" name="cdSendType" value="full" checked style="display:none">
        </label>
        <label class="cd-radio-opt" onclick="_selectSendRadio(this,'location')">
          <div class="cd-radio-indicator"></div>
          <div class="cd-radio-icon" style="background:#d1fae5;">📍</div>
          <div class="cd-radio-text">
            <div class="cd-radio-title">Gửi vị trí (bản đồ)</div>
            <div class="cd-radio-sub">Link Google Maps đến BĐS</div>
          </div>
          <input type="radio" name="cdSendType" value="location" style="display:none">
        </label>
        <label class="cd-radio-opt" onclick="_selectSendRadio(this,'legal')">
          <div class="cd-radio-indicator"></div>
          <div class="cd-radio-icon" style="background:#fef3c7;">📄</div>
          <div class="cd-radio-text">
            <div class="cd-radio-title">Giấy tờ pháp lý</div>
            <div class="cd-radio-sub">Số đỏ, giấy phép xây dựng</div>
          </div>
          <input type="radio" name="cdSendType" value="legal" style="display:none">
        </label>
        <label class="cd-radio-opt" onclick="_selectSendRadio(this,'gallery')">
          <div class="cd-radio-indicator"></div>
          <div class="cd-radio-icon" style="background:#ede9fe;">🖼</div>
          <div class="cd-radio-text">
            <div class="cd-radio-title">Hình ảnh BĐS</div>
            <div class="cd-radio-sub">Thư viện ảnh thực tế</div>
          </div>
          <input type="radio" name="cdSendType" value="gallery" style="display:none">
        </label>
      </div>

      <!-- Ghi chú -->
      <div style="padding:8px 16px 12px;">
        <textarea id="cdSendNote" class="cd-send-note" placeholder="Ghi chú gửi kèm (tuỳ chọn)..." rows="3"></textarea>
      </div>
    </div>

    <div class="cd-send-footer">
      <button class="cd-send-btn-cancel" onclick="closeSendPropSheet()">Huỷ</button>
      <button class="cd-send-btn-confirm" id="cdSendConfirmBtn" onclick="sendPropToClient()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        Gửi qua Telegram
      </button>
    </div>
  </div>
</div>
