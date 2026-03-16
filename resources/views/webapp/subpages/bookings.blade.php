  <!-- ========== SUBPAGE: LỊCH HẸN ========== -->
  <div class="subpage" id="subpage-bookings">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('bookings')">←</button>
      <div class="sp-title">🗓️ Lịch hẹn xem nhà</div>
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
            <div class="bk-customer">👤 Anh Minh Tuấn · <span style="color:var(--primary);font-weight:600">0901.234.567</span></div>
            <div class="bk-note">Ghi chú: Khách đi xe gầm cao, hẹn trước cổng nhà</div>
          </div>
          <span class="badge badge-green">Hôm nay</span>
        </div>
        <div class="bk-actions">
          <button class="bk-btn" onclick="showToast('Đang gọi...')">📞 Gọi</button>
          <button class="bk-btn warning" onclick="toggleBkResult('bk1-result')">🔄 Dời lịch</button>
          <button class="bk-btn primary" onclick="toggleBkResult('bk1-result')">✓ Cập nhật kết quả</button>
        </div>
        <div class="bk-result-panel" id="bk1-result">
          <div style="font-size:11px;font-weight:600;color:var(--text-tertiary);margin-bottom:8px;text-transform:uppercase;letter-spacing:.04em;">Kết quả buổi xem nhà</div>
          <div class="bk-result-opts">
            <div class="bk-result-opt" onclick="selectBkResult(this,'success')">
              <div class="bk-result-icon">😊</div>Ưng ý
            </div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'warning')">
              <div class="bk-result-icon">🤔</div>Cân nhắc
            </div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'danger')">
              <div class="bk-result-icon">😞</div>Không ưng
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
            <div class="bk-customer">👤 Chị Thu Hà · <span style="color:var(--primary);font-weight:600">0978.654.321</span></div>
            <div class="bk-note">Ghi chú: Chủ nhà có mặt, chuẩn bị hồ sơ pháp lý</div>
          </div>
          <span class="badge badge-green">Chiều nay</span>
        </div>
        <div class="bk-actions">
          <button class="bk-btn" onclick="showToast('Đang gọi...')">📞 Gọi</button>
          <button class="bk-btn warning" onclick="showToast('Mở form dời lịch...')">🔄 Dời</button>
          <button class="bk-btn primary" onclick="toggleBkResult('bk2-result')">✓ Cập nhật</button>
        </div>
        <div class="bk-result-panel" id="bk2-result">
          <div class="bk-result-opts">
            <div class="bk-result-opt" onclick="selectBkResult(this,'success')"><div class="bk-result-icon">😊</div>Ưng ý</div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'warning')"><div class="bk-result-icon">🤔</div>Cân nhắc</div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'danger')"><div class="bk-result-icon">😞</div>Không ưng</div>
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
            <div class="bk-customer">👤 Anh Minh Tuấn</div>
            <div class="bk-note">Xem lần 2 — Khách muốn đo lại diện tích</div>
          </div>
          <span class="badge badge-blue">Ngày mai</span>
        </div>
        <div class="bk-actions">
          <button class="bk-btn" onclick="showToast('Đang gọi...')">📞 Nhắc</button>
          <button class="bk-btn warning" onclick="showToast('Mở form dời lịch...')">🔄 Dời lịch</button>
          <button class="bk-btn danger" onclick="showToast('Đã huỷ lịch')">✕ Huỷ</button>
        </div>
      </div>

      <!-- BOOKING cần update (đã qua, chưa update) -->
      <div style="padding:14px 14px 4px;font-size:11px;font-weight:700;color:var(--warning);text-transform:uppercase;letter-spacing:.05em;">⚠ Cần cập nhật kết quả</div>
      <div class="bk-card rescheduled">
        <div class="bk-head">
          <div class="bk-datetime" style="background:var(--warning)">
            <div class="bk-date-day">14</div>
            <div class="bk-date-mon">Tháng 3</div>
            <div class="bk-time">14:00</div>
          </div>
          <div class="bk-info">
            <div class="bk-prop">Biệt thự View Đồi Chè Cầu Đất</div>
            <div class="bk-customer">👤 Chị Thu Hà</div>
            <div class="bk-note">Đã xem xong, chưa cập nhật kết quả!</div>
          </div>
          <span class="badge badge-amber">⚠ Chưa update</span>
        </div>
        <div class="bk-actions">
          <button class="bk-btn primary" onclick="toggleBkResult('bk3-result')" style="flex:2">✓ Cập nhật kết quả ngay</button>
        </div>
        <div class="bk-result-panel" id="bk3-result">
          <div class="bk-result-opts">
            <div class="bk-result-opt" onclick="selectBkResult(this,'success')"><div class="bk-result-icon">😊</div>Ưng ý</div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'warning')"><div class="bk-result-icon">🤔</div>Cân nhắc</div>
            <div class="bk-result-opt" onclick="selectBkResult(this,'danger')"><div class="bk-result-icon">😞</div>Không ưng</div>
          </div>
          <textarea class="lc-note-area" rows="2" placeholder="Phản hồi của khách..."></textarea>
          <button class="bk-result-confirm" id="bk3-confirm" disabled onclick="showToast('✓ Đã cập nhật kết quả!')">✓ Xác nhận kết quả</button>
        </div>
      </div>

      <div style="height:16px"></div>
    </div>
  </div>

