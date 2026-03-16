  <!-- ========== SUBPAGE: LEAD CỦA TÔI ========== -->
  <div class="subpage" id="subpage-leads">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('leads')">←</button>
      <div class="sp-title">🎯 Lead của tôi</div>
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
        <span style="font-size:15px;color:var(--text-tertiary)">🔍</span>
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
              <span>📞 0912.345.678</span>
              <span style="color:var(--danger);font-weight:600">⚠ Chưa liên hệ!</span>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
            <span class="badge badge-red">🔴 Mới</span>
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
          <span class="lc-source">📢 Facebook Ads · 15/03/2026</span>
          <div class="lc-actions">
            <button class="lc-btn icon" onclick="showToast('Đang gọi...')">📞</button>
            <button class="lc-btn primary" onclick="toggleLeadExpand('lead1-expand')">Xử lý ▾</button>
          </div>
        </div>
        <div class="lc-expand" id="lead1-expand">
          <div style="font-size:11px;font-weight:600;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Cập nhật trạng thái</div>
          <div class="lc-action-row">
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">📞</span>Đã gọi</div>
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">💬</span>Zalo</div>
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">📅</span>Hẹn gặp</div>
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">🤝</span>Tạo Deal</div>
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
            <div class="lc-meta"><span>📞 0966.111.222</span><span style="color:var(--warning);font-weight:600">6 giờ trước</span></div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
            <span class="badge badge-red">🔴 Mới</span>
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
          <span class="lc-source">💬 Zalo OA · 15/03/2026</span>
          <div class="lc-actions">
            <button class="lc-btn icon" onclick="showToast('Đang gọi...')">📞</button>
            <button class="lc-btn primary" onclick="toggleLeadExpand('lead2-expand')">Xử lý ▾</button>
          </div>
        </div>
        <div class="lc-expand" id="lead2-expand">
          <div class="lc-action-row">
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">📞</span>Đã gọi</div>
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">💬</span>Zalo</div>
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">🤝</span>Tạo Deal</div>
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">❌</span>Huỷ</div>
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
            <div class="lc-meta"><span>📞 0944.567.890</span><span>Đã gọi hôm qua</span></div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
            <span class="badge badge-blue">📞 Đã liên hệ</span>
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
          <span class="lc-source">📅 Hẹn gặp: 22/03/2026</span>
          <div class="lc-actions">
            <button class="lc-btn icon" onclick="showToast('Đang gọi...')">📞</button>
            <button class="lc-btn success" onclick="showToast('✓ Đang tạo Deal...')">🤝 Tạo Deal</button>
          </div>
        </div>
      </div>

      <!-- LEAD 4 — Đã liên hệ, cần follow-up -->
      <div class="lead-card contacted">
        <div class="lc-head">
          <div class="lc-avatar" style="background:var(--purple);">HM</div>
          <div class="lc-info">
            <div class="lc-name">Anh Hoàng Minh</div>
            <div class="lc-meta"><span>📞 0911.222.333</span><span style="color:var(--warning)">Cần follow-up</span></div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
            <span class="badge badge-blue">📞 Đã liên hệ</span>
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
          <span class="lc-source">🌐 Website · 10/03/2026</span>
          <div class="lc-actions">
            <button class="lc-btn icon" onclick="showToast('Đang nhắn...')">💬</button>
            <button class="lc-btn primary" onclick="toggleLeadExpand('lead4-expand')">Cập nhật ▾</button>
          </div>
        </div>
        <div class="lc-expand" id="lead4-expand">
          <div class="lc-action-row">
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">📞</span>Gọi lại</div>
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">📅</span>Nhắc T4</div>
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">🤝</span>Tạo Deal</div>
            <div class="lc-action-btn" onclick="selectLeadAction(this)"><span class="lc-action-icon">❌</span>Huỷ</div>
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

