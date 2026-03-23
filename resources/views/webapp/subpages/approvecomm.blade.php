<!-- ========== SUBPAGE: DUYỆT HOA HỒNG ========== -->
<div class="subpage" id="subpage-approvecomm">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('approvecomm')">←</button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> Duyệt hoa hồng</span></div>
    <div class="sp-actions">
      <button class="sp-action-btn" onclick="loadApproveComm(true)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></button>
    </div>
  </div>

  <div class="admin-hero purple-grad">
    <div class="ah-label">QUẢN LÝ HOA HỒNG — ADMIN</div>
    <div class="ah-main"><span id="acommHeroMain">— triệu chờ duyệt</span></div>
    <div class="ah-grid">
      <div class="ah-stat"><div class="ah-stat-val" id="acommPendingCount">—</div><div class="ah-stat-lbl">Chờ duyệt</div></div>
      <div class="ah-stat"><div class="ah-stat-val" id="acommProcessingCount">—</div><div class="ah-stat-lbl">Đang CN</div></div>
      <div class="ah-stat"><div class="ah-stat-val" id="acommMonthlyTotal">—</div><div class="ah-stat-lbl">Tháng này</div></div>
      <div class="ah-stat"><div class="ah-stat-val" id="acommWaitingDeposit">—</div><div class="ah-stat-lbl">Chờ cọc</div></div>
    </div>
  </div>

  <div class="sp-tabs" id="acommTabBar">
    <button class="sp-tab active" data-tab="pending" onclick="switchAcommTab('pending',this)">Chờ duyệt (<span class="acomm-tab-count-pending">—</span>)</button>
    <button class="sp-tab" data-tab="processing" onclick="switchAcommTab('processing',this)">Đang xử lý (<span class="acomm-tab-count-processing">—</span>)</button>
    <button class="sp-tab" data-tab="completed" onclick="switchAcommTab('completed',this)">Đã hoàn tất</button>
  </div>

  <div class="sp-scroll" style="padding-bottom:16px;">
    <div id="acommListContainer"></div>
    <div style="height:16px;"></div>
  </div>

  <!-- Hold sheet — Giữ lại -->
  <div class="reject-sheet" id="acommHoldSheet">
    <div class="reject-sheet-inner">
      <div class="rs-handle"></div>
      <div class="rs-title">⏸ Giữ lại để xem xét</div>
      <textarea id="acommHoldNote" class="rs-note" rows="3" placeholder="Ghi chú lý do giữ lại (tùy chọn)..."></textarea>
      <button class="rs-submit" onclick="submitHoldComm()">Gửi thông báo → Sale</button>
    </div>
  </div>

  <!-- Detail sheet — Hợp đồng / Chi tiết -->
  <div class="reject-sheet" id="acommDetailSheet">
    <div class="reject-sheet-inner">
      <div class="rs-handle"></div>
      <div class="rs-title" id="acommDetailTitle">Chi tiết hoa hồng</div>
      <div id="acommDetailBody" style="padding-bottom:8px;"></div>
      <button class="rs-submit" style="background:var(--bg-secondary);color:var(--text-primary);" onclick="document.getElementById('acommDetailSheet').classList.remove('open')">Đóng</button>
    </div>
  </div>
</div><!-- end subpage-approvecomm -->
