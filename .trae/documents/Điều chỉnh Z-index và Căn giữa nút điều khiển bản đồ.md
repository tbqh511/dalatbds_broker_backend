Tôi sẽ thực hiện các thay đổi sau để giải quyết 2 vấn đề bạn gặp phải:

1.  **Xử lý lỗi Z-index:**
    *   **Nguyên nhân:** Có thể do Tailwind JIT chưa biên dịch kịp class `z-[1000]` hoặc cấu hình project chưa hỗ trợ cú pháp arbitrary value này, dẫn đến class không sinh ra CSS.
    *   **Giải pháp:** Tôi sẽ loại bỏ class `z-[1000]` và sử dụng thuộc tính `style="z-index: 1000;"` trực tiếp trên thẻ HTML. Đây là cách chắc chắn nhất để đảm bảo thứ tự xếp chồng (stacking order) mà không phụ thuộc vào việc biên dịch CSS.

2.  **Căn giữa Button (Right Center):**
    *   **Giải pháp:**
        *   Xóa class `bottom-6` (đang căn dưới).
        *   Thêm class `top-1/2` (đẩy xuống 50% chiều cao cha).
        *   Thêm class `-translate-y-1/2` (dịch ngược lại 50% chiều cao bản thân) để căn giữa chính xác theo chiều dọc.
    *   Giữ nguyên `absolute`, `right-4`, `flex`, `flex-col`, `gap-3`.

**Code thay đổi dự kiến:**

```html
<!-- Cũ -->
<div class="absolute bottom-6 right-4 z-[1000] flex flex-col gap-3">

<!-- Mới -->
<div class="absolute top-1/2 right-4 flex flex-col gap-3 -translate-y-1/2" style="z-index: 1000;">
```

Giải pháp này đảm bảo nút luôn nằm giữa mép phải màn hình trên mọi thiết bị và hiển thị nổi bật trên bản đồ.