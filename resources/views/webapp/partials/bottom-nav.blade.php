  <div class="bottom-nav">
    <button class="nav-item active" id="nav-home" onclick="goTo('home')">
      <span class="nav-icon">🏠</span>
      Khám phá
    </button>
    <button class="nav-item" id="nav-search" onclick="goTo('search')">
      <span class="nav-icon">🔍</span>
      Tìm kiếm
    </button>
    <button class="nav-item fab-wrap" id="nav-post" onclick="openSheet()">
      <div class="fab">＋</div>
      <span style="font-size:9px;color:var(--text-tertiary);margin-top:2px;">Đăng tin</span>
    </button>
    <button class="nav-item" id="nav-activity" onclick="goTo('activity')">
      <span class="nav-icon">🔔</span>
      Hoạt động
      <span class="nav-indicator"></span>
    </button>
    <button class="nav-item" id="nav-profile" onclick="goTo('profile')">
      <span class="nav-icon">👤</span>
      Hồ sơ
    </button>
  </div>
  <div class="overlay" id="overlay" onclick="closeSheet()"></div>
  <div class="bottom-sheet" id="bottomSheet">
    <div class="sheet-handle"></div>
    <div class="sheet-title">Tạo mới</div>
    <div class="sheet-option" onclick="closeSheet();goTo('post')">
      <div class="sheet-opt-icon" style="background:var(--primary-light);">🏡</div>
      <div class="sheet-opt-body">
        <div class="sheet-opt-title">Đăng BĐS mới</div>
        <div class="sheet-opt-sub">Gửi tin bán/cho thuê để duyệt</div>
      </div>
    </div>
    <div class="sheet-option role-sale role-bds_admin role-sale_admin role-admin">
      <div class="sheet-opt-icon" style="background:var(--danger-light);">🎯</div>
      <div class="sheet-opt-body">
        <div class="sheet-opt-title">Thêm Lead / Khách mới</div>
        <div class="sheet-opt-sub">Ghi nhận khách hàng tiềm năng</div>
      </div>
    </div>
    <div class="sheet-option role-sale role-bds_admin role-sale_admin role-admin">
      <div class="sheet-opt-icon" style="background:var(--purple-light);">🤝</div>
      <div class="sheet-opt-body">
        <div class="sheet-opt-title">Tạo Deal mới</div>
        <div class="sheet-opt-sub">Từ Lead đã xác nhận</div>
      </div>
    </div>
    <div class="sheet-option role-sale role-bds_admin role-sale_admin role-admin">
      <div class="sheet-opt-icon" style="background:var(--primary-light);">📅</div>
      <div class="sheet-opt-body">
        <div class="sheet-opt-title">Đặt lịch xem nhà</div>
        <div class="sheet-opt-sub">Booking cho Deal đang chăm</div>
      </div>
    </div>
  </div>
