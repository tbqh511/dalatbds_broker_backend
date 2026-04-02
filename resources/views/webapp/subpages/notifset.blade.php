<!-- ========== SUBPAGE: CÀI ĐẶT THÔNG BÁO ========== -->
<div class="subpage" id="subpage-notifset">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('notifset')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg> Cài đặt thông báo</span></div>
  </div>

  <div class="sp-scroll" style="padding-bottom:80px;">

    <!-- Master switch -->
    <div class="form-section" style="margin-bottom:8px;">
      <div class="notif-row" style="padding:14px 16px;background:var(--primary-light);border-bottom:1px solid #bfdbfe;">
        <div class="notif-row-body">
          <div class="notif-row-title" style="font-size:14px;font-weight:700;color:var(--primary-dark);">Tất cả thông báo</div>
          <div class="notif-row-sub">Bật/tắt toàn bộ thông báo từ Đà Lạt BĐS</div>
        </div>
        <div class="toggle-wrap">
          <input type="checkbox" class="ios-toggle" checked id="ntog-master" onchange="toggleMaster(this)">
        </div>
      </div>
    </div>

    <!-- CRM Notifications — Sale -->
    <div class="notif-category role-sale">
      <div class="nc-header">
        <div class="nc-icon" style="background:var(--danger-light);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
        <div class="nc-title">Lead & Khách hàng</div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Lead mới được assign</div>
          <div class="notif-row-sub">Thông báo khi có Lead mới từ Admin</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-lead-assigned" checked></div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Nhắc nhở Lead chưa liên hệ</div>
          <div class="notif-row-sub">Sau 24h nếu Lead chưa được contact</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-lead-followup" checked></div>
      </div>
      <div class="channel-row">
        <span class="channel-label"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg> Kênh nhận</span></span>
        <div class="channel-badges">
          <span class="ch-badge active" data-cat="lead" data-ch="telegram" onclick="toggleChBadge(this)">Telegram Bot</span>
          <span class="ch-badge active" data-cat="lead" data-ch="in_app" onclick="toggleChBadge(this)">In-app</span>
          <span class="ch-badge" data-cat="lead" data-ch="zalo" onclick="toggleChBadge(this)">Zalo</span>
        </div>
      </div>
    </div>

    <div class="notif-category role-sale">
      <div class="nc-header">
        <div class="nc-icon" style="background:var(--purple-light);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <div class="nc-title">Deal & Giao dịch</div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Cập nhật trạng thái Deal</div>
          <div class="notif-row-sub">Khi deal chuyển trạng thái mới</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-deal-status" checked></div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Phản hồi của khách về BĐS đã gửi</div>
          <div class="notif-row-sub">Ưng ý / Không ưng / Muốn xem</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-deal-feedback" checked></div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Deal bị stuck (không hoạt động 5 ngày)</div>
          <div class="notif-row-sub">Nhắc nhở cập nhật deal bị trì trệ</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-deal-stuck" checked></div>
      </div>
      <div class="channel-row">
        <span class="channel-label"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg> Kênh nhận</span></span>
        <div class="channel-badges">
          <span class="ch-badge active" data-cat="deal" data-ch="telegram" onclick="toggleChBadge(this)">Telegram Bot</span>
          <span class="ch-badge active" data-cat="deal" data-ch="in_app" onclick="toggleChBadge(this)">In-app</span>
        </div>
      </div>
    </div>

    <div class="notif-category role-sale">
      <div class="nc-header">
        <div class="nc-icon" style="background:var(--primary-light);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        <div class="nc-title">Lịch hẹn & Booking</div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Nhắc trước lịch xem nhà 1 ngày</div>
          <div class="notif-row-sub">Thông báo 1 ngày trước lịch hẹn</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-booking-day_before" checked></div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Nhắc trước 1 giờ</div>
          <div class="notif-row-sub">Nhắc lại 1 giờ trước khi xem nhà</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-booking-hour_before" checked></div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Nhắc cập nhật kết quả sau khi xem</div>
          <div class="notif-row-sub">Sau 2h từ giờ hẹn, nhắc điền kết quả</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-booking-result" checked></div>
      </div>
      <div class="channel-row">
        <span class="channel-label"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg> Kênh nhận</span></span>
        <div class="channel-badges">
          <span class="ch-badge active" data-cat="booking" data-ch="telegram" onclick="toggleChBadge(this)">Telegram Bot</span>
          <span class="ch-badge active" data-cat="booking" data-ch="in_app" onclick="toggleChBadge(this)">In-app</span>
        </div>
      </div>
    </div>

    <div class="notif-category role-sale">
      <div class="nc-header">
        <div class="nc-icon" style="background:var(--success-light);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
        <div class="nc-title">Hoa hồng</div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Hoa hồng được duyệt</div>
          <div class="notif-row-sub">Khi Admin xác nhận commission</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-commission-approved" checked></div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Cập nhật trạng thái hoa hồng</div>
          <div class="notif-row-sub">Cọc → Công chứng → Hoàn tất</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-commission-status" checked></div>
      </div>
      <div class="channel-row">
        <span class="channel-label"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg> Kênh nhận</span></span>
        <div class="channel-badges">
          <span class="ch-badge active" data-cat="commission" data-ch="telegram" onclick="toggleChBadge(this)">Telegram Bot</span>
          <span class="ch-badge active" data-cat="commission" data-ch="in_app" onclick="toggleChBadge(this)">In-app</span>
          <span class="ch-badge active" data-cat="commission" data-ch="zalo" onclick="toggleChBadge(this)">Zalo</span>
        </div>
      </div>
    </div>

    <!-- BĐS Notifications — Broker -->
    <div class="notif-category role-broker">
      <div class="nc-header">
        <div class="nc-icon" style="background:var(--amber-light,#fef3c7);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></div>
        <div class="nc-title">Bất động sản của tôi</div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">BĐS được duyệt / từ chối</div>
          <div class="notif-row-sub">Kết quả sau khi gửi tin lên hệ thống</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-property-status" checked></div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Có người quan tâm BĐS của tôi</div>
          <div class="notif-row-sub">Khi có Bookmark hoặc yêu cầu xem</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-property-interest" checked></div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Tin sắp hết hạn</div>
          <div class="notif-row-sub">Nhắc 3 ngày trước khi tin bị ẩn</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-property-expiry"></div>
      </div>
      <div class="channel-row">
        <span class="channel-label"><span style="display:inline-flex;align-items:center;gap:4px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg> Kênh nhận</span></span>
        <div class="channel-badges">
          <span class="ch-badge active" data-cat="property" data-ch="telegram" onclick="toggleChBadge(this)">Telegram Bot</span>
          <span class="ch-badge active" data-cat="property" data-ch="in_app" onclick="toggleChBadge(this)">In-app</span>
        </div>
      </div>
    </div>

    <!-- Marketing -->
    <div class="notif-category">
      <div class="nc-header">
        <div class="nc-icon" style="background:var(--bg-secondary);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg></div>
        <div class="nc-title">Tin tức & Thị trường</div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Biến động giá thị trường</div>
          <div class="notif-row-sub">Báo cáo thị trường hàng tuần</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-market-news" checked></div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">BĐS mới phù hợp với khách đang deal</div>
          <div class="notif-row-sub">AI gợi ý BĐS match với nhu cầu khách</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-market-ai_suggest" checked></div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Thông báo khuyến mãi & sự kiện</div>
          <div class="notif-row-sub">Từ Đà Lạt BĐS (tối đa 2 lần/tuần)</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="ntog-market-promotions"></div>
      </div>
    </div>

    <!-- Quiet hours -->
    <div class="notif-category">
      <div class="nc-header">
        <div class="nc-icon" style="background:#f1f5f9;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg></div>
        <div class="nc-title">Giờ yên lặng (Quiet Hours)</div>
      </div>
      <div class="notif-row">
        <div class="notif-row-body">
          <div class="notif-row-title">Bật giờ yên lặng</div>
          <div class="notif-row-sub">Tắt thông báo không khẩn cấp trong giờ này</div>
        </div>
        <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" id="quiet-toggle" checked onchange="toggleQuiet(this)"></div>
      </div>
      <div class="quiet-wrap" id="quiet-hours">
        <span class="quiet-label">Không làm phiền từ</span>
        <div class="quiet-time">
          <select class="time-select" id="quiet-start">
            @foreach(['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'] as $h)
              <option value="{{ $h }}:00">{{ $h }}:00</option>
            @endforeach
          </select>
          <span style="font-size:13px;color:var(--text-secondary);">đến</span>
          <select class="time-select" id="quiet-end">
            @foreach(['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'] as $h)
              <option value="{{ $h }}:00">{{ $h }}:00</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <div style="height:16px;"></div>
  </div>

  <div class="sticky-save">
    <button class="cancel-btn" onclick="closeSubpage('notifset')">Hủy</button>
    <button class="save-btn" id="notifset-save-btn" onclick="saveNotifSettings()">✓ Lưu cài đặt</button>
  </div>
</div><!-- end subpage-notifset -->


