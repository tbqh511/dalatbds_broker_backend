  <div id="page-detail">

    <!-- sticky header -->
    <div class="detail-sticky-header" id="detailStickyHeader">
      <button class="dh-btn" onclick="closeDetail()"><span>←</span></button>
      <div class="dh-title" id="detailHeaderTitle">Chi tiết BĐS</div>
      <div class="dh-actions">
        <button class="dh-btn" onclick="toggleBookmark(this)" id="bookmarkBtn"><span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span></button>
        <button class="dh-btn" onclick="shareDetail()"><span><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span></button>
      </div>
    </div>

    <!-- ROLE SWITCHER (dev tool) -->
    <div class="detail-role-switcher" style="display:flex;gap:6px;padding:8px 12px;background:rgba(0,0,0,0.65);overflow-x:auto;scrollbar-width:none;flex-shrink:0;">
      <button class="rbtn" onclick="setRole('guest',this)" style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);white-space:nowrap;">👤 Guest</button>
      <button class="rbtn" onclick="setRole('broker',this)" style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);white-space:nowrap;">🏠 Broker</button>
      <button class="rbtn" onclick="setRole('bds_admin',this)" style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);white-space:nowrap;">🏘️ BĐS Admin</button>
      <button class="rbtn active" onclick="setRole('sale',this)" style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:#3270FC;color:#fff;white-space:nowrap;">💼 Sale</button>
      <button class="rbtn" onclick="setRole('sale_admin',this)" style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);white-space:nowrap;">📋 Sale Admin</button>
      <button class="rbtn" onclick="setRole('admin',this)" style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);white-space:nowrap;">👑 Admin</button>
    </div>

    <!-- gallery -->
    <div class="detail-gallery">
      <div class="gallery-slides" id="gallerySlides">
        <div class="gallery-slide" id="gslide-0" style="background:#1e2a3a;"><img src="" alt="Ảnh BĐS 1" style="width:100%;height:100%;object-fit:cover;display:block;"></div>
        <div class="gallery-slide" id="gslide-1" style="background:#162032;"><img src="" alt="Ảnh BĐS 2" style="width:100%;height:100%;object-fit:cover;display:block;"></div>
        <div class="gallery-slide" id="gslide-2" style="background:#1a2840;"><img src="" alt="Ảnh BĐS 3" style="width:100%;height:100%;object-fit:cover;display:block;"></div>
        <div class="gallery-slide" id="gslide-3" style="background:#0f1c2e;"><img src="" alt="Ảnh BĐS 4" style="width:100%;height:100%;object-fit:cover;display:block;"></div>
      </div>
      <div class="gallery-price-badge">
        <div class="price-big" id="detailPrice">1,000 triệu</div>
        <div class="price-unit" id="detailPriceM2">≈ 4 tr/m² · Có thương lượng</div>
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
          <span class="detail-addr-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
          <span id="detailAddr">Đường Yersin, Phường Cam Ly, Thành phố Đà Lạt, Lâm Đồng</span>
        </div>
        <!-- 4 stat boxes -->
        <div class="detail-stats-row">
          <div class="ds-item">
            <div class="ds-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg></div>
            <div class="ds-val" id="detailArea">250 m²</div>
            <div class="ds-lbl">Diện tích</div>
          </div>
          <div class="ds-item">
            <div class="ds-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
            <div class="ds-val" id="detailRoom">—</div>
            <div class="ds-lbl">Phòng ngủ</div>
          </div>
          <div class="ds-item">
            <div class="ds-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg></div>
            <div class="ds-val" id="detailDirection">Đông Nam</div>
            <div class="ds-lbl">Hướng</div>
          </div>
          <div class="ds-item">
            <div class="ds-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></div>
            <div class="ds-val" id="detailViews">3</div>
            <div class="ds-lbl">Lượt xem</div>
          </div>
        </div>
      </div>

      <!-- THÔNG SỐ KỸ THUẬT -->
      <div class="detail-section">
        <div class="detail-section-header" onclick="toggleSection(this)">
          <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg> Thông số chi tiết</span></div>
          <span class="detail-section-toggle open">▾</span>
        </div>
        <div class="detail-section-body">
          <div class="spec-grid">
            <div class="spec-item">
              <span class="spec-label">Loại BĐS</span>
              <span class="spec-value blue" id="specType">Đất ở phân quyền</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Mục đích</span>
              <span class="spec-value" id="specPurpose">Ở / Đầu tư</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Diện tích đất</span>
              <span class="spec-value" id="specLandArea">250 m²</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Diện tích XD</span>
              <span class="spec-value" id="specFloorArea">—</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Mặt tiền</span>
              <span class="spec-value" id="specFrontage">12 m</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Chiều sâu</span>
              <span class="spec-value" id="specDepth">20.8 m</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Hướng</span>
              <span class="spec-value" id="specDirection">Đông Nam</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Đường trước</span>
              <span class="spec-value" id="specRoadWidth">6 m (nhựa)</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Số tầng</span>
              <span class="spec-value" id="specFloors">—</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Năm xây</span>
              <span class="spec-value" id="specYear">—</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Giá / m²</span>
              <span class="spec-value green" id="specPriceM2">4 triệu/m²</span>
            </div>
            <div class="spec-item">
              <span class="spec-label">Thương lượng</span>
              <span class="spec-value green" id="specNegotiable">✓ Có</span>
            </div>
          </div>
        </div>
      </div>

      <!-- PHÁP LÝ -->
      <div class="detail-section">
        <div class="detail-section-header" onclick="toggleSection(this)">
          <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> Pháp lý & Hồ sơ</span></div>
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
              <button style="padding:5px 10px;background:var(--primary);color:#fff;border-radius:8px;font-size:11px;font-weight:600;" onclick="callOwner()"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Gọi</span></button>
            </div>
          </div>
          <div class="role-guest" style="margin-top:8px;">
            <div style="background:var(--bg-secondary);border-radius:var(--radius-sm);padding:10px;text-align:center;border:1px dashed var(--border);">
              <div style="font-size:12px;color:var(--text-tertiary);display:flex;align-items:center;justify-content:center;gap:5px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Đăng ký eBroker để xem thông tin liên hệ chủ nhà</div>
            </div>
          </div>
        </div>
      </div>

      <!-- MÔ TẢ -->
      <div class="detail-section">
        <div class="detail-section-header" onclick="toggleSection(this)">
          <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Mô tả</span></div>
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
          <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg> Vị trí</span></div>
          <span class="detail-section-toggle open">▾</span>
        </div>
        <div class="detail-section-body">
          <div class="map-preview">
            <div class="map-pin-center"></div>
            <div class="map-preview-label">
              <span class="role-guest"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> Phường Cam Ly, Đà Lạt · Vị trí ẩn với Guest</span></span>
              <span class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> Đường Yersin, Phường Cam Ly · <span style="color:var(--primary);font-weight:600;">Xem bản đồ đầy đủ →</span></span></span>
            </div>
          </div>
          <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-secondary);"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg> BV Hoàn Mỹ <span style="color:var(--primary);">1.2 km</span></div>
            <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-secondary);"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg> Trường Marie Curie <span style="color:var(--primary);">800 m</span></div>
            <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-secondary);"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg> AEON <span style="color:var(--primary);">2.1 km</span></div>
            <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-secondary);"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg> Trung tâm TP <span style="color:var(--primary);">3.5 km</span></div>
          </div>
        </div>
      </div>

      <!-- LỊCH SỬ GIÁ -->
      <div class="detail-section">
        <div class="detail-section-header" onclick="toggleSection(this)">
          <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Lịch sử giá</span></div>
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
          <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Người đăng</span></div>
        </div>
        <div class="owner-card">
          <div class="owner-avatar" id="ownerInitials">HT</div>
          <div class="owner-info">
            <div class="owner-name" id="ownerName">Huy Thái</div>
            <div class="owner-role" id="ownerRole">eBroker · Đà Lạt BĐS</div>
            <div class="owner-rating">
              <span class="stars">★★★★★</span>
              <span style="font-size:12px;font-weight:600;">4.8</span>
              <span style="font-size:11px;color:var(--text-tertiary);">(32 đánh giá)</span>
            </div>
          </div>
          <div class="owner-actions">
            <div class="owner-btn role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin" onclick="callOwner()"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
            <div class="owner-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
          </div>
        </div>
        <!-- CRM: Gửi cho khách nhanh — Sale+ -->
        <div class="role-sale role-bds_admin role-sale_admin role-admin" style="margin:0 16px 14px;">
          <div style="background:var(--purple-light);border-radius:var(--radius-md);padding:10px 12px;display:flex;align-items:center;gap:10px;">
            <span style="display:inline-flex;align-items:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
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
          <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg> BĐS tương tự</span></div>
        </div>
        <div class="similar-scroll">
          <div class="similar-card" onclick="openDetail({title:'Đất ở Đường Trần Phú',price:'850 triệu',type:'Đất ở',area:'200 m²',addr:'P.Cam Ly',room:'—',slide:1})">
            <div class="similar-img gs2" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div class="similar-body">
              <div class="similar-price">850 triệu</div>
              <div class="similar-area">200 m² · P.Cam Ly</div>
            </div>
          </div>
          <div class="similar-card" onclick="openDetail({title:'Biệt thự View Đồi Cam Ly',price:'4,200 triệu',type:'Biệt thự',area:'350 m²',addr:'P.Cam Ly',room:'4 PN',slide:2})">
            <div class="similar-img gs3" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
            <div class="similar-body">
              <div class="similar-price">4,200 triệu</div>
              <div class="similar-area">350 m² · 4 PN</div>
            </div>
          </div>
          <div class="similar-card" onclick="openDetail({title:'Nhà phố Lâm Viên',price:'1,500 triệu',type:'Nhà phố',area:'120 m²',addr:'P.Lâm Viên',room:'3 PN',slide:3})">
            <div class="similar-img gs4" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
            <div class="similar-body">
              <div class="similar-price">1,500 triệu</div>
              <div class="similar-area">120 m² · 3 PN</div>
            </div>
          </div>
          <div class="similar-card" onclick="openDetail({title:'Đất nền Lâm Viên',price:'650 triệu',type:'Đất ở',area:'180 m²',addr:'P.Lâm Viên',room:'—',slide:0})">
            <div class="similar-img gs1" style="display:flex;align-items:center;justify-content:center;"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div>
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
        <button class="crm-secondary-btn" onclick="toggleBookmark(document.getElementById('bookmarkBtn'))" title="Lưu"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
        <button class="crm-secondary-btn" onclick="openSendModal()" title="Gửi cho khách"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></button>
      </div>
      <button class="crm-primary-btn green" onclick="openBookingForm()"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Đặt lịch xem</span></button>
      <button class="crm-primary-btn purple" onclick="openSendModal()"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Gửi Deal</span></button>
    </div>
    <!-- Broker -->
    <div class="crm-action-bar role-broker" id="brokerActionBar">
      <div class="crm-action-secondary">
        <button class="crm-secondary-btn" onclick="toggleBookmark(document.getElementById('bookmarkBtn'))"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
        <button class="crm-secondary-btn" onclick="shareDetail()"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></button>
      </div>
      <button class="crm-primary-btn" onclick="callOwner()"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Liên hệ chủ nhà</span></button>
    </div>
    <!-- Guest CTA -->
    <div class="guest-cta-bar role-guest">
      <div class="guest-cta-hint"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Đăng ký để xem địa chỉ & liên hệ trực tiếp</span></div>
      <div class="guest-cta-btns">
        <button class="guest-cta-register">Đăng ký Broker</button>
        <button class="guest-cta-contact" onclick="showToast('Yêu cầu đã gửi!')">Gửi yêu cầu</button>
      </div>
    </div>

    <!-- BOOKING MINI FORM (hidden by default) -->
    <div id="bookingFormOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:650;align-items:flex-end;justify-content:center;">
      <div style="background:var(--bg-card);border-radius:20px 20px 0 0;padding:8px 0 32px;width:100%;max-width:430px;">
        <div style="width:36px;height:4px;background:var(--border);border-radius:2px;margin:0 auto 16px;"></div>
        <div style="font-size:16px;font-weight:700;padding:0 20px 14px;border-bottom:1px solid var(--border);color:var(--text-primary);display:flex;align-items:center;gap:6px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Đặt lịch xem nhà</div>
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
      <div class="send-modal-title"><span style="display:inline-flex;align-items:center;gap:6px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi BĐS cho khách đang Deal</span></div>
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

