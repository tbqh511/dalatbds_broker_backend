  <!-- ========== SUBPAGE: CHỈNH SỬA HỒ SƠ ========== -->
  <div class="subpage" id="subpage-editprofile">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('editprofile')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><line x1="7" y1="8" x2="7.01" y2="8"/><line x1="11" y1="8" x2="17" y2="8"/><line x1="7" y1="11" x2="7.01" y2="11"/><line x1="11" y1="11" x2="17" y2="11"/></svg> Chỉnh sửa hồ sơ</span></div>
    </div>

    <div class="sp-scroll" style="padding-bottom:80px;">

      <!-- Avatar -->
      <div class="profile-edit-avatar">
        <div class="pea-circle">
          HT
          <div class="pea-edit-btn" onclick="showToast('Chọn ảnh từ thư viện...')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
        </div>
        <div class="pea-name">Huy Thái</div>
        <div class="pea-role">
          <span class="badge badge-blue" style="font-size:10px;display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg> Sale</span>
          <span>Đà Lạt BĐS</span>
        </div>
        <div class="pea-change-photo" onclick="showToast('Chọn ảnh từ thư viện...')"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg> Đổi ảnh đại diện</span></div>
      </div>

      <!-- Thông tin cơ bản -->
      <div class="form-section">
        <div class="form-section-title">Thông tin cá nhân</div>
        <div class="form-row">
          <span class="form-row-label">Họ và tên</span>
          <input class="form-row-value" type="text" value="Huy Thái" placeholder="Nhập họ tên...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Số điện thoại</span>
          <input class="form-row-value" type="tel" value="0901.234.567" placeholder="SĐT...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Email</span>
          <input class="form-row-value" type="email" value="huythai@gmail.com" placeholder="Email...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Zalo</span>
          <input class="form-row-value" type="text" value="0901.234.567" placeholder="SĐT Zalo...">
        </div>
        <div class="form-row" style="align-items:flex-start;">
          <span class="form-row-label" style="padding-top:2px;">Giới thiệu</span>
          <textarea class="form-row-value" placeholder="Mô tả ngắn về bạn và kinh nghiệm BĐS..." rows="3">Sale BĐS chuyên khu vực Đà Lạt, 3+ năm kinh nghiệm. Tư vấn tận tâm, minh bạch.</textarea>
        </div>
      </div>

      <!-- Thông tin nghề nghiệp -->
      <div class="form-section">
        <div class="form-section-title">Nghề nghiệp & Khu vực</div>
        <div class="form-row">
          <span class="form-row-label">Kinh nghiệm</span>
          <input class="form-row-value" type="text" value="3 năm" placeholder="Số năm kinh nghiệm...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Khu vực</span>
          <input class="form-row-value" type="text" value="P.Cam Ly, P.Lâm Viên, P.1" placeholder="Khu vực hoạt động...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Chuyên về</span>
          <input class="form-row-value" type="text" value="Đất ở, Biệt thự" placeholder="Loại BĐS chuyên...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Facebook</span>
          <input class="form-row-value" type="text" value="" placeholder="Link Facebook cá nhân...">
          <span class="form-row-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></span>
        </div>
      </div>

      <!-- Chứng chỉ & Giấy tờ -->
      <div class="form-section">
        <div class="form-section-title">Giấy tờ & Chứng chỉ</div>
        <div class="cert-item">
          <div class="cert-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><line x1="7" y1="8" x2="7.01" y2="8"/><line x1="11" y1="8" x2="17" y2="8"/><line x1="7" y1="11" x2="7.01" y2="11"/><line x1="11" y1="11" x2="17" y2="11"/></svg></div>
          <div class="cert-info">
            <div class="cert-name">CMND / CCCD</div>
            <div class="cert-status" style="color:var(--success);">✓ Đã xác minh</div>
          </div>
          <span class="cert-action" onclick="showToast('Xem ảnh CMND...')">Xem</span>
        </div>
        <div class="cert-item">
          <div class="cert-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></div>
          <div class="cert-info">
            <div class="cert-name">Chứng chỉ môi giới BĐS</div>
            <div class="cert-status" style="color:var(--success);">✓ Hợp lệ · Hết hạn 12/2026</div>
          </div>
          <span class="cert-action" onclick="showToast('Xem chứng chỉ...')">Xem</span>
        </div>
        <div class="cert-item">
          <div class="cert-icon" style="border:1.5px dashed var(--border);"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
          <div class="cert-info">
            <div class="cert-name">Ảnh thực địa / Portfolio</div>
            <div class="cert-status" style="color:var(--text-tertiary);">Chưa upload</div>
          </div>
          <span class="cert-action" onclick="showToast('Chọn ảnh upload...')">Upload</span>
        </div>
      </div>

      <!-- Đánh giá -->
      <div class="form-section">
        <div class="form-section-title">Đánh giá từ khách hàng (32 đánh giá)</div>
        <div style="display:flex;align-items:center;gap:14px;padding:14px 16px;border-bottom:1px solid var(--border-light);">
          <div style="text-align:center;">
            <div style="font-size:36px;font-weight:800;color:var(--text-primary);line-height:1;">4.8</div>
            <div class="rating-stars" style="justify-content:center;margin-top:4px;">
              <span class="star">★</span><span class="star">★</span><span class="star">★</span>
              <span class="star">★</span><span class="star" style="color:#fbbf24;opacity:.5;">★</span>
            </div>
            <div style="font-size:11px;color:var(--text-tertiary);margin-top:3px;">32 đánh giá</div>
          </div>
          <div style="flex:1;display:flex;flex-direction:column;gap:5px;">
            <div style="display:flex;align-items:center;gap:6px;font-size:11px;">
              <span style="min-width:12px;color:var(--text-tertiary);">5★</span>
              <div style="flex:1;height:5px;background:var(--border);border-radius:3px;overflow:hidden;"><div style="width:75%;height:100%;background:#fbbf24;border-radius:3px;"></div></div>
              <span style="min-width:14px;text-align:right;color:var(--text-tertiary);">24</span>
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:11px;">
              <span style="min-width:12px;color:var(--text-tertiary);">4★</span>
              <div style="flex:1;height:5px;background:var(--border);border-radius:3px;overflow:hidden;"><div style="width:18%;height:100%;background:#fbbf24;border-radius:3px;"></div></div>
              <span style="min-width:14px;text-align:right;color:var(--text-tertiary);">6</span>
            </div>
            <div style="display:flex;align-items:center;gap:6px;font-size:11px;">
              <span style="min-width:12px;color:var(--text-tertiary);">3★</span>
              <div style="flex:1;height:5px;background:var(--border);border-radius:3px;overflow:hidden;"><div style="width:6%;height:100%;background:#fbbf24;border-radius:3px;"></div></div>
              <span style="min-width:14px;text-align:right;color:var(--text-tertiary);">2</span>
            </div>
          </div>
        </div>
        <div class="review-item">
          <div class="review-head">
            <div class="review-avatar">MT</div>
            <div class="review-name">Anh Minh Tuấn</div>
            <div class="rating-stars"><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span></div>
            <div class="review-date">14/03/2026</div>
          </div>
          <div class="review-text">Huy Thái tư vấn rất nhiệt tình, kiên nhẫn, hiểu rõ nhu cầu của mình. Tìm được đúng lô đất mình muốn sau 2 tuần. Rất hài lòng!</div>
        </div>
        <div class="review-item">
          <div class="review-head">
            <div class="review-avatar" style="background:var(--purple-light);color:var(--purple);">TH</div>
            <div class="review-name">Chị Thu Hà</div>
            <div class="rating-stars"><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star" style="color:#fbbf24;opacity:.4;">★</span></div>
            <div class="review-date">10/03/2026</div>
          </div>
          <div class="review-text">Sale nắm rõ thị trường, tư vấn thẳng thắn ưu/nhược điểm từng lô đất. Hỗ trợ tốt từ đầu đến khi công chứng xong.</div>
        </div>
        <div style="padding:10px 16px;text-align:center;">
          <button style="font-size:12px;color:var(--primary);font-weight:600;background:none;border:none;cursor:pointer;" onclick="showToast('Xem tất cả đánh giá...')">Xem tất cả 32 đánh giá →</button>
        </div>
      </div>

      <!-- Tài khoản Telegram -->
      <div class="form-section">
        <div class="form-section-title">Tài khoản Telegram</div>
        <div class="form-row">
          <span class="form-row-label">Username</span>
          <span style="flex:1;text-align:right;font-size:13px;font-weight:500;color:var(--text-primary);">@huythai_dalatbds</span>
          <span class="form-row-icon">✓</span>
        </div>
        <div class="form-row">
          <span class="form-row-label">Telegram ID</span>
          <span style="flex:1;text-align:right;font-size:13px;color:var(--text-tertiary);">123456789</span>
        </div>
        <div style="padding:10px 16px;background:var(--success-light);display:flex;gap:8px;align-items:center;">
          <span style="font-size:16px;">✓</span>
          <span style="font-size:12px;color:var(--success);">Tài khoản đã được xác thực qua Telegram WebApp</span>
        </div>
      </div>

      <div style="height:16px;"></div>
    </div>

    <!-- Save bar -->
    <div class="sticky-save">
      <button class="cancel-btn" onclick="closeSubpage('editprofile')">Hủy</button>
      <button class="save-btn" onclick="showToast('✓ Đã lưu thông tin hồ sơ!')">✓ Lưu thay đổi</button>
    </div>
  </div><!-- end subpage-editprofile -->


