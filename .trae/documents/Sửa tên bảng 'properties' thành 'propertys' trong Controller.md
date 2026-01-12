# Kế hoạch Sửa Lỗi SQL: "Table properties doesn't exist"

Lỗi này xảy ra do sự nhầm lẫn về tên bảng trong câu truy vấn thủ công (Raw SQL) tại `TelegramWebAppController`.
- Tên bảng thực tế trong Database và Model: `propertys`.
- Tên bảng đang dùng trong câu query lỗi: `properties`.

## Giải pháp: Cập nhật Tên Bảng trong Controller

Chúng ta cần sửa lại tên bảng trong các câu truy vấn sub-query tại `app/Http/Controllers/TelegramWebAppController.php`.

### Chi tiết thay đổi:

1.  **File**: `app/Http/Controllers/TelegramWebAppController.php`
2.  **Vị trí**: Hàm `index`, bên trong logic tính toán `$stats`.
3.  **Thay đổi**:
    *   Sửa `from('properties')` thành `from('propertys')` trong câu query đếm `reviews_count`.
    *   Sửa `from('properties')` thành `from('propertys')` trong câu query đếm `favourites_count`.

Sau khi sửa đổi, câu truy vấn sẽ khớp với tên bảng thực tế (`propertys`) và hoạt động bình thường.
