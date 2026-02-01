Tôi sẽ thực hiện các thay đổi trong file `app/Http/Controllers/TelegramWebAppController.php` để đồng bộ logic lưu trữ hình ảnh với `ApiController.php`.

**Chi tiết các thay đổi:**

1.  **Ảnh tiêu đề (Title Image):**
    *   Thay đổi đường dẫn lưu từ `images/property_images/` sang `public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH')`.

2.  **Ảnh bộ sưu tập (Gallery Images):**
    *   Thay đổi đường dẫn lưu từ `images/property_images/` sang `public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $property->id`.
    *   Thêm logic kiểm tra và tạo thư mục nếu chưa tồn tại.

3.  **Ảnh pháp lý (Legal Images):**
    *   Thay đổi đường dẫn lưu từ `images/property_legal_images/` sang `public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $property->id`.
    *   Lưu chung thư mục với Gallery Images theo đúng yêu cầu.

4.  **Ảnh 3D (3D Image):**
    *   Thêm logic xử lý cho `threeD_image` (hiện tại chưa có).
    *   Đường dẫn lưu: `public_path('images') . config('global.3D_IMG_PATH')`.

5.  **Ảnh tham số (Parameter Images):**
    *   Cập nhật logic xử lý parameters để hỗ trợ upload file hình ảnh cho tham số (giống API).
    *   Đường dẫn lưu: `public_path('images') . config('global.PARAMETER_IMAGE_PATH')`.

Tôi sẽ tiến hành chỉnh sửa mã nguồn ngay sau khi bạn xác nhận.