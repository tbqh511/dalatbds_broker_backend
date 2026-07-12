# Handoff: Đà Lạt BĐS — Web Desktop (4 màn hình)

## Tổng quan
Bộ 4 màn hình cho nền tảng web desktop của Đà Lạt BĐS: trang chủ marketing, trang danh sách tin (list/map/split), trang chi tiết BĐS (role-aware: khách/sale/admin), và dashboard desktop cho eBroker/Sale. Đây là lớp sản phẩm **desktop** (bố cục nhiều cột, rộng 1200–1440px) — khác với Telegram Mini App phone-first (max 430px) đã có trong hệ thống thiết kế. Cả hai dùng chung token màu/type/spacing và bộ component `LTBSDesignSystem_ab8fed`.

## Về các file thiết kế
Các file `.dc.html` trong bộ này là **bản thiết kế tham khảo dựng bằng HTML** — prototype thể hiện đúng giao diện và hành vi dự kiến, **không phải code để copy thẳng vào production**. Việc cần làm là **dựng lại các thiết kế này trong môi trường hiện có của codebase** (Laravel Blade + `public/css/webapp-v2.css` / `public/js/webapp-v2.js` theo tài liệu hệ thống thiết kế, hoặc stack thực tế đang dùng) bằng đúng pattern, component, và convention hiện có của dự án — không đưa thêm thư viện/pattern lạ.

Mỗi file `.dc.html` là **prototype chạy được thật** — mở trực tiếp bằng trình duyệt (double-click hoặc kéo vào tab mới) là bấm thử được toàn bộ tương tác (search, filter, mở bản đồ quy hoạch, chuyển role, v.v.) mà không cần cài gì thêm. Toàn bộ asset tham chiếu (font, logo, token CSS, runtime `support.js`) đã được copy kèm theo đúng đường dẫn tương đối trong thư mục này — đừng xoá hay di chuyển file lẻ, cứ giữ nguyên cấu trúc thư mục khi mở.

## Mức độ hoàn thiện (Fidelity)
**High-fidelity.** Màu sắc, typography, spacing, icon, trạng thái hover/active, animation đều là giá trị cuối — dev nên bám sát pixel. Toàn bộ nội dung số liệu, tên khách, giá tiền, ảnh BĐS (Unsplash), avatar (pravatar.cc) đều là **dữ liệu giả lập để demo** — cần thay bằng dữ liệu thật/API thật trước khi lên production.

## Bối cảnh chung — áp dụng cho cả 4 màn hình
- **Header** (66px, sticky, nền trắng mờ `rgba(255,255,255,.86)` + `backdrop-filter: blur(12px)`, viền dưới `1px solid var(--border)`): logo trái, nav giữa (ẩn dưới ~1000px), CTA phải (`Đăng tin`/`Ký gửi` ghost-link + button "Mở app" solid primary). Giống hệt trên 3/4 màn hình (Dashboard có sidebar riêng thay vì header ngang).
- **Footer**: card gradient newsletter (trang chủ) + 4–5 cột (brand/liên kết/liên hệ/tải app), bottom bar copyright — lặp lại gần như nguyên trạng ở trang chủ và trang danh sách.
- **Component hệ thống dùng xuyên suốt** (`window.LTBSDesignSystem_ab8fed`, xem `_ds/.../_ds_bundle.js`): `Button`, `Icon`, `Badge`, `Input`, `Avatar`, `SectionHeader`. Dev nên map các component này sang component tương đương đã có trong codebase (đừng dựng lại từ đầu bằng CSS thuần).
- **Icon**: phần lớn dùng component `Icon` (bộ Lucide/Feather, 24×24, `stroke-width` 1.7, `stroke-linecap/linejoin: round`), nhưng nhiều chỗ trong 4 file này viết SVG inline trực tiếp (class `.ic`) thay vì gọi qua component — khi dựng lại, quy về dùng nhất quán bộ icon hệ thống của codebase, giữ đúng hình dạng/tỉ lệ nét.
- **Quy tắc màu icon trong khối feature/card dạng lưới** (vd: 4 ô "verify-rail", "broker-values" ở trang chủ; các icon trong card KPI/section ở dashboard): **luôn phẳng, một tông** — icon màu `var(--primary)` trên nền `var(--primary-light)`. Không phối nhiều màu accent (success/purple/teal…) cho các icon cùng một nhóm lưới. Nền các khối lớn mang tính trang trí (hero, card CTA, widget nổi bật) ưu tiên gradient/primary (`--grad-hero`, `--grad-primary`), chỉ dùng accent khác (success/warning/danger) khi có ý nghĩa semantic thật (trạng thái duyệt, cảnh báo, lỗi).
- **"Kiểm tra quy hoạch" (zoning check)** là một widget/luồng lặp lại ở cả trang chủ và trang chi tiết: mở overlay + sheet (`.qhs-overlay`/`.qhs-sheet`, full sheet trên desktop, bottom-sheet kéo lên trên mobile ≤640px), có trạng thái loading (spinner 1.6–1.8s giả lập), rồi hiển thị kết quả dạng banner theo 1 trong 4 trạng thái: `clean` (xanh lá — chưa ghi nhận chồng lấn), `warning` (vàng — cần lưu ý), `danger` (đỏ — rủi ro cao), `notfound` (chưa có dữ liệu — form nhờ chuyên viên tra). Bản đồ so sánh vệ tinh/quy hoạch bằng thanh kéo (drag slider, chia đôi bằng `clip-path`). Đây là **mock hoàn toàn phía client** — cần API tra cứu quy hoạch thật ở backend.
- **Responsive**: breakpoint chính 1080–1180px (ẩn nav ngang, sidebar/lưới rút cột) và 640px (mobile: sheet thay popover, lưới 1 cột, thanh hành động dính đáy).
- **Không có props/data-props** ở trang chủ và trang danh sách (không có Tweaks) — 100% nội dung là mock data khai báo cứng trong class logic. Trang chi tiết và Dashboard có props (xem từng mục bên dưới).

---

## Màn hình 1 — Trang chủ (`Trang chủ Đà Lạt BĐS.dc.html`)
**Mục đích**: Landing page marketing công khai — tìm kiếm BĐS, giới thiệu quy trình xác minh, tra quy hoạch miễn phí, xem giá thị trường, chuyển đổi người dùng thành người mua/bán/môi giới.

**Bố cục** (từ trên xuống, mỗi block là 1 `<section class="sec">`, padding dọc 76px / 52px mobile):
1. **Hero** (`.hero`, nền `var(--grad-hero)` + ảnh slideshow `images/dalat-city-hero.jpg` phủ scrim gradient xanh, đồ hoạ SVG "mạng lưới kết nối" animate góc phải): eyebrow + `h1` (52px/1.08, tối đa 760px) + sub + **widget Search** (card trắng bo góc 18px, shadow lớn): tab Mua bán/Cho thuê/Dự án, 2 chế độ — "Classic" (3 field khu vực/loại/giá + nút Tìm) và "AI/NLP" (input tự nhiên ngôn ngữ + mic giọng nói, badge "AI"). Dưới cùng: ward quick-chips, trust bar.
2. **BĐS nổi bật** (`#listings`): carousel cuộn ngang scroll-snap, 7 property card (300px/thẻ), nút prev/next tròn, dot indicator ẩn (CSS `display:none` mặc định, chỉ hiện hint mobile).
3. **Quy hoạch check** (`#quyhoach`, nền `--grad-hero` tối, hoạ tiết contour mờ): panel trắng nổi với 3 tab input (Tờ/Thửa — 3 field số tờ/số thửa/phường; Toạ độ/Link — input dán link Google Maps; Định vị — nút "Tôi đang đứng tại đất" giả lập GPS 1.4s), ticker cuộn ngang các lượt tra gần đây, mở sheet kết quả như mô tả ở trên.
4. **Nhịp đập thị trường** (`#market`, nền trắng): eyebrow + slider ngân sách (1–15 tỷ, có 4 chip nhanh 2/3/5/8 tỷ) tính "mua được bao nhiêu m²" theo từng phường, lưới 6 ô giá/phường (mũi tên xu hướng tăng/giảm/đi ngang), CTA "định giá đất của bạn".
5. **Quy trình xác minh** (`#verify`): 4 bước dạng rail ngang có đường nối, icon tròn `--primary-light` + số thứ tự nổi góc.
6. **Vì sao làm môi giới ở đây** (nền tối `broker-dark`, gradient xanh navy): 2 cột — copy + 4 value-tile (lưới 2×2 responsive) bên trái/trên; **card tính hoa hồng** nổi bên phải (slider giá trị giao dịch 0.5–20 tỷ, animate số bằng easing, chia 4 vai trò 25%/25%/25%/25%).
7. **Mạng lưới thổ địa** (`#coverage`): bản đồ bong bóng SVG 4 vòng tròn đồng tâm (14 phường/xã, vị trí tính bằng lượng giác trong `buildWardGeometry()`), animate stagger-in khi cuộn tới (IntersectionObserver) + legend + danh sách phường cuộn dọc bên phải.
8. **FAQ** (accordion 1-mở-tại-1-thời-điểm, animate bằng `grid-template-rows`).
9. **Băng ký gửi** (slim CTA 1 dòng).
10. **Bạn đến vì điều gì** (`#role-cta`): 3 card (Mua/Bán/Môi giới — card giữa nổi bật nền primary) + thanh bot Telegram.
11. **Footer** (5 cột + newsletter card gradient).

**Props**: không có (không tweakable qua Tweaks panel).

**Assets dùng**: `logo-color.svg` (header + footer), `images/dalat-city-hero.jpg` (hero), ảnh Unsplash (property card — placeholder), tất cả copy tiếng Việt là bản dựng cuối, giữ nguyên văn phong khi chuyển ngữ cảnh code.

**Lưu ý riêng**: địa chỉ email `contact@dalatbds.com` trong footer có comment TODO ngay trong code — "xác nhận hộp thư domain đã thiết lập, hiện dùng placeholder"; hỏi lại phía sản phẩm trước khi lên production.

---

## Màn hình 2 — Trang danh sách BĐS (`Trang danh sách BĐS.dc.html`)
**Mục đích**: Trang kết quả tìm kiếm/duyệt tin mua-bán hoặc cho-thuê tại Đà Lạt, với 3 chế độ xem và bộ lọc đầy đủ + bản đồ tương tác.

**Bố cục**:
- Thanh tìm kiếm dính đỉnh dưới header (`.searchbar`, top:66px): segment Mua bán/Cho thuê, ô tìm kiếm có autocomplete (panel gợi ý nhóm theo Đường/Phường/Dự án + "tìm gần đây" + "tìm quanh đây"), pill chọn phạm vi (Toàn Đà Lạt/Trung tâm/Ven đô/Ngoại thành), nút Tìm.
- Thanh filter dính (`.filterbarRow`, top:138px): chip filter đang bật (xoá từng cái hoặc "Xoá tất cả") + trigger "Bộ lọc" mở panel/bottom-sheet: Khu vực (checkbox theo phường, có đếm số tin), Loại BĐS, Mức giá (từ–đến), Số phòng ngủ (chip 1–5+), Pháp lý.
- Trust strip 1 dòng ("Mọi tin đều xác minh…").
- Thanh công cụ kết quả: tiêu đề + đếm số tin + sort dropdown (6 lựa chọn: nổi bật/mới nhất/giá tăng-giảm/giá·m² thấp nhất/diện tích lớn nhất) + segment 3 chế độ xem (**Danh sách** / **Bản đồ** / **Chia đôi** — "Chia đôi" chỉ hiện ở desktop ≥1180px) + nút chuông "Lưu tìm kiếm" (mở panel chọn kênh nhận Telegram/Zalo).
- **Chế độ Danh sách**: sidebar filter cố định bên trái (khi ở desktop thuần list) + lưới 3 cột property row card.
- **Chế độ Chia đôi** (mặc định desktop): 55% danh sách cuộn / 45% bản đồ. Bản đồ vẽ bằng CSS/SVG thuần (không phải map engine thật) — hồ, đường, landmark, layer quy hoạch (3 màu: đất ở/TM-DV/nông nghiệp, toggle bật/tắt), pin từng tin (cluster khi zoom out, click cluster để zoom vào 1 cụm), công cụ **vẽ vùng tìm kiếm** (click nhiều điểm → polygon → lọc tin nằm trong vùng bằng point-in-polygon), panel danh sách trượt ra từ trái đè lên bản đồ (có thể thu gọn bằng tab kéo).
- **Chế độ Bản đồ**: bản đồ full-bleed, nút back mobile, bottom sheet vuốt ngang xem từng tin khi tap pin (mobile).
- Empty state khi lọc ra 0 kết quả (icon + CTA "trở thành eBroker" / "nhận thông báo").
- Footer (4 cột, không có newsletter card).

**Dữ liệu mock**: mảng `RAW` 28 bản ghi BĐS (id, tiêu đề, phường, loại, giao dịch, giá, diện tích, pháp lý, số phòng, cờ mới, quy hoạch, tên đường, số ngày đăng, toạ độ x/y ảo cho bản đồ mock) + danh sách môi giới mock cho avatar. Toàn bộ filter/sort/search/vẽ-vùng chạy 100% phía client trên mảng này — **cần thay bằng API thật** (search, filter, pagination, geocoding thật).

**Props**: không có.

**Lưu ý kỹ thuật quan trọng cho dev**: bản đồ trong màn này là minh hoạ (toạ độ % vị trí đặt tay trong data mock, không phải toạ độ địa lý thật) — khi làm thật cần tích hợp map engine thật (Google Maps/Mapbox/Leaflet…) và giữ đúng các lớp tương tác đã thiết kế (toggle quy hoạch, vẽ vùng, cluster, đồng bộ hover list↔pin).

---

## Màn hình 3 — Trang chi tiết BĐS (`Trang chi tiết BĐS.dc.html`)
**Mục đích**: Trang chi tiết 1 tin BĐS — màn phức tạp nhất, **role-aware theo 3 vai trò**: `guest` (khách xem công khai), `sale` (môi giới mạng lưới), `admin` (quản trị). Cùng 1 URL nhưng nội dung/quyền xem thay đổi theo role.

**Bố cục chung** (áp dụng mọi role):
- Breadcrumb, title row (badge trạng thái mở bán/thương lượng/đã cọc/đã bán + mã tin + badge "Đã xác minh", `h1`, địa chỉ, 3 nút hành động: Lưu tin/Chia sẻ/Báo tin sai).
- Gallery 2×2 (1 ảnh lớn + 2 nhỏ, ô thứ 3 có overlay "Xem tất cả") + thumbnail strip cuộn ngang → mở lightbox toàn màn hình.
- Cột chính: card giá + lưới 8 thông số (diện tích/phòng ngủ/phòng tắm/hướng/mặt tiền/số tầng/pháp lý/nội thất) → card Vị trí (bản đồ mock CSS + POI, **hiển thị theo role**: guest/sale chỉ thấy vùng bán kính ước tính + banner "địa chỉ được bảo vệ"; admin thấy pin chính xác + toạ độ GPS thật) + CTA "Kiểm tra quy hoạch" (mở sheet, luôn trả kết quả "clean" sau 1.6s — mock) → card Mô tả chi tiết + lưới tiện ích (8 icon).
- **Panel riêng cho role `sale`** (`isSale`): pipeline 5 bước chăm khách (click để chuyển bước), 3 "claim card" vai trò trong deal (có khách/có nguồn chủ/chốt giao dịch — bấm để nhận/nhả vai trò), donut chart hoa hồng chia 4 (khách/nguồn/chốt/nền tảng, mỗi phần 25%) + legend, nhật ký chăm sóc (timeline + form thêm ghi chú), nút nhanh (đặt lịch xem/nhận thêm khách).
- **Panel riêng cho role `admin`** (`isAdmin`): danh sách pipeline của mọi sale trên tin này, bảng phân bổ hoa hồng 4 bên (dropdown gán người + tổng tự tính), khối thông tin chủ nhà + toạ độ chính xác (kèm banner cảnh báo "thông tin nhạy cảm, mọi lần mở đều ghi log"), lưới 6 nút hành động tin (duyệt/từ chối/sửa/mở thương lượng/đánh dấu đã cọc/khoá-gỡ tin), audit log.
- Related listings (3 card).
- Sidebar phải (sticky): card môi giới phụ trách + khối liên hệ **theo role** (guest: CTA "đăng ký xem nhà"; sale: liên hệ chủ bị khoá, cần claim vai trò mới mở; admin: xem đầy đủ SĐT chủ nhà), thống kê lượt xem/ngày đăng.
- **Widget "Xem trang với vai trò"** (`.rswitch`, nổi góc phải trên) — **chỉ để demo/preview trong quá trình thiết kế, không dựng vào production**: role thật phải lấy từ session đăng nhập, không phải nút bấm chuyển tay trên trang.
- Thanh CTA dính đáy — nút thay đổi theo role.

**Props (`data-props`)** — đây là màn duy nhất có Tweaks đầy đủ:
- `initialRole`: enum `guest | sale | admin`, mặc định `guest` — role hiển thị khi load trang.
- `zoneRadius`: range 300–500 (bước 50, đơn vị m) — bán kính vùng mờ hiển thị trên bản đồ cho role không phải admin.
- `commissionRate`: range 1–3 (bước 0.5, đơn vị %) — % hoa hồng dùng để tính 4 phần chia trong donut chart.

**Lưu ý cho dev**: đây là màn "giống backend thật" nhất trong bộ — role, trạng thái tin, bước pipeline, phân bổ hoa hồng, claim vai trò, ghi chú chăm sóc hiện đều là state cục bộ giả lập. Khi tích hợp thật cần: hệ thống phân quyền theo role ở backend (không chỉ ẩn/hiện ở frontend), API cập nhật pipeline/claim/note, và kiểm soát chặt việc lộ SĐT/địa chỉ chủ nhà (đúng tinh thần "chống cắt cầu" đã mô tả trong thiết kế).

---

## Màn hình 4 — Dashboard desktop (`Dashboard desktop.dc.html`)
**Mục đích**: Không gian làm việc desktop hằng ngày của 1 Sale/eBroker sau khi đăng nhập — tổng quan KPI, độ phủ nguồn theo địa bàn, hàng chờ xác minh, lịch hôm nay, khách cần chăm, thông báo.

**Bố cục**:
- Sidebar cố định 240px (logo + 7 mục nav: Tổng quan/Tin của tôi/Chờ xác minh/Khách/Lịch xem/Địa bàn/Hồ sơ) — thu vào drawer trượt + scrim mờ dưới 1100px, có nút hamburger.
- Top bar 76px sticky: lời chào theo giờ trong ngày ("Chào buổi sáng/trưa/chiều/tối, {tên}") + dòng địa bàn phụ trách, ô tìm kiếm (ẩn dưới 860px), chuông thông báo (badge đếm số), avatar.
- Lưới 4 KPI card (icon, nhãn, giá trị lớn + sparkline SVG tuỳ chọn, delta caption).
- 2 cột nội dung:
  - **Cột chính**: card "Nguồn theo địa bàn" (từng phường: đã có nguồn + số tin xác minh, hoặc badge "chưa có nguồn" + CTA thêm nguồn) · card "Đang chờ xác minh" (từng tin: ảnh + thanh tiến độ 3 bước Sổ/Quy hoạch/Hiện trạng).
  - **Cột phụ**: card "Việc hôm nay" (lịch xem hôm nay theo giờ + danh sách khách cần chăm kèm badge giai đoạn) · card "Thông báo gần đây" (feed, icon xanh cho thông báo tích cực).
- Mỗi card đều có **empty state** riêng (icon + câu gợi ý + CTA) khi không có dữ liệu.

**Props (`data-props`)**:
- `dataMode`: enum `"Có dữ liệu" | "Trống (mới bắt đầu)"`, mặc định `"Có dữ liệu"` — chuyển toàn bộ 4 card giữa trạng thái có dữ liệu mock và trạng thái rỗng (broker mới, chưa có gì).
- `activeNavStyle`: enum `"Nền màu" | "Thanh màu"`, mặc định `"Thanh màu"` — kiểu highlight mục nav đang active trong sidebar: nền màu phủ cả hàng, hoặc thanh dọc 3px bên trái.
- `showSparkline`: boolean, mặc định `true` — ẩn/hiện sparkline SVG trong KPI card đầu tiên có `showSpark`.

**Lưu ý cho dev**: toàn bộ số liệu (KPI, phường, tin chờ duyệt, lịch, khách, thông báo) là mảng mock khai báo cứng trong class — cần thay bằng API thật theo broker đăng nhập.

---

## Design tokens
Nguồn tham chiếu đầy đủ: `_ds/l-t-b-s-design-system-ab8fedcf-19df-47f5-9322-bcaf9725c5e9/tokens/*.css` (copy kèm trong bộ này).

**Màu** (`colors.css`):
- Brand: `--primary #3270FC` (blue-500), `--primary-hover #2159E0`, `--primary-light #E8EFFE`, `--secondary/blue-800 #163A8F`.
- Ink/neutral: `--ink-900 #0F1C32` (heading) → `--ink-50 #F7F8FA` (nền app), `--border #E5E7EB`, `--border-strong #CBD2DD`.
- Semantic: `--success #059669` / `--warning #D97706` / `--danger #EF4444` / `--purple #7C3AED` / `--teal #0D9488` / `--pink #DB2777` — mỗi màu có cặp `-light` để làm nền chip/tint.
- Gradient: `--grad-primary`, `--grad-hero` (dùng cho hero + khối tối + card CTA), `--grad-pine`, `--grad-dusk`.

**Typography** (`typography.css`): 1 font duy nhất **Jost** (400/500/600/700, file `.ttf` copy kèm ở `assets/fonts/`), scale 9→34px, line-height 1.15–1.65, letter-spacing -0.01em (tight, dùng cho heading) → 0.08em (wider, dùng cho eyebrow/uppercase label).

**Spacing/Radius/Shadow** (`spacing.css`): thang spacing 2–40px (base 4px), radius 6px (input) → 999px (pill/chip/avatar), shadow tông lạnh `rgba(15,28,50,…)` ("sương mù thung lũng") — `--shadow-card`, `--shadow-lg`, `--shadow-sheet`, `--shadow-primary` (glow xanh cho nút primary).

## Assets
- `logo-color.svg` — logo chính dùng trong header/footer/sidebar của cả 4 màn (nền sáng).
- `images/dalat-city-hero.jpg` — ảnh nền hero trang chủ.
- Font Jost 4 weight (`.ttf`) — do client cung cấp, không phải font thay thế.
- Ảnh property card, avatar môi giới: **toàn bộ là Unsplash/pravatar.cc placeholder** — cần thay bằng ảnh BĐS và avatar thật trước khi lên production.

## Files trong bộ handoff này
- `Trang chủ Đà Lạt BĐS.dc.html` — Màn hình 1
- `Trang danh sách BĐS.dc.html` — Màn hình 2
- `Trang chi tiết BĐS.dc.html` — Màn hình 3
- `Dashboard desktop.dc.html` — Màn hình 4
- `support.js` — runtime để 4 file trên chạy được khi mở trực tiếp bằng trình duyệt (không phải code cần đưa vào codebase đích)
- `_ds/.../` — token CSS, bundle component, logo, font — giữ nguyên cấu trúc thư mục để các file `.dc.html` render đúng khi mở thử

*Không nằm trong bộ này (đã lược bỏ vì không cần cho handoff)*: 2 file `-print-*.dc.html` (bản xuất PDF/in, chỉ là snapshot của Màn 1 và Màn 2) và `Trang danh sách BĐS (v1 - trước nâng cấp bản đồ).dc.html` (bản cũ trước khi nâng cấp bản đồ, đã được thay thế bởi Màn hình 2 hiện tại).
