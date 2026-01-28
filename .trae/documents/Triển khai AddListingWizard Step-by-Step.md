# Kế hoạch Triển khai Hệ thống AddListingWizard (Step-by-Step)

Kế hoạch này sẽ tái cấu trúc file `frontend_dashboard_add_listing.blade.php` để chuyển đổi từ form dài thành một wizard nhập liệu từng bước (progressive disclosure) sử dụng Alpine.js.

## 1. Cấu trúc Dữ liệu & Quản lý Trạng thái (State Management)
Cập nhật Alpine.js component `realEstateForm` để quản lý luồng chi tiết:

- **State mới:**
  - `currentStepIndex`: Số nguyên, theo dõi bước nhỏ hiện tại (0, 1, 2...).
  - `steps`: Mảng định nghĩa cấu hình các bước (Tiêu đề, logic validation, nhóm macro step tương ứng).
  - `validationErrors`: Object lưu trữ lỗi validation cho từng trường.
- **Logic:**
  - Map `currentStepIndex` về `step` (1-4) cũ để giữ tương thích với thanh Progress Bar phía trên.
  - Hàm `validateCurrentStep()`: Kiểm tra dữ liệu của bước hiện tại.
  - Hàm `nextStepWizard()`: Validate và chuyển sang bước tiếp theo.
  - Hàm `prevStepWizard()`: Quay lại bước trước.

## 2. Định nghĩa Luồng Nhập liệu (Workflow)
Chia nhỏ các form hiện tại thành chuỗi các màn hình đơn lẻ:

### Giai đoạn 1: Khởi tạo & Định danh (Macro Step 1)
1.  **Transaction Type:** Chỉ hiện 2 nút "Cần Bán" / "Cho Thuê". (Chọn xong tự chuyển bước).
2.  **Contact Info:** Form nhập Họ tên, SĐT, Ghi chú. (Nút "Tiếp tục").
3.  **Property Type:** Grid chọn loại BĐS. (Chọn xong tự chuyển bước).
4.  **Location (Ward):** Grid chọn Phường. (Chọn xong tự chuyển bước).
5.  **Location (Detail):** Chọn đường, số nhà, map picker. (Nút "Tiếp tục").

### Giai đoạn 2: Pháp lý & Giá (Macro Step 2)
6.  **Legal:** Chọn loại giấy tờ pháp lý.
7.  **Pricing:** Nhập Giá & Đơn vị.
8.  **Area:** Nhập Diện tích.
9.  **Commission:** Chọn mức hoa hồng.

### Giai đoạn 3: Chi tiết & Hình ảnh (Macro Step 3)
10. **Description:** Nhập mô tả.
11. **Images:** Upload ảnh Avatar, Pháp lý, Ảnh khác.
12. **Parameters:** Các thông số kỹ thuật (Phòng ngủ, hướng...).

### Giai đoạn 4: Tiện ích & Hoàn tất (Macro Step 4)
13. **Amenities:** Chọn tiện ích xung quanh.
14. **Review & Submit:** Màn hình tổng quan và nút Gửi.

## 3. Triển khai UI/UX & Validator
- **Refactor View:**
  - Sử dụng `x-show="currentStepIndex === X"` cho từng khối `div` thay vì hiển thị toàn bộ.
  - Thêm hiệu ứng `x-transition` mượt mà khi chuyển bước.
- **Component Validator:**
  - Hiển thị lỗi ngay dưới trường input (`x-show="errors.fieldName"`).
  - Disable nút "Tiếp tục" nếu dữ liệu chưa hợp lệ (hoặc hiển thị thông báo khi click).
- **Navigation Bar:**
  - Cập nhật thanh footer dính dưới đáy (Floating Footer):
    - Nút "Quay lại" luôn hiện (trừ bước 0).
    - Nút "Tiếp tục" chỉ hiện khi cần thiết (các bước nhập liệu).

## 4. Các bước thực hiện Code
1.  **Bước 1:** Cập nhật `Alpine.data` trong thẻ `<script>`: Thêm logic `steps`, `currentStepIndex`, và các hàm validation.
2.  **Bước 2:** Viết lại phần HTML của **Macro Step 1** theo cấu trúc từng bước nhỏ (Transaction -> Contact -> Type -> Location).
3.  **Bước 3:** Viết lại phần HTML của **Macro Step 2, 3, 4** tương tự.
4.  **Bước 4:** Cập nhật logic nút Submit cuối cùng để gom toàn bộ dữ liệu.
5.  **Bước 5:** Kiểm tra (Test) luồng đi, validation các trường hợp rỗng, sai định dạng.

## 5. Kết quả mong đợi
- Người dùng vào trang chỉ thấy duy nhất lựa chọn "Cần Bán/Cho Thuê".
- Giao diện tập trung, không bị rối mắt bởi quá nhiều trường nhập liệu.
- Dữ liệu được validate chặt chẽ từng bước trước khi cho phép đi tiếp.
