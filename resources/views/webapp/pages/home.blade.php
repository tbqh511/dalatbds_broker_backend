    <div class="page active" id="page-home">

      <!-- Booking hôm nay — chỉ Sale+ -->
      <div class="booking-today role-sale role-bds_admin role-sale_admin role-admin" style="margin-top:14px;">
        <div class="booking-today-title">📅 Lịch hẹn hôm nay</div>
        <div class="booking-item">
          <div class="booking-time">09:00</div>
          <div class="booking-info">
            <div class="booking-name">Anh Minh Tuấn</div>
            <div class="booking-addr">Đường 3/4, P.Lâm Viên</div>
          </div>
          <span class="booking-status">Sắp tới</span>
        </div>
        <div class="booking-item">
          <div class="booking-time">14:30</div>
          <div class="booking-info">
            <div class="booking-name">Chị Thu Hà</div>
            <div class="booking-addr">Đường Yersin, P.Cam Ly</div>
          </div>
          <span class="booking-status" style="background:#d1fae5;color:#065f46;">Đã xem</span>
        </div>
      </div>

      <!-- Stats — Broker+ -->
      <div class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin" style="margin-top:14px;">
        <div class="stats-grid">
          <div class="stat-card" style="--icon-bg:#e8effe;--icon-color:#3270FC">
            <div class="stat-icon">🏡</div>
            <div class="stat-label">Tin đang hiển thị</div>
            <div class="stat-value">45</div>
            <div class="stat-delta">+3 tuần này</div>
          </div>
          <div class="stat-card" style="--icon-bg:#fef3c7;--icon-color:#d97706">
            <div class="stat-icon">👁️</div>
            <div class="stat-label">Lượt xem</div>
            <div class="stat-value">8,849</div>
            <div class="stat-delta">+0 tuần này</div>
          </div>
          <div class="stat-card role-sale role-bds_admin role-sale_admin role-admin" style="--icon-bg:#d1fae5;--icon-color:#059669">
            <div class="stat-icon">🎯</div>
            <div class="stat-label">Lead đang xử lý</div>
            <div class="stat-value">7</div>
            <div class="stat-delta">2 chưa contact</div>
          </div>
          <div class="stat-card role-sale role-bds_admin role-sale_admin role-admin" style="--icon-bg:#ede9fe;--icon-color:#7c3aed">
            <div class="stat-icon">🤝</div>
            <div class="stat-label">Deal đang chăm</div>
            <div class="stat-value">3</div>
            <div class="stat-delta">1 đang thương lượng</div>
          </div>
          <div class="stat-card role-broker" style="--icon-bg:#fce7f3;--icon-color:#db2777">
            <div class="stat-icon">💬</div>
            <div class="stat-label">Đánh giá</div>
            <div class="stat-value">11</div>
            <div class="stat-delta">+0 tuần này</div>
          </div>
          <div class="stat-card role-broker" style="--icon-bg:#ccfbf1;--icon-color:#0d9488">
            <div class="stat-icon">❤️</div>
            <div class="stat-label">Lượt quan tâm</div>
            <div class="stat-value">9</div>
            <div class="stat-delta">+0 tuần này</div>
          </div>
        </div>
      </div>

      <!-- Admin: Queue duyệt -->
      <div class="role-admin role-bds_admin" style="margin:14px 16px 0;">
        <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
          <span style="font-size:20px;">⚠️</span>
          <div style="flex:1;">
            <div style="font-size:13px;font-weight:600;color:#92400e;">8 BĐS chờ duyệt</div>
            <div style="font-size:11px;color:#b45309;">Cần xem xét và phê duyệt</div>
          </div>
          <button style="padding:6px 12px;background:#d97706;color:#fff;border-radius:8px;font-size:12px;font-weight:600;">Duyệt</button>
        </div>
      </div>

      <!-- Market prices -->
      <div class="page-section-title">Thị trường Đà Lạt</div>
      <div class="market-strip">
        <div class="market-title">📈 GIÁ TRUNG BÌNH / M² — THÁNG 3/2026</div>
        <div class="market-prices">
          <div class="market-price-item">
            <div class="market-price-area">P.Cam Ly</div>
            <div class="market-price-val">28.5tr</div>
            <div class="market-price-trend up">↑ 2.3%</div>
          </div>
          <div class="market-price-item">
            <div class="market-price-area">P.Lâm Viên</div>
            <div class="market-price-val">42.1tr</div>
            <div class="market-price-trend up">↑ 1.8%</div>
          </div>
          <div class="market-price-item">
            <div class="market-price-area">Đường 3/4</div>
            <div class="market-price-val">35.7tr</div>
            <div class="market-price-trend dn">↓ 0.5%</div>
          </div>
        </div>
      </div>

      <!-- Filter chips -->
      <div class="filter-bar">
        <div class="chip active" onclick="toggleChip(this)">Tất cả</div>
        <div class="chip" onclick="toggleChip(this)">Đất ở</div>
        <div class="chip" onclick="toggleChip(this)">Nhà phố</div>
        <div class="chip" onclick="toggleChip(this)">Biệt thự</div>
        <div class="chip" onclick="toggleChip(this)">Căn hộ</div>
        <div class="chip" onclick="toggleChip(this)">Khách sạn</div>
        <div class="chip" onclick="toggleChip(this)">Mua</div>
        <div class="chip" onclick="toggleChip(this)">Thuê</div>
      </div>

      <!-- Property listings -->
      <div class="page-section-title">Tin mới nhất</div>

      <div class="prop-card">
        <div class="prop-img">
          <div class="img-prop1 prop-img-inner"><div class="img-center">🏠</div></div>
          <div class="prop-img-gradient"></div>
          <div class="prop-img-tags">
            <span class="badge badge-blue">Đất ở</span>
            <span class="badge badge-green">Đã duyệt</span>
          </div>
          <div class="prop-img-price">1,000 triệu</div>
          <div class="prop-img-status"><span class="badge badge-green">Đang hiển thị</span></div>
          <div class="prop-actions">
            <div class="prop-action-btn">❤️</div>
            <div class="prop-action-btn">↗️</div>
          </div>
        </div>
        <div class="prop-body">
          <div class="prop-title">Bán Đất ở phân quyền, Đường Yersin, Phường Cam Ly</div>
          <div class="prop-location">📍 <span class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">Đường Yersin, Phường Cam Ly</span><span class="role-guest">Phường Cam Ly, Đà Lạt</span></div>
          <div class="prop-meta">
            <div class="prop-meta-item">📐 <span>250 m²</span></div>
            <div class="prop-meta-item">⚖️ <span>Sổ đỏ</span></div>
            <div class="prop-meta-item role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">📞 <span style="color:var(--primary)">0912.xxx.xxx</span></div>
          </div>
        </div>
        <div class="prop-footer">
          <div class="prop-views">👁 3 lượt xem</div>
          <div class="prop-quick-actions">
            <div class="prop-quick-btn role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">✏️</div>
            <div class="prop-quick-btn role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">👁</div>
            <div class="prop-quick-btn role-sale role-bds_admin role-sale_admin role-admin" style="background:var(--primary-light);border-color:transparent;">🤝</div>
          </div>
        </div>
      </div>

      <div class="prop-card">
        <div class="prop-img">
          <div class="img-prop3 prop-img-inner"><div class="img-center">🏢</div></div>
          <div class="prop-img-gradient"></div>
          <div class="prop-img-tags">
            <span class="badge badge-amber">Nhà phố</span>
          </div>
          <div class="prop-img-price">2,800 triệu</div>
          <div class="prop-actions">
            <div class="prop-action-btn">❤️</div>
          </div>
        </div>
        <div class="prop-body">
          <div class="prop-title">Nhà mặt tiền đường Trần Phú, gần chợ Đà Lạt</div>
          <div class="prop-location">📍 <span class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">Đường Trần Phú, P.1</span><span class="role-guest">Phường 1, Đà Lạt</span></div>
          <div class="prop-meta">
            <div class="prop-meta-item">🏠 <span>4 PN</span></div>
            <div class="prop-meta-item">📐 <span>120 m²</span></div>
            <div class="prop-meta-item">⚖️ <span>Sổ hồng</span></div>
          </div>
        </div>
        <div class="prop-footer">
          <div class="prop-views">👁 24 lượt xem</div>
          <div class="prop-quick-actions">
            <div class="prop-quick-btn role-sale role-bds_admin role-sale_admin role-admin" style="background:var(--primary-light);border-color:transparent;">🤝</div>
          </div>
        </div>
      </div>

      <div class="prop-card">
        <div class="prop-img">
          <div class="img-prop2 prop-img-inner"><div class="img-center">🏡</div></div>
          <div class="prop-img-gradient"></div>
          <div class="prop-img-tags">
            <span class="badge badge-purple">Biệt thự</span>
          </div>
          <div class="prop-img-price">8,500 triệu</div>
        </div>
        <div class="prop-body">
          <div class="prop-title">Biệt thự view đồi chè Cầu Đất, Đà Lạt</div>
          <div class="prop-location">📍 <span class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">Xã Xuân Trường, huyện Đà Lạt</span><span class="role-guest">Ngoại ô Đà Lạt</span></div>
          <div class="prop-meta">
            <div class="prop-meta-item">🏠 <span>5 PN</span></div>
            <div class="prop-meta-item">📐 <span>580 m²</span></div>
            <div class="prop-meta-item">🌲 <span>Sân vườn</span></div>
          </div>
        </div>
        <div class="prop-footer">
          <div class="prop-views">👁 87 lượt xem</div>
          <div class="prop-quick-actions">
            <div class="prop-quick-btn role-sale role-bds_admin role-sale_admin role-admin" style="background:var(--primary-light);border-color:transparent;">🤝</div>
          </div>
        </div>
      </div>

    </div><!-- end page-home -->
