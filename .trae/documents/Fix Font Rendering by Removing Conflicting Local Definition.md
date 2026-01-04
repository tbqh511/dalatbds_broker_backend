Dựa trên hình ảnh và kiểm tra code, tôi xác định nguyên nhân chính gây lỗi font là do **xung đột giữa font Google và cấu hình font nội bộ (Local)**.

1. **Vấn đề:** Trong file `public/css/style.css`, ngay sau dòng `import` font Google, vẫn còn một đoạn code `@font-face` trỏ về file `Jost-Regular.woff2` nằm trong thư mục dự án.

   * Trình duyệt sẽ ưu tiên dùng cấu hình này (`@font-face`) thay vì font Google.

   * File `Jost-Regular.woff2` này chỉ có **một độ đậm duy nhất (Regular)**, nên khi web cần hiển thị chữ đậm (Bold), trình duyệt phải tự "tô đậm" font thường (fake bold), gây ra lỗi hiển thị xấu và lỗi dấu tiếng Việt như bạn thấy.

   * Thư mục `public/fonts/` của dự án hiện tại **thiếu toàn bộ** các file font khác (Bold, Medium, Light...) mà bạn thấy trong hình chụp bên thiết kế gửi.

2. **Giải pháp:**

   * **Ưu tiên:** Sử dụng Font Google (Online) vì nó đầy đủ và tiện lợi nhất. Tôi sẽ **xóa hoặc vô hiệu hóa** đoạn code `@font-face` trỏ về file nội bộ trong `public/css/style.css`. Khi đó, web sẽ tự động nhận font xịn từ Google với đầy đủ độ đậm nhạt và hỗ trợ tiếng Việt chuẩn.

   * **Về các file thiết kế gửi:** Bạn **không cần** phải upload hết đống file đó lên nếu dùng cách trên. Cách dùng Google Fonts là chuẩn và tối ưu hơn cho web.

**Kế hoạch thực hiện:**

1. Mở file `public/css/style.css`.
2. Tìm và **xóa/comment** đoạn code `@font-face` đang trỏ vào `../fonts/Jost-Regular.woff2`.
3. Kiểm tra tương tự với file `public/css/cs-style.css` (nếu có).

