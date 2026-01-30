Vì bảng `crm_hosts` không có cột `email` và bạn chưa có ý định lưu trữ thông tin này, tôi sẽ tiến hành loại bỏ dòng code gán email trong quá trình tạo host mới.

### Kế hoạch thực hiện:
1.  **Chỉnh sửa Controller**:
    *   Mở file [TelegramWebAppController.php](file:///Users/huythai/Documents/GitHub/dalatbds_broker_backend/app/Http/Controllers/TelegramWebAppController.php).
    *   Tìm đến dòng 318 trong phương thức `submitForm`.
    *   Xóa dòng code: `$host->email = $customer->email ?? '';`.

Việc này sẽ giải quyết lỗi `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'email'` bằng cách không cố gắng lưu dữ liệu vào cột không tồn tại nữa.
