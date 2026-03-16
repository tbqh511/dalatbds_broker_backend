  <!-- ========== SUBPAGE: ASSIGN LEAD ========== -->
  <div class="subpage" id="subpage-assignlead">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('assignlead')">←</button>
      <div class="sp-title">📋 Assign Lead cho Sale</div>
    </div>

    <!-- Pool summary -->
    <div class="assign-pool">
      <div class="ap-title">
        <span>LEAD CHƯA PHÂN CÔNG</span>
        <span class="ap-count" id="unassignedCount">4 lead</span>
      </div>
      <div style="display:flex;gap:8px;">
        <div style="flex:1;background:var(--danger-light);border-radius:var(--radius-sm);padding:8px 10px;text-align:center;">
          <div style="font-size:16px;font-weight:700;color:var(--danger);">2</div>
          <div style="font-size:10px;color:var(--danger);">Ngân sách cao (3 tỷ+)</div>
        </div>
        <div style="flex:1;background:var(--warning-light);border-radius:var(--radius-sm);padding:8px 10px;text-align:center;">
          <div style="font-size:16px;font-weight:700;color:var(--warning);">1</div>
          <div style="font-size:10px;color:var(--warning);">Ngân sách trung</div>
        </div>
        <div style="flex:1;background:var(--primary-light);border-radius:var(--radius-sm);padding:8px 10px;text-align:center;">
          <div style="font-size:16px;font-weight:700;color:var(--primary);">1</div>
          <div style="font-size:10px;color:var(--primary);">Ngân sách thấp</div>
        </div>
      </div>
    </div>

    <div class="sp-tabs">
      <button class="sp-tab active" onclick="spTabSwitch(this)">Chờ assign (4)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đã assign hôm nay</button>
    </div>

    <div class="sp-scroll" style="padding-bottom:80px;">

      <!-- UNASSIGNED LEAD 1 — Hot, ngân sách cao -->
      <div class="ul-card" id="ul1" onclick="toggleUlSelect('ul1')">
        <div class="ul-head">
          <div class="ul-checkbox" id="ul1-cb">○</div>
          <div style="width:38px;height:38px;border-radius:50%;background:#ef4444;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">NT</div>
          <div class="ul-info">
            <div class="ul-name">Nguyễn Văn Tuấn</div>
            <div class="ul-meta">
              <span>📞 0912.345.678</span>
              <span style="color:var(--danger);font-weight:600;">🔥 2 giờ trước</span>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">
            <span class="badge badge-red">Hot</span>
            <span style="font-size:10px;color:var(--text-tertiary);">Facebook Ads</span>
          </div>
        </div>
        <div class="ul-body">
          <div class="ul-row"><span class="ul-label">Loại BĐS</span><span class="ul-value">Biệt thự, Đất ở</span></div>
          <div class="ul-row"><span class="ul-label">Nhu cầu</span><span class="ul-value">Mua để ở + đầu tư</span></div>
          <div class="ul-row"><span class="ul-label">Ngân sách</span><span class="ul-value money">3 tỷ – 5 tỷ</span></div>
          <div class="ul-row"><span class="ul-label">Khu vực</span><span class="ul-value">P.Lâm Viên</span></div>
        </div>
        <div class="ul-tags">
          <span class="ul-tag">Biệt thự</span><span class="ul-tag">3–5 tỷ</span><span class="ul-tag">Lâm Viên</span>
          <span class="ul-tag" style="background:#fee2e2;color:#dc2626;">🔥 Ưu tiên cao</span>
        </div>
        <div class="ul-footer">
          <span style="font-size:11px;color:var(--text-tertiary);">Gợi ý: Huy Thái (phù hợp khu vực)</span>
          <button class="lc-btn primary" style="height:28px;font-size:11px;" onclick="event.stopPropagation();openSalePicker(['ul1'])">🎯 Assign ngay</button>
        </div>
      </div>

      <!-- UNASSIGNED LEAD 2 -->
      <div class="ul-card" id="ul2" onclick="toggleUlSelect('ul2')">
        <div class="ul-head">
          <div class="ul-checkbox" id="ul2-cb">○</div>
          <div style="width:38px;height:38px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">BT</div>
          <div class="ul-info">
            <div class="ul-name">Anh Bảo Trâm</div>
            <div class="ul-meta"><span>📞 0966.111.222</span><span style="color:var(--warning);font-weight:600;">6 giờ trước</span></div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">
            <span class="badge badge-amber">Trung bình</span>
            <span style="font-size:10px;color:var(--text-tertiary);">Zalo OA</span>
          </div>
        </div>
        <div class="ul-body">
          <div class="ul-row"><span class="ul-label">Loại BĐS</span><span class="ul-value">Khách sạn mini</span></div>
          <div class="ul-row"><span class="ul-label">Nhu cầu</span><span class="ul-value">Mua kinh doanh</span></div>
          <div class="ul-row"><span class="ul-label">Ngân sách</span><span class="ul-value money">3 tỷ – 5 tỷ</span></div>
          <div class="ul-row"><span class="ul-label">Khu vực</span><span class="ul-value">Trung tâm TP</span></div>
        </div>
        <div class="ul-tags"><span class="ul-tag">Khách sạn</span><span class="ul-tag">KD du lịch</span></div>
        <div class="ul-footer">
          <span style="font-size:11px;color:var(--text-tertiary);">Gợi ý: Minh Khoa (chuyên KD)</span>
          <button class="lc-btn primary" style="height:28px;font-size:11px;" onclick="event.stopPropagation();openSalePicker(['ul2'])">🎯 Assign ngay</button>
        </div>
      </div>

      <!-- UNASSIGNED LEAD 3 -->
      <div class="ul-card" id="ul3" onclick="toggleUlSelect('ul3')">
        <div class="ul-head">
          <div class="ul-checkbox" id="ul3-cb">○</div>
          <div style="width:38px;height:38px;border-radius:50%;background:var(--purple);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">LT</div>
          <div class="ul-info">
            <div class="ul-name">Chị Lan Trinh</div>
            <div class="ul-meta"><span>📞 0944.777.888</span><span>Hôm qua</span></div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">
            <span class="badge badge-blue">Bình thường</span>
            <span style="font-size:10px;color:var(--text-tertiary);">Website</span>
          </div>
        </div>
        <div class="ul-body">
          <div class="ul-row"><span class="ul-label">Loại BĐS</span><span class="ul-value">Nhà phố</span></div>
          <div class="ul-row"><span class="ul-label">Nhu cầu</span><span class="ul-value">Mua để ở</span></div>
          <div class="ul-row"><span class="ul-label">Ngân sách</span><span class="ul-value money">1.5 tỷ – 2 tỷ</span></div>
          <div class="ul-row"><span class="ul-label">Khu vực</span><span class="ul-value">P.Cam Ly, P.1</span></div>
        </div>
        <div class="ul-tags"><span class="ul-tag">Nhà phố</span><span class="ul-tag">1.5–2 tỷ</span></div>
        <div class="ul-footer">
          <span style="font-size:11px;color:var(--text-tertiary);">Gợi ý: Anh Linh (khu vực Cam Ly)</span>
          <button class="lc-btn primary" style="height:28px;font-size:11px;" onclick="event.stopPropagation();openSalePicker(['ul3'])">🎯 Assign ngay</button>
        </div>
      </div>

      <!-- UNASSIGNED LEAD 4 -->
      <div class="ul-card" id="ul4" onclick="toggleUlSelect('ul4')">
        <div class="ul-head">
          <div class="ul-checkbox" id="ul4-cb">○</div>
          <div style="width:38px;height:38px;border-radius:50%;background:#f59e0b;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">PD</div>
          <div class="ul-info">
            <div class="ul-name">Anh Phong Dũng</div>
            <div class="ul-meta"><span>📞 0988.222.444</span><span>2 ngày trước</span></div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;">
            <span class="badge badge-gray">Thấp</span>
            <span style="font-size:10px;color:var(--text-tertiary);">Giới thiệu</span>
          </div>
        </div>
        <div class="ul-body">
          <div class="ul-row"><span class="ul-label">Loại BĐS</span><span class="ul-value">Đất nền</span></div>
          <div class="ul-row"><span class="ul-label">Nhu cầu</span><span class="ul-value">Đầu tư dài hạn</span></div>
          <div class="ul-row"><span class="ul-label">Ngân sách</span><span class="ul-value money">500tr – 800tr</span></div>
          <div class="ul-row"><span class="ul-label">Khu vực</span><span class="ul-value">Ngoại ô Đà Lạt</span></div>
        </div>
        <div class="ul-tags"><span class="ul-tag">Đất nền</span><span class="ul-tag">Đầu tư</span></div>
        <div class="ul-footer">
          <span style="font-size:11px;color:var(--text-tertiary);">Gợi ý: Thu Nga hoặc Đức Huy</span>
          <button class="lc-btn primary" style="height:28px;font-size:11px;" onclick="event.stopPropagation();openSalePicker(['ul4'])">🎯 Assign ngay</button>
        </div>
      </div>

      <!-- Assign history -->
      <div class="assign-history" style="margin-top:16px;">
        <div class="ah-title">Đã assign hôm nay</div>
        <div class="ah-item">
          <div class="ah-avatar" style="background:var(--primary);">HT</div>
          <div class="ah-info">
            <div class="ah-name">Huy Thái</div>
            <div class="ah-detail">Nhận lead: Chị Phương Hoa · Nhà phố 2–4 tỷ</div>
          </div>
          <div class="ah-time">10:23</div>
        </div>
        <div class="ah-item">
          <div class="ah-avatar" style="background:var(--teal);">MK</div>
          <div class="ah-info">
            <div class="ah-name">Minh Khoa</div>
            <div class="ah-detail">Nhận lead: Anh Hoàng Minh · Đất nền đầu tư</div>
          </div>
          <div class="ah-time">09:05</div>
        </div>
        <div class="ah-item">
          <div class="ah-avatar" style="background:var(--primary);">HT</div>
          <div class="ah-info">
            <div class="ah-name">Huy Thái</div>
            <div class="ah-detail">Nhận lead: Nguyễn Văn Tuấn · Biệt thự 3–5 tỷ</div>
          </div>
          <div class="ah-time">08:30</div>
        </div>
      </div>

      <div style="height:20px;"></div>
    </div>

    <!-- Floating assign CTA (hiện khi chọn lead) -->
    <div class="assign-cta" id="assignCta" style="display:none;">
      <div class="assign-cta-info">
        <div class="assign-cta-count" id="assignCtaCount">0 lead được chọn</div>
        <div class="assign-cta-sub">Chọn lead rồi assign cho Sale</div>
      </div>
      <button class="assign-cta-btn" id="assignCtaBtn" onclick="openSalePicker(getSelectedLeads())">🎯 Assign cho Sale</button>
    </div>

  </div><!-- end subpage-assignlead -->

  <!-- Sale Picker Modal -->
  <div class="sale-picker" id="salePicker">
    <div class="sale-picker-inner">
      <div class="sp-inner-handle"></div>
      <div class="sp-inner-title">🎯 Chọn Sale để Assign</div>
      <div class="sp-inner-sub" id="salePickerSub">Đang assign 1 lead · Chọn Sale phù hợp</div>
      <div id="salePickerList">
        <div class="sale-pick-item" onclick="selectSalePick(this,'HT')">
          <div class="spi-avatar" style="background:var(--primary)">HT</div>
          <div class="spi-info">
            <div class="spi-name">Huy Thái</div>
            <div class="spi-meta">7 deal đang chăm · 🔥 Top tháng</div>
          </div>
          <div class="spi-workload">
            <span class="spi-leads mid">7 deals</span>
          </div>
          <span class="spi-check" id="sp-HT">○</span>
        </div>
        <div class="sale-pick-item" onclick="selectSalePick(this,'MK')">
          <div class="spi-avatar" style="background:var(--teal)">MK</div>
          <div class="spi-info">
            <div class="spi-name">Minh Khoa</div>
            <div class="spi-meta">5 deal đang chăm</div>
          </div>
          <div class="spi-workload">
            <span class="spi-leads mid">5 deals</span>
          </div>
          <span class="spi-check" id="sp-MK">○</span>
        </div>
        <div class="sale-pick-item" onclick="selectSalePick(this,'AL')">
          <div class="spi-avatar" style="background:#f59e0b">AL</div>
          <div class="spi-info">
            <div class="spi-name">Anh Linh</div>
            <div class="spi-meta">3 deal · ⚠ Cần hỗ trợ</div>
          </div>
          <div class="spi-workload">
            <span class="spi-leads low">3 deals</span>
          </div>
          <span class="spi-check" id="sp-AL">○</span>
        </div>
        <div class="sale-pick-item" onclick="selectSalePick(this,'TN')">
          <div class="spi-avatar" style="background:#8b5cf6">TN</div>
          <div class="spi-info">
            <div class="spi-name">Thu Nga</div>
            <div class="spi-meta">2 deal · Tháng đầu</div>
          </div>
          <div class="spi-workload">
            <span class="spi-leads low">2 deals</span>
          </div>
          <span class="spi-check" id="sp-TN">○</span>
        </div>
        <div class="sale-pick-item" onclick="selectSalePick(this,'DH')">
          <div class="spi-avatar" style="background:#059669">DH</div>
          <div class="spi-info">
            <div class="spi-name">Đức Huy</div>
            <div class="spi-meta">1 deal · Sale mới · Đang học việc</div>
          </div>
          <div class="spi-workload">
            <span class="spi-leads low">1 deal</span>
          </div>
          <span class="spi-check" id="sp-DH">○</span>
        </div>
      </div>
      <button class="sp-assign-btn" id="spAssignBtn" disabled onclick="confirmAssign()">✓ Xác nhận Assign</button>
    </div>
  </div>

