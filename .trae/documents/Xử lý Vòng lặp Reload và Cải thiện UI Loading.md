# Kế hoạch Xử lý Vòng lặp Reload và Hiển thị Loading trên WebApp

Dựa trên phân tích, nguyên nhân chính của việc trang web liên tục hiển thị "Đang tải dữ liệu..." và reload là do sự **bất đồng bộ giữa trạng thái Cookie/Session trên trình duyệt và kiểm tra Auth phía Server**.

Cụ thể:
1.  **Frontend**: Gửi API login thành công -> Nhận Cookie -> Gọi `window.location.reload()`.
2.  **Trình duyệt**: Do chạy trong Iframe và thiếu cấu hình `SameSite=None` + `Secure` (như đã phân tích ở bước trước), Cookie **bị chặn/không gửi đi** khi reload.
3.  **Backend (Sau reload)**: Kiểm tra `Auth::guard('webapp')->check()` thấy `false` (do không có cookie) -> Render lại trang Loading + Script Login.
4.  **Kết quả**: Script lại chạy, lại login thành công, lại reload -> **Vòng lặp vô tận**.

Mặc dù chúng ta đã sửa config Session ở bước trước, nhưng để giải quyết triệt để và an toàn hơn cho Frontend (tránh trải nghiệm reload liên tục nếu cookie vẫn lỗi), tôi đề xuất kế hoạch sau:

## 1. Cải tiến Logic Frontend (JavaScript)
Thay vì reload ngay lập tức, chúng ta sẽ kiểm tra xem việc set cookie có thành công hay không và xử lý mềm dẻo hơn.

*   **Bước 1**: Sau khi API trả về `authenticated`, không reload ngay.
*   **Bước 2**: Thực hiện một request kiểm tra nhẹ (ví dụ: gọi lại chính trang này hoặc một API check session) để xem Cookie đã thực sự được trình duyệt chấp nhận chưa.
*   **Bước 3**:
    *   Nếu Cookie hoạt động: Mới thực hiện `window.location.reload()`.
    *   Nếu Cookie không hoạt động (vẫn lỗi Iframe): Hiển thị thông báo lỗi rõ ràng cho người dùng (ví dụ: "Vui lòng mở bằng trình duyệt ngoài") thay vì reload vô tận.

## 2. Thêm Cơ chế "Force Reload Limit"
Để ngăn chặn vòng lặp vô tận làm treo ứng dụng người dùng:
*   Sử dụng `sessionStorage` để đếm số lần reload tự động.
*   Nếu reload quá 3 lần trong thời gian ngắn mà vẫn chưa login được -> Dừng lại và hiển thị lỗi.

## 3. Tinh chỉnh Giao diện Loading
*   Thêm timeout cho màn hình loading. Nếu sau 10 giây mà chưa xử lý xong -> Hiển thị nút "Thử lại" hoặc thông báo lỗi, tránh treo mãi ở màn hình "Đang tải".

## Chi tiết Thay đổi Code (Frontend Dashboard Blade)

Sẽ cập nhật đoạn script trong `resources/views/frontend_dashboard.blade.php`:

```javascript
// Pseudo-code logic mới
if (data.status === 'authenticated') {
    // Kiểm tra vòng lặp reload
    let reloadCount = parseInt(sessionStorage.getItem('tg_auth_reload_count') || 0);
    if (reloadCount > 3) {
        document.getElementById('webapp-status').innerText = "Lỗi xác thực: Không thể lưu phiên đăng nhập. Vui lòng thử mở bằng trình duyệt ngoài.";
        return; 
    }
    
    sessionStorage.setItem('tg_auth_reload_count', reloadCount + 1);
    window.location.reload();
}
```
Và logic xóa đếm khi login thành công (phía server render dashboard).

## Bước tiếp theo
Tôi sẽ tiến hành cập nhật code Frontend theo hướng an toàn này để dứt điểm tình trạng reload vô tận, đồng thời cung cấp phản hồi rõ ràng hơn cho người dùng khi gặp lỗi môi trường.
