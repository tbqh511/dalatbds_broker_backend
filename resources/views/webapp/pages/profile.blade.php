    <div class="page" id="page-profile">

      <div class="profile-hero">
        <div class="profile-avatar">HT</div>
        <div class="profile-name">Huy Thái</div>
        <div style="display:flex;gap:8px;margin-top:8px;align-items:center;">
          <div class="profile-role">💼 Sale</div>
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
          <div class="ps-val">4.8⭐</div>
          <div class="ps-lbl">Đánh giá</div>
        </div>
      </div>


      <!-- Broker section — hiện với broker, sale, sale_admin, admin -->
      <div class="role-broker">
        <div class="menu-section"><div class="menu-section-title">Quản lý BĐS</div></div>
        <div class="menu-item" onclick="openSubpage('mybds')">
          <div class="menu-item-icon" style="background:var(--primary-light);">🏡</div>
          <div class="menu-item-body">
            <div class="menu-item-title">BĐS của tôi</div>
            <div class="menu-item-sub">45 tin · 3 chờ duyệt</div>
          </div>
          <div class="menu-item-right"><span class="badge badge-amber">3 pending</span> ›</div>
        </div>
        <div class="menu-item" onclick="openSubpage('mycustomers')">
          <div class="menu-item-icon" style="background:var(--teal-light);">👥</div>
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
          <div class="menu-item-icon" style="background:var(--danger-light);">🎯</div>
          <div class="menu-item-body">
            <div class="menu-item-title">Lead của tôi</div>
            <div class="menu-item-sub">7 lead · 2 chưa contact</div>
          </div>
          <div class="menu-item-right"><span class="badge badge-red">2 urgent</span> ›</div>
        </div>
        <div class="menu-item" onclick="openSubpage('deals')">
          <div class="menu-item-icon" style="background:var(--purple-light);">🤝</div>
          <div class="menu-item-body">
            <div class="menu-item-title">Deal đang chăm</div>
            <div class="menu-item-sub">3 deal · 1 đang thương lượng</div>
          </div>
          <div class="menu-item-right">›</div>
        </div>
        <div class="menu-item" onclick="openSubpage('bookings')">
          <div class="menu-item-icon" style="background:var(--primary-light);">🗓️</div>
          <div class="menu-item-body">
            <div class="menu-item-title">Lịch hẹn</div>
            <div class="menu-item-sub">2 lịch hẹn tuần này</div>
          </div>
          <div class="menu-item-right"><span class="badge badge-blue">2 tới</span> ›</div>
        </div>
        <div class="menu-item" onclick="openSubpage('commissions')">
          <div class="menu-item-icon" style="background:var(--success-light);">💰</div>
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
        <div class="menu-section"><div class="menu-section-title">🏘️ Quản lý khu vực BĐS</div></div>
        <div class="menu-item" onclick="openSubpage('mybds')">
          <div class="menu-item-icon" style="background:var(--teal-light);">🏡</div>
          <div class="menu-item-body">
            <div class="menu-item-title">BĐS khu vực</div>
            <div class="menu-item-sub">P. Xuân Hương · 10 eBroker đăng tin</div>
          </div>
          <div class="menu-item-right">›</div>
        </div>
        <div class="menu-item" onclick="openSubpage('referral')" style="border-left:3px solid var(--purple);">
          <div class="menu-item-icon" style="background:var(--purple-light);">🎁</div>
          <div class="menu-item-body">
            <div class="menu-item-title">Mạng lưới eBroker</div>
            <div class="menu-item-sub">10 eBroker · 5% thu nhập từ khu vực</div>
          </div>
          <div class="menu-item-right"><span class="badge badge-purple">5% MLM</span> ›</div>
        </div>
        <div class="menu-item" onclick="openSubpage('approvebds')">
          <div class="menu-item-icon" style="background:var(--warning-light);">✅</div>
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
          <div class="menu-item-icon" style="background:#fef3c7;">📊</div>
          <div class="menu-item-body">
            <div class="menu-item-title">KPI & Team</div>
            <div class="menu-item-sub">5 sale · Tháng này 12 deals</div>
          </div>
          <div class="menu-item-right">›</div>
        </div>
        <div class="menu-item" onclick="openSubpage('assignlead')">
          <div class="menu-item-icon" style="background:var(--danger-light);">📋</div>
          <div class="menu-item-body">
            <div class="menu-item-title">Assign Lead</div>
            <div class="menu-item-sub">4 lead chờ phân công</div>
          </div>
          <div class="menu-item-right"><span class="badge badge-amber">4 mới</span> ›</div>
        </div>
      </div>

      <!-- Admin section -->
      <div class="role-admin">
        <div class="menu-section"><div class="menu-section-title">👑 Quản trị hệ thống</div></div>
        <div class="menu-item" style="border-left:3px solid var(--danger);" onclick="openSubpage('approvebds')">
          <div class="menu-item-icon" style="background:var(--danger-light);">🏘️</div>
          <div class="menu-item-body">
            <div class="menu-item-title">Duyệt BĐS</div>
            <div class="menu-item-sub">8 tin chờ xem xét</div>
          </div>
          <div class="menu-item-right"><span class="badge badge-red">8</span> ›</div>
        </div>
        <div class="menu-item" onclick="openSubpage('users')">
          <div class="menu-item-icon" style="background:var(--primary-light);">👤</div>
          <div class="menu-item-body">
            <div class="menu-item-title">Quản lý người dùng</div>
            <div class="menu-item-sub">3 Broker chờ duyệt tài khoản</div>
          </div>
          <div class="menu-item-right"><span class="badge badge-amber">3</span> ›</div>
        </div>
        <div class="menu-item" onclick="openSubpage('reports')">
          <div class="menu-item-icon" style="background:var(--success-light);">📈</div>
          <div class="menu-item-body">
            <div class="menu-item-title">Báo cáo tổng hợp</div>
            <div class="menu-item-sub">Doanh thu · Deals · Hoa hồng</div>
          </div>
          <div class="menu-item-right">›</div>
        </div>
        <div class="menu-item" onclick="openSubpage('approvecomm')">
          <div class="menu-item-icon" style="background:var(--purple-light);">💳</div>
          <div class="menu-item-body">
            <div class="menu-item-title">Duyệt hoa hồng</div>
            <div class="menu-item-sub">2 khoản chờ xác nhận</div>
          </div>
          <div class="menu-item-right"><span class="badge badge-amber">2</span> ›</div>
        </div>
      </div>

      <!-- Referral section — tất cả roles -->
      <div class="menu-section" style="margin-top:8px;"><div class="menu-section-title">🔗 Mạng lưới giới thiệu</div></div>
      <div class="menu-item" onclick="openSubpage('referral')" style="border-left:3px solid var(--purple);">
        <div class="menu-item-icon" style="background:var(--purple-light);">🎁</div>
        <div class="menu-item-body">
          <div class="menu-item-title">Mã giới thiệu</div>
          <div class="menu-item-sub">Chia sẻ link · Nhận 5% thu nhập</div>
        </div>
        <div class="menu-item-right"><span class="badge badge-purple">5% MLM</span> ›</div>
      </div>

      <!-- Common settings — tất cả roles -->
      <div class="menu-section" style="margin-top:8px;"><div class="menu-section-title">Tài khoản</div></div>
      <div class="menu-item" onclick="openSubpage('editprofile')">
        <div class="menu-item-icon" style="background:var(--bg-secondary);">🪪</div>
        <div class="menu-item-body"><div class="menu-item-title">Chỉnh sửa hồ sơ</div></div>
        <div class="menu-item-right">›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('notifset')">
        <div class="menu-item-icon" style="background:var(--bg-secondary);">🔔</div>
        <div class="menu-item-body"><div class="menu-item-title">Cài đặt thông báo</div></div>
        <div class="menu-item-right">›</div>
      </div>
      <div class="menu-item" onclick="openSubpage('support')">
        <div class="menu-item-icon" style="background:var(--bg-secondary);">❓</div>
        <div class="menu-item-body"><div class="menu-item-title">Hỗ trợ & FAQ</div></div>
        <div class="menu-item-right">›</div>
      </div>
      <div class="menu-item" style="margin-bottom:8px;">
        <div class="menu-item-icon" style="background:var(--danger-light);">🚪</div>
        <div class="menu-item-body"><div class="menu-item-title" style="color:var(--danger);">Đăng xuất</div></div>
        <div class="menu-item-right" style="color:var(--danger);">›</div>
      </div>

    </div><!-- end page-profile -->
