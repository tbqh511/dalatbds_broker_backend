# Kế hoạch Xử lý Vòng lặp Redirect Telegram WebApp

Sau khi phân tích kỹ lưỡng mã nguồn và hiện tượng "refresh liên tục", tôi xác định nguyên nhân chính nằm ở cấu hình **Session Cookie** không tương thích với môi trường Iframe của Telegram.

## Nguyên nhân chi tiết
1.  **Môi trường Iframe**: Telegram WebApp chạy ứng dụng của bạn trong một Iframe.
2.  **Cấu hình hiện tại**: `config/session.php` đang thiết lập `'same_site' => 'lax'`.
3.  **Cơ chế lỗi**:
    *   Trình duyệt (Chrome/Safari) coi Iframe là "third-party context".
    *   Cookie với `SameSite=Lax` bị chặn khi set từ response của API Login.
    *   Khi trang reload, request mới không mang theo Cookie session.
    *   Server thấy chưa login -> Trả về trang login (JS) -> JS lại gọi API Login -> Reload -> **Vòng lặp vô tận**.

## Giải pháp: Cấu hình lại Session Cookie

Để Cookie hoạt động được trong Iframe (và trên Mobile), chúng ta cần thay đổi cấu hình `config/session.php` để hỗ trợ Cross-Site request.

### Bước 1: Cập nhật `config/session.php`
*   Thay đổi `same_site` từ `lax` thành `none`.
*   Đảm bảo `secure` được set thành `true` (vì `SameSite=None` bắt buộc phải đi kèm với `Secure`).

### Bước 2: Cập nhật biến môi trường `.env` (Nếu cần)
*   Kiểm tra và đảm bảo `SESSION_SECURE_COOKIE=true` trong file `.env` trên server production. (Bước này tôi sẽ thêm todo để nhắc bạn kiểm tra, vì tôi không sửa trực tiếp file .env trên server).

### Bước 3: Kiểm tra và dọn dẹp Cache
*   Chạy `php artisan config:clear` để xóa cache cấu hình cũ.

## Kế hoạch thực hiện
1.  Sửa file `config/session.php`: Cập nhật `secure` và `same_site`.
2.  Chạy lệnh xóa cache config.
3.  Thông báo hoàn tất và hướng dẫn bạn kiểm tra lại trên thiết bị thật.
