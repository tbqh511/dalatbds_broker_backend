@php
  $ep = $customer ?? null;
  $epInitials = '?';
  if ($ep && $ep->name) {
    $parts = preg_split('/\s+/', trim($ep->name));
    $epInitials = count($parts) === 1
      ? mb_strtoupper(mb_substr($parts[0], 0, 1))
      : mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1));
  }
  $epHasAvatar = $ep && $ep->getRawOriginal('profile');
  $epAvatarUrl = $epHasAvatar ? url('images' . config('global.USER_IMG_PATH') . $ep->getRawOriginal('profile')) : '';
  $epRoleLabels = ['guest'=>'Khách','broker'=>'Broker','sale'=>'Sale','bds_admin'=>'BĐS Admin','sale_admin'=>'Sale Admin','admin'=>'Admin'];
  $epRole = $ep ? ($epRoleLabels[$ep->getEffectiveRole()] ?? 'Broker') : 'Khách';
@endphp

  <!-- ========== SUBPAGE: CHỈNH SỬA HỒ SƠ ========== -->
  <div class="subpage" id="subpage-editprofile">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('editprofile')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><line x1="7" y1="8" x2="7.01" y2="8"/><line x1="11" y1="8" x2="17" y2="8"/><line x1="7" y1="11" x2="7.01" y2="11"/><line x1="11" y1="11" x2="17" y2="11"/></svg> Chỉnh sửa hồ sơ</span></div>
    </div>

    <div class="sp-scroll" style="padding-bottom:80px;">

      <!-- Avatar -->
      <div class="profile-edit-avatar">
        <div class="pea-circle" id="epAvatarCircle" onclick="document.getElementById('epAvatarInput').click()" style="cursor:pointer;">
          @if($epHasAvatar)
            <img id="epAvatarImg" src="{{ $epAvatarUrl }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" alt="{{ $ep->name }}">
          @else
            <span id="epAvatarInitials">{{ $epInitials }}</span>
          @endif
          <div class="pea-edit-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
        </div>
        <div class="pea-name" id="epDisplayName">{{ $ep->name ?? '' }}</div>
        <div class="pea-role">
          <span class="badge badge-blue" style="font-size:10px;display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg> {{ $epRole }}</span>
          <span>Đà Lạt BĐS</span>
        </div>
        <input type="file" id="epAvatarInput" accept="image/*" style="display:none;" onchange="epUploadAvatar(this)">
        <div class="pea-change-photo" onclick="document.getElementById('epAvatarInput').click()">
          <span style="display:inline-flex;align-items:center;gap:5px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            <span id="epAvatarBtnText">Đổi ảnh đại diện</span>
          </span>
        </div>
      </div>

      <!-- Thông tin cơ bản -->
      <div class="form-section">
        <div class="form-section-title">Thông tin cá nhân</div>
        <div class="form-row">
          <span class="form-row-label">Họ và tên</span>
          <input class="form-row-value" id="epName" type="text" value="{{ $ep->name ?? '' }}" placeholder="Nhập họ tên...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Số điện thoại</span>
          <span style="flex:1;text-align:right;font-size:13px;color:var(--text-tertiary);">{{ $ep->mobile ?? '—' }}</span>
        </div>
        <div class="form-row">
          <span class="form-row-label">Email</span>
          <input class="form-row-value" id="epEmail" type="email" value="{{ $ep->email ?? '' }}" placeholder="Email...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Zalo</span>
          <input class="form-row-value" id="epZalo" type="tel" value="{{ $ep->zalo ?? '' }}" placeholder="SĐT Zalo...">
        </div>
        <div class="form-row" style="align-items:flex-start;">
          <span class="form-row-label" style="padding-top:2px;">Giới thiệu</span>
          <textarea class="form-row-value" id="epBio" placeholder="Mô tả ngắn về bạn và kinh nghiệm BĐS..." rows="3">{{ $ep->bio ?? '' }}</textarea>
        </div>
      </div>

      <!-- Thông tin nghề nghiệp -->
      <div class="form-section">
        <div class="form-section-title">Nghề nghiệp & Khu vực</div>
        <div class="form-row">
          <span class="form-row-label">Kinh nghiệm</span>
          <input class="form-row-value" id="epYearsExp" type="number" min="0" max="50" value="{{ $ep->years_experience ?? '' }}" placeholder="Số năm...">
          <span style="font-size:12px;color:var(--text-tertiary);white-space:nowrap;"> năm</span>
        </div>
        <div class="form-row">
          <span class="form-row-label">Khu vực</span>
          <input class="form-row-value" id="epWorkArea" type="text" value="{{ $ep->work_area ?? '' }}" placeholder="Khu vực hoạt động...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Chuyên về</span>
          <input class="form-row-value" id="epSpecialization" type="text" value="{{ $ep->specialization ?? '' }}" placeholder="Loại BĐS chuyên...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Facebook</span>
          <input class="form-row-value" id="epFacebook" type="url" value="{{ $ep->facebook_link ?? '' }}" placeholder="https://facebook.com/...">
          <span class="form-row-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></span>
        </div>
      </div>

      <!-- Tài khoản Telegram -->
      <div class="form-section">
        <div class="form-section-title">Tài khoản Telegram</div>
        <div class="form-row">
          <span class="form-row-label">Telegram ID</span>
          <span style="flex:1;text-align:right;font-size:13px;color:var(--text-tertiary);">{{ $ep->telegram_id ?? '—' }}</span>
        </div>
        <div style="padding:10px 16px;background:var(--success-light);display:flex;gap:8px;align-items:center;">
          <span style="font-size:16px;">✓</span>
          <span style="font-size:12px;color:var(--success);">Tài khoản đã được xác thực qua Telegram WebApp</span>
        </div>
      </div>

      <!-- Lỗi validation -->
      <div id="epErrors" style="display:none;margin:0 16px 12px;padding:12px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;"></div>

      <div style="height:16px;"></div>
    </div>

    <!-- Save bar -->
    <div class="sticky-save">
      <button class="cancel-btn" onclick="closeSubpage('editprofile')">Hủy</button>
      <button class="save-btn" id="epSaveBtn" onclick="epSaveProfile()">✓ Lưu thay đổi</button>
    </div>
  </div><!-- end subpage-editprofile -->

  <script>
  function epUploadAvatar(input) {
    var file = input.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) { showToast('Ảnh không được vượt quá 2MB!'); return; }

    var btn = document.getElementById('epAvatarBtnText');
    btn.textContent = 'Đang tải...';

    var form = new FormData();
    form.append('avatar', file);
    form.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch(window.WEBAPP_CONFIG.routes.profileAvatar, { method: 'POST', body: form })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if (data.success) {
          // Cập nhật avatar trong subpage
          var circle = document.getElementById('epAvatarCircle');
          var initials = document.getElementById('epAvatarInitials');
          var img = document.getElementById('epAvatarImg');
          if (initials) initials.remove();
          if (img) { img.src = data.url; }
          else {
            var newImg = document.createElement('img');
            newImg.id = 'epAvatarImg';
            newImg.src = data.url;
            newImg.style = 'width:100%;height:100%;object-fit:cover;border-radius:50%;';
            circle.insertBefore(newImg, circle.querySelector('.pea-edit-btn'));
          }
          // Cập nhật avatar trên profile hero
          var heroAvatar = document.querySelector('#page-profile .profile-avatar');
          if (heroAvatar) {
            heroAvatar.innerHTML = '<img src="' + data.url + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
          }
          showToast('✓ Đã cập nhật ảnh đại diện!');
        } else {
          showToast('Lỗi upload ảnh!');
        }
      })
      .catch(function() { showToast('Lỗi upload ảnh, thử lại!'); })
      .finally(function() {
        btn.textContent = 'Đổi ảnh đại diện';
        input.value = '';
      });
  }

  function epSaveProfile() {
    var name    = document.getElementById('epName').value.trim();
    var email   = document.getElementById('epEmail').value.trim();
    var zalo    = document.getElementById('epZalo').value.replace(/\./g, '').trim();
    var bio     = document.getElementById('epBio').value.trim();
    var years   = document.getElementById('epYearsExp').value;
    var area    = document.getElementById('epWorkArea').value.trim();
    var spec    = document.getElementById('epSpecialization').value.trim();
    var fb      = document.getElementById('epFacebook').value.trim();

    // Client-side validation
    var errors = [];
    if (!name) errors.push('Họ và tên không được để trống.');
    if (zalo && !/^0[3-9][0-9]{8}$/.test(zalo)) errors.push('SĐT Zalo phải là số VN 10 chữ số (bắt đầu 03-09).');

    var errBox = document.getElementById('epErrors');
    if (errors.length > 0) {
      errBox.innerHTML = errors.map(function(e) { return '<div style="font-size:12px;color:#dc2626;margin-bottom:3px;">• ' + e + '</div>'; }).join('');
      errBox.style.display = 'block';
      return;
    }
    errBox.style.display = 'none';

    var btn = document.getElementById('epSaveBtn');
    btn.disabled = true;
    btn.textContent = 'Đang lưu...';

    fetch(window.WEBAPP_CONFIG.routes.profileUpdate, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({
        name: name, email: email, zalo: zalo,
        bio: bio, facebook_link: fb,
        years_experience: years || null, work_area: area, specialization: spec,
      })
    })
    .then(function(r) {
      return r.json().then(function(data) { return { status: r.status, data: data }; });
    })
    .then(function(res) {
      btn.disabled = false;
      btn.textContent = '✓ Lưu thay đổi';
      if (res.status === 422) {
        var valErrors = res.data.errors || {};
        var msgs = Object.values(valErrors).flat();
        errBox.innerHTML = msgs.map(function(e) { return '<div style="font-size:12px;color:#dc2626;margin-bottom:3px;">• ' + e + '</div>'; }).join('');
        errBox.style.display = 'block';
        return;
      }
      if (res.data.success) {
        document.getElementById('epDisplayName').textContent = name;
        var profileName = document.querySelector('#page-profile .profile-name');
        if (profileName) profileName.textContent = name;
        if (window.WEBAPP_CONFIG.customerProfile) {
          Object.assign(window.WEBAPP_CONFIG.customerProfile, res.data.customer || {});
        }
        showToast('✓ Đã lưu thông tin hồ sơ!');
        closeSubpage('editprofile');
      } else {
        showToast('Lỗi lưu hồ sơ!');
      }
    })
    .catch(function() {
      btn.disabled = false;
      btn.textContent = '✓ Lưu thay đổi';
      showToast('Lỗi kết nối, thử lại!');
    });
  }
  </script>

