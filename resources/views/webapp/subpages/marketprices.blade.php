  <!-- ========== SUBPAGE: GIÁ THỊ TRƯỜNG ========== -->
  <div class="subpage" id="subpage-marketprices">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('marketprices')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/><polyline points="1 4 1 20 23 20"/></svg> Giá thị trường Đà Lạt</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="loadMarketPrices(true)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></button>
      </div>
    </div>

    <div class="admin-hero green-grad">
      <div class="ah-label">GIÁ THỊ TRƯỜNG — ADMIN</div>
      <div class="ah-main"><span id="mpHeroMain">— khu vực</span></div>
      <div class="ah-grid">
        <div class="ah-stat"><div class="ah-stat-val" id="mpAreaCount">—</div><div class="ah-stat-lbl">Khu vực</div></div>
        <div class="ah-stat"><div class="ah-stat-val" id="mpCurrentMonth" style="font-size:11px;">—</div><div class="ah-stat-lbl">Tháng HT</div></div>
        <div class="ah-stat"><div class="ah-stat-val" id="mpAvgPrice">—</div><div class="ah-stat-lbl">Giá TB</div></div>
        <div class="ah-stat"><div class="ah-stat-val" id="mpRecordCount">—</div><div class="ah-stat-lbl">Tổng bản ghi</div></div>
      </div>
    </div>

    <div style="padding:12px 14px 0;">
      <button onclick="mpOpenAddForm()" style="width:100%;padding:10px;background:var(--primary);color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Thêm khu vực mới
      </button>
    </div>

    <div class="sp-tabs" id="mpTabBar" style="margin-top:10px;"></div>

    <div class="sp-scroll" style="padding-bottom:16px;">
      <div id="mpListContainer">
        <div id="mpSkeletonLoader" style="padding:16px;">
          <div style="height:70px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;animation:pulse 1.5s infinite;"></div>
          <div style="height:70px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;animation:pulse 1.5s infinite;animation-delay:.15s;"></div>
          <div style="height:70px;background:var(--bg-secondary);border-radius:12px;animation:pulse 1.5s infinite;animation-delay:.3s;"></div>
        </div>
      </div>
    </div>

    <!-- Bottom sheet: Add / Edit form -->
    <div class="reject-sheet" id="mpFormSheet" style="display:none;">
      <div class="reject-sheet-inner" style="max-height:85vh;overflow-y:auto;">
        <div class="rs-handle"></div>
        <div class="rs-title" id="mpFormTitle">Thêm khu vực mới</div>
        <input type="hidden" id="mpFormId" value="">

        <div style="display:flex;flex-direction:column;gap:12px;margin-top:4px;">
          <div>
            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:4px;">Tên khu vực *</div>
            <input id="mpFormAreaName" type="text" placeholder="VD: Phường 1, Đường 3/4..." style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:14px;box-sizing:border-box;background:var(--bg-primary);color:var(--text-primary);">
          </div>
          <div>
            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:4px;">Giá TB (VNĐ/m²) *</div>
            <input id="mpFormAvgPrice" type="number" min="0" placeholder="VD: 28500000" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:14px;box-sizing:border-box;background:var(--bg-primary);color:var(--text-primary);">
          </div>
          <div>
            <div style="font-size:12px;color:var(--text-secondary);margin-bottom:4px;">Giá tháng trước (VNĐ/m²) — tuỳ chọn</div>
            <input id="mpFormPrevPrice" type="number" min="0" placeholder="Để trống nếu không có" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:14px;box-sizing:border-box;background:var(--bg-primary);color:var(--text-primary);">
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div>
              <div style="font-size:12px;color:var(--text-secondary);margin-bottom:4px;">Tháng *</div>
              <input id="mpFormMonth" type="number" min="1" max="12" placeholder="1–12" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:14px;box-sizing:border-box;background:var(--bg-primary);color:var(--text-primary);">
            </div>
            <div>
              <div style="font-size:12px;color:var(--text-secondary);margin-bottom:4px;">Năm *</div>
              <input id="mpFormYear" type="number" min="2020" max="2100" placeholder="VD: 2026" style="width:100%;padding:10px 12px;border:1px solid var(--border);border-radius:8px;font-size:14px;box-sizing:border-box;background:var(--bg-primary);color:var(--text-primary);">
            </div>
          </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:16px;">
          <button onclick="mpCloseForm()" style="flex:1;padding:12px;background:var(--bg-secondary);color:var(--text-secondary);border:none;border-radius:10px;font-size:14px;cursor:pointer;">Huỷ</button>
          <button onclick="mpSubmitForm()" id="mpFormSubmitBtn" style="flex:2;padding:12px;background:var(--primary);color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;">Lưu</button>
        </div>
      </div>
    </div>

    <!-- Bottom sheet: Delete confirm -->
    <div class="reject-sheet" id="mpDeleteSheet" style="display:none;">
      <div class="reject-sheet-inner">
        <div class="rs-handle"></div>
        <div class="rs-title">Xoá bản ghi này?</div>
        <input type="hidden" id="mpDeleteId" value="">
        <div id="mpDeleteConfirmText" style="font-size:13px;color:var(--text-secondary);margin-bottom:16px;text-align:center;line-height:1.5;"></div>
        <div style="display:flex;gap:10px;">
          <button onclick="mpCloseDelete()" style="flex:1;padding:12px;background:var(--bg-secondary);color:var(--text-secondary);border:none;border-radius:10px;font-size:14px;cursor:pointer;">Huỷ</button>
          <button onclick="mpConfirmDelete()" style="flex:2;padding:12px;background:#ef4444;color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;">Xoá</button>
        </div>
      </div>
    </div>

  </div><!-- end subpage-marketprices -->
