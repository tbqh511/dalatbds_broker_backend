<!-- ========== SUBPAGE: KPI & TEAM ========== -->
<div class="subpage" id="subpage-kpiteam">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('kpiteam')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title">
      <span style="display:inline-flex;align-items:center;gap:5px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        KPI & Team Sale
      </span>
    </div>
    <div class="sp-actions">
      <button class="sp-action-btn" onclick="loadKpiTeamData(true)" title="Làm mới">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
      </button>
    </div>
  </div>

  <!-- Loading state -->
  <div id="kpiTeamLoading" style="padding:64px 16px;text-align:center;">
    <div style="width:36px;height:36px;border:3px solid var(--border-light);border-top-color:var(--primary);border-radius:50%;animation:spin 0.7s linear infinite;margin:0 auto 12px;"></div>
    <div style="font-size:13px;color:var(--text-tertiary);">Đang tải dữ liệu KPI...</div>
  </div>

  <!-- Empty state -->
  <div id="kpiTeamEmpty" style="display:none;padding:64px 24px;text-align:center;">
    <div style="font-size:36px;margin-bottom:10px;">📊</div>
    <div style="font-size:14px;font-weight:700;color:var(--text-secondary);margin-bottom:4px;">Chưa có dữ liệu KPI</div>
    <div style="font-size:12px;color:var(--text-tertiary);">Team chưa có nhân viên sale nào</div>
  </div>

  <!-- Main content (populated by JS) -->
  <div id="kpiTeamContent" style="display:none;">

    <!-- Team hero: populated by renderKpiTeam() -->
    <div class="team-hero" id="kpiTeamHero"></div>

    <!-- Leaderboard: populated by renderKpiTeam() -->
    <div class="leaderboard" id="kpiLeaderboard"></div>

    <!-- Tab bar -->
    <div class="sp-tabs">
      <button class="sp-tab active" id="kpiTabAll"     onclick="kpiTabSwitch(this,'all')">Tất cả</button>
      <button class="sp-tab"        id="kpiTabActive"  onclick="kpiTabSwitch(this,'active')">Đang active</button>
      <button class="sp-tab"        id="kpiTabSupport" onclick="kpiTabSwitch(this,'support')">Cần hỗ trợ</button>
    </div>

    <!-- Sale card list: populated by renderKpiSaleCards() -->
    <div class="sp-scroll" id="kpiSaleCardsList" style="padding-bottom:16px;"></div>

  </div>
</div>
