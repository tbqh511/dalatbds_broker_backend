    <div class="notif-detail-page" id="nd-approve">
      <div class="nd-header">
        <button class="nd-back" onclick="closeNotifDetail('nd-approve')">←</button>
        <div class="nd-title">Duyệt BĐS chờ xét</div>
      </div>
      <div class="nd-scroll">
        <div class="nd-hero admin-type">
          <div class="nd-hero-icon">⏳</div>
          <div class="nd-hero-title">BĐS chờ duyệt</div>
          <div class="nd-hero-sub">Nhà mặt tiền Trần Phú, P.1<br>Broker Trần Văn Hùng gửi lúc 10:23</div>
          <div class="nd-hero-time">📅 15/03/2026 · 10:23</div>
        </div>

        <!-- BĐS info -->
        <div class="nd-card">
          <div class="nd-card-title">🏡 Thông tin BĐS</div>
          <div class="nd-info-row">
            <span class="nd-info-icon">🏷</span>
            <span class="nd-info-label">Tiêu đề</span>
            <span class="nd-info-value" style="font-size:12px;">Nhà mặt tiền Trần Phú, 4PN</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">🏠</span>
            <span class="nd-info-label">Loại</span>
            <span class="nd-info-value">Nhà phố</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">💰</span>
            <span class="nd-info-label">Giá</span>
            <span class="nd-info-value money">2,800 triệu</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📐</span>
            <span class="nd-info-label">Diện tích</span>
            <span class="nd-info-value">120 m²</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">📍</span>
            <span class="nd-info-label">Vị trí</span>
            <span class="nd-info-value">Đường Trần Phú, P.1</span>
          </div>
          <div class="nd-info-row">
            <span class="nd-info-icon">👤</span>
            <span class="nd-info-label">Broker</span>
            <span class="nd-info-value">Trần Văn Hùng</span>
          </div>
        </div>

        <!-- Legal checklist -->
        <div class="nd-card">
          <div class="nd-card-title">⚖️ Kiểm tra pháp lý</div>
          <div style="padding:8px 14px 12px;display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;background:var(--success-light);border-radius:var(--radius-sm);">
              <span style="color:var(--success);font-weight:700;">✓</span>
              <span style="font-size:12px;color:var(--success);">Sổ hồng — Đã upload ảnh rõ ràng</span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;background:var(--success-light);border-radius:var(--radius-sm);">
              <span style="color:var(--success);font-weight:700;">✓</span>
              <span style="font-size:12px;color:var(--success);">Không tranh chấp — Broker xác nhận</span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;background:var(--success-light);border-radius:var(--radius-sm);">
              <span style="color:var(--success);font-weight:700;">✓</span>
              <span style="font-size:12px;color:var(--success);">10 ảnh thực tế chất lượng tốt</span>
            </div>
            <div style="display:flex;align-items:center;gap:10px;padding:8px 10px;background:var(--warning-light);border-radius:var(--radius-sm);">
              <span style="color:var(--warning);font-weight:700;">!</span>
              <span style="font-size:12px;color:var(--warning);">Hệ số xây dựng chưa ghi rõ — Cần hỏi thêm</span>
            </div>
          </div>
        </div>

        <!-- Preview action -->
        <div class="nd-card">
          <div class="nd-card-title">👁 Xem trước tin đăng</div>
          <div style="padding:10px 14px 12px;">
            <button style="width:100%;padding:11px;background:var(--primary-light);color:var(--primary-dark);border:1.5px solid var(--primary-light);border-radius:var(--radius-md);font-size:13px;font-weight:700;cursor:pointer;" onclick="openDetail({title:'Nhà phố Trần Phú',price:'2,800 triệu',type:'Nhà phố',area:'120 m²',addr:'P.1',room:'4 PN',slide:2});closeNotifDetail('nd-approve')">
              👁 Xem chi tiết BĐS trước khi duyệt
            </button>
          </div>
        </div>

        <!-- Reject reason textarea -->
        <div class="nd-card">
          <div class="nd-card-title">📝 Ghi chú cho Broker (nếu từ chối)</div>
          <div class="nd-note-wrap" style="padding:10px 14px 12px;">
            <textarea class="nd-note" rows="3" placeholder="Nhập lý do từ chối hoặc yêu cầu bổ sung hồ sơ..."></textarea>
          </div>
        </div>
        <div style="height:16px;"></div>
      </div>
      <div class="nd-action-bar">
        <button class="nd-action-btn nd-btn-danger" onclick="showToast('✕ Đã từ chối và gửi phản hồi cho Broker');closeNotifDetail('nd-approve')">✕ Từ chối</button>
        <button class="nd-action-btn nd-btn-success" onclick="showToast('✓ Đã duyệt BĐS Nhà phố Trần Phú!');closeNotifDetail('nd-approve')">✓ Duyệt ngay</button>
      </div>
    </div><!-- nd-approve -->


    <!-- ND: DEAL STUCK DETAIL -->
