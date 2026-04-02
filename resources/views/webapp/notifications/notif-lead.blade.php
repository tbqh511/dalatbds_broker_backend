    <div class="notif-detail-page" id="nd-lead">
      <div class="nd-header">
        <button class="nd-back" onclick="closeNotifDetail('nd-lead')">←</button>
        <div class="nd-title">Chi tiết Lead</div>
        <button style="font-size:12px;font-weight:600;color:var(--text-tertiary);background:none;border:none;cursor:pointer;" onclick="showToast('Đã đánh dấu đã đọc')">Đã đọc</button>
      </div>
      <div class="nd-scroll">
        <div class="nd-hero lead-type">
          <div class="nd-hero-icon"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg></div>
          <div class="nd-hero-title">Lead mới được assign</div>
          <div class="nd-hero-sub">Admin đã phân công Lead này cho bạn.<br>Liên hệ sớm để tăng tỉ lệ chuyển đổi!</div>
          <div class="nd-hero-time">⏱ 2 giờ trước · 15/03/2026 11:23</div>
        </div>

        <div class="nd-urgent red">
          <span class="nd-urgent-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
          <div class="nd-urgent-text">Chưa được liên hệ! Hệ thống sẽ tự nhắc nhở sau 24h. Lead hot có ngân sách cao — ưu tiên xử lý ngay.</div>
        </div>

        <!-- Thông tin lead -->
        <div class="nd-card">
          <div class="nd-card-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Thông tin khách hàng</span></div>
          <div class="nd-info-row">
            <span class="nd-info-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></span>
            <span class="nd-info-label">Họ tên</span>
            <span class="nd-info-value">Nguyễn Văn Ssdf</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
            <span class="nd-info-label">Số điện thoại</span>
            <span class="nd-info-value primary">0912.345.678</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
            <span class="nd-info-label">Loại BĐS</span>
            <span class="nd-info-value">Biệt thự, Khách sạn</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg></span>
            <span class="nd-info-label">Nhu cầu</span>
            <span class="nd-info-value">Mua để ở + đầu tư</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
            <span class="nd-info-label">Ngân sách</span>
            <span class="nd-info-value money">3 tỷ – 5 tỷ</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
            <span class="nd-info-label">Khu vực</span>
            <span class="nd-info-value">P.Lâm Viên, Đà Lạt</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
            <span class="nd-info-label">Nguồn</span>
            <span class="nd-info-value">Facebook Ads</span>
          </div>
          <div class="nd-tags">
            <span class="nd-tag">Biệt thự</span>
            <span class="nd-tag">Khách sạn</span>
            <span class="nd-tag">3–5 tỷ</span>
            <span class="nd-tag">Lâm Viên</span>
            <span class="nd-tag" style="background:var(--danger-light);color:var(--danger);display:inline-flex;align-items:center;gap:4px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Ưu tiên cao</span>
          </div>
        </div>

        <!-- Cập nhật nhanh -->
        <div class="nd-card">
          <div class="nd-card-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Xử lý nhanh</span></div>
          <div style="padding:10px 14px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;">
              <button class="nd-action-btn nd-btn-primary" style="font-size:12px;" onclick="showToast('Đang gọi 0912.345.678...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Gọi ngay</span></button>
              <button class="nd-action-btn nd-btn-outline" style="font-size:12px;" onclick="showToast('Mở Zalo...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Nhắn Zalo</span></button>
              <button class="nd-action-btn nd-btn-success" style="font-size:12px;" onclick="showToast('✓ Đã xác nhận đã liên hệ')">✓ Đã liên hệ</button>
              <button class="nd-action-btn nd-btn-purple" style="font-size:12px;" onclick="showToast('Đang tạo Deal...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Tạo Deal</span></button>
            </div>
            <textarea class="nd-note" rows="2" placeholder="Ghi chú nhanh về khách (VD: Thích view đẹp, không thích hẻm nhỏ...)"></textarea>
            <button style="width:100%;padding:11px;background:var(--primary);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:700;cursor:pointer;" onclick="showToast('✓ Đã lưu ghi chú lead')">Lưu ghi chú</button>
          </div>
        </div>

        <!-- Timeline -->
        <div class="nd-card">
          <div class="nd-card-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Lịch sử</span></div>
          <div class="nd-timeline">
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--danger-light);display:flex;align-items:center;justify-content:center;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg></div>
              <div class="nd-tl-body">
                <div class="nd-tl-title">Lead được tạo từ Facebook Ads</div>
                <div class="nd-tl-sub">Khách điền form tư vấn BĐS Đà Lạt</div>
              </div>
              <div class="nd-tl-time">11:20</div>
            </div>
            <div class="nd-tl-row">
              <div class="nd-tl-icon" style="background:var(--primary-light);display:flex;align-items:center;justify-content:center;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
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
        <button style="width:40px;height:40px;background:var(--primary,#3270FC);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;box-shadow:0 2px 8px rgba(50,112,252,0.25);flex-shrink:0;cursor:pointer;border:none;" onclick="closeNotifDetail('nd-lead')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
        <button class="nd-action-btn nd-btn-primary" onclick="openSubpage('leads');closeNotifDetail('nd-lead')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg> Mở trang Leads</span></button>
      </div>
    </div><!-- nd-lead -->


    <!-- ND: BOOKING DETAIL -->
