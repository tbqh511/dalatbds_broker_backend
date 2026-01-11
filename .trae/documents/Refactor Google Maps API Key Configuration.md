Tôi đã phân tích codebase và lập kế hoạch chi tiết để chuyển đổi API Key sang biến môi trường và tăng cường bảo mật.

### Kế hoạch thực hiện:

#### 1. Cấu hình & Môi trường
- **Cập nhật `config/services.php`**: Thêm cấu hình `google_maps` để quản lý tập trung key từ `.env`.
  ```php
  'google_maps' => [
      'place_api_key' => env('PLACE_API_KEY'),
  ],
  ```
- **Cập nhật `.env.example`**: Thêm biến `PLACE_API_KEY=` để hướng dẫn dev mới.

#### 2. Refactor Code (Loại bỏ Hardcode)
- **File `resources/views/frontends/master.blade.php`**: Thay thế chuỗi key cứng bằng `{{ config('services.google_maps.place_api_key') }}`.
- **File `resources/views/property/create.blade.php` & `edit.blade.php`**: Chuyển từ gọi trực tiếp `env('PLACE_API_KEY')` sang dùng `config(...)` (chuẩn Laravel để hỗ trợ caching config).

#### 3. Validation (Kiểm tra khi khởi chạy)
- **File `app/Providers/AppServiceProvider.php`**: Thêm logic vào hàm `boot()` để kiểm tra xem `PLACE_API_KEY` có tồn tại không. Nếu thiếu, hệ thống sẽ ghi log cảnh báo (warning) để admin biết mà không làm sập app ngay lập tức.

#### 4. Tài liệu & Bảo mật
- **Cập nhật `README.md`**:
    - Thêm hướng dẫn cấu hình `PLACE_API_KEY`.
    - **Bảo mật**: Thêm khuyến nghị quan trọng về việc **giới hạn HTTP Referrer** cho API Key trên Google Cloud Console (chỉ cho phép domain của dự án chạy key này) để ngăn chặn việc bị đánh cắp quota.

Bạn có đồng ý với kế hoạch này không? Nếu đồng ý, tôi sẽ bắt đầu thực hiện ngay.