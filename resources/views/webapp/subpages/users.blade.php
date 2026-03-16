  <!-- ========== SUBPAGE: QUẢN LÝ NGƯỜI DÙNG ========== -->
  <div class="subpage" id="subpage-users">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('users')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Quản lý người dùng</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="showToast('Tìm kiếm người dùng')"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg></button>
      </div>
    </div>

    <div class="admin-hero blue-grad">
      <div class="ah-label">NGƯỜI DÙNG HỆ THỐNG</div>
      <div class="ah-main">48 tài khoản active</div>
      <div class="ah-grid">
        <div class="ah-stat"><div class="ah-stat-val" style="color:#fde68a;">3</div><div class="ah-stat-lbl">Chờ duyệt</div></div>
        <div class="ah-stat"><div class="ah-stat-val">38</div><div class="ah-stat-lbl">Broker</div></div>
        <div class="ah-stat"><div class="ah-stat-val">8</div><div class="ah-stat-lbl">Sale</div></div>
        <div class="ah-stat"><div class="ah-stat-val">2</div><div class="ah-stat-lbl">Bị khoá</div></div>
      </div>
    </div>

    <div class="sp-searchbar" style="padding:10px 14px;">
      <div class="sp-search-input">
        <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg></span>
        <input type="text" placeholder="Tên, SĐT, email...">
      </div>
      <button class="sp-filter-btn"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M16.24 7.76a6 6 0 0 1 0 8.49M4.93 4.93a10 10 0 0 0 0 14.14M7.76 7.76a6 6 0 0 0 0 8.49"/></svg> Lọc</span></button>
    </div>

    <div class="sp-tabs">
      <button class="sp-tab active" onclick="spTabSwitch(this)">Chờ duyệt (3)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Broker (38)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Sale (8)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Bị khoá (2)</button>
    </div>

    <div class="sp-scroll" style="padding-bottom:16px;">

      <div class="user-divider">
        <span>Chờ phê duyệt tài khoản Broker</span>
        <span class="badge badge-red">3 mới</span>
      </div>

      <!-- Pending Broker 1 -->
      <div class="user-card" id="uc1">
        <div class="uc-head">
          <div class="uc-avatar" style="background:#ef4444;">
            NT
            <span class="uc-badge" style="background:var(--warning-light);color:var(--warning);">Mới</span>
          </div>
          <div class="uc-info">
            <div class="uc-name">Nguyễn Thị Mai</div>
            <div class="uc-meta">
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>0912.111.222</span>
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></span>mai.nt@gmail.com</span>
              <span style="color:var(--warning);font-weight:600;">Đăng ký 2 giờ trước</span>
            </div>
          </div>
          <span class="badge badge-amber">⏳ Chờ duyệt</span>
        </div>
        <div class="uc-body">
          <div class="uc-row"><span class="uc-label">Xin đăng ký</span><span class="uc-value">eBroker</span></div>
          <div class="uc-row"><span class="uc-label">Kinh nghiệm</span><span class="uc-value">3 năm BĐS</span></div>
          <div class="uc-row"><span class="uc-label">Khu vực</span><span class="uc-value">P.Cam Ly, P.Lâm Viên</span></div>
          <div class="uc-row"><span class="uc-label">Chứng chỉ</span><span class="uc-value" style="color:var(--success);">Có (đã upload)</span></div>
        </div>
        <div style="padding:8px 13px;background:var(--primary-light);border-top:1px solid #bfdbfe;font-size:11px;color:var(--primary-dark);">
          Đã upload: CMND, Chứng chỉ môi giới (2026), Ảnh đại diện
        </div>
        <div class="uc-actions">
          <button class="uc-btn view" onclick="showToast('Xem hồ sơ đầy đủ...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Xem hồ sơ</span></button>
          <button class="uc-btn reject" onclick="showToast('Đã từ chối tài khoản');document.getElementById('uc1').style.display='none'">✕ Từ chối</button>
          <button class="uc-btn approve" onclick="showToast('✓ Đã duyệt tài khoản Nguyễn Thị Mai!');document.getElementById('uc1').style.display='none'">✓ Duyệt</button>
        </div>
      </div>

      <!-- Pending Broker 2 -->
      <div class="user-card" id="uc2">
        <div class="uc-head">
          <div class="uc-avatar" style="background:var(--teal);">
            PV
            <span class="uc-badge" style="background:var(--warning-light);color:var(--warning);">Mới</span>
          </div>
          <div class="uc-info">
            <div class="uc-name">Phạm Văn Bình</div>
            <div class="uc-meta"><span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>0933.444.555</span><span style="color:var(--warning);font-weight:600;">6 giờ trước</span></div>
          </div>
          <span class="badge badge-amber">⏳ Chờ duyệt</span>
        </div>
        <div class="uc-body">
          <div class="uc-row"><span class="uc-label">Xin đăng ký</span><span class="uc-value">eBroker</span></div>
          <div class="uc-row"><span class="uc-label">Kinh nghiệm</span><span class="uc-value">1 năm</span></div>
          <div class="uc-row"><span class="uc-label">Khu vực</span><span class="uc-value">Trung tâm TP</span></div>
          <div class="uc-row"><span class="uc-label">Chứng chỉ</span><span class="uc-value" style="color:var(--warning);">Đang làm thủ tục</span></div>
        </div>
        <div style="padding:8px 13px;background:var(--warning-light);border-top:1px solid #fde68a;font-size:11px;color:#92400e;display:flex;align-items:center;gap:5px;">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          Chứng chỉ môi giới đang trong quá trình cấp. Có thể duyệt tạm thời.
        </div>
        <div class="uc-actions">
          <button class="uc-btn view" onclick="showToast('Xem hồ sơ...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Hồ sơ</span></button>
          <button class="uc-btn reject" onclick="showToast('Từ chối tài khoản');document.getElementById('uc2').style.display='none'">✕ Từ chối</button>
          <button class="uc-btn warn" onclick="showToast('Duyệt tạm thời, chờ chứng chỉ');document.getElementById('uc2').style.display='none'">⏳ Duyệt tạm</button>
        </div>
      </div>

      <!-- Pending Broker 3 -->
      <div class="user-card" id="uc3">
        <div class="uc-head">
          <div class="uc-avatar" style="background:var(--purple);">
            LH
            <span class="uc-badge" style="background:var(--warning-light);color:var(--warning);">Mới</span>
          </div>
          <div class="uc-info">
            <div class="uc-name">Lê Hoàng Duy</div>
            <div class="uc-meta"><span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>0977.666.777</span><span>Hôm qua</span></div>
          </div>
          <span class="badge badge-amber">⏳ Chờ duyệt</span>
        </div>
        <div class="uc-body">
          <div class="uc-row"><span class="uc-label">Xin đăng ký</span><span class="uc-value">eBroker</span></div>
          <div class="uc-row"><span class="uc-label">Kinh nghiệm</span><span class="uc-value">5+ năm</span></div>
          <div class="uc-row"><span class="uc-label">Khu vực</span><span class="uc-value">Toàn TP.Đà Lạt</span></div>
          <div class="uc-row"><span class="uc-label">Chứng chỉ</span><span class="uc-value" style="color:var(--success);">Đầy đủ</span></div>
        </div>
        <div class="uc-actions">
          <button class="uc-btn view" onclick="showToast('Xem hồ sơ...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Hồ sơ</span></button>
          <button class="uc-btn reject" onclick="showToast('Đã từ chối');document.getElementById('uc3').style.display='none'">✕ Từ chối</button>
          <button class="uc-btn approve" onclick="showToast('✓ Đã duyệt Lê Hoàng Duy!');document.getElementById('uc3').style.display='none'">✓ Duyệt</button>
        </div>
      </div>

      <!-- Active Brokers -->
      <div class="user-divider" style="margin-top:12px;">
        <span>Broker đang active</span>
        <span class="badge badge-green">38 users</span>
      </div>

      <!-- Active user 1 -->
      <div class="user-card">
        <div class="uc-head">
          <div class="uc-avatar" style="background:var(--primary);">HT</div>
          <div class="uc-info">
            <div class="uc-name">Huy Thái</div>
            <div class="uc-meta"><span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>0901.234.xxx</span><span style="color:var(--success);">● Online</span></div>
          </div>
          <span class="badge badge-green">✓ Active</span>
        </div>
        <div class="uc-stats">
          <div class="uc-stat"><div class="uc-stat-val">45</div><div class="uc-stat-lbl">BĐS đăng</div></div>
          <div class="uc-stat"><div class="uc-stat-val">12</div><div class="uc-stat-lbl">Giao dịch</div></div>
          <div class="uc-stat"><div class="uc-stat-val" style="color:var(--success);">4.8★</div><div class="uc-stat-lbl">Đánh giá</div></div>
        </div>
        <div class="uc-actions">
          <button class="uc-btn view" onclick="showToast('Xem hồ sơ Huy Thái...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Hồ sơ</span></button>
          <button class="uc-btn warn" onclick="showToast('Gửi cảnh báo...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Cảnh báo</span></button>
          <button class="uc-btn" onclick="showToast('Đổi role...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Đổi role</span></button>
        </div>
      </div>

      <!-- Active user 2 — Bị khoá -->
      <div class="user-card" style="opacity:.75;border-color:var(--danger);">
        <div class="uc-head">
          <div class="uc-avatar" style="background:#6b7280;">XA</div>
          <div class="uc-info">
            <div class="uc-name">Xuân An</div>
            <div class="uc-meta"><span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>0955.333.xxx</span><span style="color:var(--danger);font-weight:600;display:inline-flex;align-items:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Bị khoá</span></div>
          </div>
          <span class="badge badge-red"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Khoá</span></span>
        </div>
        <div style="padding:8px 13px;background:var(--danger-light);font-size:11px;color:var(--danger);border-bottom:1px solid #fca5a5;">
          Lý do: Đăng tin sai thông tin giá 3 lần. Khoá từ 10/03/2026.
        </div>
        <div class="uc-actions">
          <button class="uc-btn view" onclick="showToast('Xem lịch sử vi phạm...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Lịch sử</span></button>
          <button class="uc-btn approve" onclick="showToast('✓ Đã mở khoá tài khoản')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg> Mở khoá</span></button>
          <button class="uc-btn danger" onclick="showToast('Đã xoá tài khoản vĩnh viễn')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg> Xoá vĩnh viễn</span></button>
        </div>
      </div>

      <div style="height:16px;"></div>
    </div>
  </div><!-- end subpage-users -->


