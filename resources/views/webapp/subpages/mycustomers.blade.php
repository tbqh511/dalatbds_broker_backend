<!-- ========== SUBPAGE: KHÁCH CỦA TÔI ========== -->
<div class="subpage" id="subpage-mycustomers">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('mycustomers')">←</button>
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
      <div class="sp-sum-val" id="mycustCountNew" style="color:var(--danger);">—</div>
      <div class="sp-sum-lbl">Lead mới</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="mycustCountCare" style="color:var(--primary);">—</div>
      <div class="sp-sum-lbl">Đang chăm</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="mycustCountDeal" style="color:var(--purple);">—</div>
      <div class="sp-sum-lbl">Deal active</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="mycustCountClosed" style="color:var(--success);">—</div>
      <div class="sp-sum-lbl">Đã chốt</div>
    </div>
  </div>

  <!-- Search bar -->
  <div class="sp-searchbar">
    <div class="sp-search-input">
      <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </span>
      <input type="text" id="mycustSearchInput" placeholder="Tên, số điện thoại..." oninput="mycustOnSearchInput(this.value)">
    </div>
  </div>

  <!-- Status tabs — labels populated by JS -->
  <div class="sp-tabs">
    <button class="sp-tab active" id="mycustTabAll"       onclick="mycustTabSwitch(this,'all')">Tất cả (—)</button>
    <button class="sp-tab"        id="mycustTabNew"       onclick="mycustTabSwitch(this,'new')">Lead mới (—)</button>
    <button class="sp-tab"        id="mycustTabContacted" onclick="mycustTabSwitch(this,'contacted')">Đã liên hệ (—)</button>
    <button class="sp-tab"        id="mycustTabConverted" onclick="mycustTabSwitch(this,'converted')">Đang Deal (—)</button>
    <button class="sp-tab"        id="mycustTabLost"      onclick="mycustTabSwitch(this,'lost')">Đã chốt (—)</button>
  </div>

  <div class="sp-scroll">
    <!-- Loading state -->
    <div id="mycustLoading" style="padding:40px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">
      <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
      Đang tải...
    </div>

    <!-- Empty state -->
    <div id="mycustEmpty" style="display:none;padding:48px 24px;text-align:center;">
      <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      <div style="font-size:14px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Chưa có khách hàng nào</div>
      <div style="font-size:12px;color:var(--text-tertiary);">Nhấn ＋ để thêm khách hàng đầu tiên</div>
    </div>

    <!-- Dynamic customer list -->
    <div id="mycustList" style="display:none;"></div>

    <div style="height:16px;"></div>
  </div><!-- end sp-scroll -->
</div><!-- end subpage-mycustomers -->
