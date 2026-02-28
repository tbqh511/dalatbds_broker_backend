# WebApp Optimization Spec

## Why
Hiện tại, webapp đang gặp phải một số vấn đề nghiêm trọng về hiệu năng và khả năng bảo trì:
1.  **Hiệu năng kém (N+1 Query)**: Các controller (đặc biệt là `ApiController` và `PropertController`) thực hiện truy vấn cơ sở dữ liệu bên trong vòng lặp, gây ra số lượng lớn truy vấn không cần thiết.
2.  **Khó bảo trì (Fat Controller)**: `ApiController.php` quá lớn (>4000 dòng), chứa logic nghiệp vụ phức tạp thay vì chỉ xử lý request/response.
3.  **Tải trang chậm**: Các tài nguyên frontend (CSS/JS) được tải riêng lẻ thay vì gộp và nén, làm tăng số lượng request HTTP.
4.  **Thiếu Caching**: Dữ liệu tĩnh hoặc ít thay đổi (như Slider, Category) được truy vấn liên tục từ DB mỗi lần request.

## What Changes
- **Refactoring Backend Architecture**:
    - Chuyển logic nghiệp vụ từ `ApiController` sang các Service Classes (`PropertyService`, `UserService`, `CategoryService`).
    - Sử dụng `Repository Pattern` để tách biệt truy vấn dữ liệu (nếu chưa có sẵn Repository phù hợp).
- **Database Optimization**:
    - Sử dụng **Eager Loading** (`with()`) để tải trước các quan hệ (relationships) và loại bỏ lỗi N+1.
    - Tối ưu hóa các câu truy vấn phức tạp.
- **Caching Strategy**:
    - Implement caching (Redis hoặc File) cho các dữ liệu ít thay đổi như: Danh mục, Slider, Cấu hình hệ thống.
- **Frontend Asset Optimization**:
    - Cấu hình **Vite** để bundle và minify CSS/JS.
    - Sử dụng `lazy loading` cho hình ảnh và component không cần thiết ngay lập tức.

## Impact
- **Affected specs**: Hiệu năng API, cấu trúc thư mục Codebase.
- **Affected code**:
    - `app/Http/Controllers/ApiController.php`
    - `app/Http/Controllers/PropertController.php`
    - `app/Services/` (New/Modified)
    - `resources/views/layouts/` (Modified asset loading)
    - `vite.config.js` (Modified)

## ADDED Requirements
### Requirement: Service Layer Implementation
Hệ thống PHẢI sử dụng Service Layer để xử lý logic nghiệp vụ cho các tính năng chính:
- **Property Management**: Tạo, sửa, xóa, tìm kiếm bất động sản.
- **User Management**: Đăng ký, đăng nhập, cập nhật hồ sơ.

#### Scenario: Refactor Property Listing
- **WHEN** Client gọi API lấy danh sách bất động sản (`get_property_list`).
- **THEN** Controller gọi `PropertyService`, Service gọi Repository để lấy dữ liệu (đã eager load), và trả về kết quả đã được format (qua Resource/Transformer).

## MODIFIED Requirements
### Requirement: Optimized Data Fetching
Các truy vấn danh sách có quan hệ (VD: Bất động sản kèm Parameter, Category) PHẢI sử dụng Eager Loading.
**Reason**: Loại bỏ lỗi N+1 Query.

### Requirement: Asset Loading
Các file CSS/JS chính PHẢI được bundle và load thông qua Vite manifest thay vì load lẻ tẻ.
**Reason**: Giảm số lượng request HTTP, tăng tốc độ tải trang.
