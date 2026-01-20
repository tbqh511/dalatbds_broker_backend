# Fix UX Issue in Modal Map Picker

## Task 1: Nâng Z-Index cho Top Bar Container ✅
- Tìm div chứa nút Back và Searchbox (class `absolute top-0...`)
- Thay đổi class `z-50` thành `z-[10000]`

## Task 2: Cấu hình TomSelect để Render Dropdown ra ngoài (Body) ✅
- Trong đoạn script `x-init` của `#select-street`, bổ sung tùy chọn `dropdownParent: document.body` vào cấu hình `new TomSelect(...)`
- Thêm `onDropdownOpen: function() { this.dropdown.style.zIndex = "99999"; }`

## Task 3: Xử lý sự kiện click Nút Back ✅
- Đảm bảo nút Back có class `pointer-events-auto` và `cursor-pointer`
- Thêm `relative` và `z-[10001]` trực tiếp vào thẻ `<button>`
