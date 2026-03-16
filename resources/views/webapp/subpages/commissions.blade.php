  <!-- ========== SUBPAGE: HOA HỒNG ========== -->
  <div class="subpage" id="subpage-commissions">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('commissions')">←</button>
      <div class="sp-title">💰 Hoa hồng của tôi</div>
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
          <div class="comm-card-icon" style="background:var(--warning-light)">🏡</div>
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
          <div class="cs-step"><div class="cs-dot active">🖊</div><div class="cs-label active">Công chứng</div></div>
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
          <div class="comm-card-icon" style="background:var(--primary-light)">🏠</div>
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
          <div class="cs-step"><div class="cs-dot active">⏳</div><div class="cs-label active">Chờ cọc</div></div>
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
            <button class="comm-action-btn" onclick="showToast('Đang gọi khách...')">📞 Nhắc cọc</button>
          </div>
        </div>
      </div>

      <!-- COMM 3 — Đã hoàn tất -->
      <div class="comm-card" style="border-color:var(--success);border-left:3px solid var(--success)">
        <div class="comm-card-head">
          <div class="comm-card-icon" style="background:var(--success-light)">🌱</div>
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
          <span class="comm-footer-date">✅ Hoàn tất 07/03/2026</span>
          <div class="comm-footer-actions">
            <button class="comm-action-btn" onclick="showToast('Tải PDF hóa đơn')">📄 Xuất PDF</button>
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
          <div class="ss-opt-icon">📤</div>
          <div class="ss-opt-body"><div class="ss-opt-title">Đã gửi thêm BĐS</div><div class="ss-opt-sub">sent_info — Tiếp tục chăm sóc</div></div>
        </div>
        <div class="ss-opt" onclick="selectStatus(this)">
          <div class="ss-opt-icon">📅</div>
          <div class="ss-opt-body"><div class="ss-opt-title">Đặt lịch xem nhà</div><div class="ss-opt-sub">booking_created — Khách đồng ý xem</div></div>
        </div>
        <div class="ss-opt" onclick="selectStatus(this)">
          <div class="ss-opt-icon">😊</div>
          <div class="ss-opt-body"><div class="ss-opt-title">Đã xem — Khách ưng ý</div><div class="ss-opt-sub">viewed_success — Tiến đến thương lượng</div></div>
        </div>
        <div class="ss-opt" onclick="selectStatus(this)">
          <div class="ss-opt-icon">⚡</div>
          <div class="ss-opt-body"><div class="ss-opt-title">Đang thương lượng giá</div><div class="ss-opt-sub">negotiating — Hai bên đang đàm phán</div></div>
        </div>
        <div class="ss-opt" onclick="selectStatus(this)">
          <div class="ss-opt-icon">🏦</div>
          <div class="ss-opt-body"><div class="ss-opt-title">Chờ tài chính / ngân hàng</div><div class="ss-opt-sub">waiting_finance — Khách đang vay vốn</div></div>
        </div>
        <div class="ss-opt" onclick="selectStatus(this)">
          <div class="ss-opt-icon">😞</div>
          <div class="ss-opt-body"><div class="ss-opt-title">Khách không ưng BĐS này</div><div class="ss-opt-sub">viewed_failed — Cần bắt buộc nhập lý do</div></div>
        </div>
      </div>
      <textarea class="ss-note" rows="2" placeholder="Ghi chú bắt buộc nếu khách không ưng..."></textarea>
      <button class="ss-confirm" onclick="confirmStatus()">✓ Xác nhận cập nhật</button>
    </div>
  </div>

