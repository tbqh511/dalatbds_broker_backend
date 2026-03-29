  <!-- ============ SUBPAGE: REFERRAL ============ -->
  <div class="subpage" id="subpage-referral">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('referral')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg> Mạng lưới thổ địa</span></div>
      <div class="sp-actions">
        <button class="sp-action-btn" id="refExportBtn" onclick="showToast('Đang xuất báo cáo...')"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></button>
      </div>
    </div>

    <div class="sp-scroll">

      <!-- Hero card with referral code + QR -->
      <div style="padding-top:14px;"></div>
      <div class="ref-hero">
        <div class="ref-hero-label">CHƯƠNG TRÌNH GIỚI THIỆU — MULTI LEVEL</div>
        <div class="ref-hero-title">Nhận 5% thu nhập<br>từ người bạn giới thiệu</div>
        <div class="ref-hero-sub">Quét mã QR hoặc chia sẻ link để mời bạn bè tham gia — nhận 5% thu nhập mãi mãi!</div>

        <!-- QR Code -->
        <div class="ref-qr-wrap">
          <div class="ref-qr-box" id="refQrCode">
            <div id="refQrSkeleton" style="width:140px;height:140px;background:rgba(0,0,0,0.05);border-radius:8px;display:flex;align-items:center;justify-content:center;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 1s linear infinite;"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>
            </div>
          </div>
        </div>

        <!-- Compact code + copy -->
        <div class="ref-code-compact">
          <span class="ref-code-val-sm" id="refCodeDisplay">
            <span id="refCodeSkeleton" style="display:inline-block;width:80px;height:14px;background:rgba(255,255,255,0.25);border-radius:3px;vertical-align:middle;"></span>
          </span>
          <button class="ref-copy-btn-sm" onclick="copyRefCode()"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>
        </div>
      </div>

      <!-- Share buttons -->
      <div class="ref-share-row">
        <button class="ref-share-btn ref-share-tg" onclick="shareRefLink('telegram')">
          <span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Telegram</span>
        </button>
        <button class="ref-share-btn ref-share-zalo" onclick="shareRefLink('zalo')">
          <span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> Zalo</span>
        </button>
        <button class="ref-share-btn ref-share-link" onclick="shareRefLink('copy')">
          <span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg> Copy Link</span>
        </button>
      </div>

      <!-- Claim referrer (hiện khi user chưa có người giới thiệu) -->
      <div id="refClaimSection" style="display:none;margin:0 14px 12px;">
        <div style="background:var(--bg-card);border:1.5px dashed var(--border);border-radius:var(--radius-lg);padding:14px;">
          <div style="font-size:13px;font-weight:700;color:var(--text-primary);margin-bottom:4px;display:flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            Bạn có mã giới thiệu không?
          </div>
          <div style="font-size:12px;color:var(--text-secondary);margin-bottom:10px;">Nếu ai đó đã giới thiệu bạn vào hệ thống, nhập mã của họ để kết nối.</div>
          <div style="display:flex;gap:8px;">
            <input id="refClaimInput" type="text" placeholder="Ví dụ: DLBDS-ABC123"
              style="flex:1;padding:8px 10px;border:1.5px solid var(--border);border-radius:var(--radius-md);font-size:13px;font-family:monospace;background:var(--bg-secondary);color:var(--text-primary);text-transform:uppercase;outline:none;"
              oninput="this.value=this.value.toUpperCase()" maxlength="20">
            <button onclick="claimReferralCode()" id="refClaimBtn"
              style="padding:8px 14px;background:var(--primary-color);color:#fff;border:none;border-radius:var(--radius-md);font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;">
              Xác nhận
            </button>
          </div>
          <div id="refClaimMsg" style="display:none;margin-top:8px;font-size:12px;font-weight:600;"></div>
        </div>
      </div>

      <!-- Earnings summary -->
      <div class="ref-earn-card">
        <div class="ref-earn-header">
          <div class="ref-earn-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> Thu nhập từ giới thiệu</span></div>
          <div class="ref-earn-badge" id="refMonthLabel">...</div>
        </div>
        <div class="ref-earn-stats">
          <div class="ref-earn-stat">
            <div class="ref-earn-stat-val" id="refStatTotal" style="color:var(--purple);">—</div>
            <div class="ref-earn-stat-lbl">Người đã mời</div>
          </div>
          <div class="ref-earn-stat">
            <div class="ref-earn-stat-val" id="refStatActive" style="color:var(--success);">—</div>
            <div class="ref-earn-stat-lbl">Đang hoạt động</div>
          </div>
          <div class="ref-earn-stat">
            <div class="ref-earn-stat-val" id="refStatEarned" style="color:var(--warning);">—</div>
            <div class="ref-earn-stat-lbl">Tổng nhận (5%)</div>
          </div>
        </div>
      </div>

      <!-- How it works -->
      <div class="ref-how-section">
        <div class="ref-how-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg> Cách hoạt động Multi-Level</span></div>
        <div class="ref-how-step">
          <div class="ref-step-num">1</div>
          <div class="ref-step-body">
            <div class="ref-step-title">Chia sẻ link giới thiệu</div>
            <div class="ref-step-desc">Gửi link hoặc mã giới thiệu của bạn cho bạn bè, đối tác qua Telegram, Zalo...</div>
          </div>
        </div>
        <div class="ref-how-step">
          <div class="ref-step-num">2</div>
          <div class="ref-step-body">
            <div class="ref-step-title">Họ đăng ký & hoạt động</div>
            <div class="ref-step-desc">Người được giới thiệu tham gia Đà Lạt BĐS với vai trò eBroker hoặc Sale.</div>
          </div>
        </div>
        <div class="ref-how-step">
          <div class="ref-step-num">3</div>
          <div class="ref-step-body">
            <div class="ref-step-title">Bạn nhận 5% thu nhập</div>
            <div class="ref-step-desc">Mỗi khi người được giới thiệu có thu nhập từ hoa hồng, bạn tự động nhận 5% — không giới hạn thời gian.</div>
          </div>
        </div>
      </div>

      <!-- Tabs: Referral tree / History -->
      <div style="padding:0 14px 10px;">
        <div class="sp-tabs" id="refTabs" style="padding:0;margin:0;">
          <button class="sp-tab active" onclick="switchRefTab(this,'tree')"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Cây giới thiệu</span></button>
          <button class="sp-tab" onclick="switchRefTab(this,'history')"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Lịch sử nhận</span></button>
        </div>
      </div>

      <!-- Tab: Tree -->
      <div id="refTabTree">
        <div class="ref-tree-section">
          <div class="ref-tree-title" id="refTreeTitle">Cấp 1 — Bạn giới thiệu trực tiếp</div>
          <div id="refTreeContainer">
            <!-- Loading skeleton -->
            <div id="refTreeLoading" style="padding:20px;text-align:center;color:var(--text-tertiary);font-size:13px;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 1s linear infinite;display:inline-block;"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>
              Đang tải dữ liệu...
            </div>
          </div>
          <button id="refTreeShowMore" style="display:none;width:100%;padding:10px;background:var(--bg-secondary);border:1.5px dashed var(--border);border-radius:var(--radius-md);font-size:12px;font-weight:600;color:var(--text-secondary);cursor:pointer;margin-top:4px;" onclick="showMoreReferrals()"></button>
        </div>
      </div>

      <!-- Tab: History -->
      <div id="refTabHistory" style="display:none;">
        <div class="ref-tree-section">
          <div class="ref-tree-title">Lịch sử nhận hoa hồng giới thiệu</div>
          <div id="refHistoryContainer">
            <div id="refHistoryLoading" style="padding:20px;text-align:center;color:var(--text-tertiary);font-size:13px;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 1s linear infinite;display:inline-block;"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>
              Đang tải dữ liệu...
            </div>
          </div>
        </div>
      </div>

      <!-- Rules & Policy -->
      <div class="ref-rules-card">
        <div class="ref-rules-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Chính sách giới thiệu</span></div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div>Người giới thiệu nhận <strong>5% thu nhập hoa hồng</strong> của người được giới thiệu, áp dụng cho mọi giao dịch thành công.</div>
        </div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
          <div>Áp dụng cho <strong>mọi role</strong>: eBroker, Sale, Sale Admin, BĐS Admin, Admin đều có thể tham gia và xây dựng hệ thống.</div>
        </div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon">♾️</div>
          <div>Hoa hồng giới thiệu được tính <strong>vĩnh viễn</strong> — không giới hạn thời gian, áp dụng cho tất cả deal tương lai.</div>
        </div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
          <div>Thanh toán hoa hồng giới thiệu sẽ được xử lý cùng chu kỳ hoa hồng deal (sau khi Admin duyệt).</div>
        </div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
          <div>Mỗi người chỉ có <strong>1 người giới thiệu duy nhất</strong> — xác định tại thời điểm đăng ký thông qua link giới thiệu.</div>
        </div>
      </div>

      <!-- Visual MLM diagram -->
      <div style="margin:0 14px 14px;background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border);padding:16px;">
        <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:14px;display:flex;align-items:center;gap:6px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg> Mô hình chia sẻ thu nhập</div>
        <div style="display:flex;flex-direction:column;align-items:center;gap:4px;">
          <!-- Level 0: You -->
          <div style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;padding:10px 20px;border-radius:12px;font-size:13px;font-weight:700;text-align:center;min-width:160px;display:flex;align-items:center;justify-content:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg> BẠN
          </div>
          <div style="color:var(--text-tertiary);font-size:18px;">↓</div>
          <div style="background:var(--success-light);color:var(--success);padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;">Nhận 5% thu nhập ↓</div>
          <div style="color:var(--text-tertiary);font-size:18px;">↓</div>
          <!-- Level 1: Referrals -->
          <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
            <div style="background:var(--primary-light);color:var(--primary-dark);padding:8px 14px;border-radius:10px;font-size:11px;font-weight:600;text-align:center;display:flex;flex-direction:column;align-items:center;gap:3px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              eBroker A<br><span style="font-size:10px;opacity:0.7;">HH: 30 tr → Bạn: 1.5 tr</span>
            </div>
            <div style="background:var(--primary-light);color:var(--primary-dark);padding:8px 14px;border-radius:10px;font-size:11px;font-weight:600;text-align:center;display:flex;flex-direction:column;align-items:center;gap:3px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              Sale B<br><span style="font-size:10px;opacity:0.7;">HH: 50 tr → Bạn: 2.5 tr</span>
            </div>
            <div style="background:var(--primary-light);color:var(--primary-dark);padding:8px 14px;border-radius:10px;font-size:11px;font-weight:600;text-align:center;display:flex;flex-direction:column;align-items:center;gap:3px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
              eBroker C<br><span style="font-size:10px;opacity:0.7;">HH: 40 tr → Bạn: 2.0 tr</span>
            </div>
          </div>
          <div style="margin-top:8px;background:var(--warning-light);color:var(--warning);padding:6px 14px;border-radius:8px;font-size:11px;font-weight:600;text-align:center;">
            Tổng 5%: 6.0 triệu / tháng từ 3 người
          </div>
        </div>
      </div>

      <div style="height:24px;"></div>
    </div>
  </div><!-- end subpage-referral -->
