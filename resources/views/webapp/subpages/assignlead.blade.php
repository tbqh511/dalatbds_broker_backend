<!-- ========== SUBPAGE: ASSIGN LEAD ========== -->
<div class="subpage" id="subpage-assignlead">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('assignlead')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Assign Lead cho Sale</span></div>
  </div>

  {{-- Đã xóa phần bộ lọc "Lead chưa phân công" (ngân sách cao/trung/thấp) để tiết kiệm diện tích --}}
  {{-- Các phần tử ẩn giữ id để JS không bị lỗi khi cập nhật count --}}
  <span id="unassignedCount" style="display:none;"></span>
  <span id="budgetHigh" style="display:none;"></span>
  <span id="budgetMedium" style="display:none;"></span>
  <span id="budgetLow" style="display:none;"></span>

  <div class="sp-tabs" style="overflow-x:auto;white-space:nowrap;-webkit-overflow-scrolling:touch;scrollbar-width:none;">
    <button class="sp-tab active" id="tabUnassigned" data-tab="unassigned" onclick="assignLeadTabSwitch(this)">Chờ assign</button>
    <button class="sp-tab" id="tabNew" data-tab="new" onclick="assignLeadTabSwitch(this)">Mới</button>
    <button class="sp-tab" id="tabContacted" data-tab="contacted" onclick="assignLeadTabSwitch(this)">Đang xử lý</button>
    <button class="sp-tab" id="tabConverted" data-tab="converted" onclick="assignLeadTabSwitch(this)">Đã tạo Deal</button>
    <button class="sp-tab" id="tabFailed" data-tab="failed" onclick="assignLeadTabSwitch(this)">Thất bại</button>
    <button class="sp-tab" data-tab="history" onclick="assignLeadTabSwitch(this)">Lịch sử</button>
  </div>

  <div class="sp-scroll" style="padding-bottom:80px;">

    <!-- Tab: Chờ assign -->
    <div id="assignLeadTabUnassigned">
      <div id="assignLeadLoading" style="padding:48px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">
        <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
        Đang tải...
      </div>
      <div id="assignLeadEmpty" style="display:none;padding:48px 24px;text-align:center;">
        <div style="font-size:32px;margin-bottom:8px;">✅</div>
        <div style="font-size:14px;font-weight:600;color:var(--text-secondary);">Không có lead nào chờ assign</div>
        <div style="font-size:12px;color:var(--text-tertiary);margin-top:4px;">Tất cả leads đã được phân công</div>
      </div>
      <div id="unassignedLeadsList" style="display:none;"></div>
    </div>

    <!-- Tab: Mới (status = new) -->
    <div id="assignLeadTabNew" style="display:none;">
      <div id="assignLeadTabNewContent"></div>
    </div>

    <!-- Tab: Đang xử lý (status = contacted) -->
    <div id="assignLeadTabContacted" style="display:none;">
      <div id="assignLeadTabContactedContent"></div>
    </div>

    <!-- Tab: Đã tạo Deal (status = converted) -->
    <div id="assignLeadTabConverted" style="display:none;">
      <div id="assignLeadTabConvertedContent"></div>
    </div>

    <!-- Tab: Thất bại (status = bad-contact / lost) -->
    <div id="assignLeadTabFailed" style="display:none;">
      <div id="assignLeadTabFailedContent"></div>
    </div>

    <!-- Tab: Lịch sử assign hôm nay -->
    <div id="assignLeadTabHistory" style="display:none;">
      <div class="assign-history" style="margin-top:8px;">
        <div class="ah-title">Đã assign hôm nay</div>
        <div id="assignHistoryList"></div>
        <div id="assignHistoryEmpty" style="display:none;padding:32px 24px;text-align:center;color:var(--text-tertiary);font-size:13px;">
          Chưa có lượt assign nào hôm nay
        </div>
      </div>
    </div>

    <div style="height:20px;"></div>
  </div>

  <!-- Floating assign CTA (hiện khi chọn lead) -->
  <div class="assign-cta" id="assignCta" style="display:none;">
    <div class="assign-cta-info">
      <div class="assign-cta-count" id="assignCtaCount">0 lead được chọn</div>
      <div class="assign-cta-sub">Chọn lead rồi assign cho Sale</div>
    </div>
    <button class="assign-cta-btn" id="assignCtaBtn" onclick="openSalePicker(getSelectedLeads())"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Assign cho Sale</span></button>
  </div>

</div><!-- end subpage-assignlead -->

<!-- Sale Picker Modal -->
<div class="sale-picker" id="salePicker">
  <div class="sale-picker-inner">
    <div class="sp-inner-handle"></div>
    <div class="sp-inner-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Chọn Sale để Assign</span></div>
    <div class="sp-inner-sub" id="salePickerSub">Chọn Sale phù hợp</div>
    <!-- Search bar -->
    <div style="margin:10px 0 6px;padding:0 2px;">
      <div style="display:flex;align-items:center;gap:8px;background:#f3f4f6;border-radius:999px;padding:8px 14px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input id="searchInputSale" type="text" placeholder="Tìm kiếm tên Sale..." style="flex:1;border:none;background:transparent;outline:none;font-size:14px;color:var(--text-primary);" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
      </div>
    </div>
    <div id="salePickerList">
      <!-- populated by loadAssignLeadData() -->
    </div>
    <button class="sp-assign-btn" id="spAssignBtn" disabled onclick="confirmAssign()">✓ Xác nhận Assign</button>
  </div>
</div>

