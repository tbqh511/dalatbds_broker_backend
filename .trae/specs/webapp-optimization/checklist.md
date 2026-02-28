* [x] Checkpoint 1: Controller Size Reduction

  * [x] `ApiController.php` đã giảm ít nhất 30% kích thước (hoặc tách logic user/property thành công).

  * [x] `PropertController.php` đã tách logic xử lý form (FormRequest) và query (Service/Repo).

* [x] Checkpoint 2: N+1 Query Fixes

  * [x] Các endpoint `get_property_list` (API) đã sử dụng `with()` cho `parameters`, `category`.

  * [x] Các loop trong `PropertController` không còn gọi `Customer::find()` hoặc `Property::where()` bên trong vòng lặp.

* [x] Checkpoint 3: Asset Loading

  * [x] Các file CSS/JS chính (app.css, app.js) được load qua Vite (`@vite`).

  * [ ] Các thư viện JS lớn (jQuery, FilePond) được lazy load hoặc bundle hợp lý. (Giữ nguyên để tránh lỗi UI)

* [x] Checkpoint 4: Code Quality

  * [x] Sử dụng `FormRequest` thay vì `$validator` trực tiếp trong Controller.

  * [x] Các hardcoded values (nếu có) đã được chuyển vào config.

