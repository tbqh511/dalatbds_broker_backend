1. **Cập nhật FrontEndNewsController@show**:
   - Hiện tại, `FrontEndNewsController@show` (tại `app/Http/Controllers/FrontEndNewsController.php:226`) không eager load `tags` cho `$post`.
   - Tôi sẽ thêm `with('tags')` vào truy vấn lấy `$post` để lấy được tags của bài viết hiện tại.

2. **Cập nhật view `frontends/news/show.blade.php`**:
   - Tìm đến vị trí `div.list-single-tags` (dòng 148-156).
   - Thay thế mã tĩnh bằng vòng lặp hiển thị tags của bài viết.
   - Logic:
     - Kiểm tra nếu bài viết có tags (`$post->tags` không rỗng).
     - Duyệt qua từng tag.
     - Hiển thị mỗi tag dưới dạng thẻ `<a>` với class và cấu trúc giống sidebar.
     - Link trỏ đến route `news.tag` với slug của tag.

3. **Style và hiển thị**:
   - Sử dụng lại các class CSS `list-single-tags` và `tags-stylwrap` đã có trong sidebar (`frontends/news/components/news_sidebar.blade.php`) để đảm bảo giao diện đồng nhất (badge, hover effect, wrap).
   - Không cần thêm CSS mới vì đã kế thừa từ theme.

4. **Kiểm thử (kế hoạch)**:
   - Truy cập trang chi tiết một bài viết có gắn tags.
   - Xác nhận tags hiển thị đúng style.
   - Click vào tag để kiểm tra điều hướng đến trang danh sách bài viết theo tag.
   - Kiểm tra responsive (tự động xuống dòng) nhờ class `fl-wrap` và `tags-stylwrap`.