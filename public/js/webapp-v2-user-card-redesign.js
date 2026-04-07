/**
 * ========== USER CARD REDESIGN (MINIMALIST + FLAT DESIGN) ==========
 *
 * Thay thế hàm renderUserCard trong webapp-v2.js
 * - Xóa badge nhiều màu sắc (thay bằng Primary + Gray neutral)
 * - Loại bỏ border đậm, shadow nặng
 * - Flat Design icons (outline, không gradient)
 * - Tối giản layout
 *
 * USAGE: Copy hàm này để thay thế renderUserCard trong webapp-v2.js (line ~6202)
 */

function renderUserCard(u, tab) {
  var initials = u.initials || u.name.slice(0, 2).toUpperCase();

  // Flat Design Avatar: Chỉ dùng Primary hoặc Gray, không gradient
  var avatarBg = u.avatar_color || '#3b82f6';
  var avatar = '<div class="uc-avatar" style="background:' + avatarBg + ';color:#fff;display:flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:8px;font-weight:600;font-size:14px;flex-shrink:0;">' + initials + '</div>';

  // ===== TAB: CHỜ DUYỆT =====
  if (tab === 'pending') {
    return '<div class="user-card" id="uc-' + u.id + '" style="border:1px solid var(--border);border-radius:8px;margin-bottom:8px;background:#fff;overflow:hidden;">'
      // Header: Avatar + Info + Badge
      + '<div style="padding:12px 14px;display:flex;justify-content:space-between;align-items:center;gap:12px;">'
      + '<div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">'
      + avatar
      + '<div style="flex:1;min-width:0;">'
      + '<div style="font-weight:600;font-size:14px;color:var(--primary);word-break:break-word;">' + escHtml(u.name) + '</div>'
      + '<div style="font-size:12px;color:var(--text-tertiary);margin-top:2px;">'
      + (u.mobile ? '📱 ' + escHtml(u.mobile) : (u.email ? '✉️ ' + escHtml(u.email) : ''))
      + '</div>'
      + '</div>'
      + '</div>'
      // Minimalist Badge: Primary color
      + '<div style="background:var(--primary);background-opacity:0.1;color:var(--primary);padding:4px 8px;border-radius:6px;font-size:11px;font-weight:500;white-space:nowrap;">⏳ Chờ</div>'
      + '</div>'
      // Actions: Flat buttons
      + '<div style="padding:8px 14px;border-top:1px solid var(--border);display:flex;gap:6px;flex-wrap:wrap;">'
      + '<button class="uc-btn reject" onclick="rejectUser(' + u.id + ',\'' + escHtml(u.name) + '\')" style="flex:1;min-width:70px;padding:8px 10px;border-radius:6px;border:1px solid #e5e7eb;background:#fff;color:#6b7280;font-size:12px;cursor:pointer;font-weight:500;">✕ Từ chối</button>'
      + '<button class="uc-btn warn" onclick="approveTempUser(' + u.id + ',\'' + escHtml(u.name) + '\')" style="flex:1;min-width:70px;padding:8px 10px;border-radius:6px;border:1px solid #e5e7eb;background:#f3f4f6;color:var(--text-primary);font-size:12px;cursor:pointer;font-weight:500;">⏳ Tạm</button>'
      + '<button class="uc-btn" onclick="approveUser(' + u.id + ',\'' + escHtml(u.name) + '\')" style="flex:1;min-width:70px;padding:8px 10px;border-radius:6px;background:var(--primary);color:#fff;border:none;font-size:12px;cursor:pointer;font-weight:500;">✓ Duyệt</button>'
      + '</div>'
      + '</div>';
  }

  // ===== TAB: EBROKER =====
  if (tab === 'broker') {
    var roleBadge = getRoleBadgeMinimalist(u.role);
    return '<div class="user-card" id="uc-' + u.id + '" style="border:1px solid var(--border);border-radius:8px;margin-bottom:8px;background:#fff;overflow:hidden;">'
      // Header: Avatar + Info + Role Badge
      + '<div style="padding:12px 14px;display:flex;justify-content:space-between;align-items:center;gap:12px;">'
      + '<div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">'
      + avatar
      + '<div style="flex:1;min-width:0;">'
      + '<div style="font-weight:600;font-size:14px;color:var(--primary);word-break:break-word;">' + escHtml(u.name) + '</div>'
      + '<div style="font-size:12px;color:var(--text-tertiary);margin-top:2px;">'
      + (u.mobile ? '📱 ' + escHtml(u.mobile) : (u.email ? '✉️ ' + escHtml(u.email) : ''))
      + '</div>'
      + '</div>'
      + '</div>'
      + roleBadge
      + '</div>'
      // Actions: Flat buttons
      + '<div style="padding:8px 14px;border-top:1px solid var(--border);display:flex;gap:6px;">'
      + '<button class="uc-btn warn" onclick="toggleUserLock(' + u.id + ',\'' + escHtml(u.name) + '\',' + u.isActive + ')" style="flex:1;padding:8px 10px;border-radius:6px;border:1px solid #e5e7eb;background:#fff;color:#6b7280;font-size:12px;cursor:pointer;font-weight:500;">🔒 Khoá</button>'
      + '<button class="uc-btn" onclick="openRoleSheet(' + u.id + ',\'' + u.role + '\')" style="flex:1;padding:8px 10px;border-radius:6px;border:1px solid var(--primary);background:#fff;color:var(--primary);font-size:12px;cursor:pointer;font-weight:500;">👤 Phân quyền</button>'
      + '</div>'
      + '</div>';
  }

  // ===== TAB: SALE =====
  if (tab === 'sale') {
    var roleBadge = getRoleBadgeMinimalist(u.role);
    return '<div class="user-card" id="uc-' + u.id + '" style="border:1px solid var(--border);border-radius:8px;margin-bottom:8px;background:#fff;overflow:hidden;">'
      // Header: Avatar + Info + Role Badge
      + '<div style="padding:12px 14px;display:flex;justify-content:space-between;align-items:center;gap:12px;">'
      + '<div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">'
      + avatar
      + '<div style="flex:1;min-width:0;">'
      + '<div style="font-weight:600;font-size:14px;color:var(--primary);word-break:break-word;">' + escHtml(u.name) + '</div>'
      + '<div style="font-size:12px;color:var(--text-tertiary);margin-top:2px;">'
      + (u.mobile ? '📱 ' + escHtml(u.mobile) : (u.email ? '✉️ ' + escHtml(u.email) : ''))
      + '</div>'
      + '</div>'
      + '</div>'
      + roleBadge
      + '</div>'
      // Actions: Flat buttons
      + '<div style="padding:8px 14px;border-top:1px solid var(--border);display:flex;gap:6px;">'
      + '<button class="uc-btn warn" onclick="toggleUserLock(' + u.id + ',\'' + escHtml(u.name) + '\',' + u.isActive + ')" style="flex:1;padding:8px 10px;border-radius:6px;border:1px solid #e5e7eb;background:#fff;color:#6b7280;font-size:12px;cursor:pointer;font-weight:500;">🔒 Khoá</button>'
      + '<button class="uc-btn" onclick="openRoleSheet(' + u.id + ',\'' + u.role + '\')" style="flex:1;padding:8px 10px;border-radius:6px;border:1px solid var(--primary);background:#fff;color:var(--primary);font-size:12px;cursor:pointer;font-weight:500;">👤 Phân quyền</button>'
      + '</div>'
      + '</div>';
  }

  // ===== TAB: KHOÁ =====
  if (tab === 'locked') {
    return '<div class="user-card" id="uc-' + u.id + '" style="border:1px solid #e5e7eb;border-radius:8px;margin-bottom:8px;background:#f9fafb;overflow:hidden;opacity:0.85;">'
      // Header: Avatar + Info + Locked Badge
      + '<div style="padding:12px 14px;display:flex;justify-content:space-between;align-items:center;gap:12px;">'
      + '<div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">'
      + '<div style="background:#9ca3af;color:#fff;display:flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:8px;font-weight:600;font-size:14px;flex-shrink:0;">' + initials + '</div>'
      + '<div style="flex:1;min-width:0;">'
      + '<div style="font-weight:600;font-size:14px;color:#6b7280;word-break:break-word;">' + escHtml(u.name) + '</div>'
      + '<div style="font-size:12px;color:#9ca3af;margin-top:2px;">'
      + (u.mobile ? '📱 ' + escHtml(u.mobile) : '')
      + '</div>'
      + '</div>'
      + '</div>'
      // Locked Badge: Gray + Primary
      + '<div style="background:#ef4444;color:#fff;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:500;white-space:nowrap;">🔒 Khoá</div>'
      + '</div>'
      // Actions: Flat buttons
      + '<div style="padding:8px 14px;border-top:1px solid #e5e7eb;display:flex;gap:6px;flex-wrap:wrap;">'
      + '<button class="uc-btn approve" onclick="toggleUserLock(' + u.id + ',\'' + escHtml(u.name) + '\',' + u.isActive + ')" style="flex:1;min-width:70px;padding:8px 10px;border-radius:6px;background:var(--primary);color:#fff;border:none;font-size:12px;cursor:pointer;font-weight:500;">🔓 Mở</button>'
      + '<button class="uc-btn danger" onclick="deleteUserConfirm(' + u.id + ',\'' + escHtml(u.name) + '\')" style="flex:1;min-width:70px;padding:8px 10px;border-radius:6px;background:#ef4444;color:#fff;border:none;font-size:12px;cursor:pointer;font-weight:500;">🗑 Xoá</button>'
      + '</div>'
      + '</div>';
  }

  return '';
}

/**
 * getRoleBadgeMinimalist - Minimalist Role Badges (Primary Focus)
 *
 * Chuyển đổi từ many-color badges sang Primary + Gray neutral
 * Giữ icon emoji cho context nhưng dùng color scheme đơn giản
 */
function getRoleBadgeMinimalist(role) {
  var badges = {
    'broker':     '<div style="background:var(--primary);background-opacity:0.1;color:var(--primary);padding:4px 8px;border-radius:6px;font-size:11px;font-weight:500;white-space:nowrap;">🏠 eBroker</div>',
    'bds_admin':  '<div style="background:var(--primary);background-opacity:0.15;color:var(--primary);padding:4px 8px;border-radius:6px;font-size:11px;font-weight:500;white-space:nowrap;">🏘️ BĐS Admin</div>',
    'sale':       '<div style="background:#f3f4f6;color:#6b7280;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:500;white-space:nowrap;">💼 Sale</div>',
    'sale_admin': '<div style="background:var(--primary);background-opacity:0.1;color:var(--primary);padding:4px 8px;border-radius:6px;font-size:11px;font-weight:500;white-space:nowrap;">📋 Sale Admin</div>',
    'admin':      '<div style="background:var(--primary);color:#fff;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:500;white-space:nowrap;">👑 Admin</div>',
    'guest':      '<div style="background:#f3f4f6;color:#6b7280;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:500;white-space:nowrap;">👤 Guest</div>',
  };
  return badges[role] || '<div style="background:#f3f4f6;color:#6b7280;padding:4px 8px;border-radius:6px;font-size:11px;font-weight:500;white-space:nowrap;">' + role + '</div>';
}

/**
 * escHtml - HTML escape helper (giữ nguyên, không thay đổi)
 */
function escHtml(str) {
  if (!str) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}
