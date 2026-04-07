  <!-- ========== SUBPAGE: QUẢN LÝ NGƯỜI DÙNG ========== -->
  <div class="subpage" id="subpage-users">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('users')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Quản lý người dùng</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="loadUsers(true)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></button>
      </div>
    </div>



    <div class="sp-searchbar" style="padding:10px 14px;">
      <div class="sp-search-input">
        <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg></span>
        <input type="text" id="usersSearchInput" placeholder="Tên, SĐT, email..." oninput="usersSearchDebounce()">
      </div>
    </div>

    <div class="sp-tabs" id="usersTabBar">
      <button class="sp-tab active" data-tab="pending" onclick="switchUsersTab('pending',this)">Chờ duyệt (<span class="users-tab-count-pending">—</span>)</button>
      <button class="sp-tab" data-tab="broker" onclick="switchUsersTab('broker',this)">eBroker (<span class="users-tab-count-broker">—</span>)</button>
      <button class="sp-tab" data-tab="sale" onclick="switchUsersTab('sale',this)">Sale (<span class="users-tab-count-sale">—</span>)</button>
      <button class="sp-tab" data-tab="locked" onclick="switchUsersTab('locked',this)">Bị khoá (<span class="users-tab-count-locked">—</span>)</button>
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

