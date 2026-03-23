document.addEventListener('DOMContentLoaded', function(){

// ============ NAVIGATION ============
const pages = ['home','search','post','activity','profile'];
const navIds = ['nav-home','nav-search','nav-post','nav-activity','nav-profile'];

window.goTo = function(page){
  pages.forEach(p=>{
    const el=document.getElementById('page-'+p);
    if(el) el.classList.toggle('active',p===page);
  });
  navIds.forEach((id,i)=>{
    const btn=document.getElementById(id);
    if(btn) btn.classList.toggle('active',pages[i]===page);
  });
  document.getElementById('scrollArea').scrollTop=0;
};

window.toggleSearch = function(){goTo('search');};

// ============ ROLE SYSTEM ============
let currentRole = (window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.customerRole) || 'guest';
const roleHierarchy={
  guest:      ['guest'],
  broker:     ['guest','broker'],
  customer:   ['guest','broker'],   // legacy alias → broker
  bds_admin:  ['guest','broker','bds_admin'],
  sale:       ['guest','broker','sale'],
  sale_admin: ['guest','broker','sale','sale_admin'],
  admin:      ['guest','broker','bds_admin','sale','sale_admin','admin']
};

// Elements với multi-role classes cần logic khác:
// Một element VISIBLE nếu BẤT KỲ class role nào của nó nằm trong allowed list
window.setRole = function(role, btn){
  currentRole = role;
  document.querySelectorAll('.rbtn').forEach(b=>b.classList.remove('active'));
  if(btn) btn.classList.add('active');

  const allowed = roleHierarchy[role] || roleHierarchy['guest']; // fallback to guest if role not found
  const allRoles = ['guest','broker','bds_admin','sale','sale_admin','admin'];

  // Find every element that has at least one role- class
  const allRoleEls = document.querySelectorAll(
    allRoles.map(r=>'.role-'+r).join(',')
  );

  allRoleEls.forEach(el => {
    // Check if this element has ANY role class that is in allowed list
    const hasAllowed = allowed.some(r => el.classList.contains('role-'+r));
    el.style.display = hasAllowed ? '' : 'none';
  });

  // Restore correct display types for grid/flex containers that got shown
  const gridSelectors = ['.stats-grid','.type-grid','.market-prices',
    '.commission-breakdown','.profile-stats','.detail-stats-row',
    '.market-prices','.booking-datetime','.spec-grid'];
  gridSelectors.forEach(sel=>{
    document.querySelectorAll(sel).forEach(el=>{
      if(el.style.display !== 'none') el.style.display = 'grid';
    });
  });
  document.querySelectorAll('.commission-total').forEach(el=>{
    if(el.style.display !== 'none') el.style.display = 'block';
  });
  document.querySelectorAll('button.role-guest,button.role-broker,button.role-bds_admin,button.role-sale,button.role-sale_admin,button.role-admin').forEach(el=>{
    if(el.style.display !== 'none') el.style.display = 'inline-block';
  });
  document.querySelectorAll('.similar-scroll,.filter-bar,.sp-tabs,.notif-tabs,.crm-actions,.owner-actions,.mybds-quick,.cust-actions,.cust-tags,.detail-badges,.lead-flow').forEach(el=>{
    if(el.style.display !== 'none') el.style.display = 'flex';
  });

  // Update role label
  const roleLabels={guest:'Khách vãng lai',broker:'eBroker',bds_admin:'BĐS Admin',sale:'Sale',sale_admin:'Sale Admin',admin:'Admin'};
  const profileRole = document.querySelector('.profile-role');
  if(profileRole) profileRole.innerHTML = '💼 '+roleLabels[role];

  // Sync detail-role-switcher with current role
  const roleTextMap = {guest:'Guest',broker:'Broker',bds_admin:'BĐS Admin',sale:'Sale',sale_admin:'Sale Admin',admin:'Admin'};
  document.querySelectorAll('.detail-role-switcher .rbtn').forEach(b => {
    const match = roleTextMap[role] || '';
    const isActive = b.textContent.trim().replace(/^[^\s]+\s*/,'') === match;
    b.classList.toggle('active', isActive);
    b.style.background = isActive ? '#3270FC' : 'rgba(255,255,255,0.12)';
    b.style.color = isActive ? '#fff' : 'rgba(255,255,255,0.6)';
  });

  // Show/hide exact-role CTA bars (data-for-role attribute)
  document.querySelectorAll('[data-for-role]').forEach(el => {
    el.style.display = el.dataset.forRole === role ? '' : 'none';
  });
};

// init role from server config
setRole(currentRole, document.querySelector('.rbtn.active'));

// ============ PROPERTY DETAIL ACTIONS ============
window.approveProperty = function(){
  showToast('Chức năng duyệt BĐS đang phát triển');
};
window.openAssignSaleModal = function(){
  showToast('Chức năng giao Sale đang phát triển');
};

// ============ ACTION LOGGING ============
// Fire-and-forget — không block UI, silent fail
function logAction(subjectType, subjectId, action, subjectTitle, metadata) {
  const token = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if (!token || !subjectId) return;
  fetch('/webapp/log-action', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({
      subject_type:  subjectType,
      subject_id:    subjectId,
      subject_title: subjectTitle || null,
      action:        action,
      metadata:      metadata || null,
    }),
  }).catch(function(){});
}

// ============ PROP-CARD ACTION HANDLERS ============
// Nút Gọi trên prop-card: log → fetch phone → dial
window.propCallAction = function(propData, e) {
  e.stopPropagation();
  logAction('property', propData.id, 'call', propData.title);
  // Fetch phone từ detail API
  fetch('/webapp/property/' + propData.id + '/json', {
    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
  })
  .then(function(r){ return r.json(); })
  .then(function(d){
    if (d.host && d.host.phone) {
      window.location.href = 'tel:' + d.host.phone;
    } else {
      showToast('Chưa có số điện thoại chủ nhà');
    }
  })
  .catch(function(){ showToast('Không thể kết nối, thử lại sau'); });
};

// Nút Chia sẻ trên prop-card: log → copy/share
window.propShareAction = function(propData, e) {
  e.stopPropagation();
  logAction('property', propData.id, 'share', propData.title);
  const url = window.location.origin + '/property/' + propData.id;
  const title = propData.title || 'BĐS Đà Lạt';
  if (navigator.share) {
    navigator.share({ title: title, url: url }).catch(function(){});
  } else if (navigator.clipboard) {
    navigator.clipboard.writeText(url);
    showToast('Đã sao chép link BĐS!');
  } else {
    showToast('Đã sao chép link BĐS!');
  }
};

// Nút Sửa trên prop-card: log → navigate to edit
window.propEditAction = function(propData, e) {
  e.stopPropagation();
  logAction('property', propData.id, 'edit', propData.title);
  window.location.href = '/webapp/edit-listing/' + propData.id;
};

// ============ CHIPS ============
window.toggleChip = function(el){
  el.closest('.filter-bar').querySelectorAll('.chip').forEach(c=>c.classList.remove('active'));
  el.classList.add('active');
};

// ============ TYPE SELECT ============
window.selectType = function(el){
  const parent=el.closest('.type-grid')||el.closest('div[style*="grid-template-columns"]');
  if(parent){
    parent.querySelectorAll('.type-option').forEach(o=>o.classList.remove('selected'));
  }else{
    el.parentElement.querySelectorAll('.type-option').forEach(o=>o.classList.remove('selected'));
  }
  el.classList.add('selected');
};

// ============ BOTTOM SHEET ============
window.openSheet = function(){
  document.getElementById('overlay').classList.add('open');
  document.getElementById('bottomSheet').classList.add('open');
};
window.closeSheet = function(){
  document.getElementById('overlay').classList.remove('open');
  document.getElementById('bottomSheet').classList.remove('open');
};

// ============ NOTIF TABS ============
window.switchNotifTab = function(btn,tab){
  btn.closest('.notif-tabs').querySelectorAll('.ntab').forEach(t=>t.classList.remove('active'));
  btn.classList.add('active');
};

// ============ SHOW DEALS ============
window.showDeals = function(){
  goTo('profile');
};

// ============ SUB-PAGES (BĐS / Khách) ============
window.openSubpage = function(id){
  const sp = document.getElementById('subpage-'+id);
  if(!sp) return;
  sp.classList.add('open');
  document.querySelector('.bottom-nav').style.transform='translateY(100%)';
  if(id === 'users') { usersCurrentTab = 'pending'; document.getElementById('usersSearchInput').value = ''; loadUsers(true); }
  if(id === 'likedbds') loadLikedBds(true);
  if(id === 'mybds') loadMyBds(true);
  if(id === 'mycustomers') loadMyCustomers(true);
  if(id === 'leads') {
    leadsCurrentStatus = '';
    leadsCurrentSearch = '';
    document.querySelectorAll('#subpage-leads .sp-tab').forEach(function(t) { t.classList.remove('active'); });
    var tabAllEl = document.getElementById('leadsTabAll');
    if (tabAllEl) tabAllEl.classList.add('active');
    var searchEl = document.getElementById('leadsSearchInput');
    if (searchEl) searchEl.value = '';
    loadLeads(true);
  }
  if(id === 'deals') {
    dealsCurrentStatus = '';
    dealsCurrentSearch = '';
    document.querySelectorAll('#subpage-deals .sp-tab').forEach(function(t) { t.classList.remove('active'); });
    var dealsTabAllEl = document.getElementById('dealsTabAll');
    if (dealsTabAllEl) dealsTabAllEl.classList.add('active');
    var dealsSearchEl = document.getElementById('dealsSearchInput');
    if (dealsSearchEl) dealsSearchEl.value = '';
    loadDeals(true);
  }
  if(id === 'bookings') {
    if(typeof window.loadBookings === 'function') window.loadBookings();
  }
  if(id === 'commissions') {
    commCurrentStatus = '';
    document.querySelectorAll('#subpage-commissions .sp-tab').forEach(function(t) { t.classList.remove('active'); });
    var commTabAllEl = document.getElementById('commTabAll');
    if (commTabAllEl) commTabAllEl.classList.add('active');
    loadCommissions(true);
  }
  if(id === 'referral') {
    loadReferralData();
  }
  if(id === 'notifset') {
    initNotifSettings();
  }
  if(id === 'assignlead') {
    loadAssignLeadData();
  }
  if(id === 'kpiteam') {
    loadKpiTeamData();
  }
  if(id === 'approvebds') {
    abdsCurrentTab = 'pending';
    document.querySelectorAll('#abdsTabBar .sp-tab').forEach(function(t) { t.classList.remove('active'); });
    var abdsDefaultTab = document.querySelector('#abdsTabBar [data-tab="pending"]');
    if(abdsDefaultTab) abdsDefaultTab.classList.add('active');
    loadApprovalBds(true);
  }
  if(id === 'reports') {
    loadAdminReports('month');
  }
  if(id === 'approvecomm') {
    acommCurrentTab = 'pending';
    document.querySelectorAll('#acommTabBar .sp-tab').forEach(function(t) { t.classList.remove('active'); });
    var acommDefaultTab = document.querySelector('#acommTabBar [data-tab="pending"]');
    if(acommDefaultTab) acommDefaultTab.classList.add('active');
    loadApproveComm(true);
  }
};
window.closeSubpage = function(id){
  const sp = document.getElementById('subpage-'+id);
  if(!sp) return;
  sp.classList.remove('open');
  document.querySelector('.bottom-nav').style.transform='';
};

window.spTabSwitch = function(btn){
  btn.closest('.sp-tabs').querySelectorAll('.sp-tab').forEach(t=>t.classList.remove('active'));
  btn.classList.add('active');
};

window.toggleCustDetail = function(id){
  const panel = document.getElementById(id);
  if(!panel) return;
  const isOpen = panel.classList.contains('open');
  document.querySelectorAll('.cust-detail-panel.open').forEach(p=>p.classList.remove('open'));
  if(!isOpen) panel.classList.add('open');
  const btn = panel.previousElementSibling?.querySelector('.cust-btn.primary');
  if(btn) btn.textContent = isOpen ? '▼ Chi tiết' : '▲ Thu gọn';
};

// ============ CRM FUNCTIONS ============
window.toggleLeadExpand = function(id){
  const el = document.getElementById(id);
  if(!el) return;
  const isOpen = el.classList.contains('open');
  document.querySelectorAll('.lc-expand.open').forEach(e=>e.classList.remove('open'));
  if(!isOpen) el.classList.add('open');
};

window.selectLeadAction = function(btn){
  btn.closest('.lc-action-row').querySelectorAll('.lc-action-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  if(btn.textContent.includes('Tạo Deal')){
    setTimeout(()=>showToast('✓ Đang tạo Deal...'),200);
  }
};

window.toggleBkResult = function(id){
  const el = document.getElementById(id);
  if(!el) return;
  const isOpen = el.classList.contains('open');
  document.querySelectorAll('.bk-result-panel.open').forEach(e=>e.classList.remove('open'));
  if(!isOpen) el.classList.add('open');
};

window.selectBkResult = function(opt, type){
  const panel = opt.closest('.bk-result-panel');
  panel.querySelectorAll('.bk-result-opt').forEach(o=>{
    o.classList.remove('selected','success','danger','warning');
  });
  opt.classList.add('selected', type);
  // enable confirm btn
  const confirmBtn = panel.querySelector('.bk-result-confirm');
  if(confirmBtn){ confirmBtn.disabled = false; }
};

window.selectCalDay = function(day){
  day.closest('.cal-week').querySelectorAll('.cal-day').forEach(d=>d.classList.remove('active'));
  day.classList.add('active');
};

window.openStatusSheet = function(dealId){
  document.getElementById('statusSheet').classList.add('open');
  document.getElementById('statusSheet').dataset.deal = dealId;
};

window.selectStatus = function(opt){
  opt.closest('.ss-options').querySelectorAll('.ss-opt').forEach(o=>o.classList.remove('selected'));
  opt.classList.add('selected');
};

window.confirmStatus = function(){
  const sel = document.querySelector('.ss-opt.selected');
  const note = document.querySelector('.ss-note').value;
  if(!sel){ showToast('Vui lòng chọn trạng thái'); return; }
  document.getElementById('statusSheet').classList.remove('open');
  showToast('✓ Đã cập nhật trạng thái Deal!');
};

document.getElementById('statusSheet')?.addEventListener('click', function(e){
  if(e.target === this) this.classList.remove('open');
});

// ============ NOTIFICATION DETAIL PAGES ============
window.openNotifDetail = function(id){
  const page = document.getElementById(id);
  if(!page) return;
  page.classList.add('open');
  document.querySelector('.bottom-nav').style.transform='translateY(100%)';
};
window.closeNotifDetail = function(id){
  const page = document.getElementById(id);
  if(!page) return;
  page.classList.remove('open');
  document.querySelector('.bottom-nav').style.transform='';
};

// ============ ACCOUNT PAGES ============
window.toggleMaster = function(cb){
  const allToggles = document.querySelectorAll('#subpage-notifset .ios-toggle:not(#ntog-master)');
  allToggles.forEach(t => { t.disabled = !cb.checked; t.style.opacity = cb.checked ? '1' : '.4'; });
};
window.toggleQuiet = function(cb){
  const qh = document.getElementById('quiet-hours');
  if(qh) qh.style.display = cb.checked ? 'flex' : 'none';
};
window.toggleChBadge = function(el){
  el.classList.toggle('active');
};

// Notification settings — toggle id → settings key mapping
var _notifToggleMap = [
  ['ntog-master',             'master',               null],
  ['ntog-lead-assigned',      'lead',                 'assigned'],
  ['ntog-lead-followup',      'lead',                 'followup'],
  ['ntog-deal-status',        'deal',                 'status'],
  ['ntog-deal-feedback',      'deal',                 'feedback'],
  ['ntog-deal-stuck',         'deal',                 'stuck'],
  ['ntog-booking-day_before', 'booking',              'day_before'],
  ['ntog-booking-hour_before','booking',              'hour_before'],
  ['ntog-booking-result',     'booking',              'result'],
  ['ntog-commission-approved','commission',           'approved'],
  ['ntog-commission-status',  'commission',           'status'],
  ['ntog-property-status',    'property',             'status'],
  ['ntog-property-interest',  'property',             'interest'],
  ['ntog-property-expiry',    'property',             'expiry'],
  ['ntog-market-news',        'market',               'news'],
  ['ntog-market-ai_suggest',  'market',               'ai_suggest'],
  ['ntog-market-promotions',  'market',               'promotions'],
  ['quiet-toggle',            'quiet_hours',          'enabled'],
];

window.initNotifSettings = function(){
  var s = (window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.notifSettings) ? window.WEBAPP_CONFIG.notifSettings : {};

  // Toggles
  _notifToggleMap.forEach(function(row){
    var elId = row[0], cat = row[1], key = row[2];
    var el = document.getElementById(elId);
    if(!el) return;
    var val;
    if(key === null) {
      val = s.master !== undefined ? s.master : true;
    } else {
      val = (s[cat] && s[cat][key] !== undefined) ? s[cat][key] : true;
    }
    el.checked = !!val;
  });

  // Channel badges
  document.querySelectorAll('#subpage-notifset .ch-badge[data-cat][data-ch]').forEach(function(badge){
    var cat = badge.getAttribute('data-cat');
    var ch  = badge.getAttribute('data-ch');
    var channels = (s[cat] && Array.isArray(s[cat].channels)) ? s[cat].channels : [];
    badge.classList.toggle('active', channels.indexOf(ch) !== -1);
  });

  // Quiet hours time selects
  var startSel = document.getElementById('quiet-start');
  var endSel   = document.getElementById('quiet-end');
  var qh = s.quiet_hours || {};
  if(startSel && qh.start) startSel.value = qh.start;
  if(endSel   && qh.end)   endSel.value   = qh.end;

  // Apply master toggle visual state
  var masterEl = document.getElementById('ntog-master');
  if(masterEl) toggleMaster(masterEl);

  // Apply quiet toggle visual state
  var quietEl = document.getElementById('quiet-toggle');
  if(quietEl) toggleQuiet(quietEl);
};

window.saveNotifSettings = function(){
  var s = {};

  // Collect toggle values
  _notifToggleMap.forEach(function(row){
    var elId = row[0], cat = row[1], key = row[2];
    var el = document.getElementById(elId);
    var val = el ? el.checked : true;
    if(key === null) {
      s.master = val;
    } else {
      if(!s[cat]) s[cat] = {};
      s[cat][key] = val;
    }
  });

  // Collect channel badges
  ['lead','deal','booking','commission','property'].forEach(function(cat){
    var channels = [];
    document.querySelectorAll('#subpage-notifset .ch-badge[data-cat="'+cat+'"].active').forEach(function(b){
      channels.push(b.getAttribute('data-ch'));
    });
    if(!s[cat]) s[cat] = {};
    s[cat].channels = channels;
  });

  // Quiet hours time
  var startEl = document.getElementById('quiet-start');
  var endEl   = document.getElementById('quiet-end');
  if(!s.quiet_hours) s.quiet_hours = {};
  s.quiet_hours.start = startEl ? startEl.value : '22:00';
  s.quiet_hours.end   = endEl   ? endEl.value   : '07:00';

  // Send as nested JSON — Laravel dot-notation validation handles nested objects
  var payload = s;

  var saveBtn = document.getElementById('notifset-save-btn');
  if(saveBtn) { saveBtn.disabled = true; saveBtn.textContent = 'Đang lưu…'; }

  var url  = window.WEBAPP_CONFIG.routes.notifSettingsSave;
  var csrf = window.WEBAPP_CONFIG.csrfToken;

  fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
    body: JSON.stringify(payload),
  })
    .then(function(res){ return res.json(); })
    .then(function(data){
      if(data && data.success) {
        window.WEBAPP_CONFIG.notifSettings = s;
        showToast('✓ Đã lưu cài đặt thông báo!');
      } else {
        showToast('⚠ Không thể lưu, thử lại!');
      }
    })
    .catch(function(){
      showToast('⚠ Không thể lưu, thử lại!');
    })
    .finally(function(){
      if(saveBtn) { saveBtn.disabled = false; saveBtn.textContent = '✓ Lưu cài đặt'; }
    });
};

// FAQ accordion
window.toggleFaq = function(questionEl){
  const answer = questionEl.nextElementSibling;
  const chevron = questionEl.querySelector('.faq-chevron');
  const isOpen = answer.classList.contains('open');
  // close all
  document.querySelectorAll('#subpage-support .faq-answer.open').forEach(a=>a.classList.remove('open'));
  document.querySelectorAll('#subpage-support .faq-chevron.open').forEach(c=>c.classList.remove('open'));
  if(!isOpen){
    answer.classList.add('open');
    chevron.classList.add('open');
  }
};

// ============ ADMIN — DUYỆT BĐS ============
var currentRejectId = null;
var abdsCurrentTab = 'pending';

window.switchAbdsTab = function(tab, btn) {
  abdsCurrentTab = tab;
  document.querySelectorAll('#abdsTabBar .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  if(btn) btn.classList.add('active');
  loadApprovalBds(true);
};

window.loadApprovalBds = function(reset) {
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if(!cfg || !cfg.adminPropertiesJson) return;
  var url = cfg.adminPropertiesJson + '?tab=' + encodeURIComponent(abdsCurrentTab);
  var container = document.getElementById('abdsListContainer');
  if(!container) return;

  if(reset) {
    container.innerHTML =
      '<div style="padding:16px;">'
      + '<div style="height:120px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;opacity:.6;"></div>'
      + '<div style="height:120px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;opacity:.4;"></div>'
      + '</div>';
  }

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if(!data.success) {
        container.innerHTML = '<div style="text-align:center;padding:32px;color:var(--danger);">Lỗi tải dữ liệu.</div>';
        return;
      }
      var s = data.stats || {};
      // Update hero stats
      var heroMain = document.getElementById('abdsHeroMain');
      if(heroMain) heroMain.textContent = (s.pending || 0) + ' BĐS chờ xem xét';
      var elPending = document.getElementById('abdsPendingCount');
      if(elPending) elPending.textContent = s.pending || 0;
      var elToday = document.getElementById('abdsApprovedToday');
      if(elToday) elToday.textContent = s.approved_today || 0;
      var elTotal = document.getElementById('abdsTotalApproved');
      if(elTotal) elTotal.textContent = s.total_approved || 0;
      var elAvg = document.getElementById('abdsAvgTime');
      if(elAvg) elAvg.textContent = s.avg_hours ? s.avg_hours + 'h' : '—';
      document.querySelectorAll('.abds-tab-count-pending').forEach(function(el) {
        el.textContent = s.pending || 0;
      });

      var props = data.properties || [];
      if(props.length === 0) {
        container.innerHTML =
          '<div style="text-align:center;padding:48px 16px;color:var(--text-tertiary);">'
          + '<div style="font-size:14px;">Không có BĐS nào</div></div>';
        return;
      }
      var html = '';
      props.forEach(function(p) { html += _renderAbdsCard(p); });
      container.innerHTML = html;
    })
    .catch(function() {
      container.innerHTML =
        '<div style="text-align:center;padding:32px;color:var(--danger);">Lỗi kết nối. '
        + '<button onclick="loadApprovalBds(true)" style="color:var(--primary);text-decoration:underline;background:none;border:none;cursor:pointer;">Thử lại</button></div>';
    });
};

function _renderAbdsCard(p) {
  var checks = p.checks || {};
  var legalItems = [
    { key: 'has_legal_docs',    yes: 'Sổ đỏ / GCNQSD — Đã upload',  no: 'Sổ đỏ / GCNQSD — Chưa upload' },
    { key: 'has_enough_photos', yes: 'Ảnh thực tế đầy đủ (≥3 ảnh)', no: 'Ảnh chưa đủ — Cần thêm' },
    { key: 'location_valid',    yes: 'Vị trí / địa chỉ đầy đủ',     no: 'Vị trí / địa chỉ thiếu thông tin' },
    { key: 'price_reasonable',  yes: 'Giá đã nhập',                  no: 'Chưa nhập giá' },
  ];
  var legalHtml = '';
  legalItems.forEach(function(item) {
    var pass = !!checks[item.key];
    var dotCls = pass ? 'yes' : 'no';
    var dotIcon = pass ? '✓' : '✕';
    var txt = pass ? item.yes : item.no;
    var style = pass ? '' : ' style="color:var(--danger);"';
    legalHtml += '<div class="abds-legal-item"><div class="abds-legal-dot ' + dotCls + '">' + dotIcon + '</div>'
      + '<span class="abds-legal-text"' + style + '>' + escHtml(txt) + '</span></div>';
  });

  var warningBanner = '';
  if(!p.all_checks_pass && p.status === 0) {
    warningBanner = '<div style="padding:8px 13px;background:var(--danger-light);border-top:1px solid #fca5a5;display:flex;gap:8px;align-items:center;">'
      + '<div><div style="font-size:11px;font-weight:700;color:var(--danger);">Không đủ điều kiện duyệt</div>'
      + '<div style="font-size:10px;color:#b91c1c;">Cần bổ sung thông tin trước khi duyệt</div></div></div>';
  }

  var rejectedBanner = '';
  if(p.status === 2 && p.rejection_reason) {
    rejectedBanner = '<div style="padding:8px 13px;background:#fef2f2;border-top:1px solid #fecaca;">'
      + '<div style="font-size:11px;font-weight:700;color:var(--danger);">Lý do từ chối: ' + escHtml(p.rejection_reason) + '</div>'
      + (p.rejection_note ? '<div style="font-size:10px;color:#b91c1c;">' + escHtml(p.rejection_note) + '</div>' : '')
      + '</div>';
  }

  var actionBtns = '';
  if(p.status === 0) {
    actionBtns =
      '<button class="abds-btn view" onclick="showToast(\'Xem chi tiết BĐS #' + p.id + '\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Xem</span></button>'
      + '<button class="abds-btn reject" onclick="openRejectSheet(' + p.id + ')">✕ Từ chối</button>'
      + '<button class="abds-btn approve" onclick="approveAbds(' + p.id + ',\'' + escHtml(p.title).replace(/'/g, "\\'") + '\')">✓ Duyệt</button>';
  } else {
    actionBtns =
      '<button class="abds-btn view" onclick="showToast(\'Xem chi tiết BĐS #' + p.id + '\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Xem</span></button>';
  }

  var addr = '';
  if(p.street) addr += p.street;
  if(p.ward) addr += (addr ? ', ' : '') + p.ward;
  if(addr) addr += ', TP.Đà Lạt';

  var specs = '';
  if(p.area) specs += '<div class="abds-spec"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>' + escHtml(p.area) + '</div>';
  if(p.number_room) specs += '<div class="abds-spec"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>' + escHtml(String(p.number_room)) + ' PN</div>';
  if(p.direction) specs += '<div class="abds-spec"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>' + escHtml(p.direction) + '</div>';

  return '<div class="abds-card" id="abds-' + p.id + '">'
    + '<div class="abds-body">'
    + '<div class="abds-title">' + escHtml(p.title || '') + '</div>'
    + (addr ? '<div class="abds-addr"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + escHtml(addr) + '</div>' : '')
    + (specs ? '<div class="abds-specs">' + specs + '</div>' : '')
    + (p.price ? '<div style="font-size:13px;font-weight:700;color:var(--primary);padding:4px 0 2px;">' + escHtml(String(p.price)) + '</div>' : '')
    + '</div>'
    + '<div class="abds-broker">'
    + '<div class="abds-broker-avatar">' + escHtml(p.broker_initials || 'BK') + '</div>'
    + '<span class="abds-broker-name">' + escHtml(p.broker_name || '') + ' · eBroker</span>'
    + '<span class="abds-broker-time">' + escHtml(p.created_at_fmt || '') + '</span>'
    + '</div>'
    + '<div class="abds-legal"><div class="abds-legal-title">Kiểm tra pháp lý</div>' + legalHtml + '</div>'
    + warningBanner
    + rejectedBanner
    + '<div class="abds-actions">' + actionBtns + '</div>'
    + '</div>';
}

window.openRejectSheet = function(propertyId) {
  currentRejectId = propertyId;
  document.querySelectorAll('.rs-reason').forEach(function(r) { r.classList.remove('selected'); });
  var noteEl = document.getElementById('rsNoteText');
  if(noteEl) noteEl.value = '';
  document.getElementById('rejectSheet').classList.add('open');
};

window.selectRejectReason = function(el) {
  document.querySelectorAll('.rs-reason').forEach(function(r) { r.classList.remove('selected'); });
  el.classList.add('selected');
};

window.submitReject = function() {
  var selected = document.querySelector('.rs-reason.selected');
  if(!selected) { showToast('Vui lòng chọn lý do từ chối'); return; }

  var reason = selected.getAttribute('data-reason') || '';
  var note = (document.getElementById('rsNoteText') ? document.getElementById('rsNoteText').value : '').trim();
  if(!currentRejectId) return;

  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  var url = cfg && cfg.adminPropertiesBase ? cfg.adminPropertiesBase + currentRejectId + '/reject' : null;
  var csrf = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if(!url || !csrf) { showToast('Lỗi cấu hình'); return; }

  var submitBtn = document.querySelector('#rejectSheet .rs-submit');
  if(submitBtn) submitBtn.disabled = true;

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({ reason: reason, note: note }),
  })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      document.getElementById('rejectSheet').classList.remove('open');
      if(submitBtn) submitBtn.disabled = false;
      if(data.success) {
        var card = document.getElementById('abds-' + currentRejectId);
        if(card) {
          card.style.transition = 'opacity .3s';
          card.style.opacity = '0';
          setTimeout(function() { if(card.parentNode) card.parentNode.removeChild(card); }, 300);
        }
        _abdsUpdatePendingCount(data.pending_count);
        showToast('✕ Đã từ chối — Broker đã được thông báo');
      } else {
        showToast(data.message || 'Có lỗi xảy ra');
      }
    })
    .catch(function() {
      if(submitBtn) submitBtn.disabled = false;
      showToast('Lỗi kết nối');
    });
};

window.approveAbds = function(propertyId, name) {
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  var url = cfg && cfg.adminPropertiesBase ? cfg.adminPropertiesBase + propertyId + '/approve' : null;
  var csrf = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if(!url || !csrf) { showToast('Lỗi cấu hình'); return; }

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({}),
  })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if(data.success) {
        var card = document.getElementById('abds-' + propertyId);
        if(card) {
          card.style.transition = 'opacity .3s';
          card.style.opacity = '0';
          setTimeout(function() { if(card.parentNode) card.parentNode.removeChild(card); }, 300);
        }
        _abdsUpdatePendingCount(data.pending_count);
        // Increment today count locally
        var todayEl = document.getElementById('abdsApprovedToday');
        if(todayEl) todayEl.textContent = (parseInt(todayEl.textContent, 10) || 0) + 1;
        showToast('✓ Đã duyệt: ' + (name || 'BĐS') + ' — Broker đã được thông báo');
      } else {
        showToast(data.message || 'Có lỗi xảy ra');
      }
    })
    .catch(function() { showToast('Lỗi kết nối'); });
};

function _abdsUpdatePendingCount(count) {
  if(count === undefined) return;
  var el = document.getElementById('abdsPendingCount');
  if(el) el.textContent = count;
  document.querySelectorAll('.abds-tab-count-pending').forEach(function(e) { e.textContent = count; });
  var heroMain = document.getElementById('abdsHeroMain');
  if(heroMain) heroMain.textContent = count + ' BĐS chờ xem xét';
}

document.getElementById('rejectSheet')?.addEventListener('click', function(e) {
  if(e.target === this) this.classList.remove('open');
});
document.getElementById('acommHoldSheet')?.addEventListener('click', function(e) {
  if(e.target === this) this.classList.remove('open');
});
document.getElementById('acommDetailSheet')?.addEventListener('click', function(e) {
  if(e.target === this) this.classList.remove('open');
});

// ============ ADMIN — DUYỆT HOA HỒNG ============
var acommCurrentTab = 'pending';
var acommHoldId = null;

window.switchAcommTab = function(tab, btn) {
  acommCurrentTab = tab;
  document.querySelectorAll('#acommTabBar .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  if(btn) btn.classList.add('active');
  loadApproveComm(true);
};

window.loadApproveComm = function(reset) {
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if(!cfg || !cfg.adminCommissionsJson) return;
  var url = cfg.adminCommissionsJson + '?tab=' + encodeURIComponent(acommCurrentTab);
  var container = document.getElementById('acommListContainer');
  if(!container) return;

  if(reset) {
    container.innerHTML =
      '<div style="padding:16px;">'
      + '<div style="height:140px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;opacity:.6;"></div>'
      + '<div style="height:140px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;opacity:.4;"></div>'
      + '</div>';
  }

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if(!data.success) {
        container.innerHTML = '<div style="text-align:center;padding:32px;color:var(--danger);">Lỗi tải dữ liệu.</div>';
        return;
      }
      var s = data.stats || {};
      // Update hero stats
      var heroMain = document.getElementById('acommHeroMain');
      if(heroMain) heroMain.textContent = (s.monthly_total_trieu || 0) + ' triệu tháng này';
      var elPending = document.getElementById('acommPendingCount');
      if(elPending) elPending.textContent = s.pending_count || 0;
      var elProcessing = document.getElementById('acommProcessingCount');
      if(elProcessing) elProcessing.textContent = s.processing_count || 0;
      var elMonthly = document.getElementById('acommMonthlyTotal');
      if(elMonthly) elMonthly.textContent = (s.monthly_total_trieu || 0) + 'tr';
      var elWaiting = document.getElementById('acommWaitingDeposit');
      if(elWaiting) elWaiting.textContent = s.waiting_deposit || 0;
      document.querySelectorAll('.acomm-tab-count-pending').forEach(function(el) {
        el.textContent = s.pending_count || 0;
      });
      document.querySelectorAll('.acomm-tab-count-processing').forEach(function(el) {
        el.textContent = s.processing_count || 0;
      });

      var comms = data.commissions || [];
      if(comms.length === 0) {
        container.innerHTML =
          '<div style="text-align:center;padding:48px 16px;color:var(--text-tertiary);">'
          + '<div style="font-size:14px;">Không có hoa hồng nào</div></div>';
        return;
      }
      var html = '';
      comms.forEach(function(c) { html += _renderAcommCard(c); });
      container.innerHTML = html;
    })
    .catch(function() {
      container.innerHTML =
        '<div style="text-align:center;padding:32px;color:var(--danger);">Lỗi kết nối. '
        + '<button onclick="loadApproveComm(true)" style="color:var(--primary);text-decoration:underline;background:none;border:none;cursor:pointer;">Thử lại</button></div>';
    });
};

function _renderAcommCard(c) {
  var status = c.status;

  // Stepper HTML
  var steps5 = [
    { label: 'Chốt giá',  svg: '✓' },
    { label: 'Chờ duyệt', svg: '⏳' },
    { label: 'Đặt cọc',   svg: '3' },
    { label: 'Công chứng',svg: '4' },
    { label: 'Hoàn tất',  svg: '5' },
  ];
  var steps4 = [
    { label: 'Chốt giá',  svg: '✓' },
    { label: 'Đặt cọc',   svg: '2' },
    { label: 'Công chứng',svg: '3' },
    { label: 'Hoàn tất',  svg: '4' },
  ];

  var stepperHtml = '';
  if(status === 'pending_deposit') {
    stepperHtml = _acommStepper5([
      { done: true, active: false, label: 'Chốt giá', dot: '✓' },
      { done: false, active: true,  label: 'Chờ duyệt',dot: '⏳'},
      { done: false, active: false, label: 'Đặt cọc',  dot: '3' },
      { done: false, active: false, label: 'Công chứng',dot:'4' },
      { done: false, active: false, label: 'Hoàn tất', dot: '5' },
    ]);
  } else if(status === 'deposited') {
    stepperHtml = _acommStepper5([
      { done: true, active: false, label: 'Chốt giá',   dot: '✓' },
      { done: true, active: false, label: 'Chờ duyệt',  dot: '✓' },
      { done: true, active: false, label: 'Đặt cọc',    dot: '✓' },
      { done: false, active: true, label: 'Công chứng', dot: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>' },
      { done: false, active: false, label: 'Hoàn tất', dot: '5' },
    ]);
  } else if(status === 'notarizing') {
    stepperHtml = _acommStepper5([
      { done: true, active: false, label: 'Chốt giá',   dot: '✓' },
      { done: true, active: false, label: 'Chờ duyệt',  dot: '✓' },
      { done: true, active: false, label: 'Đặt cọc',    dot: '✓' },
      { done: false, active: true, label: 'Công chứng', dot: '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>' },
      { done: false, active: false, label: 'Hoàn tất', dot: '5' },
    ]);
  } else {
    // completed
    stepperHtml = _acommStepper5([
      { done: true, active: false, label: 'Chốt giá',   dot: '✓' },
      { done: true, active: false, label: 'Chờ duyệt',  dot: '✓' },
      { done: true, active: false, label: 'Đặt cọc',    dot: '✓' },
      { done: true, active: false, label: 'Công chứng', dot: '✓' },
      { done: true, active: false, label: 'Hoàn tất',   dot: '✓' },
    ]);
  }

  // Deal detail rows
  var detailHtml =
    '<div class="acomm-detail-item"><div class="acomm-detail-label">Giá chốt</div><div class="acomm-detail-val">' + escHtml(String(c.deal_amount_trieu || 0)) + ' triệu</div></div>'
    + '<div class="acomm-detail-item"><div class="acomm-detail-label">Tổng HH (' + escHtml(String(c.comm_pct || 0)) + '%)</div><div class="acomm-detail-val">' + escHtml(String(c.total_trieu || 0)) + ' triệu</div></div>'
    + '<div class="acomm-detail-item"><div class="acomm-detail-label">Ngày chốt</div><div class="acomm-detail-val">' + escHtml(c.created_at_fmt || '—') + '</div></div>'
    + '<div class="acomm-detail-item"><div class="acomm-detail-label">Cọc dự kiến</div><div class="acomm-detail-val">' + escHtml(c.deposit_expected_date || '—') + '</div></div>';

  // Commission breakdown
  var brokerLine = c.broker_name
    ? '<div class="acomm-bl-row"><div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--teal);"></div>Broker (' + escHtml(c.broker_name) + ')</div><div><span class="acomm-bl-val">' + escHtml(String(c.owner_commission_trieu)) + ' tr</span><span class="acomm-bl-pct">' + escHtml(String(c.owner_pct)) + '%</span></div></div>'
    : '<div class="acomm-bl-row"><div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--teal);"></div>Broker</div><div><span class="acomm-bl-val">' + escHtml(String(c.owner_commission_trieu)) + ' tr</span><span class="acomm-bl-pct">' + escHtml(String(c.owner_pct)) + '%</span></div></div>';

  var breakdownHtml =
    '<div class="acomm-breakdown">'
    + '<div class="acomm-bl-title">Phân chia hoa hồng</div>'
    + '<div class="acomm-bl-row"><div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--primary);"></div>Sale (' + escHtml(c.sale_name || 'Sale') + ')</div><div><span class="acomm-bl-val">' + escHtml(String(c.sale_commission_trieu)) + ' tr</span><span class="acomm-bl-pct">' + escHtml(String(c.sale_pct)) + '%</span></div></div>'
    + '<div class="acomm-bl-row"><div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--purple);"></div>App (Đà Lạt BĐS)</div><div><span class="acomm-bl-val">' + escHtml(String(c.app_commission_trieu)) + ' tr</span><span class="acomm-bl-pct">' + escHtml(String(c.app_pct)) + '%</span></div></div>'
    + brokerLine
    + '</div>';

  // Notes timeline
  var timelineHtml = '';
  if(c.notes) {
    timelineHtml =
      '<div class="acomm-timeline">'
      + '<div class="acomm-tl-item"><div class="acomm-tl-dot" style="background:var(--primary);"></div>'
      + '<div class="acomm-tl-text">' + escHtml(c.notes) + '</div>'
      + '<div class="acomm-tl-time">' + escHtml(c.created_at_fmt || '') + '</div></div>'
      + '</div>';
  }

  // Action buttons per status
  var safeTitle  = (c.property_title || 'BĐS').replace(/'/g, "\\'");
  var safeTotal  = String(c.total_trieu || 0) + ' tr';
  var commJson   = JSON.stringify(c).replace(/</g, '\\u003c').replace(/'/g, "\\'");
  var actionHtml = '';

  if(status === 'pending_deposit') {
    actionHtml =
      '<button class="acomm-btn hold" onclick="holdComm(' + c.id + ')">⏸ Giữ lại</button>'
      + '<button class="acomm-btn detail" onclick="showCommDetail(\'' + commJson + '\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Hợp đồng</span></button>'
      + '<button class="acomm-btn approve" onclick="approveComm(' + c.id + ',\'' + safeTitle + '\',\'' + safeTotal + '\')">✓ Xác nhận & Chờ cọc</button>';
  } else if(status === 'deposited') {
    actionHtml =
      '<button class="acomm-btn detail" onclick="showCommDetail(\'' + commJson + '\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Chi tiết</span></button>'
      + '<button class="acomm-btn approve" onclick="advanceComm(' + c.id + ',\'' + safeTitle + '\')">✓ Xác nhận Công chứng</button>';
  } else if(status === 'notarizing') {
    actionHtml =
      '<button class="acomm-btn detail" onclick="showCommDetail(\'' + commJson + '\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Chi tiết</span></button>'
      + '<button class="acomm-btn approve" onclick="advanceComm(' + c.id + ',\'' + safeTitle + '\')">✓ Xác nhận Hoàn tất</button>';
  } else {
    actionHtml =
      '<button class="acomm-btn detail" onclick="showCommDetail(\'' + commJson + '\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg> Chi tiết</span></button>';
  }

  var amtColor = (status === 'notarizing') ? 'style="color:var(--warning);"' : '';
  var amtSuffix = (status === 'notarizing') ? 'Đang CN' : (escHtml(String(c.comm_pct)) + '% / ' + escHtml(String(c.deal_amount_trieu)) + ',000 tr');

  return '<div class="acomm-card" id="acomm-' + c.id + '">'
    + '<div class="acomm-head">'
    + '<div class="acomm-icon" style="background:var(--warning-light);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></div>'
    + '<div class="acomm-info"><div class="acomm-name">' + escHtml(c.property_title || 'BĐS') + '</div>'
    + '<div class="acomm-sub">' + escHtml(c.customer_name || 'Khách hàng') + ' · Sale: ' + escHtml(c.sale_name || '') + '</div></div>'
    + '<div class="acomm-amount"><div class="acomm-val" ' + amtColor + '>' + escHtml(String(c.total_trieu || 0)) + ' tr</div>'
    + '<div class="acomm-pct">' + amtSuffix + '</div></div>'
    + '</div>'
    + stepperHtml
    + '<div class="acomm-detail">' + detailHtml + '</div>'
    + breakdownHtml
    + timelineHtml
    + '<div class="acomm-actions">' + actionHtml + '</div>'
    + '</div>';
}

function _acommStepper5(steps) {
  var html = '<div class="acomm-stepper">';
  steps.forEach(function(s, i) {
    var dotCls = s.done ? 'done' : (s.active ? 'active' : '');
    var labelCls = s.done ? 'done' : (s.active ? 'active' : '');
    html += '<div class="cs-step"><div class="cs-dot ' + dotCls + '" style="display:flex;align-items:center;justify-content:center;">' + s.dot + '</div><div class="cs-label ' + labelCls + '">' + escHtml(s.label) + '</div></div>';
    if(i < steps.length - 1) {
      html += '<div class="cs-line' + (s.done ? ' done' : '') + '"></div>';
    }
  });
  html += '</div>';
  return html;
}

window.approveComm = function(id, name, amount) {
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  var url = cfg && cfg.adminCommissionsBase ? cfg.adminCommissionsBase + id + '/approve' : null;
  var csrf = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if(!url || !csrf) { showToast('Lỗi cấu hình'); return; }

  var card = document.getElementById('acomm-' + id);
  if(card) { card.style.opacity = '0.5'; card.style.pointerEvents = 'none'; }

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({}),
  })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if(data.success) {
        if(card) {
          card.style.transition = 'opacity .3s';
          card.style.opacity = '0';
          setTimeout(function() { if(card.parentNode) card.parentNode.removeChild(card); }, 300);
        }
        _acommUpdatePendingCount(data.pending_count);
        showToast('✓ Đã duyệt: ' + (name || 'Hoa hồng') + ' — ' + (amount || '') + ' → Chờ cọc');
      } else {
        if(card) { card.style.opacity = '1'; card.style.pointerEvents = ''; }
        showToast(data.message || 'Có lỗi xảy ra');
      }
    })
    .catch(function() {
      if(card) { card.style.opacity = '1'; card.style.pointerEvents = ''; }
      showToast('Lỗi kết nối');
    });
};

window.advanceComm = function(id, name) {
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  var url = cfg && cfg.adminCommissionsBase ? cfg.adminCommissionsBase + id + '/advance' : null;
  var csrf = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if(!url || !csrf) { showToast('Lỗi cấu hình'); return; }

  var card = document.getElementById('acomm-' + id);
  if(card) { card.style.opacity = '0.5'; card.style.pointerEvents = 'none'; }

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({}),
  })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if(data.success) {
        if(card) {
          card.style.transition = 'opacity .3s';
          card.style.opacity = '0';
          setTimeout(function() { if(card.parentNode) card.parentNode.removeChild(card); }, 300);
        }
        var label = data.new_status === 'completed' ? 'Hoàn tất' : 'Công chứng';
        showToast('✓ ' + (name || 'Hoa hồng') + ' → ' + label);
      } else {
        if(card) { card.style.opacity = '1'; card.style.pointerEvents = ''; }
        showToast(data.message || 'Có lỗi xảy ra');
      }
    })
    .catch(function() {
      if(card) { card.style.opacity = '1'; card.style.pointerEvents = ''; }
      showToast('Lỗi kết nối');
    });
};

window.holdComm = function(id) {
  acommHoldId = id;
  var noteEl = document.getElementById('acommHoldNote');
  if(noteEl) noteEl.value = '';
  document.getElementById('acommHoldSheet').classList.add('open');
};

window.submitHoldComm = function() {
  if(!acommHoldId) return;
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  var url = cfg && cfg.adminCommissionsBase ? cfg.adminCommissionsBase + acommHoldId + '/hold' : null;
  var csrf = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if(!url || !csrf) { showToast('Lỗi cấu hình'); return; }

  var note = (document.getElementById('acommHoldNote') ? document.getElementById('acommHoldNote').value : '').trim();
  var submitBtn = document.querySelector('#acommHoldSheet .rs-submit');
  if(submitBtn) submitBtn.disabled = true;

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({ note: note }),
  })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      document.getElementById('acommHoldSheet').classList.remove('open');
      if(submitBtn) submitBtn.disabled = false;
      if(data.success) {
        showToast('⏸ Đã giữ lại — Sale đã được thông báo');
      } else {
        showToast(data.message || 'Có lỗi xảy ra');
      }
    })
    .catch(function() {
      if(submitBtn) submitBtn.disabled = false;
      showToast('Lỗi kết nối');
    });
};

window.showCommDetail = function(commJsonStr) {
  var c;
  try { c = JSON.parse(commJsonStr); } catch(e) { showToast('Lỗi dữ liệu'); return; }
  var titleEl = document.getElementById('acommDetailTitle');
  if(titleEl) titleEl.textContent = c.property_title || 'Chi tiết hoa hồng';
  var bodyEl = document.getElementById('acommDetailBody');
  if(bodyEl) {
    bodyEl.innerHTML =
      '<div style="padding:0 0 8px;">'
      + '<div style="font-size:12px;color:var(--text-tertiary);margin-bottom:12px;">'
      + escHtml(c.customer_name || '') + ' · Sale: ' + escHtml(c.sale_name || '') + '</div>'
      + '<div class="acomm-detail" style="margin-bottom:12px;">'
      + '<div class="acomm-detail-item"><div class="acomm-detail-label">Giá chốt</div><div class="acomm-detail-val">' + escHtml(String(c.deal_amount_trieu || 0)) + ' triệu</div></div>'
      + '<div class="acomm-detail-item"><div class="acomm-detail-label">Tổng HH (' + escHtml(String(c.comm_pct || 0)) + '%)</div><div class="acomm-detail-val">' + escHtml(String(c.total_trieu || 0)) + ' triệu</div></div>'
      + '<div class="acomm-detail-item"><div class="acomm-detail-label">Ngày chốt</div><div class="acomm-detail-val">' + escHtml(c.created_at_fmt || '—') + '</div></div>'
      + '<div class="acomm-detail-item"><div class="acomm-detail-label">Cọc dự kiến</div><div class="acomm-detail-val">' + escHtml(c.deposit_expected_date || '—') + '</div></div>'
      + '</div>'
      + '<div class="acomm-bl-title">Phân chia hoa hồng</div>'
      + '<div class="acomm-bl-row"><div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--primary);"></div>Sale (' + escHtml(c.sale_name || 'Sale') + ')</div><div><span class="acomm-bl-val">' + escHtml(String(c.sale_commission_trieu)) + ' tr</span><span class="acomm-bl-pct">' + escHtml(String(c.sale_pct)) + '%</span></div></div>'
      + '<div class="acomm-bl-row"><div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--purple);"></div>App (Đà Lạt BĐS)</div><div><span class="acomm-bl-val">' + escHtml(String(c.app_commission_trieu)) + ' tr</span><span class="acomm-bl-pct">' + escHtml(String(c.app_pct)) + '%</span></div></div>'
      + '<div class="acomm-bl-row"><div class="acomm-bl-who"><div class="acomm-bl-who-dot" style="background:var(--teal);"></div>Broker' + (c.broker_name ? ' (' + escHtml(c.broker_name) + ')' : '') + '</div><div><span class="acomm-bl-val">' + escHtml(String(c.owner_commission_trieu)) + ' tr</span><span class="acomm-bl-pct">' + escHtml(String(c.owner_pct)) + '%</span></div></div>'
      + (c.notes ? '<div style="margin-top:12px;padding:10px;background:var(--bg-secondary);border-radius:8px;font-size:12px;color:var(--text-secondary);">📝 ' + escHtml(c.notes) + '</div>' : '')
      + '</div>';
  }
  document.getElementById('acommDetailSheet').classList.add('open');
};

function _acommUpdatePendingCount(count) {
  if(count === undefined) return;
  var el = document.getElementById('acommPendingCount');
  if(el) el.textContent = count;
  document.querySelectorAll('.acomm-tab-count-pending').forEach(function(el) {
    el.textContent = count;
  });
  var heroMain = document.getElementById('acommHeroMain');
  if(heroMain && count !== undefined) {
    // Keep existing text, just update the pending count display
  }
}

// ============ ADMIN — REPORT ============
var _rpLoading = false;

window.switchRpTab = function(btn, period) {
  btn.closest('.report-period').querySelectorAll('.rp-tab').forEach(function(t) { t.classList.remove('active'); });
  btn.classList.add('active');
  loadAdminReports(period || 'month');
};

window.loadAdminReports = function(period) {
  if (_rpLoading) return;
  var url = (window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes && window.WEBAPP_CONFIG.routes.adminReportsJson)
    ? window.WEBAPP_CONFIG.routes.adminReportsJson + '?period=' + (period || 'month')
    : null;
  if (!url) return;
  _rpLoading = true;
  var loading = document.getElementById('rp-loading');
  var grid    = document.getElementById('rp-metrics-grid');
  if (loading) loading.style.display = 'block';
  if (grid)    grid.style.opacity    = '0.4';
  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      _rpLoading = false;
      if (loading) loading.style.display = 'none';
      if (grid)    grid.style.opacity    = '1';
      renderAdminReports(data);
    })
    .catch(function() {
      _rpLoading = false;
      if (loading) loading.style.display = 'none';
      if (grid)    grid.style.opacity    = '1';
    });
};

window.renderAdminReports = function(d) {
  // Hero
  _rpTxt('rp-hero-label',   d.hero.label);
  _rpTxt('rp-hero-revenue', d.hero.revenue_label);
  _rpTxt('rp-stat-deals',   d.hero.deals_closed);
  _rpTxt('rp-stat-comm',    d.hero.commission_label);
  _rpTxt('rp-stat-live',    Number(d.hero.live_bds).toLocaleString('vi-VN'));
  _rpTxt('rp-stat-views',   Number(d.hero.total_views).toLocaleString('vi-VN'));

  // Metric cards
  _rpSetMetric('revenue', d.metrics.revenue);
  _rpSetMetric('deals',   d.metrics.deals);
  _rpSetMetric('comm',    d.metrics.commission);
  _rpSetMetric('cust',    d.metrics.new_customers);

  // Bar chart
  var barEl = document.getElementById('rp-bar-chart');
  if (barEl && d.bar_chart && d.bar_chart.length) {
    var maxVal = Math.max.apply(null, d.bar_chart.map(function(b) { return b.value; })) || 0.1;
    barEl.innerHTML = d.bar_chart.map(function(b) {
      var pct   = Math.round((b.value / maxVal) * 100);
      var color = b.is_current ? 'var(--success)' : 'var(--primary-light)';
      var style = b.is_current ? 'color:var(--success);font-weight:700;' : '';
      return '<div class="bc-col">' +
        '<div class="bc-val" style="' + style + '">' + b.value + '</div>' +
        '<div class="bc-bars"><div class="bc-seg" style="height:' + pct + '%;background:' + color + ';"></div></div>' +
        '<div class="bc-label" style="' + style + '">' + b.label + '</div>' +
        '</div>';
    }).join('');
  }

  // Funnel ring
  var ring = document.getElementById('rp-stat-ring');
  if (ring) ring.style.setProperty('--pct', d.funnel.conv_rate + '%');
  _rpTxt('rp-ring-val',      d.funnel.conv_rate + '%');
  _rpTxt('rp-funnel-leads',  d.funnel.leads);
  _rpTxt('rp-funnel-deals',  d.funnel.deals_created);
  _rpTxt('rp-funnel-closed', d.funnel.closed);
  _rpTxt('rp-funnel-lost',   d.funnel.lost);

  // Top brokers
  var tbEl = document.getElementById('rp-top-brokers');
  if (tbEl) {
    if (!d.top_brokers || d.top_brokers.length === 0) {
      tbEl.innerHTML = '<tr><td colspan="4" style="text-align:center;color:var(--text-tertiary);padding:16px 0;font-size:12px;">Chưa có dữ liệu</td></tr>';
    } else {
      var rankClasses = ['rank-1', 'rank-2', 'rank-3'];
      tbEl.innerHTML = d.top_brokers.map(function(b, i) {
        var rc     = rankClasses[i] || 'rank-n';
        var bold   = i < 3 ? 'font-weight:600;' : '';
        var revCol = i === 0 ? 'color:var(--success);' : i === 1 ? 'color:var(--primary);' : '';
        var rev    = b.revenue_raw > 0
          ? '<span style="' + revCol + '">' + _esc(b.revenue_label) + '</span>'
          : '<span style="color:var(--text-tertiary);">—</span>';
        return '<tr>' +
          '<td><span class="rank-badge ' + rc + '">' + b.rank + '</span></td>' +
          '<td style="' + bold + '">' + _esc(b.name) + '</td>' +
          '<td>' + b.deals + '</td>' +
          '<td>' + rev + '</td>' +
          '</tr>';
      }).join('');
    }
  }

  // Property types
  var ptEl = document.getElementById('rp-prop-types');
  if (ptEl) {
    if (!d.property_types || d.property_types.length === 0) {
      ptEl.innerHTML = '<div style="text-align:center;color:var(--text-tertiary);font-size:12px;padding:8px 0;">Chưa có dữ liệu</div>';
    } else {
      var typeColors = ['var(--primary)', 'var(--purple)', 'var(--teal)', 'var(--warning)', 'var(--danger)'];
      ptEl.innerHTML = d.property_types.map(function(t, i) {
        var col = typeColors[i] || 'var(--primary)';
        return '<div style="display:flex;align-items:center;gap:10px;">' +
          '<span style="font-size:12px;min-width:70px;color:var(--text-secondary);">' + _esc(t.name) + '</span>' +
          '<div style="flex:1;height:8px;background:var(--border);border-radius:4px;overflow:hidden;">' +
            '<div style="height:100%;width:' + t.pct + '%;background:' + col + ';border-radius:4px;"></div>' +
          '</div>' +
          '<span style="font-size:12px;font-weight:700;color:' + col + ';min-width:28px;">' + t.pct + '%</span>' +
          '</div>';
      }).join('');
    }
  }
};

function _rpTxt(id, val) {
  var el = document.getElementById(id);
  if (el) el.textContent = val != null ? val : '—';
}
function _rpSetMetric(key, m) {
  _rpTxt('rp-mc-' + key + '-val', m.label);
  var el = document.getElementById('rp-mc-' + key + '-delta');
  if (!el) return;
  if (m.delta == null) { el.textContent = '—'; el.className = 'mc2-delta'; return; }
  var isUp  = m.delta_dir === 'up';
  var sign  = isUp ? '↑ +' : '↓ ';
  var value = m.delta_abs ? Math.abs(m.delta) : Math.abs(m.delta) + '%';
  el.textContent = sign + value + ' so kỳ trước';
  el.className   = 'mc2-delta ' + (isUp ? 'up' : 'dn');
}

window.toggleScDetail = function(id){
  const el = document.getElementById(id);
  if(!el) return;
  const isOpen = el.classList.contains('open');
  document.querySelectorAll('.sc-detail.open').forEach(d=>d.classList.remove('open'));
  if(!isOpen) el.classList.add('open');
};

window.switchScTab = function(btn){
  btn.closest('.sc-detail-tabs').querySelectorAll('.sc-dtab').forEach(t=>t.classList.remove('active'));
  btn.classList.add('active');
};

// ---- Assign Lead logic ----
let selectedLeads = new Set();
let currentPickLeads = [];
let selectedSale = null;

window.toggleUlSelect = function(id){
  const card = document.getElementById(id);
  const cb = document.getElementById(id+'-cb');
  if(!card || !cb) return;
  const isSelected = card.classList.contains('selected');
  if(isSelected){
    card.classList.remove('selected');
    cb.textContent = '○';
    selectedLeads.delete(id);
  } else {
    card.classList.add('selected');
    cb.textContent = '✓';
    selectedLeads.add(id);
  }
  updateAssignCta();
};

function updateAssignCta(){
  const cta = document.getElementById('assignCta');
  const countEl = document.getElementById('assignCtaCount');
  const btn = document.getElementById('assignCtaBtn');
  const n = selectedLeads.size;
  if(n > 0){
    cta.style.display = 'flex';
    countEl.textContent = n + ' lead được chọn';
    btn.disabled = false;
  } else {
    cta.style.display = 'none';
  }
}

window.getSelectedLeads = function(){
  return [...selectedLeads];
};

window.openSalePicker = function(leads){
  currentPickLeads = leads;
  selectedSale = null;
  // reset picker state
  document.querySelectorAll('.spi-check').forEach(el=>el.textContent='○');
  document.querySelectorAll('.sale-pick-item').forEach(el=>el.classList.remove('selected'));
  document.getElementById('spAssignBtn').disabled = true;
  const sub = document.getElementById('salePickerSub');
  sub.textContent = `Đang assign ${leads.length} lead · Chọn Sale phù hợp`;
  document.getElementById('salePicker').classList.add('open');
};

window.selectSalePick = function(item, saleId){
  document.querySelectorAll('.sale-pick-item').forEach(i=>i.classList.remove('selected'));
  document.querySelectorAll('.spi-check').forEach(el=>el.textContent='○');
  item.classList.add('selected');
  document.getElementById('sp-'+saleId).textContent = '✓';
  selectedSale = saleId;
  document.getElementById('spAssignBtn').disabled = false;
};

window.confirmAssign = function(){
  if (!selectedSale) { showToast('Vui lòng chọn Sale'); return; }
  if (!currentPickLeads || currentPickLeads.length === 0) { showToast('Không có lead nào được chọn'); return; }

  // Extract integer lead IDs from DOM ids (format: 'ull-{id}')
  var leadIds = currentPickLeads.map(function(domId){
    return parseInt(String(domId).replace(/^ull-/, ''), 10);
  }).filter(function(id){ return !isNaN(id) && id > 0; });

  if (leadIds.length === 0) { showToast('Không tìm thấy lead ID hợp lệ'); return; }

  var cfg  = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  var csrf = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if (!cfg || !cfg.bulkAssign) { showToast('Lỗi: Thiếu cấu hình endpoint'); return; }

  var assignBtn = document.getElementById('spAssignBtn');
  if (assignBtn) { assignBtn.disabled = true; assignBtn.textContent = 'Đang assign...'; }

  fetch(cfg.bulkAssign, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({ lead_ids: leadIds, sale_id: selectedSale }),
  })
  .then(function(r){ return r.json(); })
  .then(function(res){
    var picker = document.getElementById('salePicker');
    if (picker) picker.classList.remove('open');

    if (res.success) {
      var assignedCount = res.assigned_count || 0;
      var skippedCount  = (res.skipped_ids || []).length;

      // Remove assigned cards from DOM
      (res.assigned_ids || []).forEach(function(id){
        var domId = 'ull-' + id;
        var card  = document.getElementById(domId);
        if (card) card.style.display = 'none';
        selectedLeads.delete(domId);
      });
      // Clear skipped from selection too
      (res.skipped_ids || []).forEach(function(id){
        selectedLeads.delete('ull-' + id);
      });

      updateAssignCta();

      // Update unassigned count
      var remain = document.querySelectorAll('#unassignedLeadsList .ul-card:not([style*="display: none"])').length;
      var countEl = document.getElementById('unassignedCount');
      if (countEl) countEl.textContent = remain + ' lead';
      var tabEl = document.getElementById('tabUnassigned');
      if (tabEl) tabEl.textContent = 'Chờ assign (' + remain + ')';

      // Decrement budget counts in cache
      if (_assignLeadData) {
        (res.assigned_ids || []).forEach(function(id){
          var lead = (_assignLeadData.leads || []).find(function(l){ return l.id === id; });
          if (lead) {
            var tier = lead.budget_tier;
            _assignLeadData.budget_counts[tier] = Math.max(0, (_assignLeadData.budget_counts[tier] || 0) - 1);
          }
        });
        var bc = _assignLeadData.budget_counts;
        var hEl = document.getElementById('budgetHigh');
        var mEl = document.getElementById('budgetMedium');
        var lEl = document.getElementById('budgetLow');
        if (hEl) hEl.textContent = bc.high   || 0;
        if (mEl) mEl.textContent = bc.medium  || 0;
        if (lEl) lEl.textContent = bc.low     || 0;
      }

      // Refresh history tab
      loadAssignHistoryOnly();

      var saleName = res.sale_name || 'Sale';
      var msg = '✓ Đã assign ' + assignedCount + ' lead cho ' + saleName;
      if (skippedCount > 0) msg += ' (' + skippedCount + ' đã được assign trước)';
      showToast(msg);
    } else {
      showToast(res.message || 'Lỗi assign lead');
    }
  })
  .catch(function(){
    var picker = document.getElementById('salePicker');
    if (picker) picker.classList.remove('open');
    showToast('Lỗi kết nối, vui lòng thử lại');
  })
  .finally(function(){
    if (assignBtn) {
      assignBtn.disabled = true;
      assignBtn.textContent = '✓ Xác nhận Assign';
    }
  });
};

// Close sale picker on backdrop
document.getElementById('salePicker')?.addEventListener('click', function(e){
  if(e.target === this) this.classList.remove('open');
});

// ---- Assign Lead: tab switch ----
window.assignLeadTabSwitch = function(btn){
  btn.closest('.sp-tabs').querySelectorAll('.sp-tab').forEach(function(t){ t.classList.remove('active'); });
  btn.classList.add('active');
  var tab = btn.dataset.tab;
  var unassignedPanel = document.getElementById('assignLeadTabUnassigned');
  var historyPanel    = document.getElementById('assignLeadTabHistory');
  if (unassignedPanel) unassignedPanel.style.display = tab === 'unassigned' ? '' : 'none';
  if (historyPanel)    historyPanel.style.display    = tab === 'history'    ? '' : 'none';
};

// ---- Assign Lead: data loading ----
var _assignLeadData = null;

function loadAssignLeadData(){
  _assignLeadData = null;
  selectedLeads.clear();
  updateAssignCta();

  var loadingEl = document.getElementById('assignLeadLoading');
  var emptyEl   = document.getElementById('assignLeadEmpty');
  var listEl    = document.getElementById('unassignedLeadsList');
  if (loadingEl) { loadingEl.style.display = ''; }
  if (emptyEl)   { emptyEl.style.display = 'none'; }
  if (listEl)    { listEl.style.display = 'none'; listEl.innerHTML = ''; }

  // Ensure unassigned tab is active
  var tabUnassigned = document.getElementById('tabUnassigned');
  var tabHistory    = document.querySelector('[data-tab="history"]');
  var panelUnassigned = document.getElementById('assignLeadTabUnassigned');
  var panelHistory    = document.getElementById('assignLeadTabHistory');
  if (tabUnassigned)  tabUnassigned.classList.add('active');
  if (tabHistory)     tabHistory.classList.remove('active');
  if (panelUnassigned) panelUnassigned.style.display = '';
  if (panelHistory)    panelHistory.style.display = 'none';

  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if (!cfg || !cfg.assignData) { showToast('Lỗi: Không tìm thấy endpoint assign data'); return; }

  fetch(cfg.assignData, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
  .then(function(r){ return r.json(); })
  .then(function(data){
    if (!data.success) {
      if (loadingEl) loadingEl.style.display = 'none';
      if (emptyEl)   emptyEl.style.display = '';
      return;
    }
    _assignLeadData = data;

    var bc = data.budget_counts || {};
    var hEl = document.getElementById('budgetHigh');
    var mEl = document.getElementById('budgetMedium');
    var lEl = document.getElementById('budgetLow');
    if (hEl) hEl.textContent = bc.high   || 0;
    if (mEl) mEl.textContent = bc.medium  || 0;
    if (lEl) lEl.textContent = bc.low     || 0;

    var total   = (data.leads || []).length;
    var countEl = document.getElementById('unassignedCount');
    if (countEl) countEl.textContent = total + ' lead';
    var tabEl = document.getElementById('tabUnassigned');
    if (tabEl) tabEl.textContent = 'Chờ assign (' + total + ')';

    if (loadingEl) loadingEl.style.display = 'none';

    if (!data.leads || data.leads.length === 0) {
      if (emptyEl) emptyEl.style.display = '';
    } else {
      if (listEl) { listEl.innerHTML = renderUnassignedLeadCards(data.leads); listEl.style.display = ''; }
    }

    renderSalePickerList(data.sales || []);
    renderAssignHistory(data.history || []);
  })
  .catch(function(){
    if (loadingEl) loadingEl.style.display = 'none';
    if (emptyEl)   emptyEl.style.display = '';
    showToast('Lỗi tải dữ liệu assign lead');
  });
}

function loadAssignHistoryOnly(){
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if (!cfg || !cfg.assignData) return;
  fetch(cfg.assignData, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
  .then(function(r){ return r.json(); })
  .then(function(data){
    if (data.success) {
      renderAssignHistory(data.history || []);
      if (_assignLeadData) _assignLeadData.history = data.history;
    }
  })
  .catch(function(){});
}

function renderUnassignedLeadCards(leads){
  var svgPhone  = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg>';
  var svgFlame  = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2c0 0-7 6-7 12a7 7 0 0 0 14 0c0-6-7-12-7-12z"/></svg>';
  var svgAssign = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';

  var priorityConfig = {
    hot:    { badge: 'badge-red',   label: 'Hot',        color: 'var(--danger)',        tagStyle: 'background:#fee2e2;color:#dc2626;' },
    medium: { badge: 'badge-amber', label: 'Trung bình', color: 'var(--warning)',       tagStyle: 'background:#fef3c7;color:#d97706;' },
    normal: { badge: 'badge-blue',  label: 'Bình thường',color: 'var(--primary)',       tagStyle: 'background:#dbeafe;color:#1d4ed8;' },
    low:    { badge: 'badge-gray',  label: 'Thấp',       color: 'var(--text-tertiary)', tagStyle: 'background:var(--bg-secondary);color:var(--text-secondary);' },
  };
  var avatarColors = ['#ef4444', 'var(--teal)', 'var(--purple)', '#f59e0b', 'var(--primary)', '#059669'];

  return leads.map(function(lead, index){
    var domId    = 'ull-' + lead.id;
    var pCfg     = priorityConfig[lead.priority] || priorityConfig.normal;
    var avatarBg = avatarColors[index % avatarColors.length];

    var catStr  = (lead.categories || []).join(', ');
    var wardStr = (lead.wards || []).join(', ');
    var needStr = [lead.lead_type, lead.purpose].filter(Boolean).join(' ');

    var rows = '';
    if (catStr)  rows += '<div class="ul-row"><span class="ul-label">Loại BĐS</span><span class="ul-value">' + escHtml(catStr) + '</span></div>';
    if (needStr) rows += '<div class="ul-row"><span class="ul-label">Nhu cầu</span><span class="ul-value">' + escHtml(needStr) + '</span></div>';
    if (lead.budget_min || lead.budget_max)
                 rows += '<div class="ul-row"><span class="ul-label">Ngân sách</span><span class="ul-value money">' + escHtml(lead.budget_min + ' – ' + lead.budget_max) + '</span></div>';
    if (wardStr) rows += '<div class="ul-row"><span class="ul-label">Khu vực</span><span class="ul-value">' + escHtml(wardStr) + '</span></div>';

    var tags = [];
    (lead.categories || []).forEach(function(c){ if(c) tags.push(c); });
    if (lead.budget_min && lead.budget_max) tags.push(lead.budget_min + '–' + lead.budget_max);
    (lead.wards || []).forEach(function(w){ if(w) tags.push(w); });
    var tagsHtml = tags.slice(0, 4).map(function(t){ return '<span class="ul-tag">' + escHtml(t) + '</span>'; }).join('');
    if (lead.priority === 'hot') {
      tagsHtml += '<span class="ul-tag" style="' + pCfg.tagStyle + 'display:inline-flex;align-items:center;gap:3px;">' + svgFlame + ' Ưu tiên cao</span>';
    }

    var timeStyle    = 'color:' + pCfg.color + ';font-weight:600;display:inline-flex;align-items:center;gap:3px;';
    var suggestionTxt = lead.suggestion ? 'Gợi ý: ' + lead.suggestion + ' (phù hợp khu vực)' : 'Chưa có gợi ý';

    return '<div class="ul-card" id="' + domId + '" onclick="toggleUlSelect(\'' + domId + '\')">'
      + '<div class="ul-head">'
        + '<div class="ul-checkbox" id="' + domId + '-cb">○</div>'
        + '<div style="width:38px;height:38px;border-radius:50%;background:' + avatarBg + ';display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">' + escHtml(lead.initials) + '</div>'
        + '<div class="ul-info">'
          + '<div class="ul-name">' + escHtml(lead.name) + '</div>'
          + '<div class="ul-meta">'
            + (lead.phone ? '<span>' + svgPhone + escHtml(lead.phone) + '</span>' : '')
            + '<span style="' + timeStyle + '">' + svgFlame + escHtml(lead.time_ago) + '</span>'
          + '</div>'
        + '</div>'
        + '<div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">'
          + '<span class="badge ' + pCfg.badge + '">' + pCfg.label + '</span>'
          + '<span style="font-size:10px;color:var(--text-tertiary);">' + escHtml(lead.source_note) + '</span>'
        + '</div>'
      + '</div>'
      + (rows ? '<div class="ul-body">' + rows + '</div>' : '')
      + (tagsHtml ? '<div class="ul-tags">' + tagsHtml + '</div>' : '')
      + '<div class="ul-footer">'
        + '<span style="font-size:11px;color:var(--text-tertiary);">' + escHtml(suggestionTxt) + '</span>'
        + '<button class="lc-btn primary" style="height:28px;font-size:11px;" onclick="event.stopPropagation();openSalePicker([\'' + domId + '\'])">'
          + '<span style="display:inline-flex;align-items:center;gap:4px;">' + svgAssign + ' Assign ngay</span>'
        + '</button>'
      + '</div>'
      + '</div>';
  }).join('');
}

function renderSalePickerList(sales){
  var container = document.getElementById('salePickerList');
  if (!container) return;
  if (sales.length === 0) {
    container.innerHTML = '<div style="padding:24px;text-align:center;color:var(--text-tertiary);">Không có sale nào</div>';
    return;
  }
  var avatarColors = ['var(--primary)', 'var(--teal)', '#f59e0b', '#8b5cf6', '#059669', '#ef4444'];
  container.innerHTML = sales.map(function(sale, index){
    var wClass   = sale.workload === 'high' ? 'high' : (sale.workload === 'mid' ? 'mid' : 'low');
    var cntLabel = sale.active_leads + ' deal' + (sale.active_leads !== 1 ? 's' : '');
    var avatarBg = avatarColors[index % avatarColors.length];
    return '<div class="sale-pick-item" onclick="selectSalePick(this,' + sale.id + ')">'
      + '<div class="spi-avatar" style="background:' + avatarBg + '">' + escHtml(sale.initials) + '</div>'
      + '<div class="spi-info">'
        + '<div class="spi-name">' + escHtml(sale.name) + '</div>'
        + '<div class="spi-meta">' + escHtml(sale.active_leads + ' lead đang chăm') + '</div>'
      + '</div>'
      + '<div class="spi-workload"><span class="spi-leads ' + wClass + '">' + escHtml(cntLabel) + '</span></div>'
      + '<span class="spi-check" id="sp-' + sale.id + '">○</span>'
      + '</div>';
  }).join('');
}

function renderAssignHistory(history){
  var listEl  = document.getElementById('assignHistoryList');
  var emptyEl = document.getElementById('assignHistoryEmpty');
  if (!listEl) return;
  if (!history || history.length === 0) {
    listEl.innerHTML = '';
    if (emptyEl) emptyEl.style.display = '';
    return;
  }
  if (emptyEl) emptyEl.style.display = 'none';
  var avatarColors = ['var(--primary)', 'var(--teal)', '#f59e0b', '#8b5cf6', '#059669'];
  listEl.innerHTML = history.map(function(item, index){
    var avatarBg = avatarColors[index % avatarColors.length];
    return '<div class="ah-item">'
      + '<div class="ah-avatar" style="background:' + avatarBg + ';">' + escHtml(item.sale_initials) + '</div>'
      + '<div class="ah-info">'
        + '<div class="ah-name">' + escHtml(item.sale_name) + '</div>'
        + '<div class="ah-detail">Nhận lead: ' + escHtml(item.customer_name) + ' · ' + escHtml(item.lead_type) + ' ' + escHtml(item.budget_label) + '</div>'
      + '</div>'
      + '<div class="ah-time">' + escHtml(item.time_label) + '</div>'
      + '</div>';
  }).join('');
}

// ============ SEARCH STATE MACHINE ============
// States: discovery | suggestions | results
let searchState = 'discovery';
let searchTypingTimer = null;

window.activateSearch = function(){
  const box = document.querySelector('.search-box-main');
  const placeholder = document.getElementById('searchPlaceholder');
  const input = document.getElementById('searchInput');
  box.classList.add('focused');
  placeholder.style.display = 'none';
  input.style.display = 'block';
  input.focus();
};

window.onSearchType = function(val){
  clearTimeout(searchTypingTimer);
  
  const clearBtn = document.getElementById('clearSearchBtn');
  if(clearBtn) {
      clearBtn.style.display = val.length > 0 ? 'block' : 'none';
  }

  if(val.length === 0){
    if(currentSearchMode === 'bds') setState('discovery');
    else if(currentSearchMode === 'lead') fetchLeads('');
    return;
  }

  if(currentSearchMode === 'lead') {
    clearTimeout(searchTypingTimer);
    searchTypingTimer = setTimeout(() => fetchLeads(val), 500);
    return;
  }
  if(document.getElementById('stateSuggestions').innerHTML.includes('suggest-item')) {
    setState('suggestions');
  } else {
    document.getElementById('stateSuggestions').innerHTML = '<div style="padding:16px;text-align:center;color:var(--text-tertiary);font-size:13px;">Đang tìm kiếm...</div>';
    setState('suggestions');
  }

  searchTypingTimer = setTimeout(()=>{
    fetchSuggestions(val);
  }, 500);
};

window.resetSearch = function(e, fromResults = false) {
    if(e) e.stopPropagation();
    
    const input = document.getElementById('searchInput');
    const placeholder = document.getElementById('searchPlaceholder');
    const box = document.querySelector('.search-box-main');
    const clearBtn = document.getElementById('clearSearchBtn');
    
    input.value = '';
    placeholder.textContent = 'Tìm BĐS, đường, phường...';
    
    if(clearBtn) clearBtn.style.display = 'none';
    
    if(fromResults) {
        // Nếu click từ nút Back, ẩn input và hiện placeholder như ban đầu
        input.style.display = 'none';
        placeholder.style.display = 'block';
        box.classList.remove('focused');
        clearFilters(true); // Xóa hết bộ lọc hiện tại
    } else {
        // Nếu click chữ X trong ô input, vẫn focus vào ô input
        input.focus();
    }
    
    setState('discovery');
};

window.fetchSuggestions = function(query) {
  fetch('/webapp/search/suggestions?q=' + encodeURIComponent(query))
    .then(r => r.json())
    .then(res => {
        if(res.success) {
            renderSuggestions(res.data);
            setState('suggestions');
        }
    });
};

function renderSuggestions(data) {
    const container = document.getElementById('stateSuggestions');
    if(!container) return;
    
    let html = '<div style="padding:8px 16px 0;">';
    
    if(data.length === 0) {
        html += '<div style="padding:10px;text-align:center;color:var(--text-tertiary);font-size:13px;">Không tìm thấy kết quả phù hợp</div>';
    } else {
        data.forEach(item => {
            let iconSvg = '';
            let badgeClass = 'badge-blue';
            let typeLabel = 'BĐS';
            
            if(item.type === 'street') {
                iconSvg = '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>';
                badgeClass = 'badge-blue';
                typeLabel = 'Đường';
            } else if(item.type === 'ward') {
                iconSvg = '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>';
                badgeClass = 'badge-teal';
                typeLabel = 'Phường';
            } else {
                iconSvg = '<path d="M12 22c4.97-5 9-8.58 9-12a9 9 0 0 0-18 0c0 3.42 4.03 7 9 12z"/><circle cx="12" cy="10" r="3"/>';
                badgeClass = 'badge-green';
                typeLabel = 'BĐS';
            }

            // Property suggestions: open detail directly; others: doSearch
            const clickAction = item.type === 'property' && item.id 
                ? `openPropertyById(${item.id})` 
                : `doSearch('${item.query.replace(/'/g,"\\'")}')`;

            html += `
            <div class="suggest-item" onclick="${clickAction}">
              <span class="suggest-icon"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">${iconSvg}</svg></span>
              <div class="suggest-body">
                <div class="suggest-title">${item.title}</div>
                <div class="suggest-sub">${item.sub}</div>
              </div>
              <span class="suggest-type badge ${badgeClass}">${typeLabel}</span>
            </div>
            `;
        });
    }
    html += '</div>';
    container.innerHTML = html;
}

window.showSuggestions = function(){
  if(currentSearchMode !== 'bds') return;
  const input = document.getElementById('searchInput');
  if(input.value.length > 0) {
      if(document.getElementById('stateSuggestions').innerHTML.includes('suggest-item')) {
          setState('suggestions');
      } else {
          onSearchType(input.value);
      }
  }
};

function setState(state){
  searchState = state;
  document.getElementById('stateDiscovery').style.display = state==='discovery'?'block':'none';
  document.getElementById('stateSuggestions').style.display = state==='suggestions'?'block':'none';
  document.getElementById('stateResults').style.display = state==='results'?'block':'none';
}

// Helper: Rebuild activeFilters from currentFilters
function rebuildActiveFilters() {
  const afContainer = document.getElementById('activeFilters');
  afContainer.querySelectorAll('.af-chip').forEach(c => c.remove());

  const labelMap = {
    property_type: v => v === '0' ? 'Bán' : 'Cho thuê',
    categoryName: v => v,
    price: v => v,
    location: v => v,
    area: v => v.includes('+') ? 'Trên 1000m²' : v.replace('-', '–') + 'm²',
    direction: v => v,
    legal: v => v
  };

  Object.entries(currentFilters).forEach(([key, val]) => {
    if(val) {
      const label = labelMap[key] ? labelMap[key](val) : val;
      addActiveFilter(label);
    }
  });
}

// Helper: Update filter count badge
function updateFilterCountBadge() {
  let count = Object.values(currentFilters).filter(v => v).length;
  const fc = document.getElementById('filterCount');
  if(fc) {
    if(count > 0) { fc.textContent = count; fc.style.display = 'flex'; }
    else { fc.style.display = 'none'; }
  }
}

// Helper: Sync quick-chip rows with filter sheet
function syncQuickChipRows() {
  // Category row
  const catRow = document.getElementById('resultsFilterCategory');
  if(catRow) {
    catRow.querySelectorAll('.chip').forEach(c => {
      const onclick = c.getAttribute('onclick') || '';
      const match = onclick.match(/doSearchCategory\('([^']*)'/);
      if(match) c.classList.toggle('active', match[1] === currentFilters.categoryName);
    });
    catRow.style.display = currentFilters.categoryName ? 'none' : '';
  }

  // Price row
  const priceRow = document.getElementById('resultsFilterPrice');
  if(priceRow) {
    priceRow.querySelectorAll('.chip').forEach(c => {
      const onclick = c.getAttribute('onclick') || '';
      const match = onclick.match(/doSearchPrice\('([^']*)'/);
      if(match) c.classList.toggle('active', match[1] === currentFilters.price);
    });
    priceRow.style.display = currentFilters.price ? 'none' : '';
  }

  updateFilterCountBadge();
}

window.doSearchCategory = function(categoryName, chipEl) {
  // Mark chip active
  if(chipEl) {
      chipEl.closest('.filter-bar').querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
      chipEl.classList.add('active');
  }

  // Sync with filter sheet
  document.querySelectorAll('.fs-chip[data-filter="categoryName"]').forEach(c => {
    c.classList.toggle('active', c.dataset.value === categoryName);
  });

  // Update currentFilters
  currentFilters.categoryName = categoryName;

  // Rebuild activeFilters from currentFilters
  rebuildActiveFilters();

  // Hide/show category row
  const catRow = document.getElementById('resultsFilterCategory');
  if(catRow) catRow.style.display = categoryName ? 'none' : '';

  updateFilterCountBadge();

  let q = document.getElementById('searchInput').value || '';
  doSearch(q || null);
};

window.doSearchPrice = function(priceLabel, chipEl) {
  // Mark chip active
  if(chipEl) {
      chipEl.closest('.filter-bar').querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
      chipEl.classList.add('active');
  }

  // Sync with filter sheet
  document.querySelectorAll('.fs-chip[data-filter="price"]').forEach(c => {
    c.classList.toggle('active', c.dataset.value === priceLabel);
  });

  // Update currentFilters
  currentFilters.price = priceLabel;

  // Rebuild activeFilters
  rebuildActiveFilters();

  // Hide/show price row
  const priceRow = document.getElementById('resultsFilterPrice');
  if(priceRow) priceRow.style.display = priceLabel ? 'none' : '';

  updateFilterCountBadge();

  let q = document.getElementById('searchInput').value || '';
  doSearch(q || null);
};

window.doSearchLocation = function(locationName, chipEl) {
  // Mark chip active
  if(chipEl) {
      chipEl.closest('.filter-bar').querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
      chipEl.classList.add('active');
  }

  // Sync with filter sheet
  document.querySelectorAll('.fs-chip[data-filter="location"]').forEach(c => {
    c.classList.toggle('active', c.dataset.value === locationName);
  });

  // Update currentFilters
  currentFilters.location = locationName;

  // Rebuild activeFilters
  rebuildActiveFilters();

  // Hide/show location row
  const locationRow = document.getElementById('resultsFilterLocation');
  if(locationRow) locationRow.style.display = locationName ? 'none' : '';

  updateFilterCountBadge();

  let q = document.getElementById('searchInput').value || '';
  doSearch(q || null);
};

window.doSearch = function(query, chipEl, append = false, page = 1){
  // Ensure BDS mode is active — hide lead tab content and mark BDS tab as active
  if(currentSearchMode !== 'bds') {
    currentSearchMode = 'bds';
    const leadTab = document.getElementById('leadTabContent');
    if(leadTab) leadTab.style.display = 'none';
    document.querySelectorAll('#searchModeTabs .smt').forEach(b => b.classList.remove('active'));
    const bdsBtn = document.querySelector('#searchModeTabs .smt:first-child');
    if(bdsBtn) bdsBtn.classList.add('active');
  }

  // Update search bar
  const placeholder = document.getElementById('searchPlaceholder');
  const input = document.getElementById('searchInput');
  const box = document.querySelector('.search-box-main');
  box.classList.add('focused');
  
  if(query !== undefined && query !== null) {
      placeholder.textContent = query;
      input.value = query;
  } else {
      query = input.value;
  }
  
  placeholder.style.display = 'block';
  input.style.display = 'none';

  // Save to recent searches
  if(query && query.trim() && query !== 'Tất cả') {
      saveRecentSearch(query.trim());
  }

  // Update result header
  document.getElementById('resultQuery').textContent = query;
  
  // Update filter count badge based on activeFilters div
  const activeChips = document.getElementById('activeFilters').querySelectorAll('.af-chip');
  const fc = document.getElementById('filterCount');
  if(activeChips.length > 0) {
      fc.textContent = activeChips.length;
      fc.style.display = 'flex';
  } else {
      fc.style.display = 'none';
  }

  setState('results');
  
  if(!append) {
      switchView('list');
      document.getElementById('scrollArea').scrollTop = 0;
      document.getElementById('listView').innerHTML = '<div style="padding:20px;text-align:center;"><div class="spinner" style="display:inline-block;width:24px;height:24px;border:3px solid var(--border);border-top:3px solid var(--primary);border-radius:50%;animation:spin 1s linear infinite;"></div><div style="margin-top:10px;font-size:13px;color:var(--text-secondary);">Đang tìm kiếm...</div></div>';
  }
  
  // build filter params from active chips
  let price = '';
  let categoryName = '';

  activeChips.forEach(c => {
      let txt = c.textContent.replace('×', '').trim();
      if(c.dataset.filterType === 'price' || txt.includes('tỷ')) price = txt;
      else if(c.dataset.filterType === 'category') categoryName = txt;
  });

  // If chipEl was passed (quick filter chip)
  if(chipEl && chipEl.classList.contains('chip')) {
      let txt = chipEl.textContent.trim();
      if(txt !== 'Tất cả') {
          addActiveFilter(txt);
          if(chipEl.dataset.filterType === 'price' || txt.includes('tỷ')) price = txt;
          else if(chipEl.dataset.filterType === 'category') categoryName = txt;
      } else {
         clearFilters(true);
         categoryName = '';
         price = '';
      }
      const newActiveChips = document.getElementById('activeFilters').querySelectorAll('.af-chip');
      if(newActiveChips.length > 0) {
          fc.textContent = newActiveChips.length;
          fc.style.display = 'flex';
      } else {
          fc.style.display = 'none';
      }
  }

  // Merge chip values with currentFilters (currentFilters takes precedence if set from filter sheet/quick chips)
  const effectivePrice = price || currentFilters.price || '';
  const effectiveCategory = categoryName || currentFilters.categoryName || '';

  // Build URL with all params
  let url = '/webapp/search/results?page=' + page;
  if(query && query !== 'Tất cả') url += '&q=' + encodeURIComponent(query);
  if(effectivePrice) url += '&price=' + encodeURIComponent(effectivePrice);
  if(effectiveCategory) url += '&categoryName=' + encodeURIComponent(effectiveCategory);

  // Advanced filter params (from filter sheet)
  if(currentSort && currentSort !== 'latest') url += '&sort=' + encodeURIComponent(currentSort);
  if(currentFilters.property_type) url += '&type=' + encodeURIComponent(currentFilters.property_type);
  if(currentFilters.location) url += '&location=' + encodeURIComponent(currentFilters.location);
  if(currentFilters.area) url += '&area_range=' + encodeURIComponent(currentFilters.area);
  if(currentFilters.direction) url += '&direction=' + encodeURIComponent(currentFilters.direction);
  if(currentFilters.legal) url += '&legal=' + encodeURIComponent(currentFilters.legal);
  
  fetch(url)
    .then(r => r.json())
    .then(res => {
        if(res.success) {
            document.getElementById('resultCount').textContent = res.total + ' kết quả';
            renderSearchResults(res, append, page, query, price, categoryName);
        }
    });
};

function renderSearchResults(res, append, currentPage, query, price, categoryName) {
    const listView = document.getElementById('listView');
    
    if(res.properties.length === 0 && !append) {
        listView.innerHTML = '<div style="padding:40px 20px;text-align:center;color:var(--text-tertiary);"><div style="margin-bottom:10px;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg></div><div>Không tìm thấy BĐS nào phù hợp.</div></div>';
        return;
    }
    
    let html = '';
    res.properties.forEach(p => {
        const propJson = JSON.stringify(p).replace(/'/g,'\\u0027');
        
        let tagsHtml = '';
        if(p.type_label) tagsHtml += `<span class="badge badge-blue" style="font-size:9px;padding:2px 6px;">${p.type_label}</span>`;
        if(p.legal) tagsHtml += `<span class="badge badge-green" style="font-size:9px;padding:2px 6px;">${p.legal}</span>`;

        const isLiked = !!(p.id && window.likedIds && window.likedIds.has(String(p.id)));
        
        html += `
        <div class="result-card" data-prop='${propJson}' onclick="openDetail(JSON.parse(this.dataset.prop))" style="cursor:pointer">
          <div class="rc-img">
            ${p.title_image ? `<img src="${p.title_image}" style="width:100%;height:100%;object-fit:cover;display:block;" alt="">` : '<div style="display:flex;align-items:center;justify-content:center;height:100%;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>'}
          </div>
          <div class="rc-body">
            <div class="rc-tags">
              ${tagsHtml}
            </div>
            <div class="rc-title">${p.title}</div>
            <div class="rc-price">${p.price}</div>
            <div class="rc-meta">
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></span>${p.area||'--'}</span>
              <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span><span class="role-broker role-sale role-bds_admin role-sale_admin role-admin">${p.location}</span><span class="role-guest">${p.location}</span></span>
            </div>
            <div class="rc-footer">
              <span class="rc-time">${p.created_at_diff}</span>
              <div style="display:flex;gap:6px;">
                <button class="rc-btn${isLiked?' liked':''}" style="background:transparent;color:var(--primary);" onclick="event.stopPropagation();toggleBookmark(this,${p.id})"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="${isLiked?'var(--primary)':'none'}" stroke="var(--primary)" stroke-width="1.7"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span></button>
                <button class="rc-btn role-sale role-bds_admin role-sale_admin role-admin" style="background:var(--primary-light);color:var(--primary);" onclick="event.stopPropagation();careForProperty(${p.id},'${(p.title||'').replace(/'/g,"\\'")}')"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Chăm</span></button>
              </div>
            </div>
          </div>
        </div>
        `;
    });
    
    // pagination button
    if(res.has_more) {
        html += `
        <div style="padding:16px;text-align:center;" id="loadMoreContainer">
          <button style="padding:11px 28px;border:1.5px solid var(--border);border-radius:20px;font-size:13px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);" onclick="loadMoreSearchResult(this, '${query}', '${price}', '${categoryName}', ${currentPage+1})">Xem thêm kết quả</button>
        </div>
        `;
    }
    
    if(append) {
        const btnContainer = document.getElementById('loadMoreContainer');
        if(btnContainer) btnContainer.remove();
        listView.insertAdjacentHTML('beforeend', html);
    } else {
        listView.innerHTML = html;
    }
    
    applyDetailRole();
}

window.loadMoreSearchResult = function(btn, query, price, categoryName, nextPage) {
    btn.textContent = 'Đang tải...';
    btn.disabled = true;
    doSearch(query, null, true, nextPage);
};

window.addActiveFilter = function(txt) {
    const afContainer = document.getElementById('activeFilters');
    let exists = false;
    afContainer.querySelectorAll('.af-chip').forEach(c => {
        if(c.textContent.replace('×', '').trim() === txt) exists = true;
    });
    if(!exists) {
        const chip = document.createElement('div');
        chip.className = 'af-chip';
        chip.innerHTML = `${txt} <span onclick="removeFilter(this)">×</span>`;
        const clearBtn = afContainer.querySelector('.af-clear');
        if(clearBtn) {
            afContainer.insertBefore(chip, clearBtn);
            clearBtn.style.display = '';
        } else {
            afContainer.appendChild(chip);
        }
    }
};

window.switchView = function(view){
  document.getElementById('listView').style.display = view==='list'?'block':'none';
  document.getElementById('mapView').style.display = view==='map'?'block':'none';
  document.getElementById('viewList').classList.toggle('active', view==='list');
  document.getElementById('viewMap').classList.toggle('active', view==='map');
};

let currentSearchMode = 'bds';
let currentLeadStatus = '';

window.switchMode = function(mode, btn){
  if(mode === 'area') mode = 'bds';
  btn.closest('.search-mode-tabs').querySelectorAll('.smt').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  currentSearchMode = mode;
  
  // Toggle tab content visibility
  const bdsElements = ['stateDiscovery','stateSuggestions','stateResults'];
  bdsElements.forEach(id => {
    const el = document.getElementById(id);
    if(el) el.style.display = (mode === 'bds' && id === 'stateDiscovery') ? 'block' : (mode === 'bds' ? el.style.display : 'none');
  });
  
  // Reset BDS states when switching away
  if(mode !== 'bds') {
    document.getElementById('stateDiscovery').style.display = 'none';
    document.getElementById('stateSuggestions').style.display = 'none';
    document.getElementById('stateResults').style.display = 'none';
  } else {
    setState('discovery');
  }
  
  const leadTab = document.getElementById('leadTabContent');
  
  if(leadTab) leadTab.style.display = mode === 'lead' ? 'block' : 'none';
  
  // Update search placeholder
  const placeholder = document.getElementById('searchPlaceholder');
  if(mode === 'bds') placeholder.textContent = 'Tìm BĐS, đường, phường...';
  else if(mode === 'lead') placeholder.textContent = 'Tìm khách hàng, lead...';
  
  // Fetch data for the tab
  if(mode === 'lead') fetchLeads();
};

// ============ LEAD TAB (Task 7-9) ============
function fetchLeads(q, page) {
    q = q || '';
    page = page || 1;
    
    let url = '/webapp/search/leads?page=' + page;
    if(q) url += '&q=' + encodeURIComponent(q);
    if(currentLeadStatus) url += '&status=' + encodeURIComponent(currentLeadStatus);
    
    const container = document.getElementById('leadResults');
    if(page === 1) {
        container.innerHTML = '<div style="padding:20px;text-align:center;"><div class="spinner" style="display:inline-block;width:24px;height:24px;border:3px solid var(--border);border-top:3px solid var(--primary);border-radius:50%;animation:spin 1s linear infinite;"></div></div>';
    }
    
    fetch(url)
        .then(r => r.json())
        .then(res => {
            if(res.success) renderLeads(res, page > 1, page, q);
        });
}

function renderLeads(res, append, currentPage, query) {
    const container = document.getElementById('leadResults');
    
    if(res.leads.length === 0 && !append) {
        container.innerHTML = '<div style="padding:40px 20px;text-align:center;color:var(--text-tertiary);"><div style="font-size:32px;margin-bottom:10px;">👤</div><div>Không tìm thấy lead nào.</div></div>';
        return;
    }
    
    let html = '';
    const statusClasses = { 'new':'urgent', 'contacted':'contacted', 'qualified':'contacted', 'won':'converted', 'lost':'' };
    const badgeColors = { 'new':'badge-red', 'contacted':'badge-blue', 'qualified':'badge-blue', 'won':'badge-green', 'lost':'badge-red' };

    res.leads.forEach(lead => {
        const rawStatus = lead.status || 'new';
        const sClass = statusClasses[rawStatus] || '';
        const bClass = badgeColors[rawStatus] || 'badge-blue';
        const initials = lead.customer_name ? lead.customer_name.split(' ').map(n => n[0]).join('').substring(0,2).toUpperCase() : '??';
        const avatarBg = rawStatus === 'new' ? '#ef4444' : (rawStatus === 'won' ? 'var(--success)' : 'var(--primary)');
        const expandId = `lead-${lead.id}-expand`;

        html += `
        <div class="lead-card ${sClass}">
          <div class="lc-head">
            <div class="lc-avatar" style="background:${avatarBg};">${initials}</div>
            <div class="lc-info">
              <div class="lc-name">${lead.customer_name}</div>
              <div class="lc-meta">
                <span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>${lead.customer_phone || '--'}</span>
                ${rawStatus === 'new' ? '<span style="color:var(--danger);font-weight:600"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span> Chưa liên hệ!</span>' : ''}
              </div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
              <span class="badge ${bClass}">${lead.status_label}</span>
              <span class="lc-time">${lead.created_at_diff}</span>
            </div>
          </div>
          <div class="lc-body">
            <div class="lc-row"><span class="lc-label">Nhu cầu</span><span class="lc-value">${lead.lead_type || '—'}</span></div>
            <div class="lc-row"><span class="lc-label">Ngân sách</span><span class="lc-value money">${(lead.budget_min||'?') + ' - ' + (lead.budget_max||'?')}</span></div>
            <div class="lc-row" style="grid-column: span 2;"><span class="lc-label">Ghi chú</span><span class="lc-value" style="font-weight:400;color:var(--text-secondary);font-style:italic;">${lead.note || '—'}</span></div>
          </div>
          <div class="lc-footer">
            <span class="lc-source"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg></span> ${lead.source || 'Website'}</span>
            <div class="lc-actions">
              <button class="lc-btn icon" onclick="showToast('Đang gọi ${lead.customer_phone}...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></button>
              ${rawStatus === 'new' ? `<button class="lc-btn primary" onclick="toggleLeadExpand('${expandId}')">Xử lý ▾</button>` : `<button class="lc-btn success" onclick="showToast('✓ Đang tạo Deal cho ${lead.customer_name}...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg> Tạo Deal</span></button>`}
            </div>
          </div>
          <div class="lc-expand" id="${expandId}">
            <div style="font-size:11px;font-weight:600;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Cập nhật trạng thái</div>
            <div class="lc-action-row">
              <div class="lc-action-btn" onclick="selectLeadAction(this);toggleLeadExpand('${expandId}');"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>Đã gọi</div>
              <div class="lc-action-btn" onclick="selectLeadAction(this);toggleLeadExpand('${expandId}');"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>Zalo</div>
              <div class="lc-action-btn" onclick="selectLeadAction(this);toggleLeadExpand('${expandId}');"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>Hẹn gặp</div>
              <div class="lc-action-btn" onclick="selectLeadAction(this);toggleLeadExpand('${expandId}');"><span class="lc-action-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg></span>Tạo Deal</div>
            </div>
            <textarea class="lc-note-area" rows="2" placeholder="Ghi chú nhanh cho ${lead.customer_name}..."></textarea>
            <div style="display:flex;gap:8px;">
              <button class="lc-btn" onclick="toggleLeadExpand('${expandId}')" style="flex:1">Đóng</button>
              <button class="lc-btn success" onclick="showToast('✓ Đã lưu cập nhật lead');toggleLeadExpand('${expandId}');" style="flex:2">✓ Lưu & Cập nhật</button>
            </div>
          </div>
        </div>
        `;
    });
    
    if(res.has_more) {
        html += `<div style="padding:16px;text-align:center;" id="leadLoadMore"><button style="padding:11px 28px;border:1.5px solid var(--border);border-radius:20px;font-size:13px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);" onclick="this.textContent='Đang tải...';fetchLeads('${query||''}',${currentPage+1})">Xem thêm</button></div>`;
    }
    
    if(append) {
        const btn = document.getElementById('leadLoadMore');
        if(btn) btn.remove();
        container.insertAdjacentHTML('beforeend', html);
    } else {
        container.innerHTML = html;
    }
}

window.filterLeadByStatus = function(chip) {
    chip.parentElement.querySelectorAll('.fs-chip').forEach(c => c.classList.remove('active'));
    chip.classList.add('active');
    currentLeadStatus = chip.dataset.leadStatus || '';
    fetchLeads(document.getElementById('searchInput').value || '');
};



// ============ FILTER SHEET ============
let currentFilters = { property_type:'', categoryName:'', price:'', area:'', direction:'', legal:'' };

window.openFilterSheet = function(){
  document.getElementById('filterOverlay').classList.add('open');
  document.getElementById('filterSheet').classList.add('open');
};

window.closeFilterSheet = function(){
  document.getElementById('filterOverlay').classList.remove('open');
  document.getElementById('filterSheet').classList.remove('open');
};

window.selectFilterChip = function(chip){
  const filterGroup = chip.dataset.filter;
  const siblings = chip.parentElement.querySelectorAll('.fs-chip[data-filter="'+filterGroup+'"]');
  siblings.forEach(c => c.classList.remove('active'));
  chip.classList.add('active');
};

window.resetFilterSheet = function(){
  document.querySelectorAll('.fs-chip').forEach(c => {
      c.classList.remove('active');
      if(c.dataset.value === '') c.classList.add('active');
  });
  currentFilters = { property_type:'', categoryName:'', price:'', area:'', direction:'', legal:'' };
};

window.applyFilterSheet = function(){
  // Collect selected values
  currentFilters = { property_type:'', categoryName:'', price:'', area:'', direction:'', legal:'' };
  document.querySelectorAll('.fs-chip.active').forEach(c => {
      if(c.dataset.value) {
          currentFilters[c.dataset.filter] = c.dataset.value;
      }
  });

  // Update active filter chips in results
  clearFilters(true);
  Object.entries(currentFilters).forEach(([key, val]) => {
      if(val) {
          const labels = {
              property_type: val === '0' ? 'Bán' : 'Cho thuê',
              categoryName: val,
              price: val,
              area: val.includes('+') ? 'Trên 1000m²' : val.replace('-','–') + 'm²',
              direction: val,
              legal: val
          };
          addActiveFilter(labels[key] || val);
      }
  });

  // Sync quick-chip rows with filter sheet selections
  syncQuickChipRows();

  closeFilterSheet();

  // Re-search with filters
  let q = document.getElementById('searchInput').value || '';
  doSearch(q);
};

// ============ SORT SHEET ============
let currentSort = 'latest';

window.openSortSheet = function(){
  document.getElementById('sortOverlay').classList.add('open');
  document.getElementById('sortSheet').classList.add('open');
};

window.closeSortSheet = function(){
  document.getElementById('sortOverlay').classList.remove('open');
  document.getElementById('sortSheet').classList.remove('open');
};

window.selectSort = function(opt){
  document.querySelectorAll('.ss-option').forEach(o => {
      o.classList.remove('active');
      o.querySelector('.ss-check').textContent = '';
  });
  opt.classList.add('active');
  opt.querySelector('.ss-check').textContent = '✓';
  currentSort = opt.dataset.sort;
  
  // Update sort button text
  const sortLabels = { latest:'Mới nhất', oldest:'Cũ nhất', price_asc:'Giá ↑', price_desc:'Giá ↓', area_asc:'DT ↑', area_desc:'DT ↓' };
  const sortBtnEl = document.querySelector('.sort-btn');
  if(sortBtnEl) sortBtnEl.innerHTML = '<span>↕</span> ' + (sortLabels[currentSort] || 'Sắp xếp');
  
  closeSortSheet();
  
  // Re-search with new sort
  let q = document.getElementById('searchInput').value || '';
  doSearch(q);
};

// ============ RECENT SEARCHES (localStorage) ============
const RECENT_KEY = 'dalatbds_recent_searches';
const MAX_RECENT = 10;

function getRecentSearches() {
    try { return JSON.parse(localStorage.getItem(RECENT_KEY)) || []; }
    catch(e) { return []; }
}

function saveRecentSearch(query) {
    let list = getRecentSearches();
    list = list.filter(q => q !== query);
    list.unshift(query);
    if(list.length > MAX_RECENT) list = list.slice(0, MAX_RECENT);
    localStorage.setItem(RECENT_KEY, JSON.stringify(list));
    renderRecentSearches();
}

let recentSearchesExpanded = false;

function toggleRecentSearches() {
    recentSearchesExpanded = !recentSearchesExpanded;
    renderRecentSearches();
}

function renderRecentSearches() {
    const container = document.getElementById('recentSearchesList');
    const section = document.getElementById('recentSearchesSection');
    if(!container || !section) return;

    const list = getRecentSearches();
    if(list.length === 0) {
        section.style.display = 'none';
        return;
    }
    section.style.display = 'block';

    // Show only 5 items by default, or all if expanded
    const itemsToShow = recentSearchesExpanded ? list : list.slice(0, 5);

    let html = '';
    itemsToShow.forEach(q => {
        html += '<div class="recent-item" onclick="doSearch(\'' + q.replace(/'/g,"\\'") + '\')"><span class="ri"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span><span style="flex:1">' + q + '</span><span style="color:var(--text-tertiary);font-size:13px;">↗</span></div>';
    });

    // Add "View more" button if there are more than 5 items
    if(list.length > 5) {
        const buttonText = recentSearchesExpanded ? 'Ẩn bớt' : 'Xem thêm';
        html += '<div style="text-align:center;padding:8px 0;"><button onclick="toggleRecentSearches()" style="font-size:12px;color:var(--primary);background:none;border:none;cursor:pointer;font-weight:600;">+ ' + buttonText + '</button></div>';
    }

    container.innerHTML = html;
}

window.clearRecentSearches = function() {
    localStorage.removeItem(RECENT_KEY);
    renderRecentSearches();
};

// Init recent searches on page load
renderRecentSearches();

// ============ CARE FOR PROPERTY (Chăm sóc) ============
window.careForProperty = function(propId, title) {
  const note = encodeURIComponent('Quan tâm BĐS #' + propId + ': ' + title);
  window.location.href = '/webapp/add-customer?note=' + note + '&property_id=' + propId;
};

window.removeFilter = function(span){
  const chip = span.closest('.af-chip');
  const label = chip.textContent.replace('×', '').trim();

  // Map label back to filter key and reset it
  const labelMap = {
    property_type: v => v === '0' ? 'Bán' : 'Cho thuê',
    categoryName: v => v,
    price: v => v,
    location: v => v,
    area: v => v.includes('+') ? 'Trên 1000m²' : v.replace('-', '–') + 'm²',
    direction: v => v,
    legal: v => v
  };

  // Find which filter key this label belongs to
  Object.entries(currentFilters).forEach(([key, val]) => {
    if(val) {
      const displayLabel = labelMap[key] ? labelMap[key](val) : val;
      if(displayLabel === label) {
        // Reset this filter
        currentFilters[key] = '';

        // Show filter buttons again
        const filterRowId = {
          categoryName: 'resultsFilterCategory',
          price: 'resultsFilterPrice',
          location: 'resultsFilterLocation'
        }[key];

        if(filterRowId) {
          const row = document.getElementById(filterRowId);
          if(row) {
            row.style.display = '';
            row.querySelectorAll('.chip').forEach(c => {
              c.classList.toggle('active', c.textContent.trim() === 'Tất cả');
            });
          }
        }

        // Sync filter sheet
        document.querySelectorAll(`.fs-chip[data-filter="${key}"]`).forEach(c => {
          c.classList.toggle('active', c.dataset.value === '');
        });
      }
    }
  });

  // Remove chip and rebuild
  chip.remove();
  const afContainer2 = document.getElementById('activeFilters');
  const clearBtn2 = afContainer2.querySelector('.af-clear');
  if(clearBtn2 && afContainer2.querySelectorAll('.af-chip').length === 0) clearBtn2.style.display = 'none';
  updateFilterCountBadge();

  let searchInput = document.getElementById('searchInput');
  doSearch(searchInput.value || '');
};

window.clearFilters = function(silent = false){
  const afEl = document.getElementById('activeFilters');
  afEl.querySelectorAll('.af-chip').forEach(c=>c.remove());
  const clearBtnEl = afEl.querySelector('.af-clear');
  if(clearBtnEl) clearBtnEl.style.display = 'none';

  // Only reset UI and filters if not silent
  if(silent !== true) {
    // Reset quick-chip rows to "Tất cả" and show them
    ['resultsFilterCategory', 'resultsFilterPrice', 'resultsFilterLocation'].forEach(id => {
      const row = document.getElementById(id);
      if(!row) return;
      row.style.display = '';
      row.querySelectorAll('.chip').forEach(c => {
        c.classList.toggle('active', c.textContent.trim() === 'Tất cả');
      });
    });

    // Reset currentFilters
    currentFilters = { property_type:'', categoryName:'', price:'', location:'', area:'', direction:'', legal:'' };
    updateFilterCountBadge();

    let searchInput = document.getElementById('searchInput');
    doSearch(searchInput.value || '');
  }
};

window.showMapCard = function(idx){
  document.querySelectorAll('.map-pin').forEach(p=>p.classList.remove('active'));
  event.currentTarget.classList.add('active');
};

// ============ DETAIL PAGE ============
let galleryCurrentIdx = 0;
let gallerTotal = 1;
let descExpanded = false;
let bookmarked = false;
let selectedDeal = null;
let currentDetailPhone = null;  // host phone for callOwner
let currentDetailPropId = null; // property id for logging
let currentDetailTitle = null;  // property title for logging

// ---- gallery helpers ----
function buildGallery(images){
  const slidesEl = document.getElementById('gallerySlides');
  if(!slidesEl) return;
  slidesEl.innerHTML = '';
  const imgs = (images && images.length) ? images : [''];
  gallerTotal = imgs.length;
  imgs.forEach((src,i)=>{
    const div = document.createElement('div');
    div.className = 'gallery-slide';
    div.id = 'gslide-'+i;
    div.style.background = '#1e2a3a';
    if(src){
      const img = document.createElement('img');
      img.src = src; img.alt = 'Ảnh BĐS '+(i+1);
      img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';
      div.appendChild(img);
    }
    slidesEl.appendChild(div);
  });
  const dotsEl = document.getElementById('galleryDots');
  if(dotsEl){
    dotsEl.innerHTML='';
    imgs.forEach((_,i)=>{
      const d=document.createElement('div');
      d.className='gdot'+(i===0?' active':'');
      dotsEl.appendChild(d);
    });
  }
  const gt = document.getElementById('galleryTotal');
  if(gt) gt.textContent = gallerTotal;
  galleryCurrentIdx = 0;
  updateGallery();
}

// ---- spec helpers ----
function setDetailText(id, val){ const el=document.getElementById(id); if(el) el.textContent = val||''; }
function showHideEl(id, show){ const el=document.getElementById(id); if(el) el.style.display = show ? '' : 'none'; }

function populateBasic(d){
  // Store for logging
  currentDetailPropId = d.id || null;
  currentDetailTitle  = d.title || null;

  setDetailText('detailTitle', d.title||'Chi tiết BĐS');
  setDetailText('detailPrice', d.price||'--');
  setDetailText('detailPriceM2', d.priceM2||'');
  setDetailText('detailType', d.type||'BĐS');
  setDetailText('detailArea', d.area||'—');
  setDetailText('detailRoom', d.room||'—');
  setDetailText('detailAddr', d.addr||'Đà Lạt');
  setDetailText('detailHeaderTitle', d.title||'Chi tiết BĐS');
  setDetailText('bookingPropName', (d.title||'BĐS').substring(0,50));
  setDetailText('detailDirection', d.direction||'—');
  setDetailText('detailViews', d.views !== undefined ? d.views : '0');

  // transaction badge
  const tbadge = document.getElementById('detailTransactionBadge');
  if(tbadge){
    const isRent = d.transactionType === 'rent' || d.property_type == 1;
    tbadge.textContent = isRent ? 'Cho thuê' : 'Đang bán';
    tbadge.style.background = isRent ? 'rgba(234,179,8,0.15)' : 'rgba(34,197,94,0.15)';
    tbadge.style.color = isRent ? '#b45309' : '#15803d';
  }

  // show/hide direction & room boxes
  showHideEl('detailDirectionBox', !!(d.direction && d.direction !== '—'));
  showHideEl('detailRoomBox', !!(d.room && d.room !== '—'));

  // build gallery from basic images
  buildGallery(d.images||[]);
}

function populateFull(d){
  // basic fields again (fresher data)
  populateBasic(d);

  // ---- spec section ----
  const specGrid = document.getElementById('specGrid');

  // remove previously added dynamic items
  specGrid.querySelectorAll('.spec-item-dyn').forEach(el=>el.remove());

  // area
  if(d.area){ setDetailText('specArea',d.area); showHideEl('specAreaItem',true); }
  // legal
  if(d.legal){ setDetailText('specLegal',d.legal); showHideEl('specLegalItem',true); }
  // priceM2
  if(d.priceM2){ setDetailText('specPriceM2',d.priceM2); showHideEl('specPriceM2Item',true); }
  // commission
  if(d.commissionRate){
    const el = document.getElementById('specCommission');
    if(el) el.textContent = d.commissionRate + '%' + (d.commission ? ' (' + formatVND(d.commission) + ')' : '');
    showHideEl('specCommissionItem',true);
  }

  // dynamic parameters from DB
  if(d.parameters && d.parameters.length){
    const excludeNames = ['Diện tích','Pháp lý','Giá m2','Giá / m²'];
    d.parameters.forEach(p=>{
      if(!p.value || excludeNames.includes(p.name)) return;
      const item = document.createElement('div');
      item.className = 'spec-item spec-item-dyn';
      item.innerHTML = `<span class="spec-label">${escHtml(p.name)}</span><span class="spec-value">${escHtml(p.value)}</span>`;
      specGrid.appendChild(item);
    });
  }

  // ---- description ----
  if(d.description && d.description.trim()){
    const dt = document.getElementById('descText');
    if(dt) dt.innerHTML = escHtml(d.description).replace(/\n/g,'<br>');
    showHideEl('detailDescSection', true);
    const rmBtn = document.getElementById('readMoreBtn');
    if(rmBtn) rmBtn.style.display = '';
  } else {
    showHideEl('detailDescSection', false);
  }

  // ---- facilities ----
  const facGrid = document.getElementById('facilitiesGrid');
  if(facGrid && d.facilities && d.facilities.length){
    facGrid.innerHTML='';
    d.facilities.forEach(f=>{
      const chip = document.createElement('div');
      chip.style.cssText='display:inline-flex;align-items:center;gap:5px;padding:5px 10px;background:var(--bg-secondary);border-radius:20px;font-size:12px;font-weight:500;color:var(--text-secondary);border:1px solid var(--border);';
      let icon = '';
      if(f.icon){
        if(f.icon.endsWith('.svg')||f.icon.endsWith('.png')||f.icon.includes('http')){
          icon = `<img src="${f.icon}" style="width:14px;height:14px;object-fit:contain;" alt="">`;
        } else {
          icon = `<i class="fa-solid ${f.icon}" style="font-size:12px;"></i>`;
        }
      }
      chip.innerHTML = icon + `<span>${escHtml(f.name)}</span>` + (f.distance?`<span style="color:var(--primary);font-weight:600;margin-left:2px;">${escHtml(f.distance)}</span>`:'');
      facGrid.appendChild(chip);
    });
    showHideEl('detailFacilitiesSection', true);
  } else {
    showHideEl('detailFacilitiesSection', false);
  }

  // ---- legal section ----
  if(d.legal){
    setDetailText('detailLegalText', d.legal);
    showHideEl('detailLegalItem', true);
    showHideEl('detailLegalEmpty', false);
  } else {
    showHideEl('detailLegalItem', false);
    showHideEl('detailLegalEmpty', true);
  }

  // ---- host contact (store phone for call button, no display) ----
  currentDetailPhone = null;
  if(d.host && d.host.phone){
    currentDetailPhone = d.host.phone;
  }

  // ---- location map ----
  if(d.latitude && d.longitude){
    const ward   = d.ward  || 'Đà Lạt';
    const street = d.street|| '';
    const addr   = street ? street+', '+ward : ward;
    setDetailText('mapAddrLabel', addr);

    const mapsUrl = `https://maps.google.com/?q=${d.latitude},${d.longitude}`;
    const link = document.getElementById('mapLink');
    if(link) link.href = mapsUrl;

    // Store URL for click overlay
    const preview = document.getElementById('detailMapPreview');
    if(preview) {
      preview.dataset.mapsUrl = mapsUrl;
      preview.dataset.lat = d.latitude;
      preview.dataset.lng = d.longitude;
    }

    // Inject Google Maps iframe embed (no API key needed)
    const iframeContainer = document.getElementById('mapIframeContainer');
    const clickOverlay    = document.getElementById('mapClickOverlay');
    const pinCenter       = document.getElementById('mapPinCenter');
    if(iframeContainer){
      const embedUrl = `https://maps.google.com/maps?q=${d.latitude},${d.longitude}&z=16&output=embed`;
      iframeContainer.innerHTML = `<iframe src="${embedUrl}" width="100%" height="100%" style="border:0;" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>`;
      iframeContainer.style.display = '';
      if(clickOverlay) clickOverlay.style.display = '';
      if(pinCenter)    pinCenter.style.display    = 'none';
    }

    showHideEl('detailLocationSection', true);
  } else {
    showHideEl('detailLocationSection', false);
    // Reset map state for next open
    const iframeContainer = document.getElementById('mapIframeContainer');
    if(iframeContainer){ iframeContainer.innerHTML=''; iframeContainer.style.display='none'; }
    const clickOverlay = document.getElementById('mapClickOverlay');
    if(clickOverlay) clickOverlay.style.display='none';
    const pinCenter = document.getElementById('mapPinCenter');
    if(pinCenter) pinCenter.style.display='';
  }

  // ---- broker (poster) ----
  if(d.broker){
    setDetailText('ownerInitials', d.broker.initials||'BK');
    setDetailText('ownerName',     d.broker.name||'Môi giới');
    setDetailText('ownerRole',     d.broker.role||'eBroker · Đà Lạt BĐS');
  }

  // ---- similar properties ----
  const simScroll = document.getElementById('similarScroll');
  if(simScroll){
    if(d.similar && d.similar.length){
      simScroll.innerHTML = d.similar.map(s=>{
        const imgHtml = s.image
          ? `<div class="similar-img" style="overflow:hidden;padding:0;"><img src="${escHtml(s.image)}" style="width:100%;height:100%;object-fit:cover;display:block;" alt=""></div>`
          : `<div class="similar-img" style="background:#1e2a3a;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="1.7"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>`;
        const meta = [s.type, s.ward].filter(Boolean).join(' · ');
        const propJson = JSON.stringify(s).replace(/'/g,'\\u0027');
        return `<div class="similar-card" data-prop='${propJson}' onclick="openDetail(JSON.parse(this.dataset.prop))">${imgHtml}<div class="similar-body"><div class="similar-price">${escHtml(s.price)}</div><div class="similar-area">${escHtml(meta)}</div></div></div>`;
      }).join('');
    } else {
      simScroll.innerHTML = '<div style="padding:4px 0;font-size:12px;color:var(--text-tertiary);">Không có BĐS tương tự</div>';
    }
  }
}

function formatVND(n){
  if(!n) return '';
  const ty=1e9,tr=1e6;
  if(n>=ty) return (n/ty).toFixed(n%ty===0?0:1)+' tỷ';
  if(n>=tr) return Math.round(n/tr)+' triệu';
  return new Intl.NumberFormat('vi-VN').format(n);
}

function escHtml(s){
  if(!s) return '';
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// Open property detail directly by ID (used from suggestion click)
window.openPropertyById = function(propId) {
  fetch('/webapp/property/' + propId + '/json')
    .then(r => r.json())
    .then(data => {
      if(data && data.id) {
        openDetail(data);
      }
    })
    .catch(() => {
      // Fallback: just search
      doSearch('BĐS #' + propId);
    });
};

window.openDetail = function(data){
  const d = data || {};

  // 1. Show basic data immediately
  populateBasic(d);

  // 2. Reset UI state — sync bookmark state
  bookmarked = !!(d.id && window.likedIds && window.likedIds.has(String(d.id)));
  const _hBtn = document.getElementById('bookmarkBtn');
  if(_hBtn) _applyBookmarkState(_hBtn, bookmarked, true);
  descExpanded = false;
  const dt = document.getElementById('descText');
  if(dt){ dt.classList.add('clamped'); dt.innerHTML=''; }
  const rmBtn = document.getElementById('readMoreBtn');
  if(rmBtn){ rmBtn.textContent='Xem thêm ▾'; rmBtn.style.display='none'; }
  document.getElementById('detailScroll').scrollTop = 0;
  document.getElementById('detailStickyHeader').classList.remove('scrolled');
  // hide optional sections until data loaded
  showHideEl('detailDescSection',false);
  showHideEl('detailFacilitiesSection',false);
  showHideEl('detailLocationSection',false);
  // Reset map state
  const _mic = document.getElementById('mapIframeContainer');
  if(_mic){ _mic.innerHTML=''; _mic.style.display='none'; }
  const _mco = document.getElementById('mapClickOverlay');
  if(_mco) _mco.style.display='none';
  const _mpc = document.getElementById('mapPinCenter');
  if(_mpc) _mpc.style.display='';
  showHideEl('specAreaItem',false);
  showHideEl('specLegalItem',false);
  showHideEl('specPriceM2Item',false);
  showHideEl('specCommissionItem',false);

  // 3. Slide in panel
  applyDetailRole();
  document.getElementById('page-detail').classList.add('open');
  document.querySelector('.app-header').style.zIndex='0';
  document.querySelector('.bottom-nav').style.transform='translateY(100%)';

  // 4. Fetch full data from server if id available
  if(d.id){
    const loader = document.getElementById('detailGalleryLoader');
    if(loader) loader.style.display='flex';
    fetch('/webapp/property/'+d.id+'/json')
      .then(r=>r.ok?r.json():Promise.reject(r.status))
      .then(full=>{ populateFull(full); applyDetailRole(); })
      .catch(()=>{ /* keep basic data shown */ })
      .finally(()=>{ if(loader) loader.style.display='none'; });
  }
};

window.closeDetail = function(){
  document.getElementById('page-detail').classList.remove('open');
  document.querySelector('.app-header').style.zIndex='100';
  document.querySelector('.bottom-nav').style.transform='';
  closeSendModal();
  closeBookingForm();
};

function applyDetailRole(){
  // Reset CTA bars so role filtering below can decide visibility
  const bars = ['crmActionBar','brokerActionBar'];
  bars.forEach(id=>{
    const el = document.getElementById(id);
    if(el) el.style.display='';
  });
  // Re-apply role visibility to all role-based elements (including newly shown sections)
  const allowed = roleHierarchy[currentRole] || roleHierarchy['guest'];
  const allRoles = ['guest','broker','bds_admin','sale','sale_admin','admin'];
  document.querySelectorAll(allRoles.map(r=>'.role-'+r).join(',')).forEach(el=>{
    const hasAllowed = allowed.some(r=>el.classList.contains('role-'+r));
    el.style.display = hasAllowed ? '' : 'none';
  });
  // Restore flex display for flex containers
  document.querySelectorAll('.crm-action-bar,.owner-actions').forEach(el=>{
    if(el.style.display !== 'none') el.style.display = 'flex';
  });
}

// gallery swipe
let touchStartX = 0;
document.getElementById('page-detail').addEventListener('touchstart',e=>{
  touchStartX = e.touches[0].clientX;
},{passive:true});
document.getElementById('page-detail').addEventListener('touchend',e=>{
  const dx = e.changedTouches[0].clientX - touchStartX;
  if(Math.abs(dx)>50){
    if(dx<0) nextSlide();
    else prevSlide();
  }},{passive:true});

function nextSlide(){ galleryCurrentIdx=(galleryCurrentIdx+1)%gallerTotal; updateGallery(); }
function prevSlide(){ galleryCurrentIdx=(galleryCurrentIdx-1+gallerTotal)%gallerTotal; updateGallery(); }
function updateGallery(){
  document.getElementById('gallerySlides').style.transform=`translateX(-${galleryCurrentIdx*100}%)`;
  document.getElementById('galleryIdx').textContent=galleryCurrentIdx+1;
  document.querySelectorAll('.gdot').forEach((d,i)=>d.classList.toggle('active',i===galleryCurrentIdx));
}

// scroll-based sticky header
document.getElementById('detailScroll').addEventListener('scroll',function(){
  const scrolled = this.scrollTop>240;
  document.getElementById('detailStickyHeader').classList.toggle('scrolled',scrolled);
},{passive:true});

// section toggle
window.toggleSection = function(header){
  const body = header.nextElementSibling;
  const toggle = header.querySelector('.detail-section-toggle');
  const isOpen = toggle.classList.contains('open');
  if(isOpen){
    body.style.display='none';
    toggle.classList.remove('open');
  } else {
    body.style.display='';
    toggle.classList.add('open');
  }
};

// read more
window.toggleReadMore = function(){
  descExpanded = !descExpanded;
  const dt = document.getElementById('descText');
  dt.classList.toggle('clamped',!descExpanded);
  document.getElementById('readMoreBtn').textContent = descExpanded?'Thu gọn ▴':'Xem thêm ▾';
};

// bookmark — works for both prop-card heart and detail page header button
window.toggleBookmark = function(btn, propId){
  const id = propId || currentDetailPropId;
  if(!id) return;
  const idStr = String(id);
  const token = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  const url   = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes && window.WEBAPP_CONFIG.routes.favouriteToggle;
  if(!token || !url) return;

  const isLiked = window.likedIds && window.likedIds.has(idStr);
  const newState = !isLiked;

  // Optimistic UI update
  _applyBookmarkState(btn, newState);
  if(newState){ window.likedIds.add(idStr); } else { window.likedIds.delete(idStr); }
  // Sync detail header button if open
  if(String(currentDetailPropId) === idStr){
    bookmarked = newState;
    const hBtn = document.getElementById('bookmarkBtn');
    if(hBtn) _applyBookmarkState(hBtn, newState, true);
  }

  fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ property_id: id })
  })
  .then(r=>r.json())
  .then(data=>{
    if(!data.success){ // rollback on failure
      _applyBookmarkState(btn, isLiked);
      if(newState){ window.likedIds.delete(idStr); } else { window.likedIds.add(idStr); }
      if(String(currentDetailPropId) === idStr){
        bookmarked = isLiked;
        const hBtn = document.getElementById('bookmarkBtn');
        if(hBtn) _applyBookmarkState(hBtn, isLiked, true);
      }
      showToast('Không thể thực hiện, thử lại sau');
    } else {
      showToast(data.liked ? 'Đã thêm vào BĐS đã thích' : 'Đã bỏ khỏi BĐS đã thích');
      // Update liked count in profile menu
      const countEl = document.getElementById('likedBdsCount');
      if(countEl) countEl.textContent = window.likedIds.size + ' BĐS đã lưu';
    }
  })
  .catch(function(){ showToast('Không thể kết nối, thử lại sau'); });
};

function _applyBookmarkState(btn, liked, isDetailHeader){
  if(!btn) return;
  const svg = btn.querySelector('svg');
  if(!svg) return;
  // Get computed primary color
  const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();
  if(isDetailHeader){
    svg.setAttribute('stroke', liked ? primaryColor : 'currentColor');
    svg.setAttribute('fill', liked ? primaryColor : 'none');
  } else {
    svg.setAttribute('stroke', primaryColor);
    svg.setAttribute('fill', liked ? primaryColor : 'none');
    if(liked){ btn.classList.add('liked'); } else { btn.classList.remove('liked'); }
  }
}

// share detail
window.shareDetail = function(){
  if(currentDetailPropId) {
    logAction('property', currentDetailPropId, 'share', currentDetailTitle);
  }
  const url = window.location.origin + '/property/' + (currentDetailPropId || '');
  const title = currentDetailTitle || 'BĐS Đà Lạt';
  if(navigator.share){
    navigator.share({ title: title, url: url }).catch(function(){});
  } else if(navigator.clipboard){
    navigator.clipboard.writeText(url);
    showToast('Đã sao chép link!');
  } else {
    showToast('Đã sao chép link!');
  }
};

// full map logic
let currentFullMap = null;
let mapMarkers = [];
let bounds = null;

// open Google Maps for current property (now full screen interactive map)
window.openGoogleMaps = function(){
  const el = document.getElementById('detailMapPreview');
  const url = el && el.dataset.mapsUrl;
  
  if(!url && (!currentDetailPropId)) return; // No location info

  // Extract lat, lng by fetching detail again?
  // We already have latitude/longitude implicitly. Let's get it from the iframe src or store it during populateFull.
  // Easiest is to add data attributes to detailMapPreview in populateFull.
  
  const latStr = el.dataset.lat;
  const lngStr = el.dataset.lng;
  
  // If we can't find coords, fallback to old behavior
  if (!latStr || !lngStr) {
    if(url) window.open(url, '_blank');
    return;
  }
  
  const centerLat = parseFloat(latStr);
  const centerLng = parseFloat(lngStr);

  document.getElementById('fullMapModal').style.display = 'flex';
  
  if (!currentFullMap) {
    currentFullMap = new google.maps.Map(document.getElementById('fullMapCanvas'), {
      center: { lat: centerLat, lng: centerLng },
      zoom: 16,
      mapTypeControl: true, // Allow user to toggle layers (Satellite vs Map)
      mapTypeControlOptions: {
          style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
          position: google.maps.ControlPosition.RIGHT_TOP
      },
      zoomControl: true,
      zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER },
      streetViewControl: false,
      fullscreenControl: false
    });
    
    // Create Custom Control "BĐS Khác"
    const customControlDiv = document.createElement("div");
    customControlDiv.style.margin = "10px";
    
    const controlButton = document.createElement("button");
    controlButton.style.backgroundColor = "#fff";
    controlButton.style.border = "none";
    controlButton.style.outline = "none";
    controlButton.style.width = "auto";
    controlButton.style.height = "40px";
    controlButton.style.borderRadius = "20px";
    controlButton.style.boxShadow = "rgba(0, 0, 0, 0.3) 0px 1px 4px -1px";
    controlButton.style.cursor = "pointer";
    controlButton.style.padding = "0 16px";
    controlButton.style.display = "flex";
    controlButton.style.alignItems = "center";
    controlButton.style.justifyContent = "center";
    controlButton.style.gap = "6px";
    controlButton.innerHTML = `<span style="color:var(--primary);"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 16 12 12 8"></polyline><line x1="8" y1="12" x2="16" y2="12"></line></svg></span><span style="font-size:14px;font-weight:600;color:#374151;">BĐS khác</span>`;
    
    controlButton.addEventListener("click", () => {
      controlButton.innerHTML = `<span style="font-size:14px;font-weight:600;color:#374151;">Đang tìm...</span>`;
      fetch(`/webapp/properties/nearby?lat=${centerLat}&lng=${centerLng}&exclude_id=${currentDetailPropId}`)
        .then(r => r.json())
        .then(res => {
          if (res.success && res.data && res.data.length > 0) {
            renderNearbyProperties(res.data);
            controlButton.innerHTML = `<span style="font-size:14px;font-weight:600;color:#374151;">Đã tải ${res.data.length} BĐS lân cận</span>`;
          } else {
            controlButton.innerHTML = `<span style="font-size:14px;font-weight:600;color:#374151;">Không có BĐS lân cận</span>`;
            showToast('Không tìm thấy BĐS lân cận nào');
          }
          setTimeout(()=>{ controlButton.style.display = 'none'; }, 3000);
        })
        .catch(() => {
          controlButton.innerHTML = `<span style="font-size:14px;font-weight:600;color:#374151;">Lỗi tải dữ liệu</span>`;
          showToast('Có lỗi xảy ra khi tải BĐS lân cận');
        });
    });
    
    customControlDiv.appendChild(controlButton);
    currentFullMap.controls[google.maps.ControlPosition.TOP_CENTER].push(customControlDiv);
  } else {
    // Re-center map if map already initialized
    currentFullMap.setCenter({ lat: centerLat, lng: centerLng });
    currentFullMap.setZoom(16);
  }

  // Clear old markers
  mapMarkers.forEach(m => m.setMap(null));
  mapMarkers = [];
  bounds = new google.maps.LatLngBounds();

  // Add center marker (Current Property)
  const centerPos = { lat: centerLat, lng: centerLng };
  const centerMarker = new google.maps.Marker({
    position: centerPos,
    map: currentFullMap,
    icon: {
      url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
      scaledSize: new google.maps.Size(40, 40)
    },
    zIndex: 999
  });
  
  const infoWindow = new google.maps.InfoWindow({
    content: `<div style="padding:4px;"><strong style="color:var(--primary);font-size:14px;">BĐS đang xem</strong><br><span style="font-size:12px;">${currentDetailTitle||'Đà Lạt'}</span></div>`
  });
  
  centerMarker.addListener("click", () => {
    infoWindow.open(currentFullMap, centerMarker);
  });
  
  mapMarkers.push(centerMarker);
  bounds.extend(centerPos);
  
  // Also open the infowindow initially for the main property
  infoWindow.open(currentFullMap, centerMarker);
};

window.closeFullMap = function() {
  document.getElementById('fullMapModal').style.display = 'none';
};

function renderNearbyProperties(data) {
  const activeInfoWindow = new google.maps.InfoWindow();

  data.forEach(p => {
    const pos = { lat: p.lat, lng: p.lng };
    const marker = new google.maps.Marker({
      position: pos,
      map: currentFullMap,
      icon: {
        url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png",
        scaledSize: new google.maps.Size(32, 32)
      },
      title: p.title
    });
    
    marker.addListener("click", () => {
      const imgHtml = p.image ? `<img src="${escHtml(p.image)}" style="width:100%;height:80px;object-fit:cover;border-radius:4px;margin-bottom:6px;">` : '';
      const html = `
        <div style="width:160px;cursor:pointer;" onclick="closeFullMap(); openDetail({id: ${p.id}});">
          ${imgHtml}
          <div style="font-weight:600;font-size:13px;line-height:1.2;margin-bottom:4px;color:var(--text-primary);">${escHtml(p.title)}</div>
          <div style="font-size:13px;font-weight:700;color:var(--primary);">${escHtml(p.price)}</div>
          <div style="font-size:11px;color:var(--text-tertiary);margin-top:2px;">Cắt khoảng: ${p.distance}</div>
          <div style="margin-top:6px;text-align:center;font-size:12px;color:#fff;background:var(--primary);padding:4px;border-radius:4px;">Xem chi tiết</div>
        </div>
      `;
      activeInfoWindow.setContent(html);
      activeInfoWindow.open(currentFullMap, marker);
    });
    
    mapMarkers.push(marker);
    bounds.extend(pos);
  });
  
  if (!bounds.isEmpty()) {
    currentFullMap.fitBounds(bounds);
    
    // Don't zoom in too far if properties are very close
    google.maps.event.addListenerOnce(currentFullMap, 'bounds_changed', function() {
      if (this.getZoom() > 16) {
        this.setZoom(16);
      }
    });
  }
}

// call owner
window.callOwner = function(){
  if(currentDetailPhone){
    if(currentDetailPropId) {
      logAction('property', currentDetailPropId, 'call', currentDetailTitle);
    }
    window.location.href = 'tel:'+currentDetailPhone;
  } else {
    showToast('Chưa có thông tin liên hệ chủ nhà');
  }
};

// send modal
window.openSendModal = function(){
  document.getElementById('sendModalOverlay').classList.add('open');
  selectedDeal=null;
  document.querySelectorAll('.deal-pick-check').forEach(el=>el.textContent='○');
  const btn=document.getElementById('sendConfirmBtn');
  btn.disabled=true; btn.style.opacity='0.4';
};
window.closeSendModal = function(){
  document.getElementById('sendModalOverlay').classList.remove('open');
};
window.selectDeal = function(row,idx){
  selectedDeal=idx;
  document.querySelectorAll('.deal-pick-check').forEach(el=>el.textContent='○');
  document.getElementById('pick'+idx).textContent='✓';
  const btn=document.getElementById('sendConfirmBtn');
  btn.disabled=false; btn.style.opacity='1';
};
window.confirmSend = function(){
  closeSendModal();
  showToast('✓ Đã gửi BĐS cho khách!');
};

// booking form
window.openBookingForm = function(){
  const overlay = document.getElementById('bookingFormOverlay');
  overlay.style.display='flex';
};
window.closeBookingForm = function(){
  document.getElementById('bookingFormOverlay').style.display='none';
};
window.confirmBooking = function(){
  closeBookingForm();
  showToast('✓ Lịch hẹn đã được tạo!');
};

// toast
let toastTimer;
window.showToast = function(msg){
  const t=document.getElementById('toast');
  t.textContent=msg;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer=setTimeout(()=>t.classList.remove('show'),2200);
};

// prop-cards: handled via inline onclick + data-prop attribute

// also wire send-modal close on backdrop
document.getElementById('sendModalOverlay')?.addEventListener('click',function(e){
  if(e.target===this) closeSendModal();
});

// ============ REFERRAL FUNCTIONS ============
var _refData = null;
var _refTreeAll = [];
var _refTreeShown = 5;

function loadReferralData() {
  _refData = null;
  _refTreeAll = [];
  _refTreeShown = 5;

  // Reset stats to loading state
  ['refStatTotal','refStatActive','refStatEarned'].forEach(function(id){
    var el = document.getElementById(id);
    if(el) el.textContent = '—';
  });
  var monthEl = document.getElementById('refMonthLabel');
  if(monthEl) monthEl.textContent = '...';

  // Show loading in containers
  var treeLoading = document.getElementById('refTreeLoading');
  var histLoading = document.getElementById('refHistoryLoading');
  if(treeLoading) { treeLoading.style.display = ''; }
  if(histLoading) { histLoading.style.display = ''; }
  var showMore = document.getElementById('refTreeShowMore');
  if(showMore) showMore.style.display = 'none';

  // Show code skeleton
  var codeSkeleton = document.getElementById('refCodeSkeleton');
  if(codeSkeleton) codeSkeleton.style.display = 'inline-block';
  var codeDisplay = document.getElementById('refCodeDisplay');

  fetch('/webapp/referral/data')
    .then(function(res) { return res.json(); })
    .then(function(data) {
      _refData = data;
      _refTreeAll = _refData.tree || [];

      // Update code
      if(codeDisplay) {
        if(codeSkeleton) codeSkeleton.style.display = 'none';
        codeDisplay.textContent = _refData.referral_code || '';
      }

      // Update stats
      var s = _refData.stats || {};
      var totalEl = document.getElementById('refStatTotal');
      var activeEl = document.getElementById('refStatActive');
      var earnedEl = document.getElementById('refStatEarned');
      if(totalEl) totalEl.textContent = s.total_referrals ?? 0;
      if(activeEl) activeEl.textContent = s.active_referrals ?? 0;
      if(earnedEl) earnedEl.textContent = (s.month_earned_trieu > 0 ? s.month_earned_trieu + ' tr' : '0');
      if(monthEl) monthEl.textContent = s.month_label || '';

      // Update tree title
      var treeTitle = document.getElementById('refTreeTitle');
      if(treeTitle) treeTitle.textContent = 'Cấp 1 — Bạn giới thiệu trực tiếp (' + _refTreeAll.length + ' người)';

      // Render tree
      renderReferralTree();

      // Render history
      renderReferralHistory(_refData.history || []);
    })
    .catch(function(err) {
      console.error('Referral data error:', err);
      if(treeLoading) { treeLoading.style.display=''; treeLoading.innerHTML = '<span style="color:var(--danger);">Không tải được dữ liệu</span>'; }
      if(histLoading) { histLoading.style.display=''; histLoading.innerHTML = '<span style="color:var(--danger);">Không tải được dữ liệu</span>'; }
    });
}

function renderReferralTree() {
  var container = document.getElementById('refTreeContainer');
  var loading   = document.getElementById('refTreeLoading');
  var showMore  = document.getElementById('refTreeShowMore');
  if(!container) return;
  if(loading) loading.style.display = 'none';

  var items = _refTreeAll.slice(0, _refTreeShown);

  if(items.length === 0) {
    container.innerHTML = '<div style="padding:24px;text-align:center;color:var(--text-tertiary);font-size:13px;">Chưa có người nào được giới thiệu.<br><span style="font-size:12px;">Chia sẻ mã để bắt đầu xây dựng hệ thống!</span></div>';
    if(showMore) showMore.style.display = 'none';
    return;
  }

  var html = items.map(function(m) {
    var roleColor = m.role_label === 'Sale' || m.role_label === 'Sale Admin' ? 'var(--purple)' : 'var(--success)';
    var roleBg    = m.role_label === 'Sale' || m.role_label === 'Sale Admin' ? 'var(--purple-light)' : 'var(--success-light)';
    var earnText  = m.month_earned_trieu > 0 ? '+' + m.month_earned_trieu + ' tr' : '0';
    var earnColor = m.month_earned_trieu > 0 ? 'var(--success)' : 'var(--text-tertiary)';
    var earnLabel = m.month_earned_trieu > 0 ? '5% tháng này' : 'Chưa phát sinh';
    var opacity   = m.is_active ? '1' : '0.55';
    var inactiveBadge = m.is_active ? '' : ' · Chưa HĐ';
    return '<div class="ref-member" style="opacity:'+opacity+';">'
      + '<div class="ref-member-avatar" style="background:linear-gradient(135deg,'+m.avatar_color+','+m.avatar_color+'cc);">'+m.avatar_letter+'</div>'
      + '<div class="ref-member-body">'
      + '<div class="ref-member-name">'+_escHtml(m.name)+' <span class="ref-level-tag" style="background:'+roleBg+';color:'+roleColor+';">'+_escHtml(m.role_label)+inactiveBadge+'</span></div>'
      + '<div class="ref-member-meta">Tham gia: '+m.joined_at+' · '+_escHtml(m.ward_name)+'</div>'
      + '</div>'
      + '<div class="ref-member-right">'
      + '<div class="ref-member-earn" style="color:'+earnColor+';">'+earnText+'</div>'
      + '<div class="ref-member-earn-label">'+earnLabel+'</div>'
      + '</div>'
      + '</div>';
  }).join('');

  // Insert before the show-more button area
  var existingItems = container.querySelectorAll('.ref-member');
  existingItems.forEach(function(el){ el.remove(); });
  container.insertAdjacentHTML('beforeend', html);

  // Show more button
  var remaining = _refTreeAll.length - _refTreeShown;
  if(remaining > 0) {
    showMore.textContent = 'Xem thêm ' + remaining + ' thành viên khác ▾';
    showMore.style.display = '';
  } else {
    if(showMore) showMore.style.display = 'none';
  }
}

window.showMoreReferrals = function() {
  _refTreeShown += 5;
  renderReferralTree();
};

function renderReferralHistory(history) {
  var container = document.getElementById('refHistoryContainer');
  var loading   = document.getElementById('refHistoryLoading');
  if(!container) return;
  if(loading) loading.style.display = 'none';

  if(!history || history.length === 0) {
    container.innerHTML = '<div style="padding:24px;text-align:center;color:var(--text-tertiary);font-size:13px;">Chưa có hoa hồng giới thiệu nào được ghi nhận.</div>';
    return;
  }

  var svgMoney = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>';
  var svgClock = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';

  var html = history.map(function(h) {
    var isPending   = h.status === 'pending';
    var isUpcoming  = h.status === 'upcoming';
    var isCancelled = h.status === 'cancelled';

    var avatarBg    = isPending || isUpcoming ? 'linear-gradient(135deg,#d97706,#f59e0b)' : 'linear-gradient(135deg,#059669,#10b981)';
    var avatarIcon  = isPending || isUpcoming ? svgClock : svgMoney;
    var earnText    = isPending || isUpcoming ? '~' + h.referral_amount_trieu + ' tr' : '+' + h.referral_amount_trieu + ' tr';
    var earnColor   = isPending ? 'var(--warning)' : (isCancelled ? 'var(--text-tertiary)' : 'var(--success)');
    var earnLabel   = isPending ? 'Đang chờ duyệt' : (isUpcoming ? 'Dự kiến' : (isCancelled ? 'Đã hủy' : '5% × ' + h.base_commission_trieu + ' tr HH'));
    var statusMeta  = isPending ? ' · <span style="color:var(--warning);font-weight:600;">Đang chờ duyệt</span>' : (isUpcoming ? ' · <span style="color:var(--text-tertiary);">Sắp phát sinh</span>' : '');

    return '<div class="ref-member">'
      + '<div class="ref-member-avatar" style="background:'+avatarBg+';">'+avatarIcon+'</div>'
      + '<div class="ref-member-body">'
      + '<div class="ref-member-name">'+_escHtml(h.referee_name)+' — Deal #'+h.deal_id+'</div>'
      + '<div class="ref-member-meta">'+h.date+' · '+_escHtml(h.property_label)+statusMeta+'</div>'
      + '</div>'
      + '<div class="ref-member-right">'
      + '<div class="ref-member-earn" style="color:'+earnColor+';">'+earnText+'</div>'
      + '<div class="ref-member-earn-label">'+earnLabel+'</div>'
      + '</div>'
      + '</div>';
  }).join('');

  container.innerHTML = html;
}

function _escHtml(str) {
  if(!str) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

window.copyRefCode = function(){
  var codeEl = document.getElementById('refCodeDisplay');
  var code = _refData ? _refData.referral_code : (codeEl ? codeEl.textContent.trim() : '');
  if(!code) { showToast('Chưa tải được mã giới thiệu'); return; }
  if(navigator.clipboard){
    navigator.clipboard.writeText(code);
  }
  showToast('Đã sao chép mã: ' + code);
};

window.shareRefLink = function(platform){
  var code    = _refData ? _refData.referral_code : '';
  var link    = _refData ? _refData.share_url : ('/ref/' + code);
  var tgUrl   = _refData ? _refData.telegram_share_url : '';
  if(!code) { showToast('Chưa tải được mã giới thiệu'); return; }

  if(platform === 'telegram') {
    if(tgUrl) window.open(tgUrl, '_blank');
    showToast('Đang mở Telegram để chia sẻ...');
  } else if(platform === 'zalo') {
    var zaloUrl = 'https://zalo.me/s/share?url=' + encodeURIComponent(link)
      + '&text=' + encodeURIComponent('Tham gia Đà Lạt BĐS với mã giới thiệu ' + code + '. Đăng ký ngay!');
    window.open(zaloUrl, '_blank');
    showToast('Đang mở Zalo để chia sẻ...');
  } else {
    if(navigator.clipboard) navigator.clipboard.writeText(link);
    showToast('Đã sao chép link: ' + link);
  }
};

window.switchRefTab = function(btn, tab){
  btn.closest('.sp-tabs').querySelectorAll('.sp-tab').forEach(t=>t.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('refTabTree').style.display = tab==='tree' ? '' : 'none';
  document.getElementById('refTabHistory').style.display = tab==='history' ? '' : 'none';
};

// ============ HOME FEED — LAZY LOADING ============

function renderPropertyCard(p) {
  const svgHome = `<svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.45)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V13h6v9"/></svg>`;
  const isLikedCard = !!(p.id && window.likedIds && window.likedIds.has(String(p.id)));
  const heartFill = isLikedCard ? 'var(--primary)' : 'none';
  const svgHeart = `<svg width="14" height="14" viewBox="0 0 24 24" fill="${heartFill}" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>`;
  const svgShare = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>`;
  const svgPin = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>`;
  const svgArea = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>`;
  const svgLegal = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>`;
  const svgBed = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M2 15h20"/><path d="M6 10V6a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4"/></svg>`;
  const svgEye = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;stroke:var(--primary);"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
  const svgEdit = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
  const svgPhone = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg>`;

  const imgHtml = p.title_image
    ? `<img src="${p.title_image}" class="prop-img-inner" style="object-fit:cover;width:100%;height:100%;" alt="">`
    : `<div class="img-prop1 prop-img-inner"><div class="img-center">${svgHome}</div></div>`;

  const categoryBadge = p.category_name
    ? `<span class="badge badge-blue">${p.category_name}</span>` : '';

  const areaMeta = p.area ? `<div class="prop-meta-item">${svgArea} <span>${p.area} m²</span></div>` : '';
  const legalMeta = p.legal ? `<div class="prop-meta-item">${svgLegal} <span>${p.legal}</span></div>` : '';
  const roomMeta = p.number_room
    ? `<div class="prop-meta-item role-broker role-bds_admin role-sale role-sale_admin role-admin">${svgBed} <span>${p.number_room} PN</span></div>` : '';

  const propJson = JSON.stringify({
    id: p.id || null,
    title: p.title || '',
    price: p.price || '',
    type: p.category_name || 'BĐS',
    area: p.area ? `${p.area} m²` : '—',
    room: p.number_room ? `${p.number_room} PN` : '—',
    addr: p.location || 'Đà Lạt, Lâm Đồng',
    views: p.total_click || 0,
    images: (p.gallery_images && p.gallery_images.length) ? p.gallery_images : (p.title_image ? [p.title_image] : []),
    priceM2: p.price_m2 || '',
    direction: p.direction || '—',
  }).replace(/'/g, '\\u0027');

  // Determine if current logged-in broker owns this listing
  const isOwn = !!(window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.customerId && p.added_by && p.added_by == window.WEBAPP_CONFIG.customerId);
  const editRoles = isOwn ? 'role-broker role-bds_admin role-admin' : 'role-bds_admin role-admin';
  const callRoles = isOwn ? 'role-broker role-sale role-sale_admin role-admin' : 'role-sale role-sale_admin role-admin';

  const svgShareBtn = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>`;

  return `
    <div class="prop-card" data-prop='${propJson}' onclick="if(!event.target.closest('.prop-quick-btn,.prop-action-btn'))openDetail(JSON.parse(this.dataset.prop))">
      <div class="prop-img">
        ${imgHtml}
        <div class="prop-img-gradient"></div>
        <div class="prop-img-tags">${categoryBadge}</div>
        <div class="prop-img-price">${p.price || ''}</div>
        <div class="prop-actions">
          <div class="prop-action-btn${isLikedCard ? ' liked' : ''}" onclick="toggleBookmark(this,${p.id||''});event.stopPropagation();">${svgHeart}</div>
        </div>
      </div>
      <div class="prop-body">
        <div class="prop-title">${p.title || ''}</div>
        <div class="prop-location">${svgPin} ${p.location || ''}</div>
        <div class="prop-meta">${areaMeta}${legalMeta}${roomMeta}</div>
      </div>
      <div class="prop-footer">
        <div class="prop-views" style="display:flex;align-items:center;gap:4px;">${svgEye} ${p.total_click || 0} lượt xem</div>
        <div class="prop-quick-actions">
          <div class="prop-quick-btn ${editRoles}" style="background:var(--primary-light);border-color:transparent;color:var(--primary);" title="Chỉnh sửa" onclick="propEditAction(JSON.parse(this.closest('.prop-card').dataset.prop),event)">${svgEdit}</div>
          <div class="prop-quick-btn ${callRoles}" style="background:var(--primary-light);border-color:transparent;color:var(--primary);" title="Gọi" onclick="propCallAction(JSON.parse(this.closest('.prop-card').dataset.prop),event)">${svgPhone}</div>
          <div class="prop-quick-btn" style="background:var(--primary-light);border-color:transparent;color:var(--primary);" title="Chia sẻ" onclick="propShareAction(JSON.parse(this.closest('.prop-card').dataset.prop),event)">${svgShareBtn}</div>
        </div>
      </div>
    </div>`;
}

let homeFeedFilters = { type: '', category: '' };

window.resetHomeFeed = function(chip) {
  const bar = document.getElementById('home-filter-bar');
  if (!bar) return;
  homeFeedFilters.type = chip.dataset.type || '';
  homeFeedFilters.category = chip.dataset.category || '';

  const feed = document.getElementById('prop-feed');
  const sentinel = document.getElementById('feed-sentinel');
  if (feed) feed.innerHTML = '';
  if (sentinel) {
    sentinel.dataset.page = '1';
    sentinel.dataset.hasMore = 'true';
    sentinel.dataset.loading = 'false';
    const spinner = document.getElementById('feed-spinner');
    if (spinner) spinner.style.display = '';
  }
  loadHomeFeedPage();
};

function loadHomeFeedPage() {
  const sentinel = document.getElementById('feed-sentinel');
  if (!sentinel) return;
  if (sentinel.dataset.hasMore !== 'true') return;
  if (sentinel.dataset.loading === 'true') return;

  const page = parseInt(sentinel.dataset.page) || 1;
  sentinel.dataset.loading = 'true';

  const spinner = document.getElementById('feed-spinner');
  if (spinner) spinner.style.display = '';

  const params = new URLSearchParams({ page });
  if (homeFeedFilters.type !== '') params.set('type', homeFeedFilters.type);
  if (homeFeedFilters.category !== '') params.set('category_id', homeFeedFilters.category);

  fetch(`/webapp/home-feed?${params}`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
  })
    .then(r => r.json())
    .then(data => {
      const feed = document.getElementById('prop-feed');
      if (feed && data.properties) {
        data.properties.forEach(p => {
          feed.insertAdjacentHTML('beforeend', renderPropertyCard(p));
        });
        // Re-apply current role visibility to newly inserted cards
        if (typeof setRole === 'function') {
          setRole(currentRole, null);
        }
      }
      sentinel.dataset.loading = 'false';
      sentinel.dataset.hasMore = data.has_more ? 'true' : 'false';
      sentinel.dataset.page = data.next_page || (page + 1);
      if (!data.has_more && spinner) spinner.style.display = 'none';
    })
    .catch(() => {
      sentinel.dataset.loading = 'false';
    });
}

// ============ LIKED BDS SUBPAGE ============
let likedbdsLoaded = false;

function loadLikedBds(force){
  if(likedbdsLoaded && !force) return;
  likedbdsLoaded = true;

  const url = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes && window.WEBAPP_CONFIG.routes.favouritesJson;
  if(!url) return;

  document.getElementById('likedbdsLoading').style.display = '';
  document.getElementById('likedbdsEmpty').style.display = 'none';
  document.getElementById('likedbdsList').style.display = 'none';

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(function(res){
      document.getElementById('likedbdsLoading').style.display = 'none';
      if(!res.success || !res.data || !res.data.length){
        document.getElementById('likedbdsEmpty').style.display = '';
        return;
      }
      const svgPin  = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>`;
      const svgArea = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>`;
      const svgHeart= `<svg width="14" height="14" viewBox="0 0 24 24" fill="var(--primary)" stroke="var(--primary)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>`;
      const svgHome = `<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>`;

      const html = res.data.map(function(p){
        const imgHtml = p.title_image
          ? `<div style="width:90px;height:70px;flex-shrink:0;border-radius:8px;overflow:hidden;"><img src="${escHtml(p.title_image)}" style="width:100%;height:100%;object-fit:cover;display:block;" alt=""></div>`
          : `<div style="width:90px;height:70px;flex-shrink:0;border-radius:8px;overflow:hidden;background:#1e2a3a;display:flex;align-items:center;justify-content:center;">${svgHome}</div>`;
        const propData = JSON.stringify({id:p.id,title:p.title,price:p.price,addr:p.location,area:p.area?p.area+' m²':'—',type:p.category_name||'BĐS',room:'—'}).replace(/'/g,'\\u0027');
        return `
          <div style="display:flex;gap:10px;align-items:flex-start;padding:12px 16px;border-bottom:1px solid var(--border);cursor:pointer;"
               onclick="openDetail(JSON.parse(this.dataset.prop))" data-prop='${propData}'>
            ${imgHtml}
            <div style="flex:1;min-width:0;">
              <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escHtml(p.title)}</div>
              <div style="font-size:12px;color:var(--primary);font-weight:700;margin-bottom:4px;">${escHtml(p.price)}</div>
              <div style="display:flex;align-items:center;gap:4px;font-size:11px;color:var(--text-tertiary);">${svgPin} ${escHtml(p.location||'')}</div>
              ${p.area ? `<div style="display:flex;align-items:center;gap:4px;font-size:11px;color:var(--text-tertiary);margin-top:2px;">${svgArea} ${p.area} m²</div>` : ''}
            </div>
            <div class="prop-action-btn liked" style="flex-shrink:0;" onclick="toggleBookmark(this,${p.id});event.stopPropagation();reloadLikedBdsAfterUnlike(${p.id});">${svgHeart}</div>
          </div>`;
      }).join('');

      const listEl = document.getElementById('likedbdsList');
      listEl.innerHTML = html;
      listEl.style.display = '';

      // Update count
      const countEl = document.getElementById('likedBdsCount');
      if(countEl) countEl.textContent = res.data.length + ' BĐS đã lưu';
    })
    .catch(function(){
      document.getElementById('likedbdsLoading').style.display = 'none';
      document.getElementById('likedbdsEmpty').style.display = '';
    });
}

window.reloadLikedBdsAfterUnlike = function(propId){
  // After un-liking from the list, remove that card
  setTimeout(function(){
    if(!window.likedIds || !window.likedIds.has(String(propId))){
      likedbdsLoaded = false;
      loadLikedBds(true);
    }
  }, 400);
};

// ============ MY BDS SUBPAGE ============
let mybdsLoaded        = false;
let mybdsCurrentStatus = 'all';
let mybdsCurrentSearch = '';
let mybdsCurrentSort   = 'latest';
let mybdsSearchTimer   = null;
let mybdsAllData       = [];

function loadMyBds(force) {
  if (mybdsLoaded && !force) return;
  mybdsLoaded = true;

  const loadingEl = document.getElementById('mybdsLoading');
  const emptyEl   = document.getElementById('mybdsEmpty');
  const listEl    = document.getElementById('mybdsList');
  if (!loadingEl || !emptyEl || !listEl) return;

  loadingEl.style.display = '';
  emptyEl.style.display   = 'none';
  listEl.style.display    = 'none';

  const cfg = window.WEBAPP_CONFIG || {};
  const url = cfg.routes && cfg.routes.myPropertiesJson;
  if (!url) return;

  const params = new URLSearchParams({
    status: mybdsCurrentStatus === 'all' ? 'all' : mybdsCurrentStatus,
    search: mybdsCurrentSearch,
    sort:   mybdsCurrentSort,
  });

  fetch(url + '?' + params.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(function(res) {
      loadingEl.style.display = 'none';
      if (!res.success) { emptyEl.style.display = ''; return; }

      // Update summary strip
      const counts = res.counts || {};
      setElText('mybdsCountActive',  counts.active  ?? 0);
      setElText('mybdsCountPending', counts.pending ?? 0);
      setElText('mybdsCountHidden',  counts.hidden  ?? 0);
      setElText('mybdsTotalViews',   (counts.total_views || 0).toLocaleString('vi-VN'));

      // Update tab labels
      setElText('mybdsTabAll',     'Tất cả (' + (counts.all ?? 0) + ')');
      setElText('mybdsTabActive',  'Hiển thị (' + (counts.active ?? 0) + ')');
      setElText('mybdsTabPending', 'Chờ duyệt (' + (counts.pending ?? 0) + ')');
      setElText('mybdsTabHidden',  'Đã ẩn (' + (counts.hidden ?? 0) + ')');

      const props = res.properties || [];
      mybdsAllData = props;
      // Cache properties by ID for safe onclick lookup
      window._mybdsPropCache = {};
      props.forEach(function(p) { window._mybdsPropCache[p.id] = p; });

      if (!props.length) { emptyEl.style.display = ''; return; }

      const maxViews = Math.max(1, ...props.map(p => p.total_click));
      const maxFav   = Math.max(1, ...props.map(p => p.favourite_count));

      listEl.innerHTML = props.map(p => mybdsBuildCard(p, maxViews, maxFav)).join('');
      listEl.style.display = '';
    })
    .catch(function() {
      if (loadingEl) loadingEl.style.display = 'none';
      if (emptyEl)   emptyEl.style.display = '';
    });
}

function setElText(id, val) {
  const el = document.getElementById(id);
  if (el) el.textContent = val;
}

function mybdsBuildCard(p, maxViews, maxFav) {
  const statusInfo = p.status === 1
    ? { cls: 'status-active',   label: '● Đang hiển thị' }
    : p.status === 0
      ? { cls: 'status-pending', label: '⏳ Chờ duyệt' }
      : { cls: 'status-hidden',  label: '⊘ Đã ẩn' };

  const imgStyle = p.title_image
    ? `background:url('${escHtml(p.title_image)}') center/cover no-repeat;`
    : 'background:linear-gradient(135deg,#1e3a5f,#0d1f3c);display:flex;align-items:center;justify-content:center;';

  const imgPlaceholder = p.title_image
    ? ''
    : `<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>`;

  // Stat chips (views & likes) — only for active
  const statChips = p.status === 1
    ? `<div class="mybds-img-stats">
        <div class="mybds-stat-chip"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> ${p.total_click}</span></div>
        <div class="mybds-stat-chip"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> ${p.favourite_count}</span></div>
      </div>`
    : '';

  // Metadata items
  const metaItems = [
    p.area     ? `<div class="mybds-meta-item"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>${escHtml(p.area)} m²</div>` : '',
    p.rooms    ? `<div class="mybds-meta-item"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>${escHtml(p.rooms)} PN</div>` : '',
    p.legal    ? `<div class="mybds-meta-item"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>${escHtml(p.legal)}</div>` : '',
    p.direction? `<div class="mybds-meta-item"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>${escHtml(p.direction)}</div>` : '',
    `<div class="mybds-meta-item"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>${p.property_type === 0 ? 'Mua' : 'Thuê'}</div>`,
  ].filter(Boolean).join('');

  // Performance bars (active only)
  const viewPct = Math.round((p.total_click / maxViews) * 100);
  const favPct  = Math.round((p.favourite_count / maxFav) * 100);
  const perfBars = p.status === 1
    ? `<div class="perf-row">
        <span class="perf-label">Lượt xem</span>
        <div class="perf-bar-bg"><div class="perf-bar-fill" style="width:${viewPct}%;"></div></div>
        <span class="perf-val">${p.total_click}</span>
      </div>
      <div class="perf-row">
        <span class="perf-label">Quan tâm</span>
        <div class="perf-bar-bg"><div class="perf-bar-fill" style="width:${favPct}%;background:var(--danger);"></div></div>
        <span class="perf-val">${p.favourite_count}</span>
      </div>`
    : '';

  // Pending notice banner
  const pendingBanner = p.status === 0
    ? `<div style="padding:10px 13px;background:var(--warning-light);border-top:1px solid #fde68a;display:flex;gap:8px;align-items:center;">
        <span style="font-size:14px;">⏳</span>
        <div>
          <div style="font-size:11px;font-weight:600;color:var(--warning);">Đang chờ Admin duyệt</div>
          <div style="font-size:10px;color:#b45309;">Gửi ${escHtml(p.created_at)} · Thường duyệt trong 24h</div>
        </div>
        <button style="margin-left:auto;padding:4px 10px;background:var(--warning);color:#fff;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;" onclick="mybdsWithdraw(${p.id})">Rút tin</button>
      </div>`
    : '';

  // Action buttons per status
  const svgEye      = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
  const svgEdit     = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`;
  const svgHide     = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;
  const svgTrash    = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>`;

  const editUrl = (window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes && window.WEBAPP_CONFIG.routes.editListingBase)
    ? window.WEBAPP_CONFIG.routes.editListingBase + p.id
    : '/webapp/edit-listing/' + p.id;

  let quickBtns = '';
  if (p.status === 1) {
    // Active: view, edit, hide, delete
    quickBtns = `
      <div class="mybds-qbtn" onclick="mybdsViewProp(${p.id})" title="Xem">${svgEye}</div>
      <div class="mybds-qbtn" onclick="window.location.href='${escHtml(editUrl)}'" title="Chỉnh sửa">${svgEdit}</div>
      <div class="mybds-qbtn" onclick="mybdsToggleStatus(${p.id}, 1)" title="Ẩn tin">${svgHide}</div>
      <div class="mybds-qbtn danger" onclick="mybdsDelete(${p.id})" title="Xóa">${svgTrash}</div>`;
  } else if (p.status === 0) {
    // Pending: edit, delete
    quickBtns = `
      <div class="mybds-qbtn" onclick="window.location.href='${escHtml(editUrl)}'" title="Chỉnh sửa">${svgEdit}</div>
      <div class="mybds-qbtn danger" onclick="mybdsDelete(${p.id})" title="Xóa">${svgTrash}</div>`;
  } else {
    // Hidden: show again, delete
    quickBtns = `
      <button style="padding:5px 12px;background:var(--primary);color:#fff;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;" onclick="mybdsToggleStatus(${p.id}, 2)">Hiển thị lại</button>
      <div class="mybds-qbtn danger" onclick="mybdsDelete(${p.id})" title="Xóa">${svgTrash}</div>`;
  }

  // Footer analytics
  const footerLabel = p.status === 1
    ? `Đăng ${escHtml(p.created_at)}`
    : p.status === 0
      ? `Gửi ${escHtml(p.created_at)}`
      : `Ẩn từ ${escHtml(p.created_at)}`;

  const cardOpacity = p.status === 2 ? 'opacity:0.75;' : '';

  return `<div class="mybds-card" style="${cardOpacity}">
    <div class="mybds-img" style="${imgStyle}">
      ${imgPlaceholder}
      <div class="mybds-img-overlay"></div>
      <div class="mybds-img-status">
        <span class="status-pill ${statusInfo.cls}">${statusInfo.label}</span>
      </div>
      <div class="mybds-img-price">${escHtml(p.price)}</div>
      ${statChips}
    </div>
    <div class="mybds-body">
      <div class="mybds-title" ${p.status === 1 ? `onclick="mybdsViewProp(${p.id})" style="cursor:pointer;"` : ''}>${escHtml(p.title)}</div>
      <div class="mybds-addr"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>${escHtml(p.address_location || '')}</div>
      <div class="mybds-meta">${metaItems}</div>
    </div>
    ${perfBars}
    ${pendingBanner}
    <div class="mybds-footer">
      <div class="mybds-analytics">
        <div class="mybds-analytic"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>${footerLabel}</div>
      </div>
      <div class="mybds-quick">${quickBtns}</div>
    </div>
  </div>`;
}

window.mybdsViewProp = function(id) {
  const p = window._mybdsPropCache && window._mybdsPropCache[id];
  if (!p) return;
  openDetail({
    id:     p.id,
    title:  p.title,
    price:  p.price,
    priceM2: p.price_m2 || '',
    area:   p.area ? p.area + ' m²' : '—',
    room:   p.rooms ? p.rooms + ' PN' : '—',
    type:   p.category_name || '',
    addr:   p.address_location || '',
    images: p.title_image ? [p.title_image] : [],
  });
};

window.mybdsToggleStatus = function(id, currentStatus) {
  const cfg  = window.WEBAPP_CONFIG || {};
  const base = (cfg.routes && cfg.routes.myListingToggleBase) || '/webapp/listings/';
  const csrf = cfg.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';

  fetch(base + id + '/toggle', {
    method:  'PATCH',
    headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
  })
    .then(r => r.json())
    .then(function(res) {
      if (res.success) {
        const msg = res.status === 1 ? 'Đã hiển thị tin' : 'Đã ẩn tin';
        showToast(msg);
        mybdsLoaded = false;
        loadMyBds(true);
      } else {
        showToast(res.message || 'Lỗi cập nhật trạng thái');
      }
    })
    .catch(function() { showToast('Lỗi kết nối'); });
};

window.mybdsDelete = function(id) {
  if (!confirm('Bạn có chắc muốn xóa tin đăng này không?')) return;
  const cfg  = window.WEBAPP_CONFIG || {};
  const base = (cfg.routes && cfg.routes.myListingDeleteBase) || '/webapp/listings/';
  const csrf = cfg.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';

  fetch(base + id, {
    method:  'DELETE',
    headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
  })
    .then(r => r.json())
    .then(function(res) {
      if (res.success) {
        showToast('Đã xóa tin đăng');
        mybdsLoaded = false;
        loadMyBds(true);
      } else {
        showToast(res.message || 'Lỗi xóa tin');
      }
    })
    .catch(function() { showToast('Lỗi kết nối'); });
};

window.mybdsWithdraw = function(id) {
  if (!confirm('Rút tin này khỏi hàng chờ duyệt?')) return;
  const cfg  = window.WEBAPP_CONFIG || {};
  const base = (cfg.routes && cfg.routes.myListingDeleteBase) || '/webapp/listings/';
  const csrf = cfg.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';
  fetch(base + id, {
    method:  'DELETE',
    headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
  })
    .then(r => r.json())
    .then(function(res) {
      if (res.success) {
        showToast('Đã rút tin');
        mybdsLoaded = false;
        loadMyBds(true);
      } else {
        showToast(res.message || 'Lỗi rút tin');
      }
    })
    .catch(function() { showToast('Lỗi kết nối'); });
};

window.mybdsTabSwitch = function(btn, status) {
  document.querySelectorAll('#subpage-mybds .sp-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  mybdsCurrentStatus = status;
  mybdsLoaded = false;
  loadMyBds(true);
};

window.mybdsOnSearchInput = function(val) {
  clearTimeout(mybdsSearchTimer);
  mybdsSearchTimer = setTimeout(function() {
    mybdsCurrentSearch = val.trim();
    mybdsLoaded = false;
    loadMyBds(true);
  }, 400);
};

window.mybdsToggleFilterSheet = function() {
  const sheet = document.getElementById('mybdsFilterSheet');
  if (!sheet) return;
  sheet.style.display = sheet.style.display === 'none' ? '' : 'none';
  // Highlight active sort option
  const sortMap = { latest: 'mybdsSortLatest', oldest: 'mybdsSortOldest', views: 'mybdsSortViews', price_asc: 'mybdsSortPriceAsc', price_desc: 'mybdsSortPriceDesc' };
  Object.entries(sortMap).forEach(([key, elId]) => {
    const el = document.getElementById(elId);
    if (el) {
      const isActive = key === mybdsCurrentSort;
      el.style.color = isActive ? 'var(--primary)' : 'var(--text-secondary)';
      el.style.fontWeight = isActive ? '700' : '400';
      el.textContent = (isActive ? '✓ ' : '  ') + el.textContent.replace(/^[✓ ]+/, '');
    }
  });
};

window.mybdsCloseFilterSheet = function() {
  const sheet = document.getElementById('mybdsFilterSheet');
  if (sheet) sheet.style.display = 'none';
};

window.mybdsSortSelect = function(sort) {
  mybdsCurrentSort = sort;
  mybdsCloseFilterSheet();
  mybdsLoaded = false;
  loadMyBds(true);
};

// ============ MY CUSTOMERS SUBPAGE ============
let mycustLoaded        = false;
let mycustCurrentStatus = 'all';
let mycustCurrentSearch = '';
let mycustSearchTimer   = null;

function loadMyCustomers(force) {
  if (mycustLoaded && !force) return;
  mycustLoaded = true;

  const loadingEl = document.getElementById('mycustLoading');
  const emptyEl   = document.getElementById('mycustEmpty');
  const listEl    = document.getElementById('mycustList');
  if (!loadingEl || !emptyEl || !listEl) return;

  loadingEl.style.display = '';
  emptyEl.style.display   = 'none';
  listEl.style.display    = 'none';

  const cfg = window.WEBAPP_CONFIG || {};
  const url = cfg.routes && cfg.routes.myCustomersJson;
  if (!url) return;

  const params = new URLSearchParams({
    status: mycustCurrentStatus,
    search: mycustCurrentSearch,
  });

  fetch(url + '?' + params.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      loadingEl.style.display = 'none';
      if (!res.success) { emptyEl.style.display = ''; return; }

      const counts = res.counts || {};
      setElText('mycustCountNew',    counts.new    ?? 0);
      setElText('mycustCountCare',   counts.care   ?? 0);
      setElText('mycustCountDeal',   counts.deal   ?? 0);
      setElText('mycustCountClosed', counts.closed ?? 0);

      setElText('mycustTabAll',       'Tất cả ('     + (counts.all     ?? 0) + ')');
      setElText('mycustTabNew',       'Lead mới ('   + (counts.new     ?? 0) + ')');
      setElText('mycustTabContacted', 'Đã liên hệ (' + (counts.care    ?? 0) + ')');
      setElText('mycustTabConverted', 'Đang Deal ('  + (counts.deal    ?? 0) + ')');
      setElText('mycustTabLost',      'Đã chốt ('    + (counts.closed  ?? 0) + ')');

      const leads = res.leads || [];
      if (!leads.length) { emptyEl.style.display = ''; return; }

      listEl.innerHTML = leads.map(function(l) { return mycustBuildCard(l); }).join('');
      listEl.style.display = '';
    })
    .catch(function() {
      if (loadingEl) loadingEl.style.display = 'none';
      if (emptyEl)   emptyEl.style.display   = '';
    });
}

function mycustBuildCard(lead) {
  // Avatar initials: first + last word
  var nameParts = (lead.customer_name || '?').trim().split(/\s+/);
  var initials  = nameParts.length >= 2
    ? (nameParts[0][0] + nameParts[nameParts.length - 1][0]).toUpperCase()
    : ((lead.customer_name || '?')[0] || '?').toUpperCase();

  // Status badge
  var statusMap = {
    'new':         { cls: 'badge-red',    label: 'Lead mới' },
    'contacted':   { cls: 'badge-blue',   label: 'Đã liên hệ' },
    'converted':   { cls: 'badge-purple', label: 'Đang Deal' },
    'lost':        { cls: 'badge-gray',   label: 'Đã đóng' },
    'bad-contact': { cls: 'badge-amber',  label: 'Liên hệ lỗi' },
  };
  var si = statusMap[lead.status] || statusMap['new'];

  var leadTypeLabel = lead.lead_type === 'buy' ? 'Cần mua' : 'Cần thuê';

  // Lead flow stepper
  var stepByStatus = { 'new': 1, 'contacted': 2, 'converted': 4, 'lost': 5, 'bad-contact': 2 };
  var activeStep   = stepByStatus[lead.status] || 1;
  var isLost       = lead.status === 'lost' || lead.status === 'bad-contact';

  function lfDot(n) {
    if (!isLost && n < activeStep) return '<div class="lf-dot done">✓</div>';
    if (n === activeStep)          return '<div class="lf-dot active">' + n + '</div>';
    return '<div class="lf-dot">' + n + '</div>';
  }
  function lfLbl(n, txt) {
    if (!isLost && n < activeStep) return '<div class="lf-label done">' + txt + '</div>';
    if (n === activeStep)          return '<div class="lf-label active">' + txt + '</div>';
    return '<div class="lf-label">' + txt + '</div>';
  }
  function lfLine(afterN) {
    return (!isLost && afterN < activeStep)
      ? '<div class="lf-line done"></div>'
      : '<div class="lf-line"></div>';
  }

  var leadFlow = '<div class="lead-flow">'
    + '<div class="lf-step">' + lfDot(1) + lfLbl(1, 'Lead mới') + '</div>' + lfLine(1)
    + '<div class="lf-step">' + lfDot(2) + lfLbl(2, 'Đã liên hệ') + '</div>' + lfLine(2)
    + '<div class="lf-step">' + lfDot(3) + lfLbl(3, 'Tạo Deal') + '</div>' + lfLine(3)
    + '<div class="lf-step">' + lfDot(4) + lfLbl(4, 'Chăm sóc') + '</div>' + lfLine(4)
    + '<div class="lf-step">' + lfDot(5) + lfLbl(5, 'Chốt') + '</div>'
    + '</div>';

  // Phone
  var phoneRaw = (lead.customer_phone || '').replace(/\D/g, '');
  var phoneHref = phoneRaw
    ? 'href="tel:' + escHtml(lead.customer_phone) + '"'
    : 'onclick="showToast(\'Không có số điện thoại\')"';

  // Body rows
  var bodyHtml = '<div class="cust-row"><span class="cust-row-label">Nhu cầu</span><span class="cust-row-val">' + leadTypeLabel + '</span></div>';
  if (lead.categories && lead.categories.length) {
    bodyHtml += '<div class="cust-row"><span class="cust-row-label">Loại BĐS</span><span class="cust-row-val">' + escHtml(lead.categories.join(', ')) + '</span></div>';
  }
  if (lead.budget) {
    bodyHtml += '<div class="cust-row"><span class="cust-row-label">Ngân sách</span><span class="cust-row-val" style="color:var(--success);font-weight:600;">' + escHtml(lead.budget) + '</span></div>';
  }
  if (lead.wards && lead.wards.length) {
    bodyHtml += '<div class="cust-row"><span class="cust-row-label">Khu vực</span><span class="cust-row-val">' + escHtml(lead.wards.join(', ')) + '</span></div>';
  }

  // Tags
  var tagsHtml = '';
  (lead.categories || []).slice(0, 3).forEach(function(c) {
    tagsHtml += '<span class="cust-tag">' + escHtml(c) + '</span>';
  });
  (lead.wards || []).slice(0, 2).forEach(function(w) {
    tagsHtml += '<span class="cust-tag" style="background:var(--bg-secondary);">' + escHtml(w) + '</span>';
  });

  // Detail panel content
  var detailPanelId = 'mycust-detail-' + lead.id;
  var detailContent = '';
  if (lead.status === 'new') {
    detailContent = '<div class="cdp-note">Lead chưa được liên hệ. Liên hệ sớm để không bỏ lỡ cơ hội.</div>';
  } else {
    if (lead.activities && lead.activities.length) {
      detailContent += '<div class="cdp-section-title">Lịch sử hoạt động</div><div class="cdp-timeline">';
      lead.activities.slice(0, 3).forEach(function(a) {
        var dotColor = a.type === 'call' ? 'var(--primary)' : a.type === 'note' ? 'var(--success)' : 'var(--text-tertiary)';
        detailContent += '<div class="cdp-tl-item">'
          + '<div class="cdp-tl-dot" style="background:' + dotColor + ';"></div>'
          + '<div class="cdp-tl-text">' + escHtml(a.type_label) + ': ' + escHtml(a.content || '') + '</div>'
          + '<div class="cdp-tl-time">' + escHtml(a.created_at) + '</div>'
          + '</div>';
      });
      detailContent += '</div>';
    } else {
      detailContent += '<div style="font-size:12px;color:var(--text-tertiary);padding:8px 0;">Chưa có hoạt động nào.</div>';
    }
    if (lead.note) {
      detailContent += '<div class="cdp-section-title" style="margin-top:12px;">Ghi chú</div>'
        + '<div style="font-size:12px;color:var(--text-secondary);padding:6px 0;">' + escHtml(lead.note) + '</div>';
    }
  }

  var svgCalendar = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
  var svgPhone    = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';

  return '<div class="cust-card">'
    + '<div class="cust-header">'
    +   '<div class="cust-avatar" style="background:var(--primary);">' + escHtml(initials) + '</div>'
    +   '<div class="cust-info">'
    +     '<div class="cust-name">' + escHtml(lead.customer_name) + '</div>'
    +     '<div class="cust-meta">'
    +       '<span>' + escHtml(lead.customer_phone) + '</span>'
    +       '<span style="color:var(--text-tertiary);">· ' + escHtml(lead.created_diff) + '</span>'
    +     '</div>'
    +   '</div>'
    +   '<div class="cust-status-badge"><span class="badge ' + si.cls + '">' + si.label + '</span></div>'
    + '</div>'
    + '<div class="cust-body">' + bodyHtml
    +   (tagsHtml ? '<div class="cust-tags">' + tagsHtml + '</div>' : '')
    + '</div>'
    + leadFlow
    + '<div class="cust-footer">'
    +   '<div class="cust-date">' + svgCalendar + ' Nhận ' + escHtml(lead.created_at) + '</div>'
    +   '<div class="cust-actions">'
    +     '<a class="cust-btn" title="Gọi" ' + phoneHref + '>' + svgPhone + '</a>'
    +     '<div class="cust-btn primary" onclick="toggleCustDetail(\'' + detailPanelId + '\')">▼ Chi tiết</div>'
    +   '</div>'
    + '</div>'
    + '<div class="cust-detail-panel" id="' + detailPanelId + '">' + detailContent + '</div>'
    + '</div>';
}

window.mycustTabSwitch = function(btn, status) {
  document.querySelectorAll('#subpage-mycustomers .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  btn.classList.add('active');
  mycustCurrentStatus = status;
  mycustLoaded = false;
  loadMyCustomers(true);
};

window.mycustOnSearchInput = function(val) {
  clearTimeout(mycustSearchTimer);
  mycustSearchTimer = setTimeout(function() {
    mycustCurrentSearch = val.trim();
    mycustLoaded = false;
    loadMyCustomers(true);
  }, 400);
};

// Initialize liked count
(function(){
  const countEl = document.getElementById('likedBdsCount');
  if(countEl && window.likedIds){
    countEl.textContent = window.likedIds.size + ' BĐS đã lưu';
  }
})();

// ============ MY LEADS SUBPAGE ============
let leadsLoaded        = false;
let leadsCurrentStatus = '';
let leadsCurrentSearch = '';
let leadsSearchTimer   = null;
let leadsCurrentPage   = 1;
let leadsHasMore       = false;

function loadLeads(force) {
  if (leadsLoaded && !force) return;
  leadsLoaded   = true;
  leadsCurrentPage = 1;

  const loadingEl = document.getElementById('leadsLoading');
  const emptyEl   = document.getElementById('leadsEmpty');
  const listEl    = document.getElementById('leadsList');
  const moreEl    = document.getElementById('leadsLoadMore');
  if (!loadingEl || !emptyEl || !listEl) return;

  loadingEl.style.display = '';
  emptyEl.style.display   = 'none';
  listEl.style.display    = 'none';
  if (moreEl) moreEl.style.display = 'none';

  const cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if (!cfg || !cfg.myLeadsJson) return;

  const url = cfg.myLeadsJson
    + '?search=' + encodeURIComponent(leadsCurrentSearch)
    + '&status=' + encodeURIComponent(leadsCurrentStatus)
    + '&page=1';

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      loadingEl.style.display = 'none';
      if (!res.success) { emptyEl.style.display = ''; return; }

      // Update KPI
      if (res.kpi) {
        var kn = document.getElementById('leadsKpiNew');
        var kc = document.getElementById('leadsKpiContacted');
        var kv = document.getElementById('leadsKpiConverted');
        var kl = document.getElementById('leadsKpiLost');
        if (kn) kn.textContent = res.kpi.new;
        if (kc) kc.textContent = res.kpi.contacted;
        if (kv) kv.textContent = res.kpi.converted;
        if (kl) kl.textContent = res.kpi.lost;
      }

      // Update tab labels
      var tabAll = document.getElementById('leadsTabAll');
      if (tabAll && res.total !== undefined) {
        var total = res.kpi ? (res.kpi.new + res.kpi.contacted + res.kpi.converted + res.kpi.lost) : res.total;
        tabAll.textContent = 'Tất cả (' + total + ')';
      }

      if (!res.leads || res.leads.length === 0) {
        emptyEl.style.display = '';
        return;
      }

      listEl.innerHTML = renderLeadCards(res.leads);
      listEl.style.display = '';
      leadsHasMore   = res.has_more;
      leadsCurrentPage = 1;
      if (moreEl) moreEl.style.display = res.has_more ? '' : 'none';
    })
    .catch(function() {
      loadingEl.style.display = 'none';
      emptyEl.style.display   = '';
    });
}

window.leadsLoadMore = function() {
  if (!leadsHasMore) return;
  const cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if (!cfg || !cfg.myLeadsJson) return;

  const nextPage = leadsCurrentPage + 1;
  const url = cfg.myLeadsJson
    + '?search=' + encodeURIComponent(leadsCurrentSearch)
    + '&status=' + encodeURIComponent(leadsCurrentStatus)
    + '&page=' + nextPage;

  var moreEl = document.getElementById('leadsLoadMore');
  if (moreEl) moreEl.querySelector('button').textContent = 'Đang tải...';

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      if (!res.success || !res.leads || res.leads.length === 0) {
        if (moreEl) moreEl.style.display = 'none';
        return;
      }
      var listEl = document.getElementById('leadsList');
      if (listEl) listEl.innerHTML += renderLeadCards(res.leads);
      leadsHasMore   = res.has_more;
      leadsCurrentPage = nextPage;
      if (moreEl) {
        if (res.has_more) {
          moreEl.querySelector('button').textContent = 'Xem thêm';
          moreEl.style.display = '';
        } else {
          moreEl.style.display = 'none';
        }
      }
    })
    .catch(function() {
      if (moreEl) moreEl.style.display = 'none';
    });
};

function renderLeadCards(leads) {
  var svgPhone = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg>';
  var svgSource = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>';
  var svgZalo = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>';
  var svgMeeting = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
  var svgDeal = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>';
  var svgCancel = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
  var svgPhoneLg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg>';

  var statusColors = { new: '#ef4444', contacted: 'var(--primary)', converted: 'var(--success)', lost: 'var(--text-tertiary)' };
  var statusLabels = { new: 'Mới', contacted: 'Đã liên hệ', converted: 'Đã chuyển', lost: 'Huỷ' };
  var badgeClasses = { new: 'badge-red', contacted: 'badge-blue', converted: 'badge-green', lost: '' };
  var cardClasses  = { new: 'urgent', contacted: 'contacted', converted: 'converted', lost: '' };

  return leads.map(function(lead) {
    var status     = lead.status || 'new';
    var name       = lead.customer_name || 'Khách vãng lai';
    var phone      = lead.customer_phone || '';
    var initials   = name.split(' ').slice(-2).map(function(w) { return w[0] || ''; }).join('').toUpperCase().slice(0, 2);
    var avatarColor = statusColors[status] || '#9ca3af';
    var badgeCls   = badgeClasses[status] || '';
    var cardCls    = cardClasses[status] || '';
    var statusLbl  = statusLabels[status] || status;
    var id         = lead.id;

    var bodyRows = '';
    if (lead.categories) bodyRows += '<div class="lc-row"><span class="lc-label">Loại BĐS</span><span class="lc-value">' + escHtml(lead.categories) + '</span></div>';
    if (lead.lead_type || lead.purpose) {
      var need = [lead.lead_type, lead.purpose].filter(Boolean).join(' · ');
      bodyRows += '<div class="lc-row"><span class="lc-label">Nhu cầu</span><span class="lc-value">' + escHtml(need) + '</span></div>';
    }
    if (lead.budget) bodyRows += '<div class="lc-row"><span class="lc-label">Ngân sách</span><span class="lc-value money">' + escHtml(lead.budget) + '</span></div>';
    if (lead.wards) bodyRows += '<div class="lc-row"><span class="lc-label">Khu vực</span><span class="lc-value">' + escHtml(lead.wards) + '</span></div>';

    var tags = [];
    if (lead.categories) lead.categories.split(',').forEach(function(c) { var t = c.trim(); if(t) tags.push(t); });
    if (lead.budget) tags.push(lead.budget);
    if (lead.wards) lead.wards.split(',').forEach(function(w) { var t = w.trim(); if(t) tags.push(t); });
    if (lead.source_note) tags.push(lead.source_note);
    var tagsHtml = tags.slice(0,5).map(function(t) { return '<span class="lc-tag">' + escHtml(t) + '</span>'; }).join('');

    var sourceText = (lead.source_note || '') + (lead.source_note && lead.created_at ? ' · ' : '') + (lead.created_at || '');

    // Expand section action buttons (shown for new/contacted leads; converted only shows note)
    var expandBtns = '';
    if (status !== 'converted' && status !== 'lost') {
      expandBtns = '<div class="lc-action-row">'
        + '<div class="lc-action-btn" data-action="call" data-new-status="contacted" onclick="leadsSelectAction(this,' + id + ')"><span class="lc-action-icon">' + svgPhoneLg + '</span>Đã gọi</div>'
        + '<div class="lc-action-btn" data-action="zalo" data-new-status="contacted" onclick="leadsSelectAction(this,' + id + ')"><span class="lc-action-icon">' + svgZalo + '</span>Zalo</div>'
        + '<div class="lc-action-btn" data-action="meeting" data-new-status="contacted" onclick="leadsSelectAction(this,' + id + ')"><span class="lc-action-icon">' + svgMeeting + '</span>Hẹn gặp</div>'
        + '<div class="lc-action-btn" data-action="deal" onclick="leadsSelectAction(this,' + id + ')"><span class="lc-action-icon">' + svgDeal + '</span>Tạo Deal</div>'
        + '<div class="lc-action-btn" data-action="cancel" data-new-status="lost" onclick="leadsSelectAction(this,' + id + ')"><span class="lc-action-icon">' + svgCancel + '</span>Huỷ</div>'
        + '</div>';
    }

    // Footer primary CTA
    var ctaBtn = '';
    if (status === 'contacted') {
      ctaBtn = '<button class="lc-btn success" onclick="leadsOpenExpand(' + id + ')">'
        + '<span style="display:inline-flex;align-items:center;gap:5px;">' + svgDeal + ' Tạo Deal</span></button>';
    } else if (status !== 'converted' && status !== 'lost') {
      ctaBtn = '<button class="lc-btn primary" onclick="leadsOpenExpand(' + id + ')">Xử lý ▾</button>';
    }

    return '<div class="lead-card ' + cardCls + '" id="lc-card-' + id + '">'
      + '<div class="lc-head">'
      + '<div class="lc-avatar" style="background:' + avatarColor + ';">' + escHtml(initials) + '</div>'
      + '<div class="lc-info">'
      + '<div class="lc-name">' + escHtml(name) + '</div>'
      + '<div class="lc-meta">'
      + (phone ? '<span><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;">' + svgPhone + '</span>' + escHtml(phone) + '</span>' : '')
      + '</div>'
      + '</div>'
      + '<div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">'
      + '<span class="badge ' + badgeCls + '">' + statusLbl + '</span>'
      + '<span class="lc-time">' + escHtml(lead.created_diff || lead.created_at || '') + '</span>'
      + '</div>'
      + '</div>'
      + (bodyRows ? '<div class="lc-body">' + bodyRows + '</div>' : '')
      + (tagsHtml ? '<div class="lc-tags">' + tagsHtml + '</div>' : '')
      + '<div class="lc-footer">'
      + (sourceText ? '<span class="lc-source"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;">' + svgSource + '</span> ' + escHtml(sourceText) + '</span>' : '<span></span>')
      + '<div class="lc-actions">'
      + (phone ? '<a class="lc-btn icon" href="tel:' + encodeURIComponent(phone) + '" title="Gọi">' + svgPhoneLg + '</a>' : '')
      + ctaBtn
      + '</div>'
      + '</div>'
      + '<div class="lc-expand" id="lc-expand-' + id + '">'
      + '<div style="font-size:11px;font-weight:600;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Cập nhật trạng thái</div>'
      + expandBtns
      + '<textarea class="lc-note-area" id="lc-note-' + id + '" rows="2" placeholder="Ghi chú nhanh (VD: Khách nói chờ tháng sau...)"></textarea>'
      + '<div style="display:flex;gap:8px;">'
      + '<button class="lc-btn" onclick="leadsCloseExpand(' + id + ')" style="flex:1">Đóng</button>'
      + '<button class="lc-btn success" onclick="leadsSave(' + id + ')" style="flex:2">✓ Lưu & Cập nhật</button>'
      + '</div>'
      + '</div>'
      + '</div>';
  }).join('');
}

window.leadsOpenExpand = function(id) {
  var el = document.getElementById('lc-expand-' + id);
  if (el) el.classList.add('open');
};

window.leadsCloseExpand = function(id) {
  var el = document.getElementById('lc-expand-' + id);
  if (el) el.classList.remove('open');
};

window.leadsSelectAction = function(btn, leadId) {
  var expand = document.getElementById('lc-expand-' + leadId);
  if (!expand) return;
  expand.querySelectorAll('.lc-action-btn').forEach(function(b) { b.classList.remove('active'); });
  btn.classList.add('active');
};

window.leadsSave = function(leadId) {
  var expand = document.getElementById('lc-expand-' + leadId);
  if (!expand) return;
  var activeBtn = expand.querySelector('.lc-action-btn.active');
  if (!activeBtn) { showToast('Vui lòng chọn hành động trước'); return; }

  var action    = activeBtn.dataset.action;
  var noteEl    = document.getElementById('lc-note-' + leadId);
  var note      = noteEl ? noteEl.value.trim() : '';
  var csrf      = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  var cfg       = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;

  if (action === 'deal') {
    // POST /webapp/leads/{id}/deal
    var dealUrl = (cfg && cfg.leadsCreateDealBase ? cfg.leadsCreateDealBase : '/webapp/leads/') + leadId + '/deal';
    fetch(dealUrl, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
      body: JSON.stringify({ note: note }),
    })
      .then(function(r) { return r.json(); })
      .then(function(res) {
        if (res.success) {
          showToast('✓ Đã tạo Deal thành công');
          leadsLoaded = false;
          loadLeads(true);
        } else {
          showToast(res.message || 'Lỗi tạo deal');
        }
      })
      .catch(function() { showToast('Lỗi kết nối'); });
    return;
  }

  var newStatus = activeBtn.dataset.newStatus || 'contacted';
  var actionTypeMap = { call: 'call', zalo: 'note', meeting: 'note', cancel: 'status_change' };
  var notePrefixMap = { call: '', zalo: 'Đã nhắn Zalo. ', meeting: 'Đã hẹn gặp. ', cancel: '' };
  var actionType = actionTypeMap[action] || 'note';
  var fullNote   = (notePrefixMap[action] || '') + note;

  var statusUrl = (cfg && cfg.leadsUpdateStatusBase ? cfg.leadsUpdateStatusBase : '/webapp/leads/') + leadId + '/status';
  fetch(statusUrl, {
    method: 'PATCH',
    headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
    body: JSON.stringify({ status: newStatus, action_type: actionType, note: fullNote }),
  })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      if (res.success) {
        showToast('✓ Đã lưu cập nhật');
        leadsLoaded = false;
        loadLeads(true);
      } else {
        showToast(res.message || 'Lỗi cập nhật');
      }
    })
    .catch(function() { showToast('Lỗi kết nối'); });
};

window.leadsTabSwitch = function(btn, status) {
  if (!btn) return;
  document.querySelectorAll('#subpage-leads .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  btn.classList.add('active');
  leadsCurrentStatus = status;
  leadsLoaded = false;
  loadLeads(true);
};

window.leadsOnSearchInput = function(val) {
  clearTimeout(leadsSearchTimer);
  leadsSearchTimer = setTimeout(function() {
    leadsCurrentSearch = val.trim();
    leadsLoaded = false;
    loadLeads(true);
  }, 400);
};

// ============ DEALS ============
let dealsLoaded        = false;
let dealsCurrentStatus = '';
let dealsCurrentSearch = '';
let dealsSearchTimer   = null;
let dealsCurrentPage   = 1;
let dealsHasMore       = false;

function loadDeals(force) {
  if (dealsLoaded && !force) return;
  dealsLoaded      = true;
  dealsCurrentPage = 1;

  var loadingEl = document.getElementById('dealsLoading');
  var emptyEl   = document.getElementById('dealsEmpty');
  var listEl    = document.getElementById('dealsList');
  var moreEl    = document.getElementById('dealsLoadMore');
  if (!loadingEl || !emptyEl || !listEl) return;

  loadingEl.style.display = '';
  emptyEl.style.display   = 'none';
  listEl.style.display    = 'none';
  if (moreEl) moreEl.style.display = 'none';

  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if (!cfg || !cfg.myDealsJson) return;

  var url = cfg.myDealsJson
    + '?search=' + encodeURIComponent(dealsCurrentSearch)
    + '&status=' + encodeURIComponent(dealsCurrentStatus)
    + '&page=1';

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      loadingEl.style.display = 'none';
      if (!res.success) { emptyEl.style.display = ''; return; }

      // Update KPI
      if (res.kpi) {
        var ka = document.getElementById('dealsKpiActive');
        var kn = document.getElementById('dealsKpiNegotiating');
        var kc = document.getElementById('dealsKpiClosed');
        var kh = document.getElementById('dealsKpiCommission');
        if (ka) ka.textContent = res.kpi.active;
        if (kn) kn.textContent = res.kpi.negotiating;
        if (kc) kc.textContent = res.kpi.closed;
        if (kh) kh.textContent = res.kpi.commission_expected || '0';
      }

      // Update tab "Active" count
      var tabOpen = document.getElementById('dealsTabOpen');
      if (tabOpen && res.kpi) {
        tabOpen.textContent = 'Active (' + res.kpi.active + ')';
      }

      if (!res.deals || res.deals.length === 0) {
        emptyEl.style.display = '';
        return;
      }

      listEl.innerHTML = renderDealCards(res.deals);
      listEl.style.display = '';
      dealsHasMore     = res.has_more;
      dealsCurrentPage = 1;
      if (moreEl) moreEl.style.display = res.has_more ? '' : 'none';
    })
    .catch(function() {
      loadingEl.style.display = 'none';
      emptyEl.style.display   = '';
    });
}

window.dealsLoadMore = function() {
  if (!dealsHasMore) return;
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if (!cfg || !cfg.myDealsJson) return;

  var nextPage = dealsCurrentPage + 1;
  var url = cfg.myDealsJson
    + '?search=' + encodeURIComponent(dealsCurrentSearch)
    + '&status=' + encodeURIComponent(dealsCurrentStatus)
    + '&page=' + nextPage;

  var moreEl = document.getElementById('dealsLoadMore');
  if (moreEl) moreEl.querySelector('button').textContent = 'Đang tải...';

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      if (!res.success || !res.deals || res.deals.length === 0) {
        if (moreEl) moreEl.style.display = 'none';
        return;
      }
      var listEl = document.getElementById('dealsList');
      if (listEl) listEl.innerHTML += renderDealCards(res.deals);
      dealsHasMore     = res.has_more;
      dealsCurrentPage = nextPage;
      if (moreEl) {
        if (res.has_more) {
          moreEl.querySelector('button').textContent = 'Xem thêm';
          moreEl.style.display = '';
        } else {
          moreEl.style.display = 'none';
        }
      }
    })
    .catch(function() {
      if (moreEl) moreEl.style.display = 'none';
    });
};

window.dealsTabSwitch = function(btn, status) {
  if (!btn) return;
  document.querySelectorAll('#subpage-deals .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  btn.classList.add('active');
  dealsCurrentStatus = status;
  dealsLoaded = false;
  loadDeals(true);
};

window.dealsOnSearchInput = function(val) {
  clearTimeout(dealsSearchTimer);
  dealsSearchTimer = setTimeout(function() {
    dealsCurrentSearch = val.trim();
    dealsLoaded = false;
    loadDeals(true);
  }, 400);
};

function renderDealCards(deals) {
  return deals.map(renderDealCard).join('');
}

function renderDealCard(deal) {
  // Badge by status
  var statusBadge = '';
  if (deal.status === 'open') {
    if (deal.products_count === 0) {
      statusBadge = '<span class="badge badge-blue">Mới tạo</span>';
    } else {
      statusBadge = '<span class="badge badge-purple"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Đang chăm</span></span>';
    }
  } else if (deal.status === 'negotiating') {
    statusBadge = '<span class="badge badge-amber"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Thương lượng</span></span>';
  } else if (deal.status === 'waiting_finance') {
    statusBadge = '<span class="badge badge-amber">Chờ tài chính</span>';
  } else if (deal.status === 'closed') {
    statusBadge = '<span class="badge badge-green" style="background:#d1fae5;color:#065f46;">✓ Đã chốt</span>';
  }

  // Sub-info line
  var subParts = [];
  if (deal.lead_type) subParts.push((deal.lead_type === 'Mua' ? 'Tìm Mua' : 'Tìm Thuê'));
  if (deal.categories) subParts.push(deal.categories);
  if (deal.budget) subParts.push(deal.budget);
  if (deal.wards) subParts.push(deal.wards);
  var subInfo = subParts.join(' · ') || 'Deal #' + deal.id;

  // Progress stages
  var stageLabels = ['Lead', 'Deal', 'Chăm sóc', 'Xem nhà', 'Thương lượng', 'Chốt'];
  var stagesHtml = '';
  for (var i = 0; i < 6; i++) {
    var isDone   = deal.stages_done[i];
    var isActive = !isDone && (i === 0 || deal.stages_done[i - 1]);
    var dotClass = isDone ? 'done' : (isActive ? 'active' : '');
    var lblClass = isDone ? 'done' : (isActive ? 'active' : '');
    var dotContent = isDone ? '✓' : (i + 1);

    if (i === 4 && !isDone && isActive) {
      dotContent = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>';
    }

    stagesHtml += '<div class="dc-stage"><div class="dc-stage-dot ' + dotClass + '">' + dotContent + '</div><div class="dc-stage-label ' + lblClass + '">' + stageLabels[i] + '</div></div>';

    if (i < 5) {
      var lineClass = isDone ? 'done' : ((deal.stages_done[i] && !deal.stages_done[i + 1]) ? 'active' : '');
      stagesHtml += '<div class="dc-stage-line ' + lineClass + '"></div>';
    }
  }

  // Negotiation banner
  var negotiationBanner = '';
  if (deal.status === 'negotiating' && deal.negotiation_info) {
    negotiationBanner = '<div style="padding:8px 13px;background:var(--warning-light);border-top:1px solid #fde68a;">'
      + '<div style="font-size:11px;font-weight:700;color:var(--warning);margin-bottom:3px;display:flex;align-items:center;gap:4px;">'
      + '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Đang thương lượng</div>'
      + '<div style="font-size:12px;color:var(--text-secondary);">' + escHtml(deal.negotiation_info) + '</div>'
      + '</div>';
  }

  // Products list
  var productsHtml = '';
  if (deal.products && deal.products.length > 0) {
    var productItems = deal.products.map(function(p) {
      var statusCls = 'dbs-' + p.status_key;
      var statusIcon = '';
      if (p.status_key === 'liked') {
        statusIcon = '<svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> ';
      } else if (p.status_key === 'negotiating') {
        statusIcon = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> ';
      } else if (p.status_key === 'sent') {
        statusIcon = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 2 15 22 11 13 2 9 22 2"/></svg> ';
      }
      var bedroomsText = p.bedrooms ? ' · ' + p.bedrooms + ' PN' : '';
      return '<div class="dc-bds-item">'
        + '<div class="dc-bds-thumb gs1" style="display:flex;align-items:center;justify-content:center;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></div>'
        + '<div class="dc-bds-info">'
        + '<div class="dc-bds-title">' + escHtml(p.title) + '</div>'
        + '<div class="dc-bds-price">' + escHtml(p.price) + '</div>'
        + '<div class="dc-bds-area">' + escHtml(p.area) + bedroomsText + '</div>'
        + '</div>'
        + '<span class="dc-bds-status ' + statusCls + '"><span style="display:inline-flex;align-items:center;gap:3px;">' + statusIcon + escHtml(p.status_label) + '</span></span>'
        + '</div>';
    }).join('');

    productsHtml = '<div class="dc-bds-list">'
      + '<div style="padding:6px 13px;font-size:10px;font-weight:700;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:.05em;">BĐS đã gửi (' + deal.products_count + ')</div>'
      + negotiationBanner
      + productItems
      + '<div class="dc-add-bds" onclick="showToast(\'Chức năng gửi BĐS đang phát triển\')">'
      + '<div class="dc-add-icon">＋</div>'
      + '<div class="dc-add-text">Gửi thêm BĐS phù hợp cho khách</div>'
      + '</div>'
      + '</div>';
  } else {
    productsHtml = '<div class="dc-add-bds" onclick="showToast(\'Chức năng gửi BĐS đang phát triển\')">'
      + '<div class="dc-add-icon">＋</div>'
      + '<div class="dc-add-text">Bắt đầu gửi BĐS phù hợp cho khách</div>'
      + '</div>';
  }

  // Footer date text
  var footerDate = deal.latest_booking_display
    ? deal.latest_booking_display
    : 'Tạo ' + deal.created_at + ' · ' + (deal.products_count === 0 ? 'Chưa có BĐS' : deal.products_count + ' BĐS');

  // Footer buttons
  var chatBtn = '<button class="dc-footer-btn" onclick="dealsChatCustomer(\'' + escAttr(deal.customer_telegram_id) + '\',\'' + escAttr(deal.customer_phone) + '\')">'
    + '<span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Chat</span></button>';

  var actionBtn = '';
  if (deal.status === 'closed') {
    actionBtn = '<span class="badge badge-green" style="background:#d1fae5;color:#065f46;padding:6px 12px;">✓ Đã chốt</span>';
  } else if (deal.status === 'negotiating') {
    actionBtn = '<button class="dc-footer-btn success" onclick="showToast(\'Chức năng chốt deal đang phát triển\')">✓ Chốt!</button>';
  } else if (deal.products_count === 0) {
    actionBtn = '<button class="dc-footer-btn primary" onclick="showToast(\'Chức năng gửi BĐS đang phát triển\')">'
      + '<span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 2 15 22 11 13 2 9 22 2"/></svg> Gửi BĐS</span></button>';
  } else {
    actionBtn = '<button class="dc-footer-btn primary" onclick="showToast(\'Chức năng cập nhật đang phát triển\')">Cập nhật</button>';
  }

  return '<div class="deal-card">'
    + '<div class="dc-head">'
    + '<div class="dc-avatar" style="background:' + escAttr(deal.avatar_color) + '">' + escHtml(deal.avatar_initials) + '</div>'
    + '<div class="dc-info">'
    + '<div class="dc-name">' + escHtml(deal.customer_name) + ' — Deal #' + deal.id + '</div>'
    + '<div class="dc-sub">' + escHtml(subInfo) + '</div>'
    + '</div>'
    + statusBadge
    + '</div>'
    + '<div class="dc-progress"><div class="dc-stages">' + stagesHtml + '</div></div>'
    + productsHtml
    + '<div class="dc-footer">'
    + '<span class="dc-footer-date"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>' + escHtml(footerDate) + '</span>'
    + chatBtn
    + actionBtn
    + '</div>'
    + '</div>';
}

window.dealsChatCustomer = function(telegramId, phone) {
  if (telegramId) {
    window.open('https://t.me/' + telegramId, '_blank');
  } else if (phone) {
    window.open('tel:' + phone);
  } else {
    showToast('Không có thông tin liên hệ');
  }
};

function escAttr(str) {
  if (!str) return '';
  return String(str).replace(/'/g, '&#39;').replace(/"/g, '&quot;');
}

// Intersection Observer — trigger lazy load when sentinel is visible
const homeFeedObserver = new IntersectionObserver(function(entries) {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      loadHomeFeedPage();
    }
  });
}, { rootMargin: '200px' });

const feedSentinel = document.getElementById('feed-sentinel');
if (feedSentinel) homeFeedObserver.observe(feedSentinel);

// ============ HOA HỒNG (COMMISSIONS) ============
var commCurrentStatus = '';
var commLoaded = false;

function loadCommissions(reset) {
  if (!reset && commLoaded) return;
  commLoaded = true;

  var loadingEl = document.getElementById('commLoading');
  var emptyEl   = document.getElementById('commEmpty');
  var listEl    = document.getElementById('commList');
  if (!loadingEl || !emptyEl || !listEl) return;

  loadingEl.style.display = '';
  emptyEl.style.display   = 'none';
  listEl.style.display    = 'none';

  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if (!cfg || !cfg.myCommissionsJson) { loadingEl.style.display = 'none'; emptyEl.style.display = ''; return; }

  var url = cfg.myCommissionsJson + '?status=' + encodeURIComponent(commCurrentStatus);

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(res) {
      loadingEl.style.display = 'none';
      if (!res.success) { emptyEl.style.display = ''; return; }

      // Update hero summary
      if (res.summary) {
        var el;
        el = document.getElementById('commHeroTotal');    if (el) el.textContent = res.summary.total_fmt || '0';
        el = document.getElementById('commHeroReceived'); if (el) el.textContent = (res.summary.received_trieu || 0) + ' tr';
        el = document.getElementById('commHeroPending');  if (el) el.textContent = (res.summary.pending_trieu  || 0) + ' tr';
        el = document.getElementById('commHeroUpcoming'); if (el) el.textContent = (res.summary.upcoming_trieu || 0) + ' tr';
      }

      // Update chart
      if (res.chart && res.chart.length) {
        var chartEl  = document.getElementById('commChart');
        var labelsEl = document.getElementById('commChartLabels');
        if (chartEl) {
          chartEl.innerHTML = res.chart.map(function(bar) {
            var cls = bar.is_current ? 'mc-bar active' : 'mc-bar';
            return '<div class="' + cls + '" style="height:' + bar.height_pct + '%"><div class="mc-bar-tip">' + bar.trieu + '</div></div>';
          }).join('');
        }
        if (labelsEl) {
          labelsEl.innerHTML = res.chart.map(function(bar, i) {
            var style = bar.is_current
              ? 'font-size:9px;font-weight:700;color:var(--primary)'
              : 'font-size:9px;color:var(--text-tertiary)';
            return '<span style="' + style + '">' + escHtml(bar.label) + '</span>';
          }).join('');
        }
      }

      if (!res.commissions || res.commissions.length === 0) {
        emptyEl.style.display = '';
        return;
      }

      listEl.innerHTML = renderCommissionCards(res.commissions);
      listEl.style.display = '';
    })
    .catch(function() {
      var loadingEl2 = document.getElementById('commLoading');
      var emptyEl2   = document.getElementById('commEmpty');
      if (loadingEl2) loadingEl2.style.display = 'none';
      if (emptyEl2)   emptyEl2.style.display   = '';
    });
}

window.commTabSwitch = function(btn, status) {
  if (!btn) return;
  document.querySelectorAll('#subpage-commissions .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  btn.classList.add('active');
  commCurrentStatus = status;
  commLoaded = false;
  loadCommissions(true);
};

function renderCommissionCards(list) {
  return list.map(renderCommissionCard).join('');
}

function renderCommissionCard(comm) {
  var isCompleted = comm.status === 'completed';
  var cardStyle   = isCompleted ? 'border-color:var(--primary);border-left:3px solid var(--primary)' : '';
  var valStyle    = isCompleted ? 'color:var(--primary)' : 'color:var(--primary)';
  var valText     = comm.sale_commission_trieu + ' tr' + (isCompleted ? ' ✓' : '');

  // Stepper
  var stepLabels = ['Chốt giá', 'Đặt cọc', 'Công chứng', 'Hoàn tất'];
  if (comm.status === 'pending_deposit') stepLabels[1] = 'Chờ cọc';

  var doneCount  = 0;
  var activeStep = -1;
  if (comm.status === 'pending_deposit')   { doneCount = 1; activeStep = 1; }
  else if (comm.status === 'deposited')    { doneCount = 2; activeStep = 2; }
  else if (comm.status === 'notarizing')   { doneCount = 2; activeStep = 2; }
  else if (comm.status === 'completed')    { doneCount = 4; }

  var stepperHtml = '';
  for (var i = 0; i < 4; i++) {
    var isDone   = i < doneCount;
    var isActive = i === activeStep;
    var isFinal  = isDone && i === 3;
    var dotCls   = isFinal ? 'final' : (isDone ? 'done' : (isActive ? 'active' : ''));
    var lblCls   = isDone ? 'done' : (isActive ? 'active' : '');
    var dotContent;
    if (isDone) {
      dotContent = '✓';
    } else if (isActive && activeStep === 1) {
      dotContent = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
    } else if (isActive && activeStep === 2) {
      dotContent = '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>';
    } else {
      dotContent = (i + 1).toString();
    }
    stepperHtml += '<div class="cs-step"><div class="cs-dot ' + dotCls + '">' + dotContent + '</div><div class="cs-label ' + lblCls + '">' + escHtml(stepLabels[i]) + '</div></div>';
    if (i < 3) {
      stepperHtml += '<div class="cs-line' + (isDone ? ' done' : '') + '"></div>';
    }
  }

  // Breakdown section
  var breakdownHtml = '<div class="comm-breakdown">';
  if (isCompleted) {
    breakdownHtml += '<div class="comm-breakdown-item"><div class="comm-breakdown-label">Đã nhận</div><div class="comm-breakdown-val" style="color:var(--primary)">' + comm.sale_commission_trieu + ' triệu ✓</div></div>';
    breakdownHtml += '<div class="comm-breakdown-item"><div class="comm-breakdown-label">Ngày nhận</div><div class="comm-breakdown-val">' + escHtml(comm.updated_at) + '</div></div>';
  } else if (comm.status === 'notarizing' || comm.status === 'deposited') {
    breakdownHtml += '<div class="comm-breakdown-item"><div class="comm-breakdown-label">HH của tôi (Sale)</div><div class="comm-breakdown-val" style="color:var(--primary)">' + comm.sale_commission_trieu + ' triệu</div></div>';
    breakdownHtml += '<div class="comm-breakdown-item"><div class="comm-breakdown-label">HH App</div><div class="comm-breakdown-val">' + escHtml(comm.app_commission_fmt) + '</div></div>';
    breakdownHtml += '<div class="comm-breakdown-item"><div class="comm-breakdown-label">Trạng thái</div><div class="comm-breakdown-val" style="color:var(--warning)">' + escHtml(comm.status_label) + '</div></div>';
    breakdownHtml += '<div class="comm-breakdown-item"><div class="comm-breakdown-label">Dự kiến nhận</div><div class="comm-breakdown-val">—</div></div>';
  } else {
    breakdownHtml += '<div class="comm-breakdown-item"><div class="comm-breakdown-label">HH của tôi</div><div class="comm-breakdown-val" style="color:var(--warning)">' + comm.sale_commission_trieu + ' triệu (chờ)</div></div>';
    breakdownHtml += '<div class="comm-breakdown-item"><div class="comm-breakdown-label">Cọc dự kiến</div><div class="comm-breakdown-val">—</div></div>';
  }
  breakdownHtml += '</div>';

  // Footer date
  var footerDate = '';
  if (isCompleted) {
    footerDate = '<span class="comm-footer-date" style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Hoàn tất ' + escHtml(comm.updated_at) + '</span>';
  } else {
    footerDate = '<span class="comm-footer-date">Chốt ' + escHtml(comm.created_at) + '</span>';
  }

  // Action button
  var actionBtn = '';
  if (isCompleted) {
    actionBtn = '<button class="comm-action-btn" onclick="showToast(\'Đang xuất PDF...\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg> Xuất PDF</span></button>';
  } else if (comm.status === 'notarizing' || comm.status === 'deposited') {
    actionBtn = '<button class="comm-action-btn primary" onclick="showToast(\'Xem chi tiết hợp đồng\')">Xem hợp đồng</button>';
  } else if (comm.status === 'pending_deposit') {
    var phone = comm.customer_phone || '';
    if (phone) {
      actionBtn = '<button class="comm-action-btn" onclick="window.location.href=\'tel:' + escHtml(phone) + '\'"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Nhắc cọc</span></button>';
    } else {
      actionBtn = '<button class="comm-action-btn" onclick="showToast(\'Liên hệ khách nhắc đặt cọc\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Nhắc cọc</span></button>';
    }
  }

  // Property icon background
  var iconBg = isCompleted ? 'var(--primary-light)' : (comm.status === 'notarizing' ? 'var(--warning-light)' : 'var(--primary-light)');

  return '<div class="comm-card" style="' + cardStyle + '">'
    + '<div class="comm-card-head">'
    + '<div class="comm-card-icon" style="background:' + iconBg + ';display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>'
    + '<div class="comm-card-info">'
    + '<div class="comm-card-name">' + escHtml(comm.property_name) + '</div>'
    + '<div class="comm-card-sub">' + escHtml(comm.customer_name) + ' · Giá chốt: ' + escHtml(comm.deal_amount_fmt) + '</div>'
    + '</div>'
    + '<div class="comm-card-amount">'
    + '<div class="comm-card-val" style="' + valStyle + '">' + escHtml(valText) + '</div>'
    + '<div class="comm-card-pct">' + (comm.comm_pct > 0 ? comm.comm_pct + '% hoa hồng' : 'Hoa hồng') + '</div>'
    + '</div>'
    + '</div>'
    + '<div class="comm-stepper">' + stepperHtml + '</div>'
    + breakdownHtml
    + '<div class="comm-card-footer">'
    + footerDate
    + '<div class="comm-footer-actions">' + actionBtn + '</div>'
    + '</div>'
    + '</div>';
}

// ========== ADMIN: QUẢN LÝ NGƯỜI DÙNG ==========

var usersCurrentTab = 'pending';
var usersCurrentSearch = '';
var usersSearchTimer = null;

window.usersSearchDebounce = function() {
  clearTimeout(usersSearchTimer);
  usersSearchTimer = setTimeout(function() {
    usersCurrentSearch = document.getElementById('usersSearchInput').value.trim();
    loadUsers(true);
  }, 400);
};

window.switchUsersTab = function(tab, btn) {
  usersCurrentTab = tab;
  document.querySelectorAll('#usersTabBar .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  if (btn) btn.classList.add('active');
  loadUsers(true);
};

window.loadUsers = function(reset) {
  var url = window.WEBAPP_CONFIG.routes.adminUsersJson
    + '?tab=' + encodeURIComponent(usersCurrentTab)
    + '&search=' + encodeURIComponent(usersCurrentSearch);

  var container = document.getElementById('usersListContainer');
  if (reset) {
    container.innerHTML = '<div id="usersSkeletonLoader" style="padding:16px;">'
      + '<div style="height:90px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;animation:pulse 1.5s infinite;"></div>'
      + '<div style="height:90px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;animation:pulse 1.5s infinite;"></div>'
      + '<div style="height:90px;background:var(--bg-secondary);border-radius:12px;animation:pulse 1.5s infinite;"></div>'
      + '</div>';
  }

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      // Update hero stats
      var s = data.stats || {};
      var activeEl = document.getElementById('usersActiveCount');
      if (activeEl) activeEl.textContent = s.active || 0;
      var pendingEl = document.getElementById('usersPendingCount');
      if (pendingEl) pendingEl.textContent = s.pending || 0;
      var brokerEl = document.getElementById('usersBrokerCount');
      if (brokerEl) brokerEl.textContent = s.broker || 0;
      var saleEl = document.getElementById('usersSaleCount');
      if (saleEl) saleEl.textContent = s.sale || 0;
      var lockedEl = document.getElementById('usersLockedCount');
      if (lockedEl) lockedEl.textContent = s.locked || 0;

      // Update tab counts
      document.querySelectorAll('.users-tab-count-pending').forEach(function(el) { el.textContent = s.pending || 0; });
      document.querySelectorAll('.users-tab-count-broker').forEach(function(el) { el.textContent = s.broker || 0; });
      document.querySelectorAll('.users-tab-count-sale').forEach(function(el) { el.textContent = s.sale || 0; });
      document.querySelectorAll('.users-tab-count-locked').forEach(function(el) { el.textContent = s.locked || 0; });

      // Render user cards
      var users = data.users || [];
      if (users.length === 0) {
        container.innerHTML = '<div style="text-align:center;padding:48px 16px;color:var(--text-tertiary);">'
          + '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.3;margin-bottom:12px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
          + '<div style="font-size:14px;">Không có người dùng nào</div></div>';
        return;
      }

      var html = '';
      var tab = usersCurrentTab;

      if (tab === 'pending') {
        html += '<div class="user-divider"><span>Chờ phê duyệt tài khoản eBroker</span><span class="badge badge-red">' + users.length + ' tài khoản</span></div>';
      } else if (tab === 'broker') {
        html += '<div class="user-divider"><span>eBroker đang active</span><span class="badge badge-green">' + users.length + ' users</span></div>';
      } else if (tab === 'sale') {
        html += '<div class="user-divider"><span>Nhân viên Sale</span><span class="badge badge-green">' + users.length + ' users</span></div>';
      } else if (tab === 'locked') {
        html += '<div class="user-divider"><span>Tài khoản bị khoá</span><span class="badge badge-red">' + users.length + ' users</span></div>';
      }

      users.forEach(function(u) {
        html += renderUserCard(u, tab);
      });

      container.innerHTML = html;
    })
    .catch(function(err) {
      container.innerHTML = '<div style="text-align:center;padding:32px;color:var(--danger);">Lỗi tải dữ liệu. <button onclick="loadUsers(true)" style="color:var(--primary);text-decoration:underline;background:none;border:none;cursor:pointer;">Thử lại</button></div>';
    });
};

function renderUserCard(u, tab) {
  var roleBadge = getRoleBadge(u.role);
  var initials = u.initials || u.name.slice(0, 2).toUpperCase();
  var avatar = '<div class="uc-avatar" style="background:' + u.avatar_color + ';">' + initials + '</div>';

  if (tab === 'pending') {
    return '<div class="user-card" id="uc-' + u.id + '">'
      + '<div class="uc-head">'
      + avatar
      + '<div class="uc-info">'
      + '<div class="uc-name">' + escHtml(u.name) + '</div>'
      + '<div class="uc-meta">'
      + (u.mobile ? '<span>📞 ' + escHtml(u.mobile) + '</span>' : '')
      + (u.email ? '<span>✉ ' + escHtml(u.email) + '</span>' : '')
      + '<span style="color:var(--warning);font-weight:600;">' + escHtml(u.created_at_human) + '</span>'
      + '</div></div>'
      + '<span class="badge badge-amber">⏳ Chờ duyệt</span>'
      + '</div>'
      + '<div class="uc-body">'
      + '<div class="uc-row"><span class="uc-label">Xin đăng ký</span><span class="uc-value">eBroker</span></div>'
      + '<div class="uc-row"><span class="uc-label">BĐS đã đăng</span><span class="uc-value">' + u.property_count + ' tin</span></div>'
      + '</div>'
      + '<div class="uc-actions">'
      + '<button class="uc-btn reject" onclick="rejectUser(' + u.id + ',\'' + escHtml(u.name) + '\')">✕ Từ chối</button>'
      + '<button class="uc-btn warn" onclick="approveTempUser(' + u.id + ',\'' + escHtml(u.name) + '\')">⏳ Duyệt tạm</button>'
      + '<button class="uc-btn" style="background:var(--primary);color:#fff;" onclick="approveUser(' + u.id + ',\'' + escHtml(u.name) + '\')">✓ Duyệt</button>'
      + '</div>'
      + '</div>';
  }

  if (tab === 'broker') {
    return '<div class="user-card" id="uc-' + u.id + '">'
      + '<div class="uc-head">'
      + avatar
      + '<div class="uc-info">'
      + '<div class="uc-name">' + escHtml(u.name) + '</div>'
      + '<div class="uc-meta">'
      + (u.mobile ? '<span>📞 ' + escHtml(u.mobile) + '</span>' : '')
      + '</div></div>'
      + roleBadge
      + '</div>'
      + '<div class="uc-stats">'
      + '<div class="uc-stat"><div class="uc-stat-val">' + u.property_count + '</div><div class="uc-stat-lbl">BĐS đăng</div></div>'
      + '</div>'
      + '<div class="uc-actions">'
      + '<button class="uc-btn warn" onclick="toggleUserLock(' + u.id + ',\'' + escHtml(u.name) + '\',' + u.isActive + ')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Khoá</span></button>'
      + '<button class="uc-btn" onclick="openRoleSheet(' + u.id + ',\'' + u.role + '\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Đổi role</span></button>'
      + '</div>'
      + '</div>';
  }

  if (tab === 'sale') {
    return '<div class="user-card" id="uc-' + u.id + '">'
      + '<div class="uc-head">'
      + avatar
      + '<div class="uc-info">'
      + '<div class="uc-name">' + escHtml(u.name) + '</div>'
      + '<div class="uc-meta">'
      + (u.mobile ? '<span>📞 ' + escHtml(u.mobile) + '</span>' : '')
      + '</div></div>'
      + roleBadge
      + '</div>'
      + '<div class="uc-actions">'
      + '<button class="uc-btn warn" onclick="toggleUserLock(' + u.id + ',\'' + escHtml(u.name) + '\',' + u.isActive + ')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Khoá</span></button>'
      + '<button class="uc-btn" onclick="openRoleSheet(' + u.id + ',\'' + u.role + '\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Đổi role</span></button>'
      + '</div>'
      + '</div>';
  }

  if (tab === 'locked') {
    return '<div class="user-card" id="uc-' + u.id + '" style="opacity:.8;border-color:var(--danger);">'
      + '<div class="uc-head">'
      + '<div class="uc-avatar" style="background:#6b7280;">' + initials + '</div>'
      + '<div class="uc-info">'
      + '<div class="uc-name">' + escHtml(u.name) + '</div>'
      + '<div class="uc-meta">'
      + (u.mobile ? '<span>📞 ' + escHtml(u.mobile) + '</span>' : '')
      + '<span style="color:var(--danger);font-weight:600;display:inline-flex;align-items:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Bị khoá</span>'
      + '</div></div>'
      + '<span class="badge badge-red"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Khoá</span>'
      + '</div>'
      + '<div class="uc-actions">'
      + '<button class="uc-btn approve" onclick="toggleUserLock(' + u.id + ',\'' + escHtml(u.name) + '\',' + u.isActive + ')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg> Mở khoá</span></button>'
      + '<button class="uc-btn danger" onclick="deleteUserConfirm(' + u.id + ',\'' + escHtml(u.name) + '\')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg> Xoá</span></button>'
      + '</div>'
      + '</div>';
  }

  return '';
}

function getRoleBadge(role) {
  var badges = {
    'broker':     '<span class="badge badge-green">🏠 eBroker</span>',
    'bds_admin':  '<span class="badge" style="background:#ccfbf1;color:#0f766e;">🏘️ BĐS Admin</span>',
    'sale':       '<span class="badge" style="background:#ede9fe;color:#7c3aed;">💼 Sale</span>',
    'sale_admin': '<span class="badge badge-amber">📋 Sale Admin</span>',
    'admin':      '<span class="badge badge-red">👑 Admin</span>',
    'guest':      '<span class="badge" style="background:var(--bg-secondary);color:var(--text-secondary);">👤 Guest</span>',
  };
  return badges[role] || '<span class="badge" style="background:var(--bg-secondary);color:var(--text-secondary);">' + role + '</span>';
}

function escHtml(str) {
  if (!str) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

window.approveUser = function(id, name) {
  if (!confirm('Duyệt tài khoản "' + name + '" làm eBroker?')) return;
  userAction('/approve', id, null, 'Đã duyệt ' + name + ' làm eBroker!');
};

window.rejectUser = function(id, name) {
  if (!confirm('Từ chối và khoá tài khoản "' + name + '"?')) return;
  userAction('/reject', id, null, 'Đã từ chối tài khoản ' + name);
};

window.approveTempUser = function(id, name) {
  if (!confirm('Duyệt tạm thời tài khoản "' + name + '"?')) return;
  userAction('/approve-temp', id, null, 'Đã duyệt tạm thời ' + name);
};

window.openRoleSheet = function(id, currentRole) {
  document.getElementById('userRoleSheetId').value = id;
  var sheet = document.getElementById('userRoleSheet');
  sheet.style.display = 'flex';
  // Highlight current role
  sheet.querySelectorAll('.rs-reason').forEach(function(el) { el.style.background = ''; });
};

window.changeUserRole = function(role) {
  var id = document.getElementById('userRoleSheetId').value;
  document.getElementById('userRoleSheet').style.display = 'none';
  userAction('/role', id, { role: role }, 'Đã đổi role thành công!', 'PATCH');
};

window.toggleUserLock = function(id, name, isActive) {
  var action = isActive ? 'khoá' : 'mở khoá';
  if (!confirm('Xác nhận ' + action + ' tài khoản "' + name + '"?')) return;
  userAction('/toggle-active', id, null, isActive ? 'Đã khoá ' + name : 'Đã mở khoá ' + name, 'PATCH');
};

window.deleteUserConfirm = function(id, name) {
  if (!confirm('XOÁ VĨNH VIỄN tài khoản "' + name + '"? Hành động này không thể hoàn tác!')) return;
  userAction('', id, null, 'Đã xoá tài khoản ' + name, 'DELETE');
};

function userAction(suffix, id, body, successMsg, method) {
  method = method || 'POST';
  var url = window.WEBAPP_CONFIG.routes.adminUsersBase + id + suffix;
  var opts = {
    method: method,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': window.WEBAPP_CONFIG.csrfToken,
      'Content-Type': 'application/json',
    },
  };
  if (body) opts.body = JSON.stringify(body);

  fetch(url, opts)
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.success) {
        showToast(successMsg || 'Thành công');
        loadUsers(true);
      } else {
        showToast(data.message || 'Có lỗi xảy ra');
      }
    })
    .catch(function() { showToast('Lỗi kết nối'); });
}

// ============ KPI & TEAM SALE ============
var _kpiTeamData = null;

window.loadKpiTeamData = function(force) {
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if (!cfg || !cfg.kpiTeamJson) return;
  if (_kpiTeamData && !force) { renderKpiTeam(_kpiTeamData); return; }

  var loading  = document.getElementById('kpiTeamLoading');
  var content  = document.getElementById('kpiTeamContent');
  var empty    = document.getElementById('kpiTeamEmpty');
  if (loading) loading.style.display = '';
  if (content) content.style.display = 'none';
  if (empty)   empty.style.display   = 'none';

  fetch(cfg.kpiTeamJson, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (loading) loading.style.display = 'none';
      if (!data.success || !data.sales || data.sales.length === 0) {
        if (empty) empty.style.display = '';
        return;
      }
      _kpiTeamData = data;
      if (content) content.style.display = '';
      renderKpiTeam(data);
    })
    .catch(function() {
      if (loading) loading.style.display = 'none';
      if (empty)   empty.style.display   = '';
      showToast('Lỗi tải dữ liệu KPI');
    });
};

window.kpiTabSwitch = function(btn, tab) {
  var tabs = btn.closest('.sp-tabs');
  if (tabs) tabs.querySelectorAll('.sp-tab').forEach(function(t) { t.classList.remove('active'); });
  btn.classList.add('active');
  if (_kpiTeamData) renderKpiSaleCards(_kpiTeamData.sales || [], tab);
};

window.kpiToggleDetail = function(saleId) {
  var el = document.getElementById('kpi-detail-' + saleId);
  if (!el) return;
  var isOpen = el.classList.contains('open');
  document.querySelectorAll('.sc-detail.open').forEach(function(d) { d.classList.remove('open'); });
  if (!isOpen) el.classList.add('open');
};

window.kpiSendSupport = function(saleId, saleName) {
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if (!cfg || !cfg.kpiTeamSupport) { showToast('Chức năng không khả dụng'); return; }
  showToast('Đang gửi nhắc nhở cho ' + saleName + '...');
  fetch(cfg.kpiTeamSupport, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': window.WEBAPP_CONFIG.csrfToken,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({ sale_id: saleId }),
  })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      showToast(data.success ? ('Đã nhắc nhở ' + saleName + ' ✓') : (data.message || 'Không gửi được'));
    })
    .catch(function() { showToast('Lỗi kết nối'); });
};

function renderKpiTeam(data) {
  var t = data.team || {};
  var hero = document.getElementById('kpiTeamHero');
  if (hero) {
    hero.innerHTML =
      '<div class="th-label">THÁNG ' + _esc(t.month_label || '') + ' — TEAM SALE</div>' +
      '<div class="th-title">Đà Lạt BĐS · ' + (t.team_count || 0) + ' Sale</div>' +
      '<div class="th-grid">' +
        '<div class="th-stat"><div class="th-stat-val">' + (t.deals_this_month || 0) + '</div><div class="th-stat-lbl">Deals tháng</div></div>' +
        '<div class="th-stat"><div class="th-stat-val">' + (t.closed_this_month || 0) + '</div><div class="th-stat-lbl">Đã chốt</div></div>' +
        '<div class="th-stat"><div class="th-stat-val">' + _esc(t.revenue_this_month || '0đ') + '</div><div class="th-stat-lbl">Doanh số</div></div>' +
        '<div class="th-stat"><div class="th-stat-val">' + _esc(t.commission_this_month || '0đ') + '</div><div class="th-stat-lbl">HH phát sinh</div></div>' +
      '</div>';
  }

  var lb = document.getElementById('kpiLeaderboard');
  if (lb) {
    var rankColors = { 1: 'var(--success)', 2: 'var(--primary)', 3: 'var(--warning)' };
    var lbHtml =
      '<div class="lb-title"><span style="display:inline-flex;align-items:center;gap:5px;">' +
      '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 14 20 9 15 4"/><path d="M4 20v-7a4 4 0 0 1 4-4h12"/></svg>' +
      ' BXH Tháng này</span></div>';
    (data.leaderboard || []).forEach(function(s) {
      var rankClass = s.rank <= 3 ? 'rank-' + s.rank : 'rank-n';
      var color = rankColors[s.rank] || 'var(--text-tertiary)';
      lbHtml +=
        '<div class="lb-row">' +
          '<div class="rank-badge ' + rankClass + '">' + s.rank + '</div>' +
          '<div class="lb-name">' + _esc(s.name) + '</div>' +
          '<div class="lb-bar-wrap"><div class="lb-bar-bg"><div class="lb-bar-fill" style="width:' + (s.bar_pct || 5) + '%;background:' + color + '"></div></div></div>' +
          '<div class="lb-val" style="color:' + color + '">' + (s.da_chot || 0) + ' chốt</div>' +
        '</div>';
    });
    lb.innerHTML = lbHtml;
  }

  var sales = data.sales || [];
  var countActive  = sales.filter(function(s) { return !s.needs_support && (s.dang_cham > 0 || s.da_chot > 0); }).length;
  var countSupport = sales.filter(function(s) { return s.needs_support; }).length;
  var tabAll     = document.getElementById('kpiTabAll');
  var tabActive  = document.getElementById('kpiTabActive');
  var tabSupport = document.getElementById('kpiTabSupport');
  if (tabAll)     tabAll.textContent     = 'Tất cả (' + sales.length + ')';
  if (tabActive)  tabActive.textContent  = 'Đang active (' + countActive + ')';
  if (tabSupport) tabSupport.textContent = 'Cần hỗ trợ (' + countSupport + ')';

  // Reset to "all" tab
  var tabBar = document.querySelector('#subpage-kpiteam .sp-tabs');
  if (tabBar) tabBar.querySelectorAll('.sp-tab').forEach(function(t, i) { t.classList.toggle('active', i === 0); });

  renderKpiSaleCards(sales, 'all');
}

function renderKpiSaleCards(sales, tab) {
  var filtered = sales;
  if (tab === 'active')  filtered = sales.filter(function(s) { return !s.needs_support && (s.dang_cham > 0 || s.da_chot > 0); });
  if (tab === 'support') filtered = sales.filter(function(s) { return s.needs_support; });

  var list = document.getElementById('kpiSaleCardsList');
  if (!list) return;

  if (filtered.length === 0) {
    list.innerHTML = '<div style="padding:48px 24px;text-align:center;color:var(--text-tertiary);font-size:13px;">Không có nhân viên nào trong mục này</div>';
    return;
  }
  list.innerHTML = filtered.map(renderSaleCard).join('') + '<div style="height:20px;"></div>';
}

var _kpiAvatarColors = ['var(--primary)', 'var(--teal)', '#f59e0b', '#8b5cf6', '#059669', '#ef4444', '#0ea5e9'];

function renderSaleCard(s) {
  var avatarColor = _kpiAvatarColors[s.id % _kpiAvatarColors.length];
  var rankClass   = s.rank <= 3 ? 'rank-' + s.rank : 'rank-n';
  var borderStyle = s.needs_support ? 'border-color:var(--warning);border-left:3px solid var(--warning);' : '';
  var statusLabel = s.is_online ? 'Online' : (s.last_seen_label || 'Offline');
  var statusColor = s.is_online ? 'color:var(--success);' : 'color:var(--text-tertiary);';
  var onlineDot   = s.is_online ? '<div class="sc-online"></div>' : '';

  // Close rate bar
  var crPct   = Math.min(s.close_rate || 0, 100);
  var crColor = crPct >= 60 ? 'var(--success)' : (crPct >= 30 ? 'var(--warning)' : 'var(--danger)');

  // Response bar
  var rh      = s.avg_response_h;
  var rhLabel = rh === null || rh === undefined ? '—' : rh + 'h';
  var rhPct   = rh === null || rh === undefined ? 0 : Math.max(5, Math.min(100, (8 - rh) / 8 * 100));
  var rhColor = (rh === null || rh === undefined) ? 'var(--text-tertiary)' : (rh < 2 ? 'var(--success)' : (rh <= 5 ? 'var(--primary)' : 'var(--danger)'));
  var rhWarn  = rh !== null && rh !== undefined && rh > 5
    ? '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>' : '';

  // Bookings bar
  var bw    = s.bookings_week || 0;
  var bwPct = Math.min(bw, 7) / 7 * 100;

  // Stuck warning banner
  var warnBanner = '';
  if (s.needs_support && s.stuck_items && s.stuck_items.length > 0) {
    var si = s.stuck_items[0];
    warnBanner =
      '<div style="padding:8px 13px;background:var(--warning-light);border-top:1px solid #fde68a;">' +
        '<div style="font-size:11px;font-weight:600;color:var(--warning);display:inline-flex;align-items:center;gap:4px;">' +
          '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>' +
          ' Deal #' + si.deal_id + ' bị stuck ' + si.days_stuck + ' ngày — Chưa cập nhật trạng thái' +
        '</div>' +
      '</div>';
  }

  // Footer buttons
  var footerBtns = '';
  footerBtns +=
    '<button class="sc-btn" onclick="kpiToggleDetail(' + s.id + ')">' +
      '<span style="display:inline-flex;align-items:center;gap:4px;">' +
        '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>' +
        ' Xem chi tiết' +
      '</span>' +
    '</button>';
  if (s.needs_support) {
    footerBtns +=
      '<button class="sc-btn danger" onclick="kpiSendSupport(' + s.id + ', \'' + _esc(s.name) + '\')">' +
        '<span style="display:inline-flex;align-items:center;gap:4px;">' +
          '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>' +
          ' Hỗ trợ ngay' +
        '</span>' +
      '</button>';
  } else {
    footerBtns +=
      '<button class="sc-btn primary" onclick="openSubpage(\'assignlead\')">' +
        '<span style="display:inline-flex;align-items:center;gap:4px;">' +
          '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>' +
          ' Assign Lead' +
        '</span>' +
      '</button>';
  }

  // Detail panel: recent activities
  var actHtml = '';
  if (s.recent_activities && s.recent_activities.length > 0) {
    actHtml = '<div style="margin-top:10px;"><div style="font-size:11px;font-weight:700;color:var(--text-tertiary);margin-bottom:6px;">HOẠT ĐỘNG GẦN ĐÂY</div>';
    s.recent_activities.forEach(function(a) {
      actHtml +=
        '<div class="sc-tl-item">' +
          '<div class="sc-tl-dot" style="background:var(--primary);"></div>' +
          '<div class="sc-tl-text">' + _esc(a.type_label) + ' — ' + _esc(a.customer_name) + (a.content ? ': ' + _esc(a.content) : '') + '</div>' +
          '<div class="sc-tl-time">' + _esc(a.time_ago) + '</div>' +
        '</div>';
    });
    actHtml += '</div>';
  } else {
    actHtml = '<div style="font-size:12px;color:var(--text-tertiary);padding:8px 0;">Chưa có hoạt động nào</div>';
  }

  // Top tháng badge
  var topBadge = s.rank === 1
    ? '<div style="font-size:10px;color:var(--success);font-weight:600;display:inline-flex;align-items:center;gap:2px;">' +
        '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2c0 6-8 10-8 10s8 4 8 10c0-6 8-10 8-10S12 8 12 2z"/></svg>' +
        ' Top tháng</div>'
    : '';

  return (
    '<div class="sale-card" style="' + borderStyle + '">' +
      '<div class="sc-head">' +
        '<div class="sc-avatar" style="background:' + avatarColor + ';">' + _esc(s.initials) + onlineDot + '</div>' +
        '<div class="sc-info">' +
          '<div class="sc-name">' + _esc(s.name) + '</div>' +
          '<div class="sc-role">' +
            '<span class="badge badge-blue" style="font-size:9px;">Sale</span>' +
            '<span style="' + statusColor + '">· ' + _esc(statusLabel) + '</span>' +
          '</div>' +
        '</div>' +
        '<div style="text-align:right;">' +
          '<div class="rank-badge ' + rankClass + '" style="margin-left:auto;margin-bottom:4px;">#' + s.rank + '</div>' +
          topBadge +
        '</div>' +
      '</div>' +
      '<div class="sc-kpi-grid">' +
        '<div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--danger);">' + (s.lead_moi || 0) + '</div><div class="sc-kpi-lbl">Lead mới</div></div>' +
        '<div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--primary);">' + (s.dang_cham || 0) + '</div><div class="sc-kpi-lbl">Đang chăm</div></div>' +
        '<div class="sc-kpi"><div class="sc-kpi-val" style="color:var(--success);">' + (s.da_chot || 0) + '</div><div class="sc-kpi-lbl">Đã chốt</div></div>' +
        '<div class="sc-kpi"><div class="sc-kpi-val" style="color:' + (s.da_chot > 0 ? 'var(--success)' : 'var(--text-tertiary)') + ';">' + _esc(s.hh_du_kien || '0đ') + '</div><div class="sc-kpi-lbl">HH dự kiến</div></div>' +
      '</div>' +
      warnBanner +
      '<div class="sc-perf">' +
        '<div class="sc-perf-row">' +
          '<span class="sc-perf-label">Tỉ lệ chốt</span>' +
          '<div class="sc-perf-bar"><div class="sc-perf-fill" style="width:' + crPct + '%;background:' + crColor + ';"></div></div>' +
          '<span class="sc-perf-val" style="color:' + crColor + ';">' + crPct + '%</span>' +
        '</div>' +
        '<div class="sc-perf-row">' +
          '<span class="sc-perf-label">Phản hồi lead</span>' +
          '<div class="sc-perf-bar"><div class="sc-perf-fill" style="width:' + Math.round(rhPct) + '%;background:' + rhColor + ';"></div></div>' +
          '<span class="sc-perf-val" style="color:' + rhColor + ';">' + rhLabel + rhWarn + '</span>' +
        '</div>' +
        '<div class="sc-perf-row">' +
          '<span class="sc-perf-label">Lịch hẹn/tuần</span>' +
          '<div class="sc-perf-bar"><div class="sc-perf-fill" style="width:' + Math.round(bwPct) + '%;background:var(--purple);"></div></div>' +
          '<span class="sc-perf-val" style="color:var(--purple);">' + bw + '</span>' +
        '</div>' +
      '</div>' +
      '<div class="sc-footer">' + footerBtns + '</div>' +
      '<div class="sc-detail" id="kpi-detail-' + s.id + '">' +
        '<div style="padding:10px 13px;">' + actHtml + '</div>' +
      '</div>' +
    '</div>'
  );
}

function _esc(str) {
  if (str === null || str === undefined) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

// ============ HEADER PANELS ============
var _npFilter = 'all';

var _NP_ICONS = {
  target: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>',
  bell: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
  'user-plus': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>',
  calendar: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
  clipboard: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>',
  refresh: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>',
  check: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
  'x-circle': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
  clock: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
  dollar: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
  handshake: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
  'alert-triangle': '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
  activity: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>'
};

var _NP_TYPE_SUBPAGE = {
  lead_assigned:'leads', lead_followup:'leads', lead_created:'leads',
  booking_reminder:'bookings', booking_result:'bookings', booking_changed:'bookings',
  property_approved:'mybds', property_rejected:'mybds', property_pending:'approvebds',
  commission_status:'commissions', commission_completed:'commissions',
  deal_created:'deals', deal_stuck:'deals', deal_status:'deals'
};

window.toggleNotifPanel = function(e) {
  if (e) e.stopPropagation();
  var panel = document.getElementById('notif-panel');
  var isOpen = panel && panel.style.display !== 'none';
  closeAllPanels();
  if (!isOpen) {
    panel.style.display = 'flex';
    document.getElementById('panel-backdrop').style.display = 'block';
    loadNotifPanel(_npFilter);
  }
};

window.closeNotifPanel = function() {
  var panel = document.getElementById('notif-panel');
  if (panel) panel.style.display = 'none';
};

window.toggleUserMenu = function(e) {
  if (e) e.stopPropagation();
  var menu = document.getElementById('user-menu');
  var isOpen = menu && menu.style.display !== 'none';
  closeAllPanels();
  if (!isOpen) {
    menu.style.display = 'block';
    document.getElementById('panel-backdrop').style.display = 'block';
  }
};

window.closeUserMenu = function() {
  var menu = document.getElementById('user-menu');
  if (menu) menu.style.display = 'none';
};

window.closeAllPanels = function() {
  closeNotifPanel();
  closeUserMenu();
  var bd = document.getElementById('panel-backdrop');
  if (bd) bd.style.display = 'none';
};

window.notifPanelTab = function(tab) {
  _npFilter = tab;
  var allBtn = document.getElementById('nptab-all');
  var unreadBtn = document.getElementById('nptab-unread');
  if (allBtn) allBtn.classList.toggle('active', tab === 'all');
  if (unreadBtn) unreadBtn.classList.toggle('active', tab === 'unread');
  loadNotifPanel(tab);
};

function loadNotifPanel(tab) {
  var list = document.getElementById('notif-panel-list');
  if (!list) return;
  list.innerHTML = '<div class="notif-panel-loading">Đang tải...</div>';

  var cfg = window.WEBAPP_CONFIG || {};
  var url = '/webapp/api/notifications?per_page=10';
  if (tab === 'unread') url += '&unread=1';

  fetch(url, {
    headers: {
      'X-CSRF-TOKEN': cfg.csrfToken || '',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(function(r) { return r.json(); })
  .then(function(json) {
    if (!json.success || !json.notifications || !json.notifications.length) {
      list.innerHTML = '<div class="notif-panel-loading">Không có thông báo nào</div>';
      return;
    }
    var html = '';
    json.notifications.forEach(function(n) {
      var iconBg = (n.type_config && n.type_config.icon_bg) ? n.type_config.icon_bg : 'var(--bg-secondary)';
      var iconName = (n.type_config && n.type_config.icon) ? n.type_config.icon : 'bell';
      var iconSvg = _NP_ICONS[iconName] || _NP_ICONS['bell'];
      var unreadClass = n.is_unread ? ' unread' : '';
      html += '<div class="np-item' + unreadClass + '" onclick="notifPanelClick(' + n.id + ',\'' + _esc(n.type) + '\')">';
      html += '<div class="np-icon" style="background:' + iconBg + ';">' + iconSvg + '</div>';
      html += '<div class="np-body">';
      html += '<div class="np-title">' + _esc(n.title) + '</div>';
      if (n.body) html += '<div class="np-desc">' + _esc(n.body) + '</div>';
      html += '<div class="np-time">' + _esc(n.time_ago || '') + '</div>';
      html += '</div>';
      if (n.is_unread) html += '<div class="np-dot"></div>';
      html += '</div>';
    });
    list.innerHTML = html;
  })
  .catch(function() {
    list.innerHTML = '<div class="notif-panel-loading">Không thể tải thông báo</div>';
  });
}

window.notifPanelClick = function(id, type) {
  var cfg = window.WEBAPP_CONFIG || {};
  fetch('/webapp/api/notifications/' + id + '/read', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': cfg.csrfToken || '',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  });
  closeAllPanels();
  var subpage = _NP_TYPE_SUBPAGE[type];
  if (subpage && typeof openSubpage === 'function') {
    openSubpage(subpage);
  } else {
    goTo('activity');
  }
};

// Sync header badge with the unread count (called from activityApp.updateBadge)
var _origUpdateBadge;
document.addEventListener('webapp:badge-update', function(e) {
  var count = e.detail && e.detail.count ? e.detail.count : 0;
  var badge = document.getElementById('header-notif-badge');
  if (badge) {
    badge.textContent = count > 99 ? '99+' : (count > 0 ? count : '');
    badge.style.display = count > 0 ? '' : 'none';
  }
});

// Initial badge load on page ready
(function initHeaderBadge() {
  var cfg = window.WEBAPP_CONFIG || {};
  var url = (cfg.routes && cfg.routes.notificationsUnread) || '/webapp/api/notifications/unread-count';
  fetch(url, {
    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': cfg.csrfToken || '' }
  })
  .then(function(r) { return r.json(); })
  .then(function(json) {
    if (json.success) {
      var count = json.count || 0;
      var badge = document.getElementById('header-notif-badge');
      if (badge) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = count > 0 ? '' : 'none';
      }
      // Also sync the bottom-nav badge
      var navBadge = document.getElementById('notif-badge');
      if (navBadge) {
        navBadge.textContent = count > 0 ? (count > 99 ? '99+' : count) : '';
        navBadge.style.display = count > 0 ? 'flex' : 'none';
      }
    }
  })
  .catch(function() {});
})();

}); // end DOMContentLoaded

// ============ ACTIVITY PAGE (IN-APP NOTIFICATIONS) ============
// Defined outside DOMContentLoaded so Alpine.js can find it during initialization
window.activityApp = function() {
  var ICON_SVGS = {
    target: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>',
    bell: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
    'user-plus': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>',
    calendar: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
    clipboard: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>',
    refresh: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>',
    check: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
    'x-circle': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
    clock: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
    dollar: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
    handshake: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    'alert-triangle': '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    activity: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>',
    eye: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
    phone: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.74a16 16 0 0 0 6.29 6.29l1.63-1.63a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
    'check-circle': '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
  };

  // Action button configs per notification type
  var TYPE_ACTIONS = {
    lead_assigned: [
      { label: 'Xem Lead', primary: true, icon: 'eye', subpage: 'leads' },
      { label: 'Gọi ngay', primary: false, icon: 'phone', action: 'call' }
    ],
    lead_followup: [
      { label: 'Xem Lead', primary: true, icon: 'eye', subpage: 'leads' }
    ],
    lead_created: [
      { label: 'Phân công', primary: true, icon: 'eye', subpage: 'leads' }
    ],
    booking_reminder: [
      { label: 'Xem chi tiết', primary: true, icon: 'clipboard', subpage: 'bookings' },
      { label: 'Dời lịch', primary: false, icon: 'refresh', subpage: 'bookings' }
    ],
    booking_result: [
      { label: 'Xem chi tiết', primary: true, icon: 'clipboard', subpage: 'bookings' }
    ],
    booking_changed: [
      { label: 'Xem chi tiết', primary: true, icon: 'clipboard', subpage: 'bookings' }
    ],
    property_approved: [
      { label: 'Xem tin', primary: true, icon: 'eye', subpage: 'mybds' }
    ],
    property_rejected: [
      { label: 'Xem tin', primary: true, icon: 'eye', subpage: 'mybds' }
    ],
    property_pending: [
      { label: 'Duyệt', primary: true, icon: 'check-circle', action: 'approve' },
      { label: 'Từ chối', primary: false, icon: null, action: 'reject', style: 'color:var(--danger);border-color:var(--danger-light);background:var(--danger-light);' }
    ],
    commission_status: [
      { label: 'Xem hoa hồng', primary: true, icon: 'dollar', subpage: 'commissions' }
    ],
    commission_completed: [
      { label: 'Xem chi tiết', primary: true, icon: 'dollar', subpage: 'commissions' }
    ],
    deal_created: [
      { label: 'Xem Deal', primary: true, icon: 'clipboard', subpage: 'deals' }
    ],
    deal_stuck: [
      { label: 'Xem Deal', primary: true, icon: 'clipboard', subpage: 'deals' }
    ],
    deal_status: [
      { label: 'Xem Deal', primary: true, icon: 'clipboard', subpage: 'deals' }
    ]
  };

  // Navigation mapping
  var TYPE_SUBPAGE = {
    lead_assigned: 'leads', lead_followup: 'leads', lead_created: 'leads',
    booking_reminder: 'bookings', booking_result: 'bookings', booking_changed: 'bookings',
    property_approved: 'mybds', property_rejected: 'mybds', property_pending: 'approvebds',
    commission_status: 'commissions', commission_completed: 'commissions',
    deal_created: 'deals', deal_stuck: 'deals', deal_status: 'deals'
  };

  return {
    tabs: [
      { key: 'all', label: 'Tất cả', adminOnly: false },
      { key: 'lead', label: 'Lead', adminOnly: false },
      { key: 'deal', label: 'Deal', adminOnly: false },
      { key: 'booking', label: 'Lịch hẹn', adminOnly: false },
      { key: 'commission', label: 'Hoa hồng', adminOnly: false },
      { key: 'admin', label: 'Duyệt BĐS', adminOnly: true }
    ],
    activeTab: 'all',
    notifications: [],
    loading: false,
    currentPage: 1,
    lastPage: 1,
    isAdminRole: false,

    get hasMore() { return this.currentPage < this.lastPage; },

    init: function() {
      var cfg = window.WEBAPP_CONFIG || {};
      var role = cfg.customerRole || 'guest';
      this.isAdminRole = ['admin', 'bds_admin', 'sale_admin'].indexOf(role) !== -1;
      this.fetchNotifications();
      this.updateBadge();
    },

    switchTab: function(tab) {
      this.activeTab = tab;
      this.currentPage = 1;
      this.notifications = [];
      this.fetchNotifications();
    },

    fetchNotifications: function() {
      var self = this;
      self.loading = true;
      var cfg = window.WEBAPP_CONFIG || {};
      var url = (cfg.routes && cfg.routes.notificationsJson) || '/webapp/api/notifications';
      var params = 'page=' + self.currentPage;
      if (self.activeTab !== 'all') params += '&category=' + self.activeTab;

      fetch(url + '?' + params, {
        headers: {
          'X-CSRF-TOKEN': cfg.csrfToken || '',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(function(r) { return r.json(); })
      .then(function(json) {
        if (json.success) {
          if (self.currentPage === 1) {
            self.notifications = json.notifications;
          } else {
            self.notifications = self.notifications.concat(json.notifications);
          }
          self.lastPage = json.pagination.last_page;
        }
        self.loading = false;
      })
      .catch(function() {
        self.loading = false;
      });
    },

    loadMore: function() {
      this.currentPage++;
      this.fetchNotifications();
    },

    openDetail: function(notif) {
      // Mark as read
      if (notif.is_unread) {
        var cfg = window.WEBAPP_CONFIG || {};
        fetch('/webapp/api/notifications/' + notif.id + '/read', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': cfg.csrfToken || '',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        notif.is_unread = false;
        this.updateBadge();
      }
      // Navigate to relevant subpage
      var subpage = TYPE_SUBPAGE[notif.type];
      if (subpage && typeof openSubpage === 'function') {
        openSubpage(subpage);
      }
    },

    getActions: function(notif) {
      var actions = TYPE_ACTIONS[notif.type] || [];
      var result = [];
      for (var i = 0; i < actions.length; i++) {
        var a = actions[i];
        var iconHtml = a.icon && ICON_SVGS[a.icon] ? ICON_SVGS[a.icon] + ' ' : '';
        result.push({
          label: a.label,
          primary: a.primary,
          style: a.style || '',
          html: '<span style="display:inline-flex;align-items:center;gap:4px;">' + iconHtml + a.label + '</span>',
          _action: a.action || null,
          _subpage: a.subpage || null,
          _notif: notif
        });
      }
      return result;
    },

    handleAction: function(action, notif) {
      if (action._action === 'call') {
        var phone = notif.data && notif.data.customer_phone;
        if (phone) {
          window.location.href = 'tel:' + phone;
        } else {
          showToast('Không có số điện thoại');
        }
        return;
      }
      if (action._action === 'approve' && notif.data && notif.data.property_id) {
        var cfg = window.WEBAPP_CONFIG || {};
        fetch('/webapp/api/admin/properties/' + notif.data.property_id + '/approve', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': cfg.csrfToken || '', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function(r) { return r.json(); }).then(function(res) {
          if (res.success) showToast('Đã duyệt BĐS!');
          else showToast(res.message || 'Lỗi');
        });
        return;
      }
      if (action._action === 'reject' && notif.data && notif.data.property_id) {
        if (typeof openSubpage === 'function') openSubpage('approvebds');
        return;
      }
      if (action._subpage && typeof openSubpage === 'function') {
        openSubpage(action._subpage);
      }
    },

    getIconSvg: function(iconName) {
      return ICON_SVGS[iconName] || ICON_SVGS['bell'];
    },

    updateBadge: function() {
      var cfg = window.WEBAPP_CONFIG || {};
      var url = (cfg.routes && cfg.routes.notificationsUnread) || '/webapp/api/notifications/unread-count';
      fetch(url, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': cfg.csrfToken || '' }
      })
      .then(function(r) { return r.json(); })
      .then(function(json) {
        if (json.success) {
          var badge = document.getElementById('notif-badge');
          if (badge) {
            badge.textContent = json.count > 0 ? (json.count > 99 ? '99+' : json.count) : '';
            badge.style.display = json.count > 0 ? '' : 'none';
          }
        }
      })
      .catch(function() {});
    }
  };
};
