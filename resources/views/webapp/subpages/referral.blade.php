  <!-- ============ SUBPAGE: REFERRAL ============ -->
  <div class="subpage" id="subpage-referral">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('referral')">←</button>
      <div class="sp-title">🎁 Mã giới thiệu</div>
      <div class="sp-actions">
        <button class="sp-action-btn" onclick="showToast('📊 Xuất báo cáo MLM...')">📊</button>
      </div>
    </div>

    <div class="sp-scroll">

      <!-- Hero card with referral code -->
      <div style="padding-top:14px;"></div>
      <div class="ref-hero">
        <div class="ref-hero-label">CHƯƠNG TRÌNH GIỚI THIỆU — MULTI LEVEL</div>
        <div class="ref-hero-title">Nhận 5% thu nhập<br>từ người bạn giới thiệu</div>
        <div class="ref-hero-sub">Chia sẻ mã giới thiệu của bạn. Khi ai đó tham gia Đà Lạt BĐS qua link của bạn, bạn sẽ nhận 5% thu nhập của họ — mãi mãi!</div>
        <div class="ref-code-box">
          <div class="ref-code-val" id="refCodeDisplay">DLBDS-NVA2026</div>
          <button class="ref-copy-btn" onclick="copyRefCode()">📋 Sao chép</button>
        </div>
      </div>

      <!-- Share buttons -->
      <div class="ref-share-row">
        <button class="ref-share-btn ref-share-tg" onclick="shareRefLink('telegram')">
          <span>✈️</span> Telegram
        </button>
        <button class="ref-share-btn ref-share-zalo" onclick="shareRefLink('zalo')">
          <span>💬</span> Zalo
        </button>
        <button class="ref-share-btn ref-share-link" onclick="shareRefLink('copy')">
          <span>🔗</span> Copy Link
        </button>
      </div>

      <!-- Earnings summary -->
      <div class="ref-earn-card">
        <div class="ref-earn-header">
          <div class="ref-earn-title">💰 Thu nhập từ giới thiệu</div>
          <div class="ref-earn-badge">Tháng 3/2026</div>
        </div>
        <div class="ref-earn-stats">
          <div class="ref-earn-stat">
            <div class="ref-earn-stat-val" style="color:var(--purple);">12</div>
            <div class="ref-earn-stat-lbl">Người đã mời</div>
          </div>
          <div class="ref-earn-stat">
            <div class="ref-earn-stat-val" style="color:var(--success);">8</div>
            <div class="ref-earn-stat-lbl">Đang hoạt động</div>
          </div>
          <div class="ref-earn-stat">
            <div class="ref-earn-stat-val" style="color:var(--warning);">22.5 tr</div>
            <div class="ref-earn-stat-lbl">Tổng nhận (5%)</div>
          </div>
        </div>
        <div class="ref-earn-progress">
          <div class="ref-prog-label">
            <span>Mục tiêu tháng: 50 triệu</span>
            <span style="font-weight:700;color:var(--purple);">45%</span>
          </div>
          <div class="ref-prog-bar">
            <div class="ref-prog-fill" style="width:45%;"></div>
          </div>
        </div>
      </div>

      <!-- How it works -->
      <div class="ref-how-section">
        <div class="ref-how-title">📖 Cách hoạt động Multi-Level</div>
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

      <!-- Tabs: Referral tree -->
      <div style="padding:0 14px 10px;">
        <div class="sp-tabs" id="refTabs" style="padding:0;margin:0;">
          <button class="sp-tab active" onclick="switchRefTab(this,'tree')">🌳 Cây giới thiệu</button>
          <button class="sp-tab" onclick="switchRefTab(this,'history')">📜 Lịch sử nhận</button>
        </div>
      </div>

      <!-- Tab: Tree -->
      <div id="refTabTree">
        <div class="ref-tree-section">
          <div class="ref-tree-title">Cấp 1 — Bạn giới thiệu trực tiếp (12 người)</div>

          <!-- Admin BĐS example members -->
          <div class="ref-member" onclick="showToast('Xem chi tiết Trần Văn Bình...')">
            <div class="ref-member-avatar" style="background:linear-gradient(135deg,#059669,#10b981);">B</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Trần Văn Bình <span class="ref-level-tag" style="background:var(--success-light);color:var(--success);">eBroker</span></div>
              <div class="ref-member-meta">Tham gia: 15/01/2026 · P. Xuân Hương</div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn">+3.5 tr</div>
              <div class="ref-member-earn-label">5% tháng này</div>
            </div>
          </div>

          <div class="ref-member" onclick="showToast('Xem chi tiết Lê Thị Mai...')">
            <div class="ref-member-avatar" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);">M</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Lê Thị Mai <span class="ref-level-tag" style="background:var(--purple-light);color:var(--purple);">Sale</span></div>
              <div class="ref-member-meta">Tham gia: 20/01/2026 · P. Cam Ly</div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn">+5.2 tr</div>
              <div class="ref-member-earn-label">5% tháng này</div>
            </div>
          </div>

          <div class="ref-member" onclick="showToast('Xem chi tiết Nguyễn Hoàng Dũng...')">
            <div class="ref-member-avatar" style="background:linear-gradient(135deg,#2563eb,#3b82f6);">D</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Nguyễn Hoàng Dũng <span class="ref-level-tag" style="background:var(--success-light);color:var(--success);">eBroker</span></div>
              <div class="ref-member-meta">Tham gia: 02/02/2026 · P. Lâm Viên</div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn">+2.8 tr</div>
              <div class="ref-member-earn-label">5% tháng này</div>
            </div>
          </div>

          <div class="ref-member" onclick="showToast('Xem chi tiết Phạm Thùy Linh...')">
            <div class="ref-member-avatar" style="background:linear-gradient(135deg,#d97706,#f59e0b);">L</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Phạm Thùy Linh <span class="ref-level-tag" style="background:var(--purple-light);color:var(--purple);">Sale</span></div>
              <div class="ref-member-meta">Tham gia: 10/02/2026 · P. 3 Tháng 4</div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn">+4.1 tr</div>
              <div class="ref-member-earn-label">5% tháng này</div>
            </div>
          </div>

          <div class="ref-member" onclick="showToast('Xem chi tiết Võ Minh Tuấn...')">
            <div class="ref-member-avatar" style="background:linear-gradient(135deg,#0d9488,#14b8a6);">T</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Võ Minh Tuấn <span class="ref-level-tag" style="background:var(--success-light);color:var(--success);">eBroker</span></div>
              <div class="ref-member-meta">Tham gia: 25/02/2026 · P. Xuân Hương</div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn">+1.9 tr</div>
              <div class="ref-member-earn-label">5% tháng này</div>
            </div>
          </div>

          <div class="ref-member" style="opacity:0.55;">
            <div class="ref-member-avatar" style="background:#94a3b8;">H</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Đặng Thanh Hà <span class="ref-level-tag" style="background:var(--bg-secondary);color:var(--text-tertiary);">eBroker · Chưa HĐ</span></div>
              <div class="ref-member-meta">Tham gia: 05/03/2026 · Chưa có giao dịch</div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn" style="color:var(--text-tertiary);">0</div>
              <div class="ref-member-earn-label">Chưa phát sinh</div>
            </div>
          </div>

          <!-- Show more -->
          <button style="width:100%;padding:10px;background:var(--bg-secondary);border:1.5px dashed var(--border);border-radius:var(--radius-md);font-size:12px;font-weight:600;color:var(--text-secondary);cursor:pointer;margin-top:4px;" onclick="showToast('Đang tải thêm 6 thành viên...')">
            Xem thêm 6 thành viên khác ▾
          </button>
        </div>
      </div>

      <!-- Tab: History -->
      <div id="refTabHistory" style="display:none;">
        <div class="ref-tree-section">
          <div class="ref-tree-title">Lịch sử nhận hoa hồng giới thiệu</div>

          <div class="ref-member">
            <div class="ref-member-avatar" style="background:linear-gradient(135deg,#059669,#10b981);font-size:14px;">💰</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Lê Thị Mai — Deal #D2026-041</div>
              <div class="ref-member-meta">14/03/2026 · BĐS Đường Yersin, P. Cam Ly</div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn">+2.5 tr</div>
              <div class="ref-member-earn-label">5% × 50 tr HH</div>
            </div>
          </div>

          <div class="ref-member">
            <div class="ref-member-avatar" style="background:linear-gradient(135deg,#059669,#10b981);font-size:14px;">💰</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Trần Văn Bình — Deal #D2026-038</div>
              <div class="ref-member-meta">10/03/2026 · Đất nền Xuân Hương</div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn">+1.5 tr</div>
              <div class="ref-member-earn-label">5% × 30 tr HH</div>
            </div>
          </div>

          <div class="ref-member">
            <div class="ref-member-avatar" style="background:linear-gradient(135deg,#059669,#10b981);font-size:14px;">💰</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Nguyễn Hoàng Dũng — Deal #D2026-035</div>
              <div class="ref-member-meta">05/03/2026 · Biệt thự Lâm Viên</div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn">+3.0 tr</div>
              <div class="ref-member-earn-label">5% × 60 tr HH</div>
            </div>
          </div>

          <div class="ref-member">
            <div class="ref-member-avatar" style="background:linear-gradient(135deg,#d97706,#f59e0b);font-size:14px;">⏳</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Phạm Thùy Linh — Deal #D2026-044</div>
              <div class="ref-member-meta">13/03/2026 · Nhà phố P.1 · <span style="color:var(--warning);font-weight:600;">Đang chờ duyệt</span></div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn" style="color:var(--warning);">~2.0 tr</div>
              <div class="ref-member-earn-label">Dự kiến</div>
            </div>
          </div>

          <div class="ref-member">
            <div class="ref-member-avatar" style="background:linear-gradient(135deg,#059669,#10b981);font-size:14px;">💰</div>
            <div class="ref-member-body">
              <div class="ref-member-name">Võ Minh Tuấn — Deal #D2026-030</div>
              <div class="ref-member-meta">28/02/2026 · Đất nền Xuân Hương</div>
            </div>
            <div class="ref-member-right">
              <div class="ref-member-earn">+1.9 tr</div>
              <div class="ref-member-earn-label">5% × 38 tr HH</div>
            </div>
          </div>

        </div>
      </div>

      <!-- Rules & Policy -->
      <div class="ref-rules-card">
        <div class="ref-rules-title">📋 Chính sách giới thiệu</div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon">✅</div>
          <div>Người giới thiệu nhận <strong>5% thu nhập hoa hồng</strong> của người được giới thiệu, áp dụng cho mọi giao dịch thành công.</div>
        </div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon">🏠</div>
          <div><strong>BĐS Admin</strong> quản lý khu vực sẽ nhận 5% từ tất cả eBroker đăng tin trong khu vực đó.</div>
        </div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon">💼</div>
          <div><strong>Sale Admin</strong> nhận 5% từ thu nhập của các Sale trực thuộc, dựa trên hoa hồng deal thành công.</div>
        </div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon">♾️</div>
          <div>Hoa hồng giới thiệu được tính <strong>vĩnh viễn</strong> — không giới hạn thời gian, áp dụng cho tất cả deal tương lai.</div>
        </div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon">💳</div>
          <div>Thanh toán hoa hồng giới thiệu sẽ được xử lý cùng chu kỳ hoa hồng deal (sau khi Admin duyệt).</div>
        </div>
        <div class="ref-rule-item">
          <div class="ref-rule-icon">⚠️</div>
          <div>Mỗi người chỉ có <strong>1 người giới thiệu duy nhất</strong> — xác định tại thời điểm đăng ký thông qua link giới thiệu.</div>
        </div>
      </div>

      <!-- Visual MLM diagram -->
      <div style="margin:0 14px 14px;background:var(--bg-card);border-radius:var(--radius-lg);border:1px solid var(--border);padding:16px;">
        <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:14px;">🔀 Mô hình chia sẻ thu nhập</div>
        <!-- Mini visual diagram -->
        <div style="display:flex;flex-direction:column;align-items:center;gap:4px;">
          <!-- Level 0: You -->
          <div style="background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;padding:10px 20px;border-radius:12px;font-size:13px;font-weight:700;text-align:center;min-width:160px;">
            🙋 BẠN (Admin/Sale Admin)
          </div>
          <div style="color:var(--text-tertiary);font-size:18px;">↓</div>
          <div style="background:var(--success-light);color:var(--success);padding:5px 12px;border-radius:20px;font-size:11px;font-weight:700;">Nhận 5% thu nhập ↓</div>
          <div style="color:var(--text-tertiary);font-size:18px;">↓</div>
          <!-- Level 1: Referrals -->
          <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
            <div style="background:var(--primary-light);color:var(--primary-dark);padding:8px 14px;border-radius:10px;font-size:11px;font-weight:600;text-align:center;">
              👤 eBroker A<br><span style="font-size:10px;opacity:0.7;">HH: 30 tr → Bạn: 1.5 tr</span>
            </div>
            <div style="background:var(--primary-light);color:var(--primary-dark);padding:8px 14px;border-radius:10px;font-size:11px;font-weight:600;text-align:center;">
              👤 Sale B<br><span style="font-size:10px;opacity:0.7;">HH: 50 tr → Bạn: 2.5 tr</span>
            </div>
            <div style="background:var(--primary-light);color:var(--primary-dark);padding:8px 14px;border-radius:10px;font-size:11px;font-weight:600;text-align:center;">
              👤 eBroker C<br><span style="font-size:10px;opacity:0.7;">HH: 40 tr → Bạn: 2.0 tr</span>
            </div>
          </div>
          <div style="margin-top:8px;background:var(--warning-light);color:var(--warning);padding:6px 14px;border-radius:8px;font-size:11px;font-weight:600;text-align:center;">
            💡 Tổng 5%: 6.0 triệu / tháng từ 3 người
          </div>
        </div>
      </div>

      <!-- Role-specific context -->
      <div class="role-bds_admin" style="margin:0 14px 14px;">
        <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);border:1.5px solid #f59e0b;border-radius:var(--radius-lg);padding:14px 16px;">
          <div style="font-size:13px;font-weight:700;color:#92400e;margin-bottom:6px;">🏘️ Đặc quyền BĐS Admin</div>
          <div style="font-size:12px;color:#78350f;line-height:1.5;">
            Với vai trò BĐS Admin quản lý khu vực, bạn tự động nhận <strong>5% thu nhập</strong> từ tất cả eBroker đăng tin BĐS trong khu vực phụ trách. Hiện bạn đang quản lý <strong>P. Xuân Hương</strong> với <strong>10 eBroker</strong> đang hoạt động.
          </div>
        </div>
      </div>

      <div class="role-admin" style="margin:0 14px 14px;">
        <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);border:1.5px solid #f59e0b;border-radius:var(--radius-lg);padding:14px 16px;">
          <div style="font-size:13px;font-weight:700;color:#92400e;margin-bottom:6px;">👑 Đặc quyền Admin hệ thống</div>
          <div style="font-size:12px;color:#78350f;line-height:1.5;">
            Với vai trò Admin hệ thống, bạn quản lý toàn bộ khu vực và nhận <strong>5% thu nhập</strong> từ tất cả BĐS Admin và eBroker trên hệ thống. Tổng <strong>25 eBroker</strong> đang hoạt động.
          </div>
        </div>
      </div>

      <div class="role-sale_admin" style="margin:0 14px 14px;">
        <div style="background:linear-gradient(135deg,#ede9fe,#ddd6fe);border:1.5px solid #8b5cf6;border-radius:var(--radius-lg);padding:14px 16px;">
          <div style="font-size:13px;font-weight:700;color:#5b21b6;margin-bottom:6px;">📋 Đặc quyền Sale Admin</div>
          <div style="font-size:12px;color:#4c1d95;line-height:1.5;">
            Với vai trò Sale Admin, bạn nhận <strong>5% thu nhập hoa hồng</strong> từ tất cả Sale trực thuộc. Hiện bạn đang quản lý <strong>5 Sale</strong> trong team, với tổng <strong>12 deals</strong> tháng này.
          </div>
        </div>
      </div>

      <div style="height:24px;"></div>
    </div>
  </div><!-- end subpage-referral -->
