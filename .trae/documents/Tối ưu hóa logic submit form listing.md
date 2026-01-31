Tôi sẽ thực hiện các thay đổi tối ưu hóa quy trình submit form trong file `TelegramWebAppController.php` theo các yêu cầu chi tiết của bạn.

### Kế hoạch thực hiện:

1.  **Chỉnh sửa `submitForm` trong `TelegramWebAppController.php`**:
    *   **Xử lý CrmHost**:
        *   Lấy giá trị `gender` từ request và lưu vào bảng `crm_hosts`.
        *   Thêm logic format số điện thoại: loại bỏ ký tự không phải số, chuyển `0` đầu thành `84`.
    *   **Xử lý Property Title**:
        *   Điều chỉnh logic tạo tiêu đề: bỏ dấu phẩy sau hành động (Bán/Cho thuê), thêm hậu tố " - Đà Lạt".
    *   **Xử lý Property Address**:
        *   Cập nhật logic tạo địa chỉ: thêm " - Đà Lạt, Tỉnh Lâm Đồng" vào cuối chuỗi địa chỉ.
        *   Áp dụng logic tương tự cho `client_address`.
    *   **Cập nhật các trường Property khác**:
        *   Gán `post_type = 1`.
        *   Lưu `street_code` và `ward_code` từ input.
        *   Đảm bảo `latitude` và `longitude` được lưu chính xác (đã có code, sẽ kiểm tra lại validation).
    *   **Validation**:
        *   Thêm validation cho `latitude`, `longitude` (nullable, numeric).

### Chi tiết thay đổi Code dự kiến:

**1. Format số điện thoại:**
```php
$phone = $contact['phone'] ?? $customer->phone ?? '';
// Remove non-numeric
$phone = preg_replace('/[^0-9]/', '', $phone);
// Convert 0xxx to 84xxx
if (substr($phone, 0, 1) === '0') {
    $phone = '84' . substr($phone, 1);
}
```

**2. Tạo Title & Address:**
```php
// Title: "Bán nhà, An Sơn, Phường Cam Ly - Đà Lạt"
$actionName = ($propertyType == 0) ? 'Bán' : 'Cho thuê';
$titleParts = [$actionName . ' ' . strtolower($catName)]; // "Bán nhà"
if ($streetName) $titleParts[] = $streetName;
if ($wardName) $titleParts[] = $wardName;
$title = implode(', ', $titleParts) . ' - Đà Lạt';

// Address: "An Sơn, Phường Cam Ly - Đà Lạt, Tỉnh Lâm Đồng"
$addressParts = [];
if ($houseNumber) $addressParts[] = $houseNumber;
if ($streetName) $addressParts[] = $streetName;
if ($wardName) $addressParts[] = $wardName;
$address = implode(', ', $addressParts) . ' - Đà Lạt, Tỉnh Lâm Đồng';
```

**3. Lưu Property:**
```php
$property->post_type = 1;
$property->street_code = $streetId;
$property->ward_code = $wardId;
// ... latitude/longitude logic
```

Tôi sẽ tiến hành áp dụng các thay đổi này ngay sau khi bạn xác nhận.
