# Khắc phục lỗi Google Maps API: Xung đột `mapId` và `styles`

## Nguyên nhân
Lỗi `Google Maps JavaScript API: A Map's styles property cannot be set when a mapId is present` xuất hiện do bạn đang cố gắng sử dụng đồng thời:
1.  **`mapId`**: Được sử dụng để kích hoạt Vector Maps và tính năng `AdvancedMarkerElement` (đang được dùng trong code của bạn).
2.  **`styles`**: Một mảng JSON định nghĩa giao diện bản đồ (màu sắc đường, ẩn hiện các địa điểm, v.v.).

Google Maps không cho phép dùng `styles` (client-side styling) khi đã có `mapId` (cloud-based styling).

## Giải pháp
Vì code của bạn đang sử dụng `AdvancedMarkerElement` (dòng 3 và 28 trong `public/js/map-single.js`), thuộc tính `mapId` là **bắt buộc**. Do đó, chúng ta phải loại bỏ thuộc tính `styles`.

### Các bước thực hiện:
1.  **Chỉnh sửa file**: `public/js/map-single.js`
2.  **Thao tác**: Xóa hoặc comment lại thuộc tính `styles` trong cấu hình khởi tạo `new Map(...)`.

**Lưu ý**: Sau khi xóa `styles` trong code, bản đồ sẽ hiển thị với giao diện mặc định. Nếu bạn muốn tùy chỉnh giao diện (ví dụ: làm mờ bớt các địa điểm hành chính, đổi màu đường...), bạn cần truy cập **Google Cloud Console**, chọn Map Style editor, và gán style đó cho `DEMO_MAP_ID` (hoặc Map ID thực tế bạn đang dùng).
