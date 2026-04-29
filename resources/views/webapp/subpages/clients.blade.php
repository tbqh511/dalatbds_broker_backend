<!-- ========== SUBPAGE: KHÁCH HÀNG (SALE CRM) ========== -->
<div class="subpage" id="subpage-clients">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('clients')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title">
      <span style="display:inline-flex;align-items:center;gap:5px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Khách của tôi
      </span>
    </div>
    <div class="sp-actions">
      <button class="sp-action-btn" onclick="openSheet()" title="Thêm khách hàng">＋</button>
    </div>
  </div>

  <!-- Summary strip — values populated by JS -->
  <div class="sp-summary">
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="clientsKpiNew" style="color:var(--danger);">—</div>
      <div class="sp-sum-lbl">Mới</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="clientsKpiCaring" style="color:var(--primary);">—</div>
      <div class="sp-sum-lbl">Đang chăm</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="clientsKpiViewing" style="color:var(--purple);">—</div>
      <div class="sp-sum-lbl">Hẹn xem</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="clientsKpiClosed" style="color:var(--success);">—</div>
      <div class="sp-sum-lbl">Đã chốt</div>
    </div>
  </div>

  <!-- Search bar -->
  <div class="sp-searchbar d-flex justify-content-between align-items-center mb-3">
    <div class="sp-search-input flex-grow-1 mr-2">
      <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </span>
      <input type="text" id="clientsSearchInput" placeholder="Tên, số điện thoại..." oninput="clientsOnSearchInput(this.value)">
    </div>
    <button class="sp-filter-btn" style="margin-left: 10px;" onclick="openFilterSheet('filterSheet')">
      <span style="display:inline-flex;align-items:center;gap:4px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
      </span> Lọc
    </button>
  </div>

  <!-- Status tabs — labels populated by JS -->
  <div class="sp-tabs">
    <button class="sp-tab active" id="clientsTabNew"     onclick="clientsTabSwitch(this,'new')">Mới (—)</button>
    <button class="sp-tab"        id="clientsTabCaring"  onclick="clientsTabSwitch(this,'caring')">Chăm (—)</button>
    <button class="sp-tab"        id="clientsTabViewing" onclick="clientsTabSwitch(this,'viewing')">Hẹn (—)</button>
    <button class="sp-tab"        id="clientsTabClosed"  onclick="clientsTabSwitch(this,'closed')">Chốt (—)</button>
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
