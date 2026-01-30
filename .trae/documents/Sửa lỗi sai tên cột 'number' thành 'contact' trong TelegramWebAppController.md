Tôi đã xác nhận được vấn đề. Trong model `CrmHost` ([app/Models/CrmHost.php](file:///Users/huythai/Documents/GitHub/dalatbds_broker_backend/app/Models/CrmHost.php)), cột lưu số điện thoại/liên hệ được định nghĩa là `contact`, nhưng trong controller đang gọi là `number`, gây ra lỗi `Column not found`.

Kế hoạch sửa lỗi như sau:

1.  **Sửa file Controller**:
    -   Mở file [app/Http/Controllers/TelegramWebAppController.php](file:///Users/huythai/Documents/GitHub/dalatbds_broker_backend/app/Http/Controllers/TelegramWebAppController.php).
    -   Tại dòng **316** (trong method `submitForm`), đổi `$host->number` thành `$host->contact`.

```php
// Trước:
$host->number = $contact['phone'] ?? $customer->phone ?? '';

// Sau:
$host->contact = $contact['phone'] ?? $customer->phone ?? '';
```

Việc này sẽ khớp với cấu trúc bảng `crm_hosts` và khắc phục lỗi khi submit form.