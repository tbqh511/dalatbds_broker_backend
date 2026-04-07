  <!-- ========== SUBPAGE: QUẢN LÝ NGƯỜI DÙNG ========== -->
  <!-- Redesign: Minimalist + Flat Design (Primary Focus) -->
  <div class="subpage" id="subpage-users">
    <!-- Header: Nút quay lại + Tiêu đề + Nút làm mới -->
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('users')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Quản lý người dùng</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="loadUsers(true)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></button>
      </div>
    </div>

    <!-- Minimalist Status Bar: Thay thế banner anh hùng bằng inline stats -->
    <div style="padding:12px 16px;border-bottom:1px solid var(--border);">
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;text-align:center;font-size:12px;">
        <div style="display:flex;flex-direction:column;gap:4px;">
          <div style="font-weight:600;font-size:16px;color:var(--text-primary);" id="usersActiveCount">—</div>
          <div style="color:var(--text-tertiary);font-size:11px;">Active</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;">
          <div style="font-weight:600;font-size:16px;color:var(--primary);" id="usersPendingCount">—</div>
          <div style="color:var(--text-tertiary);font-size:11px;">Chờ duyệt</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;">
          <div style="font-weight:600;font-size:16px;color:var(--text-primary);" id="usersBrokerCount">—</div>
          <div style="color:var(--text-tertiary);font-size:11px;">eBroker</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;">
          <div style="font-weight:600;font-size:16px;color:var(--text-primary);" id="usersLockedCount">—</div>
          <div style="color:var(--text-tertiary);font-size:11px;">Khoá</div>
        </div>
      </div>
    </div>

    <!-- Search Bar: Thiết kế phẳng, tối giản -->
    <div class="sp-searchbar" style="padding:12px 16px;border-bottom:1px solid var(--border);">
      <div class="sp-search-input" style="background:var(--bg-secondary);border-radius:8px;border:none;">
        <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg></span>
        <input type="text" id="usersSearchInput" placeholder="Tên, SĐT, email..." oninput="usersSearchDebounce()" style="background:transparent;border:none;">
      </div>
    </div>

    <!-- Tab Navigation: Minimalist, flat design -->
    <div class="sp-tabs" id="usersTabBar" style="border-bottom:1px solid var(--border);">
      <button class="sp-tab active" data-tab="pending" onclick="switchUsersTab('pending',this)" style="font-size:13px;font-weight:500;color:var(--primary);border-bottom:2px solid var(--primary);">Chờ duyệt <span class="users-tab-count-pending" style="background:var(--primary);color:white;border-radius:10px;padding:1px 6px;font-size:11px;margin-left:4px;">—</span></button>
      <button class="sp-tab" data-tab="broker" onclick="switchUsersTab('broker',this)" style="font-size:13px;font-weight:500;color:var(--text-secondary);border-bottom:2px solid transparent;">eBroker <span class="users-tab-count-broker" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:10px;padding:1px 6px;font-size:11px;margin-left:4px;">—</span></button>
      <button class="sp-tab" data-tab="sale" onclick="switchUsersTab('sale',this)" style="font-size:13px;font-weight:500;color:var(--text-secondary);border-bottom:2px solid transparent;">Sale <span class="users-tab-count-sale" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:10px;padding:1px 6px;font-size:11px;margin-left:4px;">—</span></button>
      <button class="sp-tab" data-tab="locked" onclick="switchUsersTab('locked',this)" style="font-size:13px;font-weight:500;color:var(--text-secondary);border-bottom:2px solid transparent;">Khoá <span class="users-tab-count-locked" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:10px;padding:1px 6px;font-size:11px;margin-left:4px;">—</span></button>
    </div>

    <div class="sp-scroll" style="padding-bottom:16px;">
      <div id="usersListContainer">
        <!-- Skeleton loader -->
        <div id="usersSkeletonLoader" style="padding:16px;">
          <div style="height:90px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;animation:pulse 1.5s infinite;"></div>
          <div style="height:90px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;animation:pulse 1.5s infinite;"></div>
          <div style="height:90px;background:var(--bg-secondary);border-radius:12px;animation:pulse 1.5s infinite;"></div>
        </div>
      </div>
      <div style="height:16px;"></div>
    </div>

  </div><!-- end subpage-users -->

  <!-- Role Change Bottom Sheet -->
  <div class="reject-sheet" id="userRoleSheet" style="display:none;">
    <div class="reject-sheet-inner">
      <div class="rs-handle"></div>
      <div class="rs-title">Đổi role người dùng</div>
      <input type="hidden" id="userRoleSheetId" value="">
      <div class="rs-reasons" id="userRoleOptions">
        <div class="rs-reason" onclick="changeUserRole('broker')">
          <span class="rs-reason-icon">🏠</span>
          <span class="rs-reason-text">eBroker — Môi giới bất động sản</span>
        </div>
        <div class="rs-reason" onclick="changeUserRole('bds_admin')">
          <span class="rs-reason-icon">🏘️</span>
          <span class="rs-reason-text">BĐS Admin — Quản lý khu vực + duyệt tin</span>
        </div>
        <div class="rs-reason" onclick="changeUserRole('sale')">
          <span class="rs-reason-icon">💼</span>
          <span class="rs-reason-text">Sale — Nhân viên chăm sóc khách hàng</span>
        </div>
        <div class="rs-reason" onclick="changeUserRole('sale_admin')">
          <span class="rs-reason-icon">📋</span>
          <span class="rs-reason-text">Sale Admin — Quản lý đội Sale</span>
        </div>
        <div class="rs-reason" onclick="changeUserRole('customer')">
          <span class="rs-reason-icon">👤</span>
          <span class="rs-reason-text">Khách hàng — Hạ cấp về tài khoản cơ bản</span>
        </div>
      </div>
      <button class="rs-submit" style="background:var(--bg-secondary);color:var(--text-secondary);" onclick="document.getElementById('userRoleSheet').style.display='none'">Huỷ</button>
    </div>
  </div>

