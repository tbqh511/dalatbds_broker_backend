<!-- ========== SUBPAGE: KHÁCH HÀNG ========== -->
<div class="subpage" id="subpage-clients">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('clients')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title">
      <span style="display:inline-flex;align-items:center;gap:5px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Khách hàng
      </span>
    </div>
    <div class="sp-actions"></div>
  </div>

  <!-- Search bar -->
  <div class="sp-searchbar">
    <div class="sp-search-input">
      <span style="display:inline-flex;align-items:center;color:var(--text-tertiary)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg>
      </span>
      <input type="text" id="clientsSearchInput" placeholder="Tên, SĐT..." oninput="clientsOnSearchInput(this.value)">
    </div>
  </div>

  <!-- Status tabs -->
  <div class="sp-tabs">
    <button class="sp-tab active" id="clientsTabAll"      onclick="clientsTabSwitch(this,'')">Tất cả</button>
    <button class="sp-tab"        id="clientsTabNew"      onclick="clientsTabSwitch(this,'new')">Mới</button>
    <button class="sp-tab"        id="clientsTabCare"     onclick="clientsTabSwitch(this,'care')">Đang chăm</button>
    <button class="sp-tab"        id="clientsTabWaiting"  onclick="clientsTabSwitch(this,'waiting')">Chờ chốt</button>
    <button class="sp-tab"        id="clientsTabArchived" onclick="clientsTabSwitch(this,'archived')">Lưu trữ</button>
  </div>

  <div class="sp-scroll">
    <!-- Loading state -->
    <div id="clientsLoading" style="padding:40px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">
      <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
      Đang tải...
    </div>

    <!-- Empty state -->
    <div id="clientsEmpty" style="display:none;padding:48px 24px;text-align:center;">
      <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      <div style="font-size:14px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Chưa có khách hàng nào</div>
      <div style="font-size:12px;color:var(--text-tertiary);">Khách hàng mới sẽ xuất hiện ở đây</div>
    </div>

    <!-- Dynamic list -->
    <div id="clientsList" style="display:none;"></div>

    <!-- Load more -->
    <div id="clientsLoadMore" style="display:none;padding:12px 16px;text-align:center;">
      <button onclick="clientsLoadMore()" style="padding:10px 28px;border:1.5px solid var(--border);border-radius:20px;font-size:13px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);cursor:pointer;">Xem thêm</button>
    </div>

    <div style="height:16px;"></div>
  </div>
</div>
