  <!-- ========== SUBPAGE: BĐS CỦA TÔI ========== -->
  <div class="subpage" id="subpage-mybds">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('mybds')">←</button>
      <div class="sp-title">🏡 BĐS của tôi</div>
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
        <span style="font-size:15px;color:var(--text-tertiary);">🔍</span>
        <input type="text" placeholder="Tìm theo tiêu đề, địa chỉ...">
      </div>
      <button class="sp-filter-btn">⚙️ Lọc</button>
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
        <div class="mybds-img gs1">
          🏡
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-active">● Đang hiển thị</span>
          </div>
          <div class="mybds-img-price">1,000 triệu</div>
          <div class="mybds-img-stats">
            <div class="mybds-stat-chip">👁 3</div>
            <div class="mybds-stat-chip">❤️ 2</div>
          </div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title">Bán Đất ở phân quyền, Đường Yersin, Phường Cam Ly</div>
          <div class="mybds-addr">📍 Đường Yersin, P.Cam Ly, Đà Lạt</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item">📐 250 m²</div>
            <div class="mybds-meta-item">⚖️ Sổ đỏ</div>
            <div class="mybds-meta-item">🧭 Đông Nam</div>
            <div class="mybds-meta-item">🔑 Mua</div>
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
            <div class="mybds-analytic">📅 Đăng 14/03/2026</div>
          </div>
          <div class="mybds-quick">
            <div class="mybds-qbtn" onclick="openDetail({title:'Bán Đất ở phân quyền, Đường Yersin',price:'1,000 triệu',type:'Đất ở',area:'250 m²',addr:'P.Cam Ly',room:'—',slide:0})" title="Xem">👁</div>
            <div class="mybds-qbtn" title="Chỉnh sửa">✏️</div>
            <div class="mybds-qbtn" onclick="showToast('Đã ẩn tin')" title="Ẩn tin">🙈</div>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')" title="Xóa">🗑</div>
          </div>
        </div>
      </div>

      <!-- BĐS CARD 2 — Đang hiển thị -->
      <div class="mybds-card">
        <div class="mybds-img gs3">
          🏠
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-active">● Đang hiển thị</span>
          </div>
          <div class="mybds-img-price">2,800 triệu</div>
          <div class="mybds-img-stats">
            <div class="mybds-stat-chip">👁 24</div>
            <div class="mybds-stat-chip">❤️ 7</div>
          </div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title">Nhà mặt tiền Đường Trần Phú, gần chợ Đà Lạt</div>
          <div class="mybds-addr">📍 Đường Trần Phú, P.1, Đà Lạt</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item">📐 120 m²</div>
            <div class="mybds-meta-item">🏠 4 PN</div>
            <div class="mybds-meta-item">⚖️ Sổ hồng</div>
            <div class="mybds-meta-item">🔑 Mua</div>
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
            <div class="mybds-analytic">📅 Đăng 10/03/2026</div>
          </div>
          <div class="mybds-quick">
            <div class="mybds-qbtn" onclick="openDetail({title:'Nhà mặt tiền Đường Trần Phú',price:'2,800 triệu',type:'Nhà phố',area:'120 m²',addr:'P.1, Đà Lạt',room:'4 PN',slide:2})">👁</div>
            <div class="mybds-qbtn">✏️</div>
            <div class="mybds-qbtn" onclick="showToast('Đã ẩn tin')">🙈</div>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')">🗑</div>
          </div>
        </div>
      </div>

      <!-- BĐS CARD 3 — Chờ duyệt -->
      <div class="mybds-card">
        <div class="mybds-img gs4">
          🏡
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-pending">⏳ Chờ duyệt</span>
          </div>
          <div class="mybds-img-price">4,200 triệu</div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title">Biệt thự View Đồi Cam Ly, 4 phòng ngủ, sân vườn rộng</div>
          <div class="mybds-addr">📍 P.Cam Ly, Đà Lạt</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item">📐 350 m²</div>
            <div class="mybds-meta-item">🏠 4 PN</div>
            <div class="mybds-meta-item">🌿 Sân vườn</div>
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
          <div class="mybds-analytics"><div class="mybds-analytic">📅 Gửi 15/03/2026</div></div>
          <div class="mybds-quick">
            <div class="mybds-qbtn">✏️</div>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')">🗑</div>
          </div>
        </div>
      </div>

      <!-- BĐS CARD 4 — Đang hiển thị, hiệu suất tốt -->
      <div class="mybds-card">
        <div class="mybds-img gs2">
          🏘️
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-active">● Đang hiển thị</span>
            <span class="status-pill" style="background:rgba(255,193,7,0.9);color:#000;">⭐ Nổi bật</span>
          </div>
          <div class="mybds-img-price">8,500 triệu</div>
          <div class="mybds-img-stats">
            <div class="mybds-stat-chip">👁 87</div>
            <div class="mybds-stat-chip">❤️ 15</div>
          </div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title">Biệt thự View Đồi Chè Cầu Đất, Đà Lạt — 5PN</div>
          <div class="mybds-addr">📍 Xã Xuân Trường, Huyện Đà Lạt</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item">📐 580 m²</div>
            <div class="mybds-meta-item">🏠 5 PN</div>
            <div class="mybds-meta-item">⚖️ Sổ đỏ</div>
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
          <div class="mybds-analytics"><div class="mybds-analytic" style="color:var(--success);font-weight:600;">🔥 Đang hot!</div></div>
          <div class="mybds-quick">
            <div class="mybds-qbtn" onclick="openDetail({title:'Biệt thự View Đồi Chè Cầu Đất',price:'8,500 triệu',type:'Biệt thự',area:'580 m²',addr:'Xuân Trường, Đà Lạt',room:'5 PN',slide:1})">👁</div>
            <div class="mybds-qbtn">✏️</div>
            <div class="mybds-qbtn" onclick="showToast('Đã ẩn tin')">🙈</div>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')">🗑</div>
          </div>
        </div>
      </div>

      <!-- BĐS CARD 5 — Đã ẩn -->
      <div class="mybds-card" style="opacity:0.7;">
        <div class="mybds-img" style="background:#8b8b8b;">
          🏠
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-hidden">⊘ Đã ẩn</span>
          </div>
          <div class="mybds-img-price" style="opacity:0.7;">1,500 triệu</div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title" style="color:var(--text-secondary);">Nhà phố Đường 3/4, P.Lâm Viên</div>
          <div class="mybds-addr">📍 Đường 3/4, P.Lâm Viên, Đà Lạt</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item">📐 130 m²</div>
            <div class="mybds-meta-item">🏠 3 PN</div>
          </div>
        </div>
        <div class="mybds-footer">
          <div class="mybds-analytics"><div class="mybds-analytic">📅 Ẩn từ 01/03/2026</div></div>
          <div class="mybds-quick">
            <button style="padding:5px 12px;background:var(--primary);color:#fff;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;" onclick="showToast('Đã bật hiển thị tin')">Hiển thị lại</button>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')">🗑</div>
          </div>
        </div>
      </div>

      <!-- BĐS CARD 6 — Chờ duyệt -->
      <div class="mybds-card">
        <div class="mybds-img gs1" style="filter:brightness(0.6);">
          🏡
          <div class="mybds-img-overlay"></div>
          <div class="mybds-img-status">
            <span class="status-pill status-pending">⏳ Chờ duyệt</span>
          </div>
          <div class="mybds-img-price">650 triệu</div>
        </div>
        <div class="mybds-body">
          <div class="mybds-title">Đất nền hẻm Đường 3/2, P.Lâm Viên, 180m²</div>
          <div class="mybds-addr">📍 Hẻm Đường 3/2, P.Lâm Viên</div>
          <div class="mybds-meta">
            <div class="mybds-meta-item">📐 180 m²</div>
            <div class="mybds-meta-item">⚖️ Giấy tay</div>
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
          <div class="mybds-analytics"><div class="mybds-analytic">📅 Gửi 13/03/2026</div></div>
          <div class="mybds-quick">
            <div class="mybds-qbtn">✏️</div>
            <div class="mybds-qbtn danger" onclick="showToast('Đã xóa tin')">🗑</div>
          </div>
        </div>
      </div>

      <div style="height:16px;"></div>
    </div><!-- end sp-scroll -->
  </div><!-- end subpage-mybds -->


