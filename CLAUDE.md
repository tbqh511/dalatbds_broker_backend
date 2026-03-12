# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

---

## Tổng quan dự án

**DalatBDS E-Broker** — nền tảng môi giới bất động sản Đà Lạt (Laravel 9, PHP 8+). Gồm 3 phần chính:

1. **Frontend công khai** — trang liệt kê BĐS, tin tức, trang agent
2. **Admin backend** — quản lý BĐS, người dùng, CRM, cài đặt hệ thống
3. **Telegram Mini App (WebApp)** — dashboard dành cho broker, chạy bên trong Telegram tại `/webapp/*`

---

## Lệnh thường dùng

```bash
# Cài đặt dependencies
composer install
npm install

# Chạy local
php artisan serve          # Laravel dev server
npm run dev                # Vite asset watcher

# Build production
npm run build

# Database
php artisan migrate
php artisan migrate:fresh  # Xóa toàn bộ bảng và chạy lại migration

# Format code (Laravel Pint)
./vendor/bin/pint

# Chạy test
php artisan test
./vendor/bin/phpunit --filter TestName   # Chạy 1 test cụ thể
```

---

## Kiến trúc hệ thống

### Xác thực — 2 hệ thống riêng biệt

| Bề mặt | Phương thức | Guard | Model |
|---|---|---|---|
| Admin backend | Laravel session | `web` | `App\Models\User` |
| Telegram WebApp | Laravel session | `webapp` | `App\Models\Customer` |
| Mobile API | JWT (`tymon/jwt-auth`) | `webapp` | `App\Models\Customer` |

**JWT Middleware** (`App\Http\Middleware\JwtMiddleware`, đăng ký là `jwt.verify`):
- Đọc claim `auth_model` trong token payload
- `auth_model=user` → dùng model `User`, set guard `web`
- `auth_model=customer` (mặc định) → kiểm tra token với cột `customers.api_token`, set guard `webapp`

**Telegram WebApp Middleware** (`TelegramWebAppAuth`, đăng ký là `telegram.webapp`):
- Production: kiểm tra `Auth::guard('webapp')` session
- Dev bypass: set `WEBAPP_DEV_MODE=true` và `WEBAPP_DEV_CUSTOMER_ID=<id>` trong `.env`

### 2 Model người dùng

- **`App\Models\Customer`** — người dùng mobile app / WebApp; có `telegram_id`, `api_token`, `role`, `isActive`
- **`App\Models\User`** — người dùng admin backend; có hệ thống phân quyền (xem `config/rolepermission.php`)

### Routes

- `routes/web.php` — public site, admin, Telegram WebApp (`/webapp/*`)
- `routes/api.php` — REST API (`/api/*`); toàn bộ route ghi dữ liệu đều cần middleware `jwt.verify`

### API Controllers — Cũ vs. Mới

- **`ApiController`** — controller monolithic lớn, xử lý hầu hết API cũ
- **`Api/` namespace** — các controller chuyên biệt mới hơn (ưu tiên dùng cho code mới):
  - `PropertyApiController`, `LeadApiController`, `DealApiController`, `DealProductApiController`, `BookingApiController`, `CommissionApiController`, `NewsPostApiController`, v.v.

Khi thêm API endpoint mới, tạo controller riêng trong `app/Http/Controllers/Api/`.

### Luồng CRM

```
CrmLead → convert → CrmDeal → CrmDealProduct → CrmDealProductBooking
                             ↘ CrmDealCommission
```

Models: `CrmLead`, `CrmDeal`, `CrmDealAssigned`, `CrmDealProduct`, `CrmDealProductBooking`, `CrmDealCommission`, `CrmHost`

### Repository / Service Layer

- `app/Repositories/` — `RepositoryInterface`, `CrmLeadRepositoryInterface`, `Eloquent/BaseRepository`, `Eloquent/CrmLeadRepository`
- `app/Services/` — `CrmLeadService`, `NotificationService`, `PropertyService`, `UserService`, `Telegram/TelegramMessageTemplates`

Tính năng CRM mới nên theo pattern này, không đặt logic trực tiếp trong controller.

### Helper toàn cục (auto-loaded)

- `app/Helpers/custom_helper.php` — `system_setting()`, xử lý ảnh/blurhash, Google API
- `app/Helpers/verify-permission_helper.php` — kiểm tra quyền admin

### Views

- `resources/views/frontends/` — partials/layout public site (`frontends.master`)
- `resources/views/frontend_dashboard_*.blade.php` — views Telegram WebApp
- `resources/views/admin/` — views admin backend
- Để ẩn newsletter/footer trong layout, thêm vào child view: `@section('hide_newsletter')@endsection` hoặc `@section('hide_footer')@endsection`

### Biến `.env` quan trọng

| Biến | Mục đích |
|---|---|
| `API_LOGIN_SECRET` | Xác thực server-to-server cho `POST /api/check_telegram_user` và `POST /api/login` |
| `PLACE_API_KEY` | Google Maps JavaScript API + Places API |
| `WEBAPP_DEV_MODE` | Đặt `true` để bỏ qua xác thực Telegram khi dev local |
| `WEBAPP_DEV_CUSTOMER_ID` | Customer ID để auto-login ở dev mode |

---

## Telegram WebApp (`/webapp/*`)

Đây là **Telegram Mini App** — giao diện dành cho broker chạy bên trong Telegram. Tất cả route dưới `/webapp/*` đều yêu cầu middleware `telegram.webapp` (session guard `webapp`, model `Customer`).

### Danh sách trang

| URL | View file | Mô tả |
|---|---|---|
| `/webapp` | `frontend_dashboard.blade.php` | Dashboard chính (thống kê, menu) |
| `/webapp/profile` | `frontend_dashboard_myprofile.blade.php` | Hồ sơ broker |
| `/webapp/listings` | `frontend_dashboard_listings.blade.php` | Danh sách BĐS của broker |
| `/webapp/add-listing` | `frontend_dashboard_add_listing.blade.php` | Đăng tin BĐS mới |
| `/webapp/edit-listing/{id}` | (dùng lại view add-listing) | Chỉnh sửa tin BĐS |
| `/webapp/add-customer` | `frontend_dashboard_add_customer.blade.php` | Thêm khách hàng + tạo lead |
| `/webapp/leads` | `frontend_dashboard_leads.blade.php` | Danh sách leads |
| `/webapp/leads/create` | `frontend_dashboard_lead_create.blade.php` | Tạo lead thủ công |
| `/webapp/leads/{id}/edit` | `frontend_dashboard_lead_edit.blade.php` | Chỉnh sửa lead |
| `/webapp/messages` | `frontend_dashboard_messages.blade.php` | Tin nhắn |
| `/webapp/bookings` | `frontend_dashboard_bookings.blade.php` | Lịch xem BĐS |
| `/webapp/feed` | `frontend_dashboard_feed.blade.php` | Feed BĐS |
| `/webapp/agents` | `frontend_dashboard_agents.blade.php` | Danh sách agents |

### Controller chính

**`TelegramWebAppController`** — xử lý toàn bộ WebApp views và form submissions:
- `addCustomer()` / `storeCustomer()` — tạo `CrmCustomer` + `CrmLead` cùng lúc; `user_id` trong lead là `Customer.id` của broker đang login (không lưu `telegram_id` trực tiếp, truy xuất qua `$lead->user->telegram_id`)
- `submitForm()` / `updateForm()` — đăng / cập nhật tin BĐS (`Property`)
- `toggleStatus()` — bật/tắt trạng thái tin đăng

**`CrmLeadController`** — CRUD leads từ WebApp (dùng guard `webapp`).

### Frontend stack của WebApp

- **Alpine.js** (CDN) — reactive UI, form validation
- **TomSelect** (CDN) — dropdown tìm kiếm đường phố
- **Axios** (CDN) — AJAX submit form
- **Tailwind CSS** — styling (build qua Vite)
- Tất cả WebApp views đều `@extends('frontends.master')` với `@section('hide_newsletter')` và `@section('hide_footer')`

### Form `/webapp/add-customer` — Logic quan trọng

File: `resources/views/frontend_dashboard_add_customer.blade.php`

**Progressive disclosure** — mỗi bước chỉ hiện khi bước trước hoàn thành:

1. Nhập SĐT (validate định dạng VN 10 số)
2. Nhập tên liên hệ
3. Chọn nhu cầu: Cần mua / Cần thuê
4. Chọn loại BĐS
5. Mức tài chính
6. Mục đích giao dịch
7. Ưu tiên khu vực (phường/xã)
8. Tên đường (TomSelect)

**Lưu ý kỹ thuật:**
- `lead_type` mặc định `''` (rỗng) — **không** để `'buy'` vì sẽ bỏ qua bước chọn nhu cầu
- `nameTouched` flag — chỉ hiện hint lỗi tên sau khi user đã focus vào ô rồi bỏ trống (`@blur`)
- Auto-focus vào ô tên khi số điện thoại vừa hợp lệ (dùng `$watch('isPhoneValid', ...)`)
- Nút "Lưu Khách Hàng": xám + icon khóa khi `!isFormValid`; xanh + icon lưu khi valid
- `isFormValid` yêu cầu: `phone` hợp lệ + `name` hợp lệ + `lead_type` được chọn

### Chạy WebApp ở local (dev mode)

```env
WEBAPP_DEV_MODE=true
WEBAPP_DEV_CUSTOMER_ID=1   # Customer ID để auto-login; để trống = dùng Customer::first()
```

---

## Thư viện bên thứ 3 — Lưu ý

- **CKEditor**: ghim ở v4.22.1 qua CDN với `versionCheck: false` — **không** nâng cấp lên 4.23.0+ (yêu cầu license key trả phí). Dùng trong `create.blade.php` / `edit.blade.php`.
- **Tin tức/blog**: cấu trúc dữ liệu kiểu WordPress (`news_posts`, `news_postmeta`, `news_terms`, `news_term_taxonomy`, `news_term_relationships`).
- **Địa danh**: phân cấp hành chính Việt Nam (`LocationsProvince` → `LocationsDistrict` → `LocationsWard` → `LocationsStreet`).
- **Sluggable**: `cviebrock/eloquent-sluggable` cho model Property và tin tức.
- **Role middleware**: `RoleMiddleware` xử lý phân quyền kiểu `role:admin,editor` trên các API route.
