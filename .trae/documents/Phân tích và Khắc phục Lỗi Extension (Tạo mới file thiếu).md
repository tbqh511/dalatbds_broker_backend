# Kế hoạch Khắc phục Lỗi Extension

Hiện tại, tôi đã kiểm tra toàn bộ thư mục dự án `dalatbds_broker_backend` và xác nhận rằng các file `background.js`, `extensionState.js`, và `heuristicsRedefinitions.js` **không tồn tại** trong mã nguồn hiện tại. Các lỗi `net::ERR_FILE_NOT_FOUND` trong hình ảnh cũng xác nhận việc thiếu các file này.

Dựa trên log lỗi và yêu cầu của bạn, tôi đề xuất kế hoạch tái tạo và khắc phục như sau:

## 1. Phân tích Chi tiết Lỗi (Dựa trên Log)

### A. `background.js`
*   **Lỗi**: `FrameDoesNotExistError: Frame ... does not exist` và `runtime.lastError: The message port closed...`.
*   **Loại lỗi**: Runtime Error (Race Condition).
*   **Nguyên nhân**:
    *   Extension cố gắng gửi tin nhắn (`sendMessage`) đến một frame hoặc tab đã bị đóng hoặc điều hướng sang trang khác.
    *   Xử lý bất đồng bộ không bắt (catch) lỗi khi promise bị từ chối.
*   **Giải pháp**: Thêm `catch` block cho các lệnh gọi `sendMessage` và kiểm tra `runtime.lastError`.

### B. `utils.js`, `extensionState.js`, `heuristicsRedefinitions.js`
*   **Lỗi**: `net::ERR_FILE_NOT_FOUND`.
*   **Loại lỗi**: Configuration/Deployment Error.
*   **Nguyên nhân**:
    *   Các file này được khai báo trong `manifest.json` hoặc import trong code nhưng không có thực tế trong thư mục build/extension.
*   **Giải pháp**: Tạo mới các file này với cấu trúc chuẩn.

## 2. Giải pháp Đề xuất: Tạo và Sửa Code

Tôi sẽ tạo một thư mục mới `chrome_extension/` (để tách biệt với backend Laravel) và tạo các file sau:

### Bước 1: Tạo cấu trúc file
*   `chrome_extension/manifest.json`: Khai báo đúng các script background và permission.
*   `chrome_extension/background.js`: Cài đặt logic gửi tin nhắn an toàn.
*   `chrome_extension/utils.js`: Chứa các hàm tiện ích (placeholder).
*   `chrome_extension/extensionState.js`: Quản lý state (placeholder).
*   `chrome_extension/heuristicsRedefinitions.js`: (placeholder).

### Bước 2: Sửa lỗi logic trong `background.js`
Sử dụng pattern an toàn để tránh crash extension:
```javascript
// Minh họa giải pháp
function safeSendMessage(tabId, message) {
    chrome.tabs.sendMessage(tabId, message).catch(err => {
        // Bỏ qua lỗi Frame không tồn tại - đây là điều bình thường khi tab đóng nhanh
        if (err.message.includes("Frame") && err.message.includes("does not exist")) return;
        if (chrome.runtime.lastError) return; // Bỏ qua lastError đã check
        console.warn("Message delivery failed:", err);
    });
}
```

### Bước 3: Đảm bảo tương thích
*   Đảm bảo `utils.js` được load trước các file phụ thuộc khác trong `manifest.json`.

## 3. Kiểm thử & Tài liệu
*   **Test Case**: Giả lập việc đóng tab ngay sau khi gửi tin nhắn để verify fix `FrameDoesNotExistError`.
*   **Docs**: Tạo file `chrome_extension/README.md` hướng dẫn cài đặt và debug.

Bạn có đồng ý để tôi tạo thư mục `chrome_extension` và các file này để khắc phục vấn đề không?