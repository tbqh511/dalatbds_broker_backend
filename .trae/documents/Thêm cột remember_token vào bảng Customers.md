# Kế hoạch Xử lý Lỗi `Unknown column 'remember_token'`

Lỗi này xảy ra do Laravel Session Guard mặc định cố gắng ghi vào cột `remember_token` khi thực hiện đăng nhập với tùy chọn "Remember Me" (`login($user, true)`), nhưng bảng `customers` hiện chưa có cột này. Cột `api_token` hiện có được dùng cho JWT Authentication và không nên dùng chung cho Session "Remember Me" cookie.

## Giải pháp Đề xuất: Thêm cột `remember_token`

Để đảm bảo tính năng "Remember Login" hoạt động đúng chuẩn Laravel và không ảnh hưởng đến cơ chế JWT hiện tại, chúng ta sẽ thêm cột `remember_token` vào bảng `customers`.

### Các bước thực hiện:

1. **Tạo Migration mới**:

   * Tạo file migration: `add_remember_token_to_customers_table`.

   * Nội dung: Thêm cột `$table->rememberToken();` (tương đương `VARCHAR(100) NULL`) vào bảng `customers`.

2. **Chạy Migration**: (Cái này để tôi chạy trên host chứ chạy local được không?)

   * Thực thi lệnh `php artisan migrate` để cập nhật cấu trúc database.

3. **Cập nhật Model** **`Customer`** **(Tùy chọn nhưng khuyến nghị)**:

   * Thêm `remember_token` vào mảng `$hidden` để tránh lộ token này khi trả về JSON response trong các API khác (giống như `password`).

4. **Kiểm chứng**:

   * Thử lại chức năng đăng nhập từ Telegram WebApp.

   * Kiểm tra database để xác nhận cột `remember_token` đã được điền dữ liệu sau khi đăng nhập thành công.

### Tại sao không dùng `api_token` thay thế?

* `api_token`: Dùng cho cơ chế Stateless Authentication (mỗi request gửi token lên header).

* `remember_token`: Dùng cho Session Authentication (Laravel tự động quản lý qua Cookie mã hóa).

* Việc tách biệt giúp hệ thống an toàn hơn và tránh xung đột logic giữa App Mobile (JWT) và WebApp (Session).

