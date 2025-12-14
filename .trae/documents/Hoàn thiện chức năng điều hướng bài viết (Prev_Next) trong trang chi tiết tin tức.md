1. **Cập nhật** **`resources/views/frontends/news/show.blade.php`**:

   * **Xử lý hình ảnh (Background Image)**:

     * Viết logic PHP (trong block `@php`) để kiểm tra và tạo URL hình ảnh chính xác cho bài viết trước/sau.

     * Sử dụng `Storage::url()` nếu ảnh lưu trong storage, hoặc `asset()` nếu ảnh trong public.

     * Nếu không có ảnh, sử dụng ảnh mặc định hoặc để trống `data-bg` và dùng màu nền fallback.

   * **Giao diện & Nội dung**:

     * Dịch "Prev" -> "Trước", "Next" -> "Sau".

     * Thêm CSS inline hoặc block `<style>` để:

       * Giới hạn chiều rộng nút (`max-width`).

       * Xử lý text quá dài (`text-overflow: ellipsis`, `white-space: nowrap`).

       * Thêm hiệu ứng hover (đổi màu, shadow).

       * Thiết lập màu nền fallback cho `.bg` khi không có ảnh.

   * **Logic & An toàn**:

     * Giữ nguyên logic kiểm tra `if($prevPost)` / `if($nextPost)`.

     * Đảm bảo các thuộc tính `post_title`, `slug` được truy cập an toàn (dùng `optional()` hoặc `??`).

2. **Cải thiện CSS (trực tiếp trong file view hoặc stack style)**:

   * `.content-nav li a`: Thêm `transition`, `max-width`, `overflow: hidden`.

   * `.content-nav li a:hover`: Thêm hiệu ứng visual.

   * `.content-nav-media .bg`: Thêm `background-color: #eee` (hoặc màu theme) để hiển thị khi ảnh chưa tải hoặc lỗi.

3. **Kiểm tra script**:

   * Đoạn script xử lý `data-bg` trong `scripts.js` khá đơn giản (`$(this).css("background-image", ...) ` ). Việc đảm bảo URL trong `data-bg` là hợp lệ sẽ ngăn chặn lỗi JS/CSS.

