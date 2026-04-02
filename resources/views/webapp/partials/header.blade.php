@php
  $hInitials = '?';
  if (!empty($customer->name)) {
    $parts = preg_split('/\s+/', trim($customer->name));
    $hInitials = count($parts) === 1
      ? mb_strtoupper(mb_substr($parts[0], 0, 1))
      : mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1));
  }
  $hRoleLabels = [
    'guest'      => 'Khách',
    'broker'     => 'Broker',
    'sale'       => 'Sale',
    'bds_admin'  => 'BĐS Admin',
    'sale_admin' => 'Sale Admin',
    'admin'      => 'Admin',
  ];
  $hRole = $customer ? $customer->getEffectiveRole() : 'guest';
  $hRoleLabel = $hRoleLabels[$hRole] ?? 'Broker';
  $defaultAvatarUrl = 'https://dalatbds.com/images/users/1693209486.1303.png';
  $hHasAvatar = $customer && $customer->getRawOriginal('profile');
  $hAvatarUrl = $hHasAvatar ? url('images' . config('global.USER_IMG_PATH') . $customer->getRawOriginal('profile')) : $defaultAvatarUrl;
@endphp

<div class="app-header">
  <div class="header-logo">
    <img src="{{ asset('images/logo.svg') }}" alt="Đà Lạt BĐS" style="height:36px;width:auto;display:block;">
  </div>
  <div class="header-actions">
    <button class="hbtn" onclick="toggleSearch()">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg>
    </button>
    <button class="hbtn" id="btn-notif" onclick="toggleNotifPanel(event)">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      <span id="header-notif-badge" class="badge-num" style="display:none;"></span>
    </button>
    <div class="avatar-btn" id="btn-avatar" onclick="toggleUserMenu(event)">
      <img src="{{ $hAvatarUrl }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" alt="">
    </div>
  </div>
</div>

{{-- Notification panel dropdown --}}
<div id="notif-panel" class="notif-panel" style="display:none;">
  <div class="notif-panel-header">
    <div class="notif-panel-title">Thông báo</div>
    <div class="notif-panel-tabs">
      <button class="nptab active" id="nptab-all" onclick="notifPanelTab('all')">Tất cả</button>
      <button class="nptab" id="nptab-unread" onclick="notifPanelTab('unread')">Chưa đọc</button>
    </div>
  </div>
  <div id="notif-panel-list" class="notif-panel-list">
    <div class="notif-panel-loading">Đang tải...</div>
  </div>
  <div class="notif-panel-footer">
    <button onclick="closeNotifPanel();goTo('activity')">Xem tất cả thông báo</button>
  </div>
</div>

{{-- User menu dropdown --}}
<div id="user-menu" class="user-menu" style="display:none;">
  <div class="user-menu-header">
    <div class="user-menu-avatar">
      <img src="{{ $hAvatarUrl }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" alt="">
    </div>
    <div class="user-menu-info">
      <div class="user-menu-name">{{ $customer->name ?? 'Khách' }}</div>
      <div class="user-menu-role">{{ $hRoleLabel }}</div>
    </div>
  </div>
  <button class="user-menu-item user-menu-item-primary" onclick="closeUserMenu();goTo('profile')">
    <div class="user-menu-item-icon" style="background:var(--primary-light);">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
    </div>
    Xem trang cá nhân
  </button>
  <div class="user-menu-divider"></div>
  <button class="user-menu-item" onclick="closeUserMenu();openSubpage('editprofile')">
    <div class="user-menu-item-icon">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
    </div>
    Chỉnh sửa hồ sơ
  </button>
  <button class="user-menu-item" onclick="closeUserMenu();openSubpage('notifset')">
    <div class="user-menu-item-icon">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
    </div>
    Cài đặt thông báo
  </button>
  <button class="user-menu-item" onclick="closeUserMenu();openSubpage('support')">
    <div class="user-menu-item-icon">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    </div>
    Hỗ trợ & FAQ
  </button>
  <button class="user-menu-item" onclick="closeUserMenu();openSubpage('referral')" style="color:var(--primary);">
    <div class="user-menu-item-icon" style="background:var(--primary-light);">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="5" y="5" width="3" height="3" fill="var(--primary)" stroke="none"/><rect x="16" y="5" width="3" height="3" fill="var(--primary)" stroke="none"/><rect x="5" y="16" width="3" height="3" fill="var(--primary)" stroke="none"/><path d="M14 14h3v3"/><path d="M20 14v.01"/><path d="M14 20h3"/><path d="M20 17v3"/></svg>
    </div>
    Mạng lưới thổ địa
  </button>
  <div class="user-menu-divider"></div>
  <button class="user-menu-item user-menu-item-danger" onclick="if(confirm('Bạn có chắc muốn đăng xuất?')) window.location.href='/webapp/logout'">
    <div class="user-menu-item-icon" style="background:var(--danger-light);">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
    </div>
    Đăng xuất
  </button>
</div>

{{-- Backdrop to close panels on outside click --}}
<div id="panel-backdrop" onclick="closeAllPanels()" style="display:none;position:fixed;inset:0;z-index:199;"></div>
