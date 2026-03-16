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
    const isActive = b.textContent.trim().replace(/^[^\s]+\s*/,'') === match ||
                     b.textContent.trim().includes(match);
    b.classList.toggle('active', isActive);
    b.style.background = isActive ? '#3270FC' : 'rgba(255,255,255,0.12)';
    b.style.color = isActive ? '#fff' : 'rgba(255,255,255,0.6)';
  });
};

// init role from server config
setRole(currentRole, document.querySelector('.rbtn.active'));

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
  if(val.length === 0){
    setState('discovery');
    return;
  }
  setState('suggestions');
  searchTypingTimer = setTimeout(()=>{
    // auto-show results after 800ms of no typing (simulate search)
  }, 800);
};

window.showSuggestions = function(){
  const input = document.getElementById('searchInput');
  if(input.value.length > 0) setState('suggestions');
};

function setState(state){
  searchState = state;
  document.getElementById('stateDiscovery').style.display = state==='discovery'?'block':'none';
  document.getElementById('stateSuggestions').style.display = state==='suggestions'?'block':'none';
  document.getElementById('stateResults').style.display = state==='results'?'block':'none';
}

window.doSearch = function(query, chipEl){
  // Update search bar
  const placeholder = document.getElementById('searchPlaceholder');
  const input = document.getElementById('searchInput');
  const box = document.querySelector('.search-box-main');
  box.classList.add('focused');
  placeholder.textContent = query;
  placeholder.style.display = 'block';
  input.style.display = 'none';
  input.value = query;

  // Update result header
  document.getElementById('resultQuery').textContent = query;
  const counts = {
    'Tất cả':128,'Đất ở':45,'Nhà phố':32,'Biệt thự':18,
    'Căn hộ':12,'Khách sạn':8,'Dưới 1 tỷ':24,'1–3 tỷ':38,
    '3–5 tỷ':29,'Trên 5 tỷ':15,'Đường Yersin, Cam Ly':8,
    'Biệt thự Lâm Viên':6,'Đất mặt tiền 3/4':11,'Nhà phố dưới 2 tỷ':28,
    'P.Cam Ly':34,'P.Lâm Viên':21,'Đường 3/4':18,'Hồ Xuân Hương':12,
  };
  const n = counts[query] || Math.floor(Math.random()*80+20);
  document.getElementById('resultCount').textContent = n + ' kết quả';

  // Update filter count badge
  const fc = document.getElementById('filterCount');
  fc.style.display = 'flex';

  setState('results');
  // Reset to list view
  switchView('list');
  document.getElementById('scrollArea').scrollTop = 0;
};

window.switchView = function(view){
  document.getElementById('listView').style.display = view==='list'?'block':'none';
  document.getElementById('mapView').style.display = view==='map'?'block':'none';
  document.getElementById('viewList').classList.toggle('active', view==='list');
  document.getElementById('viewMap').classList.toggle('active', view==='map');
};

window.switchMode = function(mode, btn){
  btn.closest('.search-mode-tabs').querySelectorAll('.smt').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
};

window.openFilterSheet = function(){
  // reuse existing sheet mechanism
  openSheet();
};

window.openSortSheet = function(){
  // future: open sort options
};

window.removeFilter = function(span){
  span.closest('.af-chip').remove();
};

window.clearFilters = function(){
  document.getElementById('activeFilters').querySelectorAll('.af-chip').forEach(c=>c.remove());
};

window.loadMore = function(btn){
  btn.textContent = 'Đang tải...';
  setTimeout(()=>{ btn.textContent = 'Đã tải hết kết quả'; btn.disabled=true; }, 1000);
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
let currentDetailPhone = null; // host phone for callOwner

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
    if(preview) preview.dataset.mapsUrl = mapsUrl;

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

window.openDetail = function(data){
  const d = data || {};

  // 1. Show basic data immediately
  populateBasic(d);

  // 2. Reset UI state
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
  const scrolled = this.scrollTop>180;
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

// bookmark
window.toggleBookmark = function(btn){
  bookmarked=!bookmarked;
  btn.querySelector('span').textContent=bookmarked?'❤️':'🤍';
  showToast(bookmarked?'Đã lưu quan tâm':'Đã bỏ lưu');
};

// share
window.shareDetail = function(){ showToast('Link đã sao chép!'); };

// open Google Maps for current property
window.openGoogleMaps = function(){
  const el = document.getElementById('detailMapPreview');
  const url = el && el.dataset.mapsUrl;
  if(url) window.open(url, '_blank');
};

// call owner
window.callOwner = function(){
  if(currentDetailPhone){
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
  const imgHtml = p.title_image
    ? `<img src="${p.title_image}" class="prop-img-inner" style="object-fit:cover;width:100%;height:100%;" alt="">`
    : `<div class="img-prop1 prop-img-inner"><div class="img-center">🏠</div></div>`;

  const categoryBadge = p.category_name
    ? `<span class="badge badge-blue">${p.category_name}</span>` : '';

  const areaMeta = p.area ? `<div class="prop-meta-item">📐 <span>${p.area} m²</span></div>` : '';
  const legalMeta = p.legal ? `<div class="prop-meta-item">⚖️ <span>${p.legal}</span></div>` : '';
  const roomMeta = p.number_room
    ? `<div class="prop-meta-item role-broker role-bds_admin role-sale role-sale_admin role-admin">🛏 <span>${p.number_room} PN</span></div>` : '';

  const propJson = JSON.stringify({
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

  return `
    <div class="prop-card" data-prop='${propJson}' onclick="if(!event.target.closest('.prop-quick-btn,.prop-action-btn'))openDetail(JSON.parse(this.dataset.prop))">
      <div class="prop-img">
        ${imgHtml}
        <div class="prop-img-gradient"></div>
        <div class="prop-img-tags">${categoryBadge}</div>
        <div class="prop-img-price">${p.price || ''}</div>
        <div class="prop-actions">
          <div class="prop-action-btn">❤️</div>
          <div class="prop-action-btn">↗️</div>
        </div>
      </div>
      <div class="prop-body">
        <div class="prop-title">${p.title || ''}</div>
        <div class="prop-location">📍 ${p.location || ''}</div>
        <div class="prop-meta">${areaMeta}${legalMeta}${roomMeta}</div>
      </div>
      <div class="prop-footer">
        <div class="prop-views">👁 ${p.total_click || 0} lượt xem</div>
        <div class="prop-quick-actions">
          <div class="prop-quick-btn role-broker role-bds_admin role-sale role-sale_admin role-admin">✏️</div>
          <div class="prop-quick-btn role-sale role-bds_admin role-sale_admin role-admin" style="background:var(--primary-light);border-color:transparent;">🤝</div>
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
