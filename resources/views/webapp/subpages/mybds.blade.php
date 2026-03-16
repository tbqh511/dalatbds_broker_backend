  <!-- ========== SUBPAGE: BĐS CỦA TÔI ========== -->
  <div class="subpage" id="subpage-mybds">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('mybds')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg> BĐS của tôi</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="openSheet()">＋</button>
        <button class="sp-action-btn">⋮</button>
      </div>
    </div>

    <!-- Summary strip -->
    <div class="sp-summary">
      <div class="sp-sum-item">
        <div class="sp-sum-val" style="color:var(--success);">3</div>
        <div class="sp-sum-lbl">Đang hiển thị</div>
      </div>
      <div class="sp-sum-item">
        <div class="sp-sum-val" style="color:var(--warning);">2</div>
        <div class="sp-sum-lbl">Chờ duyệt</div>
      </div>
      <div class="sp-sum-item">
        <div class="sp-sum-val" style="color:var(--text-tertiary);">1</div>
        <div class="sp-sum-lbl">Đã ẩn</div>
      </div>
      <div class="sp-sum-item">
        <div class="sp-sum-val">8,849</div>
        <div class="sp-sum-lbl">Tổng lượt xem</div>
      </div>
    </div>

    <!-- Search bar -->
    <div class="sp-searchbar">
      <div class="sp-search-input">
        <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg></span>
        <input type="text" placeholder="Tìm theo tiêu đề, địa chỉ...">
      </div>
      <button class="sp-filter-btn"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M16.24 7.76a6 6 0 0 1 0 8.49M4.93 4.93a10 10 0 0 0 0 14.14M7.76 7.76a6 6 0 0 0 0 8.49"/></svg> Lọc</span></button>
    </div>

    <!-- Status tabs -->
    <div class="sp-tabs">
      <button class="sp-tab active" onclick="spTabSwitch(this)">Tất cả (6)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đang hiển thị (3)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Chờ duyệt (2)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đã ẩn (1)</button>
    </div>

    <div class="sp-scroll">

      <!-- BĐS CARD 1 — Đang hiển thị -->
      <div class="mybds-card">
        <div class="mybds-img gs1" style="display:flex;align-items:center;justify-content:center;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-active">● Đang hiển thị</span>
          </div>
          <div class="mybds-img-price">1,000 triệu</div>
          <div class="mybds-img-stats">
            <div class="mybds-stat-chip"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> 3</span></div>
            <div class="mybds-stat-chip"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> 2</span></div>
          </div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title">Bán Đất ở phân quyền, Đường Yersin, Phường Cam Ly</div>
          <div class="mybds-addr"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>Đường Yersin, P.Cam Ly, Đà Lạt</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></span>250 m²</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg></span>Sổ đỏ</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg></span>Đông Nam</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg></span>Mua</div>
          </div>
        </div>
        <!-- performance bars -->
        <div class="perf-row">
          <span class="perf-label">Lượt xem</span>
          <div class="perf-bar-bg"><div class="perf-bar-fill" style="width:4%;"></div></div>
          <span class="perf-val">3</span>
        </div>
        <div class="perf-row">
          <span class="perf-label">Quan tâm</span>
          <div class="perf-bar-bg"><div class="perf-bar-fill" style="width:22%;background:var(--danger);"></div></div>
          <span class="perf-val">2</span>
        </div>
        <div class="mybds-footer">
          <div class="mybds-analytics">
            <div class="mybds-analytic"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Đăng 14/03/2026</div>
          </div>
          <div class="mybds-quick">
            <div class="mybds-qbtn" onclick="openDetail({title:'Bán Đất ở phân quyền, Đường Yersin',price:'1,000 triệu',type:'Đất ở',area:'250 m²',addr:'P.Cam Ly',room:'—',slide:0})" title="Xem"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></div>
            <div class="mybds-qbtn" title="Chỉnh sửa"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
            <div class="mybds-qbtn" onclick="showToast('Đã ẩn tin')" title="Ẩn tin"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></div>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')" title="Xóa"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></div>
          </div>
        </div>
      </div>

      <!-- BĐS CARD 2 — Đang hiển thị -->
      <div class="mybds-card">
        <div class="mybds-img gs3" style="display:flex;align-items:center;justify-content:center;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-active">● Đang hiển thị</span>
          </div>
          <div class="mybds-img-price">2,800 triệu</div>
          <div class="mybds-img-stats">
            <div class="mybds-stat-chip"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> 24</span></div>
            <div class="mybds-stat-chip"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> 7</span></div>
          </div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title">Nhà mặt tiền Đường Trần Phú, gần chợ Đà Lạt</div>
          <div class="mybds-addr"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>Đường Trần Phú, P.1, Đà Lạt</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></span>120 m²</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></span>4 PN</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg></span>Sổ hồng</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg></span>Mua</div>
          </div>
        </div>
        <div class="perf-row">
          <span class="perf-label">Lượt xem</span>
          <div class="perf-bar-bg"><div class="perf-bar-fill" style="width:28%;"></div></div>
          <span class="perf-val">24</span>
        </div>
        <div class="perf-row">
          <span class="perf-label">Quan tâm</span>
          <div class="perf-bar-bg"><div class="perf-bar-fill" style="width:35%;background:var(--danger);"></div></div>
          <span class="perf-val">7</span>
        </div>
        <div class="mybds-footer">
          <div class="mybds-analytics">
            <div class="mybds-analytic"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Đăng 10/03/2026</div>
          </div>
          <div class="mybds-quick">
            <div class="mybds-qbtn" onclick="openDetail({title:'Nhà mặt tiền Đường Trần Phú',price:'2,800 triệu',type:'Nhà phố',area:'120 m²',addr:'P.1, Đà Lạt',room:'4 PN',slide:2})"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></div>
            <div class="mybds-qbtn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
            <div class="mybds-qbtn" onclick="showToast('Đã ẩn tin')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></div>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></div>
          </div>
        </div>
      </div>

      <!-- BĐS CARD 3 — Chờ duyệt -->
      <div class="mybds-card">
        <div class="mybds-img gs4" style="display:flex;align-items:center;justify-content:center;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-pending">⏳ Chờ duyệt</span>
          </div>
          <div class="mybds-img-price">4,200 triệu</div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title">Biệt thự View Đồi Cam Ly, 4 phòng ngủ, sân vườn rộng</div>
          <div class="mybds-addr"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>P.Cam Ly, Đà Lạt</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></span>350 m²</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></span>4 PN</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4.97-5 9-8.58 9-12A9 9 0 0 0 3 10c0 3.42 4.03 7 9 12z"/></svg></span>Sân vườn</div>
          </div>
        </div>
        <!-- pending notice -->
        <div style="padding:10px 13px;background:var(--warning-light);border-top:1px solid #fde68a;display:flex;gap:8px;align-items:center;">
          <span style="font-size:14px;">⏳</span>
          <div>
            <div style="font-size:11px;font-weight:600;color:var(--warning);">Đang chờ Admin duyệt</div>
            <div style="font-size:10px;color:#b45309;">Gửi 15/03/2026 · Thường duyệt trong 24h</div>
          </div>
          <button style="margin-left:auto;padding:4px 10px;background:var(--warning);color:#fff;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;" onclick="showToast('Đã rút tin')">Rút tin</button>
        </div>
        <div class="mybds-footer">
          <div class="mybds-analytics"><div class="mybds-analytic"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Gửi 15/03/2026</div></div>
          <div class="mybds-quick">
            <div class="mybds-qbtn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></div>
          </div>
        </div>
      </div>

      <!-- BĐS CARD 4 — Đang hiển thị, hiệu suất tốt -->
      <div class="mybds-card">
        <div class="mybds-img gs2" style="display:flex;align-items:center;justify-content:center;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-active">● Đang hiển thị</span>
            <span class="status-pill" style="background:rgba(255,193,7,0.9);color:#000;display:inline-flex;align-items:center;gap:2px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="#000" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Nổi bật</span>
          </div>
          <div class="mybds-img-price">8,500 triệu</div>
          <div class="mybds-img-stats">
            <div class="mybds-stat-chip"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> 87</span></div>
            <div class="mybds-stat-chip"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> 15</span></div>
          </div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title">Biệt thự View Đồi Chè Cầu Đất, Đà Lạt — 5PN</div>
          <div class="mybds-addr"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>Xã Xuân Trường, Huyện Đà Lạt</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></span>580 m²</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></span>5 PN</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg></span>Sổ đỏ</div>
          </div>
        </div>
        <div class="perf-row">
          <span class="perf-label">Lượt xem</span>
          <div class="perf-bar-bg"><div class="perf-bar-fill" style="width:100%;background:var(--success);"></div></div>
          <span class="perf-val" style="color:var(--success);">87</span>
        </div>
        <div class="perf-row">
          <span class="perf-label">Quan tâm</span>
          <div class="perf-bar-bg"><div class="perf-bar-fill" style="width:78%;background:var(--danger);"></div></div>
          <span class="perf-val" style="color:var(--danger);">15</span>
        </div>
        <div class="mybds-footer">
          <div class="mybds-analytics"><div class="mybds-analytic" style="color:var(--success);font-weight:600;display:inline-flex;align-items:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2c0 0-7 6-7 12a7 7 0 0 0 14 0c0-6-7-12-7-12z"/></svg> Đang hot!</div></div>
          <div class="mybds-quick">
            <div class="mybds-qbtn" onclick="openDetail({title:'Biệt thự View Đồi Chè Cầu Đất',price:'8,500 triệu',type:'Biệt thự',area:'580 m²',addr:'Xuân Trường, Đà Lạt',room:'5 PN',slide:1})"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></div>
            <div class="mybds-qbtn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
            <div class="mybds-qbtn" onclick="showToast('Đã ẩn tin')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg></div>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></div>
          </div>
        </div>
      </div>

      <!-- BĐS CARD 5 — Đã ẩn -->
      <div class="mybds-card" style="opacity:0.7;">
        <div class="mybds-img" style="background:#8b8b8b;display:flex;align-items:center;justify-content:center;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-hidden">⊘ Đã ẩn</span>
          </div>
          <div class="mybds-img-price" style="opacity:0.7;">1,500 triệu</div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title" style="color:var(--text-secondary);">Nhà phố Đường 3/4, P.Lâm Viên</div>
          <div class="mybds-addr"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>Đường 3/4, P.Lâm Viên, Đà Lạt</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></span>130 m²</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></span>3 PN</div>
          </div>
        </div>
        <div class="mybds-footer">
          <div class="mybds-analytics"><div class="mybds-analytic"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Ẩn từ 01/03/2026</div></div>
          <div class="mybds-quick">
            <button style="padding:5px 12px;background:var(--primary);color:#fff;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;" onclick="showToast('Đã bật hiển thị tin')">Hiển thị lại</button>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></div>
          </div>
        </div>
      </div>

      <!-- BĐS CARD 6 — Chờ duyệt -->
      <div class="mybds-card">
        <div class="mybds-img gs1" style="filter:brightness(0.6);display:flex;align-items:center;justify-content:center;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-pending">⏳ Chờ duyệt</span>
          </div>
          <div class="mybds-img-price">650 triệu</div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title">Đất nền hẻm Đường 3/2, P.Lâm Viên, 180m²</div>
          <div class="mybds-addr"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>Hẻm Đường 3/2, P.Lâm Viên</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></span>180 m²</div>
            <div class="mybds-meta-item"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg></span>Giấy tay</div>
          </div>
        </div>
        <div style="padding:10px 13px;background:var(--warning-light);border-top:1px solid #fde68a;display:flex;gap:8px;align-items:center;">
          <span style="font-size:14px;">⏳</span>
          <div style="flex:1;">
            <div style="font-size:11px;font-weight:600;color:var(--warning);">Đang chờ Admin duyệt</div>
            <div style="font-size:10px;color:#b45309;">Gửi 13/03/2026</div>
          </div>
        </div>
        <div class="mybds-footer">
          <div class="mybds-analytics"><div class="mybds-analytic"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Gửi 13/03/2026</div></div>
          <div class="mybds-quick">
            <div class="mybds-qbtn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></div>
          </div>
        </div>
      </div>

      <div style="height:16px;"></div>
    </div><!-- end sp-scroll -->
  </div><!-- end subpage-mybds -->


