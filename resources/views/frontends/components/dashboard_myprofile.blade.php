<div class="content">
    <div class="dashbard-menu-overlay"></div>
    @include('components.dashboard.sidebar')

    <!-- dashboard content -->
    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        <div class="container dasboard-container">
            <!-- dashboard-title -->
            @include('components.dashboard.header', ['title' => 'Chỉnh sửa hồ sơ'])

            @if(session('success'))
                <div class="alert" style="background:#f0fdf4;border:1px solid #86efac;color:#166534;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert" style="background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert" style="background:#fff7ed;border:1px solid #fb923c;color:#9a3412;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif
            <!-- dashboard-title end -->
            <!-- dasboard-wrapper-->
            <div class="dasboard-wrapper fl-wrap no-pag">
                <div class="row">
                    <div class="col-md-7">
                        <div class="dasboard-widget-title fl-wrap">
                            <h5><i class="fas fa-user-circle"></i>Thay đổi ảnh đại diện</h5>
                        </div>
                        <div class="dasboard-widget-box nopad-dash-widget-box fl-wrap" x-data="avatarUpload()">
                            <div class="edit-profile-photo">
                                <img :src="avatarUrl" class="respimg" alt="">
                                <div class="change-photo-btn">
                                    <div class="photoUpload">
                                        <span x-text="uploading ? 'Đang tải...' : 'Tải ảnh mới'"></span>
                                        <input type="file" x-ref="fileInput" class="upload" accept="image/jpeg,image/png,image/gif,image/webp" @change="uploadAvatar($event)" :disabled="uploading">
                                    </div>
                                </div>
                            </div>
                            <div class="bg-wrap bg-parallax-wrap-gradien">
                                <div class="bg" data-bg="{{ asset('images/bg/1.jpg') }}"></div>
                            </div>
                            <template x-if="uploadError">
                                <div style="padding:8px 16px;color:#991b1b;font-size:13px;" x-text="uploadError"></div>
                            </template>
                            <template x-if="uploadSuccess">
                                <div style="padding:8px 16px;color:#166534;font-size:13px;">✓ Cập nhật ảnh đại diện thành công!</div>
                            </template>
                        </div>

                        <div class="dasboard-widget-title fl-wrap">
                            <h5><i class="fas fa-key"></i>Thông tin cá nhân</h5>
                        </div>
                        <div class="dasboard-widget-box fl-wrap">
                            <form method="POST" action="{{ route('webapp.profile.update') }}" class="custom-form">
                                @csrf

                                @if($errors->any())
                                    <div style="background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;">
                                        <ul style="margin:0;padding-left:16px;">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <label>Họ và tên <span class="dec-icon"><i class="far fa-user"></i></span></label>
                                <input type="text" name="name" placeholder="Nhập họ tên"
                                    value="{{ old('name', $customer->name ?? '') }}" />

                                <label>Địa chỉ Email <span class="dec-icon"><i class="far fa-envelope"></i></span></label>
                                <input type="text" name="email" placeholder="example@domain.com"
                                    value="{{ old('email', $customer->email ?? '') }}" />

                                <label>Số điện thoại <span class="dec-icon"><i class="far fa-phone"></i></span></label>
                                <input type="text" name="mobile" placeholder="0901234567"
                                    value="{{ old('mobile', $customer->mobile ?? '') }}" />

                                <label>Địa chỉ <span class="dec-icon"><i class="fas fa-map-marker"></i></span></label>
                                <input type="text" name="address" placeholder="Đà Lạt, Lâm Đồng"
                                    value="{{ old('address', $customer->address ?? '') }}" />

                                <button type="submit" class="btn color-bg float-btn">Lưu thay đổi</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="dasboard-widget-title dbt-mm fl-wrap">
                            <h5><i class="fas fa-key"></i>Đổi mật khẩu</h5>
                        </div>
                        <div class="dasboard-widget-box fl-wrap" style="opacity:0.5;pointer-events:none;">
                            <div class="custom-form">
                                <p style="font-size:13px;color:#666;margin-bottom:12px;"><i class="fab fa-telegram" style="color:#229ED9;"></i> Tài khoản Telegram không sử dụng mật khẩu.</p>
                                <div class="pass-input-wrap fl-wrap">
                                    <label>Mật khẩu hiện tại <span class="dec-icon"><i class="far fa-lock-open-alt"></i></span></label>
                                    <input type="password" class="pass-input" placeholder="" value="" disabled />
                                    <span class="eye"><i class="far fa-eye" aria-hidden="true"></i></span>
                                </div>
                                <div class="pass-input-wrap fl-wrap">
                                    <label>Mật khẩu mới <span class="dec-icon"><i class="far fa-lock-alt"></i></span></label>
                                    <input type="password" class="pass-input" placeholder="" value="" disabled />
                                    <span class="eye"><i class="far fa-eye" aria-hidden="true"></i></span>
                                </div>
                                <div class="pass-input-wrap fl-wrap">
                                    <label>Xác nhận mật khẩu mới <span class="dec-icon"><i class="far fa-shield-check"></i></span></label>
                                    <input type="password" class="pass-input" placeholder="" value="" disabled />
                                    <span class="eye"><i class="far fa-eye" aria-hidden="true"></i></span>
                                </div>
                                <button class="btn color-bg float-btn" disabled>Lưu thay đổi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- dasboard-wrapper end -->

        </div>
    </div>
    <div class="dashbard-bg gray-bg"></div>
</div>

<script>
function avatarUpload() {
    return {
        avatarUrl: '{{ $customer->profile ?? asset("images/avatar/1.jpg") }}',
        uploading: false,
        uploadError: null,
        uploadSuccess: false,

        uploadAvatar(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Kiểm tra kích thước file phía client (2MB)
            if (file.size > 2 * 1024 * 1024) {
                this.uploadError = 'Ảnh không được vượt quá 2MB.';
                this.$refs.fileInput.value = '';
                return;
            }

            this.uploading = true;
            this.uploadError = null;
            this.uploadSuccess = false;

            const formData = new FormData();
            formData.append('avatar', file);

            // Đọc CSRF token từ meta tag (luôn fresh, tránh hết hạn)
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '{{ csrf_token() }}';
            formData.append('_token', csrfToken);

            axios.post('{{ route("webapp.profile.avatar") }}', formData, {
                headers: { 'Content-Type': 'multipart/form-data', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(response => {
                if (response.data.success) {
                    this.avatarUrl = response.data.url + '?t=' + Date.now();
                    this.uploadSuccess = true;
                    setTimeout(() => { this.uploadSuccess = false; }, 3000);
                }
            })
            .catch(error => {
                if (error.response?.status === 401) {
                    this.uploadError = 'Phiên đăng nhập hết hạn. Vui lòng mở lại ứng dụng.';
                } else if (error.response?.status === 419) {
                    this.uploadError = 'Phiên làm việc hết hạn. Vui lòng tải lại trang.';
                } else {
                    const msg = error.response?.data?.errors?.avatar?.[0]
                        ?? error.response?.data?.message
                        ?? 'Tải ảnh thất bại. Vui lòng thử lại.';
                    this.uploadError = msg;
                }
            })
            .finally(() => {
                this.uploading = false;
                // Reset input để có thể chọn lại cùng file nếu cần
                this.$refs.fileInput.value = '';
            });
        }
    }
}
</script>
