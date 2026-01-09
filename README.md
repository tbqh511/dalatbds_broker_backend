# E-Broker

## Page-specific layout flags

Some pages need to opt out of global layout pieces (newsletter and footer). You can disable these per-view by defining an empty section in your Blade child view:

```blade
@section('hide_newsletter')@endsection
@section('hide_footer')@endsection
```

When those sections are present, the `frontends.master` layout will skip including `frontends.newsletter` and `frontends.footer` respectively, avoiding empty containers or extra whitespace in the rendered HTML.

This change was added to support `resources/views/frontend_dashboard.blade.php` which hides both components.

## Third-party Libraries Updates

### CKEditor
- Downgraded to CKEditor 4.22.1 (Standard Edition) to avoid license key requirement in LTS versions (4.23.0+).
- The editor is now loaded via CDN in `create.blade.php` and `edit.blade.php` with `versionCheck: false`.

## Telegram User Check API

API này được sử dụng để kiểm tra người dùng Telegram và cấp phát/sử dụng lại JWT Token.

**Endpoint**: `POST /api/check_telegram_user`

**Headers**:
- `Content-Type: application/json`

**Body Parameters**:
- `telegram_id` (required, numeric): ID Telegram của người dùng.
- `secret` (required, string): Secret key để xác thực request (được cấu hình trong `.env` là `API_LOGIN_SECRET`).

**Logic xử lý**:
1. Xác thực `secret` và `telegram_id`.
2. Tìm kiếm khách hàng trong bảng `customers` theo `telegram_id`.
3. Nếu tìm thấy:
    - Kiểm tra trạng thái tài khoản (`isActive`).
    - Kiểm tra `api_token` hiện có trong database:
        - Nếu token tồn tại và còn hợp lệ: **Sử dụng lại token này**.
        - Nếu token không tồn tại hoặc đã hết hạn/lỗi: **Tạo token mới** và cập nhật vào database.
    - Trả về thông tin user và access token.
4. Nếu không tìm thấy:
    - Trả về trạng thái `guest`.

**Response (Authenticated)**:
```json
{
    "status": "authenticated",
    "message": "Người dùng đã tồn tại.",
    "user": {
        "id": 123,
        "name": "Nguyen Van A",
        "phone": "0909000111"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLC..."
}
```

**Response (Guest)**:
```json
{
    "status": "guest",
    "message": "Người dùng chưa tồn tại. Vui lòng gửi Contact."
}
```
