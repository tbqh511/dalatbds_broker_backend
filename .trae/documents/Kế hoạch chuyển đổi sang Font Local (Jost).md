Bạn nói đúng, hình ảnh danh sách font bạn gửi xác nhận rằng bạn đang có đầy đủ các file font cần thiết (Bold, Medium, SemiBold...) ở máy tính cá nhân, **NHƯNG** trên server (thư mục code `public/fonts/`) thì lại **đang thiếu** các file này (chỉ có mỗi file Regular).

Đó là lý do tại sao khi dùng "local", web không hiển thị được chữ đậm (Bold) hoặc hiển thị bị lỗi.

Dưới đây là **Bảng Kế Hoạch Chuyển Sang Dùng Font Local** (theo đúng yêu cầu của bạn để ổn định nhất và kiểm soát được file nguồn):

### Bước 1: Upload file Font
Bạn cần copy các file font từ máy tính của bạn vào thư mục `public/fonts/` của dự án.
Các file cần thiết (ưu tiên file `.ttf` hoặc `.woff2` nếu có):
1.  `Jost-Regular.ttf` (hoặc .otf)
2.  `Jost-Medium.ttf`
3.  `Jost-SemiBold.ttf`
4.  `Jost-Bold.ttf`

### Bước 2: Cấu hình lại CSS (Tôi sẽ làm bước này)
Sau khi bạn upload file xong, tôi sẽ viết lại code trong `style.css` để khai báo từng loại font này. Code sẽ trông như sau:

```css
/* Khai báo Font Local đầy đủ */

/* 1. Regular (400) */
@font-face {
    font-family: 'Jost';
    src: url('../fonts/Jost-Regular.ttf') format('truetype');
    font-weight: 400;
    font-style: normal;
}

/* 2. Medium (500) */
@font-face {
    font-family: 'Jost';
    src: url('../fonts/Jost-Medium.ttf') format('truetype');
    font-weight: 500;
    font-style: normal;
}

/* 3. SemiBold (600) */
@font-face {
    font-family: 'Jost';
    src: url('../fonts/Jost-SemiBold.ttf') format('truetype');
    font-weight: 600;
    font-style: normal;
}

/* 4. Bold (700) */
@font-face {
    font-family: 'Jost';
    src: url('../fonts/Jost-Bold.ttf') format('truetype');
    font-weight: 700;
    font-style: normal;
}
```

### Tại sao phương án này tốt hơn?
*   **Chủ động:** Bạn kiểm soát được chính xác file font hiển thị (file bạn đã Việt hóa).
*   **Ổn định:** Không phụ thuộc vào đường truyền tới máy chủ Google.
*   **Khắc phục triệt để:** Không còn tình trạng "fake bold" (tự làm đậm) gây lỗi hiển thị vì trình duyệt đã có file font đậm "xịn" để dùng.

**Bạn hãy xác nhận để tôi tiến hành cấu hình CSS theo hướng này nhé!** (Lưu ý: Bạn cần tự upload file vào thư mục `public/fonts` vì tôi không có quyền truy cập file trên máy tính cá nhân của bạn).
