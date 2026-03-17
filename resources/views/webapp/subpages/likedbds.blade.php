  <!-- ========== SUBPAGE: BĐS ĐÃ THÍCH ========== -->
  <div class="subpage" id="subpage-likedbds">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('likedbds')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="var(--primary)" stroke="var(--primary)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> BĐS đã thích</span></div>
      <div class="sp-actions"></div>
    </div>

    <div class="sp-scroll" id="likedbdsScroll">
      <!-- loaded by JS -->
      <div id="likedbdsLoading" style="padding:40px 16px;text-align:center;color:var(--text-tertiary);font-size:13px;">
        <div style="width:28px;height:28px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
        Đang tải...
      </div>
      <div id="likedbdsEmpty" style="display:none;padding:48px 24px;text-align:center;">
        <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        <div style="font-size:14px;font-weight:600;color:var(--text-secondary);margin-bottom:6px;">Chưa có BĐS yêu thích</div>
        <div style="font-size:12px;color:var(--text-tertiary);">Nhấn icon ❤️ trên thẻ BĐS để lưu vào đây</div>
      </div>
      <div id="likedbdsList" style="display:none;"></div>
      <div style="height:16px;"></div>
    </div>
  </div>
