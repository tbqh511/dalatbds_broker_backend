  <!-- ========== SUBPAGE: HỖ TRỢ & FAQ ========== -->
  <div class="subpage" id="subpage-support">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('support')">←</button>
      <div class="sp-title">❓ Hỗ trợ & FAQ</div>
    </div>

    <div class="sp-scroll" style="padding-bottom:16px;">

      <!-- Hero -->
      <div class="faq-hero">
        <div class="faq-hero-title">Chúng tôi có thể giúp gì?</div>
        <div class="faq-hero-sub">Tìm câu trả lời hoặc liên hệ đội hỗ trợ Đà Lạt BĐS</div>
        <div class="faq-search-wrap">
          <span style="font-size:16px;color:var(--text-tertiary);">🔍</span>
          <input type="text" placeholder="Tìm kiếm câu hỏi...">
        </div>
      </div>

      <!-- Quick contact -->
      <div class="contact-strip">
        <div class="contact-btn" onclick="showToast('Mở Telegram hỗ trợ...')">
          <span class="contact-btn-icon">✈️</span>
          <span class="contact-btn-label">Telegram</span>
          <span class="contact-btn-sub">Phản hồi nhanh</span>
        </div>
        <div class="contact-btn" onclick="showToast('Gọi hotline: 1900.xxxx')">
          <span class="contact-btn-icon">📞</span>
          <span class="contact-btn-label">Hotline</span>
          <span class="contact-btn-sub">8:00 – 21:00</span>
        </div>
        <div class="contact-btn" onclick="showToast('Mở Zalo hỗ trợ...')">
          <span class="contact-btn-icon">💬</span>
          <span class="contact-btn-label">Zalo OA</span>
          <span class="contact-btn-sub">Zalo chính thức</span>
        </div>
      </div>

      <!-- FAQ: BĐS -->
      <div class="faq-category-label">🏡 Bất động sản</div>
      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span class="faq-q-icon">🏡</span>
          <span class="faq-q-text">Làm thế nào để đăng tin BĐS lên hệ thống?</span>
          <span class="faq-chevron">›</span>
        </div>
        <div class="faq-answer">
          <div class="faq-answer-step"><div class="faq-answer-step-num">1</div><div>Tap nút <strong>＋ Đăng tin</strong> ở giữa bottom bar</div></div>
          <div class="faq-answer-step"><div class="faq-answer-step-num">2</div><div>Điền đầy đủ 6 bước: Loại BĐS → Vị trí → Chi tiết → Giá → Upload ảnh → Xác nhận</div></div>
          <div class="faq-answer-step"><div class="faq-answer-step-num">3</div><div>Sau khi gửi, tin sẽ vào hàng chờ duyệt. Admin Đà Lạt BĐS sẽ xem xét trong <strong>vòng 24h</strong></div></div>
          <div class="faq-answer-step"><div class="faq-answer-step-num">4</div><div>Bạn nhận thông báo khi tin được duyệt hoặc yêu cầu bổ sung</div></div>
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span class="faq-q-icon">⏳</span>
          <span class="faq-q-text">Tại sao tin BĐS của tôi chưa được duyệt?</span>
          <span class="faq-chevron">›</span>
        </div>
        <div class="faq-answer">
          Thời gian duyệt thường trong 24h làm việc. Tin có thể bị delay nếu: thiếu ảnh thực tế, chưa upload giấy tờ pháp lý (sổ đỏ/hồng), thông tin không đầy đủ. Kiểm tra lại trong <a onclick="openSubpage('mybds');closeSubpage('support')">BĐS của tôi</a> để xem trạng thái và lý do.
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span class="faq-q-icon">✏️</span>
          <span class="faq-q-text">Tôi có thể chỉnh sửa tin sau khi đã đăng không?</span>
          <span class="faq-chevron">›</span>
        </div>
        <div class="faq-answer">
          Có. Vào <strong>BĐS của tôi</strong> → tap icon ✏️ trên card tin muốn sửa. Nếu tin đang được duyệt, chỉnh sửa sẽ cần duyệt lại. Nếu tin đang hiển thị, thay đổi nhỏ (giá, mô tả) được áp dụng ngay, thay đổi lớn (vị trí, diện tích) cần duyệt lại.
        </div>
      </div>

      <!-- FAQ: CRM -->
      <div class="faq-category-label">🤝 Chăm sóc khách hàng (CRM)</div>
      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span class="faq-q-icon">🎯</span>
          <span class="faq-q-text">Lead mới mà tôi không liên hệ thì sao?</span>
          <span class="faq-chevron">›</span>
        </div>
        <div class="faq-answer">
          Hệ thống sẽ <strong>tự động nhắc nhở sau 24h</strong> nếu Lead vẫn ở trạng thái "Mới". Sau 48h không xử lý, Lead có thể bị Admin thu hồi và assign cho Sale khác. Luôn cập nhật trạng thái dù chưa liên hệ được (VD: "Máy bận, sẽ gọi lại").
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span class="faq-q-icon">📅</span>
          <span class="faq-q-text">Tôi muốn dời lịch xem nhà thì làm thế nào?</span>
          <span class="faq-chevron">›</span>
        </div>
        <div class="faq-answer">
          Vào <strong>Lịch hẹn</strong> → tap booking cần dời → bấm <strong>"🔄 Dời lịch"</strong> → chọn ngày giờ mới → xác nhận. Hệ thống sẽ tự gửi thông báo cho tất cả bên: Khách hàng, Chủ nhà (nếu có) và bạn.
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span class="faq-q-icon">💰</span>
          <span class="faq-q-text">Tại sao tôi chưa thấy hoa hồng dù đã chốt?</span>
          <span class="faq-chevron">›</span>
        </div>
        <div class="faq-answer">
          Hoa hồng được tạo khi bạn bấm "Chốt Deal" và được <strong>Admin duyệt</strong>. Quy trình: Chốt → Admin duyệt → Chờ cọc → Đặt cọc → Công chứng → Hoàn tất. Kiểm tra trong <strong>Hoa hồng của tôi</strong> để xem trạng thái. Nếu chưa thấy gì, liên hệ Sale Admin qua chat.
        </div>
      </div>

      <!-- FAQ: Tài khoản -->
      <div class="faq-category-label">👤 Tài khoản & Bảo mật</div>
      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span class="faq-q-icon">🔐</span>
          <span class="faq-q-text">Hệ thống đăng nhập như thế nào?</span>
          <span class="faq-chevron">›</span>
        </div>
        <div class="faq-answer">
          Đà Lạt BĐS dùng <strong>Telegram làm phương thức xác thực duy nhất</strong> — không cần mật khẩu. Tài khoản Telegram của bạn được dùng làm định danh. Điều này đảm bảo an toàn và tiện lợi. Nếu mất quyền truy cập Telegram, liên hệ Admin để khôi phục.
        </div>
      </div>
      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span class="faq-q-icon">🏠</span>
          <span class="faq-q-text">Làm thế nào để trở thành eBroker?</span>
          <span class="faq-chevron">›</span>
        </div>
        <div class="faq-answer">
          <div class="faq-answer-step"><div class="faq-answer-step-num">1</div><div>Tap nút <strong>＋</strong> → "Đăng ký làm Broker" (với tài khoản Guest)</div></div>
          <div class="faq-answer-step"><div class="faq-answer-step-num">2</div><div>Điền thông tin: Họ tên, SĐT, Kinh nghiệm, Khu vực hoạt động</div></div>
          <div class="faq-answer-step"><div class="faq-answer-step-num">3</div><div>Upload: CMND/CCCD + Chứng chỉ môi giới BĐS (nếu có)</div></div>
          <div class="faq-answer-step"><div class="faq-answer-step-num">4</div><div>Admin xét duyệt trong 1–2 ngày làm việc</div></div>
        </div>
      </div>

      <!-- Gửi ticket -->
      <div class="ticket-form">
        <div class="ticket-form-title">📨 Gửi yêu cầu hỗ trợ</div>
        <select class="ticket-select">
          <option>Chọn loại vấn đề...</option>
          <option>BĐS / Tin đăng</option>
          <option>CRM / Lead / Deal</option>
          <option>Hoa hồng / Thanh toán</option>
          <option>Tài khoản</option>
          <option>Lỗi kỹ thuật</option>
          <option>Khác</option>
        </select>
        <input class="ticket-input" type="text" placeholder="Tiêu đề vấn đề...">
        <textarea class="ticket-textarea" placeholder="Mô tả chi tiết vấn đề bạn gặp phải..."></textarea>
        <button class="ticket-submit" onclick="showToast('✓ Đã gửi ticket! Chúng tôi phản hồi trong 4h làm việc.')">📨 Gửi yêu cầu</button>
      </div>

      <!-- App info -->
      <div class="form-section" style="margin-top:8px;">
        <div class="form-section-title">Thông tin ứng dụng</div>
        <div class="app-info-row"><span class="app-info-label">Phiên bản</span><span class="app-info-val">v2.4.1</span></div>
        <div class="app-info-row"><span class="app-info-label">Build</span><span class="app-info-val">March 2026</span></div>
        <div class="app-info-row" style="cursor:pointer;" onclick="showToast('Mở Điều khoản dịch vụ...')"><span class="app-info-label">Điều khoản dịch vụ</span><span class="app-info-val" style="color:var(--primary);">Xem →</span></div>
        <div class="app-info-row" style="cursor:pointer;" onclick="showToast('Mở Chính sách bảo mật...')"><span class="app-info-label">Chính sách bảo mật</span><span class="app-info-val" style="color:var(--primary);">Xem →</span></div>
        <div class="app-info-row" style="cursor:pointer;" onclick="showToast('Đánh giá trên App Store...')"><span class="app-info-label">Đánh giá ứng dụng</span><span class="app-info-val">⭐⭐⭐⭐⭐</span></div>
      </div>

      <div style="text-align:center;padding:20px 16px;color:var(--text-tertiary);font-size:11px;">
        © 2026 Đà Lạt BĐS · Phát triển bởi TREA Team<br>
        <span style="color:var(--primary);font-weight:600;cursor:pointer;" onclick="showToast('Mở Telegram cộng đồng...')">Tham gia cộng đồng Telegram →</span>
      </div>

    </div>
  </div><!-- end subpage-support -->

