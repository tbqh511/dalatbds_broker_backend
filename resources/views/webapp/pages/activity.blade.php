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
      <div class="notif-icon" style="background:var(--danger-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg></div>
      <div class="notif-body">
        <div class="notif-title">Lead mới được assign cho bạn</div>
        <div class="notif-desc">Nguyễn Văn Ssdf — Mua Biệt thự, Khách sạn | Ngân sách 3–5 tỷ | Khu vực P.Lâm Viên</div>
        <div class="notif-actions" onclick="event.stopPropagation()">
          <button class="notif-action-btn primary" onclick="openNotifDetail('nd-lead')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Xem Lead</span></button>
          <button class="notif-action-btn" onclick="showToast('Đang gọi...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Gọi ngay</span></button>
        </div>
        <div class="notif-time">2 giờ trước</div>
      </div>
    </div>

    <!-- Booking reminder -->
    <div class="notif-item unread role-sale role-bds_admin role-sale_admin role-admin" onclick="openNotifDetail('nd-booking')" style="cursor:pointer;">
      <div class="notif-icon" style="background:var(--primary-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
      <div class="notif-body">
        <div class="notif-title">Lịch xem nhà ngày mai lúc 09:00</div>
        <div class="notif-desc">Anh Minh Tuấn — Đường Yersin, Phường Cam Ly. Nhớ chuẩn bị hồ sơ pháp lý và ảnh thực tế.</div>
        <div class="notif-actions" onclick="event.stopPropagation()">
          <button class="notif-action-btn primary" onclick="openNotifDetail('nd-booking')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Xem chi tiết</span></button>
          <button class="notif-action-btn" onclick="showToast('Mở form dời lịch...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Dời lịch</span></button>
        </div>
        <div class="notif-time">5 giờ trước</div>
      </div>
    </div>

    <!-- BĐS approved -->
    <div class="notif-item role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin" onclick="openNotifDetail('nd-bds')" style="cursor:pointer;">
      <div class="notif-icon" style="background:var(--success-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
      <div class="notif-body">
        <div class="notif-title">BĐS của bạn đã được duyệt!</div>
        <div class="notif-desc">Bán Đất ở phân quyền, Đường Yersin, Phường Cam Ly — đang hiển thị công khai</div>
        <div class="notif-actions" onclick="event.stopPropagation()">
          <button class="notif-action-btn primary" onclick="openNotifDetail('nd-bds')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Xem tin</span></button>
        </div>
        <div class="notif-time">Hôm qua</div>
      </div>
    </div>

    <!-- Commission update -->
    <div class="notif-item role-sale role-bds_admin role-sale_admin role-admin" onclick="openNotifDetail('nd-comm')" style="cursor:pointer;">
      <div class="notif-icon" style="background:var(--success-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
      <div class="notif-body">
        <div class="notif-title">Hoa hồng cập nhật: Đã nhận cọc</div>
        <div class="notif-desc">Deal Biệt thự Cầu Đất — Khách đã cọc 200 triệu. Hoa hồng của bạn: 85 triệu đang chờ công chứng.</div>
        <div class="notif-actions" onclick="event.stopPropagation()">
          <button class="notif-action-btn primary" onclick="openNotifDetail('nd-comm')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> Xem hoa hồng</span></button>
        </div>
        <div class="notif-time">2 ngày trước</div>
      </div>
    </div>

    <!-- Admin: Pending approval -->
    <div class="notif-item role-admin role-bds_admin role-sale_admin" onclick="openNotifDetail('nd-approve')" style="cursor:pointer;">
      <div class="notif-icon" style="background:var(--warning-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
      <div class="notif-body">
        <div class="notif-title">BĐS chờ duyệt: Nhà mặt tiền P.1</div>
        <div class="notif-desc">Broker Trần Văn Hùng gửi lúc 10:23 — Nhà phố, 2,800 triệu, có sổ hồng. Cần kiểm tra pháp lý.</div>
        <div class="notif-actions" onclick="event.stopPropagation()">
          <button class="notif-action-btn primary" onclick="showToast('✓ Đã duyệt BĐS!')" style="display:inline-flex;align-items:center;gap:5px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Duyệt</button>
          <button class="notif-action-btn" style="color:var(--danger);border-color:var(--danger-light);background:var(--danger-light);" onclick="openNotifDetail('nd-approve')">✕ Từ chối</button>
        </div>
        <div class="notif-time">3 ngày trước</div>
      </div>
    </div>

    <!-- Deal stuck -->
    <div class="notif-item role-sale_admin role-admin" onclick="openNotifDetail('nd-deal')" style="cursor:pointer;">
      <div class="notif-icon" style="background:var(--warning-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
      <div class="notif-body">
        <div class="notif-title">Deal bị stuck: Anh Linh — Nhà phố Lâm Viên</div>
        <div class="notif-desc">Deal không có cập nhật trong 5 ngày. Khách đã xem nhà nhưng chưa phản hồi kết quả.</div>
        <div class="notif-actions" onclick="event.stopPropagation()">
          <button class="notif-action-btn primary" onclick="openNotifDetail('nd-deal')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Xem Deal</span></button>
          <button class="notif-action-btn" onclick="showToast('Đã nhắc Anh Linh...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-2.99 10.45-11 14C2.99 14.45 2 4 2 4l10-2 10 2z"/></svg> Nhắc nhở</span></button>
        </div>
        <div class="notif-time">4 ngày trước</div>
      </div>
    </div>

    <!-- Hoa hồng hoàn tất -->
    <div class="notif-item role-sale role-bds_admin role-sale_admin role-admin">
      <div class="notif-icon" style="background:var(--success-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
      <div class="notif-body">
        <div class="notif-title">Đã nhận hoa hồng 54 triệu!</div>
        <div class="notif-desc">Giao dịch Đất ở Phường 3 hoàn tất công chứng. Hoa hồng của bạn đã được chuyển khoản.</div>
        <div class="notif-actions" onclick="event.stopPropagation()">
          <button class="notif-action-btn primary" onclick="openSubpage('commissions')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> Xem chi tiết</span></button>
        </div>
        <div class="notif-time">5 ngày trước</div>
      </div>
    </div>

    <div style="padding:16px;text-align:center;">
      <button style="padding:10px 20px;border:1px solid var(--border);border-radius:20px;font-size:13px;color:var(--text-secondary);background:var(--bg-card);" onclick="showToast('Đang tải thêm...')">Xem thêm hoạt động</button>
    </div>

  </div><!-- end page-activity -->

