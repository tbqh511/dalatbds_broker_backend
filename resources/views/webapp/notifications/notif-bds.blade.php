    <div class="notif-detail-page" id="nd-bds">
      <div class="nd-header">
        <button class="nd-back" onclick="closeNotifDetail('nd-bds')">←</button>
        <div class="nd-title">BĐS đã được duyệt</div>
      </div>
      <div class="nd-scroll">
        <div class="nd-hero bds-type">
          <div class="nd-hero-icon">✅</div>
          <div class="nd-hero-title">BĐS đã được duyệt!</div>
          <div class="nd-hero-sub">Tin của bạn đang được hiển thị công khai<br>và sẵn sàng để nhận khách hàng quan tâm.</div>
          <div class="nd-hero-time">📅 Duyệt lúc 09:15 · 14/03/2026</div>
        </div>

        <div class="nd-urgent green">
          <span class="nd-urgent-icon">🎉</span>
          <div class="nd-urgent-text">Tin của bạn đang live! Chia sẻ link để tăng lượt xem và nhanh chóng tìm người mua.</div>
        </div>

        <div class="nd-card">
          <div class="nd-card-title">🏡 Thông tin BĐS</div>
          <div class="nd-info-row">
            <span class="nd-info-icon">🏷</span>
            <span class="nd-info-label">Tiêu đề</span>
            <span class="nd-info-value" style="font-size:12px;">Bán Đất ở Đường Yersin, P.Cam Ly</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">💰</span>
            <span class="nd-info-label">Giá niêm yết</span>
            <span class="nd-info-value money">1,000 triệu</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📐</span>
            <span class="nd-info-label">Diện tích</span>
            <span class="nd-info-value">250 m²</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">⚖️</span>
            <span class="nd-info-label">Pháp lý</span>
            <span class="nd-info-value" style="color:var(--success);">✓ Sổ đỏ</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📊</span>
            <span class="nd-info-label">Trạng thái</span>
            <span class="nd-info-value" style="color:var(--success);">● Đang hiển thị</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">👁</span>
            <span class="nd-info-label">Lượt xem</span>
            <span class="nd-info-value">3 lượt</span>
          </div>
        </div>

        <!-- Admin comment -->
        <div class="nd-card">
          <div class="nd-card-title">💬 Nhận xét từ Admin</div>
          <div style="padding:12px 14px;">
            <div style="font-size:13px;color:var(--text-secondary);line-height:1.6;font-style:italic;">
              "Hồ sơ pháp lý đầy đủ, ảnh thực tế chất lượng tốt. Thông tin giá và vị trí chính xác. Đã duyệt và đăng lên hệ thống."
            </div>
            <div style="font-size:11px;color:var(--text-tertiary);margin-top:6px;">— Admin Đà Lạt BĐS · 09:15 · 14/03/2026</div>
          </div>
        </div>

        <!-- Performance tips -->
        <div class="nd-card">
          <div class="nd-card-title">💡 Mẹo tăng lượt xem</div>
          <div style="padding:10px 14px 12px;display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:12px;color:var(--text-secondary);">
              <span>📱</span><span>Chia sẻ link tin lên Zalo, Facebook để tăng traffic</span>
            </div>
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:12px;color:var(--text-secondary);">
              <span>📸</span><span>Thêm ảnh 360° hoặc video thực tế tăng độ tin cậy</span>
            </div>
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:12px;color:var(--text-secondary);">
              <span>🏷</span><span>Cập nhật giá theo thị trường để không bị lỗi thời</span>
            </div>
          </div>
        </div>
        <div style="height:16px;"></div>
      </div>
      <div class="nd-action-bar">
        <button class="nd-action-btn nd-btn-outline" onclick="showToast('Sao chép link tin...')">🔗 Copy link</button>
        <button class="nd-action-btn nd-btn-primary" onclick="openDetail({title:'Đất Đường Yersin',price:'1,000 triệu',type:'Đất ở',area:'250 m²',addr:'P.Cam Ly',room:'—',slide:0});closeNotifDetail('nd-bds')">👁 Xem tin live</button>
      </div>
    </div><!-- nd-bds -->


    <!-- ND: COMMISSION DETAIL -->
