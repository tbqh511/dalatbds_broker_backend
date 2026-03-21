<!-- ========== SUBPAGE: HOA HỒNG ========== -->
<div class="subpage" id="subpage-commissions">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('commissions')">←</button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> Hoa hồng của tôi</span></div>
  </div>

  <!-- Hero primary -->
  <div class="comm-hero">
    <div class="comm-hero-label">TỔNG HOA HỒNG DỰ KIẾN</div>
    <div class="comm-hero-amount" id="commHeroTotal">—</div>
    <div class="comm-hero-grid">
      <div class="comm-hero-item"><div class="comm-hero-item-label">Đã nhận</div><div class="comm-hero-item-val" id="commHeroReceived">—</div></div>
      <div class="comm-hero-item"><div class="comm-hero-item-label">Đang chờ</div><div class="comm-hero-item-val" id="commHeroPending">—</div></div>
      <div class="comm-hero-item"><div class="comm-hero-item-label">Sắp về</div><div class="comm-hero-item-val" id="commHeroUpcoming">—</div></div>
    </div>
  </div>

  <!-- Mini chart -->
  <div style="background:var(--bg-card);border-bottom:1px solid var(--border);padding:12px 13px 4px;">
    <div style="font-size:11px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Hoa hồng theo tháng (triệu)</div>
    <div class="mini-chart" id="commChart">
      <div class="mc-bar" style="height:5%"><div class="mc-bar-tip">0</div></div>
      <div class="mc-bar" style="height:5%"><div class="mc-bar-tip">0</div></div>
      <div class="mc-bar" style="height:5%"><div class="mc-bar-tip">0</div></div>
      <div class="mc-bar" style="height:5%"><div class="mc-bar-tip">0</div></div>
      <div class="mc-bar" style="height:5%"><div class="mc-bar-tip">0</div></div>
      <div class="mc-bar active" style="height:5%"><div class="mc-bar-tip">0</div></div>
    </div>
    <div style="display:flex;justify-content:space-between;padding:0 0 8px;" id="commChartLabels">
      <span style="font-size:9px;color:var(--text-tertiary)">—</span>
      <span style="font-size:9px;color:var(--text-tertiary)">—</span>
      <span style="font-size:9px;color:var(--text-tertiary)">—</span>
      <span style="font-size:9px;color:var(--text-tertiary)">—</span>
      <span style="font-size:9px;color:var(--text-tertiary)">—</span>
      <span style="font-size:9px;color:var(--text-tertiary);font-weight:700;color:var(--primary)">—</span>
    </div>
  </div>

  <!-- Tabs -->
  <div class="sp-tabs">
    <button class="sp-tab active" id="commTabAll"            onclick="commTabSwitch(this,'')">Tất cả</button>
    <button class="sp-tab"        id="commTabPendingDeposit" onclick="commTabSwitch(this,'pending_deposit')">Chờ cọc</button>
    <button class="sp-tab"        id="commTabDeposited"      onclick="commTabSwitch(this,'deposited')">Đã cọc</button>
    <button class="sp-tab"        id="commTabCompleted"      onclick="commTabSwitch(this,'completed')">Hoàn tất</button>
  </div>

  <div class="sp-scroll">
    <!-- Loading state -->
    <div id="commLoading" style="padding:40px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">
      <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
      Đang tải...
    </div>

    <!-- Empty state -->
    <div id="commEmpty" style="display:none;padding:48px 24px;text-align:center;">
      <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      <div style="font-size:14px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Chưa có hoa hồng nào</div>
      <div style="font-size:12px;color:var(--text-tertiary);">Hoa hồng được tạo khi deal được chốt thành công</div>
    </div>

    <!-- Dynamic list -->
    <div id="commList" style="display:none;"></div>

    <div style="height:16px"></div>
  </div>
</div>

<!-- Status Update Sheet -->
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
