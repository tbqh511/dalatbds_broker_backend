<!-- ========== SUBPAGE: LEAD CỦA TÔI ========== -->
<div class="subpage" id="subpage-leads">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('leads')">←</button>
    <div class="sp-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg> Lead của tôi</div>
    <div class="sp-actions">
      <button class="sp-action-btn" onclick="openSheet()">＋</button>
    </div>
  </div>
  <!-- KPI strip -->
  <div class="kpi-strip">
    <div class="kpi-item"><div class="kpi-val" style="color:var(--danger)">3</div><div class="kpi-lbl">Mới</div></div>
    <div class="kpi-item"><div class="kpi-val" style="color:var(--primary)">4</div><div class="kpi-lbl">Đã liên hệ</div></div>
    <div class="kpi-item"><div class="kpi-val" style="color:var(--success)">12</div><div class="kpi-lbl">Đã chuyển</div></div>
    <div class="kpi-item"><div class="kpi-val" style="color:var(--text-secondary)">3</div><div class="kpi-lbl">Huỷ</div></div>
  </div>
  <div class="sp-searchbar">
    <div class="sp-search-input">
      <span style="display:inline-flex;align-items:center;color:var(--text-tertiary)"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg></span>
      <input type="text" placeholder="Tên, SĐT...">
    </div>
  </div>
  <div class="sp-tabs">
    <button class="sp-tab active" onclick="spTabSwitch(this)">Tất cả (7)</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Mới (3)</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Đã liên hệ (4)</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Đã chuyển</button>
  </div>
  <div class="sp-scroll">

    <!-- LEAD 1 — mới, chưa contact, URGENT -->
    <div class="lead-card urgent">
      <div class="lc-head">
        <div class="lc-avatar" style="background:#ef4444;">NT</div>
        <div class="lc-info">
          <div class="lc-name">Nguyễn Văn Tuấn</div>
          <div class="lc-meta">
            <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>0912.345.678</span>
            <span style="color:var(--danger);font-weight:600"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span> Chưa liên hệ!</span>
          </div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
          <span class="badge badge-red">Mới</span>
          <span class="lc-time">2 giờ trước</span>
        </div>
      </div>
      <div class="lc-body">
        <div class="lc-row"><span class="lc-label">Loại BĐS</span><span class="lc-value">Biệt thự, Đất ở</span></div>
        <div class="lc-row"><span class="lc-label">Nhu cầu</span><span class="lc-value">Mua để ở + đầu tư</span></div>
        <div class="lc-row"><span class="lc-label">Ngân sách</span><span class="lc-value money">3 tỷ – 5 tỷ</span></div>
        <div class="lc-row"><span class="lc-label">Khu vực</span><span class="lc-value">P.Lâm Viên</span></div>
      </div>
      <div class="lc-tags">
        <span class="lc-tag">Biệt thự</span><span class="lc-tag">3–5 tỷ</span><span class="lc-tag">Lâm Viên</span><span class="lc-tag">Facebook Ads</span>
      </div>
      <div class="lc-footer">
        <span class="lc-source"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg></span> Facebook Ads · 15/03/2026</span>
        <div class="lc-actions">
          <button class="lc-btn icon" onclick="showToast('Đang gọi...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></button>
          <button class="lc-btn primary" onclick="toggleLeadExpand('lead1-expand')">Xử lý ▾</button>
        </div>
      </div>
      <div class="lc-expand" id="lead1-expand">
        <div style="font-size:11px;font-weight:600;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Cập nhật trạng thái</div>
        <div class="lc-action-row">
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>Đã gọi</div>
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>Zalo</div>
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Hẹn gặp</div>
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>Tạo Deal</div>
        </div>
        <textarea class="lc-note-area" rows="2" placeholder="Ghi chú nhanh (VD: Khách nói chờ tháng sau, thích khu vực yên tĩnh...)"></textarea>
        <div style="display:flex;gap:8px;">
          <button class="lc-btn" onclick="toggleLeadExpand('lead1-expand')" style="flex:1">Đóng</button>
          <button class="lc-btn success" onclick="showToast('✓ Đã lưu cập nhật lead')" style="flex:2">✓ Lưu & Cập nhật</button>
        </div>
      </div>
    </div>

    <!-- LEAD 2 — mới -->
    <div class="lead-card urgent">
      <div class="lc-head">
        <div class="lc-avatar" style="background:var(--teal);">BT</div>
        <div class="lc-info">
          <div class="lc-name">Anh Bảo Trâm</div>
          <div class="lc-meta"><span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>0966.111.222</span><span style="color:var(--warning);font-weight:600">6 giờ trước</span></div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
          <span class="badge badge-red">Mới</span>
          <span class="lc-time">Hôm nay</span>
        </div>
      </div>
      <div class="lc-body">
        <div class="lc-row"><span class="lc-label">Loại BĐS</span><span class="lc-value">Khách sạn mini</span></div>
        <div class="lc-row"><span class="lc-label">Nhu cầu</span><span class="lc-value">Mua kinh doanh</span></div>
        <div class="lc-row"><span class="lc-label">Ngân sách</span><span class="lc-value money">3 tỷ – 5 tỷ</span></div>
        <div class="lc-row"><span class="lc-label">Khu vực</span><span class="lc-value">Trung tâm TP</span></div>
      </div>
      <div class="lc-tags"><span class="lc-tag">Khách sạn</span><span class="lc-tag">KD du lịch</span><span class="lc-tag">Zalo OA</span></div>
      <div class="lc-footer">
        <span class="lc-source"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span> Zalo OA · 15/03/2026</span>
        <div class="lc-actions">
          <button class="lc-btn icon" onclick="showToast('Đang gọi...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></button>
          <button class="lc-btn primary" onclick="toggleLeadExpand('lead2-expand')">Xử lý ▾</button>
        </div>
      </div>
      <div class="lc-expand" id="lead2-expand">
        <div class="lc-action-row">
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>Đã gọi</div>
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>Zalo</div>
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>Tạo Deal</div>
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></span>Huỷ</div>
        </div>
        <textarea class="lc-note-area" rows="2" placeholder="Ghi chú..."></textarea>
        <div style="display:flex;gap:8px;">
          <button class="lc-btn" onclick="toggleLeadExpand('lead2-expand')" style="flex:1">Đóng</button>
          <button class="lc-btn success" onclick="showToast('✓ Đã lưu')" style="flex:2">✓ Lưu</button>
        </div>
      </div>
    </div>

    <!-- LEAD 3 — Đã liên hệ -->
    <div class="lead-card contacted">
      <div class="lc-head">
        <div class="lc-avatar" style="background:var(--primary);">PH</div>
        <div class="lc-info">
          <div class="lc-name">Chị Phương Hoa</div>
          <div class="lc-meta"><span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>0944.567.890</span><span>Đã gọi hôm qua</span></div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
          <span class="badge badge-blue"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span> Đã liên hệ</span>
          <span class="lc-time">14/03/2026</span>
        </div>
      </div>
      <div class="lc-body">
        <div class="lc-row"><span class="lc-label">Loại BĐS</span><span class="lc-value">Nhà phố, Biệt thự</span></div>
        <div class="lc-row"><span class="lc-label">Nhu cầu</span><span class="lc-value">Mua để ở</span></div>
        <div class="lc-row"><span class="lc-label">Ngân sách</span><span class="lc-value money">2 tỷ – 4 tỷ</span></div>
        <div class="lc-row"><span class="lc-label">Khu vực</span><span class="lc-value">P.Cam Ly</span></div>
      </div>
      <div class="lc-tags"><span class="lc-tag">Nhà phố</span><span class="lc-tag">2–4 tỷ</span><span class="lc-tag" style="background:var(--success-light);color:var(--success)">Hẹn gặp T7</span></div>
      <div class="lc-footer">
        <span class="lc-source"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span> Hẹn gặp: 22/03/2026</span>
        <div class="lc-actions">
          <button class="lc-btn icon" onclick="showToast('Đang gọi...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></button>
          <button class="lc-btn success" onclick="showToast('✓ Đang tạo Deal...')"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg> Tạo Deal</span></button>
        </div>
      </div>
    </div>

    <!-- LEAD 4 — Đã liên hệ, cần follow-up -->
    <div class="lead-card contacted">
      <div class="lc-head">
        <div class="lc-avatar" style="background:var(--purple);">HM</div>
        <div class="lc-info">
          <div class="lc-name">Anh Hoàng Minh</div>
          <div class="lc-meta"><span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>0911.222.333</span><span style="color:var(--warning)">Cần follow-up</span></div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
          <span class="badge badge-blue"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span> Đã liên hệ</span>
          <span class="lc-time">12/03/2026</span>
        </div>
      </div>
      <div class="lc-body">
        <div class="lc-row"><span class="lc-label">Loại BĐS</span><span class="lc-value">Đất nền</span></div>
        <div class="lc-row"><span class="lc-label">Nhu cầu</span><span class="lc-value">Đầu tư</span></div>
        <div class="lc-row"><span class="lc-label">Ngân sách</span><span class="lc-value money">500tr – 1 tỷ</span></div>
        <div class="lc-row"><span class="lc-label">Ghi chú</span><span class="lc-value" style="color:var(--warning)">Chờ lương T4</span></div>
      </div>
      <div class="lc-tags"><span class="lc-tag">Đất nền</span><span class="lc-tag" style="background:var(--warning-light);color:var(--warning)">Follow-up T4</span></div>
      <div class="lc-footer">
        <span class="lc-source">Website · 10/03/2026</span>
        <div class="lc-actions">
          <button class="lc-btn icon" onclick="showToast('Đang nhắn...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></button>
          <button class="lc-btn primary" onclick="toggleLeadExpand('lead4-expand')">Cập nhật ▾</button>
        </div>
      </div>
      <div class="lc-expand" id="lead4-expand">
        <div class="lc-action-row">
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>Gọi lại</div>
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Nhắc T4</div>
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>Tạo Deal</div>
          <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></span>Huỷ</div>
        </div>
        <textarea class="lc-note-area" rows="2" placeholder="Ghi chú thêm..."></textarea>
        <div style="display:flex;gap:8px;">
          <button class="lc-btn" onclick="toggleLeadExpand('lead4-expand')" style="flex:1">Đóng</button>
          <button class="lc-btn success" onclick="showToast('✓ Đã lưu')" style="flex:2">✓ Lưu</button>
        </div>
      </div>
    </div>

    <div style="height:16px"></div>
  </div>
</div>

