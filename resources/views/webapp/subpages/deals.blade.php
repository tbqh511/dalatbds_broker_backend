<!-- ========== SUBPAGE: DEAL ĐANG CHĂM ========== -->
<div class="subpage" id="subpage-deals">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('deals')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Deal đang chăm</span></div>
    <div class="sp-actions"></div>
  </div>

  <!-- KPI strip -->
  <div class="kpi-strip">
    <div class="kpi-item" onclick="dealsTabSwitch(document.getElementById('dealsTabOpen'),'open')" style="cursor:pointer;">
      <div class="kpi-val" id="dealsKpiActive" style="color:var(--primary)">—</div>
      <div class="kpi-lbl">Đang chăm</div>
    </div>
    <div class="kpi-item" onclick="dealsTabSwitch(document.getElementById('dealsTabNegotiating'),'negotiating')" style="cursor:pointer;">
      <div class="kpi-val" id="dealsKpiNegotiating" style="color:var(--warning)">—</div>
      <div class="kpi-lbl">Thương lượng</div>
    </div>
    <div class="kpi-item" onclick="dealsTabSwitch(document.getElementById('dealsTabClosed'),'closed')" style="cursor:pointer;">
      <div class="kpi-val" id="dealsKpiClosed" style="color:var(--success)">—</div>
      <div class="kpi-lbl">Đã chốt</div>
    </div>
    <div class="kpi-item">
      <div class="kpi-val" id="dealsKpiCommission" style="color:var(--success)">—</div>
      <div class="kpi-lbl">HH dự kiến</div>
    </div>
  </div>

  <!-- Search bar -->
  <div class="sp-searchbar">
    <div class="sp-search-input">
      <span style="display:inline-flex;align-items:center;color:var(--text-tertiary)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg>
      </span>
      <input type="text" id="dealsSearchInput" placeholder="Tên, SĐT khách..." oninput="dealsOnSearchInput(this.value)">
    </div>
  </div>

  <!-- Status tabs -->
  <div class="sp-tabs">
    <button class="sp-tab active" id="dealsTabAll"         onclick="dealsTabSwitch(this,'')">Tất cả</button>
    <button class="sp-tab"        id="dealsTabOpen"        onclick="dealsTabSwitch(this,'open')">Active</button>
    <button class="sp-tab"        id="dealsTabNegotiating" onclick="dealsTabSwitch(this,'negotiating')">Thương lượng</button>
    <button class="sp-tab"        id="dealsTabWaiting"     onclick="dealsTabSwitch(this,'waiting_finance')">Chờ tài chính</button>
    <button class="sp-tab"        id="dealsTabClosed"      onclick="dealsTabSwitch(this,'closed')">Đã chốt</button>
  </div>

  <div class="sp-scroll">
    <!-- Loading state -->
    <div id="dealsLoading" style="padding:40px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">
      <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
      Đang tải...
    </div>

    <!-- Empty state -->
    <div id="dealsEmpty" style="display:none;padding:48px 24px;text-align:center;">
      <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      <div style="font-size:14px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Chưa có deal nào</div>
      <div style="font-size:12px;color:var(--text-tertiary);">Các deal được tạo từ lead sẽ xuất hiện ở đây</div>
    </div>

    <!-- Dynamic list -->
    <div id="dealsList" style="display:none;"></div>

    <!-- Load more -->
    <div id="dealsLoadMore" style="display:none;padding:12px 16px;text-align:center;">
      <button onclick="dealsLoadMore()" style="padding:10px 28px;border:1.5px solid var(--border);border-radius:20px;font-size:13px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);cursor:pointer;">Xem thêm</button>
    </div>

    <div style="height:16px;"></div>
  </div>
</div>
