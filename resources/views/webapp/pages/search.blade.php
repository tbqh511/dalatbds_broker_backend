    <div class="page" id="page-search">

      <!-- Search bar sticky -->
      <div class="search-sticky">
        <div class="search-bar-row">
          <div class="search-box-main" onclick="activateSearch()">
            <span class="srch-ico">🔍</span>
            <span class="srch-placeholder" id="searchPlaceholder">Tìm BĐS, đường, phường...</span>
            <input type="text" id="searchInput" class="srch-input" placeholder="Tìm BĐS, đường, phường..." oninput="onSearchType(this.value)" onfocus="showSuggestions()" style="display:none">
          </div>
          <button class="filter-pill" onclick="openFilterSheet()">
            <span>⚙️</span> Lọc <span class="filter-count-badge" id="filterCount" style="display:none">2</span>
          </button>
        </div>

        <!-- Search mode tabs -->
        <div class="search-mode-tabs" id="searchModeTabs">
          <button class="smt active" onclick="switchMode('bds',this)">🏡 BĐS</button>
          <button class="smt role-sale role-bds_admin role-sale_admin role-admin" onclick="switchMode('lead',this)">🎯 Khách/Lead</button>
          <button class="smt" onclick="switchMode('area',this)">🗺 Khu vực</button>
        </div>
      </div>

      <!-- ---- STATE: DISCOVERY (mặc định khi chưa gõ) ---- -->
      <div id="stateDiscovery">

        <!-- Quick type chips -->
        <div class="filter-bar" style="padding-top:8px;">
          <div class="chip active" onclick="doSearch('Tất cả',this)">Tất cả</div>
          <div class="chip" onclick="doSearch('Đất ở',this)">🌱 Đất ở</div>
          <div class="chip" onclick="doSearch('Nhà phố',this)">🏠 Nhà phố</div>
          <div class="chip" onclick="doSearch('Biệt thự',this)">🏰 Biệt thự</div>
          <div class="chip" onclick="doSearch('Căn hộ',this)">🏢 Căn hộ</div>
          <div class="chip" onclick="doSearch('Khách sạn',this)">🏨 Khách sạn</div>
        </div>

        <!-- Price filter quick -->
        <div class="filter-bar" style="padding-top:0;">
          <div class="chip" onclick="doSearch('Dưới 1 tỷ',this)">Dưới 1 tỷ</div>
          <div class="chip" onclick="doSearch('1–3 tỷ',this)">1–3 tỷ</div>
          <div class="chip" onclick="doSearch('3–5 tỷ',this)">3–5 tỷ</div>
          <div class="chip" onclick="doSearch('Trên 5 tỷ',this)">Trên 5 tỷ</div>
        </div>

        <div style="padding:4px 16px 0;">
          <!-- Recent searches -->
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
            <span class="recent-label" style="margin:0;">🕐 Tìm kiếm gần đây</span>
            <button style="font-size:11px;color:var(--primary);background:none;border:none;cursor:pointer;">Xóa tất cả</button>
          </div>
          <div class="recent-item" onclick="doSearch('Đường Yersin, Cam Ly')"><span class="ri">🕐</span><span style="flex:1">Đường Yersin, Cam Ly</span><span style="color:var(--text-tertiary);font-size:13px;">↗</span></div>
          <div class="recent-item" onclick="doSearch('Biệt thự Lâm Viên')"><span class="ri">🕐</span><span style="flex:1">Biệt thự Lâm Viên</span><span style="color:var(--text-tertiary);font-size:13px;">↗</span></div>
          <div class="recent-item" onclick="doSearch('Đất mặt tiền 3/4')"><span class="ri">🕐</span><span style="flex:1">Đất mặt tiền 3/4</span><span style="color:var(--text-tertiary);font-size:13px;">↗</span></div>
          <div class="recent-item" onclick="doSearch('Nhà phố dưới 2 tỷ')"><span class="ri">🕐</span><span style="flex:1">Nhà phố dưới 2 tỷ</span><span style="color:var(--text-tertiary);font-size:13px;">↗</span></div>
        </div>

        <!-- Popular areas -->
        <div style="padding:16px 16px 6px;">
          <div class="recent-label" style="margin-bottom:10px;">📍 Khu vực hot</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
            <div class="area-card" onclick="doSearch('P.Cam Ly')">
              <div class="area-card-img" style="background:linear-gradient(135deg,#1e3a5f,#2d6a4f);">🌿</div>
              <div class="area-card-name">P. Cam Ly</div>
              <div class="area-card-count">34 tin • 28.5tr/m²</div>
            </div>
            <div class="area-card" onclick="doSearch('P.Lâm Viên')">
              <div class="area-card-img" style="background:linear-gradient(135deg,#7c3aed,#4f46e5);">🏔</div>
              <div class="area-card-name">P. Lâm Viên</div>
              <div class="area-card-count">21 tin • 42.1tr/m²</div>
            </div>
            <div class="area-card" onclick="doSearch('Đường 3/4')">
              <div class="area-card-img" style="background:linear-gradient(135deg,#b45309,#d97706);">🛣</div>
              <div class="area-card-name">Đường 3/4</div>
              <div class="area-card-count">18 tin • 35.7tr/m²</div>
            </div>
            <div class="area-card" onclick="doSearch('Hồ Xuân Hương')">
              <div class="area-card-img" style="background:linear-gradient(135deg,#0284c7,#0ea5e9);">💧</div>
              <div class="area-card-name">Hồ Xuân Hương</div>
              <div class="area-card-count">12 tin • 55tr/m²</div>
            </div>
          </div>
        </div>
      </div>

      <!-- ---- STATE: SUGGESTIONS (đang gõ) ---- -->
      <div id="stateSuggestions" style="display:none;">
        <div style="padding:8px 16px 0;">
          <div class="suggest-item" onclick="doSearch('Đường Yersin, Phường Cam Ly')">
            <span class="suggest-icon">📍</span>
            <div class="suggest-body">
              <div class="suggest-title">Đường <strong>Yer</strong>sin</div>
              <div class="suggest-sub">Phường Cam Ly · 8 tin</div>
            </div>
            <span class="suggest-type badge badge-blue">Đường</span>
          </div>
          <div class="suggest-item" onclick="doSearch('Phường Cam Ly')">
            <span class="suggest-icon">🏘</span>
            <div class="suggest-body">
              <div class="suggest-title">Phường Cam Ly</div>
              <div class="suggest-sub">34 bất động sản</div>
            </div>
            <span class="suggest-type badge badge-teal">Phường</span>
          </div>
          <div class="suggest-item" onclick="doSearch('Đất ở Cam Ly')">
            <span class="suggest-icon">🌱</span>
            <div class="suggest-body">
              <div class="suggest-title">Đất ở · Cam Ly</div>
              <div class="suggest-sub">Khoảng 15 kết quả</div>
            </div>
            <span class="suggest-type badge badge-green">BĐS</span>
          </div>
          <div class="suggest-item" onclick="doSearch('Yersin nhà phố')">
            <span class="suggest-icon">🏠</span>
            <div class="suggest-body">
              <div class="suggest-title">Nhà phố Yersin</div>
              <div class="suggest-sub">Khoảng 6 kết quả</div>
            </div>
            <span class="suggest-type badge badge-amber">BĐS</span>
          </div>
        </div>
      </div>

      <!-- ---- STATE: KẾT QUẢ TÌM KIẾM ---- -->
      <div id="stateResults" style="display:none;">

        <!-- Result header bar -->
        <div class="result-header">
          <div class="result-meta">
            <span class="result-query" id="resultQuery">Đường Yersin, Cam Ly</span>
            <span class="result-count" id="resultCount">128 kết quả</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;">
            <div class="sort-btn" onclick="openSortSheet()">
              <span>↕</span> Sắp xếp
            </div>
            <div class="view-toggle">
              <button class="view-btn active" id="viewList" onclick="switchView('list')">☰</button>
              <button class="view-btn" id="viewMap" onclick="switchView('map')">🗺</button>
            </div>
          </div>
        </div>

        <!-- Active filters row -->
        <div class="active-filters" id="activeFilters">
          <div class="af-chip">Đất ở <span onclick="removeFilter(this)">×</span></div>
          <div class="af-chip">1–3 tỷ <span onclick="removeFilter(this)">×</span></div>
          <div class="af-chip">Sổ đỏ <span onclick="removeFilter(this)">×</span></div>
          <button class="af-clear" onclick="clearFilters()">Xóa bộ lọc</button>
        </div>

        <!-- LIST VIEW -->
        <div id="listView">

          <!-- Result card 1 — compact horizontal -->
          <div class="result-card" onclick="">
            <div class="rc-img img-prop1"><div style="display:flex;align-items:center;justify-content:center;height:100%;color:rgba(255,255,255,0.4);font-size:32px;">🏠</div></div>
            <div class="rc-body">
              <div class="rc-tags">
                <span class="badge badge-blue" style="font-size:9px;padding:2px 6px;">Đất ở</span>
                <span class="badge badge-green" style="font-size:9px;padding:2px 6px;">Sổ đỏ</span>
              </div>
              <div class="rc-title">Đất phân quyền Đường Yersin, P.Cam Ly</div>
              <div class="rc-price">1,000 triệu</div>
              <div class="rc-meta">
                <span>📐 250m²</span>
                <span>📍 <span class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">Đường Yersin</span><span class="role-guest">P.Cam Ly</span></span>
              </div>
              <div class="rc-footer">
                <span class="rc-time">2 ngày trước</span>
                <div style="display:flex;gap:6px;">
                  <button class="rc-btn">❤️</button>
                  <button class="rc-btn role-sale role-bds_admin role-sale_admin role-admin" style="background:var(--primary-light);color:var(--primary);">🤝 Chăm</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Result card 2 -->
          <div class="result-card">
            <div class="rc-img img-prop3"><div style="display:flex;align-items:center;justify-content:center;height:100%;color:rgba(255,255,255,0.4);font-size:32px;">🏢</div></div>
            <div class="rc-body">
              <div class="rc-tags">
                <span class="badge badge-amber" style="font-size:9px;padding:2px 6px;">Nhà phố</span>
                <span class="badge badge-purple" style="font-size:9px;padding:2px 6px;">Thương lượng</span>
              </div>
              <div class="rc-title">Nhà mặt tiền Trần Phú gần chợ</div>
              <div class="rc-price">2,800 triệu</div>
              <div class="rc-meta">
                <span>🏠 4PN · 3WC</span>
                <span>📐 120m²</span>
              </div>
              <div class="rc-footer">
                <span class="rc-time">5 ngày trước</span>
                <div style="display:flex;gap:6px;">
                  <button class="rc-btn">❤️</button>
                  <button class="rc-btn role-sale role-bds_admin role-sale_admin role-admin" style="background:var(--primary-light);color:var(--primary);">🤝 Chăm</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Featured card — big -->
          <div class="featured-label">⭐ Nổi bật</div>
          <div class="result-card-big">
            <div class="rcb-img img-prop2">
              <div style="display:flex;align-items:center;justify-content:center;height:100%;color:rgba(255,255,255,0.4);font-size:48px;">🏡</div>
              <div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,rgba(0,0,0,0.6));"></div>
              <div style="position:absolute;top:10px;left:10px;display:flex;gap:6px;">
                <span class="badge badge-purple">Biệt thự</span>
                <span class="badge" style="background:rgba(255,200,0,0.9);color:#7c3900;">⭐ Nổi bật</span>
              </div>
              <div style="position:absolute;bottom:10px;left:12px;color:#fff;">
                <div style="font-size:18px;font-weight:700;">8,500 triệu</div>
                <div style="font-size:11px;opacity:0.85;">Thương lượng được</div>
              </div>
              <button style="position:absolute;top:10px;right:10px;width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,0.85);display:flex;align-items:center;justify-content:center;font-size:16px;">❤️</button>
            </div>
            <div class="rcb-body">
              <div class="rcb-title">Biệt thự view đồi chè Cầu Đất, toàn cảnh thung lũng</div>
              <div class="rcb-location">📍 <span class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">Xã Xuân Trường, huyện Đà Lạt</span><span class="role-guest">Ngoại ô Đà Lạt</span></div>
              <div class="rcb-specs">
                <div class="rcb-spec-item"><div class="rcb-spec-val">5</div><div class="rcb-spec-lbl">Phòng ngủ</div></div>
                <div class="rcb-spec-item"><div class="rcb-spec-val">580</div><div class="rcb-spec-lbl">m² đất</div></div>
                <div class="rcb-spec-item"><div class="rcb-spec-val">Sổ đỏ</div><div class="rcb-spec-lbl">Pháp lý</div></div>
                <div class="rcb-spec-item"><div class="rcb-spec-val">ĐN</div><div class="rcb-spec-lbl">Hướng</div></div>
              </div>
              <div class="rcb-footer">
                <div>
                  <div style="font-size:10px;color:var(--text-tertiary);">Đăng bởi</div>
                  <div style="font-size:12px;font-weight:600;color:var(--text-primary);">Nguyễn Broker ⭐4.8</div>
                </div>
                <div style="display:flex;gap:8px;">
                  <button class="rc-btn" style="padding:0 14px;border-radius:8px;">📞</button>
                  <button class="rc-btn role-sale role-bds_admin role-sale_admin role-admin" style="padding:0 14px;border-radius:8px;background:var(--primary);color:#fff;border:none;">🤝 Chăm</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Result card 3 -->
          <div class="result-card">
            <div class="rc-img img-prop4"><div style="display:flex;align-items:center;justify-content:center;height:100%;color:rgba(255,255,255,0.4);font-size:32px;">🌿</div></div>
            <div class="rc-body">
              <div class="rc-tags">
                <span class="badge badge-blue" style="font-size:9px;padding:2px 6px;">Đất ở</span>
              </div>
              <div class="rc-title">Lô đất view hồ Tuyền Lâm, Phường 4</div>
              <div class="rc-price">3,200 triệu</div>
              <div class="rc-meta">
                <span>📐 400m²</span>
                <span>⚖️ Sổ đỏ</span>
              </div>
              <div class="rc-footer">
                <span class="rc-time">1 tuần trước</span>
                <div style="display:flex;gap:6px;">
                  <button class="rc-btn">❤️</button>
                  <button class="rc-btn role-sale role-bds_admin role-sale_admin role-admin" style="background:var(--primary-light);color:var(--primary);">🤝 Chăm</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Result card 4 -->
          <div class="result-card">
            <div class="rc-img" style="background:linear-gradient(135deg,#4a1942,#7c3aed);"><div style="display:flex;align-items:center;justify-content:center;height:100%;color:rgba(255,255,255,0.4);font-size:32px;">🏨</div></div>
            <div class="rc-body">
              <div class="rc-tags">
                <span class="badge badge-purple" style="font-size:9px;padding:2px 6px;">Khách sạn</span>
                <span class="badge badge-red" style="font-size:9px;padding:2px 6px;">Hot</span>
              </div>
              <div class="rc-title">Khách sạn 20 phòng khu Hòa Bình</div>
              <div class="rc-price">15,000 triệu</div>
              <div class="rc-meta">
                <span>🛏 20 phòng</span>
                <span>📐 350m²</span>
              </div>
              <div class="rc-footer">
                <span class="rc-time">3 ngày trước</span>
                <div style="display:flex;gap:6px;">
                  <button class="rc-btn">❤️</button>
                  <button class="rc-btn role-sale role-bds_admin role-sale_admin role-admin" style="background:var(--primary-light);color:var(--primary);">🤝 Chăm</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Load more -->
          <div style="padding:16px;text-align:center;">
            <button style="padding:11px 28px;border:1.5px solid var(--border);border-radius:20px;font-size:13px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);" onclick="loadMore(this)">Xem thêm kết quả</button>
          </div>

        </div><!-- end listView -->

        <!-- MAP VIEW (fake) -->
        <div id="mapView" style="display:none;">
          <div style="height:420px;background:linear-gradient(180deg,#e8f4f0 0%,#d1e8e0 100%);position:relative;overflow:hidden;margin:0 0 0 0;">
            <!-- Fake map grid -->
            <div style="position:absolute;inset:0;opacity:0.3;">
              <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#aac" stroke-width="0.5"/></pattern></defs>
                <rect width="100%" height="100%" fill="url(#grid)"/>
                <!-- Fake roads -->
                <line x1="0" y1="180" x2="430" y2="160" stroke="#fff" stroke-width="6" opacity="0.8"/>
                <line x1="0" y1="280" x2="430" y2="260" stroke="#fff" stroke-width="4" opacity="0.7"/>
                <line x1="120" y1="0" x2="100" y2="420" stroke="#fff" stroke-width="5" opacity="0.8"/>
                <line x1="280" y1="0" x2="260" y2="420" stroke="#fff" stroke-width="3" opacity="0.6"/>
                <!-- Fake blocks -->
                <rect x="20" y="60" width="80" height="100" rx="4" fill="#c8ddd5" opacity="0.7"/>
                <rect x="140" y="40" width="100" height="120" rx="4" fill="#c8ddd5" opacity="0.7"/>
                <rect x="300" y="70" width="110" height="80" rx="4" fill="#c8ddd5" opacity="0.7"/>
                <rect x="20" y="210" width="75" height="60" rx="4" fill="#c8ddd5" opacity="0.7"/>
                <rect x="140" y="200" width="90" height="50" rx="4" fill="#c8ddd5" opacity="0.7"/>
                <rect x="290" y="195" width="120" height="55" rx="4" fill="#c8ddd5" opacity="0.7"/>
                <rect x="30" y="310" width="60" height="80" rx="4" fill="#c8ddd5" opacity="0.7"/>
                <rect x="140" y="300" width="100" height="90" rx="4" fill="#c8ddd5" opacity="0.7"/>
                <rect x="290" y="305" width="115" height="85" rx="4" fill="#c8ddd5" opacity="0.7"/>
              </svg>
            </div>
            <!-- Price pins -->
            <div class="map-pin" style="left:60px;top:130px;" onclick="showMapCard(0)">1,000tr</div>
            <div class="map-pin active" style="left:170px;top:90px;" onclick="showMapCard(1)">2,800tr</div>
            <div class="map-pin" style="left:310px;top:120px;" onclick="showMapCard(2)">3,200tr</div>
            <div class="map-pin featured" style="left:55px;top:240px;" onclick="showMapCard(3)">8,500tr ⭐</div>
            <div class="map-pin" style="left:185px;top:250px;" onclick="showMapCard(4)">1,500tr</div>
            <div class="map-pin" style="left:300px;top:230px;" onclick="showMapCard(5)">15,000tr</div>
            <div class="map-pin" style="left:90px;top:340px;" onclick="showMapCard(6)">650tr</div>
            <div class="map-pin" style="left:220px;top:330px;" onclick="showMapCard(7)">4,200tr</div>

            <!-- Map controls -->
            <div style="position:absolute;top:12px;right:12px;display:flex;flex-direction:column;gap:6px;">
              <button style="width:34px;height:34px;background:#fff;border:none;border-radius:8px;font-size:18px;box-shadow:0 2px 8px rgba(0,0,0,0.15);cursor:pointer;">＋</button>
              <button style="width:34px;height:34px;background:#fff;border:none;border-radius:8px;font-size:18px;box-shadow:0 2px 8px rgba(0,0,0,0.15);cursor:pointer;">－</button>
            </div>
            <button style="position:absolute;top:12px;left:12px;background:#fff;border:none;border-radius:20px;padding:6px 12px;font-size:12px;font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,0.12);cursor:pointer;display:flex;align-items:center;gap:5px;">📍 Vị trí của tôi</button>
            <div style="position:absolute;bottom:12px;left:50%;transform:translateX(-50%);background:#fff;border-radius:20px;padding:6px 14px;font-size:11px;font-weight:600;color:var(--text-secondary);box-shadow:0 2px 8px rgba(0,0,0,0.12);">8 bất động sản trong khu vực này</div>
          </div>

          <!-- Map bottom card -->
          <div class="map-bottom-card" id="mapBottomCard">
            <div class="mbc-handle"></div>
            <div style="display:flex;gap:12px;padding:0 4px;">
              <div style="width:70px;height:70px;border-radius:10px;overflow:hidden;flex-shrink:0;">
                <div class="img-prop3" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:28px;color:rgba(255,255,255,0.4);">🏢</div>
              </div>
              <div style="flex:1;min-width:0;">
                <div style="display:flex;gap:5px;margin-bottom:4px;">
                  <span class="badge badge-amber" style="font-size:9px;">Nhà phố</span>
                  <span class="badge badge-purple" style="font-size:9px;">Thương lượng</span>
                </div>
                <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:2px;line-height:1.3;">Nhà mặt tiền Trần Phú gần chợ Đà Lạt</div>
                <div style="font-size:14px;font-weight:700;color:var(--primary);">2,800 triệu</div>
                <div style="font-size:11px;color:var(--text-secondary);margin-top:2px;">📐 120m² · 🏠 4PN · ⚖️ Sổ hồng</div>
              </div>
            </div>
            <div style="display:flex;gap:8px;margin-top:12px;">
              <button style="flex:1;padding:10px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);">Xem chi tiết</button>
              <button style="flex:1;padding:10px;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#fff;background:var(--primary);" class="role-sale role-bds_admin role-sale_admin role-admin">🤝 Chăm sóc</button>
              <button style="flex:1;padding:10px;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#fff;background:var(--primary);" class="role-guest role-broker">📞 Liên hệ</button>
            </div>
          </div>
        </div><!-- end mapView -->

      </div><!-- end stateResults -->

    </div><!-- end page-search -->

