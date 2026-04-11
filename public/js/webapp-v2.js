document.addEventListener('DOMContentLoaded', function(){

// ============ NAVIGATION ============
const pages = ['home','search','post','activity','profile'];
const navIds = ['nav-home','nav-search','nav-post','nav-activity','nav-profile'];

window.goTo = function(page){
  if((page === 'activity' || page === 'profile') && window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.customerRole === 'guest') {
    showGuestDialog();
    return;
  }
  
  pages.forEach(p=>{
    const el=document.getElementById('page-'+p);
    if(el) el.classList.toggle('active',p===page);
  });
  navIds.forEach((id,i)=>{
    const btn=document.getElementById(id);
    if(btn) btn.classList.toggle('active',pages[i]===page);
  });
  document.getElementById('scrollArea').scrollTop=0;
  window.dispatchEvent(new CustomEvent('webapp:page-changed', { detail: { page: page } }));
};

window.toggleSearch = function(){goTo('search');};

// ============ HASH-BASED NAVIGATION ============
(function(){
  var hash = window.location.hash.replace('#', '');
  if(hash && pages.indexOf(hash) !== -1) {
    goTo(hash);
    history.replaceState(null, '', window.location.pathname);
  }
})();

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
  var propertyId = currentDetailPropId;
  if(!propertyId) { showToast('Không tìm thấy BĐS'); return; }

  var cfg = window.WEBAPP_CONFIG || {};
  var url = cfg.routes && cfg.routes.adminPropertiesBase ? cfg.routes.adminPropertiesBase + propertyId + '/approve' : null;
  var csrf = cfg.csrfToken;
  if(!url || !csrf) { showToast('Lỗi cấu hình'); return; }

  // Disable buttons during request
  document.querySelectorAll('[data-for-role] .crm-primary-btn').forEach(function(b){ b.disabled = true; b.style.opacity = '0.5'; });

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({}),
  })
  .then(function(r){ return r.json(); })
  .then(function(data){
    if(data.success) {
      showToast('✓ Đã duyệt BĐS — Broker đã được thông báo');
      // Update status badge on detail page
      var sbadge = document.getElementById('detailStatusBadge');
      if(sbadge) { sbadge.textContent = 'Đã duyệt'; sbadge.className = 'badge badge-green'; }
      // Also remove from approval list if open
      var card = document.getElementById('abds-' + propertyId);
      if(card) {
        card.style.transition = 'opacity .3s';
        card.style.opacity = '0';
        setTimeout(function(){ if(card.parentNode) card.parentNode.removeChild(card); }, 300);
      }
      _abdsUpdatePendingCount(data.pending_count);
      // Increment today count locally
      var todayEl = document.getElementById('abdsApprovedToday');
      if(todayEl) todayEl.textContent = (parseInt(todayEl.textContent, 10) || 0) + 1;
    } else {
      showToast(data.message || 'Có lỗi xảy ra');
    }
  })
  .catch(function(){ showToast('Lỗi kết nối'); })
  .finally(function(){
    document.querySelectorAll('[data-for-role] .crm-primary-btn').forEach(function(b){ b.disabled = false; b.style.opacity = ''; });
  });
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

// ============ UNIFIED PROPERTY SHARE MODULE ============
var _sharePropId = null, _sharePropTitle = null, _sharePropSlug = null;

function openPropertyShareSheet(id, title, slug) {
  _sharePropId = id; _sharePropTitle = title; _sharePropSlug = slug;
  logAction('property', id, 'share', title);
  document.getElementById('shareSheetOverlay').classList.add('open');
}

window.copyPropertyShareLink = function() {
  var tg = window.Telegram && window.Telegram.WebApp;
  var url;
  if (tg && tg.initData) {
    var cfg = window.WEBAPP_CONFIG || {};
    url = 'https://t.me/' + (cfg.telegramBotUsername || 'dalatbds_telegram_bot')
        + '/' + (cfg.telegramWebappShortName || 'dangtin')
        + '?startapp=property_' + _sharePropId;
  } else {
    url = window.location.origin + '/share/p/' + _sharePropId;
  }
  if (navigator.clipboard) navigator.clipboard.writeText(url);
  closeShareSheet();
  showToast('Đã sao chép link BĐS!');
};

window.openSendFromShare = function() {
  closeShareSheet();
  openSendModal();
};

window.closeShareSheet = function() {
  document.getElementById('shareSheetOverlay').classList.remove('open');
};

// Nút Chia sẻ trên prop-card: mở share sheet
window.propShareAction = function(propData, e) {
  e.stopPropagation();
  openPropertyShareSheet(propData.id, propData.title, propData.slug);
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
  if(id === 'users') { usersCurrentTab = 'brokers'; document.getElementById('usersSearchInput').value = ''; loadUsers(true); }
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
    var urlParams = new URLSearchParams(window.location.search);
    var urlTab = urlParams.get('tab');

    abdsCurrentTab = (urlTab === 'pending' || urlTab === 'approved' || urlTab === 'rejected' || urlTab === 'hidden') ? urlTab : 'pending';

    var searchEl = document.getElementById('abdsApprovedSearch');
    // DO NOT load ref_slug into the backend search query string
    abdsCurrentSearch = '';
    if (searchEl) searchEl.value = '';
    window._approvebdsSearchId = null;

    // Reset filter người đăng khi mở subpage thông thường (không phải từ viewUserBds)
    abdsFilterAddedBy = null;

    document.querySelectorAll('#abdsTabBar .sp-tab').forEach(function(t) { t.classList.remove('active'); });
    var abdsDefaultTab = document.querySelector('#abdsTabBar [data-tab="' + abdsCurrentTab + '"]');
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
  if(id === 'marketprices') { loadMarketPrices(true); }
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

// ============ HELPER: FORMAT GIÁ TIỀN SANG CHỮ VN ============
function formatPriceToVNText(price) {
  if (!price || price <= 0) return 'Thỏa thuận';
  var ty = 1000000000;
  var trieu = 1000000;
  if (price >= ty) {
    var soTy = Math.floor(price / ty);
    var duTrieu = Math.round((price % ty) / (100 * trieu)); // 100 triệu = 0.1 tỷ
    if (duTrieu === 0) return soTy + ' tỷ';
    if (duTrieu === 10) return (soTy + 1) + ' tỷ';
    return soTy + ' tỷ ' + duTrieu;
  }
  if (price >= trieu) {
    var soTrieu = Math.round(price / trieu);
    return soTrieu + ' triệu';
  }
  return new Intl.NumberFormat('vi-VN').format(price) + ' ₫';
}

// ============ ADMIN — DUYỆT BĐS ============
var currentRejectId    = null;
var abdsCurrentTab     = 'pending';
var abdsCurrentFilters = {};
var abdsCurrentSearch  = '';
var abdsSearchTimer    = null;
// ID người dùng cần filter (set bởi viewUserBds, reset về null khi mở subpage thông thường)
var abdsFilterAddedBy  = null;

// Cache dữ liệu card để filter client-side (được dùng bởi approvebds.blade.php)
if(typeof window._abdsCardData === 'undefined') {
  window._abdsCardData = {};
}

// Stat blocks as tabs
window.switchAbdsStatTab = function(tab, statEl) {
  abdsCurrentTab = tab;
  // Hỗ trợ cả class cũ và mới
  if(statEl) {
    var container = statEl.closest('#abdsTabBar') || statEl.closest('.admin-hero');
    if(container) {
      container.querySelectorAll('.sp-tab, .ah-stat--clickable').forEach(function(s) {
        s.classList.remove('active', 'ah-stat--active');
      });
    }
    statEl.classList.add('active'); // Cho UI mới
    statEl.classList.add('ah-stat--active'); // Fallback cũ
  }

  // Hiển thị/ẩn filter bar: chỉ xuất hiện khi đang ở tab "approved" hoặc "hidden"
  var filterBar = document.getElementById('abdsApprovedFilter');
  if(filterBar) filterBar.style.display = (tab === 'approved' || tab === 'hidden') ? 'block' : 'none';

  // Reset bộ lọc khi rời khỏi cả hai tab có filter
  if(tab !== 'approved' && tab !== 'hidden' && typeof _abdsResetApprovedFilter === 'function') {
    _abdsResetApprovedFilter();
  }

  loadApprovalBds(true);
};

// Keep old function for backward compat
window.switchAbdsTab = function(tab, btn) {
  abdsCurrentTab = tab;
  loadApprovalBds(true);
};

window.abdsOnSearchInput = function(val) {
  clearTimeout(abdsSearchTimer);
  abdsSearchTimer = setTimeout(function() {
    abdsCurrentSearch = val.trim();
    loadApprovalBds(true);
  }, 400);
};

window.loadApprovalBds = function(reset) {
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  if(!cfg || !cfg.adminPropertiesJson) return;
  
  var params = new URLSearchParams();
  params.append('tab', abdsCurrentTab);
  
  var sp = new URLSearchParams(window.location.search);
  var refSlugUrl = sp.get('ref_slug');
  if(refSlugUrl) {
    params.append('ref_slug', refSlugUrl);
  }

  if(abdsCurrentSearch) params.append('search', abdsCurrentSearch);
  // Lọc theo người đăng khi được gọi từ viewUserBds
  if(abdsFilterAddedBy) params.append('added_by', abdsFilterAddedBy);
  if(abdsCurrentFilters) {
    Object.keys(abdsCurrentFilters).forEach(key => {
      if(abdsCurrentFilters[key]) params.append(key, abdsCurrentFilters[key]);
    });
  }

  var url = cfg.adminPropertiesJson + '?' + params.toString();
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
      // Cập nhật các chỉ số thống kê trên hero
      var heroMain = document.getElementById('abdsHeroMain');
      if(heroMain) heroMain.textContent = (s.pending || 0) + ' BĐS chờ xem xét';
      var elPending = document.getElementById('abdsPendingCount');
      if(elPending) elPending.textContent = s.pending || 0;
      var elToday = document.getElementById('abdsApprovedToday');
      if(elToday) elToday.textContent = s.approved_today || 0;
      var elTotal = document.getElementById('abdsTotalApproved');
      if(elTotal) elTotal.textContent = s.total_approved || 0;
      var elRejected = document.getElementById('abdsRejectedCount');
      if(elRejected) elRejected.textContent = s.rejected || 0;
      // Cập nhật số BĐS đã ẩn (status=3)
      var elHidden = document.getElementById('abdsHiddenCount');
      if(elHidden) elHidden.textContent = s.hidden || 0;

      // Highlight active stat tab
      document.querySelectorAll('.admin-hero .ah-stat--clickable').forEach(function(s) {
        s.classList.toggle('ah-stat--active', s.getAttribute('data-tab') === abdsCurrentTab);
      });

      var props = data.properties || [];
      if(props.length === 0) {
        container.innerHTML =
          '<div style="text-align:center;padding:48px 16px;color:var(--text-tertiary);">'
          + '<div style="font-size:14px;">Không có BĐS nào</div></div>';
        return;
      }
      // Reset cache card data trước khi render batch mới
      window._abdsCardData = {};
      var html = '';
      props.forEach(function(p) { html += _renderAbdsCard(p); });
      container.innerHTML = html;

      // Auto-filter for ref_slug from URL
      var sp = new URLSearchParams(window.location.search);
      var refSlug = sp.get('ref_slug');
      if(refSlug) {
        var cardsInContainer = container.querySelectorAll('.abds-card');
        cardsInContainer.forEach(function(card) {
          if(card.getAttribute('data-slug') !== refSlug && card.getAttribute('id') !== 'abds-' + refSlug) {
            card.style.display = 'none';
          } else {
            card.style.display = '';
            card.style.border = '2px solid var(--primary)';
          }
        });
        
        var notice = document.createElement('div');
        notice.id = 'abdsSlugFilterNotice';
        notice.style.padding = '12px 16px';
        notice.style.background = 'var(--primary-light)';
        notice.style.color = 'var(--primary-dark)';
        notice.style.borderRadius = '8px';
        notice.style.marginBottom = '16px';
        notice.style.fontSize = '14px';
        notice.style.display = 'flex';
        notice.style.justifyContent = 'space-between';
        notice.style.alignItems = 'center';
        notice.innerHTML = '<span>Đang lọc xem 1 BĐS từ thông báo.</span>' +
          '<button onclick="window.clearAbdsSlugFilter()" style="background:none;border:none;color:var(--primary);font-weight:700;cursor:pointer;">[Xem tất cả]</button>';
        container.insertBefore(notice, container.firstChild);
      }

      // Áp dụng filter nếu đang ở tab có thanh tìm kiếm (approved / hidden)
      if((abdsCurrentTab === 'approved' || abdsCurrentTab === 'hidden') && typeof abdsApprovedFilterApply === 'function') {
        abdsApprovedFilterApply();
      }
    })
    .catch(function() {
      container.innerHTML =
        '<div style="text-align:center;padding:32px;color:var(--danger);">Lỗi kết nối. '
        + '<button onclick="loadApprovalBds(true)" style="color:var(--primary);text-decoration:underline;background:none;border:none;cursor:pointer;">Thử lại</button></div>';
    });
};

function _renderAbdsCard(p) {
  // Lưu dữ liệu card vào cache để filter client-side (tab "Đã duyệt")
  window._abdsCardData[p.id] = {
    title:        (p.title       || '').toLowerCase(),
    broker:       (p.broker_name || '').toLowerCase(),
    addr:         ((p.street || '') + ' ' + (p.ward || '')).toLowerCase(),
    category:     p.category_name  || '',
    price:        p.price_raw      || 0,
    ward:         p.ward           || '',
    broker_name:  p.broker_name    || '',
    broker_phone: p.broker_phone   || '',
    host_name:    p.host_name      || '',
    host_contact: p.host_contact   || '',
  };

  // === BLOCK A: Thumbnail + Header ===
  var imgUrl = p.title_image || '';
  var thumbHtml = imgUrl
    ? '<div class="abds-thumb"><img src="' + escHtml(imgUrl) + '" alt="" loading="lazy"></div>'
    : '<div class="abds-thumb"><div class="abds-thumb-placeholder">🏠</div></div>';

  var typeBadge = p.property_type === 1 ? 'Cho thuê' : 'Bán';
  var typeCls = p.property_type === 1 ? 'rent' : 'sell';

  var addr = '';
  if(p.street) addr += p.street;
  if(p.ward) addr += (addr ? ', ' : '') + p.ward;
  if(addr) addr += ', TP.Đà Lạt';

  var headerContent = '<div class="abds-header-content">'
    + '<div class="abds-prop-header">'
    + '<span class="abds-type-badge ' + typeCls + '">' + typeBadge + '</span>'
    + '<span class="abds-category">' + escHtml(p.category_name || 'BĐS') + '</span>'
    + '</div>'
    + '<div class="abds-title">' + escHtml(p.title || '') + '</div>'
    + (addr ? '<div class="abds-addr"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;flex-shrink:0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + escHtml(addr) + '</div>' : '')
    + '</div>';

  var blockA = '<div class="abds-thumb-header">' + thumbHtml + headerContent + '</div>';

  // === BLOCK B: Price & Area ===
  var priceText = formatPriceToVNText(p.price_raw);
  var block3Items = '';
  block3Items += '<div class="abds-pa-item abds-pa-price"><div class="abds-pa-label">Giá</div><div class="abds-pa-val price">' + escHtml(priceText) + '</div></div>';
  if(p.area) block3Items += '<div class="abds-pa-item"><div class="abds-pa-label">Diện tích</div><div class="abds-pa-val">' + escHtml(p.area) + '</div></div>';
  if(p.direction) block3Items += '<div class="abds-pa-item"><div class="abds-pa-label">Hướng</div><div class="abds-pa-val">' + escHtml(p.direction) + '</div></div>';
  if(p.number_room) block3Items += '<div class="abds-pa-item"><div class="abds-pa-label">Phòng ngủ</div><div class="abds-pa-val">' + escHtml(String(p.number_room)) + ' PN</div></div>';
  var blockB = '<div class="abds-block abds-block-pa"><div class="abds-pa-grid">' + block3Items + '</div></div>';

  // === BLOCK C: Legal Check (with Host contact confirmation on top) ===
  var hasHostContact = !!(p.host_name || p.host_contact);

  var checks = p.checks || {};
  var legalItems = [
    { key: 'has_host_contact',  yes: 'Thông tin liên hệ chủ nhà — Đã nhập', no: 'Thông tin liên hệ chủ nhà — Chưa nhập', val: hasHostContact },
    { key: 'has_legal_docs',    yes: 'Sổ đỏ / GCNQSD — Đã upload',  no: 'Sổ đỏ / GCNQSD — Chưa upload' },
    { key: 'has_enough_photos', yes: 'Ảnh thực tế — Đã có ảnh', no: 'Ảnh chưa có — Cần thêm ít nhất 1 ảnh' },
    { key: 'location_valid',    yes: 'Vị trí / địa chỉ đầy đủ',     no: 'Vị trí / địa chỉ thiếu thông tin' },
    { key: 'price_reasonable',  yes: 'Giá đã nhập',                  no: 'Chưa nhập giá' },
  ];
  var legalHtml = '';
  legalItems.forEach(function(item) {
    var pass = item.val !== undefined ? !!item.val : !!checks[item.key];
    var dotCls = pass ? 'yes' : 'no';
    var dotIcon = pass ? '✓' : '✕';
    var txt = pass ? item.yes : item.no;
    var style = pass ? '' : ' style="color:var(--danger);"';
    legalHtml += '<div class="abds-legal-item"><div class="abds-legal-dot ' + dotCls + '">' + dotIcon + '</div>'
      + '<span class="abds-legal-text"' + style + '>' + escHtml(txt) + '</span></div>';
  });

  var blockC = '<div class="abds-legal"><div class="abds-legal-title">Kiểm tra pháp lý</div>' + legalHtml + '</div>';

  // === Warning / Rejected / Approver banners ===
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

  var approverBadge = '';
  if(p.status === 1 && p.approved_by_name) {
    approverBadge = '<div style="padding:7px 13px;background:#f0fdf4;border-top:1px solid #bbf7d0;display:flex;align-items:center;gap:6px;">'
      + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>'
      + '<span style="font-size:11px;color:#15803d;">Đã duyệt bởi <strong>' + escHtml(p.approved_by_name) + '</strong>'
      + (p.approved_at_full ? ' · ' + escHtml(p.approved_at_full) : '')
      + '</span></div>';
  } else if(p.status === 2 && p.rejected_by_name) {
    approverBadge = '<div style="padding:7px 13px;background:#fef2f2;border-top:1px solid #fecaca;display:flex;align-items:center;gap:6px;">'
      + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>'
      + '<span style="font-size:11px;color:#b91c1c;">Từ chối bởi <strong>' + escHtml(p.rejected_by_name) + '</strong>'
      + (p.rejected_at_full ? ' · ' + escHtml(p.rejected_at_full) : '')
      + '</span></div>';
  }

  // === BLOCK D: Commission (primary color) ===
  var commText = formatPriceToVNText(p.commission_raw);
  var commBlock = '';
  if(p.commission_raw && p.commission_raw > 0) {
    commBlock = '<div class="abds-commission-row">'
      + '<div class="abds-comm-label"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:4px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>Hoa hồng</div>'
      + '<div class="abds-comm-val">' + escHtml(commText) + '</div>'
      + '</div>';
  }

  // === BLOCK E: Broker Info (moved to bottom) ===
  var brokerPhone = p.broker_phone || '';
  var phoneLink = ''; // Ẩn số điện thoại theo yêu cầu

  var avatarHtml = '';
  if (p.broker_avatar) {
    avatarHtml = '<img src="' + escHtml(p.broker_avatar) + '" alt="' + escHtml(p.broker_name || '') + '" ' +
      'onerror="this.onerror=null; this.outerHTML=\'' + escHtml(p.broker_initials || 'BK') + '\'" ' +
      'style="width:100%; height:100%; object-fit:cover; border-radius:50%;">';
  } else {
    avatarHtml = escHtml(p.broker_initials || 'BK');
  }

  var blockE = '<div class="abds-block abds-block-broker">'
    + '<div class="abds-broker-avatar">' + avatarHtml + '</div>'
    + '<div class="abds-broker-info">'
    + '<div class="abds-broker-name">' + escHtml(p.broker_name || 'Môi giới') + ' <span class="abds-broker-tag">eBroker</span></div>'
    + (phoneLink ? '<div class="abds-broker-contact">' + phoneLink + '</div>' : '')
    + '</div>'
    + '<span class="abds-broker-time">' + escHtml(p.created_at_fmt || '') + '</span>'
    + '</div>';

  // === BLOCK F: Actions ===
  var viewBtn = '<button class="abds-btn view" onclick="openDetail({id:' + p.id + '})"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Xem</span></button>';
  var actionBtns = '';
  if(p.status === 0) {
    // --- Tab "Chờ duyệt": nút Từ chối + Duyệt ---
    var safeTitle = (p.title || 'BĐS').replace(/['"\\\r\n]/g, ' ');
    actionBtns = viewBtn
      + '<button class="abds-btn reject" onclick="event.stopPropagation(); openRejectSheet(' + p.id + ')">✕ Từ chối</button>'
      + '<button class="abds-btn approve" onclick="event.stopPropagation(); approveAbds(' + p.id + ', \'' + safeTitle + '\')">✓ Duyệt</button>';
  } else if(abdsCurrentTab === 'approved') {
    // --- Tab "Đã duyệt" (Admin only): Xem trang công khai + Ẩn BĐS + Đối tượng liên quan ---
    var safeTitleApproved = (p.title || 'BĐS').replace(/['"\\\r\n]/g, ' ');

    // Nút "Xem chi tiết" — mở trang BĐS công khai trong tab mới
    var detailUrl = '/property/' + p.id;
    if(p.slug) detailUrl = '/bat-dong-san/' + p.slug;
    actionBtns = viewBtn
      + '<a href="' + detailUrl + '" target="_blank" class="abds-btn"'
      + ' style="text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:4px;"'
      + ' onclick="event.stopPropagation();">'
      + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>'
      + ' Trang</a>'
      // Nút "Ẩn BĐS" — xác nhận và gọi API ẩn
      + '<button class="abds-btn reject"'
      + ' onclick="event.stopPropagation();hideAbds(' + p.id + ',\'' + safeTitleApproved + '\')">'
      + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
      + ' Ẩn</button>'
      // Nút "Đối tượng liên quan" — mở modal broker/chủ nhà
      + '<button class="abds-btn" style="background:var(--primary-light,#eff6ff);color:var(--primary);border:none;"'
      + ' onclick="event.stopPropagation();openAbdsRelatedModal(' + p.id + ')">'
      + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>'
      + ' Liên quan</button>';
  } else if(abdsCurrentTab === 'hidden') {
    // --- Tab "Đã ẩn" (Admin only): Xem + Khôi phục về đã duyệt + Đối tượng liên quan ---
    var safeTitleHidden = (p.title || 'BĐS').replace(/['"\\\r\n]/g, ' ');
    actionBtns = viewBtn
      // Nút "Khôi phục" — đưa BĐS về trạng thái đã duyệt (status=1)
      + '<button class="abds-btn approve"'
      + ' onclick="event.stopPropagation();restoreAbds(' + p.id + ',\'' + safeTitleHidden + '\')">'
      + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 .49-4.5"/></svg>'
      + ' Khôi phục</button>'
      // Nút "Đối tượng liên quan"
      + '<button class="abds-btn" style="background:var(--primary-light,#eff6ff);color:var(--primary);border:none;"'
      + ' onclick="event.stopPropagation();openAbdsRelatedModal(' + p.id + ')">'
      + '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>'
      + ' Liên quan</button>';
  } else {
    actionBtns = viewBtn;
  }

  var blockF = '<div class="abds-block abds-block-actions">'
    + commBlock
    + '<div class="abds-actions">' + actionBtns + '</div>'
    + '</div>';

  // === Assemble card: A → B → C → banners → D+F (with broker E before actions) ===
  return '<div class="abds-card" id="abds-' + p.id + '" data-slug="' + escHtml(p.slug || '') + '">'
    + blockA
    + blockB
    + blockC
    + warningBanner
    + rejectedBanner
    + approverBadge
    + '<div class="abds-block abds-block-actions">'
    + commBlock
    + blockE
    + '<div class="abds-actions">' + actionBtns + '</div>'
    + '</div>'
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
  if(!selected) { showToast('Vui lòng chọn lý do từ chối', 'warn'); return; }

  var reason = selected.getAttribute('data-reason') || '';
  var note = (document.getElementById('rsNoteText') ? document.getElementById('rsNoteText').value : '').trim();
  if(!currentRejectId) return;

  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  var url = cfg && cfg.adminPropertiesBase ? cfg.adminPropertiesBase + currentRejectId + '/reject' : null;
  var csrf = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if(!url || !csrf) { showToast('Lỗi cấu hình', 'error'); return; }

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
        showToast('✕ Đã từ chối\nBroker đã được thông báo', 'error');
      } else {
        showToast(data.message || 'Có lỗi xảy ra', 'error');
      }
    })
    .catch(function() {
      if(submitBtn) submitBtn.disabled = false;
      showToast('Lỗi kết nối', 'error');
    });
};

window.approveAbds = function(propertyId, name) {
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  var url = cfg && cfg.adminPropertiesBase ? cfg.adminPropertiesBase + propertyId + '/approve' : null;
  var csrf = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if(!url || !csrf) { showToast('Lỗi cấu hình', 'error'); return; }

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
        showToast('✓ Đã duyệt: ' + (name || 'BĐS') + '\nBroker đã được thông báo', 'success');

        // Mock: Telegram notification (handled by backend)
        _mockSendTelegramApproval(propertyId, name);
        // Mock: Activity log (handled by backend InAppNotificationService)
        _mockLogActivity(propertyId, name);
      } else {
        showToast(data.message || 'Có lỗi xảy ra', 'error');
      }
    })
    .catch(function() { showToast('Lỗi kết nối', 'error'); });
};

function _abdsUpdatePendingCount(count) {
  if(count === undefined) return;
  var el = document.getElementById('abdsPendingCount');
  if(el) el.textContent = count;
  var heroMain = document.getElementById('abdsHeroMain');
  if(heroMain) heroMain.textContent = count + ' BĐS chờ xem xét';
}

window.clearAbdsSlugFilter = function() {
  var notice = document.getElementById('abdsSlugFilterNotice');
  if(notice) notice.remove();
  var params = new URLSearchParams(window.location.search);
  params.delete('ref_slug');
  var newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '') + window.location.hash;
  window.history.replaceState(null, '', newUrl);
  
  loadApprovalBds(true);
};

// === Mock functions for future expansion ===
function _mockSendTelegramApproval(propertyId, propertyName) {
  // Backend already sends Telegram notification to broker.
  // This mock is placeholder for additional Telegram channels (e.g., group chat notification).
  console.log('[Mock Telegram] 🎉 BĐS "' + (propertyName || propertyId) + '" đã được duyệt → Thông báo Broker.');
}

function _mockLogActivity(propertyId, propertyName) {
  // Backend already logs via InAppNotificationService.
  // This mock is placeholder for client-side activity feed append.
  console.log('[Mock Activity] 🎉 BĐS "' + (propertyName || propertyId) + '" của bạn đã được admin duyệt thành công!');
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

      // Refresh history tab + reload all status tabs
      loadAssignHistoryOnly();
      // Re-render status tabs if _assignLeadData exists (mark assigned leads)
      if (_assignLeadData) {
        (res.assigned_ids || []).forEach(function(id){
          var lead = (_assignLeadData.leads || []).find(function(l){ return l.id === id; });
          if (lead) { lead.is_assigned = true; lead.sale_name = res.sale_name || ''; }
        });
        var allLeads = _assignLeadData.leads || [];
        _renderAssignStatusTab('assignLeadTabNewContent',       allLeads, 'new');
        _renderAssignStatusTab('assignLeadTabContactedContent', allLeads, 'contacted');
        _renderAssignStatusTab('assignLeadTabConvertedContent', allLeads, 'converted');
        _renderAssignStatusTab('assignLeadTabFailedContent',    allLeads, 'failed');
      }

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
var _assignLeadTabMap = {
  'unassigned': 'assignLeadTabUnassigned',
  'new':        'assignLeadTabNew',
  'contacted':  'assignLeadTabContacted',
  'converted':  'assignLeadTabConverted',
  'failed':     'assignLeadTabFailed',
  'history':    'assignLeadTabHistory',
};
window.assignLeadTabSwitch = function(btn){
  btn.closest('.sp-tabs').querySelectorAll('.sp-tab').forEach(function(t){ t.classList.remove('active'); });
  btn.classList.add('active');
  var tab = btn.dataset.tab;
  Object.values(_assignLeadTabMap).forEach(function(panelId){
    var el = document.getElementById(panelId);
    if (el) el.style.display = 'none';
  });
  var activeId = _assignLeadTabMap[tab];
  if (activeId) {
    var activeEl = document.getElementById(activeId);
    if (activeEl) activeEl.style.display = '';
  }
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

  // Reset all tab panels — show only unassigned on load
  Object.values(_assignLeadTabMap).forEach(function(panelId){
    var el = document.getElementById(panelId);
    if (el) el.style.display = panelId === 'assignLeadTabUnassigned' ? '' : 'none';
  });
  // Reset tab buttons — activate "Chờ assign"
  document.querySelectorAll('#subpage-assignlead .sp-tabs .sp-tab').forEach(function(t){ t.classList.remove('active'); });
  var tabUnassignedBtn = document.getElementById('tabUnassigned');
  if (tabUnassignedBtn) tabUnassignedBtn.classList.add('active');

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

    var sc = data.status_counts || {};
    var bc = data.budget_counts || {};

    // Update budget tier counts (unassigned leads only)
    var hEl = document.getElementById('budgetHigh');
    var mEl = document.getElementById('budgetMedium');
    var lEl = document.getElementById('budgetLow');
    if (hEl) hEl.textContent = bc.high   || 0;
    if (mEl) mEl.textContent = bc.medium  || 0;
    if (lEl) lEl.textContent = bc.low     || 0;

    // Update unassigned pool count badge
    var unassignedCount = sc.unassigned || 0;
    var countEl = document.getElementById('unassignedCount');
    if (countEl) countEl.textContent = unassignedCount + ' lead';

    // Update tab labels with counts
    var tabUnassigned = document.getElementById('tabUnassigned');
    var tabNew        = document.getElementById('tabNew');
    var tabContacted  = document.getElementById('tabContacted');
    var tabConverted  = document.getElementById('tabConverted');
    var tabFailed     = document.getElementById('tabFailed');
    if (tabUnassigned) tabUnassigned.textContent = 'Chờ assign' + (unassignedCount ? ' (' + unassignedCount + ')' : '');
    if (tabNew)        tabNew.textContent        = 'Mới'        + (sc.new       ? ' (' + sc.new       + ')' : '');
    if (tabContacted)  tabContacted.textContent  = 'Đang xử lý' + (sc.contacted ? ' (' + sc.contacted + ')' : '');
    if (tabConverted)  tabConverted.textContent  = 'Đã tạo Deal'+ (sc.converted ? ' (' + sc.converted + ')' : '');
    if (tabFailed)     tabFailed.textContent     = 'Thất bại'   + (sc.failed    ? ' (' + sc.failed    + ')' : '');

    if (loadingEl) loadingEl.style.display = 'none';

    var allLeads = data.leads || [];

    // Tab: Chờ assign — unassigned leads only (with checkbox + bulk assign)
    var unassignedLeads = allLeads.filter(function(l){ return !l.is_assigned; });
    if (unassignedLeads.length === 0) {
      if (emptyEl) emptyEl.style.display = '';
    } else {
      if (listEl) { listEl.innerHTML = renderUnassignedLeadCards(unassignedLeads); listEl.style.display = ''; }
    }

    // Status tabs — render leads by status_raw
    _renderAssignStatusTab('assignLeadTabNewContent',       allLeads, 'new');
    _renderAssignStatusTab('assignLeadTabContactedContent', allLeads, 'contacted');
    _renderAssignStatusTab('assignLeadTabConvertedContent', allLeads, 'converted');
    _renderAssignStatusTab('assignLeadTabFailedContent',    allLeads, 'failed');

    renderSalePickerList(data.sales || []);
    renderAssignHistory(data.history || []);
  })
  .catch(function(){
    if (loadingEl) loadingEl.style.display = 'none';
    if (emptyEl)   emptyEl.style.display = '';
    showToast('Lỗi tải dữ liệu assign lead');
  });
}

function _renderAssignStatusTab(containerId, allLeads, status){
  var el = document.getElementById(containerId);
  if (!el) return;
  var filtered = allLeads.filter(function(l){
    if (status === 'failed') return l.status_raw === 'bad-contact' || l.status_raw === 'lost';
    return l.status_raw === status;
  });
  if (filtered.length === 0) {
    var labels = { new: 'Mới', contacted: 'Đang xử lý', converted: 'Đã tạo Deal', failed: 'Thất bại' };
    el.innerHTML = '<div style="padding:48px 24px;text-align:center;">'
      + '<div style="font-size:28px;margin-bottom:8px;">📋</div>'
      + '<div style="font-size:14px;font-weight:600;color:var(--text-secondary);">Không có lead nào</div>'
      + '<div style="font-size:12px;color:var(--text-tertiary);margin-top:4px;">Chưa có lead trạng thái ' + (labels[status] || status) + '</div>'
      + '</div>';
  } else {
    el.innerHTML = renderStatusLeadCards(filtered);
  }
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

// Render lead cards for status tabs (no checkbox, read-only; assign btn only if unassigned)
function renderStatusLeadCards(leads){
  var svgPhone  = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg>';
  var svgAssign = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';

  var statusConfig = {
    'new':         { label: 'Mới',         style: 'background:#dbeafe;color:#1d4ed8;' },
    'contacted':   { label: 'Đang xử lý',  style: 'background:#fef3c7;color:#d97706;' },
    'converted':   { label: 'Đã tạo Deal', style: 'background:#d1fae5;color:#065f46;' },
    'bad-contact': { label: 'Sai số',      style: 'background:#fee2e2;color:#dc2626;' },
    'lost':        { label: 'Thất bại',    style: 'background:#f3f4f6;color:#6b7280;' },
  };
  var avatarColors = ['#ef4444', 'var(--teal)', 'var(--purple)', '#f59e0b', 'var(--primary)', '#059669'];

  return leads.map(function(lead, index){
    var domId    = 'sll-' + lead.id;
    var avatarBg = avatarColors[index % avatarColors.length];
    var sCfg     = statusConfig[lead.status_raw] || { label: lead.status_raw, style: 'background:#f3f4f6;color:#6b7280;' };

    var catStr  = (lead.categories || []).join(', ');
    var wardStr = (lead.wards || []).join(', ');
    var needStr = [lead.lead_type, lead.purpose].filter(Boolean).join(' ');

    var rows = '';
    if (catStr)  rows += '<div class="ul-row"><span class="ul-label">Loại BĐS</span><span class="ul-value">' + escHtml(catStr) + '</span></div>';
    if (needStr) rows += '<div class="ul-row"><span class="ul-label">Nhu cầu</span><span class="ul-value">' + escHtml(needStr) + '</span></div>';
    if (lead.budget_min || lead.budget_max)
                 rows += '<div class="ul-row"><span class="ul-label">Ngân sách</span><span class="ul-value money">' + escHtml(lead.budget_min + ' – ' + lead.budget_max) + '</span></div>';
    if (wardStr) rows += '<div class="ul-row"><span class="ul-label">Khu vực</span><span class="ul-value">' + escHtml(wardStr) + '</span></div>';

    var footerRight = lead.is_assigned
      ? '<span style="font-size:11px;color:var(--text-secondary);display:inline-flex;align-items:center;gap:3px;">'
          + '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
          + ' ' + escHtml(lead.sale_name)
          + '</span>'
      : '<button class="lc-btn primary" style="height:28px;font-size:11px;" onclick="event.stopPropagation();openSalePicker([\'ull-' + lead.id + '\']);">'
          + '<span style="display:inline-flex;align-items:center;gap:4px;">' + svgAssign + ' Assign ngay</span>'
          + '</button>';

    return '<div class="ul-card" id="' + domId + '">'
      + '<div class="ul-head">'
        + '<div style="width:38px;height:38px;border-radius:50%;background:' + avatarBg + ';display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">' + escHtml(lead.initials) + '</div>'
        + '<div class="ul-info">'
          + '<div class="ul-name">' + escHtml(lead.name) + '</div>'
          + '<div class="ul-meta">'
            + (lead.phone ? '<span>' + svgPhone + escHtml(lead.phone) + '</span>' : '')
            + '<span style="font-size:11px;color:var(--text-tertiary);">' + escHtml(lead.time_ago) + '</span>'
          + '</div>'
        + '</div>'
        + '<div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">'
          + '<span style="font-size:10px;font-weight:600;padding:2px 6px;border-radius:4px;' + sCfg.style + '">' + sCfg.label + '</span>'
        + '</div>'
      + '</div>'
      + (rows ? '<div class="ul-body">' + rows + '</div>' : '')
      + '<div class="ul-footer">'
        + '<span style="font-size:11px;color:var(--text-tertiary);">' + escHtml(lead.source_note || '') + '</span>'
        + footerRight
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
      // Invalidate map so it reloads when user switches to map view
      searchMapNeedsReload = true;
      searchMapData = [];
      hideMapBottomCard();

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

// ============ SEARCH MAP ============
let searchMap = null;
let searchMapMarkers = [];
let searchMapData = [];
let myLocationMarker = null;
let activeSearchMarkerIdx = -1;
let searchMapNeedsReload = true;

window.switchView = function(view){
  document.getElementById('viewList').classList.toggle('active', view==='list');
  document.getElementById('viewMap').classList.toggle('active', view==='map');

  if(view === 'map') {
    const modal = document.getElementById('searchMapModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    if(searchMapNeedsReload) {
      loadSearchMap();
    } else if(searchMap) {
      google.maps.event.trigger(searchMap, 'resize');
      if(activeSearchMarkerIdx === -1 && searchMapMarkers.length > 0) {
        selectSearchMarker(0, searchMapMarkers[0]._pinEl || null);
      }
    }
  } else {
    document.getElementById('listView').style.display = 'block';
  }
};

window.closeSearchMapModal = function() {
  document.getElementById('searchMapModal').style.display = 'none';
  document.body.style.overflow = '';
  document.getElementById('viewMap').classList.remove('active');
  document.getElementById('viewList').classList.add('active');
};

let searchMapIsSatellite = false;
window.toggleMapLayer = function() {
  if(!searchMap) return;
  searchMapIsSatellite = !searchMapIsSatellite;
  searchMap.setMapTypeId(searchMapIsSatellite ? 'hybrid' : 'roadmap');
  const btn = document.getElementById('mapLayerBtn');
  if(btn) {
    btn.style.background = searchMapIsSatellite ? 'var(--primary)' : '#fff';
    btn.querySelector('svg').setAttribute('stroke', searchMapIsSatellite ? '#fff' : 'currentColor');
  }
};

function buildCurrentSearchParams() {
  let params = [];
  const input = document.getElementById('searchInput');
  const query = input ? input.value : '';
  if(query && query !== 'Tất cả') params.push('q=' + encodeURIComponent(query));

  // Use currentFilters (already declared elsewhere in this file)
  if(currentFilters.price) params.push('price=' + encodeURIComponent(currentFilters.price));
  if(currentFilters.categoryName) params.push('categoryName=' + encodeURIComponent(currentFilters.categoryName));
  if(currentSort && currentSort !== 'latest') params.push('sort=' + encodeURIComponent(currentSort));
  if(currentFilters.property_type) params.push('type=' + encodeURIComponent(currentFilters.property_type));
  if(currentFilters.location) params.push('location=' + encodeURIComponent(currentFilters.location));
  if(currentFilters.area) params.push('area_range=' + encodeURIComponent(currentFilters.area));
  if(currentFilters.direction) params.push('direction=' + encodeURIComponent(currentFilters.direction));
  if(currentFilters.legal) params.push('legal=' + encodeURIComponent(currentFilters.legal));

  // Also check active filter chips for price/category not yet in currentFilters
  const activeChips = document.getElementById('activeFilters').querySelectorAll('.af-chip');
  activeChips.forEach(c => {
    let txt = c.textContent.replace('×', '').trim();
    if(!currentFilters.price && (c.dataset.filterType === 'price' || txt.includes('tỷ'))) {
      params.push('price=' + encodeURIComponent(txt));
    } else if(!currentFilters.categoryName && c.dataset.filterType === 'category') {
      params.push('categoryName=' + encodeURIComponent(txt));
    }
  });

  return params.join('&');
}

function loadSearchMap() {
  const params = buildCurrentSearchParams();

  const loadingEl = document.getElementById('mapLoading');
  if(loadingEl) loadingEl.style.display = 'flex';

  fetch('/webapp/search/results/map?' + params)
    .then(r => r.json())
    .then(res => {
      if(res.success) {
        searchMapData = res.properties;
        searchMapNeedsReload = false;
        initSearchMap(res.properties, res.total, res.total_with_coords);
      }
      if(loadingEl) loadingEl.style.display = 'none';
    })
    .catch(() => {
      if(loadingEl) loadingEl.style.display = 'none';
      showToast('Không thể tải bản đồ');
    });
}

function initSearchMap(properties, total, totalWithCoords) {
  const defaultCenter = { lat: 11.9404, lng: 108.4583 };
  const hasAdvancedMarkers = !!(window.google && google.maps.marker && google.maps.marker.AdvancedMarkerElement);

  if(!searchMap) {
    const mapOptions = {
      center: defaultCenter,
      zoom: 14,
      mapTypeControl: false,
      zoomControl: true,
      zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER },
      streetViewControl: false,
      fullscreenControl: false
    };
    if(hasAdvancedMarkers) {
      mapOptions.mapId = 'search_map';
    }
    searchMap = new google.maps.Map(document.getElementById('searchMapCanvas'), mapOptions);

    // Click on map to hide bottom card
    searchMap.addListener('click', function() {
      hideMapBottomCard();
    });
  }

  // Clear existing markers
  searchMapMarkers.forEach(m => {
    if(m.setMap) m.setMap(null); // legacy Marker
    else if(m.map !== undefined) m.map = null; // AdvancedMarkerElement
  });
  searchMapMarkers = [];
  activeSearchMarkerIdx = -1;
  hideMapBottomCard();

  if(properties.length === 0) {
    document.getElementById('mapModalPropertyCount').textContent = '0/' + total + ' BĐS có tọa độ';
    searchMap.setCenter(defaultCenter);
    searchMap.setZoom(14);
    return;
  }

  const bounds = new google.maps.LatLngBounds();

  properties.forEach((p, idx) => {
    const lat = parseFloat(p.latitude);
    const lng = parseFloat(p.longitude);
    if(isNaN(lat) || isNaN(lng)) return;

    const pos = { lat: lat, lng: lng };

    if(hasAdvancedMarkers) {
      // Create price pin HTML element
      const pinEl = document.createElement('div');
      pinEl.className = 'map-pin search-map-pin';
      pinEl.textContent = p.price;
      pinEl.dataset.idx = idx;

      const marker = new google.maps.marker.AdvancedMarkerElement({
        map: searchMap,
        position: pos,
        content: pinEl,
        title: p.title
      });

      marker.addListener('click', () => {
        selectSearchMarker(idx, pinEl);
      });

      marker._pinEl = pinEl;
      searchMapMarkers.push(marker);
    } else {
      // Fallback: regular Marker with label
      const marker = new google.maps.Marker({
        position: pos,
        map: searchMap,
        label: { text: p.price, fontSize: '10px', fontWeight: '700', color: '#fff' },
        title: p.title,
        icon: {
          url: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
          scaledSize: new google.maps.Size(32, 32)
        }
      });

      marker.addListener('click', () => {
        selectSearchMarker(idx, null);
      });

      searchMapMarkers.push(marker);
    }

    bounds.extend(pos);
  });

  // Update count badge
  document.getElementById('mapModalPropertyCount').textContent =
    totalWithCoords + '/' + total + ' BĐS trong khu vực';

  // Fit bounds then auto-select first property
  if(!bounds.isEmpty()) {
    searchMap.fitBounds(bounds, { top: 60, bottom: 100, left: 20, right: 20 });
    google.maps.event.addListenerOnce(searchMap, 'bounds_changed', function() {
      if(this.getZoom() > 16) this.setZoom(16);
    });
    google.maps.event.addListenerOnce(searchMap, 'idle', function() {
      if(searchMapMarkers.length > 0) {
        selectSearchMarker(0, searchMapMarkers[0]._pinEl || null);
      }
    });
  }
}

function selectSearchMarker(idx, pinEl) {
  // Deselect previous
  searchMapMarkers.forEach(m => {
    if(m._pinEl) m._pinEl.classList.remove('active');
  });

  // Select current
  if(pinEl) pinEl.classList.add('active');
  activeSearchMarkerIdx = idx;

  // Pan map
  const p = searchMapData[idx];
  if(p) {
    searchMap.panTo({ lat: parseFloat(p.latitude), lng: parseFloat(p.longitude) });
    renderMapBottomCard(p);
  }
}

function renderMapBottomCard(p) {
  const card = document.getElementById('mapBottomCard');
  const content = document.getElementById('mapBottomCardContent');

  const imgHtml = p.title_image
    ? '<img src="' + escHtml(p.title_image) + '" style="width:100%;height:100%;object-fit:cover;display:block;" alt="">'
    : '<div style="display:flex;align-items:center;justify-content:center;height:100%;background:#1e2a3a;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.7"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>';

  let tagsHtml = '';
  if(p.category_name) tagsHtml += '<span class="badge badge-blue" style="font-size:9px;">' + escHtml(p.category_name) + '</span>';
  if(p.type_label) tagsHtml += '<span class="badge badge-green" style="font-size:9px;margin-left:4px;">' + escHtml(p.type_label) + '</span>';

  let metaParts = [];
  if(p.area) metaParts.push(escHtml(p.area));
  if(p.number_room) metaParts.push(p.number_room + 'PN');
  if(p.legal) metaParts.push(escHtml(p.legal));

  content.innerHTML =
    '<div style="display:flex;gap:12px;padding:0 4px;cursor:pointer;" onclick="hideMapBottomCard(); openDetail({id:' + p.id + '})">' +
      '<div style="width:70px;height:70px;border-radius:10px;overflow:hidden;flex-shrink:0;">' + imgHtml + '</div>' +
      '<div style="flex:1;min-width:0;">' +
        '<div style="display:flex;gap:5px;margin-bottom:4px;">' + tagsHtml + '</div>' +
        '<div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:2px;line-height:1.3;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + escHtml(p.title) + '</div>' +
        '<div style="font-size:14px;font-weight:700;color:var(--primary);">' + escHtml(p.price) + '</div>' +
        '<div style="font-size:11px;color:var(--text-secondary);margin-top:2px;">' + metaParts.join(' · ') + '</div>' +
      '</div>' +
    '</div>' +
    '<div style="display:flex;gap:8px;margin-top:12px;">' +
      '<button style="flex:1;padding:10px;border:1.5px solid var(--border);border-radius:10px;font-size:13px;font-weight:600;color:var(--text-secondary);background:var(--bg-card);" onclick="hideMapBottomCard()">Quay lại</button>' +
      '<button style="flex:1;padding:10px;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#fff;background:var(--primary);" onclick="closeSearchMapModal(); openDetail({id:' + p.id + '})">' +
        'Xem chi tiết' +
      '</button>' +
    '</div>';

  card.style.display = 'block';
}

function hideMapBottomCard() {
  const card = document.getElementById('mapBottomCard');
  if(card) card.style.display = 'none';
  // Deselect markers
  searchMapMarkers.forEach(m => {
    if(m._pinEl) m._pinEl.classList.remove('active');
  });
  activeSearchMarkerIdx = -1;
}

window.goToMyLocation = function() {
  if(!navigator.geolocation) {
    showToast('Trình duyệt không hỗ trợ định vị');
    return;
  }
  if(!searchMap) return;

  const btn = document.getElementById('myLocationBtn');
  const origHtml = btn.innerHTML;
  btn.innerHTML = '<div class="spinner" style="width:12px;height:12px;border:2px solid #ccc;border-top:2px solid var(--primary);border-radius:50%;animation:spin 1s linear infinite;display:inline-block;"></div> Đang định vị...';

  navigator.geolocation.getCurrentPosition(
    function(pos) {
      const myPos = { lat: pos.coords.latitude, lng: pos.coords.longitude };
      const hasAdvancedMarkers = !!(google.maps.marker && google.maps.marker.AdvancedMarkerElement);

      // Remove old my-location marker
      if(myLocationMarker) {
        if(myLocationMarker.setMap) myLocationMarker.setMap(null);
        else if(myLocationMarker.map !== undefined) myLocationMarker.map = null;
      }

      if(hasAdvancedMarkers) {
        const dotEl = document.createElement('div');
        dotEl.style.cssText = 'width:16px;height:16px;background:#4285f4;border:3px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(66,133,244,0.5);';
        myLocationMarker = new google.maps.marker.AdvancedMarkerElement({
          map: searchMap,
          position: myPos,
          content: dotEl,
          title: 'Vị trí của tôi'
        });
      } else {
        myLocationMarker = new google.maps.Marker({
          map: searchMap,
          position: myPos,
          icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 8,
            fillColor: '#4285f4',
            fillOpacity: 1,
            strokeColor: '#fff',
            strokeWeight: 3
          },
          title: 'Vị trí của tôi'
        });
      }

      searchMap.panTo(myPos);
      searchMap.setZoom(15);
      btn.innerHTML = origHtml;
    },
    function() {
      showToast('Không thể lấy vị trí. Vui lòng cho phép truy cập vị trí.');
      btn.innerHTML = origHtml;
    },
    { enableHighAccuracy: true, timeout: 10000 }
  );
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
let mybdsCurrentFilters = { property_type:'', categoryName:'', price:'', area:'', direction:'', legal:'' };

window.openFilterSheet = function(id = 'filterSheet'){
  const overlay = document.getElementById(id + 'Overlay');
  if (overlay) overlay.classList.add('open');
  const sheet = document.getElementById(id);
  if (sheet) sheet.classList.add('open');
};

window.closeAdvancedFilter = function(id = 'filterSheet'){
  const overlay = document.getElementById(id + 'Overlay');
  if (overlay) overlay.classList.remove('open');
  const sheet = document.getElementById(id);
  if (sheet) sheet.classList.remove('open');
};

window.closeFilterSheet = function(){ // Mặc định cho search cũ nếu còn gọi
  closeAdvancedFilter('filterSheet');
};

// Cho chip cũ trong file search nều chưa apply dùng chung partial (an toàn)
window.selectFilterChip = function(chip){
  selectAdvancedFilterChip(chip);
};

window.selectAdvancedFilterChip = function(chip){
  const filterGroup = chip.dataset.filter;
  const siblings = chip.parentElement.querySelectorAll('.fs-chip[data-filter="'+filterGroup+'"]');
  siblings.forEach(c => c.classList.remove('active'));
  chip.classList.add('active');
};

window.resetAdvancedFilter = function(id = 'filterSheet'){
  document.querySelectorAll('#' + id + ' .fs-chip').forEach(c => {
      c.classList.remove('active');
      if(c.dataset.value === '') c.classList.add('active');
  });
  if(id === 'mybdsAdvancedFilter') {
    mybdsCurrentFilters = { property_type:'', categoryName:'', price:'', area:'', direction:'', legal:'' };
  } else if(id === 'abdsAdvancedFilter') {
    abdsCurrentFilters = { property_type:'', categoryName:'', price:'', area:'', direction:'', legal:'' };
  } else {
    currentFilters = { property_type:'', categoryName:'', price:'', area:'', direction:'', legal:'' };
  }
};

window.resetFilterSheet = function(){ // Legacy cho search cũ
  resetAdvancedFilter('filterSheet');
};

window.applyFilterSheet = function(id = 'filterSheet'){
  let filters = { property_type:'', categoryName:'', price:'', area:'', direction:'', legal:'', location:'' };
  document.querySelectorAll('#' + id + ' .fs-chip.active').forEach(c => {
      if(c.dataset.value) {
          filters[c.dataset.filter] = c.dataset.value;
      }
  });

  if(id === 'mybdsAdvancedFilter') {
    mybdsCurrentFilters = filters;
    closeAdvancedFilter(id);
    loadMyBds(true);
  } else if(id === 'abdsAdvancedFilter') {
    abdsCurrentFilters = filters;
    closeAdvancedFilter(id);
    loadApprovalBds(true);
  } else {
    currentFilters = filters;
    clearFilters(true);
    Object.entries(currentFilters).forEach(([key, val]) => {
        if(val) {
            const labels = {
                property_type: val === '0' ? 'Bán' : 'Cho thuê',
                categoryName: val,
                price: val,
                area: val.includes('+') ? 'Trên 1000m²' : val.replace('-','–') + 'm²',
                direction: val,
                legal: val,
                location: val
            };
            addActiveFilter(labels[key] || val);
        }
    });

    syncQuickChipRows();
    closeAdvancedFilter(id);

    let q = document.getElementById('searchInput').value || '';
    doSearch(q);
  }
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
let currentDetailSlug = null;   // property slug for share links
let currentDetailAddedBy = null; // broker id who posted the property

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
  currentDetailSlug   = d.slug || null;
  currentDetailAddedBy = d.addedBy || null;

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

  // status badge (approval status)
  const sbadge = document.getElementById('detailStatusBadge');
  if(sbadge){
    if(d.status === 0){
      sbadge.textContent = 'Chờ duyệt';
      sbadge.style.background = 'rgba(234,179,8,0.15)';
      sbadge.style.color = '#b45309';
    } else if(d.status === 2){
      sbadge.textContent = 'Từ chối';
      sbadge.style.background = 'rgba(239,68,68,0.15)';
      sbadge.style.color = '#dc2626';
    } else {
      sbadge.textContent = 'Còn hàng';
      sbadge.style.background = 'rgba(245,158,11,0.15)';
      sbadge.style.color = '#b45309';
    }
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
  // Update addedBy from full data (more reliable)
  currentDetailAddedBy = d.addedBy || currentDetailAddedBy;
  _updateOwnerEditButton();

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
  // commission — only visible to sale/bds_admin/sale_admin/admin or the broker who posted the property
  if(d.commissionRate){
    const cfg = window.WEBAPP_CONFIG || {};
    const canSeeCommission = ['sale','bds_admin','sale_admin','admin'].includes(cfg.customerRole)
      || (cfg.customerId && d.addedBy && String(cfg.customerId) === String(d.addedBy));
    if(canSeeCommission){
      const el = document.getElementById('specCommission');
      if(el){
        const isRentProp = d.transactionType === 'rent' || d.property_type == 1;
        const commLabel = isRentProp ? d.commissionRate + ' tháng' : d.commissionRate + '%';
        el.textContent = commLabel + (d.commission ? ' (' + formatVND(d.commission) + ')' : '');
      }
      showHideEl('specCommissionItem',true);
    } else {
      showHideEl('specCommissionItem',false);
    }
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
      preview.dataset.mapsUrl  = mapsUrl;
      preview.dataset.lat      = d.latitude;
      preview.dataset.lng      = d.longitude;
      preview.dataset.price    = d.price    || '';
      preview.dataset.propType = d.type     || '';
      preview.dataset.addr     = addr       || '';
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
    const ownerAvatarEl = document.getElementById('ownerInitials');
    if(ownerAvatarEl){
      if(d.broker.avatar){
        ownerAvatarEl.innerHTML = '<img src="'+escAttr(d.broker.avatar)+'" alt="'+escAttr(d.broker.name||'Môi giới')+'" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
      } else {
        ownerAvatarEl.textContent = d.broker.initials||'BK';
      }
    }
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
  closeShareSheet();
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

// broker register — show phone verification notice
window.openBrokerRegisterSheet = function(){
  const el = document.getElementById('brokerRegisterOverlay');
  if(el){ el.style.display='flex'; }
};
window.closeBrokerRegisterSheet = function(){
  const el = document.getElementById('brokerRegisterOverlay');
  if(el){ el.style.display='none'; }
};

// ---- Owner edit button visibility ----
function _updateOwnerEditButton() {
  var btn = document.getElementById('ownerEditBtn');
  if(!btn) return;
  var cfg = window.WEBAPP_CONFIG || {};
  var isOwner = !!(cfg.customerId && currentDetailAddedBy && String(cfg.customerId) === String(currentDetailAddedBy));
  btn.style.display = isOwner ? '' : 'none';
}

window.editCurrentProperty = function() {
  if(!currentDetailPropId) return;
  var cfg = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes;
  var editUrl = cfg && cfg.editListingBase ? cfg.editListingBase + currentDetailPropId : '/webapp/edit-listing/' + currentDetailPropId;
  window.location.href = editUrl;
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
    svg.setAttribute('stroke', primaryColor);
    svg.setAttribute('fill', liked ? primaryColor : 'none');
  } else {
    svg.setAttribute('stroke', primaryColor);
    svg.setAttribute('fill', liked ? primaryColor : 'none');
    if(liked){ btn.classList.add('liked'); } else { btn.classList.remove('liked'); }
  }
}

// share detail — mở share sheet thống nhất
window.shareDetail = function(){
  openPropertyShareSheet(currentDetailPropId, currentDetailTitle, currentDetailSlug);
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
  
  const latStr    = el.dataset.lat;
  const lngStr    = el.dataset.lng;
  const propPrice = el.dataset.price    || '';
  const propType  = el.dataset.propType || 'BĐS';
  const propAddr  = el.dataset.addr     || '';

  // If we can't find coords, fallback to old behavior
  if (!latStr || !lngStr) {
    if(url) window.open(url, '_blank');
    return;
  }
  
  const centerLat = parseFloat(latStr);
  const centerLng = parseFloat(lngStr);

  document.getElementById('fullMapModal').style.display = 'flex';

  // Trigger resize nếu map đã init trước đó (re-open)
  if(currentFullMap){
    setTimeout(()=>{
      google.maps.event.trigger(currentFullMap, 'resize');
      currentFullMap.setCenter({lat:centerLat, lng:centerLng});
    }, 100);
  }

  if (!currentFullMap) {
    currentFullMap = new google.maps.Map(document.getElementById('fullMapCanvas'), {
      center: { lat: centerLat, lng: centerLng },
      zoom: 16,
      mapId: 'DEMO_MAP_ID',
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
    
    // Create Custom Control "BĐS Lân Cận" — icon tròn tối giản góc trên trái
    const customControlDiv = document.createElement("div");
    customControlDiv.style.margin = "10px";

    const controlButton = document.createElement("button");
    controlButton.title = "BĐS lân cận";
    controlButton.style.cssText = "background:#fff;border:none;outline:none;width:40px;height:40px;border-radius:50%;box-shadow:rgba(0,0,0,0.3) 0px 1px 4px -1px;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;";
    const _nearbyIcon = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary,#2563eb)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="5" cy="5" r="1.5" fill="var(--primary,#2563eb)" stroke="none"/><circle cx="12" cy="5" r="1.5" fill="var(--primary,#2563eb)" stroke="none"/><circle cx="19" cy="5" r="1.5" fill="var(--primary,#2563eb)" stroke="none"/><circle cx="5" cy="12" r="1.5" fill="var(--primary,#2563eb)" stroke="none"/><circle cx="12" cy="12" r="1.5" fill="var(--primary,#2563eb)" stroke="none"/><circle cx="19" cy="12" r="1.5" fill="var(--primary,#2563eb)" stroke="none"/><circle cx="5" cy="19" r="1.5" fill="var(--primary,#2563eb)" stroke="none"/><circle cx="12" cy="19" r="1.5" fill="var(--primary,#2563eb)" stroke="none"/><circle cx="19" cy="19" r="1.5" fill="var(--primary,#2563eb)" stroke="none"/></svg>`;
    const _spinnerIcon = `<div style="width:16px;height:16px;border:2px solid #e5e7eb;border-top:2px solid var(--primary,#2563eb);border-radius:50%;animation:spin 1s linear infinite;"></div>`;
    controlButton.innerHTML = _nearbyIcon;

    controlButton.addEventListener("click", () => {
      controlButton.innerHTML = _spinnerIcon;
      controlButton.style.cursor = 'default';
      fetch(`/webapp/properties/nearby?lat=${centerLat}&lng=${centerLng}&exclude_id=${currentDetailPropId}`)
        .then(r => r.json())
        .then(res => {
          if (res.success && res.data && res.data.length > 0) {
            renderNearbyProperties(res.data);
            showToast(`Đã tải ${res.data.length} BĐS lân cận`);
          } else {
            showToast('Không tìm thấy BĐS lân cận nào');
          }
          setTimeout(()=>{ controlButton.style.display = 'none'; }, 2000);
        })
        .catch(() => {
          controlButton.innerHTML = _nearbyIcon;
          controlButton.style.cursor = 'pointer';
          showToast('Có lỗi xảy ra khi tải BĐS lân cận');
        });
    });

    customControlDiv.appendChild(controlButton);
    currentFullMap.controls[google.maps.ControlPosition.TOP_LEFT].push(customControlDiv);
  } else {
    // Re-center map if map already initialized
    currentFullMap.setCenter({ lat: centerLat, lng: centerLng });
    currentFullMap.setZoom(16);
  }

  // Clear old markers
  mapMarkers.forEach(m => { m.map = null; });
  mapMarkers = [];
  bounds = new google.maps.LatLngBounds();

  // Add center marker (Current Property) — card pin với Loại BĐS + Giá
  const centerPos = { lat: centerLat, lng: centerLng };
  const centerMarkerEl = document.createElement('div');
  centerMarkerEl.innerHTML = `
    <div style="background:var(--primary,#2563eb);color:#fff;border-radius:10px 10px 10px 2px;padding:7px 12px;box-shadow:0 4px 14px rgba(37,99,235,0.45);white-space:nowrap;position:relative;cursor:pointer;user-select:none;max-width:200px;">
      ${propType ? `<div style="font-size:10px;opacity:0.85;font-weight:500;letter-spacing:.3px;margin-bottom:2px;">${propType}</div>` : ''}
      <div style="font-size:14px;font-weight:800;line-height:1.2;">${propPrice || 'BĐS đang xem'}</div>
      ${propAddr ? `<div style="font-size:10px;opacity:0.8;margin-top:3px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${propAddr}</div>` : ''}
      <div style="position:absolute;bottom:-7px;left:50%;transform:translateX(-50%);width:0;height:0;border-left:7px solid transparent;border-right:7px solid transparent;border-top:7px solid var(--primary,#2563eb);"></div>
    </div>`;
  const centerMarker = new google.maps.marker.AdvancedMarkerElement({
    position: centerPos,
    map: currentFullMap,
    content: centerMarkerEl,
    title: 'BĐS đang xem',
    zIndex: 999
  });

  const infoWindow = new google.maps.InfoWindow({
    content: `<div style="padding:4px 2px;max-width:200px;"><strong style="color:var(--primary,#2563eb);font-size:13px;">BĐS đang xem</strong><br><span style="font-size:12px;color:#374151;">${currentDetailTitle||'Đà Lạt'}</span></div>`
  });

  centerMarker.addListener("click", () => {
    infoWindow.open({ map: currentFullMap, anchor: centerMarker });
  });

  mapMarkers.push(centerMarker);
  bounds.extend(centerPos);
};

window.closeFullMap = function() {
  document.getElementById('fullMapModal').style.display = 'none';
};

function renderNearbyProperties(data) {
  const activeInfoWindow = new google.maps.InfoWindow();

  data.forEach(p => {
    const pos = { lat: p.lat, lng: p.lng };
    const nearbyMarkerEl = document.createElement('div');
    nearbyMarkerEl.style.cssText = 'width:14px;height:14px;background:#1a73e8;border:2px solid #fff;border-radius:50%;box-shadow:0 2px 4px rgba(26,115,232,0.4);';
    const marker = new google.maps.marker.AdvancedMarkerElement({
      position: pos,
      map: currentFullMap,
      content: nearbyMarkerEl,
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
      activeInfoWindow.open({ map: currentFullMap, anchor: marker });
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

// toast — showToast(msg, type?) where type: 'success' | 'error' | 'warn' | undefined (primary)
let toastTimer;
window.showToast = function(msg, type) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'toast' + (type ? ' toast-' + type : '');
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 2800);
};

// prop-cards: handled via inline onclick + data-prop attribute

// also wire send-modal close on backdrop
document.getElementById('sendModalOverlay')?.addEventListener('click',function(e){
  if(e.target===this) closeSendModal();
});

// wire share-sheet close on backdrop
document.getElementById('shareSheetOverlay')?.addEventListener('click',function(e){
  if(e.target===this) closeShareSheet();
});

// ============ GUEST DIALOG (no-account prompt) ============
window.showGuestDialog = function(){
  var el = document.getElementById('guestDialogOverlay');
  if(el) el.classList.add('open');
};

window.closeGuestDialog = function(){
  var el = document.getElementById('guestDialogOverlay');
  if(el) el.classList.remove('open');
};

window.guestShareContact = function(){
  var tg = window.Telegram && window.Telegram.WebApp;
  if(!tg) return;

  if(typeof tg.requestContact === 'function'){
    tg.requestContact(function(sent){
      if(sent){
        // Hiện loading UI trong guest dialog
        var dialog = document.querySelector('#guestDialogOverlay .guest-dialog');
        if(dialog){
          dialog.innerHTML = '<div style="text-align:center;padding:32px 16px;">'
            + '<div style="width:32px;height:32px;border:3px solid #e5e7eb;border-top-color:var(--primary-color,#2563eb);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 16px;"></div>'
            + '<p style="font-size:15px;color:#374151;margin:0;">Đang tạo tài khoản...</p>'
            + '<p style="font-size:13px;color:#9ca3af;margin:8px 0 0;">Vui lòng chờ khoảng 30 giây</p>'
            + '</div>';
        }
        // Đợi 8 giây cho bot xử lý webhook rồi submit form POST tới /webapp/auth.
        // Telegram webhook delivery có thể mất 5-20 giây → cần buffer đủ lớn.
        // Dùng form POST thay vì window.location.replace để session cookie được set
        // trong navigation response (tránh vấn đề iOS WKWebView không persist cookie XHR).
        setTimeout(function(){
          var cfg = window.WEBAPP_CONFIG || {};
          var form = document.createElement('form');
          form.method = 'POST';
          form.action = '/webapp/auth';
          form.style.display = 'none';
          [
            ['_token',   cfg.csrfToken || (document.querySelector('meta[name="csrf-token"]') || {getAttribute: function(){return '';}}).getAttribute('content')],
            ['initData', tg.initData || ''],
            ['retry',    '0'],
            ['referral_code', sessionStorage.getItem('referral_code') || ''],
          ].forEach(function(pair) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = pair[0]; inp.value = pair[1] || '';
            form.appendChild(inp);
          });
          document.body.appendChild(form);
          // Đánh dấu user vừa share SĐT để auth handler biết cần retry (chờ bot webhook xử lý)
          try { sessionStorage.setItem('_phone_shared', '1'); } catch(e) {}
          form.submit();
        }, 8000);
      }
    });
  } else {
    // Fallback: đóng webapp, user quay lại bot để share thủ công
    closeGuestDialog();
    tg.close();
  }
};

// wire guest-dialog close on backdrop
document.getElementById('guestDialogOverlay')?.addEventListener('click',function(e){
  if(e.target===this) closeGuestDialog();
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

  fetch('/webapp/referral/data', { headers: { 'Accept': 'application/json' } })
    .then(function(res) { return res.json(); })
    .then(function(data) {
      _refData = data;
      _refTreeAll = _refData.tree || [];

      // Update code
      if(codeDisplay) {
        if(codeSkeleton) codeSkeleton.style.display = 'none';
        codeDisplay.textContent = _refData.referral_code || '';
      }

      // Generate QR code
      var qrContainer = document.getElementById('refQrCode');
      var qrSkeleton = document.getElementById('refQrSkeleton');
      if(qrSkeleton) qrSkeleton.remove();
      if(qrContainer && _refData.share_url) {
        if(typeof qrcode === 'function') {
          try {
            var qr = qrcode(0, 'M');
            qr.addData(_refData.share_url);
            qr.make();
            // Build SVG manually — no canvas, no table, works in Telegram WebView
            var n = qr.getModuleCount();
            var cell = Math.floor(140 / n);
            var sz = n * cell;
            var rects = '';
            for(var ri = 0; ri < n; ri++) {
              for(var ci = 0; ci < n; ci++) {
                if(qr.isDark(ri, ci)) {
                  rects += '<rect x="' + (ci * cell) + '" y="' + (ri * cell) + '" width="' + cell + '" height="' + cell + '"/>';
                }
              }
            }
            var svgHtml = '<svg xmlns="http://www.w3.org/2000/svg" width="' + sz + '" height="' + sz + '" style="display:block;border-radius:4px;">'
              + '<rect width="' + sz + '" height="' + sz + '" fill="#fff"/>'
              + '<g fill="#000">' + rects + '</g>'
              + '</svg>';
            qrContainer.innerHTML = svgHtml;
          } catch(e) {
            qrContainer.innerHTML = '<div style="width:140px;height:140px;display:flex;align-items:center;justify-content:center;font-size:11px;color:#666;text-align:center;padding:8px;">QR không khả dụng</div>';
          }
        } else {
          qrContainer.innerHTML = '<div style="width:140px;height:140px;display:flex;align-items:center;justify-content:center;font-size:11px;color:#666;text-align:center;padding:8px;">QR không khả dụng</div>';
        }
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

      // Hiện section nhập mã nếu user chưa có người giới thiệu
      var claimSection = document.getElementById('refClaimSection');
      if(claimSection) claimSection.style.display = _refData.has_referrer ? 'none' : '';

      // Render tree
      renderReferralTree();

      // Render history
      renderReferralHistory(_refData.history || []);
    })
    .catch(function(err) {
      console.error('Referral data error:', err);
      var qrSkeleton = document.getElementById('refQrSkeleton');
      if(qrSkeleton) qrSkeleton.remove();
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

window.claimReferralCode = function(){
  var input = document.getElementById('refClaimInput');
  var btn   = document.getElementById('refClaimBtn');
  var msg   = document.getElementById('refClaimMsg');
  var code  = (input ? input.value.trim().toUpperCase() : '');
  if(!code) { showToast('Vui lòng nhập mã giới thiệu'); return; }

  if(btn) { btn.disabled = true; btn.textContent = '...'; }
  if(msg) msg.style.display = 'none';

  var csrf = (window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken)
    || document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  fetch('/webapp/referral/claim', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    body: JSON.stringify({ referral_code: code }),
  })
  .then(function(r){ return r.json(); })
  .then(function(data){
    if(btn) { btn.disabled = false; btn.textContent = 'Xác nhận'; }
    if(data.success) {
      if(msg) {
        msg.style.display = '';
        msg.style.color = 'var(--success)';
        msg.textContent = '✓ ' + (data.message || 'Thành công!');
      }
      // Ẩn section sau 2 giây rồi reload dữ liệu
      setTimeout(function(){
        var sec = document.getElementById('refClaimSection');
        if(sec) sec.style.display = 'none';
        loadReferralData();
      }, 2000);
    } else {
      if(msg) {
        msg.style.display = '';
        msg.style.color = 'var(--danger)';
        msg.textContent = '✗ ' + (data.message || 'Có lỗi xảy ra');
      }
    }
  })
  .catch(function(){
    if(btn) { btn.disabled = false; btn.textContent = 'Xác nhận'; }
    if(msg) { msg.style.display = ''; msg.style.color = 'var(--danger)'; msg.textContent = '✗ Lỗi kết nối, thử lại.'; }
  });
};

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

  var tg = window.Telegram && window.Telegram.WebApp;

  if(platform === 'telegram') {
    try {
      if(tg && tg.openTelegramLink && tgUrl) {
        tg.openTelegramLink(tgUrl);
      } else if(tgUrl) {
        window.open(tgUrl, '_blank');
      }
    } catch(e) {}
    showToast('Đang mở Telegram để chia sẻ...');
  } else if(platform === 'zalo') {
    var zaloUrl = 'https://zalo.me/s/share?url=' + encodeURIComponent(link)
      + '&text=' + encodeURIComponent('Tham gia Đà Lạt BĐS với mã giới thiệu ' + code + '. Đăng ký ngay!');
    try {
      if(tg && tg.openLink) {
        tg.openLink(zaloUrl);
      } else {
        window.open(zaloUrl, '_blank');
      }
    } catch(e) {}
    showToast('Đang mở Zalo để chia sẻ...');
  } else {
    if(navigator.clipboard) navigator.clipboard.writeText(link).catch(function(){});
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
    slug: p.slug || null,
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

function refreshHomeFeed() {
  const feed = document.getElementById('prop-feed');
  const sentinel = document.getElementById('feed-sentinel');
  if (!feed || !sentinel) return;
  feed.innerHTML = '';
  sentinel.dataset.page = '1';
  sentinel.dataset.hasMore = 'true';
  sentinel.dataset.loading = 'false';
  const spinner = document.getElementById('feed-spinner');
  if (spinner) spinner.style.display = '';
  loadHomeFeedPage();
}

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

  // Append advanced filters
  if (typeof mybdsCurrentFilters !== 'undefined') {
    Object.entries(mybdsCurrentFilters).forEach(([key, val]) => {
      if (val) params.append(key, val);
    });
  }

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
  const isRejected = p.status === 2 && p.rejection_reason;
  const statusInfo = p.status === 1
    ? { cls: 'status-active',    label: '● Đang hiển thị' }
    : p.status === 0
      ? { cls: 'status-pending', label: '⏳ Chờ duyệt' }
      : isRejected
        ? { cls: 'status-rejected', label: '✕ Bị từ chối' }
        : { cls: 'status-hidden',   label: '⊘ Đã ẩn' };

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

  // Rejected notice banner
  const rejectedBanner = isRejected
    ? `<div style="padding:10px 13px;background:#fef2f2;border-top:1px solid #fecaca;">
        <div style="font-size:11px;font-weight:700;color:var(--danger);">❌ Lý do từ chối: ${escHtml(p.rejection_reason)}</div>
        ${p.rejection_note ? `<div style="font-size:10px;color:#b91c1c;margin-top:3px;">${escHtml(p.rejection_note)}</div>` : ''}
        <div style="font-size:10px;color:#b91c1c;margin-top:4px;">💡 Vui lòng chỉnh sửa và gửi lại để được duyệt.</div>
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
  } else if (isRejected) {
    // Rejected by admin: edit + resubmit + delete
    quickBtns = `
      <div class="mybds-qbtn" onclick="window.location.href='${escHtml(editUrl)}'" title="Chỉnh sửa">${svgEdit}</div>
      <button style="padding:5px 12px;background:var(--primary);color:#fff;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;" onclick="mybdsResubmit(${p.id})">↩ Gửi lại</button>
      <div class="mybds-qbtn danger" onclick="mybdsDelete(${p.id})" title="Xóa">${svgTrash}</div>`;
  } else {
    // Hidden by broker: show again, delete
    quickBtns = `
      <button style="padding:5px 12px;background:var(--primary);color:#fff;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;" onclick="mybdsToggleStatus(${p.id}, 2)">Hiển thị lại</button>
      <div class="mybds-qbtn danger" onclick="mybdsDelete(${p.id})" title="Xóa">${svgTrash}</div>`;
  }

  // Footer analytics
  const footerLabel = p.status === 1
    ? `Đăng ${escHtml(p.created_at)}`
    : p.status === 0
      ? `Gửi ${escHtml(p.created_at)}`
      : isRejected
        ? `Bị từ chối: ${escHtml(p.created_at)}`
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
    ${rejectedBanner}
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
        refreshHomeFeed();
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
        refreshHomeFeed();
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

window.mybdsResubmit = function(id) {
  if (!confirm('Xác nhận gửi lại tin đăng này để Admin xem xét duyệt?')) return;
  var csrf = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.csrfToken;
  if (!csrf) { showToast('Lỗi cấu hình'); return; }
  fetch('/webapp/listings/' + id + '/resubmit', {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-Requested-With': 'XMLHttpRequest',
    },
  })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.success) {
        showToast('↩ Đã gửi lại — Admin sẽ xem xét trong 24h');
        mybdsLoaded = false;
        loadMyBds(true);
      } else {
        showToast(data.message || 'Có lỗi xảy ra');
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

var usersCurrentTab = 'brokers';
var usersCurrentSearch = '';
var usersSearchTimer = null;

/**
 * Debounce search + chuyển đổi SĐT "0xxx" → "84xxx" trước khi search
 * để khớp với format lưu trong DB.
 */
window.usersSearchDebounce = function() {
  clearTimeout(usersSearchTimer);
  usersSearchTimer = setTimeout(function() {
    var raw = (document.getElementById('usersSearchInput').value || '').trim();
    // Nếu chuỗi bắt đầu bằng "0" và toàn số → chuyển thành "84..."
    if (/^0\d+$/.test(raw)) {
      usersCurrentSearch = '84' + raw.slice(1);
    } else {
      usersCurrentSearch = raw;
    }
    loadUsers(true);
  }, 400);
};

window.switchUsersTab = function(tab, btn) {
  usersCurrentTab = tab;
  document.querySelectorAll('#usersTabBar .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  if (btn) btn.classList.add('active');
  // Cập nhật style badge đếm cho tab active/inactive
  document.querySelectorAll('#usersTabBar .sp-tab').forEach(function(t) {
    var badge = t.querySelector('span');
    if (!badge) return;
    if (t.classList.contains('active')) {
      badge.style.background = 'var(--primary)';
      badge.style.color = '#fff';
    } else {
      badge.style.background = 'var(--bg-secondary)';
      badge.style.color = 'var(--text-secondary)';
    }
  });
  loadUsers(true);
};

window.loadUsers = function(reset) {
  var url = window.WEBAPP_CONFIG.routes.adminUsersJson
    + '?tab=' + encodeURIComponent(usersCurrentTab)
    + '&search=' + encodeURIComponent(usersCurrentSearch);

  var container = document.getElementById('usersListContainer');
  if (reset) {
    container.innerHTML = '<div style="padding:12px 16px;">'
      + '<div style="height:64px;background:var(--bg-secondary);border-radius:10px;margin-bottom:8px;animation:pulse 1.5s infinite;"></div>'
      + '<div style="height:64px;background:var(--bg-secondary);border-radius:10px;margin-bottom:8px;animation:pulse 1.5s infinite;"></div>'
      + '<div style="height:64px;background:var(--bg-secondary);border-radius:10px;animation:pulse 1.5s infinite;"></div>'
      + '</div>';
  }

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      // Update tab count badges (3 tabs: brokers, sales, management)
      var s = data.stats || {};

      // Hidden stat holders
      var brokersEl = document.getElementById('usersBrokersCount');
      var salesEl   = document.getElementById('usersSalesCount');
      var adminEl   = document.getElementById('usersAdminCount');
      if (brokersEl) brokersEl.textContent = s.brokers || 0;
      if (salesEl)   salesEl.textContent   = s.sales   || 0;
      if (adminEl)   adminEl.textContent   = s.management || 0;

      // Badge in tab bar
      document.querySelectorAll('.users-tab-count-brokers').forEach(function(el) { el.textContent = s.brokers || 0; });
      document.querySelectorAll('.users-tab-count-sales').forEach(function(el)   { el.textContent = s.sales   || 0; });
      document.querySelectorAll('.users-tab-count-management').forEach(function(el) { el.textContent = s.management || 0; });

      // Cache users
      var users = data.users || [];
      window._usersCache = {};
      users.forEach(function(u) { window._usersCache[u.id] = u; });

      if (users.length === 0) {
        container.innerHTML = '<div style="text-align:center;padding:48px 16px;color:var(--text-tertiary);">'
          + '<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity:.25;margin-bottom:12px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
          + '<div style="font-size:13px;color:var(--text-tertiary);">Không có người dùng nào</div></div>';
        return;
      }

      var tab = usersCurrentTab;
      var tabLabels = { brokers: 'Brokers & Khách hàng', sales: 'Đội ngũ Sales', management: 'Quản trị hệ thống' };
      var html = '<div style="display:flex;align-items:center;justify-content:space-between;padding:10px 16px 6px;">'
        + '<span style="font-size:11px;font-weight:600;letter-spacing:.5px;color:var(--text-tertiary);text-transform:uppercase;">'
        + escHtml(tabLabels[tab] || tab) + '</span>'
        + '<span style="font-size:11px;color:var(--text-tertiary);">' + users.length + ' người</span>'
        + '</div>';

      users.forEach(function(u) {
        html += renderUserCard(u, tab);
      });

      container.innerHTML = html;
    })
    .catch(function() {
      container.innerHTML = '<div style="text-align:center;padding:32px;color:var(--danger);">Lỗi tải dữ liệu. <button onclick="loadUsers(true)" style="color:var(--primary);text-decoration:underline;background:none;border:none;cursor:pointer;">Thử lại</button></div>';
    });
};

/**
 * renderUserCard — Flat minimalist list item.
 * 3 tabs: brokers | sales | management
 */
function renderUserCard(u, tab) {
  var initials = u.initials || (u.name || '??').slice(0, 2).toUpperCase();
  var isLocked = (u.isActive === 0);

  // Avatar hình + status dot
  var avatarImgSrc = u.profile ? ('/images/users/' + u.profile) : (u.avatar || u.avatar_url || '/images/favicon.ico');
  var dotColor = isLocked ? 'background:#d1d5db;border:2px solid var(--bg-primary);' : 'background:var(--primary);border:2px solid var(--bg-primary);';
  var avatar = '<div style="position:relative;flex-shrink:0;">'
    + '<img src="' + escAttr(avatarImgSrc) + '" style="width:42px;height:42px;border-radius:50%;object-fit:cover;display:block;filter:' + (isLocked ? 'grayscale(100%) opacity(60%)' : 'none') + '" onerror="this.src=\'/images/favicon.ico\'">'
    + '<div style="position:absolute;bottom:0;right:0;width:10px;height:10px;border-radius:50%;' + dotColor + ';"></div>'
    + '</div>';

  // SĐT hiển thị
  var phoneDisplay = u.mobile || u.email || '—';

  // Role badge — hiện ở tất cả các tab
  var _roleMap = {
    'admin':      { label: 'Admin',      style: 'background:rgba(50,112,252,.1);color:var(--primary);border:1px solid rgba(50,112,252,.25);' },
    'bds_admin':  { label: 'BĐS Admin',  style: 'background:rgba(50,112,252,.1);color:var(--primary);border:1px solid rgba(50,112,252,.25);' },
    'sale_admin': { label: 'Sale Admin', style: 'background:rgba(50,112,252,.1);color:var(--primary);border:1px solid rgba(50,112,252,.25);' },
    'sale':       { label: 'Sale',       style: 'background:rgba(50,112,252,.1);color:var(--primary);border:1px solid rgba(50,112,252,.25);' },
    'broker':     { label: 'Broker',     style: 'background:rgba(50,112,252,.1);color:var(--primary);border:1px solid rgba(50,112,252,.25);' },
    'customer':   { label: 'Customer',   style: 'background:rgba(50,112,252,.1);color:var(--primary);border:1px solid rgba(50,112,252,.25);' },
  };
  var _rc = _roleMap[u.role] || (u.role ? { label: u.role.charAt(0).toUpperCase() + u.role.slice(1), style: 'background:rgba(50,112,252,.1);color:var(--primary);border:1px solid rgba(50,112,252,.25);' } : null);
  var roleBadge = _rc
    ? '<span style="font-size:10px;font-weight:600;padding:2px 7px;border-radius:6px;' + _rc.style + 'white-space:nowrap;flex-shrink:0;margin-right:4px;">' + escHtml(_rc.label) + '</span>'
    : '';

  // Three-dot button (flat icon)
  var dotsBtn = '<button onclick="openUserActionSheet(' + u.id + ',\'' + tab + '\')"'
    + ' style="width:36px;height:36px;border:none;background:none;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:50%;color:var(--text-tertiary);flex-shrink:0;"'
    + ' aria-label="Tuỳ chọn">'
    + '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'
    + '<circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>'
    + '</svg>'
    + '</button>';

  return '<div id="uc-' + u.id + '" style="display:flex;align-items:center;gap:12px;padding:10px 16px;border-bottom:1px solid var(--border);">'
    + avatar
    + '<div style="flex:1;min-width:0;">'
    + '<div style="font-size:14px;font-weight:600;color:' + (isLocked ? 'var(--text-tertiary)' : 'var(--text-primary)') + ';white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'
    + escHtml(u.name || '—') + '</div>'
    + '<div style="font-size:12px;color:var(--text-secondary);margin-top:1px;display:flex;align-items:center;gap:4px;">'
    + '<span>' + escHtml(phoneDisplay) + '</span>'
    + '</div>'
    + '</div>'
    + roleBadge
    + dotsBtn
    + '</div>';
}

function escHtml(str) {
  if (!str) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

window.openUserActionSheet = function(id, tab) {
  var u = window._usersCache && window._usersCache[id];
  if (!u) return;

  var avatarEl = document.getElementById('userActionSheetAvatar');
  var dotEl    = document.getElementById('userActionSheetStatusDot');
  var nameEl   = document.getElementById('userActionSheetName');
  var metaEl   = document.getElementById('userActionSheetMeta');
  var optsEl   = document.getElementById('userActionSheetOptions');

  var isLocked = (u.isActive === 0);

  if (avatarEl) {
    var avatarImgSrc = u.profile ? ('/images/users/' + u.profile) : (u.avatar || u.avatar_url || '/images/favicon.ico');
    avatarEl.innerHTML = '<img src="' + escAttr(avatarImgSrc) + '" style="width:100%;height:100%;border-radius:50%;object-fit:cover;display:block;filter:' + (isLocked ? 'grayscale(100%) opacity(60%)' : 'none') + '" onerror="this.src=\'/images/favicon.ico\'">';
    avatarEl.style.background = 'transparent';
    avatarEl.style.padding = '0';
  }
  if (dotEl) { dotEl.style.background = isLocked ? '#d1d5db' : 'var(--primary)'; }
  if (nameEl) nameEl.textContent = u.name || '—';
  if (metaEl) metaEl.textContent = (u.mobile || u.email || '') + (isLocked ? ' · Đang bị khoá' : '');

  var iconEdit   = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>';
  var iconHome   = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>';
  var iconLock   = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>';
  var iconUnlock = '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>';

  var lockLabel = isLocked ? 'Mở khoá tài khoản' : 'Khoá tài khoản';
  var lockSub   = isLocked ? 'Khôi phục quyền truy cập' : 'Tạm thời vô hiệu hoá';

  var actions = [
    { icon: iconEdit, label: 'Chỉnh sửa thông tin', sub: 'Cập nhật hồ sơ người dùng',
      fn: 'openEditUserSheet(' + id + ')', danger: false },
    { icon: iconHome, label: 'Xem BĐS đã đăng',     sub: (u.property_count || 0) + ' BĐS',
      fn: 'viewUserBds(' + id + ',\'' + escHtml(u.name || '') + '\')', danger: false },
    { icon: isLocked ? iconUnlock : iconLock, label: lockLabel, sub: lockSub,
      fn: 'toggleUserLock(' + id + ',\'' + escHtml(u.name || '') + '\',' + u.isActive + ')', danger: !isLocked },
  ];

  if (optsEl) {
    optsEl.innerHTML = actions.map(function(a) {
      var lc = a.danger ? 'color:var(--danger);' : 'color:var(--text-primary);';
      var ib = a.danger ? 'background:rgba(239,68,68,.08);' : 'background:var(--bg-secondary);';
      return '<button onclick="closeUserActionSheet();' + a.fn + '"'
        + ' style="width:100%;display:flex;align-items:center;gap:14px;padding:13px 18px;background:none;border:none;border-bottom:1px solid var(--border);cursor:pointer;text-align:left;">'
        + '<span style="width:34px;height:34px;border-radius:9px;' + ib + 'display:flex;align-items:center;justify-content:center;flex-shrink:0;">' + a.icon + '</span>'
        + '<span style="flex:1;">'
        + '<span style="display:block;font-size:14px;font-weight:500;' + lc + '">' + escHtml(a.label) + '</span>'
        + '<span style="display:block;font-size:12px;color:var(--text-tertiary);margin-top:1px;">' + escHtml(a.sub) + '</span>'
        + '</span>'
        + '</button>';
    }).join('');
  }

  document.getElementById('userActionSheet').style.display = 'flex';
};

window.closeUserActionSheet = function() {
  var sheet = document.getElementById('userActionSheet');
  if (sheet) sheet.style.display = 'none';
};

/* Xem BĐS đã đăng của 1 user — mở subpage approvebds với filter added_by */
/* Xem BĐS đã đăng của 1 user — mở subpage userbds */
window.viewUserBds = function(userId, userName) {
  window._userBdsUserId        = userId;
  window._userBdsCurrentTab    = 'all';
  window._userBdsCurrentSearch = '';
  window._userBdsCurrentSort   = 'latest';

  // Cập nhật tiêu đề
  var titleEl = document.getElementById('userBdsTitle');
  if (titleEl) titleEl.textContent = 'BĐS của ' + (userName || 'người dùng');

  // Reset search input
  var searchEl = document.getElementById('userBdsSearchInput');
  if (searchEl) searchEl.value = '';

  // Reset tabs — chọn "Tất cả"
  document.querySelectorAll('#userBdsTabs .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  var allTab = document.getElementById('userBdsTabAll');
  if (allTab) allTab.classList.add('active');

  // Mở subpage
  var sp = document.getElementById('subpage-userbds');
  if (sp) {
    sp.classList.add('open');
    var nav = document.querySelector('.bottom-nav');
    if (nav) nav.style.transform = 'translateY(100%)';
  }

  loadUserBds(true);
};

var _userBdsSearchTimer = null;

window.loadUserBds = function(reset) {
  var userId = window._userBdsUserId;
  if (!userId) return;

  var loadingEl = document.getElementById('userBdsLoading');
  var emptyEl   = document.getElementById('userBdsEmpty');
  var listEl    = document.getElementById('userBdsList');
  if (!loadingEl || !emptyEl || !listEl) return;

  if (reset) {
    loadingEl.style.display = '';
    emptyEl.style.display   = 'none';
    listEl.style.display    = 'none';
  }

  var url = '/webapp/api/admin/users/' + userId + '/properties'
    + '?status='  + encodeURIComponent(window._userBdsCurrentTab    || 'all')
    + '&search=' + encodeURIComponent(window._userBdsCurrentSearch || '')
    + '&sort='   + encodeURIComponent(window._userBdsCurrentSort   || 'latest');

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      loadingEl.style.display = 'none';
      if (!data.success) { emptyEl.style.display = ''; return; }

      var counts = data.counts || {};

      // Update stat strip
      setElText('userBdsCountActive',  counts.active  != null ? counts.active  : 0);
      setElText('userBdsCountPending', counts.pending != null ? counts.pending : 0);
      setElText('userBdsCountHidden',  counts.hidden  != null ? counts.hidden  : 0);
      setElText('userBdsTotalViews',   (counts.total_views || 0).toLocaleString('vi-VN'));

      // Update tab labels
      setElText('userBdsTabAll',     'Tất cả ('     + (counts.all     || 0) + ')');
      setElText('userBdsTabActive',  'Hiển thị ('   + (counts.active  || 0) + ')');
      setElText('userBdsTabPending', 'Chờ duyệt ('  + (counts.pending || 0) + ')');
      setElText('userBdsTabHidden',  'Đã ẩn ('      + (counts.hidden  || 0) + ')');

      var props = data.properties || [];
      if (!props.length) { emptyEl.style.display = ''; return; }

      var maxViews = Math.max(1, Math.max.apply(null, props.map(function(p) { return p.total_click || 0; })));
      var maxFav   = Math.max(1, Math.max.apply(null, props.map(function(p) { return p.favourite_count || 0; })));

      listEl.innerHTML = props.map(function(p) {
        return userBdsBuildCard(p, maxViews, maxFav);
      }).join('');
      listEl.style.display = '';
    })
    .catch(function() {
      loadingEl.style.display = 'none';
      emptyEl.style.display   = '';
    });
};

function userBdsBuildCard(p, maxViews, maxFav) {
  var statusInfo = p.status === 1
    ? { cls: 'status-active',   label: '● Đang hiển thị' }
    : p.status === 0
      ? { cls: 'status-pending', label: '⏳ Chờ duyệt' }
      : { cls: 'status-hidden',  label: '⊘ Đã ẩn' };

  var imgStyle = p.title_image
    ? 'background:url(\'' + escHtml(p.title_image) + '\') center/cover no-repeat;'
    : 'background:linear-gradient(135deg,#1e3a5f,#0d1f3c);display:flex;align-items:center;justify-content:center;';

  var imgPlaceholder = p.title_image
    ? ''
    : '<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg>';

  var statChips = p.status === 1
    ? '<div class="mybds-img-stats">'
      + '<div class="mybds-stat-chip"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> ' + (p.total_click || 0) + '</span></div>'
      + '<div class="mybds-stat-chip"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> ' + (p.favourite_count || 0) + '</span></div>'
      + '</div>'
    : '';

  var metaItems = [
    p.area          ? '<div class="mybds-meta-item"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>' + escHtml(p.area) + ' m²</div>' : '',
    '<div class="mybds-meta-item"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>' + (p.property_type === 0 ? 'Mua' : 'Thuê') + '</div>',
  ].filter(Boolean).join('');

  var viewPct = Math.round((p.total_click / maxViews) * 100);
  var favPct  = Math.round((p.favourite_count / maxFav) * 100);
  var perfBars = p.status === 1
    ? '<div class="perf-row">'
      + '<span class="perf-label">Lượt xem</span>'
      + '<div class="perf-bar-bg"><div class="perf-bar-fill" style="width:' + viewPct + '%;"></div></div>'
      + '<span class="perf-val">' + p.total_click + '</span>'
      + '</div>'
      + '<div class="perf-row">'
      + '<span class="perf-label">Quan tâm</span>'
      + '<div class="perf-bar-bg"><div class="perf-bar-fill" style="width:' + favPct + '%;background:var(--danger);"></div></div>'
      + '<span class="perf-val">' + p.favourite_count + '</span>'
      + '</div>'
    : '';

  var footerLabel = p.status === 1
    ? 'Đăng ' + escHtml(p.created_at)
    : p.status === 0
      ? 'Gửi ' + escHtml(p.created_at)
      : 'Ẩn từ ' + escHtml(p.created_at);

  var cardOpacity = p.status === 2 ? 'opacity:0.75;' : '';

  return '<div class="mybds-card" style="' + cardOpacity + '">'
    + '<div class="mybds-img" style="' + imgStyle + '">'
    + imgPlaceholder
    + '<div class="mybds-img-overlay"></div>'
    + '<div class="mybds-img-status"><span class="status-pill ' + statusInfo.cls + '">' + statusInfo.label + '</span></div>'
    + '<div class="mybds-img-price">' + escHtml(p.price) + '</div>'
    + statChips
    + '</div>'
    + '<div class="mybds-body">'
    + '<div class="mybds-title">' + escHtml(p.title) + '</div>'
    + '<div class="mybds-addr"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + escHtml(p.address_location || '') + '</div>'
    + '<div class="mybds-meta">' + metaItems + '</div>'
    + '</div>'
    + perfBars
    + '<div class="mybds-footer">'
    + '<div class="mybds-analytics"><div class="mybds-analytic"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:3px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>' + footerLabel + '</div></div>'
    + '<div class="mybds-quick"></div>'
    + '</div>'
    + '</div>';
}

window.userBdsTabSwitch = function(btn, tab) {
  document.querySelectorAll('#userBdsTabs .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  btn.classList.add('active');
  window._userBdsCurrentTab = tab;
  loadUserBds(true);
};

window.userBdsOnSearchInput = function(val) {
  clearTimeout(_userBdsSearchTimer);
  _userBdsSearchTimer = setTimeout(function() {
    window._userBdsCurrentSearch = val.trim();
    loadUserBds(true);
  }, 400);
};

window.userBdsToggleSortSheet = function() {
  var sheet = document.getElementById('userBdsSortSheet');
  if (sheet) sheet.style.display = 'flex';
};

window.userBdsCloseSortSheet = function() {
  var sheet = document.getElementById('userBdsSortSheet');
  if (sheet) sheet.style.display = 'none';
};

window.userBdsSortSelect = function(sort) {
  window._userBdsCurrentSort = sort;
  // Update checkmark labels
  var sortMap = { latest: 'userBdsSortLatest', oldest: 'userBdsSortOldest', views: 'userBdsSortViews', price_asc: 'userBdsSortPriceAsc', price_desc: 'userBdsSortPriceDesc' };
  var labels  = { latest: 'Mới nhất', oldest: 'Cũ nhất', views: 'Lượt xem nhiều nhất', price_asc: 'Giá tăng dần', price_desc: 'Giá giảm dần' };
  Object.keys(sortMap).forEach(function(key) {
    var el = document.getElementById(sortMap[key]);
    if (el) el.textContent = (key === sort ? '✓ ' : '  ') + labels[key];
  });
  userBdsCloseSortSheet();
  loadUserBds(true);
};

/* Mở form chỉnh sửa thông tin người dùng — bind dữ liệu từ _usersCache */
window.openEditUserSheet = function(id) {
  var u = window._usersCache && window._usersCache[id];
  if (!u) return;

  // Điền dữ liệu vào form
  document.getElementById('editUserId').value    = u.id;
  document.getElementById('editUserName').value  = u.name || '';
  document.getElementById('editUserMobile').value = u.mobile || '';
  document.getElementById('editUserEmail').value = u.email || '';

  // Set role select — map 'customer'/null → 'broker'
  var roleEl = document.getElementById('editUserRole');
  var roleVal = u.role || 'broker';
  if (roleVal === 'customer' || !roleVal) roleVal = 'broker';
  roleEl.value = roleVal;

  // Hiển thị bottom sheet
  document.getElementById('editUserSheet').style.display = 'flex';
};

window.closeEditUserSheet = function() {
  var sheet = document.getElementById('editUserSheet');
  if (sheet) sheet.style.display = 'none';
};

/* Submit form chỉnh sửa thông tin — gọi PATCH /webapp/api/admin/users/{id} */
window.submitEditUserForm = function() {
  var id     = document.getElementById('editUserId').value;
  var name   = (document.getElementById('editUserName').value || '').trim();
  var mobile = (document.getElementById('editUserMobile').value || '').trim();
  var email  = (document.getElementById('editUserEmail').value || '').trim();
  var role   = document.getElementById('editUserRole').value;

  if (!name) { showToast('Vui lòng nhập họ tên'); return; }

  var btn = document.getElementById('editUserSubmitBtn');
  if (btn) { btn.disabled = true; btn.textContent = 'Đang lưu...'; }

  var url = window.WEBAPP_CONFIG.routes.adminUsersBase + id;
  fetch(url, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': window.WEBAPP_CONFIG.csrfToken,
    },
    body: JSON.stringify({ name: name, mobile: mobile, email: email, role: role }),
  })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (btn) { btn.disabled = false; btn.textContent = 'Lưu thay đổi'; }
      if (data.success) {
        // Cập nhật cache local để action sheet header hiển thị đúng ngay lập tức
        if (window._usersCache && window._usersCache[id]) {
          window._usersCache[id].name   = data.user.name;
          window._usersCache[id].mobile = data.user.mobile;
          window._usersCache[id].email  = data.user.email;
          window._usersCache[id].role   = data.user.role;
        }
        closeEditUserSheet();
        showToast('Đã cập nhật thông tin thành công');
        loadUsers(true); // Reload danh sách để hiển thị thay đổi
      } else {
        showToast(data.message || 'Có lỗi xảy ra');
      }
    })
    .catch(function() {
      if (btn) { btn.disabled = false; btn.textContent = 'Lưu thay đổi'; }
      showToast('Lỗi kết nối');
    });
};

/* Đổi role qua prompt inline — được gọi từ Bottom Sheet */
window.openChangeRoleSheet = function(id) {
  var u = window._usersCache && window._usersCache[id];
  if (!u) return;
  var validRoles = ['broker', 'sale', 'sale_admin', 'bds_admin', 'admin'];
  var targetRole = prompt(
    'Đổi vai trò cho "' + (u.name || '') + '"\n' +
    'Nhập role mới: broker | sale | sale_admin | bds_admin | admin',
    u.role || 'broker'
  );
  if (!targetRole) return;
  var clean = (targetRole || '').trim().toLowerCase();
  if (!validRoles.includes(clean)) { showToast('Role không hợp lệ!'); return; }
  if (!confirm('Đổi role "' + (u.name || '') + '" thành "' + clean + '"?')) return;
  userAction('/role', id, { role: clean }, 'Đã cập nhật role thành công!', 'PATCH');
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
  activity: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>',
  gift: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>',
  star: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>'
};

var _NP_TYPE_SUBPAGE = {
  lead_assigned:'leads', lead_followup:'leads', lead_created:'leads',
  booking_reminder:'bookings', booking_result:'bookings', booking_changed:'bookings',
  property_submitted:'mybds', property_approved:'mybds', property_rejected:'mybds', property_pending:'approvebds',
  commission_status:'commissions', commission_completed:'commissions',
  deal_created:'deals', deal_stuck:'deals', deal_status:'deals',
  referral_new_signup:'referral'
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
      var dataStr = n.data ? encodeURIComponent(JSON.stringify(n.data)) : '';
      html += '<div class="np-item' + unreadClass + '" onclick="notifPanelClick(' + n.id + ',\'' + _esc(n.type) + '\', \'' + dataStr + '\')">';
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

window.notifPanelClick = function(id, type, dataStr) {
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

  var nData = {};
  if (dataStr) {
    try { nData = JSON.parse(decodeURIComponent(dataStr)); } catch(e) {}
  }

  // 1. Link to property detail
  if ((type === 'property_approved' || type === 'property_submitted') && nData.property_id) {
    if (typeof window.openDetail === 'function') {
      window.openDetail({ id: nData.property_id });
      return;
    }
  } 
  // 2. Link to approvebds with query parameters
  else if (type === 'property_pending' && nData.property_id) {
    var refVal = nData.slug || nData.property_id;
    window.history.pushState(null, '', '?ref_slug=' + refVal + '&tab=pending');
    if (typeof openSubpage === 'function') openSubpage('approvebds');
    return;
  }

  var subpage = _NP_TYPE_SUBPAGE[type];
  if (subpage && typeof openSubpage === 'function') {
    openSubpage(subpage);
  } else {
    goTo('activity');
  }
};

// Helper: sync bottom-nav badge + dot indicator (mutually exclusive)
function syncNavBadge(count) {
  var navBadge = document.getElementById('notif-badge');
  var navDot   = document.getElementById('notif-dot');
  if (navBadge) {
    navBadge.textContent = count > 0 ? (count > 99 ? '99+' : count) : '';
    navBadge.style.display = count > 0 ? 'flex' : 'none';
  }
  if (navDot) {
    navDot.style.display = count > 0 ? 'none' : '';
  }
}

// Sync header badge with the unread count (called from activityApp.updateBadge)
var _origUpdateBadge;
document.addEventListener('webapp:badge-update', function(e) {
  var count = e.detail && e.detail.count ? e.detail.count : 0;
  var badge = document.getElementById('header-notif-badge');
  if (badge) {
    badge.textContent = count > 99 ? '99+' : (count > 0 ? count : '');
    badge.style.display = count > 0 ? '' : 'none';
  }
  syncNavBadge(count);
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
      syncNavBadge(count);
    }
  })
  .catch(function() {});
})();

}); // end DOMContentLoaded

// ============ REAL-TIME NOTIFICATIONS VIA WEBSOCKET ============
(function initRealtimeNotifications() {
  var cfg = window.WEBAPP_CONFIG || {};
  if (!cfg.customerId || !window.Echo) return;

  window.Echo.private('customer.' + cfg.customerId)
    .listen('.notification.new', function(data) {
      // Update badge counts immediately
      var count = data.unread_count || 0;
      document.dispatchEvent(new CustomEvent('webapp:badge-update', {
        detail: { count: count }
      }));

      // Dispatch for activityApp to prepend the new notification
      document.dispatchEvent(new CustomEvent('webapp:new-notification', {
        detail: { notification: data.notification }
      }));
    });
})();

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
    'check-circle': '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
    gift: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>',
    star: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>'
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
    property_submitted: [
      { label: 'Xem tin đăng', primary: true, icon: 'eye', action: 'view_property' }
    ],
    property_approved: [
      { label: 'Xem tin đăng', primary: true, icon: 'eye', action: 'view_property' }
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
    ],
    referral_new_signup: [
      { label: 'Xem mạng lưới', primary: true, icon: 'gift', subpage: 'referral' }
    ]
  };

  // Navigation mapping: type → subpage to open on tap
  var TYPE_SUBPAGE = {
    lead_assigned: 'leads', lead_followup: 'leads', lead_created: 'leads',
    booking_reminder: 'bookings', booking_result: 'bookings', booking_changed: 'bookings',
    property_submitted: 'mybds', property_approved: 'mybds', property_rejected: 'mybds', property_pending: 'approvebds',
    commission_status: 'commissions', commission_completed: 'commissions',
    deal_created: 'deals', deal_stuck: 'deals', deal_status: 'deals',
    referral_new_signup: 'referral'
  };

  // Tab definitions: key → { label, categories[] (null = no filter) }
  // categories: null = all, array = whereIn filter sent as ?categories=a,b or ?category=a
  var TAB_DEFS = {
    all:         { label: 'Tất cả',    categories: null },
    property:    { label: 'BĐS',       categories: ['property'] },
    referral:    { label: 'Khách',     categories: ['referral', 'system'] },
    transaction: { label: 'Giao dịch', categories: ['lead', 'deal', 'commission', 'booking'] },
    approvebds:  { label: 'BĐS Duyệt', categories: ['admin'] }
  };

  // Role → ordered list of tab keys
  var ROLE_TABS = {
    guest:      ['all'],
    broker:     ['all', 'property', 'referral'],
    sale:       ['all', 'transaction'],
    sale_admin: ['all', 'transaction'],
    bds_admin:  ['all', 'property', 'approvebds'],
    admin:      ['all', 'property', 'transaction', 'approvebds', 'referral']
  };

  return {
    tabs: [],
    activeTab: 'all',
    notifications: [],
    loading: false,
    markingAll: false,
    currentPage: 1,
    lastPage: 1,

    get hasMore() { return this.currentPage < this.lastPage; },
    get hasUnread() {
      for (var i = 0; i < this.notifications.length; i++) {
        if (this.notifications[i].is_unread) return true;
      }
      return false;
    },

    init: function() {
      var self = this;
      var cfg = window.WEBAPP_CONFIG || {};
      var role = cfg.customerRole || 'guest';

      // Build tab list for this role
      var keys = ROLE_TABS[role] || ROLE_TABS['broker'];
      self.tabs = keys.map(function(k) { return { key: k, label: TAB_DEFS[k].label }; });

      self.fetchNotifications();
      self.updateBadge();

      // Refresh when switching to Hoạt động page
      window.addEventListener('webapp:page-changed', function(e) {
        if (e.detail && e.detail.page === 'activity') {
          self.currentPage = 1;
          self.notifications = [];
          self.fetchNotifications();
          self.updateBadge();
        }
      });

      // Listen for real-time new notifications via WebSocket
      document.addEventListener('webapp:new-notification', function(e) {
        var notif = e.detail && e.detail.notification;
        if (!notif) return;

        // type_config is already included from server payload
        if (!notif.type_config) {
          notif.type_config = { icon_bg: 'var(--primary-light)', icon: 'bell' };
        }

        // Check if notification belongs to the current tab filter
        var tabDef = TAB_DEFS[self.activeTab];
        var shouldShow = !tabDef.categories || tabDef.categories.indexOf(notif.category) !== -1;

        if (shouldShow) {
          // Check if this notification already exists (updated in-place)
          var existingIndex = -1;
          for (var i = 0; i < self.notifications.length; i++) {
            if (self.notifications[i].id === notif.id) { existingIndex = i; break; }
          }
          if (existingIndex >= 0) {
            // Replace in-place (notification was updated, not new)
            self.notifications.splice(existingIndex, 1, notif);
          } else {
            self.notifications.unshift(notif);
          }
        }
      });
    },

    switchTab: function(tab) {
      this.activeTab = tab;
      this.currentPage = 1;
      this.notifications = [];
      this.fetchNotifications();
    },

    // Build query-string category params for the active tab
    _categoryParams: function() {
      var tabDef = TAB_DEFS[this.activeTab];
      if (!tabDef || !tabDef.categories) return '';
      if (tabDef.categories.length === 1) return '&category=' + tabDef.categories[0];
      return '&categories=' + tabDef.categories.join(',');
    },

    fetchNotifications: function() {
      var self = this;
      self.loading = true;
      var cfg = window.WEBAPP_CONFIG || {};
      var url = (cfg.routes && cfg.routes.notificationsJson) || '/webapp/api/notifications';
      var params = 'page=' + self.currentPage + self._categoryParams();

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

      // Consistent behavior with dropdown for activity page clicks
      if ((notif.type === 'property_approved' || notif.type === 'property_submitted') && notif.data && notif.data.property_id) {
        if (typeof window.openDetail === 'function') {
          window.openDetail({ id: notif.data.property_id });
          return;
        }
      } else if (notif.type === 'property_pending' && notif.data && notif.data.property_id) {
        var refVal = notif.data.slug || notif.data.property_id;
        window.history.pushState(null, '', '?ref_slug=' + refVal + '&tab=pending');
        if (typeof openSubpage === 'function') openSubpage('approvebds');
        return;
      }

      var subpage = TYPE_SUBPAGE[notif.type];
      if (subpage && typeof openSubpage === 'function') {
        openSubpage(subpage);
      }
    },

    markAllRead: function() {
      var self = this;
      if (self.markingAll || !self.hasUnread) return;
      self.markingAll = true;
      var cfg = window.WEBAPP_CONFIG || {};
      var url = (cfg.routes && cfg.routes.notificationsReadAll) || '/webapp/api/notifications/read-all';

      // Pass categories of current tab so only visible items are marked
      var body = {};
      var tabDef = TAB_DEFS[self.activeTab];
      if (tabDef && tabDef.categories && tabDef.categories.length === 1) {
        body.category = tabDef.categories[0];
      }

      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': cfg.csrfToken || '',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(body)
      })
      .then(function(r) { return r.json(); })
      .then(function(json) {
        if (json.success) {
          // Mark all currently loaded items as read
          for (var i = 0; i < self.notifications.length; i++) {
            self.notifications[i].is_unread = false;
          }
          self.updateBadge();
        }
        self.markingAll = false;
      })
      .catch(function() { self.markingAll = false; });
    },

    getActions: function(notif) {
      // If a property_pending notification was already handled by another admin, hide action buttons
      if (notif.type === 'property_pending' && notif.data && notif.data.handled_by_id) {
        return [];
      }
      var actions = TYPE_ACTIONS[notif.type] || [];
      var result = [];
      for (var i = 0; i < actions.length; i++) {
        var a = actions[i];

        // Safe check for property link
        if (a.action === 'open_url' && (!notif.data || !notif.data.property_url)) {
          continue; // Ẩn nút xem tin nếu không có URL hợp lệ
        }
        if (a.action === 'view_property' && (!notif.data || !notif.data.property_id)) {
          continue; // Ẩn nút nếu không có ID
        }

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

    getHandledByLabel: function(notif) {
      if (notif.type !== 'property_pending' || !notif.data) return '';
      var d = notif.data;
      if (!d.handled_by_id) return '';
      return 'Đã xử lý bởi admin khác';
    },

    handleAction: function(action, notif) {
      if (action._action === 'view_property') {
        var propId = notif.data && notif.data.property_id;
        if (propId) {
          openDetail({ id: propId });
        } else {
          if (typeof openSubpage === 'function') openSubpage('mybds');
        }
        return;
      }
      if (action._action === 'open_url') {
        var url = notif.data && notif.data.property_url;
        if (url) {
          window.location.href = url;
        } else {
          if (typeof openSubpage === 'function') openSubpage('mybds');
        }
        return;
      }
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
          document.dispatchEvent(new CustomEvent('webapp:badge-update', { detail: { count: json.count || 0 } }));
        }
      })
      .catch(function() {});
    }
  };
};

// ============ TELEGRAM DEEP LINK (startapp) HANDLING ============
(function handleDeepLink() {
  var tg = window.Telegram && window.Telegram.WebApp;
  if (!tg || !tg.initDataUnsafe || !tg.initDataUnsafe.start_param) return;
  var param = tg.initDataUnsafe.start_param;

  if (param.indexOf('property_') === 0) {
    var propId = parseInt(param.substring(9));
    if (propId) {
      setTimeout(function() { openDetail({ id: propId }); }, 500);
    }
  } else if (param.indexOf('ref_') === 0) {
    var refCode = param.substring(4);
    if (refCode) {
      sessionStorage.setItem('referral_code', refCode);
    }
  }
})();

// ============================================================
// MARKET PRICES ADMIN
// ============================================================

var mpGroups = [];
var mpCurrentGroupLabel = null;

window.loadMarketPrices = function(reset) {
  var url = window.WEBAPP_CONFIG && window.WEBAPP_CONFIG.routes && window.WEBAPP_CONFIG.routes.adminMarketPricesJson;
  if (!url) return;
  if (reset) {
    mpGroups = [];
    mpCurrentGroupLabel = null;
    var container = document.getElementById('mpListContainer');
    if (container) {
      container.innerHTML = '<div id="mpSkeletonLoader" style="padding:16px;">' +
        '<div style="height:70px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;animation:pulse 1.5s infinite;"></div>' +
        '<div style="height:70px;background:var(--bg-secondary);border-radius:12px;margin-bottom:10px;animation:pulse 1.5s infinite;animation-delay:.15s;"></div>' +
        '<div style="height:70px;background:var(--bg-secondary);border-radius:12px;animation:pulse 1.5s infinite;animation-delay:.3s;"></div>' +
        '</div>';
    }
    document.getElementById('mpTabBar').innerHTML = '';
  }
  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      // Update hero stats
      var stats = data.stats || {};
      var heroEl = document.getElementById('mpHeroMain');
      if (heroEl) heroEl.textContent = (stats.area_count || 0) + ' khu vực';
      var areaEl = document.getElementById('mpAreaCount');
      if (areaEl) areaEl.textContent = stats.area_count || '0';
      var monthEl = document.getElementById('mpCurrentMonth');
      if (monthEl) monthEl.textContent = stats.current_month || '—';
      var avgEl = document.getElementById('mpAvgPrice');
      if (avgEl) avgEl.textContent = stats.avg_price || '—';
      var recEl = document.getElementById('mpRecordCount');
      if (recEl) recEl.textContent = stats.record_count || '0';

      mpGroups = data.groups || [];

      // Render tabs
      var tabBar = document.getElementById('mpTabBar');
      if (tabBar) {
        if (mpGroups.length === 0) {
          tabBar.innerHTML = '';
        } else {
          tabBar.innerHTML = mpGroups.map(function(g, i) {
            return '<button class="sp-tab' + (i === 0 ? ' active' : '') + '" onclick="mpSwitchTab(\'' + g.label + '\',this)">' + g.label + '</button>';
          }).join('');
        }
      }

      // Render first group
      if (mpGroups.length > 0) {
        mpCurrentGroupLabel = mpGroups[0].label;
        mpRenderGroup(mpGroups[0]);
      } else {
        mpCurrentGroupLabel = null;
        var container = document.getElementById('mpListContainer');
        if (container) {
          container.innerHTML = '<div style="padding:40px 20px;text-align:center;color:var(--text-tertiary);">' +
            '<div style="font-size:32px;margin-bottom:12px;">📊</div>' +
            '<div style="font-size:14px;font-weight:600;margin-bottom:6px;">Chưa có dữ liệu</div>' +
            '<div style="font-size:12px;">Nhấn "Thêm khu vực mới" để bắt đầu nhập giá thị trường.</div>' +
            '</div>';
        }
      }
    })
    .catch(function() {
      var container = document.getElementById('mpListContainer');
      if (container) {
        container.innerHTML = '<div style="padding:30px 20px;text-align:center;color:var(--text-tertiary);">' +
          '<div style="font-size:13px;">Lỗi kết nối. <a href="#" onclick="loadMarketPrices(true);return false;" style="color:var(--primary);">Thử lại</a></div>' +
          '</div>';
      }
    });
};

window.mpSwitchTab = function(label, btn) {
  document.querySelectorAll('#mpTabBar .sp-tab').forEach(function(t) { t.classList.remove('active'); });
  if (btn) btn.classList.add('active');
  mpCurrentGroupLabel = label;
  var group = mpGroups.find(function(g) { return g.label === label; });
  if (group) mpRenderGroup(group);
};

function mpRenderGroup(group) {
  var container = document.getElementById('mpListContainer');
  if (!container) return;
  if (!group || !group.items || group.items.length === 0) {
    container.innerHTML = '<div style="padding:30px 20px;text-align:center;color:var(--text-tertiary);font-size:13px;">Không có dữ liệu cho ' + (group ? group.label : 'tháng này') + '.</div>';
    return;
  }
  var html = '<div style="padding:12px 14px;display:flex;flex-direction:column;gap:10px;">';
  group.items.forEach(function(mp) {
    var trendHtml = '';
    if (mp.trend_dir === 'up') {
      trendHtml = '<span style="color:#16a34a;font-size:11px;font-weight:600;">↑ ' + mp.trend_pct + '%</span>';
    } else if (mp.trend_dir === 'dn') {
      trendHtml = '<span style="color:#dc2626;font-size:11px;font-weight:600;">↓ ' + mp.trend_pct + '%</span>';
    } else {
      trendHtml = '<span style="color:var(--text-tertiary);font-size:11px;">—</span>';
    }
    html += '<div style="background:var(--bg-primary);border-radius:12px;padding:12px 14px;box-shadow:0 1px 4px rgba(0,0,0,.06);display:flex;align-items:center;gap:10px;">' +
      '<div style="flex:1;min-width:0;">' +
        '<div style="font-size:14px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + mp.area_name + '</div>' +
        '<div style="font-size:12px;color:var(--text-secondary);margin-top:2px;">' + mp.formatted_price + '/m² &nbsp;' + trendHtml + '</div>' +
      '</div>' +
      '<div style="display:flex;gap:6px;flex-shrink:0;">' +
        '<button onclick="mpOpenEditForm(' + mp.id + ',\'' + mp.area_name.replace(/'/g, "\\'") + '\',' + mp.avg_price_m2 + ',' + (mp.prev_price_m2 || 0) + ',' + mp.month + ',' + mp.year + ')" ' +
          'style="padding:6px 10px;background:var(--primary-light);color:var(--primary);border:none;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;">Sửa</button>' +
        '<button onclick="mpOpenDelete(' + mp.id + ',\'' + mp.area_name.replace(/'/g, "\\'") + '\',' + mp.month + ',' + mp.year + ')" ' +
          'style="padding:6px 10px;background:#fee2e2;color:#ef4444;border:none;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;">Xoá</button>' +
      '</div>' +
    '</div>';
  });
  html += '</div>';
  container.innerHTML = html;
}

window.mpOpenAddForm = function() {
  document.getElementById('mpFormId').value = '';
  document.getElementById('mpFormTitle').textContent = 'Thêm khu vực mới';
  document.getElementById('mpFormAreaName').value = '';
  document.getElementById('mpFormAvgPrice').value = '';
  document.getElementById('mpFormPrevPrice').value = '';
  var now = new Date();
  document.getElementById('mpFormMonth').value = now.getMonth() + 1;
  document.getElementById('mpFormYear').value = now.getFullYear();
  document.getElementById('mpFormSheet').style.display = 'flex';
};

window.mpOpenEditForm = function(id, areaName, avgPrice, prevPrice, month, year) {
  document.getElementById('mpFormId').value = id;
  document.getElementById('mpFormTitle').textContent = 'Sửa: ' + areaName;
  document.getElementById('mpFormAreaName').value = areaName;
  document.getElementById('mpFormAvgPrice').value = avgPrice;
  document.getElementById('mpFormPrevPrice').value = prevPrice || '';
  document.getElementById('mpFormMonth').value = month;
  document.getElementById('mpFormYear').value = year;
  document.getElementById('mpFormSheet').style.display = 'flex';
};

window.mpCloseForm = function() {
  document.getElementById('mpFormSheet').style.display = 'none';
};

window.mpSubmitForm = function() {
  var id = document.getElementById('mpFormId').value;
  var areaName = document.getElementById('mpFormAreaName').value.trim();
  var avgPrice = document.getElementById('mpFormAvgPrice').value;
  var prevPrice = document.getElementById('mpFormPrevPrice').value;
  var month = document.getElementById('mpFormMonth').value;
  var year = document.getElementById('mpFormYear').value;

  if (!areaName || !avgPrice || !month || !year) {
    if (typeof showToast === 'function') showToast('Vui lòng điền đầy đủ các trường bắt buộc.');
    return;
  }

  var base = window.WEBAPP_CONFIG.routes.adminMarketPricesBase;
  var url = id ? (base + id) : base.slice(0, -1);
  var method = id ? 'PUT' : 'POST';

  var btn = document.getElementById('mpFormSubmitBtn');
  if (btn) { btn.disabled = true; btn.textContent = 'Đang lưu...'; }

  fetch(url, {
    method: method,
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': window.WEBAPP_CONFIG.csrfToken,
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({
      area_name:     areaName,
      avg_price_m2:  parseFloat(avgPrice),
      prev_price_m2: prevPrice ? parseFloat(prevPrice) : null,
      month:         parseInt(month),
      year:          parseInt(year),
    }),
  })
  .then(function(r) {
    if (r.status === 422) return r.json().then(function(d) { throw { userMsg: d.message || 'Dữ liệu không hợp lệ.' }; });
    if (!r.ok) throw new Error('server');
    return r.json();
  })
  .then(function() {
    mpCloseForm();
    if (typeof showToast === 'function') showToast('✓ Đã lưu thành công!');
    loadMarketPrices(true);
  })
  .catch(function(err) {
    var msg = (err && err.userMsg) ? err.userMsg : 'Lỗi kết nối. Vui lòng thử lại.';
    if (typeof showToast === 'function') showToast(msg);
  })
  .finally(function() {
    if (btn) { btn.disabled = false; btn.textContent = 'Lưu'; }
  });
};

window.mpOpenDelete = function(id, areaName, month, year) {
  document.getElementById('mpDeleteId').value = id;
  var txt = document.getElementById('mpDeleteConfirmText');
  if (txt) txt.innerHTML = 'Bạn chắc chắn muốn xoá dữ liệu giá của<br><strong style="color:#ef4444;">' + areaName + '</strong><br>tháng ' + month + '/' + year + '?';
  document.getElementById('mpDeleteSheet').style.display = 'flex';
};

window.mpCloseDelete = function() {
  document.getElementById('mpDeleteSheet').style.display = 'none';
};

window.mpConfirmDelete = function() {
  var id = document.getElementById('mpDeleteId').value;
  if (!id) return;
  var url = window.WEBAPP_CONFIG.routes.adminMarketPricesBase + id;
  fetch(url, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': window.WEBAPP_CONFIG.csrfToken,
      'X-Requested-With': 'XMLHttpRequest',
    },
  })
  .then(function(r) {
    if (!r.ok) throw new Error('server');
    return r.json();
  })
  .then(function() {
    mpCloseDelete();
    if (typeof showToast === 'function') showToast('✓ Đã xoá thành công!');
    loadMarketPrices(true);
  })
  .catch(function() {
    if (typeof showToast === 'function') showToast('Lỗi kết nối. Vui lòng thử lại.');
  });
};
