  <!-- ========== SUBPAGE: QUẢN LÝ NGƯỜI DÙNG ========== -->
  <div class="subpage" id="subpage-users">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('users')">←</button>
      <div class="sp-title">👤 Quản lý người dùng</div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="showToast('Tìm kiếm người dùng')">🔍</button>
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
        <span style="font-size:15px;color:var(--text-tertiary);">🔍</span>
        <input type="text" placeholder="Tên, SĐT, email...">
      </div>
      <button class="sp-filter-btn">⚙️ Lọc</button>
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
              <span>📞 0912.111.222</span>
              <span>📧 mai.nt@gmail.com</span>
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
          📎 Đã upload: CMND, Chứng chỉ môi giới (2026), Ảnh đại diện
        </div>
        <div class="uc-actions">
          <button class="uc-btn view" onclick="showToast('Xem hồ sơ đầy đủ...')">📋 Xem hồ sơ</button>
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
            <div class="uc-meta"><span>📞 0933.444.555</span><span style="color:var(--warning);font-weight:600;">6 giờ trước</span></div>
          </div>
          <span class="badge badge-amber">⏳ Chờ duyệt</span>
        </div>
        <div class="uc-body">
          <div class="uc-row"><span class="uc-label">Xin đăng ký</span><span class="uc-value">eBroker</span></div>
          <div class="uc-row"><span class="uc-label">Kinh nghiệm</span><span class="uc-value">1 năm</span></div>
          <div class="uc-row"><span class="uc-label">Khu vực</span><span class="uc-value">Trung tâm TP</span></div>
          <div class="uc-row"><span class="uc-label">Chứng chỉ</span><span class="uc-value" style="color:var(--warning);">Đang làm thủ tục</span></div>
        </div>
        <div style="padding:8px 13px;background:var(--warning-light);border-top:1px solid #fde68a;font-size:11px;color:#92400e;">
          ⚠ Chứng chỉ môi giới đang trong quá trình cấp. Có thể duyệt tạm thời.
        </div>
        <div class="uc-actions">
          <button class="uc-btn view" onclick="showToast('Xem hồ sơ...')">📋 Hồ sơ</button>
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
            <div class="uc-meta"><span>📞 0977.666.777</span><span>Hôm qua</span></div>
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
          <button class="uc-btn view" onclick="showToast('Xem hồ sơ...')">📋 Hồ sơ</button>
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
            <div class="uc-meta"><span>📞 0901.234.xxx</span><span style="color:var(--success);">● Online</span></div>
          </div>
          <span class="badge badge-green">✓ Active</span>
        </div>
        <div class="uc-stats">
          <div class="uc-stat"><div class="uc-stat-val">45</div><div class="uc-stat-lbl">BĐS đăng</div></div>
          <div class="uc-stat"><div class="uc-stat-val">12</div><div class="uc-stat-lbl">Giao dịch</div></div>
          <div class="uc-stat"><div class="uc-stat-val" style="color:var(--success);">4.8★</div><div class="uc-stat-lbl">Đánh giá</div></div>
        </div>
        <div class="uc-actions">
          <button class="uc-btn view" onclick="showToast('Xem hồ sơ Huy Thái...')">📋 Hồ sơ</button>
          <button class="uc-btn warn" onclick="showToast('Gửi cảnh báo...')">⚠ Cảnh báo</button>
          <button class="uc-btn" onclick="showToast('Đổi role...')">🔄 Đổi role</button>
        </div>
      </div>

      <!-- Active user 2 — Bị khoá -->
      <div class="user-card" style="opacity:.75;border-color:var(--danger);">
        <div class="uc-head">
          <div class="uc-avatar" style="background:#6b7280;">XA</div>
          <div class="uc-info">
            <div class="uc-name">Xuân An</div>
            <div class="uc-meta"><span>📞 0955.333.xxx</span><span style="color:var(--danger);font-weight:600;">🔒 Bị khoá</span></div>
          </div>
          <span class="badge badge-red">🔒 Khoá</span>
        </div>
        <div style="padding:8px 13px;background:var(--danger-light);font-size:11px;color:var(--danger);border-bottom:1px solid #fca5a5;">
          Lý do: Đăng tin sai thông tin giá 3 lần. Khoá từ 10/03/2026.
        </div>
        <div class="uc-actions">
          <button class="uc-btn view" onclick="showToast('Xem lịch sử vi phạm...')">📋 Lịch sử</button>
          <button class="uc-btn approve" onclick="showToast('✓ Đã mở khoá tài khoản')">🔓 Mở khoá</button>
          <button class="uc-btn danger" onclick="showToast('Đã xoá tài khoản vĩnh viễn')">🗑 Xoá vĩnh viễn</button>
        </div>
      </div>

      <div style="height:16px;"></div>
    </div>
  </div><!-- end subpage-users -->


