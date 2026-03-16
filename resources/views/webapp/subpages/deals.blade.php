  <!-- ========== SUBPAGE: DEAL ĐANG CHĂM ========== -->
  <div class="subpage" id="subpage-deals">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('deals')">←</button>
      <div class="sp-title">🤝 Deal đang chăm</div>
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
          <span class="badge badge-purple">🤝 Đang chăm</span>
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
            <div class="dc-bds-thumb gs1">🏡</div>
            <div class="dc-bds-info">
              <div class="dc-bds-title">Đất Đường Yersin, Cam Ly</div>
              <div class="dc-bds-price">1,000 triệu</div>
              <div class="dc-bds-area">250 m²</div>
            </div>
            <span class="dc-bds-status dbs-liked">❤️ Ưng ý</span>
          </div>
          <div class="dc-bds-item" onclick="openDetail({title:'Nhà phố Trần Phú',price:'2,800 triệu',type:'Nhà phố',area:'120 m²',addr:'P.1',room:'4 PN',slide:2})">
            <div class="dc-bds-thumb gs2">🏠</div>
            <div class="dc-bds-info">
              <div class="dc-bds-title">Nhà phố Trần Phú</div>
              <div class="dc-bds-price">2,800 triệu</div>
              <div class="dc-bds-area">120 m²</div>
            </div>
            <span class="dc-bds-status dbs-dislike">✕ Không ưng</span>
          </div>
          <div class="dc-bds-item">
            <div class="dc-bds-thumb gs3">🌿</div>
            <div class="dc-bds-info">
              <div class="dc-bds-title">Đất nền Lâm Viên</div>
              <div class="dc-bds-price">650 triệu</div>
              <div class="dc-bds-area">180 m²</div>
            </div>
            <span class="dc-bds-status dbs-sent">📤 Đã gửi</span>
          </div>
          <div class="dc-add-bds" onclick="openSendModal();closeSubpage('deals')">
            <div class="dc-add-icon">＋</div>
            <div class="dc-add-text">Gửi thêm BĐS phù hợp cho khách</div>
          </div>
        </div>
        <div class="dc-footer">
          <span class="dc-footer-date">📅 Lịch xem: 16/03 09:00</span>
          <button class="dc-footer-btn" onclick="showToast('Mở chat khách...')">💬 Chat</button>
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
          <span class="badge badge-amber">⚡ Thương lượng</span>
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
            <div class="dc-stage"><div class="dc-stage-dot warn">⚡</div><div class="dc-stage-label active">Thương lượng</div></div>
            <div class="dc-stage-line"></div>
            <div class="dc-stage"><div class="dc-stage-dot">6</div><div class="dc-stage-label">Chốt</div></div>
          </div>
        </div>
        <div class="dc-bds-list">
          <div style="padding:8px 13px;background:var(--warning-light);border-top:1px solid #fde68a;">
            <div style="font-size:11px;font-weight:700;color:var(--warning);margin-bottom:3px;">⚡ Đang thương lượng</div>
            <div style="font-size:12px;color:var(--text-secondary);">Khách đề nghị 7,800 tr · Chủ chốt 8,000 tr · Chênh 200 triệu</div>
          </div>
          <div class="dc-bds-item" onclick="openDetail({title:'Biệt thự View Đồi Chè',price:'8,500 triệu',type:'Biệt thự',area:'580 m²',addr:'Xuân Trường',room:'5 PN',slide:1})">
            <div class="dc-bds-thumb gs2">🏡</div>
            <div class="dc-bds-info">
              <div class="dc-bds-title">Biệt thự View Đồi Chè Cầu Đất</div>
              <div class="dc-bds-price">8,500 triệu</div>
              <div class="dc-bds-area">580 m² · 5 PN</div>
            </div>
            <span class="dc-bds-status dbs-negotiating">⚡ Đàm phán</span>
          </div>
        </div>
        <div class="dc-footer">
          <span class="dc-footer-date">Xem nhà 14/03 · Chờ phản hồi</span>
          <button class="dc-footer-btn warning" onclick="showToast('Cập nhật giá...')">📝 Cập nhật giá</button>
          <button class="dc-footer-btn success" onclick="showToast('🎉 Tạo commission!')">🎉 Chốt!</button>
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
          <span class="badge badge-blue">🆕 Mới tạo</span>
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
          <button class="dc-footer-btn" onclick="showToast('Mở chat...')">💬 Chat</button>
          <button class="dc-footer-btn primary" onclick="openSendModal();closeSubpage('deals')">📤 Gửi BĐS</button>
        </div>
      </div>

      <div style="height:16px"></div>
    </div>
  </div>

