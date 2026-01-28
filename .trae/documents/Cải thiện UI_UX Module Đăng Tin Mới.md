# Kế hoạch cải thiện UI/UX cho module Đăng Tin Mới

Kế hoạch này tập trung giải quyết các vấn đề về căn chỉnh (alignment), hiển thị icon, tính nhất quán (consistency) và responsive cho file `frontend_dashboard_add_listing.blade.php`.

## 1. Phân tích vấn đề hiện tại (Dựa trên hình ảnh và yêu cầu)
- **Lỗi Icon dính chữ:** Icon trong các nút "Cần Bán" và "Cho Thuê" đang bị dính sát hoặc chồng lên text, kích thước icon quá lớn hoặc padding không đủ.
- **Khoảng cách chưa đều:** Padding bên trong các thẻ card chưa cân đối, khoảng cách giữa các phần tử (icon - text) quá hẹp.
- **Nút "Tiếp tục" phía dưới:** Nút màu xanh quá khổ (full width) dính sát mép dưới, cần kiểm tra lại padding container và shadow.
- **Màu sắc & Typography:** Cần tinh chỉnh lại font-weight và màu sắc phụ (text-gray-500) để tăng độ tương phản và dễ đọc.

## 2. Các hạng mục cải thiện chi tiết

### A. Cải thiện Step 0: Chọn loại giao dịch (Transaction Type)
- **Refactor Card Button:**
  - Tăng `gap` giữa icon và text.
  - Sử dụng Flexbox với `items-center` để căn giữa theo trục dọc chuẩn xác.
  - Điều chỉnh kích thước vòng tròn nền icon (`w-12 h-12` -> `w-14 h-14`) để icon "thở" hơn.
  - Thêm `shadow-sm` và hiệu ứng `hover:shadow-md`.
- **Icon:** Đảm bảo icon (FontAwesome) được căn giữa tuyệt đối trong vòng tròn nền.

### B. Chuẩn hóa Layout & Container
- **Container chính:** Kiểm tra `max-w-md` và `padding` của form container để đảm bảo không bị quá rộng trên desktop nhưng vẫn full-width trên mobile.
- **Header:** Căn chỉnh lại thanh progress bar và text "Bước 1/4".
- **Footer (Floating):**
  - Thêm padding an toàn cho footer (`safe-area-inset-bottom` cho mobile).
  - Điều chỉnh độ rộng và bo góc của nút bấm.

### C. Tinh chỉnh các bước tiếp theo (Contact, Type, Location...)
- Áp dụng cùng style guide cho các grid button (Loại BĐS, Tiện ích):
  - Đồng bộ `aspect-ratio`.
  - Căn giữa icon và text trong grid item.
  - Xử lý text dài (truncate hoặc line-clamp).

### D. Responsive & Mobile Optimization
- Kiểm tra các touch target (vùng chạm) tối thiểu 44px.
- Đảm bảo input field không bị zoom khi focus trên iOS (font-size >= 16px).

## 3. Các bước thực hiện (Execution Steps)
1.  **Bước 1: Sửa lỗi hiển thị ở Step 0 (Transaction Type).**
    - Tập trung vào class CSS của nút "Cần Bán" và "Cho Thuê".
    - Fix lỗi dính icon.
2.  **Bước 2: Cải thiện Header & Footer.**
    - Tinh chỉnh shadow và padding.
3.  **Bước 3: Rà soát và cập nhật style cho các bước còn lại.**
    - Áp dụng các class utility mới nhất quán.
4.  **Bước 4: Kiểm tra lại toàn bộ trên các kích thước màn hình giả lập.**

## 4. Kết quả mong đợi
- Icon hiển thị rõ ràng, tách biệt với text.
- Bố cục thoáng, cân đối.
- Trải nghiệm người dùng mượt mà, chuyên nghiệp.
