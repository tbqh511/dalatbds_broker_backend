<!-- ========== SUBPAGE: ASSIGN LEAD ========== -->
<div class="subpage" id="subpage-assignlead">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('assignlead')">←</button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Assign Lead cho Sale</span></div>
  </div>

  <!-- Pool summary -->
  <div class="assign-pool">
    <div class="ap-title">
      <span>LEAD CHƯA PHÂN CÔNG</span>
      <span class="ap-count" id="unassignedCount">–</span>
    </div>
    <div style="display:flex;gap:8px;">
      <div style="flex:1;background:var(--danger-light);border-radius:var(--radius-sm);padding:8px 10px;text-align:center;">
        <div id="budgetHigh" style="font-size:16px;font-weight:700;color:var(--danger);">–</div>
        <div style="font-size:10px;color:var(--danger);">Ngân sách cao (3 tỷ+)</div>
      </div>
      <div style="flex:1;background:var(--warning-light);border-radius:var(--radius-sm);padding:8px 10px;text-align:center;">
        <div id="budgetMedium" style="font-size:16px;font-weight:700;color:var(--warning);">–</div>
        <div style="font-size:10px;color:var(--warning);">Ngân sách trung</div>
      </div>
      <div style="flex:1;background:var(--primary-light);border-radius:var(--radius-sm);padding:8px 10px;text-align:center;">
        <div id="budgetLow" style="font-size:16px;font-weight:700;color:var(--primary);">–</div>
        <div style="font-size:10px;color:var(--primary);">Ngân sách thấp</div>
      </div>
    </div>
  </div>

  <div class="sp-tabs">
    <button class="sp-tab active" id="tabUnassigned" data-tab="unassigned" onclick="assignLeadTabSwitch(this)">Chờ assign</button>
    <button class="sp-tab" data-tab="history" onclick="assignLeadTabSwitch(this)">Đã assign hôm nay</button>
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

    <!-- Tab: Đã assign hôm nay -->
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
    <div id="salePickerList">
      <!-- populated by loadAssignLeadData() -->
    </div>
    <button class="sp-assign-btn" id="spAssignBtn" disabled onclick="confirmAssign()">✓ Xác nhận Assign</button>
  </div>
</div>

