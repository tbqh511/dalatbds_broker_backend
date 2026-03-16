  <!-- ========== SUBPAGE: BÁO CÁO TỔNG HỢP ========== -->
  <div class="subpage" id="subpage-reports">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('reports')">←</button>
      <div class="sp-title">📈 Báo cáo tổng hợp</div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="showToast('Xuất PDF báo cáo tháng 3')">📄</button>
      </div>
    </div>

    <div class="admin-hero green-grad">
      <div class="ah-label">TỔNG QUAN THÁNG 3 / 2026</div>
      <div class="ah-main">Doanh số: 22.8 tỷ</div>
      <div class="ah-grid">
        <div class="ah-stat"><div class="ah-stat-val">12</div><div class="ah-stat-lbl">Deals chốt</div></div>
        <div class="ah-stat"><div class="ah-stat-val">684tr</div><div class="ah-stat-lbl">HH phát sinh</div></div>
        <div class="ah-stat"><div class="ah-stat-val">156</div><div class="ah-stat-lbl">BĐS live</div></div>
        <div class="ah-stat"><div class="ah-stat-val">8,849</div><div class="ah-stat-lbl">Lượt xem</div></div>
      </div>
    </div>

    <!-- Period tabs -->
    <div class="report-period">
      <button class="rp-tab active" onclick="switchRpTab(this)">Tháng này</button>
      <button class="rp-tab" onclick="switchRpTab(this)">Quý 1</button>
      <button class="rp-tab" onclick="switchRpTab(this)">6 tháng</button>
      <button class="rp-tab" onclick="switchRpTab(this)">Năm 2026</button>
    </div>

    <div class="sp-scroll" style="padding-bottom:16px;">

      <!-- Metric cards -->
      <div class="metrics-grid">
        <div class="metric-card">
          <div class="mc2-icon" style="background:var(--success-light);">💰</div>
          <div class="mc2-label">Doanh số GD</div>
          <div class="mc2-val">22.8 tỷ</div>
          <div class="mc2-delta up">↑ +18.4% so tháng trước</div>
        </div>
        <div class="metric-card">
          <div class="mc2-icon" style="background:var(--primary-light);">🤝</div>
          <div class="mc2-label">Deals đã chốt</div>
          <div class="mc2-val">12</div>
          <div class="mc2-delta up">↑ +4 so tháng trước</div>
        </div>
        <div class="metric-card">
          <div class="mc2-icon" style="background:var(--warning-light);">💳</div>
          <div class="mc2-label">Hoa hồng phát sinh</div>
          <div class="mc2-val">684 tr</div>
          <div class="mc2-delta up">↑ +22% so tháng trước</div>
        </div>
        <div class="metric-card">
          <div class="mc2-icon" style="background:var(--purple-light);">👥</div>
          <div class="mc2-label">Khách hàng mới</div>
          <div class="mc2-val">47</div>
          <div class="mc2-delta dn">↓ -3 so tháng trước</div>
        </div>
      </div>

      <!-- Doanh số chart -->
      <div class="report-chart-card">
        <div class="rc-title">📊 Doanh số theo tháng (tỷ đồng)</div>
        <div class="rc-sub">6 tháng gần nhất</div>
        <div class="bar-chart">
          <div class="bc-col">
            <div class="bc-val">12.4</div>
            <div class="bc-bars"><div class="bc-seg" style="height:54%;background:var(--primary-light);"></div></div>
            <div class="bc-label">T10</div>
          </div>
          <div class="bc-col">
            <div class="bc-val">15.1</div>
            <div class="bc-bars"><div class="bc-seg" style="height:66%;background:var(--primary-light);"></div></div>
            <div class="bc-label">T11</div>
          </div>
          <div class="bc-col">
            <div class="bc-val">18.7</div>
            <div class="bc-bars"><div class="bc-seg" style="height:82%;background:var(--primary-light);"></div></div>
            <div class="bc-label">T12</div>
          </div>
          <div class="bc-col">
            <div class="bc-val">14.2</div>
            <div class="bc-bars"><div class="bc-seg" style="height:62%;background:var(--primary-light);"></div></div>
            <div class="bc-label">T1</div>
          </div>
          <div class="bc-col">
            <div class="bc-val">19.3</div>
            <div class="bc-bars"><div class="bc-seg" style="height:85%;background:var(--primary-light);"></div></div>
            <div class="bc-label">T2</div>
          </div>
          <div class="bc-col">
            <div class="bc-val" style="color:var(--success);font-weight:700;">22.8</div>
            <div class="bc-bars"><div class="bc-seg" style="height:100%;background:var(--success);"></div></div>
            <div class="bc-label" style="color:var(--success);font-weight:700;">T3</div>
          </div>
        </div>
        <!-- Legend -->
        <div style="display:flex;gap:12px;margin-top:6px;font-size:11px;color:var(--text-secondary);">
          <div style="display:flex;align-items:center;gap:5px;"><div style="width:10px;height:10px;border-radius:2px;background:var(--success);"></div>Tháng hiện tại</div>
          <div style="display:flex;align-items:center;gap:5px;"><div style="width:10px;height:10px;border-radius:2px;background:var(--primary-light);"></div>Tháng trước</div>
        </div>
      </div>

      <!-- Tỉ lệ deals -->
      <div class="report-chart-card">
        <div class="rc-title">🎯 Tỉ lệ chuyển đổi Deal</div>
        <div class="rc-sub">Lead → Deal → Chốt tháng này</div>
        <div class="stat-ring-wrap">
          <div class="stat-ring" style="--pct:68%;">
            <div class="stat-ring-inner">
              <div class="stat-ring-val">68%</div>
              <div class="stat-ring-lbl">Chốt deal</div>
            </div>
          </div>
          <div class="stat-ring-legend">
            <div class="srl-row">
              <div class="srl-dot-label"><div class="srl-dot" style="background:var(--primary);"></div>Lead nhận</div>
              <div class="srl-val">47</div>
            </div>
            <div class="srl-row">
              <div class="srl-dot-label"><div class="srl-dot" style="background:var(--purple);"></div>Tạo Deal</div>
              <div class="srl-val">23</div>
            </div>
            <div class="srl-row">
              <div class="srl-dot-label"><div class="srl-dot" style="background:var(--success);"></div>Đã chốt</div>
              <div class="srl-val">12 <span style="font-size:10px;color:var(--success);">↑68%</span></div>
            </div>
            <div class="srl-row">
              <div class="srl-dot-label"><div class="srl-dot" style="background:var(--danger);"></div>Bỏ cuộc</div>
              <div class="srl-val">11</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Brokers -->
      <div class="report-chart-card">
        <div class="rc-title">🏆 Top Broker tháng 3</div>
        <div class="rc-sub">Xếp hạng theo doanh số giao dịch</div>
        <table class="top-table">
          <thead>
            <tr><th>#</th><th>Broker</th><th>Deals</th><th>Doanh số</th></tr>
          </thead>
          <tbody>
            <tr>
              <td><span class="rank-badge rank-1">1</span></td>
              <td style="font-weight:600;">Huy Thái</td>
              <td>3</td>
              <td style="color:var(--success);">8.0 tỷ</td>
            </tr>
            <tr>
              <td><span class="rank-badge rank-2">2</span></td>
              <td style="font-weight:600;">Minh Khoa</td>
              <td>2</td>
              <td style="color:var(--primary);">4.6 tỷ</td>
            </tr>
            <tr>
              <td><span class="rank-badge rank-3">3</span></td>
              <td style="font-weight:600;">Anh Linh</td>
              <td>1</td>
              <td>2.8 tỷ</td>
            </tr>
            <tr>
              <td><span class="rank-badge rank-n">4</span></td>
              <td>Thu Nga</td>
              <td>1</td>
              <td>1.8 tỷ</td>
            </tr>
            <tr>
              <td><span class="rank-badge rank-n">5</span></td>
              <td>Đức Huy</td>
              <td>0</td>
              <td style="color:var(--text-tertiary);">—</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- BDS loại phổ biến -->
      <div class="report-chart-card">
        <div class="rc-title">🏡 Loại BĐS giao dịch nhiều nhất</div>
        <div class="rc-sub">Tỉ lệ theo số giao dịch chốt</div>
        <div style="display:flex;flex-direction:column;gap:8px;margin-top:8px;">
          <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:12px;min-width:70px;color:var(--text-secondary);">Đất ở</span>
            <div style="flex:1;height:8px;background:var(--border);border-radius:4px;overflow:hidden;"><div style="height:100%;width:55%;background:var(--primary);border-radius:4px;"></div></div>
            <span style="font-size:12px;font-weight:700;color:var(--primary);min-width:28px;">55%</span>
          </div>
          <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:12px;min-width:70px;color:var(--text-secondary);">Biệt thự</span>
            <div style="flex:1;height:8px;background:var(--border);border-radius:4px;overflow:hidden;"><div style="height:100%;width:28%;background:var(--purple);border-radius:4px;"></div></div>
            <span style="font-size:12px;font-weight:700;color:var(--purple);min-width:28px;">28%</span>
          </div>
          <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:12px;min-width:70px;color:var(--text-secondary);">Nhà phố</span>
            <div style="flex:1;height:8px;background:var(--border);border-radius:4px;overflow:hidden;"><div style="height:100%;width:17%;background:var(--teal);border-radius:4px;"></div></div>
            <span style="font-size:12px;font-weight:700;color:var(--teal);min-width:28px;">17%</span>
          </div>
        </div>
      </div>

      <div style="height:16px;"></div>
    </div>
  </div><!-- end subpage-reports -->


