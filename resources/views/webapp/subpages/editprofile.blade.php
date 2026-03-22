  <!-- ========== SUBPAGE: CHỈNH SỬA HỒ SƠ ========== -->
  <div class="subpage" id="subpage-editprofile" x-data="editProfileApp()" x-init="init()">
    <div class="sp-header">
      <button class="sp-back" onclick="closeSubpage('editprofile')">←</button>
      <div class="sp-title"><span style="display:inline-flex;align-items:center;gap:5px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/><line x1="7" y1="8" x2="7.01" y2="8"/><line x1="11" y1="8" x2="17" y2="8"/><line x1="7" y1="11" x2="7.01" y2="11"/><line x1="11" y1="11" x2="17" y2="11"/></svg> Chỉnh sửa hồ sơ</span></div>
    </div>

    <div class="sp-scroll" style="padding-bottom:80px;">

      <!-- Avatar -->
      <div class="profile-edit-avatar">
        <div class="pea-circle" @click="$refs.avatarInput.click()" style="cursor:pointer;">
          <template x-if="avatarUrl">
            <img :src="avatarUrl" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" :alt="name">
          </template>
          <template x-if="!avatarUrl">
            <span x-text="initials"></span>
          </template>
          <div class="pea-edit-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
        </div>
        <div class="pea-name" x-text="name"></div>
        <div class="pea-role">
          <span class="badge badge-blue" style="font-size:10px;display:inline-flex;align-items:center;gap:3px;"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg> <span x-text="roleLabel"></span></span>
          <span>Đà Lạt BĐS</span>
        </div>
        <input type="file" x-ref="avatarInput" accept="image/*" style="display:none;" @change="uploadAvatar($event)">
        <div class="pea-change-photo" @click="$refs.avatarInput.click()">
          <span style="display:inline-flex;align-items:center;gap:5px;">
            <template x-if="!avatarUploading">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </template>
            <span x-text="avatarUploading ? 'Đang tải...' : 'Đổi ảnh đại diện'"></span>
          </span>
        </div>
      </div>

      <!-- Thông tin cơ bản -->
      <div class="form-section">
        <div class="form-section-title">Thông tin cá nhân</div>
        <div class="form-row">
          <span class="form-row-label">Họ và tên</span>
          <input class="form-row-value" type="text" x-model="name" placeholder="Nhập họ tên...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Số điện thoại</span>
          <input class="form-row-value" type="tel" x-model="mobile" placeholder="SĐT 10 số...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Email</span>
          <input class="form-row-value" type="email" x-model="email" placeholder="Email...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Zalo</span>
          <input class="form-row-value" type="tel" x-model="zalo" placeholder="SĐT Zalo...">
        </div>
        <div class="form-row" style="align-items:flex-start;">
          <span class="form-row-label" style="padding-top:2px;">Giới thiệu</span>
          <textarea class="form-row-value" placeholder="Mô tả ngắn về bạn và kinh nghiệm BĐS..." rows="3" x-model="bio"></textarea>
        </div>
      </div>

      <!-- Thông tin nghề nghiệp -->
      <div class="form-section">
        <div class="form-section-title">Nghề nghiệp & Khu vực</div>
        <div class="form-row">
          <span class="form-row-label">Kinh nghiệm</span>
          <input class="form-row-value" type="number" min="0" max="50" x-model="years_experience" placeholder="Số năm...">
          <span style="font-size:12px;color:var(--text-tertiary);white-space:nowrap;"> năm</span>
        </div>
        <div class="form-row">
          <span class="form-row-label">Khu vực</span>
          <input class="form-row-value" type="text" x-model="work_area" placeholder="Khu vực hoạt động...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Chuyên về</span>
          <input class="form-row-value" type="text" x-model="specialization" placeholder="Loại BĐS chuyên...">
        </div>
        <div class="form-row">
          <span class="form-row-label">Facebook</span>
          <input class="form-row-value" type="url" x-model="facebook_link" placeholder="https://facebook.com/...">
          <span class="form-row-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></span>
        </div>
      </div>

      <!-- Tài khoản Telegram -->
      <div class="form-section">
        <div class="form-section-title">Tài khoản Telegram</div>
        <div class="form-row">
          <span class="form-row-label">Telegram ID</span>
          <span style="flex:1;text-align:right;font-size:13px;color:var(--text-tertiary);" x-text="telegramId || '—'"></span>
        </div>
        <div style="padding:10px 16px;background:var(--success-light);display:flex;gap:8px;align-items:center;">
          <span style="font-size:16px;">✓</span>
          <span style="font-size:12px;color:var(--success);">Tài khoản đã được xác thực qua Telegram WebApp</span>
        </div>
      </div>

      <!-- Lỗi validation -->
      <template x-if="errors.length > 0">
        <div style="margin:0 16px 12px;padding:12px 14px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;">
          <template x-for="err in errors" :key="err">
            <div style="font-size:12px;color:#dc2626;margin-bottom:3px;" x-text="'• ' + err"></div>
          </template>
        </div>
      </template>

      <div style="height:16px;"></div>
    </div>

    <!-- Save bar -->
    <div class="sticky-save">
      <button class="cancel-btn" onclick="closeSubpage('editprofile')">Hủy</button>
      <button class="save-btn" @click="saveProfile()" :disabled="saving" :style="saving ? 'opacity:0.7;' : ''">
        <template x-if="!saving">
          <span>✓ Lưu thay đổi</span>
        </template>
        <template x-if="saving">
          <span>Đang lưu...</span>
        </template>
      </button>
    </div>
  </div><!-- end subpage-editprofile -->

  <script>
  function editProfileApp() {
    const cfg = window.WEBAPP_CONFIG || {};
    const p = cfg.customerProfile || {};
    return {
      name: p.name || '',
      email: p.email || '',
      mobile: p.mobile || '',
      zalo: p.zalo || '',
      bio: p.bio || '',
      facebook_link: p.facebook_link || '',
      years_experience: p.years_experience || '',
      work_area: p.work_area || '',
      specialization: p.specialization || '',
      telegramId: p.telegram_id || '',
      avatarUrl: p.avatar_url || '',
      roleLabel: (function() {
        const map = {guest:'Guest', broker:'Broker', sale:'Sale', bds_admin:'BĐS Admin', sale_admin:'Sale Admin', admin:'Admin'};
        return map[p.role] || 'Broker';
      })(),
      saving: false,
      avatarUploading: false,
      errors: [],

      get initials() {
        if (!this.name) return '?';
        const parts = this.name.trim().split(/\s+/);
        if (parts.length === 1) return parts[0].charAt(0).toUpperCase();
        return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
      },

      init() {
        // Sync tên hiển thị trên profile page khi name thay đổi
        this.$watch('name', val => {
          const el = document.querySelector('.profile-name');
          if (el) el.textContent = val;
        });
      },

      uploadAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
          showToast('Ảnh không được vượt quá 2MB!');
          return;
        }
        this.avatarUploading = true;
        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('_token', cfg.csrfToken || document.querySelector('meta[name="csrf-token"]').content);
        axios.post(cfg.routes.profileAvatar, formData, {
          headers: {'Content-Type': 'multipart/form-data'}
        }).then(res => {
          if (res.data.success) {
            this.avatarUrl = res.data.url;
            // Cập nhật avatar trên profile hero
            const heroAvatar = document.querySelector('.profile-avatar');
            if (heroAvatar) heroAvatar.innerHTML = '<img src="' + res.data.url + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
            showToast('✓ Đã cập nhật ảnh đại diện!');
          }
        }).catch(() => {
          showToast('Lỗi upload ảnh, thử lại!');
        }).finally(() => {
          this.avatarUploading = false;
          event.target.value = '';
        });
      },

      saveProfile() {
        this.errors = [];
        if (!this.name.trim()) {
          this.errors.push('Họ và tên không được để trống.');
        }
        if (this.mobile && !/^(0[3-9][0-9]{8})$/.test(this.mobile.replace(/\./g, ''))) {
          this.errors.push('Số điện thoại phải là số VN 10 chữ số (bắt đầu 03-09).');
        }
        if (this.zalo && !/^(0[3-9][0-9]{8})$/.test(this.zalo.replace(/\./g, ''))) {
          this.errors.push('SĐT Zalo phải là số VN 10 chữ số (bắt đầu 03-09).');
        }
        if (this.errors.length > 0) return;

        this.saving = true;
        const data = {
          _token: cfg.csrfToken || document.querySelector('meta[name="csrf-token"]').content,
          name: this.name,
          email: this.email,
          mobile: this.mobile.replace(/\./g, ''),
          zalo: this.zalo.replace(/\./g, ''),
          bio: this.bio,
          facebook_link: this.facebook_link,
          years_experience: this.years_experience || null,
          work_area: this.work_area,
          specialization: this.specialization,
        };
        axios.post(cfg.routes.profileUpdate, data, {
          headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': data._token}
        }).then(res => {
          if (res.data.success) {
            // Cập nhật WEBAPP_CONFIG
            Object.assign(window.WEBAPP_CONFIG.customerProfile, res.data.customer);
            // Cập nhật tên trên profile hero
            const profileName = document.querySelector('#page-profile .profile-name');
            if (profileName) profileName.textContent = this.name;
            showToast('✓ Đã lưu thông tin hồ sơ!');
            closeSubpage('editprofile');
          }
        }).catch(err => {
          if (err.response && err.response.status === 422) {
            const valErrors = err.response.data.errors || {};
            this.errors = Object.values(valErrors).flat();
          } else {
            showToast('Lỗi lưu hồ sơ, thử lại!');
          }
        }).finally(() => {
          this.saving = false;
        });
      }
    };
  }
  </script>


