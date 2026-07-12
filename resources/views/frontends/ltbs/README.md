# Giao diện desktop mới (LTBS) — trạng thái & TODO

Bộ giao diện desktop mới thay thế theme cũ cho **Trang chủ**, **Danh sách BĐS**, **Chi tiết BĐS** và thêm **Dashboard desktop**. Dựng lại từ `Temp/design_handoff_dalat_bds` theo design-system Jost + token `--primary #3270FC`.

## Cấu trúc
- **Layout:** `resources/views/frontends/ltbs/master.blade.php` (+ `header`, `footer`).
- **Component:** `frontends/ltbs/components/` — `property_card`, `search_widget`, `zoning_sheet`, `pagination`.
- **Views:** `frontend_home.blade.php`, `frontend_properties_listing.blade.php`, `frontend_properties_detail.blade.php`, `frontend_dashboard_desktop.blade.php`.
- **Assets:** `public/css/ltbs-core.css` (tokens + Jost + `.ds-*` components) + `ltbs-{home,listing,detail,dashboard}.css`; `public/js/ltbs-{core,home,listing,detail}.js`; `public/fonts/jost/`.
- **Routes:** `/` (home), `/properties`, `/bds/{slug}` & `/property/{id}` (detail), `/webapp/desktop` (dashboard, guard `webapp`).

## Đã wire dữ liệu thật
- Home: BĐS mới nhất, quick-chips phường, số tin/phường.
- Listing: toàn bộ filter server-side (ward/category/price/rooms/legal/property_type/sort), phân trang, autocomplete đường, map Google Maps (pin lat/lng thật).
- Detail: gallery, 8 thông số (EAV), giá, related, **role-aware server-side** (guest/sale ẩn toạ độ + SĐT chủ; admin/bds_admin thấy đầy đủ + map chính xác).
- Dashboard: KPI, nguồn theo địa bàn, tin chờ xác minh, khách cần chăm — theo `Customer` đăng nhập.

## TODO (stub cần backend thật)
1. **Kiểm tra quy hoạch** (`zoning_sheet` + `ltbs-home.js` `qhCheck`): kết quả hiện là mock client. Cần API tra cứu quy hoạch (số tờ/thửa/toạ độ → kết quả clean/warning/danger/notfound).
2. **Tìm kiếm AI/NLP** (`search_widget` + `ltbs-home.js` `aiSearch`): hiện parse thô phía client rồi chuyển sang classic search. Cần NLP server-side + nhận diện giọng nói.
3. **Giá thị trường theo phường** (home section "Nhịp đập thị trường"): hiện hiển thị **số tin** thực; giá trung bình + máy tính ngân sách cần dùng bảng `market_prices` (đã có CRUD admin `webapp.admin.market-prices.*`).
4. **Bản đồ listing** (`ltbs-listing.js`): pin thật OK; còn thiếu **layer quy hoạch** (đất ở/TM-DV/nông nghiệp), **vẽ vùng tìm kiếm**, **cluster** nâng cao.
5. **Panel sale/admin ở trang chi tiết**: pipeline chăm khách, claim vai trò, donut hoa hồng theo tin — hiện là UI shell read-only. Cần API riêng cho luồng này.
6. **Feed thông báo ở Dashboard**: nối `InAppNotificationService`.
7. **Ảnh/nội dung demo**: dữ liệu seed local ở `scratchpad/seed_demo.php` chỉ để kiểm thử; production dùng dữ liệu thật.
