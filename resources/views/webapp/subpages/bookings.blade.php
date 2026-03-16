  <!-- ========== SUBPAGE: LỊCH HẸN ========== -->
  <div class="subpage" id="subpage-bookings">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('bookings')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Lịch hẹn xem nhà</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="openBookingForm()">＋</button>
      </div>
    </div>
    <div class="kpi-strip">
      <div class="kpi-item"><div class="kpi-val" style="color:var(--success)">2</div><div class="kpi-lbl">Hôm nay</div></div>
      <div class="kpi-item"><div class="kpi-val" style="color:var(--primary)">3</div><div class="kpi-lbl">Tuần này</div></div>
      <div class="kpi-item"><div class="kpi-val" style="color:var(--warning)">1</div><div class="kpi-lbl">Cần update</div></div>
      <div class="kpi-item"><div class="kpi-val">18</div><div class="kpi-lbl">Tháng này</div></div>
    </div>
    <!-- Calendar week strip -->
    <div class="cal-strip">
      <div class="cal-month-nav">
        <div class="cal-month-label">Tháng 3, 2026</div>
        <div style="display:flex;gap:6px;">
          <button class="cal-nav-btn">‹</button>
          <button class="cal-nav-btn">›</button>
        </div>
      </div>
      <div class="cal-week">
        <div class="cal-day" onclick="selectCalDay(this)"><div class="cal-day-name">T2</div><div class="cal-day-num">9</div></div>
        <div class="cal-day" onclick="selectCalDay(this)"><div class="cal-day-name">T3</div><div class="cal-day-num">10</div></div>
        <div class="cal-day" onclick="selectCalDay(this)"><div class="cal-day-name">T4</div><div class="cal-day-num">11</div></div>
        <div class="cal-day" onclick="selectCalDay(this)"><div class="cal-day-name">T5</div><div class="cal-day-num">12</div></div>
        <div class="cal-day" onclick="selectCalDay(this)"><div class="cal-day-name">T6</div><div class="cal-day-num">13</div></div>
        <div class="cal-day" onclick="selectCalDay(this)"><div class="cal-day-name">T7</div><div class="cal-day-num">14</div></div>
        <div class="cal-day active has-event" onclick="selectCalDay(this)"><div class="cal-day-name">CN</div><div class="cal-day-num">15</div></div>
        <div class="cal-day has-event" onclick="selectCalDay(this)"><div class="cal-day-name">T2</div><div class="cal-day-num">16</div></div>
        <div class="cal-day" onclick="selectCalDay(this)"><div class="cal-day-name">T3</div><div class="cal-day-num">17</div></div>
      </div>
    </div>
    <div class="sp-tabs">
      <button class="sp-tab active" onclick="spTabSwitch(this)">Sắp tới</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Cần update (1)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đã qua</button>
    </div>
    <div class="sp-scroll">

      <!-- BOOKING TODAY 1 -->
      <div style="padding:10px 14px 4px;font-size:11px;font-weight:700;color:var(--success);text-transform:uppercase;letter-spacing:.05em;">Hôm nay — 15/03</div>
      <div class="bk-card today">
        <div class="bk-head">
          <div class="bk-datetime" style="background:var(--success)">
            <div class="bk-date-day">15</div>
            <div class="bk-date-mon">Tháng 3</div>
            <div class="bk-time">09:00</div>
          </div>
          <div class="bk-info">
            <div class="bk-prop">Đất Đường Yersin, Cam Ly</div>
            <div class="bk-customer"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>Anh Minh Tuấn · <span style="color:var(--primary);font-weight:600">0901.234.567</span></div>
            <div class="bk-note">Ghi chú: Khách đi xe gầm cao, hẹn trước cổng nhà</div>
          </div>
          <span class="badge badge-green">Hôm nay</span>
        </div>
        <div class="bk-actions">
          <button class="bk-btn" onclick="showToast('Đang gọi...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg> Gọi</span></button>
          <button class="bk-btn warning" onclick="toggleBkResult('bk1-result')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Dời lịch</span></button>
          <button class="bk-btn primary" onclick="toggleBkResult('bk1-result')">✓ Cập nhật kết quả</button>
        </div>
        <div class="bk-result-panel" id="bk1-result">
          <div style="font-size:11px;font-weight:600;color:var(--text-tertiary);margin-bottom:8px;text-transform:uppercase;letter-spacing:.04em;">Kết quả buổi xem nhà</div>
          <div class="bk-result-opts">
            <div class="bk-result-opt" onclick="selectBkResult(this,'success')">
              <div class="bk-result-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>Ưng ý
            </div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'warning')">
              <div class="bk-result-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="15" x2="16" y2="15"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>Cân nhắc
            </div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'danger')">
              <div class="bk-result-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>Không ưng
            </div>
          </div>
          <textarea class="lc-note-area" rows="2" placeholder="Phản hồi chi tiết của khách (VD: Thích view nhưng đường hẻm nhỏ...)"></textarea>
          <button class="bk-result-confirm" id="bk1-confirm" disabled onclick="showToast('✓ Đã cập nhật kết quả!')">✓ Xác nhận kết quả</button>
        </div>
      </div>

      <!-- BOOKING TODAY 2 -->
      <div class="bk-card today">
        <div class="bk-head">
          <div class="bk-datetime" style="background:var(--success)">
            <div class="bk-date-day">15</div>
            <div class="bk-date-mon">Tháng 3</div>
            <div class="bk-time">14:30</div>
          </div>
          <div class="bk-info">
            <div class="bk-prop">Nhà phố Trần Phú, P.1</div>
            <div class="bk-customer"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>Chị Thu Hà · <span style="color:var(--primary);font-weight:600">0978.654.321</span></div>
            <div class="bk-note">Ghi chú: Chủ nhà có mặt, chuẩn bị hồ sơ pháp lý</div>
          </div>
          <span class="badge badge-green">Chiều nay</span>
        </div>
        <div class="bk-actions">
          <button class="bk-btn" onclick="showToast('Đang gọi...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg> Gọi</span></button>
          <button class="bk-btn warning" onclick="showToast('Mở form dời lịch...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Dời</span></button>
          <button class="bk-btn primary" onclick="toggleBkResult('bk2-result')">✓ Cập nhật</button>
        </div>
        <div class="bk-result-panel" id="bk2-result">
          <div class="bk-result-opts">
            <div class="bk-result-opt" onclick="selectBkResult(this,'success')"><div class="bk-result-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>Ưng ý</div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'warning')"><div class="bk-result-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="15" x2="16" y2="15"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>Cân nhắc</div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'danger')"><div class="bk-result-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>Không ưng</div>
          </div>
          <textarea class="lc-note-area" rows="2" placeholder="Phản hồi khách..."></textarea>
          <button class="bk-result-confirm" id="bk2-confirm" disabled onclick="showToast('✓ Đã cập nhật!')">✓ Xác nhận</button>
        </div>
      </div>

      <!-- BOOKING UPCOMING -->
      <div style="padding:14px 14px 4px;font-size:11px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:.05em;">Sắp tới</div>
      <div class="bk-card upcoming">
        <div class="bk-head">
          <div class="bk-datetime">
            <div class="bk-date-day">16</div>
            <div class="bk-date-mon">Tháng 3</div>
            <div class="bk-time">09:00</div>
          </div>
          <div class="bk-info">
            <div class="bk-prop">Đất nền Lâm Viên 180m²</div>
            <div class="bk-customer"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>Anh Minh Tuấn</div>
            <div class="bk-note">Xem lần 2 — Khách muốn đo lại diện tích</div>
          </div>
          <span class="badge badge-blue">Ngày mai</span>
        </div>
        <div class="bk-actions">
          <button class="bk-btn" onclick="showToast('Đang gọi...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg> Nhắc</span></button>
          <button class="bk-btn warning" onclick="showToast('Mở form dời lịch...')"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Dời lịch</span></button>
          <button class="bk-btn danger" onclick="showToast('Đã huỷ lịch')">✕ Huỷ</button>
        </div>
      </div>

      <!-- BOOKING cần update (đã qua, chưa update) -->
      <div style="padding:14px 14px 4px;font-size:11px;font-weight:700;color:var(--warning);text-transform:uppercase;letter-spacing:.05em;display:flex;align-items:center;gap:5px;"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Cần cập nhật kết quả</div>
      <div class="bk-card rescheduled">
        <div class="bk-head">
          <div class="bk-datetime" style="background:var(--warning)">
            <div class="bk-date-day">14</div>
            <div class="bk-date-mon">Tháng 3</div>
            <div class="bk-time">14:00</div>
          </div>
          <div class="bk-info">
            <div class="bk-prop">Biệt thự View Đồi Chè Cầu Đất</div>
            <div class="bk-customer"><span style="display:inline-flex;align-items:center;vertical-align:middle;margin-right:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>Chị Thu Hà</div>
            <div class="bk-note">Đã xem xong, chưa cập nhật kết quả!</div>
          </div>
          <span class="badge badge-amber"><span style="display:inline-flex;align-items:center;gap:2px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></span> Chưa update</span>
        </div>
        <div class="bk-actions">
          <button class="bk-btn primary" onclick="toggleBkResult('bk3-result')" style="flex:2">✓ Cập nhật kết quả ngay</button>
        </div>
        <div class="bk-result-panel" id="bk3-result">
          <div class="bk-result-opts">
            <div class="bk-result-opt" onclick="selectBkResult(this,'success')"><div class="bk-result-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>Ưng ý</div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'warning')"><div class="bk-result-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="15" x2="16" y2="15"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>Cân nhắc</div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'danger')"><div class="bk-result-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>Không ưng</div>
          </div>
          <textarea class="lc-note-area" rows="2" placeholder="Phản hồi của khách..."></textarea>
          <button class="bk-result-confirm" id="bk3-confirm" disabled onclick="showToast('✓ Đã cập nhật kết quả!')">✓ Xác nhận kết quả</button>
        </div>
      </div>

      <div style="height:16px"></div>
    </div>
  </div>

