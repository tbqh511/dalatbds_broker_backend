  <!-- ========== SUBPAGE: DUYỆT BĐS ========== -->
  <div class="subpage" id="subpage-approvebds">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('approvebds')">←</button>
      <div class="sp-title">🏘️ Duyệt BĐS</div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="showToast('Xuất danh sách chờ duyệt')">📄</button>
      </div>
    </div>

    <div class="admin-hero red-grad">
      <div class="ah-label">HÀNG CHỜ DUYỆT — OPERATOR</div>
      <div class="ah-main">8 BĐS chờ xem xét</div>
      <div class="ah-grid">
        <div class="ah-stat"><div class="ah-stat-val">8</div><div class="ah-stat-lbl">Chờ duyệt</div></div>
        <div class="ah-stat"><div class="ah-stat-val">3</div><div class="ah-stat-lbl">Hôm nay</div></div>
        <div class="ah-stat"><div class="ah-stat-val">156</div><div class="ah-stat-lbl">Đã duyệt</div></div>
        <div class="ah-stat"><div class="ah-stat-val">4.2h</div><div class="ah-stat-lbl">TB xử lý</div></div>
      </div>
    </div>

    <div class="sp-tabs">
      <button class="sp-tab active" onclick="spTabSwitch(this)">Chờ duyệt (8)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đã duyệt hôm nay</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Từ chối</button>
    </div>

    <div class="sp-scroll" style="padding-bottom:16px;">

      <!-- BĐS 1 — Hot, mới gửi -->
      <div class="abds-card" id="abds1">
        <div class="abds-img gs1">🏡
          <div class="abds-img-overlay"></div>
          <div class="abds-img-tags">
            <span class="badge badge-blue">Đất ở</span>
            <span class="badge" style="background:rgba(239,68,68,.9);color:#fff;">🔴 Mới</span>
          </div>
          <div class="abds-img-price">1,000 triệu</div>
          <div class="abds-img-time">15/03 · 2 giờ trước</div>
        </div>
        <div class="abds-body">
          <div class="abds-title">Bán Đất ở phân quyền, Đường Yersin, Phường Cam Ly</div>
          <div class="abds-addr">📍 Đường Yersin, P.Cam Ly, TP.Đà Lạt</div>
          <div class="abds-specs">
            <div class="abds-spec">📐 250 m²</div>
            <div class="abds-spec">🧭 Đông Nam</div>
            <div class="abds-spec">🛣 MT 12m</div>
          </div>
        </div>
        <div class="abds-broker">
          <div class="abds-broker-avatar">HT</div>
          <span class="abds-broker-name">Huy Thái · eBroker</span>
          <span class="abds-broker-time">15/03/2026 13:22</span>
        </div>
        <div class="abds-legal">
          <div class="abds-legal-title">Kiểm tra pháp lý</div>
          <div class="abds-legal-item"><div class="abds-legal-dot yes">✓</div><span class="abds-legal-text">Sổ đỏ / GCNQSD — Đã upload hình</span></div>
          <div class="abds-legal-item"><div class="abds-legal-dot yes">✓</div><span class="abds-legal-text">Đất trong quy hoạch ở</span></div>
          <div class="abds-legal-item"><div class="abds-legal-dot maybe">!</div><span class="abds-legal-text">Hệ số xây dựng — Chưa rõ</span></div>
          <div class="abds-legal-item"><div class="abds-legal-dot yes">✓</div><span class="abds-legal-text">Ảnh thực tế đầy đủ (5 ảnh)</span></div>
        </div>
        <div class="abds-actions">
          <button class="abds-btn view" onclick="openDetail({title:'Đất Đường Yersin',price:'1,000 triệu',type:'Đất ở',area:'250 m²',addr:'P.Cam Ly',room:'—',slide:0})">👁 Xem</button>
          <button class="abds-btn reject" onclick="openRejectSheet('abds1')">✕ Từ chối</button>
          <button class="abds-btn approve" onclick="approveAbds('abds1','Bán Đất Đường Yersin')">✓ Duyệt</button>
        </div>
      </div>

      <!-- BĐS 2 -->
      <div class="abds-card" id="abds2">
        <div class="abds-img gs3">🏠
          <div class="abds-img-overlay"></div>
          <div class="abds-img-tags"><span class="badge badge-amber">Nhà phố</span></div>
          <div class="abds-img-price">2,800 triệu</div>
          <div class="abds-img-time">15/03 · 4 giờ trước</div>
        </div>
        <div class="abds-body">
          <div class="abds-title">Nhà mặt tiền Trần Phú, 4PN, Trung tâm Đà Lạt</div>
          <div class="abds-addr">📍 Đường Trần Phú, P.1, TP.Đà Lạt</div>
          <div class="abds-specs">
            <div class="abds-spec">📐 120 m²</div>
            <div class="abds-spec">🏠 4 PN</div>
            <div class="abds-spec">⚖️ Sổ hồng</div>
          </div>
        </div>
        <div class="abds-broker">
          <div class="abds-broker-avatar" style="background:var(--teal);">MK</div>
          <span class="abds-broker-name">Minh Khoa · eBroker</span>
          <span class="abds-broker-time">15/03/2026 11:05</span>
        </div>
        <div class="abds-legal">
          <div class="abds-legal-title">Kiểm tra pháp lý</div>
          <div class="abds-legal-item"><div class="abds-legal-dot yes">✓</div><span class="abds-legal-text">Sổ hồng đầy đủ</span></div>
          <div class="abds-legal-item"><div class="abds-legal-dot yes">✓</div><span class="abds-legal-text">Không có tranh chấp</span></div>
          <div class="abds-legal-item"><div class="abds-legal-dot yes">✓</div><span class="abds-legal-text">10 ảnh thực tế chất lượng tốt</span></div>
        </div>
        <div class="abds-actions">
          <button class="abds-btn view" onclick="openDetail({title:'Nhà phố Trần Phú',price:'2,800 triệu',type:'Nhà phố',area:'120 m²',addr:'P.1',room:'4 PN',slide:2})">👁 Xem</button>
          <button class="abds-btn reject" onclick="openRejectSheet('abds2')">✕ Từ chối</button>
          <button class="abds-btn approve" onclick="approveAbds('abds2','Nhà phố Trần Phú')">✓ Duyệt</button>
        </div>
      </div>

      <!-- BĐS 3 — Thiếu hồ sơ -->
      <div class="abds-card" id="abds3">
        <div class="abds-img gs4">🏡
          <div class="abds-img-overlay"></div>
          <div class="abds-img-tags"><span class="badge badge-purple">Biệt thự</span></div>
          <div class="abds-img-price">4,200 triệu</div>
          <div class="abds-img-time">14/03 · 1 ngày trước</div>
        </div>
        <div class="abds-body">
          <div class="abds-title">Biệt thự View Đồi Cam Ly, 4PN, Sân vườn rộng</div>
          <div class="abds-addr">📍 P.Cam Ly, TP.Đà Lạt</div>
          <div class="abds-specs">
            <div class="abds-spec">📐 350 m²</div>
            <div class="abds-spec">🏠 4 PN</div>
            <div class="abds-spec" style="color:var(--danger)">⚠ Thiếu SĐ</div>
          </div>
        </div>
        <div class="abds-broker">
          <div class="abds-broker-avatar" style="background:#f59e0b;">AL</div>
          <span class="abds-broker-name">Anh Linh · eBroker</span>
          <span class="abds-broker-time">14/03/2026 16:30</span>
        </div>
        <div class="abds-legal">
          <div class="abds-legal-title">Kiểm tra pháp lý</div>
          <div class="abds-legal-item"><div class="abds-legal-dot no">✕</div><span class="abds-legal-text" style="color:var(--danger);">Sổ đỏ / SĐ — Chưa upload</span></div>
          <div class="abds-legal-item"><div class="abds-legal-dot yes">✓</div><span class="abds-legal-text">Quy hoạch ở rõ ràng</span></div>
          <div class="abds-legal-item"><div class="abds-legal-dot maybe">!</div><span class="abds-legal-text">Ảnh chỉ có 2 ảnh — Cần thêm</span></div>
        </div>
        <div style="padding:8px 13px;background:var(--danger-light);border-top:1px solid #fca5a5;display:flex;gap:8px;align-items:center;">
          <span style="font-size:14px;">⚠️</span>
          <div>
            <div style="font-size:11px;font-weight:700;color:var(--danger);">Không đủ điều kiện duyệt</div>
            <div style="font-size:10px;color:#b91c1c;">Thiếu sổ đỏ + cần thêm ảnh thực tế</div>
          </div>
        </div>
        <div class="abds-actions">
          <button class="abds-btn view" onclick="openDetail({title:'Biệt thự View Đồi Cam Ly',price:'4,200 triệu',type:'Biệt thự',area:'350 m²',addr:'P.Cam Ly',room:'4 PN',slide:3})">👁 Xem</button>
          <button class="abds-btn reject" style="flex:2;" onclick="openRejectSheet('abds3')">✕ Yêu cầu bổ sung hồ sơ</button>
        </div>
      </div>

      <div style="padding:12px 14px 4px;font-size:11px;font-weight:600;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:.05em;">Thêm 5 tin đang chờ · Vuốt để xem</div>
      <div style="padding:8px 14px;font-size:13px;color:var(--text-secondary);text-align:center;">... và 5 BĐS khác chờ duyệt</div>
      <div style="height:16px;"></div>
    </div>

    <!-- Reject sheet -->
    <div class="reject-sheet" id="rejectSheet">
      <div class="reject-sheet-inner">
        <div class="rs-handle"></div>
        <div class="rs-title">✕ Lý do từ chối / Yêu cầu bổ sung</div>
        <div class="rs-reasons">
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon">📄</span>
            <span class="rs-reason-text">Thiếu giấy tờ pháp lý (sổ đỏ/hồng)</span>
          </div>
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon">📸</span>
            <span class="rs-reason-text">Ảnh không đủ / chất lượng kém</span>
          </div>
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon">📍</span>
            <span class="rs-reason-text">Thông tin vị trí không chính xác</span>
          </div>
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon">💰</span>
            <span class="rs-reason-text">Giá bất hợp lý / không thực tế</span>
          </div>
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon">⚠️</span>
            <span class="rs-reason-text">Đất trong vùng tranh chấp / quy hoạch đặc biệt</span>
          </div>
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon">✏️</span>
            <span class="rs-reason-text">Khác (ghi rõ bên dưới)</span>
          </div>
        </div>
        <textarea class="rs-note" rows="2" placeholder="Ghi chú thêm cho Broker (tùy chọn)..."></textarea>
        <button class="rs-submit" onclick="submitReject()">Gửi yêu cầu bổ sung → Broker</button>
      </div>
    </div>
  </div><!-- end subpage-approvebds -->


