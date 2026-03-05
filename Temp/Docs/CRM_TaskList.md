🏡 ĐÀ LẠT BĐS CRM — TASK LIST TRIỂN KHAI
Hệ thống CRM BĐS trên nền tảng Laravel Backend + Telegram WebApp
Tổng số tasks: 42   |   🔴 Ưu tiên Cao: 35   |   🟡 Trung bình: 7   |   Migration: 4   Backend: 26   Telegram: 15
ID
Module
Tên Task
Mô Tả Chi Tiết / Acceptance Criteria
Layer
Assignee
Ưu Tiên
Est.
1.1
DB Migration
Tạo migration: thêm cột status vào bảng crm_deals_commission
Thêm ENUM: pending_deposit | deposited | notarizing | completed | cancelled. Default: pending_deposit
Migration
TREA
Cao
0.5h
1.2
DB Migration
Tạo migration: chuẩn hóa cột status trong bảng crm_deals_products
ENUM: sent_info | sent_location | sent_legal | customer_feedback | booking_created | viewed_success | viewed_failed | negotiating | waiting_finance
Migration
TREA
Cao
0.5h
1.3
DB Migration
Tạo migration: tạo bảng crm_deals_product_bookings (nếu chưa có)
Các cột: id, crm_deals_products_id (FK), booking_date, booking_time, status (ENUM), customer_feedback (text nullable), internal_note (text nullable), created_at, updated_at
Migration
TREA
Cao
1h
1.4
DB Migration
Kiểm tra & tạo index cho các bảng liên quan
Index: crm_deals_products(deal_id), crm_deals_products(property_id), crm_deals_product_bookings(crm_deals_products_id), crm_deals_commission(deal_id, sale_id)
Migration
TREA
Trung bình
0.5h
2.1
Backend - Model
Tạo/cập nhật Model CrmDealsProduct
Thêm $casts status => Enum, khai báo $fillable đầy đủ, quan hệ: belongsTo Deal, belongsTo Property, hasMany Bookings
Backend
TREA
Cao
1h
2.2
Backend - Model
Tạo Model CrmDealsProductBooking
Khai báo $fillable, $casts (booking_date => date, status => Enum), quan hệ: belongsTo CrmDealsProduct
Backend
TREA
Cao
1h
2.3
Backend - Model
Cập nhật Model CrmDealsCommission
Thêm cột status vào $fillable và $casts. Thêm quan hệ belongsTo Deal, belongsTo Property, belongsTo Sale (User)
Backend
TREA
Cao
0.5h
2.4
Backend - Model
Tạo Enum Classes cho Laravel
Tạo: App\Enums\DealsProductStatus, App\Enums\BookingStatus, App\Enums\CommissionStatus với đầy đủ cases và label() method tiếng Việt
Backend
TREA
Cao
1.5h
3.1
Bất Động Sản
API: Tạo BĐS (POST /api/properties)
Validate đầy đủ thông tin, lưu DB với status=pending_verify. Return property_id
Backend
TREA
Cao
2h
3.2
Bất Động Sản
API: Operator verify BĐS (PATCH /api/properties/{id}/verify)
Operator duyệt BĐS, cập nhật status=verified, trigger gửi notification cho customer + user owner
Backend
TREA
Cao
2h
3.3
Bất Động Sản
Notification: Gửi thông báo khi BĐS được duyệt
Gửi qua message channel (Telegram bot) tới: customer liên quan + user chủ BĐS. Nội dung: tên BĐS, địa chỉ, link xem chi tiết
Backend + Telegram
TREA
Cao
2h
3.4
Bất Động Sản
Telegram: Form tạo BĐS trên Webapp Telegram
Multi-step form: Loại BĐS → Vị trí → Pháp lý → Giá → Upload ảnh → Xác nhận. Gọi API backend
Telegram
TREA
Cao
4h
4.1
Leads
API: Tạo Lead thủ công (POST /api/leads)
User/Sale tạo lead, lưu thông tin: tên, SĐT, nhu cầu, nguồn. Status default: new
Backend
TREA
Cao
1.5h
4.2
Leads
API: Operator gán Lead cho Sale (POST /api/leads/{id}/assign)
Gán lead_id + sale_id, tạo bản ghi deal_assign, trigger notification cho sale được gán
Backend
TREA
Cao
1.5h
4.3
Leads
Notification: Thông báo Sale khi nhận Lead mới
Gửi Telegram message tới Sale: tên lead, SĐT, nhu cầu sơ bộ, link mở webapp để xử lý
Telegram
TREA
Cao
1.5h
4.4
Leads
Telegram: Giao diện quản lý Lead cho Sale
List lead được gán, xem chi tiết, nút 'Xác nhận Lead' / 'Liên hệ thành công' chuyển sang tạo Deal
Telegram
TREA
Cao
3h
5.1
Deal
API: Tạo Deal từ Lead đã xác nhận (POST /api/deals)
Sale xác nhận nhu cầu khách → tạo Deal. Liên kết lead_id, customer_id, sale_id. Status: active
Backend
TREA
Cao
2h
5.2
Deal
Notification: Thông báo tạo Deal thành công
Gửi thông báo tới: Public group, Sale Admin group, Sale phụ trách, Customer, User chủ BĐS (nếu có). Template đầy đủ thông tin deal
Backend + Telegram
TREA
Cao
2.5h
5.3
Deal
API: Lấy danh sách Deal theo Sale (GET /api/deals)
Filter: status, date range. Include: customer info, property count, booking count, last activity
Backend
TREA
Cao
1.5h
5.4
Deal
API: Chi tiết Deal (GET /api/deals/{id})
Trả về đầy đủ: deal info, customer, danh sách crm_deals_products, bookings, commission info
Backend
TREA
Cao
1h
6.1
CRM - Chăm Khách
API: Gửi BĐS cho khách trong Deal (POST /api/deals/{id}/products)
Thêm property_id vào deal, tạo bản ghi crm_deals_products với status=sent_info. Kèm gửi Telegram tới customer
Backend + Telegram
TREA
Cao
2.5h
6.2
CRM - Chăm Khách
API: Cập nhật trạng thái chăm sóc BĐS (PATCH /api/deals/products/{id})
Sale cập nhật status: sent_location, sent_legal, customer_feedback, v.v. Bắt buộc note nếu status=viewed_failed
Backend
TREA
Cao
1.5h
6.3
CRM - Chăm Khách
API: Lấy danh sách BĐS đã gửi trong Deal (GET /api/deals/{id}/products)
Trả về list BĐS kèm status hiện tại, note, booking gần nhất, lịch sử cập nhật
Backend
TREA
Cao
1h
6.4
CRM - Chăm Khách
Telegram: Giao diện chăm sóc BĐS cho Sale
Card từng BĐS với inline buttons: Cập nhật trạng thái | Đặt lịch xem | Xem phản hồi. Hiển thị timeline trạng thái
Telegram
TREA
Cao
4h
6.5
CRM - Chăm Khách
Notification: Gửi thông tin BĐS cho Customer qua Telegram
Template gửi BĐS: ảnh đại diện, tên, địa chỉ, giá, diện tích, link xem chi tiết, nút phản hồi
Telegram
TREA
Cao
2h
7.1
Booking
API: Tạo lịch hẹn xem BĐS (POST /api/deals/products/{id}/bookings)
Tạo booking với: booking_date, booking_time, note. Status mặc định: scheduled. Validate không trùng lịch của sale
Backend
TREA
Cao
2h
7.2
Booking
API: Cập nhật kết quả sau khi xem BĐS (PATCH /api/bookings/{id})
Cho phép cập nhật status: completed_success | completed_negotiating | completed_failed | rescheduled | cancelled. Bắt buộc customer_feedback nếu failed
Backend
TREA
Cao
1.5h
7.3
Booking
API: Dời lịch hẹn (PATCH /api/bookings/{id}/reschedule)
Cập nhật booking_date, booking_time mới. Status → rescheduled. Gửi thông báo tất cả bên liên quan
Backend
TREA
Trung bình
1h
7.4
Booking
Notification: Thông báo tạo/thay đổi lịch xem BĐS
Gửi tới: Sale, Customer, User chủ BĐS. Nội dung: địa chỉ BĐS, ngày giờ hẹn, tên các bên tham gia
Telegram
TREA
Cao
2h
7.5
Booking
Telegram: Giao diện đặt lịch và cập nhật kết quả xem nhà
Date/time picker, form nhập feedback sau xem, quick-reply buttons: Ưng ý | Đang thương lượng | Không ưng ý (bắt buộc note)
Telegram
TREA
Cao
3h
8.1
Commission
API: Tạo Commission khi chốt Deal (POST /api/deals/{id}/commission)
Lưu: app_commission, lead_commission, owner_commission, sale_commission, sale_id, property_id. Status: pending_deposit
Backend
TREA
Cao
2h
8.2
Commission
API: Cập nhật trạng thái Commission (PATCH /api/commissions/{id})
Chuyển trạng thái theo flow: pending_deposit → deposited → notarizing → completed | cancelled. Validate đúng thứ tự flow
Backend
TREA
Cao
1.5h
8.3
Commission
Notification: Thông báo khi có Commission mới / thay đổi trạng thái
Gửi tới: Sale phụ trách, Sale Admin. Nội dung: tên BĐS, địa chỉ, số tiền commission, trạng thái mới
Telegram
TREA
Cao
1.5h
8.4
Commission
API: Báo cáo Commission của Sale (GET /api/commissions/report)
Filter: sale_id, date range, status. Tổng hợp: tổng tiền theo status, số deal, danh sách chi tiết
Backend
TREA
Trung bình
2h
8.5
Commission
Telegram: Giao diện quản lý Commission cho Sale & Admin
Sale xem commission của mình. Admin xem tổng + filter theo sale. Nút cập nhật trạng thái (Admin only)
Telegram
TREA
Trung bình
3h
9.1
Notification
Tạo NotificationService tập trung (Laravel Service)
Service class xử lý tất cả gửi tin: sendToUser(), sendToGroup(), sendToCustomer(). Log lịch sử gửi. Retry khi fail
Backend
TREA
Cao
3h
9.2
Notification
Tạo Telegram Message Templates
Template cho: BĐS mới duyệt, Lead mới, Deal tạo, BĐS gửi khách, Lịch hẹn, Kết quả xem, Commission. Dùng Markdown format
Telegram
TREA
Cao
2h
9.3
Notification
Cấu hình Telegram Group Channel
Setup: Public group (thông báo BĐS mới), Sale Admin group, kênh riêng từng Sale. Cấu hình bot permission cho từng group
Telegram
TREA
Cao
2h
10.1
Testing
Viết Unit Test cho Enum Classes
Test đủ cases, test label() method, test transition hợp lệ/không hợp lệ giữa các status
Backend
TREA
Trung bình
1.5h
10.2
Testing
Viết Feature Test cho API: Property → Lead → Deal flow
Test toàn bộ luồng: tạo property → verify → tạo lead → gán sale → xác nhận → tạo deal
Backend
TREA
Trung bình
2h
10.3
Testing
Viết Feature Test cho API: Deal → Booking → Commission flow
Test luồng: gửi BĐS → đặt lịch → cập nhật kết quả → tạo commission → cập nhật trạng thái
Backend
TREA
Trung bình
2h
10.4
Testing
Manual Test: Toàn bộ luồng trên Telegram Webapp
Test thực tế: Sale thao tác trên Telegram, kiểm tra notification gửi đúng, dữ liệu lưu đúng DB
Telegram
TREA
Cao
3h

CHÚ THÍCH LAYER
■ Migration  Tạo/chỉnh sửa database schema qua Laravel migration file   ■ Backend  Laravel API, Model, Service, Controller   ■ Telegram  Telegram Webapp UI, Bot notifications   ■ Backend + Telegram  Cần triển khai cả hai layer
Lưu ý: Tất cả migration phải được review và chạy trước khi bắt đầu các task Backend liên quan đến bảng đó.
