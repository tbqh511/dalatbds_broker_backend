Tôi sẽ cập nhật file `resources/views/frontend_dashboard_add_listing.blade.php` để thay đổi giá trị lưu trữ giới tính:

1.  **Cập nhật giá trị Radio Button**:
    *   Đổi `value="ong"` thành `value="1"`.
    *   Đổi `value="ba"` thành `value="2"`.

2.  **Cập nhật Logic hiển thị**:
    *   Sửa điều kiện hiển thị `Ông/Bà` từ kiểm tra `'ong'` sang kiểm tra `'1'`.

3.  **Cập nhật dữ liệu khởi tạo (AlpineJS)**:
    *   Đặt giá trị mặc định `formData.contact.gender` là `'1'` (thay vì `'ong'`).

Vì controller chỉ đơn giản là nhận và lưu giá trị được gửi lên, nên không cần sửa đổi gì ở phía backend.
