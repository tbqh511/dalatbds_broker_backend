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

<!-- ========== OVERLAY: CALL RESULT SHEET ========== -->
<div class="cr-overlay" id="crOverlay" onclick="crCloseSheet()">
  <div class="cr-sheet" id="crSheet" onclick="event.stopPropagation()">
    <div class="cr-handle"></div>

    <!-- STEP 1: Kết quả cuộc gọi -->
    <div id="crStep1">
      <div class="cr-section-eyebrow">Kết quả cuộc gọi</div>
      <div class="cr-call-info" id="crCallInfo">— · Vừa gọi</div>
      <div class="cr-opts">
        <div class="cr-opt" id="crOptAnswered" onclick="crSelectResult('answered')">
          <div class="cr-opt-icon-wrap green">✓</div>
          <div class="cr-opt-body">
            <div class="cr-opt-title">Nghe máy</div>
            <div class="cr-opt-sub">Đã nói chuyện · Xác nhận nhu cầu</div>
          </div>
          <div class="cr-radio" id="crRadioAnswered"></div>
        </div>
        <div class="cr-opt" id="crOptNoAnswer" onclick="crSelectResult('no_answer')">
          <div class="cr-opt-icon-wrap red">✕</div>
          <div class="cr-opt-body">
            <div class="cr-opt-title">Không nghe máy</div>
            <div class="cr-opt-sub" id="crNoAnswerSub">Lần gọi đầu · Sẽ gọi lại sau</div>
          </div>
          <div class="cr-radio" id="crRadioNoAnswer"></div>
        </div>
      </div>
      <button class="cr-btn-primary" onclick="crNext()">Tiếp theo →</button>
    </div>

    <!-- STEP 2A: Nghe máy — xác nhận nhu cầu -->
    <div id="crStep2A" style="display:none;">
      <div class="cr-2a-header">
        <div class="cr-2a-icon">✓</div>
        <div>
          <div class="cr-2a-title">Xác nhận nhu cầu khách</div>
          <div class="cr-2a-sub" id="cr2aSub">Bổ sung thông tin còn thiếu</div>
        </div>
      </div>
      <div class="cr-needs-label">THÔNG TIN NHU CẦU</div>
      <div class="cr-needs-grid" id="crNeedsGrid"></div>
      <div class="cr-needs-label" style="margin-top:14px;">KHÁCH CÓ NHU CẦU KHÔNG?</div>
      <div class="cr-decision-row">
        <div class="cr-decision-btn cr-decision-yes" id="crDecisionYes" onclick="crSelectDecision('yes')">
          <div style="font-weight:700;font-size:13px;">✓ Có nhu cầu</div>
          <div style="font-size:11px;margin-top:2px;color:inherit;opacity:.8;">Tạo giao dịch</div>
        </div>
        <div class="cr-decision-btn cr-decision-no" id="crDecisionNo" onclick="crSelectDecision('no')">
          <div style="font-weight:700;font-size:13px;">✗ Không có</div>
          <div style="font-size:11px;margin-top:2px;color:inherit;opacity:.8;">Huỷ lead</div>
        </div>
      </div>
      <button class="cr-btn-primary" onclick="crConfirmNeeds()">Xác nhận · Bắt đầu chăm</button>
    </div>

    <!-- STEP 2B: Không nghe lần 1 -->
    <div id="crStep2B" style="display:none;">
      <div class="cr-2b-header">
        <div class="cr-2b-icon amber">⏰</div>
        <div>
          <div class="cr-2b-title">Khách chưa nghe máy</div>
          <div class="cr-2b-sub">Lần gọi 1/2 · Còn 1 lần trước khi huỷ</div>
        </div>
      </div>
      <div class="cr-tracker-row">
        <div class="cr-tracker-label">Lịch sử gọi <span>1 / 2 lần</span></div>
        <div class="cr-tracker-dots">
          <div class="cr-tdot filled">1</div>
          <div class="cr-tdot">2</div>
          <div class="cr-tdot cancel">✕</div>
          <span class="cr-tdot-hint">Huỷ nếu lần 2 không nghe</span>
        </div>
      </div>
      <div class="cr-reminder-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span>Nhắc gọi lại lúc</span>
        <select class="cr-time-select" id="crReminderSelect" onchange="crUpdateReminderBtn()">
          <option value="today-14:00">Hôm nay · 14:00</option>
          <option value="today-16:00">Hôm nay · 16:00</option>
          <option value="today-18:00">Hôm nay · 18:00</option>
          <option value="tomorrow-09:00">Ngày mai · 09:00</option>
          <option value="tomorrow-14:00">Ngày mai · 14:00</option>
        </select>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <button class="cr-btn-amber" id="crSaveReminderBtn" onclick="crSaveReminder()">⏰ Lưu · Nhắc gọi lại lúc 14:00</button>
      <button class="cr-btn-ghost" onclick="crSkipReminder()">Bỏ qua nhắc nhở</button>
    </div>

    <!-- STEP 2C: Không nghe lần 2 -->
    <div id="crStep2C" style="display:none;">
      <div class="cr-2b-header">
        <div class="cr-2b-icon red">⊗</div>
        <div>
          <div class="cr-2b-title">Đã gọi 2 lần không nghe</div>
          <div class="cr-2b-sub">Lần gọi 2/2 · Khuyến nghị huỷ lead</div>
        </div>
      </div>
      <div class="cr-tracker-row danger">
        <div class="cr-tracker-label" style="color:var(--danger);">Lịch sử gọi <span>2 / 2 lần · Không nghe</span></div>
        <div class="cr-tracker-dots">
          <div class="cr-tdot filled">1</div>
          <div class="cr-tdot filled">2</div>
          <div class="cr-tdot cancel active">✕</div>
        </div>
        <div class="cr-2c-warning">Gọi 2 lần không liên lạc được · Lead sẽ được chuyển sang Huỷ</div>
      </div>
      <button class="cr-btn-danger" onclick="crCancelLead()">⊗ Huỷ lead · Không liên lạc được</button>
      <button class="cr-btn-ghost" onclick="crTryAgain()">Thử gọi lần 3 (ngoại lệ)</button>
    </div>

  </div>
</div>

<!-- ========== OVERLAY: GỬI THÔNG TIN BĐS SHEET (2-step) ========== -->
<div class="cd-send-overlay" id="cdSendOverlay" onclick="closeSendPropSheet()">
  <div class="cd-send-sheet" data-send-step="1" onclick="event.stopPropagation()">
    <div class="cd-send-handle"></div>

    {{-- ── STEP 1: Chọn BĐS ── --}}
    <div class="cd-send-header cd-send-step-1">
      <div class="cd-send-title">Chọn BĐS</div>
      <div class="cd-send-subtitle">Tìm và chọn bất động sản phù hợp với khách</div>
    </div>

    {{-- Compact client bar (step 1 only) --}}
    <div class="cd-send-client-bar cd-send-step-1">
      <div class="cd-client-bar-avatar" id="cdSendAvatar"></div>
      <div class="cd-client-bar-name" id="cdSendCriteriaName"></div>
      <div class="cd-client-bar-info" id="cdSendCriteriaInfo"></div>
      {{-- Hidden tags — still populated by JS, not shown --}}
      <span id="cdSendCriteriaTags" style="display:none;"></span>
      <button class="cd-auto-filter-btn" id="cdAutoFilterBtn" onclick="_applySendAutoFilter(this)">Tự động lọc</button>
    </div>

    {{-- Scrollable body step 1 --}}
    <div class="cd-send-body cd-send-step-1">
      <div class="cd-prop-search-row">
        <div class="cd-prop-search-wrap">
          <svg class="cd-prop-search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="cdSendSearchInput" class="cd-prop-search-input" placeholder="Tiêu đề, địa chỉ...">
        </div>
      </div>

      <div class="cd-prop-type-chips" id="cdSendCatChips">
        <button class="cd-prop-type-chip active" onclick="_selectSendCatChip(this,'')">Tất cả</button>
        <button class="cd-prop-type-chip" onclick="_selectSendCatChip(this,'Nhà')">Nhà</button>
        <button class="cd-prop-type-chip" onclick="_selectSendCatChip(this,'Chung cư')">Chung cư</button>
        <button class="cd-prop-type-chip" onclick="_selectSendCatChip(this,'Biệt thự')">Biệt thự</button>
        <button class="cd-prop-type-chip" onclick="_selectSendCatChip(this,'Khách sạn')">Khách sạn</button>
        <button class="cd-prop-type-chip" onclick="_selectSendCatChip(this,'Đất')">Đất</button>
      </div>

      <div class="cd-send-section-label">Chọn BĐS</div>
      <div id="cdSendPropList"></div>
    </div>

    {{-- Footer step 1 --}}
    <div class="cd-send-footer cd-send-step-1">
      <button class="cd-send-btn-cancel" onclick="closeSendPropSheet()">Huỷ</button>
      <button class="cd-send-btn-next" id="cdSendNextBtn" onclick="_sendPropGoStep(2)" disabled>
        Tiếp tục
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
    </div>

    {{-- ── STEP 2: Nội dung gửi ── --}}
    <div class="cd-send-header cd-send-step-2">
      <button class="cd-send-back-btn" onclick="_sendPropGoStep(1)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Quay lại
      </button>
      <div class="cd-send-title">Nội dung gửi</div>
      <div class="cd-send-subtitle">Chọn loại thông tin gửi cho khách</div>
    </div>

    {{-- Scrollable body step 2 --}}
    <div class="cd-send-body cd-send-step-2">
      {{-- Selected count summary --}}
      <div class="cd-send-selected-summary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span id="cdSendSelectedCount">0 BĐS đã chọn</span>
      </div>

      <div class="cd-send-section-label">Nội dung gửi cho khách</div>
      <div class="cd-send-checks" id="cdSendChecks">
        <label class="cd-check-opt selected" onclick="_toggleSendCheckbox(this,'full')">
          <div class="cd-check-indicator checked">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div class="cd-radio-icon" style="background:#dbeafe;">🏠</div>
          <div class="cd-radio-text">
            <div class="cd-radio-title">Toàn bộ thông tin BĐS</div>
            <div class="cd-radio-sub">Tên, mô tả, giá, diện tích, hình ảnh</div>
          </div>
          <input type="checkbox" name="cdSendType" value="full" checked style="display:none">
        </label>
        <label class="cd-check-opt" onclick="_toggleSendCheckbox(this,'location')">
          <div class="cd-check-indicator">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div class="cd-radio-icon" style="background:#d1fae5;">📍</div>
          <div class="cd-radio-text">
            <div class="cd-radio-title">Gửi vị trí (bản đồ)</div>
            <div class="cd-radio-sub">Link Google Maps đến BĐS</div>
          </div>
          <input type="checkbox" name="cdSendType" value="location" style="display:none">
        </label>
        <label class="cd-check-opt" onclick="_toggleSendCheckbox(this,'legal')">
          <div class="cd-check-indicator">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div class="cd-radio-icon" style="background:#fef3c7;">📄</div>
          <div class="cd-radio-text">
            <div class="cd-radio-title">Giấy tờ pháp lý</div>
            <div class="cd-radio-sub">Số đỏ, giấy phép xây dựng</div>
          </div>
          <input type="checkbox" name="cdSendType" value="legal" style="display:none">
        </label>
        <label class="cd-check-opt" onclick="_toggleSendCheckbox(this,'gallery')">
          <div class="cd-check-indicator">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <div class="cd-radio-icon" style="background:#ede9fe;">🖼</div>
          <div class="cd-radio-text">
            <div class="cd-radio-title">Hình ảnh BĐS</div>
            <div class="cd-radio-sub">Thư viện ảnh thực tế</div>
          </div>
          <input type="checkbox" name="cdSendType" value="gallery" style="display:none">
        </label>
      </div>

      <div style="padding:8px 16px 16px;">
        <textarea id="cdSendNote" class="cd-send-note" placeholder="Ghi chú gửi kèm (tuỳ chọn)..." rows="3"></textarea>
      </div>
    </div>

    {{-- Footer step 2 --}}
    <div class="cd-send-footer cd-send-step-2">
      <button class="cd-send-btn-confirm" id="cdSendConfirmBtn" onclick="sendPropToClient()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        Gửi qua Telegram
      </button>
    </div>
  </div>
</div>
