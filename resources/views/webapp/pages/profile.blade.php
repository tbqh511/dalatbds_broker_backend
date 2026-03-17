  <div class="page" id="page-profile">

    <div class="profile-hero">
      <div class="profile-avatar">HT</div>
      <div class="profile-name">Huy Thái</div>
      <div style="display:flex;gap:8px;margin-top:8px;align-items:center;">
        <div class="profile-role"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg> Sale</span></div>
        <div style="font-size:11px;color:rgba(255,255,255,0.65);">Đà Lạt BĐS</div>
      </div>
    </div>

    <!-- Profile stats -->
    <div class="profile-stats">
      <div class="ps-item">
        <div class="ps-val">45</div>
        <div class="ps-lbl">Tin BĐS</div>
      </div>
      <div class="ps-item">
        <div class="ps-val">12</div>
        <div class="ps-lbl">Giao dịch</div>
      </div>
      <div class="ps-item">
        <div class="ps-val">4.8 <svg width="13" height="13" viewBox="0 0 24 24" fill="#fbbf24" stroke="#fbbf24" stroke-width="1.5" style="vertical-align:middle;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <div class="ps-lbl">Đánh giá</div>
      </div>
    </div>


    <!-- Broker section — hiện với broker, sale, sale_admin, admin -->
    <div class="role-broker">
      <div class="menu-section"><div class="menu-section-title">Quản lý BĐS</div></div>
      <div class="menu-item" onclick="openSubpage('mybds')">
        <div class="menu-item-icon" style="background:var(--primary-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">BĐS của tôi</div>
          <div class="menu-item-sub">45 tin · 3 chờ duyệt</div>
        </div>
        <div class="menu-item-right"><span class="badge badge-amber">3 pending</span> ›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('mycustomers')">
        <div class="menu-item-icon" style="background:var(--teal-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Khách hàng của tôi</div>
          <div class="menu-item-sub">23 khách · 7 đang chăm</div>
        </div>
        <div class="menu-item-right">›</div>
      </div>
    </div>

    <!-- Sale CRM section — hiện với sale, sale_admin, admin -->
    <div class="role-sale">
      <div class="menu-section"><div class="menu-section-title">CRM — Chăm sóc khách hàng</div></div>
      <div class="menu-item" onclick="openSubpage('leads')">
        <div class="menu-item-icon" style="background:var(--danger-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Lead của tôi</div>
          <div class="menu-item-sub">7 lead · 2 chưa contact</div>
        </div>
        <div class="menu-item-right"><span class="badge badge-red">2 urgent</span> ›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('deals')">
        <div class="menu-item-icon" style="background:var(--purple-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Deal đang chăm</div>
          <div class="menu-item-sub">3 deal · 1 đang thương lượng</div>
        </div>
        <div class="menu-item-right">›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('bookings')">
        <div class="menu-item-icon" style="background:var(--primary-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Lịch hẹn</div>
          <div class="menu-item-sub">2 lịch hẹn tuần này</div>
        </div>
        <div class="menu-item-right"><span class="badge badge-blue">2 tới</span> ›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('commissions')">
        <div class="menu-item-icon" style="background:var(--success-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Hoa hồng của tôi</div>
          <div class="menu-item-sub">450 triệu dự kiến · 3 deal</div>
        </div>
        <div class="menu-item-right">›</div>
      </div>
      <!-- Commission quick view -->
      <div style="margin:0 16px 12px;">
        <div class="commission-total">
          <div class="commission-label">TỔNG HOA HỒNG DỰ KIẾN</div>
          <div class="commission-amount">450 triệu</div>
          <div class="commission-breakdown">
            <div class="commission-sub">
              <div class="commission-sub-label">Đã nhận</div>
              <div class="commission-sub-val">120 tr</div>
            </div>
            <div class="commission-sub">
              <div class="commission-sub-label">Đang chờ</div>
              <div class="commission-sub-val">330 tr</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- BĐS Admin section -->
    <div class="role-bds_admin">
      <div class="menu-section"><div class="menu-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg> Quản lý khu vực BĐS</span></div></div>
      <div class="menu-item" onclick="openSubpage('mybds')">
        <div class="menu-item-icon" style="background:var(--teal-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">BĐS khu vực</div>
          <div class="menu-item-sub">P. Xuân Hương · 10 eBroker đăng tin</div>
        </div>
        <div class="menu-item-right">›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('referral')" style="border-left:3px solid var(--purple);">
        <div class="menu-item-icon" style="background:var(--purple-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Mạng lưới eBroker</div>
          <div class="menu-item-sub">10 eBroker · 5% thu nhập từ khu vực</div>
        </div>
        <div class="menu-item-right"><span class="badge badge-purple">5% MLM</span> ›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('approvebds')">
        <div class="menu-item-icon" style="background:var(--warning-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Duyệt BĐS khu vực</div>
          <div class="menu-item-sub">3 tin chờ duyệt trong khu vực</div>
        </div>
        <div class="menu-item-right"><span class="badge badge-amber">3</span> ›</div>
      </div>
    </div>

    <!-- Sale Admin section -->
    <div class="role-sale_admin">
      <div class="menu-section"><div class="menu-section-title">Quản lý Team Sale</div></div>
      <div class="menu-item" onclick="openSubpage('kpiteam')">
        <div class="menu-item-icon" style="background:#fef3c7;display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">KPI & Team</div>
          <div class="menu-item-sub">5 sale · Tháng này 12 deals</div>
        </div>
        <div class="menu-item-right">›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('assignlead')">
        <div class="menu-item-icon" style="background:var(--danger-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Assign Lead</div>
          <div class="menu-item-sub">4 lead chờ phân công</div>
        </div>
        <div class="menu-item-right"><span class="badge badge-amber">4 mới</span> ›</div>
      </div>
    </div>

    <!-- Admin section -->
    <div class="role-admin">
      <div class="menu-section"><div class="menu-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Quản trị hệ thống</span></div></div>
      <div class="menu-item" style="border-left:3px solid var(--danger);" onclick="openSubpage('approvebds')">
        <div class="menu-item-icon" style="background:var(--danger-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Duyệt BĐS</div>
          <div class="menu-item-sub">8 tin chờ xem xét</div>
        </div>
        <div class="menu-item-right"><span class="badge badge-red">8</span> ›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('users')">
        <div class="menu-item-icon" style="background:var(--primary-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Quản lý người dùng</div>
          <div class="menu-item-sub">3 Broker chờ duyệt tài khoản</div>
        </div>
        <div class="menu-item-right"><span class="badge badge-amber">3</span> ›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('reports')">
        <div class="menu-item-icon" style="background:var(--success-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Báo cáo tổng hợp</div>
          <div class="menu-item-sub">Doanh thu · Deals · Hoa hồng</div>
        </div>
        <div class="menu-item-right">›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('approvecomm')">
        <div class="menu-item-icon" style="background:var(--purple-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Duyệt hoa hồng</div>
          <div class="menu-item-sub">2 khoản chờ xác nhận</div>
        </div>
        <div class="menu-item-right"><span class="badge badge-amber">2</span> ›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('activitylog')">
        <div class="menu-item-icon" style="background:#f0f9ff;display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></div>
        <div class="menu-item-body">
          <div class="menu-item-title">Lịch sử hành động</div>
          <div class="menu-item-sub">Gọi · Chia sẻ · Sửa BĐS</div>
        </div>
        <div class="menu-item-right">›</div>
      </div>
    </div>

    <!-- Referral section — tất cả roles -->
    <div class="menu-section" style="margin-top:8px;"><div class="menu-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg> Mạng lưới giới thiệu</span></div></div>
    <div class="menu-item" onclick="openSubpage('referral')" style="border-left:3px solid var(--purple);">
      <div class="menu-item-icon" style="background:var(--purple-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg></div>
      <div class="menu-item-body">
        <div class="menu-item-title">Mã giới thiệu</div>
        <div class="menu-item-sub">Chia sẻ link · Nhận 5% thu nhập</div>
      </div>
      <div class="menu-item-right"><span class="badge badge-purple">5% MLM</span> ›</div>
    </div>

    <!-- BĐS đã thích — tất cả roles -->
    <div class="menu-section" style="margin-top:8px;"><div class="menu-section-title">BĐS yêu thích</div></div>
    <div class="menu-item" onclick="openSubpage('likedbds')">
      <div class="menu-item-icon" style="background:var(--primary-light);display:flex;align-items:center;justify-content:center;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="var(--primary)" stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
      </div>
      <div class="menu-item-body">
        <div class="menu-item-title">BĐS đã thích</div>
        <div class="menu-item-sub" id="likedBdsCount">Đang tải...</div>
      </div>
      <div class="menu-item-right">›</div>
    </div>

    <!-- Common settings — tất cả roles -->
    <div class="menu-section" style="margin-top:8px;"><div class="menu-section-title">Tài khoản</div></div>
    <div class="menu-item" onclick="openSubpage('editprofile')">
      <div class="menu-item-icon" style="background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><line x1="7" y1="8" x2="7.01" y2="8"/><line x1="11" y1="8" x2="17" y2="8"/><line x1="7" y1="11" x2="7.01" y2="11"/><line x1="11" y1="11" x2="17" y2="11"/></svg></div>
      <div class="menu-item-body"><div class="menu-item-title">Chỉnh sửa hồ sơ</div></div>
      <div class="menu-item-right">›</div>
    </div>
    <div class="menu-item" onclick="openSubpage('notifset')">
      <div class="menu-item-icon" style="background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></div>
      <div class="menu-item-body"><div class="menu-item-title">Cài đặt thông báo</div></div>
      <div class="menu-item-right">›</div>
    </div>
    <div class="menu-item" onclick="openSubpage('support')">
      <div class="menu-item-icon" style="background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
      <div class="menu-item-body"><div class="menu-item-title">Hỗ trợ & FAQ</div></div>
      <div class="menu-item-right">›</div>
    </div>
    <div class="menu-item" style="margin-bottom:8px;">
      <div class="menu-item-icon" style="background:var(--danger-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></div>
      <div class="menu-item-body"><div class="menu-item-title" style="color:var(--danger);">Đăng xuất</div></div>
      <div class="menu-item-right" style="color:var(--danger);">›</div>
    </div>

  </div><!-- end page-profile -->
