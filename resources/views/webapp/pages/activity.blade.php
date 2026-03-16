    <div class="page" id="page-activity">

      <div class="notif-tabs">
        <button class="ntab active" onclick="switchNotifTab(this,'all')">Tất cả</button>
        <button class="ntab" onclick="switchNotifTab(this,'lead')">Lead</button>
        <button class="ntab" onclick="switchNotifTab(this,'deal')">Deal</button>
        <button class="ntab" onclick="switchNotifTab(this,'booking')">Lịch hẹn</button>
        <button class="ntab" onclick="switchNotifTab(this,'commission')">Hoa hồng</button>
        <button class="ntab role-admin role-bds_admin role-sale_admin" onclick="switchNotifTab(this,'admin')">Duyệt BĐS</button>
      </div>

      <!-- Lead notification -->
      <div class="notif-item unread role-sale role-bds_admin role-sale_admin role-admin" onclick="openNotifDetail('nd-lead')" style="cursor:pointer;">
        <div class="notif-icon" style="background:var(--danger-light);">🎯</div>
        <div class="notif-body">
          <div class="notif-title">Lead mới được assign cho bạn</div>
          <div class="notif-desc">Nguyễn Văn Ssdf — Mua Biệt thự, Khách sạn | Ngân sách 3–5 tỷ | Khu vực P.Lâm Viên</div>
          <div class="notif-actions" onclick="event.stopPropagation()">
            <button class="notif-action-btn primary" onclick="openNotifDetail('nd-lead')">👁 Xem Lead</button>
            <button class="notif-action-btn" onclick="showToast('Đang gọi...')">📞 Gọi ngay</button>
          </div>
          <div class="notif-time">⏱ 2 giờ trước</div>
        </div>
      </div>

      <!-- Booking reminder -->
      <div class="notif-item unread role-sale role-bds_admin role-sale_admin role-admin" onclick="openNotifDetail('nd-booking')" style="cursor:pointer;">
        <div class="notif-icon" style="background:var(--primary-light);">📅</div>
        <div class="notif-body">
          <div class="notif-title">Lịch xem nhà ngày mai lúc 09:00</div>
          <div class="notif-desc">Anh Minh Tuấn — Đường Yersin, Phường Cam Ly. Nhớ chuẩn bị hồ sơ pháp lý và ảnh thực tế.</div>
          <div class="notif-actions" onclick="event.stopPropagation()">
            <button class="notif-action-btn primary" onclick="openNotifDetail('nd-booking')">📋 Xem chi tiết</button>
            <button class="notif-action-btn" onclick="showToast('Mở form dời lịch...')">🔄 Dời lịch</button>
          </div>
          <div class="notif-time">⏱ 5 giờ trước</div>
        </div>
      </div>

      <!-- BĐS approved -->
      <div class="notif-item role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin" onclick="openNotifDetail('nd-bds')" style="cursor:pointer;">
        <div class="notif-icon" style="background:var(--success-light);">✅</div>
        <div class="notif-body">
          <div class="notif-title">BĐS của bạn đã được duyệt!</div>
          <div class="notif-desc">Bán Đất ở phân quyền, Đường Yersin, Phường Cam Ly — đang hiển thị công khai</div>
          <div class="notif-actions" onclick="event.stopPropagation()">
            <button class="notif-action-btn primary" onclick="openNotifDetail('nd-bds')">👁 Xem tin</button>
          </div>
          <div class="notif-time">Hôm qua</div>
        </div>
      </div>

      <!-- Commission update -->
      <div class="notif-item role-sale role-bds_admin role-sale_admin role-admin" onclick="openNotifDetail('nd-comm')" style="cursor:pointer;">
        <div class="notif-icon" style="background:var(--success-light);">💰</div>
        <div class="notif-body">
          <div class="notif-title">Hoa hồng cập nhật: Đã nhận cọc</div>
          <div class="notif-desc">Deal Biệt thự Cầu Đất — Khách đã cọc 200 triệu. Hoa hồng của bạn: 85 triệu đang chờ công chứng.</div>
          <div class="notif-actions" onclick="event.stopPropagation()">
            <button class="notif-action-btn primary" onclick="openNotifDetail('nd-comm')">💰 Xem hoa hồng</button>
          </div>
          <div class="notif-time">2 ngày trước</div>
        </div>
      </div>

      <!-- Admin: Pending approval -->
      <div class="notif-item role-admin role-bds_admin role-sale_admin" onclick="openNotifDetail('nd-approve')" style="cursor:pointer;">
        <div class="notif-icon" style="background:var(--warning-light);">⏳</div>
        <div class="notif-body">
          <div class="notif-title">BĐS chờ duyệt: Nhà mặt tiền P.1</div>
          <div class="notif-desc">Broker Trần Văn Hùng gửi lúc 10:23 — Nhà phố, 2,800 triệu, có sổ hồng. Cần kiểm tra pháp lý.</div>
          <div class="notif-actions" onclick="event.stopPropagation()">
            <button class="notif-action-btn primary" onclick="showToast('✓ Đã duyệt BĐS!')">✅ Duyệt</button>
            <button class="notif-action-btn" style="color:var(--danger);border-color:var(--danger-light);background:var(--danger-light);" onclick="openNotifDetail('nd-approve')">❌ Từ chối</button>
          </div>
          <div class="notif-time">3 ngày trước</div>
        </div>
      </div>

      <!-- Deal stuck -->
      <div class="notif-item role-sale_admin role-admin" onclick="openNotifDetail('nd-deal')" style="cursor:pointer;">
        <div class="notif-icon" style="background:var(--warning-light);">⚠️</div>
        <div class="notif-body">
          <div class="notif-title">Deal bị stuck: Anh Linh — Nhà phố Lâm Viên</div>
          <div class="notif-desc">Deal không có cập nhật trong 5 ngày. Khách đã xem nhà nhưng chưa phản hồi kết quả.</div>
          <div class="notif-actions" onclick="event.stopPropagation()">
            <button class="notif-action-btn primary" onclick="openNotifDetail('nd-deal')">📋 Xem Deal</button>
            <button class="notif-action-btn" onclick="showToast('Đã nhắc Anh Linh...')">📣 Nhắc nhở</button>
          </div>
          <div class="notif-time">4 ngày trước</div>
        </div>
      </div>

      <!-- Hoa hồng hoàn tất -->
      <div class="notif-item role-sale role-bds_admin role-sale_admin role-admin">
        <div class="notif-icon" style="background:var(--success-light);">🎉</div>
        <div class="notif-body">
          <div class="notif-title">Đã nhận hoa hồng 54 triệu!</div>
          <div class="notif-desc">Giao dịch Đất ở Phường 3 hoàn tất công chứng. Hoa hồng của bạn đã được chuyển khoản.</div>
          <div class="notif-actions" onclick="event.stopPropagation()">
            <button class="notif-action-btn primary" onclick="openSubpage('commissions')">💰 Xem chi tiết</button>
          </div>
          <div class="notif-time">5 ngày trước</div>
        </div>
      </div>

      <div style="padding:16px;text-align:center;">
        <button style="padding:10px 20px;border:1px solid var(--border);border-radius:20px;font-size:13px;color:var(--text-secondary);background:var(--bg-card);" onclick="showToast('Đang tải thêm...')">Xem thêm hoạt động</button>
      </div>

    </div><!-- end page-activity -->

