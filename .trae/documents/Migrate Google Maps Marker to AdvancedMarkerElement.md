Tôi đã phân tích vấn đề cảnh báo trong console và xác định nguyên nhân là do việc sử dụng lớp `google.maps.Marker` đã bị Google đánh dấu lỗi thời (deprecated). Để giải quyết vấn đề này, tôi sẽ tiến hành nâng cấp code để sử dụng `google.maps.marker.AdvancedMarkerElement` theo khuyến nghị mới nhất của Google.

### Kế hoạch thực hiện:

#### 1. Cập nhật `resources/views/property/create.blade.php`
-   Sửa hàm `initMap()`:
    -   Thay thế `new google.maps.Marker({...})` bằng `new google.maps.marker.AdvancedMarkerElement({...})`.
    -   Cập nhật logic xử lý sự kiện `dragend` (vì `AdvancedMarkerElement` trả về tọa độ khác một chút so với Marker cũ).
    -   Thêm `mapId` vào cấu hình khởi tạo Map (bắt buộc khi dùng Advanced Marker).

#### 2. Cập nhật `resources/views/property/edit.blade.php`
-   Thực hiện tương tự như file `create.blade.php`:
    -   Thay thế `Marker` bằng `AdvancedMarkerElement`.
    -   Cập nhật xử lý sự kiện kéo thả marker để cập nhật tọa độ vào form.
    -   Thêm `mapId` (có thể dùng ID mặc định hoặc tạo mới trên Google Cloud Console, ở đây tôi sẽ dùng một ID mẫu hoặc để trống nếu Google cho phép test, nhưng tốt nhất là thêm tham số `mapId: "DEMO_MAP_ID"` để test).

#### 3. Cập nhật các file JS công khai (nếu cần thiết)
-   Kiểm tra và sửa `public/js/map-single.js`, `public/assets/js/custom/custom.js` để đảm bảo tính đồng bộ trên toàn bộ dự án.

#### 4. Lưu ý về `loading=async`
-   Cảnh báo đầu tiên trong hình (`without loading=async`) nhắc nhở về cách tải script tối ưu. Tôi sẽ cập nhật thẻ `<script>` trong các file blade để thêm tham số `loading=async` vào URL hoặc thuộc tính của thẻ script nếu chưa có.

Bạn có đồng ý với kế hoạch nâng cấp này không? Nếu đồng ý, tôi sẽ bắt đầu chỉnh sửa code ngay.