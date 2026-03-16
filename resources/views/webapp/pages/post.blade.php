  <div class="page" id="page-post">

    <!-- Guest view -->
    <div class="role-guest" style="padding:24px 16px;text-align:center;">
      <div style="font-size:56px;margin-bottom:16px;display:flex;align-items:center;justify-content:center;"><svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
      <div style="font-size:18px;font-weight:700;color:var(--text-primary);margin-bottom:8px;">Trở thành eBroker</div>
      <div style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin-bottom:24px;">Đăng tin BĐS, nhận khách hàng, kiếm hoa hồng ngay trên Đà Lạt BĐS</div>
      <div style="display:grid;gap:10px;margin-bottom:24px;">
        <div style="display:flex;gap:12px;align-items:center;text-align:left;padding:12px;background:var(--primary-light);border-radius:12px;">
          <span style="display:inline-flex;flex-shrink:0;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary-dark)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
          <span style="font-size:13px;color:var(--primary-dark);">Xem địa chỉ & SĐT chủ nhà đầy đủ</span>
        </div>
        <div style="display:flex;gap:12px;align-items:center;text-align:left;padding:12px;background:var(--success-light);border-radius:12px;">
          <span style="display:inline-flex;flex-shrink:0;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-2.99 10.45-11 14C2.99 14.45 2 4 2 4l10-2 10 2z"/></svg></span>
          <span style="font-size:13px;color:var(--success);">Đăng tin BĐS miễn phí</span>
        </div>
        <div style="display:flex;gap:12px;align-items:center;text-align:left;padding:12px;background:var(--warning-light);border-radius:12px;">
          <span style="display:inline-flex;flex-shrink:0;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
          <span style="font-size:13px;color:var(--warning);">Nhận hoa hồng khi chốt thành công</span>
        </div>
      </div>
      <button class="btn-primary">Đăng ký làm Broker</button>
    </div>

    <!-- Broker+ view -->
    <div class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">
      <!-- Step indicator -->
      <div class="form-progress">
        <div class="progress-step done"></div>
        <div class="progress-step active"></div>
        <div class="progress-step"></div>
        <div class="progress-step"></div>
        <div class="progress-step"></div>
        <div class="progress-step"></div>
      </div>
      <div class="form-step-label">Bước 2 / 6</div>
      <div class="form-step-title">Loại bất động sản</div>

      <div class="form-group">
        <label class="form-label">Loại giao dịch</label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;">
          <div class="type-option selected" onclick="selectType(this)">
            <div class="type-option-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></div>
            Bán
          </div>
          <div class="type-option" onclick="selectType(this)">
            <div class="type-option-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg></div>
            Cho thuê
          </div>
        </div>
        <label class="form-label">Loại bất động sản</label>
        <div class="type-grid">
          <div class="type-option selected" onclick="selectType(this)">
            <div class="type-option-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4.97-5 9-8.58 9-12a9 9 0 0 0-18 0c0 3.42 4.03 7 9 12z"/><circle cx="12" cy="10" r="3"/></svg></div>
            Đất ở
          </div>
          <div class="type-option" onclick="selectType(this)">
            <div class="type-option-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
            Nhà phố
          </div>
          <div class="type-option" onclick="selectType(this)">
            <div class="type-option-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M3 7l9-4 9 4M4 7v14M20 7v14M9 21v-4a3 3 0 0 1 6 0v4"/></svg></div>
            Biệt thự
          </div>
          <div class="type-option" onclick="selectType(this)">
            <div class="type-option-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg></div>
            Căn hộ
          </div>
          <div class="type-option" onclick="selectType(this)">
            <div class="type-option-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M4 21V9l8-6 8 6v12M9 21v-6h6v6"/><path d="M10 3h4v3l-2-1-2 1V3z"/></svg></div>
            Khách sạn
          </div>
          <div class="type-option" onclick="selectType(this)">
            <div class="type-option-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
            Khác
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Tiêu đề tin</label>
        <input class="form-input" type="text" placeholder="VD: Bán đất ở Đường Yersin, Phường Cam Ly...">
      </div>

      <div class="form-group">
        <label class="form-label">Giá (triệu đồng)</label>
        <input class="form-input" type="number" placeholder="VD: 1500">
      </div>

      <!-- FAB action sheet cho Sale -->
      <div class="role-sale role-bds_admin role-sale_admin role-admin">
        <div style="padding:0 16px 8px;">
          <div style="font-size:12px;font-weight:600;color:var(--text-secondary);margin-bottom:10px;text-transform:uppercase;letter-spacing:0.04em;">Hoặc tạo nhanh</div>
          <div class="action-option" onclick="openSheet()">
            <div class="action-opt-icon" style="background:var(--primary-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg></div>
            <div class="action-opt-body">
              <div class="action-opt-title">Thêm Lead / Khách mới</div>
              <div class="action-opt-sub">Ghi nhận khách hàng tiềm năng</div>
            </div>
            <span class="action-opt-arrow">›</span>
          </div>
          <div class="action-option" onclick="openSheet()">
            <div class="action-opt-icon" style="background:var(--purple-light);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
            <div class="action-opt-body">
              <div class="action-opt-title">Tạo Deal mới</div>
              <div class="action-opt-sub">Từ Lead đã xác nhận</div>
            </div>
            <span class="action-opt-arrow">›</span>
          </div>
        </div>
      </div>

      <div class="form-bottom">
        <button class="btn-outline">← Quay lại</button>
        <button class="btn-primary">Tiếp theo →</button>
      </div>
    </div>

  </div><!-- end page-post -->

