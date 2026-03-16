    <div class="page" id="page-post">

      <!-- Guest view -->
      <div class="role-guest" style="padding:24px 16px;text-align:center;">
        <div style="font-size:56px;margin-bottom:16px;">🏡</div>
        <div style="font-size:18px;font-weight:700;color:var(--text-primary);margin-bottom:8px;">Trở thành eBroker</div>
        <div style="font-size:14px;color:var(--text-secondary);line-height:1.6;margin-bottom:24px;">Đăng tin BĐS, nhận khách hàng, kiếm hoa hồng ngay trên Đà Lạt BĐS</div>
        <div style="display:grid;gap:10px;margin-bottom:24px;">
          <div style="display:flex;gap:12px;align-items:center;text-align:left;padding:12px;background:var(--primary-light);border-radius:12px;">
            <span style="font-size:20px;">👁</span>
            <span style="font-size:13px;color:var(--primary-dark);">Xem địa chỉ & SĐT chủ nhà đầy đủ</span>
          </div>
          <div style="display:flex;gap:12px;align-items:center;text-align:left;padding:12px;background:var(--success-light);border-radius:12px;">
            <span style="font-size:20px;">📢</span>
            <span style="font-size:13px;color:var(--success);">Đăng tin BĐS miễn phí</span>
          </div>
          <div style="display:flex;gap:12px;align-items:center;text-align:left;padding:12px;background:var(--warning-light);border-radius:12px;">
            <span style="font-size:20px;">💰</span>
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
              <div class="type-option-icon">🏷️</div>
              Bán
            </div>
            <div class="type-option" onclick="selectType(this)">
              <div class="type-option-icon">🔑</div>
              Cho thuê
            </div>
          </div>
          <label class="form-label">Loại bất động sản</label>
          <div class="type-grid">
            <div class="type-option selected" onclick="selectType(this)">
              <div class="type-option-icon">🌱</div>
              Đất ở
            </div>
            <div class="type-option" onclick="selectType(this)">
              <div class="type-option-icon">🏠</div>
              Nhà phố
            </div>
            <div class="type-option" onclick="selectType(this)">
              <div class="type-option-icon">🏰</div>
              Biệt thự
            </div>
            <div class="type-option" onclick="selectType(this)">
              <div class="type-option-icon">🏢</div>
              Căn hộ
            </div>
            <div class="type-option" onclick="selectType(this)">
              <div class="type-option-icon">🏨</div>
              Khách sạn
            </div>
            <div class="type-option" onclick="selectType(this)">
              <div class="type-option-icon">🏭</div>
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
              <div class="action-opt-icon" style="background:var(--primary-light);">🎯</div>
              <div class="action-opt-body">
                <div class="action-opt-title">Thêm Lead / Khách mới</div>
                <div class="action-opt-sub">Ghi nhận khách hàng tiềm năng</div>
              </div>
              <span class="action-opt-arrow">›</span>
            </div>
            <div class="action-option" onclick="openSheet()">
              <div class="action-opt-icon" style="background:var(--purple-light);">🤝</div>
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

