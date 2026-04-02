<!-- ========== SUBPAGE: LEAD CỦA TÔI ========== -->
<div class="subpage" id="subpage-leads">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('leads')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title">
      <span style="display:inline-flex;align-items:center;gap:5px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
        Lead của tôi
      </span>
    </div>
    <div class="sp-actions"></div>
  </div>

  <!-- KPI strip -->
  <div class="kpi-strip">
    <div class="kpi-item" onclick="leadsTabSwitch(document.getElementById('leadsTabNew'),'new')" style="cursor:pointer;">
      <div class="kpi-val" id="leadsKpiNew" style="color:var(--danger)">—</div>
      <div class="kpi-lbl">Mới</div>
    </div>
    <div class="kpi-item" onclick="leadsTabSwitch(document.getElementById('leadsTabContacted'),'contacted')" style="cursor:pointer;">
      <div class="kpi-val" id="leadsKpiContacted" style="color:var(--primary)">—</div>
      <div class="kpi-lbl">Đã liên hệ</div>
    </div>
    <div class="kpi-item" onclick="leadsTabSwitch(document.getElementById('leadsTabConverted'),'converted')" style="cursor:pointer;">
      <div class="kpi-val" id="leadsKpiConverted" style="color:var(--success)">—</div>
      <div class="kpi-lbl">Đã chuyển</div>
    </div>
    <div class="kpi-item" onclick="leadsTabSwitch(document.getElementById('leadsTabLost'),'lost')" style="cursor:pointer;">
      <div class="kpi-val" id="leadsKpiLost" style="color:var(--text-secondary)">—</div>
      <div class="kpi-lbl">Huỷ</div>
    </div>
  </div>

  <!-- Search bar -->
  <div class="sp-searchbar">
    <div class="sp-search-input">
      <span style="display:inline-flex;align-items:center;color:var(--text-tertiary)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg>
      </span>
      <input type="text" id="leadsSearchInput" placeholder="Tên, SĐT..." oninput="leadsOnSearchInput(this.value)">
    </div>
  </div>

  <!-- Status tabs -->
  <div class="sp-tabs">
    <button class="sp-tab active" id="leadsTabAll"       onclick="leadsTabSwitch(this,'')">Tất cả</button>
    <button class="sp-tab"        id="leadsTabNew"       onclick="leadsTabSwitch(this,'new')">Mới</button>
    <button class="sp-tab"        id="leadsTabContacted" onclick="leadsTabSwitch(this,'contacted')">Đã liên hệ</button>
    <button class="sp-tab"        id="leadsTabConverted" onclick="leadsTabSwitch(this,'converted')">Đã chuyển</button>
    <button class="sp-tab"        id="leadsTabLost"      onclick="leadsTabSwitch(this,'lost')">Huỷ</button>
  </div>

  <div class="sp-scroll">
    <!-- Loading state -->
    <div id="leadsLoading" style="padding:40px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">
      <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
      Đang tải...
    </div>

    <!-- Empty state -->
    <div id="leadsEmpty" style="display:none;padding:48px 24px;text-align:center;">
      <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px;"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
      <div style="font-size:14px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Chưa có lead nào</div>
      <div style="font-size:12px;color:var(--text-tertiary);">Các lead được giao sẽ xuất hiện ở đây</div>
    </div>

    <!-- Dynamic list -->
    <div id="leadsList" style="display:none;"></div>

    <!-- Load more -->
    <div id="leadsLoadMore" style="display:none;padding:12px 16px;text-align:center;">
      <button onclick="leadsLoadMore()" style="padding:10px 28px;border:1.5px solid var(--border);border-radius:20px;font-size:13px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);cursor:pointer;">Xem thêm</button>
    </div>

    <div style="height:16px;"></div>
  </div>
</div>
