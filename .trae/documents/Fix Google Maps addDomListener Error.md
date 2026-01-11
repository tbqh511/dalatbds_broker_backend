Tôi đã phân tích lỗi "Uncaught TypeError: Cannot read properties of undefined (reading 'addDomListener')" trong file `map-single.js`.

### 1. Phân tích nguyên nhân
- **Lỗi:** `google.maps.event` là `undefined` tại thời điểm gọi `addDomListener`.
- **Nguyên nhân:** Do thay đổi cách tải Google Maps API sang chế độ bất đồng bộ (`loading=async` và dùng `importLibrary`), namespace `google.maps` có thể chưa sẵn sàng hoặc cấu trúc module mới thay đổi cách truy cập các sự kiện DOM.
- **Vị trí lỗi:** `google.maps.event.addDomListener(window, 'load', singleMap);` (dòng 167 file `public/js/map-single.js`).

### 2. Giải pháp đề xuất
Chúng ta cần thay thế phương thức lắng nghe sự kiện cũ của Google Maps bằng sự kiện chuẩn của trình duyệt, vì hàm `singleMap` giờ đây là hàm `async` và tự xử lý việc import thư viện.

**Các bước thực hiện:**
1.  **Sửa file `public/js/map-single.js`:**
    - Thay thế `google.maps.event.addDomListener(window, 'load', singleMap);` bằng `window.addEventListener('load', singleMap);`.
    - Điều này đảm bảo hàm `singleMap` được gọi khi trang tải xong mà không phụ thuộc vào `google.maps.event`.

2.  **Kiểm tra và cập nhật các file Blade (nếu cần):**
    - Đảm bảo script `map-single.js` được tải sau khi DOM đã sẵn sàng (thường đặt ở cuối `<body>` hoặc dùng `defer`). Hiện tại trong `master.blade.php` đã đặt ở cuối body, nên việc dùng `addEventListener` là an toàn.

Bạn có đồng ý với giải pháp này không? Nếu đồng ý, tôi sẽ tiến hành sửa lỗi ngay.