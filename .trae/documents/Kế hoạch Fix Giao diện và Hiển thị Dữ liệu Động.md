# Kế hoạch Xử lý Vấn đề Giao diện và Dữ liệu Customer

Dựa trên phân tích mã nguồn và yêu cầu của bạn, tôi đề xuất kế hoạch thực hiện chi tiết như sau:

## 1. Xử lý Vấn đề Giao diện (CSS/HTML)
**Vấn đề:** Header bị vỡ layout, chữ "Tổng quan" nhảy dòng đè lên Menu quản lý.
**Nguyên nhân:** Do xung đột CSS layout flexbox trong component `header.blade.php`.
**Giải pháp:**
*   Chỉnh sửa file `resources/views/components/dashboard/header.blade.php`.
*   Thêm class CSS hoặc điều chỉnh lại cấu trúc HTML để đảm bảo các phần tử nằm trên cùng một hàng (row) và responsive.
*   Cụ thể: Kiểm tra class `dashboard-title` và các class con `dashboard-title-item`, `dashbard-menu-header`.

## 2. Giải thích Cơ chế Seamless Authentication
Tôi sẽ soạn một phần giải thích chi tiết về luồng xác thực, bao gồm:
*   **Init:** Telegram WebApp gửi `initData` (đã ký HMAC).
*   **Verify:** Server (Laravel) xác thực chữ ký này -> Đảm bảo request đến từ Telegram thật.
*   **Login:** Nếu đúng, server tạo Session Laravel (`Auth::guard('webapp')->login`) và trả về Cookie.
*   **Persist:** Trình duyệt lưu Cookie -> Các request sau tự động gửi Cookie -> Server nhận diện User qua Session.
*   **Frontend:** Logic trong file blade kiểm tra Session (`@if(Auth::check())`) để render nội dung hoặc hiển thị Loading/Login script.

## 3. Xử lý Hiển thị Dữ liệu Customer (Backend & Frontend)
Hiện tại View đang dùng dữ liệu tĩnh (hard-coded). Cần chuyển sang dữ liệu động từ Database.

### 3.1. Cập nhật Controller (`TelegramWebAppController`)
*   Sửa hàm `index` để lấy dữ liệu thực tế của User đang đăng nhập.
*   Lấy danh sách Bất động sản (Properties) của User đó.
*   Truyền biến `$customer` và `$properties` (hoặc các biến thống kê) sang View.

### 3.2. Cập nhật View (`dashboard_home.blade.php`)
*   Thay thế các số liệu tĩnh (124 tin, 1056 lượt xem...) bằng biến động từ Controller.
*   Ví dụ: `{{ $customer->properties->count() }}` thay cho `124`.

## 4. Kiểm thử
*   Kiểm tra lại giao diện Header sau khi fix CSS.
*   Kiểm tra số liệu thống kê hiển thị đúng với User test.

---
**Bắt đầu thực hiện:** Tôi sẽ tạo Todo list để thực hiện từng bước, bắt đầu từ việc fix lỗi giao diện Header (ưu tiên 1).
