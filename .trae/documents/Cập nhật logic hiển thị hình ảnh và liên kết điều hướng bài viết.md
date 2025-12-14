1. **Cập nhật hình ảnh (data-bg):**
   - Thay thế toàn bộ logic lấy ảnh động `$getThumbnailUrl($prevPost)` bằng ảnh tĩnh cố định.
   - Sử dụng `asset('assets/images/posts/sidebar_banner.png')` cho thuộc tính `data-bg` của cả `prevPost` và `nextPost`.

2. **Cập nhật liên kết (href) và hàm `$makeNewsUrl`:**
   - Hiện tại hàm `$makeNewsUrl` nhận vào **object post** (`$p`) và tự trích xuất `slug` hoặc `id`.
   - Yêu cầu mới là: `href="{{ $makeNewsUrl($prevPost->slug) }}"` -> tức là hàm `$makeNewsUrl` sẽ nhận vào **string slug** trực tiếp, hoặc hàm vẫn nhận object nhưng logic gọi hàm thay đổi.
   - **Tuy nhiên**, để an toàn và linh hoạt nhất (và tuân thủ đúng yêu cầu "Cập nhật hàm $makeNewsUrl để nhận tham số là slug"), tôi sẽ sửa hàm `$makeNewsUrl` để nhận tham số đầu vào là `slug` (string).
   - Logic gọi hàm trong view sẽ đổi thành: `$makeNewsUrl($prevPost->post_name ?? $prevPost->slug)` (Lưu ý: Trong WordPress/Laravel DB hiện tại trường slug thường là `post_name`, cần kiểm tra kỹ để tránh lỗi null).
   - *Điều chỉnh*: Yêu cầu user ghi là `href="{{ $makeNewsUrl($prevPost->slug) }}"`. Tôi sẽ tuân thủ, nhưng sẽ thêm fallback `$prevPost->post_name` nếu `slug` không tồn tại trong model để tránh lỗi.

3. **Thực hiện thay đổi trong `resources/views/frontends/news/show.blade.php`:**
   - Sửa định nghĩa `$makeNewsUrl`.
   - Sửa logic hiển thị `data-bg`.
   - Sửa logic gọi hàm `$makeNewsUrl` trong thẻ `<a>`.

4. **Kiểm tra:**
   - Hình ảnh sẽ hiển thị cố định là `sidebar_banner.png`.
   - Link sẽ trỏ về `/tin-tuc/{slug}`.

*Lưu ý:* Route `/tin-tuc/{slug}` đã tồn tại và hoạt động (dựa trên các task trước), nên không cần sửa route, chỉ cần đảm bảo view tạo link đúng.