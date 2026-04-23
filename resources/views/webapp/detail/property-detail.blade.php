<div id="page-detail">

  <!-- sticky header -->
  <div class="detail-sticky-header" id="detailStickyHeader">
    <button class="search-back-btn" style="margin-right:0;flex-shrink:0;" onclick="closeDetail()"><svg width="20"
        height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6" />
      </svg></button>
    <div class="dh-title" id="detailHeaderTitle">Chi tiết BĐS</div>
    <div class="dh-actions">
      <button class="dh-btn" onclick="toggleBookmark(this)" id="bookmarkBtn"><span><svg width="18" height="18"
            viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round"
            stroke-linejoin="round">
            <path
              d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
          </svg></span></button>
      <button class="dh-btn" onclick="shareDetail()"><span><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
            stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" />
            <polyline points="16 6 12 2 8 6" />
            <line x1="12" y1="2" x2="12" y2="15" />
          </svg></span></button>
    </div>
  </div>

  <!-- scrollable content (includes gallery so it scrolls away naturally) -->
  <div class="detail-scroll" id="detailScroll">

    <!-- gallery -->
    <div class="detail-gallery">
      <!-- loading overlay for gallery -->
      <div id="detailGalleryLoader"
        style="display:none;position:absolute;inset:0;background:rgba(15,28,50,0.7);z-index:10;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
        <div
          style="width:32px;height:32px;border:3px solid rgba(255,255,255,0.2);border-top-color:#fff;border-radius:50%;animation:spin 0.7s linear infinite;">
        </div>
        <span style="color:rgba(255,255,255,0.8);font-size:12px;">Đang tải...</span>
      </div>
      <div class="gallery-slides" id="gallerySlides">
        <!-- slides generated dynamically by JS -->
        <div class="gallery-slide" id="gslide-0" style="background:#1e2a3a;"><img src="" alt="Ảnh BĐS"
            style="width:100%;height:100%;object-fit:cover;display:block;"></div>
      </div>
      <div class="gallery-price-badge">
        <div class="price-big" id="detailPrice">--</div>
        <div class="price-unit" id="detailPriceM2"></div>
      </div>
      <div class="gallery-bottom">
        <div class="gallery-counter"><span id="galleryIdx">1</span> / <span id="galleryTotal">1</span> ảnh</div>
        <div class="gallery-dots" id="galleryDots">
          <div class="gdot active"></div>
        </div>
      </div>
    </div>

    @if(isset($customer) && $customer->getEffectiveRole() === 'admin')
      <!-- ROLE SWITCHER (dev tool) — below gallery, scrolls with content -->
      <div class="detail-role-switcher"
        style="display:flex;gap:6px;padding:8px 12px;background:rgba(0,0,0,0.65);overflow-x:auto;scrollbar-width:none;flex-shrink:0;">
        <button class="rbtn" onclick="setRole('guest',this)"
          style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);white-space:nowrap;">👤
          Guest</button>
        <button class="rbtn" onclick="setRole('broker',this)"
          style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);white-space:nowrap;">🏠
          Broker</button>
        <button class="rbtn" onclick="setRole('bds_admin',this)"
          style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);white-space:nowrap;">🏘️
          BĐS Admin</button>
        <button class="rbtn active" onclick="setRole('sale',this)"
          style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:#3270FC;color:#fff;white-space:nowrap;">💼
          Sale</button>
        <button class="rbtn" onclick="setRole('sale_admin',this)"
          style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);white-space:nowrap;">📋
          Sale Admin</button>
        <button class="rbtn" onclick="setRole('admin',this)"
          style="padding:3px 10px;border-radius:20px;font-size:10px;font-weight:600;background:rgba(255,255,255,0.12);color:rgba(255,255,255,0.6);white-space:nowrap;">👑
          Admin</button>
      </div>
    @endif

    <!-- INFO CARD -->
    <div class="detail-info-card">
      <div class="detail-badges" id="detailBadges">
        <span class="badge badge-blue" id="detailType">BĐS</span>
        <span class="badge badge-green" id="detailTransactionBadge">Đang bán</span>
        <span class="badge badge-amber" id="detailStatusBadge">Còn hàng</span>
      </div>
      <div class="detail-title" id="detailTitle">Đang tải...</div>
      <div class="detail-addr">
        <span class="detail-addr-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
          </svg></span>
        <span id="detailAddr">--</span>
      </div>
      <!-- 4 stat boxes -->
      <div class="detail-stats-row">
        <div class="ds-item">
          <div class="ds-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
              <line x1="3" y1="9" x2="21" y2="9" />
              <line x1="9" y1="21" x2="9" y2="9" />
            </svg></div>
          <div class="ds-val" id="detailArea">--</div>
          <div class="ds-lbl">Diện tích</div>
        </div>
        <div class="ds-item" id="detailRoomBox">
          <div class="ds-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
              <polyline points="9 22 9 12 15 12 15 22" />
            </svg></div>
          <div class="ds-val" id="detailRoom">—</div>
          <div class="ds-lbl">Phòng ngủ</div>
        </div>
        <div class="ds-item" id="detailDirectionBox">
          <div class="ds-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10" />
              <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" />
            </svg></div>
          <div class="ds-val" id="detailDirection">—</div>
          <div class="ds-lbl">Hướng</div>
        </div>
        <div class="ds-item">
          <div class="ds-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
              <circle cx="12" cy="12" r="3" />
            </svg></div>
          <div class="ds-val" id="detailViews">0</div>
          <div class="ds-lbl">Lượt xem</div>
        </div>
      </div>
    </div>

    <!-- THÔNG SỐ KỸ THUẬT — dynamic -->
    <div class="detail-section" id="detailSpecSection">
      <div class="detail-section-header" onclick="toggleSection(this)">
        <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14"
              height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
              <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
            </svg> Thông số chi tiết</span></div>
        <span class="detail-section-toggle open">▾</span>
      </div>
      <div class="detail-section-body">
        <div class="spec-grid" id="specGrid">
          <!-- populated dynamically -->
          <div class="spec-item" id="specAreaItem" style="display:none;">
            <span class="spec-label">Diện tích</span>
            <span class="spec-value blue" id="specArea">—</span>
          </div>
          <div class="spec-item" id="specLegalItem" style="display:none;">
            <span class="spec-label">Pháp lý</span>
            <span class="spec-value green" id="specLegal">—</span>
          </div>
          <div class="spec-item" id="specPriceM2Item" style="display:none;">
            <span class="spec-label">Giá / m²</span>
            <span class="spec-value green" id="specPriceM2">—</span>
          </div>
          <div class="spec-item" id="specCommissionItem" style="display:none;">
            <span class="spec-label">Hoa hồng</span>
            <span class="spec-value blue" id="specCommission">—</span>
          </div>
          <!-- dynamic params appended here by JS -->
        </div>
      </div>
    </div>

    <!-- TIỆN ÍCH — dynamic, hidden when empty -->
    <div class="detail-section" id="detailFacilitiesSection" style="display:none;">
      <div class="detail-section-header" onclick="toggleSection(this)">
        <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14"
              height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="16" />
              <line x1="8" y1="12" x2="16" y2="12" />
            </svg> Tiện ích xung quanh</span></div>
        <span class="detail-section-toggle open">▾</span>
      </div>
      <div class="detail-section-body">
        <div id="facilitiesGrid" style="display:flex;flex-wrap:wrap;gap:8px;padding:4px 0;"></div>
      </div>
    </div>

    <!-- MÔ TẢ — hidden when empty -->
    <div class="detail-section" id="detailDescSection" style="display:none;">
      <div class="detail-section-header" onclick="toggleSection(this)">
        <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14"
              height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg> Mô tả</span></div>
        <span class="detail-section-toggle open">▾</span>
      </div>
      <div class="detail-section-body">
        <div class="desc-text clamped" id="descText"></div>
        <button class="read-more-btn" id="readMoreBtn" onclick="toggleReadMore()" style="display:none;">Xem thêm
          ▾</button>
      </div>
    </div>

    <!-- PHÁP LÝ & HỒ SƠ — Broker+ only -->
    <div class="detail-section role-broker role-bds_admin role-sale role-sale_admin role-admin" id="detailLegalSection">
      <div class="detail-section-header" onclick="toggleSection(this)">
        <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14"
              height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
            </svg> Pháp lý & Hồ sơ</span></div>
        <span class="detail-section-toggle open">▾</span>
      </div>
      <div class="detail-section-body">
        <!-- Legal type badge -->
        <div id="detailLegalItem" style="display:none;">
          <div class="legal-item">
            <div class="legal-check yes">✓</div>
            <div class="legal-text" id="detailLegalText">—</div>
            <span class="legal-badge" style="background:var(--success-light);color:var(--success);">Có sổ</span>
          </div>
        </div>
        <!-- Placeholder when no legal data -->
        <div id="detailLegalEmpty" style="font-size:12px;color:var(--text-tertiary);padding:4px 0;">Chưa có thông tin
          pháp lý</div>
      </div>
    </div>

    <!-- VỊ TRÍ — hidden when no coordinates, Broker+ only -->
    <div class="detail-section role-broker role-bds_admin role-sale role-sale_admin role-admin"
      id="detailLocationSection" style="display:none;">
      <div class="detail-section-header" onclick="toggleSection(this)">
        <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14"
              height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10" />
              <line x1="2" y1="12" x2="22" y2="12" />
              <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
            </svg> Vị trí</span></div>
        <span class="detail-section-toggle open">▾</span>
      </div>
      <div class="detail-section-body">
        <div class="map-preview" id="detailMapPreview">
          <!-- iframe embed (injected by JS when coordinates available) -->
          <div id="mapIframeContainer"
            style="position:absolute;inset:0;border-radius:var(--radius-md);overflow:hidden;display:none;"></div>
          <!-- transparent click overlay (sits above iframe, opens Google Maps) -->
          <div id="mapClickOverlay" onclick="openGoogleMaps()"
            style="position:absolute;inset:0;z-index:2;display:none;cursor:pointer;"></div>
          <!-- pin placeholder (hidden when map loaded) -->
          <div class="map-pin-center" id="mapPinCenter"></div>
          <div class="map-preview-label" style="z-index:3;">
            <span style="display:inline-flex;align-items:center;gap:3px;">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                <circle cx="12" cy="10" r="3" />
              </svg>
              <span id="mapAddrLabel">Đà Lạt</span>
              &nbsp;·&nbsp;
              <a id="mapLink" href="#" target="_blank"
                style="color:var(--primary);font-weight:600;display:inline-flex;align-items:center;gap:3px;">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                  <polyline points="15 3 21 3 21 9" />
                  <line x1="10" y1="14" x2="21" y2="3" />
                </svg>
                Xem bản đồ
              </a>
            </span>
          </div>
        </div>
        <!-- Nút bản đồ pháp lý — chỉ hiện khi có lat/lng (JS toggle qua data-wardcode) -->
        <div id="btnLegalMapWrap" style="margin-top:10px;display:none;">
          <button onclick="openLegalMap()" style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px 0;border-radius:10px;border:1.5px solid var(--primary);background:transparent;color:var(--primary);font-size:13px;font-weight:600;cursor:pointer;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
            Xem bản đồ pháp lý thửa đất
          </button>
        </div>
      </div>
    </div>

    <!-- Bottom sheet: bản đồ pháp lý -->
    <div id="legalMapSheet" style="display:none;position:fixed;inset:0;z-index:3000;background:rgba(0,0,0,0.45);" onclick="if(event.target===this)closeLegalMap()">
      <div style="position:absolute;bottom:0;left:0;right:0;background:#fff;border-radius:18px 18px 0 0;display:flex;flex-direction:column;max-height:92vh;">
        <!-- Header -->
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px 10px;border-bottom:1px solid #eee;flex-shrink:0;">
          <div>
            <div style="font-size:15px;font-weight:700;color:#1a1a2e;">Bản đồ pháp lý thửa đất</div>
            <div style="font-size:11px;color:#aaa;margin-top:1px;">Nguồn: UBND TP Đà Lạt · QH 2030</div>
          </div>
          <button onclick="closeLegalMap()" style="width:30px;height:30px;border-radius:50%;border:none;background:#f0f0f0;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">✕</button>
        </div>
        <!-- Map container (với loading skeleton bên trong) -->
        <div style="position:relative;flex:1;min-height:300px;max-height:55vh;">
          <div id="legalMapContainer" style="position:absolute;inset:0;"></div>
          <!-- Loading skeleton overlay -->
          <div id="legalMapLoading" style="position:absolute;inset:0;z-index:10;background:#f0f2f5;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;">
            <div style="width:40px;height:40px;border:3px solid #e0e0e0;border-top-color:#4a7fb5;border-radius:50%;animation:legalMapSpin 0.8s linear infinite;"></div>
            <div style="font-size:12px;color:#aaa;">Đang tải bản đồ quy hoạch...</div>
          </div>
        </div>
        <!-- Legend bar -->
        <div id="legalMapLegend" style="flex-shrink:0;display:flex;gap:6px;flex-wrap:wrap;padding:8px 14px;border-top:1px solid #eee;background:#fafafa;">
          <div id="legalMapZoomNotice" style="display:none;width:100%;font-size:11px;color:#888;padding:2px 0;">ℹ️ Lớp QH 2030 ẩn ở mức zoom này — thu nhỏ để xem lại</div>
        </div>
        <!-- Kết quả quy hoạch -->
        <div id="legalParcelInfo" style="flex-shrink:0;padding:10px 14px 14px;border-top:1px solid #eee;max-height:150px;overflow-y:auto;">
          <div style="color:#bbb;font-size:12px;text-align:center;padding:6px 0;">Đang phân tích vùng quy hoạch...</div>
        </div>
      </div>
    </div>
    <style>
      @keyframes legalMapSpin { to { transform: rotate(360deg); } }
    </style>

    <!-- BROKER / NGƯỜI ĐĂNG -->
    <div class="detail-section">
      <div class="detail-section-header" style="cursor:default;">
        <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14"
              height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg> Người đăng</span></div>
      </div>
      <div class="owner-card">
        <div class="owner-avatar" id="ownerInitials">BK</div>
        <div class="owner-info">
          <div class="owner-name" id="ownerName">Môi giới</div>
          <div class="owner-role" id="ownerRole">eBroker · Đà Lạt BĐS</div>
        </div>
        <div class="owner-actions">
          {{-- Nút Edit — chỉ hiện nếu Broker là chủ tin đăng (JS kiểm tra quyền) --}}
          <div class="owner-btn" id="ownerEditBtn" onclick="editCurrentProperty()" style="display:none;" title="Chỉnh sửa BĐS">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
          </div>
          <div class="owner-btn role-broker role-bds_admin role-sale role-sale_admin role-admin" onclick="callOwner()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round">
              <path
                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
            </svg>
          </div>
        </div>
      </div>
      <!-- Send to customer — Sale+ -->
      <div class="role-sale role-bds_admin role-sale_admin role-admin" style="margin:0 16px 14px;">
        <div
          style="background:var(--purple-light);border-radius:var(--radius-md);padding:10px 12px;display:flex;align-items:center;gap:10px;">
          <span style="display:inline-flex;align-items:center;"><svg width="18" height="18" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
              <line x1="22" y1="2" x2="11" y2="13" />
              <polygon points="22 2 15 22 11 13 2 9 22 2" />
            </svg></span>
          <div style="flex:1;">
            <div style="font-size:12px;font-weight:600;color:var(--purple);">Gửi BĐS này cho khách đang deal</div>
            <div style="font-size:11px;color:var(--text-secondary);">Chọn deal để gửi</div>
          </div>
          <button
            style="padding:6px 12px;background:var(--purple);color:#fff;border-radius:8px;font-size:12px;font-weight:600;"
            onclick="openSendModal()">Gửi</button>
        </div>
      </div>
    </div>

    <!-- BĐS TƯƠNG TỰ -->
    <div class="detail-section" style="padding-bottom:4px;">
      <div class="detail-section-header" style="cursor:default;">
        <div class="detail-section-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14"
              height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
              <polyline points="9 22 9 12 15 12 15 22" />
            </svg> BĐS tương tự</span></div>
      </div>
      <div class="similar-scroll" id="similarScroll">
        <div style="padding:4px 0;font-size:12px;color:var(--text-tertiary);">Đang tải...</div>
      </div>
    </div>

    <div style="height:16px;"></div>
  </div><!-- end detail-scroll -->

  <!-- CTA BOTTOM BAR — mỗi role một bar riêng, quản lý bởi setRole() -->

  <!-- Guest: Quay lại + Đăng ký Broker + Gửi yêu cầu -->
  <div class="crm-action-bar" data-for-role="guest" style="display:none;flex-direction:column;gap:8px;padding-top:8px;">
    <div class="guest-cta-hint"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12"
          height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
          stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
          <path d="M7 11V7a5 5 0 0 1 10 0v4" />
        </svg> Đăng ký để xem địa chỉ & liên hệ trực tiếp</span></div>
    <div style="display:flex;gap:8px;">
      <button class="crm-secondary-btn crm-secondary-btn--primary" onclick="closeDetail()" title="Quay lại"><svg
          width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6" />
        </svg></button>
      <button class="crm-primary-btn" onclick="openBrokerRegisterSheet()"><span
          style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="8.5" cy="7" r="4" />
            <line x1="20" y1="8" x2="20" y2="14" />
            <line x1="23" y1="11" x2="17" y2="11" />
          </svg> Đăng ký</span></button>
      <button class="crm-primary-btn crm-outline-btn"
        onclick="window.location.href='/webapp/add-customer' + (currentDetailPropId ? '?property_id=' + currentDetailPropId : '')"><span
          style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13" />
            <polygon points="22 2 15 22 11 13 2 9 22 2" />
          </svg> Gửi yêu cầu</span></button>
    </div>
  </div>

  <!-- Broker: Quay lại + Gửi yêu cầu -->
  <div class="crm-action-bar" data-for-role="broker" style="display:none;">
    <div class="crm-action-secondary">
      <button class="crm-secondary-btn crm-secondary-btn--primary" onclick="closeDetail()" title="Quay lại"><svg
          width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6" />
        </svg></button>
    </div>
    <button class="crm-primary-btn"
      onclick="window.location.href='/webapp/add-customer' + (currentDetailPropId ? '?property_id=' + currentDetailPropId : '')"><span
        style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
          <circle cx="8.5" cy="7" r="4" />
          <line x1="20" y1="8" x2="20" y2="14" />
          <line x1="23" y1="11" x2="17" y2="11" />
        </svg> Thêm Lead / Khách mới</span></button>
  </div>

  <!-- BĐS Admin: Quay lại + Duyệt BĐS -->
  <div class="crm-action-bar" data-for-role="bds_admin" style="display:none;">
    <div class="crm-action-secondary">
      <button class="crm-secondary-btn crm-secondary-btn--primary" onclick="closeDetail()" title="Quay lại"><svg
          width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6" />
        </svg></button>
    </div>
    <button class="crm-primary-btn amber" onclick="approveProperty()"><span
        style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12" />
        </svg> Duyệt BĐS</span></button>
  </div>

  <!-- Sale: Quay lại + Gửi cho khách (icon) + Đặt lịch xem + Gọi chủ nhà -->
  <div class="crm-action-bar" data-for-role="sale" style="display:none;">
    <div class="crm-action-secondary">
      <button class="crm-secondary-btn crm-secondary-btn--primary" onclick="closeDetail()" title="Quay lại"><svg
          width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6" />
        </svg></button>
      <button class="crm-secondary-btn" onclick="openSendModal()" title="Gửi cho khách"><svg width="18" height="18"
          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
          stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
          <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg></button>
    </div>
    <button class="crm-primary-btn green" onclick="openBookingForm()"><span
        style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
          <line x1="16" y1="2" x2="16" y2="6" />
          <line x1="8" y1="2" x2="8" y2="6" />
          <line x1="3" y1="10" x2="21" y2="10" />
        </svg> Đặt lịch xem</span></button>
    <button class="crm-primary-btn" onclick="callOwner()"><span
        style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <path
            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
        </svg> Gọi chủ nhà</span></button>
  </div>

  <!-- Sale Admin: Quay lại + Giao cho Sale + Gọi chủ nhà -->
  <div class="crm-action-bar" data-for-role="sale_admin" style="display:none;">
    <div class="crm-action-secondary">
      <button class="crm-secondary-btn crm-secondary-btn--primary" onclick="closeDetail()" title="Quay lại"><svg
          width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6" />
        </svg></button>
    </div>
    <button class="crm-primary-btn purple" onclick="openAssignSaleModal()"><span
        style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
          <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg> Giao cho Sale</span></button>
    <button class="crm-primary-btn" onclick="callOwner()"><span
        style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <path
            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
        </svg> Gọi chủ nhà</span></button>
  </div>

  <!-- Admin: Quay lại + Duyệt (icon) + Gửi cho khách (icon) + Đặt lịch xem + Gọi chủ nhà -->
  <div class="crm-action-bar" data-for-role="admin" style="display:none;">
    <div class="crm-action-secondary">
      <button class="crm-secondary-btn crm-secondary-btn--primary" onclick="closeDetail()" title="Quay lại"><svg
          width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6" />
        </svg></button>
      <button class="crm-secondary-btn crm-secondary-btn--amber" onclick="approveProperty()" title="Duyệt BĐS"><svg
          width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
          stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12" />
        </svg></button>
      <button class="crm-second0ary-btn" onclick="openSendModal()" title="Gửi cho khách"><svg width="18" height="18"
          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
          stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
          <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg></button>
    </div>
    <button class="crm-primary-btn green" onclick="openBookingForm()"><span
        style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
          <line x1="16" y1="2" x2="16" y2="6" />
          <line x1="8" y1="2" x2="8" y2="6" />
          <line x1="3" y1="10" x2="21" y2="10" />
        </svg> Đặt lịch xem</span></button>
    <button class="crm-primary-btn" onclick="callOwner()"><span
        style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <path
            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
        </svg> Gọi chủ nhà</span></button>
  </div>

  <!-- BROKER REGISTER — phone verification notice -->
  <div id="brokerRegisterOverlay"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:650;align-items:flex-end;justify-content:center;">
    <div style="background:var(--bg-card);border-radius:20px 20px 0 0;padding:8px 0 32px;width:100%;max-width:430px;">
      <div style="width:36px;height:4px;background:var(--border);border-radius:2px;margin:0 auto 16px;"></div>
      <div style="padding:0 20px 16px;border-bottom:1px solid var(--border);">
        <div
          style="font-size:16px;font-weight:700;color:var(--text-primary);margin-bottom:4px;display:flex;align-items:center;gap:6px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.7"
            stroke-linecap="round" stroke-linejoin="round">
            <path
              d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.96a16 16 0 0 0 6.13 6.13l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
          </svg> Xác minh số điện thoại
        </div>
        <div style="font-size:13px;color:var(--text-secondary);line-height:1.5;">
          Để trở thành Broker và xem đầy đủ thông tin BĐS, bạn cần xác minh số điện thoại trong hồ sơ cá nhân.
        </div>
      </div>
      <div style="padding:16px 20px 0;display:flex;gap:10px;">
        <button onclick="closeBrokerRegisterSheet()"
          style="flex:1;padding:11px;border-radius:12px;border:1.5px solid var(--border);background:none;color:var(--text-secondary);font-size:14px;font-weight:600;cursor:pointer;">Để
          sau</button>
        <button onclick="closeBrokerRegisterSheet();goTo('profile')"
          style="flex:2;padding:11px;border-radius:12px;border:none;background:var(--primary);color:#fff;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6" />
          </svg> Xác minh ngay
        </button>
      </div>
    </div>
  </div>

  <!-- BOOKING MINI FORM -->
  <div id="bookingFormOverlay"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:650;align-items:flex-end;justify-content:center;">
    <div style="background:var(--bg-card);border-radius:20px 20px 0 0;padding:8px 0 32px;width:100%;max-width:430px;">
      <div style="width:36px;height:4px;background:var(--border);border-radius:2px;margin:0 auto 16px;"></div>
      <div
        style="font-size:16px;font-weight:700;padding:0 20px 14px;border-bottom:1px solid var(--border);color:var(--text-primary);display:flex;align-items:center;gap:6px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
          stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
          <line x1="16" y1="2" x2="16" y2="6" />
          <line x1="8" y1="2" x2="8" y2="6" />
          <line x1="3" y1="10" x2="21" y2="10" />
        </svg> Đặt lịch xem nhà
      </div>
      <div style="padding:16px 20px;">
        <div
          style="font-size:12px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.04em;">
          BĐS</div>
        <div
          style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:14px;padding:10px 12px;background:var(--bg-secondary);border-radius:var(--radius-sm);"
          id="bookingPropName">--</div>
        <div
          style="font-size:12px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;text-transform:uppercase;letter-spacing:0.04em;">
          Khách hàng</div>
        <select class="dt-input" style="margin-bottom:12px;">
          <option>Chọn Deal / Khách hàng...</option>
        </select>
        <div class="booking-datetime">
          <input type="date" class="dt-input">
          <input type="time" class="dt-input" value="09:00">
        </div>
        <textarea class="dt-input" rows="2" placeholder="Ghi chú cho chủ nhà / khách..."
          style="resize:none;margin-bottom:14px;"></textarea>
        <div style="display:flex;gap:10px;">
          <button
            style="flex:1;padding:13px;border:1.5px solid var(--border);border-radius:var(--radius-md);font-size:14px;color:var(--text-secondary);"
            onclick="closeBookingForm()">Hủy</button>
          <button
            style="flex:2;padding:13px;background:var(--success);color:#fff;border-radius:var(--radius-md);font-size:14px;font-weight:700;"
            onclick="confirmBooking()">✓ Tạo lịch hẹn</button>
        </div>
      </div>
    </div>
  </div>

</div><!-- end page-detail -->

<!-- SEND TO CUSTOMER MODAL -->
<div class="send-modal-overlay" id="sendModalOverlay">
  <div class="send-modal">
    <div class="send-modal-handle"></div>
    <div class="send-modal-title"><span style="display:inline-flex;align-items:center;gap:6px;"><svg width="16"
          height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
          stroke-linejoin="round">
          <line x1="22" y1="2" x2="11" y2="13" />
          <polygon points="22 2 15 22 11 13 2 9 22 2" />
        </svg> Gửi BĐS cho khách đang Deal</span></div>
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
    </div>
    <button class="send-confirm-btn" onclick="confirmSend()" id="sendConfirmBtn" disabled style="opacity:0.4;">Gửi BĐS
      cho khách đã chọn</button>
    <button onclick="closeSendModal()"
      style="display:block;width:calc(100% - 40px);margin:10px 20px 0;padding:12px;border:1px solid var(--border);border-radius:var(--radius-md);font-size:14px;color:var(--text-secondary);">Hủy</button>
  </div>
</div>

<!-- SHARE SHEET OVERLAY -->
<div class="send-modal-overlay" id="shareSheetOverlay">
  <div class="send-modal">
    <div class="send-modal-handle"></div>
    <div class="send-modal-title"><span style="display:inline-flex;align-items:center;gap:6px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
          stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" />
          <polyline points="16 6 12 2 8 6" />
          <line x1="12" y1="2" x2="12" y2="15" />
        </svg> Chia se BDS</span>
    </div>
    <!-- Option 1: Copy link -->
    <div class="deal-pick-item" onclick="copyPropertyShareLink()" style="cursor:pointer;">
      <div
        style="width:40px;height:40px;border-radius:12px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.7"
          stroke-linecap="round" stroke-linejoin="round">
          <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
          <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
        </svg>
      </div>
      <div style="flex:1;min-width:0;">
        <div class="deal-pick-name">Copy link chia se BDS</div>
        <div class="deal-pick-meta">Sao chep link de gui cho bat ky ai</div>
      </div>
    </div>
    <!-- Option 2: Gui cho khach (chi sale/admin thay) -->
    <div class="deal-pick-item role-sale role-sale_admin role-bds_admin role-admin" onclick="openSendFromShare()"
      style="cursor:pointer;">
      <div
        style="width:40px;height:40px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.7"
          stroke-linecap="round" stroke-linejoin="round">
          <line x1="22" y1="2" x2="11" y2="13" />
          <polygon points="22 2 15 22 11 13 2 9 22 2" />
        </svg>
      </div>
      <div style="flex:1;min-width:0;">
        <div class="deal-pick-name">Gui BDS cho khach</div>
        <div class="deal-pick-meta">Chon deal de gui BDS nay cho khach</div>
      </div>
    </div>
    <button onclick="closeShareSheet()"
      style="display:block;width:calc(100% - 40px);margin:10px 20px 0;padding:12px;border:1px solid var(--border);border-radius:var(--radius-md);font-size:14px;color:var(--text-secondary);">Huy</button>
  </div>
</div>

<!-- TOAST (id renamed to avoid conflict with global #toast in partials/toast.blade.php) -->
<div class="toast" id="detail-toast">✓ Đã lưu bookmark</div>

<!-- FULL SCREEN MAP MODAL -->
<div id="fullMapModal" style="display:none;position:fixed;inset:0;background:#fff;z-index:9999;flex-direction:column;">
  <div
    style="height:56px;display:flex;align-items:center;padding:0 16px;border-bottom:1px solid #e5e7eb;background:#fff;position:relative;z-index:10;">
    <button onclick="closeFullMap()" style="padding:8px;margin-left:-8px;background:none;border:none;outline:none;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
      </svg>
    </button>
    <div style="font-weight:600;font-size:16px;margin-left:8px;">Bản đồ vị trí</div>
  </div>
  <div id="fullMapCanvas" style="flex:1;width:100%;min-height:200px;"></div>
</div>