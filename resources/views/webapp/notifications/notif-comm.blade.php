    <div class="notif-detail-page" id="nd-comm">
      <div class="nd-header">
        <button class="nd-back" onclick="closeNotifDetail('nd-comm')">←</button>
        <div class="nd-title">Cập nhật Hoa hồng</div>
      </div>
      <div class="nd-scroll">
        <div class="nd-hero comm-type">
          <div class="nd-hero-icon">💰</div>
          <div class="nd-hero-title">Đã nhận cọc thành công!</div>
          <div class="nd-hero-sub">Biệt thự View Đồi Chè Cầu Đất<br>Hoa hồng của bạn đang trong giai đoạn công chứng.</div>
          <div class="nd-hero-time">📅 Cập nhật 12:30 · 13/03/2026</div>
        </div>

        <!-- Stepper -->
        <div class="nd-card">
          <div class="nd-card-title">📊 Trạng thái giao dịch</div>
          <div class="nd-progress">
            <div class="nd-prog-step"><div class="nd-prog-dot done">✓</div><div class="nd-prog-label done">Chốt giá</div></div>
            <div class="nd-prog-line done"></div>
            <div class="nd-prog-step"><div class="nd-prog-dot done">✓</div><div class="nd-prog-label done">Đặt cọc</div></div>
            <div class="nd-prog-line done"></div>
            <div class="nd-prog-step"><div class="nd-prog-dot active">🖊</div><div class="nd-prog-label active">Công chứng</div></div>
            <div class="nd-prog-line"></div>
            <div class="nd-prog-step"><div class="nd-prog-dot">4</div><div class="nd-prog-label">Hoàn tất</div></div>
          </div>
          <div style="padding:0 14px 12px;font-size:12px;color:var(--text-secondary);">Dự kiến công chứng: <strong>20/03/2026</strong> · Còn 5 ngày nữa</div>
        </div>

        <!-- Thông tin hoa hồng -->
        <div class="nd-card">
          <div class="nd-card-title">💰 Chi tiết hoa hồng</div>
          <div class="nd-info-row">
            <span class="nd-info-icon">🏡</span>
            <span class="nd-info-label">BĐS</span>
            <span class="nd-info-value" style="font-size:12px;">Biệt thự Cầu Đất</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">💵</span>
            <span class="nd-info-label">Giá chốt</span>
            <span class="nd-info-value money">8,000 triệu</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">💳</span>
            <span class="nd-info-label">Tiền cọc</span>
            <span class="nd-info-value">200 triệu (đã nhận)</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">💰</span>
            <span class="nd-info-label">HH của bạn (3%)</span>
            <span class="nd-info-value money" style="font-size:16px;font-weight:800;">240 triệu</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📅</span>
            <span class="nd-info-label">Dự kiến nhận</span>
            <span class="nd-info-value primary">Sau công chứng 20/03</span>
          </div>
        </div>

        <!-- Timeline -->
        <div class="nd-card">
          <div class="nd-card-title">📋 Lịch sử giao dịch</div>
          <div class="nd-timeline">
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--success-light);">💰</div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Khách cọc 200 triệu thành công</div>
                <div class="nd-tl-sub">Admin xác nhận đã nhận tiền cọc</div>
              </div>
              <div class="nd-tl-time">13/03</div>
            </div>
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--success-light);">🎉</div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Deal chốt thành công</div>
                <div class="nd-tl-sub">Chị Thu Hà đồng ý giá 8,000 triệu</div>
              </div>
              <div class="nd-tl-time">10/03</div>
            </div>
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--primary-light);">📅</div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Xem nhà lần 2 — Khách ưng ý</div>
                <div class="nd-tl-sub">Chị Thu Hà muốn thương lượng giá</div>
              </div>
              <div class="nd-tl-time">09/03</div>
            </div>
          </div>
        </div>
        <div style="height:16px;"></div>
      </div>
      <div class="nd-action-bar">
        <button class="nd-action-btn nd-btn-outline" onclick="closeNotifDetail('nd-comm')">← Quay lại</button>
        <button class="nd-action-btn nd-btn-success" onclick="openSubpage('commissions');closeNotifDetail('nd-comm')">💰 Xem hoa hồng</button>
      </div>
    </div><!-- nd-comm -->


    <!-- ND: ADMIN APPROVE BĐS DETAIL -->
