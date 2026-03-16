    <div class="notif-detail-page" id="nd-deal">
      <div class="nd-header">
        <button class="nd-back" onclick="closeNotifDetail('nd-deal')">←</button>
        <div class="nd-title">Deal bị stuck</div>
      </div>
      <div class="nd-scroll">
        <div class="nd-hero admin-type">
          <div class="nd-hero-icon">⚠️</div>
          <div class="nd-hero-title">Deal không có cập nhật</div>
          <div class="nd-hero-sub">Anh Linh · Nhà phố Lâm Viên<br>Không cập nhật trong 5 ngày liên tiếp.</div>
          <div class="nd-hero-time">📅 Lần cuối cập nhật 10/03/2026</div>
        </div>

        <div class="nd-urgent red">
          <span class="nd-urgent-icon">🚨</span>
          <div class="nd-urgent-text">Deal này có nguy cơ mất khách! Cần liên hệ Sale Anh Linh ngay để hỗ trợ xử lý.</div>
        </div>

        <div class="nd-card">
          <div class="nd-card-title">🤝 Thông tin Deal</div>
          <div class="nd-info-row">
            <span class="nd-info-icon">👤</span>
            <span class="nd-info-label">Sale phụ trách</span>
            <span class="nd-info-value warning">Anh Linh</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">👥</span>
            <span class="nd-info-label">Khách hàng</span>
            <span class="nd-info-value">Trần Thị Bích</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">🏡</span>
            <span class="nd-info-label">BĐS</span>
            <span class="nd-info-value">Nhà phố Đường 3/4, P.Lâm Viên</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">💰</span>
            <span class="nd-info-label">Giá trị deal</span>
            <span class="nd-info-value money">1,500 triệu</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📊</span>
            <span class="nd-info-label">Trạng thái</span>
            <span class="nd-info-value warning">Đã xem · Chưa phản hồi</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">⏰</span>
            <span class="nd-info-label">Stuck</span>
            <span class="nd-info-value danger">5 ngày không update</span>
          </div>
        </div>

        <!-- Timeline -->
        <div class="nd-card">
          <div class="nd-card-title">📋 Lịch sử Deal</div>
          <div class="nd-timeline">
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--warning-light);">⏰</div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Không có cập nhật (5 ngày)</div>
                <div class="nd-tl-sub">Sale chưa cập nhật kết quả sau khi xem nhà</div>
              </div>
              <div class="nd-tl-time">Hôm nay</div>
            </div>
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--primary-light);">📅</div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Khách xem nhà thực tế</div>
                <div class="nd-tl-sub">Nhà phố Đường 3/4 — cần kết quả phản hồi</div>
              </div>
              <div class="nd-tl-time">10/03</div>
            </div>
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--success-light);">📤</div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Gửi 2 BĐS cho khách</div>
                <div class="nd-tl-sub">Nhà phố Lâm Viên + Đất Yersin</div>
              </div>
              <div class="nd-tl-time">08/03</div>
            </div>
          </div>
        </div>

        <!-- Action for Sale Admin -->
        <div class="nd-card">
          <div class="nd-card-title">⚡ Hành động hỗ trợ</div>
          <div style="padding:10px 14px 12px;display:flex;flex-direction:column;gap:8px;">
            <button style="padding:11px;background:var(--warning-light);color:var(--warning);border:1.5px solid #fde68a;border-radius:var(--radius-md);font-size:13px;font-weight:700;cursor:pointer;" onclick="showToast('Đã gửi nhắc nhở cho Anh Linh')">📣 Gửi nhắc nhở cho Sale</button>
            <button style="padding:11px;background:var(--primary-light);color:var(--primary-dark);border:1.5px solid transparent;border-radius:var(--radius-md);font-size:13px;font-weight:700;cursor:pointer;" onclick="showToast('Mở chat hỗ trợ Anh Linh...')">💬 Chat hỗ trợ Sale</button>
            <button style="padding:11px;background:var(--danger-light);color:var(--danger);border:1.5px solid transparent;border-radius:var(--radius-md);font-size:13px;font-weight:700;cursor:pointer;" onclick="showToast('Thu hồi và assign lại Deal...')">🔄 Thu hồi & Assign lại</button>
          </div>
        </div>
        <div style="height:16px;"></div>
      </div>
      <div class="nd-action-bar">
        <button class="nd-action-btn nd-btn-outline" onclick="closeNotifDetail('nd-deal')">← Quay lại</button>
        <button class="nd-action-btn nd-btn-primary" onclick="openSubpage('kpiteam');closeNotifDetail('nd-deal')">📊 Xem KPI Team</button>
      </div>
    </div><!-- nd-deal -->



