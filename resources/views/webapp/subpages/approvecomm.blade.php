  <!-- ========== SUBPAGE: DUYỆT HOA HỒNG ========== -->
  <div class="subpage" id="subpage-approvecomm">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('approvecomm')">←</button>
      <div class="sp-title">💳 Duyệt hoa hồng</div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="showToast('Xuất sao kê hoa hồng')">📄</button>
      </div>
    </div>

    <div class="admin-hero purple-grad">
      <div class="ah-label">QUẢN LÝ HOA HỒNG — ADMIN</div>
      <div class="ah-main">Tổng chờ duyệt: 324 triệu</div>
      <div class="ah-grid">
        <div class="ah-stat"><div class="ah-stat-val">2</div><div class="ah-stat-lbl">Chờ duyệt</div></div>
        <div class="ah-stat"><div class="ah-stat-val">5</div><div class="ah-stat-lbl">Đang CN</div></div>
        <div class="ah-stat"><div class="ah-stat-val">684tr</div><div class="ah-stat-lbl">Tháng này</div></div>
        <div class="ah-stat"><div class="ah-stat-val">3</div><div class="ah-stat-lbl">Chờ cọc</div></div>
      </div>
    </div>

    <div class="sp-tabs">
      <button class="sp-tab active" onclick="spTabSwitch(this)">Chờ duyệt (2)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đang xử lý (5)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đã hoàn tất</button>
    </div>

    <div class="sp-scroll" style="padding-bottom:16px;">

      <!-- COMM APPROVE 1 — Chốt deal, cần duyệt để sang trạng thái Chờ cọc -->
      <div class="acomm-card" id="acomm1">
        <div class="acomm-head">
          <div class="acomm-icon" style="background:var(--warning-light);">🏡</div>
          <div class="acomm-info">
            <div class="acomm-name">Biệt thự View Đồi Chè Cầu Đất</div>
            <div class="acomm-sub">Chị Thu Hà · Sale: Huy Thái</div>
          </div>
          <div class="acomm-amount">
            <div class="acomm-val">240 tr</div>
            <div class="acomm-pct">3% / 8,000 tr</div>
          </div>
        </div>

        <!-- Status stepper (reuse cs-step style) -->
        <div class="acomm-stepper">
          <div class="cs-step"><div class="cs-dot done">✓</div><div class="cs-label done">Chốt giá</div></div>
          <div class="cs-line done"></div>
          <div class="cs-step"><div class="cs-dot active">⏳</div><div class="cs-label active">Chờ duyệt</div></div>
          <div class="cs-line"></div>
          <div class="cs-step"><div class="cs-dot">3</div><div class="cs-label">Đặt cọc</div></div>
          <div class="cs-line"></div>
          <div class="cs-step"><div class="cs-dot">4</div><div class="cs-label">Công chứng</div></div>
          <div class="cs-line"></div>
          <div class="cs-step"><div class="cs-dot">5</div><div class="cs-label">Hoàn tất</div></div>
        </div>

        <div class="acomm-detail">
          <div class="acomm-detail-item"><div class="acomm-detail-label">Giá chốt</div><div class="acomm-detail-val">8,000 triệu</div></div>
          <div class="acomm-detail-item"><div class="acomm-detail-label">Tổng HH (3%)</div><div class="acomm-detail-val">240 triệu</div></div>
          <div class="acomm-detail-item"><div class="acomm-detail-label">Ngày chốt</div><div class="acomm-detail-val">14/03/2026</div></div>
          <div class="acomm-detail-item"><div class="acomm-detail-label">Cọc dự kiến</div><div class="acomm-detail-val">20/03/2026</div></div>
        </div>

        <div class="acomm-breakdown">
          <div class="acomm-bl-title">Phân chia hoa hồng</div>
          <div class="acomm-bl-row">
            <div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--primary);"></div>Sale (Huy Thái)</div>
            <div><span class="acomm-bl-val">120 tr</span><span class="acomm-bl-pct">50%</span></div>
          </div>
          <div class="acomm-bl-row">
            <div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--purple);"></div>App (Đà Lạt BĐS)</div>
            <div><span class="acomm-bl-val">80 tr</span><span class="acomm-bl-pct">33%</span></div>
          </div>
          <div class="acomm-bl-row">
            <div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--teal);"></div>Broker (Huy Thái)</div>
            <div><span class="acomm-bl-val">40 tr</span><span class="acomm-bl-pct">17%</span></div>
          </div>
        </div>

        <div class="acomm-timeline">
          <div class="acomm-tl-item">
            <div class="acomm-tl-dot" style="background:var(--success);"></div>
            <div class="acomm-tl-text">Deal chốt thành công · Chị Thu Hà đồng ý giá 8,000 triệu</div>
            <div class="acomm-tl-time">14/03</div>
          </div>
          <div class="acomm-tl-item">
            <div class="acomm-tl-dot" style="background:var(--primary);"></div>
            <div class="acomm-tl-text">Huy Thái tạo commission request · Chờ Admin duyệt</div>
            <div class="acomm-tl-time">14/03</div>
          </div>
        </div>

        <div class="acomm-actions">
          <button class="acomm-btn hold" onclick="showToast('Yêu cầu kiểm tra thêm...')">⏸ Giữ lại</button>
          <button class="acomm-btn detail" onclick="showToast('Xem hợp đồng...')">📋 Hợp đồng</button>
          <button class="acomm-btn approve" onclick="approveComm('acomm1','Biệt thự Cầu Đất','240 triệu')">✓ Xác nhận & Chờ cọc</button>
        </div>
      </div>

      <!-- COMM APPROVE 2 -->
      <div class="acomm-card" id="acomm2">
        <div class="acomm-head">
          <div class="acomm-icon" style="background:var(--primary-light);">🏠</div>
          <div class="acomm-info">
            <div class="acomm-name">Nhà phố Trần Phú, P.1</div>
            <div class="acomm-sub">Anh Ngọc Lâm · Sale: Minh Khoa</div>
          </div>
          <div class="acomm-amount">
            <div class="acomm-val">84 tr</div>
            <div class="acomm-pct">3% / 2,800 tr</div>
          </div>
        </div>

        <div class="acomm-stepper">
          <div class="cs-step"><div class="cs-dot done">✓</div><div class="cs-label done">Chốt giá</div></div>
          <div class="cs-line done"></div>
          <div class="cs-step"><div class="cs-dot active">⏳</div><div class="cs-label active">Chờ duyệt</div></div>
          <div class="cs-line"></div>
          <div class="cs-step"><div class="cs-dot">3</div><div class="cs-label">Đặt cọc</div></div>
          <div class="cs-line"></div>
          <div class="cs-step"><div class="cs-dot">4</div><div class="cs-label">Công chứng</div></div>
          <div class="cs-line"></div>
          <div class="cs-step"><div class="cs-dot">5</div><div class="cs-label">Hoàn tất</div></div>
        </div>

        <div class="acomm-detail">
          <div class="acomm-detail-item"><div class="acomm-detail-label">Giá chốt</div><div class="acomm-detail-val">2,800 triệu</div></div>
          <div class="acomm-detail-item"><div class="acomm-detail-label">Tổng HH (3%)</div><div class="acomm-detail-val">84 triệu</div></div>
          <div class="acomm-detail-item"><div class="acomm-detail-label">Ngày chốt</div><div class="acomm-detail-val">15/03/2026</div></div>
          <div class="acomm-detail-item"><div class="acomm-detail-label">Cọc dự kiến</div><div class="acomm-detail-val">17/03/2026</div></div>
        </div>

        <div class="acomm-breakdown">
          <div class="acomm-bl-title">Phân chia hoa hồng</div>
          <div class="acomm-bl-row">
            <div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--primary);"></div>Sale (Minh Khoa)</div>
            <div><span class="acomm-bl-val">42 tr</span><span class="acomm-bl-pct">50%</span></div>
          </div>
          <div class="acomm-bl-row">
            <div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--purple);"></div>App (Đà Lạt BĐS)</div>
            <div><span class="acomm-bl-val">28 tr</span><span class="acomm-bl-pct">33%</span></div>
          </div>
          <div class="acomm-bl-row">
            <div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--teal);"></div>Broker</div>
            <div><span class="acomm-bl-val">14 tr</span><span class="acomm-bl-pct">17%</span></div>
          </div>
        </div>

        <div class="acomm-actions">
          <button class="acomm-btn hold" onclick="showToast('Giữ lại kiểm tra...')">⏸ Giữ lại</button>
          <button class="acomm-btn detail" onclick="showToast('Xem hợp đồng...')">📋 Hợp đồng</button>
          <button class="acomm-btn approve" onclick="approveComm('acomm2','Nhà phố Trần Phú','84 triệu')">✓ Xác nhận & Chờ cọc</button>
        </div>
      </div>

      <!-- Đang xử lý section -->
      <div class="user-divider" style="margin-top:12px;">
        <span>Đang trong quá trình xử lý</span>
        <span class="badge badge-blue">5 giao dịch</span>
      </div>

      <!-- Comm processing 1 — Đang công chứng -->
      <div class="acomm-card" style="opacity:.85;">
        <div class="acomm-head">
          <div class="acomm-icon" style="background:var(--success-light);">🌱</div>
          <div class="acomm-info">
            <div class="acomm-name">Đất ở Phường 3, 200m²</div>
            <div class="acomm-sub">Chị Lan Hương · Sale: Huy Thái</div>
          </div>
          <div class="acomm-amount">
            <div class="acomm-val" style="color:var(--warning);">54 tr</div>
            <div class="acomm-pct">Đang CN</div>
          </div>
        </div>
        <div class="acomm-stepper">
          <div class="cs-step"><div class="cs-dot done">✓</div><div class="cs-label done">Chốt giá</div></div>
          <div class="cs-line done"></div>
          <div class="cs-step"><div class="cs-dot done">✓</div><div class="cs-label done">Đặt cọc</div></div>
          <div class="cs-line done"></div>
          <div class="cs-step"><div class="cs-dot active">🖊</div><div class="cs-label active">Công chứng</div></div>
          <div class="cs-line"></div>
          <div class="cs-step"><div class="cs-dot">5</div><div class="cs-label">Hoàn tất</div></div>
        </div>
        <div class="acomm-actions">
          <button class="acomm-btn detail" onclick="showToast('Xem chi tiết...')">📋 Chi tiết</button>
          <button class="acomm-btn approve" onclick="showToast('✓ Cập nhật: Hoàn tất công chứng!')">✓ Xác nhận Hoàn tất</button>
        </div>
      </div>

      <div style="height:16px;"></div>
    </div>
  </div><!-- end subpage-approvecomm -->

