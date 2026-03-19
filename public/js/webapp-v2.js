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
  if(id === 'likedbds') loadLikedBds();
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
  const allToggles = document.querySelectorAll('#subpage-notifset .ios-toggle:not(#master-toggle)');
  allToggles.forEach(t => { t.disabled = !cb.checked; t.style.opacity = cb.checked ? '1' : '.4'; });
};
window.toggleQuiet = function(cb){
  const qh = document.getElementById('quiet-hours');
  if(qh) qh.style.display = cb.checked ? 'flex' : 'none';
};
window.toggleChBadge = function(el){
  el.classList.toggle('active');
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
let currentRejectId = null;

window.openRejectSheet = function(bdsId){
  currentRejectId = bdsId;
  document.querySelectorAll('.rs-reason').forEach(r=>r.classList.remove('selected'));
  document.getElementById('rejectSheet').classList.add('open');
};
window.selectRejectReason = function(el){
  document.querySelectorAll('.rs-reason').forEach(r=>r.classList.remove('selected'));
  el.classList.add('selected');
};
window.submitReject = function(){
  const selected = document.querySelector('.rs-reason.selected');
  if(!selected){ showToast('Vui lòng chọn lý do từ chối'); return; }
  document.getElementById('rejectSheet').classList.remove('open');
  if(currentRejectId){
    const card = document.getElementById(currentRejectId);
    if(card) card.style.display='none';
  }
  showToast('✕ Đã gửi yêu cầu bổ sung → Broker');
};
window.approveAbds = function(id, name){
  const card = document.getElementById(id);
  if(card) card.style.display='none';
  showToast('✓ Đã duyệt: ' + name);
};
document.getElementById('rejectSheet')?.addEventListener('click', function(e){
  if(e.target===this) this.classList.remove('open');
});

// ============ ADMIN — DUYỆT HOA HỒNG ============
window.approveComm = function(id, name, amount){
  const card = document.getElementById(id);
  if(card) card.style.display='none';
  showToast('✓ Đã duyệt HH ' + amount + ' — ' + name + ' → Chờ cọc');
};

// ============ ADMIN — REPORT ============
window.switchRpTab = function(btn){
  btn.closest('.report-period').querySelectorAll('.rp-tab').forEach(t=>t.classList.remove('active'));
  btn.classList.add('active');
};

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
  const picker = document.getElementById('salePicker');
  picker.classList.remove('open');
  // remove assigned cards from list
  currentPickLeads.forEach(id=>{
    const card = document.getElementById(id);
    if(card) card.style.display = 'none';
    selectedLeads.delete(id);
  });
  updateAssignCta();
  // update count badge
  const remain = document.querySelectorAll('.ul-card:not([style*="display: none"])').length;
  document.getElementById('unassignedCount').textContent = remain + ' lead';
  const saleNames = {HT:'Huy Thái',MK:'Minh Khoa',AL:'Anh Linh',TN:'Thu Nga',DH:'Đức Huy'};
  showToast('✓ Đã assign ' + currentPickLeads.length + ' lead cho ' + (saleNames[selectedSale]||selectedSale));
};

// Close sale picker on backdrop
document.getElementById('salePicker')?.addEventListener('click', function(e){
  if(e.target === this) this.classList.remove('open');
});

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
      if(txt.includes('tỷ')) price = txt;
      else if(['Đất ở', 'Nhà phố', 'Biệt thự', 'Căn hộ', 'Khách sạn'].includes(txt)) categoryName = txt;
  });
  
  // If chipEl was passed (quick filter chip)
  if(chipEl && chipEl.classList.contains('chip')) {
      let txt = chipEl.textContent.trim();
      if(txt !== 'Tất cả') {
          addActiveFilter(txt);
          if(txt.includes('tỷ')) price = txt;
          else if(['Đất ở', 'Nhà phố', 'Biệt thự', 'Căn hộ', 'Khách sạn'].includes(txt)) categoryName = txt;
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

  // Build URL with all params
  let url = '/webapp/search/results?page=' + page;
  if(query && query !== 'Tất cả') url += '&q=' + encodeURIComponent(query);
  if(price) url += '&price=' + encodeURIComponent(price);
  if(categoryName) url += '&categoryName=' + encodeURIComponent(categoryName);
  
  // Advanced filter params (from filter sheet)
  if(currentSort && currentSort !== 'latest') url += '&sort=' + encodeURIComponent(currentSort);
  if(currentFilters.property_type) url += '&type=' + encodeURIComponent(currentFilters.property_type);
  if(currentFilters.area) url += '&area_range=' + encodeURIComponent(currentFilters.area);
  if(currentFilters.direction) url += '&direction=' + encodeURIComponent(currentFilters.direction);
  if(currentFilters.legal) url += '&legal=' + encodeURIComponent(currentFilters.legal);
  // Override price/categoryName from filter sheet if set
  if(currentFilters.price && !price) url += '&price=' + encodeURIComponent(currentFilters.price);
  if(currentFilters.categoryName && !categoryName) url += '&categoryName=' + encodeURIComponent(currentFilters.categoryName);
  
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
                <button class="rc-btn" style="background:${isLiked?'var(--danger-light)':'var(--bg-secondary)'};color:${isLiked?'var(--danger)':'var(--text-tertiary)'};" onclick="event.stopPropagation();toggleBookmark(this,${p.id})"><span style="display:inline-flex;align-items:center;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="${isLiked?'currentColor':'none'}" stroke="currentColor" stroke-width="1.7"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span></button>
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
    const statusColors = { 'new':'#3b82f6', 'contacted':'#f59e0b', 'qualified':'#10b981', 'won':'#059669', 'lost':'#ef4444' };
    
    res.leads.forEach(lead => {
        const rawStatus = lead.status || 'new';
        const statusColor = statusColors[rawStatus] || '#6b7280';
        
        html += `
        <div class="crm-card" onclick="window.location.href='/webapp/leads/${lead.id}'" style="cursor:pointer;">
            <div class="crm-card-header">
                <div class="crm-card-name">${lead.customer_name}</div>
                <span class="badge" style="background:${statusColor}15;color:${statusColor};font-size:10px;padding:3px 8px;border-radius:20px;font-weight:600;">${lead.status_label}</span>
            </div>
            <div class="crm-card-body">
                ${lead.customer_phone ? '<div class="crm-row"><span class="crm-label">SĐT</span><span class="crm-value">' + lead.customer_phone + '</span></div>' : ''}
                <div class="crm-row"><span class="crm-label">Nhu cầu</span><span class="crm-value">${lead.lead_type}</span></div>
                ${lead.budget_min || lead.budget_max ? '<div class="crm-row"><span class="crm-label">Ngân sách</span><span class="crm-value">' + (lead.budget_min||'?') + ' - ' + (lead.budget_max||'?') + '</span></div>' : ''}
                ${lead.note ? '<div class="crm-row"><span class="crm-label">Ghi chú</span><span class="crm-value" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px;">' + lead.note + '</span></div>' : ''}
            </div>
            <div class="crm-card-footer">
                <span class="crm-date">${lead.created_at_diff}</span>
                ${lead.sale_name ? '<span style="font-size:10px;color:var(--primary);">👤 ' + lead.sale_name + '</span>' : ''}
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
  
  // Update filter count badge
  let count = Object.values(currentFilters).filter(v => v).length;
  const fc = document.getElementById('filterCount');
  if(count > 0) { fc.textContent = count; fc.style.display = 'flex'; }
  else { fc.style.display = 'none'; }
  
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
    
    let html = '';
    list.forEach(q => {
        html += '<div class="recent-item" onclick="doSearch(\'' + q.replace(/'/g,"\\'") + '\')"><span class="ri"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span><span style="flex:1">' + q + '</span><span style="color:var(--text-tertiary);font-size:13px;">↗</span></div>';
    });
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
  span.closest('.af-chip').remove();
  let searchInput = document.getElementById('searchInput');
  doSearch(searchInput.value || '');
};

window.clearFilters = function(silent = false){
  document.getElementById('activeFilters').querySelectorAll('.af-chip').forEach(c=>c.remove());
  if(silent !== true) {
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
  if(isDetailHeader){
    svg.setAttribute('stroke', liked ? 'var(--primary)' : 'currentColor');
    svg.setAttribute('fill', liked ? 'var(--primary)' : 'none');
  } else {
    svg.setAttribute('fill', liked ? 'var(--primary)' : 'none');
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
window.copyRefCode = function(){
  const code = document.getElementById('refCodeDisplay').textContent;
  if(navigator.clipboard){
    navigator.clipboard.writeText(code);
  }
  showToast('📋 Đã sao chép mã: '+code);
};

window.shareRefLink = function(platform){
  const code = document.getElementById('refCodeDisplay').textContent;
  const link = 'https://dalatbds.vn/ref/'+code;
  const msg = 'Tham gia Đà Lạt BĐS với mã giới thiệu '+code+'. Đăng ký tại: '+link;
  if(platform==='telegram'){
    showToast('✈️ Đang mở Telegram để chia sẻ...');
  } else if(platform==='zalo'){
    showToast('💬 Đang mở Zalo để chia sẻ...');
  } else {
    if(navigator.clipboard) navigator.clipboard.writeText(link);
    showToast('🔗 Đã sao chép link: '+link);
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

// Initialize liked count
(function(){
  const countEl = document.getElementById('likedBdsCount');
  if(countEl && window.likedIds){
    countEl.textContent = window.likedIds.size + ' BĐS đã lưu';
  }
})();

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

}); // end DOMContentLoaded
