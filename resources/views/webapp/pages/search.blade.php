  <div class="page" id="page-search">
  
    <style>
      .search-back-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: none;
        background: var(--primary);
        color: #fff;
        cursor: pointer;
        border-radius: 20px;
        flex-shrink: 0;
        transition: opacity 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      }
      .search-back-btn:hover {
        opacity: 0.9;
      }
      .rc-btn {
        background: transparent !important;
      }
      .rc-btn svg {
        stroke: var(--primary);
        fill: none;
        transition: stroke 0.2s, fill 0.2s;
      }
      .rc-btn.liked svg {
        stroke: var(--primary);
        fill: var(--primary);
      }
    </style>

    <!-- Search bar sticky -->
    <div class="search-sticky">
      <div class="search-bar-row">
        <button onclick="document.getElementById('stateDiscovery').style.display === 'none' ? resetSearch(event, true) : goTo('home')" class="search-back-btn">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div class="search-box-main" onclick="activateSearch()" style="flex:1;display:flex;align-items:center;position:relative;">
          <span class="srch-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></span>
          <span class="srch-placeholder" id="searchPlaceholder" style="flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;padding-right:24px;">Tìm BĐS, đường, phường...</span>
          <input type="text" id="searchInput" class="srch-input" placeholder="Tìm BĐS, đường, phường..." oninput="onSearchType(this.value)" onfocus="showSuggestions()" style="display:none;padding-right:32px;">
          <!-- Nút X (Clear Input) -->
          <button id="clearSearchBtn" onclick="resetSearch(event)" style="display:none;position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-tertiary);padding:4px;cursor:pointer;border-radius:50%;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <button class="filter-pill" onclick="openFilterSheet()">
          <span style="display:inline-flex;align-items:center;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg></span> Lọc <span class="filter-count-badge" id="filterCount" style="display:none">2</span>
        </button>
      </div>

    </div>

    <!-- ---- STATE: DISCOVERY (mặc định khi chưa gõ) ---- -->
    <div id="stateDiscovery">

      <!-- Quick type chips — lấy từ DB -->
      <div class="filter-bar" style="padding-top:8px;">
        <div class="chip active" onclick="doSearchCategory('',this)">Tất cả</div>
        @foreach($categories as $cat)
        <div class="chip" onclick="doSearchCategory('{{ $cat->category }}',this)">{{ $cat->category }}</div>
        @endforeach
      </div>

      <!-- Price filter quick -->
      <div class="filter-bar" style="padding-top:0;">
        <div class="chip active" onclick="doSearchPrice('',this)">Tất cả</div>
        <div class="chip" onclick="doSearchPrice('Dưới 1 tỷ',this)">Dưới 1 tỷ</div>
        <div class="chip" onclick="doSearchPrice('1–2 tỷ',this)">1–2 tỷ</div>
        <div class="chip" onclick="doSearchPrice('2–3 tỷ',this)">2–3 tỷ</div>
        <div class="chip" onclick="doSearchPrice('3–5 tỷ',this)">3–5 tỷ</div>
        <div class="chip" onclick="doSearchPrice('5–7 tỷ',this)">5–7 tỷ</div>
        <div class="chip" onclick="doSearchPrice('7–10 tỷ',this)">7–10 tỷ</div>
        <div class="chip" onclick="doSearchPrice('Trên 10 tỷ',this)">Trên 10 tỷ</div>
      </div>

      <!-- Location filter quick -->
      <div class="filter-bar" style="padding-top:0;">
        <div class="chip active" onclick="doSearchLocation('',this)">Tất cả</div>
        @php
          $hotWards = \App\Models\LocationsWard::where('district_code', config('location.district_code'))
            ->orderByRaw("FIELD(code, '24796', '24790', '24778', '24769', '24811')")
            ->get();
        @endphp
        @foreach($hotWards as $w)
        <div class="chip" onclick="doSearchLocation('{{ trim($w->full_name) }}',this)">{{ trim($w->full_name) }}</div>
        @endforeach
      </div>

      <div style="padding:4px 16px 0;">
        <!-- Recent searches (dynamic from localStorage) -->
        <div id="recentSearchesSection">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
            <span class="recent-label" style="margin:0;display:inline-flex;align-items:center;gap:5px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Tìm kiếm gần đây</span>
            <button onclick="clearRecentSearches()" style="font-size:11px;color:var(--primary);background:none;border:none;cursor:pointer;">Xóa tất cả</button>
          </div>
          <div id="recentSearchesList"></div>
        </div>
      </div>
    </div>

    <!-- ---- STATE: SUGGESTIONS (đang gõ) ---- -->
    <div id="stateSuggestions" style="display:none;">
      <div style="padding:8px 16px 0;">
        <div class="suggest-item" onclick="doSearch('Đường Yersin, Phường Cam Ly')">
          <span class="suggest-icon"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
          <div class="suggest-body">
            <div class="suggest-title">Đường <strong>Yer</strong>sin</div>
            <div class="suggest-sub">Phường Cam Ly · 8 tin</div>
          </div>
          <span class="suggest-type badge badge-blue">Đường</span>
        </div>
        <div class="suggest-item" onclick="doSearch('Phường Cam Ly')">
          <span class="suggest-icon"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
          <div class="suggest-body">
            <div class="suggest-title">Phường Cam Ly</div>
            <div class="suggest-sub">34 bất động sản</div>
          </div>
          <span class="suggest-type badge badge-teal">Phường</span>
        </div>
        <div class="suggest-item" onclick="doSearch('Đất ở Cam Ly')">
          <span class="suggest-icon"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4.97-5 9-8.58 9-12a9 9 0 0 0-18 0c0 3.42 4.03 7 9 12z"/><circle cx="12" cy="10" r="3"/></svg></span>
          <div class="suggest-body">
            <div class="suggest-title">Đất ở · Cam Ly</div>
            <div class="suggest-sub">Khoảng 15 kết quả</div>
          </div>
          <span class="suggest-type badge badge-green">BĐS</span>
        </div>
        <div class="suggest-item" onclick="doSearch('Yersin nhà phố')">
          <span class="suggest-icon"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
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
      <div class="result-header" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:8px;padding-bottom:12px;">
        <div style="display:flex;align-items:center;gap:8px;width:100%;">
          <button onclick="resetSearch(event, true)" class="search-back-btn" style="margin-right:0;flex-shrink:0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
          </button>
          
          <div class="result-meta" style="flex:1;min-width:0;margin-bottom:0;">
            <span class="result-query" id="resultQuery" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">Đường Yersin, Cam Ly</span>
            <span class="result-count" id="resultCount">128 kết quả</span>
          </div>

          <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
            <div class="sort-btn" onclick="openSortSheet()">
              <span>↕</span> Sắp xếp
            </div>
            <div class="view-toggle">
              <button class="view-btn active" id="viewList" onclick="switchView('list')">☰</button>
              <button class="view-btn role-sale role-bds_admin role-sale_admin role-admin" id="viewMap" onclick="switchView('map')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg></button>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick filter chips on search results — TRƯỚC active-filters -->
      <div id="resultsFilterCategory" class="filter-bar" style="padding:8px 16px 4px;">
        <div class="chip active" onclick="doSearchCategory('',this)">Tất cả</div>
        @foreach($categories as $cat)
        <div class="chip" onclick="doSearchCategory('{{ $cat->category }}',this)">{{ $cat->category }}</div>
        @endforeach
      </div>

      <div id="resultsFilterPrice" class="filter-bar" style="padding:4px 16px 4px;">
        <div class="chip active" onclick="doSearchPrice('',this)">Tất cả</div>
        <div class="chip" onclick="doSearchPrice('Dưới 1 tỷ',this)">Dưới 1 tỷ</div>
        <div class="chip" onclick="doSearchPrice('1–2 tỷ',this)">1–2 tỷ</div>
        <div class="chip" onclick="doSearchPrice('2–3 tỷ',this)">2–3 tỷ</div>
        <div class="chip" onclick="doSearchPrice('3–5 tỷ',this)">3–5 tỷ</div>
        <div class="chip" onclick="doSearchPrice('5–7 tỷ',this)">5–7 tỷ</div>
        <div class="chip" onclick="doSearchPrice('7–10 tỷ',this)">7–10 tỷ</div>
        <div class="chip" onclick="doSearchPrice('Trên 10 tỷ',this)">Trên 10 tỷ</div>
      </div>

      <div id="resultsFilterLocation" class="filter-bar" style="padding:4px 16px 4px;">
        <div class="chip active" onclick="doSearchLocation('',this)">Tất cả</div>
        @foreach($hotWards as $w)
        <div class="chip" onclick="doSearchLocation('{{ trim($w->full_name) }}',this)">{{ trim($w->full_name) }}</div>
        @endforeach
      </div>

      <!-- Active filters row — SAU quick chips -->
      <div class="active-filters" id="activeFilters">
        <button class="af-clear" onclick="clearFilters()" style="display:none">Xóa bộ lọc</button>
      </div>

      <!-- LIST VIEW -->
      <div id="listView">

        <!-- Result card 1 — compact horizontal -->
        <div class="result-card" onclick="">
          <div class="rc-img img-prop1"><div style="display:flex;align-items:center;justify-content:center;height:100%;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div></div>
          <div class="rc-body">
            <div class="rc-tags">
              <span class="badge badge-blue" style="font-size:9px;padding:2px 6px;">Đất ở</span>
              <span class="badge badge-green" style="font-size:9px;padding:2px 6px;">Sổ đỏ</span>
            </div>
            <div class="rc-title">Đất phân quyền Đường Yersin, P.Cam Ly</div>
            <div class="rc-price">1,000 triệu</div>
            <div class="rc-meta">
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></span>250m²</span>
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span><span class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">Đường Yersin</span><span class="role-guest">P.Cam Ly</span></span>
            </div>
            <div class="rc-footer">
              <span class="rc-time">2 ngày trước</span>
              <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <button class="rc-btn" style="color: var(--primary);" onclick="toggleBookmark(this, Math.random()); event.stopPropagation();"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
                <!-- Guest: Đăng ký + Gửi yêu cầu -->
                <button class="rc-btn role-guest" style="background:var(--primary);color:#fff;flex:1;min-width:80px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg> Đăng ký</span></button>
                <button class="rc-btn role-guest" style="background:var(--primary-light);color:var(--primary);flex:1;min-width:90px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi</span></button>
                <!-- Broker: Gửi yêu cầu -->
                <button class="rc-btn role-broker" style="background:var(--primary);color:#fff;flex:1;min-width:100px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi yêu cầu</span></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Result card 2 -->
        <div class="result-card">
          <div class="rc-img img-prop3"><div style="display:flex;align-items:center;justify-content:center;height:100%;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/></svg></div></div>
          <div class="rc-body">
            <div class="rc-tags">
              <span class="badge badge-amber" style="font-size:9px;padding:2px 6px;">Nhà phố</span>
              <span class="badge badge-purple" style="font-size:9px;padding:2px 6px;">Thương lượng</span>
            </div>
            <div class="rc-title">Nhà mặt tiền Trần Phú gần chợ</div>
            <div class="rc-price">2,800 triệu</div>
            <div class="rc-meta">
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>4PN · 3WC</span>
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></span>120m²</span>
            </div>
            <div class="rc-footer">
              <span class="rc-time">5 ngày trước</span>
              <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <button class="rc-btn" style="color: var(--primary);" onclick="toggleBookmark(this, Math.random()); event.stopPropagation();"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
                <!-- Guest: Đăng ký + Gửi yêu cầu -->
                <button class="rc-btn role-guest" style="background:var(--primary);color:#fff;flex:1;min-width:80px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg> Đăng ký</span></button>
                <button class="rc-btn role-guest" style="background:var(--primary-light);color:var(--primary);flex:1;min-width:90px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi</span></button>
                <!-- Broker: Gửi yêu cầu -->
                <button class="rc-btn role-broker" style="background:var(--primary);color:#fff;flex:1;min-width:100px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi yêu cầu</span></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Featured card — big -->
        <div class="featured-label"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="#fbbf24" stroke="#fbbf24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Nổi bật</span></div>
        <div class="result-card-big">
          <div class="rcb-img img-prop2">
            <div style="display:flex;align-items:center;justify-content:center;height:100%;"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
            <div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 40%,rgba(0,0,0,0.6));"></div>
            <div style="position:absolute;top:10px;left:10px;display:flex;gap:6px;">
              <span class="badge badge-purple">Biệt thự</span>
              <span class="badge" style="background:rgba(255,200,0,0.9);color:#7c3900;display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="#7c3900" stroke="#7c3900" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Nổi bật</span>
            </div>
            <div style="position:absolute;bottom:10px;left:12px;color:#fff;">
              <div style="font-size:18px;font-weight:700;">8,500 triệu</div>
              <div style="font-size:11px;opacity:0.85;">Thương lượng được</div>
            </div>
            <button style="position:absolute;top:10px;right:10px;width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,0.85);display:flex;align-items:center;justify-content:center;color:var(--primary);" onclick="toggleBookmark(this, Math.random()); event.stopPropagation();"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
          </div>
          <div class="rcb-body">
            <div class="rcb-title">Biệt thự view đồi chè Cầu Đất, toàn cảnh thung lũng</div>
            <div class="rcb-location"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span><span class="role-broker role-bds_admin role-sale role-bds_admin role-sale_admin role-admin">Xã Xuân Trường, huyện Đà Lạt</span><span class="role-guest">Ngoại ô Đà Lạt</span></div>
            <div class="rcb-specs">
              <div class="rcb-spec-item"><div class="rcb-spec-val">5</div><div class="rcb-spec-lbl">Phòng ngủ</div></div>
              <div class="rcb-spec-item"><div class="rcb-spec-val">580</div><div class="rcb-spec-lbl">m² đất</div></div>
              <div class="rcb-spec-item"><div class="rcb-spec-val">Sổ đỏ</div><div class="rcb-spec-lbl">Pháp lý</div></div>
              <div class="rcb-spec-item"><div class="rcb-spec-val">ĐN</div><div class="rcb-spec-lbl">Hướng</div></div>
            </div>
            <div class="rcb-footer">
              <div>
                <div style="font-size:10px;color:var(--text-tertiary);">Đăng bởi</div>
                <div style="font-size:12px;font-weight:600;color:var(--text-primary);">Nguyễn Broker <span style="display:inline-flex;align-items:center;"><svg width="11" height="11" viewBox="0 0 24 24" fill="#fbbf24" stroke="#fbbf24" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></span>4.8</div>
              </div>
              <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <button class="rc-btn" style="padding:0 14px;border-radius:8px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></button>
                <!-- Guest: Đăng ký + Gửi yêu cầu -->
                <button class="rc-btn role-guest" style="flex:1;min-width:80px;border-radius:8px;background:var(--primary);color:#fff;border:none;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg> Đăng ký</span></button>
                <button class="rc-btn role-guest" style="flex:1;min-width:90px;border-radius:8px;background:var(--primary-light);color:var(--primary);border:none;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi</span></button>
                <!-- Broker: Gửi yêu cầu -->
                <button class="rc-btn role-broker" style="flex:1;min-width:100px;border-radius:8px;background:var(--primary);color:#fff;border:none;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi yêu cầu</span></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Result card 3 -->
        <div class="result-card">
          <div class="rc-img img-prop4"><div style="display:flex;align-items:center;justify-content:center;height:100%;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4.97-5 9-8.58 9-12a9 9 0 0 0-18 0c0 3.42 4.03 7 9 12z"/><circle cx="12" cy="10" r="3"/></svg></div></div>
          <div class="rc-body">
            <div class="rc-tags">
              <span class="badge badge-blue" style="font-size:9px;padding:2px 6px;">Đất ở</span>
            </div>
            <div class="rc-title">Lô đất view hồ Tuyền Lâm, Phường 4</div>
            <div class="rc-price">3,200 triệu</div>
            <div class="rc-meta">
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></span>400m²</span>
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 0v4m0 12v4M2 12h4m12 0h4"/></svg></span>Sổ đỏ</span>
            </div>
            <div class="rc-footer">
              <span class="rc-time">1 tuần trước</span>
              <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <button class="rc-btn" style="color: var(--primary);" onclick="toggleBookmark(this, Math.random()); event.stopPropagation();"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
                <!-- Guest: Đăng ký + Gửi yêu cầu -->
                <button class="rc-btn role-guest" style="background:var(--primary);color:#fff;flex:1;min-width:80px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg> Đăng ký</span></button>
                <button class="rc-btn role-guest" style="background:var(--primary-light);color:var(--primary);flex:1;min-width:90px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi</span></button>
                <!-- Broker: Gửi yêu cầu -->
                <button class="rc-btn role-broker" style="background:var(--primary);color:#fff;flex:1;min-width:100px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi yêu cầu</span></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Result card 4 -->
        <div class="result-card">
          <div class="rc-img" style="background:linear-gradient(135deg,#4a1942,#7c3aed);"><div style="display:flex;align-items:center;justify-content:center;height:100%;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M4 21V9l8-6 8 6v12"/></svg></div></div>
          <div class="rc-body">
            <div class="rc-tags">
              <span class="badge badge-purple" style="font-size:9px;padding:2px 6px;">Khách sạn</span>
              <span class="badge badge-red" style="font-size:9px;padding:2px 6px;">Hot</span>
            </div>
            <div class="rc-title">Khách sạn 20 phòng khu Hòa Bình</div>
            <div class="rc-price">15,000 triệu</div>
            <div class="rc-meta">
              <span>20 phòng</span>
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></span>350m²</span>
            </div>
            <div class="rc-footer">
              <span class="rc-time">3 ngày trước</span>
              <div style="display:flex;gap:6px;flex-wrap:wrap;">
                <button class="rc-btn" style="color: var(--primary);" onclick="toggleBookmark(this, Math.random()); event.stopPropagation();"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
                <!-- Guest: Đăng ký + Gửi yêu cầu -->
                <button class="rc-btn role-guest" style="background:var(--primary);color:#fff;flex:1;min-width:80px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg> Đăng ký</span></button>
                <button class="rc-btn role-guest" style="background:var(--primary-light);color:var(--primary);flex:1;min-width:90px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi</span></button>
                <!-- Broker: Gửi yêu cầu -->
                <button class="rc-btn role-broker" style="background:var(--primary);color:#fff;flex:1;min-width:100px;"><span style="display:inline-flex;align-items:center;justify-content:center;gap:3px;font-size:12px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi yêu cầu</span></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Load more -->
        <div style="padding:16px;text-align:center;">
          <button style="padding:11px 28px;border:1.5px solid var(--border);border-radius:20px;font-size:13px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);" onclick="loadMore(this)">Xem thêm kết quả</button>
        </div>

      </div><!-- end listView -->

    </div><!-- end stateResults -->

    <!-- FULL SCREEN MAP MODAL -->
    <div id="searchMapModal" style="display:none;position:fixed;inset:0;z-index:500;flex-direction:column;background:#fff;">

      <!-- Header -->
      <div style="height:52px;display:flex;align-items:center;padding:0 12px;gap:10px;border-bottom:1px solid var(--border);flex-shrink:0;background:#fff;">
        <button onclick="closeSearchMapModal()" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border:none;background:var(--primary);border-radius:50%;cursor:pointer;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        </button>
        <div style="flex:1;font-size:15px;font-weight:600;color:var(--text-primary);">Bản đồ BĐS</div>
        <div id="mapModalPropertyCount" style="font-size:11px;font-weight:600;color:var(--text-secondary);background:var(--bg-secondary);padding:4px 10px;border-radius:12px;"></div>
      </div>

      <!-- Map canvas -->
      <div style="position:relative;flex:1;overflow:hidden;">
        <div id="searchMapCanvas" style="height:100%;width:100%;"></div>

        <!-- Layer toggle button (top-right) -->
        <button id="mapLayerBtn" onclick="toggleMapLayer()" title="Chuyển layer bản đồ" style="position:absolute;top:12px;right:12px;width:36px;height:36px;background:#fff;border:none;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.15);cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:5;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
        </button>

        <!-- My Location button (bottom-right) -->
        <button id="myLocationBtn" onclick="goToMyLocation()" style="position:absolute;bottom:16px;right:12px;width:40px;height:40px;background:#fff;border:none;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.15);cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:5;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </button>

        <!-- Loading overlay -->
        <div id="mapLoading" style="position:absolute;inset:0;background:rgba(255,255,255,0.7);display:none;align-items:center;justify-content:center;z-index:10;">
          <div class="spinner" style="width:32px;height:32px;border:3px solid var(--border);border-top:3px solid var(--primary);border-radius:50%;animation:spin 1s linear infinite;"></div>
        </div>
      </div>

      <!-- Bottom card -->
      <div id="mapBottomCard" style="display:none;position:absolute;bottom:0;left:0;right:0;background:#fff;border-radius:16px 16px 0 0;padding:16px;box-shadow:0 -4px 20px rgba(0,0,0,0.12);z-index:10;">
        <div style="width:32px;height:4px;background:var(--border);border-radius:2px;margin:0 auto 12px;"></div>
        <div id="mapBottomCardContent"></div>
      </div>

    </div><!-- end searchMapModal -->

    <!-- ========== LEAD TAB CONTENT ========== -->
    <div id="leadTabContent" style="display:none;">
      <div class="role-sale role-bds_admin role-sale_admin role-admin">
        <!-- Lead search bar placeholder info -->
        <div style="padding:12px 16px 6px;">
          <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px;">
            <div class="fs-chip active" data-lead-status="" onclick="filterLeadByStatus(this)">Tất cả</div>
            <div class="fs-chip" data-lead-status="new" onclick="filterLeadByStatus(this)">Mới</div>
            <div class="fs-chip" data-lead-status="contacted" onclick="filterLeadByStatus(this)">Đã liên hệ</div>
            <div class="fs-chip" data-lead-status="qualified" onclick="filterLeadByStatus(this)">Tiềm năng</div>
            <div class="fs-chip" data-lead-status="won" onclick="filterLeadByStatus(this)">Thành công</div>
            <div class="fs-chip" data-lead-status="lost" onclick="filterLeadByStatus(this)">Mất</div>
          </div>
        </div>
        <!-- Lead results -->
        <div id="leadResults">
          <div style="padding:20px;text-align:center;">
            <div class="spinner" style="display:inline-block;width:24px;height:24px;border:3px solid var(--border);border-top:3px solid var(--primary);border-radius:50%;animation:spin 1s linear infinite;"></div>
            <div style="margin-top:10px;font-size:13px;color:var(--text-secondary);">Đang tải danh sách lead...</div>
          </div>
        </div>
      </div>
    </div>

  </div><!-- end page-search -->

  <!-- ========== FILTER SHEET ========== -->
  <div class="filter-overlay" id="filterOverlay" onclick="closeFilterSheet()"></div>
  <div class="filter-sheet" id="filterSheet">
    <div class="sheet-handle"></div>
    <div style="display:flex;align-items:center;justify-content:space-between;padding:0 20px 12px;">
      <div style="font-size:16px;font-weight:700;color:var(--text-primary);">Bộ lọc nâng cao</div>
      <button onclick="resetFilterSheet()" style="font-size:12px;color:var(--primary);background:none;border:none;cursor:pointer;font-weight:600;">Đặt lại</button>
    </div>

    <div class="filter-sheet-body">
      <!-- Loại giao dịch -->
      <div class="fs-section">
        <div class="fs-label">Loại giao dịch</div>
        <div class="fs-chips">
          <div class="fs-chip active" data-filter="property_type" data-value="" onclick="selectFilterChip(this)">Tất cả</div>
          <div class="fs-chip" data-filter="property_type" data-value="0" onclick="selectFilterChip(this)">Bán</div>
          <div class="fs-chip" data-filter="property_type" data-value="1" onclick="selectFilterChip(this)">Cho thuê</div>
        </div>
      </div>

      <!-- Loại BĐS — lấy từ DB -->
      <div class="fs-section">
        <div class="fs-label">Loại BĐS</div>
        <div class="fs-chips">
          <div class="fs-chip active" data-filter="categoryName" data-value="" onclick="selectFilterChip(this)">Tất cả</div>
          @foreach($categories as $cat)
          <div class="fs-chip" data-filter="categoryName" data-value="{{ $cat->category }}" onclick="selectFilterChip(this)">{{ $cat->category }}</div>
          @endforeach
        </div>
      </div>

      <!-- Khoảng giá -->
      <div class="fs-section">
        <div class="fs-label">Khoảng giá</div>
        <div class="fs-chips">
          <div class="fs-chip active" data-filter="price" data-value="" onclick="selectFilterChip(this)">Tất cả</div>
          <div class="fs-chip" data-filter="price" data-value="Dưới 1 tỷ" onclick="selectFilterChip(this)">Dưới 1 tỷ</div>
          <div class="fs-chip" data-filter="price" data-value="1–2 tỷ" onclick="selectFilterChip(this)">1–2 tỷ</div>
          <div class="fs-chip" data-filter="price" data-value="2–3 tỷ" onclick="selectFilterChip(this)">2–3 tỷ</div>
          <div class="fs-chip" data-filter="price" data-value="3–5 tỷ" onclick="selectFilterChip(this)">3–5 tỷ</div>
          <div class="fs-chip" data-filter="price" data-value="5–7 tỷ" onclick="selectFilterChip(this)">5–7 tỷ</div>
          <div class="fs-chip" data-filter="price" data-value="7–10 tỷ" onclick="selectFilterChip(this)">7–10 tỷ</div>
          <div class="fs-chip" data-filter="price" data-value="Trên 10 tỷ" onclick="selectFilterChip(this)">Trên 10 tỷ</div>
        </div>
      </div>

      <!-- Khu vực -->
      <div class="fs-section">
        <div class="fs-label">Khu vực</div>
        <div class="fs-chips">
          <div class="fs-chip active" data-filter="location" data-value="" onclick="selectFilterChip(this)">Tất cả</div>
          @php
            $hotWards = \App\Models\LocationsWard::where('district_code', config('location.district_code'))->get();
          @endphp
          @foreach($hotWards as $w)
          <div class="fs-chip" data-filter="location" data-value="{{ $w->full_name }}" onclick="selectFilterChip(this)">{{ $w->full_name }}</div>
          @endforeach
        </div>
      </div>

      <!-- Diện tích -->
      <div class="fs-section">
        <div class="fs-label">Diện tích</div>
        <div class="fs-chips">
          <div class="fs-chip active" data-filter="area" data-value="" onclick="selectFilterChip(this)">Tất cả</div>
          <div class="fs-chip" data-filter="area" data-value="0-100" onclick="selectFilterChip(this)">Dưới 100m²</div>
          <div class="fs-chip" data-filter="area" data-value="100-200" onclick="selectFilterChip(this)">100–200m²</div>
          <div class="fs-chip" data-filter="area" data-value="200-500" onclick="selectFilterChip(this)">200–500m²</div>
          <div class="fs-chip" data-filter="area" data-value="500-1000" onclick="selectFilterChip(this)">500–1000m²</div>
          <div class="fs-chip" data-filter="area" data-value="1000+" onclick="selectFilterChip(this)">Trên 1000m²</div>
        </div>
      </div>

      <!-- Hướng -->
      <div class="fs-section">
        <div class="fs-label">Hướng</div>
        <div class="fs-chips">
          <div class="fs-chip active" data-filter="direction" data-value="" onclick="selectFilterChip(this)">Tất cả</div>
          <div class="fs-chip" data-filter="direction" data-value="Đông" onclick="selectFilterChip(this)">Đông</div>
          <div class="fs-chip" data-filter="direction" data-value="Tây" onclick="selectFilterChip(this)">Tây</div>
          <div class="fs-chip" data-filter="direction" data-value="Nam" onclick="selectFilterChip(this)">Nam</div>
          <div class="fs-chip" data-filter="direction" data-value="Bắc" onclick="selectFilterChip(this)">Bắc</div>
          <div class="fs-chip" data-filter="direction" data-value="Đông Nam" onclick="selectFilterChip(this)">ĐN</div>
          <div class="fs-chip" data-filter="direction" data-value="Đông Bắc" onclick="selectFilterChip(this)">ĐB</div>
          <div class="fs-chip" data-filter="direction" data-value="Tây Nam" onclick="selectFilterChip(this)">TN</div>
          <div class="fs-chip" data-filter="direction" data-value="Tây Bắc" onclick="selectFilterChip(this)">TB</div>
        </div>
      </div>

      <!-- Pháp lý -->
      <div class="fs-section">
        <div class="fs-label">Pháp lý</div>
        <div class="fs-chips">
          <div class="fs-chip active" data-filter="legal" data-value="" onclick="selectFilterChip(this)">Tất cả</div>
          <div class="fs-chip" data-filter="legal" data-value="Sổ đỏ" onclick="selectFilterChip(this)">Sổ đỏ</div>
          <div class="fs-chip" data-filter="legal" data-value="Sổ hồng" onclick="selectFilterChip(this)">Sổ hồng</div>
          <div class="fs-chip" data-filter="legal" data-value="Giấy tay" onclick="selectFilterChip(this)">Giấy tay</div>
          <div class="fs-chip" data-filter="legal" data-value="Hợp đồng" onclick="selectFilterChip(this)">Hợp đồng</div>
        </div>
      </div>
    </div>

    <div class="fs-footer">
      <button class="fs-btn-reset" onclick="resetFilterSheet()">Đặt lại</button>
      <button class="fs-btn-apply" onclick="applyFilterSheet()">Xem kết quả</button>
    </div>
  </div>

  <!-- ========== SORT SHEET ========== -->
  <div class="sort-overlay" id="sortOverlay" onclick="closeSortSheet()"></div>
  <div class="sort-sheet" id="sortSheet">
    <div class="sheet-handle"></div>
    <div style="font-size:16px;font-weight:700;padding:0 20px 16px;color:var(--text-primary);border-bottom:1px solid var(--border-light);">Sắp xếp theo</div>
    <div class="ss-option active" data-sort="latest" onclick="selectSort(this)"><span>Mới nhất</span><span class="ss-check">✓</span></div>
    <div class="ss-option" data-sort="oldest" onclick="selectSort(this)"><span>Cũ nhất</span><span class="ss-check"></span></div>
    <div class="ss-option" data-sort="price_asc" onclick="selectSort(this)"><span>Giá thấp → cao</span><span class="ss-check"></span></div>
    <div class="ss-option" data-sort="price_desc" onclick="selectSort(this)"><span>Giá cao → thấp</span><span class="ss-check"></span></div>
    <div class="ss-option" data-sort="area_asc" onclick="selectSort(this)"><span>Diện tích nhỏ → lớn</span><span class="ss-check"></span></div>
    <div class="ss-option" data-sort="area_desc" onclick="selectSort(this)"><span>Diện tích lớn → nhỏ</span><span class="ss-check"></span></div>
  </div>

