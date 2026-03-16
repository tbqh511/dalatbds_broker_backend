    <div class="notif-detail-page" id="nd-lead">
      <div class="nd-header">
        <button class="nd-back" onclick="closeNotifDetail('nd-lead')">←</button>
        <div class="nd-title">Chi tiết Lead</div>
        <button style="font-size:12px;font-weight:600;color:var(--text-tertiary);background:none;border:none;cursor:pointer;" onclick="showToast('Đã đánh dấu đã đọc')">Đã đọc</button>
      </div>
      <div class="nd-scroll">
        <div class="nd-hero lead-type">
          <div class="nd-hero-icon">🎯</div>
          <div class="nd-hero-title">Lead mới được assign</div>
          <div class="nd-hero-sub">Admin đã phân công Lead này cho bạn.<br>Liên hệ sớm để tăng tỉ lệ chuyển đổi!</div>
          <div class="nd-hero-time">⏱ 2 giờ trước · 15/03/2026 11:23</div>
        </div>

        <div class="nd-urgent red">
          <span class="nd-urgent-icon">⚠️</span>
          <div class="nd-urgent-text">Chưa được liên hệ! Hệ thống sẽ tự nhắc nhở sau 24h. Lead hot có ngân sách cao — ưu tiên xử lý ngay.</div>
        </div>

        <!-- Thông tin lead -->
        <div class="nd-card">
          <div class="nd-card-title">👤 Thông tin khách hàng</div>
          <div class="nd-info-row">
            <span class="nd-info-icon">🏷</span>
            <span class="nd-info-label">Họ tên</span>
            <span class="nd-info-value">Nguyễn Văn Ssdf</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📞</span>
            <span class="nd-info-label">Số điện thoại</span>
            <span class="nd-info-value primary">0912.345.678</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">🏡</span>
            <span class="nd-info-label">Loại BĐS</span>
            <span class="nd-info-value">Biệt thự, Khách sạn</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">🎯</span>
            <span class="nd-info-label">Nhu cầu</span>
            <span class="nd-info-value">Mua để ở + đầu tư</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">💰</span>
            <span class="nd-info-label">Ngân sách</span>
            <span class="nd-info-value money">3 tỷ – 5 tỷ</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📍</span>
            <span class="nd-info-label">Khu vực</span>
            <span class="nd-info-value">P.Lâm Viên, Đà Lạt</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📢</span>
            <span class="nd-info-label">Nguồn</span>
            <span class="nd-info-value">Facebook Ads</span>
          </div>
          <div class="nd-tags">
            <span class="nd-tag">Biệt thự</span>
            <span class="nd-tag">Khách sạn</span>
            <span class="nd-tag">3–5 tỷ</span>
            <span class="nd-tag">Lâm Viên</span>
            <span class="nd-tag" style="background:var(--danger-light);color:var(--danger);">🔥 Ưu tiên cao</span>
          </div>
        </div>

        <!-- Cập nhật nhanh -->
        <div class="nd-card">
          <div class="nd-card-title">⚡ Xử lý nhanh</div>
          <div style="padding:10px 14px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;">
              <button class="nd-action-btn nd-btn-primary" style="font-size:12px;" onclick="showToast('Đang gọi 0912.345.678...')">📞 Gọi ngay</button>
              <button class="nd-action-btn nd-btn-outline" style="font-size:12px;" onclick="showToast('Mở Zalo...')">💬 Nhắn Zalo</button>
              <button class="nd-action-btn nd-btn-success" style="font-size:12px;" onclick="showToast('✓ Đã xác nhận đã liên hệ')">✓ Đã liên hệ</button>
              <button class="nd-action-btn nd-btn-purple" style="font-size:12px;" onclick="showToast('Đang tạo Deal...')">🤝 Tạo Deal</button>
            </div>
            <textarea class="nd-note" rows="2" placeholder="Ghi chú nhanh về khách (VD: Thích view đẹp, không thích hẻm nhỏ...)"></textarea>
            <button style="width:100%;padding:11px;background:var(--primary);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:700;cursor:pointer;" onclick="showToast('✓ Đã lưu ghi chú lead')">Lưu ghi chú</button>
          </div>
        </div>

        <!-- Timeline -->
        <div class="nd-card">
          <div class="nd-card-title">📋 Lịch sử</div>
          <div class="nd-timeline">
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--danger-light);">🎯</div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Lead được tạo từ Facebook Ads</div>
                <div class="nd-tl-sub">Khách điền form tư vấn BĐS Đà Lạt</div>
              </div>
              <div class="nd-tl-time">11:20</div>
            </div>
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--primary-light);">👤</div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Admin assign cho bạn</div>
                <div class="nd-tl-sub">Lý do: Chuyên khu vực P.Lâm Viên</div>
              </div>
              <div class="nd-tl-time">11:23</div>
            </div>
          </div>
        </div>
        <div style="height:16px;"></div>
      </div>
      <div class="nd-action-bar">
        <button class="nd-action-btn nd-btn-outline" onclick="closeNotifDetail('nd-lead')">← Quay lại</button>
        <button class="nd-action-btn nd-btn-primary" onclick="openSubpage('leads');closeNotifDetail('nd-lead')">🎯 Mở trang Leads</button>
      </div>
    </div><!-- nd-lead -->


    <!-- ND: BOOKING DETAIL -->
