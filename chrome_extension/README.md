# DalatBDS Broker Extension (Fix Pack)

Thư mục này chứa mã nguồn đã được sửa lỗi cho Chrome Extension, khắc phục các vấn đề `FrameDoesNotExistError` và thiếu file resource.

## Cấu trúc File

*   **`manifest.json`**: File cấu hình chính (Manifest V3). Đã thêm quyền truy cập cho `utils.js` và các file phụ trợ.
*   **`background.js`**: Service worker chính.
    *   **FIXED**: Đã thêm logic `try-catch` và kiểm tra `chrome.runtime.lastError` để xử lý lỗi khi gửi tin nhắn đến các frame/tab đã đóng.
    *   **FIXED**: Class `DelayedMessageSender` giờ đây xử lý graceful shutdown khi kết nối bị mất.
*   **`utils.js`**: Chứa các hàm tiện ích chung.
*   **`extensionState.js`**: Quản lý state của extension.
*   **`heuristicsRedefinitions.js`**: Định nghĩa các quy tắc xử lý (heuristics).

## Các lỗi đã khắc phục

1.  **`FrameDoesNotExistError: Frame ... does not exist`**
    *   **Nguyên nhân**: Extension gửi tin nhắn đến frame đã bị đóng.
    *   **Cách sửa**: Bắt lỗi trong `background.js` và bỏ qua nếu thông báo lỗi chứa chuỗi "Frame ... does not exist".

2.  **`Unchecked runtime.lastError: The message port closed...`**
    *   **Nguyên nhân**: Kết nối message port bị ngắt đột ngột.
    *   **Cách sửa**: Kiểm tra `chrome.runtime.lastError` trong callback của `sendMessage`.

3.  **`net::ERR_FILE_NOT_FOUND` (utils.js, etc.)**
    *   **Nguyên nhân**: File không tồn tại hoặc không được khai báo trong `web_accessible_resources`.
    *   **Cách sửa**: Đã tạo các file này và thêm vào `manifest.json`.

## Hướng dẫn Cài đặt

1.  Mở Chrome và truy cập `chrome://extensions`.
2.  Bật chế độ **Developer mode** (góc trên bên phải).
3.  Nhấn nút **Load unpacked**.
4.  Chọn thư mục `chrome_extension` trong dự án này.
5.  Kiểm tra console của background page (nhấn vào "service worker" link) để xem log hoạt động.

## Kiểm thử

Để kiểm thử fix lỗi `FrameDoesNotExistError`:
1.  Mở một tab mới.
2.  Mở Console của Service Worker extension.
3.  Chạy lệnh test (nếu có) hoặc sử dụng extension bình thường.
4.  Đóng tab nhanh chóng ngay khi extension đang xử lý.
5.  **Kết quả mong đợi**: Không có lỗi "Uncaught (in promise)" màu đỏ trong console; thay vào đó là cảnh báo màu vàng `[Handled] Frame ... is gone`.
