Qua kiểm tra file `frontend_dashboard_add_listing.blade.php`, tôi phát hiện các thành phần đang bị **hard code** (gán cứng) sau đây cần được xử lý để đảm bảo tính động và dễ bảo trì:

1.  **Danh sách Loại giấy tờ (Legal Types)**:
    *   *Hiện tại:* Đang khai báo cứng trong JavaScript (dòng 199-205).
    *   *Giải quyết:* Chuyển mảng dữ liệu này sang Controller và truyền xuống View.

2.  **Danh sách Hướng nhà (Directions)**:
    *   *Hiện tại:* Đang khai báo cứng trong JavaScript (dòng 209).
    *   *Giải quyết:* Chuyển mảng dữ liệu này sang Controller.

3.  **Mức hoa hồng (Commission Rates)**:
    *   *Hiện tại:* Đang gán cứng mảng `[1, 1.5, 2, 2.5, 3]` ngay trong vòng lặp HTML `x-for` (dòng 1170).
    *   *Giải quyết:* Định nghĩa mảng này trong Controller và truyền xuống, giúp dễ dàng thay đổi chính sách hoa hồng sau này.

4.  **Tọa độ mặc định bản đồ (Đà Lạt)**:
    *   *Hiện tại:* `const defaultPos = { lat: 11.940419, lng: 108.458313 };` (dòng 432).
    *   *Giải quyết:* Tạm thời giữ nguyên vì ứng dụng đặc thù cho Đà Lạt, nhưng nếu cần mở rộng sẽ đưa vào Config. (Trong phạm vi task này tôi sẽ tập trung xử lý 3 mục dữ liệu trên trước).

---

### Kế hoạch thực hiện:

#### Bước 1: Cập nhật `TelegramWebAppController.php`
Tôi sẽ sửa method `addListing` để chuẩn bị các dữ liệu trên:
```php
// ...
$legalTypes = [
    ['value' => 'Sổ riêng xây dựng', 'name' => 'Sổ riêng xây dựng', 'icon' => 'fa-file-contract'],
    ['value' => 'Sổ riêng nông nghiệp', 'name' => 'Sổ riêng nông nghiệp', 'icon' => 'fa-file-contract'],
    // ... các loại khác
];

$directions = ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'];

$commissionRates = [1, 1.5, 2, 2.5, 3];

return view('frontend_dashboard_add_listing', compact(..., 'legalTypes', 'directions', 'commissionRates'));
```

#### Bước 2: Cập nhật `frontend_dashboard_add_listing.blade.php`
Thay thế các giá trị hard code bằng biến được truyền từ Controller:
*   `legalTypes: @json($legalTypes)`
*   `directions: @json($directions)`
*   Thêm `commissionRates: @json($commissionRates)` vào Alpine data.
*   Sửa template: `<template x-for="rate in commissionRates">`.

Việc này giúp code sạch hơn và quản lý dữ liệu tập trung tại Controller (hoặc sau này là Database/Config).
