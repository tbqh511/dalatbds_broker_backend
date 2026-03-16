  <!-- ========== SUBPAGE: CÀI ĐẶT THÔNG BÁO ========== -->
  <div class="subpage" id="subpage-notifset">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('notifset')">←</button>
      <div class="sp-title">🔔 Cài đặt thông báo</div>
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
            <input type="checkbox" class="ios-toggle" checked id="master-toggle" onchange="toggleMaster(this)">
          </div>
        </div>
      </div>

      <!-- CRM Notifications — Sale -->
      <div class="notif-category role-sale">
        <div class="nc-header">
          <div class="nc-icon" style="background:var(--danger-light);">🎯</div>
          <div class="nc-title">Lead & Khách hàng</div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Lead mới được assign</div>
            <div class="notif-row-sub">Thông báo khi có Lead mới từ Admin</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Nhắc nhở Lead chưa liên hệ</div>
            <div class="notif-row-sub">Sau 24h nếu Lead chưa được contact</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="channel-row">
          <span class="channel-label">📢 Kênh nhận</span>
          <div class="channel-badges">
            <span class="ch-badge active" onclick="toggleChBadge(this)">Telegram Bot</span>
            <span class="ch-badge active" onclick="toggleChBadge(this)">In-app</span>
            <span class="ch-badge" onclick="toggleChBadge(this)">Zalo</span>
          </div>
        </div>
      </div>

      <div class="notif-category role-sale">
        <div class="nc-header">
          <div class="nc-icon" style="background:var(--purple-light);">🤝</div>
          <div class="nc-title">Deal & Giao dịch</div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Cập nhật trạng thái Deal</div>
            <div class="notif-row-sub">Khi deal chuyển trạng thái mới</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Phản hồi của khách về BĐS đã gửi</div>
            <div class="notif-row-sub">Ưng ý / Không ưng / Muốn xem</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Deal bị stuck (không hoạt động 5 ngày)</div>
            <div class="notif-row-sub">Nhắc nhở cập nhật deal bị trì trệ</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="channel-row">
          <span class="channel-label">📢 Kênh nhận</span>
          <div class="channel-badges">
            <span class="ch-badge active" onclick="toggleChBadge(this)">Telegram Bot</span>
            <span class="ch-badge active" onclick="toggleChBadge(this)">In-app</span>
          </div>
        </div>
      </div>

      <div class="notif-category role-sale">
        <div class="nc-header">
          <div class="nc-icon" style="background:var(--primary-light);">📅</div>
          <div class="nc-title">Lịch hẹn & Booking</div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Nhắc trước lịch xem nhà 1 ngày</div>
            <div class="notif-row-sub">Thông báo 1 ngày trước lịch hẹn</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Nhắc trước 1 giờ</div>
            <div class="notif-row-sub">Nhắc lại 1 giờ trước khi xem nhà</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Nhắc cập nhật kết quả sau khi xem</div>
            <div class="notif-row-sub">Sau 2h từ giờ hẹn, nhắc điền kết quả</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="channel-row">
          <span class="channel-label">📢 Kênh nhận</span>
          <div class="channel-badges">
            <span class="ch-badge active" onclick="toggleChBadge(this)">Telegram Bot</span>
            <span class="ch-badge active" onclick="toggleChBadge(this)">In-app</span>
          </div>
        </div>
      </div>

      <div class="notif-category role-sale">
        <div class="nc-header">
          <div class="nc-icon" style="background:var(--success-light);">💰</div>
          <div class="nc-title">Hoa hồng</div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Hoa hồng được duyệt</div>
            <div class="notif-row-sub">Khi Admin xác nhận commission</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Cập nhật trạng thái hoa hồng</div>
            <div class="notif-row-sub">Cọc → Công chứng → Hoàn tất</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="channel-row">
          <span class="channel-label">📢 Kênh nhận</span>
          <div class="channel-badges">
            <span class="ch-badge active" onclick="toggleChBadge(this)">Telegram Bot</span>
            <span class="ch-badge active" onclick="toggleChBadge(this)">In-app</span>
            <span class="ch-badge active" onclick="toggleChBadge(this)">Zalo</span>
          </div>
        </div>
      </div>

      <!-- BĐS Notifications — Broker -->
      <div class="notif-category role-broker">
        <div class="nc-header">
          <div class="nc-icon" style="background:var(--amber-light,#fef3c7);">🏡</div>
          <div class="nc-title">Bất động sản của tôi</div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">BĐS được duyệt / từ chối</div>
            <div class="notif-row-sub">Kết quả sau khi gửi tin lên hệ thống</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Có người quan tâm BĐS của tôi</div>
            <div class="notif-row-sub">Khi có Bookmark hoặc yêu cầu xem</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Tin sắp hết hạn</div>
            <div class="notif-row-sub">Nhắc 3 ngày trước khi tin bị ẩn</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle"></div>
        </div>
        <div class="channel-row">
          <span class="channel-label">📢 Kênh nhận</span>
          <div class="channel-badges">
            <span class="ch-badge active" onclick="toggleChBadge(this)">Telegram Bot</span>
            <span class="ch-badge active" onclick="toggleChBadge(this)">In-app</span>
          </div>
        </div>
      </div>

      <!-- Marketing -->
      <div class="notif-category">
        <div class="nc-header">
          <div class="nc-icon" style="background:var(--bg-secondary);">📢</div>
          <div class="nc-title">Tin tức & Thị trường</div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Biến động giá thị trường</div>
            <div class="notif-row-sub">Báo cáo thị trường hàng tuần</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">BĐS mới phù hợp với khách đang deal</div>
            <div class="notif-row-sub">AI gợi ý BĐS match với nhu cầu khách</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle" checked></div>
        </div>
        <div class="notif-row">
          <div class="notif-row-body">
            <div class="notif-row-title">Thông báo khuyến mãi & sự kiện</div>
            <div class="notif-row-sub">Từ Đà Lạt BĐS (tối đa 2 lần/tuần)</div>
          </div>
          <div class="toggle-wrap"><input type="checkbox" class="ios-toggle"></div>
        </div>
      </div>

      <!-- Quiet hours -->
      <div class="notif-category">
        <div class="nc-header">
          <div class="nc-icon" style="background:#f1f5f9;">🌙</div>
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
            <select class="time-select"><option>22:00</option><option>21:00</option><option>23:00</option></select>
            <span style="font-size:13px;color:var(--text-secondary);">đến</span>
            <select class="time-select"><option>07:00</option><option>06:00</option><option>08:00</option></select>
          </div>
        </div>
      </div>

      <div style="height:16px;"></div>
    </div>

    <div class="sticky-save">
      <button class="cancel-btn" onclick="closeSubpage('notifset')">Hủy</button>
      <button class="save-btn" onclick="showToast('✓ Đã lưu cài đặt thông báo!')">✓ Lưu cài đặt</button>
    </div>
  </div><!-- end subpage-notifset -->


