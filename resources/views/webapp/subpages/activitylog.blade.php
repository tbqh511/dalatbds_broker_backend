  <!-- ========== SUBPAGE: LỊCH SỬ HÀNH ĐỘNG ========== -->
  <div class="subpage" id="subpage-activitylog">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('activitylog')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg> Lịch sử hành động</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="refreshActivityLog()" title="Làm mới"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg></button>
      </div>
    </div>

    <!-- Hero stats -->
    <div class="admin-hero" style="background:linear-gradient(135deg,#0369a1,#0284c7,#38bdf8);">
      <div class="ah-label">THEO DÕI HOẠT ĐỘNG — ADMIN</div>
      <div class="ah-main" id="alTotalText">Đang tải...</div>
      <div class="ah-grid">
        <div class="ah-stat"><div class="ah-stat-val" id="alTotalCount">—</div><div class="ah-stat-lbl">Tổng log</div></div>
        <div class="ah-stat"><div class="ah-stat-val" id="alCallCount">—</div><div class="ah-stat-lbl">Gọi điện</div></div>
        <div class="ah-stat"><div class="ah-stat-val" id="alShareCount">—</div><div class="ah-stat-lbl">Chia sẻ</div></div>
        <div class="ah-stat"><div class="ah-stat-val" id="alEditCount">—</div><div class="ah-stat-lbl">Chỉnh sửa</div></div>
      </div>
    </div>

    <!-- Filter: Subject type -->
    <div class="filter-bar" id="al-type-filter" style="padding:10px 16px 0;">
      <div class="chip active" data-type="" onclick="alFilterType(this)">Tất cả</div>
      <div class="chip" data-type="property" onclick="alFilterType(this)">
        <span style="display:inline-flex;align-items:center;gap:3px;">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg> BĐS
        </span>
      </div>
      <div class="chip" data-type="lead" onclick="alFilterType(this)">
        <span style="display:inline-flex;align-items:center;gap:3px;">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg> Lead
        </span>
      </div>
      <div class="chip" data-type="deal" onclick="alFilterType(this)">
        <span style="display:inline-flex;align-items:center;gap:3px;">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg> Deal
        </span>
      </div>
    </div>

    <!-- Filter: Action type -->
    <div class="filter-bar" id="al-action-filter" style="padding:6px 16px 0;">
      <div class="chip active" data-action="" onclick="alFilterAction(this)">Tất cả HĐ</div>
      <div class="chip" data-action="call" onclick="alFilterAction(this)" style="--chip-accent:#059669;">
        <span style="display:inline-flex;align-items:center;gap:3px;">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg> Gọi
        </span>
      </div>
      <div class="chip" data-action="share" onclick="alFilterAction(this)">
        <span style="display:inline-flex;align-items:center;gap:3px;">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg> Chia sẻ
        </span>
      </div>
      <div class="chip" data-action="edit" onclick="alFilterAction(this)">
        <span style="display:inline-flex;align-items:center;gap:3px;">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Sửa
        </span>
      </div>
      <div class="chip" data-action="view" onclick="alFilterAction(this)">Xem</div>
      <div class="chip" data-action="create" onclick="alFilterAction(this)">Tạo mới</div>
    </div>

    <!-- Log list -->
    <div class="sp-scroll" id="alScrollArea" style="padding-bottom:24px;">
      <div id="activityLogList">
        <div style="display:flex;align-items:center;justify-content:center;padding:40px 16px;color:var(--text-tertiary);font-size:13px;" id="alLoading">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;animation:spin 0.8s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
          Đang tải...
        </div>
      </div>
      <!-- Sentinel inside scroll container for IntersectionObserver -->
      <div id="al-sentinel" data-page="1" data-has-more="false" data-loading="false" style="height:1px;"></div>
    </div>

  </div><!-- end subpage-activitylog -->

  <script>
  (function(){
    let alFilters = { subject_type: '', action: '' };
    let alObserver = null;

    const actionColors = {
      call:   { bg: '#d1fae5', color: '#065f46' },
      share:  { bg: '#dbeafe', color: '#1d4ed8' },
      edit:   { bg: '#fef3c7', color: '#92400e' },
      view:   { bg: '#f3f4f6', color: '#374151' },
      create: { bg: '#ede9fe', color: '#5b21b6' },
      delete: { bg: '#fee2e2', color: '#991b1b' },
    };
    const actionLabels = {
      call: 'Gọi điện', share: 'Chia sẻ', edit: 'Chỉnh sửa',
      view: 'Xem', create: 'Tạo mới', delete: 'Xóa',
    };
    const subjectColors = {
      property: { bg: 'var(--primary-light)', color: 'var(--primary)' },
      lead:     { bg: 'var(--danger-light)',   color: 'var(--danger)'  },
      deal:     { bg: 'var(--purple-light)',   color: 'var(--purple)'  },
    };
    const subjectLabels = { property: 'BĐS', lead: 'Lead', deal: 'Deal' };

    window.alFilterType = function(chip) {
      chip.closest('.filter-bar').querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      alFilters.subject_type = chip.dataset.type || '';
      resetActivityLog();
    };

    window.alFilterAction = function(chip) {
      chip.closest('.filter-bar').querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      alFilters.action = chip.dataset.action || '';
      resetActivityLog();
    };

    window.refreshActivityLog = function() { resetActivityLog(); };

    function resetActivityLog() {
      // Reset list content
      const list = document.getElementById('activityLogList');
      if (list) list.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;padding:40px 16px;color:var(--text-tertiary);font-size:13px;" id="alLoading"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;animation:spin 0.8s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>Đang tải...</div>';

      // Reset sentinel
      const sentinel = document.getElementById('al-sentinel');
      if (sentinel) {
        sentinel.dataset.page = '1';
        sentinel.dataset.hasMore = 'true';
        sentinel.dataset.loading = 'false';
      }

      // Reset banner to loading state
      const totalText = document.getElementById('alTotalText');
      if (totalText) totalText.textContent = 'Đang tải...';
      ['alTotalCount','alCallCount','alShareCount','alEditCount'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) el.textContent = '—';
      });

      // Scroll to top
      const scrollArea = document.getElementById('alScrollArea');
      if (scrollArea) scrollArea.scrollTop = 0;

      loadActivityLogPage();
    }

    function loadActivityLogPage() {
      const sentinel = document.getElementById('al-sentinel');
      if (!sentinel || sentinel.dataset.hasMore !== 'true' || sentinel.dataset.loading === 'true') return;
      sentinel.dataset.loading = 'true';

      const page = parseInt(sentinel.dataset.page) || 1;
      const params = new URLSearchParams({ page });
      if (alFilters.subject_type) params.set('subject_type', alFilters.subject_type);
      if (alFilters.action) params.set('action', alFilters.action);

      const token = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
      fetch('/webapp/action-logs?' + params, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': token || '' }
      })
      .then(function(r) { return r.json(); })
      .then(function(res) {
        const list = document.getElementById('activityLogList');
        const loading = document.getElementById('alLoading');
        if (loading) loading.remove();

        // Update banner stats on first page (counts_by_action respects subject_type filter)
        if (page === 1 && res.total !== undefined) {
          const totalText = document.getElementById('alTotalText');
          if (totalText) totalText.textContent = res.total + ' hành động được ghi nhận';
          const totalCount = document.getElementById('alTotalCount');
          if (totalCount) totalCount.textContent = res.total;

          const counts = res.counts_by_action || {};
          const callEl = document.getElementById('alCallCount');
          if (callEl) callEl.textContent = counts.call !== undefined ? counts.call : '—';
          const shareEl = document.getElementById('alShareCount');
          if (shareEl) shareEl.textContent = counts.share !== undefined ? counts.share : '—';
          const editEl = document.getElementById('alEditCount');
          if (editEl) editEl.textContent = counts.edit !== undefined ? counts.edit : '—';
        }

        if (list && res.data) {
          res.data.forEach(function(log) {
            list.insertAdjacentHTML('beforeend', renderLogItem(log));
          });
        }

        sentinel.dataset.loading = 'false';
        sentinel.dataset.hasMore = res.has_more ? 'true' : 'false';
        sentinel.dataset.page = res.next_page || (page + 1);

        // Empty state
        if (page === 1 && (!res.data || res.data.length === 0)) {
          if (list) list.innerHTML = '<div style="padding:40px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">Chưa có hoạt động nào được ghi nhận</div>';
        }
      })
      .catch(function() {
        sentinel.dataset.loading = 'false';
        const loading = document.getElementById('alLoading');
        if (loading) loading.textContent = 'Không thể tải dữ liệu';
      });
    }

    function renderLogItem(log) {
      const ac = actionColors[log.action] || { bg: '#f3f4f6', color: '#374151' };
      const sc = subjectColors[log.subject_type] || { bg: '#f3f4f6', color: '#374151' };
      const al = actionLabels[log.action] || log.action;
      const sl = subjectLabels[log.subject_type] || log.subject_type;

      return `<div style="display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-bottom:1px solid var(--border-light);">
        <div style="width:36px;height:36px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">${log.actor_initials}</div>
        <div style="flex:1;min-width:0;">
          <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:3px;">
            <span style="font-size:12px;font-weight:600;color:var(--text-primary);">${log.actor_name}</span>
            <span style="font-size:10px;font-weight:600;padding:2px 7px;border-radius:10px;background:${ac.bg};color:${ac.color};">${al}</span>
            <span style="font-size:10px;font-weight:600;padding:2px 7px;border-radius:10px;background:${sc.bg};color:${sc.color};">${sl}</span>
          </div>
          <div style="font-size:12px;color:var(--text-secondary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${log.subject_title || ''}">${log.subject_title || ('#' + log.subject_id)}</div>
          <div style="font-size:11px;color:var(--text-tertiary);margin-top:2px;" title="${log.time_full}">${log.time_diff}</div>
        </div>
      </div>`;
    }

    // IntersectionObserver — root is the scroll container so it works inside fixed subpage
    function setupAlObserver() {
      if (alObserver) alObserver.disconnect();
      const sentinel = document.getElementById('al-sentinel');
      const scrollArea = document.getElementById('alScrollArea');
      if (!sentinel || !scrollArea) return;
      alObserver = new IntersectionObserver(function(entries) {
        if (entries[0].isIntersecting) loadActivityLogPage();
      }, { root: scrollArea, rootMargin: '120px' });
      alObserver.observe(sentinel);
    }

    // Hook into openSubpage
    const subpage = document.getElementById('subpage-activitylog');
    if (subpage) {
      const origOpen = window.openSubpage;
      window.openSubpage = function(id) {
        origOpen(id);
        if (id === 'activitylog') {
          resetActivityLog();
          setupAlObserver();
        }
      };
    }
  })();
  </script>
