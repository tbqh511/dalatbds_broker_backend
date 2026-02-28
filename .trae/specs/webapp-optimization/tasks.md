# Tasks

* [x] Task 1: Refactor `ApiController` - User & Profile Management

  * [x] SubTask 1.1: Tạo `UserService` (nếu chưa có) và di chuyển logic đăng ký/đăng nhập/cập nhật user.

  * [x] SubTask 1.2: Sử dụng `UserResource` (API Transformer) để chuẩn hóa JSON response.

  * [x] SubTask 1.3: Refactor các endpoint liên quan đến user trong `ApiController`.

* [ ] Task 2: Refactor & Optimize `ApiController` - Property Management (Fix N+1)

  * [ ] SubTask 2.1: Tạo `PropertyService` (nếu chưa có) để xử lý logic thêm/sửa/xóa BĐS.

  * [ ] SubTask 2.2: Sửa lỗi N+1 Query trong `get_property_list` và `get_property_inquiry` bằng cách sử dụng `with()` (Eager Loading).

  * [ ] SubTask 2.3: Implement caching cho danh sách BĐS nổi bật (Slider, Featured Properties).

* [ ] Task 3: Refactor `PropertController` & Fix N+1 Queries

  * [ ] SubTask 3.1: Xác định và sửa lỗi N+1 trong vòng lặp `foreach` khi lấy danh sách `interested_users` và `customer`.

  * [ ] SubTask 3.2: Tách logic xử lý form (store/update) ra khỏi Controller (dùng FormRequest cho validation).

* [ ] Task 4: Frontend Asset Optimization (Vite)

  * [ ] SubTask 4.1: Kiểm tra và cập nhật `vite.config.js` để bundle các file CSS/JS chính.

  * [ ] SubTask 4.2: Cập nhật `layouts/main.blade.php` (hoặc tương đương) để sử dụng `@vite()` directive thay vì load file tĩnh lẻ tẻ.

  * [ ] SubTask 4.3: Minify và nén ảnh tĩnh nếu có thể.

# Task Dependencies

* \[Task 2] depends on \[Task 1] (Recommended order for consistency)

* \[Task 4] is independent and can be done in parallel.

