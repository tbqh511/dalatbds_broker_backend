@php
  $hotline    = system_setting('company_tel1') ?: '';
  $appVersion = system_setting('system_version') ?: '1.0.0';
@endphp
<!-- ========== SUBPAGE: HỖ TRỢ & FAQ ========== -->
<div class="subpage" id="subpage-support">
  <div class="sp-header">
    <button class="sp-back" onclick="closeSubpage('support')"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
    <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Hỗ trợ & FAQ</span></div>
  </div>

  <div class="sp-scroll" style="padding-bottom:16px;">

    <!-- Hero -->
    <div class="faq-hero">
      <div class="faq-hero-title">Chúng tôi có thể giúp gì?</div>
      <div class="faq-hero-sub">Tìm câu trả lời hoặc liên hệ đội hỗ trợ Đà Lạt BĐS</div>
      <div class="faq-search-wrap">
        <span style="display:inline-flex;align-items:center;color:var(--text-tertiary);"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><line x1="15.5" y1="15.5" x2="21" y2="21"/></svg></span>
        <input id="faqSearchInput" type="text" placeholder="Tìm kiếm câu hỏi...">
      </div>
    </div>

    <!-- Quick contact -->
    <div class="contact-strip">
      <div class="contact-btn" onclick="showToast('Liên hệ Admin qua Telegram Bot')">
        <span class="contact-btn-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
        <span class="contact-btn-label">Telegram</span>
        <span class="contact-btn-sub">Phản hồi nhanh</span>
      </div>
      @if($hotline)
        <a class="contact-btn" href="tel:{{ $hotline }}" style="text-decoration:none;color:inherit;">
          <span class="contact-btn-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>
          <span class="contact-btn-label">Hotline</span>
          <span class="contact-btn-sub">{{ $hotline }}</span>
        </a>
      @else
        <div class="contact-btn" onclick="showToast('Chưa cấu hình hotline')">
          <span class="contact-btn-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.93a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg></span>
          <span class="contact-btn-label">Hotline</span>
          <span class="contact-btn-sub">8:00 – 21:00</span>
        </div>
      @endif
      <div class="contact-btn" onclick="showToast('Liên hệ Zalo OA Đà Lạt BĐS')">
        <span class="contact-btn-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>
        <span class="contact-btn-label">Zalo OA</span>
        <span class="contact-btn-sub">Zalo chính thức</span>
      </div>
    </div>

    <!-- FAQ: BĐS -->
    <div class="faq-category-label"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg> Bất động sản</span></div>
    <div class="faq-item">
      <div class="faq-question" onclick="toggleFaq(this)">
        <span class="faq-q-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></span>
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
        <span class="faq-q-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span>
        <span class="faq-q-text">Tôi có thể chỉnh sửa tin sau khi đã đăng không?</span>
        <span class="faq-chevron">›</span>
      </div>
      <div class="faq-answer">
        Có. Vào <strong>BĐS của tôi</strong> → tap icon <span style="display:inline-flex;align-items:center;vertical-align:middle;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></span> trên card tin muốn sửa. Nếu tin đang được duyệt, chỉnh sửa sẽ cần duyệt lại. Nếu tin đang hiển thị, thay đổi nhỏ (giá, mô tả) được áp dụng ngay, thay đổi lớn (vị trí, diện tích) cần duyệt lại.
      </div>
    </div>

    <!-- FAQ: CRM -->
    <div class="faq-category-label"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Chăm sóc khách hàng (CRM)</span></div>
    <div class="faq-item">
      <div class="faq-question" onclick="toggleFaq(this)">
        <span class="faq-q-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
        <span class="faq-q-text">Lead mới mà tôi không liên hệ thì sao?</span>
        <span class="faq-chevron">›</span>
      </div>
      <div class="faq-answer">
        Hệ thống sẽ <strong>tự động nhắc nhở sau 24h</strong> nếu Lead vẫn ở trạng thái "Mới". Sau 48h không xử lý, Lead có thể bị Admin thu hồi và assign cho Sale khác. Luôn cập nhật trạng thái dù chưa liên hệ được (VD: "Máy bận, sẽ gọi lại").
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question" onclick="toggleFaq(this)">
        <span class="faq-q-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
        <span class="faq-q-text">Tôi muốn dời lịch xem nhà thì làm thế nào?</span>
        <span class="faq-chevron">›</span>
      </div>
      <div class="faq-answer">
        Vào <strong>Lịch hẹn</strong> → tap booking cần dời → bấm <strong>"<span style="display:inline-flex;align-items:center;vertical-align:middle;gap:3px;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Dời lịch</span>"</strong> → chọn ngày giờ mới → xác nhận. Hệ thống sẽ tự gửi thông báo cho tất cả bên: Khách hàng, Chủ nhà (nếu có) và bạn.
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question" onclick="toggleFaq(this)">
        <span class="faq-q-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
        <span class="faq-q-text">Tại sao tôi chưa thấy hoa hồng dù đã chốt?</span>
        <span class="faq-chevron">›</span>
      </div>
      <div class="faq-answer">
        Hoa hồng được tạo khi bạn bấm "Chốt Deal" và được <strong>Admin duyệt</strong>. Quy trình: Chốt → Admin duyệt → Chờ cọc → Đặt cọc → Công chứng → Hoàn tất. Kiểm tra trong <strong>Hoa hồng của tôi</strong> để xem trạng thái. Nếu chưa thấy gì, liên hệ Sale Admin qua chat.
      </div>
    </div>

    <!-- FAQ: Tài khoản -->
    <div class="faq-category-label"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> Tài khoản & Bảo mật</span></div>
    <div class="faq-item">
      <div class="faq-question" onclick="toggleFaq(this)">
        <span class="faq-q-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
        <span class="faq-q-text">Hệ thống đăng nhập như thế nào?</span>
        <span class="faq-chevron">›</span>
      </div>
      <div class="faq-answer">
        Đà Lạt BĐS dùng <strong>Telegram làm phương thức xác thực duy nhất</strong> — không cần mật khẩu. Tài khoản Telegram của bạn được dùng làm định danh. Điều này đảm bảo an toàn và tiện lợi. Nếu mất quyền truy cập Telegram, liên hệ Admin để khôi phục.
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question" onclick="toggleFaq(this)">
        <span class="faq-q-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10.5z"/><path d="M9 22V12h6v10"/></svg></span>
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
      <div class="ticket-form-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> Gửi yêu cầu hỗ trợ</span></div>

      <div>
        <select id="ticketCategory" class="ticket-select" onchange="spClearErr('ticketCategory','errCategory')">
          <option value="">Chọn loại vấn đề...</option>
          <option>BĐS / Tin đăng</option>
          <option>CRM / Lead / Deal</option>
          <option>Hoa hồng / Thanh toán</option>
          <option>Tài khoản</option>
          <option>Lỗi kỹ thuật</option>
          <option>Khác</option>
        </select>
        <p id="errCategory" style="display:none;margin:4px 0 0;font-size:12px;color:#dc2626;padding:0 2px;">Vui lòng chọn loại vấn đề.</p>
      </div>

      <div style="margin-top:8px;">
        <input id="ticketSubject" class="ticket-input" type="text" maxlength="255" placeholder="Tiêu đề vấn đề..." oninput="spClearErr('ticketSubject','errSubject')">
        <p id="errSubject" style="display:none;margin:4px 0 0;font-size:12px;color:#dc2626;padding:0 2px;">Vui lòng nhập tiêu đề vấn đề.</p>
      </div>

      <div style="margin-top:8px;">
        <textarea id="ticketMessage" class="ticket-textarea" maxlength="2000" placeholder="Mô tả chi tiết vấn đề bạn gặp phải..." oninput="spClearErr('ticketMessage','errMessage');spUpdateCount(this)"></textarea>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;padding:0 2px;">
          <p id="errMessage" style="display:none;font-size:12px;color:#dc2626;margin:0;">Vui lòng mô tả chi tiết vấn đề.</p>
          <span id="ticketMsgCount" style="font-size:11px;color:var(--text-tertiary);margin-left:auto;">0 / 2000</span>
        </div>
      </div>

      <button id="ticketSubmitBtn" class="ticket-submit" style="margin-top:12px;" onclick="spSubmitTicket()"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> Gửi yêu cầu</span></button>
    </div>

    <!-- App info -->
    <div class="form-section" style="margin-top:8px;">
      <div class="form-section-title">Thông tin ứng dụng</div>
      <div class="app-info-row"><span class="app-info-label">Phiên bản</span><span class="app-info-val">v{{ $appVersion }}</span></div>
      <div class="app-info-row"><span class="app-info-label">Build</span><span class="app-info-val">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span></div>
      <div class="app-info-row" style="cursor:pointer;" onclick="showToast('Mở Điều khoản dịch vụ...')"><span class="app-info-label">Điều khoản dịch vụ</span><span class="app-info-val" style="color:var(--primary);">Xem →</span></div>
      <div class="app-info-row" style="cursor:pointer;" onclick="showToast('Mở Chính sách bảo mật...')"><span class="app-info-label">Chính sách bảo mật</span><span class="app-info-val" style="color:var(--primary);">Xem →</span></div>
      <div class="app-info-row" style="cursor:pointer;" onclick="showToast('Đánh giá trên App Store...')"><span class="app-info-label">Đánh giá ứng dụng</span><span class="app-info-val" style="display:inline-flex;align-items:center;gap:1px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      </span></div>
    </div>

    <div style="text-align:center;padding:20px 16px;color:var(--text-tertiary);font-size:11px;">
      © {{ date('Y') }} Đà Lạt BĐS · Phát triển bởi TREA Team<br>
      <span style="color:var(--primary);font-weight:600;cursor:pointer;" onclick="showToast('Mở Telegram cộng đồng...')">Tham gia cộng đồng Telegram →</span>
    </div>

  </div>
</div><!-- end subpage-support -->

<script>
// FAQ Search
(function() {
  var input = document.getElementById('faqSearchInput');
  if (!input) return;
  input.addEventListener('input', function() {
    var q = this.value.trim().toLowerCase();
    var items  = document.querySelectorAll('#subpage-support .faq-item');
    var labels = document.querySelectorAll('#subpage-support .faq-category-label');
    if (!q) {
      items.forEach(function(el)  { el.style.display = ''; });
      labels.forEach(function(el) { el.style.display = ''; });
      return;
    }
    items.forEach(function(el) {
      var text = (el.querySelector('.faq-q-text') || {}).textContent || '';
      var ans  = (el.querySelector('.faq-answer')  || {}).textContent || '';
      el.style.display = (text + ans).toLowerCase().includes(q) ? '' : 'none';
    });
    labels.forEach(function(label) {
      var next = label.nextElementSibling;
      var hasVisible = false;
      while (next && next.classList.contains('faq-item')) {
        if (next.style.display !== 'none') { hasVisible = true; break; }
        next = next.nextElementSibling;
      }
      label.style.display = hasVisible ? '' : 'none';
    });
  });
})();

// Ticket helpers
function spSetErr(fieldId, errId, msg) {
  var field = document.getElementById(fieldId);
  var err   = document.getElementById(errId);
  if (field) field.style.outline = '1.5px solid #dc2626';
  if (err)   { err.textContent = msg || err.textContent; err.style.display = 'block'; }
}
function spClearErr(fieldId, errId) {
  var field = document.getElementById(fieldId);
  var err   = document.getElementById(errId);
  if (field) field.style.outline = '';
  if (err)   err.style.display = 'none';
}
function spUpdateCount(textarea) {
  var el = document.getElementById('ticketMsgCount');
  if (el) el.textContent = textarea.value.length + ' / 2000';
}

// Ticket submission
function spSubmitTicket() {
  var category = document.getElementById('ticketCategory').value;
  var subject  = document.getElementById('ticketSubject').value.trim();
  var message  = document.getElementById('ticketMessage').value.trim();
  var btn      = document.getElementById('ticketSubmitBtn');

  // Inline validation — show all errors at once
  var valid = true;
  if (!category) { spSetErr('ticketCategory', 'errCategory'); valid = false; } else { spClearErr('ticketCategory', 'errCategory'); }
  if (!subject)  { spSetErr('ticketSubject',  'errSubject');  valid = false; } else { spClearErr('ticketSubject',  'errSubject'); }
  if (!message)  { spSetErr('ticketMessage',  'errMessage');  valid = false; } else { spClearErr('ticketMessage',  'errMessage'); }
  if (!valid) return;

  btn.disabled = true;
  btn.textContent = 'Đang gửi...';
  fetch(window.WEBAPP_CONFIG.routes.supportTicket, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({ category: category, subject: subject, message: message })
  })
  .then(function(r) { return r.json(); })
  .then(function(data) {
    btn.disabled = false;
    btn.innerHTML = '<span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> Gửi yêu cầu</span>';
    if (data.success) {
      showToast('✓ Đã gửi! Chúng tôi phản hồi trong 4h làm việc.');
      document.getElementById('ticketCategory').value = '';
      document.getElementById('ticketSubject').value = '';
      document.getElementById('ticketMessage').value = '';
      var cnt = document.getElementById('ticketMsgCount');
      if (cnt) cnt.textContent = '0 / 2000';
    } else {
      showToast(data.message || 'Lỗi gửi yêu cầu, thử lại!');
    }
  })
  .catch(function() {
    btn.disabled = false;
    btn.innerHTML = '<span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> Gửi yêu cầu</span>';
    showToast('Lỗi kết nối, thử lại!');
  });
}
</script>
