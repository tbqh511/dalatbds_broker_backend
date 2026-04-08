  <!-- ========== SUBPAGE: QUẢN LÝ NGƯỜI DÙNG ========== -->
  <div class="subpage" id="subpage-users">

    <!-- Header -->
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('users')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Quản lý người dùng</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="loadUsers(true)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></button>
      </div>
    </div>

    <!-- Hidden stat holders — updated by JS -->
    <div id="usersPendingCount"   style="display:none;">—</div>
    <div id="usersBrokerCount"    style="display:none;">—</div>
    <div id="usersSaleCount"      style="display:none;">—</div>
    <div id="usersSaleAdminCount" style="display:none;">—</div>
    <div id="usersBdsAdminCount"  style="display:none;">—</div>
    <div id="usersAdminCount"     style="display:none;">—</div>

    <!-- Search Bar -->
    <div class="sp-searchbar" style="padding:12px 16px;border-bottom:1px solid var(--border);">
      <div class="sp-search-input" style="background:var(--bg-secondary);border-radius:8px;border:none;">
        <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg></span>
        <input type="text" id="usersSearchInput" placeholder="Tên, SĐT, email..." oninput="usersSearchDebounce()" style="background:transparent;border:none;">
      </div>
    </div>

    <!-- 6-Tab Navigation (horizontally scrollable) -->
    <div class="sp-tabs" id="usersTabBar" style="border-bottom:1px solid var(--border);overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none;">
      <button class="sp-tab active" data-tab="pending" onclick="switchUsersTab('pending',this)" style="white-space:nowrap;">
        Chờ duyệt <span class="users-tab-count-pending" style="background:var(--primary);color:#fff;border-radius:10px;padding:1px 6px;font-size:10px;margin-left:3px;">—</span>
      </button>
      <button class="sp-tab" data-tab="broker" onclick="switchUsersTab('broker',this)" style="white-space:nowrap;">
        Broker <span class="users-tab-count-broker" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:10px;padding:1px 6px;font-size:10px;margin-left:3px;">—</span>
      </button>
      <button class="sp-tab" data-tab="sale" onclick="switchUsersTab('sale',this)" style="white-space:nowrap;">
        Sale <span class="users-tab-count-sale" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:10px;padding:1px 6px;font-size:10px;margin-left:3px;">—</span>
      </button>
      <button class="sp-tab" data-tab="sale_admin" onclick="switchUsersTab('sale_admin',this)" style="white-space:nowrap;">
        Sale Admin <span class="users-tab-count-sale_admin" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:10px;padding:1px 6px;font-size:10px;margin-left:3px;">—</span>
      </button>
      <button class="sp-tab" data-tab="bds_admin" onclick="switchUsersTab('bds_admin',this)" style="white-space:nowrap;">
        BĐS Admin <span class="users-tab-count-bds_admin" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:10px;padding:1px 6px;font-size:10px;margin-left:3px;">—</span>
      </button>
      <button class="sp-tab" data-tab="admin" onclick="switchUsersTab('admin',this)" style="white-space:nowrap;">
        Admin <span class="users-tab-count-admin" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:10px;padding:1px 6px;font-size:10px;margin-left:3px;">—</span>
      </button>
    </div>

    <!-- List -->
    <div class="sp-scroll" style="padding-bottom:16px;">
      <div id="usersListContainer">
        <!-- Skeleton loader -->
        <div id="usersSkeletonLoader" style="padding:16px;">
          <div style="height:68px;background:var(--bg-secondary);border-radius:12px;margin-bottom:8px;animation:pulse 1.5s infinite;"></div>
          <div style="height:68px;background:var(--bg-secondary);border-radius:12px;margin-bottom:8px;animation:pulse 1.5s infinite;"></div>
          <div style="height:68px;background:var(--bg-secondary);border-radius:12px;animation:pulse 1.5s infinite;"></div>
        </div>
      </div>
      <div style="height:16px;"></div>
    </div>

  </div><!-- end subpage-users -->

  <!-- ===== User Action Bottom Sheet ===== -->
  <div class="reject-sheet" id="userActionSheet" style="display:none;" onclick="if(event.target===this)closeUserActionSheet()">
    <div class="reject-sheet-inner" style="padding-bottom:env(safe-area-inset-bottom);">
      <div class="rs-handle"></div>

      <!-- User info header -->
      <div id="userActionSheetHeader" style="display:flex;align-items:center;gap:12px;padding:4px 18px 16px;border-bottom:1px solid var(--border);">
        <div id="userActionSheetAvatar"
             style="width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;flex-shrink:0;background:#3270FC;">
          ??
        </div>
        <div style="flex:1;min-width:0;">
          <div id="userActionSheetName" style="font-size:15px;font-weight:700;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">—</div>
          <div id="userActionSheetMeta" style="font-size:12px;color:var(--text-secondary);margin-top:2px;">—</div>
        </div>
      </div>

      <!-- Action items (filled by JS) -->
      <div id="userActionSheetOptions" style="padding:6px 0;"></div>

      <!-- Cancel -->
      <div style="padding:4px 18px 8px;">
        <button onclick="closeUserActionSheet()"
                style="width:100%;padding:13px;background:var(--bg-secondary);border:none;border-radius:12px;font-size:14px;font-weight:500;color:var(--text-secondary);cursor:pointer;">
          Huỷ
        </button>
      </div>
    </div>
  </div>
