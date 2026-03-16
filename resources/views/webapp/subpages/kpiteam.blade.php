<!-- ========== SUBPAGE: KPI & TEAM ========== -->
<div class="subpage" id="subpage-kpiteam">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('kpiteam')">←</button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> KPI & Team Sale</span></div>
    <div class="sp-actions">
      <button class="sp-action-btn" onclick="showToast('Xuất báo cáo PDF')"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></button>
    </div>
  </div>

  <!-- Team hero -->
  <div class="team-hero">
    <div class="th-label">THÁNG 3 / 2026 — TEAM SALE</div>
    <div class="th-title">Đà Lạt BĐS · 5 Sale</div>
    <div class="th-grid">
      <div class="th-stat"><div class="th-stat-val">12</div><div class="th-stat-lbl">Deals tháng</div></div>
      <div class="th-stat"><div class="th-stat-val">3</div><div class="th-stat-lbl">Đã chốt</div></div>
      <div class="th-stat"><div class="th-stat-val">1.2 tỷ</div><div class="th-stat-lbl">Doanh số</div></div>
      <div class="th-stat"><div class="th-stat-val">36tr</div><div class="th-stat-lbl">HH phát sinh</div></div>
    </div>
  </div>

  <!-- Leaderboard mini -->
  <div class="leaderboard">
    <div class="lb-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 14 20 9 15 4"/><path d="M4 20v-7a4 4 0 0 1 4-4h12"/></svg> BXH Tháng này</span></div>
    <div class="lb-row">
      <div class="rank-badge rank-1">1</div>
      <div class="lb-name">Huy Thái</div>
      <div class="lb-bar-wrap"><div class="lb-bar-bg"><div class="lb-bar-fill" style="width:100%;background:var(--success)"></div></div></div>
      <div class="lb-val" style="color:var(--success)">3 chốt</div>
    </div>
    <div class="lb-row">
      <div class="rank-badge rank-2">2</div>
      <div class="lb-name">Minh Khoa</div>
      <div class="lb-bar-wrap"><div class="lb-bar-bg"><div class="lb-bar-fill" style="width:70%;background:var(--primary)"></div></div></div>
      <div class="lb-val" style="color:var(--primary)">2 chốt</div>
    </div>
    <div class="lb-row">
      <div class="rank-badge rank-3">3</div>
      <div class="lb-name">Anh Linh</div>
      <div class="lb-bar-wrap"><div class="lb-bar-bg"><div class="lb-bar-fill" style="width:35%;background:var(--warning)"></div></div></div>
      <div class="lb-val" style="color:var(--warning)">1 chốt</div>
    </div>
    <div class="lb-row">
      <div class="rank-badge rank-n">4</div>
      <div class="lb-name">Thu Nga</div>
      <div class="lb-bar-wrap"><div class="lb-bar-bg"><div class="lb-bar-fill" style="width:20%;background:var(--text-tertiary)"></div></div></div>
      <div class="lb-val" style="color:var(--text-tertiary)">0 chốt</div>
    </div>
    <div class="lb-row">
      <div class="rank-badge rank-n">5</div>
      <div class="lb-name">Đức Huy</div>
      <div class="lb-bar-wrap"><div class="lb-bar-bg"><div class="lb-bar-fill" style="width:10%;background:var(--text-tertiary)"></div></div></div>
      <div class="lb-val" style="color:var(--text-tertiary)">0 chốt</div>
    </div>
  </div>

  <div class="sp-tabs">
    <button class="sp-tab active" onclick="spTabSwitch(this)">Tất cả (5)</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Đang active</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Cần hỗ trợ</button>
  </div>

  <div class="sp-scroll" style="padding-bottom:16px;">

    <!-- SALE CARD 1 — Top performer -->
    <div class="sale-card">
      <div class="sc-head">
        <div class="sc-avatar" style="background:var(--primary);">
          HT
          <div class="sc-online"></div>
        </div>
        <div class="sc-info">
          <div class="sc-name">Huy Thái</div>
          <div class="sc-role">
            <span class="badge badge-blue" style="font-size:9px;"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>Sale</span>
            <span>· Online</span>
          </div>
        </div>
        <div style="text-align:right;">
          <div class="rank-badge rank-1" style="margin-left:auto;margin-bottom:4px;">#1</div>
          <div style="font-size:10px;color:var(--success);font-weight:600;display:inline-flex;align-items:center;gap:2px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2c0 6-8 10-8 10s8 4 8 10c0-6 8-10 8-10S12 8 12 2z"/></svg> Top tháng</div>
        </div>
      </div>

      <div class="sc-kpi-grid">
        <div class="sc-kpi">
          <div class="sc-kpi-val" style="color:var(--danger);">3</div>
          <div class="sc-kpi-lbl">Lead mới</div>
        </div>
        <div class="sc-kpi">
          <div class="sc-kpi-val" style="color:var(--primary);">7</div>
          <div class="sc-kpi-lbl">Đang chăm</div>
        </div>
        <div class="sc-kpi">
          <div class="sc-kpi-val" style="color:var(--success);">3</div>
          <div class="sc-kpi-lbl">Đã chốt</div>
        </div>
        <div class="sc-kpi">
          <div class="sc-kpi-val" style="color:var(--success);">450tr</div>
          <div class="sc-kpi-lbl">HH dự kiến</div>
        </div>
      </div>

      <div class="sc-perf">
        <div class="sc-perf-row">
          <span class="sc-perf-label">Tỉ lệ chốt</span>
          <div class="sc-perf-bar"><div class="sc-perf-fill" style="width:75%;background:var(--success);"></div></div>
          <span class="sc-perf-val" style="color:var(--success);">75%</span>
        </div>
        <div class="sc-perf-row">
          <span class="sc-perf-label">Phản hồi lead</span>
          <div class="sc-perf-bar"><div class="sc-perf-fill" style="width:90%;background:var(--primary);"></div></div>
          <span class="sc-perf-val" style="color:var(--primary);">2.1h</span>
        </div>
        <div class="sc-perf-row">
          <span class="sc-perf-label">Lịch hẹn/tuần</span>
          <div class="sc-perf-bar"><div class="sc-perf-fill" style="width:80%;background:var(--purple);"></div></div>
          <span class="sc-perf-val" style="color:var(--purple);">4.2</span>
        </div>
      </div>

      <div class="sc-footer">
        <button class="sc-btn" onclick="toggleScDetail('sc1-detail')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Xem chi tiết</span></button>
        <button class="sc-btn primary" onclick="showToast('Assign lead cho Huy Thái...');openSubpage('assignlead')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg> Assign Lead</span></button>
      </div>

      <div class="sc-detail" id="sc1-detail">
        <div class="sc-detail-tabs">
          <button class="sc-dtab active" onclick="switchScTab(this)">Deals (7)</button>
          <button class="sc-dtab" onclick="switchScTab(this)">Lịch hẹn</button>
          <button class="sc-dtab" onclick="switchScTab(this)">Hoạt động</button>
        </div>
        <div style="padding:10px 13px;">
          <!-- deal list mini -->
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
            <span style="font-size:11px;font-weight:700;color:var(--text-tertiary);">DEAL ĐANG CHĂM</span>
            <span class="badge badge-purple">7 active</span>
          </div>
          <div style="display:flex;flex-direction:column;gap:6px;">
            <div style="display:flex;align-items:center;gap:8px;padding:8px 10px;background:var(--bg-secondary);border-radius:var(--radius-sm);">
              <div style="width:6px;height:6px;border-radius:50%;background:var(--warning);flex-shrink:0;"></div>
              <div style="flex:1;font-size:12px;font-weight:600;color:var(--text-primary);">Chị Thu Hà · Biệt thự Cầu Đất</div>
              <span class="badge badge-amber" style="font-size:9px;display:inline-flex;align-items:center;gap:2px;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Đàm phán</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;padding:8px 10px;background:var(--bg-secondary);border-radius:var(--radius-sm);">
              <div style="width:6px;height:6px;border-radius:50%;background:var(--primary);flex-shrink:0;"></div>
              <div style="flex:1;font-size:12px;font-weight:600;color:var(--text-primary);">Anh Minh Tuấn · Đất Yersin</div>
              <span class="badge badge-purple" style="font-size:9px;display:inline-flex;align-items:center;gap:2px;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Chăm sóc</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;padding:8px 10px;background:var(--bg-secondary);border-radius:var(--radius-sm);">
              <div style="width:6px;height:6px;border-radius:50%;background:var(--teal);flex-shrink:0;"></div>
              <div style="flex:1;font-size:12px;font-weight:600;color:var(--text-primary);">Anh Ngọc Lâm · Nhà phố</div>
              <span class="badge badge-blue" style="font-size:9px;">Mới tạo</span>
            </div>
          </div>
          <!-- timeline -->
          <div style="margin-top:12px;">
            <div style="font-size:11px;font-weight:700;color:var(--text-tertiary);margin-bottom:6px;">HOẠT ĐỘNG GẦN ĐÂY</div>
            <div class="sc-tl-item">
              <div class="sc-tl-dot" style="background:var(--success);"></div>
              <div class="sc-tl-text">Chốt deal Biệt thự Cầu Đất · 8,000 triệu</div>
              <div class="sc-tl-time">Hôm nay</div>
            </div>
            <div class="sc-tl-item">
              <div class="sc-tl-dot" style="background:var(--primary);"></div>
              <div class="sc-tl-text">Đặt lịch xem nhà với Anh Minh Tuấn</div>
              <div class="sc-tl-time">Hôm nay</div>
            </div>
            <div class="sc-tl-item">
              <div class="sc-tl-dot" style="background:var(--warning);"></div>
              <div class="sc-tl-text">Gửi 2 BĐS mới cho Chị Thu Hà</div>
              <div class="sc-tl-time">14/03</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- SALE CARD 2 -->
    <div class="sale-card">
      <div class="sc-head">
        <div class="sc-avatar" style="background:var(--teal);">
          MK
          <div class="sc-online"></div>
        </div>
        <div class="sc-info">
          <div class="sc-name">Minh Khoa</div>
          <div class="sc-role"><span class="badge badge-blue" style="font-size:9px;"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>Sale</span><span>· Online</span></div>
        </div>
        <div style="text-align:right;">
          <div class="rank-badge rank-2" style="margin-left:auto;margin-bottom:4px;">#2</div>
        </div>
      </div>
      <div class="sc-kpi-grid">
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--danger);">2</div><div class="sc-kpi-lbl">Lead mới</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--primary);">5</div><div class="sc-kpi-lbl">Đang chăm</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--success);">2</div><div class="sc-kpi-lbl">Đã chốt</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--success);">210tr</div><div class="sc-kpi-lbl">HH dự kiến</div></div>
      </div>
      <div class="sc-perf">
        <div class="sc-perf-row">
          <span class="sc-perf-label">Tỉ lệ chốt</span>
          <div class="sc-perf-bar"><div class="sc-perf-fill" style="width:60%;background:var(--primary);"></div></div>
          <span class="sc-perf-val">60%</span>
        </div>
        <div class="sc-perf-row">
          <span class="sc-perf-label">Phản hồi lead</span>
          <div class="sc-perf-bar"><div class="sc-perf-fill" style="width:75%;background:var(--primary);"></div></div>
          <span class="sc-perf-val">3.5h</span>
        </div>
      </div>
      <div class="sc-footer">
        <button class="sc-btn" onclick="toggleScDetail('sc2-detail')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Chi tiết</span></button>
        <button class="sc-btn primary" onclick="openSubpage('assignlead')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg> Assign Lead</span></button>
      </div>
      <div class="sc-detail" id="sc2-detail">
        <div style="padding:12px 13px;font-size:12px;color:var(--text-secondary);">5 deal đang chăm · 2 lịch xem tuần này · Chưa có vấn đề.</div>
      </div>
    </div>

    <!-- SALE CARD 3 — Cần hỗ trợ -->
    <div class="sale-card" style="border-color:var(--warning);border-left:3px solid var(--warning);">
      <div class="sc-head">
        <div class="sc-avatar" style="background:#f59e0b;">AL</div>
        <div class="sc-info">
          <div class="sc-name">Anh Linh</div>
          <div class="sc-role"><span class="badge badge-blue" style="font-size:9px;"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>Sale</span><span style="color:var(--warning);font-weight:600;">· Cần hỗ trợ</span></div>
        </div>
        <div style="text-align:right;">
          <div class="rank-badge rank-3" style="margin-left:auto;margin-bottom:4px;">#3</div>
        </div>
      </div>
      <div class="sc-kpi-grid">
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--danger);">1</div><div class="sc-kpi-lbl">Lead mới</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--primary);">3</div><div class="sc-kpi-lbl">Đang chăm</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--success);">1</div><div class="sc-kpi-lbl">Đã chốt</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--warning);">85tr</div><div class="sc-kpi-lbl">HH dự kiến</div></div>
      </div>
      <div style="padding:8px 13px;background:var(--warning-light);border-top:1px solid #fde68a;">
        <div style="font-size:11px;font-weight:600;color:var(--warning);display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Deal #2 bị stuck 5 ngày — Chưa cập nhật trạng thái</div>
      </div>
      <div class="sc-perf">
        <div class="sc-perf-row">
          <span class="sc-perf-label">Tỉ lệ chốt</span>
          <div class="sc-perf-bar"><div class="sc-perf-fill" style="width:40%;background:var(--warning);"></div></div>
          <span class="sc-perf-val" style="color:var(--warning);">40%</span>
        </div>
        <div class="sc-perf-row">
          <span class="sc-perf-label">Phản hồi lead</span>
          <div class="sc-perf-bar"><div class="sc-perf-fill" style="width:30%;background:var(--danger);"></div></div>
          <span class="sc-perf-val" style="color:var(--danger);">8.2h <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
        </div>
      </div>
      <div class="sc-footer">
        <button class="sc-btn" onclick="toggleScDetail('sc3-detail')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Chi tiết</span></button>
        <button class="sc-btn danger" onclick="showToast('Mở chat hỗ trợ Anh Linh...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Hỗ trợ ngay</span></button>
      </div>
      <div class="sc-detail" id="sc3-detail">
        <div style="padding:12px 13px;">
          <div style="font-size:11px;font-weight:700;color:var(--danger);margin-bottom:8px;">VẤN ĐỀ CẦN XỬ LÝ</div>
          <div style="padding:10px 12px;background:var(--danger-light);border-radius:var(--radius-sm);font-size:12px;color:var(--danger);margin-bottom:8px;">
            Deal "Nhà phố Lâm Viên" — Khách đã xem 5 ngày trước nhưng chưa cập nhật phản hồi. Cần nhắc nhở Sale cập nhật ngay.
          </div>
          <button style="width:100%;padding:10px;background:var(--warning);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:700;cursor:pointer;" onclick="showToast('Đã nhắc Anh Linh cập nhật')"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-2.99 10.45-11 14C2.99 14.45 2 4 2 4l10-2 10 2z"/></svg> Nhắc nhở cập nhật</span></button>
        </div>
      </div>
    </div>

    <!-- SALE CARD 4 -->
    <div class="sale-card">
      <div class="sc-head">
        <div class="sc-avatar" style="background:#8b5cf6;">TN</div>
        <div class="sc-info">
          <div class="sc-name">Thu Nga</div>
          <div class="sc-role"><span class="badge badge-blue" style="font-size:9px;"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>Sale</span><span style="color:var(--text-tertiary);">· Offline 2h</span></div>
        </div>
        <div class="rank-badge rank-n" style="margin-left:auto;">#4</div>
      </div>
      <div class="sc-kpi-grid">
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--danger);">1</div><div class="sc-kpi-lbl">Lead mới</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--primary);">2</div><div class="sc-kpi-lbl">Đang chăm</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--text-tertiary);">0</div><div class="sc-kpi-lbl">Đã chốt</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--text-tertiary);">0</div><div class="sc-kpi-lbl">HH</div></div>
      </div>
      <div class="sc-perf">
        <div class="sc-perf-row">
          <span class="sc-perf-label">Tỉ lệ chốt</span>
          <div class="sc-perf-bar"><div class="sc-perf-fill" style="width:0%;background:var(--text-tertiary);"></div></div>
          <span class="sc-perf-val" style="color:var(--text-tertiary);">0%</span>
        </div>
        <div class="sc-perf-row">
          <span class="sc-perf-label">Phản hồi lead</span>
          <div class="sc-perf-bar"><div class="sc-perf-fill" style="width:55%;background:var(--primary);"></div></div>
          <span class="sc-perf-val">4.1h</span>
        </div>
      </div>
      <div class="sc-footer">
        <button class="sc-btn" onclick="toggleScDetail('sc4-detail')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Chi tiết</span></button>
        <button class="sc-btn primary" onclick="openSubpage('assignlead')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg> Assign Lead</span></button>
      </div>
      <div class="sc-detail" id="sc4-detail">
        <div style="padding:12px 13px;font-size:12px;color:var(--text-secondary);">Tháng đầu · 2 deal đang chăm · Cần thêm thời gian làm quen quy trình.</div>
      </div>
    </div>

    <!-- SALE CARD 5 -->
    <div class="sale-card">
      <div class="sc-head">
        <div class="sc-avatar" style="background:#059669;">DH</div>
        <div class="sc-info">
          <div class="sc-name">Đức Huy</div>
          <div class="sc-role"><span class="badge badge-teal" style="font-size:9px;">Sale mới</span><span style="color:var(--text-tertiary);">· Offline</span></div>
        </div>
        <div class="rank-badge rank-n" style="margin-left:auto;">#5</div>
      </div>
      <div class="sc-kpi-grid">
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--text-tertiary);">0</div><div class="sc-kpi-lbl">Lead mới</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--text-tertiary);">1</div><div class="sc-kpi-lbl">Đang chăm</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--text-tertiary);">0</div><div class="sc-kpi-lbl">Đã chốt</div></div>
        <div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--text-tertiary);">0</div><div class="sc-kpi-lbl">HH</div></div>
      </div>
      <div style="padding:8px 13px;font-size:12px;color:var(--text-tertiary);border-top:1px solid var(--border-light);">
        Mới onboard tuần này · Đang trong giai đoạn học việc
      </div>
      <div class="sc-footer">
        <button class="sc-btn" onclick="showToast('Mở chat onboarding...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Hỗ trợ</span></button>
        <button class="sc-btn primary" onclick="openSubpage('assignlead')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg> Assign Lead thử</span></button>
      </div>
    </div>

    <div style="height:20px;"></div>
  </div><!-- end sp-scroll -->
</div><!-- end subpage-kpiteam -->


