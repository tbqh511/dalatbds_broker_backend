# Bản nghiên cứu: Chức năng "Gửi BĐS cho khách" + Theo dõi trong CRM

> Phạm vi: Telegram WebApp (broker/sale). Mục tiêu: giúp sale gửi thông tin BĐS cho
> khách (qua Zalo thủ công + trang public có mã riêng), và để CRM ghi nhận đầy đủ
> "đã gửi BĐS nào, cho khách nào, phục vụ deal nào, khách phản hồi ra sao".

---

## 1. Quyết định nền tảng (đã chốt với chủ dự án)

1. **2 kênh gửi:**
   - **(A) Zalo — sale tự tay gửi.** Hệ thống chuẩn bị sẵn: text mô tả, link map,
     và **tải gói ảnh** (ảnh BĐS + ảnh pháp lý) để sale copy/tải rồi dán vào Zalo.
   - **(B) Trang public có mã riêng theo Deal** — link dạng `/share/{deal_code}`.
     Khách mở link xem đầy đủ ảnh + pháp lý + map + **nút phản hồi**. Mọi lượt xem
     và phản hồi tự đổ về CRM.
2. **Mã link gắn với DEAL**, không gắn với khách → 1 mã = 1 deal = biết
   *sale nào* gửi *cho khách nào* phục vụ *deal nào*. Mỗi BĐS trong deal là 1
   `CrmDealProduct`.
3. **Mô hình DB:** tự động tạo/dùng `CrmDeal`, mỗi BĐS gửi = 1 `CrmDealProduct`
   (tận dụng 100% hạ tầng `DealsProductStatus` đã có).

---

## 2. Hiện trạng codebase (đã phân tích)

### 2.1 Đã có sẵn (~60% hạ tầng)

| Thành phần | Vị trí | Ghi chú |
|---|---|---|
| Enum trạng thái gửi | `app/Enums/DealsProductStatus.php` | `sent_info`, `sent_location`, `sent_legal`, `customer_feedback`, `booking_created`, `viewed_success/failed`, `negotiating`, `waiting_finance` |
| Bảng BĐS đã gửi | `crm_deals_products` | cột `status`, `note`, `reason_dont_like` |
| UI chọn & gửi BĐS | `resources/views/webapp/subpages/clients.blade.php:371` | 2 bước: chọn BĐS → chọn loại nội dung (full / 📍 map / 📄 pháp lý / 🖼 gallery) + ghi chú |
| JS gọi API | `public/js/webapp-v2.js:7749` `sendPropToClient()` | POST `/webapp/api/send-property-to-client` |
| Endpoint gửi (webapp) | `app/Http/Controllers/TelegramWebAppController.php:1171` `sendPropertyToClientApi()` | có nhưng nhiều lỗi (mục 2.2) |
| API gửi qua Deal | `app/Http/Controllers/Api/DealProductApiController.php` | `store()` tạo CrmDealProduct + notify; `update()` đổi status; **luồng này đúng hướng nhất** |
| Booking sau khi xem | `app/Http/Controllers/Api/BookingApiController.php` + `crm_deals_product_bookings` | có cột `customer_feedback`, `internal_note` |
| Lịch sử chăm sóc | `crm_lead_activities` (model `CrmLeadActivity`) | type: call/note/assignment/status_change — **cần thêm type `sent_property`, `customer_feedback`** |

### 2.2 Lỗi/khoảng trống chặn việc chạy đúng

1. **CRM không ghi nhận gì** ở luồng `sendPropertyToClientApi()`: gửi Telegram xong là
   `return`, **không** tạo `CrmDealProduct`, **không** ghi `CrmLeadActivity`. → Đây
   chính là yêu cầu cốt lõi đang thiếu.
2. **Bug số nhiều/ít:** FE gửi `content_types` (mảng), BE đọc `content_type` (chuỗi)
   → luôn fallback `'full'`, bỏ qua lựa chọn pháp lý/map của sale.
   (`webapp-v2.js:7772` vs `TelegramWebAppController.php:1181`)
3. **`CrmCustomer` không có `telegram_id`** (bảng chỉ có `contact`). Controller đọc
   `$crmCustomer->telegram_id` → luôn rỗng → **message không bao giờ gửi đi**.
   Bản chất: khách cuối phần lớn **không dùng Telegram**, họ dùng **Zalo**.
4. **Pháp lý/gallery chỉ gửi text:** `NotificationService` chỉ có `sendMessage`,
   chưa có `sendPhoto`/`sendMediaGroup` → ảnh sổ đỏ (`PropertyLegalImage`) và ảnh
   thực tế không gửi được, chỉ ra link.
5. **Không có đường phản hồi** từ khách quay lại CRM.

**Kết luận:** không xây lại từ đầu. Sửa luồng cho đúng + bổ sung lớp tracking & trang
public share.

---

## 3. Kiến trúc đề xuất

### 3.1 Luồng dữ liệu tổng thể

```
Sale (WebApp) ──"Gửi BĐS"──▶ POST /webapp/api/send-property-to-client
   │
   ├─ 1. Đảm bảo có CrmDeal cho lead (tạo nếu chưa có)        ← Deal = đơn vị "mã share"
   ├─ 2. Mỗi property → upsert CrmDealProduct (status=sent_info)
   ├─ 3. Ghi CrmLeadActivity (type=sent_property, metadata=property_ids,kênh,nội dung)
   ├─ 4. Sinh / lấy deal_share_code (mã public của deal)
   └─ 5. Trả về cho FE:
         • share_url  = https://.../share/{code}        (gửi link cho khách)
         • zalo_text  = nội dung text đã format sẵn       (sale copy dán Zalo)
         • images[]   = URL ảnh BĐS + ảnh pháp lý          (sale tải về gửi Zalo)

Khách mở /share/{code} (KHÔNG cần login)
   │
   ├─ Xem: gallery ảnh, thông tin, pháp lý, Google Map embed
   ├─ Ghi nhận lượt xem → CrmDealProduct.status = sent_info→viewed (hoặc activity)
   └─ Nút phản hồi: [❤️ Quan tâm] [👎 Không hợp] [📅 Đặt lịch xem]
         → POST /share/{code}/feedback
         → cập nhật CrmDealProduct.status = customer_feedback / viewed_failed
         → ghi CrmLeadActivity(type=customer_feedback)
         → notify sale qua Telegram (sale CÓ telegram_id)
```

### 3.2 Vì sao "mã = Deal" là đúng

- 1 lead → 1 deal (đã có quan hệ `CrmLead::deal()` / `CrmDeal::lead()`).
- Deal đã gom: khách (`customer_id`), sale (qua `lead.user_id`/`sale_id`), danh sách
  BĐS (`products`), booking, hoa hồng. → Mã share theo deal cho khách 1 "trang tổng"
  các BĐS được giới thiệu, đồng thời CRM quy chiếu mọi tương tác về đúng deal/sale.

---

## 4. Thay đổi cần thực hiện

### 4.1 Database (migrations mới)

**a) Mã share cho deal** — thêm cột vào `crm_deals`:
```php
$table->string('share_code', 32)->nullable()->unique()->after('id');
// sinh bằng Str::random(10) hoặc hashids(deal_id) khi gửi lần đầu
```

**b) Kênh gửi cho khách** — `crm_customers` đang thiếu thông tin liên hệ phong phú.
Tối thiểu thêm (để biết gửi Zalo số nào, có Telegram không):
```php
// migration add_contact_channels_to_crm_customers
$table->string('zalo_phone', 20)->nullable();
$table->string('telegram_id', 32)->nullable(); // optional, nếu khách có
```
(Có thể tái dùng cột `contact` sẵn có làm SĐT Zalo mặc định.)

**c) Bảng log gửi (audit chi tiết theo từng lần gửi từng kênh)** — *khuyến nghị*
nếu muốn báo cáo "gửi mấy lần, kênh nào, lúc nào":
```php
Schema::create('crm_property_send_logs', function (Blueprint $t) {
    $t->id();
    $t->foreignId('deal_id');
    $t->foreignId('deal_product_id')->nullable(); // BĐS cụ thể
    $t->foreignId('lead_id')->nullable();
    $t->unsignedBigInteger('sent_by');             // Customer.id của sale/broker
    $t->string('channel');                          // 'zalo' | 'share_link' | 'telegram'
    $t->json('content_types');                      // ['full','location','legal','gallery']
    $t->text('note')->nullable();
    $t->timestamps();
});
```
> Lựa chọn tối giản: bỏ bảng log, chỉ dùng `CrmLeadActivity` (type=`sent_property`,
> lưu chi tiết trong `metadata` JSON). Đủ cho v1.

**d) Mở rộng enum activity type** (nếu DB enforce ENUM) hoặc chỉ thêm giá trị
chuỗi mới: `sent_property`, `customer_feedback`, `viewed_share`.

### 4.2 Backend

**`NotificationService`** — thêm:
- `sendPhoto($chatId, $photoUrl, $caption, $options)`
- `sendMediaGroup($chatId, array $photoUrls, $caption)` (album ảnh BĐS + pháp lý)
→ phục vụ kênh Telegram (sale + khách nếu có telegram_id) và để gửi cho chính sale
bản "đã gửi gì".

**Tạo `CrmSendPropertyService`** (theo pattern Service đã có trong repo):
- `getOrCreateDeal(CrmLead $lead): CrmDeal` — tự tạo deal + share_code lần đầu.
- `attachProperties(CrmDeal $deal, array $propertyIds, array $contentTypes, ?string $note, Customer $sender): Collection<CrmDealProduct>`
  - upsert `CrmDealProduct` (không trùng), status=`sent_info`.
  - ghi `CrmLeadActivity(type=sent_property, metadata={property_ids, channel, content_types, note})`.
  - (tùy chọn) ghi `crm_property_send_logs`.
- `buildZaloPayload(...)` → trả `{ text, images[], map_url, share_url }` cho FE.
- `recordCustomerView(deal_product)` / `recordCustomerFeedback(deal_product, verdict, note)`.

**Sửa `sendPropertyToClientApi()`** (`TelegramWebAppController.php:1171`):
- Đọc đúng `content_types` (mảng) thay vì `content_type`.
- Gọi `CrmSendPropertyService` để TẠO deal/products + GHI activity (phần đang thiếu).
- Trả về `share_url`, `zalo_text`, `images[]` cho FE.
- Bỏ phụ thuộc `$crmCustomer->telegram_id` làm điều kiện gửi; thay bằng:
  "đã ghi nhận trong CRM" (luôn true) + tách riêng việc gửi Telegram (chỉ khi có id).

**Controller mới `PropertyShareController`** (public, KHÔNG auth):
- `GET /share/{code}` → render trang gói BĐS của deal (gallery, info, pháp lý, map).
  - middleware: rate-limit + ẩn thông tin nhạy cảm chủ nhà (SĐT host) khỏi khách.
  - ghi nhận view (1 lần/session) → cập nhật status/activity.
- `POST /share/{code}/feedback` → body `{ deal_product_id, verdict: like|dislike|book, reason?, booking_date? }`
  - cập nhật `CrmDealProduct.status` (`customer_feedback` / `viewed_failed` + `reason_dont_like`).
  - tạo `CrmDealProductBooking` nếu verdict=book.
  - ghi `CrmLeadActivity(type=customer_feedback)`.
  - notify sale qua Telegram (sale có telegram_id).

### 4.3 Routes

```php
// routes/web.php — public, không telegram.webapp
Route::get('/share/{code}', [PropertyShareController::class, 'show'])->name('share.show');
Route::post('/share/{code}/feedback', [PropertyShareController::class, 'feedback'])
     ->middleware('throttle:30,1')->name('share.feedback');

// (đã có) POST /webapp/api/send-property-to-client → sửa controller
```

### 4.4 Frontend (WebApp)

**Sửa `sendPropToClient()`** (`webapp-v2.js:7749`):
- Sau khi nhận response: hiển thị **màn hình kết quả "Gửi qua Zalo"** thay cho toast,
  gồm:
  - nút **Copy nội dung** (đổ `data.zalo_text` vào clipboard),
  - nút **Tải ảnh** / mở từng ảnh `data.images[]` để lưu,
  - nút **Mở Zalo** (`https://zalo.me/{phone}` hoặc share intent),
  - nút **Copy link khách** (`data.share_url`).
- Hiển thị badge "✅ Đã ghi vào CRM — Deal #id".

**Gallery "BĐS đã gửi"** (`webapp-v2.js:7882`): thay `showToast('...đang phát triển')`
bằng dữ liệu thật từ `GET /api/deals/{id}/products` (đã có sẵn endpoint), render
status label + booking mới nhất + nút "Gửi thêm".

**Trang `/share/{code}`** (blade public mới): layout gọn cho mobile, gallery swipe,
khối pháp lý (ảnh sổ đỏ), Google Map embed (dùng `PLACE_API_KEY` đã có), 3 nút phản hồi.

---

## 5. Mapping nội dung gửi → dữ liệu nguồn

| Loại (UI) | content_type | Nguồn dữ liệu | Zalo (sale copy) | Telegram | Share page |
|---|---|---|---|---|---|
| Toàn bộ | `full` | title, category, price, area, address, slug | text + link share | text + link | section info |
| Vị trí 📍 | `location` | `latitude/longitude` → Google Maps URL | text + map link | text (link preview) | Map embed |
| Pháp lý 📄 | `legal` | `legal` param + **`PropertyLegalImage`** | text + **tải ảnh sổ** | `sendMediaGroup` ảnh | gallery pháp lý |
| Hình ảnh 🖼 | `gallery` | `propery_image` + `title_image` | text + **tải ảnh** | `sendMediaGroup` | gallery ảnh |

> Pháp lý là điểm yếu lớn nhất của "gửi link website" → trang share + gói tải Zalo
> giải quyết trực tiếp hạn chế này.

---

## 6. CRM ghi nhận gì (đáp ứng yêu cầu cốt lõi)

Sau khi triển khai, với mỗi lần gửi, CRM biết:

- **Khách nào**: `CrmDeal.customer_id` → `CrmCustomer`.
- **Sale/broker nào gửi**: `CrmLeadActivity.actor_id` + `send_log.sent_by` + `deal.lead.user_id/sale_id`.
- **BĐS nào đã gửi**: `CrmDealProduct` (mỗi BĐS 1 dòng) với `status`.
- **Gửi gì, kênh nào, khi nào**: `CrmLeadActivity.metadata` / `crm_property_send_logs`.
- **Khách phản hồi thế nào**:
  - `CrmDealProduct.status`: `customer_feedback` (quan tâm) / `viewed_failed` + `reason_dont_like` (không hợp).
  - `CrmDealProductBooking` nếu khách đặt lịch xem.
  - `CrmLeadActivity(type=customer_feedback)` ghi mốc thời gian + nội dung.

---

## 7. Lộ trình triển khai (đề xuất theo giai đoạn)

**Giai đoạn 1 — Sửa cho chạy đúng + ghi CRM (ưu tiên cao nhất, ~nửa ngày)**
1. Migration: `crm_deals.share_code`, mở rộng activity types.
2. `CrmSendPropertyService`: getOrCreateDeal + attachProperties + ghi activity.
3. Sửa `sendPropertyToClientApi()`: đọc `content_types`, gọi service, trả `zalo_text`/`images`/`share_url`.
4. FE: màn hình kết quả "Gửi qua Zalo" (copy text + tải ảnh + copy link).
5. FE: gallery "BĐS đã gửi" đọc dữ liệu thật.

**Giai đoạn 2 — Trang public share + phản hồi (~1 ngày)**
6. `PropertyShareController` + route + blade `/share/{code}`.
7. Phản hồi khách → cập nhật status + activity + notify sale.
8. `NotificationService::sendMediaGroup/sendPhoto` (cho Telegram + notify sale).

**Giai đoạn 3 — Hoàn thiện (tùy chọn)**
9. Bảng `crm_property_send_logs` + báo cáo "BĐS đã gửi / tỉ lệ phản hồi" trong dashboard sale.
10. Đặt lịch xem ngay từ trang share (đổ vào `crm_deals_product_bookings`).
11. Theo dõi "đã xem link" (view tracking) hiển thị cho sale.

---

## 8. Rủi ro & lưu ý

- **Bảo mật trang share:** mã `share_code` phải khó đoán (≥10 ký tự random). Ẩn SĐT
  chủ nhà / thông tin nội bộ khỏi trang khách. Cân nhắc cho phép sale **thu hồi** link.
- **Pháp lý nhạy cảm:** mặc định KHÔNG public ảnh sổ đỏ trên trang share nếu chưa
  bật; để sale chủ động chọn (đúng với checkbox "Giấy tờ pháp lý" đã có).
- **Zalo không có API gửi tự động (gói free):** nên hướng "chuẩn bị sẵn để sale dán"
  là đúng thực tế, không cố tự động hóa gửi Zalo.
- **Trùng dữ liệu:** `attachProperties` phải upsert (đã có check trùng trong
  `DealProductApiController::store`) để gửi lại không tạo bản ghi rác.
- **Quyền:** chỉ `user_id`/`sale_id` của lead mới được gửi (đã có check trong
  `sendPropertyToClientApi`). Giữ nguyên.
```
