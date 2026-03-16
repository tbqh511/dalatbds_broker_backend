<div class="bottom-nav">
  <button class="nav-item active" id="nav-home" onclick="goTo('home')">
    <span class="nav-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/>
        <path d="M9 22V12h6v10"/>
      </svg>
    </span>
    Khám phá
  </button>
  <button class="nav-item" id="nav-search" onclick="goTo('search')">
    <span class="nav-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="10.5" cy="10.5" r="6.5"/>
        <line x1="15.5" y1="15.5" x2="21" y2="21"/>
      </svg>
    </span>
    Tìm kiếm
  </button>
  <button class="nav-item fab-wrap" id="nav-post" onclick="openSheet()">
    <div class="fab">
      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
        <line x1="11" y1="4" x2="11" y2="18"/>
        <line x1="4" y1="11" x2="18" y2="11"/>
      </svg>
    </div>
    <span style="font-size:9px;color:var(--text-tertiary);margin-top:2px;">Đăng tin</span>
  </button>
  <button class="nav-item" id="nav-activity" onclick="goTo('activity')">
    <span class="nav-icon" style="position:relative;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
      </svg>
    </span>
    Hoạt động
    <span class="nav-indicator"></span>
  </button>
  <button class="nav-item" id="nav-profile" onclick="goTo('profile')">
    <span class="nav-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="8" r="4"/>
        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
      </svg>
    </span>
    Hồ sơ
  </button>
</div>
<div class="overlay" id="overlay" onclick="closeSheet()"></div>
<div class="bottom-sheet" id="bottomSheet">
  <div class="sheet-handle"></div>
  <div class="sheet-title">Tạo mới</div>
  <div class="sheet-option" onclick="closeSheet();goTo('post')">
    <div class="sheet-opt-icon" style="background:var(--primary-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
    <div class="sheet-opt-body">
      <div class="sheet-opt-title">Đăng BĐS mới</div>
      <div class="sheet-opt-sub">Gửi tin bán/cho thuê để duyệt</div>
    </div>
  </div>
  <div class="sheet-option role-sale role-bds_admin role-sale_admin role-admin">
    <div class="sheet-opt-icon" style="background:var(--danger-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg></div>
    <div class="sheet-opt-body">
      <div class="sheet-opt-title">Thêm Lead / Khách mới</div>
      <div class="sheet-opt-sub">Ghi nhận khách hàng tiềm năng</div>
    </div>
  </div>
  <div class="sheet-option role-sale role-bds_admin role-sale_admin role-admin">
    <div class="sheet-opt-icon" style="background:var(--purple-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
    <div class="sheet-opt-body">
      <div class="sheet-opt-title">Tạo Deal mới</div>
      <div class="sheet-opt-sub">Từ Lead đã xác nhận</div>
    </div>
  </div>
  <div class="sheet-option role-sale role-bds_admin role-sale_admin role-admin">
    <div class="sheet-opt-icon" style="background:var(--primary-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
    <div class="sheet-opt-body">
      <div class="sheet-opt-title">Đặt lịch xem nhà</div>
      <div class="sheet-opt-sub">Booking cho Deal đang chăm</div>
    </div>
  </div>
</div>
