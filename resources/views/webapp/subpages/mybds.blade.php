<!-- ========== SUBPAGE: BĐS CỦA TÔI ========== -->
<div class="subpage" id="subpage-mybds">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('mybds')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg> BĐS của tôi</span></div>
    <div class="sp-actions">
      <button class="sp-action-btn" onclick="openSheet()" title="Đăng tin mới">＋</button>
      <button class="sp-action-btn" onclick="mybdsToggleFilterSheet()" title="Sắp xếp">⋮</button>
    </div>
  </div>

  <!-- Summary strip -->
  <div class="sp-summary">
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="mybdsCountActive" style="color:var(--success);">—</div>
      <div class="sp-sum-lbl">Đang hiển thị</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="mybdsCountPending" style="color:var(--warning);">—</div>
      <div class="sp-sum-lbl">Chờ duyệt</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="mybdsCountHidden" style="color:var(--text-tertiary);">—</div>
      <div class="sp-sum-lbl">Đã ẩn</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" id="mybdsTotalViews">—</div>
      <div class="sp-sum-lbl">Tổng lượt xem</div>
    </div>
  </div>

  <!-- Search bar -->
  <div class="sp-searchbar">
    <div class="sp-search-input">
      <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg></span>
      <input type="text" id="mybdsSearchInput" placeholder="Tìm theo tiêu đề, địa chỉ..." oninput="mybdsOnSearchInput(this.value)">
    </div>
    <button class="sp-filter-btn" onclick="mybdsToggleFilterSheet()">
      <span style="display:inline-flex;align-items:center;gap:4px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M16.24 7.76a6 6 0 0 1 0 8.49M4.93 4.93a10 10 0 0 0 0 14.14M7.76 7.76a6 6 0 0 0 0 8.49"/></svg>
        Lọc
      </span>
    </button>
  </div>

  <!-- Status tabs -->
  <div class="sp-tabs">
    <button class="sp-tab active" id="mybdsTabAll"     onclick="mybdsTabSwitch(this,'all')">Tất cả (—)</button>
    <button class="sp-tab"        id="mybdsTabActive"  onclick="mybdsTabSwitch(this,'1')">Hiển thị (—)</button>
    <button class="sp-tab"        id="mybdsTabPending" onclick="mybdsTabSwitch(this,'0')">Chờ duyệt (—)</button>
    <button class="sp-tab"        id="mybdsTabHidden"  onclick="mybdsTabSwitch(this,'2')">Đã ẩn (—)</button>
  </div>

  <div class="sp-scroll">
    <!-- Loading state -->
    <div id="mybdsLoading" style="padding:40px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">
      <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
      Đang tải...
    </div>

    <!-- Empty state -->
    <div id="mybdsEmpty" style="display:none;padding:48px 24px;text-align:center;">
      <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px;"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>
      <div style="font-size:14px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Chưa có tin đăng nào</div>
      <div style="font-size:12px;color:var(--text-tertiary);">Nhấn ＋ để đăng tin BĐS đầu tiên của bạn</div>
    </div>

    <!-- Dynamic list -->
    <div id="mybdsList" style="display:none;"></div>

    <div style="height:16px;"></div>
  </div><!-- end sp-scroll -->

  <!-- Sort / Filter bottom sheet -->
  <div id="mybdsFilterSheet" style="display:none;position:fixed;inset:0;z-index:9999;" onclick="mybdsCloseFilterSheet()">
    <div style="position:absolute;bottom:0;left:0;right:0;background:var(--surface);border-radius:18px 18px 0 0;padding:16px 0 32px;" onclick="event.stopPropagation()">
      <div style="width:36px;height:4px;background:var(--border);border-radius:2px;margin:0 auto 16px;"></div>
      <div style="font-size:13px;font-weight:700;color:var(--text-primary);padding:0 16px 12px;">Sắp xếp theo</div>
      <button class="mybds-sort-opt" id="mybdsSortLatest"   onclick="mybdsSortSelect('latest')"    style="display:flex;align-items:center;width:100%;padding:12px 16px;background:none;border:none;font-size:13px;color:var(--text-primary);cursor:pointer;gap:10px;">✓ Mới nhất</button>
      <button class="mybds-sort-opt" id="mybdsSortOldest"   onclick="mybdsSortSelect('oldest')"    style="display:flex;align-items:center;width:100%;padding:12px 16px;background:none;border:none;font-size:13px;color:var(--text-secondary);cursor:pointer;gap:10px;">  Cũ nhất</button>
      <button class="mybds-sort-opt" id="mybdsSortViews"    onclick="mybdsSortSelect('views')"     style="display:flex;align-items:center;width:100%;padding:12px 16px;background:none;border:none;font-size:13px;color:var(--text-secondary);cursor:pointer;gap:10px;">  Lượt xem nhiều nhất</button>
      <button class="mybds-sort-opt" id="mybdsSortPriceAsc" onclick="mybdsSortSelect('price_asc')" style="display:flex;align-items:center;width:100%;padding:12px 16px;background:none;border:none;font-size:13px;color:var(--text-secondary);cursor:pointer;gap:10px;">  Giá tăng dần</button>
      <button class="mybds-sort-opt" id="mybdsSortPriceDesc"onclick="mybdsSortSelect('price_desc')"style="display:flex;align-items:center;width:100%;padding:12px 16px;background:none;border:none;font-size:13px;color:var(--text-secondary);cursor:pointer;gap:10px;">  Giá giảm dần</button>
    </div>
  </div>
</div><!-- end subpage-mybds -->
