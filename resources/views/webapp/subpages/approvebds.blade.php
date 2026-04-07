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
      <div class="ah-stat ah-stat--clickable" data-tab="approved" onclick="switchAbdsStatTab('approved',this)"><div class="ah-stat-val" id="abdsTotalApproved">—</div><div class="ah-stat-lbl">Đã duyệt</div></div>
      <div class="ah-stat ah-stat--clickable" data-tab="rejected" onclick="switchAbdsStatTab('rejected',this)"><div class="ah-stat-val" id="abdsRejectedCount">—</div><div class="ah-stat-lbl">Từ chối</div></div>
    </div>
  </div>

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
</div><!-- end subpage-approvebds -->
