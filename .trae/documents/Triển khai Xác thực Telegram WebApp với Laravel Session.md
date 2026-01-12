# Kế hoạch Triển khai Chứng thực Telegram WebApp

Để đảm bảo người dùng Telegram được xác thực và duy trì phiên đăng nhập khi truy cập các route `/webapp` (sử dụng Blade Templates), chúng ta cần chuyển từ cơ chế chỉ dùng JWT (Frontend) sang kết hợp **Session/Cookie (Server-side)**. Điều này giúp bảo vệ các route con (ví dụ `/webapp/profile`) khỏi truy cập trái phép.

## 1. Cấu hình Authentication (Backend)

Cần định nghĩa một Guard mới cho người dùng Telegram (Customer) sử dụng Session driver để Laravel có thể quản lý trạng thái đăng nhập.

* **File**: `config/auth.php`

* **Thao tác**:

  * Thêm guard `webapp` sử dụng driver `session` và provider `customers`.

  * Đảm bảo provider `customers` trỏ tới model `App\Models\Customer`.

## 2. Tạo Middleware Bảo vệ Route

Tạo một Middleware tùy chỉnh để kiểm tra trạng thái đăng nhập của người dùng Telegram trước khi cho phép truy cập vào các trang.

* **Tên Middleware**: `TelegramWebAppAuth`

* **Logic**:

  * Kiểm tra xem người dùng đã đăng nhập qua guard `webapp` chưa.

  * Nếu **ĐÃ** đăng nhập: Cho phép truy cập.

  * Nếu **CHƯA** đăng nhập:

    * Nếu đang ở trang chủ `/webapp`: Cho phép đi tiếp (để tải JS xử lý đăng nhập).

    * Nếu đang ở trang con (ví dụ `/webapp/profile`): Chuyển hướng về `/webapp`.

## 3. Cập nhật Logic Đăng nhập (API)

Sửa đổi API `loginViaMiniApp` để không chỉ trả về JWT token mà còn thiết lập Cookie phiên làm việc.

* **File**: `app/Http/Controllers/ApiController.php`

* **Hàm**: `loginViaMiniApp`

* **Thay đổi**:

  * Sau khi xác thực `initData` thành công.

  * Thực hiện `Auth::guard('webapp')->login($customer, true)` để tạo session "Remember Me".

  * Hoặc thủ công tạo Cookie chứa Token và gửi kèm response (tuy nhiên dùng Auth::login tiện hơn).

## 4. Cập nhật Route Web

Áp dụng middleware mới vào nhóm route `/webapp`.

* **File**: `routes/web.php`

* **Thao tác**: Bọc các route `/webapp/...` trong group middleware `telegram.webapp`.

## 5. Cập nhật Frontend (JavaScript)

Điều chỉnh script trên Blade View để đồng bộ với cơ chế mới.

* **Logic**:

  * Sau khi gọi API `/api/webapp/login` thành công (nhận response `authenticated`).

  * Thực hiện `window.location.reload()` để tải lại trang.

  * Lúc này Middleware sẽ thấy Cookie/Session và cho phép server render nội dung dashboard thay vì màn hình loading.

***

### Tóm tắt Luồng hoạt động mới:

1. **Lần đầu truy cập**: User vào `/webapp` -> Middleware thấy chưa login -> Hiện trang Loading (có JS).
2. **JS chạy**: Gửi `initData` lên API.
3. **API**: Validate OK -> Đăng nhập Session -> Trả về OK + Cookie.
4. **JS**: Nhận OK -> Reload trang.
5. **Reload**: Middleware thấy Session -> Render Dashboard -> User truy cập được các sub-route.

