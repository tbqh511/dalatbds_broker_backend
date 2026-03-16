    <div class="notif-detail-page" id="nd-booking">
      <div class="nd-header">
        <button class="nd-back" onclick="closeNotifDetail('nd-booking')">←</button>
        <div class="nd-title">Chi tiết Lịch hẹn</div>
      </div>
      <div class="nd-scroll">
        <div class="nd-hero booking-type">
          <div class="nd-hero-icon">📅</div>
          <div class="nd-hero-title">Lịch xem nhà ngày mai</div>
          <div class="nd-hero-sub">Anh Minh Tuấn · Đường Yersin, P.Cam Ly<br>Chuẩn bị kỹ để tạo ấn tượng tốt!</div>
          <div class="nd-hero-time">📅 16/03/2026 · 09:00 sáng</div>
        </div>

        <div class="nd-urgent amber">
          <span class="nd-urgent-icon">⏰</span>
          <div class="nd-urgent-text">Còn ~18 tiếng nữa. Hãy xác nhận với khách và chủ nhà trước 21:00 hôm nay.</div>
        </div>

        <!-- Thông tin lịch hẹn -->
        <div class="nd-card">
          <div class="nd-card-title">📅 Thông tin lịch hẹn</div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📅</span>
            <span class="nd-info-label">Ngày giờ</span>
            <span class="nd-info-value primary">16/03/2026 · 09:00</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">👤</span>
            <span class="nd-info-label">Khách hàng</span>
            <span class="nd-info-value">Anh Minh Tuấn</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📞</span>
            <span class="nd-info-label">SĐT khách</span>
            <span class="nd-info-value primary">0901.234.567</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">🏡</span>
            <span class="nd-info-label">BĐS</span>
            <span class="nd-info-value">Đất ở Đường Yersin</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📍</span>
            <span class="nd-info-label">Địa chỉ</span>
            <span class="nd-info-value">Đường Yersin, P.Cam Ly</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">🏠</span>
            <span class="nd-info-label">Chủ nhà</span>
            <span class="nd-info-value">Nguyễn Văn A · 0912.345.678</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📝</span>
            <span class="nd-info-label">Ghi chú</span>
            <span class="nd-info-value" style="font-size:11px;font-weight:400;color:var(--text-secondary);">Khách đi xe gầm cao, hẹn trước cổng nhà</span>
          </div>
        </div>

        <!-- Checklist chuẩn bị -->
        <div class="nd-card">
          <div class="nd-card-title">✅ Checklist chuẩn bị</div>
          <div style="padding:8px 14px 12px;">
            <div style="display:flex;flex-direction:column;gap:8px;">
              <label style="display:flex;align-items:center;gap:10px;font-size:13px;cursor:pointer;">
                <input type="checkbox" checked style="width:16px;height:16px;accent-color:var(--primary);"> Xác nhận lại với khách hàng
              </label>
              <label style="display:flex;align-items:center;gap:10px;font-size:13px;cursor:pointer;">
                <input type="checkbox" checked style="width:16px;height:16px;accent-color:var(--primary);"> Xác nhận với chủ nhà
              </label>
              <label style="display:flex;align-items:center;gap:10px;font-size:13px;cursor:pointer;">
                <input type="checkbox" style="width:16px;height:16px;accent-color:var(--primary);"> Chuẩn bị hồ sơ pháp lý (sổ đỏ)
              </label>
              <label style="display:flex;align-items:center;gap:10px;font-size:13px;cursor:pointer;">
                <input type="checkbox" style="width:16px;height:16px;accent-color:var(--primary);"> Chụp ảnh thực tế mới nhất
              </label>
              <label style="display:flex;align-items:center;gap:10px;font-size:13px;cursor:pointer;">
                <input type="checkbox" style="width:16px;height:16px;accent-color:var(--primary);"> In bản đồ / chuẩn bị đường đi
              </label>
            </div>
          </div>
        </div>

        <!-- Kết quả lần xem trước -->
        <div class="nd-card">
          <div class="nd-card-title">📊 Lịch sử xem nhà của khách này</div>
          <div class="nd-timeline">
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--danger-light);">✕</div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Lần 1: Nhà phố Trần Phú</div>
                <div class="nd-tl-sub">Không ưng — "Hẻm nhỏ, xa chợ, không tiện sinh hoạt"</div>
              </div>
              <div class="nd-tl-time">13/03</div>
            </div>
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--primary-light);">📅</div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Lần 2: Đất Đường Yersin (lần này)</div>
                <div class="nd-tl-sub">Khách đã xem ảnh và thích vị trí, muốn xem thực tế</div>
              </div>
              <div class="nd-tl-time">Ngày mai</div>
            </div>
          </div>
        </div>

        <div style="height:16px;"></div>
      </div>
      <div class="nd-action-bar">
        <button class="nd-action-btn nd-btn-outline" onclick="showToast('Mở form dời lịch...')">🔄 Dời lịch</button>
        <button class="nd-action-btn nd-btn-warning" onclick="showToast('Đang gọi khách...')">📞 Gọi xác nhận</button>
        <button class="nd-action-btn nd-btn-success" onclick="openSubpage('bookings');closeNotifDetail('nd-booking')">📅 Quản lý lịch</button>
      </div>
    </div><!-- nd-booking -->


    <!-- ND: BĐS DETAIL -->
