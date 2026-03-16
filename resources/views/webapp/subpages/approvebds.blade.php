  <!-- ========== SUBPAGE: DUYỆT BĐS ========== -->
  <div class="subpage" id="subpage-approvebds">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('approvebds')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg> Duyệt BĐS</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="showToast('Xuất danh sách chờ duyệt')"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></button>
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
        <div class="abds-img gs1" style="display:flex;align-items:center;justify-content:center;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>
          <div class="abds-img-overlay"></div>
          <div class="abds-img-tags">
            <span class="badge badge-blue">Đất ở</span>
            <span class="badge" style="background:rgba(239,68,68,.9);color:#fff;display:inline-flex;align-items:center;gap:3px;"><svg width="8" height="8" viewBox="0 0 24 24" fill="currentColor" stroke="none"><circle cx="12" cy="12" r="10"/></svg> Mới</span>
          </div>
          <div class="abds-img-price">1,000 triệu</div>
          <div class="abds-img-time">15/03 · 2 giờ trước</div>
        </div>
        <div class="abds-body">
          <div class="abds-title">Bán Đất ở phân quyền, Đường Yersin, Phường Cam Ly</div>
          <div class="abds-addr"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>Đường Yersin, P.Cam Ly, TP.Đà Lạt</div>
          <div class="abds-specs">
            <div class="abds-spec"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></span>250 m²</div>
            <div class="abds-spec"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg></span>Đông Nam</div>
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
          <button class="abds-btn view" onclick="openDetail({title:'Đất Đường Yersin',price:'1,000 triệu',type:'Đất ở',area:'250 m²',addr:'P.Cam Ly',room:'—',slide:0})"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Xem</span></button>
          <button class="abds-btn reject" onclick="openRejectSheet('abds1')">✕ Từ chối</button>
          <button class="abds-btn approve" onclick="approveAbds('abds1','Bán Đất Đường Yersin')">✓ Duyệt</button>
        </div>
      </div>

      <!-- BĐS 2 -->
      <div class="abds-card" id="abds2">
        <div class="abds-img gs3" style="display:flex;align-items:center;justify-content:center;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>
          <div class="abds-img-overlay"></div>
          <div class="abds-img-tags"><span class="badge badge-amber">Nhà phố</span></div>
          <div class="abds-img-price">2,800 triệu</div>
          <div class="abds-img-time">15/03 · 4 giờ trước</div>
        </div>
        <div class="abds-body">
          <div class="abds-title">Nhà mặt tiền Trần Phú, 4PN, Trung tâm Đà Lạt</div>
          <div class="abds-addr"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>Đường Trần Phú, P.1, TP.Đà Lạt</div>
          <div class="abds-specs">
            <div class="abds-spec"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></span>120 m²</div>
            <div class="abds-spec"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></span>4 PN</div>
            <div class="abds-spec"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg></span>Sổ hồng</div>
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
          <button class="abds-btn view" onclick="openDetail({title:'Nhà phố Trần Phú',price:'2,800 triệu',type:'Nhà phố',area:'120 m²',addr:'P.1',room:'4 PN',slide:2})"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Xem</span></button>
          <button class="abds-btn reject" onclick="openRejectSheet('abds2')">✕ Từ chối</button>
          <button class="abds-btn approve" onclick="approveAbds('abds2','Nhà phố Trần Phú')">✓ Duyệt</button>
        </div>
      </div>

      <!-- BĐS 3 — Thiếu hồ sơ -->
      <div class="abds-card" id="abds3">
        <div class="abds-img gs4" style="display:flex;align-items:center;justify-content:center;">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>
          <div class="abds-img-overlay"></div>
          <div class="abds-img-tags"><span class="badge badge-purple">Biệt thự</span></div>
          <div class="abds-img-price">4,200 triệu</div>
          <div class="abds-img-time">14/03 · 1 ngày trước</div>
        </div>
        <div class="abds-body">
          <div class="abds-title">Biệt thự View Đồi Cam Ly, 4PN, Sân vườn rộng</div>
          <div class="abds-addr"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>P.Cam Ly, TP.Đà Lạt</div>
          <div class="abds-specs">
            <div class="abds-spec"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></span>350 m²</div>
            <div class="abds-spec"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></span>4 PN</div>
            <div class="abds-spec" style="color:var(--danger)"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>Thiếu SĐ</div>
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
          <span style="display:inline-flex;align-items:center;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
          <div>
            <div style="font-size:11px;font-weight:700;color:var(--danger);">Không đủ điều kiện duyệt</div>
            <div style="font-size:10px;color:#b91c1c;">Thiếu sổ đỏ + cần thêm ảnh thực tế</div>
          </div>
        </div>
        <div class="abds-actions">
          <button class="abds-btn view" onclick="openDetail({title:'Biệt thự View Đồi Cam Ly',price:'4,200 triệu',type:'Biệt thự',area:'350 m²',addr:'P.Cam Ly',room:'4 PN',slide:3})"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Xem</span></button>
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
            <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span>
            <span class="rs-reason-text">Thiếu giấy tờ pháp lý (sổ đỏ/hồng)</span>
          </div>
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></span>
            <span class="rs-reason-text">Ảnh không đủ / chất lượng kém</span>
          </div>
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
            <span class="rs-reason-text">Thông tin vị trí không chính xác</span>
          </div>
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
            <span class="rs-reason-text">Giá bất hợp lý / không thực tế</span>
          </div>
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
            <span class="rs-reason-text">Đất trong vùng tranh chấp / quy hoạch đặc biệt</span>
          </div>
          <div class="rs-reason" onclick="selectRejectReason(this)">
            <span class="rs-reason-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span>
            <span class="rs-reason-text">Khác (ghi rõ bên dưới)</span>
          </div>
        </div>
        <textarea class="rs-note" rows="2" placeholder="Ghi chú thêm cho Broker (tùy chọn)..."></textarea>
        <button class="rs-submit" onclick="submitReject()">Gửi yêu cầu bổ sung → Broker</button>
      </div>
    </div>
  </div><!-- end subpage-approvebds -->


