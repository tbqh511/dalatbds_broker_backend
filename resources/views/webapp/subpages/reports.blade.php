<!-- ========== SUBPAGE: BÁO CÁO TỔNG HỢP ========== -->
<div class="subpage" id="subpage-reports">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('reports')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Báo cáo tổng hợp</span></div>
    <div class="sp-actions">
      <button class="sp-action-btn" onclick="showToast('Tính năng xuất PDF đang phát triển')"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></button>
    </div>
  </div>

  <div class="admin-hero green-grad">
    <div class="ah-label" id="rp-hero-label">TỔNG QUAN THÁNG NÀY</div>
    <div class="ah-main" id="rp-hero-revenue">—</div>
    <div class="ah-grid">
      <div class="ah-stat"><div class="ah-stat-val" id="rp-stat-deals">—</div><div class="ah-stat-lbl">Deals chốt</div></div>
      <div class="ah-stat"><div class="ah-stat-val" id="rp-stat-comm">—</div><div class="ah-stat-lbl">HH phát sinh</div></div>
      <div class="ah-stat"><div class="ah-stat-val" id="rp-stat-live">—</div><div class="ah-stat-lbl">BĐS live</div></div>
      <div class="ah-stat"><div class="ah-stat-val" id="rp-stat-views">—</div><div class="ah-stat-lbl">Lượt xem</div></div>
    </div>
  </div>

  <!-- Period tabs -->
  <div class="report-period">
    <button class="rp-tab active" onclick="switchRpTab(this,'month')">Tháng này</button>
    <button class="rp-tab" onclick="switchRpTab(this,'q1')">Quý 1</button>
    <button class="rp-tab" onclick="switchRpTab(this,'6months')">6 tháng</button>
    <button class="rp-tab" onclick="switchRpTab(this,'year')">Năm {{ date('Y') }}</button>
  </div>

  <div class="sp-scroll" style="padding-bottom:16px;">

    <!-- Loading overlay -->
    <div id="rp-loading" style="display:none;text-align:center;padding:32px 0;color:var(--text-secondary);font-size:13px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 1s linear infinite;display:inline-block;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
      &nbsp;Đang tải dữ liệu...
    </div>

    <!-- Metric cards -->
    <div class="metrics-grid" id="rp-metrics-grid">
      <div class="metric-card">
        <div class="mc2-icon" style="background:var(--success-light);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
        <div class="mc2-label">Doanh số GD</div>
        <div class="mc2-val" id="rp-mc-revenue-val">—</div>
        <div class="mc2-delta" id="rp-mc-revenue-delta">—</div>
      </div>
      <div class="metric-card">
        <div class="mc2-icon" style="background:var(--primary-light);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <div class="mc2-label">Deals đã chốt</div>
        <div class="mc2-val" id="rp-mc-deals-val">—</div>
        <div class="mc2-delta" id="rp-mc-deals-delta">—</div>
      </div>
      <div class="metric-card">
        <div class="mc2-icon" style="background:var(--warning-light);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
        <div class="mc2-label">Hoa hồng phát sinh</div>
        <div class="mc2-val" id="rp-mc-comm-val">—</div>
        <div class="mc2-delta" id="rp-mc-comm-delta">—</div>
      </div>
      <div class="metric-card">
        <div class="mc2-icon" style="background:var(--purple-light);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <div class="mc2-label">Khách hàng mới</div>
        <div class="mc2-val" id="rp-mc-cust-val">—</div>
        <div class="mc2-delta" id="rp-mc-cust-delta">—</div>
      </div>
    </div>

    <!-- Doanh số chart -->
    <div class="report-chart-card">
      <div class="rc-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Doanh số theo tháng (tỷ đồng)</span></div>
      <div class="rc-sub">6 tháng gần nhất</div>
      <div class="bar-chart" id="rp-bar-chart">
        <!-- Filled by JS -->
        <div style="width:100%;text-align:center;padding:24px 0;color:var(--text-tertiary);font-size:12px;">Đang tải...</div>
      </div>
      <div style="display:flex;gap:12px;margin-top:6px;font-size:11px;color:var(--text-secondary);">
        <div style="display:flex;align-items:center;gap:5px;"><div style="width:10px;height:10px;border-radius:2px;background:var(--success);"></div>Tháng hiện tại</div>
        <div style="display:flex;align-items:center;gap:5px;"><div style="width:10px;height:10px;border-radius:2px;background:var(--primary-light);"></div>Tháng trước</div>
      </div>
    </div>

    <!-- Tỉ lệ deals -->
    <div class="report-chart-card">
      <div class="rc-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Tỉ lệ chuyển đổi Deal</span></div>
      <div class="rc-sub">Lead → Deal → Chốt kỳ này</div>
      <div class="stat-ring-wrap">
        <div class="stat-ring" id="rp-stat-ring" style="--pct:0%;">
          <div class="stat-ring-inner">
            <div class="stat-ring-val" id="rp-ring-val">0%</div>
            <div class="stat-ring-lbl">Chốt deal</div>
          </div>
        </div>
        <div class="stat-ring-legend">
          <div class="srl-row">
            <div class="srl-dot-label"><div class="srl-dot" style="background:var(--primary);"></div>Lead nhận</div>
            <div class="srl-val" id="rp-funnel-leads">—</div>
          </div>
          <div class="srl-row">
            <div class="srl-dot-label"><div class="srl-dot" style="background:var(--purple);"></div>Tạo Deal</div>
            <div class="srl-val" id="rp-funnel-deals">—</div>
          </div>
          <div class="srl-row">
            <div class="srl-dot-label"><div class="srl-dot" style="background:var(--success);"></div>Đã chốt</div>
            <div class="srl-val" id="rp-funnel-closed">—</div>
          </div>
          <div class="srl-row">
            <div class="srl-dot-label"><div class="srl-dot" style="background:var(--danger);"></div>Bỏ cuộc</div>
            <div class="srl-val" id="rp-funnel-lost">—</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Brokers -->
    <div class="report-chart-card">
      <div class="rc-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/></svg> <span id="rp-top-brokers-title">Top Broker tháng này</span></span></div>
      <div class="rc-sub">Xếp hạng theo doanh số giao dịch</div>
      <table class="top-table">
        <thead>
          <tr><th>#</th><th>Broker</th><th>Deals</th><th>Doanh số</th></tr>
        </thead>
        <tbody id="rp-top-brokers">
          <tr><td colspan="4" style="text-align:center;color:var(--text-tertiary);padding:16px 0;font-size:12px;">Đang tải...</td></tr>
        </tbody>
      </table>
    </div>

    <!-- BDS loại phổ biến -->
    <div class="report-chart-card">
      <div class="rc-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg> Loại BĐS giao dịch nhiều nhất</span></div>
      <div class="rc-sub">Tỉ lệ theo số giao dịch chốt</div>
      <div style="display:flex;flex-direction:column;gap:8px;margin-top:8px;" id="rp-prop-types">
        <div style="text-align:center;color:var(--text-tertiary);font-size:12px;padding:8px 0;">Đang tải...</div>
      </div>
    </div>

    <div style="height:16px;"></div>
  </div>
</div><!-- end subpage-reports -->

