  <div id="page-detail">

    <!-- sticky header -->
    <div class="detail-sticky-header" id="detailStickyHeader">
      <button class="dh-btn" onclick="closeDetail()"><span>←</span></button>
      <div class="dh-title" id="detailHeaderTitle">Chi tiết BĐS</div>
      <div class="dh-actions">
        <button class="dh-btn" onclick="toggleBookmark(this)" id="bookmarkBtn"><span>🤍</span></button>
        <button class="dh-btn" onclick="shareDetail()"><span>↗️</span></button>
      </div>
    </div>

    <!-- gallery -->
    <div class="detail-gallery">
      <div class="gallery-slides" id="gallerySlides">
        <div class="gallery-slide gs1">🏡</div>
        <div class="gallery-slide gs2">🌿</div>
        <div class="gallery-slide gs3">🏠</div>
        <div class="gallery-slide gs4">🌄</div>
      </div>
      <div class="gallery-price-badge">
        <div class="price-big" id="detailPrice">1,000 triệu</div>
        <div class="price-unit">≈ 4 tr/m² · Có thương lượng</div>
      </div>
      <div class="gallery-bottom">
        <div class="gallery-counter"><span id="galleryIdx">1</span> / <span id="galleryTotal">4</span> ảnh</div>
        <div class="gallery-dots" id="galleryDots">
          <div class="gdot active"></div>
          <div class="gdot"></div>
          <div class="gdot"></div>
          <div class="gdot"></div>
        </div>
      </div>
    </div>

    <!-- scrollable content -->
    <div class="detail-scroll" id="detailScroll">

      <!-- INFO CARD -->
      <div class="detail-info-card">
        <div class="detail-badges">
          <span class="badge badge-blue" id="detailType">Đất ở</span>
          <span class="badge badge-green">✓ Đã xác minh</span>
          <span class="badge badge-amber">Còn hàng</span>
        </div>
        <div class="detail-title" id="detailTitle">
          Bán Đất ở phân quyền, Đường Yersin, Phường Cam Ly, Đà Lạt
        </div>
        <div class="detail-addr">
          <span class="detail-addr-icon">📍</span>
          <span id="detailAddr">Đường Yersin, Phường Cam Ly, Thành phố Đà Lạt, Lâm Đồng</span>
        </div>
        <!-- 4 stat boxes -->
        <div class="detail-stats-row">
          <div class="ds-item">
            <div class="ds-icon">📐</div>
            <div class="ds-val" id="detailArea">250 m²</div>
            <div class="ds-lbl">Diện tích</div>
          </div>
          <div class="ds-item">
            <div class="ds-icon">🏠</div>
            <div class="ds-val" id="detailRoom">—</div>
            <div class="ds-lbl">Phòng ngủ</div>
          </div>
          <div class="ds-item">
            <div class="ds-icon">🧭</div>
            <div class="ds-val">Đông Nam</div>
            <div class="ds-lbl">Hướng</div>
          </div>
          <div class="ds-item">
            <div class="ds-icon">👁</div>
            <div class="ds-val">3</div>
            <div class="ds-lbl">Lượt xem</div>
          </div>
        </div>
      </div>

      <!-- THÔNG SỐ KỸ THUẬT -->
      <div class="detail-section">
        <div class="detail-section-header" onclick="toggleSection(this)">
          <div class="detail-section-title">📋 Thông số chi tiết</div>
          <span class="detail-section-toggle open">▾</span>
        </div>
        <div class="detail-section-body">
          <div class="spec-grid">
            <div class="spec-item">
              <span class="spec-label">Loại BĐS</span>
              <span class="spec-value blue">Đất ở phân quyền</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Mục đích</span>
              <span class="spec-value">Ở / Đầu tư</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Diện tích đất</span>
              <span class="spec-value">250 m²</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Diện tích XD</span>
              <span class="spec-value">—</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Mặt tiền</span>
              <span class="spec-value">12 m</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Chiều sâu</span>
              <span class="spec-value">20.8 m</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Hướng</span>
              <span class="spec-value">Đông Nam</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Đường trước</span>
              <span class="spec-value">6 m (nhựa)</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Số tầng</span>
              <span class="spec-value">—</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Năm xây</span>
              <span class="spec-value">—</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Giá / m²</span>
              <span class="spec-value green">4 triệu/m²</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Thương lượng</span>
              <span class="spec-value green">✓ Có</span>
            </div>
          </div>
        </div>
      </div>

      <!-- PHÁP LÝ -->
      <div class="detail-section">
        <div class="detail-section-header" onclick="toggleSection(this)">
          <div class="detail-section-title">⚖️ Pháp lý & Hồ sơ</div>
          <span class="detail-section-toggle open">▾</span>
        </div>
        <div class="detail-section-body">
          <div class="legal-item">
            <div class="legal-check yes">✓</div>
            <div class="legal-text">Sổ đỏ / GCNQSD Đất</div>
            <span class="legal-badge" style="background:var(--success-light);color:var(--success);">Có</span>
          </div>
          <div class="legal-item">
            <div class="legal-check yes">✓</div>
            <div class="legal-text">Đất trong quy hoạch ở</div>
            <span class="legal-badge" style="background:var(--success-light);color:var(--success);">Đã xác minh</span>
          </div>
          <div class="legal-item">
            <div class="legal-check yes">✓</div>
            <div class="legal-text">Không tranh chấp</div>
            <span class="legal-badge" style="background:var(--success-light);color:var(--success);">Xác nhận</span>
          </div>
          <div class="legal-item">
            <div class="legal-check maybe">!</div>
            <div class="legal-text">Quy hoạch xây dựng (hệ số 0.8)</div>
            <span class="legal-badge" style="background:var(--warning-light);color:var(--warning);">Cần hỏi thêm</span>
          </div>
          <div class="legal-item">
            <div class="legal-check yes">✓</div>
            <div class="legal-text">Điện — Nước — Internet đầy đủ</div>
            <span class="legal-badge" style="background:var(--success-light);color:var(--success);">Có</span>
          </div>
          <!-- Địa chỉ chi tiết chỉ Broker+ -->
          <div class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">
            <div class="legal-item" style="background:var(--primary-light);border-radius:var(--radius-sm);padding:10px 10px;margin-top:8px;">
              <div class="legal-check" style="background:var(--primary);color:#fff;">i</div>
              <div style="flex:1;">
                <div style="font-size:12px;font-weight:600;color:var(--primary-dark);">Thông tin chủ nhà (Broker only)</div>
                <div style="font-size:12px;color:var(--primary);">Nguyễn Văn A · <strong>0912.345.678</strong></div>
              </div>
              <button style="padding:5px 10px;background:var(--primary);color:#fff;border-radius:8px;font-size:11px;font-weight:600;" onclick="callOwner()">📞 Gọi</button>
            </div>
          </div>
          <div class="role-guest" style="margin-top:8px;">
            <div style="background:var(--bg-secondary);border-radius:var(--radius-sm);padding:10px;text-align:center;border:1px dashed var(--border);">
              <div style="font-size:12px;color:var(--text-tertiary);">🔒 Đăng ký eBroker để xem thông tin liên hệ chủ nhà</div>
            </div>
          </div>
        </div>
      </div>

      <!-- MÔ TẢ -->
      <div class="detail-section">
        <div class="detail-section-header" onclick="toggleSection(this)">
          <div class="detail-section-title">📝 Mô tả</div>
          <span class="detail-section-toggle open">▾</span>
        </div>
        <div class="detail-section-body">
          <div class="desc-text clamped" id="descText">
            Lô đất phân quyền sở hữu riêng, vị trí đẹp trên đường Yersin, Phường Cam Ly, Thành phố Đà Lạt. Lô đất có diện tích 250m², mặt tiền rộng 12m, hướng Đông Nam mát mẻ, phù hợp để ở hoặc đầu tư.
            <br><br>
            Khu vực Phường Cam Ly nổi tiếng với khí hậu mát mẻ, view nhìn ra thung lũng, gần Thác Cam Ly và trung tâm thành phố. Hạ tầng hoàn thiện: đường nhựa 6m, điện 3 pha, nước máy thành phố, cáp quang.
            <br><br>
            Chủ đất cần bán gấp, giá có thể thương lượng với người thiện chí. Hỗ trợ thủ tục sang tên nhanh trong vòng 7–10 ngày làm việc.
          </div>
          <button class="read-more-btn" id="readMoreBtn" onclick="toggleReadMore()">Xem thêm ▾</button>
        </div>
      </div>

      <!-- VỊ TRÍ -->
      <div class="detail-section">
        <div class="detail-section-header" onclick="toggleSection(this)">
          <div class="detail-section-title">🗺️ Vị trí</div>
          <span class="detail-section-toggle open">▾</span>
        </div>
        <div class="detail-section-body">
          <div class="map-preview">
            <div class="map-pin-center"></div>
            <div class="map-preview-label">
              <span class="role-guest">📍 Phường Cam Ly, Đà Lạt · Vị trí ẩn với Guest</span>
              <span class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">📍 Đường Yersin, Phường Cam Ly · <span style="color:var(--primary);font-weight:600;">Xem bản đồ đầy đủ →</span></span>
            </div>
          </div>
          <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-secondary);">🏥 BV Hoàn Mỹ <span style="color:var(--primary);">1.2 km</span></div>
            <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-secondary);">🏫 Trường Marie Curie <span style="color:var(--primary);">800 m</span></div>
            <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-secondary);">🛒 AEON <span style="color:var(--primary);">2.1 km</span></div>
            <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-secondary);">🏙 Trung tâm TP <span style="color:var(--primary);">3.5 km</span></div>
          </div>
        </div>
      </div>

      <!-- LỊCH SỬ GIÁ -->
      <div class="detail-section">
        <div class="detail-section-header" onclick="toggleSection(this)">
          <div class="detail-section-title">📊 Lịch sử giá</div>
          <span class="detail-section-toggle open">▾</span>
        </div>
        <div class="detail-section-body">
          <div class="price-history-chart">
            <div class="ph-bar-wrap">
              <div class="ph-bar-val">900tr</div>
              <div class="ph-bar" style="height:65%;"></div>
              <div class="ph-bar-month">T10</div>
            </div>
            <div class="ph-bar-wrap">
              <div class="ph-bar-val">920tr</div>
              <div class="ph-bar" style="height:70%;"></div>
              <div class="ph-bar-month">T11</div>
            </div>
            <div class="ph-bar-wrap">
              <div class="ph-bar-val">950tr</div>
              <div class="ph-bar" style="height:75%;"></div>
              <div class="ph-bar-month">T12</div>
            </div>
            <div class="ph-bar-wrap">
              <div class="ph-bar-val">980tr</div>
              <div class="ph-bar" style="height:80%;"></div>
              <div class="ph-bar-month">T1</div>
            </div>
            <div class="ph-bar-wrap">
              <div class="ph-bar-val">990tr</div>
              <div class="ph-bar" style="height:82%;"></div>
              <div class="ph-bar-month">T2</div>
            </div>
            <div class="ph-bar-wrap">
              <div class="ph-bar-val">1 tỷ</div>
              <div class="ph-bar current" style="height:100%;"></div>
              <div class="ph-bar-month">T3</div>
            </div>
          </div>
          <div style="font-size:11px;color:var(--text-tertiary);margin-top:4px;">Giá tăng <span style="color:var(--success);font-weight:600;">+11.1%</span> trong 6 tháng qua</div>
        </div>
      </div>

      <!-- BROKER / CHỦ ĐẤT -->
      <div class="detail-section">
        <div class="detail-section-header" style="cursor:default;">
          <div class="detail-section-title">👤 Người đăng</div>
        </div>
        <div class="owner-card">
          <div class="owner-avatar">HT</div>
          <div class="owner-info">
            <div class="owner-name">Huy Thái</div>
            <div class="owner-role">eBroker · Đà Lạt BĐS</div>
            <div class="owner-rating">
              <span class="stars">★★★★★</span>
              <span style="font-size:12px;font-weight:600;">4.8</span>
              <span style="font-size:11px;color:var(--text-tertiary);">(32 đánh giá)</span>
            </div>
          </div>
          <div class="owner-actions">
            <div class="owner-btn role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin" onclick="callOwner()">📞</div>
            <div class="owner-btn">💬</div>
          </div>
        </div>
        <!-- CRM: Gửi cho khách nhanh — Sale+ -->
        <div class="role-sale role-bds_admin role-sale_admin role-admin" style="margin:0 16px 14px;">
          <div style="background:var(--purple-light);border-radius:var(--radius-md);padding:10px 12px;display:flex;align-items:center;gap:10px;">
            <span style="font-size:18px;">📤</span>
            <div style="flex:1;">
              <div style="font-size:12px;font-weight:600;color:var(--purple);">Gửi BĐS này cho khách đang deal</div>
              <div style="font-size:11px;color:var(--text-secondary);">3 deal đang active có thể phù hợp</div>
            </div>
            <button style="padding:6px 12px;background:var(--purple);color:#fff;border-radius:8px;font-size:12px;font-weight:600;" onclick="openSendModal()">Gửi</button>
          </div>
        </div>
      </div>

      <!-- BĐS TƯƠNG TỰ -->
      <div class="detail-section" style="padding-bottom:4px;">
        <div class="detail-section-header" style="cursor:default;">
          <div class="detail-section-title">🏘️ BĐS tương tự</div>
        </div>
        <div class="similar-scroll">
          <div class="similar-card" onclick="openDetail({title:'Đất ở Đường Trần Phú',price:'850 triệu',type:'Đất ở',area:'200 m²',addr:'P.Cam Ly',room:'—',slide:1})">
            <div class="similar-img gs2">🌿</div>
            <div class="similar-body">
              <div class="similar-price">850 triệu</div>
              <div class="similar-area">200 m² · P.Cam Ly</div>
            </div>
          </div>
          <div class="similar-card" onclick="openDetail({title:'Biệt thự View Đồi Cam Ly',price:'4,200 triệu',type:'Biệt thự',area:'350 m²',addr:'P.Cam Ly',room:'4 PN',slide:2})">
            <div class="similar-img gs3">🏡</div>
            <div class="similar-body">
              <div class="similar-price">4,200 triệu</div>
              <div class="similar-area">350 m² · 4 PN</div>
            </div>
          </div>
          <div class="similar-card" onclick="openDetail({title:'Nhà phố Lâm Viên',price:'1,500 triệu',type:'Nhà phố',area:'120 m²',addr:'P.Lâm Viên',room:'3 PN',slide:3})">
            <div class="similar-img gs4">🏠</div>
            <div class="similar-body">
              <div class="similar-price">1,500 triệu</div>
              <div class="similar-area">120 m² · 3 PN</div>
            </div>
          </div>
          <div class="similar-card" onclick="openDetail({title:'Đất nền Lâm Viên',price:'650 triệu',type:'Đất ở',area:'180 m²',addr:'P.Lâm Viên',room:'—',slide:0})">
            <div class="similar-img gs1">🌄</div>
            <div class="similar-body">
              <div class="similar-price">650 triệu</div>
              <div class="similar-area">180 m² · P.Lâm Viên</div>
            </div>
          </div>
        </div>
      </div>

      <div style="height:16px;"></div>
    </div><!-- end detail-scroll -->

    <!-- CTA BOTTOM BAR — thay đổi theo role -->
    <!-- Sale+ -->
    <div class="crm-action-bar role-sale role-bds_admin role-sale_admin role-admin" id="crmActionBar">
      <div class="crm-action-secondary">
        <button class="crm-secondary-btn" onclick="toggleBookmark(document.getElementById('bookmarkBtn'))" title="Lưu">🤍</button>
        <button class="crm-secondary-btn" onclick="openSendModal()" title="Gửi cho khách">📤</button>
      </div>
      <button class="crm-primary-btn green" onclick="openBookingForm()">📅 Đặt lịch xem</button>
      <button class="crm-primary-btn purple" onclick="openSendModal()">🤝 Gửi Deal</button>
    </div>
    <!-- Broker -->
    <div class="crm-action-bar role-broker" id="brokerActionBar">
      <div class="crm-action-secondary">
        <button class="crm-secondary-btn" onclick="toggleBookmark(document.getElementById('bookmarkBtn'))">🤍</button>
        <button class="crm-secondary-btn" onclick="shareDetail()">↗️</button>
      </div>
      <button class="crm-primary-btn" onclick="callOwner()">📞 Liên hệ chủ nhà</button>
    </div>
    <!-- Guest CTA -->
    <div class="guest-cta-bar role-guest">
      <div class="guest-cta-hint">🔒 Đăng ký để xem địa chỉ & liên hệ trực tiếp</div>
      <div class="guest-cta-btns">
        <button class="guest-cta-register">Đăng ký Broker</button>
        <button class="guest-cta-contact" onclick="showToast('Yêu cầu đã gửi!')">Gửi yêu cầu</button>
      </div>
    </div>

    <!-- BOOKING MINI FORM (hidden by default) -->
    <div id="bookingFormOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:650;align-items:flex-end;justify-content:center;">
      <div style="background:var(--bg-card);border-radius:20px 20px 0 0;padding:8px 0 32px;width:100%;max-width:430px;">
        <div style="width:36px;height:4px;background:var(--border);border-radius:2px;margin:0 auto 16px;"></div>
        <div style="font-size:16px;font-weight:700;padding:0 20px 14px;border-bottom:1px solid var(--border);color:var(--text-primary);">📅 Đặt lịch xem nhà</div>
        <div style="padding:16px 20px;">
          <div style="font-size:12px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.04em;">BĐS</div>
          <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:14px;padding:10px 12px;background:var(--bg-secondary);border-radius:var(--radius-sm);" id="bookingPropName">Đất ở Đường Yersin, Cam Ly</div>
          <div style="font-size:12px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.04em;">Khách hàng</div>
          <select class="dt-input" style="margin-bottom:12px;">
            <option>Chọn Deal / Khách hàng...</option>
            <option>Anh Minh Tuấn — Deal #1</option>
            <option>Chị Thu Hà — Deal #2</option>
            <option>Anh Bảo — Deal #3</option>
          </select>
          <div class="booking-datetime">
            <input type="date" class="dt-input" value="2026-03-18">
            <input type="time" class="dt-input" value="09:00">
          </div>
          <textarea class="dt-input" rows="2" placeholder="Ghi chú cho chủ nhà / khách..." style="resize:none;margin-bottom:14px;"></textarea>
          <div style="display:flex;gap:10px;">
            <button style="flex:1;padding:13px;border:1.5px solid var(--border);border-radius:var(--radius-md);font-size:14px;color:var(--text-secondary);" onclick="closeBookingForm()">Hủy</button>
            <button style="flex:2;padding:13px;background:var(--success);color:#fff;border-radius:var(--radius-md);font-size:14px;font-weight:700;" onclick="confirmBooking()">✓ Tạo lịch hẹn</button>
          </div>
        </div>
      </div>
    </div>

  </div><!-- end page-detail -->

  <!-- SEND TO CUSTOMER MODAL -->
  <div class="send-modal-overlay" id="sendModalOverlay">
    <div class="send-modal">
      <div class="send-modal-handle"></div>
      <div class="send-modal-title">📤 Gửi BĐS cho khách đang Deal</div>
      <div id="dealPickList">
        <div class="deal-pick-item" onclick="selectDeal(this,1)">
          <div class="deal-pick-avatar">MT</div>
          <div>
            <div class="deal-pick-name">Anh Minh Tuấn</div>
            <div class="deal-pick-meta">Deal #1 · Tìm Đất ở · 1–2 tỷ · P.Cam Ly</div>
          </div>
          <span class="deal-pick-check" id="pick1">○</span>
        </div>
        <div class="deal-pick-item" onclick="selectDeal(this,2)">
          <div class="deal-pick-avatar" style="background:var(--teal-light);color:var(--teal);">TH</div>
          <div>
            <div class="deal-pick-name">Chị Thu Hà</div>
            <div class="deal-pick-meta">Deal #2 · Tìm Nhà phố · 1.5–3 tỷ · P.Cam Ly</div>
          </div>
          <span class="deal-pick-check" id="pick2">○</span>
        </div>
        <div class="deal-pick-item" onclick="selectDeal(this,3)">
          <div class="deal-pick-avatar" style="background:var(--warning-light);color:var(--warning);">BT</div>
          <div>
            <div class="deal-pick-name">Anh Bảo Trâm</div>
            <div class="deal-pick-meta">Deal #3 · Tìm Đất + Nhà · 800tr–1.2 tỷ</div>
          </div>
          <span class="deal-pick-check" id="pick3">○</span>
        </div>
      </div>
      <button class="send-confirm-btn" onclick="confirmSend()" id="sendConfirmBtn" disabled style="opacity:0.4;">Gửi BĐS cho khách đã chọn</button>
      <button onclick="closeSendModal()" style="display:block;width:calc(100%-40px);margin:10px 20px 0;padding:12px;border:1px solid var(--border);border-radius:var(--radius-md);font-size:14px;color:var(--text-secondary);">Hủy</button>
    </div>
  </div>

  <!-- TOAST -->
  <div class="toast" id="toast">✓ Đã lưu bookmark</div>

