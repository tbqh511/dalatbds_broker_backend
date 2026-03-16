  <!-- ========== SUBPAGE: KHÁCH CỦA TÔI ========== -->
  <div class="subpage" id="subpage-mycustomers">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('mycustomers')">←</button>
      <div class="sp-title">👥 Khách của tôi</div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="openSheet()">＋</button>
        <button class="sp-action-btn">⋮</button>
      </div>
    </div>

    <!-- Summary strip -->
    <div class="sp-summary">
      <div class="sp-sum-item">
        <div class="sp-sum-val" style="color:var(--danger);">3</div>
        <div class="sp-sum-lbl">Lead mới</div>
      </div>
      <div class="sp-sum-item">
        <div class="sp-sum-val" style="color:var(--primary);">7</div>
        <div class="sp-sum-lbl">Đang chăm</div>
      </div>
      <div class="sp-sum-item">
        <div class="sp-sum-val" style="color:var(--purple);">3</div>
        <div class="sp-sum-lbl">Deal active</div>
      </div>
      <div class="sp-sum-item">
        <div class="sp-sum-val" style="color:var(--success);">12</div>
        <div class="sp-sum-lbl">Đã chốt</div>
      </div>
    </div>

    <!-- Search bar -->
    <div class="sp-searchbar">
      <div class="sp-search-input">
        <span style="font-size:15px;color:var(--text-tertiary);">🔍</span>
        <input type="text" placeholder="Tên, số điện thoại...">
      </div>
      <button class="sp-filter-btn">⚙️ Lọc</button>
    </div>

    <!-- Status tabs -->
    <div class="sp-tabs">
      <button class="sp-tab active" onclick="spTabSwitch(this)">Tất cả (10)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Lead mới (3)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đã liên hệ (4)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đang Deal (3)</button>
      <button class="sp-tab" onclick="spTabSwitch(this)">Đã chốt (12)</button>
    </div>

    <div class="sp-scroll">

      <!-- CUSTOMER 1 — Lead mới, chưa contact -->
      <div class="cust-card">
        <div class="cust-header">
          <div class="cust-avatar" style="background:#ef4444;">NT</div>
          <div class="cust-info">
            <div class="cust-name">Nguyễn Văn Tuấn</div>
            <div class="cust-meta">
              <span>📞 0912.345.678</span>
              <span style="color:var(--danger);font-weight:600;">● 2 giờ trước</span>
            </div>
          </div>
          <div class="cust-status-badge">
            <span class="badge badge-red">🔴 Lead mới</span>
          </div>
        </div>
        <div class="cust-body">
          <div class="cust-row">
            <span class="cust-row-label">Loại BĐS</span>
            <span class="cust-row-val">Biệt thự, Đất ở</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Nhu cầu</span>
            <span class="cust-row-val">Mua để ở + đầu tư</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Ngân sách</span>
            <span class="cust-row-val" style="color:var(--success);font-weight:600;">3 tỷ – 5 tỷ</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Khu vực</span>
            <span class="cust-row-val">P.Lâm Viên, P.Cam Ly</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Nguồn</span>
            <span class="cust-row-val">Facebook Ads</span>
          </div>
          <div class="cust-tags">
            <span class="cust-tag">Biệt thự</span>
            <span class="cust-tag">3–5 tỷ</span>
            <span class="cust-tag">Lâm Viên</span>
            <span class="cust-tag">Đầu tư</span>
          </div>
        </div>
        <!-- Lead flow -->
        <div class="lead-flow">
          <div class="lf-step">
            <div class="lf-dot active">1</div>
            <div class="lf-label active">Lead mới</div>
          </div>
          <div class="lf-line"></div>
          <div class="lf-step">
            <div class="lf-dot">2</div>
            <div class="lf-label">Đã liên hệ</div>
          </div>
          <div class="lf-line"></div>
          <div class="lf-step">
            <div class="lf-dot">3</div>
            <div class="lf-label">Tạo Deal</div>
          </div>
          <div class="lf-line"></div>
          <div class="lf-step">
            <div class="lf-dot">4</div>
            <div class="lf-label">Chăm sóc</div>
          </div>
          <div class="lf-line"></div>
          <div class="lf-step">
            <div class="lf-dot">5</div>
            <div class="lf-label">Chốt</div>
          </div>
        </div>
        <div class="cust-footer">
          <div class="cust-date">📅 Nhận 15/03/2026 · <span style="color:var(--danger);font-weight:600;">Chưa liên hệ!</span></div>
          <div class="cust-actions">
            <div class="cust-btn" title="Gọi" onclick="showToast('Đang gọi...')">📞</div>
            <div class="cust-btn" title="Nhắn tin" onclick="showToast('Mở chat Zalo...')">💬</div>
            <div class="cust-btn primary" title="Xác nhận & Tạo Deal" onclick="toggleCustDetail('cust1-detail')">▼ Chi tiết</div>
          </div>
        </div>
        <div class="cust-detail-panel" id="cust1-detail">
          <div class="cdp-note">
            ⚠️ Lead chưa được liên hệ sau 2 giờ. Hệ thống sẽ cảnh báo sau 24h nếu chưa xử lý.
          </div>
          <div style="display:flex;gap:8px;margin-top:12px;">
            <button style="flex:1;padding:10px;background:var(--primary);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;" onclick="showToast('✓ Đã xác nhận lead')">✓ Xác nhận đã liên hệ</button>
            <button style="flex:1;padding:10px;background:var(--success);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;" onclick="showToast('✓ Đang tạo Deal...')">🤝 Tạo Deal ngay</button>
          </div>
        </div>
      </div>

      <!-- CUSTOMER 2 — Đang Deal, chăm sóc -->
      <div class="cust-card">
        <div class="cust-header">
          <div class="cust-avatar" style="background:var(--primary);">MT</div>
          <div class="cust-info">
            <div class="cust-name">Anh Minh Tuấn</div>
            <div class="cust-meta">
              <span>📞 0901.234.567</span>
              <span>· Hôm nay 09:00</span>
            </div>
          </div>
          <div class="cust-status-badge">
            <span class="badge badge-purple">🤝 Đang Deal</span>
          </div>
        </div>
        <div class="cust-body">
          <div class="cust-row">
            <span class="cust-row-label">Loại BĐS</span>
            <span class="cust-row-val">Đất ở phân quyền</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Ngân sách</span>
            <span class="cust-row-val" style="color:var(--success);font-weight:600;">800tr – 1.2 tỷ</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Khu vực</span>
            <span class="cust-row-val">P.Cam Ly, Đường Yersin</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">BĐS đã gửi</span>
            <span class="cust-row-val">3 BĐS · 1 ưng ý</span>
          </div>
          <div class="cust-tags">
            <span class="cust-tag">Đất ở</span>
            <span class="cust-tag">Cam Ly</span>
            <span class="cust-tag" style="background:var(--success-light);color:var(--success);">Có lịch xem</span>
          </div>
        </div>
        <div class="lead-flow">
          <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Lead mới</div></div>
          <div class="lf-line done"></div>
          <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Đã liên hệ</div></div>
          <div class="lf-line done"></div>
          <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Tạo Deal</div></div>
          <div class="lf-line done"></div>
          <div class="lf-step"><div class="lf-dot active">4</div><div class="lf-label active">Chăm sóc</div></div>
          <div class="lf-line"></div>
          <div class="lf-step"><div class="lf-dot">5</div><div class="lf-label">Chốt</div></div>
        </div>
        <div class="cust-footer">
          <div class="cust-date">📅 Deal từ 10/03 · <span style="color:var(--primary);">Lịch xem: 16/03 09:00</span></div>
          <div class="cust-actions">
            <div class="cust-btn" onclick="showToast('Đang gọi...')">📞</div>
            <div class="cust-btn" onclick="showToast('Mở chat...')">💬</div>
            <div class="cust-btn primary" onclick="toggleCustDetail('cust2-detail')">▼ Chi tiết</div>
          </div>
        </div>
        <div class="cust-detail-panel" id="cust2-detail">
          <div class="cdp-section-title">BĐS đã gửi cho khách</div>
          <div class="cdp-bds-item" onclick="openDetail({title:'Đất ở Đường Yersin',price:'1,000 triệu',type:'Đất ở',area:'250 m²',addr:'P.Cam Ly',room:'—',slide:0})">
            <div class="cdp-bds-thumb gs1">🏡</div>
            <div class="cdp-bds-info">
              <div class="cdp-bds-title">Đất Đường Yersin, Cam Ly</div>
              <div class="cdp-bds-meta">1,000 triệu · 250 m²</div>
            </div>
            <span class="cdp-bds-status" style="background:var(--success-light);color:var(--success);">Ưng ý ❤️</span>
          </div>
          <div class="cdp-bds-item" onclick="openDetail({title:'Nhà phố Trần Phú',price:'2,800 triệu',type:'Nhà phố',area:'120 m²',addr:'P.1',room:'4 PN',slide:2})">
            <div class="cdp-bds-thumb gs2">🏠</div>
            <div class="cdp-bds-info">
              <div class="cdp-bds-title">Nhà phố Trần Phú</div>
              <div class="cdp-bds-meta">2,800 triệu · 120 m²</div>
            </div>
            <span class="cdp-bds-status" style="background:var(--danger-light);color:var(--danger);">Không ưng ✗</span>
          </div>
          <div class="cdp-bds-item">
            <div class="cdp-bds-thumb gs3">🌿</div>
            <div class="cdp-bds-info">
              <div class="cdp-bds-title">Đất nền Lâm Viên</div>
              <div class="cdp-bds-meta">650 triệu · 180 m²</div>
            </div>
            <span class="cdp-bds-status" style="background:var(--primary-light);color:var(--primary);">Đã gửi</span>
          </div>
          <button style="width:100%;margin-top:8px;padding:10px;background:var(--primary-light);color:var(--primary);border:1px dashed var(--primary);border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;" onclick="openSendModal();closeSubpage('mycustomers')">＋ Gửi thêm BĐS</button>

          <div class="cdp-section-title" style="margin-top:14px;">Lịch sử hoạt động</div>
          <div class="cdp-timeline">
            <div class="cdp-tl-item">
              <div class="cdp-tl-dot" style="background:var(--primary);"></div>
              <div class="cdp-tl-text">Đặt lịch xem nhà — Đất Yersin lúc 09:00 ngày 16/03</div>
              <div class="cdp-tl-time">Hôm nay</div>
            </div>
            <div class="cdp-tl-item">
              <div class="cdp-tl-dot" style="background:var(--danger);"></div>
              <div class="cdp-tl-text">Khách không ưng BĐS Nhà phố Trần Phú — "Hẻm nhỏ, xa chợ"</div>
              <div class="cdp-tl-time">13/03</div>
            </div>
            <div class="cdp-tl-item">
              <div class="cdp-tl-dot" style="background:var(--success);"></div>
              <div class="cdp-tl-text">Khách ưng BĐS Đất Yersin · Đang cân nhắc giá</div>
              <div class="cdp-tl-time">11/03</div>
            </div>
            <div class="cdp-tl-item">
              <div class="cdp-tl-dot"></div>
              <div class="cdp-tl-text">Gửi 2 BĐS đầu tiên cho khách qua Telegram</div>
              <div class="cdp-tl-time">10/03</div>
            </div>
          </div>
        </div>
      </div>

      <!-- CUSTOMER 3 — Đang thương lượng -->
      <div class="cust-card">
        <div class="cust-header">
          <div class="cust-avatar" style="background:var(--purple);">TH</div>
          <div class="cust-info">
            <div class="cust-name">Chị Thu Hà</div>
            <div class="cust-meta">
              <span>📞 0978.654.321</span>
              <span>· 1 ngày trước</span>
            </div>
          </div>
          <div class="cust-status-badge">
            <span class="badge badge-amber">💬 Thương lượng</span>
          </div>
        </div>
        <div class="cust-body">
          <div class="cust-row">
            <span class="cust-row-label">Loại BĐS</span>
            <span class="cust-row-val">Biệt thự, Nhà phố</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Ngân sách</span>
            <span class="cust-row-val" style="color:var(--success);font-weight:600;">5 tỷ – 8 tỷ</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Trạng thái</span>
            <span class="cust-row-val" style="color:var(--warning);font-weight:600;">Đang ép giá BT Cầu Đất</span>
          </div>
          <div class="cust-tags">
            <span class="cust-tag" style="background:var(--warning-light);color:var(--warning);">Đang thương lượng</span>
            <span class="cust-tag">Biệt thự</span>
            <span class="cust-tag">View đẹp</span>
          </div>
        </div>
        <div class="lead-flow">
          <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Lead mới</div></div>
          <div class="lf-line done"></div>
          <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Đã liên hệ</div></div>
          <div class="lf-line done"></div>
          <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Tạo Deal</div></div>
          <div class="lf-line done"></div>
          <div class="lf-step"><div class="lf-dot done">✓</div><div class="lf-label done">Xem nhà</div></div>
          <div class="lf-line"></div>
          <div class="lf-step"><div class="lf-dot active">⚡</div><div class="lf-label active">Thương lượng</div></div>
        </div>
        <div class="cust-footer">
          <div class="cust-date">📅 Xem nhà 14/03 · <span style="color:var(--warning);">Chờ phản hồi giá</span></div>
          <div class="cust-actions">
            <div class="cust-btn" onclick="showToast('Đang gọi...')">📞</div>
            <div class="cust-btn" onclick="showToast('Mở chat...')">💬</div>
            <div class="cust-btn primary" onclick="toggleCustDetail('cust3-detail')">▼ Chi tiết</div>
          </div>
        </div>
        <div class="cust-detail-panel" id="cust3-detail">
          <div class="cdp-section-title">BĐS đang thương lượng</div>
          <div class="cdp-bds-item" onclick="openDetail({title:'Biệt thự View Đồi Chè Cầu Đất',price:'8,500 triệu',type:'Biệt thự',area:'580 m²',addr:'Xuân Trường',room:'5 PN',slide:1})">
            <div class="cdp-bds-thumb gs2">🏡</div>
            <div class="cdp-bds-info">
              <div class="cdp-bds-title">Biệt thự View Đồi Chè Cầu Đất</div>
              <div class="cdp-bds-meta">8,500 triệu · 580 m²</div>
            </div>
            <span class="cdp-bds-status" style="background:var(--warning-light);color:var(--warning);">Thương lượng</span>
          </div>
          <div class="cdp-note">
            💬 Khách đề nghị 7,800 triệu. Chủ nhà đồng ý tối thiểu 8,000 triệu. Cần thuyết phục chênh lệch 200 triệu.
          </div>
          <div style="display:flex;gap:8px;margin-top:12px;">
            <button style="flex:1;padding:10px;background:var(--warning);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;" onclick="showToast('Cập nhật giá thương lượng')">📝 Cập nhật</button>
            <button style="flex:1;padding:10px;background:var(--success);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;" onclick="showToast('🎉 Đã tạo commission!')">🎉 Chốt Deal!</button>
          </div>
        </div>
      </div>

      <!-- CUSTOMER 4 — Lead mới -->
      <div class="cust-card">
        <div class="cust-header">
          <div class="cust-avatar" style="background:var(--teal);">BT</div>
          <div class="cust-info">
            <div class="cust-name">Anh Bảo Trâm</div>
            <div class="cust-meta">
              <span>📞 0966.111.222</span>
              <span style="color:var(--warning);font-weight:600;">● 6 giờ trước</span>
            </div>
          </div>
          <div class="cust-status-badge">
            <span class="badge badge-red">🔴 Lead mới</span>
          </div>
        </div>
        <div class="cust-body">
          <div class="cust-row">
            <span class="cust-row-label">Loại BĐS</span>
            <span class="cust-row-val">Khách sạn mini, Nhà phố</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Ngân sách</span>
            <span class="cust-row-val" style="color:var(--success);font-weight:600;">3 tỷ – 5 tỷ</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Nguồn</span>
            <span class="cust-row-val">Zalo OA</span>
          </div>
          <div class="cust-tags">
            <span class="cust-tag">Khách sạn</span>
            <span class="cust-tag">3–5 tỷ</span>
          </div>
        </div>
        <div class="lead-flow">
          <div class="lf-step"><div class="lf-dot active">1</div><div class="lf-label active">Lead mới</div></div>
          <div class="lf-line"></div>
          <div class="lf-step"><div class="lf-dot">2</div><div class="lf-label">Đã liên hệ</div></div>
          <div class="lf-line"></div>
          <div class="lf-step"><div class="lf-dot">3</div><div class="lf-label">Tạo Deal</div></div>
          <div class="lf-line"></div>
          <div class="lf-step"><div class="lf-dot">4</div><div class="lf-label">Chăm sóc</div></div>
          <div class="lf-line"></div>
          <div class="lf-step"><div class="lf-dot">5</div><div class="lf-label">Chốt</div></div>
        </div>
        <div class="cust-footer">
          <div class="cust-date">📅 Nhận 15/03/2026</div>
          <div class="cust-actions">
            <div class="cust-btn" onclick="showToast('Đang gọi...')">📞</div>
            <div class="cust-btn" onclick="showToast('Mở chat...')">💬</div>
            <div class="cust-btn success" onclick="showToast('✓ Đã tạo Deal')">🤝 Deal</div>
          </div>
        </div>
      </div>

      <!-- CUSTOMER 5 — Đã chốt -->
      <div class="cust-card" style="border-color:var(--success);border-left:3px solid var(--success);">
        <div class="cust-header">
          <div class="cust-avatar" style="background:var(--success);">LH</div>
          <div class="cust-info">
            <div class="cust-name">Chị Lan Hương</div>
            <div class="cust-meta">
              <span>📞 0944.888.999</span>
              <span>· 05/03/2026</span>
            </div>
          </div>
          <div class="cust-status-badge">
            <span class="badge badge-green">✅ Đã chốt</span>
          </div>
        </div>
        <div class="cust-body">
          <div class="cust-row">
            <span class="cust-row-label">BĐS đã mua</span>
            <span class="cust-row-val" style="color:var(--primary);font-weight:600;">Đất ở Phường 3, 200m²</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Giá trị</span>
            <span class="cust-row-val" style="color:var(--success);font-weight:700;">1,800 triệu</span>
          </div>
          <div class="cust-row">
            <span class="cust-row-label">Hoa hồng</span>
            <span class="cust-row-val" style="color:var(--success);font-weight:700;">54 triệu ✓</span>
          </div>
          <div class="cust-tags">
            <span class="cust-tag" style="background:var(--success-light);color:var(--success);">✅ Đã công chứng</span>
            <span class="cust-tag" style="background:var(--success-light);color:var(--success);">Đã nhận HH</span>
          </div>
        </div>
        <div class="cust-footer">
          <div class="cust-date">📅 Chốt 05/03/2026 · Công chứng 07/03</div>
          <div class="cust-actions">
            <div class="cust-btn" onclick="showToast('Mở chat khách cũ...')">💬</div>
            <div class="cust-btn" style="background:var(--primary-light);border-color:transparent;font-size:10px;width:auto;padding:0 8px;color:var(--primary);font-weight:600;" onclick="showToast('Mở form referral')">🎁 Giới thiệu</div>
          </div>
        </div>
      </div>

      <div style="height:16px;"></div>
    </div><!-- end sp-scroll -->
  </div><!-- end subpage-mycustomers -->

