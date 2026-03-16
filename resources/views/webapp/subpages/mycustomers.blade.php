<!-- ========== SUBPAGE: KHÁCH CỦA TÔI ========== -->
<div class="subpage" id="subpage-mycustomers">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('mycustomers')">←</button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Khách của tôi</span></div>
    <div class="sp-actions">
      <button class="sp-action-btn" onclick="openSheet()">＋</button>
      <button class="sp-action-btn">⋮</button>
    </div>
  </div>

  <!-- Summary strip -->
  <div class="sp-summary">
    <div class="sp-sum-item">
      <div class="sp-sum-val" style="color:var(--danger);">3</div>
      <div class="sp-sum-lbl">Lead mới</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" style="color:var(--primary);">7</div>
      <div class="sp-sum-lbl">Đang chăm</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" style="color:var(--purple);">3</div>
      <div class="sp-sum-lbl">Deal active</div>
    </div>
    <div class="sp-sum-item">
      <div class="sp-sum-val" style="color:var(--success);">12</div>
      <div class="sp-sum-lbl">Đã chốt</div>
    </div>
  </div>

  <!-- Search bar -->
  <div class="sp-searchbar">
    <div class="sp-search-input">
      <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
      <input type="text" placeholder="Tên, số điện thoại...">
    </div>
    <button class="sp-filter-btn"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg> Lọc</span></button>
  </div>

  <!-- Status tabs -->
  <div class="sp-tabs">
    <button class="sp-tab active" onclick="spTabSwitch(this)">Tất cả (10)</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Lead mới (3)</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Đã liên hệ (4)</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Đang Deal (3)</button>
    <button class="sp-tab" onclick="spTabSwitch(this)">Đã chốt (12)</button>
  </div>

  <div class="sp-scroll">

    <!-- CUSTOMER 1 — Lead mới, chưa contact -->
    <div class="cust-card">
      <div class="cust-header">
        <div class="cust-avatar" style="background:#ef4444;">NT</div>
        <div class="cust-info">
          <div class="cust-name">Nguyễn Văn Tuấn</div>
          <div class="cust-meta">
            <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>0912.345.678</span>
            <span style="color:var(--danger);font-weight:600;">● 2 giờ trước</span>
          </div>
        </div>
        <div class="cust-status-badge">
          <span class="badge badge-red"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="8" height="8" viewBox="0 0 10 10" fill="var(--danger)" stroke="none"><circle cx="5" cy="5" r="5"/></svg></span>Lead mới</span>
        </div>
      </div>
      <div class="cust-body">
        <div class="cust-row">
          <span class="cust-row-label">Loại BĐS</span>
          <span class="cust-row-val">Biệt thự, Đất ở</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Nhu cầu</span>
          <span class="cust-row-val">Mua để ở + đầu tư</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Ngân sách</span>
          <span class="cust-row-val" style="color:var(--success);font-weight:600;">3 tỷ – 5 tỷ</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Khu vực</span>
          <span class="cust-row-val">P.Lâm Viên, P.Cam Ly</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Nguồn</span>
          <span class="cust-row-val">Facebook Ads</span>
        </div>
        <div class="cust-tags">
          <span class="cust-tag">Biệt thự</span>
          <span class="cust-tag">3–5 tỷ</span>
          <span class="cust-tag">Lâm Viên</span>
          <span class="cust-tag">Đầu tư</span>
        </div>
      </div>
      <!-- Lead flow -->
      <div class="lead-flow">
        <div class="lf-step">
          <div class="lf-dot active">1</div>
          <div class="lf-label active">Lead mới</div>
        </div>
        <div class="lf-line"></div>
        <div class="lf-step">
          <div class="lf-dot">2</div>
          <div class="lf-label">Đã liên hệ</div>
        </div>
        <div class="lf-line"></div>
        <div class="lf-step">
          <div class="lf-dot">3</div>
          <div class="lf-label">Tạo Deal</div>
        </div>
        <div class="lf-line"></div>
        <div class="lf-step">
          <div class="lf-dot">4</div>
          <div class="lf-label">Chăm sóc</div>
        </div>
        <div class="lf-line"></div>
        <div class="lf-step">
          <div class="lf-dot">5</div>
          <div class="lf-label">Chốt</div>
        </div>
      </div>
      <div class="cust-footer">
        <div class="cust-date"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Nhận 15/03/2026 · <span style="color:var(--danger);font-weight:600;">Chưa liên hệ!</span></div>
        <div class="cust-actions">
          <div class="cust-btn" title="Gọi" onclick="showToast('Đang gọi...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
          <div class="cust-btn" title="Nhắn tin" onclick="showToast('Mở chat Zalo...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
          <div class="cust-btn primary" title="Xác nhận & Tạo Deal" onclick="toggleCustDetail('cust1-detail')">▼ Chi tiết</div>
        </div>
      </div>
      <div class="cust-detail-panel" id="cust1-detail">
        <div class="cdp-note">
          <span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>Lead chưa được liên hệ sau 2 giờ. Hệ thống sẽ cảnh báo sau 24h nếu chưa xử lý.
        </div>
        <div style="display:flex;gap:8px;margin-top:12px;">
          <button style="flex:1;padding:10px;background:var(--primary);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;" onclick="showToast('✓ Đã xác nhận lead')">✓ Xác nhận đã liên hệ</button>
          <button style="flex:1;padding:10px;background:var(--success);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;" onclick="showToast('✓ Đang tạo Deal...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Tạo Deal ngay</span></button>
        </div>
      </div>
    </div>

    <!-- CUSTOMER 2 — Đang Deal, chăm sóc -->
    <div class="cust-card">
      <div class="cust-header">
        <div class="cust-avatar" style="background:var(--primary);">MT</div>
        <div class="cust-info">
          <div class="cust-name">Anh Minh Tuấn</div>
          <div class="cust-meta">
            <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>0901.234.567</span>
            <span>· Hôm nay 09:00</span>
          </div>
        </div>
        <div class="cust-status-badge">
          <span class="badge badge-purple"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>Đang Deal</span>
        </div>
      </div>
      <div class="cust-body">
        <div class="cust-row">
          <span class="cust-row-label">Loại BĐS</span>
          <span class="cust-row-val">Đất ở phân quyền</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Ngân sách</span>
          <span class="cust-row-val" style="color:var(--success);font-weight:600;">800tr – 1.2 tỷ</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Khu vực</span>
          <span class="cust-row-val">P.Cam Ly, Đường Yersin</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">BĐS đã gửi</span>
          <span class="cust-row-val">3 BĐS · 1 ưng ý</span>
        </div>
        <div class="cust-tags">
          <span class="cust-tag">Đất ở</span>
          <span class="cust-tag">Cam Ly</span>
          <span class="cust-tag" style="background:var(--success-light);color:var(--success);">Có lịch xem</span>
        </div>
      </div>
      <div class="lead-flow">
        <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Lead mới</div></div>
        <div class="lf-line done"></div>
        <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Đã liên hệ</div></div>
        <div class="lf-line done"></div>
        <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Tạo Deal</div></div>
        <div class="lf-line done"></div>
        <div class="lf-step"><div class="lf-dot active">4</div><div class="lf-label active">Chăm sóc</div></div>
        <div class="lf-line"></div>
        <div class="lf-step"><div class="lf-dot">5</div><div class="lf-label">Chốt</div></div>
      </div>
      <div class="cust-footer">
        <div class="cust-date"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Deal từ 10/03 · <span style="color:var(--primary);">Lịch xem: 16/03 09:00</span></div>
        <div class="cust-actions">
          <div class="cust-btn" onclick="showToast('Đang gọi...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
          <div class="cust-btn" onclick="showToast('Mở chat...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
          <div class="cust-btn primary" onclick="toggleCustDetail('cust2-detail')">▼ Chi tiết</div>
        </div>
      </div>
      <div class="cust-detail-panel" id="cust2-detail">
        <div class="cdp-section-title">BĐS đã gửi cho khách</div>
        <div class="cdp-bds-item" onclick="openDetail({title:'Đất ở Đường Yersin',price:'1,000 triệu',type:'Đất ở',area:'250 m²',addr:'P.Cam Ly',room:'—',slide:0})">
          <div class="cdp-bds-thumb gs1" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
          <div class="cdp-bds-info">
            <div class="cdp-bds-title">Đất Đường Yersin, Cam Ly</div>
            <div class="cdp-bds-meta">1,000 triệu · 250 m²</div>
          </div>
          <span class="cdp-bds-status" style="background:var(--success-light);color:var(--success);">Ưng ý ❤️</span>
        </div>
        <div class="cdp-bds-item" onclick="openDetail({title:'Nhà phố Trần Phú',price:'2,800 triệu',type:'Nhà phố',area:'120 m²',addr:'P.1',room:'4 PN',slide:2})">
          <div class="cdp-bds-thumb gs2" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
          <div class="cdp-bds-info">
            <div class="cdp-bds-title">Nhà phố Trần Phú</div>
            <div class="cdp-bds-meta">2,800 triệu · 120 m²</div>
          </div>
          <span class="cdp-bds-status" style="background:var(--danger-light);color:var(--danger);">Không ưng ✗</span>
        </div>
        <div class="cdp-bds-item">
          <div class="cdp-bds-thumb gs3" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4.97-5 9-8.58 9-12a9 9 0 0 0-18 0c0 3.42 4.03 7 9 12z"/><circle cx="12" cy="10" r="3"/></svg></div>
          <div class="cdp-bds-info">
            <div class="cdp-bds-title">Đất nền Lâm Viên</div>
            <div class="cdp-bds-meta">650 triệu · 180 m²</div>
          </div>
          <span class="cdp-bds-status" style="background:var(--primary-light);color:var(--primary);">Đã gửi</span>
        </div>
        <button style="width:100%;margin-top:8px;padding:10px;background:var(--primary-light);color:var(--primary);border:1px dashed var(--primary);border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;" onclick="openSendModal();closeSubpage('mycustomers')">＋ Gửi thêm BĐS</button>

        <div class="cdp-section-title" style="margin-top:14px;">Lịch sử hoạt động</div>
        <div class="cdp-timeline">
          <div class="cdp-tl-item">
            <div class="cdp-tl-dot" style="background:var(--primary);"></div>
            <div class="cdp-tl-text">Đặt lịch xem nhà — Đất Yersin lúc 09:00 ngày 16/03</div>
            <div class="cdp-tl-time">Hôm nay</div>
          </div>
          <div class="cdp-tl-item">
            <div class="cdp-tl-dot" style="background:var(--danger);"></div>
            <div class="cdp-tl-text">Khách không ưng BĐS Nhà phố Trần Phú — "Hẻm nhỏ, xa chợ"</div>
            <div class="cdp-tl-time">13/03</div>
          </div>
          <div class="cdp-tl-item">
            <div class="cdp-tl-dot" style="background:var(--success);"></div>
            <div class="cdp-tl-text">Khách ưng BĐS Đất Yersin · Đang cân nhắc giá</div>
            <div class="cdp-tl-time">11/03</div>
          </div>
          <div class="cdp-tl-item">
            <div class="cdp-tl-dot"></div>
            <div class="cdp-tl-text">Gửi 2 BĐS đầu tiên cho khách qua Telegram</div>
            <div class="cdp-tl-time">10/03</div>
          </div>
        </div>
      </div>
    </div>

    <!-- CUSTOMER 3 — Đang thương lượng -->
    <div class="cust-card">
      <div class="cust-header">
        <div class="cust-avatar" style="background:var(--purple);">TH</div>
        <div class="cust-info">
          <div class="cust-name">Chị Thu Hà</div>
          <div class="cust-meta">
            <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>0978.654.321</span>
            <span>· 1 ngày trước</span>
          </div>
        </div>
        <div class="cust-status-badge">
          <span class="badge badge-amber"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>Thương lượng</span>
        </div>
      </div>
      <div class="cust-body">
        <div class="cust-row">
          <span class="cust-row-label">Loại BĐS</span>
          <span class="cust-row-val">Biệt thự, Nhà phố</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Ngân sách</span>
          <span class="cust-row-val" style="color:var(--success);font-weight:600;">5 tỷ – 8 tỷ</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Trạng thái</span>
          <span class="cust-row-val" style="color:var(--warning);font-weight:600;">Đang ép giá BT Cầu Đất</span>
        </div>
        <div class="cust-tags">
          <span class="cust-tag" style="background:var(--warning-light);color:var(--warning);">Đang thương lượng</span>
          <span class="cust-tag">Biệt thự</span>
          <span class="cust-tag">View đẹp</span>
        </div>
      </div>
      <div class="lead-flow">
        <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Lead mới</div></div>
        <div class="lf-line done"></div>
        <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Đã liên hệ</div></div>
        <div class="lf-line done"></div>
        <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Tạo Deal</div></div>
        <div class="lf-line done"></div>
        <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Xem nhà</div></div>
        <div class="lf-line"></div>
        <div class="lf-step"><div class="lf-dot active"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div><div class="lf-label active">Thương lượng</div></div>
      </div>
      <div class="cust-footer">
        <div class="cust-date"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Xem nhà 14/03 · <span style="color:var(--warning);">Chờ phản hồi giá</span></div>
        <div class="cust-actions">
          <div class="cust-btn" onclick="showToast('Đang gọi...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
          <div class="cust-btn" onclick="showToast('Mở chat...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
          <div class="cust-btn primary" onclick="toggleCustDetail('cust3-detail')">▼ Chi tiết</div>
        </div>
      </div>
      <div class="cust-detail-panel" id="cust3-detail">
        <div class="cdp-section-title">BĐS đang thương lượng</div>
        <div class="cdp-bds-item" onclick="openDetail({title:'Biệt thự View Đồi Chè Cầu Đất',price:'8,500 triệu',type:'Biệt thự',area:'580 m²',addr:'Xuân Trường',room:'5 PN',slide:1})">
          <div class="cdp-bds-thumb gs2" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
          <div class="cdp-bds-info">
            <div class="cdp-bds-title">Biệt thự View Đồi Chè Cầu Đất</div>
            <div class="cdp-bds-meta">8,500 triệu · 580 m²</div>
          </div>
          <span class="cdp-bds-status" style="background:var(--warning-light);color:var(--warning);">Thương lượng</span>
        </div>
        <div class="cdp-note">
          <span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>Khách đề nghị 7,800 triệu. Chủ nhà đồng ý tối thiểu 8,000 triệu. Cần thuyết phục chênh lệch 200 triệu.
        </div>
        <div style="display:flex;gap:8px;margin-top:12px;">
          <button style="flex:1;padding:10px;background:var(--warning);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;" onclick="showToast('Cập nhật giá thương lượng')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Cập nhật</span></button>
          <button style="flex:1;padding:10px;background:var(--success);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;" onclick="showToast('✓ Đã tạo commission!')">✓ Chốt Deal!</button>
        </div>
      </div>
    </div>

    <!-- CUSTOMER 4 — Lead mới -->
    <div class="cust-card">
      <div class="cust-header">
        <div class="cust-avatar" style="background:var(--teal);">BT</div>
        <div class="cust-info">
          <div class="cust-name">Anh Bảo Trâm</div>
          <div class="cust-meta">
            <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>0966.111.222</span>
            <span style="color:var(--warning);font-weight:600;">● 6 giờ trước</span>
          </div>
        </div>
        <div class="cust-status-badge">
          <span class="badge badge-red"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="8" height="8" viewBox="0 0 10 10" fill="var(--danger)" stroke="none"><circle cx="5" cy="5" r="5"/></svg></span>Lead mới</span>
        </div>
      </div>
      <div class="cust-body">
        <div class="cust-row">
          <span class="cust-row-label">Loại BĐS</span>
          <span class="cust-row-val">Khách sạn mini, Nhà phố</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Ngân sách</span>
          <span class="cust-row-val" style="color:var(--success);font-weight:600;">3 tỷ – 5 tỷ</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Nguồn</span>
          <span class="cust-row-val">Zalo OA</span>
        </div>
        <div class="cust-tags">
          <span class="cust-tag">Khách sạn</span>
          <span class="cust-tag">3–5 tỷ</span>
        </div>
      </div>
      <div class="lead-flow">
        <div class="lf-step"><div class="lf-dot active">1</div><div class="lf-label active">Lead mới</div></div>
        <div class="lf-line"></div>
        <div class="lf-step"><div class="lf-dot">2</div><div class="lf-label">Đã liên hệ</div></div>
        <div class="lf-line"></div>
        <div class="lf-step"><div class="lf-dot">3</div><div class="lf-label">Tạo Deal</div></div>
        <div class="lf-line"></div>
        <div class="lf-step"><div class="lf-dot">4</div><div class="lf-label">Chăm sóc</div></div>
        <div class="lf-line"></div>
        <div class="lf-step"><div class="lf-dot">5</div><div class="lf-label">Chốt</div></div>
      </div>
      <div class="cust-footer">
        <div class="cust-date"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Nhận 15/03/2026</div>
        <div class="cust-actions">
          <div class="cust-btn" onclick="showToast('Đang gọi...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
          <div class="cust-btn" onclick="showToast('Mở chat...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
          <div class="cust-btn success" onclick="showToast('✓ Đã tạo Deal')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Deal</span></div>
        </div>
      </div>
    </div>

    <!-- CUSTOMER 5 — Đã chốt -->
    <div class="cust-card" style="border-color:var(--success);border-left:3px solid var(--success);">
      <div class="cust-header">
        <div class="cust-avatar" style="background:var(--success);">LH</div>
        <div class="cust-info">
          <div class="cust-name">Chị Lan Hương</div>
          <div class="cust-meta">
            <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>0944.888.999</span>
            <span>· 05/03/2026</span>
          </div>
        </div>
        <div class="cust-status-badge">
          <span class="badge badge-green"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Đã chốt</span></span>
        </div>
      </div>
      <div class="cust-body">
        <div class="cust-row">
          <span class="cust-row-label">BĐS đã mua</span>
          <span class="cust-row-val" style="color:var(--primary);font-weight:600;">Đất ở Phường 3, 200m²</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Giá trị</span>
          <span class="cust-row-val" style="color:var(--success);font-weight:700;">1,800 triệu</span>
        </div>
        <div class="cust-row">
          <span class="cust-row-label">Hoa hồng</span>
          <span class="cust-row-val" style="color:var(--success);font-weight:700;">54 triệu ✓</span>
        </div>
        <div class="cust-tags">
          <span class="cust-tag" style="background:var(--success-light);color:var(--success);"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Đã công chứng</span></span>
          <span class="cust-tag" style="background:var(--success-light);color:var(--success);">Đã nhận HH</span>
        </div>
      </div>
      <div class="cust-footer">
        <div class="cust-date"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Chốt 05/03/2026 · Công chứng 07/03</div>
        <div class="cust-actions">
          <div class="cust-btn" onclick="showToast('Mở chat khách cũ...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
          <div class="cust-btn" style="background:var(--primary-light);border-color:transparent;font-size:10px;width:auto;padding:0 8px;color:var(--primary);font-weight:600;" onclick="showToast('Mở form referral')"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg> Giới thiệu</span></div>
        </div>
      </div>
    </div>

    <div style="height:16px;"></div>
  </div><!-- end sp-scroll -->
</div><!-- end subpage-mycustomers -->


