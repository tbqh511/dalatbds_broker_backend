  <!-- ========== SUBPAGE: KPI & TEAM ========== -->
  <div class="subpage" id="subpage-kpiteam">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('kpiteam')">←</button>
      <div class="sp-title">📊 KPI & Team Sale</div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="showToast('Xuất báo cáo PDF')">📄</button>
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
      <div class="lb-title">🏆 BXH Tháng này</div>
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
              <span class="badge badge-blue" style="font-size:9px;">💼 Sale</span>
              <span>· Online</span>
            </div>
          </div>
          <div style="text-align:right;">
            <div class="rank-badge rank-1" style="margin-left:auto;margin-bottom:4px;">#1</div>
            <div style="font-size:10px;color:var(--success);font-weight:600;">🔥 Top tháng</div>
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
          <button class="sc-btn" onclick="toggleScDetail('sc1-detail')">📋 Xem chi tiết</button>
          <button class="sc-btn primary" onclick="showToast('Assign lead cho Huy Thái...');openSubpage('assignlead')">🎯 Assign Lead</button>
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
                <span class="badge badge-amber" style="font-size:9px;">⚡ Đàm phán</span>
              </div>
              <div style="display:flex;align-items:center;gap:8px;padding:8px 10px;background:var(--bg-secondary);border-radius:var(--radius-sm);">
                <div style="width:6px;height:6px;border-radius:50%;background:var(--primary);flex-shrink:0;"></div>
                <div style="flex:1;font-size:12px;font-weight:600;color:var(--text-primary);">Anh Minh Tuấn · Đất Yersin</div>
                <span class="badge badge-purple" style="font-size:9px;">🤝 Chăm sóc</span>
              </div>
              <div style="display:flex;align-items:center;gap:8px;padding:8px 10px;background:var(--bg-secondary);border-radius:var(--radius-sm);">
                <div style="width:6px;height:6px;border-radius:50%;background:var(--teal);flex-shrink:0;"></div>
                <div style="flex:1;font-size:12px;font-weight:600;color:var(--text-primary);">Anh Ngọc Lâm · Nhà phố</div>
                <span class="badge badge-blue" style="font-size:9px;">🆕 Mới tạo</span>
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
            <div class="sc-role"><span class="badge badge-blue" style="font-size:9px;">💼 Sale</span><span>· Online</span></div>
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
          <button class="sc-btn" onclick="toggleScDetail('sc2-detail')">📋 Chi tiết</button>
          <button class="sc-btn primary" onclick="openSubpage('assignlead')">🎯 Assign Lead</button>
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
            <div class="sc-role"><span class="badge badge-blue" style="font-size:9px;">💼 Sale</span><span style="color:var(--warning);font-weight:600;">· Cần hỗ trợ</span></div>
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
          <div style="font-size:11px;font-weight:600;color:var(--warning);">⚠ Deal #2 bị stuck 5 ngày — Chưa cập nhật trạng thái</div>
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
            <span class="sc-perf-val" style="color:var(--danger);">8.2h ⚠</span>
          </div>
        </div>
        <div class="sc-footer">
          <button class="sc-btn" onclick="toggleScDetail('sc3-detail')">📋 Chi tiết</button>
          <button class="sc-btn danger" onclick="showToast('Mở chat hỗ trợ Anh Linh...')">⚡ Hỗ trợ ngay</button>
        </div>
        <div class="sc-detail" id="sc3-detail">
          <div style="padding:12px 13px;">
            <div style="font-size:11px;font-weight:700;color:var(--danger);margin-bottom:8px;">VẤN ĐỀ CẦN XỬ LÝ</div>
            <div style="padding:10px 12px;background:var(--danger-light);border-radius:var(--radius-sm);font-size:12px;color:var(--danger);margin-bottom:8px;">
              Deal "Nhà phố Lâm Viên" — Khách đã xem 5 ngày trước nhưng chưa cập nhật phản hồi. Cần nhắc nhở Sale cập nhật ngay.
            </div>
            <button style="width:100%;padding:10px;background:var(--warning);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:700;cursor:pointer;" onclick="showToast('Đã nhắc Anh Linh cập nhật')">📣 Nhắc nhở cập nhật</button>
          </div>
        </div>
      </div>

      <!-- SALE CARD 4 -->
      <div class="sale-card">
        <div class="sc-head">
          <div class="sc-avatar" style="background:#8b5cf6;">TN</div>
          <div class="sc-info">
            <div class="sc-name">Thu Nga</div>
            <div class="sc-role"><span class="badge badge-blue" style="font-size:9px;">💼 Sale</span><span style="color:var(--text-tertiary);">· Offline 2h</span></div>
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
          <button class="sc-btn" onclick="toggleScDetail('sc4-detail')">📋 Chi tiết</button>
          <button class="sc-btn primary" onclick="openSubpage('assignlead')">🎯 Assign Lead</button>
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
            <div class="sc-role"><span class="badge badge-teal" style="font-size:9px;">🆕 Sale mới</span><span style="color:var(--text-tertiary);">· Offline</span></div>
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
          <button class="sc-btn" onclick="showToast('Mở chat onboarding...')">💬 Hỗ trợ</button>
          <button class="sc-btn primary" onclick="openSubpage('assignlead')">🎯 Assign Lead thử</button>
        </div>
      </div>

      <div style="height:20px;"></div>
    </div><!-- end sp-scroll -->
  </div><!-- end subpage-kpiteam -->


