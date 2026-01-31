Dựa trên hình ảnh bạn cung cấp và phân tích code, tôi nhận thấy vấn đề nằm ở cách hiển thị trạng thái **Disabled** của nút "Tiếp tục".

### Phân tích:
1.  **Vấn đề**: Trong hình, nút "Tiếp tục" có màu xanh đậm (`bg-primary`), tạo cảm giác là có thể bấm được, mặc dù trường "Tên đường" chưa được chọn (đang hiển thị placeholder).
2.  **Nguyên nhân trong code**:
    *   Button hiện tại được gán cứng class màu sắc `bg-primary text-white` trong thuộc tính `class` tĩnh.
    *   Khi logic validation trả về `false` (disabled), code chỉ thêm class `opacity-40`.
    *   Kết quả là button chỉ bị mờ đi một chút nhưng vẫn giữ màu xanh, khiến người dùng khó phân biệt với trạng thái active.
3.  **Giải pháp**:
    *   Cần thay đổi logic CSS để khi disabled, button chuyển hẳn sang màu xám (ví dụ: `bg-gray-200 text-gray-400`) để thể hiện rõ ràng là không thể bấm được.
    *   Cách làm này cũng đồng bộ với nút "Xác nhận vị trí" trong popup bản đồ.

### Kế hoạch sửa lỗi:
Tôi sẽ chỉnh sửa file `resources/views/frontend_dashboard_add_listing.blade.php`:
*   Di chuyển các class màu sắc (`bg-primary`, `text-white`, `shadow...`) từ thuộc tính `class` tĩnh sang thuộc tính `:class` động.
*   Cập nhật logic `:class`:
    *   **Disabled**: `bg-gray-200 text-gray-400 cursor-not-allowed`
    *   **Enabled**: `bg-primary text-white shadow-lg shadow-blue-200 hover:bg-blue-600 active:scale-[0.98]`

Tôi sẽ thực hiện thay đổi này ngay bây giờ.
