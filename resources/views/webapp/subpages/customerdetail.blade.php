<!-- ========== SUBPAGE: CHI TIẾT KHÁCH HÀNG ========== -->
<div class="subpage" id="subpage-customerdetail">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('customerdetail')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title">Chi tiết khách hàng</div>
    <div class="sp-actions"></div>
  </div>

  <div class="sp-scroll">
    <!-- Loading -->
    <div id="custDetailLoading" style="padding:48px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">
      <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
      Đang tải...
    </div>

    <!-- Content (rendered by JS) -->
    <div id="custDetailContent" style="display:none;"></div>
  </div>
</div>
