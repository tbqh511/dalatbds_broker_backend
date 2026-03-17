    <div class="page active" id="page-home">

      <!-- Booking hôm nay — chỉ Sale+ -->
      <div class="booking-today role-sale role-bds_admin role-sale_admin role-admin" style="margin-top:14px;">
        <div class="booking-today-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Lịch hẹn hôm nay</span></div>
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
            <div class="stat-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/>
                <path d="M9 22V13h6v9"/>
              </svg>
            </div>
            <div class="stat-label">Tin đang hiển thị</div>
            <div class="stat-value">{{ $stats['properties_count'] ?? 0 }}</div>
            <div class="stat-delta">+0 tuần này</div>
          </div>
          <div class="stat-card" style="--icon-bg:#fef3c7;--icon-color:#d97706">
            <div class="stat-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </div>
            <div class="stat-label">Lượt xem</div>
            <div class="stat-value">{{ number_format($stats['views_count'] ?? 0) }}</div>
            <div class="stat-delta">+0 tuần này</div>
          </div>
          <div class="stat-card role-sale role-bds_admin role-sale_admin role-admin" style="--icon-bg:#d1fae5;--icon-color:#059669">
            <div class="stat-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <circle cx="12" cy="12" r="6"/>
                <circle cx="12" cy="12" r="2"/>
              </svg>
            </div>
            <div class="stat-label">Lead đang xử lý</div>
            <div class="stat-value">0</div>
            <div class="stat-delta">—</div>
          </div>
          <div class="stat-card role-sale role-bds_admin role-sale_admin role-admin" style="--icon-bg:#ede9fe;--icon-color:#7c3aed">
            <div class="stat-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="14" rx="2"/>
                <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                <line x1="12" y1="12" x2="12" y2="16"/>
                <line x1="10" y1="14" x2="14" y2="14"/>
              </svg>
            </div>
            <div class="stat-label">Deal đang chăm</div>
            <div class="stat-value">0</div>
            <div class="stat-delta">—</div>
          </div>
          <div class="stat-card role-broker" style="--icon-bg:#fce7f3;--icon-color:#db2777">
            <div class="stat-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
              </svg>
            </div>
            <div class="stat-label">Đánh giá</div>
            <div class="stat-value">{{ $stats['reviews_count'] ?? 0 }}</div>
            <div class="stat-delta">+{{ $stats['reviews_count_week'] ?? 0 }} tuần này</div>
          </div>
          <div class="stat-card role-broker" style="--icon-bg:#ccfbf1;--icon-color:#0d9488">
            <div class="stat-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
              </svg>
            </div>
            <div class="stat-label">Lượt quan tâm</div>
            <div class="stat-value">{{ $stats['favourites_count'] ?? 0 }}</div>
            <div class="stat-delta">+{{ $stats['favourites_count_week'] ?? 0 }} tuần này</div>
          </div>
        </div>
      </div>

      <!-- Admin: Queue duyệt -->
      <div class="role-admin role-bds_admin" style="margin:14px 16px 0;">
        <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
          <span style="font-size:20px;display:inline-flex;align-items:center;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span>
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
        @if($marketPrices->isNotEmpty())
          <div class="market-title" style="display:flex;align-items:center;gap:5px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg> GIÁ TRUNG BÌNH / M² — THÁNG {{ $marketPrices->first()->month }}/{{ $marketPrices->first()->year }}</div>
          <div class="market-prices">
            @foreach($marketPrices as $mp)
              <div class="market-price-item">
                <div class="market-price-area">{{ $mp->area_name }}</div>
                <div class="market-price-val">{{ $mp->formatted_price }}</div>
                @if($mp->trend_dir !== 'flat')
                  <div class="market-price-trend {{ $mp->trend_dir }}">{{ $mp->trend_dir === 'up' ? '↑' : '↓' }} {{ $mp->trend_pct }}%</div>
                @else
                  <div class="market-price-trend" style="color:#6b7280;">—</div>
                @endif
              </div>
            @endforeach
          </div>
        @else
          <div class="market-title" style="display:flex;align-items:center;gap:5px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg> GIÁ TRUNG BÌNH / M² — ĐÀ LẠT</div>
          <div style="padding:12px;font-size:12px;color:#9ca3af;text-align:center;">Chưa có dữ liệu thị trường</div>
        @endif
      </div>

      <!-- Filter chips -->
      <div class="filter-bar" id="home-filter-bar">
        <div class="chip active" data-type="" data-category="" onclick="toggleChip(this);resetHomeFeed(this)">Tất cả</div>
        <div class="chip" data-type="" data-category="dato" onclick="toggleChip(this);resetHomeFeed(this)">Đất ở</div>
        <div class="chip" data-type="" data-category="nha" onclick="toggleChip(this);resetHomeFeed(this)">Nhà phố</div>
        <div class="chip" data-type="" data-category="bietthu" onclick="toggleChip(this);resetHomeFeed(this)">Biệt thự</div>
        <div class="chip" data-type="" data-category="chungcu" onclick="toggleChip(this);resetHomeFeed(this)">Căn hộ</div>
        <div class="chip" data-type="" data-category="khachsan" onclick="toggleChip(this);resetHomeFeed(this)">Khách sạn</div>
        <div class="chip" data-type="0" data-category="" onclick="toggleChip(this);resetHomeFeed(this)">Mua</div>
        <div class="chip" data-type="1" data-category="" onclick="toggleChip(this);resetHomeFeed(this)">Thuê</div>
      </div>

      <!-- Property listings — Tin mới nhất -->
      <div class="page-section-title">Tin mới nhất</div>

      <div id="prop-feed">
        @foreach($properties as $p)
          @php
            $galleryBase = url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH');
            $galleryImgs = $p->propery_image
                ->filter(fn($img) => $img->image)
                ->map(fn($img) => $galleryBase . $p->id . '/' . $img->image)
                ->values()->toArray();
            $propData = [
              'id'        => $p->id,
              'title'     => $p->title_by_address,
              'price'     => $p->formatted_prices,
              'type'      => $p->category?->category ?? 'BĐS',
              'area'      => $p->area ? $p->area.' m²' : '—',
              'room'      => $p->number_room ? $p->number_room.' PN' : '—',
              'addr'      => $p->address_location,
              'views'     => $p->total_click,
              'images'    => count($galleryImgs) ? $galleryImgs : ($p->title_image ? [$p->title_image] : []),
              'priceM2'   => $p->formatted_price_m2 ?? '',
              'direction' => $p->direction ?? '—',
              'transactionType' => $p->property_type == 1 ? 'rent' : 'sale',
            ];
          @endphp
          <div class="prop-card"
            data-prop='@json($propData)'
            onclick="if(!event.target.closest('.prop-quick-btn,.prop-action-btn'))openDetail(JSON.parse(this.dataset.prop))">
            <div class="prop-img">
              @if($p->title_image)
                <img src="{{ $p->title_image }}" class="prop-img-inner" style="object-fit:cover;width:100%;height:100%;" alt="">
              @else
                <div class="img-prop1 prop-img-inner"><div class="img-center">
                  <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.45)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/>
                    <path d="M9 22V13h6v9"/>
                  </svg>
                </div></div>
              @endif
              <div class="prop-img-gradient"></div>
              <div class="prop-img-tags">
                @if($p->category)
                  <span class="badge badge-blue">{{ $p->category->category }}</span>
                @endif
              </div>
              <div class="prop-img-price">{{ $p->formatted_prices }}</div>
              <div class="prop-actions">
                <div class="prop-action-btn">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                  </svg>
                </div>
              </div>
            </div>
            <div class="prop-body">
              <div class="prop-title">{{ $p->title_by_address }}</div>
              <div class="prop-location">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $p->address_location }}
              </div>
              <div class="prop-meta">
                @if($p->area)
                  <div class="prop-meta-item">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                      <polyline points="15 3 21 3 21 9"/>
                      <polyline points="9 21 3 21 3 15"/>
                      <line x1="21" y1="3" x2="14" y2="10"/>
                      <line x1="3" y1="21" x2="10" y2="14"/>
                    </svg>
                    <span>{{ $p->area }} m²</span>
                  </div>
                @endif
                @if($p->legal)
                  <div class="prop-meta-item">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                      <polyline points="14 2 14 8 20 8"/>
                      <line x1="16" y1="13" x2="8" y2="13"/>
                      <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <span>{{ $p->legal }}</span>
                  </div>
                @endif
                @if($p->number_room)
                  <div class="prop-meta-item role-broker role-bds_admin role-sale role-sale_admin role-admin">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                      <path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/>
                      <path d="M2 15h20"/>
                      <path d="M6 10V6a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4"/>
                    </svg>
                    <span>{{ $p->number_room }} PN</span>
                  </div>
                @endif
              </div>
            </div>
            <div class="prop-footer">
              <div class="prop-views" style="display:flex;align-items:center;gap:4px;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                {{ $p->total_click }} lượt xem
              </div>
              @php $isOwn = auth()->guard('webapp')->check() && $p->added_by == auth()->guard('webapp')->id(); @endphp
              <div class="prop-quick-actions">
                {{-- Edit: bds_admin + admin always; broker only for own listing --}}
                <div class="prop-quick-btn {{ $isOwn ? 'role-broker ' : '' }}role-bds_admin role-admin" title="Chỉnh sửa"
                     onclick="propEditAction(JSON.parse(this.closest('.prop-card').dataset.prop),event)">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                </div>
                {{-- Call: sale + sale_admin + admin always; broker only for own listing --}}
                <div class="prop-quick-btn {{ $isOwn ? 'role-broker ' : '' }}role-sale role-sale_admin role-admin" style="background:var(--primary-light);border-color:transparent;color:var(--primary);" title="Gọi"
                     onclick="propCallAction(JSON.parse(this.closest('.prop-card').dataset.prop),event)">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/>
                  </svg>
                </div>
                {{-- Share: all roles (no restriction) --}}
                <div class="prop-quick-btn" title="Chia sẻ"
                     onclick="propShareAction(JSON.parse(this.closest('.prop-card').dataset.prop),event)">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                    <polyline points="16 6 12 2 8 6"/>
                    <line x1="12" y1="2" x2="12" y2="15"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Sentinel for Intersection Observer -->
      <div id="feed-sentinel"
           data-page="{{ $properties->hasMorePages() ? 2 : 0 }}"
           data-has-more="{{ $properties->hasMorePages() ? 'true' : 'false' }}"
           data-loading="false"
           style="height:60px;display:flex;align-items:center;justify-content:center;">
        @if($properties->hasMorePages())
          <div id="feed-spinner" style="color:#9ca3af;font-size:13px;">Đang tải...</div>
        @endif
      </div>

    </div><!-- end page-home -->
