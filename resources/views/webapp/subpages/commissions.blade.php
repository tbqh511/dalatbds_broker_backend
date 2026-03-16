<!-- ========== SUBPAGE: HOA HỒNG ========== -->
<div class="subpage" id="subpage-commissions">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('commissions')">←</button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> Hoa hồng của tôi</span></div>
  </div>
  <!-- Hero green -->
  <div class="comm-hero">
    <div class="comm-hero-label">TỔNG HOA HỒNG DỰ KIẾN</div>
    <div class="comm-hero-amount">450 triệu</div>
    <div class="comm-hero-grid">
      <div class="comm-hero-item"><div class="comm-hero-item-label">Đã nhận</div><div class="comm-hero-item-val">120 tr</div></div>
      <div class="comm-hero-item"><div class="comm-hero-item-label">Đang chờ</div><div class="comm-hero-item-val">276 tr</div></div>
      <div class="comm-hero-item"><div class="comm-hero-item-label">Sắp về</div><div class="comm-hero-item-val">54 tr</div></div>
    </div>
  </div>
  <!-- mini chart -->
  <div style="background:var(--bg-card);border-bottom:1px solid var(--border);padding:12px 13px 4px;">
    <div style="font-size:11px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Hoa hồng theo tháng (triệu)</div>
    <div class="mini-chart">
      <div class="mc-bar" style="height:30%"><div class="mc-bar-tip">45</div></div>
      <div class="mc-bar" style="height:55%"><div class="mc-bar-tip">80</div></div>
      <div class="mc-bar" style="height:40%"><div class="mc-bar-tip">60</div></div>
      <div class="mc-bar" style="height:70%"><div class="mc-bar-tip">105</div></div>
      <div class="mc-bar" style="height:50%"><div class="mc-bar-tip">75</div></div>
      <div class="mc-bar active" style="height:90%"><div class="mc-bar-tip">135</div></div>
    </div>
    <div style="display:flex;justify-content:space-between;padding:0 0 8px;">
      <span style="font-size:9px;color:var(--text-tertiary)">T10</span>
      <span style="font-size:9px;color:var(--text-tertiary)">T11</span>
      <span style="font-size:9px;color:var(--text-tertiary)">T12</span>
      <span style="font-size:9px;color:var(--text-tertiary)">T1</span>
      <span style="font-size:9px;color:var(--text-tertiary)">T2</span>
      <span style="font-size:9px;color:var(--text-tertiary);font-weight:700;color:var(--success)">T3</span>
    </div>
  </div>
  <div class="sp-tabs">
    <button class="sp-tab active" onclick="spTabSwitch(this)">Tất cả</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Chờ cọc</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Đã cọc</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Hoàn tất</button>
  </div>
  <div class="sp-scroll">

    <!-- COMM 1 — Đang công chứng -->
    <div class="comm-card">
      <div class="comm-card-head">
        <div class="comm-card-icon" style="background:var(--warning-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div class="comm-card-info">
          <div class="comm-card-name">Biệt thự View Đồi Chè Cầu Đất</div>
          <div class="comm-card-sub">Chị Thu Hà · Giá chốt: 8,000 triệu</div>
        </div>
        <div class="comm-card-amount">
          <div class="comm-card-val">240 tr</div>
          <div class="comm-card-pct">3% hoa hồng</div>
        </div>
      </div>
      <div class="comm-stepper">
        <div class="cs-step"><div class="cs-dot done">✓</div><div class="cs-label done">Chốt giá</div></div>
        <div class="cs-line done"></div>
        <div class="cs-step"><div class="cs-dot done">✓</div><div class="cs-label done">Đặt cọc</div></div>
        <div class="cs-line done"></div>
        <div class="cs-step"><div class="cs-dot active"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div><div class="cs-label active">Công chứng</div></div>
        <div class="cs-line"></div>
        <div class="cs-step"><div class="cs-dot">4</div><div class="cs-label">Hoàn tất</div></div>
      </div>
      <div class="comm-breakdown">
        <div class="comm-breakdown-item"><div class="comm-breakdown-label">HH của tôi (Sale)</div><div class="comm-breakdown-val" style="color:var(--success)">240 triệu</div></div>
        <div class="comm-breakdown-item"><div class="comm-breakdown-label">HH App</div><div class="comm-breakdown-val">80 triệu</div></div>
        <div class="comm-breakdown-item"><div class="comm-breakdown-label">Trạng thái</div><div class="comm-breakdown-val" style="color:var(--warning)">Đang công chứng</div></div>
        <div class="comm-breakdown-item"><div class="comm-breakdown-label">Dự kiến nhận</div><div class="comm-breakdown-val">20/03/2026</div></div>
      </div>
      <div class="comm-card-footer">
        <span class="comm-footer-date">Chốt 10/03/2026 · Cọc 12/03</span>
        <div class="comm-footer-actions">
          <button class="comm-action-btn primary" onclick="showToast('Xem chi tiết hợp đồng')">Xem hợp đồng</button>
        </div>
      </div>
    </div>

    <!-- COMM 2 — Chờ cọc -->
    <div class="comm-card">
      <div class="comm-card-head">
        <div class="comm-card-icon" style="background:var(--primary-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div class="comm-card-info">
          <div class="comm-card-name">Nhà phố Trần Phú, P.1</div>
          <div class="comm-card-sub">Anh Ngọc Lâm · Chốt: 2,800 triệu</div>
        </div>
        <div class="comm-card-amount">
          <div class="comm-card-val">84 tr</div>
          <div class="comm-card-pct">3% hoa hồng</div>
        </div>
      </div>
      <div class="comm-stepper">
        <div class="cs-step"><div class="cs-dot done">✓</div><div class="cs-label done">Chốt giá</div></div>
        <div class="cs-line done"></div>
        <div class="cs-step"><div class="cs-dot active"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div class="cs-label active">Chờ cọc</div></div>
        <div class="cs-line"></div>
        <div class="cs-step"><div class="cs-dot">3</div><div class="cs-label">Công chứng</div></div>
        <div class="cs-line"></div>
        <div class="cs-step"><div class="cs-dot">4</div><div class="cs-label">Hoàn tất</div></div>
      </div>
      <div class="comm-breakdown">
        <div class="comm-breakdown-item"><div class="comm-breakdown-label">HH của tôi</div><div class="comm-breakdown-val" style="color:var(--warning)">84 triệu (chờ)</div></div>
        <div class="comm-breakdown-item"><div class="comm-breakdown-label">Cọc dự kiến</div><div class="comm-breakdown-val">17/03/2026</div></div>
      </div>
      <div class="comm-card-footer">
        <span class="comm-footer-date">Chốt 14/03/2026</span>
        <div class="comm-footer-actions">
          <button class="comm-action-btn" onclick="showToast('Đang gọi khách...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Nhắc cọc</span></button>
        </div>
      </div>
    </div>

    <!-- COMM 3 — Đã hoàn tất -->
    <div class="comm-card" style="border-color:var(--success);border-left:3px solid var(--success)">
      <div class="comm-card-head">
        <div class="comm-card-icon" style="background:var(--success-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4.97-5 9-8.58 9-12a9 9 0 0 0-18 0c0 3.42 4.03 7 9 12z"/><circle cx="12" cy="10" r="3"/></svg></div>
        <div class="comm-card-info">
          <div class="comm-card-name">Đất ở Phường 3, 200m²</div>
          <div class="comm-card-sub">Chị Lan Hương · Giá: 1,800 triệu</div>
        </div>
        <div class="comm-card-amount">
          <div class="comm-card-val" style="color:var(--success)">54 tr ✓</div>
          <div class="comm-card-pct">3% · Đã nhận</div>
        </div>
      </div>
      <div class="comm-stepper">
        <div class="cs-step"><div class="cs-dot done">✓</div><div class="cs-label done">Chốt giá</div></div>
        <div class="cs-line done"></div>
        <div class="cs-step"><div class="cs-dot done">✓</div><div class="cs-label done">Đặt cọc</div></div>
        <div class="cs-line done"></div>
        <div class="cs-step"><div class="cs-dot done">✓</div><div class="cs-label done">Công chứng</div></div>
        <div class="cs-line done"></div>
        <div class="cs-step"><div class="cs-dot final">✓</div><div class="cs-label done">Hoàn tất</div></div>
      </div>
      <div class="comm-breakdown">
        <div class="comm-breakdown-item"><div class="comm-breakdown-label">Đã nhận</div><div class="comm-breakdown-val" style="color:var(--success)">54 triệu ✓</div></div>
        <div class="comm-breakdown-item"><div class="comm-breakdown-label">Ngày nhận</div><div class="comm-breakdown-val">07/03/2026</div></div>
      </div>
      <div class="comm-card-footer">
        <span class="comm-footer-date" style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Hoàn tất 07/03/2026</span>
        <div class="comm-footer-actions">
          <button class="comm-action-btn" onclick="showToast('Tải PDF hóa đơn')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg> Xuất PDF</span></button>
        </div>
      </div>
    </div>

    <div style="height:16px"></div>
  </div>
</div>

<!-- Status Update Sheet (for deals) -->
<div class="status-sheet" id="statusSheet">
  <div class="status-sheet-inner">
    <div class="ss-handle"></div>
    <div class="ss-title">Cập nhật trạng thái Deal</div>
    <div class="ss-options">
      <div class="ss-opt" onclick="selectStatus(this)">
        <div class="ss-opt-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></div>
        <div class="ss-opt-body"><div class="ss-opt-title">Đã gửi thêm BĐS</div><div class="ss-opt-sub">sent_info — Tiếp tục chăm sóc</div></div>
      </div>
      <div class="ss-opt" onclick="selectStatus(this)">
        <div class="ss-opt-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        <div class="ss-opt-body"><div class="ss-opt-title">Đặt lịch xem nhà</div><div class="ss-opt-sub">booking_created — Khách đồng ý xem</div></div>
      </div>
      <div class="ss-opt" onclick="selectStatus(this)">
        <div class="ss-opt-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>
        <div class="ss-opt-body"><div class="ss-opt-title">Đã xem — Khách ưng ý</div><div class="ss-opt-sub">viewed_success — Tiến đến thương lượng</div></div>
      </div>
      <div class="ss-opt" onclick="selectStatus(this)">
        <div class="ss-opt-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
        <div class="ss-opt-body"><div class="ss-opt-title">Đang thương lượng giá</div><div class="ss-opt-sub">negotiating — Hai bên đang đàm phán</div></div>
      </div>
      <div class="ss-opt" onclick="selectStatus(this)">
        <div class="ss-opt-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="14" rx="2"/><polyline points="16 3 12 7 8 3"/><line x1="12" y1="12" x2="12" y2="17"/><line x1="9.5" y1="14.5" x2="14.5" y2="14.5"/></svg></div>
        <div class="ss-opt-body"><div class="ss-opt-title">Chờ tài chính / ngân hàng</div><div class="ss-opt-sub">waiting_finance — Khách đang vay vốn</div></div>
      </div>
      <div class="ss-opt" onclick="selectStatus(this)">
        <div class="ss-opt-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>
        <div class="ss-opt-body"><div class="ss-opt-title">Khách không ưng BĐS này</div><div class="ss-opt-sub">viewed_failed — Cần bắt buộc nhập lý do</div></div>
      </div>
    </div>
    <textarea class="ss-note" rows="2" placeholder="Ghi chú bắt buộc nếu khách không ưng..."></textarea>
    <button class="ss-confirm" onclick="confirmStatus()">✓ Xác nhận cập nhật</button>
  </div>
</div>

