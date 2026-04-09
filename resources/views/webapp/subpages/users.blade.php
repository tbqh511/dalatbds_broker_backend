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
    <div id="usersBrokersCount"  style="display:none;">—</div>
    <div id="usersSalesCount"    style="display:none;">—</div>
    <div id="usersAdminCount"    style="display:none;">—</div>

    <!-- Search Bar -->
    <div class="sp-searchbar" style="padding:10px 16px;border-bottom:1px solid var(--border);">
      <div class="sp-search-input" style="background:var(--bg-secondary);border-radius:8px;border:none;">
        <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg></span>
        <input type="text" id="usersSearchInput" placeholder="Tên, SĐT, email..." oninput="usersSearchDebounce()" autocomplete="off" style="background:transparent;border:none;">
      </div>
    </div>

    <!-- 3-Tab Navigation -->
    <div class="sp-tabs" id="usersTabBar" style="border-bottom:1px solid var(--border);overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:none;">
      <button class="sp-tab active" data-tab="brokers" onclick="switchUsersTab('brokers',this)" style="white-space:nowrap;">
        Brokers <span class="users-tab-count-brokers" style="background:var(--primary);color:#fff;border-radius:10px;padding:1px 6px;font-size:10px;margin-left:3px;">—</span>
      </button>
      <button class="sp-tab" data-tab="sales" onclick="switchUsersTab('sales',this)" style="white-space:nowrap;">
        Đội ngũ Sales <span class="users-tab-count-sales" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:10px;padding:1px 6px;font-size:10px;margin-left:3px;">—</span>
      </button>
      <button class="sp-tab" data-tab="management" onclick="switchUsersTab('management',this)" style="white-space:nowrap;">
        Quản trị <span class="users-tab-count-management" style="background:var(--bg-secondary);color:var(--text-secondary);border-radius:10px;padding:1px 6px;font-size:10px;margin-left:3px;">—</span>
      </button>
    </div>

    <!-- List -->
    <div class="sp-scroll" style="padding-bottom:16px;">
      <div id="usersListContainer">
        <!-- Skeleton loader -->
        <div id="usersSkeletonLoader" style="padding:12px 16px;">
          <div style="height:64px;background:var(--bg-secondary);border-radius:10px;margin-bottom:8px;animation:pulse 1.5s infinite;"></div>
          <div style="height:64px;background:var(--bg-secondary);border-radius:10px;margin-bottom:8px;animation:pulse 1.5s infinite;"></div>
          <div style="height:64px;background:var(--bg-secondary);border-radius:10px;animation:pulse 1.5s infinite;"></div>
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
        <div style="position:relative;flex-shrink:0;">
          <div id="userActionSheetAvatar"
               style="width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;background:var(--primary);">
            ??
          </div>
          <div id="userActionSheetStatusDot"
               style="position:absolute;bottom:0;right:0;width:11px;height:11px;border-radius:50%;background:var(--primary);border:2px solid var(--bg-primary);"></div>
        </div>
        <div style="flex:1;min-width:0;">
          <div id="userActionSheetName" style="font-size:15px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">—</div>
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
