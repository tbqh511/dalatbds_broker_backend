  <!-- ========== SUBPAGE: DEAL ĐANG CHĂM ========== -->
  <div class="subpage" id="subpage-deals">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('deals')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Deal đang chăm</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="showToast('Tạo Deal mới')">＋</button>
      </div>
    </div>
    <div class="kpi-strip">
      <div class="kpi-item"><div class="kpi-val" style="color:var(--primary)">3</div><div class="kpi-lbl">Đang chăm</div></div>
      <div class="kpi-item"><div class="kpi-val" style="color:var(--warning)">1</div><div class="kpi-lbl">Thương lượng</div></div>
      <div class="kpi-item"><div class="kpi-val" style="color:var(--success)">12</div><div class="kpi-lbl">Đã chốt</div></div>
      <div class="kpi-item"><div class="kpi-val" style="color:var(--success)">450tr</div><div class="kpi-lbl">HH dự kiến</div></div>
    </div>
    <div class="sp-tabs">
      <button class="sp-tab active" onclick="spTabSwitch(this)">Active (3)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Thương lượng</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Chờ tài chính</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đã chốt</button>
    </div>
    <div class="sp-scroll">

      <!-- DEAL 1 — Đang chăm, có lịch xem -->
      <div class="deal-card">
        <div class="dc-head">
          <div class="dc-avatar" style="background:var(--primary)">MT</div>
          <div class="dc-info">
            <div class="dc-name">Anh Minh Tuấn — Deal #1</div>
            <div class="dc-sub">Tìm Đất ở · 800tr–1.2 tỷ · P.Cam Ly</div>
          </div>
          <span class="badge badge-purple"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Đang chăm</span></span>
        </div>
        <div class="dc-progress">
          <div class="dc-stages">
            <div class="dc-stage"><div class="dc-stage-dot done">✓</div><div class="dc-stage-label done">Lead</div></div>
            <div class="dc-stage-line done"></div>
            <div class="dc-stage"><div class="dc-stage-dot done">✓</div><div class="dc-stage-label done">Deal</div></div>
            <div class="dc-stage-line done"></div>
            <div class="dc-stage"><div class="dc-stage-dot active">3</div><div class="dc-stage-label active">Chăm sóc</div></div>
            <div class="dc-stage-line"></div>
            <div class="dc-stage"><div class="dc-stage-dot">4</div><div class="dc-stage-label">Xem nhà</div></div>
            <div class="dc-stage-line"></div>
            <div class="dc-stage"><div class="dc-stage-dot">5</div><div class="dc-stage-label">Thương lượng</div></div>
            <div class="dc-stage-line"></div>
            <div class="dc-stage"><div class="dc-stage-dot">6</div><div class="dc-stage-label">Chốt</div></div>
          </div>
        </div>
        <!-- BĐS đã gửi -->
        <div class="dc-bds-list">
          <div style="padding:6px 13px;font-size:10px;font-weight:700;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:.05em;">BĐS đã gửi (3)</div>
          <div class="dc-bds-item" onclick="openDetail({title:'Đất Đường Yersin',price:'1,000 triệu',type:'Đất ở',area:'250 m²',addr:'P.Cam Ly',room:'—',slide:0})">
            <div class="dc-bds-thumb gs1" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></div>
            <div class="dc-bds-info">
              <div class="dc-bds-title">Đất Đường Yersin, Cam Ly</div>
              <div class="dc-bds-price">1,000 triệu</div>
              <div class="dc-bds-area">250 m²</div>
            </div>
            <span class="dc-bds-status dbs-liked"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Ưng ý</span></span>
          </div>
          <div class="dc-bds-item" onclick="openDetail({title:'Nhà phố Trần Phú',price:'2,800 triệu',type:'Nhà phố',area:'120 m²',addr:'P.1',room:'4 PN',slide:2})">
            <div class="dc-bds-thumb gs2" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></div>
            <div class="dc-bds-info">
              <div class="dc-bds-title">Nhà phố Trần Phú</div>
              <div class="dc-bds-price">2,800 triệu</div>
              <div class="dc-bds-area">120 m²</div>
            </div>
            <span class="dc-bds-status dbs-dislike">✕ Không ưng</span>
          </div>
          <div class="dc-bds-item">
            <div class="dc-bds-thumb gs3" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4.97-5 9-8.58 9-12A9 9 0 0 0 3 10c0 3.42 4.03 7 9 12z"/></svg></div>
            <div class="dc-bds-info">
              <div class="dc-bds-title">Đất nền Lâm Viên</div>
              <div class="dc-bds-price">650 triệu</div>
              <div class="dc-bds-area">180 m²</div>
            </div>
            <span class="dc-bds-status dbs-sent"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 2 15 22 11 13 2 9 22 2"/></svg> Đã gửi</span></span>
          </div>
          <div class="dc-add-bds" onclick="openSendModal();closeSubpage('deals')">
            <div class="dc-add-icon">＋</div>
            <div class="dc-add-text">Gửi thêm BĐS phù hợp cho khách</div>
          </div>
        </div>
        <div class="dc-footer">
          <span class="dc-footer-date"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Lịch xem: 16/03 09:00</span>
          <button class="dc-footer-btn" onclick="showToast('Mở chat khách...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Chat</span></button>
          <button class="dc-footer-btn primary" onclick="openStatusSheet('deal1')">Cập nhật</button>
        </div>
      </div>

      <!-- DEAL 2 — Đang thương lượng -->
      <div class="deal-card">
        <div class="dc-head">
          <div class="dc-avatar" style="background:var(--purple)">TH</div>
          <div class="dc-info">
            <div class="dc-name">Chị Thu Hà — Deal #2</div>
            <div class="dc-sub">Biệt thự View Đồi Chè · 8,500 triệu</div>
          </div>
          <span class="badge badge-amber"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Thương lượng</span></span>
        </div>
        <div class="dc-progress">
          <div class="dc-stages">
            <div class="dc-stage"><div class="dc-stage-dot done">✓</div><div class="dc-stage-label done">Lead</div></div>
            <div class="dc-stage-line done"></div>
            <div class="dc-stage"><div class="dc-stage-dot done">✓</div><div class="dc-stage-label done">Deal</div></div>
            <div class="dc-stage-line done"></div>
            <div class="dc-stage"><div class="dc-stage-dot done">✓</div><div class="dc-stage-label done">Chăm sóc</div></div>
            <div class="dc-stage-line done"></div>
            <div class="dc-stage"><div class="dc-stage-dot done">✓</div><div class="dc-stage-label done">Xem nhà</div></div>
            <div class="dc-stage-line active"></div>
            <div class="dc-stage"><div class="dc-stage-dot warn" style="display:flex;align-items:center;justify-content:center;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div><div class="dc-stage-label active">Thương lượng</div></div>
            <div class="dc-stage-line"></div>
            <div class="dc-stage"><div class="dc-stage-dot">6</div><div class="dc-stage-label">Chốt</div></div>
          </div>
        </div>
        <div class="dc-bds-list">
          <div style="padding:8px 13px;background:var(--warning-light);border-top:1px solid #fde68a;">
            <div style="font-size:11px;font-weight:700;color:var(--warning);margin-bottom:3px;display:flex;align-items:center;gap:4px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Đang thương lượng</div>
            <div style="font-size:12px;color:var(--text-secondary);">Khách đề nghị 7,800 tr · Chủ chốt 8,000 tr · Chênh 200 triệu</div>
          </div>
          <div class="dc-bds-item" onclick="openDetail({title:'Biệt thự View Đồi Chè',price:'8,500 triệu',type:'Biệt thự',area:'580 m²',addr:'Xuân Trường',room:'5 PN',slide:1})">
            <div class="dc-bds-thumb gs2" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></div>
            <div class="dc-bds-info">
              <div class="dc-bds-title">Biệt thự View Đồi Chè Cầu Đất</div>
              <div class="dc-bds-price">8,500 triệu</div>
              <div class="dc-bds-area">580 m² · 5 PN</div>
            </div>
            <span class="dc-bds-status dbs-negotiating"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Đàm phán</span></span>
          </div>
        </div>
        <div class="dc-footer">
          <span class="dc-footer-date">Xem nhà 14/03 · Chờ phản hồi</span>
          <button class="dc-footer-btn warning" onclick="showToast('Cập nhật giá...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Cập nhật giá</span></button>
          <button class="dc-footer-btn success" onclick="showToast('Tạo commission!')">✓ Chốt!</button>
        </div>
      </div>

      <!-- DEAL 3 — Mới, chưa có BĐS -->
      <div class="deal-card">
        <div class="dc-head">
          <div class="dc-avatar" style="background:var(--teal)">NL</div>
          <div class="dc-info">
            <div class="dc-name">Anh Ngọc Lâm — Deal #3</div>
            <div class="dc-sub">Tìm Nhà phố · 1.5–3 tỷ · P.Lâm Viên</div>
          </div>
          <span class="badge badge-blue">Mới tạo</span>
        </div>
        <div class="dc-progress">
          <div class="dc-stages">
            <div class="dc-stage"><div class="dc-stage-dot done">✓</div><div class="dc-stage-label done">Lead</div></div>
            <div class="dc-stage-line done"></div>
            <div class="dc-stage"><div class="dc-stage-dot active">2</div><div class="dc-stage-label active">Deal mới</div></div>
            <div class="dc-stage-line"></div>
            <div class="dc-stage"><div class="dc-stage-dot">3</div><div class="dc-stage-label">Chăm sóc</div></div>
            <div class="dc-stage-line"></div>
            <div class="dc-stage"><div class="dc-stage-dot">4</div><div class="dc-stage-label">Xem nhà</div></div>
            <div class="dc-stage-line"></div>
            <div class="dc-stage"><div class="dc-stage-dot">5</div><div class="dc-stage-label">Thương lượng</div></div>
            <div class="dc-stage-line"></div>
            <div class="dc-stage"><div class="dc-stage-dot">6</div><div class="dc-stage-label">Chốt</div></div>
          </div>
        </div>
        <div class="dc-add-bds" onclick="openSendModal();closeSubpage('deals')">
          <div class="dc-add-icon">＋</div>
          <div class="dc-add-text">Bắt đầu gửi BĐS phù hợp cho khách</div>
        </div>
        <div class="dc-footer">
          <span class="dc-footer-date">Tạo 15/03/2026 · Chưa có BĐS</span>
          <button class="dc-footer-btn" onclick="showToast('Mở chat...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Chat</span></button>
          <button class="dc-footer-btn primary" onclick="openSendModal();closeSubpage('deals')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi BĐS</span></button>
        </div>
      </div>

      <div style="height:16px"></div>
    </div>
  </div>

